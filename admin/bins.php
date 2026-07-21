<?php
require_once '../includes/config.php';
requireRole('admin');
$pageTitle = 'Manage Waste Bins';

$msg = ''; $msgType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $bin_code    = sanitize($_POST['bin_code']);
        $location    = sanitize($_POST['location_name']);
        $area        = sanitize($_POST['area']);
        $capacity    = (int)$_POST['capacity_liters'];
        $fill        = (int)$_POST['current_fill_percent'];
        $bin_type    = sanitize($_POST['bin_type']);
        $status      = sanitize($_POST['status']);

        if ($action === 'add') {
            $stmt = $conn->prepare("INSERT INTO waste_bins (bin_code,location_name,area,capacity_liters,current_fill_percent,bin_type,status) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param('sssiiis', $bin_code, $location, $area, $capacity, $fill, $bin_type, $status);
            if ($stmt->execute()) { $msg = 'Bin added successfully!'; $msgType = 'success'; }
            else { $msg = 'Error: ' . $conn->error; $msgType = 'danger'; }
        } else {
            $id = (int)$_POST['bin_id'];
            $stmt = $conn->prepare("UPDATE waste_bins SET bin_code=?,location_name=?,area=?,capacity_liters=?,current_fill_percent=?,bin_type=?,status=? WHERE id=?");
            $stmt->bind_param('sssiissi', $bin_code, $location, $area, $capacity, $fill, $bin_type, $status, $id);
            if ($stmt->execute()) { $msg = 'Bin updated successfully!'; $msgType = 'success'; }
            else { $msg = 'Error: ' . $conn->error; $msgType = 'danger'; }
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['bin_id'];
        if ($conn->query("DELETE FROM waste_bins WHERE id=$id")) {
            $msg = 'Bin deleted.'; $msgType = 'warning';
        }
    }
}

// Filters
$search = sanitize($_GET['search'] ?? '');
$filterType = sanitize($_GET['type'] ?? '');
$filterStatus = sanitize($_GET['status'] ?? '');

$where = '1=1';
if ($search)       $where .= " AND (bin_code LIKE '%$search%' OR location_name LIKE '%$search%' OR area LIKE '%$search%')";
if ($filterType)   $where .= " AND bin_type='$filterType'";
if ($filterStatus) $where .= " AND status='$filterStatus'";

$bins = $conn->query("SELECT * FROM waste_bins WHERE $where ORDER BY current_fill_percent DESC")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>

<div class="main-content">
<div class="content-area">

<?php if ($msg): ?>
<div class="alert alert-<?= $msgType ?> alert-auto-hide alert-dismissible" style="border-radius:10px">
    <i class="bi bi-<?= $msgType==='success'?'check-circle':'exclamation-circle' ?> me-2"></i> <?= $msg ?>
</div>
<?php endif; ?>

<!-- Filter Bar -->
<div class="content-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="🔍 Search bins..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="general" <?= $filterType==='general'?'selected':'' ?>>General</option>
                <option value="recyclable" <?= $filterType==='recyclable'?'selected':'' ?>>Recyclable</option>
                <option value="organic" <?= $filterType==='organic'?'selected':'' ?>>Organic</option>
                <option value="hazardous" <?= $filterType==='hazardous'?'selected':'' ?>>Hazardous</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" <?= $filterStatus==='active'?'selected':'' ?>>Active</option>
                <option value="full" <?= $filterStatus==='full'?'selected':'' ?>>Full</option>
                <option value="maintenance" <?= $filterStatus==='maintenance'?'selected':'' ?>>Maintenance</option>
            </select>
        </div>
        <div class="col-md-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="bins.php" class="btn btn-outline-secondary ms-1">Reset</a>
        </div>
        <div class="col-md-auto ms-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBinModal">
                <i class="bi bi-plus-circle me-1"></i> Add New Bin
            </button>
        </div>
    </form>
</div>

