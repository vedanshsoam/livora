<?php
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
session_set_cookie_params([
    'lifetime' => 0, 'path' => '/', 'secure' => $isHttps,
    'httponly' => true, 'samesite' => 'Lax'
]);
session_start();
require_once __DIR__ . '/db.php';
if (isset($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $conn = getDB();
    if (!$conn) {
        $error = 'The database is unavailable. Please check your database settings.';
    } else {
        $stmt = $conn->prepare('SELECT id,name,email,password,role FROM users WHERE email=? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $account = $result ? $result->fetch_assoc() : null;
        if ($account && password_verify($password, $account['password'])) {
            session_regenerate_id(true);
            unset($account['password']);
            $_SESSION['user'] = $account;
            header('Location: dashboard.php');
            exit;
        }
        $error = 'Invalid email or password.';
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login – Livora</title><link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head><body>
<nav class="navbar"><div class="nav-inner"><a href="index.php" class="logo">Liv<span>ora</span></a><div class="nav-auth"><a href="register.php" class="btn-fill">Create Account</a></div></div></nav>
<div class="auth-page"><div class="auth-card"><div class="logo-center">Liv<span>ora</span></div><h2>Welcome Back!</h2><p class="auth-sub">Sign in to your account</p>
<?php if($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="POST"><div class="form-group"><label><i class="fas fa-envelope"></i> Email Address</label><input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email']??''); ?>"></div>
<div class="form-group"><label><i class="fas fa-lock"></i> Password</label><input type="password" name="password" required></div>
<button type="submit" class="btn-submit"><i class="fas fa-sign-in-alt"></i> Sign In</button></form>
<div class="auth-footer">Don't have an account? <a href="register.php">Sign up free</a></div></div></div>
</body></html>
