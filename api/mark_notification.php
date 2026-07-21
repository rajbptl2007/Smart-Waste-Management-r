<?php
require_once '../includes/config.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
$uid = $_SESSION['user_id'];

if ($id > 0) {
    $conn->query("UPDATE notifications SET is_read=1 WHERE id=$id AND user_id=$uid");
}

http_response_code(200);
echo json_encode(['success' => true]);
?>
