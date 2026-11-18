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
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'seeker';

    if ($role === 'service') $role = 'cook';
    $allowedRoles = ['seeker', 'roommate', 'cook', 'maid'];
    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$password) {
        $error = 'Please enter a valid name, email and password.';
    } elseif (!in_array($role, $allowedRoles, true)) {
        $error = 'Please choose a valid account type.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must contain at least 8 characters.';
    } else {
        $conn = getDB();
        if (!$conn) {
            $error = 'The database is unavailable. Please check the database configuration and try again.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO users (name,email,phone,password,role) VALUES (?,?,?,?,?)');
            if (!$stmt) {
                $error = 'Unable to prepare account creation.';
            } else {
                $stmt->bind_param('sssss', $name, $email, $phone, $hash, $role);
                if ($stmt->execute()) {
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id' => $stmt->insert_id, 'name' => $name,
                        'email' => $email, 'role' => $role
                    ];
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = ($stmt->errno === 1062)
                        ? 'An account with this email already exists.'
                        : 'Account creation failed. Please try again.';
                }
                $stmt->close();
            }
            $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register – Livora</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<nav class="navbar"><div class="nav-inner"><a href="index.php" class="logo">Liv<span>ora</span></a><div class="nav-auth"><a href="login.php" class="btn-outline">Login</a></div></div></nav>
<div class="auth-page" style="padding-top:120px;padding-bottom:60px">
<div class="auth-card" style="max-width:560px">
<div class="logo-center">Liv<span>ora</span></div><h2>Create Your Account</h2><p class="auth-sub">Join Livora — it's free!</p>
<?php if($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="POST">
<div class="form-group"><label>I am a...</label><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:8px">
<label style="cursor:pointer"><input type="radio" name="role" value="seeker" checked style="display:none" class="role-radio"><div class="role-option" data-val="seeker">🔍<br><span>Looking for Help</span></div></label>
<label style="cursor:pointer"><input type="radio" name="role" value="roommate" style="display:none" class="role-radio"><div class="role-option" data-val="roommate">🏠<br><span>Roommate</span></div></label>
<label style="cursor:pointer"><input type="radio" name="role" value="service" style="display:none" class="role-radio"><div class="role-option" data-val="service">💼<br><span>Cook / Maid</span></div></label>
</div></div>
<div class="form-row"><div class="form-group"><label>Full Name *</label><input type="text" name="name" required value="<?php echo htmlspecialchars($_POST['name']??''); ?>"></div>
<div class="form-group"><label>Phone Number</label><input type="tel" name="phone" value="<?php echo htmlspecialchars($_POST['phone']??''); ?>"></div></div>
<div class="form-group"><label>Email Address *</label><input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email']??''); ?>"></div>
<div class="form-row"><div class="form-group"><label>Password *</label><input type="password" name="password" minlength="8" required></div>
<div class="form-group"><label>Confirm Password *</label><input type="password" name="confirm_password" minlength="8" required></div></div>
<div class="form-group"><label style="display:flex;align-items:center;gap:8px;cursor:pointer"><input type="checkbox" required> I agree to the Terms of Service and Privacy Policy</label></div>
<button type="submit" class="btn-submit">🎉 Create Free Account</button>
</form><div class="auth-footer">Already have an account? <a href="login.php">Sign in</a></div>
</div></div>
<style>.role-option{padding:16px 12px;border:2px solid var(--border);border-radius:12px;text-align:center;font-size:13px;font-weight:600;color:var(--text);transition:all .2s}.role-option:hover{border-color:var(--primary)}input[type=radio]:checked+.role-option{border-color:var(--primary);background:rgba(255,107,53,.08);color:var(--primary)}</style>
</body></html>
