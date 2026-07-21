<?php
require_once '../includes/config.php';
requireRole('admin');
$pageTitle = 'Manage Users';

$msg=''; $msgType='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $name   = sanitize($_POST['full_name']);
        $email  = sanitize($_POST['email']);
        $pass   = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role   = sanitize($_POST['role']);
        $phone  = sanitize($_POST['phone']);
        $addr   = sanitize($_POST['address']);
        $stmt = $conn->prepare("INSERT INTO users (full_name,email,password,role,phone,address) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param('ssssss',$name,$email,$pass,$role,$phone,$addr);
        if ($stmt->execute()) { $msg='User added!'; $msgType='success'; }
        else { $msg='Error: '.$conn->error; $msgType='danger'; }
    } elseif ($action === 'toggle') {
        $id = (int)$_POST['user_id'];
        $conn->query("UPDATE users SET status=IF(status='active','inactive','active') WHERE id=$id");
        $msg='Status toggled!'; $msgType='info';
    } elseif ($action === 'delete') {
        $id = (int)$_POST['user_id'];
        if ($id != $_SESSION['user_id']) {
            $conn->query("DELETE FROM users WHERE id=$id");
            $msg='User deleted.'; $msgType='warning';
        } else { $msg='Cannot delete yourself!'; $msgType='danger'; }
    }
}

$filterRole = sanitize($_GET['role'] ?? '');
$where = $filterRole ? "WHERE role='$filterRole'" : '';
$users = $conn->query("SELECT * FROM users $where ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">
<?php if ($msg): ?><div class="alert alert-<?= $msgType ?> alert-auto-hide"><?= $msg ?></div><?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex gap-2">
        <?php foreach([''=>'All','admin'=>'Admins','collector'=>'Collectors','resident'=>'Residents'] as $r => $l): ?>
        <a href="?role=<?= $r ?>" class="btn btn-sm <?= $filterRole===$r?'btn-primary':'btn-outline-secondary' ?>"><?= $l ?></a>
        <?php endforeach; ?>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus me-1"></i> Add User
    </button>
</div>

<div class="data-table">
    <div class="table-header"><h6>👥 Users (<?= count($users) ?>)</h6></div>
    <div style="overflow-x:auto">
        <table class="table mb-0">
            <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Phone</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($users as $i => $u):
                    $roleColors=['admin'=>'danger','collector'=>'primary','resident'=>'success'];
                    $roleEmoji=['admin'=>'👑','collector'=>'🚛','resident'=>'🏠'];
                    $rc=$roleColors[$u['role']]??'secondary';
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width:32px;height:32px;font-size:12px"><?= strtoupper(substr($u['full_name'],0,1)) ?></div>
                            <strong style="font-size:14px"><?= htmlspecialchars($u['full_name']) ?></strong>
                        </div>
                    </td>
                    <td style="font-size:13px"><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $roleEmoji[$u['role']] ?> <span class="badge bg-<?= $rc ?>-subtle text-<?= $rc ?>"><?= ucfirst($u['role']) ?></span></td>
                    <td style="font-size:13px"><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
                    <td><span class="badge bg-<?= $u['status']==='active'?'success':'secondary' ?>-subtle text-<?= $u['status']==='active'?'success':'secondary' ?>"><?= ucfirst($u['status']) ?></span></td>
                    <td style="font-size:12px"><?= date('d M Y',strtotime($u['created_at'])) ?></td>
                    <td>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button class="btn btn-sm btn-outline-<?= $u['status']==='active'?'warning':'success' ?>"><?= $u['status']==='active'?'Disable':'Enable' ?></button>
                        </form>
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                        <form method="POST" style="display:inline" onsubmit="return confirm('Delete user?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0"><h5 class="fw-bold">👤 Add User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">Full Name *</label><input type="text" name="full_name" class="form-control" required></div>
                        <div class="col-12"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
                        <div class="col-6"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" required minlength="6"></div>
                        <div class="col-6"><label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="resident">Resident</option>
                                <option value="collector">Collector</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
                        <div class="col-6"><label class="form-label">Address</label><input type="text" name="address" class="form-control"></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
