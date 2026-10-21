<?php
declare(strict_types=1);
$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$ng = '/mnt/sdcard/htdocs/ng88b';
$bet5 = '/mnt/sdcard/htdocs/bet5666';
if ($path === '/' || $path === '/index.php') { require $ng . '/index.php'; return true; }

if (preg_match('#^/ng88b(?:/(.*))?$#', $path, $m)) {
    require '/mnt/sdcard/htdocs/ng88b/index.php'; return true;
}

if (preg_match('#^/bet5666(?:/(.*))?$#', $path, $m)) {
    require '/mnt/sdcard/htdocs/bet5666/index.php'; return true;
}

http_response_code(404); echo "Not found\n"; return true;
