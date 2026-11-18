<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Livora – Find Your Perfect Living</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="nav-inner">
    <a href="index.php" class="logo">Liv<span>ora</span></a>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="roommates.php">Roommates</a></li>
      <li><a href="cooks.php">Cooks</a></li>
      <li><a href="maids.php">Maids</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
    <div class="nav-auth">
      <?php if(isset($_SESSION['user'])): ?>
        <a href="dashboard.php" class="btn-dash">Dashboard</a>
        <a href="logout.php" class="btn-outline">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn-outline">Login</a>
        <a href="register.php" class="btn-fill">Sign Up</a>
      <?php endif; ?>
    </div>
    <div class="hamburger" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg">
    <div class="blob b1"></div>
    <div class="blob b2"></div>
    <div class="blob b3"></div>
  </div>
  <div class="hero-content">
    <div class="hero-badge"><i class="fas fa-star"></i> Trusted by 50,000+ users</div>
    <h1>Find Your Perfect<br><span class="highlight">Living Partner</span></h1>
    <p>Discover roommates, hire skilled cooks & maids — all in one place. Safe, verified, and community-driven.</p>
    <div class="hero-search">
      <form action="search.php" method="GET">
        <div class="search-tabs">
          <button type="button" class="tab active" onclick="setTab(this,'roommate')">🏠 Roommate</button>
          <button type="button" class="tab" onclick="setTab(this,'cook')">🍳 Cook</button>
          <button type="button" class="tab" onclick="setTab(this,'maid')">🧹 Maid</button>
        </div>
        <div class="search-bar">
          <input type="hidden" name="type" id="searchType" value="roommate">
          <input type="text" name="location" placeholder="Enter your city or area..." required>
          <button type="submit" class="btn-search"><i class="fas fa-search"></i> Search</button>
        </div>
      </form>
    </div>
    <div class="hero-stats">
      <div class="stat"><strong>12K+</strong><span>Roommates</span></div>
      <div class="stat-divider"></div>
      <div class="stat"><strong>8K+</strong><span>Cooks</span></div>
      <div class="stat-divider"></div>
      <div class="stat"><strong>15K+</strong><span>Maids</span></div>
    </div>
  </div>
  <div class="hero-image">
    <div class="hero-card card1">
      <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=300&h=300&fit=crop" alt="Happy roommates">
      <div class="card-label">🤝 Just matched!</div>
    </div>
    <div class="hero-card card2">
      <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=300&fit=crop" alt="Cook">
      <div class="card-label">⭐ 4.9 rated cook</div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-it-works">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Simple Process</span>
      <h2>How Livora Works</h2>
      <p>Three easy steps to find what you need</p>
    </div>
    <div class="steps-grid">
      <div class="step-card">
        <div class="step-icon">
          <i class="fas fa-user-plus"></i>
          <span class="step-num">01</span>
        </div>
        <h3>Create Profile</h3>
        <p>Sign up and build your profile in minutes. Tell us about yourself and what you're looking for.</p>
      </div>
      <div class="step-arrow"><i class="fas fa-arrow-right"></i></div>
      <div class="step-card">
        <div class="step-icon">
          <i class="fas fa-search-location"></i>
          <span class="step-num">02</span>
        </div>
        <h3>Search & Browse</h3>
        <p>Filter by location, budget, preferences and browse verified profiles that match your needs.</p>
      </div>
      <div class="step-arrow"><i class="fas fa-arrow-right"></i></div>
      <div class="step-card">
        <div class="step-icon">
          <i class="fas fa-handshake"></i>
          <span class="step-num">03</span>
        </div>
        <h3>Connect & Hire</h3>
        <p>Message directly, schedule meetings, and finalize the arrangement safely through Livora.</p>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section class="services">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Our Services</span>
      <h2>Everything You Need Under One Roof</h2>
    </div>
    <div class="services-grid">
      <div class="service-card roommate-card">
        <div class="service-img">
          <img src="https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=500&h=350&fit=crop" alt="Roommates">
          <div class="service-overlay">
            <a href="roommates.php" class="btn-white">Find Roommates</a>
          </div>
        </div>
        <div class="service-info">
          <div class="service-icon"><i class="fas fa-home"></i></div>
          <h3>Roommate Finder</h3>
          <p>Find compatible roommates based on lifestyle, schedule, and budget preferences.</p>
          <div class="service-features">
            <span><i class="fas fa-check"></i> Verified Profiles</span>
            <span><i class="fas fa-check"></i> Smart Matching</span>
            <span><i class="fas fa-check"></i> Chat Feature</span>
          </div>
          <a href="roommates.php" class="service-link">Browse Roommates <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="service-card cook-card">
        <div class="service-img">
          <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&h=350&fit=crop" alt="Cook">
          <div class="service-overlay">
            <a href="cooks.php" class="btn-white">Find Cooks</a>
          </div>
        </div>
        <div class="service-info">
          <div class="service-icon"><i class="fas fa-utensils"></i></div>
          <h3>Cook Finder</h3>
          <p>Hire experienced home cooks who specialize in regional cuisines and dietary needs.</p>
          <div class="service-features">
            <span><i class="fas fa-check"></i> Background Checked</span>
            <span><i class="fas fa-check"></i> Menu Customization</span>
            <span><i class="fas fa-check"></i> Flexible Hours</span>
          </div>
          <a href="cooks.php" class="service-link">Browse Cooks <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>

      <div class="service-card maid-card">
        <div class="service-img">
          <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500&h=350&fit=crop" alt="Maid">
          <div class="service-overlay">
            <a href="maids.php" class="btn-white">Find Maids</a>
          </div>
        </div>
        <div class="service-info">
          <div class="service-icon"><i class="fas fa-broom"></i></div>
          <h3>Maid Finder</h3>
          <p>Connect with reliable, trained house help for cleaning, laundry, and daily chores.</p>
          <div class="service-features">
            <span><i class="fas fa-check"></i> ID Verified</span>
            <span><i class="fas fa-check"></i> Trained Staff</span>
            <span><i class="fas fa-check"></i> Daily/Weekly</span>
          </div>
          <a href="maids.php" class="service-link">Browse Maids <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURED LISTINGS -->
