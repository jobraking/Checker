<?php
declare(strict_types=1);

const SOLVER_FILE = __DIR__ . '/solver.json';
const SOLVER_ENDPOINT_CREATE = 'https://api.2captcha.com/createTask';
const SOLVER_ENDPOINT_RESULT = 'https://api.2captcha.com/getTaskResult';

function solver_config(): array
{
    $cfg = [
        'enabled'    => false,
        'provider'   => '2captcha',
        'accounts'   => [],
        'last_error' => '',
    ];
    if (!is_readable(SOLVER_FILE)) {
        return $cfg;
    }
    $data = json_decode((string) file_get_contents(SOLVER_FILE), true);
    if (!is_array($data)) {
        return $cfg;
    }
    $cfg['enabled'] = !empty($data['enabled']);
    $cfg['provider'] = isset($data['provider']) && is_string($data['provider']) ? $data['provider'] : '2captcha';
    $cfg['last_error'] = (string) ($data['last_error'] ?? '');
    if (isset($data['accounts']) && is_array($data['accounts'])) {
        foreach ($data['accounts'] as $row) {
            if (!is_array($row)) {
                continue;
            }
            if (($row['status'] ?? 'ok') === 'dead') {
                continue;
            }
            $cfg['accounts'][] = [
                'label'      => (string) ($row['label'] ?? ''),
                'key'        => (string) ($row['key'] ?? ''),
                'username'   => (string) ($row['username'] ?? ''),
                'password'   => (string) ($row['password'] ?? ''),
                'status'     => (string) ($row['status'] ?? 'ok'),
                'last_error' => (string) ($row['last_error'] ?? ''),
                'used_at'    => (string) ($row['used_at'] ?? ''),
            ];
        }
    }
    return $cfg;
}

function solver_save(array $cfg): bool
{
    $accounts = [];
    foreach ($cfg['accounts'] ?? [] as $row) {
        if (!is_array($row)) {
            continue;
        }
        $accounts[] = [
            'label'      => trim((string) ($row['label'] ?? '')),
            'key'        => trim((string) ($row['key'] ?? '')),
            'username'   => trim((string) ($row['username'] ?? '')),
            'password'   => (string) ($row['password'] ?? ''),
            'status'     => trim((string) ($row['status'] ?? 'ok')),
            'last_error' => trim((string) ($row['last_error'] ?? '')),
            'used_at'    => (string) ($row['used_at'] ?? ''),
        ];
    }
    $payload = [
        'enabled'    => !empty($cfg['enabled']),
        'provider'   => (string) ($cfg['provider'] ?? '2captcha'),
        'accounts'   => $accounts,
        'last_error' => (string) ($cfg['last_error'] ?? ''),
    ];
    $ok = file_put_contents(
        SOLVER_FILE,
        json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    ) !== false;
    @chmod(SOLVER_FILE, 0600);
    return $ok;
}

function solver_keys(?array $cfg = null): array
{
    $cfg = $cfg ?? solver_config();
    $keys = [];
    foreach ($cfg['accounts'] as $row) {
        if (($row['key'] ?? '') !== '' && ($row['status'] ?? 'ok') !== 'dead') {
            $keys[] = $row['key'];
        }
    }
    return array_values(array_unique($keys));
}


function solver_fatal_key_error(string $code): bool
{
    $code = strtoupper(trim($code));
    if ($code === '') {
        return false;
    }
    foreach ([
        'ERROR_KEY_DOES_NOT_EXIST',
        'ERROR_WRONG_USER_KEY',
        'ERROR_ZERO_BALANCE',
        'ERROR_BANNED',
        'ERROR_IP_NOT_ALLOWED',
        'ERROR_ACCESS_DENIED',
        'ERROR_ACCOUNT_SUSPENDED',
    ] as $fatal) {
        if (strpos($code, $fatal) !== false) {
            return true;
        }
    }
    return false;
}

function solver_mark_key(string $key, string $status, string $error = ''): void
{
    $cfg = solver_config();
    if ($status === 'ok') {
        $cfg['last_error'] = '';
    }
    $changed = false;
    foreach ($cfg['accounts'] as $i => $row) {
        if (($row['key'] ?? '') === $key) {
            $cfg['accounts'][$i]['status'] = $status;
            $cfg['accounts'][$i]['last_error'] = $error;
            $cfg['accounts'][$i]['used_at'] = gmdate('c');
            $changed = true;
        }
    }
    if ($changed) {
        solver_save($cfg);
    }
}

function solver_remove_key(string $key, string $error = ''): void
{
    if ($key === '') {
        return;
    }
    $cfg = solver_config();
    $kept = [];
    foreach ($cfg['accounts'] as $row) {
        if (($row['key'] ?? '') === $key) {
            continue;
        }
        $kept[] = $row;
    }
    if (count($kept) !== count($cfg['accounts'])) {
        $cfg['accounts'] = $kept;
        $cfg['last_error'] = 'Key ' . solver_mask($key) . ' removed: ' . ($error !== '' ? $error : 'rejected');
        solver_save($cfg);
    }
}

