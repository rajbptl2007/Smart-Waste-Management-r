<?php
require_once '../includes/config.php';
requireRole('admin');
$pageTitle = 'Reports & Analytics';

// Monthly data (last 6 months)
$monthlyData = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $label = date('M Y', strtotime("-$i months"));
    $collections = $conn->query("SELECT COUNT(*) as c, SUM(collected_weight_kg) as w FROM collection_logs WHERE DATE_FORMAT(collection_date,'%Y-%m')='$month'")->fetch_assoc();
    $complaints  = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE DATE_FORMAT(created_at,'%Y-%m')='$month'")->fetch_assoc()['c'];
    $monthlyData[] = ['month'=>$label, 'collections'=>$collections['c'], 'weight'=>round($collections['w']??0,1), 'complaints'=>$complaints];
}

// Bin type stats
$binTypeStats = $conn->query("SELECT bin_type, COUNT(*) as total, AVG(current_fill_percent) as avg_fill FROM waste_bins GROUP BY bin_type")->fetch_all(MYSQLI_ASSOC);

// Top full bins
$topBins = $conn->query("SELECT bin_code, location_name, area, current_fill_percent FROM waste_bins ORDER BY current_fill_percent DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// Complaint types
$complaintStats = $conn->query("SELECT complaint_type, COUNT(*) as c FROM complaints GROUP BY complaint_type ORDER BY c DESC")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">

<!-- Monthly Chart -->
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="content-card p-4">
            <h6 class="fw-bold mb-4">📊 Monthly Collections & Complaints (Last 6 Months)</h6>
            <canvas id="monthlyChart" height="150"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card p-4">
            <h6 class="fw-bold mb-4">🗑️ Bin Type Distribution</h6>
            <canvas id="binPie" height="220"></canvas>
        </div>
    </div>
</div>

<!-- Summary Table -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="data-table">
            <div class="table-header"><h6>📅 Monthly Summary</h6></div>
            <table class="table mb-0">
                <thead><tr><th>Month</th><th>Collections</th><th>Weight (kg)</th><th>Complaints</th></tr></thead>
                <tbody>
                    <?php foreach ($monthlyData as $m): ?>
                    <tr>
                        <td><strong><?= $m['month'] ?></strong></td>
                        <td><?= $m['collections'] ?></td>
                        <td><?= number_format($m['weight'],1) ?></td>
                        <td><?= $m['complaints'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="data-table">
            <div class="table-header"><h6>🔴 Top 5 Critical Bins</h6></div>
            <div class="p-3">
                <?php foreach ($topBins as $bin):
                    $cl = getBinStatusClass($bin['current_fill_percent']);
                    $colors = ['success'=>'#1a7a4c','warning'=>'#f0a500','danger'=>'#e53935'];
                    $color = $colors[$cl];
                ?>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:70px;font-size:12px;font-weight:600"><?= $bin['bin_code'] ?></div>
                    <div class="flex-grow-1">
                        <div style="font-size:12px;color:#6b7280"><?= htmlspecialchars($bin['location_name']) ?></div>
                        <div class="fill-bar mt-1"><div class="fill-bar-inner" style="width:<?= $bin['current_fill_percent'] ?>%;background:<?= $color ?>"></div></div>
                    </div>
                    <span style="font-weight:700;color:<?= $color ?>"><?= $bin['current_fill_percent'] ?>%</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Complaint Types -->
<div class="content-card p-4 mb-4">
    <h6 class="fw-bold mb-4">📢 Complaints by Type</h6>
    <div class="row">
        <?php foreach ($complaintStats as $cs):
            $total = $conn->query("SELECT COUNT(*) as c FROM complaints")->fetch_assoc()['c'];
            $pct = $total > 0 ? round($cs['c']/$total*100) : 0;
        ?>
        <div class="col-md-6 mb-3">
            <div class="d-flex justify-content-between mb-1">
                <span style="font-size:13px"><?= ucwords(str_replace('_',' ',$cs['complaint_type'])) ?></span>
                <span style="font-size:13px;font-weight:600"><?= $cs['c'] ?> (<?= $pct ?>%)</span>
            </div>
            <div class="fill-bar" style="width:100%;height:10px">
                <div class="fill-bar-inner" style="width:<?= $pct ?>%;background:#1a7a4c"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Print Button -->
<div class="text-end">
    <button onclick="window.print()" class="btn btn-outline-primary">
        <i class="bi bi-printer me-1"></i> Print Report
    </button>
</div>

</div>
</div>

<script>
// Monthly Chart
new Chart(document.getElementById('monthlyChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($monthlyData,'month')) ?>,
        datasets: [
            {
                label: 'Collections',
                data: <?= json_encode(array_column($monthlyData,'collections')) ?>,
                backgroundColor: 'rgba(26,122,76,0.75)',
                borderRadius: 5
            },
            {
                label: 'Complaints',
                data: <?= json_encode(array_column($monthlyData,'complaints')) ?>,
                backgroundColor: 'rgba(229,57,53,0.65)',
                borderRadius: 5
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: true } }
    }
});

// Bin Pie
new Chart(document.getElementById('binPie').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map(fn($b)=>ucfirst($b['bin_type']).' ('.round($b['avg_fill']).'%)',$binTypeStats)) ?>,
        datasets: [{
            data: <?= json_encode(array_column($binTypeStats,'total')) ?>,
            backgroundColor: ['#1a7a4c','#0d6efd','#f0a500','#e53935'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        cutout: '60%',
        plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } }
    }
});
</script>
<?php include '../includes/footer.php'; ?>
