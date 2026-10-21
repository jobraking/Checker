<?php
declare(strict_types=1);

require __DIR__ . '/auth.php';

require_login();
$user = current_user();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard</title>
<style>
body{font-family:system-ui,sans-serif;background:#f4f8fc;margin:0;min-height:100vh;display:grid;place-items:center}
.card{background:#fff;border:1px solid #dbe6f0;border-radius:16px;padding:28px;width:min(420px,90vw);box-shadow:0 18px 50px rgba(15,42,70,.08)}
h1{margin:0 0 10px;font-size:1.4rem}
a{color:#0877ed}
</style>
</head>
<body>
<div class="card">
<h1>Welcome, <?= htmlspecialchars((string) ($user['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h1>
<p>Your account is active.</p>
<p><a href="/logout.php">Sign out</a></p>
</div>
</body>
</html>
