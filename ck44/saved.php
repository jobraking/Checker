<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/guard.php';

require_once __DIR__ . '/ck-client.php';


function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function savedInitials(string $name): string
{
    $name = trim($name);
    if ($name === '') {
        return '?';
    }
    $clean = (string) preg_replace('/[^A-Za-z0-9]+/', '', $name);
    return strtoupper(substr($clean !== '' ? $clean : $name, 0, 2));
}

function savedLoad(string $file): array
{
    if (!is_readable($file)) {
        return [];
    }
    $decoded = json_decode((string) file_get_contents($file), true);
    return is_array($decoded) ? array_values(array_filter($decoded, 'is_array')) : [];
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'path'     => '/',
    ]);
    session_name(basename(__DIR__) . 'checker');
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = (string) $_SESSION['csrf'];

header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');

$file = __DIR__ . '/pwd.json';
$list = savedLoad($file);

$action = isset($_POST['action']) && is_string($_POST['action']) ? $_POST['action'] : '';
$wantedUser = isset($_POST['user']) && is_string($_POST['user']) ? $_POST['user'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedCsrf = isset($_POST['csrf']) && is_string($_POST['csrf']) ? $_POST['csrf'] : '';
    if ($postedCsrf === '' || !hash_equals($csrf, $postedCsrf)) {
        http_response_code(400);
        if ($action === 'check_balance' || $action === 'check_all_live') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token. Reload the page.']);
        } else {
            echo 'Invalid CSRF token. Reload the page.';
        }
        exit;
    }

    if ($action === 'check_balance') {
        header('Content-Type: application/json; charset=utf-8');
        set_time_limit(120);

        $entry = null;
        foreach ($list as $candidate) {
            if (($candidate['username'] ?? '') === $wantedUser) {
                $entry = $candidate;
                break;
            }
        }
        if ($entry === null) {
            echo json_encode(['status' => 'error', 'message' => 'Account not found']);
            exit;
        }

        $merchant = CK_DEFAULT_MERCHANT;
        $session = [
            'token'        => (string) ($entry['token'] ?? ''),
            'merchantCode' => $merchant,
            'userName'     => $wantedUser,
        ];

        $prof = ckProfile($session);
        if ($prof['status'] === 401 || (is_array($prof['body']) && ($prof['body']['success'] ?? null) === false)) {
            $login = ckLoginRetry($wantedUser, (string) ($entry['password'] ?? ''), $merchant);
            if (!$login['ok'] || $login['session'] === null) {
                echo json_encode(['status' => 'error', 'message' => (string) ($login['error'] ?? 'login failed')]);
                exit;
            }
            $session = $login['session'];
            $prof = ckProfile($session);
        }

        $value = is_array($prof['body']) ? ($prof['body']['value'] ?? $prof['body']['data'] ?? []) : [];
        $rows = ckProfileRows($value);

        $balRes = ckBalance($session);
        $fundsRes = ckAgentFunds($session);
        $refRes = ckReferralOverview($session);
        $promoRes = ckHttp('GET', '/wps/relay/MCSFE_getAvailablePromotions', ['headers' => ckAuthHeaders($session)]);
        $ticketsRes = ckHttp('GET', '/wps/relay/PROMOFE_getClaimTickets', ['headers' => ckAuthHeaders($session)]);
        $ticketListRes = ckHttp('GET', '/wps/relay/PROMOFE_getClaimTicketList', ['headers' => ckAuthHeaders($session)]);
        $rankSalaryRes = ckHttp('GET', '/wps/relay/PROMOFE_getRankSalaryRewardDetail', ['headers' => ckAuthHeaders($session)]);

        $balance = null;
        $rawBalance = $balRes['body']['value'] ?? null;
        if (is_array($rawBalance)) {
            if (isset($rawBalance['sumBalance'])) {
                $balance = $rawBalance['sumBalance'];
            } elseif (isset($rawBalance['mainWalletBalance'])) {
                $balance = $rawBalance['mainWalletBalance'];
            } elseif (isset($rawBalance['balance'][0]['balance'])) {
                $balance = $rawBalance['balance'][0]['balance'];
            }
        }

        $funds = [];
        if (is_array($fundsRes['body']['value'] ?? null)) {
            foreach ($fundsRes['body']['value'] as $key => $fundValue) {
                if ($fundValue !== null && $fundValue !== '' && $fundValue !== []) {
                    $funds[$key] = $fundValue;
                }
            }
        }

        $ref = $refRes['body']['value'] ?? null;

        echo json_encode([
            'status'              => 'ok',
            'user'                => $wantedUser,
            'profile'             => $rows,
            'profile_raw'         => $value,
            'balance'             => $balance,
            'funds'               => $funds,
            'balance_raw'         => $balRes['body'],
            'funds_raw'           => $fundsRes['body'],
            'referral_raw'        => $refRes['body'],
            'valid_referrals'     => $ref['memberQualifiedCount'] ?? $ref['memberCount'] ?? null,
            'available_promotions'=> $promoRes['body'] ?? null,
            'tickets'             => $ticketsRes['body']['value'] ?? null,
            'ticket_list'         => $ticketListRes['body']['value'] ?? null,
            'rank_salary'         => $rankSalaryRes['body']['value'] ?? null,
        ]);
        exit;
    }

    if ($action === 'check_all_live') {
        header('Content-Type: application/json; charset=utf-8');
        set_time_limit(0);

        $entry = null;
        foreach ($list as $candidate) {
            if (($candidate['username'] ?? '') === $wantedUser) {
                $entry = $candidate;
                break;
            }
        }
        if ($entry === null) {
            echo json_encode(['status' => 'error', 'message' => 'Account not found']);
            exit;
        }

        $merchant = CK_DEFAULT_MERCHANT;
        $session = [
            'token'        => (string) ($entry['token'] ?? ''),
            'merchantCode' => $merchant,
            'userName'     => $wantedUser,
        ];

        $prof = ckProfile($session);
        if ($prof['status'] === 401 || (is_array($prof['body']) && ($prof['body']['success'] ?? null) === false)) {
            $login = ckLoginRetry($wantedUser, (string) ($entry['password'] ?? ''), $merchant);
            if (!$login['ok'] || $login['session'] === null) {
                echo json_encode(['status' => 'error', 'message' => (string) ($login['error'] ?? 'login failed')]);
                exit;
            }
            $session = $login['session'];
        }

        $results = [];
        $endpointFile = __DIR__ . '/live_endpoints.txt';
        $endpoints = is_readable($endpointFile) ? file($endpointFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
        foreach ($endpoints as $endpoint) {
            $endpoint = trim($endpoint);
            if ($endpoint === '') {
                continue;
            }
            $res = ckHttp('GET', $endpoint, ['headers' => ckAuthHeaders($session)]);
            $results[$endpoint] = $res['body'] ?? ['status' => $res['status'], 'raw' => $res['raw']];
        }

        echo json_encode(['status' => 'ok', 'user' => $wantedUser, 'count' => count($results), 'results' => $results]);
        exit;
    }

    if ($action === 'delete') {
        $list = array_values(array_filter($list, static fn($entry) => ($entry['username'] ?? '') !== $wantedUser));
        file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        @chmod($file, 0600);
        header('Location: ./saved.php');
        exit;
    }
}

