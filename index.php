<?php
require_once __DIR__ . '/includes/auth.php';
if (isset($_SESSION['user_id'])) { header("Location: dashboard.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GYMZ — Gym Membership for the Bold</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="landing">
  <nav class="landing-nav">
    <div class="brand">
      <span class="brand-mark">GYMZ</span>
      <span class="brand-dot"></span>
    </div>
    <div class="links">
      <a href="login.php" class="btn btn-ghost btn-sm">Login</a>
      <a href="register.php" class="btn btn-primary btn-sm">Join Now</a>
    </div>
  </nav>
  <section class="hero">
    <div>
      <h1>TRAIN HARD.<br><span class="accent">LIVE LOUD.</span></h1>
      <p>Sistem keanggotaan gym yang dibangun untuk generasi yang nggak suka basa-basi. Daftar, latihan, kuasai.</p>
      <div class="hero-cta">
        <a href="register.php" class="btn btn-primary">⚡ Register new staff account</a>
        <a href="login.php" class="btn btn-ghost">Admin Login</a>
      </div>
      <div class="hero-tags">
        <span class="chip">💪 BASIC</span>
        <span class="chip">🔥 PRO</span>
        <span class="chip">👑 ELITE</span>
      </div>
    </div>
  </section>
</div>
</body>
</html>
