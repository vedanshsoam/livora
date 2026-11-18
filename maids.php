<?php session_start(); include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Find Maids – Livora</title>
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
      <li><a href="maids.php" class="active-link">Maids</a></li>
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

<section class="page-hero" style="background:linear-gradient(135deg,#1565c0,#0288d1)">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a><span>/</span><span>Maids</span></div>
    <h1>🧹 Find Trusted House Help</h1>
    <p>Verified, background-checked maids for daily & weekly home care</p>
    <a href="post_listing.php" class="btn-fill-white" style="display:inline-block;margin-top:16px">+ Register as Maid</a>
  </div>
</section>

<section class="filters-bar">
  <div class="container">
    <form class="filters-inner" method="GET">
      <select class="filter-select" name="city">
        <option value="">All Cities</option>
        <option>Mumbai</option><option>Delhi</option><option>Bangalore</option>
        <option>Chennai</option><option>Hyderabad</option><option>Kolkata</option><option>Ahmedabad</option>
      </select>
      <select class="filter-select" name="availability">
        <option value="">Any Schedule</option>
        <option>Full Time</option><option>Part Time</option><option>Daily</option>
      </select>
      <select class="filter-select" name="maxcharge">
        <option value="">Any Budget</option>
        <option value="3000">Under ₹3,000</option>
        <option value="4000">Under ₹4,000</option>
        <option value="5000">Under ₹5,000</option>
      </select>
      <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
      <a href="maids.php" class="btn-filter" style="background:#999;text-decoration:none">Clear</a>
    </form>
  </div>
</section>

<?php $maids = getSampleMaids(); ?>

<section class="listing-section">
  <div class="container">
    <p style="margin-bottom:24px;color:#777">Showing <strong><?php echo count($maids); ?></strong> maid profiles</p>
    <div class="listing-grid">
      <?php foreach($maids as $m): ?>
      <div class="listing-card">
        <div class="listing-img">
          <img src="<?php echo $m['img']; ?>" alt="<?php echo $m['name']; ?>">
          <?php if($m['verified']): ?>
          <span class="listing-verified"><i class="fas fa-shield-alt"></i> ID Verified</span>
          <?php endif; ?>
          <span class="listing-fav"><i class="far fa-heart"></i></span>
        </div>
        <div class="listing-body">
          <h3><?php echo $m['name']; ?></h3>
          <div class="listing-meta">
            <span><i class="fas fa-map-marker-alt"></i> <?php echo $m['area'].', '.$m['city']; ?></span>
            <span><i class="fas fa-star" style="color:#ffc947"></i> <?php echo $m['rating']; ?></span>
          </div>
          <div class="listing-meta">
            <span><i class="fas fa-clock"></i> <?php echo $m['availability']; ?></span>
            <span><i class="fas fa-briefcase"></i> <?php echo $m['experience']; ?> yrs exp.</span>
          </div>
          <div class="listing-tags">
            <?php foreach(explode(',',$m['tags']) as $tag): ?>
            <span><?php echo trim($tag); ?></span>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="listing-footer">
          <div class="listing-price">₹<?php echo number_format($m['charge']); ?><span>/mo</span></div>
          <a href="profile.php?type=maid&id=<?php echo $m['id']; ?>" class="btn-contact">View Profile</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="background:#fff;border-radius:20px;padding:48px;margin-top:60px;text-align:center;box-shadow:0 4px 16px rgba(0,0,0,.06)">
      <h3 style="font-size:28px;color:var(--secondary);margin-bottom:12px">Safety is Our Priority</h3>
      <p style="color:#888;max-width:600px;margin:0 auto 32px">Every maid listed on Livora undergoes a thorough background check, ID verification, and reference check for your peace of mind.</p>
      <div style="display:flex;justify-content:center;gap:40px;flex-wrap:wrap">
        <div style="text-align:center"><div style="font-size:36px;color:var(--primary)"><i class="fas fa-shield-alt"></i></div><p style="font-weight:600;margin-top:8px">Background Checked</p></div>
        <div style="text-align:center"><div style="font-size:36px;color:var(--primary)"><i class="fas fa-id-card"></i></div><p style="font-weight:600;margin-top:8px">ID Verified</p></div>
        <div style="text-align:center"><div style="font-size:36px;color:var(--primary)"><i class="fas fa-star"></i></div><p style="font-weight:600;margin-top:8px">Rating System</p></div>
        <div style="text-align:center"><div style="font-size:36px;color:var(--primary)"><i class="fas fa-headset"></i></div><p style="font-weight:600;margin-top:8px">24/7 Support</p></div>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
<script>function toggleMenu(){document.querySelector('.nav-links').classList.toggle('open');}</script>
<style>.active-link{color:var(--primary)!important}</style>
</body></html>