$accounts = [];
$levelSet = [];
foreach ($list as $entry) {
    $username = trim((string) ($entry['username'] ?? ''));
    if ($username === '') {
        continue;
    }

    $vip = isset($entry['vip']) ? (int) $entry['vip'] : 0;
    $detailsRaw = (string) ($entry['details'] ?? '');
    if ($vip === 0 && $detailsRaw !== '' && preg_match('/club:\s*VIP(\d+)/i', $detailsRaw, $match)) {
        $vip = (int) $match[1];
    }
    $vip = max(0, $vip);

    $changedRaw = (string) ($entry['changed_at'] ?? '');
    $changedTs = $changedRaw !== '' ? (strtotime($changedRaw) ?: 0) : 0;

    $detailParts = [];
    if ($detailsRaw !== '') {
        foreach (explode('|', $detailsRaw) as $part) {
            $part = trim($part);
            if ($part !== '') {
                $detailParts[] = $part;
            }
        }
    }

    $accounts[] = [
        'username'      => $username,
        'password'      => (string) ($entry['password'] ?? ''),
        'token'         => (string) ($entry['token'] ?? ''),
        'vip'           => $vip,
        'royal'         => $vip > 3,
        'details'       => $detailParts,
        'changed_ts'    => $changedTs,
        'changed_label' => $changedTs > 0 ? date('M j, Y · H:i', $changedTs) : $changedRaw,
    ];

    if ($vip > 0) {
        $levelSet[$vip] = true;
    }
}

usort($accounts, static function (array $a, array $b): int {
    if ($a['vip'] !== $b['vip']) {
        return $b['vip'] <=> $a['vip'];
    }
    return strcasecmp($a['username'], $b['username']);
});

$levels = array_keys($levelSet);
sort($levels);

