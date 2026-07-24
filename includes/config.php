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

// ============================================================
// BUG-007 FIX: Auto-expire sessions after a period of inactivity
// ============================================================
define('SESSION_TIMEOUT_SECONDS', 1800); // 30 minutes

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT_SECONDS) {
        // Session has expired due to inactivity - log the user out
        $_SESSION = [];
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['timeout_message'] = 'Your session expired due to inactivity. Please log in again.';
    } else {
        // Still active - refresh the activity timestamp
        $_SESSION['last_activity'] = time();
    }
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

// ============================================================
// BUG-001 FIX: Enforce a strong password policy
// Requires: 8+ chars, at least 1 uppercase, 1 lowercase, 1 digit, 1 special char
// ============================================================
function isStrongPassword($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[a-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    if (!preg_match('/[^A-Za-z0-9]/', $password)) return false;
    return true;
}

function passwordPolicyMessage() {
    return 'Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.';
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
