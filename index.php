<?php
declare(strict_types=1);

require_once __DIR__ . '/guard.php';

$sites = [
    ['href' => '/ck44/', 'name' => 'CK444', 'domain' => 'ck444.ai'],
    ['href' => '/nbajee88/', 'name' => 'Nbajee88', 'domain' => 'nbajee88.cc'],
    ['href' => '/97nbajee/', 'name' => '97Nbajee', 'domain' => '97nbajee.club'],
    ['href' => '/12xbajee/', 'name' => '12Xbajee', 'domain' => '12xbajee.com'],
    ['href' => '/88hbajee/', 'name' => 'Hbajee', 'domain' => '88hbajee.com'],
    ['href' => '/299betvip/', 'name' => '299BetVip', 'domain' => '299betvip.com'],
    ['href' => '/399bet02/', 'name' => '399Bet02', 'domain' => '399bet02.com'],
    ['href' => '/499bet999/', 'name' => '499Bet999', 'domain' => '499bet999.com'],
    ['href' => '/5777bet/', 'name' => '5777Bet', 'domain' => '5777bet.win'],
    ['href' => '/8777bet888/', 'name' => '8777Bet888', 'domain' => '8777bet888.com'],
    ['href' => '/jaya9bangla/', 'name' => 'Jaya9Bangla', 'domain' => 'ck444.ai'],
    ['href' => '/kx8/', 'name' => 'KX8', 'domain' => 'kx8.info'],
    ['href' => '/ppvip10/', 'name' => 'PPVip10', 'domain' => 'ppvip01.com'],
];

$stats = [
    ['value' => '43', 'label' => 'Live deployments'],
    ['value' => '4.3k', 'label' => 'Lines per node'],
    ['value' => '2', 'label' => 'Crypto layers'],
    ['value' => '0', 'label' => 'Remote dependencies'],
];

$features = [
    [
        'title' => 'Mirrored request pipeline',
        'body' => 'Rebuilds the browser handshake byte for byte: RSA-wrapped 16-char secret in the Encryption header, DES-ECB/PKCS7 payload as X-Digest.',
    ],
    [
        'title' => 'Merchant auto-detection',
        'body' => 'Resolves the hashed app bundle, reads the build config, and pins the merchant this node actually serves. No manual config.',
    ],
    [
        'title' => 'Single and bulk checks',
        'body' => 'Pipe, colon, whitespace or URL-prefixed credential rows. Batch mode reports valid, invalid and captcha states per line.',
    ],
    [
        'title' => 'Live endpoint explorer',
        'body' => 'Authenticated GET sweep across the full live endpoint map, rendered as raw JSON so nothing is hidden behind formatting.',
    ],
    [
        'title' => 'Saved account vault',
        'body' => 'Successful VIP logins land in a per-node vault with masked tokens, rank filters, live balance and promo lookups.',
    ],
    [
        'title' => 'Local-only surface',
        'body' => 'Every checker and saved vault requires a signed-in session. CSRF tokens guard mutations and passwords are never written to the PHP session.',
    ],
];

