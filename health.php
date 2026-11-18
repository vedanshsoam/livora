<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/db.php';
$db = getDB();
$database = $db instanceof mysqli;
if ($db) $db->close();
http_response_code($database ? 200 : 503);
echo json_encode([
  'application' => 'Livora',
  'status' => $database ? 'ok' : 'degraded',
  'database' => $database ? 'connected' : 'unavailable',
  'storage_mode' => (getenv('AWS_S3_BUCKET') ? 'amazon-s3' : 'local'),
  'timestamp' => gmdate('c')
], JSON_PRETTY_PRINT);
