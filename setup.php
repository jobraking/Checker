<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/config.php';

require_login();

$message = '';
$error = '';
$defaultDb = 'if0_43151253_relax';
$defaultUser = '';
if (is_file(__DIR__ . '/db_name.txt')) {
    $candidate = trim((string) file_get_contents(__DIR__ . '/db_name.txt'));
    if ($candidate !== '') {
        $defaultDb = $candidate;
    }
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        $error = 'Invalid request.';
    } else {
        $dbName = trim((string) ($_POST['db_name'] ?? ''));
        $adminUser = trim((string) ($_POST['admin_user'] ?? ''));
        $adminPass = (string) ($_POST['admin_pass'] ?? '');
        if ($dbName === '' || $adminUser === '' || $adminPass === '') {
            $error = 'Database name, username, and password are required.';
        } else {
            try {
                $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . $dbName . ';charset=utf8mb4';
                $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                $pdo->exec("CREATE TABLE IF NOT EXISTS users (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
                $hash = password_hash($adminPass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?) ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)");
                $stmt->execute([$adminUser, $hash]);
                file_put_contents(__DIR__ . '/db_name.txt', $dbName);
                $message = 'Setup complete. You can now sign in.';
            } catch (Throwable $e) {
                $error = 'Setup failed: ' . $e->getMessage();
            }
        }
    }
}

$token = csrf_token();

$extensions = [
    'pdo' => extension_loaded('pdo'),
    'pdo_mysql' => extension_loaded('pdo_mysql'),
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Setup</title>
<style>
body{font-family:system-ui,sans-serif;background:#f4f8fc;margin:0;min-height:100vh;display:grid;place-items:center}
.card{background:#fff;border:1px solid #dbe6f0;border-radius:16px;padding:28px;width:min(520px,92vw);box-shadow:0 18px 50px rgba(15,42,70,.08)}
h1{margin:0 0 18px;font-size:1.4rem}
label{display:block;margin:12px 0 6px;font-weight:600}
input{width:100%;padding:10px 12px;border:1px solid #dbe6f0;border-radius:10px;font-size:1rem}
button{margin-top:18px;width:100%;padding:11px;border:0;border-radius:10px;background:#0877ed;color:#fff;font-weight:700;cursor:pointer}
.error{background:#ffe8e8;color:#9b1c1c;padding:10px 12px;border-radius:10px;margin-bottom:12px}
.success{background:#e7fbf5;color:#087f66;padding:10px 12px;border-radius:10px;margin-bottom:12px}
.status{background:#f8fbff;border:1px solid #dbe6f0;border-radius:10px;padding:10px 12px;margin-bottom:12px;font-size:.9rem}
a{color:#0877ed}
</style>
</head>
<body>
<div class="card">
<h1>Setup</h1>
<?php if ($error !== ''): ?>
<div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
<?php if ($message !== ''): ?>
<div class="success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
<div class="status">
PDO: <?= $extensions['pdo'] ? 'available' : 'missing' ?><br>
PDO MySQL: <?= $extensions['pdo_mysql'] ? 'available' : 'missing' ?><br>
Guard file: <?= is_file(__DIR__ . '/guard.php') ? 'present' : 'missing' ?>
</div>
<form method="post" action="/setup.php">
<input type="hidden" name="csrf" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
<label for="db_name">Database name</label>
<input id="db_name" name="db_name" type="text" value="<?= htmlspecialchars($defaultDb, ENT_QUOTES, 'UTF-8') ?>" required>
<label for="admin_user">Admin username</label>
<input id="admin_user" name="admin_user" type="text" value="<?= htmlspecialchars($defaultUser, ENT_QUOTES, 'UTF-8') ?>" required>
<label for="admin_pass">Admin password</label>
<input id="admin_pass" name="admin_pass" type="password" required>
<button type="submit">Run setup</button>
</form>
<p><a href="/login.php">Go to login</a></p>
</div>
</body>
</html>
