<?php
require_once __DIR__ . '/auth.php';
require_login();
$active = $active ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GYMZ — <?= htmlspecialchars($page_title ?? 'Dashboard') ?></title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="app">
<aside class="sidebar glass">
  <div class="brand">
    <span class="brand-mark">GYMZ</span>
    <span class="brand-dot"></span>
  </div>
  <nav class="nav">
    <a href="dashboard.php" class="nav-link <?= $active==='dashboard'?'active':'' ?>">▣ Dashboard</a>
    <a href="members.php" class="nav-link <?= $active==='members'?'active':'' ?>">☰ Members</a>
    <a href="member_form.php" class="nav-link <?= $active==='add'?'active':'' ?>">＋ Tambah Member</a>
    <a href="logout.php" class="nav-link logout">⎋ Logout</a>
  </nav>
  <div class="sidebar-foot">
    <small>Logged in as</small>
    <strong><?= htmlspecialchars(current_user()) ?></strong>
  </div>
</aside>
<main class="main">
<header class="topbar">
  <h1 class="page-title"><?= htmlspecialchars($page_title ?? 'Dashboard') ?></h1>
  <div class="topbar-right">
    <span class="chip">⚡ GEN-Z MODE</span>
  </div>
</header>
<section class="content">
