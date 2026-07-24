<?php
require_once '../includes/config.php';
requireRole('admin');
$pageTitle = 'Manage Vehicles';

$msg = ''; $msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $vnum   = strtoupper(trim(sanitize($_POST['vehicle_number'])));
        $vtype  = sanitize($_POST['vehicle_type']);
        $cap    = (float)$_POST['capacity_tons'];
        $driver = sanitize($_POST['driver_name']);
        $phone  = sanitize($_POST['driver_phone']);
        $status = sanitize($_POST['status']);
        $fuel   = sanitize($_POST['fuel_type']);

        // BUG-004 FIX: validate vehicle registration number format
        // Expected Indian format, e.g. MH12AB1234
        if (!preg_match('/^[A-Z]{2}[0-9]{1,2}[A-Z]{1,3}[0-9]{4}$/', $vnum)) {
            $msg = 'Invalid vehicle registration number format. Expected format like MH12AB1234.';
            $msgType = 'danger';
        } elseif ($action === 'add') {
            $stmt = $conn->prepare("INSERT INTO vehicles (vehicle_number,vehicle_type,capacity_tons,driver_name,driver_phone,status,fuel_type) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param('ssdssss', $vnum,$vtype,$cap,$driver,$phone,$status,$fuel);
            if ($stmt->execute()) { $msg='Vehicle added!'; $msgType='success'; }
            else { $msg='Error: '.$conn->error; $msgType='danger'; }
        } else {
            $id = (int)$_POST['vehicle_id'];
            $stmt = $conn->prepare("UPDATE vehicles SET vehicle_number=?,vehicle_type=?,capacity_tons=?,driver_name=?,driver_phone=?,status=?,fuel_type=? WHERE id=?");
            $stmt->bind_param('ssdssssi', $vnum,$vtype,$cap,$driver,$phone,$status,$fuel,$id);
            if ($stmt->execute()) { $msg='Vehicle updated!'; $msgType='success'; }
            else { $msg='Error: '.$conn->error; $msgType='danger'; }
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['vehicle_id'];
        $conn->query("DELETE FROM vehicles WHERE id=$id");
        $msg='Vehicle deleted.'; $msgType='warning';
    }
}

