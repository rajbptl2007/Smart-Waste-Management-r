<?php
require_once '../includes/config.php';
requireRole('admin');
$pageTitle = 'Manage Complaints';

$msg = ''; $msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'update_status') {
        $id     = (int)$_POST['complaint_id'];
        $status = sanitize($_POST['status']);
        $notes  = sanitize($_POST['resolution_notes'] ?? '');
        $resolved = in_array($status,['resolved','closed']) ? "resolved_at=NOW()," : '';
        $stmt = $conn->prepare("UPDATE complaints SET status=?, resolution_notes=?, $resolved updated_at=NOW() WHERE id=?");
        $stmt->bind_param('ssi', $status, $notes, $id);
        if ($stmt->execute()) { $msg='Complaint updated!'; $msgType='success'; }
    } elseif ($action === 'assign') {
        $id = (int)$_POST['complaint_id'];
        $uid = (int)$_POST['assigned_to'];
        $conn->query("UPDATE complaints SET assigned_to=$uid, status='in_progress', updated_at=NOW() WHERE id=$id");
        $msg='Complaint assigned!'; $msgType='success';
    }
}

$filterStatus = sanitize($_GET['status'] ?? '');
$filterPriority = sanitize($_GET['priority'] ?? '');
$where = '1=1';
if ($filterStatus) $where .= " AND c.status='$filterStatus'";
if ($filterPriority) $where .= " AND c.priority='$filterPriority'";

