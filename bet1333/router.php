<?php
declare(strict_types=1);
$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$dir = '/mnt/sdcard/htdocs/bet1333';
if ($path === '/' || $path === '/index.php') { require $dir . '/index.php'; return true; }
if (preg_match('#^/bet1333(?:/(.*))?$#', $path, $m)) {
    $sub = $m[1] ?? '';
    if ($sub === '' || $sub === 'index.php') { require $dir . '/index.php'; return true; }
    if ($sub === 'saved' || $sub === 'saved.php') { require $dir . '/saved.php'; return true; }
    http_response_code(404); echo "Not found\n"; return true;
}
http_response_code(404); echo "Not found\n"; return true;
