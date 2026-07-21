<?php
require_once '../includes/config.php';
requireRole('resident');
$pageTitle = 'File New Complaint';
$uid = $_SESSION['user_id'];

$msg=''; $msgType='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $type        = sanitize($_POST['complaint_type']);
    $bin_id      = (int)$_POST['bin_id'] ?: null;
    $location    = sanitize($_POST['location']);
    $description = sanitize($_POST['description']);
    $priority    = sanitize($_POST['priority']);
    $cno         = generateComplaintNo();

    $stmt = $conn->prepare("INSERT INTO complaints (complaint_no,resident_id,resident_name,resident_email,resident_phone,complaint_type,bin_id,location,description,priority) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
    $stmt->bind_param('sissssssss', $cno, $uid, $user['full_name'], $user['email'], $user['phone'], $type, $bin_id, $location, $description, $priority);
    if ($stmt->execute()) {
        $msg="Complaint $cno filed successfully! We'll respond within 24 hours.";
        $msgType='success';
    } else {
        $msg='Error: '.$conn->error; $msgType='danger';
    }
}

$bins = $conn->query("SELECT id,bin_code,location_name FROM waste_bins WHERE status!='inactive' ORDER BY bin_code")->fetch_all(MYSQLI_ASSOC);

include '../includes/header.php';
include '../includes/sidebar.php';
include '../includes/topbar.php';
?>
<div class="main-content">
<div class="content-area">

<?php if ($msg): ?>
<div class="alert alert-<?= $msgType ?> alert-auto-hide">
    <i class="bi bi-<?= $msgType==='success'?'check-circle':'exclamation-circle' ?> me-2"></i> <?= $msg ?>
</div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="content-card p-4">
            <h5 class="fw-bold mb-1">📢 File a Complaint</h5>
            <p class="text-muted mb-4" style="font-size:14px">Report any waste management issues in your area</p>

            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Complaint Type *</label>
                        <select name="complaint_type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="missed_collection">Missed Collection</option>
                            <option value="overflowing_bin">Overflowing Bin</option>
                            <option value="damaged_bin">Damaged Bin</option>
                            <option value="odor">Bad Odor / Smell</option>
                            <option value="illegal_dumping">Illegal Dumping</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">🚨 Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Related Bin (optional)</label>
                        <select name="bin_id" class="form-select">
                            <option value="">-- Select Bin --</option>
                            <?php foreach ($bins as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= $b['bin_code'] ?> – <?= htmlspecialchars($b['location_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Location *</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. Near City Park Gate 2" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Describe the issue in detail..." required minlength="20"></textarea>
                    </div>
                </div>

                <!-- Tips -->
                <div class="alert alert-info mt-3 mb-3" style="font-size:13px;border-radius:10px">
                    <i class="bi bi-lightbulb me-2"></i>
                    <strong>Tips for faster resolution:</strong> Be specific about location, include bin code if visible, and choose appropriate priority.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-send me-1"></i> Submit Complaint
                    </button>
                    <a href="complaints.php" class="btn btn-outline-secondary">View My Complaints</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-md-4">
        <div class="content-card p-4 mb-3">
            <h6 class="fw-bold mb-3">⏱️ Response Times</h6>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-danger-subtle text-danger px-2">Urgent</span>
                <span style="font-size:13px">Within 2 hours</span>
            </div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-warning-subtle text-warning px-2">High</span>
                <span style="font-size:13px">Same day</span>
            </div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-primary-subtle text-primary px-2">Medium</span>
                <span style="font-size:13px">Within 24 hours</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary-subtle text-secondary px-2">Low</span>
                <span style="font-size:13px">Within 3 days</span>
            </div>
        </div>

        <div class="content-card p-4">
            <h6 class="fw-bold mb-3">📞 Emergency Contact</h6>
            <p style="font-size:13px;color:#6b7280">For urgent issues like hazardous waste spills, call directly:</p>
            <div class="fw-bold" style="font-size:16px;color:#1a7a4c">📞 1800-WASTE-123</div>
            <div style="font-size:12px;color:#9ca3af">24/7 Emergency Hotline</div>
        </div>
    </div>
</div>

</div>
</div>
<?php include '../includes/footer.php'; ?>
