<!DOCTYPE html>
<html lang="en">

<head>
<title>Laker Realty - Forms</title>
<meta charset="utf-8">
<style>
<body>{
	font-size: 1em;
}
form{
	color: black;
	text-align:center;
	font-size: 1em;
	padding:1em;
}
header,nav,footer {
	background-color:orange;
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
</header>

<nav>
<a href="index.html">Home</a>
<a href="Forms Page.php">Forms</a>
<a href="reports.html">Reports</a>
</nav>



<h6>Search, Update, Or Add Information With The Forms Below </h6>

<h3>Query Form</h3>
<strong>Search Listings:</strong>

<form method="GET" action="realestate.sql">
<label for="city">City:</label>
<input type="text" name="city" id="city" placeholder="Enter City"><br><br>

<label for="listing price">Listing Price:</label>
<input type="number" name="listing price" id="listing price"><br><br>

<label for="type">Property Type:</label>
<select name="type" id="type">
<option value="">Any</option>
<option value="Single Family">Single Family</option>
<option value="Condo">Condo</option>
<option value="Townhouse">Townhouse</option>
<option value="Multi-Family">Multi-Family</option>
<option value="Commercial">Commercial</option>
<option value="Land">Land</option>
</select><br><br>

<label for="bedrooms">Bedrooms:</label>
<select name="bedrooms" id="bedrooms">
<option value="">Any</option>
<option value="1">1+</option>
<option value="2">2+</option>
<option value="3">3+</option>
<option value="4">4+</option>
</select><br><br>

<button type="submit">Search</button>
</form>

<!--Query Form PHP Code-->

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
<strong>Modify Existing Listings:</strong> 
<form>

<label for="Client-ID">Client ID:</label>
<input type="text" id="Client-ID" name="Client-ID" placeholder="Enter Client ID"><br>

<label for="Client-Name">Client Name:</label>
<input type="text" id="Client-Name" name="Client-Name" placeholder="Enter Client Name"><br>

<label for="Office-Phone">Office Phone Number:</label>
<input type="tel" id="Office-Phone" name="Office-Phone" placeholder="Enter New Office Number"><br>

<button type="submit">Update</button>
</form>
</section>

<!--Update Form PHP Code-->
<?php



?>

<h5>Insert Form</h5>

<strong>Add New Listing Below:</strong>

<form action="Forms Page.php" method="POST">
Property: <input type="text" name="Property"><br>
Agent: <input type="text" name="Agent"><br>
Office: <input type="text" name="Office"><br>
Office Phone: <input type="number" name="Office Phone"><br>
Region: <input type="text" name="Regions"><br>
Client: <input type="text" name="Client"><br>
<input type="submit">
</form>

<!--Insert Form PHP Code-->
<?php



?>
</body>
</html>