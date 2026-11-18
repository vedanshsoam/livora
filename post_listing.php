<?php
session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')==='https'),'httponly'=>true,'samesite'=>'Lax']);
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/storage.php';
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user']['id'])) {
        $error = 'Please log in before posting a listing.';
    } elseif (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error = 'Security token expired. Please reload the page and try again.';
    } else {
        $type = $_POST['listing_type'] ?? 'roommate';
        $city = trim($_POST['city'] ?? '');
        $area = trim($_POST['area'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $rawTags = $_POST['tags'] ?? $_POST['services'] ?? [];
        if (!is_array($rawTags)) $rawTags = [];
        $tags = implode(',', array_map('trim', $rawTags));
        $db = getDB();
        if (!$db) {
            $error = 'Database unavailable. Check your MySQL or RDS connection.';
        } elseif (!in_array($type, ['roommate','cook','maid'], true) || !$city || !$area || !$bio) {
            $error = 'Please complete the required listing fields.';
            $db->close();
        } else {
            try {
                if (isset($_FILES['photo']) && ($_FILES['photo']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                    $stored = livora_store_uploaded_file($_FILES['photo'], (int)$_SESSION['user']['id']);
                    $photoKey = $stored['key'];
                    $up = $db->prepare('UPDATE users SET profile_img=? WHERE id=?');
                    $up->bind_param('si', $photoKey, $_SESSION['user']['id']);
                    $up->execute(); $up->close();
                    $_SESSION['user']['profile_img'] = $photoKey;
                }
                $uid = (int)$_SESSION['user']['id'];
                if ($type === 'roommate') {
                    $budget = max(0, (int)($_POST['budget'] ?? 0));
                    $gender = $_POST['gender'] ?? 'Any';
                    if (!in_array($gender, ['Male','Female','Any'], true)) $gender = 'Any';
                    $age = (int)($_POST['age'] ?? 0);
                    $occupation = trim($_POST['occupation'] ?? '');
                    $stmt = $db->prepare('INSERT INTO roommates (user_id,city,area,budget,gender,age,occupation,bio,tags) VALUES (?,?,?,?,?,?,?,?,?)');
                    $stmt->bind_param('issisisss', $uid,$city,$area,$budget,$gender,$age,$occupation,$bio,$tags);
                } elseif ($type === 'cook') {
                    $charge = max(0, (int)($_POST['charge'] ?? 0));
                    $specialty = trim($_POST['specialty'] ?? '');
                    $experience = max(0, (int)($_POST['experience'] ?? 0));
                    $availability = $_POST['availability'] ?? 'Full Time';
                    if (!in_array($availability, ['Full Time','Part Time','Weekends Only'], true)) $availability = 'Full Time';
                    $stmt = $db->prepare('INSERT INTO cooks (user_id,city,area,charge,specialty,experience,availability,bio,tags) VALUES (?,?,?,?,?,?,?,?,?)');
                    $stmt->bind_param('issisisss', $uid,$city,$area,$charge,$specialty,$experience,$availability,$bio,$tags);
                } else {
                    $charge = max(0, (int)($_POST['maid_charge'] ?? 0));
                    $experience = max(0, (int)($_POST['experience'] ?? 0));
                    $availability = $_POST['maid_avail'] ?? 'Full Time';
                    if ($availability === 'Daily (few hours)') $availability = 'Daily';
                    if (!in_array($availability, ['Full Time','Part Time','Daily'], true)) $availability = 'Full Time';
                    $services = implode(',', array_map('trim', $_POST['services'] ?? []));
                    $stmt = $db->prepare('INSERT INTO maids (user_id,city,area,charge,experience,availability,services,bio,tags) VALUES (?,?,?,?,?,?,?,?,?)');
                    $stmt->bind_param('issiissss', $uid,$city,$area,$charge,$experience,$availability,$services,$bio,$tags);
                }
                if (!$stmt) throw new RuntimeException('Could not prepare listing.');
                if (!$stmt->execute()) throw new RuntimeException('Could not save listing.');
                $stmt->close();
                $success = true;
            } catch (Throwable $e) {
                error_log('Livora listing save failed: '.$e->getMessage());
                $error = 'The listing could not be saved. Please check the details and try again.';
            }
            $db->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Post a Listing – Livora</title>
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

<section class="page-hero" style="background:linear-gradient(135deg,#6c2ebf,#a84cf5)">
  <div class="container">
    <div class="breadcrumb"><a href="index.php">Home</a><span>/</span><span>Post Listing</span></div>
    <h1>✍️ Post Your Listing</h1>
    <p>Reach thousands of people looking for exactly what you offer</p>
  </div>
</section>

<section class="post-section">
  <div class="container">
    <?php if($error): ?><div class="alert alert-error" style="max-width:800px;margin:0 auto 24px"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if($success): ?>
    <div class="alert alert-success" style="max-width:800px;margin:0 auto 24px;font-size:16px;text-align:center">
      <i class="fas fa-check-circle" style="font-size:24px;display:block;margin-bottom:8px"></i>
      🎉 Your listing has been posted successfully! It will appear in search results within minutes.<br>
      <a href="index.php" style="color:#2b8a3e;font-weight:700">Go to Homepage</a>
    </div>
    <?php endif; ?>

    <div class="post-card">
      <h2>Create New Listing</h2>
      <p class="subtitle">Fill in the details below to post your listing for free</p>

      <div class="type-selector">
        <div class="type-btn selected" onclick="selectType(this,'roommate')">
          <i class="fas fa-home"></i>
          <span>I'm a Roommate</span>
        </div>
        <div class="type-btn" onclick="selectType(this,'cook')">
          <i class="fas fa-utensils"></i>
          <span>I'm a Cook</span>
        </div>
        <div class="type-btn" onclick="selectType(this,'maid')">
          <i class="fas fa-broom"></i>
          <span>I'm a Maid</span>
        </div>
      </div>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="listing_type" id="listingType" value="roommate">
        <div class="form-section-title">Personal Information</div>
        <div class="form-row">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" placeholder="Your full name" required>
          </div>
          <div class="form-group">
            <label>Phone Number *</label>
            <input type="tel" name="phone" placeholder="+91 XXXXX XXXXX" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" placeholder="your@email.com" required>
          </div>
          <div class="form-group">
            <label>Age</label>
            <input type="number" name="age" placeholder="Your age" min="18" max="65">
          </div>
        </div>

        <div class="form-section-title">Location Details</div>
        <div class="form-row">
          <div class="form-group">
            <label>City *</label>
            <select name="city" required>
              <option value="">Select City</option>
              <option>Mumbai</option><option>Delhi</option><option>Bangalore</option>
              <option>Pune</option><option>Hyderabad</option><option>Chennai</option>
              <option>Kolkata</option><option>Ahmedabad</option><option>Kochi</option>
            </select>
          </div>
          <div class="form-group">
            <label>Area / Locality *</label>
            <input type="text" name="area" placeholder="e.g. Bandra, Koramangala" required>
          </div>
        </div>

        <div class="form-section-title">Listing Details</div>

        <!-- ROOMMATE FIELDS -->
        <div id="roommate-fields">
          <div class="form-row">
            <div class="form-group">
              <label>Monthly Budget (₹) *</label>
              <input type="number" name="budget" placeholder="e.g. 8000">
            </div>
            <div class="form-group">
              <label>Gender</label>
              <select name="gender">
                <option value="">Prefer not to say</option>
                <option>Male</option><option>Female</option><option>Any</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Occupation</label>
              <input type="text" name="occupation" placeholder="e.g. Software Engineer">
            </div>
            <div class="form-group">
              <label>Preferred Roommate Gender</label>
              <select name="pref_gender">
                <option>No Preference</option><option>Male Only</option><option>Female Only</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Lifestyle Tags (select all that apply)</label>
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:8px">
              <?php foreach(['Non-smoker','Vegetarian','Non-drinker','Pet Friendly','Early Riser','Night Owl','Gym Goer','Students OK'] as $tag): ?>
              <label style="cursor:pointer"><input type="checkbox" name="tags[]" value="<?php echo $tag; ?>" style="margin-right:6px"><?php echo $tag; ?></label>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- COOK FIELDS -->
        <div id="cook-fields" style="display:none">
          <div class="form-row">
            <div class="form-group">
              <label>Monthly Charge (₹)</label>
              <input type="number" name="charge" placeholder="e.g. 5000">
            </div>
            <div class="form-group">
              <label>Specialty Cuisine</label>
              <select name="specialty">
                <option>North Indian</option><option>South Indian</option><option>Multi-Cuisine</option>
                <option>Chinese</option><option>Continental</option><option>Hyderabadi</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Years of Experience</label>
              <input type="number" name="experience" placeholder="e.g. 5" min="0">
            </div>
            <div class="form-group">
              <label>Availability</label>
              <select name="availability"><option>Full Time</option><option>Part Time</option><option>Weekends Only</option></select>
            </div>
          </div>
        </div>

        <!-- MAID FIELDS -->
        <div id="maid-fields" style="display:none">
          <div class="form-row">
            <div class="form-group">
              <label>Monthly Charge (₹)</label>
              <input type="number" name="maid_charge" placeholder="e.g. 3500">
            </div>
            <div class="form-group">
              <label>Availability</label>
              <select name="maid_avail"><option>Full Time</option><option>Part Time</option><option>Daily (few hours)</option></select>
            </div>
          </div>
          <div class="form-group">
            <label>Services Offered</label>
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:8px">
              <?php foreach(['Sweeping','Mopping','Cooking','Laundry','Ironing','Baby Care','Elderly Care','Deep Cleaning'] as $s): ?>
              <label style="cursor:pointer"><input type="checkbox" name="services[]" value="<?php echo $s; ?>" style="margin-right:6px"><?php echo $s; ?></label>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label>About You / Description *</label>
          <textarea name="bio" rows="4" placeholder="Describe yourself, your preferences, and what you're looking for..." required style="resize:vertical"></textarea>
        </div>

        <div class="form-group">
          <label>Profile Photo</label>
          <input type="file" name="photo" accept="image/*" style="padding:12px">
        </div>

        <?php if(!isset($_SESSION['user'])): ?>
        <div style="background:rgba(255,107,53,0.08);border:2px solid rgba(255,107,53,0.2);border-radius:12px;padding:16px;margin-bottom:20px">
          <p style="font-size:14px;color:var(--text)"><i class="fas fa-info-circle" style="color:var(--primary)"></i> You need an account to post a listing. <a href="register.php" style="color:var(--primary);font-weight:700">Register for free</a> or <a href="login.php" style="color:var(--primary);font-weight:700">log in</a>.</p>
        </div>
        <?php endif; ?>

        <button type="submit" class="btn-submit" style="font-size:17px">🚀 Post My Listing for Free</button>
      </form>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
<script>
function selectType(el, type) {
  document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('selected'));
  el.classList.add('selected');
  document.getElementById('listingType').value = type;
  document.getElementById('roommate-fields').style.display = type==='roommate' ? 'block' : 'none';
  document.getElementById('cook-fields').style.display = type==='cook' ? 'block' : 'none';
  document.getElementById('maid-fields').style.display = type==='maid' ? 'block' : 'none';
}
</script>
</body></html>
