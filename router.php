<?php
declare(strict_types=1);

require_once __DIR__ . '/guard.php';

$path = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/';

$ck = __DIR__ . '/ck44';
$ppv = __DIR__ . '/ppvip10';
$cv = __DIR__ . '/cv666';
$ja = __DIR__ . '/jaya9bangla';
$tk = __DIR__ . '/tk999';
$tk666 = __DIR__ . '/tk666';
$bdt = __DIR__ . '/bdt399';
$bet5 = __DIR__ . '/bet5666';
$ng = __DIR__ . '/ng88b';
$xb = __DIR__ . '/xbet';
$bet = __DIR__ . '/bet499';
$bdtk3 = __DIR__ . '/bdtk3';
$bet499999 = __DIR__ . '/bet499999';
$bet499999a = __DIR__ . '/499bet999';
$l444 = __DIR__ . '/l444';
$bb44 = __DIR__ . '/bb44';
$bet39902 = __DIR__ . '/bet39902';
$bet39902a = __DIR__ . '/399bet02';
$betvip299 = __DIR__ . '/betvip299';
$betvip299a = __DIR__ . '/299betvip';
$gbajee53 = __DIR__ . '/gbajee53';
$cbaji8 = __DIR__ . '/cbaji8';
$bk3333 = __DIR__ . '/bk3333';
$bk3333a = __DIR__ . '/3333bk';
$bet1333 = __DIR__ . '/bet1333';
$bet1333a = __DIR__ . '/1333bet';
$betvip9777 = __DIR__ . '/betvip9777';
$betvip9777a = __DIR__ . '/9777betvip';
$bet8777888 = __DIR__ . '/bet8777888';
$bet8777888a = __DIR__ . '/8777bet888';
$vip6777 = __DIR__ . '/vip6777';
$vip6777a = __DIR__ . '/6777vip';
$bet5777 = __DIR__ . '/bet5777';
$bet5777a = __DIR__ . '/5777bet';
$betvip4777b = __DIR__ . '/betvip4777';
$bet4777vip = __DIR__ . '/4777betvip';
$betvip2777 = __DIR__ . '/betvip2777';
$bet2777vip = __DIR__ . '/2777betvip';
$bet1777 = __DIR__ . '/bet1777';
$bet1777a = __DIR__ . '/1777bet';

$nb = __DIR__ . '/nbajee88';
$n97 = __DIR__ . '/97nbajee';
$n12 = __DIR__ . '/12xbajee';
$n88 = __DIR__ . '/88hbajee';

