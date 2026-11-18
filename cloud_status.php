<?php
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
session_set_cookie_params([
    'lifetime' => 0, 'path' => '/', 'secure' => $isHttps,
    'httponly' => true, 'samesite' => 'Lax'
]);
session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
require_once __DIR__ . '/db.php';
$db = getDB();
$dbStatus = $db ? 'Connected' : 'Not connected';
if ($db) $db->close();
$s3Status = getenv('AWS_S3_BUCKET') ? 'Configured (bucket name supplied)' : 'Not configured; local upload storage is used';
$region = getenv('AWS_REGION') ?: 'ap-south-1';
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cloud Configuration – Livora</title><link rel="stylesheet" href="style.css"></head><body>
<nav class="navbar"><div class="nav-inner"><a href="index.php" class="logo">Liv<span>ora</span></a><div class="nav-auth"><a href="dashboard.php" class="btn-outline">Dashboard</a></div></div></nav>
<main class="container" style="padding:120px 24px 60px;max-width:900px">
<h1 style="color:var(--secondary)">Livora Cloud Configuration</h1>
<p style="margin:12px 0 28px;color:#777">Runtime checks for the configured application environment. This page reports configuration only; it does not provision AWS resources.</p>
<div style="background:#fff;border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:16px">
<h3>Database connection</h3><p><?php echo htmlspecialchars($dbStatus); ?></p>
<h3 style="margin-top:18px">File storage</h3><p><?php echo htmlspecialchars($s3Status); ?></p>
<h3 style="margin-top:18px">AWS region</h3><p><?php echo htmlspecialchars($region); ?></p>
<h3 style="margin-top:18px">Health endpoint</h3><p><a href="health.php" target="_blank">Open application health status</a></p>
</div>
<p style="font-size:13px;color:#777">Do not share credentials or expose environment files. For production, use an EC2 IAM role, private database networking, HTTPS, and restricted S3 permissions.</p>
</main></body></html>
