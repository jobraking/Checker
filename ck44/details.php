<?php
declare(strict_types=1);
require_once __DIR__ . '/ck-client.php';


$username = (string) ($_REQUEST['username'] ?? '');
$password = (string) ($_REQUEST['password'] ?? ($_REQUEST['pwd'] ?? ''));

// AJAX: run all live endpoints
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (($_POST['action'] ?? '') === 'check_all_live')) {
    header('Content-Type: application/json; charset=utf-8');
    set_time_limit(0);
    $u = (string) ($_POST['username'] ?? $username);
    $p = (string) ($_POST['password'] ?? $password);
    $merchant = CK_DEFAULT_MERCHANT;
    $r = ckLoginRetry($u, $p, $merchant);
    if (!$r['ok'] || $r['session'] === null) {
        echo json_encode(['status' => 'error', 'message' => (string) ($r['error'] ?? 'login failed')]);
        exit;
    }
    $session = $r['session'];
    $results = [];
    $f = __DIR__ . '/live_endpoints.txt';
    $eps = is_readable($f) ? file($f, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
    foreach ($eps as $ep) {
        $ep = trim($ep);
        if ($ep === '') continue;
        $res = ckHttp('GET', $ep, ['headers' => ckAuthHeaders($session)]);
        $results[$ep] = $res['body'] ?? ['status' => $res['status'], 'raw' => $res['raw']];
    }
    // also profile/balance/referral
    $prof = ckProfile($session);
    $bal = ckBalance($session);
    $ref = ckReferralOverview($session);
    echo json_encode([
        'status' => 'ok',
        'user' => $u,
        'profile' => $prof['body']['value'] ?? null,
        'balance' => $bal['body']['value'] ?? null,
        'referral' => $ref['body']['value'] ?? null,
        'count' => count($results),
        'results' => $results,
    ]);
    exit;
}

// AJAX: run a single endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (($_POST['action'] ?? '') === 'check_one')) {
    header('Content-Type: application/json; charset=utf-8');
    $u = (string) ($_POST['username'] ?? $username);
    $p = (string) ($_POST['password'] ?? $password);
    $ep = (string) ($_POST['endpoint'] ?? '');
    $merchant = CK_DEFAULT_MERCHANT;
    $r = ckLoginRetry($u, $p, $merchant);
    if (!$r['ok'] || $r['session'] === null) {
        echo json_encode(['status' => 'error', 'message' => (string) ($r['error'] ?? 'login failed'), 'endpoint' => $ep]);
        exit;
    }
    $session = $r['session'];
    $res = ckHttp('GET', $ep, ['headers' => ckAuthHeaders($session)]);
    echo json_encode(['status' => 'ok', 'endpoint' => $ep, 'response' => $res['body'] ?? ['status' => $res['status'], 'raw' => $res['raw']]]);
    exit;
}

