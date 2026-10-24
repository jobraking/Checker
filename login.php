<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

start_app_session();

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        $error = 'Invalid request.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        if ($username === '' || $password === '') {
            $error = 'Username and password are required.';
        } else {
            try {
                $stmt = db()->prepare('SELECT id, username, password_hash FROM users WHERE username = ?');
                $stmt->execute([$username]);
                $row = $stmt->fetch();
                if (is_array($row) && password_verify($password, (string) $row['password_hash'])) {
                    login_user((int) $row['id'], (string) $row['username']);
                    header('Location: /showcase.php');
                    exit;
                }
                $error = 'Invalid credentials.';
            } catch (Throwable $e) {
                $error = 'Database unavailable.';
            }
        }
    }
}

$token = csrf_token();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign in · Checker Showcase</title>
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
body{
  margin:0;
  font-family:ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
  color:var(--ink);
  background:
    radial-gradient(900px 500px at 10% -10%, #dcecff 0%, transparent 60%),
    radial-gradient(700px 420px at 95% 0%, #d9fff9 0%, transparent 55%),
    var(--bg);
  min-height:100vh;
  display:flex;
  flex-direction:column;
  -webkit-font-smoothing:antialiased;
}
a{color:inherit}
.shell{width:min(1180px,calc(100% - 40px));margin-inline:auto}
.site-header{
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
  cursor:pointer;
}
.btn-primary{background:var(--brand);color:#fff;box-shadow:0 10px 22px rgba(8,119,237,.24)}
.btn-primary:hover{background:var(--brand-dark);transform:translateY(-1px);box-shadow:0 14px 28px rgba(8,119,237,.3)}
.btn-ghost{background:#fff;color:var(--ink);border-color:var(--line)}
.btn-ghost:hover{background:#f8fbff;border-color:#b9d6f5;transform:translateY(-1px)}
.btn svg{width:17px;height:17px}
main{flex:1;display:grid;place-items:center;padding:clamp(32px,6vw,72px) 0}
.auth{
  display:grid;grid-template-columns:minmax(0,1.15fr) minmax(320px,.85fr);
  gap:clamp(24px,4vw,56px);align-items:center;width:100%;
}
.auth-copy{padding:clamp(4px,2vw,16px) 0}
.eyebrow{
  display:inline-flex;align-items:center;gap:8px;margin:0 0 14px;
  padding:7px 12px;border-radius:999px;background:#fff;border:1px solid var(--line);
  color:#0b63ce;font-size:.76rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;
  box-shadow:0 6px 16px rgba(15,42,70,.04);
}
.eyebrow::before{content:"";width:8px;height:8px;border-radius:50%;background:var(--mint);box-shadow:0 0 0 4px rgba(0,184,148,.13)}
.auth-copy h1{margin:0;font-size:clamp(2.2rem,5vw,4.1rem);line-height:1.02;letter-spacing:-.045em;max-width:13ch}
.auth-copy h1 span{color:var(--brand)}
.lede{margin:18px 0 0;max-width:56ch;color:var(--muted);font-size:clamp(1rem,1.3vw,1.12rem);line-height:1.65}
.feature-list{list-style:none;display:flex;flex-wrap:wrap;gap:8px;margin:22px 0 0;padding:0}
.feature-list li{
  display:inline-flex;align-items:center;gap:7px;
  padding:7px 12px;border-radius:999px;background:rgba(255,255,255,.85);
  border:1px solid var(--line);color:var(--muted);font-size:.8rem;font-weight:650;
}
.feature-list li::before{content:"";width:6px;height:6px;border-radius:50%;background:var(--brand);flex:none}
.auth-card{
  background:rgba(255,255,255,.9);border:1px solid var(--line);
  border-radius:22px;padding:clamp(20px,2.6vw,30px);
  box-shadow:var(--shadow);position:relative;overflow:hidden;
}
.auth-card::after{
  content:"";position:absolute;inset:auto -40px -70px auto;
  width:180px;height:180px;border-radius:50%;
  background:radial-gradient(circle,rgba(0,184,148,.16),transparent 70%);
  pointer-events:none;
}
.card-head{margin-bottom:20px}
.card-head h2{margin:0;font-size:1.25rem;letter-spacing:-.025em}
.card-head p{margin:6px 0 0;color:var(--muted);font-size:.86rem}
.error{
  display:flex;align-items:center;gap:9px;
  background:#fff0f0;color:#9b1c1c;border:1px solid #ffd9d9;
  padding:10px 13px;border-radius:12px;margin-bottom:16px;
  font-size:.86rem;font-weight:650;
}
.error::before{content:"";width:7px;height:7px;border-radius:50%;background:#e04343;flex:none}
.field{position:relative;margin-bottom:14px}
.field label{
  display:block;margin-bottom:7px;
  font-size:.76rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);
}
.field input{
  width:100%;padding:15px 14px;border:1.5px solid var(--line);border-radius:14px;
  background:#fff;font:inherit;font-size:1rem;color:var(--ink);
  transition:border-color .18s ease,box-shadow .18s ease;
}
.field input::placeholder{color:#93a3b5}
.field input:focus{outline:0;border-color:var(--brand);box-shadow:0 0 0 4px rgba(8,119,237,.12)}
.field.has-toggle input{padding-right:74px}
.toggle{
  position:absolute;right:8px;bottom:8px;
  border:0;background:transparent;color:var(--brand);
  font:inherit;font-size:.76rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;
  padding:7px 8px;border-radius:8px;cursor:pointer;
}
.toggle:hover{background:#eef6ff}
.auth-card .btn-primary{width:100%;margin-top:6px;padding:14px;font-size:.95rem}
.card-foot{margin:16px 0 0;text-align:center;color:var(--muted);font-size:.86rem}
.card-foot a{color:var(--brand);font-weight:750;text-decoration:none}
.card-foot a:hover{text-decoration:underline}
footer{border-top:1px solid var(--line);background:rgba(255,255,255,.7)}
.footer-inner{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;padding:24px 0;color:var(--muted);font-size:.82rem}
.footer-inner a{text-decoration:none;font-weight:700;color:var(--ink)}
.footer-inner a:hover{color:var(--brand)}
@media(max-width:860px){
  .auth{grid-template-columns:1fr;gap:26px}
  .auth-copy h1{max-width:16ch}
  .auth-card{max-width:620px;width:100%}
}
@media(max-width:640px){
  .shell{width:min(calc(100% - 28px),1180px)}
  .header-inner{min-height:68px;padding:10px 0;gap:12px}
  .brand strong{font-size:1rem}
  .brand small{font-size:.68rem}
  .header-actions{width:100%;justify-content:space-between}
  .count-pill{order:2}
  .btn-ghost{order:1;flex:1}
  .auth-copy h1{font-size:clamp(2rem,9vw,2.8rem)}
  .lede{font-size:.98rem}
}
@media(prefers-reduced-motion:reduce){
  *{transition:none!important;animation:none!important}
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
      <span class="count-pill"><span class="count-dot" aria-hidden="true"></span>Secure access</span>
      <a class="btn btn-ghost" href="/register.php">Create account</a>
    </div>
  </div>
</header>

<main>
  <div class="shell auth">
    <div class="auth-copy">
      <p class="eyebrow">Welcome back</p>
      <h1>Sign in to your <span>checker workspace.</span></h1>
      <p class="lede">One login unlocks the full directory. Pick up where you left off and open any checker in a single click.</p>
      <ul class="feature-list">
        <li>Every checker in one place</li>
        <li>Fast search</li>
        <li>Session protected</li>
      </ul>
    </div>

    <div class="auth-card">
      <div class="card-head">
        <h2>Account login</h2>
        <p>Enter your credentials to continue.</p>
      </div>
      <?php if ($error !== ''): ?>
      <div class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>
      <form method="post" action="/login.php" autocomplete="on">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
        <div class="field">
          <label for="username">Username</label>
          <input id="username" name="username" type="text" placeholder="Your username" autocomplete="username" required autofocus>
        </div>
        <div class="field has-toggle">
          <label for="password">Password</label>
          <input id="password" name="password" type="password" placeholder="Your password" autocomplete="current-password" required>
          <button class="toggle" type="button" id="pwToggle" aria-controls="password" aria-pressed="false">Show</button>
        </div>
        <button class="btn btn-primary" type="submit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/></svg>
          Sign in
        </button>
      </form>
      <p class="card-foot">No account yet? <a href="/register.php">Create one</a></p>
    </div>
  </div>
</main>

<footer>
  <div class="shell footer-inner">
    <span>&copy; <?= date('Y') ?> Checker Showcase</span>
    <span><a href="/showcase.php">Browse checkers</a></span>
  </div>
</footer>

<script>
(function () {
  var input = document.getElementById('password');
  var toggle = document.getElementById('pwToggle');
  if (!input || !toggle) return;
  toggle.addEventListener('click', function () {
    var visible = input.type === 'text';
    input.type = visible ? 'password' : 'text';
    toggle.textContent = visible ? 'Show' : 'Hide';
    toggle.setAttribute('aria-pressed', visible ? 'false' : 'true');
    input.focus();
  });
})();
</script>
</body>
</html>
