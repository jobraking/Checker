<?php
declare(strict_types=1);

require_once __DIR__ . '/ck-client.php';

/**
 * Bulk account checker for accounts you are authorised to sign in to.
 *
 * Reads a TXT file of "username|password" lines and signs in to each one
 * through the site's normal login flow, reporting status. Passwords are
 * never printed or written to any log.
 *
 * Usage:
 *   php bulk-check.php --file creds.txt
 *   php bulk-check.php --file creds.txt --delay 500 --retries 2
 */

$options = getopt('', ['file:', 'delay:', 'retries:', 'audit:', 'delimiter:', 'help']);
if (isset($options['help'])) {
    fwrite(STDOUT, "Usage: php bulk-check.php --file <path> [--delay ms] [--retries n] [--audit path] [--delimiter str]\n");
    exit(0);
}

$file = $options['file'] ?? null;
if (!is_string($file) || $file === '' || !is_readable($file)) {
    fwrite(STDERR, "error: --file must point to a readable TXT file\n");
    exit(1);
}

$delay   = (int) ($options['delay'] ?? 300);
$retries = (int) ($options['retries'] ?? 2);
$audit   = $options['audit'] ?? './bulk-check-audit.jsonl';
$delim   = $options['delimiter'] ?? '|';

$records = [];
foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $parsed = ckParseBulkLine($line);
    if ($parsed === null) {
        continue;
    }
    $records[] = ['username' => $parsed[0], 'password' => $parsed[1]];
}

if ($records === []) {
    fwrite(STDERR, "error: no valid records found in {$file}\n");
    exit(1);
}

fwrite(STDOUT, "Loaded " . count($records) . " record(s). Delay: {$delay}ms  Retries: {$retries}\n\n");

$auditFp = fopen($audit, 'a');
if ($auditFp === false) {
    fwrite(STDERR, "error: cannot open audit log {$audit}\n");
    exit(1);
}

$results = ['valid' => 0, 'invalid' => 0, 'error' => 0];
$merchant = CK_DEFAULT_MERCHANT;

foreach ($records as $i => $rec) {
    $username = $rec['username'];
    $password = $rec['password'];

    $outcome = 'error';
    $attempts = 0;
    $status = null;
    $errMsg = null;
    $token = null;

    for ($attempt = 1; $attempt <= $retries + 1; $attempt++) {
        $attempts = $attempt;
        $r = ckLoginRetry($username, $password, $merchant);
        if ($r['ok'] && $r['session'] !== null) {
            $outcome = 'valid';
            $token = $r['session']['token'];
            $errMsg = null;
            break;
        }
        $status = null;
        $errMsg = $r['error'];
        // Network/transport failures are retried; everything else is an auth failure.
        if (is_string($r['error']) && str_starts_with($r['error'], 'login request failed:')) {
            $outcome = 'error';
            if ($attempt <= $retries) {
                usleep(300000);
                continue;
            }
            break;
        }
        $outcome = 'invalid';
        break;
    }

    $results[$outcome]++;

    fwrite(STDOUT, sprintf("[%d/%d] %s -> %s (attempts=%d)%s\n",
        $i + 1, count($records), $username, strtoupper($outcome), $attempts,
        $errMsg !== null ? "  ({$errMsg})" : ($token !== null ? "  token=" . substr($token, 0, 6) . "…" : '')
    ));

    $entry = [
        'timestamp'  => gmdate('c'),
        'username'   => $username,
        'outcome'    => $outcome,
        'attempts'   => $attempts,
        'token'      => $token !== null ? substr($token, 0, 6) . '…' . substr($token, -4) : null,
    ];
    fwrite($auditFp, json_encode($entry, JSON_UNESCAPED_SLASHES) . "\n");
    fflush($auditFp);

    if ($i < count($records) - 1 && $delay > 0) {
        usleep($delay * 1000);
    }
}

fclose($auditFp);

fwrite(STDOUT, "\nSummary: valid={$results['valid']} invalid={$results['invalid']} error={$results['error']} (total=" . count($records) . ")\n");
fwrite(STDOUT, "Audit log: {$audit}\n");

exit($results['error'] > 0 ? 2 : 0);
