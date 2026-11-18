<?php session_start(); include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Find Cooks – Livora</title>
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
      <li><a href="cooks.php" class="active-link">Cooks</a></li>
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

<section class="page-hero" style="background:linear-gradient(135deg,#e65100,#ff8f00)">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a><span>/</span><span>Cooks</span></div>
    <h1>🍳 Find Skilled Home Cooks</h1>
    <p>Hire experienced cooks for daily meals, parties & special occasions</p>
    <a href="post_listing.php" class="btn-fill-white" style="display:inline-block;margin-top:16px">+ Register as Cook</a>
  </div>
</section>

<section class="filters-bar">
  <div class="container">
    <form class="filters-inner" method="GET">
      <select class="filter-select" name="city">
        <option value="">All Cities</option>
        <option>Mumbai</option><option>Delhi</option><option>Bangalore</option>
        <option>Chennai</option><option>Hyderabad</option><option>Pune</option><option>Kochi</option>
      </select>
      <select class="filter-select" name="specialty">
        <option value="">All Cuisines</option>
        <option>North Indian</option><option>South Indian</option><option>Multi-Cuisine</option>
        <option>Kerala Cuisine</option><option>Hyderabadi</option><option>Maharashtrian</option>
      </select>
      <select class="filter-select" name="availability">
        <option value="">Any Time</option>
        <option>Full Time</option><option>Part Time</option><option>Daily</option>
      </select>
      <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
      <a href="cooks.php" class="btn-filter" style="background:#999;text-decoration:none">Clear</a>
    </form>
  </div>
</section>

<?php $cooks = getSampleCooks(); ?>

<section class="listing-section">
  <div class="container">
    <p style="margin-bottom:24px;color:#777">Showing <strong><?php echo count($cooks); ?></strong> cook profiles</p>
    <div class="listing-grid">
      <?php foreach($cooks as $c): ?>
      <div class="listing-card">
        <div class="listing-img">
          <img src="<?php echo $c['img']; ?>" alt="<?php echo $c['name']; ?>">
          <?php if($c['verified']): ?>
          <span class="listing-verified"><i class="fas fa-check-circle"></i> Verified</span>
          <?php endif; ?>
          <span class="listing-fav"><i class="far fa-heart"></i></span>
        </div>
        <div class="listing-body">
          <h3><?php echo $c['name']; ?></h3>
          <div class="listing-meta">
            <span><i class="fas fa-map-marker-alt"></i> <?php echo $c['area'].', '.$c['city']; ?></span>
            <span><i class="fas fa-star" style="color:#ffc947"></i> <?php echo $c['rating']; ?></span>
          </div>
          <div class="listing-meta">
            <span><i class="fas fa-utensils"></i> <?php echo $c['specialty']; ?></span>
            <span><i class="fas fa-clock"></i> <?php echo $c['availability']; ?></span>
          </div>
          <div class="listing-meta">
            <span><i class="fas fa-briefcase"></i> <?php echo $c['experience']; ?> years exp.</span>
          </div>
          <div class="listing-tags">
            <?php foreach(explode(',',$c['tags']) as $tag): ?>
            <span><?php echo trim($tag); ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="listing-footer">
          <div class="listing-price">₹<?php echo number_format($c['charge']); ?><span>/mo</span></div>
          <a href="profile.php?type=cook&id=<?php echo $c['id']; ?>" class="btn-contact">View Profile</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:60px">
      <a href="post_listing.php" class="btn-fill" style="display:inline-block;padding:16px 40px;font-size:16px">🍳 Register as a Cook</a>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
<script>function toggleMenu(){document.querySelector('.nav-links').classList.toggle('open');}</script>
<style>.active-link{color:var(--primary)!important}</style>
</body></html>
