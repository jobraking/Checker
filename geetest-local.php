<?php
declare(strict_types=1);

const GTL_BASE = 'https://gcaptcha4.geevisit.com';
const GTL_PUBKEY = '00C1E3934D1614465B33053E7F48EE4EC87B14B95EF88947713D25EECBFF7E74C7977D02DC1D9451F79DD5D1C10C29ACB6A9B4D6FB7D0A0279B6719E1772565F09AF627715919221AEF91899CAE08C0D686D748B20A3603BE2318CA6BC2B59706592A9219D0BF05C9F65023A21D2330807252AE0066D59CEEFA5F2748EA80BAB81';
const GTL_UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36';

function gtl_http(string $url, string $referer): string
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_ENCODING       => '',
        CURLOPT_HTTPHEADER     => [
            'accept: */*',
            'accept-language: en-US,en;q=0.9',
            'sec-ch-ua-platform: "Windows"',
            'sec-ch-ua-mobile: ?0',
            'sec-fetch-site: same-origin',
            'sec-fetch-mode: no-cors',
            'sec-fetch-dest: script',
            'user-agent: ' . GTL_UA,
            'referer: ' . $referer,
        ],
    ]);
    $raw = curl_exec($ch);
    if ($raw === false) {
        throw new RuntimeException('curl: ' . curl_error($ch));
    }
    return (string) $raw;
}

function gtl_callback(): string
{
    return 'geetest_' . (string) ((int) (microtime(true) * 1000) + random_int(0, 9999));
}

function gtl_parse(string $raw, string $cb): array
{
    $needle = $cb . '(';
    $pos = strpos($raw, $needle);
    if ($pos === false) {
        throw new RuntimeException('unexpected response: ' . substr($raw, 0, 160));
    }
    $json = rtrim(trim(substr($raw, $pos + strlen($needle))), ');');
    $data = json_decode($json, true);
    if (!is_array($data)) {
        throw new RuntimeException('invalid response: ' . substr($json, 0, 160));
    }
    return $data;
}

function gtl_uuid(): string
{
    $d = random_bytes(16);
    $d[6] = chr((ord($d[6]) & 0x0f) | 0x40);
    $d[8] = chr((ord($d[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($d), 4));
}

function gtl_rand_uid(): string
{
    $out = '';
    for ($i = 0; $i < 4; $i++) {
        $out .= substr(dechex(random_int(0x10000, 0x1ffff)), -4);
    }
    return $out;
}

function gtl_der_len(int $len): string
{
    if ($len < 128) {
        return chr($len);
    }
    $bytes = ltrim(pack('N', $len), "\0");
    return chr(0x80 | strlen($bytes)) . $bytes;
}

function gtl_rsa(string $msg): string
{
    $n = hex2bin(strlen(GTL_PUBKEY) % 2 === 0 ? GTL_PUBKEY : '0' . GTL_PUBKEY);
    $e = hex2bin('010001');
    $intN = "\x02" . gtl_der_len(strlen($n)) . $n;
    $intE = "\x02" . gtl_der_len(strlen($e)) . $e;
    $seq = "\x30" . gtl_der_len(strlen($intN . $intE)) . $intN . $intE;
    $pem = "-----BEGIN RSA PUBLIC KEY-----\n" . chunk_split(base64_encode($seq), 64, "\n") . "-----END RSA PUBLIC KEY-----\n";
    $pub = openssl_pkey_get_public($pem);
    if ($pub === false) {
        throw new RuntimeException('rsa key rejected');
    }
    if (!openssl_public_encrypt($msg, $out, $pub, OPENSSL_PKCS1_PADDING)) {
        throw new RuntimeException('rsa encrypt failed');
    }
    return bin2hex($out);
}

function gtl_lot_fields(string $lot): array
{
    $key = substr($lot, 16, 3) . substr($lot, 13, 3) . '.' . substr($lot, 12, 7);
    return [$key => substr($lot, 22, 5)];
}

function gtl_sign(array $data, string $captchaId): string
{
    $uid = gtl_rand_uid();
    $powMsg = '1|0|md5|' . $data['pow_detail']['datetime'] . '|' . $captchaId . '|' . $data['lot_number'] . '||' . $uid;
    $base = [
        'oQ9h' => 'VQzx',
        'pow_msg' => $powMsg,
        'pow_sign' => md5($powMsg),
    ] + gtl_lot_fields($data['lot_number']) + [
        'biht' => '1426265548',
        'device_id' => '',
        'em' => ['cp' => 0, 'ek' => '11', 'nt' => 0, 'ph' => 0, 'sc' => 0, 'si' => 0, 'wd' => 1],
        'gee_guard' => ['roe' => ['auh' => '3', 'aup' => '3', 'cdc' => '3', 'egp' => '3', 'res' => '3', 'rew' => '3', 'sep' => '3', 'snh' => '3']],
        'ep' => '123',
        'geetest' => 'captcha',
        'lang' => 'zh',
        'lot_number' => $data['lot_number'],
    ];
    $json = json_encode($base, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $ct = openssl_encrypt($json, 'aes-128-cbc', $uid, OPENSSL_RAW_DATA, '0000000000000000');
    if ($ct === false) {
        throw new RuntimeException('aes failed');
    }
    return bin2hex($ct) . gtl_rsa($uid);
}

function gtl_solve(string $captchaId, string $pageUrl, ?string &$error = null): ?array
{
    $error = null;
    $referer = $pageUrl !== '' ? $pageUrl : 'https://www.299betvip.com/';
    try {
        $cb = gtl_callback();
        $loadUrl = GTL_BASE . '/load?' . http_build_query([
            'captcha_id' => $captchaId,
            'challenge' => gtl_uuid(),
            'client_type' => 'web',
            'risk_type' => 'ai',
            'lang' => 'eng',
            'callback' => $cb,
        ]);
        $res = gtl_parse(gtl_http($loadUrl, $referer), $cb);
        if (($res['status'] ?? '') !== 'success' || !is_array($res['data'] ?? null)) {
            $error = 'load: ' . (string) ($res['msg'] ?? $res['status'] ?? 'failed');
            return null;
        }
        $data = $res['data'];
        $type = (string) ($data['captcha_type'] ?? '');
        if ($type !== 'ai') {
            $error = 'local solver cannot handle type ' . ($type !== '' ? $type : 'unknown');
            return null;
        }
        $w = gtl_sign($data, $captchaId);
        $cb = gtl_callback();
        $verifyUrl = GTL_BASE . '/verify?' . http_build_query([
            'callback' => $cb,
            'captcha_id' => $captchaId,
            'client_type' => 'web',
            'lot_number' => $data['lot_number'],
            'risk_type' => 'ai',
            'payload' => $data['payload'],
            'process_token' => $data['process_token'],
            'payload_protocol' => '1',
            'pt' => '1',
            'w' => $w,
        ]);
        $res = gtl_parse(gtl_http($verifyUrl, $referer), $cb);
        $sec = $res['data']['seccode'] ?? null;
        if (!is_array($sec) || empty($sec['lot_number']) || empty($sec['pass_token'])) {
            $error = 'verify: ' . (string) ($res['msg'] ?? $res['data']['result'] ?? 'rejected');
            return null;
        }
        return [
            'lot_number'     => (string) $sec['lot_number'],
            'captcha_output' => (string) ($sec['captcha_output'] ?? ''),
            'pass_token'     => (string) $sec['pass_token'],
            'gen_time'       => (string) ($sec['gen_time'] ?? ''),
        ];
    } catch (Throwable $e) {
        $error = $e->getMessage();
        return null;
    }
}
