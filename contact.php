<?php
session_start();
$success = false;
$error = '';
if($_SERVER['REQUEST_METHOD']==='POST') {
  $name = trim($_POST['name']??'');
  $email = trim($_POST['email']??'');
  $message = trim($_POST['message']??'');
  if($name && $email && $message) {
    $success = true;
  } else {
    $error = 'Please fill in all required fields.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us – Livora</title>
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
      <li><a href="about.php">About</a></li>
      <li><a href="contact.php" class="active-link">Contact</a></li>
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

<section class="page-hero" style="background:linear-gradient(135deg,#00897b,#26a69a)">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a><span>/</span><span>Contact</span></div>
    <h1>📬 Get In Touch</h1>
    <p>We'd love to hear from you. Our team is here to help!</p>
  </div>
</section>

<section class="contact-section">
  <div class="container">
    <div class="contact-grid">
      <div class="contact-info">
        <span class="section-tag">Contact Us</span>
        <h2>Let's Talk!</h2>
        <p>Whether you have a question, feedback, or just want to say hello — we're always ready to listen and help.</p>
        <div class="contact-details">
          <div class="contact-item">
            <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
            <div><h4>Email Us</h4><p>support@livora.in<br>hello@livora.in</p></div>
          </div>
          <div class="contact-item">
            <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
            <div><h4>Call Us</h4><p>+91 98765 43210<br>Mon–Sat, 9am–7pm IST</p></div>
          </div>
          <div class="contact-item">
            <div class="contact-item-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div><h4>Our Office</h4><p>WeWork Prestige Zackria,<br>Bangalore – 560001, India</p></div>
          </div>
          <div class="contact-item">
            <div class="contact-item-icon"><i class="fas fa-comments"></i></div>
            <div><h4>Live Chat</h4><p>Available 10am–6pm IST<br>Response in under 5 minutes</p></div>
          </div>
        </div>

        <div style="margin-top:40px">
          <h4 style="margin-bottom:16px;color:var(--secondary)">Follow Us</h4>
          <div style="display:flex;gap:12px">
            <a href="#" style="width:44px;height:44px;background:var(--light);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:18px;transition:all .2s" onmouseover="this.style.background='var(--primary)';this.style.color='#fff'" onmouseout="this.style.background='var(--light)';this.style.color='var(--primary)'"><i class="fab fa-instagram"></i></a>
            <a href="#" style="width:44px;height:44px;background:var(--light);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:18px;transition:all .2s" onmouseover="this.style.background='var(--primary)';this.style.color='#fff'" onmouseout="this.style.background='var(--light)';this.style.color='var(--primary)'"><i class="fab fa-facebook"></i></a>
            <a href="#" style="width:44px;height:44px;background:var(--light);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:18px;transition:all .2s" onmouseover="this.style.background='var(--primary)';this.style.color='#fff'" onmouseout="this.style.background='var(--light)';this.style.color='var(--primary)'"><i class="fab fa-twitter"></i></a>
            <a href="#" style="width:44px;height:44px;background:var(--light);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:18px;transition:all .2s" onmouseover="this.style.background='var(--primary)';this.style.color='#fff'" onmouseout="this.style.background='var(--light)';this.style.color='var(--primary)'"><i class="fab fa-linkedin"></i></a>
          </div>
        </div>
      </div>

      <div class="contact-form-box">
        <?php if($success): ?>
        <div style="text-align:center;padding:40px 0">
          <div style="font-size:64px;margin-bottom:16px">🎉</div>
          <h3 style="color:var(--secondary);margin-bottom:8px">Message Sent!</h3>
          <p style="color:#888">Thanks for reaching out! We'll get back to you within 24 hours.</p>
          <a href="index.php" class="btn-fill" style="display:inline-block;margin-top:24px;padding:12px 32px">Back to Home</a>
        </div>
        <?php else: ?>
        <h3 style="color:var(--secondary);margin-bottom:28px;font-size:26px">Send Us a Message</h3>
        <?php if($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
          <div class="form-row">
            <div class="form-group">
              <label>Your Name *</label>
              <input type="text" name="name" placeholder="Full name" required>
            </div>
            <div class="form-group">
              <label>Email *</label>
              <input type="email" name="email" placeholder="you@email.com" required>
            </div>
          </div>
          <div class="form-group">
            <label>Phone Number</label>
            <input type="tel" name="phone" placeholder="+91 XXXXX XXXXX">
          </div>
          <div class="form-group">
            <label>Subject *</label>
            <select name="subject">
              <option>General Inquiry</option>
              <option>Listing Support</option>
              <option>Account Issue</option>
              <option>Safety Concern</option>
              <option>Feedback</option>
              <option>Business Partnership</option>
            </select>
          </div>
          <div class="form-group">
            <label>Message *</label>
            <textarea name="message" rows="5" placeholder="Tell us what's on your mind..." required style="resize:vertical"></textarea>
          </div>
          <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Send Message</button>
        </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section style="padding:80px 0;background:var(--gray)">
  <div class="container">
    <div class="section-header"><span class="section-tag">FAQ</span><h2>Frequently Asked Questions</h2></div>
    <?php
    $faqs = [
      ['Is Livora free to use?','Yes! Creating an account and posting listings is completely free. We never charge for basic connections.'],
      ['How are profiles verified?','We verify government IDs, run background checks, and collect references for all service providers.'],
      ['How do I contact a profile?','Log in to your account, visit the profile page, and click "Send Message" to start a conversation.'],
      ['Can I report a suspicious profile?','Yes! Every profile has a "Report" button. Our safety team reviews all reports within 24 hours.'],
    ];
    foreach($faqs as $i=>$faq):
    ?>
    <div style="background:#fff;border-radius:16px;margin-bottom:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.04)">
      <button onclick="toggleFaq(<?php echo $i; ?>)" style="width:100%;padding:20px 28px;text-align:left;border:none;background:#fff;cursor:pointer;display:flex;justify-content:space-between;align-items:center;font-size:16px;font-weight:600;color:var(--secondary);font-family:'DM Sans',sans-serif">
        <?php echo $faq[0]; ?>
        <i class="fas fa-chevron-down" id="faq-icon-<?php echo $i; ?>" style="color:var(--primary);transition:transform .3s"></i>
      </button>
      <div id="faq-<?php echo $i; ?>" style="display:none;padding:0 28px 20px;color:#888;font-size:15px;line-height:1.8"><?php echo $faq[1]; ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include 'footer.php'; ?>
<script>
function toggleFaq(i) {
  const el = document.getElementById('faq-'+i);
  const ic = document.getElementById('faq-icon-'+i);
  const open = el.style.display === 'block';
  el.style.display = open ? 'none' : 'block';
  ic.style.transform = open ? 'rotate(0)' : 'rotate(180deg)';
}
function toggleMenu(){document.querySelector('.nav-links').classList.toggle('open');}
</script>
<style>.active-link{color:var(--primary)!important}</style>
</body></html>
