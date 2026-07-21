<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir  = basename(dirname($_SERVER['PHP_SELF']));

function sidebarLink($href, $icon, $label, $badge = null) {
    global $currentPage;
    $file = basename($href);
    $active = ($currentPage === $file) ? 'active' : '';
    $badgeHtml = $badge ? "<span class='badge-count'>$badge</span>" : '';
    return "<a href='$href' class='sidebar-link $active'><i class='bi bi-$icon'></i> $label $badgeHtml</a>";
}
?>
<div class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand d-flex align-items-center gap-2">
        <div class="brand-icon" style="width:60px;height:65px;display:flex;align-items:center;justify-content:center;background:yellow;">
    <img src="/waste_management/logo.png"
         alt="SmartWaste Logo"
         style="width: 100px;px;height:100px;object-fit:contain;border-radius:50%;">
</div>
        <div>
            <h5>SmartWaste</h5>
            <small>Waste Management System</small>
        </div>
    </div>

    <!-- User Info -->
    <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,0.1)">
        <div class="d-flex align-items-center gap-2">
            <div class="user-avatar">
                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div>
                <div style="color:#fff;font-size:13px;font-weight:600"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></div>
                <div style="color:rgba(255,255,255,0.5);font-size:11px"><?= ucfirst($_SESSION['user_role'] ?? '') ?></div>
            </div>
        </div>
    </div>

    <div class="sidebar-menu">
        <?php if (isAdmin()): ?>
        <!-- ADMIN MENU -->
        <div class="sidebar-section-label">Main</div>
        <?= sidebarLink(BASE_URL.'/admin/dashboard.php', 'speedometer2', 'Dashboard') ?>
        <?= sidebarLink(BASE_URL.'/admin/bins.php', 'trash3', 'Waste Bins') ?>
        <?= sidebarLink(BASE_URL.'/admin/vehicles.php', 'truck', 'Vehicles') ?>
        <?= sidebarLink(BASE_URL.'/admin/routes.php', 'map', 'Collection Routes') ?>
        <?= sidebarLink(BASE_URL.'/admin/collection_logs.php', 'clipboard-check', 'Collection Logs') ?>

        <div class="sidebar-section-label">Management</div>
        <?= sidebarLink(BASE_URL.'/admin/users.php', 'people', 'Users') ?>
        <?= sidebarLink(BASE_URL.'/admin/complaints.php', 'chat-left-text', 'Complaints') ?>
        <?= sidebarLink(BASE_URL.'/admin/reports.php', 'bar-chart', 'Reports') ?>

        <?php elseif (isCollector()): ?>
        <!-- COLLECTOR MENU -->
        <div class="sidebar-section-label">My Work</div>
        <?= sidebarLink(BASE_URL.'/collector/dashboard.php', 'speedometer2', 'Dashboard') ?>
        <?= sidebarLink(BASE_URL.'/collector/my_routes.php', 'map', 'My Routes') ?>
        <?= sidebarLink(BASE_URL.'/collector/log_collection.php', 'clipboard-plus', 'Log Collection') ?>
        <?= sidebarLink(BASE_URL.'/collector/complaints.php', 'chat-left-text', 'Complaints') ?>

        <?php else: ?>
        <!-- RESIDENT MENU -->
        <div class="sidebar-section-label">Services</div>
        <?= sidebarLink(BASE_URL.'/resident/dashboard.php', 'house', 'Dashboard') ?>
        <?= sidebarLink(BASE_URL.'/resident/track_bins.php', 'geo-alt', 'Track Bins') ?>
        <?= sidebarLink(BASE_URL.'/resident/complaints.php', 'megaphone', 'My Complaints') ?>
        <?= sidebarLink(BASE_URL.'/resident/new_complaint.php', 'plus-circle', 'New Complaint') ?>
        <?php endif; ?>

        <div class="sidebar-section-label">Account</div>
        <?= sidebarLink(BASE_URL.'/profile.php', 'person-circle', 'Profile') ?>
        <a href="<?= BASE_URL ?>/logout.php" class="sidebar-link text-danger">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</div>
