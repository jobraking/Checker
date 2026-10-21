<?php
declare(strict_types=1);
$bdt = '/mnt/sdcard/htdocs/bdt399';
$bet5 = '/mnt/sdcard/htdocs/bet5666';
$ng = '/mnt/sdcard/htdocs/ng88b';
$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$xb = '/mnt/sdcard/htdocs/xbet';
if ($path === '/' || $path === '/index.php') { require $xb . '/index.php'; return true; }

if (preg_match('#^/xbet(?:/(.*))?$#', $path, $m)) {
    require '/mnt/sdcard/htdocs/xbet/index.php'; return true;
}

if (preg_match('#^/bdt399(?:/(.*))?$#', $path, $m)) {
    require '/mnt/sdcard/htdocs/bdt399/index.php'; return true;
}

if (preg_match('#^/ng88b(?:/(.*))?$#', $path, $m)) {
    require '/mnt/sdcard/htdocs/ng88b/index.php'; return true;
}

if (preg_match('#^/bet5666(?:/(.*))?$#', $path, $m)) {
    require '/mnt/sdcard/htdocs/bet5666/index.php'; return true;
}

http_response_code(404); echo "Not found\n"; return true;
