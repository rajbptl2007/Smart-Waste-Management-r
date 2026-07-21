<?php
$notifications = isLoggedIn() ? getUnreadNotifications($_SESSION['user_id']) : [];
$notifCount = count($notifications);
?>
<div class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-link text-dark p-0 d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
            <i class="bi bi-list fs-4"></i>
        </button>
        <span class="page-title"><?= $pageTitle ?? 'Dashboard' ?></span>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Notifications -->
        <div class="dropdown">
            <button class="btn btn-link text-dark notification-btn p-0" data-bs-toggle="dropdown">
                <i class="bi bi-bell fs-5"></i>
                <?php if ($notifCount > 0): ?>
                <span class="notification-dot"></span>
                <?php endif; ?>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow" style="width:320px;border-radius:12px;border:none">
                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <strong style="font-size:14px">Notifications</strong>
                    <?php if ($notifCount > 0): ?>
                    <span class="badge bg-danger"><?= $notifCount ?> new</span>
                    <?php endif; ?>
                </div>
                <?php if (empty($notifications)): ?>
                <div class="text-center text-muted py-4" style="font-size:13px">
                    <i class="bi bi-check-all fs-4 d-block"></i>
                    All caught up!
                </div>
                <?php else: ?>
                <?php foreach ($notifications as $notif): ?>
                <div class="px-3 py-2 border-bottom" style="cursor:pointer" onclick="markRead(<?= $notif['id'] ?>)">
                    <div class="d-flex gap-2">
                        <div class="mt-1">
                            <i class="bi bi-circle-fill text-<?= $notif['type'] === 'danger' ? 'danger' : ($notif['type'] === 'warning' ? 'warning' : 'primary') ?>" style="font-size:8px"></i>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:600"><?= htmlspecialchars($notif['title']) ?></div>
                            <div style="font-size:12px;color:#6b7280"><?= htmlspecialchars($notif['message']) ?></div>
                            <div style="font-size:11px;color:#9ca3af"><?= date('d M, h:i A', strtotime($notif['created_at'])) ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- User Menu -->
        <div class="dropdown">
            <button class="btn btn-link p-0 d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown">
                <div class="user-avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?></div>
                <span style="font-size:14px;font-weight:500;color:#374151"><?= explode(' ', $_SESSION['user_name'] ?? 'User')[0] ?></span>
                <i class="bi bi-chevron-down text-muted" style="font-size:12px"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end shadow" style="border-radius:12px;border:none">
                <a class="dropdown-item" href="<?= BASE_URL ?>/profile.php"><i class="bi bi-person me-2"></i>Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a>
            </div>
        </div>
    </div>
</div>

<script>
function markRead(id) {
    fetch('<?= BASE_URL ?>/api/mark_notification.php?id=' + id)
        .then(() => location.reload());
}
</script>