$complaints = $conn->query("
    SELECT c.*, u.full_name as assigned_name 
    FROM complaints c 
    LEFT JOIN users u ON c.assigned_to=u.id 
    WHERE $where 
    ORDER BY 
        FIELD(c.priority,'urgent','high','medium','low'),
        c.created_at DESC
")->fetch_all(MYSQLI_ASSOC);

$collectors = $conn->query("SELECT id, full_name FROM users WHERE role IN ('collector','admin') AND status='active'")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>

<div class="main-content">
<div class="content-area">

<?php if ($msg): ?>
<div class="alert alert-<?= $msgType ?> alert-auto-hide"><?= $msg ?></div>
<?php endif; ?>

<!-- Summary -->
<div class="row g-3 mb-4">
    <?php
    $cStats = [
        ['label'=>'Total','icon'=>'chat-left-text','color'=>'primary','q'=>"SELECT COUNT(*) as c FROM complaints"],
        ['label'=>'Pending','icon'=>'hourglass','color'=>'warning','q'=>"SELECT COUNT(*) as c FROM complaints WHERE status='pending'"],
        ['label'=>'In Progress','icon'=>'arrow-repeat','color'=>'info','q'=>"SELECT COUNT(*) as c FROM complaints WHERE status='in_progress'"],
        ['label'=>'Resolved','icon'=>'check-circle','color'=>'success','q'=>"SELECT COUNT(*) as c FROM complaints WHERE status='resolved'"],
    ];
    foreach ($cStats as $cs):
        $cnt = $conn->query($cs['q'])->fetch_assoc()['c'];
    ?>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:#f0f0f0"><i class="bi bi-<?= $cs['icon'] ?>"></i></div>
            <div class="stat-value"><?= $cnt ?></div>
            <div class="stat-label"><?= $cs['label'] ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Filters -->
<div class="content-card p-3 mb-4">
    <form method="GET" class="row g-2">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <?php foreach(['pending','in_progress','resolved','closed','rejected'] as $s): ?>
                <option value="<?= $s ?>" <?= $filterStatus===$s?'selected':'' ?>><?= ucwords(str_replace('_',' ',$s)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="priority" class="form-select">
                <option value="">All Priority</option>
                <?php foreach(['urgent','high','medium','low'] as $p): ?>
                <option value="<?= $p ?>" <?= $filterPriority===$p?'selected':'' ?>><?= ucfirst($p) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary">Filter</button>
            <a href="complaints.php" class="btn btn-outline-secondary ms-1">Reset</a>
        </div>
    </form>
</div>

<!-- Complaints Table -->
<div class="data-table">
    <div class="table-header">
        <h6>📢 Complaints (<?= count($complaints) ?>)</h6>
    </div>
    <div style="overflow-x:auto">
        <table class="table mb-0">
            <thead>
                <tr><th>#</th><th>Complaint No</th><th>Type</th><th>Resident</th><th>Priority</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($complaints as $i => $c):
                    $sc = ['pending'=>'warning','in_progress'=>'info','resolved'=>'success','closed'=>'secondary','rejected'=>'danger'];
                    $pc = ['low'=>'secondary','medium'=>'primary','high'=>'warning','urgent'=>'danger'];
                    $pEmoji = ['low'=>'🟢','medium'=>'🟡','high'=>'🟠','urgent'=>'🔴'];
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><strong><?= $c['complaint_no'] ?></strong></td>
                    <td style="font-size:13px"><?= ucwords(str_replace('_',' ',$c['complaint_type'])) ?></td>
                    <td style="font-size:13px"><?= htmlspecialchars($c['resident_name']) ?></td>
                    <td><?= $pEmoji[$c['priority']] ?> <span class="badge bg-<?= $pc[$c['priority']] ?>-subtle text-<?= $pc[$c['priority']] ?>"><?= ucfirst($c['priority']) ?></span></td>
                    <td><span class="badge bg-<?= $sc[$c['status']] ?>-subtle text-<?= $sc[$c['status']] ?> status-badge"><?= ucwords(str_replace('_',' ',$c['status'])) ?></span></td>
                    <td style="font-size:12px"><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick='viewComplaint(<?= json_encode($c) ?>, <?= json_encode($collectors) ?>)'>
                            <i class="bi bi-eye"></i> Manage
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</div>

<!-- Manage Complaint Modal -->
<div class="modal fade" id="manageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="modalComplaintNo">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><strong>Resident:</strong> <span id="mc_resident"></span></div>
                    <div class="col-md-6"><strong>Phone:</strong> <span id="mc_phone"></span></div>
                    <div class="col-md-6"><strong>Type:</strong> <span id="mc_type"></span></div>
                    <div class="col-md-6"><strong>Location:</strong> <span id="mc_location"></span></div>
                    <div class="col-12"><strong>Description:</strong><p id="mc_desc" class="mt-1 p-2 bg-light rounded"></p></div>
                </div>
                <hr>
                <!-- Update Status Form -->
                <form method="POST">
                    <input type="hidden" name="action" value="assign">
                    <input type="hidden" name="complaint_id" id="mc_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Update Status</label>
                            <select name="status" id="mc_status" class="form-select">
                                <?php foreach(['pending','in_progress','resolved','closed','rejected'] as $s): ?>
                                <option value="<?= $s ?>"><?= ucwords(str_replace('_',' ',$s)) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Assign To</label>
                            <select name="assigned_to" id="mc_assign" class="form-select">
                                <option value="">-- Not Assigned --</option>
                                <?php foreach ($collectors as $col): ?>
                                <option value="<?= $col['id'] ?>"><?= htmlspecialchars($col['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Resolution Notes</label>
                            <textarea name="resolution_notes" id="mc_notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update Complaint</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function viewComplaint(c, collectors) {
    document.getElementById('modalComplaintNo').innerText = c.complaint_no;
    document.getElementById('mc_id').value = c.id;
    document.getElementById('mc_resident').innerText = c.resident_name || 'Anonymous';
    document.getElementById('mc_phone').innerText = c.resident_phone || '-';
    document.getElementById('mc_type').innerText = c.complaint_type.replace(/_/g,' ');
    document.getElementById('mc_location').innerText = c.location || '-';
    document.getElementById('mc_desc').innerText = c.description;
    document.getElementById('mc_status').value = c.status;
    document.getElementById('mc_notes').value = c.resolution_notes || '';
    document.getElementById('mc_assign').value = c.assigned_to || '';
    new bootstrap.Modal(document.getElementById('manageModal')).show();
}
</script>
<?php include '../includes/footer.php'; ?>
