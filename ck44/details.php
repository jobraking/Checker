<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/guard.php';
require_once __DIR__ . '/ck-client.php';

header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');

function detailsPasswordFor(string $username): string
{
    $file = __DIR__ . '/pwd.json';
    if ($username === '' || !is_file($file) || !is_readable($file)) {
        return '';
    }
    $raw = file_get_contents($file);
    $saved = is_string($raw) ? json_decode($raw, true) : null;
    if (!is_array($saved)) {
        return '';
    }
    foreach ($saved as $entry) {
        if (is_array($entry) && (string) ($entry['username'] ?? '') === $username) {
            return (string) ($entry['password'] ?? '');
        }
    }
    return '';
}

$username = trim((string) ($_GET['username'] ?? ''));

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    if (!csrf_check($_POST['csrf'] ?? null)) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
        exit;
    }

    $action = (string) ($_POST['action'] ?? '');
    $accountUser = trim((string) ($_POST['username'] ?? $username));
    $accountPass = detailsPasswordFor($accountUser);
    $allowed = ['check_all_live', 'check_one', 'summary'];

    if (!in_array($action, $allowed, true)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Unknown action.']);
        exit;
    }
    if ($accountUser === '' || $accountPass === '') {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Saved account not found.']);
        exit;
    }

    $login = ckLoginRetry($accountUser, $accountPass, CK_DEFAULT_MERCHANT);
    if (!$login['ok'] || $login['session'] === null) {
        echo json_encode(['status' => 'error', 'message' => (string) ($login['error'] ?? 'login failed')]);
        exit;
    }
    $session = $login['session'];

    if ($action === 'check_all_live') {
        set_time_limit(0);
        $results = [];
        $endpointFile = __DIR__ . '/live_endpoints.txt';
        $endpoints = is_readable($endpointFile) ? file($endpointFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
        foreach ($endpoints as $endpoint) {
            $endpoint = trim((string) $endpoint);
            if ($endpoint === '') {
                continue;
            }
            $response = ckHttp('GET', $endpoint, ['headers' => ckAuthHeaders($session)]);
            $results[$endpoint] = $response['body'] ?? ['status' => $response['status'], 'raw' => $response['raw']];
        }
        $profile = ckProfile($session);
        $balance = ckBalance($session);
        $referral = ckReferralOverview($session);
        echo json_encode([
            'status' => 'ok',
            'user' => $accountUser,
            'profile' => $profile['body']['value'] ?? null,
            'balance' => $balance['body']['value'] ?? null,
            'referral' => $referral['body']['value'] ?? null,
            'count' => count($results),
            'results' => $results,
        ]);
        exit;
    }

    if ($action === 'check_one') {
        $endpoint = (string) ($_POST['endpoint'] ?? '');
        $response = ckHttp('GET', $endpoint, ['headers' => ckAuthHeaders($session)]);
        echo json_encode([
            'status' => 'ok',
            'endpoint' => $endpoint,
            'response' => $response['body'] ?? ['status' => $response['status'], 'raw' => $response['raw']],
        ]);
        exit;
    }

    $profile = ckProfile($session);
    $balance = ckBalance($session);
    $referral = ckReferralOverview($session);
    $promotions = ckHttp('GET', '/wps/relay/MCSFE_getAvailablePromotions', ['headers' => ckAuthHeaders($session)]);
    $tickets = ckHttp('GET', '/wps/relay/PROMOFE_getClaimTickets', ['headers' => ckAuthHeaders($session)]);
    $ticketList = ckHttp('GET', '/wps/relay/PROMOFE_getClaimTicketList', ['headers' => ckAuthHeaders($session)]);
    $rankSalary = ckHttp('GET', '/wps/relay/PROMOFE_getRankSalaryRewardDetail', ['headers' => ckAuthHeaders($session)]);
    $savior = ckHttp('GET', '/wps/relay/PROMOFE_getSaviorPromotion', ['headers' => ckAuthHeaders($session)]);
    echo json_encode([
        'status' => 'ok',
        'profile' => $profile['body']['value'] ?? null,
        'balance' => $balance['body']['value'] ?? null,
        'referral' => $referral['body']['value'] ?? null,
        'available_promotions' => $promotions['body'] ?? null,
        'tickets' => $tickets['body']['value'] ?? null,
        'ticket_list' => $ticketList['body']['value'] ?? null,
        'rank_salary' => $rankSalary['body']['value'] ?? null,
        'savior_promotions' => $savior['body']['value'] ?? null,
    ]);
    exit;
}

