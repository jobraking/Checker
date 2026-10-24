<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/guard.php';

$site = basename(__DIR__);
$meta = [
    'ng88b' => [
        'domain' => 'ng88b.com',
        'note'   => 'This platform runs on a Vue SPA and needs a dedicated integration before accounts can be checked here.',
    ],
    'xbet' => [
        'domain' => 'bd.1xbet.com',
        'note'   => '1xbet uses a different API and authentication flow than the standard checker template.',
    ],
][$site] ?? [
    'domain' => $site,
    'note'   => 'This checker is not implemented yet.',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>D3M09 ACCOUNT CHECKER - <?= htmlspecialchars(strtoupper($site), ENT_QUOTES) ?></title>
<style>
:root{--bg:#f5f8f7;--surface:#fff;--ink:#0f1f2e;--muted:#64748b;--line:#e2e8f0;--brand:#0f9f7a;--brand-dark:#0b7f61;--brand-soft:#e9fbf5}
*{box-sizing:border-box}
body{margin:0;min-height:100vh;font-family:ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;color:var(--ink);line-height:1.55;background:radial-gradient(900px 520px at 8% -12%,#e1fbf2 0,transparent 58%),radial-gradient(820px 460px at 100% 0,#e8f1ff 0,transparent 54%),var(--bg);-webkit-font-smoothing:antialiased}
a{color:var(--brand-dark)}
.shell{width:min(1080px,calc(100% - 40px));margin-inline:auto}
.site-header{position:sticky;top:0;z-index:20;background:rgba(255,255,255,.84);backdrop-filter:blur(14px);border-bottom:1px solid rgba(226,232,240,.9)}
.header-inner{display:flex;align-items:center;gap:18px;min-height:72px;flex-wrap:wrap}
.brand{display:flex;align-items:center;gap:12px;margin-right:auto;text-decoration:none;color:var(--ink)}
.brand-mark{width:42px;height:42px;border-radius:13px;background:linear-gradient(135deg,var(--brand),#34d399);color:#fff;display:grid;place-items:center;box-shadow:0 10px 22px rgba(15,159,122,.22)}
.brand-mark svg{width:22px;height:22px}
.brand strong{display:block;font-size:1.02rem;letter-spacing:-.02em;line-height:1.1;text-transform:uppercase}
.brand small{display:block;color:var(--muted);font-size:.68rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 15px;border-radius:11px;border:1px solid var(--line);background:#fff;color:var(--ink);font:inherit;font-size:.84rem;font-weight:750;text-decoration:none;transition:transform .18s ease,border-color .18s ease,background .18s ease}
.btn:hover{border-color:#b9e8da;background:#f7fffc;transform:translateY(-1px)}
main{padding:clamp(34px,6vw,72px) 0 56px}
.eyebrow{display:inline-flex;align-items:center;gap:8px;padding:6px 11px;border-radius:999px;background:#fff;border:1px solid var(--line);color:var(--brand-dark);font-size:.7rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase}
.dot{width:7px;height:7px;border-radius:50%;background:#f59e0b;box-shadow:0 0 0 4px rgba(245,158,11,.13)}
h1{margin:14px 0 12px;font-size:clamp(2.1rem,4.8vw,3.4rem);line-height:1.04;letter-spacing:-.05em;max-width:16ch}
h1 span{color:var(--brand)}
.lede{margin:0;max-width:60ch;color:var(--muted);font-size:1.02rem;line-height:1.65}
.card{margin-top:28px;background:rgba(255,255,255,.94);border:1px solid var(--line);border-radius:18px;box-shadow:0 16px 40px rgba(15,42,70,.06);padding:clamp(20px,3vw,30px);max-width:760px}
.card-top{display:flex;align-items:flex-start;gap:14px}
.card-icon{width:44px;height:44px;border-radius:12px;background:var(--brand-soft);color:var(--brand-dark);display:grid;place-items:center;flex:none}
.card-icon svg{width:21px;height:21px}
.card h2{margin:0 0 4px;font-size:1.08rem;letter-spacing:-.02em}
.card p{margin:0;color:var(--muted);font-size:.87rem;line-height:1.6}
.meta{display:flex;gap:8px;flex-wrap:wrap;margin-top:16px}
.chip{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;background:#f8fafc;border:1px solid var(--line);color:var(--muted);font-size:.72rem;font-weight:650}
.chip strong{color:var(--ink);font-weight:750}
.actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:20px}
.site-footer{border-top:1px solid var(--line);background:rgba(255,255,255,.62);margin-top:36px}
.footer-inner{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:20px 0;color:var(--muted);font-size:.75rem}
@media(max-width:640px){.shell{width:calc(100% - 28px)}.header-inner{min-height:64px}.brand strong{font-size:.92rem}.brand small{font-size:.62rem}.card-top{flex-direction:column}h1{font-size:clamp(2rem,9vw,2.7rem)}}
@media(prefers-reduced-motion:reduce){*{transition:none!important}}
</style>
</head>
<body>
<header class="site-header">
  <div class="shell header-inner">
    <a class="brand" href="/">
      <span class="brand-mark" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4.5 6v5.5c0 4.4 3.1 7.7 7.5 9.5 4.4-1.8 7.5-5.1 7.5-9.5V6L12 3Z"/><path d="m8.8 12 2.1 2.1 4.4-4.4"/></svg>
      </span>
      <span>
        <strong><?= htmlspecialchars(strtoupper($site), ENT_QUOTES) ?></strong>
        <small>Account checker</small>
      </span>
    </a>
    <a class="btn" href="/">All checkers</a>
    <a class="btn" href="/logout.php">Sign out</a>
  </div>
</header>

<main class="shell">
  <span class="eyebrow"><span class="dot"></span> Coming soon</span>
  <h1>A cleaner check is on the way.</h1>
  <p class="lede">The interface is ready, but this platform still needs its own account-checking integration before balances and sessions can be shown here.</p>

  <section class="card">
    <div class="card-top">
      <span class="card-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5"/><path d="M12 16h.01"/></svg>
      </span>
      <div>
        <h2><?= htmlspecialchars($meta['domain'], ENT_QUOTES) ?> is not wired up yet</h2>
        <p><?= htmlspecialchars($meta['note'], ENT_QUOTES) ?></p>
      </div>
    </div>
    <div class="meta">
      <span class="chip">Platform <strong><?= htmlspecialchars($site, ENT_QUOTES) ?></strong></span>
      <span class="chip">Status <strong>Not implemented</strong></span>
      <span class="chip">Balance <strong>Unavailable</strong></span>
    </div>
    <div class="actions">
      <a class="btn" href="/">Browse working checkers</a>
    </div>
  </section>
</main>

<footer class="site-footer">
  <div class="shell footer-inner">
    <span>&copy; <?= date('Y') ?> <?= htmlspecialchars(strtoupper($site), ENT_QUOTES) ?> checker</span>
    <span><a href="/">All checkers</a></span>
  </div>
</footer>
</body>
</html>
