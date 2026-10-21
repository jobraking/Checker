<?php
declare(strict_types=1);

if (PHP_SAPI === 'cli') {
    return;
}

$publicPaths = ['/login', '/login.php', '/register', '/register.php', '/logout', '/logout.php', '/setup', '/setup.php'];
$requestPath = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
if (in_array($requestPath, $publicPaths, true)) {
    return;
}

$script = basename((string) ($_SERVER['SCRIPT_FILENAME'] ?? ''));
$publicScripts = ['login.php', 'register.php', 'logout.php', 'setup.php', 'guard.php', 'auth.php', 'db.php', 'config.php'];
if (in_array($script, $publicScripts, true)) {
    return;
}

require __DIR__ . '/auth.php';
require_login();
