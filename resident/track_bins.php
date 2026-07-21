<?php
require_once '../includes/config.php';
requireRole('resident');
$pageTitle = 'Track Waste Bins';

$bins = $conn->query("SELECT * FROM waste_bins WHERE status != 'inactive' ORDER BY current_fill_percent DESC")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">

<!-- Summary -->
<div class="row g-3 mb-4">
    <?php
    $stats=[
        ['Total Bins', count($bins), 'trash3', '#1a7a4c'],
        ['Full/Critical', count(array_filter($bins,fn($b)=>$b['current_fill_percent']>=90)), 'exclamation-triangle', '#e53935'],
        ['Half Full', count(array_filter($bins,fn($b)=>$b['current_fill_percent']>=50&&$b['current_fill_percent']<90)), 'dash-circle', '#f0a500'],
        ['Normal', count(array_filter($bins,fn($b)=>$b['current_fill_percent']<50)), 'check-circle', '#1a7a4c'],
    ];
    foreach ($stats as [$l,$v,$ic,$col]):
    ?>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon mb-2" style="background:<?= $col ?>22;color:<?= $col ?>"><i class="bi bi-<?= $ic ?>"></i></div>
            <div class="stat-value"><?= $v ?></div>
            <div class="stat-label"><?= $l ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Bin Grid -->
<div class="row g-3">
    <?php foreach ($bins as $bin):
        $cl=getBinStatusClass($bin['current_fill_percent']);
        $colors=['success'=>'#1a7a4c','warning'=>'#f0a500','danger'=>'#e53935'];
        $c=$colors[$cl];
        $typeEmoji=['general'=>'🗑️','recyclable'=>'♻️','organic'=>'🌿','hazardous'=>'☣️'];
        $statusLabel=['active'=>'Active','full'=>'🔴 Full','maintenance'=>'🔧 Maintenance'];
    ?>
    <div class="col-md-4 col-sm-6">
        <div class="content-card p-3" style="border-top:3px solid <?= $c ?>">
            <div class="d-flex justify-content-between mb-2">
                <div>
                    <div class="fw-bold" style="font-size:15px"><?= $typeEmoji[$bin['bin_type']]??'' ?> <?= $bin['bin_code'] ?></div>
                    <div style="font-size:12px;color:#6b7280"><?= htmlspecialchars($bin['area']) ?></div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:22px;font-weight:800;color:<?= $c ?>"><?= $bin['current_fill_percent'] ?>%</div>
                    <div style="font-size:10px;color:#9ca3af">FILL</div>
                </div>
            </div>
            <div style="font-size:12px;color:#374151;margin-bottom:8px">
                📍 <?= htmlspecialchars($bin['location_name']) ?>
            </div>
            <div class="fill-bar mb-2" style="width:100%;height:10px">
                <div class="fill-bar-inner" style="width:<?= $bin['current_fill_percent'] ?>%;background:<?= $c ?>"></div>
            </div>
            <div class="d-flex justify-content-between">
                <span style="font-size:11px;color:#6b7280"><?= ucfirst($bin['bin_type']) ?> • <?= $bin['capacity_liters'] ?>L</span>
                <?php if ($bin['last_collected']): ?>
                <span style="font-size:11px;color:#9ca3af">Collected: <?= date('d M',strtotime($bin['last_collected'])) ?></span>
                <?php endif; ?>
            </div>
            <?php if ($bin['current_fill_percent'] >= 85): ?>
            <div class="mt-2">
                <a href="new_complaint.php" class="btn btn-sm btn-danger w-100" style="font-size:12px">
                    ⚠️ Report Overflow
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

</div>
</div>
<?php include '../includes/footer.php'; ?>
