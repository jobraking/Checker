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
<title>Login</title>
<style>
body{font-family:system-ui,sans-serif;background:#f4f8fc;margin:0;min-height:100vh;display:grid;place-items:center}
.card{background:#fff;border:1px solid #dbe6f0;border-radius:16px;padding:28px;width:min(420px,90vw);box-shadow:0 18px 50px rgba(15,42,70,.08)}
h1{margin:0 0 18px;font-size:1.4rem}
label{display:block;margin:12px 0 6px;font-weight:600}
input{width:100%;padding:10px 12px;border:1px solid #dbe6f0;border-radius:10px;font-size:1rem}
button{margin-top:18px;width:100%;padding:11px;border:0;border-radius:10px;background:#0877ed;color:#fff;font-weight:700;cursor:pointer}
.error{background:#ffe8e8;color:#9b1c1c;padding:10px 12px;border-radius:10px;margin-bottom:12px}
a{color:#0877ed}
</style>
</head>
<body>
<div class="card">
<h1>Login</h1>
<?php if ($error !== ''): ?>
<div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
<form method="post" action="/login.php">
<input type="hidden" name="csrf" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
<label for="username">Username</label>
<input id="username" name="username" type="text" autocomplete="username" required>
<label for="password">Password</label>
<input id="password" name="password" type="password" autocomplete="current-password" required>
<button type="submit">Sign in</button>
</form>
<p><a href="/register.php">Create account</a></p>
</div>
</body>
</html>
