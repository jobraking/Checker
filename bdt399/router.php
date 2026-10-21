<?php
declare(strict_types=1);

$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';

$ck = '/mnt/sdcard/htdocs/bdt399';
$cv = '/mnt/sdcard/htdocs/cv666';
$ja = '/mnt/sdcard/htdocs/jaya9bangla';
$tk = '/mnt/sdcard/htdocs/tk999';
$bdt = '/mnt/sdcard/htdocs/bdt399';
$bet5 = '/mnt/sdcard/htdocs/bet5666';
$ng = '/mnt/sdcard/htdocs/ng88b';
$xb = '/mnt/sdcard/htdocs/xbet';
$bet = '/mnt/sdcard/htdocs/bet499';

if ($path === '/' || $path === '/index.php') {
    echo "<!doctype html><html><head><meta charset=\"utf-8\"><title>D3M09 ACCOUNT CHECKER - CHECKER SHOWCASE</title>";
    echo "<style>body{font-family:sans-serif;background:#0f1115;color:#e6e8ee;margin:0;padding:40px}";
    echo ".grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;max-width:900px}";
    echo ".card{background:#171a21;border:1px solid #262b36;border-radius:12px;padding:24px;text-decoration:none;color:#e6e8ee}";
    echo ".card:hover{border-color:#6ea8fe}.title{font-size:20px;font-weight:700;margin:0 0 6px;color:#6ea8fe}";
    echo ".desc{color:#9aa3b2;font-size:13px}</style></head><body>";
    echo "<h1>Checker Showcase</h1><div class=\"grid\">";
    echo "<a class=\"card\" href=\"/bdt399/\"><p class=\"title\">CK Checker</p><p class=\"desc\">bdt3994.ai accounts</p></a>";
    echo "<a class=\"card\" href=\"/cv666/\"><p class=\"title\">CV Checker</p><p class=\"desc\">cv666 accounts</p></a>";
    echo "<a class=\"card\" href=\"/jaya9bangla/\"><p class=\"title\">Jaya9bangla Checker</p><p class=\"desc\">jaya9bangla accounts</p></a>";
    echo "<a class=\"card\" href=\"/tk999/\"><p class=\"title\">TK999 Checker</p><p class=\"desc\">tk999 accounts</p></a>";
    echo "<a class=\"card\" href=\"/bet5666/\"><p class=\"title\">Bet5666 Checker</p><p class=\"desc\">5666bet accounts</p></a>";
    echo "<a class=\"card\" href=\"/ng88b/\"><p class=\"title\">Ng88b Checker</p><p class=\"desc\">ng88b accounts</p></a>";
    echo "<a class=\"card\" href=\"/bdt399/\"><p class=\"title\">Bdt399 Checker</p><p class=\"desc\">399bdt accounts</p></a>";
    echo "<a class=\"card\" href=\"/xbet/\"><p class=\"title\">Xbet Checker</p><p class=\"desc\">1xbet accounts</p></a>";
    echo "<a class=\"card\" href=\"/bet499/\"><p class=\"title\">Bet499 Checker</p><p class=\"desc\">499bet444 accounts</p></a>";
    echo "</div></body></html>";
    return true;
}

if ($path === '/saved' || $path === '/saved.php') {
    require '/mnt/sdcard/htdocs/bdt399' . '/saved.php';
    return true;
}

foreach ([
    'bdt399' => $ck,
    'cv666' => $cv,
    'jaya9bangla' => $ja,
    'tk999' => $tk,
    'ng88b' => $ng,
    'bet5666' => $bet5,
    'xbet' => $xb,
    'bet499' => $bet,
] as $prefix => $dir) {
    if (preg_match('#^/' . $prefix . '(?:/(.*))?$#', $path, $m)) {
        $sub = $m[1] ?? '';
        if ($sub === '' || $sub === 'index.php') { require $dir . '/index.php'; return true; }
        if ($sub === 'saved' || $sub === 'saved.php') { require $dir . '/saved.php'; return true; }
        http_response_code(404); echo "Not found\n"; return true;
    }
}

http_response_code(404);
header('Content-Type: text/plain; charset=utf-8');
echo "Not found\n";
return true;
