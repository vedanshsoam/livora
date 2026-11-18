<?php
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
session_set_cookie_params([
    'lifetime' => 0, 'path' => '/', 'secure' => $isHttps,
    'httponly' => true, 'samesite' => 'Lax'
]);
session_start();
if(!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$user = $_SESSION['user'];
if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); }
$uploadMessage = $_SESSION['upload_message'] ?? '';
$uploadError = $_SESSION['upload_error'] ?? '';
unset($_SESSION['upload_message'], $_SESSION['upload_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard – Livora</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<nav class="navbar">
  <div class="nav-inner">
    <a href="index.php" class="logo">Liv<span>ora</span></a>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="roommates.php">Roommates</a></li>
      <li><a href="cooks.php">Cooks</a></li>
      <li><a href="maids.php">Maids</a></li>
    </ul>
    <div class="nav-auth">
      <a href="logout.php" class="btn-outline">Logout</a>
    </div>
  </div>
</nav>

<div class="dashboard">
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-logo">Liv<span>ora</span></div>
    <ul class="sidebar-nav">
      <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
      <li><a href="#"><i class="fas fa-list"></i> My Listings</a></li>
      <li><a href="#"><i class="fas fa-heart"></i> Saved</a></li>
      <li><a href="#"><i class="fas fa-envelope"></i> Messages <span style="background:var(--primary);color:#fff;padding:2px 8px;border-radius:10px;font-size:11px;margin-left:4px">3</span></a></li>
      <li><a href="post_listing.php"><i class="fas fa-plus-circle"></i> Post Listing</a></li>
      <li><a href="#"><i class="fas fa-user"></i> My Profile</a></li>
      <li><a href="#"><i class="fas fa-star"></i> Reviews</a></li>
      <li><a href="cloud_status.php"><i class="fas fa-cloud"></i> Cloud Status</a></li>
      <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
      <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </aside>

  <!-- MAIN -->
  <main class="dash-main">
    <div class="dash-header">
      <h2>👋 Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</h2>
      <p>Here's what's happening with your Livora account today.</p>
    </div>

    <div class="stats-row">
      <div class="stat-box">
        <div class="stat-icon" style="background:rgba(255,107,53,0.1)"><i class="fas fa-eye" style="color:var(--primary)"></i></div>
        <div class="stat-val">128</div>
        <div class="stat-label">Profile Views</div>
      </div>
      <div class="stat-box">
        <div class="stat-icon" style="background:rgba(81,207,102,0.1)"><i class="fas fa-envelope" style="color:#51cf66"></i></div>
        <div class="stat-val">12</div>
        <div class="stat-label">New Messages</div>
      </div>
      <div class="stat-box">
        <div class="stat-icon" style="background:rgba(255,201,71,0.1)"><i class="fas fa-heart" style="color:#ffc947"></i></div>
        <div class="stat-val">34</div>
        <div class="stat-label">Saved Profiles</div>
      </div>
      <div class="stat-box">
        <div class="stat-icon" style="background:rgba(51,154,240,0.1)"><i class="fas fa-list" style="color:#339af0"></i></div>
        <div class="stat-val">2</div>
        <div class="stat-label">Active Listings</div>
      </div>
    </div>

    <div class="dash-section" style="margin-bottom:24px">
      <h3 style="margin-bottom:14px">Profile Photo & Cloud Storage</h3>
      <?php if($uploadMessage): ?><p style="color:#2f9e44;margin-bottom:10px"><?php echo htmlspecialchars($uploadMessage); ?></p><?php endif; ?>
      <?php if($uploadError): ?><p style="color:#c92a2a;margin-bottom:10px"><?php echo htmlspecialchars($uploadError); ?></p><?php endif; ?>
      <?php if(!empty($user['profile_img'])): ?>
        <img src="view_profile_image.php" alt="Your profile image" style="width:76px;height:76px;border-radius:50%;object-fit:cover;margin-bottom:12px">
      <?php endif; ?>
      <form action="upload_profile.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <label for="profile_image" style="display:block;margin-bottom:8px">Upload a JPG, PNG or WEBP image (maximum 5 MB)</label>
        <input id="profile_image" type="file" name="profile_image" accept="image/jpeg,image/png,image/webp" required>
        <button type="submit" class="btn-fill" style="border:0;padding:10px 18px;margin-top:12px">Upload Profile Image</button>
      </form>
      <p style="font-size:12px;color:#888;margin-top:10px">Uses Amazon S3 when AWS_S3_BUCKET is configured; otherwise stores the image locally.</p>
    </div>

    <!-- Recent Messages -->
    <div class="dash-section">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h3>Recent Messages</h3>
        <a href="#" style="color:var(--primary);font-size:14px">View All</a>
      </div>
      <?php
      $messages = [
        ['from'=>'Priya S.','msg'=>'Hi! I saw your listing and would love to connect...','time'=>'2 min ago','img'=>'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=60&h=60&fit=crop','unread'=>1],
        ['from'=>'Rahul M.','msg'=>'I am interested in cooking for your household...','time'=>'1 hr ago','img'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&h=60&fit=crop','unread'=>1],
        ['from'=>'Anita R.','msg'=>'I am available for full-time housekeeping...','time'=>'3 hrs ago','img'=>'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=60&h=60&fit=crop','unread'=>0],
      ];
      foreach($messages as $m):
      ?>
      <div style="display:flex;align-items:center;gap:16px;padding:16px 0;border-bottom:1px solid var(--border)">
        <img src="<?php echo $m['img']; ?>" style="width:48px;height:48px;border-radius:50%;object-fit:cover">
        <div style="flex:1">
          <div style="font-weight:600;color:var(--secondary);font-size:15px"><?php echo $m['from']; ?> <?php if($m['unread']): ?><span style="background:var(--primary);color:#fff;font-size:11px;padding:2px 8px;border-radius:10px;margin-left:8px">New</span><?php endif; ?></div>
          <div style="font-size:13px;color:#888;margin-top:2px"><?php echo $m['msg']; ?></div>
        </div>
        <span style="font-size:12px;color:#aaa"><?php echo $m['time']; ?></span>
        <a href="#" style="padding:8px 18px;background:var(--primary);color:#fff;border-radius:8px;font-size:13px">Reply</a>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- My Listings -->
    <div class="dash-section">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
        <h3>My Listings</h3>
        <a href="post_listing.php" class="btn-fill" style="padding:8px 20px;font-size:13px;display:inline-block">+ New Listing</a>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
        <div style="border:2px solid var(--border);border-radius:16px;padding:24px">
          <div style="display:flex;justify-content:space-between;margin-bottom:12px">
            <span style="background:rgba(81,207,102,0.15);color:#51cf66;padding:4px 12px;border-radius:50px;font-size:12px;font-weight:700">Active</span>
            <span style="font-size:12px;color:#aaa">Posted 3 days ago</span>
          </div>
          <h4 style="color:var(--secondary);margin-bottom:8px">2BHK Roommate Needed – Mumbai</h4>
          <p style="font-size:13px;color:#888;margin-bottom:16px">Looking for a non-smoking, vegetarian roommate in Bandra West.</p>
          <div style="display:flex;gap:8px">
            <a href="#" style="padding:8px 16px;background:var(--light);color:var(--primary);border-radius:8px;font-size:13px;font-weight:600">Edit</a>
            <a href="#" style="padding:8px 16px;background:var(--light);color:#c92a2a;border-radius:8px;font-size:13px;font-weight:600">Delete</a>
          </div>
        </div>
        <div style="border:2px dashed var(--border);border-radius:16px;padding:24px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center">
          <i class="fas fa-plus-circle" style="font-size:36px;color:var(--border);margin-bottom:12px"></i>
          <p style="color:#aaa;margin-bottom:16px;font-size:14px">Add another listing</p>
          <a href="post_listing.php" class="btn-fill" style="padding:10px 24px;font-size:14px;display:inline-block">Post Listing</a>
        </div>
      </div>
    </div>

    <!-- Recommended -->
    <div class="dash-section">
      <h3 style="margin-bottom:24px">Recommended For You</h3>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
        <?php
        $recs = [
          ['name'=>'Kiran P.','type'=>'Roommate','city'=>'Pune','img'=>'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=200&h=200&fit=crop','match'=>95,'link'=>'roommates.php'],
          ['name'=>'Sunita N.','type'=>'Cook','city'=>'Chennai','img'=>'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200&h=200&fit=crop','match'=>88,'link'=>'cooks.php'],
          ['name'=>'Anita R.','type'=>'Maid','city'=>'Bangalore','img'=>'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&h=200&fit=crop','match'=>92,'link'=>'maids.php'],
        ];
        foreach($recs as $rec):
        ?>
        <div style="border:2px solid var(--border);border-radius:16px;padding:20px;text-align:center;transition:all .2s" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'">
          <img src="<?php echo $rec['img']; ?>" style="width:64px;height:64px;border-radius:50%;object-fit:cover;margin-bottom:12px;border:3px solid var(--light)">
          <h4 style="font-size:15px;color:var(--secondary)"><?php echo $rec['name']; ?></h4>
          <p style="font-size:12px;color:#888;margin:4px 0"><?php echo $rec['type'].' · '.$rec['city']; ?></p>
          <span style="background:rgba(81,207,102,0.15);color:#2b8a3e;font-size:12px;font-weight:700;padding:3px 10px;border-radius:50px"><?php echo $rec['match']; ?>% Match</span>
          <a href="<?php echo $rec['link']; ?>" style="display:block;margin-top:12px;padding:8px;background:var(--primary);color:#fff;border-radius:8px;font-size:13px">View</a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>
</div>
</body></html>
