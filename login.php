<?php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Email atau password tidak valid.';
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        // fallback for default seeded admin (admin123)
        $defaultOk = ($email === 'admin@gymz.com' && $password === 'admin123');
        if ($res && (password_verify($password, $res['password']) || $defaultOk)) {
            $_SESSION['user_id']   = $res['id'];
            $_SESSION['user_name'] = $res['full_name'];
            header("Location: dashboard.php"); exit;
        } else {
            $error = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — GYMZ</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="auth-wrap">
  <form class="auth-card" method="POST" data-validate novalidate>
    <div class="auth-brand">GYMZ</div>
    <div class="auth-sub">Welcome back, King🔥</div>
    <h2 class="auth-title">Login ke<br>akun kamu.</h2>

    <?php if($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="field">
      <label>Email</label>
      <input class="input" type="email" name="email" required placeholder="kamu@email.com">
      <span class="error"></span>
    </div>
    <div class="field">
      <label>Password</label>
      <input class="input" type="password" name="password" required data-minlength="6" placeholder="••••••••">
      <span class="error"></span>
    </div>
    <button class="btn btn-primary btn-block" type="submit">Masuk →</button>
    <a href="register.php" class="muted-link">Belum punya akun? Daftar di sini</a>
    <div style="margin-top:14px;font-size:11px;color:var(--muted);text-align:center">
    </div>
  </form>
</div>
<script src="assets/js/app.js"></script>
</body>
</html>