function solver_ensure_slot(): string
{
    $cfg = solver_config();
    $live = false;
    $pending = false;
    foreach ($cfg['accounts'] as $row) {
        if (($row['key'] ?? '') !== '' && ($row['status'] ?? 'ok') !== 'dead') {
            $live = true;
        }
        if (($row['key'] ?? '') === '') {
            $pending = true;
        }
    }
    if ($live || $pending) {
        return '';
    }
    $label = 'auto-' . gmdate('md-His');
    $cfg['accounts'][] = [
        'label'      => $label,
        'key'        => '',
        'username'   => '',
        'password'   => '',
        'status'     => 'pending',
        'last_error' => 'created after solver failure; paste a key',
        'used_at'    => gmdate('c'),
    ];
    solver_save($cfg);
    return $label;
}

function solver_http(string $url, array $payload, int $timeout = 30): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_SLASHES),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Accept: application/json'],
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT        => $timeout,
    ]);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($raw === false || $err !== '') {
        return ['status' => $status, 'body' => null, 'error' => $err !== '' ? $err : 'request failed'];
    }
    $body = json_decode((string) $raw, true);
    return [
        'status' => $status,
        'body'   => is_array($body) ? $body : null,
        'error'  => is_array($body) ? null : 'invalid json response',
    ];
}

/**
 * Solve a GeeTest v4 challenge with the configured 2Captcha keys.
 *
 * Keys are tried in order: a dead key, zero balance or a task error moves to
 * the next account. Returns the four validation fields the vendor expects.
 */
function solver_solve_geetest_v4(string $captchaId, string $pageUrl, ?string &$usedKey = null, ?string &$error = null): ?array
{
    $usedKey = null;
    $error = 'solver disabled';
    $cfg = solver_config();
    if (!$cfg['enabled']) {
        return null;
    }
    $keys = solver_keys($cfg);
    if ($keys === []) {
        $error = 'no usable solver key';
        solver_ensure_slot();
        return null;
    }

    foreach ($keys as $key) {
        $error = null;
        $created = solver_http(SOLVER_ENDPOINT_CREATE, [
            'clientKey' => $key,
            'task'      => [
                'type'           => 'GeeTestTaskProxyless',
                'websiteURL'     => $pageUrl,
                'version'        => 4,
                'initParameters' => ['captcha_id' => $captchaId],
            ],
        ]);
        if ($created['error'] !== null) {
            $error = 'create: ' . $created['error'];
            continue;
        }
        $body = $created['body'] ?? [];
        if (($body['errorId'] ?? 1) !== 0 || empty($body['taskId'])) {
            $error = (string) ($body['errorCode'] ?? 'create failed');
            if (solver_fatal_key_error($error)) {
                solver_remove_key($key, $error);
                solver_ensure_slot();
            }
            continue;
        }
        $taskId = (string) $body['taskId'];
        $deadline = microtime(true) + 120;
        while (microtime(true) < $deadline) {
            sleep(5);
            $poll = solver_http(SOLVER_ENDPOINT_RESULT, [
                'clientKey' => $key,
                'taskId'    => $taskId,
            ]);
            if ($poll['error'] !== null) {
                $error = 'poll: ' . $poll['error'];
                continue;
            }
            $pb = $poll['body'] ?? [];
            if (($pb['errorId'] ?? 0) !== 0) {
                $error = (string) ($pb['errorCode'] ?? 'solve failed');
                if (solver_fatal_key_error($error)) {
                    solver_remove_key($key, $error);
                    solver_ensure_slot();
                }
                break;
            }
            if (($pb['status'] ?? '') === 'ready' && isset($pb['solution']) && is_array($pb['solution'])) {
                $s = $pb['solution'];
                $solution = [
                    'lot_number'     => (string) ($s['lot_number'] ?? ''),
                    'captcha_output' => (string) ($s['captcha_output'] ?? ''),
                    'pass_token'     => (string) ($s['pass_token'] ?? ''),
                    'gen_time'       => (string) ($s['gen_time'] ?? ''),
                ];
                if ($solution['lot_number'] === '' || $solution['pass_token'] === '') {
                    $error = 'incomplete solution';
                    break;
                }
                $usedKey = $key;
                solver_mark_key($key, 'ok', '');
                return $solution;
            }
        }
        if ($error === null) {
            $error = 'timeout';
        }
    }

    solver_ensure_slot();
    return null;
}

function solver_mask(?string $value): string
{
    $value = (string) $value;
    if ($value === '') {
        return '';
    }
    if (strlen($value) <= 8) {
        return str_repeat('*', strlen($value));
    }
    return substr($value, 0, 4) . str_repeat('*', 6) . substr($value, -4);
}
