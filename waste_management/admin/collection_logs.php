<?php
require_once '../includes/config.php';
requireRole('admin');
$pageTitle = 'Collection Logs';

$logs = $conn->query("
    SELECT cl.*, wb.bin_code, wb.location_name, v.vehicle_number, u.full_name as collector
    FROM collection_logs cl
    LEFT JOIN waste_bins wb ON cl.bin_id=wb.id
    LEFT JOIN vehicles v ON cl.vehicle_id=v.id
    LEFT JOIN users u ON cl.collector_id=u.id
    ORDER BY cl.collection_date DESC
    LIMIT 100
")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">

<div class="row g-3 mb-4">
    <?php
    $logStats = [
        ['Total Collections', $conn->query("SELECT COUNT(*) as c FROM collection_logs")->fetch_assoc()['c'], 'clipboard-check', '#1a7a4c'],
        ['Total Weight (kg)', number_format($conn->query("SELECT SUM(collected_weight_kg) as t FROM collection_logs")->fetch_assoc()['t'] ?? 0, 1), 'weight', '#0d6efd'],
        ['This Month', $conn->query("SELECT COUNT(*) as c FROM collection_logs WHERE MONTH(collection_date)=MONTH(NOW())")->fetch_assoc()['c'], 'calendar-check', '#f0a500'],
        ['Today', $conn->query("SELECT COUNT(*) as c FROM collection_logs WHERE DATE(collection_date)=CURDATE()")->fetch_assoc()['c'], 'clock-history', '#7c3aed'],
    ];
    foreach ($logStats as [$label, $val, $icon, $color]):
    ?>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:<?= $color ?>22;color:<?= $color ?>"><i class="bi bi-<?= $icon ?>"></i></div>
            <div class="stat-value"><?= $val ?></div>
            <div class="stat-label"><?= $label ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="data-table">
    <div class="table-header"><h6>📋 Collection Logs (<?= count($logs) ?>)</h6></div>
    <div style="overflow-x:auto">
        <table class="table mb-0">
            <thead><tr><th>#</th><th>Bin</th><th>Location</th><th>Vehicle</th><th>Collector</th><th>Weight (kg)</th><th>Before%</th><th>After%</th><th>Date</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($logs as $i => $l):
                    $sc=['completed'=>'success','skipped'=>'warning','partial'=>'info'];
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><strong><?= htmlspecialchars($l['bin_code']??'—') ?></strong></td>
                    <td style="font-size:12px"><?= htmlspecialchars($l['location_name']??'—') ?></td>
                    <td style="font-size:12px"><?= htmlspecialchars($l['vehicle_number']??'—') ?></td>
                    <td style="font-size:12px"><?= htmlspecialchars($l['collector']??'—') ?></td>
                    <td><strong><?= $l['collected_weight_kg'] ?></strong></td>
                    <td><?= $l['before_fill_percent'] ?>%</td>
                    <td><?= $l['after_fill_percent'] ?>%</td>
                    <td style="font-size:12px"><?= date('d M Y H:i',strtotime($l['collection_date'])) ?></td>
                    <td><span class="badge bg-<?= $sc[$l['status']]??'secondary' ?>-subtle text-<?= $sc[$l['status']]??'secondary' ?>"><?= ucfirst($l['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
<?php include '../includes/footer.php'; ?>
