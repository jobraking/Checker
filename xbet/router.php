<?php
declare(strict_types=1);
$root = dirname(__DIR__);
$bdt = $root . '/bdt399';
$bet5 = $root . '/bet5666';
$ng = $root . '/ng88b';
$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';
$xb = $root . '/xbet';
if ($path === '/' || $path === '/index.php') { require $xb . '/index.php'; return true; }

foreach (['xbet' => $xb, 'bdt399' => $bdt, 'ng88b' => $ng, 'bet5666' => $bet5] as $prefix => $dir) {
    if (preg_match('#^/' . $prefix . '(?:/(.*))?$#', $path, $m)) {
        $sub = $m[1] ?? '';
        if ($sub === '' || $sub === 'index.php') { require $dir . '/index.php'; return true; }
        if (($sub === 'saved' || $sub === 'saved.php') && is_file($dir . '/saved.php')) { require $dir . '/saved.php'; return true; }
        if (($sub === 'details' || $sub === 'details.php') && is_file($dir . '/details.php')) { require $dir . '/details.php'; return true; }
        http_response_code(404); echo "Not found\n"; return true;
    }
}

http_response_code(404); echo "Not found\n"; return true;
