<?php
require_once '../includes/config.php';
requireRole('collector');
$pageTitle = 'Log Collection';
$uid = $_SESSION['user_id'];

$msg=''; $msgType='';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $bin_id   = (int)$_POST['bin_id'];
    $route_id = (int)$_POST['route_id'] ?: 'NULL';
    $vehicle_id = (int)$_POST['vehicle_id'] ?: 'NULL';
    $weight   = (float)$_POST['collected_weight_kg'];
    $before   = (int)$_POST['before_fill_percent'];
    $after    = (int)$_POST['after_fill_percent'];
    $notes    = sanitize($_POST['notes']);
    $status   = sanitize($_POST['status']);

    $route_id_val = $route_id === 'NULL' ? null : (int)$route_id;
    $vehicle_id_val = $vehicle_id === 'NULL' ? null : (int)$vehicle_id;

    // BUG-006 FIX: Prevent duplicate collection entries for the same bin
    // and route on the same collection date.
    $dupFound = false;
    if ($route_id_val) {
        $chk = $conn->prepare("SELECT id FROM collection_logs WHERE route_id=? AND bin_id=? AND DATE(collection_date)=CURDATE()");
        $chk->bind_param('ii', $route_id_val, $bin_id);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) $dupFound = true;
    }

    if ($dupFound) {
        $msg = 'A collection for this bin on this route has already been logged today.'; $msgType = 'warning';
    } else {
        $stmt = $conn->prepare("INSERT INTO collection_logs (route_id,bin_id,vehicle_id,collector_id,collected_weight_kg,before_fill_percent,after_fill_percent,notes,status) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('iiiidiiss', $route_id_val, $bin_id, $vehicle_id_val, $uid, $weight, $before, $after, $notes, $status);

        if ($stmt->execute()) {
            // Update bin fill level
            $upd = $conn->prepare("UPDATE waste_bins SET current_fill_percent=?, last_collected=NOW(), status=IF(?>=90,'full','active') WHERE id=?");
            $upd->bind_param('iii', $after, $after, $bin_id);
            $upd->execute();
            $msg='Collection logged successfully!'; $msgType='success';
        } else {
            $msg='Error: '.$conn->error; $msgType='danger';
        }
    }
}