$total = count($accounts);
$royalCount = count(array_filter($accounts, static fn($account) => $account['royal']));
$topVip = 0;
$lastTs = 0;
$hasUnranked = false;
foreach ($accounts as $account) {
    $topVip = max($topVip, $account['vip']);
    $lastTs = max($lastTs, $account['changed_ts']);
    if ($account['vip'] === 0) {
        $hasUnranked = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>D3M09 ACCOUNT CHECKER - SAVED ACCOUNTS</title>
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
  --danger-soft:#fff1f0;
  --gold:#a16207;
  --gold-soft:#fffaeb;
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
  line-height:1.55;
  -webkit-font-smoothing:antialiased;
}
a{color:var(--brand-dark)}
code{
  font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;
  font-size:.76em;background:#f1f5f9;border:1px solid #e2e8f0;
  padding:1px 5px;border-radius:5px;color:#334155;
}
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
.nav a.active{color:var(--brand-dark);background:#f2fdf9;border:1px solid #c8f0e3}
main{padding:0 0 56px}
.hero{
  display:flex;align-items:flex-end;justify-content:space-between;gap:26px;
  padding:clamp(30px,5vw,48px) 0 24px;flex-wrap:wrap;
}
.hero-copy{max-width:640px}
.eyebrow{
  display:inline-flex;align-items:center;gap:8px;padding:6px 11px;border-radius:999px;
  background:#fff;border:1px solid var(--line);color:var(--brand-dark);
  font-size:.7rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;
}
.dot{width:7px;height:7px;border-radius:50%;background:var(--brand);box-shadow:0 0 0 4px rgba(15,159,122,.12)}
.hero h1{margin:14px 0 10px;font-size:clamp(2.1rem,4.2vw,3.1rem);line-height:1.05;letter-spacing:-.05em}
.hero h1 span{color:var(--brand)}
.hero p{margin:0;max-width:56ch;color:var(--muted);font-size:.98rem;line-height:1.65}
.stats{display:grid;grid-template-columns:repeat(4,minmax(92px,1fr));gap:10px;width:100%;max-width:520px}
.stat{
  background:rgba(255,255,255,.94);border:1px solid var(--line);border-radius:14px;
  padding:12px 14px;box-shadow:0 12px 28px rgba(15,42,70,.05);min-width:0;
}
.stat span{display:block;color:var(--muted);font-size:.62rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase}
.stat strong{display:block;font-size:1.25rem;letter-spacing:-.03em;line-height:1.35;word-break:break-word}
.toolbar{
  display:flex;align-items:flex-end;gap:10px;flex-wrap:wrap;
  background:rgba(255,255,255,.94);border:1px solid var(--line);border-radius:16px;
  padding:14px;margin-bottom:20px;box-shadow:0 14px 34px rgba(15,42,70,.05);
}
.search{
  flex:1 1 260px;display:flex;align-items:center;gap:9px;padding:0 12px;
  border:1.5px solid var(--line);border-radius:11px;background:#fbfdfc;
  transition:border-color .18s ease,box-shadow .18s ease,background .18s ease;
}
.search:focus-within{border-color:var(--brand);box-shadow:0 0 0 4px rgba(15,159,122,.1);background:#fff}
.search svg{width:16px;height:16px;color:#94a3b8;flex:none}
.search input{width:100%;min-width:0;border:0;outline:0;background:transparent;padding:11px 0;font:inherit;font-size:.84rem;color:var(--ink)}
.search input::placeholder{color:#9aa8b6}
.control{display:flex;flex-direction:column;gap:5px}
.control label{font-size:.62rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}
.control select{
  appearance:none;border:1.5px solid var(--line);border-radius:10px;background:#fff;
  padding:9px 30px 9px 11px;font:inherit;font-size:.8rem;font-weight:650;color:var(--ink);cursor:pointer;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:right 9px center;background-size:14px;
}
.control select:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 4px rgba(15,159,122,.1)}
.switch{
  display:inline-flex;align-items:center;gap:8px;padding:9px 12px;border-radius:10px;
  border:1.5px solid var(--line);background:#fff;font-size:.8rem;font-weight:650;color:var(--ink);
  cursor:pointer;user-select:none;white-space:nowrap;
}
.switch input{position:absolute;opacity:0;pointer-events:none}
.switch .track{width:30px;height:17px;border-radius:999px;background:#dbe4ec;position:relative;flex:none;transition:background .18s ease}
.switch .track::after{content:"";position:absolute;top:2px;left:2px;width:13px;height:13px;border-radius:50%;background:#fff;box-shadow:0 1px 2px rgba(15,31,46,.2);transition:transform .18s ease}
.switch input:checked + .track{background:var(--brand)}
.switch input:checked + .track::after{transform:translateX(13px)}
.switch input:focus-visible + .track{box-shadow:0 0 0 4px rgba(15,159,122,.18)}
.btn{
  display:inline-flex;align-items:center;justify-content:center;gap:7px;
  padding:9px 14px;border-radius:10px;border:1px solid transparent;
  background:transparent;color:var(--ink);font:inherit;font-size:.8rem;font-weight:750;
  cursor:pointer;text-decoration:none;white-space:nowrap;
  transition:transform .18s ease,box-shadow .18s ease,background .18s ease,border-color .18s ease;
}
.btn svg{width:15px;height:15px;flex:none}
.btn-primary{background:var(--brand);color:#fff;box-shadow:0 8px 18px rgba(15,159,122,.2)}
.btn-primary:hover{background:var(--brand-dark);transform:translateY(-1px);box-shadow:0 11px 22px rgba(15,159,122,.26)}
.btn-ghost{background:#fff;border-color:var(--line);color:var(--ink)}
.btn-ghost:hover{border-color:#b9e8da;background:#f7fffc;transform:translateY(-1px)}
.btn-sm{padding:6px 9px;font-size:.72rem;border-radius:8px}
.btn:disabled{opacity:.6;cursor:progress;transform:none;box-shadow:none}
.toolbar-end{display:flex;gap:6px;align-items:center;margin-left:auto;flex-wrap:wrap}
.list-head{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-bottom:12px}
.list-head h2{margin:0;font-size:1.02rem;letter-spacing:-.02em}
.list-head p{margin:2px 0 0;color:var(--muted);font-size:.75rem}
.accounts{display:grid;grid-template-columns:repeat(auto-fill,minmax(330px,1fr));gap:16px}
.acct{
  position:relative;display:flex;flex-direction:column;gap:13px;min-width:0;
  background:rgba(255,255,255,.96);border:1px solid var(--line);border-radius:var(--radius);
  padding:18px;box-shadow:0 16px 40px rgba(15,42,70,.06);
  transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease;
}
.acct[hidden]{display:none}
.acct:hover{transform:translateY(-2px);box-shadow:0 22px 48px rgba(15,42,70,.1)}
.acct.royal{
  border-color:#f0d38b;
  background:linear-gradient(180deg,#fffdf4 0%,#fff 46%);
  box-shadow:0 16px 40px rgba(161,98,7,.12);
}
.acct.royal::before{
  content:"";position:absolute;inset:0 0 auto 0;height:3px;
  border-radius:var(--radius) var(--radius) 0 0;
  background:linear-gradient(90deg,#f6c453,#e8a317);
}
.acct-head{display:flex;align-items:center;gap:11px;min-width:0}
.avatar{
  width:42px;height:42px;border-radius:13px;flex:none;
  background:linear-gradient(135deg,#d9fbf0,#e7f0ff);color:var(--brand-dark);
  display:grid;place-items:center;font-size:.78rem;font-weight:800;letter-spacing:.02em;
}
.acct.royal .avatar{background:linear-gradient(135deg,#fff3cf,#ffe6a8);color:#8a5a00}
.acct-id{min-width:0;flex:1}
.acct-user{margin:0;font-size:.96rem;letter-spacing:-.02em;word-break:break-all;line-height:1.3}
.acct-sub{margin:2px 0 0;color:var(--muted);font-size:.68rem;word-break:break-word}
.badge{
  display:inline-flex;align-items:center;gap:3px;flex:none;
  padding:3px 8px;border-radius:999px;font-size:.64rem;font-weight:800;letter-spacing:.02em;white-space:nowrap;
}
.badge svg{width:11px;height:11px}
.badge.vip{background:var(--brand-soft);border:1px solid #c8f2e3;color:var(--brand-dark)}
.badge.royal{background:var(--gold-soft);border:1px solid #f3dfa2;color:var(--gold)}
.badge.muted{background:#f1f5f9;border:1px solid #e2e8f0;color:var(--muted)}
.meta{display:flex;flex-direction:column;gap:6px;padding:10px 12px;background:#f8fafc;border:1px solid #edf2f7;border-radius:12px}
.meta-row{display:flex;align-items:center;justify-content:space-between;gap:8px;min-height:24px;min-width:0}
.meta-label{display:inline-flex;align-items:center;gap:5px;flex:none;color:var(--muted);font-size:.66rem;font-weight:800;letter-spacing:.09em;text-transform:uppercase}
.meta-label svg{width:13px;height:13px}
.meta-actions{display:flex;align-items:center;gap:4px;min-width:0}
.secret{
  display:block;max-width:170px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
  font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:.74rem;
  background:#fff;border:1px solid var(--line);padding:2px 7px;border-radius:6px;color:#334155;
}
.secret.revealed{background:var(--brand-soft);border-color:#c8f2e3;color:var(--brand-dark)}
.icon-btn{
  width:27px;height:27px;padding:0;border-radius:8px;border:1px solid var(--line);
  background:#fff;color:var(--muted);display:grid;place-items:center;cursor:pointer;flex:none;
  transition:color .18s ease,border-color .18s ease,background .18s ease;
}
.icon-btn svg{width:13px;height:13px}
.icon-btn:hover{color:var(--brand-dark);border-color:#b9e8da;background:var(--brand-soft)}
.icon-btn.copied{color:var(--brand-dark);border-color:#b9e8da;background:var(--brand-soft)}
.icon-btn.danger:hover{color:var(--danger);border-color:#ffd7d4;background:var(--danger-soft)}
.chips{display:flex;flex-wrap:wrap;gap:5px;min-width:0}
.chip{
  max-width:100%;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
  padding:3px 8px;border-radius:999px;background:#f1f5f9;border:1px solid #e2e8f0;
  color:#475569;font-size:.64rem;
}
.chip b{font-weight:800;color:#334155;text-transform:capitalize}
.acct-actions{display:flex;align-items:center;flex-wrap:wrap;gap:6px;margin-top:auto}
.delete-form{margin:0;display:inline-flex}
.push{margin-left:auto}
.live-panel{
  border-top:1px solid var(--line);padding-top:12px;
  font-size:.75rem;animation:fadeIn .2s ease;
}
.live-panel[hidden]{display:none}
@keyframes fadeIn{from{opacity:0;transform:translateY(-3px)}to{opacity:1;transform:none}}
.loading{display:flex;align-items:center;gap:8px;color:var(--muted)}
.spinner{
  width:14px;height:14px;border-radius:50%;flex:none;
  border:2px solid #d7e3ee;border-top-color:var(--brand);
  animation:spin .7s linear infinite;
}
@keyframes spin{to{transform:rotate(360deg)}}
.live-head{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:9px}
.live-balance{font-size:1.14rem;font-weight:800;letter-spacing:-.03em;color:var(--ink);line-height:1.2}
.pill{display:inline-flex;align-items:center;padding:2px 8px;border-radius:999px;font-size:.62rem;font-weight:800;white-space:nowrap}
.pill.ok{background:#e6fbf3;color:#087a5b}
.live-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:6px}
.live-item{padding:6px 8px;background:#f8fafc;border:1px solid #edf2f7;border-radius:8px;min-width:0}
.live-label{display:block;color:var(--muted);font-size:.56rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
.live-value{display:block;font-size:.71rem;font-weight:650;word-break:break-word}
.live-error{padding:8px 10px;border-radius:9px;background:var(--danger-soft);border:1px solid #ffd7d4;color:var(--danger);word-break:break-word}
.live-note{margin-top:6px;color:var(--muted);font-size:.64rem}
details.raw{margin-top:9px}
details.raw summary{cursor:pointer;color:var(--brand-dark);font-weight:750;font-size:.66rem}
details.raw pre{
  margin:6px 0 0;padding:9px;max-height:190px;overflow:auto;
  background:#0f1f2e;color:#dbe7f3;border-radius:9px;
  font:0.66rem/1.5 ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;
  white-space:pre-wrap;word-break:break-word;
}
.empty-card{
  margin-top:6px;padding:44px 24px;border-radius:var(--radius);text-align:center;
  border:1.5px dashed #d5e4e0;background:rgba(255,255,255,.72);color:var(--muted);
}
.empty-card[hidden]{display:none}
.empty-icon{
  width:46px;height:46px;border-radius:50%;margin:0 auto 12px;
  background:var(--brand-soft);color:var(--brand-dark);display:grid;place-items:center;
}
.empty-icon svg{width:21px;height:21px}
.empty-card strong{display:block;color:var(--ink);font-size:1rem;margin-bottom:4px}
.empty-card p{margin:0 auto 16px;max-width:44ch;font-size:.82rem}
.site-footer{border-top:1px solid var(--line);background:rgba(255,255,255,.62);margin-top:36px}
.footer-inner{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:20px 0;color:var(--muted);font-size:.74rem}
.footer-inner code{font-size:.7rem}
@media(max-width:900px){
  .stats{max-width:none}
}
@media(max-width:700px){
  .header-inner{padding:10px 0;gap:10px}
  .nav{width:100%;overflow-x:auto;padding-bottom:2px}
  .toolbar-end{margin-left:0;width:100%}
  .toolbar-end .btn{flex:1}
}
@media(max-width:640px){
  .shell{width:calc(100% - 28px)}
  .brand-text strong{font-size:.92rem}
  .brand-text small{font-size:.62rem}
  .nav a{padding:7px 9px;font-size:.78rem}
  .hero{padding-top:24px}
  .hero h1{font-size:clamp(1.95rem,9vw,2.6rem)}
  .stats{grid-template-columns:repeat(2,minmax(0,1fr))}
  .accounts{grid-template-columns:1fr}
  .acct{padding:15px;border-radius:15px}
  .live-grid{grid-template-columns:1fr}
  .secret{max-width:130px}
}
@media(prefers-reduced-motion:reduce){
  *{scroll-behavior:auto!important;transition:none!important;animation:none!important}
}
</style>
</head>
<body>
<header class="site-header">
  <div class="shell header-inner">
    <a class="brand" href="./">
      <span class="brand-mark" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
      </span>
      <span class="brand-text">
        <strong><?= h(strtoupper(basename(__DIR__))) ?></strong>
        <small>Account vault</small>
      </span>
    </a>
    <nav class="nav" aria-label="Primary">
      <a href="./">Checker</a>
      <a class="active" href="saved.php" aria-current="page">Saved</a>
      <a href="/logout.php">Sign out</a>
    </nav>
  </div>
</header>

<main>
  <div class="shell">
    <section class="hero">
      <div class="hero-copy">
        <span class="eyebrow"><span class="dot" aria-hidden="true"></span>Saved sessions</span>
        <h1>Accounts, organized<span>.</span></h1>
        <p>Every saved login with its club tier and live balance. Credentials stay on this machine in <code>pwd.json</code>.</p>
      </div>
      <div class="stats" aria-label="Saved account summary">
        <div class="stat"><span>Accounts</span><strong><?= $total ?></strong></div>
        <div class="stat"><span>CLUB</span><strong><?= $royalCount ?></strong></div>
        <div class="stat"><span>Top VIP</span><strong><?= $topVip > 0 ? 'VIP ' . $topVip : '—' ?></strong></div>
        <div class="stat"><span>Updated</span><strong><?= $lastTs > 0 ? h(date('M j, Y', $lastTs)) : '—' ?></strong></div>
      </div>
    </section>

    <?php if ($total > 0): ?>
    <div class="toolbar" role="search">
      <label class="search" for="q">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input id="q" type="search" placeholder="Search username, nickname, detail…" autocomplete="off">
      </label>
      <div class="control">
        <label for="levelFilter">Level</label>
        <select id="levelFilter">
          <option value="all">All levels</option>
          <option value="royal">CLUB only</option>
          <?php foreach ($levels as $level): ?>
            <option value="<?= (int) $level ?>">VIP <?= (int) $level ?></option>
          <?php endforeach; ?>
          <?php if ($hasUnranked): ?>
            <option value="0">Unranked</option>
          <?php endif; ?>
        </select>
      </div>
      <div class="control">
        <label for="sortBy">Sort</label>
        <select id="sortBy">
          <option value="vip-desc">Highest VIP</option>
          <option value="vip-asc">Lowest VIP</option>
          <option value="name">Username A–Z</option>
          <option value="recent">Recently updated</option>
        </select>
      </div>
      <label class="switch">
        <input type="checkbox" id="royalOnly">
        <span class="track" aria-hidden="true"></span>
        <span>CLUB only</span>
      </label>
      <div class="toolbar-end">
        <button type="button" class="btn btn-ghost btn-sm" id="revealAll" aria-pressed="false">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
          <span>Show passwords</span>
        </button>
        <button type="button" class="btn btn-primary btn-sm" id="checkVisible">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5L20 7"/></svg>
          <span>Check visible</span>
        </button>
      </div>
    </div>

    <div class="list-head">
      <div>
        <h2>Saved accounts</h2>
        <p id="resultCount" aria-live="polite"><?= $total ?> account<?= $total === 1 ? '' : 's' ?></p>
      </div>
    </div>

    <div class="accounts" id="accountsGrid">
      <?php foreach ($accounts as $account): ?>
      <article class="acct<?= $account['royal'] ? ' royal' : '' ?>"
        data-vip="<?= (int) $account['vip'] ?>"
        data-royal="<?= $account['royal'] ? '1' : '0' ?>"
        data-user="<?= h($account['username']) ?>"
        data-changed="<?= (int) $account['changed_ts'] ?>"
        data-search="<?= h(strtolower($account['username'] . ' ' . implode(' ', $account['details']))) ?>">
        <div class="acct-head">
          <span class="avatar" aria-hidden="true"><?= h(savedInitials($account['username'])) ?></span>
          <div class="acct-id">
            <h3 class="acct-user"><?= h($account['username']) ?></h3>
            <p class="acct-sub"><?= $account['changed_label'] !== '' ? 'Updated ' . h($account['changed_label']) : 'Saved account' ?></p>
          </div>
          <?php if ($account['royal']): ?>
            <span class="badge royal">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m3 7 4.5 4L12 4l4.5 7L21 7l-2 11H5L3 7Z"/></svg>
              CLUB
            </span>
          <?php elseif ($account['vip'] > 0): ?>
            <span class="badge vip">VIP <?= (int) $account['vip'] ?></span>
          <?php else: ?>
            <span class="badge muted">Unranked</span>
          <?php endif; ?>
        </div>

        <div class="meta">
          <div class="meta-row">
            <span class="meta-label">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="7.5" cy="15.5" r="4.5"/><path d="m11 12 9-9M16 7l3 3"/></svg>
              Password
            </span>
            <span class="meta-actions">
              <code class="secret js-pass" data-full="<?= h($account['password']) ?>" title="Password hidden">••••••••</code>
              <button type="button" class="icon-btn js-reveal" title="Show password" aria-label="Show password" aria-pressed="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
              <button type="button" class="icon-btn js-copy-pass" title="Copy password" aria-label="Copy password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
              </button>
            </span>
          </div>
          <div class="meta-row">
            <span class="meta-label">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 2l-2 2m-7.6 7.6a5.5 5.5 0 1 1-7.8 7.8 5.5 5.5 0 0 1 7.8-7.8Zm0 0L15.5 7.5m0 0 3 3L22 7l-3-3"/></svg>
              Token
            </span>
            <code class="secret" title="Masked session token"><?= h(ckMaskToken($account['token'])) ?></code>
          </div>
        </div>

        <?php if ($account['details'] !== []): ?>
        <div class="chips">
          <?php foreach ($account['details'] as $detail): ?>
            <?php $colon = strpos($detail, ':'); ?>
            <span class="chip">
              <?php if ($colon !== false): ?>
                <b><?= h(trim(substr($detail, 0, $colon))) ?></b> <?= h(trim(substr($detail, $colon + 1))) ?>
              <?php else: ?>
                <?= h($detail) ?>
              <?php endif; ?>
            </span>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="acct-actions">
          <button type="button" class="btn btn-primary btn-sm js-check" data-user="<?= h($account['username']) ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"/></svg>
            <span>Check balance</span>
          </button>
          <a class="btn btn-ghost btn-sm" href="details.php?username=<?= h(rawurlencode($account['username'])) ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6M10 14 21 3"/></svg>
            <span>Live endpoints</span>
          </a>
          <button type="button" class="btn btn-ghost btn-sm js-copy" data-copy="<?= h($account['username'] . ' | ' . $account['password']) ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg>
            <span>Copy</span>
          </button>
          <form method="post" class="delete-form push" onsubmit="return confirm('Delete this saved account? This cannot be undone.');">
            <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="user" value="<?= h($account['username']) ?>">
            <button type="submit" class="icon-btn danger" title="Delete account" aria-label="Delete <?= h($account['username']) ?>">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            </button>
          </form>
        </div>

        <div class="live-panel" hidden aria-live="polite"></div>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="empty-card" id="noMatches" hidden>
      <span class="empty-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
      </span>
      <strong>No matching accounts</strong>
      <p>Try a different search, level, or turn off the CLUB filter.</p>
      <button type="button" class="btn btn-ghost btn-sm" id="resetFilters">Reset filters</button>
    </div>

    <?php else: ?>
    <div class="empty-card">
      <span class="empty-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
      </span>
      <strong>No saved accounts yet</strong>
      <p>Check an account, then save it to see balances, club tiers, and live endpoint data here.</p>
      <a class="btn btn-primary" href="./">Check an account</a>
    </div>
    <?php endif; ?>
  </div>
</main>

<footer class="site-footer">
  <div class="shell footer-inner">
    <span><?= h(strtoupper(basename(__DIR__))) ?> checker · saved locally</span>
    <span>Stored in <code>pwd.json</code></span>
  </div>
</footer>

<script>
(function () {
  'use strict';

  var CSRF = <?= json_encode($csrf) ?>;
  var grid = document.getElementById('accountsGrid');
  if (!grid) {
    return;
  }

  var cards = Array.prototype.slice.call(grid.querySelectorAll('.acct'));
  var search = document.getElementById('q');
  var levelFilter = document.getElementById('levelFilter');
  var sortBy = document.getElementById('sortBy');
  var royalOnly = document.getElementById('royalOnly');
  var revealAll = document.getElementById('revealAll');
  var checkVisible = document.getElementById('checkVisible');
  var resultCount = document.getElementById('resultCount');
  var noMatches = document.getElementById('noMatches');
  var resetFilters = document.getElementById('resetFilters');
  var secretsVisible = false;

  function setButtonLabel(button, text) {
    if (!button) {
      return;
    }
    var span = button.querySelector('span');
    if (span) {
      span.textContent = text;
    } else {
      button.textContent = text;
    }
  }

  function setSecret(code, visible) {
    if (!code) {
      return;
    }
    code.textContent = visible ? (code.getAttribute('data-full') || '') : '••••••••';
    code.classList.toggle('revealed', visible);
    code.title = visible ? 'Password visible' : 'Password hidden';
  }

  function applyFilters() {
    var query = (search.value || '').trim().toLowerCase();
    var level = levelFilter.value;
    var showRoyal = royalOnly.checked;
    var shown = 0;

    cards.forEach(function (card) {
      var vip = card.getAttribute('data-vip') || '0';
      var royal = card.getAttribute('data-royal') === '1';
      var haystack = (card.getAttribute('data-search') || '').toLowerCase();
      var visible = true;

      if (query && haystack.indexOf(query) === -1) {
        visible = false;
      }
      if (visible && showRoyal && !royal) {
        visible = false;
      }
      if (visible && level === 'royal' && !royal) {
        visible = false;
      }
      if (visible && level !== 'all' && level !== 'royal' && vip !== level) {
        visible = false;
      }

      card.hidden = !visible;
      if (visible) {
        shown++;
      }
    });

    resultCount.textContent = shown + ' of ' + cards.length + (cards.length === 1 ? ' account' : ' accounts');
    if (noMatches) {
      noMatches.hidden = !(shown === 0 && cards.length > 0);
    }
  }

  var sorters = {
    'vip-desc': function (a, b) {
      return (Number(b.getAttribute('data-vip')) - Number(a.getAttribute('data-vip'))) ||
        a.getAttribute('data-user').localeCompare(b.getAttribute('data-user'));
    },
    'vip-asc': function (a, b) {
      return (Number(a.getAttribute('data-vip')) - Number(b.getAttribute('data-vip'))) ||
        a.getAttribute('data-user').localeCompare(b.getAttribute('data-user'));
    },
    'name': function (a, b) {
      return a.getAttribute('data-user').localeCompare(b.getAttribute('data-user'));
    },
    'recent': function (a, b) {
      return (Number(b.getAttribute('data-changed')) - Number(a.getAttribute('data-changed'))) ||
        a.getAttribute('data-user').localeCompare(b.getAttribute('data-user'));
    }
  };

  function applySort() {
    var sorter = sorters[sortBy.value] || sorters['vip-desc'];
    var fragment = document.createDocumentFragment();
    cards.slice().sort(sorter).forEach(function (card) {
      fragment.appendChild(card);
    });
    grid.appendChild(fragment);
    applyFilters();
  }

  function copyText(text, button) {
    function done() {
      flashCopied(button);
    }
    function fallback() {
      var area = document.createElement('textarea');
      area.value = text;
      area.setAttribute('readonly', '');
      area.style.position = 'fixed';
      area.style.top = '-1000px';
      area.style.opacity = '0';
      document.body.appendChild(area);
      area.select();
      try {
        document.execCommand('copy');
        done();
      } catch (error) {
        window.alert('Copy failed. Select the text manually.');
      }
      area.remove();
    }
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(done, fallback);
    } else {
      fallback();
    }
  }

  function flashCopied(button) {
    if (!button) {
      return;
    }
    var span = button.querySelector('span');
    var oldTitle = button.getAttribute('title') || '';
    button.classList.add('copied');
    button.setAttribute('title', 'Copied');
    if (span) {
      var oldText = span.textContent;
      span.textContent = 'Copied';
      window.setTimeout(function () {
        span.textContent = oldText;
      }, 1200);
    }
    window.setTimeout(function () {
      button.classList.remove('copied');
      button.setAttribute('title', oldTitle);
    }, 1200);
  }

  function formatValue(value) {
    if (value === null || value === undefined || value === '') {
      return '—';
    }
    if (typeof value === 'object') {
      try {
        return JSON.stringify(value);
      } catch (error) {
        return String(value);
      }
    }
    return String(value);
  }

  function makeEl(tag, className, text) {
    var node = document.createElement(tag);
    if (className) {
      node.className = className;
    }
    if (text !== undefined && text !== null) {
      node.textContent = String(text);
    }
    return node;
  }

  function showLoading(panel, message) {
    panel.hidden = false;
    panel.replaceChildren();
    var wrap = makeEl('div', 'loading');
    wrap.appendChild(makeEl('span', 'spinner'));
    wrap.appendChild(makeEl('span', '', message));
    panel.appendChild(wrap);
  }

  function showError(panel, message) {
    panel.hidden = false;
    panel.replaceChildren(makeEl('div', 'live-error', message || 'Check failed'));
  }

  function renderLive(panel, data) {
    var raw = data.profile_raw && typeof data.profile_raw === 'object' ? data.profile_raw : {};
    var club = raw.clubLabel && typeof raw.clubLabel === 'object' ? raw.clubLabel : {};
    var clubName = club.labelName ? String(club.labelName) : '';
    var vip = Number(club.rankLevel);
    if (!Number.isFinite(vip)) {
      var match = clubName.match(/VIP\s*(\d+)/i);
      vip = match ? parseInt(match[1], 10) : 0;
    }
    var royal = vip > 3;

    var labels = Array.isArray(raw.memberLabel)
      ? raw.memberLabel.map(function (item) { return item && item.labelName; }).filter(Boolean).join(', ')
      : '';

    var ticketInfo = null;
    if (data.tickets && typeof data.tickets === 'object') {
      var claims = Array.isArray(data.tickets.temTicketClaims) ? data.tickets.temTicketClaims.length : 0;
      ticketInfo = claims + ' claim' + (claims === 1 ? '' : 's') +
        (data.tickets.processingTemuTicketClaimId ? ' · processing ' + data.tickets.processingTemuTicketClaimId : '');
    }

    var claimInfo = null;
    if (Array.isArray(data.ticket_list)) {
      var names = data.ticket_list.map(function (item) { return item && item.name; }).filter(Boolean);
      claimInfo = data.ticket_list.length + ' available' + (names.length ? ' · ' + names.join(', ') : '');
    }

    var rankSalary = data.rank_salary && typeof data.rank_salary === 'object'
      ? (data.rank_salary.promoName || null)
      : null;

    var rows = [
      ['Account', raw.account || data.user],
      ['Nickname', raw.nickname],
      ['Labels', labels],
      ['Club', clubName],
      ['Rank', vip || null],
      ['Total bet down', club.totalBetDown],
      ['Register source', raw.registerSource],
      ['Mobile', raw.mobile],
      ['Email', raw.email],
      ['Valid referrals', data.valid_referrals],
      ['Tickets', ticketInfo],
      ['Claim tickets', claimInfo],
      ['Rank salary', rankSalary]
    ];

    if (data.funds && typeof data.funds === 'object') {
      var fundParts = Object.keys(data.funds).map(function (key) {
        return key + ': ' + formatValue(data.funds[key]);
      });
      if (fundParts.length) {
        rows.push(['Funds', fundParts.join(' · ')]);
      }
    }

    panel.hidden = false;
    panel.replaceChildren();

    var head = makeEl('div', 'live-head');
    head.appendChild(makeEl('span', 'pill ok', 'Live'));
    head.appendChild(makeEl(
      'strong',
      'live-balance',
      (data.balance === null || data.balance === undefined || data.balance === '')
        ? 'n/a'
        : formatValue(data.balance) + ' BDT'
    ));
    if (royal) {
      head.appendChild(makeEl('span', 'badge royal', 'CLUB · VIP ' + vip));
    } else if (vip > 0) {
      head.appendChild(makeEl('span', 'badge vip', 'VIP ' + vip));
    }
    panel.appendChild(head);

    var gridEl = makeEl('div', 'live-grid');
    rows.forEach(function (row) {
      var value = formatValue(row[1]);
      if (value === '—') {
        return;
      }
      var item = makeEl('div', 'live-item');
      item.appendChild(makeEl('span', 'live-label', row[0]));
      item.appendChild(makeEl('span', 'live-value', value));
      gridEl.appendChild(item);
    });
    if (gridEl.childNodes.length) {
      panel.appendChild(gridEl);
    }

    var details = makeEl('details', 'raw');
    details.appendChild(makeEl('summary', '', 'Raw response'));
    details.appendChild(makeEl('pre', '', JSON.stringify(data, null, 2)));
    panel.appendChild(details);
  }

  async function checkBalance(card, button) {
    var user = card.getAttribute('data-user');
    var panel = card.querySelector('.live-panel');
    if (!user || !panel) {
      return;
    }

    if (button) {
      button.disabled = true;
    }
    showLoading(panel, 'Fetching live account data…');

    var body = new FormData();
    body.append('csrf', CSRF);
    body.append('action', 'check_balance');
    body.append('user', user);

    try {
      var response = await fetch(window.location.href, {
        method: 'POST',
        body: body,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      var text = await response.text();
      var data;
      try {
        data = JSON.parse(text);
      } catch (error) {
        throw new Error('Unexpected server response');
      }
      if (!data || data.status !== 'ok') {
        throw new Error((data && data.message) || 'Check failed');
      }
      renderLive(panel, data);
    } catch (error) {
      showError(panel, error && error.message ? error.message : 'Check failed');
    } finally {
      if (button) {
        button.disabled = false;
      }
    }
  }

  grid.addEventListener('click', function (event) {
    var checkBtn = event.target.closest('.js-check');
    if (checkBtn) {
      var card = checkBtn.closest('.acct');
      if (card) {
        checkBalance(card, checkBtn);
      }
      return;
    }

    var revealBtn = event.target.closest('.js-reveal');
    if (revealBtn) {
      var metaRow = revealBtn.closest('.meta-row');
      var code = metaRow ? metaRow.querySelector('.js-pass') : null;
      if (code) {
        var visible = !code.classList.contains('revealed');
        setSecret(code, visible);
        revealBtn.setAttribute('aria-pressed', String(visible));
        revealBtn.title = visible ? 'Hide password' : 'Show password';
        revealBtn.setAttribute('aria-label', revealBtn.title);
      }
      return;
    }

    var copyPassBtn = event.target.closest('.js-copy-pass');
    if (copyPassBtn) {
      var passRow = copyPassBtn.closest('.meta-row');
      var passCode = passRow ? passRow.querySelector('.js-pass') : null;
      if (passCode) {
        copyText(passCode.getAttribute('data-full') || '', copyPassBtn);
      }
      return;
    }

    var copyBtn = event.target.closest('.js-copy');
    if (copyBtn) {
      copyText(copyBtn.getAttribute('data-copy') || '', copyBtn);
    }
  });

  revealAll.addEventListener('click', function () {
    secretsVisible = !secretsVisible;
    grid.querySelectorAll('.js-pass').forEach(function (code) {
      setSecret(code, secretsVisible);
    });
    grid.querySelectorAll('.js-reveal').forEach(function (button) {
      button.setAttribute('aria-pressed', String(secretsVisible));
      button.title = secretsVisible ? 'Hide password' : 'Show password';
      button.setAttribute('aria-label', button.title);
    });
    setButtonLabel(revealAll, secretsVisible ? 'Hide passwords' : 'Show passwords');
    revealAll.setAttribute('aria-pressed', String(secretsVisible));
  });

  checkVisible.addEventListener('click', async function () {
    if (checkVisible.disabled) {
      return;
    }
    var visibleCards = cards.filter(function (card) {
      return !card.hidden;
    });
    if (!visibleCards.length) {
      return;
    }

    checkVisible.disabled = true;
    for (var index = 0; index < visibleCards.length; index++) {
      setButtonLabel(checkVisible, 'Checking ' + (index + 1) + ' / ' + visibleCards.length + '…');
      await checkBalance(visibleCards[index], visibleCards[index].querySelector('.js-check'));
      if (index < visibleCards.length - 1) {
        await new Promise(function (resolve) {
          window.setTimeout(resolve, 250);
        });
      }
    }
    setButtonLabel(checkVisible, 'Check visible');
    checkVisible.disabled = false;
  });

  search.addEventListener('input', applyFilters);
  levelFilter.addEventListener('change', applyFilters);
  royalOnly.addEventListener('change', applyFilters);
  sortBy.addEventListener('change', applySort);
  if (resetFilters) {
    resetFilters.addEventListener('click', function () {
      search.value = '';
      levelFilter.value = 'all';
      royalOnly.checked = false;
      sortBy.value = 'vip-desc';
      applySort();
      search.focus();
    });
  }
})();
</script>
</body>
</html>