// AJAX: summary (profile/balance/referral)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (($_POST['action'] ?? '') === 'summary')) {
    header('Content-Type: application/json; charset=utf-8');
    $u = (string) ($_POST['username'] ?? $username);
    $p = (string) ($_POST['password'] ?? $password);
    $merchant = CK_DEFAULT_MERCHANT;
    $r = ckLoginRetry($u, $p, $merchant);
    if (!$r['ok'] || $r['session'] === null) {
        echo json_encode(['status' => 'error', 'message' => (string) ($r['error'] ?? 'login failed')]);
        exit;
    }
    $session = $r['session'];
    $prof = ckProfile($session);
    $bal = ckBalance($session);
    $ref = ckReferralOverview($session);
    $promo = ckHttp('GET', '/wps/relay/MCSFE_getAvailablePromotions', ['headers' => ckAuthHeaders($session)]);
    $tickets = ckHttp('GET', '/wps/relay/PROMOFE_getClaimTickets', ['headers' => ckAuthHeaders($session)]);
    $ticketList = ckHttp('GET', '/wps/relay/PROMOFE_getClaimTicketList', ['headers' => ckAuthHeaders($session)]);
    $rankSalary = ckHttp('GET', '/wps/relay/PROMOFE_getRankSalaryRewardDetail', ['headers' => ckAuthHeaders($session)]);
    $savior = ckHttp('GET', '/wps/relay/PROMOFE_getSaviorPromotion', ['headers' => ckAuthHeaders($session)]);
    echo json_encode([
        'status' => 'ok',
        'profile' => $prof['body']['value'] ?? null,
        'balance' => $bal['body']['value'] ?? null,
        'referral' => $ref['body']['value'] ?? null,
        'available_promotions' => $promo['body'] ?? null,
        'tickets' => $tickets['body']['value'] ?? null,
        'ticket_list' => $ticketList['body']['value'] ?? null,
        'rank_salary' => $rankSalary['body']['value'] ?? null,
        'savior_promotions' => $savior['body']['value'] ?? null,
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>D3M09 ACCOUNT CHECKER - ACCOUNT DETAILS</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
  body{margin:0;background:#0f1115;color:#e6e8ee;font:14px/1.5 ui-sans-serif,system-ui,sans-serif}
  .wrap{max-width:1100px;margin:0 auto;padding:24px}
  h1{font-size:18px;margin:0 0 12px}
  .toolbar{display:flex;gap:10px;align-items:center;margin-bottom:12px;flex-wrap:wrap}
  input{padding:6px 10px;border:1px solid #262b36;border-radius:6px;background:#171a21;color:#e6e8ee}
  button{padding:6px 12px;border:1px solid transparent;border-radius:6px;background:#6ea8fe;color:#fff;cursor:pointer}
  .card{background:#171a21;border:1px solid #262b36;border-radius:8px;padding:12px;margin-bottom:10px}
  .ep{font-family:ui-monospace,monospace;font-size:12px;color:#9aa3b2}
  pre{white-space:pre-wrap;word-break:break-word;margin:6px 0 0;font-size:12px;max-height:240px;overflow:auto;background:#0f1115;border:1px solid #262b36;border-radius:6px;padding:8px}
  .grid{display:grid;grid-template-columns:1fr;gap:8px}
  @media (max-width:640px){ #summary{column-count:1} .grid{grid-template-columns:1fr} }
  .royal{border:2px solid #ffd700;box-shadow:0 0 18px rgba(255,215,0,.35);background:linear-gradient(135deg,#1a1600,#171a21)}
  .royal h2{color:#ffd700;margin:0 0 8px}
  .crown{font-size:20px;margin-right:6px}
</style>
</head>
<body>
<div class="wrap">
  <h1>Account details: <?= htmlspecialchars($username, ENT_QUOTES) ?></h1>
  <div class="toolbar">
    <button id="runBtn">Run all live endpoints</button>
    <input id="filter" placeholder="filter endpoints..." />
    <span class="status" id="status"></span>
  </div>
  <div id="summary" class="card">Loading summary...</div>
</div>
<script>
const username = <?= json_encode($username) ?>;
const password = <?= json_encode($password) ?>;
const ENDPOINTS = <?= json_encode(array_values(array_filter(array_map('trim', file(__DIR__.'/live_endpoints.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))))) ?>;
function run() {
  document.getElementById('status').textContent = 'loading summary...';
  const sfd = new FormData();
  sfd.append('action','summary');
  sfd.append('username', username);
  sfd.append('password', password);
  fetch(window.location.href, { method:'POST', body: sfd }).then(r=>r.json()).then(j=>{
    if (j.status!=='ok') { document.getElementById('status').textContent = j.message||'error'; return; }
    document.getElementById('status').textContent = 'done';
    const summary = document.getElementById('summary');
    const club = j.profile && j.profile.clubLabel ? j.profile.clubLabel.labelName : '';
    const vip = j.profile && j.profile.clubLabel ? (function(c){ const m=(c||'').match(/VIP(\d+)/i); return m?parseInt(m[1],10):(j.profile.clubLabel.rankLevel||0); })(club) : 0;
    const royal = vip > 3;
    const labels = j.profile && j.profile.memberLabel ? j.profile.memberLabel.map(l=>l.labelName).join(', ') : '';
    summary.className = 'card' + (royal ? ' royal' : '');
    summary.innerHTML = (royal ? '<h2><span class="crown">👑</span>VIP ' + vip + ' CLUB Account</h2>' : '') +
      '<div><span class="material-icons">person</span> <b>Account:</b> ' + (j.profile ? (j.profile.account||'') : 'n/a') + '</div>' +
      '<div><span class="material-icons">badge</span> <b>Nickname:</b> ' + (j.profile ? (j.profile.nickname||'') : '') + '</div>' +
      '<div><span class="material-icons">label</span> <b>Labels:</b> ' + labels + '</div>' +
      '<div><span class="material-icons">military_tech</span> <b>Club:</b> ' + club + ' (rank ' + vip + ')</div>' +
      '<div><span class="material-icons">trending_up</span> <b>TotalBetDown:</b> ' + (j.profile && j.profile.clubLabel ? (j.profile.clubLabel.totalBetDown ?? 'null') : 'null') + '</div>' +
      '<div><span class="material-icons">source</span> <b>Register source:</b> ' + (j.profile ? (j.profile.registerSource||'') : '') + '</div>' +
      '<div><span class="material-icons">phone</span> <b>Mobile:</b> ' + (j.profile ? (j.profile.mobile||'') : '') + '</div>' +
      '<div><span class="material-icons">email</span> <b>Email:</b> ' + (j.profile ? (j.profile.email||'') : '') + '</div>' +
      '<div><span class="material-icons">account_balance_wallet</span> <b>Balance:</b> ' + (j.balance ? ((j.balance.sumBalance ?? j.balance.mainWalletBalance ?? 0) + ' ' + (j.balance.currency||'')) : 'n/a') + '</div>' +
      '<div><span class="material-icons">group</span> <b>Valid referrals:</b> ' + (j.referral ? (j.referral.memberQualifiedCount ?? j.referral.memberCount ?? 0) : 'n/a') + '</div>' +
      '<div><span class="material-icons">card_giftcard</span> <b>Available promotions:</b> ' + (function(p){ if(!p||!p.content) return 'n/a'; return Object.keys(p.content).map(k=>k+':'+(p.content[k].count||0)+(p.content[k].enabled?' (on)':' (off)')).join(', '); })(j.available_promotions) + '</div>' +
      '<div><span class="material-icons">confirmation_number</span> <b>Tickets:</b> ' + (j.tickets ? (j.tickets.temTicketClaims ? j.tickets.temTicketClaims.length : 0) + ' claims, processing: ' + (j.tickets.processingTemuTicketClaimId || 'none') : 'n/a') + '</div>' +
      '<div><span class="material-icons">local_activity</span> <b>Claim ticket list:</b> ' + (Array.isArray(j.ticket_list) ? j.ticket_list.length + ' available (' + j.ticket_list.map(t=>t.name).join(', ') + ')' : 'n/a') + '</div>' +
      '<div><span class="material-icons">emoji_events</span> <b>Rank salary:</b> ' + (j.rank_salary ? (j.rank_salary.promoName || 'none') : 'n/a') + '</div>';
  });
}
document.getElementById('runBtn').addEventListener('click', run);
run();
</script>
</body>
</html>
PHP
echo written