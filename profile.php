<?php session_start(); include 'db.php';
$type = $_GET['type'] ?? 'roommate';
$id = (int)($_GET['id'] ?? 1);

// Get sample data based on type
if($type==='roommate') $items = getSampleRoommates();
elseif($type==='cook') $items = getSampleCooks();
else $items = getSampleMaids();

$profile = null;
foreach($items as $item) { if($item['id']==$id) { $profile=$item; break; } }
if(!$profile) { header('Location: index.php'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $profile['name']; ?> – Livora</title>
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
      <?php if(isset($_SESSION['user'])): ?>
        <a href="dashboard.php" class="btn-dash">Dashboard</a>
      <?php else: ?>
        <a href="login.php" class="btn-outline">Login</a>
        <a href="register.php" class="btn-fill">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div style="padding-top:70px;background:var(--gray);min-height:100vh">
  <div class="container" style="padding:60px 24px">
    <a href="<?php echo $type; ?>s.php" style="color:var(--primary);display:inline-flex;align-items:center;gap:8px;margin-bottom:32px;font-weight:600"><i class="fas fa-arrow-left"></i> Back to <?php echo ucfirst($type); ?>s</a>

    <div style="display:grid;grid-template-columns:1fr 2fr;gap:32px;align-items:start">
      <!-- LEFT -->
      <div>
        <div style="background:#fff;border-radius:24px;padding:40px;text-align:center;box-shadow:var(--shadow)">
          <div style="position:relative;display:inline-block">
            <img src="<?php echo $profile['img']; ?>" style="width:140px;height:140px;border-radius:50%;object-fit:cover;border:4px solid var(--light)">
            <?php if($profile['verified']): ?>
            <span style="position:absolute;bottom:0;right:0;background:#51cf66;color:#fff;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid #fff"><i class="fas fa-check" style="font-size:14px"></i></span>
            <?php endif; ?>
          </div>
          <h2 style="font-size:24px;color:var(--secondary);margin:16px 0 4px"><?php echo $profile['name']; ?></h2>
          <p style="color:var(--primary);font-weight:600"><?php echo ucfirst($type); ?></p>
          <p style="color:#888;font-size:14px;margin-top:4px"><i class="fas fa-map-marker-alt" style="color:var(--primary)"></i> <?php echo $profile['area'].', '.$profile['city']; ?></p>
          
          <div style="display:flex;justify-content:center;gap:24px;margin:20px 0;padding:20px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
            <div style="text-align:center"><strong style="font-size:22px;color:var(--secondary)"><?php echo $profile['rating']; ?></strong><p style="font-size:11px;color:#aaa">Rating</p></div>
            <div style="text-align:center"><strong style="font-size:22px;color:var(--secondary)"><?php echo isset($profile['experience'])?$profile['experience']:rand(2,8); ?></strong><p style="font-size:11px;color:#aaa">Years</p></div>
            <div style="text-align:center"><strong style="font-size:22px;color:var(--secondary)">10+</strong><p style="font-size:11px;color:#aaa">Reviews</p></div>
          </div>

          <?php
          $price = $profile['budget'] ?? $profile['charge'] ?? $profile['maid_charge'] ?? '3000';
          ?>
          <div style="font-size:28px;font-weight:700;color:var(--primary);margin-bottom:4px">₹<?php echo number_format($price); ?></div>
          <div style="font-size:13px;color:#aaa;margin-bottom:24px">per month</div>

          <?php if(isset($_SESSION['user'])): ?>
          <a href="javascript:void(0)" onclick="alert('Message sent! They will reply soon.')" class="btn-fill" style="display:block;padding:14px;font-size:15px;margin-bottom:12px"><i class="fas fa-comment"></i> Send Message</a>
          <a href="javascript:void(0)" onclick="alert('Profile saved to your favorites!')" class="btn-outline" style="display:block;padding:14px;font-size:15px;text-align:center"><i class="far fa-heart"></i> Save Profile</a>
          <?php else: ?>
          <a href="login.php" class="btn-fill" style="display:block;padding:14px;font-size:15px;margin-bottom:12px"><i class="fas fa-sign-in-alt"></i> Login to Contact</a>
          <?php endif; ?>
        </div>
      </div>

      <!-- RIGHT -->
      <div>
        <div style="background:#fff;border-radius:24px;padding:40px;box-shadow:var(--shadow);margin-bottom:24px">
          <h3 style="font-size:22px;color:var(--secondary);margin-bottom:20px">About <?php echo $profile['name']; ?></h3>
          <p style="color:#888;line-height:1.9;font-size:15px"><?php echo $profile['bio'] ?? 'Experienced and reliable '.$type.' with a proven track record. Always punctual, professional, and committed to providing the best service.'; ?></p>
          
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:28px">
            <?php
            $details = [];
            if(isset($profile['gender'])) $details['Gender'] = $profile['gender'];
            if(isset($profile['age'])) $details['Age'] = $profile['age'].' years';
            if(isset($profile['occupation'])) $details['Occupation'] = $profile['occupation'];
            if(isset($profile['specialty'])) $details['Specialty'] = $profile['specialty'];
            if(isset($profile['availability'])) $details['Availability'] = $profile['availability'];
            if(isset($profile['experience'])) $details['Experience'] = $profile['experience'].' years';
            $details['City'] = $profile['city'];
            $details['Area'] = $profile['area'];
            foreach($details as $label=>$val):
            ?>
            <div style="background:var(--light);padding:16px;border-radius:12px">
              <div style="font-size:12px;color:#aaa;margin-bottom:4px"><?php echo $label; ?></div>
              <div style="font-size:15px;font-weight:600;color:var(--secondary)"><?php echo $val; ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Tags -->
        <div style="background:#fff;border-radius:24px;padding:40px;box-shadow:var(--shadow);margin-bottom:24px">
          <h3 style="font-size:18px;color:var(--secondary);margin-bottom:16px">Tags & Preferences</h3>
          <div class="listing-tags" style="display:flex;flex-wrap:wrap;gap:10px">
            <?php foreach(explode(',',$profile['tags']) as $tag): ?>
            <span style="background:rgba(255,107,53,0.1);color:var(--primary);padding:8px 16px;border-radius:50px;font-size:13px;font-weight:600"><?php echo trim($tag); ?></span>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Reviews -->
        <div style="background:#fff;border-radius:24px;padding:40px;box-shadow:var(--shadow)">
          <h3 style="font-size:18px;color:var(--secondary);margin-bottom:24px">Reviews (10)</h3>
          <?php
          $reviews = [
            ['name'=>'Arjun K.','img'=>'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=60&h=60&fit=crop','stars'=>5,'text'=>'Absolutely amazing! Very professional and reliable.','time'=>'2 weeks ago'],
            ['name'=>'Meera S.','img'=>'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=60&h=60&fit=crop','stars'=>5,'text'=>'Highly recommended. Made our life so much easier.','time'=>'1 month ago'],
            ['name'=>'Rajesh P.','img'=>'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=60&h=60&fit=crop','stars'=>4,'text'=>'Very good, always on time and thorough in work.','time'=>'1 month ago'],
          ];
          foreach($reviews as $r):
          ?>
          <div style="padding:20px 0;border-bottom:1px solid var(--border);display:flex;gap:16px">
            <img src="<?php echo $r['img']; ?>" style="width:48px;height:48px;border-radius:50%;object-fit:cover;flex-shrink:0">
            <div>
              <div style="font-weight:600;color:var(--secondary);margin-bottom:4px"><?php echo $r['name']; ?> <span style="color:var(--accent);font-size:13px"><?php echo str_repeat('★',$r['stars']); ?></span> <span style="color:#aaa;font-weight:400;font-size:12px;margin-left:8px"><?php echo $r['time']; ?></span></div>
              <p style="font-size:14px;color:#888"><?php echo $r['text']; ?></p>
            </div>
          </div>
          <?php endforeach; ?>

          <?php if(isset($_SESSION['user'])): ?>
          <div style="margin-top:24px">
            <h4 style="color:var(--secondary);margin-bottom:12px">Leave a Review</h4>
            <div style="display:flex;gap:8px;margin-bottom:12px;font-size:24px">
              <?php for($i=1;$i<=5;$i++): ?>
              <span onclick="this.parentNode.querySelectorAll('span').forEach((s,j)=>s.style.color=j<<?php echo $i; ?>?'#ffc947':'#ddd')" style="cursor:pointer;color:#ddd;transition:color .2s">★</span>
              <?php endfor; ?>
            </div>
            <textarea placeholder="Share your experience..." style="width:100%;padding:14px;border:2px solid var(--border);border-radius:12px;font-family:'DM Sans',sans-serif;font-size:14px;outline:none;resize:vertical" rows="3"></textarea>
            <button onclick="alert('Thank you for your review!')" class="btn-fill" style="margin-top:12px;padding:12px 28px">Submit Review</button>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
</body></html>
