<?php
declare(strict_types=1);
$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$dir = __DIR__;
if ($path === '/' || $path === '/index.php') { require $dir . '/index.php'; return true; }
if (preg_match('#^/fb(?:/(.*))?$#', $path, $m)) {
    $sub = $m[1] ?? '';
    if ($sub === '' || $sub === 'index.php') { require $dir . '/index.php'; return true; }
    http_response_code(404); echo "Not found\n"; return true;
}
http_response_code(404); echo "Not found\n"; return true;
