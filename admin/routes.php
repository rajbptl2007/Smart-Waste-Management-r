<?php
require_once '../includes/config.php';
requireRole('admin');
$pageTitle = 'Collection Routes';

$msg=''; $msgType='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $name    = sanitize($_POST['route_name']);
        $area    = sanitize($_POST['area']);
        $vid     = (int)$_POST['assigned_vehicle_id'];
        $cid     = (int)$_POST['assigned_collector_id'];
        $day     = sanitize($_POST['schedule_day']);
        $time    = sanitize($_POST['schedule_time']);
        $status  = sanitize($_POST['status']);
        $notes   = sanitize($_POST['notes']);
        $vidVal  = $vid ?: null;
        $cidVal  = $cid ?: null;

        // BUG-005 FIX: Prevent duplicate route assignments for the same
        // vehicle or collector on the same day/time slot.
        $dupFound = false;
        if ($vidVal) {
            $chk = $conn->prepare("SELECT id FROM collection_routes WHERE assigned_vehicle_id=? AND schedule_day=? AND schedule_time<=>? AND status='active'");
            $chk->bind_param('iss', $vidVal, $day, $time);
            $chk->execute();
            if ($chk->get_result()->num_rows > 0) $dupFound = true;
        }
        if (!$dupFound && $cidVal) {
            $chk = $conn->prepare("SELECT id FROM collection_routes WHERE assigned_collector_id=? AND schedule_day=? AND schedule_time<=>? AND status='active'");
            $chk->bind_param('iss', $cidVal, $day, $time);
            $chk->execute();
            if ($chk->get_result()->num_rows > 0) $dupFound = true;
        }

        if ($dupFound) {
            $msg = 'This vehicle or collector is already assigned to another active route at the same day and time.';
            $msgType = 'danger';
        } else {
            // BUG-002 FIX: use a prepared statement instead of concatenating
            // user input directly into the SQL string.
            $stmt = $conn->prepare("INSERT INTO collection_routes (route_name,area,assigned_vehicle_id,assigned_collector_id,schedule_day,schedule_time,status,notes) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->bind_param('ssiissss', $name, $area, $vidVal, $cidVal, $day, $time, $status, $notes);
            if ($stmt->execute()) {
                $msg='Route added!'; $msgType='success';
            } else {
                $msg='Error: '.$conn->error; $msgType='danger';
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['route_id'];
        $conn->query("DELETE FROM collection_routes WHERE id=$id");
        $msg='Route deleted.'; $msgType='warning';
    } elseif ($action === 'toggle') {
        $id = (int)$_POST['route_id'];
        $conn->query("UPDATE collection_routes SET status=IF(status='active','inactive','active') WHERE id=$id");
        $msg='Status toggled!'; $msgType='info';
    }
}

$routes = $conn->query("
    SELECT r.*, v.vehicle_number, u.full_name as collector_name,
           COUNT(rb.id) as bin_count
    FROM collection_routes r
    LEFT JOIN vehicles v ON r.assigned_vehicle_id=v.id
    LEFT JOIN users u ON r.assigned_collector_id=u.id
    LEFT JOIN route_bins rb ON r.id=rb.route_id
    GROUP BY r.id ORDER BY r.created_at DESC
")->fetch_all(MYSQLI_ASSOC);

$vehicles   = $conn->query("SELECT id,vehicle_number FROM vehicles WHERE status!='inactive'")->fetch_all(MYSQLI_ASSOC);
$collectors = $conn->query("SELECT id,full_name FROM users WHERE role IN ('collector','admin') AND status='active'")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">
<?php if ($msg): ?><div class="alert alert-<?= $msgType ?> alert-auto-hide"><?= $msg ?></div><?php endif; ?>

<div class="d-flex justify-content-end mb-4">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRouteModal">
        <i class="bi bi-plus-circle me-1"></i> Add Route
    </button>
</div>

<div class="data-table">
    <div class="table-header"><h6>🗺️ Collection Routes (<?= count($routes) ?>)</h6></div>
    <div style="overflow-x:auto">
        <table class="table mb-0">
            <thead><tr><th>#</th><th>Route Name</th><th>Area</th><th>Vehicle</th><th>Collector</th><th>Day</th><th>Time</th><th>Bins</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($routes as $i => $r):
                    $sc = ['active'=>'success','inactive'=>'secondary','completed'=>'info'];
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><strong><?= htmlspecialchars($r['route_name']) ?></strong></td>
                    <td><?= htmlspecialchars($r['area']) ?></td>
                    <td><?= htmlspecialchars($r['vehicle_number'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['collector_name'] ?? '—') ?></td>
                    <td><?= $r['schedule_day'] ?></td>
                    <td><?= $r['schedule_time'] ? date('h:i A',strtotime($r['schedule_time'])) : '—' ?></td>
                    <td><span class="badge bg-light text-dark"><?= $r['bin_count'] ?> bins</span></td>
                    <td><span class="badge bg-<?= $sc[$r['status']]??'secondary' ?>-subtle text-<?= $sc[$r['status']]??'secondary' ?> status-badge"><?= ucfirst($r['status']) ?></span></td>
                    <td>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="route_id" value="<?= $r['id'] ?>">
                            <button class="btn btn-sm btn-outline-<?= $r['status']==='active'?'warning':'success' ?>">
                                <i class="bi bi-toggle-<?= $r['status']==='active'?'on':'off' ?>"></i>
                            </button>
                        </form>
                        <form method="POST" style="display:inline" onsubmit="return confirm('Delete?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="route_id" value="<?= $r['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger ms-1"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>

<!-- Add Route Modal -->
<div class="modal fade" id="addRouteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0"><h5 class="fw-bold">🗺️ Add Route</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-8"><label class="form-label">Route Name *</label><input type="text" name="route_name" class="form-control" required></div>
                        <div class="col-4"><label class="form-label">Area</label><input type="text" name="area" class="form-control"></div>
                        <div class="col-6"><label class="form-label">Assigned Vehicle</label>
                            <select name="assigned_vehicle_id" class="form-select">
                                <option value="">-- None --</option>
                                <?php foreach($vehicles as $v): ?><option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['vehicle_number']) ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6"><label class="form-label">Assigned Collector</label>
                            <select name="assigned_collector_id" class="form-select">
                                <option value="">-- None --</option>
                                <?php foreach($collectors as $c): ?><option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['full_name']) ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6"><label class="form-label">Day</label>
                            <select name="schedule_day" class="form-select">
                                <?php foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $d): ?>
                                <option value="<?= $d ?>"><?= $d ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6"><label class="form-label">Time</label><input type="time" name="schedule_time" class="form-control"><select name="ampm" class="form-control">  <option value="AM">AM</option>  <option value="PM">PM</option></select></div>
                        <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
                        <div class="col-6"><label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Route</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
