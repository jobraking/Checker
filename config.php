<?php
declare(strict_types=1);

$localConfig = [];
$localFile = __DIR__ . '/config.local.php';
if (is_file($localFile)) {
    $loaded = require $localFile;
    if (is_array($loaded)) {
        $localConfig = $loaded;
    }
}

$cfg = static function (string $key, string $default = '') use ($localConfig): string {
    $env = getenv($key);
    if ($env !== false && $env !== '') {
        return $env;
    }
    if (isset($localConfig[$key]) && $localConfig[$key] !== '') {
        return (string) $localConfig[$key];
    }
    return $default;
};

$dbName = $cfg('DB_NAME', 'relax');
$nameFile = __DIR__ . '/db_name.txt';
if (is_file($nameFile)) {
    $candidate = trim((string) file_get_contents($nameFile));
    if ($candidate !== '') {
        $dbName = $candidate;
    }
}

define('DB_DRIVER', strtolower($cfg('DB_DRIVER', 'mysql')));
define('DB_HOST', $cfg('DB_HOST', 'localhost'));
define('DB_PORT', (int) $cfg('DB_PORT', '3306'));
define('DB_NAME', $dbName);
define('DB_USER', $cfg('DB_USER'));
define('DB_PASS', $cfg('DB_PASS'));
define('DB_SQLITE', $cfg('DB_SQLITE', __DIR__ . '/data/checker.sqlite'));
define('APP_NAME', 'D3M09 Login');
define('SESSION_NAME', 'd3m09_session');
