<?php
require_once '../includes/config.php';
requireRole('collector');
$pageTitle = 'My Routes';
$uid = $_SESSION['user_id'];

$routes = $conn->query("
    SELECT r.*, v.vehicle_number, v.vehicle_type
    FROM collection_routes r
    LEFT JOIN vehicles v ON r.assigned_vehicle_id=v.id
    WHERE r.assigned_collector_id=$uid
    ORDER BY FIELD(r.schedule_day,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')
")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">

<?php foreach ($routes as $r):
    $bins = $conn->query("SELECT wb.*, rb.collection_order FROM waste_bins wb JOIN route_bins rb ON wb.id=rb.bin_id WHERE rb.route_id={$r['id']} ORDER BY rb.collection_order")->fetch_all(MYSQLI_ASSOC);
    $sc = ['active'=>'success','inactive'=>'secondary','completed'=>'info'];
?>
<div class="content-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h5 class="fw-bold mb-1">🗺️ <?= htmlspecialchars($r['route_name']) ?></h5>
            <p class="text-muted mb-0" style="font-size:14px">
                📍 <?= htmlspecialchars($r['area']) ?> &nbsp;|&nbsp;
                📅 <?= $r['schedule_day'] ?> at <?= date('h:i A',strtotime($r['schedule_time']?:'00:00:00')) ?> &nbsp;|&nbsp;
                🚛 <?= htmlspecialchars($r['vehicle_number']??'No vehicle') ?>
            </p>
        </div>
        <span class="badge bg-<?= $sc[$r['status']]??'secondary' ?>-subtle text-<?= $sc[$r['status']]??'secondary' ?> px-3 py-2"><?= ucfirst($r['status']) ?></span>
    </div>

    <?php if ($r['notes']): ?>
    <div class="alert alert-info mb-4" style="font-size:13px"><i class="bi bi-info-circle me-2"></i><?= htmlspecialchars($r['notes']) ?></div>
    <?php endif; ?>

    <h6 class="fw-bold mb-3">Bins on this Route (<?= count($bins) ?>)</h6>
    <div class="row g-3">
        <?php foreach ($bins as $bin):
            $cl=getBinStatusClass($bin['current_fill_percent']);
            $colors=['success'=>'#1a7a4c','warning'=>'#f0a500','danger'=>'#e53935'];
            $c=$colors[$cl];
            $typeEmoji=['general'=>'🗑️','recyclable'=>'♻️','organic'=>'🌿','hazardous'=>'☣️'];
        ?>
        <div class="col-md-4">
            <div class="p-3 rounded-3" style="border:1px solid #e5e7eb;background:#f9fafb">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <div class="fw-bold"><?= $typeEmoji[$bin['bin_type']]??'' ?> <?= $bin['bin_code'] ?></div>
                        <div style="font-size:12px;color:#6b7280"><?= htmlspecialchars($bin['location_name']) ?></div>
                    </div>
                    <span class="badge rounded-pill" style="background:<?= $c ?>22;color:<?= $c ?>;height:fit-content">Stop <?= $bin['collection_order'] ?></span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="fill-bar flex-grow-1"><div class="fill-bar-inner" style="width:<?= $bin['current_fill_percent'] ?>%;background:<?= $c ?>"></div></div>
                    <strong style="font-size:13px;color:<?= $c ?>"><?= $bin['current_fill_percent'] ?>%</strong>
                </div>
                <?php if ($bin['last_collected']): ?>
                <div style="font-size:11px;color:#9ca3af;margin-top:6px">Last: <?= date('d M H:i',strtotime($bin['last_collected'])) ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>

<?php if (empty($routes)): ?>
<div class="text-center py-5 text-muted">
    <i class="bi bi-map fs-1 d-block mb-2"></i>
    <h6>No routes assigned to you yet.</h6>
    <p>Contact your admin to assign collection routes.</p>
</div>
<?php endif; ?>

</div>
</div>
<?php include '../includes/footer.php'; ?>