$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>D3M09 ACCOUNT CHECKER - ACCOUNT DETAILS</title>
<style>
  body{margin:0;background:#0f1115;color:#e6e8ee;font:14px/1.5 ui-sans-serif,system-ui,sans-serif}
  .wrap{max-width:1100px;margin:0 auto;padding:24px}
  h1{font-size:18px;margin:0 0 12px}
  .toolbar{display:flex;gap:10px;align-items:center;margin-bottom:12px;flex-wrap:wrap}
  button{padding:6px 12px;border:1px solid transparent;border-radius:6px;background:#6ea8fe;color:#fff;cursor:pointer}
  .card{background:#171a21;border:1px solid #262b36;border-radius:8px;padding:12px;margin-bottom:10px}
  .royal{border:2px solid #ffd700;box-shadow:0 0 18px rgba(255,215,0,.35);background:linear-gradient(135deg,#1a1600,#171a21)}
  .royal h2{color:#ffd700;margin:0 0 8px}
  .crown{font-size:20px;margin-right:6px}
</style>
</head>
<body>
<div class="wrap">
  <h1>Account details: <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></h1>
  <div class="toolbar">
    <button id="runBtn">Run all live endpoints</button>
    <span class="status" id="status"></span>
  </div>
  <div id="summary" class="card">Loading summary...</div>
</div>
<script>
const username = <?= json_encode($username, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
const CSRF = <?= json_encode($csrf, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
const esc = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));
function run() {
  document.getElementById('status').textContent = 'loading summary...';
  const body = new FormData();
  body.append('action', 'summary');
  body.append('csrf', CSRF);
  body.append('username', username);
  fetch(window.location.href, { method: 'POST', body: body }).then((response) => response.json()).then((data) => {
    if (data.status !== 'ok') {
      document.getElementById('status').textContent = data.message || 'error';
      return;
    }
    document.getElementById('status').textContent = 'done';
    const summary = document.getElementById('summary');
    const profile = data.profile || {};
    const club = profile.clubLabel ? profile.clubLabel.labelName : '';
    const vip = profile.clubLabel ? (function(value) {
      const match = (value || '').match(/VIP(\d+)/i);
      return match ? parseInt(match[1], 10) : (profile.clubLabel.rankLevel || 0);
    })(club) : 0;
    const royal = vip > 3;
    const labels = profile.memberLabel ? profile.memberLabel.map((label) => label.labelName).join(', ') : '';
    const promotionText = (function(promotions) {
      if (!promotions || !promotions.content) return 'n/a';
      return Object.keys(promotions.content).map((key) => key + ':' + (promotions.content[key].count || 0) + (promotions.content[key].enabled ? ' (on)' : ' (off)')).join(', ');
    })(data.available_promotions);
    const ticketText = data.tickets ? (data.tickets.temTicketClaims ? data.tickets.temTicketClaims.length : 0) + ' claims, processing: ' + (data.tickets.processingTemuTicketClaimId || 'none') : 'n/a';
    const ticketListText = Array.isArray(data.ticket_list) ? data.ticket_list.length + ' available (' + data.ticket_list.map((ticket) => ticket.name).join(', ') + ')' : 'n/a';
    const balanceText = data.balance ? ((data.balance.sumBalance ?? data.balance.mainWalletBalance ?? 0) + ' ' + (data.balance.currency || '')) : 'n/a';
    const referralText = data.referral ? (data.referral.memberQualifiedCount ?? data.referral.memberCount ?? 0) : 'n/a';
    const rankText = data.rank_salary ? (data.rank_salary.promoName || 'none') : 'n/a';
    summary.className = 'card' + (royal ? ' royal' : '');
    summary.innerHTML = (royal ? '<h2><span class="crown">&#128081;</span>VIP ' + vip + ' CLUB Account</h2>' : '') +
      '<div><b>Account:</b> ' + esc(profile.account || 'n/a') + '</div>' +
      '<div><b>Nickname:</b> ' + esc(profile.nickname || '') + '</div>' +
      '<div><b>Labels:</b> ' + esc(labels) + '</div>' +
      '<div><b>Club:</b> ' + esc(club) + ' (rank ' + vip + ')</div>' +
      '<div><b>TotalBetDown:</b> ' + esc(profile.clubLabel ? (profile.clubLabel.totalBetDown ?? 'null') : 'null') + '</div>' +
      '<div><b>Register source:</b> ' + esc(profile.registerSource || '') + '</div>' +
      '<div><b>Mobile:</b> ' + esc(profile.mobile || '') + '</div>' +
      '<div><b>Email:</b> ' + esc(profile.email || '') + '</div>' +
      '<div><b>Balance:</b> ' + esc(balanceText) + '</div>' +
      '<div><b>Valid referrals:</b> ' + esc(referralText) + '</div>' +
      '<div><b>Available promotions:</b> ' + esc(promotionText) + '</div>' +
      '<div><b>Tickets:</b> ' + esc(ticketText) + '</div>' +
      '<div><b>Claim ticket list:</b> ' + esc(ticketListText) + '</div>' +
      '<div><b>Rank salary:</b> ' + esc(rankText) + '</div>';
  }).catch(() => {
    document.getElementById('status').textContent = 'request failed';
  });
}
document.getElementById('runBtn').addEventListener('click', run);
run();
</script>
</body>
</html>
