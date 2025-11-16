<?php
// Laker Realty — Milestone 2 — User Validation 

// --------------------
// Helper Functions
// --------------------
function req($v){ return $v !== '' && $v !== null; }
function is_zip5($v){ return preg_match('/^\d{5}$/', $v); }
function is_name($v){ return preg_match("/^[A-Za-z' -]{1,60}$/", $v); }
function max_len($v,$n){ return strlen($v) <= $n; }
function in_list($v,$list){ return in_array($v, $list, true); }
function is_city($v){ return preg_match("/^[A-Za-z' -]{1,50}$/", $v); }

// --------------------
// Error & Success Arrays
// --------------------
$query_errors = $update_errors = $insert_errors = [];
$query_msg = $update_msg = $insert_msg = '';
$success = '';

// --------------------
// Allowed Values
// --------------------
$propertytype = ['Single Family','Condo','Townhouse','Multi-Family','Commercial','Land'];
$bedrooms_allowed = ['1','2','3','4'];

// --------------------
// Variables
// --------------------

// Query form
$qcity = $_GET['qcity'] ?? '';
$SalePrice = $_GET['SalesPrice'] ?? '';
$property_type = $_GET['type'] ?? '';
$bedrooms = $_GET['bedrooms'] ?? '';

// Update form
$client_id = $_POST['client_id'] ?? '';
$client_first_name = $_POST['first_name'] ?? '';
$client_last_name = $_POST['last_name'] ?? '';
$client_email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$ucity = $_POST['ucity'] ?? '';
$state = $_POST['state'] ?? '';
$zip = $_POST['zip'] ?? '';
$client_type = $_POST['client_type'] ?? '';

// Insert form
$PropertyAddress = $_POST['PropertyAddress'] ?? '';
$PropertyCity = $_POST['PropertyCity'] ?? '';
$PropertyZipCode = $_POST['PropertyZipCode'] ?? '';
$AgentFName = $_POST['AgentFName'] ?? '';
$AgentLName = $_POST['AgentLName'] ?? '';
$OfficeName = $_POST['OfficeName'] ?? '';
$Email = $_POST['Email'] ?? '';

// ===========================================================
// 1. QUERY FORM VALIDATION
// ===========================================================

if (isset($_GET['searchSubmit'])) {

// Validate City (optional)
if ($qcity !== '' && !is_city($qcity)) {
$query_errors[] = "City must contain letters, spaces, hyphens, or apostrophes.";
}

// Validate price (optional)
if ($SalePrice !== '' && !is_numeric($SalePrice)) {
$query_errors[] = "Sales price must be a number.";
}

// Property type (optional)
if ($property_type !== '' && !in_list($property_type, $propertytype)) {
$query_errors[] = "Invalid property type selected.";
}

// Bedrooms (optional)
if ($bedrooms !== '' && !in_list($bedrooms, $bedrooms_allowed)) {
$query_errors[] = "Invalid bedroom selection.";
}

if (empty($query_errors)) {
$query_msg = "Query received — showing results for your search.";
}
}

// ===========================================================
// 2. UPDATE FORM VALIDATION
// ===========================================================

if (isset($_POST['updateSubmit'])) {

if (!req($client_id) || !is_numeric($client_id)) {
$update_errors['client_id'] = "Valid numeric Client ID is required.";
}

if (!req($client_first_name) || !is_name($client_first_name)) {
$update_errors['first_name'] = "Valid first name is required.";
}

if (!req($client_last_name) || !is_name($client_last_name)) {
$update_errors['last_name'] = "Valid last name is required.";
}

if (!req($client_email) || !filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
$update_errors['email'] = "Valid email required.";
}

if (!req($phone) || !preg_match("/^[0-9-]{10,12}$/", $phone)) {
$update_errors['phone'] = "Phone number must be 10 digits.";
}

if (!req($address)) {
$update_errors['address'] = "Address is required.";
}

if ($ucity !== '' && !is_city($ucity)) {
$update_errors[] = "City must contain letters, spaces, hyphens, or apostrophes.";
}

if (!req($state) || !preg_match('/^[A-Za-z]{2}$/', $state)) {
$update_errors['state'] = "State must be 2 letters.";
}

if (!req($zip) || !preg_match('/^[0-9]{5}$/', $zip)) {
$update_errors['zip'] = "ZIP must be 5 digits.";
}

if (!req($client_type)) {
$update_errors['client_type'] = "Client type is required.";
}

if (empty($update_errors)) {
$update_msg = "Client updated successfully.";
		}
		
	
}

