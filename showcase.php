<?php
declare(strict_types=1);

require_once __DIR__ . '/guard.php';

$user = current_user();

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
    ['href'=>'/kx8/','title'=>'KX8 Checker','desc'=>'kx8.info accounts','domain'=>'kx8.info'],
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
    $count = count($items);
    ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>D3M09 ACCOUNT CHECKER - CHECKER SHOWCASE</title>
<style>
:root{
  --bg:#f4f8fc;
  --surface:#ffffff;
  --ink:#0d2135;
  --muted:#5f7186;
  --line:#dbe6f0;
  --brand:#0877ed;
  --brand-dark:#0560c4;
  --mint:#00b894;
  --shadow:0 18px 50px rgba(15,42,70,.08);
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{
  margin:0;
  font-family:ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
  color:var(--ink);
  background:
    radial-gradient(900px 500px at 10% -10%, #dcecff 0%, transparent 60%),
    radial-gradient(700px 420px at 95% 0%, #d9fff9 0%, transparent 55%),
    var(--bg);
  min-height:100vh;
  -webkit-font-smoothing:antialiased;
}
a{color:inherit}
.shell{width:min(1180px,calc(100% - 40px));margin-inline:auto}
.site-header{
  position:sticky;top:0;z-index:20;
  background:rgba(255,255,255,.82);
  backdrop-filter:blur(14px);
  border-bottom:1px solid rgba(219,230,240,.9);
}
.header-inner{display:flex;align-items:center;gap:18px;min-height:76px;flex-wrap:wrap}
.brand{display:flex;align-items:center;gap:12px;text-decoration:none;margin-right:auto;min-width:0}
.brand-mark{
  width:44px;height:44px;border-radius:13px;
  background:linear-gradient(135deg,var(--brand),#18a0fb 60%,var(--mint));
  display:grid;place-items:center;color:#fff;flex:none;
  box-shadow:0 10px 24px rgba(8,119,237,.28);
}
.brand-mark svg{width:24px;height:24px}
.brand strong{display:block;font-size:1.08rem;letter-spacing:-.02em;line-height:1.15}
.brand small{display:block;color:var(--muted);font-size:.76rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase}
.header-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.count-pill{
  display:inline-flex;align-items:center;gap:7px;
  padding:8px 13px;border-radius:999px;
  background:#eef6ff;color:#0b63ce;font-size:.82rem;font-weight:750;
  border:1px solid #d7e9ff;white-space:nowrap;
}
.count-dot{width:7px;height:7px;border-radius:50%;background:var(--mint);box-shadow:0 0 0 4px rgba(0,184,148,.14)}
.btn{
  display:inline-flex;align-items:center;justify-content:center;gap:8px;
  padding:11px 17px;border-radius:12px;text-decoration:none;
  font-weight:750;font-size:.9rem;border:1px solid transparent;
  transition:transform .18s ease,box-shadow .18s ease,background .18s ease;
  white-space:nowrap;
}
.btn-primary{background:var(--brand);color:#fff;box-shadow:0 10px 22px rgba(8,119,237,.24)}
.btn-primary:hover{background:var(--brand-dark);transform:translateY(-1px);box-shadow:0 14px 28px rgba(8,119,237,.3)}
.btn-ghost{background:#fff;color:var(--ink);border-color:var(--line)}
.btn-ghost:hover{background:#f8fbff;border-color:#b9d6f5;transform:translateY(-1px)}
.btn svg{width:17px;height:17px}
main{padding:clamp(28px,5vw,56px) 0 64px}
.hero{
  display:grid;grid-template-columns:minmax(0,1.15fr) minmax(320px,.85fr);
  gap:clamp(24px,4vw,56px);align-items:center;
  padding:clamp(8px,2vw,18px) 0 clamp(28px,4vw,44px);
}
.eyebrow{
  display:inline-flex;align-items:center;gap:8px;margin:0 0 14px;
  padding:7px 12px;border-radius:999px;background:#fff;border:1px solid var(--line);
  color:#0b63ce;font-size:.76rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;
  box-shadow:0 6px 16px rgba(15,42,70,.04);
}
.eyebrow::before{content:"";width:8px;height:8px;border-radius:50%;background:var(--mint);box-shadow:0 0 0 4px rgba(0,184,148,.13)}
.hero h1{margin:0;font-size:clamp(2.2rem,5vw,4.1rem);line-height:1.02;letter-spacing:-.045em;max-width:13ch}
.hero h1 span{color:var(--brand)}
.lede{margin:18px 0 0;max-width:56ch;color:var(--muted);font-size:clamp(1rem,1.3vw,1.12rem);line-height:1.65}
.search-card{
  background:rgba(255,255,255,.9);border:1px solid var(--line);
  border-radius:22px;padding:clamp(18px,2.4vw,26px);
  box-shadow:var(--shadow);position:relative;overflow:hidden;
}
.search-card::after{
  content:"";position:absolute;inset:auto -40px -70px auto;
  width:180px;height:180px;border-radius:50%;
  background:radial-gradient(circle,rgba(0,184,148,.16),transparent 70%);
  pointer-events:none;
}
.search-label{display:block;font-size:.8rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:10px}
.search{
  display:flex;align-items:center;gap:11px;background:#fff;
  border:1.5px solid var(--line);border-radius:14px;padding:0 14px;min-height:56px;
  transition:border-color .18s ease,box-shadow .18s ease;
}
.search:focus-within{border-color:var(--brand);box-shadow:0 0 0 4px rgba(8,119,237,.12)}
.search svg{width:20px;height:20px;color:#7c8fa5;flex:none}
.search input{
  border:0;outline:0;background:transparent;width:100%;
  font:inherit;font-size:1rem;color:var(--ink);padding:16px 0;min-width:0;
}
.search input::placeholder{color:#93a3b5}
.search kbd{
  flex:none;border:1px solid var(--line);border-bottom-width:2px;border-radius:7px;
  background:#f8fbff;color:#6a7d92;font-size:.72rem;font-weight:700;
  padding:3px 7px;font-family:inherit;
}
.search-hint{margin:12px 2px 0;color:var(--muted);font-size:.82rem;min-height:1.2em}
.section-head{display:flex;align-items:end;justify-content:space-between;gap:16px;margin-bottom:16px}
.section-head h2{margin:0;font-size:1.15rem;letter-spacing:-.02em}
.section-head p{margin:0;color:var(--muted);font-size:.86rem}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%,270px),1fr));gap:14px}
.card{
  position:relative;display:flex;align-items:center;gap:14px;min-height:100px;
  padding:16px;background:var(--surface);border:1px solid var(--line);
  border-radius:18px;text-decoration:none;color:var(--ink);overflow:hidden;
  transition:transform .18s ease,border-color .18s ease,box-shadow .18s ease;
}
.card::before{
  content:"";position:absolute;inset:0 auto 0 0;width:3px;
  background:linear-gradient(180deg,var(--brand),var(--mint));
  opacity:0;transition:opacity .18s ease;
}
.card:hover{transform:translateY(-3px);border-color:#b9d6f5;box-shadow:0 16px 34px rgba(15,42,70,.1)}
.card:hover::before{opacity:1}
.card:focus-visible{outline:3px solid rgba(8,119,237,.35);outline-offset:2px}
.card-icon{
  width:50px;height:50px;border-radius:14px;background:#f1f7ff;
  border:1px solid #e1edfb;display:grid;place-items:center;flex:none;overflow:hidden;
}
.card-icon img{width:30px;height:30px;object-fit:contain}
.card-body{min-width:0;display:flex;flex-direction:column;gap:4px}
.card-title{font-size:1.02rem;font-weight:800;letter-spacing:-.015em;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.card-desc{color:var(--muted);font-size:.82rem;line-height:1.35;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.card-domain{
  display:inline-flex;align-items:center;gap:5px;margin-top:3px;
  color:#71849a;font-size:.72rem;font-weight:650;letter-spacing:.02em;
  white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
}
.card-domain::before{content:"";width:5px;height:5px;border-radius:50%;background:#c4d3e2;flex:none}
.card-arrow{margin-left:auto;color:#b3c3d4;flex:none;transition:transform .18s ease,color .18s ease}
.card-arrow svg{width:18px;height:18px;display:block}
.card:hover .card-arrow{color:var(--brand);transform:translateX(3px)}
.empty{
  margin:26px 0 0;padding:22px;border:1px dashed #c9d8e8;border-radius:16px;
  background:rgba(255,255,255,.7);color:var(--muted);text-align:center;font-size:.92rem;
}
.empty strong{color:var(--ink)}
[hidden]{display:none!important}
footer{border-top:1px solid var(--line);background:rgba(255,255,255,.7)}
.footer-inner{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;padding:24px 0;color:var(--muted);font-size:.82rem}
.footer-inner a{text-decoration:none;font-weight:700;color:var(--ink)}
.footer-inner a:hover{color:var(--brand)}
@media(max-width:860px){
  .hero{grid-template-columns:1fr;gap:26px}
  .hero h1{max-width:16ch}
  .search-card{max-width:620px}
}
@media(max-width:640px){
  .shell{width:min(calc(100% - 28px),1180px)}
  .header-inner{min-height:68px;padding:10px 0;gap:12px}
  .brand strong{font-size:1rem}
  .brand small{font-size:.68rem}
  .header-actions{width:100%;justify-content:space-between}
  .count-pill{order:2}
  .btn-primary{order:1;flex:1;justify-content:center}
  .hero{padding-top:6px}
  .hero h1{font-size:clamp(2rem,9vw,2.8rem)}
  .lede{font-size:.98rem}
  .search kbd{display:none}
  .card{min-height:92px;padding:14px;gap:12px}
  .card-icon{width:46px;height:46px}
  .card-icon img{width:27px;height:27px}
  .card-title{font-size:.98rem}
  .card-desc{font-size:.78rem}
  .section-head{align-items:flex-start;flex-direction:column;gap:4px}
}
@media(max-width:400px){
  .header-actions{flex-direction:column;align-items:stretch}
  .count-pill{justify-content:center}
}
@media(prefers-reduced-motion:reduce){
  *{scroll-behavior:auto!important;transition:none!important;animation:none!important}
}
</style>
</head>
<body>
<header class="site-header">
  <div class="shell header-inner">
    <a class="brand" href="/">
      <span class="brand-mark" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      </span>
      <span>
        <strong>Checker Showcase</strong>
        <small>Account tools</small>
      </span>
    </a>
    <div class="header-actions">
      <span class="count-pill"><span class="count-dot" aria-hidden="true"></span><?= $count ?> checkers</span>
      <?php if ($user !== null): ?>
      <span class="count-pill"><?= htmlspecialchars((string) $user['username'], ENT_QUOTES, 'UTF-8') ?></span>
      <a class="btn btn-ghost" href="/logout.php">Sign out</a>
      <?php endif; ?>
      <a class="btn btn-primary" href="/format-fixer.php" target="_blank" rel="noopener">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15h6"/><path d="M9 11h2"/></svg>
        Check your file
      </a>
    </div>
  </div>
</header>

<main class="shell">
  <section class="hero">
    <div>
      <p class="eyebrow">One place. Every checker.</p>
      <h1>Find the right <span>account checker</span> in seconds.</h1>
      <p class="lede">Search by brand or domain, then open the checker you need. Everything stays in one clean, fast directory.</p>
    </div>
    <div class="search-card">
      <label class="search-label" for="showcaseSearch">Search the directory</label>
      <div class="search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <input id="showcaseSearch" type="search" placeholder="Brand, domain, or checker..." autocomplete="off" spellcheck="false" oninput="filterShowcase(this.value)">
        <kbd aria-hidden="true">/</kbd>
      </div>
      <p class="search-hint" id="searchHint" aria-live="polite">Showing <?= $count ?> checkers</p>
    </div>
  </section>

  <div class="section-head">
    <h2>All checkers</h2>
    <p>Select a card to open it</p>
  </div>

  <section class="grid" id="showcaseGrid" aria-label="Checker directory">
<?php foreach ($items as $it): ?>
<?php
    $fav = 'https://www.google.com/s2/favicons?domain=' . rawurlencode($it['domain']) . '&sz=64';
    $search = strtolower($it['title'] . ' ' . $it['desc'] . ' ' . $it['domain']);
?>
    <a class="card" data-search="<?= htmlspecialchars($search, ENT_QUOTES) ?>" href="<?= htmlspecialchars($it['href'], ENT_QUOTES) ?>">
      <span class="card-icon">
        <img src="<?= htmlspecialchars($fav, ENT_QUOTES) ?>" alt="" loading="lazy" decoding="async" onerror="this.style.display='none'">
      </span>
      <span class="card-body">
        <span class="card-title"><?= htmlspecialchars($it['title'], ENT_QUOTES) ?></span>
        <span class="card-desc"><?= htmlspecialchars($it['desc'], ENT_QUOTES) ?></span>
        <span class="card-domain"><?= htmlspecialchars($it['domain'], ENT_QUOTES) ?></span>
      </span>
      <span class="card-arrow" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
      </span>
    </a>
<?php endforeach; ?>
  </section>

  <p class="empty" id="emptyState" hidden>No checkers match <strong id="emptyQuery"></strong>.</p>
</main>

<footer>
  <div class="shell footer-inner">
    <span>&copy; <?= date('Y') ?> Checker Showcase</span>
    <span><a href="/format-fixer.php" target="_blank" rel="noopener">Format fixer</a> &nbsp;·&nbsp; <?= $count ?> tools</span>
  </div>
</footer>

<script>
function filterShowcase(q) {
  q = (q || '').trim().toLowerCase();
  var shown = 0;
  document.querySelectorAll('#showcaseGrid .card').forEach(function (card) {
    var match = !q || (card.getAttribute('data-search') || '').indexOf(q) !== -1;
    card.hidden = !match;
    if (match) shown++;
  });
  var empty = document.getElementById('emptyState');
  var query = document.getElementById('emptyQuery');
  var hint = document.getElementById('searchHint');
  if (empty) empty.hidden = shown !== 0;
  if (query) query.textContent = q;
  if (hint) {
    hint.textContent = q
      ? shown + ' result' + (shown === 1 ? '' : 's') + ' for "' + q + '"'
      : 'Showing <?= $count ?> checkers';
  }
}
document.addEventListener('keydown', function (e) {
  if (e.key === '/' && document.activeElement && document.activeElement.tagName !== 'INPUT') {
    e.preventDefault();
    var input = document.getElementById('showcaseSearch');
    if (input) input.focus();
  }
});
function fixFile(inp) {
  var f = inp.files && inp.files[0];
  if (!f) return;
  var r = new FileReader();
  r.onload = function () {
    var lines = r.result.split(/\r?\n/);
    var out = [];
    lines.forEach(function (line) {
      line = line.trim();
      if (!line || /^#/ .test(line)) return;
      if (/username/i.test(line) && /password/i.test(line)) return;
      var parts = null;
      if (line.indexOf('|') !== -1) {
        var p = line.split('|').map(function (x) { return x.trim(); }).filter(Boolean);
        if (p.length >= 2) parts = [p[0], p[1]];
      } else {
        var p = line.split(/\s+/).filter(Boolean);
        if (p.length >= 3) parts = [p[1], p[2]];
        else if (p.length === 2) parts = [p[0], p[1]];
      }
      if (parts && parts[0] && parts[1] && parts[1].toLowerCase() !== '*none*') out.push(parts[0] + '|' + parts[1]);
    });
    var blob = new Blob([out.join('\n')], { type: 'text/plain' });
    var a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'fixed.txt';
    a.click();
    alert('Fixed ' + out.length + ' line(s). Downloaded fixed.txt');
    inp.value = '';
  };
  r.readAsText(f);
}
</script>
</body>
</html>
