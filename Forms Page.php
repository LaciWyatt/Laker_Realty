
<?php// Laker Realty — Milestone 2 — Validation ONLY (no database)

// ---- Helpers ----
function s($v){ return htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8'); }
function req($v){ return $v !== '' && $v !== null; }
function is_zip5($v){ return preg_match('/^\d{5}$/', $v); }
function is_name($v){ return preg_match("/^[A-Za-z' -]{1,60}$/", $v); }
function max_len($v,$n){ return strlen($v) <= $n; }
function in_list($v,$list){ return in_array($v, $list, true); }

// ---- Allowed values ----
$allowed_types = ['Single Family','Condo','Townhouse','Multi-Family','Commercial','Land'];
$allowed_bedtypes = ['1+','2+','3+','4+'];

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




<!DOCTYPE html>
<html lang="en">

<head>
<title>Laker Realty - Forms</title>
<link rel="stylesheet" href="styles.css">
<meta charset="utf-8">
<style>


{
	font-size: 1em;
}
form{
	color: black;
	text-align:center;
	font-size: 1em;
	padding:1em;
}
header,nav,footer {
	color: #2E5A88;
	text-align:center;
	padding:1em;
}
h3,h4,h5{
	color: #2E5A88;
	text-align:center;
	font-size: 2em;
}
h6{
	text-align:center;
	font-size: 1em;
}

</style>

</head>
<body>
<header>
<h1>Laker Realty Group <br> Forms Page</h1> 
<img src="/logo.png" alt="Laker Realty logo" width="400" height="250">
<nav>
 </div>
      <nav aria-label="Primary">
        <a href="IndexHome.html" aria-current="page">Home</a>
        <a href="Forms Page.php">Forms</a>
        <a href="Reports.html">Reports</a>
      </nav>
    </div>
</nav>

</header>


<h6>Search, Update, Or Add Information With The Forms Below </h6>

<h3>Query Form</h3>
<strong>Search Listings:</strong>

<form method="GET" action="realestate.sql">
<label for="city">City:</label>
<input type="text" name="city" id="city" placeholder="Enter City"><br><br>

<label for="listing price">Search Transactions by Sales Price:</label>
<input type="number" name="SalesPrice" id="SalePrice" placeholder="Enter Maximum Price"><br><br>

<label for="type">Search Properties by Property Type:</label>
<select name="type" id="type">
<option value="">Any</option>
<option value="Single Family">Single Family</option>
<option value="Condo">Condo</option>
<option value="Townhouse">Townhouse</option>
<option value="Multi-Family">Multi-Family</option>
<option value="Commercial">Commercial</option>
<option value="Land">Land</option>
</select><br><br>

<label for="bedrooms">Search Properties by Bedrooms:</label>
<select name="bedrooms" id="bedrooms">
<option value="">Any</option>
<option value="1">1+</option>
<option value="2">2+</option>
<option value="3">3+</option>
<option value="4">4+</option>
</select><br><br>

<button type="submit" name="searchSubmit">Search</button>
</form>


<!-- line break to seperate page-->
<hr> 


<h4>Update Form</h4>

<section id="update-form">
<strong>Modify Existing Clients:</strong> 
<form>

<label for="ClientID">Client ID:</label>
<input type="text" id="ClientID" name="ClientID" placeholder="Enter Client ID"><br><br>

<label for="ClientFirstName">Client First Name:</label>
<input type="text" id="ClientFirstName" name="ClientFirstName" placeholder="Enter Client First Name"><br><br>

<label for="ClientLastName">Client Last Name:</label>
<input type="text" id="ClientLastName" name="ClientLastName" placeholder="Enter Client Last Name"><br><br>

<label for="Email">Email:</label>
<input type="text" id="ClientEmail" name="ClientEmail" placeholder="Enter Client Email"><br><br>

<label for="Phone">Phone Number:</label>
<input type="tel" id="Phone" name="Phone" placeholder="Enter Phone Number"><br><br>

<label for="ClientAddress">Address:</label>
<input type="text" id="ClientAddress" name="Address" placeholder="Enter Street Address"><br><br>

<label for="City">City:</label>
<input type="text" id="city" name="city" placeholder="Enter City"><br><br>

<label for="State">State:</label>
<input type="text" id="State" name="State" placeholder="GA"><br><br>

<label for="ZipCode">Zip Code:</label>
<input type="number" id="ZipCode" name="ZipCode" placeholder="Enter Zip Code"><br><br>

<label for="clientType">Client Type:</label>
<select name="clientType" id="clientType">
<option value="">Any</option>
<option value="seller">Seller</option>
<option value="buyer">Buyer</option>
<option value="both">Both</option>

</select><br><br>

<button type="submit" name="updateSubmit">Update</button>

</form>
</section>


<!-- line break to seperate page-->
<hr>

<h5>Insert Form</h5>

<strong>Add New Listing Below:</strong>

 <?php if(!empty($errors)): ?>
    <div class="errors">
      <strong>Please fix:</strong>
      <ul><?php foreach($errors as $msg) echo "<li>$msg</li>"; ?></ul>
    </div>
  <?php elseif(null && empty($errors)): ?>
    <div class="success"><?php echo $success; ?></div>
  <?php endif; ?>

<form action="Forms Page.php" method="POST">
<label for="type">Select Property Type:</label>
<select name="type" id="type">
<option value="">Select Type</option>
<option value="Single Family">Single Family</option>
<option value="Condo">Condo</option>
<option value="Townhouse">Townhouse</option>
<option value="Multi-Family">Multi-Family</option>
<option value="Commercial">Commercial</option>
<option value="Land">Land</option>
<?php
            foreach($allowed_types as $opt){
              $sel = ($type===$opt)?'selected':'';
              echo "<option value=\"$opt\" $sel>$opt</option>";
            }
          ?>
</select><br><br>

<label for="PropertyAddress">New Address:</label>
<input type="text" id="PropertyAddress" name="PropertyAddress" value="<?php echo $PropertyAddress ?? ''; ?>" placeholder="Enter Property Address"><br><br>

<label for="City">City of Property:</label>
<input type="text" id="PropertyCity" name="PropertyCity" value="<?php echo $PropertyCity ?? ''; ?>" placeholder="Enter City of Property"><br><br>


<label for="PropertyZipCode">Zip Code:</label>
<input type="number" id="PropertyZipCode" name="PropertyZipCode" value="<?php echo $PropertyZipCode ?? ''; ?>" placeholder="Enter Property Zip Code"><br><br>

<label>Agent First Name: <input type="text" name="AgentFName" value="<?php echo $AgentFName ?? ''; ?>" placeholder="Enter First Name"></label><br><br>
<label>Agent Last Name: <input type="text" name="AgentLName" value="<?php echo $AgentLName ?? ''; ?>" placeholder="Enter Last Name"></label><br><br>
<label>Office Name: <input type="text" name="OfficeName" value="<?php $OfficeName ?? ''; ?>" placeholder="Enter Office Name"></label><br><br>
<label>Company Email: <input type="text" name="Email" value="<?php echo $Email ?? ''; ?>" placeholder="Enter Email"></label><br><br>

<input type="submit" name="inputSubmit">

</form>


<footer>
    <div class="container">
      © <span id="year"></span> Laker Realty • All rights reserved
    </div>
  </footer>
   <script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>