$items = [
    ['href'=>'/1333bet/','title'=>'1333bet Checker','desc'=>'1333bet accounts','domain'=>'1333bet.ai'],
    ['href'=>'/1777bet/','title'=>'1777bet Checker','desc'=>'1777bet accounts','domain'=>'1777bet.win'],
    ['href'=>'/2777betvip/','title'=>'2777betvip Checker','desc'=>'2777betvip accounts','domain'=>'2777betvip.com'],
    ['href'=>'/299betvip/','title'=>'299betvip Checker','desc'=>'299betvip accounts','domain'=>'299betvip.com'],
    ['href'=>'/3333bk/','title'=>'3333bk Checker','desc'=>'3333bk accounts','domain'=>'3333bk.com'],
    ['href'=>'/399bet02/','title'=>'399bet02 Checker','desc'=>'399bet02 accounts','domain'=>'399bet02.com'],
    ['href'=>'/4777betvip/','title'=>'4777betvip Checker','desc'=>'4777betvip accounts','domain'=>'4777betvip.com'],
    ['href'=>'/5777bet/','title'=>'5777bet Checker','desc'=>'5777bet accounts','domain'=>'5777bet.win'],
    ['href'=>'/6777vip/','title'=>'6777vip Checker','desc'=>'vip6777 accounts','domain'=>'vip6777.com'],
    ['href'=>'/8777bet888/','title'=>'8777bet888 Checker','desc'=>'8777bet888 accounts','domain'=>'8777bet888.com'],
    ['href'=>'/9777betvip/','title'=>'9777betvip Checker','desc'=>'9777betvip accounts','domain'=>'9777betvip.com'],
    ['href'=>'/bb44/','title'=>'Bb44 Checker','desc'=>'bb44 accounts','domain'=>'bb44.best'],
    ['href'=>'/bdt399/','title'=>'Bdt399 Checker','desc'=>'399bdt accounts','domain'=>'399bdt.com'],
    ['href'=>'/bet1333/','title'=>'Bet1333 Checker','desc'=>'1333bet accounts','domain'=>'1333bet.ai'],
    ['href'=>'/bet1777/','title'=>'Bet1777 Checker','desc'=>'1777bet accounts','domain'=>'1777bet.win'],
    ['href'=>'/bet39902/','title'=>'Bet39902 Checker','desc'=>'399bet02 accounts','domain'=>'399bet02.com'],
    ['href'=>'/bet499/','title'=>'Bet499 Checker','desc'=>'499bet444 accounts','domain'=>'499bet444.com'],
    ['href'=>'/bdtk3/','title'=>'Bdtk3 Checker','desc'=>'bdtk3 accounts','domain'=>'bdtk3.club'],
    ['href'=>'/bet499999/','title'=>'Bet499999 Checker','desc'=>'499bet999 accounts','domain'=>'499bet999.com'],
    ['href'=>'/499bet999/','title'=>'499bet999 Checker','desc'=>'499bet999 accounts','domain'=>'499bet999.com'],
    ['href'=>'/bet5666/','title'=>'Bet5666 Checker','desc'=>'5666bet accounts','domain'=>'5666bet.ai'],
    ['href'=>'/bet5777/','title'=>'Bet5777 Checker','desc'=>'5777bet accounts','domain'=>'5777bet.win'],
    ['href'=>'/bet8777888/','title'=>'Bet8777888 Checker','desc'=>'8777bet888 accounts','domain'=>'8777bet888.com'],
    ['href'=>'/betvip299/','title'=>'Betvip299 Checker','desc'=>'299betvip accounts','domain'=>'299betvip.com'],
    ['href'=>'/betvip9777/','title'=>'Betvip9777 Checker','desc'=>'9777betvip accounts','domain'=>'9777betvip.com'],
    ['href'=>'/bk3333/','title'=>'Bk3333 Checker','desc'=>'3333bk accounts','domain'=>'3333bk.com'],
    ['href'=>'/ck44/','title'=>'CK Checker','desc'=>'ck444.ai accounts','domain'=>'ck444.ai'],
    ['href'=>'/cv666/','title'=>'CV Checker','desc'=>'cv666 accounts','domain'=>'cv666app.net'],
    ['href'=>'/cbaji8/','title'=>'Cbaji8 Checker','desc'=>'cbaji8 accounts','domain'=>'cbaji8.club'],
    ['href'=>'/gbajee53/','title'=>'Gbajee53 Checker','desc'=>'gbajee53 accounts','domain'=>'gbajee53.vip'],
    ['href'=>'/jaya9bangla/','title'=>'Jaya9bangla Checker','desc'=>'jaya9bangla accounts','domain'=>'ck444.ai'],
    ['href'=>'/nbajee88/','title'=>'Nbajee88 Checker','desc'=>'nbajee88 accounts','domain'=>'nbajee88.cc'],
    ['href'=>'/97nbajee/','title'=>'97Nbajee Checker','desc'=>'97nbajee accounts','domain'=>'97nbajee.club'],
    ['href'=>'/12xbajee/','title'=>'12Xbajee Checker','desc'=>'12xbajee accounts','domain'=>'12xbajee.com'],
    ['href'=>'/88hbajee/','title'=>'Hbajee Checker','desc'=>'hbajee accounts','domain'=>'88hbajee.com'],
    ['href'=>'/l444/','title'=>'L444 Checker','desc'=>'l444 accounts','domain'=>'l444.bet'],
    ['href'=>'/ng88b/','title'=>'Ng88b Checker','desc'=>'ng88b accounts','domain'=>'ng88b.com'],
    ['href'=>'/tk999/','title'=>'TK999 Checker','desc'=>'tk999 accounts','domain'=>'tk999.org'],
    ['href'=>'/tk666/','title'=>'Tk666 Checker','desc'=>'tk666 accounts','domain'=>'tk666.best'],
    ['href'=>'/vip6777/','title'=>'Vip6777 Checker','desc'=>'vip6777 accounts','domain'=>'vip6777.com'],
    ['href'=>'/ppvip10/','title'=>'Ppvip10 Checker','desc'=>'ppvip10 accounts','domain'=>'ppvip1.ppvip01.com'],
    ['href'=>'/xbet/','title'=>'Xbet Checker','desc'=>'1xbet accounts','domain'=>'1xbet.com'],
];