$vehicles = $conn->query("SELECT * FROM vehicles ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>

<div class="main-content">
<div class="content-area">

<?php if ($msg): ?>
<div class="alert alert-<?= $msgType ?> alert-auto-hide"><?= $msg ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div></div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
        <i class="bi bi-plus-circle me-1"></i> Add Vehicle
    </button>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <?php
    $statuses = ['available','on_route','maintenance','inactive'];
    $statusIcons = ['available'=>'check-circle','on_route'=>'truck','maintenance'=>'tools','inactive'=>'x-circle'];
    $statusColors = ['available'=>'success','on_route'=>'primary','maintenance'=>'warning','inactive'=>'secondary'];
    foreach ($statuses as $s):
        $cnt = $conn->query("SELECT COUNT(*) as c FROM vehicles WHERE status='$s'")->fetch_assoc()['c'];
    ?>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:var(--bs-<?= $statusColors[$s] ?>-bg-subtle,#f0f0f0);color:var(--bs-<?= $statusColors[$s] ?>)">
                <i class="bi bi-<?= $statusIcons[$s] ?>"></i>
            </div>
            <div class="stat-value"><?= $cnt ?></div>
            <div class="stat-label"><?= ucwords(str_replace('_',' ',$s)) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Vehicles Table -->
<div class="data-table">
    <div class="table-header">
        <h6>🚛 Fleet Management (<?= count($vehicles) ?> vehicles)</h6>
    </div>
    <div style="overflow-x:auto">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th><th>Vehicle No.</th><th>Type</th><th>Capacity</th>
                    <th>Driver</th><th>Phone</th><th>Fuel</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicles as $i => $v):
                    $sc = ['available'=>'success','on_route'=>'primary','maintenance'=>'warning','inactive'=>'secondary'];
                    $fuelEmoji = ['diesel'=>'⛽','electric'=>'⚡','cng'=>'💨'];
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><strong><?= htmlspecialchars($v['vehicle_number']) ?></strong></td>
                    <td><?= htmlspecialchars($v['vehicle_type']) ?></td>
                    <td><?= $v['capacity_tons'] ?> tons</td>
                    <td><?= htmlspecialchars($v['driver_name']) ?></td>
                    <td><?= htmlspecialchars($v['driver_phone']) ?></td>
                    <td><?= $fuelEmoji[$v['fuel_type']] ?? '' ?> <?= ucfirst($v['fuel_type']) ?></td>
                    <td>
                        <span class="badge bg-<?= $sc[$v['status']] ?>-subtle text-<?= $sc[$v['status']] ?> status-badge">
                            <?= ucwords(str_replace('_',' ',$v['status'])) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick='editVehicle(<?= json_encode($v) ?>)'>
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" style="display:inline" onsubmit="return confirm('Delete?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="vehicle_id" value="<?= $v['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
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

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0"><h5 class="modal-title fw-bold">🚛 Add Vehicle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-6"><label class="form-label">Vehicle Number *</label><input type="text" name="vehicle_number" class="form-control" style="text-transform:uppercase" required pattern="[A-Za-z]{2}[0-9]{1,2}[A-Za-z]{1,3}[0-9]{4}" title="Format like MH12AB1234" placeholder="e.g. MH12AB1234"></div>
                        <div class="col-6"><label class="form-label">Vehicle Type</label><input type="text" name="vehicle_type" class="form-control" value="Garbage Truck"></div>
                        <div class="col-6"><label class="form-label">Capacity (tons)</label><input type="number" name="capacity_tons" class="form-control" value="5" step="0.5"></div>
                        <div class="col-6"><label class="form-label">Fuel Type</label>
                            <select name="fuel_type" class="form-select">
                                <option value="diesel">Diesel</option>
                                <option value="electric">Electric</option>
                                <option value="cng">CNG</option>
                            </select>
                        </div>
                        <div class="col-6"><label class="form-label">Driver Name</label><input type="text" name="driver_name" class="form-control"></div>
                        <div class="col-6"><label class="form-label">Driver Phone</label><input type="text" name="driver_phone" class="form-control"></div>
                        <div class="col-12"><label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="available">Available</option>
                                <option value="on_route">On Route</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Vehicle Modal -->
<div class="modal fade" id="editVehicleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0"><h5 class="modal-title fw-bold">✏️ Edit Vehicle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="vehicle_id" id="ev_id">
                    <div class="row g-3">
                        <div class="col-6"><label class="form-label">Vehicle Number</label><input type="text" name="vehicle_number" id="ev_num" class="form-control" style="text-transform:uppercase" required pattern="[A-Za-z]{2}[0-9]{1,2}[A-Za-z]{1,3}[0-9]{4}" title="Format like MH12AB1234"></div>
                        <div class="col-6"><label class="form-label">Vehicle Type</label><input type="text" name="vehicle_type" id="ev_type" class="form-control"></div>
                        <div class="col-6"><label class="form-label">Capacity (tons)</label><input type="number" name="capacity_tons" id="ev_cap" class="form-control" step="0.5"></div>
                        <div class="col-6"><label class="form-label">Fuel Type</label>
                            <select name="fuel_type" id="ev_fuel" class="form-select">
                                <option value="diesel">Diesel</option>
                                <option value="electric">Electric</option>
                                <option value="cng">CNG</option>
                            </select>
                        </div>
                        <div class="col-6"><label class="form-label">Driver Name</label><input type="text" name="driver_name" id="ev_driver" class="form-control"></div>
                        <div class="col-6"><label class="form-label">Driver Phone</label><input type="text" name="driver_phone" id="ev_phone" class="form-control"></div>
                        <div class="col-12"><label class="form-label">Status</label>
                            <select name="status" id="ev_status" class="form-select">
                                <option value="available">Available</option>
                                <option value="on_route">On Route</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editVehicle(v) {
    document.getElementById('ev_id').value = v.id;
    document.getElementById('ev_num').value = v.vehicle_number;
    document.getElementById('ev_type').value = v.vehicle_type;
    document.getElementById('ev_cap').value = v.capacity_tons;
    document.getElementById('ev_fuel').value = v.fuel_type;
    document.getElementById('ev_driver').value = v.driver_name;
    document.getElementById('ev_phone').value = v.driver_phone;
    document.getElementById('ev_status').value = v.status;
    new bootstrap.Modal(document.getElementById('editVehicleModal')).show();
}
</script>
<?php include '../includes/footer.php'; ?>
