<?php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/auth.php';

$error = ''; $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pw = $_POST['password'] ?? '';
    $pw2 = $_POST['password_confirm'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pw) < 6) {
        $error = 'Periksa kembali data isian Anda.';
    } elseif ($pw !== $pw2) {
        $error = 'Password tidak cocok.';
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param('s', $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'Email sudah terdaftar.';
        } else {
            $hash = password_hash($pw, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $name, $email, $hash);
            if ($stmt->execute()) {
                flash_set('ok', 'Akun dibuat! Silakan login.');
                header("Location: login.php"); exit;
            } else { $error = 'Gagal mendaftar. Coba lagi.'; }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar — GYMZ</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="auth-wrap">
  <form class="auth-card" method="POST" data-validate novalidate>
    <div class="auth-brand">GYMZ</div>
    <div class="auth-sub">Mulai journey kamu 🚀</div>
    <h2 class="auth-title">Buat akun<br>admin baru.</h2>

    <?php if($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="field">
      <label>Nama Lengkap</label>
      <input class="input" type="text" name="full_name" required placeholder="Nama kamu">
      <span class="error"></span>
    </div>
    <div class="field">
      <label>Email</label>
      <input class="input" type="email" name="email" required placeholder="kamu@email.com">
      <span class="error"></span>
    </div>
    <div class="field">
      <label>Password</label>
      <input class="input" type="password" name="password" required data-minlength="6" placeholder="Min. 6 karakter">
      <span class="error"></span>
    </div>
    <div class="field">
      <label>Konfirmasi Password</label>
      <input class="input" type="password" name="password_confirm" required placeholder="Ulangi password">
      <span class="error"></span>
    </div>
    <button class="btn btn-primary btn-block" type="submit">Daftar →</button>
    <a href="login.php" class="muted-link">Sudah punya akun? Login</a>
  </form>
</div>
<script src="assets/js/app.js"></script>
</body>
</html>
