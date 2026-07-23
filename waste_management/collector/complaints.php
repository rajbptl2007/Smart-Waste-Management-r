<?php
require_once '../includes/config.php';
requireRole('collector');
$pageTitle = 'Assigned Complaints';
$uid = $_SESSION['user_id'];

$msg=''; $msgType='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $id = (int)$_POST['complaint_id'];
    $status = sanitize($_POST['status']);
    $notes = sanitize($_POST['resolution_notes']);
    $resolved = in_array($status,['resolved','closed']) ? "resolved_at=NOW()," : '';
    $stmt = $conn->prepare("UPDATE complaints SET status=?, resolution_notes=?, {$resolved} updated_at=NOW() WHERE id=? AND assigned_to=?");
    $stmt->bind_param('ssii',$status,$notes,$id,$uid);
    if ($stmt->execute()) { $msg='Updated!'; $msgType='success'; }
}

$complaints = $conn->query("SELECT * FROM complaints WHERE assigned_to=$uid ORDER BY FIELD(priority,'urgent','high','medium','low'), created_at DESC")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">
<?php if ($msg): ?><div class="alert alert-<?= $msgType ?> alert-auto-hide"><?= $msg ?></div><?php endif; ?>

<div class="data-table">
    <div class="table-header"><h6>📢 My Assigned Complaints (<?= count($complaints) ?>)</h6></div>
    <div style="overflow-x:auto">
        <table class="table mb-0">
            <thead><tr><th>#</th><th>No.</th><th>Type</th><th>Location</th><th>Priority</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($complaints as $i => $c):
                    $sc=['pending'=>'warning','in_progress'=>'info','resolved'=>'success','closed'=>'secondary'];
                    $pc=['low'=>'secondary','medium'=>'primary','high'=>'warning','urgent'=>'danger'];
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><strong><?= $c['complaint_no'] ?></strong></td>
                    <td style="font-size:13px"><?= ucwords(str_replace('_',' ',$c['complaint_type'])) ?></td>
                    <td style="font-size:12px"><?= htmlspecialchars($c['location']??'—') ?></td>
                    <td><span class="badge bg-<?= $pc[$c['priority']] ?>-subtle text-<?= $pc[$c['priority']] ?>"><?= ucfirst($c['priority']) ?></span></td>
                    <td><span class="badge bg-<?= $sc[$c['status']]??'secondary' ?>-subtle text-<?= $sc[$c['status']]??'secondary' ?>"><?= ucwords(str_replace('_',' ',$c['status'])) ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick='updateComp(<?= json_encode($c) ?>)'>Update</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="updateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0"><h5 class="fw-bold" id="modalTitle">Update Complaint</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="complaint_id" id="uc_id">
                    <p id="uc_desc" class="text-muted p-2 bg-light rounded mb-3" style="font-size:13px"></p>
                    <div class="mb-3">
                        <label class="form-label">Update Status</label>
                        <select name="status" id="uc_status" class="form-select">
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Resolution Notes</label>
                        <textarea name="resolution_notes" id="uc_notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateComp(c) {
    document.getElementById('uc_id').value = c.id;
    document.getElementById('modalTitle').innerText = c.complaint_no;
    document.getElementById('uc_desc').innerText = c.description;
    document.getElementById('uc_status').value = c.status;
    document.getElementById('uc_notes').value = c.resolution_notes || '';
    new bootstrap.Modal(document.getElementById('updateModal')).show();
}
</script>
<?php include '../includes/footer.php'; ?>
