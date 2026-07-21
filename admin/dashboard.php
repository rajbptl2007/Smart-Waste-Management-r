<?php
require_once '../includes/config.php';
requireRole('admin');
$pageTitle = 'Admin Dashboard';

// Stats
$totalBins      = $conn->query("SELECT COUNT(*) as c FROM waste_bins")->fetch_assoc()['c'];
$fullBins        = $conn->query("SELECT COUNT(*) as c FROM waste_bins WHERE status='full' OR current_fill_percent>=90")->fetch_assoc()['c'];
$totalVehicles  = $conn->query("SELECT COUNT(*) as c FROM vehicles WHERE status != 'inactive'")->fetch_assoc()['c'];
$activeRoutes   = $conn->query("SELECT COUNT(*) as c FROM collection_routes WHERE status='active'")->fetch_assoc()['c'];
$pendingComplaints = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE status IN ('pending','in_progress')")->fetch_assoc()['c'];
$todayCollections  = $conn->query("SELECT COUNT(*) as c FROM collection_logs WHERE DATE(collection_date)=CURDATE()")->fetch_assoc()['c'];
$totalWeight    = $conn->query("SELECT SUM(collected_weight_kg) as t FROM collection_logs WHERE MONTH(collection_date)=MONTH(NOW())")->fetch_assoc()['t'] ?? 0;
$totalUsers     = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='resident'")->fetch_assoc()['c'];

// Recent complaints
$recentComplaints = $conn->query("SELECT c.*, u.full_name as resident 
    FROM complaints c LEFT JOIN users u ON c.resident_id=u.id 
    ORDER BY c.created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// Bin fill levels
$binFills = $conn->query("SELECT bin_code, location_name, current_fill_percent, bin_type, status FROM waste_bins ORDER BY current_fill_percent DESC LIMIT 8")->fetch_all(MYSQLI_ASSOC);

// Collection data for chart (last 7 days)
$chartData = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $label = date('D', strtotime("-$i days"));
    $cnt = $conn->query("SELECT SUM(collected_weight_kg) as t FROM collection_logs WHERE DATE(collection_date)='$date'")->fetch_assoc()['t'] ?? 0;
    $chartData[] = ['label' => $label, 'value' => round($cnt, 1)];
}

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>

<div class="main-content">
<div class="content-area">

<!-- Welcome Banner -->
<div class="p-4 mb-4" style="background:linear-gradient(135deg,#1a7a4c,#2ea865);border-radius:16px;color:#fff">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1 fw-bold">Good <?= date('H')<12?'Morning':( date('H')<17?'Afternoon':'Evening') ?>, <?= htmlspecialchars(explode(' ',$_SESSION['user_name'])[0]) ?>! 👋</h4>
            <p class="mb-0 opacity-75"><?= date('l, d F Y') ?> • <?= $fullBins ?> bins need urgent attention today</p>
        </div>
        <div class="text-end d-none d-md-block">
            <div style="font-size:40px">🗑️</div>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon" style="background:#e8f5ee;color:#1a7a4c"><i class="bi bi-trash3"></i></div>
                <span class="badge bg-success-subtle text-success px-2" style="height:fit-content;border-radius:8px;font-size:11px">Total</span>
            </div>
            <div class="stat-value"><?= $totalBins ?></div>
            <div class="stat-label">Waste Bins</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon" style="background:#fff3cd;color:#f0a500"><i class="bi bi-exclamation-triangle"></i></div>
                <span class="badge bg-warning-subtle text-warning px-2" style="height:fit-content;border-radius:8px;font-size:11px">Alert</span>
            </div>
            <div class="stat-value"><?= $fullBins ?></div>
            <div class="stat-label">Full / Critical Bins</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon" style="background:#e0f0ff;color:#0d6efd"><i class="bi bi-truck"></i></div>
                <span class="badge bg-info-subtle text-info px-2" style="height:fit-content;border-radius:8px;font-size:11px">Fleet</span>
            </div>
            <div class="stat-value"><?= $totalVehicles ?></div>
            <div class="stat-label">Active Vehicles</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon" style="background:#fce8e8;color:#e53935"><i class="bi bi-chat-left-text"></i></div>
                <span class="badge bg-danger-subtle text-danger px-2" style="height:fit-content;border-radius:8px;font-size:11px">Open</span>
            </div>
            <div class="stat-value"><?= $pendingComplaints ?></div>
            <div class="stat-label">Pending Complaints</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon" style="background:#e8f5ee;color:#1a7a4c"><i class="bi bi-map"></i></div>
            </div>
            <div class="stat-value"><?= $activeRoutes ?></div>
            <div class="stat-label">Active Routes</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon" style="background:#f3e8ff;color:#7c3aed"><i class="bi bi-clipboard-check"></i></div>
            </div>
            <div class="stat-value"><?= $todayCollections ?></div>
            <div class="stat-label">Today's Collections</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon" style="background:#e0f0ff;color:#0d6efd"><i class="bi bi-weight"></i></div>
            </div>
            <div class="stat-value"><?= number_format($totalWeight,0) ?></div>
            <div class="stat-label">Monthly Kg Collected</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between mb-3">
                <div class="stat-icon" style="background:#fff3cd;color:#f0a500"><i class="bi bi-people"></i></div>
            </div>
            <div class="stat-value"><?= $totalUsers ?></div>
            <div class="stat-label">Registered Residents</div>
        </div>
    </div>
