<?php
// Laker Realty - Update Form (Milestone 2 - Validation Only)

function s($v){ return htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8'); }
function req($v){ return $v !== '' && $v !== null; }
function is_int_str($v){ return $v === '' ? false : preg_match('/^\d+$/', $v); }
function is_name($v){ return $v === '' ? true : preg_match("/^[A-Za-z' -]{1,60}$/", $v); }
function is_zip5($v){ return $v === '' ? true : preg_match('/^\d{5}$/', $v); }
function is_state($v){ return $v === '' ? true : preg_match('/^[A-Z]{2}$/', strtoupper($v)); }
function is_phone($v){
  if ($v==='') return true;
  $digits = preg_replace('/\D/','',$v);
  return strlen($digits)===10;
}
function in_list($v,$list){ return $v === '' ? true : in_array($v, $list, true); }

$allowed_client_types = ['seller','buyer','both'];

$ClientID   = s($_POST['ClientID']        ?? '');
$FirstName  = s($_POST['ClientFirstName'] ?? '');
$LastName   = s($_POST['ClientLastName']  ?? '');
$Email      = s($_POST['ClientEmail']     ?? '');
$Phone      = s($_POST['Phone']           ?? '');
$Address    = s($_POST['Address']         ?? '');
$City       = s($_POST['city']            ?? '');
$State      = strtoupper(s($_POST['State'] ?? ''));
$Zip        = s($_POST['ZipCode']         ?? '');
$ClientType = s($_POST['clientType']      ?? '');

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!req($ClientID) || !is_int_str($ClientID)) {
    $errors['ClientID'] = 'Client ID is required and must be an integer.';
  }

  $payloadCount = 0;
  foreach ([$FirstName,$LastName,$Email,$Phone,$Address,$City,$State,$Zip,$ClientType] as $v){
    if ($v!=='') { $payloadCount++; }
  }
  if ($payloadCount===0){
    $errors['payload'] = 'Enter at least one field to update.';
  }

  if ($FirstName!=='' && !is_name($FirstName)) $errors['FirstName'] = 'First name may contain letters/spaces/hyphens.';
  if ($LastName!==''  && !is_name($LastName))  $errors['LastName']  = 'Last name may contain letters/spaces/hyphens.';
  if ($Email!==''     && !filter_var($Email, FILTER_VALIDATE_EMAIL)) $errors['Email'] = 'Enter a valid email (name@example.com).';
  if ($Phone!==''     && !is_phone($Phone))    $errors['Phone']  = 'Enter a 10-digit phone (dashes or spaces ok).';
  if ($City!==''      && !is_name($City))      $errors['City']   = 'City may contain letters/spaces/hyphens.';
  if ($State!==''     && !is_state($State))    $errors['State']  = 'State must be 2 letters (e.g., GA).';
  if ($Zip!==''       && !is_zip5($Zip))       $errors['Zip']    = 'ZIP must be 5 digits.';
  if ($ClientType!=='' && !in_list(strtolower($ClientType), $allowed_client_types)) {
    $errors['ClientType'] = 'Client Type must be seller, buyer, or both.';
  }

  if (!$errors){
    $success = 'Update request validated. (Milestone 2: no database update executed.)';
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Laker Realty - Update Form</title>
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
      <a href="Query%20Page.php" style="margin-right:16px">Query</a>
      <a href="Update%20Page.php" aria-current="page" style="margin-right:16px">Update</a>
      <a href="reports.html">Reports</a>
    </nav>
  </div>
</header>

<main class="container">
  <h1>Update Form (Validation Only)</h1>
  <div class="card" style="color:#cbd5e1">
    Enter a Client ID and at least one field to update.
    No database update occurs in Milestone 2.
  </div>

  <?php if($errors): ?>
    <div class="errors">
      <strong>Please fix:</strong>
      <ul><?php foreach($errors as $msg) echo "<li>$msg</li>"; ?></ul>
    </div>
  <?php elseif($success): ?>
    <div class="success"><?php echo $success; ?></div>
  <?php endif; ?>

  <form class="card" method="post" action="Update%20Page.php" novalidate>
    <div class="grid">
      <label>Client ID *
        <input type="text" name="ClientID" value="<?php echo $ClientID; ?>" placeholder="Enter Client ID">
      </label>

      <label>Client First Name
        <input type="text" name="ClientFirstName" value="<?php echo $FirstName; ?>" placeholder="Enter Client First Name">
      </label>

      <label>Client Last Name
        <input type="text" name="ClientLastName" value="<?php echo $LastName; ?>" placeholder="Enter Client Last Name">
      </label>

      <label>Email
        <input type="text" name="ClientEmail" value="<?php echo $Email; ?>" placeholder="Enter Client Email">
      </label>

      <label>Phone Number
        <input type="text" name="Phone" value="<?php echo $Phone; ?>" placeholder="Enter Phone Number">
      </label>

      <label>Address
        <input type="text" name="Address" value="<?php echo $Address; ?>" placeholder="Enter Street Address">
      </label>

      <label>City
        <input type="text" name="city" value="<?php echo $City; ?>" placeholder="Enter City">
      </label>

      <label>State
        <input type="text" name="State" value="<?php echo $State; ?>" placeholder="GA">
      </label>

      <label>Zip Code
        <input type="text" name="ZipCode" value="<?php echo $Zip; ?>" placeholder="Enter Zip Code">
      </label>

      <label>Client Type
        <select name="clientType">
          <option value="">Any</option>
          <?php
            foreach(['seller','buyer','both'] as $opt){
              $sel = (strtolower($ClientType)=== $opt)?'selected':'';
              echo "<option value=\"$opt\" $sel>".ucfirst($opt)."</option>";
            }
          ?>
        </select>
      </label>
    </div>

    <div style="margin-top:10px">
      <button type="submit">Update</button>
      <a class="btn-muted" href="Update%20Page.php">Reset</a>
    </div>
  </form>
</main>

<footer>
  <div class="container">© <span id="year"></span> Laker Realty • All rights reserved</div>
</footer>
<script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
