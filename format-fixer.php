<?php
declare(strict_types=1);

function parseLine(string $line): ?array
{
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) {
        return null;
    }
    if (stripos($line, 'username') !== false && stripos($line, 'password') !== false) {
        return null;
    }
    // colon separated (handle URLs with :// by splitting from the right)
    if (str_contains($line, ':')) {
        if (preg_match('#^https?://#i', $line)) {
            $last = strrpos($line, ':');
            $second = $last !== false ? strrpos(substr($line, 0, $last), ':') : false;
            if ($last !== false && $second !== false) {
                $user = trim(substr($line, $second + 1, $last - $second - 1));
                $pass = trim(substr($line, $last + 1));
                if ($user !== '' && $pass !== '' && strtolower($pass) !== 'null' && strtolower($user) !== 'null' && !preg_match('/^\d{3,4}$/', $user)) {
                    return [$user, $pass];
                }
            }
        } else {
            $parts = explode(':', $line, 2);
            $parts = array_map('trim', $parts);
            if (count($parts) === 2 && $parts[0] !== '' && $parts[1] !== '' && strtolower($parts[1]) !== 'null' && strtolower($parts[0]) !== 'null' && !preg_match('/^\d{3,4}$/', $parts[0])) {
                return [$parts[0], $parts[1]];
            }
        }
    }
    // pipe separated
    if (str_contains($line, '|')) {
        $parts = array_values(array_filter(array_map('trim', explode('|', $line)), static fn($x) => $x !== ''));
        if (count($parts) >= 3 && preg_match('#[./]#', $parts[0])) {
            if (strtolower($parts[2]) !== 'null' && strtolower($parts[1]) !== 'null' && !preg_match('/^\d{3,4}$/', $parts[1])) {
                return [$parts[1], $parts[2]];
            }
        }
        if (count($parts) >= 2 && strtolower($parts[1]) !== 'null' && strtolower($parts[0]) !== 'null' && !preg_match('/^\d{3,4}$/', $parts[0])) {
            return [$parts[0], $parts[1]];
        }
    }
    // whitespace separated (optionally URL first)
    $parts = preg_split('/\s+/', $line);
    $parts = array_values(array_filter($parts, static fn($x) => $x !== ''));
    if (count($parts) >= 3 && strtolower($parts[2]) !== 'null' && strtolower($parts[1]) !== 'null' && !preg_match('/^\d{3,4}$/', $parts[1])) {
        return [$parts[1], $parts[2]];
    }
    if (count($parts) === 2 && strtolower($parts[1]) !== 'null' && strtolower($parts[0]) !== 'null' && !preg_match('/^\d{3,4}$/', $parts[0])) {
        return [$parts[0], $parts[1]];
    }
    return null;
}