// ===========================================================
// 3. INSERT FORM VALIDATION
// ===========================================================

if (isset($_POST['inputSubmit'])) {

if (!req($PropertyAddress)) {
$insert_errors['PropertyAddress'] = "Address is required.";
}

if (!req($PropertyCity) || !is_city($PropertyCity)) {
$insert_errors['PropertyCity'] = "Valid city is required.";
}

if (!req($PropertyZipCode) || !is_zip5($PropertyZipCode)) {
$insert_errors['PropertyZipCode'] = "ZIP must be 5 digits.";
}

if (!req($AgentFName) || !is_name($AgentFName)) {
$insert_errors['AgentFName'] = "Valid first name required.";
}

if (!req($AgentLName) || !is_name($AgentLName)) {
$insert_errors['AgentLName'] = "Valid last name required.";
}

if (!req($OfficeName) || !is_name($OfficeName)) {
$insert_errors['OfficeName'] = "Office name is required (Letters Only).";
}

if (!req($Email) || !filter_var($Email, FILTER_VALIDATE_EMAIL)) {
$insert_errors['Email'] = "Valid email required.";
}

if (empty($insert_errors)) {
$insert_msg = "New listing added successfully.";
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


<!-- QUERY ERRORS -->


<?php if (!empty($query_errors)): ?>
<div class="errors">
<strong>Please fix:</strong> 
<ul>
<?php foreach ($query_errors as $err) echo "<li>$err</li>"; ?>
</ul>
</div>
<?php elseif (isset($_GET['searchSubmit'])): ?>
<div class="success"><?= $query_msg ?></div>
<?php endif; ?>


<strong>Search Listings:</strong>


<form method="GET">
<input type="hidden" name="action" value="query">

<label>City:</label>
<input type="text" name="qcity" value="<?= htmlspecialchars($qcity) ?>" placeholder="Enter City"><br><br>

<label>Sales Price:</label>
<input type="number" name="SalesPrice" value="<?= htmlspecialchars($SalePrice) ?>" placeholder="Enter Sales Price"><br><br>

<label>Property Type:</label>
<select name="type">
<option value="">Any</option>
<?php foreach ($propertytype as $opt): ?>
<option value="<?= $opt ?>" <?= ($property_type == $opt ? "selected" : "") ?>><?= $opt ?></option>
<?php endforeach; ?>
</select><br><br>

<label>Bedrooms:</label>
<select name="bedrooms">
<option value="">Any</option>
<option value="1">1+</option>
<option value="2">2+</option>
<option value="3">3+</option>
<option value="4">4+</option>
</select><br><br>

<button type="submit" name="searchSubmit">Search</button>
</form>

<hr>


	<h3>Update Form</h3>
	

<!-- UPDATE MESSAGES -->
<?php if (!empty($update_errors)): ?>
<div class="errors">
<strong>Please fix:</strong> 
<ul>
<?php foreach ($update_errors as $e) echo "<li>$e</li>"; ?>
</ul>
</div>
<?php elseif (isset($_POST['updateSubmit'])): ?>
<div class="success"><?= $update_msg ?></div>
<?php endif; ?>



<strong>Modify Existing Clients:</strong>
 
 
<form method="POST">

<label>Client ID:</label>
<input type="text" name="client_id" value="<?= htmlspecialchars($client_id) ?>" placeholder="Enter Client ID"><br><br>

<label>Client First Name:</label>
<input type="text" name="first_name" value="<?= htmlspecialchars($client_first_name) ?>" placeholder="Enter Client First Name "><br><br>

<label>Client Last Name:</label>
<input type="text" name="last_name" value="<?= htmlspecialchars($client_last_name) ?>" placeholder="Enter Client Last Name"><br><br>

<label>Email:</label>
<input type="text" name="email" value="<?= htmlspecialchars($client_email) ?>" placeholder="Enter Client Email"><br><br>

<label>Phone Number:</label>
<input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" placeholder="Enter Number"><br><br>

<label>Address:</label>
<input type="text" name="address" value="<?= htmlspecialchars($address) ?>" placeholder="Enter Address"><br><br>

<label>City:</label>
<input type="text" name="ucity" value="<?= htmlspecialchars($ucity) ?>" placeholder="Enter City"><br><br>

<label>State:</label>
<input type="text" name="state" value="<?= htmlspecialchars($state) ?>" placeholder="GA"><br><br>

<label>Zip Code:</label>
<input type="text" name="zip" value="<?= htmlspecialchars($zip) ?>" placeholder="Enter Zip Code"><br><br>

<label>Client Type:</label>
<select name="client_type">
<option value="">Select</option>
<option value="Seller" <?= ($client_type=="Seller"?"selected":"") ?>>Seller</option>
<option value="Buyer" <?= ($client_type=="Buyer" ?"selected":"") ?>>Buyer</option>
<option value="Both" <?= ($client_type=="Both" ?"selected":"") ?>>Both</option>
</select><br><br>

<button type="submit" name="updateSubmit">Update</button>
</form>

<hr>

	<h3>Insert Form</h3>

<!-- INSERT MESSAGES -->
<?php if (!empty($insert_errors)): ?>
<div class="errors">
<strong>Please fix:</strong> 
<ul>
<?php foreach($insert_errors as $msg) echo "<li>$msg</li>"; ?>
</ul>
</div>
<?php elseif (isset($_POST['inputSubmit'])): ?>
<div class="success"><?= $insert_msg ?></div>
<?php endif; ?>


<strong>Add New Listing Below:</strong>


<form method="POST">

<label>Select Property Type:</label>
<select name="type">
<option value="">Select</option>
<?php foreach($propertytype as $opt): ?>
<option value="<?= $opt ?>"><?= $opt ?></option>
<?php endforeach; ?>
</select><br><br>

<label>New Address:</label>
<input type="text" name="PropertyAddress" value="<?= htmlspecialchars($PropertyAddress) ?>" placeholder="Enter Address"><br><br>

<label>City of Property:</label>
<input type="text" name="PropertyCity" value="<?= htmlspecialchars($PropertyCity) ?>" placeholder="Enter City"><br><br>

<label>Zip Code:</label>
<input type="text" name="PropertyZipCode" value="<?= htmlspecialchars($PropertyZipCode) ?>" placeholder="Enter Zip Code"><br><br>

<label>Agent First Name:</label>
<input type="text" name="AgentFName" value="<?= htmlspecialchars($AgentFName) ?>" placeholder="Enter First Name"><br><br>

<label>Agent Last Name:</label>
<input type="text" name="AgentLName" value="<?= htmlspecialchars($AgentLName) ?>" placeholder="Enter Last Name"><br><br>

<label>Office Name:</label>
<input type="text" name="OfficeName" value="<?= htmlspecialchars($OfficeName) ?>" placeholder="Enter Office Name"><br><br>

<label>Email:</label>
<input type="text" name="Email" value="<?= htmlspecialchars($Email) ?>" placeholder="Enter Email"><br><br>

<button type="submit" name="inputSubmit">Add Listing</button>
</form>
<footer>
    <div class="container">
      © <span id="year"></span> Laker Realty • All rights reserved
    </div>
  </footer>
   <script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>
