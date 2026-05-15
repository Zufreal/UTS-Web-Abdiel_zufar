<?php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM members WHERE id=?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) flash_set('ok','Member berhasil dihapus.');
    else flash_set('err','Gagal menghapus member.');
}
header("Location: members.php");
exit;