// Get bins on my routes
$bins = $conn->query("
    SELECT DISTINCT wb.id, wb.bin_code, wb.location_name, wb.area, wb.current_fill_percent, wb.bin_type,
           cr.id as route_id, cr.route_name
    FROM waste_bins wb
    JOIN route_bins rb ON wb.id=rb.bin_id
    JOIN collection_routes cr ON rb.route_id=cr.id
    WHERE cr.assigned_collector_id=$uid AND cr.status='active'
    ORDER BY wb.current_fill_percent DESC
")->fetch_all(MYSQLI_ASSOC);

$allBins = $conn->query("SELECT id,bin_code,location_name,current_fill_percent FROM waste_bins WHERE status!='inactive' ORDER BY bin_code")->fetch_all(MYSQLI_ASSOC);
$vehicles = $conn->query("SELECT id,vehicle_number FROM vehicles WHERE status IN ('available','on_route')")->fetch_all(MYSQLI_ASSOC);
$routes = $conn->query("SELECT id,route_name FROM collection_routes WHERE assigned_collector_id=$uid AND status='active'")->fetch_all(MYSQLI_ASSOC);

// Today's logs
$todayLogs = $conn->query("SELECT cl.*,wb.bin_code,wb.location_name FROM collection_logs cl JOIN waste_bins wb ON cl.bin_id=wb.id WHERE cl.collector_id=$uid AND DATE(collection_date)=CURDATE() ORDER BY cl.collection_date DESC")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">

<?php if ($msg): ?><div class="alert alert-<?= $msgType ?> alert-auto-hide"><?= $msg ?></div><?php endif; ?>

<div class="row g-3">
    <!-- Log Form -->
    <div class="col-md-5">
        <div class="content-card p-4">
            <h6 class="fw-bold mb-4">📝 Log New Collection</h6>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Select Bin *</label>
                    <select name="bin_id" id="binSelect" class="form-select" required onchange="loadBinFill(this)">
                        <option value="">-- Select Bin --</option>
                        <optgroup label="My Route Bins">
                            <?php foreach ($bins as $b): ?>
                            <option value="<?= $b['id'] ?>" data-fill="<?= $b['current_fill_percent'] ?>" data-route="<?= $b['route_id'] ?>">
                                <?= $b['bin_code'] ?> - <?= htmlspecialchars($b['location_name']) ?> (<?= $b['current_fill_percent'] ?>%)
                            </option>
                            <?php endforeach; ?>
                        </optgroup>
                        <optgroup label="All Bins">
                            <?php foreach ($allBins as $b): ?>
                            <option value="<?= $b['id'] ?>" data-fill="<?= $b['current_fill_percent'] ?>" data-route="">
                                <?= $b['bin_code'] ?> - <?= htmlspecialchars($b['location_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Route</label>
                    <select name="route_id" id="routeSelect" class="form-select">
                        <option value="">-- None --</option>
                        <?php foreach ($routes as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['route_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Vehicle</label>
                    <select name="vehicle_id" class="form-select">
                        <option value="">-- None --</option>
                        <?php foreach ($vehicles as $v): ?>
                        <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['vehicle_number']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Fill Before (%) *</label>
                        <input type="number" name="before_fill_percent" id="beforeFill" class="form-control" min="0" max="100" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Fill After (%)</label>
                        <input type="number" name="after_fill_percent" class="form-control" min="0" max="100" value="5">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Weight Collected (kg) *</label>
                    <input type="number" name="collected_weight_kg" class="form-control" step="0.1" min="0" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="completed">Completed</option>
                        <option value="partial">Partial</option>
                        <option value="skipped">Skipped</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Any observations..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-clipboard-plus me-1"></i> Log Collection
                </button>
            </form>
        </div>
    </div>

    <!-- Bins Status -->
    <div class="col-md-7">
        <div class="data-table mb-3">
            <div class="table-header"><h6>🗑️ My Route Bins</h6></div>
            <div style="overflow-x:auto">
                <table class="table mb-0">
                    <thead><tr><th>Bin</th><th>Location</th><th>Type</th><th>Fill Level</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach ($bins as $b):
                            $cl=getBinStatusClass($b['current_fill_percent']);
                            $colors=['success'=>'#1a7a4c','warning'=>'#f0a500','danger'=>'#e53935'];
                            $c=$colors[$cl];
                        ?>
                        <tr>
                            <td><strong><?= $b['bin_code'] ?></strong></td>
                            <td style="font-size:12px"><?= htmlspecialchars($b['location_name']) ?></td>
                            <td><span class="badge bg-light text-dark"><?= ucfirst($b['bin_type']) ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="fill-bar"><div class="fill-bar-inner" style="width:<?= $b['current_fill_percent'] ?>%;background:<?= $c ?>"></div></div>
                                    <span style="font-weight:600;font-size:12px;color:<?= $c ?>"><?= $b['current_fill_percent'] ?>%</span>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="quickLog(<?= $b['id'] ?>,<?= $b['current_fill_percent'] ?>,<?= $b['route_id'] ?>)">
                                    Quick Log
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Today's Logs -->
        <div class="data-table">
            <div class="table-header"><h6>✅ Today's Collections (<?= count($todayLogs) ?>)</h6></div>
            <?php if (empty($todayLogs)): ?>
            <div class="text-center text-muted py-4">No collections logged today yet.</div>
            <?php else: ?>
            <table class="table mb-0">
                <thead><tr><th>Bin</th><th>Location</th><th>Weight</th><th>Time</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($todayLogs as $log):
                        $sc=['completed'=>'success','skipped'=>'warning','partial'=>'info'];
                    ?>
                    <tr>
                        <td><strong><?= $log['bin_code'] ?></strong></td>
                        <td style="font-size:12px"><?= htmlspecialchars($log['location_name']) ?></td>
                        <td><?= $log['collected_weight_kg'] ?>kg</td>
                        <td style="font-size:12px"><?= date('h:i A',strtotime($log['collection_date'])) ?></td>
                        <td><span class="badge bg-<?= $sc[$log['status']]??'secondary' ?>-subtle text-<?= $sc[$log['status']]??'secondary' ?>"><?= ucfirst($log['status']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
</div>

<script>
function loadBinFill(sel) {
    const opt = sel.options[sel.selectedIndex];
    document.getElementById('beforeFill').value = opt.dataset.fill || 0;
    const routeId = opt.dataset.route;
    if (routeId) document.getElementById('routeSelect').value = routeId;
}

function quickLog(binId, fillPct, routeId) {
    document.getElementById('binSelect').value = binId;
    document.getElementById('beforeFill').value = fillPct;
    if (routeId) document.getElementById('routeSelect').value = routeId;
    window.scrollTo({top:0, behavior:'smooth'});
}
</script>
<?php include '../includes/footer.php'; ?>
