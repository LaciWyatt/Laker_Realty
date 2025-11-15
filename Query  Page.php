<?php
// Laker Realty - Query Form (Milestone 2 - Validation Only)

function s($v){ return htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8'); }
function is_name($v){ return $v === '' ? true : preg_match("/^[A-Za-z' -]{1,60}$/", $v); }
function is_money($v){ return $v === '' ? true : preg_match('/^\d+(?:\.\d{1,2})?$/', $v); }
function in_list($v,$list){ return $v === '' ? true : in_array($v, $list, true); }

$allowed_types    = ['Single Family','Condo','Townhouse','Multi-Family','Commercial','Land'];
$allowed_bedrooms = ['1','2','3','4'];

$city      = s($_POST['city']       ?? '');
$salePrice = s($_POST['SalesPrice'] ?? '');
$type      = s($_POST['type']       ?? '');
$bedrooms  = s($_POST['bedrooms']   ?? '');

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($city==='' && $salePrice==='' && $type==='' && $bedrooms==='') {
    $errors['any'] = 'Enter at least one search criterion.';
  }

  if ($city!=='' && !is_name($city)) {
    $errors['city'] = 'City may contain letters, spaces, hyphens, apostrophes.';
  }

  if ($salePrice!=='' && !is_money($salePrice)) {
    $errors['price'] = 'Sale price must be a number like 350000 or 350000.00.';
  }

  if ($type!=='' && !in_list($type,$allowed_types)) {
    $errors['type'] = 'Choose a valid property type.';
  }

  if ($bedrooms!=='' && !in_list($bedrooms,$allowed_bedrooms)) {
    $errors['bedrooms'] = 'Choose 1, 2, 3, or 4 (meaning 1+, 2+, 3+, 4+).';
  }

  if (!$errors) {
    $success = 'Search input validated. (Milestone 2: no database query executed.)';
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Laker Realty - Query Form</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{background:#0b1220;color:#e5e7eb;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:0}
    header{background:linear-gradient(90deg,#0f172a,#0c1224);border-bottom:1px solid #1f2a44}
    .container{max-width:1100px;margin:0 auto;padding:0 20px}
    .nav{display:flex;align-items:center;justify-content:space-between;padding:14px 0}
    .left{display:flex;align-items:center;gap:12px}
    .card{background:#0f172a;border:1px solid #1f2a44;border-radius:14px;padding:16px;margin:16px 0}
    h1{color:#60a5fa;margin-top:16px}
    label{display:flex;flex-direction:column;gap:6px;margin-bottom:10px}
    input,select{background:#0e172a;border:1px solid #1f2a44;border-radius:10px;color:#e5e7eb;padding:10px}
    button{background:#0ea5e9;border:none;border-radius:10px;color:white;padding:10px 14px;font-weight:600;cursor:pointer}
    button:hover{background:#1d4ed8}
    .btn-muted{display:inline-block;padding:10px 14px;border:1px solid #2a3757;border-radius:10px;color:#cbd5e1;margin-left:8px;text-decoration:none}
    .errors{border:1px solid #3b0d0d;background:#2a0b0b;color:#fecaca;padding:10px;border-radius:10px;margin:10px 0}
    .success{border:1px solid #0f5132;background:#0a2e23;color:#d1fae5;padding:10px;border-radius:10px;margin:10px 0}
    .grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
    @media (max-width:800px){.grid{grid-template-columns:1fr}}
    footer{border-top:1px solid #1f2a44;color:#94a3b8;text-align:center;padding:18px 0;margin-top:30px}
    a{color:#e5e7eb;text-decoration:none}
  </style>
</head>
<body>
<header>
  <div class="container nav">
    <div class="left">
      <img src="assets/img/logo.png" alt="Laker Realty logo" style="height:56px;border-radius:6px">
      <strong>Laker Realty</strong>
    </div>
    <nav aria-label="Primary">
      <a href="index.html" style="margin-right:16px">Home</a>
      <a href="Forms%20Page.php" style="margin-right:16px">Insert</a>
      <a href="Query%20Page.php" aria-current="page" style="margin-right:16px">Query</a>
      <a href="Update%20Page.php" style="margin-right:16px">Update</a>
      <a href="reports.html">Reports</a>
    </nav>
  </div>
</header>

<main class="container">
  <h1>Query Form (Validation Only)</h1>
  <div class="card" style="color:#cbd5e1">
    Enter any combination of filters. At least one field is required.
    No database query occurs in Milestone 2.
  </div>

  <?php if($errors): ?>
    <div class="errors">
      <strong>Please fix:</strong>
      <ul><?php foreach($errors as $msg) echo "<li>$msg</li>"; ?></ul>
    </div>
  <?php elseif($success): ?>
    <div class="success"><?php echo $success; ?></div>
  <?php endif; ?>

  <form class="card" method="post" action="Query%20Page.php" novalidate>
    <div class="grid">
      <label>City
        <input type="text" name="city" value="<?php echo $city; ?>" placeholder="Enter City">
      </label>

      <label>Search Transactions by Sale Price
        <input type="text" name="SalesPrice" value="<?php echo $salePrice; ?>" placeholder="e.g., 350000 or 350000.00">
      </label>

      <label>Search Properties by Property Type
        <select name="type">
          <option value="">Any</option>
          <?php
            foreach($allowed_types as $opt){
              $sel = ($type===$opt)?'selected':'';
              echo "<option value=\"$opt\" $sel>$opt</option>";
            }
          ?>
        </select>
      </label>

      <label>Search Properties by Bedrooms
        <select name="bedrooms">
          <option value="">Any</option>
          <?php
            foreach($allowed_bedrooms as $b){
              $sel = ($bedrooms===$b)?'selected':'';
              echo "<option value=\"$b\" $sel>{$b}+</option>";
            }
          ?>
        </select>
      </label>
    </div>

    <div style="margin-top:10px">
      <button type="submit">Search</button>
      <a class="btn-muted" href="Query%20Page.php">Reset</a>
    </div>
  </form>
</main>

<footer>
  <div class="container">© <span id="year"></span> Laker Realty • All rights reserved</div>
</footer>
<script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