$result = '';
$input = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = (string) ($_POST['data'] ?? '');
    $lines = preg_split('/\r?\n/', $input);
    $out = [];
    $seen = [];
    foreach ($lines as $line) {
        $parsed = parseLine($line);
        if ($parsed !== null) {
            if (strtolower(trim($parsed[1])) === '*none*') {
                continue;
            }
            if (isset($seen[$parsed[0]])) {
                continue;
            }
            $seen[$parsed[0]] = true;
            $out[] = $parsed[0] . '|' . $parsed[1];
        }
    }
    $result = implode("\n", $out);
}
?>
<?php
$inputLineCount = 0;
if ($input !== '') {
    $inputLineCount = count(array_filter(preg_split('/\r?\n/', $input), static fn($l) => trim($l) !== ''));
}
$outputLineCount = $result === '' ? 0 : substr_count($result, "\n") + 1;
$removedCount = max(0, $inputLineCount - $outputLineCount);
$didPost = $_SERVER['REQUEST_METHOD'] === 'POST';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>D3M09 ACCOUNT CHECKER - FORMAT FIXER</title>
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
.btn{
  display:inline-flex;align-items:center;justify-content:center;gap:8px;
  padding:11px 17px;border-radius:12px;text-decoration:none;
  font-weight:750;font-size:.9rem;border:1px solid transparent;
  transition:transform .18s ease,box-shadow .18s ease,background .18s ease,border-color .18s ease;
  white-space:nowrap;cursor:pointer;font-family:inherit;
}
.btn-primary{background:var(--brand);color:#fff;box-shadow:0 10px 22px rgba(8,119,237,.24)}
.btn-primary:hover{background:var(--brand-dark);transform:translateY(-1px);box-shadow:0 14px 28px rgba(8,119,237,.3)}
.btn-secondary{background:#fff;color:var(--ink);border-color:var(--line)}
.btn-secondary:hover{border-color:#b9d6f5;transform:translateY(-1px);box-shadow:0 10px 22px rgba(15,42,70,.08)}
.btn-ghost{background:transparent;color:var(--muted);border-color:transparent;padding-inline:12px}
.btn-ghost:hover{background:#eef6ff;color:#0b63ce}
.btn svg{width:17px;height:17px}
main{padding:clamp(28px,5vw,52px) 0 64px}
.hero{max-width:760px;margin-bottom:clamp(24px,4vw,36px)}
.eyebrow{
  display:inline-flex;align-items:center;gap:8px;margin:0 0 14px;
  padding:7px 12px;border-radius:999px;background:#fff;border:1px solid var(--line);
  color:#0b63ce;font-size:.76rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;
  box-shadow:0 6px 16px rgba(15,42,70,.04);
}
.eyebrow::before{content:"";width:8px;height:8px;border-radius:50%;background:var(--mint);box-shadow:0 0 0 4px rgba(0,184,148,.13)}
.hero h1{margin:0;font-size:clamp(2.1rem,4.6vw,3.5rem);line-height:1.04;letter-spacing:-.045em;max-width:18ch}
.hero h1 span{color:var(--brand)}
.lede{margin:16px 0 0;max-width:62ch;color:var(--muted);font-size:clamp(1rem,1.25vw,1.08rem);line-height:1.65}
.workspace{display:grid;grid-template-columns:1fr 1fr;gap:18px;align-items:start}
.panel{
  background:rgba(255,255,255,.92);border:1px solid var(--line);
  border-radius:20px;padding:clamp(16px,2vw,22px);box-shadow:var(--shadow);
}
.panel-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:10px;flex-wrap:wrap}
.panel-title{font-size:.8rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--muted)}
.badge{
  display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;
  background:#eef6ff;border:1px solid #d7e9ff;color:#0b63ce;
  font-size:.74rem;font-weight:750;white-space:nowrap;
}
.badge.mint{background:#e9fbf6;border-color:#c9f2e6;color:#037a5f}
textarea{
  width:100%;min-height:340px;resize:vertical;
  border:1.5px solid var(--line);border-radius:14px;padding:14px;
  font:0.92rem/1.55 ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;
  background:#fbfdff;color:var(--ink);outline:none;
  transition:border-color .18s ease,box-shadow .18s ease;
}
textarea:focus{border-color:var(--brand);box-shadow:0 0 0 4px rgba(8,119,237,.12)}
textarea::placeholder{color:#93a3b5}
.actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px}
.hint{margin:10px 2px 0;color:var(--muted);font-size:.8rem;line-height:1.45}
.hint code{background:#eef6ff;border:1px solid #d7e9ff;border-radius:6px;padding:1px 6px;font-size:.76rem;color:#0b63ce}
.result-empty{
  min-height:340px;border:1.5px dashed #c9d8e8;border-radius:14px;
  display:grid;place-items:center;text-align:center;color:var(--muted);
  background:#fbfdff;padding:24px;line-height:1.55;
}
.result-empty strong{color:var(--ink);display:block;margin-bottom:3px}
.result-empty code{background:#eef6ff;border:1px solid #d7e9ff;border-radius:6px;padding:1px 6px;font-size:.82rem;color:#0b63ce}
.stat-row{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.stat{
  display:inline-flex;align-items:center;gap:6px;padding:7px 11px;border-radius:999px;
  background:#f8fbff;border:1px solid var(--line);color:var(--muted);font-size:.76rem;font-weight:650;
}
.stat strong{color:var(--ink);font-weight:800}
footer{border-top:1px solid var(--line);background:rgba(255,255,255,.7);margin-top:24px}
.footer-inner{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;padding:24px 0;color:var(--muted);font-size:.82rem}
.footer-inner a{text-decoration:none;font-weight:700;color:var(--ink)}
.footer-inner a:hover{color:var(--brand)}
@media(max-width:900px){.workspace{grid-template-columns:1fr}.panel{min-width:0}}
@media(max-width:640px){
  .shell{width:min(calc(100% - 28px),1180px)}
  .header-inner{min-height:68px;padding:10px 0;gap:12px}
  .brand strong{font-size:1rem}
  .brand small{font-size:.68rem}
  .header-actions{width:100%;justify-content:space-between}
  .hero h1{font-size:clamp(2rem,9vw,2.7rem)}
  .lede{font-size:.98rem}
  .panel{padding:14px;border-radius:16px}
  textarea,.result-empty{min-height:280px}
  .actions .btn{flex:1;justify-content:center}
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
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15h6"/><path d="M9 11h2"/></svg>
      </span>
      <span>
        <strong>Format Fixer</strong>
        <small>Credential formatter</small>
      </span>
    </a>
    <div class="header-actions">
      <a class="btn btn-ghost" href="/">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="m11 18-6-6 6-6"/></svg>
        Back to showcase
      </a>
    </div>
  </div>
</header>

<main class="shell">
  <section class="hero">
    <p class="eyebrow">Utility</p>
    <h1>Clean up <span>credential lists</span> in one pass.</h1>
    <p class="lede">Paste any supported format and get a deduplicated <code>username|password</code> list. Nothing is stored or sent anywhere else.</p>
  </section>

  <form method="post" id="fixForm" class="workspace">
    <section class="panel">
      <div class="panel-head">
        <span class="panel-title">Input</span>
        <span class="badge" id="inputCount"><?= $inputLineCount ?> lines</span>
      </div>
      <textarea name="data" id="fixInput" placeholder="user:pass&#10;user|pass&#10;https://site.com/login user pass" oninput="updateInputCount()"><?= htmlspecialchars($input, ENT_QUOTES) ?></textarea>
      <div class="actions">
        <button class="btn btn-primary" type="submit">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
          Fix format
        </button>
        <button class="btn btn-secondary" type="button" onclick="clearInput()">Clear input</button>
        <label class="btn btn-secondary" for="fileInput">Load file</label>
        <input type="file" id="fileInput" accept=".txt,text/plain" hidden onchange="loadFile(this)">
      </div>
      <p class="hint">Accepted: <code>user:pass</code>, <code>user|pass</code>, or <code>url user pass</code>. Duplicate usernames are removed.</p>
    </section>

    <section class="panel">
      <div class="panel-head">
        <span class="panel-title">Output</span>
        <span class="badge mint" id="outputCount"><?= $outputLineCount ?> lines</span>
      </div>
      <?php if ($didPost && $result !== ''): ?>
        <textarea id="resultBox" readonly><?= htmlspecialchars($result, ENT_QUOTES) ?></textarea>
        <div class="actions">
          <button class="btn btn-primary" type="button" id="copyBtn" onclick="copyOutput(this)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            Copy output
          </button>
          <button class="btn btn-secondary" type="button" onclick="downloadOutput()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>
            Download .txt
          </button>
        </div>
        <div class="stat-row">
          <span class="stat">Input <strong><?= $inputLineCount ?></strong></span>
          <span class="stat">Output <strong><?= $outputLineCount ?></strong></span>
          <span class="stat">Removed <strong><?= $removedCount ?></strong></span>
        </div>
      <?php elseif ($didPost): ?>
        <div class="result-empty">
          <div><strong>No valid credentials found.</strong>Check the format and try again.</div>
        </div>
        <div class="stat-row">
          <span class="stat">Input <strong><?= $inputLineCount ?></strong></span>
          <span class="stat">Output <strong>0</strong></span>
        </div>
      <?php else: ?>
        <div class="result-empty">
          <div><strong>Your output appears here.</strong>Formatted as <code>username|password</code>.</div>
        </div>
      <?php endif; ?>
    </section>
  </form>
</main>

<footer>
  <div class="shell footer-inner">
    <span>&copy; <?= date('Y') ?> Checker Showcase</span>
    <span><a href="/">All checkers</a> &nbsp;·&nbsp; Format fixer</span>
  </div>
</footer>

<script>
function updateInputCount() {
  var el = document.getElementById('fixInput');
  var badge = document.getElementById('inputCount');
  if (!el || !badge) return;
  var lines = el.value.split(/\r?\n/).filter(function (line) { return line.trim() !== ''; });
  badge.textContent = lines.length + ' line' + (lines.length === 1 ? '' : 's');
}
function clearInput() {
  var el = document.getElementById('fixInput');
  if (el) { el.value = ''; el.focus(); updateInputCount(); }
}
function loadFile(input) {
  var file = input.files && input.files[0];
  if (!file) return;
  var reader = new FileReader();
  reader.onload = function () {
    var el = document.getElementById('fixInput');
    if (el) { el.value = String(reader.result || ''); updateInputCount(); el.focus(); }
    input.value = '';
  };
  reader.readAsText(file);
}
function copyOutput(btn) {
  var box = document.getElementById('resultBox');
  if (!box) return;
  var text = box.value;
  var done = function () {
    var old = btn.innerHTML;
    btn.textContent = 'Copied!';
    setTimeout(function () { btn.innerHTML = old; }, 1400);
  };
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(text).then(done, function () { fallbackCopy(box, done); });
  } else {
    fallbackCopy(box, done);
  }
}
function fallbackCopy(box, done) {
  box.focus();
  box.select();
  try { document.execCommand('copy'); done(); } catch (e) {}
  box.setSelectionRange(0, 0);
}
function downloadOutput() {
  var box = document.getElementById('resultBox');
  if (!box) return;
  var blob = new Blob([box.value], { type: 'text/plain' });
  var a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = 'fixed.txt';
  a.click();
  setTimeout(function () { URL.revokeObjectURL(a.href); }, 1000);
}
</script>
</body>
</html>
