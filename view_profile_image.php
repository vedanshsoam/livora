<?php
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
session_set_cookie_params([
    'lifetime' => 0, 'path' => '/', 'secure' => $isHttps,
    'httponly' => true, 'samesite' => 'Lax'
]);
session_start();
require_once __DIR__ . '/includes/storage.php';
if (!isset($_SESSION['user']['id'])) { http_response_code(403); exit('Forbidden'); }
$conn = require __DIR__ . '/db.php';
$db = getDB();
if (!$db) { http_response_code(503); exit('Storage profile unavailable'); }
$stmt = $db->prepare('SELECT profile_img FROM users WHERE id=?');
$stmt->bind_param('i', $_SESSION['user']['id']);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close(); $db->close();
$path = $row['profile_img'] ?? '';
if ($path === '') { http_response_code(404); exit('No profile image'); }
if (str_starts_with($path, 'uploads/')) {
    $safe = basename($path);
    $userId = (int)$_SESSION['user']['id'];
    $file = __DIR__ . '/uploads/' . $userId . '/' . $safe;
    if (!is_file($file)) { http_response_code(404); exit('Image not found'); }
    $mime = mime_content_type($file) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    readfile($file);
    exit;
}
$url = livora_profile_image_url($path);
if ($url === '') { http_response_code(404); exit('Image unavailable'); }
header('Location: ' . $url, true, 302);
exit;