<!-- Bins Table -->
<div class="data-table">
    <div class="table-header">
        <h6>🗑️ Waste Bins (<?= count($bins) ?> records)</h6>
    </div>
    <div style="overflow-x:auto">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Bin Code</th>
                    <th>Location</th>
                    <th>Area</th>
                    <th>Type</th>
                    <th>Fill Level</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bins as $i => $bin):
                    $cl = getBinStatusClass($bin['current_fill_percent']);
                    $colors = ['success'=>'#1a7a4c','warning'=>'#f0a500','danger'=>'#e53935'];
                    $color = $colors[$cl];
                    $typeEmoji = ['general'=>'🗑️','recyclable'=>'♻️','organic'=>'🌿','hazardous'=>'☣️'];
                    $statusBadge = ['active'=>'success','full'=>'danger','maintenance'=>'warning','inactive'=>'secondary'];
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><strong><?= $bin['bin_code'] ?></strong></td>
                    <td><?= htmlspecialchars($bin['location_name']) ?></td>
                    <td><span class="badge bg-light text-dark"><?= htmlspecialchars($bin['area']) ?></span></td>
                    <td><?= $typeEmoji[$bin['bin_type']] ?? '' ?> <?= ucfirst($bin['bin_type']) ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="fill-bar" style="width:80px">
                                <div class="fill-bar-inner" style="width:<?= $bin['current_fill_percent'] ?>%;background:<?= $color ?>"></div>
                            </div>
                            <span style="font-weight:600;color:<?= $color ?>;font-size:13px"><?= $bin['current_fill_percent'] ?>%</span>
                        </div>
                    </td>
                    <td><?= $bin['capacity_liters'] ?>L</td>
                    <td>
                        <span class="badge bg-<?= $statusBadge[$bin['status']] ?>-subtle text-<?= $statusBadge[$bin['status']] ?> status-badge">
                            <?= ucfirst($bin['status']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary me-1"
                            onclick='openEditModal(<?= json_encode($bin) ?>)'>
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" style="display:inline" onsubmit="return confirm('Delete this bin?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="bin_id" value="<?= $bin['id'] ?>">
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

<!-- Add Bin Modal -->
<div class="modal fade" id="addBinModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">➕ Add New Bin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Bin Code *</label>
                            <input type="text" name="bin_code" class="form-control" placeholder="BIN-XXX" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Area</label>
                            <input type="text" name="area" class="form-control" placeholder="Zone A">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Location Name *</label>
                            <input type="text" name="location_name" class="form-control" placeholder="e.g. City Park Entrance" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Capacity (Liters)</label>
                            <input type="number" name="capacity_liters" class="form-control" value="100">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Fill Level (%)</label>
                            <input type="number" name="current_fill_percent" class="form-control" value="0" min="0" max="100">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Bin Type</label>
                            <select name="bin_type" class="form-select">
                                <option value="general">General</option>
                                <option value="recyclable">Recyclable</option>
                                <option value="organic">Organic</option>
                                <option value="hazardous">Hazardous</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="full">Full</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Bin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Bin Modal -->
<div class="modal fade" id="editBinModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">✏️ Edit Bin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="bin_id" id="editBinId">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Bin Code *</label>
                            <input type="text" name="bin_code" id="editBinCode" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Area</label>
                            <input type="text" name="area" id="editArea" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Location Name *</label>
                            <input type="text" name="location_name" id="editLocation" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Capacity (Liters)</label>
                            <input type="number" name="capacity_liters" id="editCapacity" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Fill Level (%)</label>
                            <input type="number" name="current_fill_percent" id="editFill" class="form-control" min="0" max="100">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Bin Type</label>
                            <select name="bin_type" id="editType" class="form-select">
                                <option value="general">General</option>
                                <option value="recyclable">Recyclable</option>
                                <option value="organic">Organic</option>
                                <option value="hazardous">Hazardous</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Status</label>
                            <select name="status" id="editStatus" class="form-select">
                                <option value="active">Active</option>
                                <option value="full">Full</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Bin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(bin) {
    document.getElementById('editBinId').value = bin.id;
    document.getElementById('editBinCode').value = bin.bin_code;
    document.getElementById('editArea').value = bin.area;
    document.getElementById('editLocation').value = bin.location_name;
    document.getElementById('editCapacity').value = bin.capacity_liters;
    document.getElementById('editFill').value = bin.current_fill_percent;
    document.getElementById('editType').value = bin.bin_type;
    document.getElementById('editStatus').value = bin.status;
    new bootstrap.Modal(document.getElementById('editBinModal')).show();
}
</script>

<?php include '../includes/footer.php'; ?>