$primary = $sites[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>D3M09 — Account Checker Platform</title>
<style>
:root{
  --bg:#f4f8fc;
  --surface:#ffffff;
  --surface-2:#f8fbff;
  --ink:#0d2135;
  --muted:#5f7186;
  --line:#dbe6f0;
  --brand:#0877ed;
  --brand-dark:#0560c4;
  --brand-soft:#e9f3ff;
  --mint:#00b894;
  --mint-soft:#e7fbf5;
  --shadow:0 18px 50px rgba(15,42,70,.08);
  --shadow-sm:0 8px 24px rgba(15,42,70,.06);
  --radius:20px;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{
  margin:0;
  font-family:ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
  color:var(--ink);
  background:
    radial-gradient(900px 520px at 8% -12%, #dcecff 0%, transparent 58%),
    radial-gradient(820px 460px at 100% 0%, #ddfff7 0%, transparent 54%),
    var(--bg);
  min-height:100vh;
  -webkit-font-smoothing:antialiased;
  line-height:1.55;
}
a{color:inherit;text-decoration:none}
.shell{width:min(1180px,calc(100% - 40px));margin-inline:auto}
.site-header{
  position:sticky;top:0;z-index:20;
  background:rgba(255,255,255,.82);
  backdrop-filter:blur(14px);
  border-bottom:1px solid rgba(219,230,240,.9);
}
.header-inner{display:flex;align-items:center;gap:18px;min-height:76px;flex-wrap:wrap}
.brand{display:flex;align-items:center;gap:12px;margin-right:auto;min-width:0}
.brand-mark{
  width:44px;height:44px;border-radius:13px;flex:none;
  background:linear-gradient(135deg,var(--brand),#18a0fb 60%,var(--mint));
  display:grid;place-items:center;color:#fff;
  box-shadow:0 10px 24px rgba(8,119,237,.28);
}
.brand-mark svg{width:24px;height:24px}
.brand strong{display:block;font-size:1.08rem;letter-spacing:-.02em;line-height:1.15}
.brand small{display:block;color:var(--muted);font-size:.72rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase}
.nav{display:flex;align-items:center;gap:6px;flex-wrap:wrap}
.nav a{
  padding:9px 13px;border-radius:10px;font-size:.9rem;font-weight:650;color:var(--muted);
  transition:background .18s ease,color .18s ease;
}
.nav a:hover{background:var(--brand-soft);color:var(--brand-dark)}
.btn{
  display:inline-flex;align-items:center;justify-content:center;gap:9px;
  padding:12px 20px;border-radius:12px;border:1px solid transparent;
  font-size:.94rem;font-weight:750;cursor:pointer;
  transition:transform .16s ease,box-shadow .16s ease,background .16s ease;
}
.btn:hover{transform:translateY(-1px)}
.btn svg{width:17px;height:17px}
.btn-primary{
  background:linear-gradient(135deg,var(--brand),#2a95ff);
  color:#fff;box-shadow:0 12px 26px rgba(8,119,237,.28);
}
.btn-ghost{background:#fff;color:var(--ink);border-color:var(--line);box-shadow:var(--shadow-sm)}
.btn-ghost:hover{border-color:#bcd4ee}
.hero{padding:64px 0 28px}
.hero-grid{display:grid;grid-template-columns:minmax(0,1.08fr) minmax(0,.92fr);gap:44px;align-items:center}
.eyebrow{
  display:inline-flex;align-items:center;gap:8px;
  padding:7px 13px;border-radius:999px;
  background:var(--mint-soft);border:1px solid #bfeee0;color:#087f66;
  font-size:.76rem;font-weight:800;letter-spacing:.09em;text-transform:uppercase;
}
.dot{width:7px;height:7px;border-radius:50%;background:var(--mint);box-shadow:0 0 0 4px rgba(0,184,148,.15)}
h1{
  margin:18px 0 16px;
  font-size:clamp(2.1rem,4.6vw,3.5rem);
  line-height:1.06;letter-spacing:-.035em;font-weight:800;
}
h1 span{
  background:linear-gradient(120deg,var(--brand) 10%,#18a0fb 55%,var(--mint) 100%);
  -webkit-background-clip:text;background-clip:text;color:transparent;
}
.lede{margin:0 0 26px;color:var(--muted);font-size:1.04rem;max-width:56ch}
.hero-actions{display:flex;gap:12px;flex-wrap:wrap;align-items:center}
.hero-note{color:var(--muted);font-size:.82rem;font-weight:600}
.panel{
  background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);
  box-shadow:var(--shadow);overflow:hidden;
}
.panel-head{
  display:flex;align-items:center;gap:8px;
  padding:13px 18px;border-bottom:1px solid var(--line);
  background:var(--surface-2);
}
.chip{width:9px;height:9px;border-radius:50%;background:#ffc2c2}
.chip.b{background:#ffe3a3}
.chip.c{background:#b6f0d9}
.panel-title{margin-left:8px;font-size:.78rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}
.pipeline{padding:8px 20px 20px;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:.82rem}
.step{display:flex;gap:12px;padding:11px 0;border-bottom:1px dashed #e8eff7}
.step:last-child{border-bottom:0}
.step-no{
  flex:none;width:22px;height:22px;border-radius:7px;display:grid;place-items:center;
  background:var(--brand-soft);color:var(--brand-dark);font-size:.7rem;font-weight:800;
}
.step code{color:#123c66}
.step small{display:block;color:var(--muted);font-family:inherit;font-size:.72rem;margin-top:2px}
.stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin:26px 0 0;padding:0;list-style:none}
.stat{
  background:rgba(255,255,255,.72);border:1px solid var(--line);border-radius:16px;
  padding:16px 18px;box-shadow:var(--shadow-sm);
}
.stat b{display:block;font-size:1.5rem;letter-spacing:-.03em;line-height:1.1}
.stat span{display:block;color:var(--muted);font-size:.76rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;margin-top:4px}
section{padding:54px 0}
.section-head{max-width:60ch;margin-bottom:26px}
.kicker{color:var(--brand-dark);font-size:.76rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase}
h2{margin:8px 0 10px;font-size:clamp(1.5rem,2.6vw,2.1rem);letter-spacing:-.025em;line-height:1.15}
.section-head p{margin:0;color:var(--muted)}
.features{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
.feature{
  background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);
  padding:22px;box-shadow:var(--shadow-sm);
  transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease;
}
.feature:hover{transform:translateY(-3px);box-shadow:var(--shadow);border-color:#c9ddf2}
.feature-icon{
  width:40px;height:40px;border-radius:12px;display:grid;place-items:center;
  background:var(--brand-soft);color:var(--brand-dark);margin-bottom:14px;
}
.feature-icon svg{width:20px;height:20px}
.feature h3{margin:0 0 7px;font-size:1.02rem;letter-spacing:-.01em}
.feature p{margin:0;color:var(--muted);font-size:.9rem}
.sites-section{background:linear-gradient(180deg,rgba(255,255,255,0) 0%,rgba(255,255,255,.62) 22%,rgba(255,255,255,0) 100%)}
.sites{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
.site{
  display:flex;align-items:center;gap:13px;
  background:var(--surface);border:1px solid var(--line);border-radius:14px;
  padding:14px 16px;box-shadow:var(--shadow-sm);
  transition:transform .16s ease,border-color .16s ease,box-shadow .16s ease;
}
.site:hover{transform:translateY(-2px);border-color:#bcd4ee;box-shadow:var(--shadow)}
.site-mark{
  flex:none;width:38px;height:38px;border-radius:11px;display:grid;place-items:center;
  background:linear-gradient(135deg,#eaf3ff,#e9fbf5);color:var(--brand-dark);
  font-size:.78rem;font-weight:800;letter-spacing:-.02em;
}
.site-meta{min-width:0}
.site-meta b{display:block;font-size:.92rem;letter-spacing:-.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.site-meta span{display:block;color:var(--muted);font-size:.76rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.site-arrow{margin-left:auto;color:#9db3c8;flex:none;transition:transform .16s ease,color .16s ease}
.site:hover .site-arrow{transform:translateX(3px);color:var(--brand)}
.site-arrow svg{width:16px;height:16px}
.sites-more{display:flex;justify-content:center;margin-top:22px}
.cta{padding:20px 0 70px}
.cta-box{
  position:relative;overflow:hidden;
  background:linear-gradient(120deg,#0b5fbe,#0877ed 45%,#00b894 130%);
  color:#fff;border-radius:26px;padding:38px 40px;
  box-shadow:0 26px 60px rgba(8,90,180,.26);
  display:flex;align-items:center;gap:26px;flex-wrap:wrap;
}
.cta-box::after{
  content:"";position:absolute;inset:-40% -10% auto auto;width:380px;height:380px;border-radius:50%;
  background:radial-gradient(circle,rgba(255,255,255,.22),transparent 65%);
}
.cta-box h2{margin:0 0 6px;color:#fff;font-size:clamp(1.4rem,2.4vw,1.9rem)}
.cta-box p{margin:0;color:rgba(255,255,255,.82);font-size:.94rem;max-width:58ch}
.cta-actions{margin-left:auto;display:flex;gap:10px;flex-wrap:wrap;position:relative;z-index:1}
.btn-light{background:#fff;color:var(--brand-dark);box-shadow:0 12px 26px rgba(3,40,80,.2)}
.btn-outline-light{background:rgba(255,255,255,.12);color:#fff;border-color:rgba(255,255,255,.4)}
footer{border-top:1px solid var(--line);padding:26px 0 34px;color:var(--muted);font-size:.84rem}
.footer-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap}
.footer-inner strong{color:var(--ink)}
@media (max-width:960px){
  .hero-grid{grid-template-columns:1fr;gap:30px}
  .features{grid-template-columns:repeat(2,minmax(0,1fr))}
  .sites{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media (max-width:720px){
  .nav{display:none}
  .stats{grid-template-columns:repeat(2,minmax(0,1fr))}
  .features,.sites{grid-template-columns:1fr}
  .cta-box{padding:28px 24px}
  .cta-actions{margin-left:0}
}
</style>
</head>
<body>
<header class="site-header">
  <div class="shell header-inner">
    <a class="brand" href="/">
      <span class="brand-mark" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7z"/><path d="M9 12l2 2 4-4"/></svg>
      </span>
      <span>
        <strong>D3M09 Checker</strong>
        <small>Account platform</small>
      </span>
    </a>
    <nav class="nav" aria-label="Primary">
      <a href="#features">Features</a>
      <a href="#sites">Sites</a>
      <a href="/showcase.php">Showcase</a>
    </nav>
    <a class="btn btn-ghost" href="/showcase.php">
      Open Showcase
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
    </a>
  </div>
</header>

<main>
  <section class="hero">
    <div class="shell">
      <div class="hero-grid">
        <div>
          <span class="eyebrow"><span class="dot"></span> Local console · v2 pipeline</span>
          <h1>Every checker node,<br><span>one clean platform.</span></h1>
          <p class="lede">Unified front end for the checker fleet. Same crypto handshake, same bulk parser, same saved vault — across every deployment, with the showcase still one click away.</p>
          <div class="hero-actions">
            <a class="btn btn-primary" href="<?= htmlspecialchars($primary['href'], ENT_QUOTES, 'UTF-8') ?>">
              Launch a checker
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
            </a>
            <a class="btn btn-ghost" href="/showcase.php">Browse all 43</a>
            <span class="hero-note">Login required</span>
          </div>
          <ul class="stats">
            <?php foreach ($stats as $stat): ?>
            <li class="stat">
              <b><?= htmlspecialchars($stat['value'], ENT_QUOTES, 'UTF-8') ?></b>
              <span><?= htmlspecialchars($stat['label'], ENT_QUOTES, 'UTF-8') ?></span>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="panel" aria-label="Request pipeline">
          <div class="panel-head">
            <span class="chip"></span><span class="chip b"></span><span class="chip c"></span>
            <span class="panel-title">Login pipeline</span>
          </div>
          <div class="pipeline">
            <div class="step"><span class="step-no">1</span><div><code>GET /wps/session/key/rsa</code><small>Fetch the 2048-bit public modulus</small></div></div>
            <div class="step"><span class="step-no">2</span><div><code>secret = rndString(16)</code><small>61-char vendor alphabet, reversed</small></div></div>
            <div class="step"><span class="step-no">3</span><div><code>Encryption: RSA(secret)</code><small>jsbn-compatible chunk packing</small></div></div>
            <div class="step"><span class="step-no">4</span><div><code>X-Digest: DES-ECB(PKCS7)</code><small>Keyed with secret[0:8]</small></div></div>
            <div class="step"><span class="step-no">5</span><div><code>POST /wps/session/login</code><small>{"value": ciphertext} → token</small></div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="features">
    <div class="shell">
      <div class="section-head">
        <span class="kicker">Under the hood</span>
        <h2>Built like the original, minus the rough edges</h2>
        <p>Each node is a self-contained PHP deployment with its own client, crypto layer and vault. The platform keeps them consistent.</p>
      </div>
      <div class="features">
        <?php foreach ($features as $feature): ?>
        <article class="feature">
          <span class="feature-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 4v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V7z"/></svg>
          </span>
          <h3><?= htmlspecialchars($feature['title'], ENT_QUOTES, 'UTF-8') ?></h3>
          <p><?= htmlspecialchars($feature['body'], ENT_QUOTES, 'UTF-8') ?></p>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="sites" class="sites-section">
    <div class="shell">
      <div class="section-head">
        <span class="kicker">Deployments</span>
        <h2>Quick launch</h2>
        <p>Twelve frequently used nodes. The full directory lives in the showcase.</p>
      </div>
      <div class="sites">
        <?php foreach ($sites as $site): ?>
        <a class="site" href="<?= htmlspecialchars($site['href'], ENT_QUOTES, 'UTF-8') ?>">
          <span class="site-mark" aria-hidden="true"><?= htmlspecialchars(strtoupper(substr($site['name'], 0, 2)), ENT_QUOTES, 'UTF-8') ?></span>
          <span class="site-meta">
            <b><?= htmlspecialchars($site['name'], ENT_QUOTES, 'UTF-8') ?></b>
            <span><?= htmlspecialchars($site['domain'], ENT_QUOTES, 'UTF-8') ?></span>
          </span>
          <span class="site-arrow" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
          </span>
        </a>
        <?php endforeach; ?>
      </div>
      <div class="sites-more">
        <a class="btn btn-ghost" href="/showcase.php">
          See every deployment
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </div>
  </section>

  <section class="cta">
    <div class="shell">
      <div class="cta-box">
        <div>
          <h2>Start from the showcase</h2>
          <p>Pick a node, open its console, and run a single or bulk check. All state stays on this machine.</p>
        </div>
        <div class="cta-actions">
          <a class="btn btn-light" href="/showcase.php">Open Showcase</a>
          <a class="btn btn-outline-light" href="<?= htmlspecialchars($primary['href'], ENT_QUOTES, 'UTF-8') ?>">Try CK444</a>
        </div>
      </div>
    </div>
  </section>
</main>

<footer>
  <div class="shell footer-inner">
    <span><strong>D3M09 Checker</strong> · local account console</span>
    <span>Session access · no telemetry</span>
  </div>
</footer>
</body>
</html>
