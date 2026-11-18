<?php session_start(); include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Find Roommates – Livora</title>
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
      <li><a href="roommates.php" class="active-link">Roommates</a></li>
      <li><a href="cooks.php">Cooks</a></li>
      <li><a href="maids.php">Maids</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
    <div class="nav-auth">
      <?php if(isset($_SESSION['user'])): ?>
        <a href="dashboard.php" class="btn-dash">Dashboard</a>
      <?php else: ?>
        <a href="login.php" class="btn-outline">Login</a>
        <a href="register.php" class="btn-fill">Sign Up</a>
      <?php endif; ?>
    </div>
    <div class="hamburger" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
  </div>
</nav>

<section class="page-hero">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a> <span>/</span> <span>Roommates</span></div>
    <h1>🏠 Find Your Perfect Roommate</h1>
    <p>Browse <?php echo count(getSampleRoommates()); ?>+ verified profiles across India</p>
    <a href="post_listing.php" class="btn-fill-white" style="display:inline-block;margin-top:16px">+ Post Your Listing</a>
  </div>
</section>

<section class="filters-bar">
  <div class="container">
    <form class="filters-inner" method="GET">
      <input type="hidden" name="type" value="roommate">
      <select class="filter-select" name="city">
        <option value="">All Cities</option>
        <option value="Mumbai" <?php echo ($_GET['city']??'')==='Mumbai'?'selected':''; ?>>Mumbai</option>
        <option value="Delhi" <?php echo ($_GET['city']??'')==='Delhi'?'selected':''; ?>>Delhi</option>
        <option value="Bangalore" <?php echo ($_GET['city']??'')==='Bangalore'?'selected':''; ?>>Bangalore</option>
        <option value="Pune" <?php echo ($_GET['city']??'')==='Pune'?'selected':''; ?>>Pune</option>
        <option value="Hyderabad" <?php echo ($_GET['city']??'')==='Hyderabad'?'selected':''; ?>>Hyderabad</option>
        <option value="Chennai" <?php echo ($_GET['city']??'')==='Chennai'?'selected':''; ?>>Chennai</option>
      </select>
      <select class="filter-select" name="gender">
        <option value="">Any Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>
      <select class="filter-select" name="budget">
        <option value="">Any Budget</option>
        <option value="5000">Under ₹5,000</option>
        <option value="8000">Under ₹8,000</option>
        <option value="12000">Under ₹12,000</option>
      </select>
      <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
      <a href="roommates.php" class="btn-filter" style="background:#999;text-decoration:none">Clear</a>
    </form>
  </div>
</section>

<?php
$roommates = getSampleRoommates();
$city = $_GET['city'] ?? '';
$gender = $_GET['gender'] ?? '';
$budget = $_GET['budget'] ?? '';
if($city) $roommates = array_filter($roommates, fn($r) => $r['city'] === $city);
if($gender) $roommates = array_filter($roommates, fn($r) => $r['gender'] === $gender);
if($budget) $roommates = array_filter($roommates, fn($r) => $r['budget'] <= (int)$budget);
?>

<section class="listing-section">
  <div class="container">
    <p style="margin-bottom:24px;color:#777">Showing <strong><?php echo count($roommates); ?></strong> roommate listings</p>
    <div class="listing-grid">
      <?php foreach($roommates as $r): ?>
      <div class="listing-card">
        <div class="listing-img">
          <img src="<?php echo $r['img']; ?>" alt="<?php echo $r['name']; ?>">
          <?php if($r['verified']): ?>
          <span class="listing-verified"><i class="fas fa-check-circle"></i> Verified</span>
          <?php endif; ?>
          <span class="listing-fav"><i class="far fa-heart"></i></span>
        </div>
        <div class="listing-body">
          <h3><?php echo $r['name']; ?></h3>
          <div class="listing-meta">
            <span><i class="fas fa-map-marker-alt"></i> <?php echo $r['area'].', '.$r['city']; ?></span>
            <span><i class="fas fa-briefcase"></i> <?php echo $r['occupation']; ?></span>
          </div>
          <div class="listing-meta">
            <span><i class="fas fa-user"></i> <?php echo $r['gender'].', Age '.$r['age']; ?></span>
            <span><i class="fas fa-star" style="color:#ffc947"></i> <?php echo $r['rating']; ?></span>
          </div>
          <div class="listing-tags">
            <?php foreach(explode(',',$r['tags']) as $tag): ?>
            <span><?php echo trim($tag); ?></span>
            <?php endforeach; ?>
          </div>
          <p style="font-size:13px;color:#888;line-height:1.6"><?php echo $r['bio']; ?></p>
        </div>
        <div class="listing-footer">
          <div class="listing-price">₹<?php echo number_format($r['budget']); ?><span>/mo</span></div>
          <a href="profile.php?type=roommate&id=<?php echo $r['id']; ?>" class="btn-contact">View Profile</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php if(empty($roommates)): ?>
    <div style="text-align:center;padding:80px;color:#999">
      <i class="fas fa-search" style="font-size:48px;margin-bottom:16px;display:block"></i>
      <h3>No results found</h3>
      <p>Try adjusting your filters</p>
      <a href="roommates.php" style="color:var(--primary)">View all roommates</a>
    </div>
    <?php endif; ?>

    <div style="text-align:center;margin-top:60px">
      <a href="post_listing.php" class="btn-fill" style="display:inline-block;padding:16px 40px;font-size:16px">+ Post Your Roommate Listing</a>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
<script>function toggleMenu(){document.querySelector('.nav-links').classList.toggle('open');}</script>
<style>.active-link{color:var(--primary)!important}</style>
</body></html>
