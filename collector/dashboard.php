<?php
require_once '../includes/config.php';
requireRole('collector');
$pageTitle = 'Collector Dashboard';
$uid = $_SESSION['user_id'];

$myRoutes = $conn->query("SELECT r.*, v.vehicle_number FROM collection_routes r LEFT JOIN vehicles v ON r.assigned_vehicle_id=v.id WHERE r.assigned_collector_id=$uid AND r.status='active'")->fetch_all(MYSQLI_ASSOC);
$todayLogs = $conn->query("SELECT COUNT(*) as c FROM collection_logs WHERE collector_id=$uid AND DATE(collection_date)=CURDATE()")->fetch_assoc()['c'];
$totalLogs = $conn->query("SELECT COUNT(*) as c FROM collection_logs WHERE collector_id=$uid")->fetch_assoc()['c'];
$myComplaints = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE assigned_to=$uid AND status='in_progress'")->fetch_assoc()['c'];
$totalWeight = $conn->query("SELECT SUM(collected_weight_kg) as t FROM collection_logs WHERE collector_id=$uid AND MONTH(collection_date)=MONTH(NOW())")->fetch_assoc()['t'] ?? 0;

// Full bins that need collection
$fullBins = $conn->query("SELECT wb.* FROM waste_bins wb 
    JOIN route_bins rb ON wb.id=rb.bin_id
    JOIN collection_routes cr ON rb.route_id=cr.id
    WHERE cr.assigned_collector_id=$uid AND wb.current_fill_percent>=70
    ORDER BY wb.current_fill_percent DESC")->fetch_all(MYSQLI_ASSOC);

// Recent logs
$recentLogs = $conn->query("SELECT cl.*, wb.bin_code, wb.location_name FROM collection_logs cl JOIN waste_bins wb ON cl.bin_id=wb.id WHERE cl.collector_id=$uid ORDER BY cl.collection_date DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">

<div class="p-4 mb-4" style="background:linear-gradient(135deg,#0d6efd,#0099cc);border-radius:16px;color:#fff">
    <h4 class="fw-bold mb-1">Welcome, <?= htmlspecialchars(explode(' ',$_SESSION['user_name'])[0]) ?>! 🚛</h4>
    <p class="mb-0 opacity-75"><?= date('l, d F Y') ?> • You have <?= count($myRoutes) ?> active route(s) today</p>
</div>

<div class="row g-3 mb-4">
    <?php foreach ([
        ['Today\'s Collections', $todayLogs, 'clipboard-check', '#1a7a4c'],
        ['Total Collections', $totalLogs, 'archive', '#0d6efd'],
        ['Monthly Weight (kg)', round($totalWeight,1), 'weight', '#f0a500'],
        ['Open Complaints', $myComplaints, 'chat-left-text', '#e53935'],
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

<!-- My Routes Today -->
<div class="data-table mb-4">
    <div class="table-header">
        <h6>🗺️ My Active Routes</h6>
        <a href="my_routes.php" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="p-3">
        <?php if (empty($myRoutes)): ?>
        <div class="text-center text-muted py-4">
            <i class="bi bi-map fs-2 d-block mb-2"></i>
            No Assigned Routes — contact your admin to get a route assigned.
        </div>
        <?php else: ?>
        <div class="row g-3">
            <?php foreach ($myRoutes as $r): ?>
            <div class="col-md-4">
                <div class="p-3 rounded-3" style="border:1px solid #e5e7eb;background:#f9fafb">
                    <div class="fw-bold" style="font-size:14px"><?= htmlspecialchars($r['route_name']) ?></div>
                    <div style="font-size:12px;color:#6b7280"><?= htmlspecialchars($r['area'] ?? '') ?></div>
                    <div style="font-size:12px;color:#6b7280">🚛 <?= htmlspecialchars($r['vehicle_number'] ?? 'No vehicle') ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="row g-3">
    <!-- Full Bins Alert -->
    <div class="col-md-6">
        <div class="data-table">
            <div class="table-header">
                <h6>🔴 Bins Needing Attention</h6>
                <a href="log_collection.php" class="btn btn-sm btn-primary">Log Collection</a>
            </div>
            <div class="p-3">
                <?php if (empty($fullBins)): ?>
                <div class="text-center text-muted py-4"><i class="bi bi-check-circle-fill text-success fs-2 d-block mb-2"></i>All bins are OK!</div>
                <?php else: ?>
                <?php foreach ($fullBins as $b):
                    $cl = getBinStatusClass($b['current_fill_percent']);
                    $colors=['success'=>'#1a7a4c','warning'=>'#f0a500','danger'=>'#e53935'];
                    $c=$colors[$cl];
                ?>
                <div class="d-flex align-items-center gap-3 mb-3 p-2 rounded" style="background:#f8fafc">
                    <div>
                        <div style="font-size:13px;font-weight:600"><?= $b['bin_code'] ?></div>
                        <div style="font-size:12px;color:#6b7280"><?= htmlspecialchars($b['location_name']) ?></div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fill-bar"><div class="fill-bar-inner" style="width:<?= $b['current_fill_percent'] ?>%;background:<?= $c ?>"></div></div>
                    </div>
                    <span style="font-weight:700;font-size:13px;color:<?= $c ?>"><?= $b['current_fill_percent'] ?>%</span>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Logs -->
    <div class="col-md-6">
        <div class="data-table">
            <div class="table-header">
                <h6>📋 Recent Collections</h6>
            </div>
            <table class="table mb-0">
                <thead><tr><th>Bin</th><th>Location</th><th>Weight</th><th>Date</th></tr></thead>
                <tbody>
                    <?php if (empty($recentLogs)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-3">No collections yet</td></tr>
                    <?php else: ?>
                    <?php foreach ($recentLogs as $log): ?>
                    <tr>
                        <td><strong style="font-size:13px"><?= $log['bin_code'] ?></strong></td>
                        <td style="font-size:12px"><?= htmlspecialchars($log['location_name']) ?></td>
                        <td><?= $log['collected_weight_kg'] ?>kg</td>
                        <td style="font-size:11px"><?= date('d M, h:i A',strtotime($log['collection_date'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
</div>
<?php include '../includes/footer.php'; ?>
