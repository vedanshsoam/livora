<?php
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
session_set_cookie_params([
    'lifetime' => 0, 'path' => '/', 'secure' => $isHttps,
    'httponly' => true, 'samesite' => 'Lax'
]);
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/storage.php';

if (!isset($_SESSION['user']['id'])) { header('Location: login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method not allowed'); }
if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    http_response_code(403); exit('Invalid security token.');
}
try {
    $stored = livora_store_uploaded_file($_FILES['profile_image'] ?? [], (int)$_SESSION['user']['id']);
    $conn = getDB();
    if (!$conn) throw new RuntimeException('Database connection unavailable.');
    $stmt = $conn->prepare('UPDATE users SET profile_img=? WHERE id=?');
    $stmt->bind_param('si', $stored['key'], $_SESSION['user']['id']);
    if (!$stmt->execute()) throw new RuntimeException('Could not update your profile.');
    $_SESSION['user']['profile_img'] = $stored['key'];
    $stmt->close(); $conn->close();
    $_SESSION['upload_message'] = 'Profile image uploaded successfully using ' . strtoupper($stored['driver']) . ' storage.';
} catch (Throwable $e) {
    error_log('Livora profile upload: ' . $e->getMessage());
    $_SESSION['upload_error'] = $e->getMessage();
}
header('Location: dashboard.php');
exit;
