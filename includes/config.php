<?php
// ============================================================
// Smart Waste Collection Management System
// Database Configuration
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'waste_management_db');
define('BASE_URL', 'http://localhost/waste_management');
define('APP_NAME', 'SmartWaste');
define('APP_VERSION', '1.0.0');

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create DB connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

$conn = getDBConnection();

// Auth helpers
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/index.php');
        exit();
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['user_role'] !== $role && $_SESSION['user_role'] !== 'admin') {
        header('Location: ' . BASE_URL . '/index.php?error=unauthorized');
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function isCollector() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'collector';
}

function isResident() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'resident';
}

function sanitize($data) {
    global $conn;
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($data))));
}

function redirectByRole() {
    $role = $_SESSION['user_role'];
    switch ($role) {
        case 'admin':     header('Location: ' . BASE_URL . '/admin/dashboard.php'); break;
        case 'collector': header('Location: ' . BASE_URL . '/collector/dashboard.php'); break;
        case 'resident':  header('Location: ' . BASE_URL . '/resident/dashboard.php'); break;
        default:          header('Location: ' . BASE_URL . '/index.php');
    }
    exit();
}

function getUnreadNotifications($user_id) {
    global $conn;
    $result = $conn->query("SELECT * FROM notifications WHERE user_id = $user_id AND is_read = 0 ORDER BY created_at DESC LIMIT 10");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getBinStatusClass($percent) {
    if ($percent >= 90) return 'danger';
    if ($percent >= 70) return 'warning';
    return 'success';
}

function generateComplaintNo() {
    return 'CMP-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
}
?>