</div>

<!-- Charts + Bin Levels -->
<div class="row g-3 mb-4">
    <!-- Collection Chart -->
    <div class="col-md-7">
        <div class="content-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-600 m-0" style="font-weight:600">📊 Collection Weight – Last 7 Days (kg)</h6>
            </div>
            <canvas id="collectionChart" height="180"></canvas>
        </div>
    </div>

    <!-- Bin Type Pie -->
    <div class="col-md-5">
        <div class="content-card p-4 h-100">
            <h6 class="fw-600 mb-4" style="font-weight:600">🗑️ Bins by Type</h6>
            <canvas id="binTypeChart" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Bin Fill Levels + Recent Complaints -->
<div class="row g-3">
    <!-- Bin Fill Levels -->
    <div class="col-md-6">
        <div class="data-table">
            <div class="table-header">
                <h6>🔴 Bin Fill Levels</h6>
                <a href="bins.php" class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="p-3">
                <?php foreach ($binFills as $bin): 
                    $cl = getBinStatusClass($bin['current_fill_percent']);
                    $colors = ['success'=>'#1a7a4c','warning'=>'#f0a500','danger'=>'#e53935'];
                    $color = $colors[$cl];
                ?>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:90px;font-size:12px;font-weight:600;color:#374151"><?= $bin['bin_code'] ?></div>
                    <div class="flex-grow-1">
                        <div style="font-size:12px;color:#6b7280;margin-bottom:4px"><?= htmlspecialchars($bin['location_name']) ?></div>
                        <div class="fill-bar">
                            <div class="fill-bar-inner" style="width:<?= $bin['current_fill_percent'] ?>%;background:<?= $color ?>"></div>
                        </div>
                    </div>
                    <div style="width:40px;text-align:right;font-size:13px;font-weight:700;color:<?= $color ?>"><?= $bin['current_fill_percent'] ?>%</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Recent Complaints -->
    <div class="col-md-6">
        <div class="data-table">
            <div class="table-header">
                <h6>📢 Recent Complaints</h6>
                <a href="complaints.php" class="btn btn-sm btn-outline-danger">View All</a>
            </div>
            <div style="overflow-x:auto">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Type</th>
                            <th>Priority</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentComplaints as $c):
                            $statusColors = ['pending'=>'warning','in_progress'=>'info','resolved'=>'success','closed'=>'secondary','rejected'=>'danger'];
                            $priorityColors = ['low'=>'secondary','medium'=>'primary','high'=>'warning','urgent'=>'danger'];
                            $sc = $statusColors[$c['status']] ?? 'secondary';
                            $pc = $priorityColors[$c['priority']] ?? 'secondary';
                        ?>
                        <tr>
                            <td><strong style="font-size:12px"><?= $c['complaint_no'] ?></strong></td>
                            <td style="font-size:12px"><?= str_replace('_',' ',ucfirst($c['complaint_type'])) ?></td>
                            <td><span class="badge bg-<?= $pc ?>-subtle text-<?= $pc ?> status-badge"><?= ucfirst($c['priority']) ?></span></td>
                            <td><span class="badge bg-<?= $sc ?>-subtle text-<?= $sc ?> status-badge"><?= str_replace('_',' ',ucfirst($c['status'])) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>
</div>

<script>
// Collection Chart
const ctx1 = document.getElementById('collectionChart').getContext('2d');
new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($chartData,'label')) ?>,
        datasets: [{
            label: 'Weight (kg)',
            data: <?= json_encode(array_column($chartData,'value')) ?>,
            backgroundColor: 'rgba(26,122,76,0.75)',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});

// Bin Type Pie Chart
<?php
$binTypes = $conn->query("SELECT bin_type, COUNT(*) as c FROM waste_bins GROUP BY bin_type")->fetch_all(MYSQLI_ASSOC);
?>
const ctx2 = document.getElementById('binTypeChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map(fn($b)=>ucfirst($b['bin_type']),$binTypes)) ?>,
        datasets: [{
            data: <?= json_encode(array_column($binTypes,'c')) ?>,
            backgroundColor: ['#1a7a4c','#0d6efd','#f0a500','#e53935'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 12 }, padding: 15 } }
        }
    }
});
</script>

<?php include '../includes/footer.php'; ?>
