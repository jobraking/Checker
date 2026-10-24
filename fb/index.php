<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/guard.php';
require_once __DIR__ . '/fb-client.php';

header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');

$user = current_user();
$csrf = csrf_token();
$bulkResults = null;
$bulkError = null;

function fbh(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function fbStatusLabel(string $status): string
{
    return match ($status) {
        'valid' => 'Valid',
        'checkpoint' => 'Checkpoint',
        'invalid' => 'Invalid',
        'error' => 'Error',
        'malformed' => 'Malformed',
        default => ucfirst($status),
    };
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        http_response_code(400);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'error' => 'Invalid CSRF token. Reload the page.']);
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'check') {
        $line = trim((string) ($_POST['line'] ?? ''));
        $parsed = fbParseCredentialLine($line);
        header('Content-Type: application/json; charset=utf-8');
        if ($parsed === null) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Use username|password.']);
            exit;
        }
        session_write_close();
        set_time_limit(30);
        $result = fbCheckCredential($parsed[0], $parsed[1]);
        echo json_encode(['ok' => true, 'result' => $result], JSON_UNESCAPED_SLASHES);
        exit;
    }

    if ($action === 'bulk') {
        $raw = (string) ($_POST['bulk'] ?? '');
        $lines = preg_split('/\r?\n/', $raw) ?: [];
        $records = [];
        foreach ($lines as $line) {
            $parsed = fbParseCredentialLine($line);
            if ($parsed === null) {
                continue;
            }
            $records[] = $parsed;
            if (count($records) >= 100) {
                break;
            }
        }
        if ($records === []) {
            $bulkError = 'No valid username|password rows were found.';
        } else {
            session_write_close();
            set_time_limit(180);
            $bulkResults = [];
            foreach ($records as $index => $record) {
                $bulkResults[] = fbCheckCredential($record[0], $record[1]);
                if ($index < count($records) - 1) {
                    usleep(150000);
                }
            }
        }
    }
}

