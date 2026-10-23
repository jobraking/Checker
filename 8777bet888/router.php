<?php
declare(strict_types=1);
$root = dirname(__DIR__);
$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$dir = $root . '/8777bet888';
if ($path === '/' || $path === '/index.php') { require $dir . '/index.php'; return true; }
if (preg_match('#^/8777bet888(?:/(.*))?$#', $path, $m)) {
    $sub = $m[1] ?? '';
    if ($sub === '' || $sub === 'index.php') { require $dir . '/index.php'; return true; }
    if (($sub === 'saved' || $sub === 'saved.php') && is_file($dir . '/saved.php')) { require $dir . '/saved.php'; return true; }
    if (($sub === 'details' || $sub === 'details.php') && is_file($dir . '/details.php')) { require $dir . '/details.php'; return true; }
    http_response_code(404); echo "Not found\n"; return true;
}
http_response_code(404); echo "Not found\n"; return true;
