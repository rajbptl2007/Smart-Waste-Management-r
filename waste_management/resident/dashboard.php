<?php
require_once '../includes/config.php';
requireRole('resident');
$pageTitle = 'Resident Dashboard';
$uid = $_SESSION['user_id'];

$myComplaints   = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE resident_id=$uid")->fetch_assoc()['c'];
$openComplaints = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE resident_id=$uid AND status IN ('pending','in_progress')")->fetch_assoc()['c'];
$resolvedComplaints = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE resident_id=$uid AND status='resolved'")->fetch_assoc()['c'];
$nearbyFullBins = $conn->query("SELECT COUNT(*) as c FROM waste_bins WHERE current_fill_percent>=80")->fetch_assoc()['c'];

$recentComplaints = $conn->query("SELECT * FROM complaints WHERE resident_id=$uid ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// Today's schedule
$todayDay = date('l');
$todaySchedule = $conn->query("
    SELECT cr.route_name, cr.area, cr.schedule_time, v.vehicle_number, u.full_name as collector
    FROM collection_routes cr
    LEFT JOIN vehicles v ON cr.assigned_vehicle_id=v.id
    LEFT JOIN users u ON cr.assigned_collector_id=u.id
    WHERE cr.schedule_day='$todayDay' AND cr.status='active'
")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">

<div class="p-4 mb-4" style="background:linear-gradient(135deg,#7c3aed,#a855f7);border-radius:16px;color:#fff">
    <h4 class="fw-bold mb-1">Hello, <?= htmlspecialchars(explode(' ',$_SESSION['user_name'])[0]) ?>! 🏠</h4>
    <p class="mb-0 opacity-75">Track waste collection & report issues in your area</p>
</div>

<div class="row g-3 mb-4">
    <?php foreach ([
        ['My Complaints', $myComplaints, 'chat-left-text', '#7c3aed'],
        ['Open Issues', $openComplaints, 'exclamation-triangle', '#f0a500'],
        ['Resolved', $resolvedComplaints, 'check-circle', '#1a7a4c'],
        ['Full Bins Nearby', $nearbyFullBins, 'trash3-fill', '#e53935'],
    ] as [$l,$v,$ic,$col]): ?>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:<?= $col ?>22;color:<?= $col ?>"><i class="bi bi-<?= $ic ?>"></i></div>
            <div class="stat-value"><?= $v ?></div>
            <div class="stat-label"><?= $l ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-3">
    <!-- Today's Schedule -->
    <div class="col-md-5">
        <div class="content-card p-4">
            <h6 class="fw-bold mb-3">📅 Today's Collection Schedule</h6>
            <p class="text-muted" style="font-size:13px">Today is <strong><?= $todayDay ?></strong></p>
            <?php if (empty($todaySchedule)): ?>
            <div class="text-center text-muted py-3">
                <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                No collections scheduled today.
            </div>
            <?php else: ?>
            <?php foreach ($todaySchedule as $s): ?>
            <div class="p-3 rounded-3 mb-2" style="border:1px solid #e5e7eb;background:#f9fafb">
                <div class="fw-bold" style="font-size:14px">🗺️ <?= htmlspecialchars($s['route_name']) ?></div>
                <div style="font-size:12px;color:#6b7280">
                    📍 <?= htmlspecialchars($s['area']) ?> &nbsp;|&nbsp;
                    ⏰ <?= date('h:i A',strtotime($s['schedule_time']?:'00:00:00')) ?>
                </div>
                <div style="font-size:12px;color:#6b7280">
                    🚛 <?= htmlspecialchars($s['vehicle_number']??'TBD') ?> &nbsp;|&nbsp;
                    👤 <?= htmlspecialchars($s['collector']??'TBD') ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>

            <div class="mt-3">
                <a href="new_complaint.php" class="btn btn-primary w-100">
                    <i class="bi bi-megaphone me-1"></i> Report an Issue
                </a>
            </div>
        </div>
    </div>

    <!-- My Complaints -->
    <div class="col-md-7">
        <div class="data-table">
            <div class="table-header">
                <h6>📢 My Complaints</h6>
                <a href="new_complaint.php" class="btn btn-sm btn-primary">New Complaint</a>
            </div>
            <?php if (empty($recentComplaints)): ?>
            <div class="text-center text-muted py-4">
                <i class="bi bi-chat-left-text fs-2 d-block mb-2"></i>
                No complaints yet. <a href="new_complaint.php">File one</a>?
            </div>
            <?php else: ?>
            <table class="table mb-0">
                <thead><tr><th>No.</th><th>Type</th><th>Priority</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($recentComplaints as $c):
                        $sc=['pending'=>'warning','in_progress'=>'info','resolved'=>'success','closed'=>'secondary'];
                        $pc=['low'=>'secondary','medium'=>'primary','high'=>'warning','urgent'=>'danger'];
                    ?>
                    <tr>
                        <td><strong style="font-size:12px"><?= $c['complaint_no'] ?></strong></td>
                        <td style="font-size:13px"><?= ucwords(str_replace('_',' ',$c['complaint_type'])) ?></td>
                        <td><span class="badge bg-<?= $pc[$c['priority']] ?>-subtle text-<?= $pc[$c['priority']] ?>"><?= ucfirst($c['priority']) ?></span></td>
                        <td><span class="badge bg-<?= $sc[$c['status']]??'secondary' ?>-subtle text-<?= $sc[$c['status']]??'secondary' ?>"><?= ucwords(str_replace('_',' ',$c['status'])) ?></span></td>
                        <td style="font-size:11px"><?= date('d M Y',strtotime($c['created_at'])) ?></td>
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
<?php include '../includes/footer.php'; ?>