$rows = $bulkResults ?? [];
$bulkValue = (string) ($_POST['bulk'] ?? '');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FB Checker - D3M09 ACCOUNT CHECKER</title>
<style>
:root{
  --bg:#f3f7fc;
  --surface:#ffffff;
  --ink:#122033;
  --muted:#617286;
  --line:#dbe6f2;
  --brand:#1877f2;
  --brand-dark:#0d5bc4;
  --green:#0d8f68;
  --amber:#a56200;
  --red:#b42318;
  --radius:16px;
  --shadow:0 18px 50px rgba(15,42,70,.09);
}
*{box-sizing:border-box}
html,body{margin:0;min-height:100%}
body{
  background:
    radial-gradient(900px 480px at 100% -10%, #dcecff 0%, transparent 58%),
    radial-gradient(700px 460px at 0% 20%, #e9fff8 0%, transparent 52%),
    var(--bg);
  color:var(--ink);
  font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;
  line-height:1.5;
}
a{color:inherit}
.shell{width:min(calc(100% - 32px),1120px);margin:0 auto}
.topbar{
  position:sticky;top:0;z-index:10;
  background:rgba(255,255,255,.86);
  backdrop-filter:blur(16px);
  border-bottom:1px solid rgba(219,230,242,.85);
}
.topbar-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;min-height:72px}
.brand{display:flex;align-items:center;gap:11px;text-decoration:none;min-width:0}
.brand-mark{
  width:42px;height:42px;border-radius:13px;display:grid;place-items:center;flex:0 0 auto;
  background:linear-gradient(145deg,#1877f2,#0b5fbe);color:#fff;font-weight:900;font-size:1.15rem;
  box-shadow:0 9px 22px rgba(24,119,242,.28);
}
.brand-copy{display:flex;flex-direction:column;min-width:0}
.brand-copy strong{font-size:1rem;letter-spacing:-.02em}
.brand-copy small{font-size:.67rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);font-weight:800}
.top-actions{display:flex;align-items:center;gap:9px;flex-wrap:wrap;justify-content:flex-end}
.user-pill,.count-pill{
  display:inline-flex;align-items:center;gap:7px;padding:7px 11px;border-radius:999px;
  border:1px solid var(--line);background:#fff;color:var(--muted);font-size:.75rem;font-weight:750;
}
.count-dot{width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 0 4px rgba(13,143,104,.11)}
.btn{
  display:inline-flex;align-items:center;justify-content:center;gap:8px;border:0;border-radius:10px;
  padding:10px 14px;font:inherit;font-size:.8rem;font-weight:800;text-decoration:none;cursor:pointer;
  transition:transform .16s ease,box-shadow .16s ease,background .16s ease,border-color .16s ease;
}
.btn-primary{background:var(--brand);color:#fff;box-shadow:0 8px 20px rgba(24,119,242,.22)}
.btn-primary:hover{background:var(--brand-dark);transform:translateY(-1px)}
.btn-ghost{background:#fff;color:var(--ink);border:1px solid var(--line)}
.btn-ghost:hover{border-color:#bfd4ee;background:#f8fbff;transform:translateY(-1px)}
.btn:disabled{opacity:.58;cursor:progress;transform:none;box-shadow:none}
main{padding:34px 0 48px}
.hero{display:grid;grid-template-columns:1.25fr .75fr;gap:22px;align-items:end;margin-bottom:24px}
.eyebrow{margin:0 0 8px;font-size:.7rem;font-weight:850;letter-spacing:.16em;text-transform:uppercase;color:var(--brand)}
h1{margin:0;font-size:clamp(2rem,5vw,3.4rem);line-height:1.02;letter-spacing:-.055em;max-width:14ch}
h1 span{color:var(--brand)}
.lede{margin:13px 0 0;color:var(--muted);font-size:.98rem;max-width:66ch}
.hero-card{
  background:rgba(255,255,255,.76);border:1px solid var(--line);border-radius:var(--radius);
  padding:17px 18px;box-shadow:var(--shadow);backdrop-filter:blur(12px);
}
.hero-card strong{display:block;font-size:.82rem;margin-bottom:5px}
.hero-card p{margin:0;color:var(--muted);font-size:.79rem}
.panel{
  background:var(--surface);border:1px solid var(--line);border-radius:20px;
  box-shadow:var(--shadow);overflow:hidden;
}
.panel-head{
  display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;
  padding:22px 24px 0;
}
.panel-head h2{margin:0;font-size:1.08rem;letter-spacing:-.02em}
.panel-head p{margin:4px 0 0;color:var(--muted);font-size:.82rem}
.panel-body{padding:20px 24px 24px}
.field{margin-bottom:16px}
.field label{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:7px;font-size:.78rem;font-weight:800}
.field label span{color:var(--muted);font-weight:650;font-size:.72rem}
textarea{
  width:100%;min-height:190px;resize:vertical;border:1.5px solid var(--line);border-radius:14px;
  padding:13px 14px;background:#fbfdff;color:var(--ink);font:0.83rem/1.55 ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;
  outline:none;transition:border-color .16s ease,box-shadow .16s ease,background .16s ease;
}
textarea:focus{border-color:var(--brand);box-shadow:0 0 0 4px rgba(24,119,242,.1);background:#fff}
textarea::placeholder{color:#9aaab9}
.actions{display:flex;align-items:center;gap:9px;flex-wrap:wrap}
.hint{margin:9px 0 0;color:var(--muted);font-size:.74rem}
.hint code{background:#eef4fb;border:1px solid #dfeaf7;border-radius:5px;padding:1px 4px;color:#2b4c70}
.status{margin-top:14px;padding:10px 12px;border-radius:10px;border:1px solid var(--line);background:#f8fbff;color:var(--muted);font-size:.78rem}
.status[hidden]{display:none}
.status.error{background:#fff6f5;border-color:#f3d3ce;color:var(--red)}
.status.ok{background:#f3fdf9;border-color:#ccefe2;color:var(--green)}
.table-wrap{margin-top:20px;border:1px solid var(--line);border-radius:14px;overflow:auto;background:#fff}
.table-wrap[hidden]{display:none}
table{width:100%;border-collapse:collapse;min-width:760px}
th{
  text-align:left;padding:11px 13px;background:#f7fafe;border-bottom:1px solid var(--line);
  color:#6a7b8e;font-size:.66rem;letter-spacing:.11em;text-transform:uppercase;white-space:nowrap;
}
td{padding:11px 13px;border-bottom:1px solid #edf3f9;vertical-align:top;font-size:.78rem;word-break:break-word}
tbody tr:last-child td{border-bottom:0}
tbody tr:hover{background:#fbfdff}
.mono{font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:.75rem}
.pill{display:inline-flex;align-items:center;border-radius:999px;padding:4px 8px;font-size:.65rem;font-weight:850;letter-spacing:.04em;text-transform:uppercase;white-space:nowrap}
.pill.valid{background:#e6fbf3;color:#0b7a5c;border:1px solid #c9f0e1}
.pill.checkpoint{background:#fff7e8;color:var(--amber);border:1px solid #f6e2b8}
.pill.invalid{background:#f1f4f8;color:#607287;border:1px solid #e1e8f0}
.pill.error,.pill.malformed{background:#fff1f0;color:var(--red);border:1px solid #f3d0cc}
.cookie{display:block;max-width:420px;padding:7px 8px;border:1px solid #dfeaf7;border-radius:8px;background:#f6faff;color:#254766;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;font-size:.7rem;line-height:1.45;word-break:break-all;white-space:normal}
.muted{color:var(--muted)}
.empty{padding:26px 12px;text-align:center;color:var(--muted);font-size:.82rem}
.footer{padding:0 0 28px;color:#7b8b9c;font-size:.73rem;text-align:center}
.noscript{margin:12px 0 0;padding:10px 12px;border-radius:10px;background:#fff7e8;border:1px solid #f6e2b8;color:#7c5200;font-size:.78rem}
@media(max-width:860px){
  .hero{grid-template-columns:1fr;align-items:start}
  h1{max-width:18ch}
  .topbar-inner{align-items:flex-start;padding:12px 0}
  .top-actions{justify-content:flex-end}
}
@media(max-width:620px){
  .shell{width:min(calc(100% - 22px),1120px)}
  .brand-copy small{display:none}
  .user-pill{display:none}
  .top-actions{gap:6px}
  .panel-head,.panel-body{padding-left:16px;padding-right:16px}
  h1{font-size:2.2rem}
  .hero-card{display:none}
  .actions .btn{flex:1}
  .actions .btn-ghost{flex:0 0 auto}
}
@media(prefers-reduced-motion:reduce){
  *{scroll-behavior:auto!important;transition:none!important;animation:none!important}
}
</style>
</head>
<body>
<header class="topbar">
  <div class="shell topbar-inner">
    <a class="brand" href="/showcase.php">
      <span class="brand-mark">f</span>
      <span class="brand-copy">
        <strong>FB Checker</strong>
        <small>Facebook account checker</small>
      </span>
    </a>
    <div class="top-actions">
      <span class="count-pill"><span class="count-dot"></span>Bulk</span>
      <?php if ($user !== null): ?>
      <span class="user-pill"><?= fbh((string) $user['username']) ?></span>
      <?php endif; ?>
      <a class="btn btn-ghost" href="/showcase.php">Showcase</a>
      <a class="btn btn-ghost" href="/logout.php">Sign out</a>
    </div>
  </div>
</header>

<main class="shell">
  <section class="hero">
    <div>
      <p class="eyebrow">Bulk account check</p>
      <h1>Check Facebook <span>credentials</span>.</h1>
      <p class="lede">Paste one credential per line, exactly like the other checker nodes. Valid logins return the session cookie for the account.</p>
    </div>
    <div class="hero-card">
      <strong>Input format</strong>
      <p><code>username|password</code> per line.<br>Pipe or space separated.</p>
    </div>
  </section>

  <section class="panel">
    <div class="panel-head">
      <div>
        <h2>Bulk check</h2>
        <p>Up to 100 rows per run. Comments and header rows are ignored.</p>
      </div>
      <span class="count-pill" id="bulkCount">0 lines</span>
    </div>
    <div class="panel-body">
      <form method="post" action="index.php" id="bulkForm" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= fbh($csrf) ?>">
        <input type="hidden" name="action" value="bulk">
        <div class="field">
          <label for="bulkInput">Accounts <span>username|password</span></label>
          <textarea id="bulkInput" name="bulk" spellcheck="false" placeholder="email@example.com|password123&#10;phone_number|another_password"><?= fbh($bulkValue) ?></textarea>
        </div>
        <div class="actions">
          <button class="btn btn-primary" type="submit" id="checkBtn">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5L20 7"/></svg>
            <span>Check all</span>
          </button>
          <button class="btn btn-ghost" type="button" id="clearBtn">Clear</button>
        </div>
        <p class="hint">Accepted examples: <code>user|pass</code> or <code>login-url user pass</code>.</p>
      </form>

      <div class="status" id="status" role="status" aria-live="polite"<?= ($bulkError === null && $bulkResults === null) ? ' hidden' : '' ?>>
        <?php if ($bulkError !== null): ?>
          <?= fbh($bulkError) ?>
        <?php elseif ($bulkResults !== null): ?>
          Checked <?= count($bulkResults) ?> record(s).
        <?php endif; ?>
      </div>

      <div class="table-wrap" id="resultsWrap"<?= $rows === [] ? ' hidden' : '' ?>>
        <table>
          <thead>
            <tr><th>#</th><th>Account</th><th>Password</th><th>Status</th><th>Details</th><th>Cookie</th></tr>
          </thead>
          <tbody id="resultsBody">
            <?php foreach ($rows as $i => $row): ?>
            <tr>
              <td class="muted"><?= $i + 1 ?></td>
              <td class="mono"><?= fbh($row['username']) ?></td>
              <td class="mono"><?= fbh($row['password']) ?></td>
              <td><span class="pill <?= fbh($row['status']) ?>"><?= fbh(fbStatusLabel($row['status'])) ?></span></td>
              <td><?= fbh($row['message']) ?><?php if ($row['http'] > 0): ?> <span class="muted">(HTTP <?= (int) $row['http'] ?>)</span><?php endif; ?></td>
              <td><?php if (is_string($row['cookie']) && $row['cookie'] !== ''): ?><code class="cookie"><?= fbh($row['cookie']) ?></code><?php else: ?><span class="muted">-</span><?php endif; ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="empty" id="emptyState"<?= $rows !== [] ? ' hidden' : '' ?>>No checks yet. Paste credentials and run the bulk check.</div>
      <noscript><div class="noscript">JavaScript is disabled. The form still submits all rows, but progress and live result rows need JavaScript.</div></noscript>
    </div>
  </section>
</main>

<div class="shell footer">D3M09 checker platform</div>

<script>
(function () {
  const form = document.getElementById('bulkForm');
  const input = document.getElementById('bulkInput');
  const button = document.getElementById('checkBtn');
  const clear = document.getElementById('clearBtn');
  const status = document.getElementById('status');
  const wrap = document.getElementById('resultsWrap');
  const body = document.getElementById('resultsBody');
  const empty = document.getElementById('emptyState');
  const count = document.getElementById('bulkCount');
  const csrf = <?= json_encode($csrf, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

  function lines() {
    return (input.value || '').split(/\r?\n/).map(function (line) { return line.trim(); }).filter(function (line) {
      return line !== '' && line.charAt(0) !== '#';
    });
  }

  function refreshCount() {
    const n = lines().length;
    count.textContent = n + (n === 1 ? ' line' : ' lines');
  }

  function label(status) {
    const names = { valid: 'Valid', checkpoint: 'Checkpoint', invalid: 'Invalid', error: 'Error', malformed: 'Malformed' };
    return names[status] || status;
  }

  function addRow(result, index) {
    const tr = document.createElement('tr');
    const number = document.createElement('td');
    number.className = 'muted';
    number.textContent = String(index);

    const account = document.createElement('td');
    account.className = 'mono';
    account.textContent = result.username || '';

    const password = document.createElement('td');
    password.className = 'mono';
    password.textContent = result.password || '';

    const state = document.createElement('td');
    const pill = document.createElement('span');
    pill.className = 'pill ' + (result.status || 'error');
    pill.textContent = label(result.status || 'error');
    state.appendChild(pill);

    const details = document.createElement('td');
    details.textContent = result.message || '';
    if (result.http) {
      const http = document.createElement('span');
      http.className = 'muted';
      http.textContent = ' (HTTP ' + result.http + ')';
      details.appendChild(http);
    }

    const cookieCell = document.createElement('td');
    if (result.cookie) {
      const code = document.createElement('code');
      code.className = 'cookie';
      code.textContent = result.cookie;
      cookieCell.appendChild(code);
    } else {
      const dash = document.createElement('span');
      dash.className = 'muted';
      dash.textContent = '-';
      cookieCell.appendChild(dash);
    }

    tr.appendChild(number);
    tr.appendChild(account);
    tr.appendChild(password);
    tr.appendChild(state);
    tr.appendChild(details);
    tr.appendChild(cookieCell);
    body.appendChild(tr);
  }

  async function checkLine(line, index) {
    const response = await fetch('index.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ action: 'check', line: line, csrf: csrf })
    });
    let data;
    try {
      data = await response.json();
    } catch (err) {
      data = { ok: false, error: 'Unexpected response from server.' };
    }
    if (!data.ok) {
      addRow({ username: line, password: '', status: 'malformed', message: data.error || 'Invalid row' }, index);
      return;
    }
    addRow(data.result, index);
  }

  async function run() {
    const rows = lines();
    if (rows.length === 0) {
      status.hidden = false;
      status.className = 'status error';
      status.textContent = 'Paste at least one username|password row.';
      return;
    }
    if (rows.length > 100) {
      status.hidden = false;
      status.className = 'status error';
      status.textContent = 'Limit is 100 rows per run.';
      return;
    }

    button.disabled = true;
    button.querySelector('span').textContent = 'Checking';
    status.hidden = false;
    status.className = 'status';
    status.textContent = 'Starting ' + rows.length + ' check(s)...';
    body.innerHTML = '';
    wrap.hidden = false;
    empty.hidden = true;

    let done = 0;
    let next = 0;
    const total = rows.length;
    const workers = Array.from({ length: Math.min(3, total) }, async function () {
      while (next < total) {
        const position = next++;
        try {
          await checkLine(rows[position], position + 1);
        } catch (err) {
          addRow({ username: rows[position], password: '', status: 'error', message: 'Request failed.' }, position + 1);
        }
        done++;
        status.textContent = 'Checked ' + done + ' of ' + total + '...';
      }
    });

    await Promise.all(workers);
    status.className = 'status ok';
    status.textContent = 'Finished ' + total + ' check(s).';
    button.disabled = false;
    button.querySelector('span').textContent = 'Check all';
  }

  form.addEventListener('submit', function (event) {
    if (!window.fetch) {
      return;
    }
    event.preventDefault();
    run();
  });

  input.addEventListener('input', refreshCount);
  clear.addEventListener('click', function () {
    input.value = '';
    body.innerHTML = '';
    wrap.hidden = true;
    empty.hidden = false;
    status.hidden = true;
    status.textContent = '';
    status.className = 'status';
    refreshCount();
    input.focus();
  });

  refreshCount();
})();
</script>
</body>
</html>
