<?php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$total   = $conn->query("SELECT COUNT(*) c FROM members")->fetch_assoc()['c'] ?? 0;
$paid    = $conn->query("SELECT COUNT(*) c FROM members WHERE payment_status='Paid'")->fetch_assoc()['c'] ?? 0;
$pending = $conn->query("SELECT COUNT(*) c FROM members WHERE payment_status='Pending'")->fetch_assoc()['c'] ?? 0;
$elite   = $conn->query("SELECT COUNT(*) c FROM members WHERE membership_type='Elite'")->fetch_assoc()['c'] ?? 0;

$recent = $conn->query("SELECT id, full_name, membership_type, payment_status, joined_at FROM members ORDER BY id DESC LIMIT 5");

$page_title = 'Dashboard';
$active = 'dashboard';
include __DIR__ . '/includes/header.php';
?>
<div class="grid grid-stats">
  <div class="card stat-card accent">
    <div class="stat-label">Total Members</div>
    <div class="stat-value"><?= $total ?></div>
    <div class="stat-foot">Semua member terdaftar</div>
  </div>
  <div class="card stat-card">
    <div class="stat-label">Paid</div>
    <div class="stat-value" style="color:var(--success)"><?= $paid ?></div>
    <div class="stat-foot">Pembayaran lunas</div>
  </div>
  <div class="card stat-card">
    <div class="stat-label">Pending</div>
    <div class="stat-value" style="color:var(--yellow)"><?= $pending ?></div>
    <div class="stat-foot">Menunggu pembayaran</div>
  </div>
  <div class="card stat-card">
    <div class="stat-label">Elite Tier</div>
    <div class="stat-value" style="color:var(--orange)"><?= $elite ?></div>
    <div class="stat-foot">Member premium</div>
  </div>
</div>

<div style="margin-top:26px" class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;flex-wrap:wrap;gap:10px">
    <h3 style="font-size:20px;font-weight:800">🔥 Member Terbaru</h3>
    <a href="members.php" class="btn btn-ghost btn-sm">Lihat semua →</a>
  </div>
  <div class="table-wrap" style="border:0">
    <table>
      <thead><tr><th>#</th><th>Nama</th><th>Tipe</th><th>Status</th><th>Bergabung</th></tr></thead>
      <tbody>
      <?php if($recent && $recent->num_rows): while($r=$recent->fetch_assoc()): ?>
        <tr>
          <td>#<?= $r['id'] ?></td>
          <td style="font-weight:700"><?= htmlspecialchars($r['full_name']) ?></td>
          <td><span class="badge <?= strtolower($r['membership_type']) ?>"><?= $r['membership_type'] ?></span></td>
          <td><span class="badge <?= strtolower($r['payment_status']) ?>"><?= $r['payment_status'] ?></span></td>
          <td style="color:var(--muted)"><?= date('d M Y', strtotime($r['joined_at'])) ?></td>
        </tr>
      <?php endwhile; else: ?>
        <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px">Belum ada member. <a href="member_form.php" style="color:var(--yellow);font-weight:700">Tambah sekarang →</a></td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
