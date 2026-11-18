<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us – Livora</title>
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
      <li><a href="about.php" class="active-link">About</a></li>
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

<section class="page-hero" style="background:linear-gradient(135deg,#2c3e50,#3498db)">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a><span>/</span><span>About</span></div>
    <h1>🌟 About Livora</h1>
    <p>Making home-finding human again — one connection at a time</p>
  </div>
</section>

<!-- STORY -->
<section class="about-story">
  <div class="container">
    <div class="about-grid">
      <div class="about-img">
        <img src="https://images.unsplash.com/photo-1556761175-4b46a572b786?w=700&h=600&fit=crop" alt="Our Story">
      </div>
      <div class="about-content">
        <span class="section-tag">Our Story</span>
        <h2>We Make Living Better, Together</h2>
        <p>Livora was born from a simple frustration: finding a trustworthy roommate, cook, or maid in India felt like a nightmare. WhatsApp groups, unreliable agents, and sketchy classifieds — we knew there had to be a better way.</p>
        <p>Founded in 2022, Livora set out to build India's most trusted living-assistance platform. We believe that everyone deserves safe, convenient, and affordable home support — whether you're a student in a new city, a working professional, or a family looking for reliable household help.</p>
        <div class="about-values">
          <div class="value-item">
            <h4>🛡️ Safety First</h4>
            <p>All profiles are verified with ID and background checks.</p>
          </div>
          <div class="value-item">
            <h4>🤝 Community Driven</h4>
            <p>Reviews and ratings from real users, for real users.</p>
          </div>
          <div class="value-item">
            <h4>💰 Affordable</h4>
            <p>Free to post listings, no hidden fees for connections.</p>
          </div>
          <div class="value-item">
            <h4>🌍 Pan-India</h4>
            <p>Available in 20+ cities and growing every month.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- NUMBERS -->
<section style="padding:80px 0;background:var(--secondary)">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:32px;text-align:center">
      <?php $stats = [['50K+','Happy Users'],['20+','Cities'],['35K+','Listings'],['4.8★','Avg Rating']]; foreach($stats as $s): ?>
      <div>
        <div style="font-size:52px;font-weight:900;color:var(--primary);font-family:'Playfair Display',serif"><?php echo $s[0]; ?></div>
        <div style="color:rgba(255,255,255,0.6);font-size:16px;margin-top:8px"><?php echo $s[1]; ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TEAM -->
<section class="team-section">
  <div class="container">
    <div class="section-header">
      <span class="section-tag">Our Team</span>
      <h2>Meet the Livora Family</h2>
    </div>
    <div class="team-grid">
      <?php
      $team = [
        ['name'=>'Arjun Kapoor','role'=>'CEO & Co-Founder','img'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop'],
        ['name'=>'Sneha Iyer','role'=>'CTO & Co-Founder','img'=>'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&h=200&fit=crop'],
        ['name'=>'Vikram Nair','role'=>'Head of Growth','img'=>'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&h=200&fit=crop'],
        ['name'=>'Pooja Mehta','role'=>'Head of Safety','img'=>'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=200&h=200&fit=crop'],
      ];
      foreach($team as $t):
      ?>
      <div class="team-card">
        <img src="<?php echo $t['img']; ?>" alt="<?php echo $t['name']; ?>">
        <h4><?php echo $t['name']; ?></h4>
        <p><?php echo $t['role']; ?></p>
        <div class="team-social">
          <a href="#"><i class="fab fa-linkedin"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- MISSION -->
<section style="padding:100px 0;background:#fff">
  <div class="container">
    <div style="max-width:700px;margin:0 auto;text-align:center">
      <span class="section-tag">Our Mission</span>
      <h2 style="font-size:42px;color:var(--secondary);margin:16px 0">Making India Feel Like Home, Everywhere</h2>
      <p style="font-size:17px;color:#888;line-height:1.9">We believe that wherever you go in India, you should be able to find trusted people to share your home with, cook for your family, and keep your space clean. Livora is not just a platform — it's a community that makes every house a home.</p>
      <a href="register.php" class="btn-fill" style="display:inline-block;padding:16px 44px;font-size:17px;margin-top:36px">Join Our Community</a>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
<script>function toggleMenu(){document.querySelector('.nav-links').classList.toggle('open');}</script>
<style>.active-link{color:var(--primary)!important}</style>
</body></html>
