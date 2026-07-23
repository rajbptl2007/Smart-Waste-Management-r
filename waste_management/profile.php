<?php
require_once 'includes/config.php';
requireLogin();
$pageTitle = 'My Profile';
$uid = $_SESSION['user_id'];

$msg=''; $msgType='';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'update') {
        $name  = sanitize($_POST['full_name']);
        $phone = sanitize($_POST['phone']);
        $addr  = sanitize($_POST['address']);
        $stmt = $conn->prepare("UPDATE users SET full_name=?,phone=?,address=? WHERE id=?");
        $stmt->bind_param('sssi',$name,$phone,$addr,$uid);
        if ($stmt->execute()) {
            $_SESSION['user_name'] = $name;
            $msg='Profile updated!'; $msgType='success';
        }
    } elseif ($action === 'password') {
        $old  = $_POST['old_password'];
        $new  = $_POST['new_password'];
        $conf = $_POST['confirm_password'];
        $user = $conn->query("SELECT password FROM users WHERE id=$uid")->fetch_assoc();
        if (!password_verify($old, $user['password'])) {
            $msg='Current password is incorrect.'; $msgType='danger';
        } elseif ($new !== $conf) {
            $msg='Passwords do not match.'; $msgType='danger';
        } elseif (strlen($new) < 6) {
            $msg='Password must be at least 6 characters.'; $msgType='danger';
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password='$hash' WHERE id=$uid");
            $msg='Password changed successfully!'; $msgType='success';
        }
    }
}

$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">

<?php if ($msg): ?><div class="alert alert-<?= $msgType ?> alert-auto-hide"><?= $msg ?></div><?php endif; ?>

<div class="row g-3">
    <!-- Profile Card -->
    <div class="col-md-4">
        <div class="content-card p-4 text-center">
            <div style="width:80px;height:80px;background:linear-gradient(135deg,#1a7a4c,#2ea865);border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:700;color:#fff">
                <?= strtoupper(substr($user['full_name'],0,1)) ?>
            </div>
            <h5 class="fw-bold"><?= htmlspecialchars($user['full_name']) ?></h5>
            <span class="badge bg-primary-subtle text-primary px-3 py-1 mb-2"><?= ucfirst($user['role']) ?></span>
            <p style="font-size:13px;color:#6b7280"><?= htmlspecialchars($user['email']) ?></p>
            <?php if ($user['phone']): ?>
            <p style="font-size:13px;color:#6b7280">📞 <?= htmlspecialchars($user['phone']) ?></p>
            <?php endif; ?>
            <?php if ($user['address']): ?>
            <p style="font-size:13px;color:#6b7280">📍 <?= htmlspecialchars($user['address']) ?></p>
            <?php endif; ?>
            <div class="mt-3">
                <small style="font-size:11px;color:#9ca3af">Member since <?= date('M Y',strtotime($user['created_at'])) ?></small>
            </div>
        </div>
    </div>

    <!-- Forms -->
    <div class="col-md-8">
        <!-- Update Info -->
        <div class="content-card p-4 mb-3">
            <h6 class="fw-bold mb-4">✏️ Update Profile</h6>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-muted">(read-only)</span></label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']??'') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user['address']??'') ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="content-card p-4">
            <h6 class="fw-bold mb-4">🔒 Change Password</h6>
            <form method="POST">
                <input type="hidden" name="action" value="password">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="old_password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required minlength="6">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-warning mt-3">Change Password</button>
            </form>
        </div>
    </div>
</div>

</div>
</div>
<?php include 'includes/footer.php'; ?>
