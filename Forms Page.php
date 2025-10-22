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
h2,h3,h4,h5{
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
<h1> Laker Realty Group </h1> 
</header>

<nav>
<a href="index.html">Home</a>
<a href="Forms Page.php">Forms</a>
<a href="reports.html">Reports</a>
</nav>


<h2>Forms Page</h2>
<h6>Search, Update, Or Add Information With The Forms Below </h6>

<h3>Query Form</h3>

<!--PHP Code-->

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


<!--PHP Code-->
<?php



?>

<h5>Insert Form</h5>

<p><strong>Add New Information Below:</strong></p>
<form action="Forms Page.php" method="POST">
Property: <input type="text" name="Property"><br>
Agent: <input type="text" name="Agent"><br>
Office: <input type="text" name="Office"><br>
Office Phone: <input type="number" name="Office Phone"><br>
Region: <input type="text" name="Regions"><br>
Client: <input type="text" name="Client"><br>
<input type="submit">
</form>
<!--PHP Code-->
<?php



?>
</body>
</html>