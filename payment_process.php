<?php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: members.php"); exit; }

$id = (int)($_POST['member_id'] ?? 0);
$method = trim($_POST['method'] ?? '');

if ($id <= 0 || $method === '') {
    flash_set('err', 'Data pembayaran tidak valid.');
    header("Location: members.php"); exit;
}

$stmt = $conn->prepare("UPDATE members SET payment_status='Paid', payment_method=? WHERE id=?");
$stmt->bind_param('si', $method, $id);
$stmt->execute();

header("Location: member_form.php?id=$id&success=1");
exit;
