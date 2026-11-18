<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/storage.php';
$userId = (int)($_GET['user'] ?? 0);
if ($userId < 1) { http_response_code(404); exit; }
$db = getDB();
if (!$db) { http_response_code(503); exit; }
$stmt = $db->prepare('SELECT profile_img FROM users WHERE id=?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close(); $db->close();
$path = $row['profile_img'] ?? '';
if (!$path) { http_response_code(404); exit; }
if (str_starts_with($path, 'uploads/')) {
    $file = __DIR__ . '/uploads/' . $userId . '/' . basename($path);
    if (!is_file($file)) { http_response_code(404); exit; }
    header('Content-Type: ' . (mime_content_type($file) ?: 'application/octet-stream'));
    header('Cache-Control: public, max-age=300');
    readfile($file); exit;
}
$url = livora_profile_image_url($path);
if (!$url) { http_response_code(404); exit; }
header('Cache-Control: no-store');
header('Location: ' . $url, true, 302);
exit;
