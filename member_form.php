<?php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$data = [
  'full_name'=>'', 'email'=>'', 'phone'=>'', 'gender'=>'Male',
  'birth_date'=>'', 'address'=>'', 'membership_type'=>'Basic',
  'payment_status'=>'Pending', 'payment_method'=>''
];
$error = '';

if ($isEdit) {
    $stmt = $conn->prepare("SELECT * FROM members WHERE id=? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row) { flash_set('err','Data tidak ditemukan.'); header("Location: members.php"); exit; }
    $data = array_merge($data, $row);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $k=>$_) $data[$k] = trim($_POST[$k] ?? '');
    if ($data['full_name']==='' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL) || $data['phone']==='') {
        $error = 'Lengkapi data wajib (nama, email valid, telp).';
    } else {
        if ($isEdit) {
            $stmt = $conn->prepare("UPDATE members SET full_name=?,email=?,phone=?,gender=?,birth_date=?,address=?,membership_type=?,payment_status=?,payment_method=? WHERE id=?");
            $bd = $data['birth_date'] ?: null;
            $stmt->bind_param('sssssssssi',
                $data['full_name'],$data['email'],$data['phone'],$data['gender'],$bd,
                $data['address'],$data['membership_type'],$data['payment_status'],$data['payment_method'],$id);
            if ($stmt->execute()) { flash_set('ok','Member berhasil diperbarui.'); header("Location: members.php"); exit; }
            else $error = 'Gagal memperbarui data (mungkin email duplikat).';
        } else {
            $stmt = $conn->prepare("INSERT INTO members (full_name,email,phone,gender,birth_date,address,membership_type,payment_status,payment_method) VALUES (?,?,?,?,?,?,?,?,?)");
            $bd = $data['birth_date'] ?: null;
            $stmt->bind_param('sssssssss',
                $data['full_name'],$data['email'],$data['phone'],$data['gender'],$bd,
                $data['address'],$data['membership_type'],$data['payment_status'],$data['payment_method']);
            if ($stmt->execute()) {
                $newId = $stmt->insert_id;
                if ($data['payment_status'] !== 'Paid') {
                    // Trigger payment modal flow on next page via query param
                    header("Location: member_form.php?id=$newId&pay=1"); exit;
                }
                flash_set('ok','Member berhasil ditambahkan.'); header("Location: members.php"); exit;
            } else $error = 'Gagal menambahkan (mungkin email duplikat).';
        }
    }
}

$page_title = $isEdit ? 'Edit Member' : 'Tambah Member';
$active = $isEdit ? 'members' : 'add';
include __DIR__ . '/includes/header.php';
?>
<?php if($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST" class="card" data-validate novalidate style="max-width:880px">
  <div class="form-grid">
    <div class="field">
      <label>Nama Lengkap *</label>
      <input class="input" type="text" name="full_name" required value="<?= htmlspecialchars($data['full_name']) ?>">
      <span class="error"></span>
    </div>
    <div class="field">
      <label>Email *</label>
      <input class="input" type="email" name="email" required value="<?= htmlspecialchars($data['email']) ?>">
      <span class="error"></span>
    </div>
    <div class="field">
      <label>No. Telepon *</label>
      <input class="input" type="text" name="phone" required value="<?= htmlspecialchars($data['phone']) ?>">
      <span class="error"></span>
    </div>
    <div class="field">
      <label>Jenis Kelamin</label>
      <select name="gender">
        <?php foreach(['Male','Female','Other'] as $g): ?>
          <option value="<?= $g ?>" <?= $data['gender']===$g?'selected':'' ?>><?= $g ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label>Tanggal Lahir</label>
      <input class="input" type="date" name="birth_date" value="<?= htmlspecialchars($data['birth_date']) ?>">
    </div>
    <div class="field">
      <label>Tipe Membership</label>
      <select name="membership_type">
        <?php foreach(['Basic','Pro','Elite'] as $t): ?>
          <option value="<?= $t ?>" <?= $data['membership_type']===$t?'selected':'' ?>><?= $t ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field full">
      <label>Alamat</label>
      <textarea name="address" rows="3"><?= htmlspecialchars($data['address']) ?></textarea>
    </div>
    <div class="field">
      <label>Status Pembayaran</label>
      <select name="payment_status">
        <option value="Pending" <?= $data['payment_status']==='Pending'?'selected':'' ?>>Pending</option>
        <option value="Paid" <?= $data['payment_status']==='Paid'?'selected':'' ?>>Paid</option>
      </select>
    </div>
    <div class="field">
      <label>Metode Pembayaran</label>
      <input class="input" type="text" name="payment_method" placeholder="—" value="<?= htmlspecialchars($data['payment_method']) ?>">
    </div>
  </div>
  <div class="form-actions">
    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Member' ?></button>
    <?php if($isEdit && $data['payment_status']!=='Paid'): ?>
      <button type="button" class="btn btn-ghost" onclick="openModal('payModal')">💳 Bayar Sekarang</button>
    <?php endif; ?>
    <a href="members.php" class="btn btn-ghost">Batal</a>
  </div>
</form>

<!-- ============ Payment Modal ============ -->
<div class="modal-backdrop" id="payModal">
  <div class="modal">
    <h3>💳 Pilih Metode Pembayaran</h3>
    <p>Pilih metode pembayaran favorit kamu untuk menyelesaikan transaksi.</p>
    <form method="POST" action="payment_process.php">
      <input type="hidden" name="member_id" value="<?= $id ?>">
      <div class="pay-options">
        <label class="pay-opt"><span>🏦 Transfer Bank</span><input type="radio" name="method" value="Bank Transfer" required></label>
        <label class="pay-opt"><span>📱 E-Wallet (OVO / GoPay / Dana)</span><input type="radio" name="method" value="E-Wallet"></label>
        <label class="pay-opt"><span>💳 Kartu Kredit</span><input type="radio" name="method" value="Credit Card"></label>
        <label class="pay-opt"><span>💵 Tunai di Tempat</span><input type="radio" name="method" value="Cash"></label>
      </div>
      <div style="display:flex;gap:10px">
        <button class="btn btn-primary btn-block" type="submit">Bayar Sekarang →</button>
        <button class="btn btn-ghost" type="button" data-close="payModal">Batal</button>
      </div>
    </form>
  </div>
</div>

<!-- ============ Success Modal ============ -->
<div class="modal-backdrop" id="successModal">
  <div class="modal modal-success">
    <div class="success-icon">✓</div>
    <h3>Pembayaran Selesai! 🎉</h3>
    <p>Terima kasih! Status member telah diperbarui menjadi <strong style="color:var(--success)">PAID</strong>.</p>
    <a href="members.php" class="btn btn-primary btn-block">Lihat Daftar Member</a>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<?php if (isset($_GET['pay']) && $isEdit && $data['payment_status']!=='Paid'): ?>
<script>openModal('payModal');</script>
<?php endif; ?>
<?php if (isset($_GET['success'])): ?>
<script>openModal('successModal');</script>
<?php endif; ?>
