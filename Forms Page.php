
<?php

// Laker Realty — Milestone 2 — Validation ONLY (no database)

//Helpers/Functions

	function req($v){ return $v !== '' && $v !== null; }
	function is_zip5($v){ return preg_match('/^\d{5}$/', $v); }
	function is_name($v){ return preg_match("/^[A-Za-z' -]{1,60}$/", $v); }
	function max_len($v,$n){ return strlen($v) <= $n; }
	function in_list($v,$list){ return in_array($v, $list, true); }
	function is_city($v){ return preg_match("/^[A-Za-z' -]{1,50}$/", $v);}
	//function clean_input($data) { return htmlspecialchars(stripslashes(trim($data)));}



// success msgs and error arrays for forms

	$query_errors = $update_errors = $insert_errors = [];
	$query_msg = $update_msg = $insert_msg = '';
	$errors = [];
	$success = '';

// ---- Allowed values ----

// Query Form Variables
	$city = ($_POST['city'] ?? '');
	$SalePrice = ($_POST['SalePrice'] ?? '');;
	$propertytype = ['Single Family','Condo','Townhouse','Multi-Family','Commercial','Land'];
	$bedrooms = ['1+','2+','3+','4+'];

// Update Form Variables


	$client_id = ($_POST['client_id'] ?? '');
    $client_first_name = ($_POST['first_name'] ?? '');
    $client_last_name = ($_POST['last_name'] ?? '');
    $client_email = ($_POST['email'] ?? '');
    $phone = ($_POST['phone'] ?? '');
    $address = ($_POST['address'] ?? '');
    $state = ($_POST['state']) ?? '';
    $zip = ($_POST['zip'] ?? '');
    $client_type = ($_POST['client_type'] ?? '');


// Insert Form Variables

	$PropertyAddress = ($_POST['PropertyAddress'] ?? '');
	$PropertyCity    = ($_POST['PropertyCity']    ?? '');
	$PropertyZipCode = ($_POST['PropertyZipCode'] ?? '');
	$AgentFName      = ($_POST['AgentFName']      ?? '');
	$AgentLName      = ($_POST['AgentLName']      ?? '');
	$OfficeName      = ($_POST['OfficeName']      ?? '');
	$Email           = ($_POST['Email']           ?? '');





	   //Query Form Validation
	
	
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] {
  if (!req($propertytype) || !in_list($propertytypes)) {
    $errors['type'] = 'Please select a valid property type.';
  }
    if (!req($bedrooms) || !in_list($bedrooms,$allowed_bedtypes)) {
    $errors['type'] = 'Please select number of bedrooms.';
  }
  
}
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'query') {

    // Collect inputs (optional during search, but if provided need validation)

    $property_type = isset($_POST['propertytype']) ?? '';

    $city = isset($_POST['city']) ?? '';

    $SalePrice = isset($_POST['SalePrice']) ?? '';
 
    $bedrooms= isset($_POST['bedrooms']) ?? '';
}

 if ($SalePrice !== '' && !is_numeric($SalePrice)) {

        $query_errors[] = "Sales price must be a number.";

    }
if ($city == (preg_match('/^[A-Za-z]$/', $city))) {

        $query_errors[] = "City must only contain letters.";

    }
	
	// If no errors, simulate a search and show confirmation 

if (empty($query_errors)) {

		$query_msg = "Query received — showing results for your search.";

	}
	

	
			//Update Form Validation 
			
