<?php
// Laker Realty — Milestone 2 (Insert Form Only) — Validation ONLY (no database)

// ---- Helpers ----
function s($v){ return htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8'); }
function req($v){ return $v !== '' && $v !== null; }
function is_zip5($v){ return preg_match('/^\d{5}$/', $v); }
function is_name($v){ return preg_match("/^[A-Za-z' -]{1,60}$/", $v); }
function max_len($v,$n){ return strlen($v) <= $n; }
function in_list($v,$list){ return in_array($v, $list, true); }

// ---- Allowed values ----
$allowed_types = ['Single Family','Condo','Townhouse','Multi-Family','Commercial','Land'];

// ---- Read inputs (exact field names from your form) ----
$type            = s($_POST['type']            ?? '');
$PropertyAddress = s($_POST['PropertyAddress'] ?? '');
$PropertyCity    = s($_POST['PropertyCity']    ?? '');
$PropertyZipCode = s($_POST['PropertyZipCode'] ?? '');
$AgentFName      = s($_POST['AgentFName']      ?? '');
$AgentLName      = s($_POST['AgentLName']      ?? '');
$OfficeName      = s($_POST['OfficeName']      ?? '');
$Email           = s($_POST['Email']           ?? '');

$errors = [];
$success = '';

// ---- Validate on submit ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!req($type) || !in_list($type,$allowed_types)) {
    $errors['type'] = 'Please select a valid property type.';
  }
  if (!req($PropertyAddress)) {
    $errors['PropertyAddress'] = 'Address is required.';
  } elseif (!max_len($PropertyAddress,120)) {
    $errors['PropertyAddress_len'] = 'Address is too long (max 120 characters).';
  }

  if (!req($PropertyCity)) {
    $errors['PropertyCity'] = 'City is required.';
  } elseif (!is_name($PropertyCity)) {
    $errors['PropertyCity_fmt'] = 'City can include letters, spaces, hyphens, apostrophes.';
  }

  if (!req($PropertyZipCode) || !is_zip5($PropertyZipCode)) {
    $errors['PropertyZipCode'] = 'ZIP must be 5 digits.';
  }

  if (!req($AgentFName) || !is_name($AgentFName)) {
    $errors['AgentFName'] = 'First name is required (letters/spaces only).';
  }
  if (!req($AgentLName) || !is_name($AgentLName)) {
    $errors['AgentLName'] = 'Last name is required (letters/spaces only).';
  }

  if (!req($OfficeName)) {
    $errors['OfficeName'] = 'Office name is required.';
  } elseif (!max_len($OfficeName,80)) {
    $errors['OfficeName_len'] = 'Office name is too long (max 80 characters).';
  }

  if (!req($Email) || !filter_var($Email, FILTER_VALIDATE_EMAIL)) {
    $errors['Email'] = 'Enter a valid company email (e.g., name@example.com).';
  }

  if (!$errors) {
    // Milestone 2 requirement: validation only (no DB)
    $success = 'New listing validated successfully. (Milestone 2: validation only — no database insert.)';
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Laker Realty - Forms</title>
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
      <<a href="index.html" style="margin-right:16px">Home</a>
      <a href="forms.php" aria-current="page" style="margin-right:16px">Forms</a>
      <a href="reports.html">Reports</a>
    </nav>
  </div>
</header>

<main class="container">
  <h1>Insert Form (Validation Only)</h1>
  <div class="card" style="color:#cbd5e1">
    Enter the listing details and submit. If valid, you’ll see a green confirmation. <strong>No database insert in Milestone 2.</strong>
  </div>

  <?php if($errors): ?>
    <div class="errors">
      <strong>Please fix:</strong>
      <ul><?php foreach($errors as $msg) echo "<li>$msg</li>"; ?></ul>
    </div>
  <?php elseif($success): ?>
    <div class="success"><?php echo $success; ?></div>
  <?php endif; ?>

  <!-- EXACT field names from your form -->
  <form class="card" method="post" action="Forms%20Page.php" novalidate>
    <div class="grid">
      <label>Select Property Type *
        <select name="type">
          <option value="">Select Type</option>
          <?php
            foreach($allowed_types as $opt){
              $sel = ($type===$opt)?'selected':'';
              echo "<option value=\"$opt\" $sel>$opt</option>";
            }
          ?>
        </select>
      </label>

      <label>New Address *
        <input type="text" name="PropertyAddress" value="<?php echo $PropertyAddress; ?>" placeholder="Enter Property Address">
      </label>

      <label>City of Property *
        <input type="text" name="PropertyCity" value="<?php echo $PropertyCity; ?>" placeholder="Enter City of Property">
      </label>

      <label>Zip Code *
        <input type="text" name="PropertyZipCode" value="<?php echo $PropertyZipCode; ?>" placeholder="Enter Property Zip Code">
      </label>

      <label>Agent First Name *
        <input type="text" name="AgentFName" value="<?php echo $AgentFName; ?>" placeholder="Enter First Name">
      </label>

      <label>Agent Last Name *
        <input type="text" name="AgentLName" value="<?php echo $AgentLName; ?>" placeholder="Enter Last Name">
      </label>

      <label>Office Name *
        <input type="text" name="OfficeName" value="<?php echo $OfficeName; ?>" placeholder="Enter Office Name">
      </label>

      <label>Company Email *
        <input type="text" name="Email" value="<?php echo $Email; ?>" placeholder="Enter Email">
      </label>
    </div>

    <div style="margin-top:10px">
      <button type="submit">Submit</button>
      <a class="btn-muted" href="Forms%20Page.php">Reset</a>
    </div>
  </form>
</main>

<footer>
  <div class="container">
    © <span id="year"></span> Laker Realty • All rights reserved
  </div>
</footer>
<script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