<section class="featured">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Featured</span>
      <h2>Top Profiles This Week</h2>
    </div>
    <div class="profiles-grid">
      <?php
      $profiles = [
        ['name'=>'Priya Sharma','role'=>'Roommate','city'=>'Mumbai','budget'=>'₹8,000/mo','img'=>'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=200&fit=crop&facepad=2','rating'=>4.8,'tag'=>'Non-smoker','color'=>'#ff6b6b'],
        ['name'=>'Rahul Mehta','role'=>'Cook','city'=>'Delhi','budget'=>'₹5,000/mo','img'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop&facepad=2','rating'=>4.9,'tag'=>'North Indian','color'=>'#ffa94d'],
        ['name'=>'Anita Rao','role'=>'Maid','city'=>'Bangalore','budget'=>'₹3,500/mo','img'=>'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&h=200&fit=crop&facepad=2','rating'=>4.7,'tag'=>'ID Verified','color'=>'#51cf66'],
        ['name'=>'Kiran Patel','role'=>'Roommate','city'=>'Pune','budget'=>'₹6,000/mo','img'=>'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=200&h=200&fit=crop&facepad=2','rating'=>4.6,'tag'=>'Pet Friendly','color'=>'#339af0'],
        ['name'=>'Sunita Nair','role'=>'Cook','city'=>'Chennai','budget'=>'₹4,500/mo','img'=>'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=200&h=200&fit=crop&facepad=2','rating'=>5.0,'tag'=>'South Indian','color'=>'#cc5de8'],
        ['name'=>'Deepak Yadav','role'=>'Maid','city'=>'Hyderabad','budget'=>'₹2,800/mo','img'=>'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=200&h=200&fit=crop&facepad=2','rating'=>4.8,'tag'=>'Full Time','color'=>'#20c997'],
      ];
      foreach($profiles as $p): 
        $link = strtolower($p['role']).'s.php';
      ?>
      <div class="profile-card" onclick="window.location='<?php echo $link; ?>'">
        <div class="profile-badge" style="background:<?php echo $p['color']; ?>"><?php echo $p['role']; ?></div>
        <img src="<?php echo $p['img']; ?>" alt="<?php echo $p['name']; ?>">
        <div class="profile-info">
          <h4><?php echo $p['name']; ?></h4>
          <p><i class="fas fa-map-marker-alt"></i> <?php echo $p['city']; ?></p>
          <div class="profile-meta">
            <span class="budget"><?php echo $p['budget']; ?></span>
            <span class="rating"><i class="fas fa-star"></i> <?php echo $p['rating']; ?></span>
          </div>
          <span class="profile-tag"><?php echo $p['tag']; ?></span>
        </div>
        <a href="<?php echo $link; ?>" class="profile-btn">View Profile</a>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="view-all-wrap">
      <a href="roommates.php" class="btn-outline-dark">View All Listings</a>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="testimonials">
  <div class="container">
    <div class="section-header light">
      <span class="section-tag">Testimonials</span>
      <h2>What Our Users Say</h2>
    </div>
    <div class="testi-grid">
      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p>"Found the perfect roommate within a week! Livora's matching system is incredibly accurate."</p>
        <div class="testi-author">
          <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=60&h=60&fit=crop" alt="User">
          <div><strong>Arjun K.</strong><span>Mumbai</span></div>
        </div>
      </div>
      <div class="testi-card featured-testi">
        <div class="testi-stars">★★★★★</div>
        <p>"Hired a cook through Livora and she's been amazing! Great food every day, very trustworthy."</p>
        <div class="testi-author">
          <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=60&h=60&fit=crop" alt="User">
          <div><strong>Meera S.</strong><span>Delhi</span></div>
        </div>
      </div>
      <div class="testi-card">
        <div class="testi-stars">★★★★★</div>
        <p>"The maid I found here is extremely professional and reliable. Highly recommend Livora!"</p>
        <div class="testi-author">
          <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&h=60&fit=crop" alt="User">
          <div><strong>Vikram P.</strong><span>Bangalore</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="container">
    <div class="cta-box">
      <div class="cta-text">
        <h2>Ready to Get Started?</h2>
        <p>Join thousands of happy users finding their perfect living solution.</p>
      </div>
      <div class="cta-btns">
        <a href="register.php" class="btn-fill-white">Create Free Account</a>
        <a href="post_listing.php" class="btn-outline-white">Post a Listing</a>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<?php include 'footer.php'; ?>

<script>
function setTab(el, type) {
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('searchType').value = type;
}
function toggleMenu() {
  document.querySelector('.nav-links').classList.toggle('open');
}
</script>
</body>
</html>
