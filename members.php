<?php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$ok = flash_get('ok'); $err = flash_get('err');
$rows = $conn->query("SELECT * FROM members ORDER BY id DESC");

$page_title = 'Members';
$active = 'members';
include __DIR__ . '/includes/header.php';
?>
<?php if($ok): ?><div class="alert success"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
<?php if($err): ?><div class="alert error"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:10px">
  <p style="color:var(--muted)">Kelola semua data member gym kamu di sini.</p>
  <a href="member_form.php" class="btn btn-primary">＋ Tambah Member</a>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>#</th><th>Nama</th><th>Email</th><th>Telp</th><th>Tipe</th><th>Pembayaran</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
    <?php if($rows && $rows->num_rows): while($r=$rows->fetch_assoc()): ?>
      <tr>
        <td>#<?= $r['id'] ?></td>
        <td style="font-weight:700"><?= htmlspecialchars($r['full_name']) ?></td>
        <td><?= htmlspecialchars($r['email']) ?></td>
        <td><?= htmlspecialchars($r['phone']) ?></td>
        <td><span class="badge <?= strtolower($r['membership_type']) ?>"><?= $r['membership_type'] ?></span></td>
        <td><span class="badge <?= strtolower($r['payment_status']) ?>"><?= $r['payment_status'] ?></span></td>
        <td>
          <div class="actions">
            <a href="member_form.php?id=<?= $r['id'] ?>" class="btn btn-ghost btn-sm">Edit</a>
            <a href="member_delete.php?id=<?= $r['id'] ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('Hapus member ini?')">Hapus</a>
          </div>
        </td>
      </tr>
    <?php endwhile; else: ?>
      <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:36px">
        Belum ada data. <a href="member_form.php" style="color:var(--yellow);font-weight:700">Tambah member pertama →</a>
      </td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