if ($path === '/' || $path === '/index.php') { require __DIR__ . '/index.php'; return true; }
if ($path === '/showcase' || $path === '/showcase.php') { require __DIR__ . '/showcase.php'; return true; }

foreach ([
    'ck44' => $ck,
    'ppvip10' => $ppv,
    'cv666' => $cv,
    'jaya9bangla' => $ja,
    'nbajee88' => $nb,
    '97nbajee' => $n97,
    '12xbajee' => $n12,
    '88hbajee' => $n88,
    'hbajee' => $n88,
    'tk999' => $tk,
    'tk666' => $tk666,
    'ng88b' => $ng,
    'bdt399' => $bdt,
    'bet5666' => $bet5,
    'xbet' => $xb,
    'bet499' => $bet,
    'bdtk3' => $bdtk3,
    'bet499999' => $bet499999,
    '499bet999' => $bet499999a,
    'l444' => $l444,
    'bb44' => $bb44,
    'bet39902' => $bet39902,
    '399bet02' => $bet39902a,
    'betvip299' => $betvip299,
    '299betvip' => $betvip299a,
    'gbajee53' => $gbajee53,
    'cbaji8' => $cbaji8,
    'bk3333' => $bk3333,
    '3333bk' => $bk3333a,
    'bet1333' => $bet1333,
    '1333bet' => $bet1333a,
    'betvip2777' => $betvip2777,
    '2777betvip' => $bet2777vip,
    'betvip4777' => $betvip4777b,
    '4777betvip' => $bet4777vip,
    'betvip9777' => $betvip9777,
    '9777betvip' => $betvip9777a,
    'bet8777888' => $bet8777888,
    '8777bet888' => $bet8777888a,
    'vip6777' => $vip6777,
    '6777vip' => $vip6777a,
    'bet5777' => $bet5777,
    '5777bet' => $bet5777a,
    'bet1777' => $bet1777,
    '1777bet' => $bet1777a,
] as $prefix => $dir) {
    if (preg_match('#^/' . $prefix . '(?:/(.*))?$#', $path, $m)) {
        $sub = $m[1] ?? '';
        if ($sub === '' || $sub === 'index.php') { require $dir . '/index.php'; return true; }
        if ($sub === 'saved' || $sub === 'saved.php') { require $dir . '/saved.php'; return true; }
        http_response_code(404); echo "Not found\n"; return true;
    }
}

if ($path === '/format-fixer.php' || $path === '/format-fixer') { require __DIR__ . '/format-fixer.php'; return true; }

if (in_array($path, ['/login', '/login.php'], true)) { require __DIR__ . '/login.php'; return true; }
if (in_array($path, ['/register', '/register.php'], true)) { require __DIR__ . '/register.php'; return true; }
if (in_array($path, ['/dashboard', '/dashboard.php'], true)) { require __DIR__ . '/dashboard.php'; return true; }
if (in_array($path, ['/logout', '/logout.php'], true)) { require __DIR__ . '/logout.php'; return true; }

http_response_code(404);
header('Content-Type: text/plain; charset=utf-8');
echo "Not found\n";
return true;
