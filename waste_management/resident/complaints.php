<?php
require_once '../includes/config.php';
requireRole('resident');
$pageTitle = 'My Complaints';
$uid = $_SESSION['user_id'];

$complaints = $conn->query("SELECT c.*, u.full_name as assigned_name FROM complaints c LEFT JOIN users u ON c.assigned_to=u.id WHERE c.resident_id=$uid ORDER BY c.created_at DESC")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">

<div class="d-flex justify-content-end mb-4">
    <a href="new_complaint.php" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> New Complaint
    </a>
</div>

<?php if (empty($complaints)): ?>
<div class="content-card p-5 text-center text-muted">
    <i class="bi bi-chat-left-text fs-1 d-block mb-3"></i>
    <h5>No complaints filed yet.</h5>
    <a href="new_complaint.php" class="btn btn-primary mt-2">File Your First Complaint</a>
</div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($complaints as $c):
        $sc=['pending'=>'warning','in_progress'=>'info','resolved'=>'success','closed'=>'secondary','rejected'=>'danger'];
        $pc=['low'=>'secondary','medium'=>'primary','high'=>'warning','urgent'=>'danger'];
        $statusBg=['pending'=>'#fff8e1','in_progress'=>'#e3f2fd','resolved'=>'#e8f5e9','closed'=>'#f5f5f5','rejected'=>'#ffebee'];
        $scKey=$c['status']??'pending';
    ?>
    <div class="col-md-6">
        <div class="content-card p-4" style="border-left:4px solid <?= ['pending'=>'#f0a500','in_progress'=>'#0d6efd','resolved'=>'#1a7a4c','closed'=>'#9ca3af','rejected'=>'#e53935'][$scKey]??'#9ca3af' ?>">
            <div class="d-flex justify-content-between mb-2">
                <strong style="font-size:14px"><?= $c['complaint_no'] ?></strong>
                <span class="badge bg-<?= $sc[$scKey] ?>-subtle text-<?= $sc[$scKey] ?> status-badge"><?= ucwords(str_replace('_',' ',$scKey)) ?></span>
            </div>
            <div class="mb-2">
                <span class="badge bg-<?= $pc[$c['priority']] ?>-subtle text-<?= $pc[$c['priority']] ?> me-2"><?= ucfirst($c['priority']) ?> Priority</span>
                <span class="badge bg-light text-dark"><?= ucwords(str_replace('_',' ',$c['complaint_type'])) ?></span>
            </div>
            <p style="font-size:13px;color:#374151;margin-bottom:8px"><?= nl2br(htmlspecialchars($c['description'])) ?></p>
            <div style="font-size:12px;color:#6b7280">
                📍 <?= htmlspecialchars($c['location']??'—') ?>
            </div>
            <?php if ($c['assigned_name']): ?>
            <div style="font-size:12px;color:#6b7280">👤 Assigned to: <strong><?= htmlspecialchars($c['assigned_name']) ?></strong></div>
            <?php endif; ?>
            <?php if ($c['resolution_notes']): ?>
            <div class="mt-2 p-2 rounded" style="background:#f0fdf4;font-size:12px;color:#166534">
                ✅ <strong>Resolution:</strong> <?= htmlspecialchars($c['resolution_notes']) ?>
            </div>
            <?php endif; ?>
            <div style="font-size:11px;color:#9ca3af;margin-top:8px">
                Filed: <?= date('d M Y, h:i A',strtotime($c['created_at'])) ?>
                <?php if ($c['resolved_at']): ?>
                | Resolved: <?= date('d M Y',strtotime($c['resolved_at'])) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

</div>
</div>
<?php include '../includes/footer.php'; ?>
