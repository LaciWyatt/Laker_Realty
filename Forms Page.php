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
	<!--background-color:orange;-->
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

<label for="listing price">Search Transactions by Sale Price:</label>
<input type="number" name="SalesPrice" id="SalePrice" placeholder="Enter Phone Number"><br><br>

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

<button type="submit">Search</button>
</form>

<!--Query Form PHP Parameters-->

<?php
/*
include "realestate.sql";

$property_type = '';
$single_family = false;
$condo = false;
$townhouse = false;
$multifam = false;
$commercial = false;
$land = false;

if($single_family){
	$property_type = 'Single Family';
}elseif($condo){
	$property_type = 'Condo';
}elseif($townhouse){
	$property_type = 'Townhouse';
}elseif($multifam){
	$property_type = 'Multi-Family';
}elseif($commercial){
	$property_type = 'Commercial';
}else($land){
	$property_type = 'Land';
}
*/


?>

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
<input type="text" id="State" name="State" placeholder="Enter State"><br><br>

<label for="ZipCode">Zip Code:</label>
<input type="number" id="ZipCode" name="ZipCode" placeholder="Enter Zip Code"><br><br>

<label for="clientType">Client Type:</label>
<select name="clientType" id="clientType">
<option value="">Any</option>
<option value="seller">Seller</option>
<option value="buyer">Buyer</option>
<option value="both">Both</option>

</select><br><br>

<button type="submit">Update</button>

</form>
</section>

<!--Update Form PHP Parameters-->
<?php



?>

<h5>Insert Form</h5>

<strong>Add New Listing Below:</strong>

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
</select><br><br>

<label for="PropertyAddress">New Address:</label>
<input type="text" id="PropertyAddress" name="PropertyAddress" placeholder="Enter Property Address"><br><br>

<label for="City">City of Property:</label>
<input type="text" id="PropertyCity" name="PropertyCity" placeholder="Enter City of Property"><br><br>


<label for="PropertyZipCode">Zip Code:</label>
<input type="number" id="PropertyZipCode" name="PropertyZipCode" placeholder="Enter Property Zip Code"><br><br>

<label>Agent First Name: <input type="text" name="AgentFName" placeholder="Enter First Name"></label><br><br>
<label>Agent Last Name: <input type="text" name="AgentLName" placeholder="Enter Last Name"></label><br><br>
<label>Office Name: <input type="text" name="OfficeName" placeholder="Enter Office Name"></label><br><br>
<label>Company Email: <input type="text" name="Email" placeholder="Enter Email"></label><br><br>

<input type="submit">
</form>

<!--Insert Form PHP Parameters-->
<?php



?>
<footer>
    <div class="container">
      © <span id="year"></span> Laker Realty • All rights reserved
    </div>
  </footer>
   <script>document.getElementById('year').textContent = new Date().getFullYear();</script>
</body>
</html>