if (empty($client_id))  {
        $update_errors['client_id'] = 'Valid Client ID is required.';
    }
	if (!is_numeric($client_id)){
		$update_errors['client_id'] = 'Valid Client ID is required (numbers only).';
	}
    if (empty($first_name)) {
        $update_errors['client_first_name'] = 'First name is required.';
    }
    if (empty($last_name)) {
        $update_errors['client_last_name'] = 'Last name is required.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $update_errors['client_email'] = 'A valid email is required.';
    }
   if (empty($phone) || !filter_var(!preg_match('/^[0-9]{10}$/', $phone))) {
        $update_errors['phone'] = 'A valid 10-digit phone number is required.';
    }
    if (empty($address)) {
        $update_errors['address'] = "Address is required.";
    }
    if (empty($state) || !preg_match('/^[A-Za-z]{2}$/', $state)) {
        $update_errors['state'] = 'A valid 2-letter state code is required.';
    }
    if (empty($zip) || !preg_match("/^[0-9]{5}$/ ", $zip)) {
        $update_errors['zip'] = 'A valid 5-digit ZIP code is required.';
    }
    if (empty($client_type)) {
        $update_errors['client_type'] = 'Client type is required.';
    }
    // If no errors, proceed to show success message
    if (empty($update_errors)) {
        // Milestone 2 requirement: validation only - no database operations
        $success = "Client updated successfully.";
    }


	

			//Insert Form Validation
	
	
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 /* if (!req($type) || !in_list($propertytype,)) {
    $errors['propertytype'] = 'Please select a valid property type.';
  }*/
  if (!req($PropertyAddress)) {
    $errors['PropertyAddress'] = 'Address is required.';
  } elseif (!max_len($PropertyAddress,120)) {
    $errors['PropertyAddress_len'] = 'Address is too long (max 120 characters).';
  }

  if (!req($PropertyCity)) {
    $errors['PropertyCity'] = 
	'City is required.';
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

  elseif (!$errors) {
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

 <!-- Display errors if any -->

        <?php if (!empty($query_errors)): ?>

            <div class="errors">

               <!-- <strong>Please fix these errors:</strong> -->

                <ul>

                    <?php foreach ($query_errors as $err) echo "<li>" . htmlspecialchars($err) . "</li>"; ?>

                </ul>

            </div>

        <?php endif; ?>



        <!-- Confirmation/message -->

        <?php if ($query_msg == ''): ?>

            <div class="success"><?= htmlspecialchars($query_msg); ?></div>

        <?php endif; ?>

<strong>Search Listings:</strong>
<form method="GET" action="Forms Page.php">

<label for="city">City:</label>
<input type="text" name="city" id="city" value= "<?= htmlspecialchars($city ?? ''); ?>" placeholder="Enter City"><br><br>

<label for="listing price">Search Transactions by Sales Price:</label>
<input type="number" name="SalesPrice" id="SalePrice" value="<?= htmlspecialchars($SalePrice ?? ''); ?>" placeholder="Enter Maximum Price"><br><br>

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


<strong>Modify Existing Clients:</strong> 
<form action="Forms Page.php" method="POST">

<?php if(empty($update_errors)): ?>
    <div class="errors">
     <!-- <strong>Please fix:</strong> -->
      <ul><?php foreach($update_errors as $msg) echo "<li>$msg</li>"; ?></ul>
    </div>
  <?php elseif(null && empty($errors)): ?>
    <div class="success"><?php echo $update_msg; ?></div>
  <?php endif; ?>
  
  
<label for="ClientID">Client ID:
<input type="text" id="ClientID" name="ClientID" value= "<?= htmlspecialchars($client_id ?? ''); ?>" placeholder="Enter Client ID"></label><br><br>

<label for="ClientFirstName">Client First Name:</label>
<input type="text" id="ClientFirstName" name="ClientFirstName" value="<?= htmlspecialchars ($client_first_name ?? ''); ?>" placeholder="Enter Client First Name"><br><br>

<label for="ClientLastName">Client Last Name:</label>
<input type="text" id="ClientLastName" name="ClientLastName" value="<?= htmlspecialchars ($client_last_name ?? ''); ?>" placeholder="Enter Client Last Name"><br><br>

<label for="Email">Email:</label>
<input type="text" id="ClientEmail" name="ClientEmail" value="<?= htmlspecialchars ($client_email ?? ''); ?>" placeholder="Enter Client Email"><br><br>

<label for="Phone">Phone Number:</label>
<input type="tel" id="Phone" name="Phone" value="<?= htmlspecialchars ($phone ?? ''); ?>"placeholder="Enter Phone Number"><br><br>

<label for="ClientAddress">Address:</label>
<input type="text" id="ClientAddress" name="Address" value="<?= htmlspecialchars ($address ?? ''); ?>" placeholder="Enter Street Address"><br><br>

<label for="City">City:</label>
<input type="text" id="city" name="city" value="<?= htmlspecialchars ($city ?? ''); ?>"placeholder="Enter City"><br><br>

<label for="State">State:</label>
<input type="text" id="State" name="State" value="<?= htmlspecialchars ($state ?? ''); ?>"placeholder="GA"><br><br>

<label for="ZipCode">Zip Code:</label>
<input type="number" id="ZipCode" name="ZipCode" value="<?= htmlspecialchars ($zip ?? ''); ?>" placeholder="Enter Zip Code"><br><br>

<label for="clientType">Client Type:</label>
<select name="clientType" id="clientType">
<option value="">Any</option>
<option value="seller" <?= ($client_type == "Seller" ? "selected" : "") ?>>Seller</option>
<option value="buyer" <?= ($client_type == "Buyer" ? "selected" : "") ?>>Buyer</option>
<option value="both" <?= ($client_type == "Both" ? "selected" : "") ?>>Both</option>

</select><br><br>

<button type="submit" name="updateSubmit">Update</button>

</form>
</section>


<!-- line break to seperate page-->
<hr>

<h5>Insert Form</h5>

<strong>Add New Listing Below:</strong>

 <?php if(empty($insert_errors)): ?>
    <div class="insert_errors">
     <!-- <strong>Please fix:</strong> -->
      <ul><?php foreach($insert_errors as $msg) echo "<li>$msg</li>"; ?></ul>
    </div>
  <?php elseif(null && empty($insert_errors)): ?>
    <div class="success"><?php echo $insert_msg; ?></div>
  <?php endif; ?>

<form action="Forms Page.php" method="POST">
<label for="type">Select Property Type:</label>
<select name="type" id="type">
<option value="">Select Type</option>
<!--
<option value="Single Family">Single Family</option>
<option value="Condo">Condo</option>
<option value="Townhouse">Townhouse</option>
<option value="Multi-Family">Multi-Family</option>
<option value="Commercial">Commercial</option>
<option value="Land">Land</option>
-->
<?php
            foreach($propertytype as $opt){
              $sel = ($propertytype===$opt)?'selected':'';
              echo "<option value=\"$opt\" $sel>$opt</option>";
            }
          ?>
</select><br><br>

<label for="PropertyAddress">New Address:</label>
<input type="text" id="PropertyAddress" name="PropertyAddress" value="<?= htmlspecialchars ($PropertyAddress ?? ''); ?>" placeholder="Enter Property Address"><br><br>

<label for="City">City of Property:</label>
<input type="text" id="PropertyCity" name="PropertyCity" value="<?= htmlspecialchars ($PropertyCity ?? ''); ?>" placeholder="Enter City of Property"><br><br>


<label for="PropertyZipCode">Zip Code:</label>
<input type="number" id="PropertyZipCode" name="PropertyZipCode" value="<?= htmlspecialchars ($PropertyZipCode ?? ''); ?>" placeholder="Enter Property Zip Code"><br><br>

<label>Agent First Name: <input type="text" name="AgentFName" value="<?= htmlspecialchars ($AgentFName ?? ''); ?>" placeholder="Enter First Name"></label><br><br>
<label>Agent Last Name: <input type="text" name="AgentLName" value="<?= htmlspecialchars($AgentLName ?? ''); ?>" placeholder="Enter Last Name"></label><br><br>
<label>Office Name: <input type="text" name="OfficeName" value="<?= htmlspecialchars($OfficeName ?? ''); ?>" placeholder="Enter Office Name"></label><br><br>
<label>Company Email: <input type="text" name="Email" value="<?= htmlspecialchars ($Email ?? ''); ?>" placeholder="Enter Email"></label><br><br>

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