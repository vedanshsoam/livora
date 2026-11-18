<?php session_start(); include 'db.php';
$type = $_GET['type'] ?? 'roommate';
$location = trim($_GET['location'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Results – Livora</title>
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

<section class="page-hero" style="padding:120px 0 60px">
  <div class="container">
    <h1>Search Results</h1>
    <p>
      <?php if($type==='roommate') echo '🏠 Roommates'; elseif($type==='cook') echo '🍳 Cooks'; else echo '🧹 Maids'; ?>
      <?php if($location) echo ' in '.htmlspecialchars($location); ?>
    </p>
  </div>
</section>

<section style="padding:40px 0 100px;background:var(--gray)">
  <div class="container">
    <!-- Search Box -->
    <div style="background:#fff;border-radius:20px;padding:32px;margin-bottom:40px;box-shadow:0 4px 16px rgba(0,0,0,.06)">
      <form class="filters-inner" method="GET" style="display:flex;gap:16px;align-items:center;flex-wrap:wrap">
        <div class="search-tabs" style="display:flex;gap:8px">
          <button type="button" class="tab <?php echo $type==='roommate'?'active':''; ?>" onclick="document.getElementById('stype').value='roommate';this.closest('form').submit()">🏠 Roommate</button>
          <button type="button" class="tab <?php echo $type==='cook'?'active':''; ?>" onclick="document.getElementById('stype').value='cook';this.closest('form').submit()">🍳 Cook</button>
          <button type="button" class="tab <?php echo $type==='maid'?'active':''; ?>" onclick="document.getElementById('stype').value='maid';this.closest('form').submit()">🧹 Maid</button>
        </div>
        <input type="hidden" name="type" id="stype" value="<?php echo htmlspecialchars($type); ?>">
        <input type="text" name="location" placeholder="City or area..." value="<?php echo htmlspecialchars($location); ?>" style="flex:1;padding:12px 18px;border:2px solid var(--border);border-radius:12px;font-size:14px;outline:none">
        <button type="submit" class="btn-search"><i class="fas fa-search"></i> Search</button>
      </form>
    </div>

    <?php
    if($type==='roommate') $items = getSampleRoommates();
    elseif($type==='cook') $items = getSampleCooks();
    else $items = getSampleMaids();

    if($location) {
      $items = array_filter($items, function($i) use($location) {
        return stripos($i['city'],$location)!==false || stripos($i['area'],$location)!==false;
      });
    }
    ?>
    <p style="margin-bottom:24px;color:#777">Found <strong><?php echo count($items); ?></strong> results <?php if($location) echo 'for "'.htmlspecialchars($location).'"'; ?></p>

    <?php if(empty($items)): ?>
    <div style="text-align:center;padding:80px;background:#fff;border-radius:20px">
      <div style="font-size:64px;margin-bottom:16px">🔍</div>
      <h3 style="color:var(--secondary);margin-bottom:8px">No results found</h3>
      <p style="color:#888;margin-bottom:24px">Try a different city or search term</p>
      <a href="search.php?type=<?php echo $type; ?>" class="btn-fill" style="display:inline-block;padding:12px 32px">Show All <?php echo ucfirst($type); ?>s</a>
    </div>
    <?php else: ?>
    <div class="listing-grid">
      <?php foreach($items as $item): ?>
      <div class="listing-card">
        <div class="listing-img">
          <img src="<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>">
          <?php if($item['verified']): ?><span class="listing-verified"><i class="fas fa-check-circle"></i> Verified</span><?php endif; ?>
          <span class="listing-fav"><i class="far fa-heart"></i></span>
        </div>
        <div class="listing-body">
          <h3><?php echo $item['name']; ?></h3>
          <div class="listing-meta">
            <span><i class="fas fa-map-marker-alt"></i> <?php echo $item['area'].', '.$item['city']; ?></span>
            <span><i class="fas fa-star" style="color:#ffc947"></i> <?php echo $item['rating']; ?></span>
          </div>
          <div class="listing-tags">
            <?php foreach(explode(',',$item['tags']) as $tag): ?><span><?php echo trim($tag); ?></span><?php endforeach; ?>
          </div>
        </div>
        <div class="listing-footer">
          <?php $price = $item['budget'] ?? $item['charge'] ?? '3000'; ?>
          <div class="listing-price">₹<?php echo number_format($price); ?><span>/mo</span></div>
          <a href="profile.php?type=<?php echo $type; ?>&id=<?php echo $item['id']; ?>" class="btn-contact">View</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php include 'footer.php'; ?>
</body></html>
