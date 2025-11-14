<?php // Laker Realty - Milestone 2 - Update Form

// Initialize variables with error array
$errors = [];
$success = '';
$client_id = $first_name = $last_name = $email = $phone = $address = $state = $zip = $client_type = " ";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize input data
    function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Retrieve and validate form inputs
    $client_id = clean_Input($_POST["client_id"]);
    $first_name = clean_Input($_POST["first_name"]);
    $last_name = clean_Input($_POST["last_name"]);
    $email = clean_Input($_POST["email"]);
    $phone = clean_Input($_POST["phone"]);
    $address = clean_Input($_POST["address"]);
    $state = clean_Input($_POST["state"]);
    $zip = clean_Input($_POST["zip"]);
    $client_type = clean_Input($_POST["client_type"]);

    // Validate on submit
    if (empty($client_id)) || !is_numeric($client_id) {
        $errors["client_id"] = "Valid Client ID is required.";
    }
    if (empty($first_name)) {
        $errors["first_name"] = "First name is required.";
    }
    if (empty($last_name)) {
        $errors["last_name"] = "Last name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "A valid email is required.";
    }
   if (empty($phone) || !filter_var(!preg_match("/^[0-9]{10}$/", $phone))) {
        $errors["phone"] = "A valid 10-digit phone number is required.";
    }
    if (empty($address)) {
        $errors["address"] = "Address is required.";
    }
    if (empty(state) || !preg_match("/^[A-Za-z]{2}$/", $state)) {
        $errors["state"] = "A valid 2-letter state code is required.";
    }
    if (empty($zip) || !preg_match("/^[0-9]{5}$/ ", $zip)) {
        $errors["zip"] = "A valid 5-digit ZIP code is required.";
    }
    if (empty($"client_type")) {
        $errors["client_type"] = "Client type is required.";
    }
    // If no errors, proceed to show success message
    if (empty($errors)) {
        // Milestone 2 requirement: validation only - no database operations
        $success = "Client updated successfully.";
    }
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
<title>Laker Realty - Forms</title>
<link rel="stylesheet" href="styles.css">
<meta charset="UTF-8">
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
<h2>Update Client Record</h2>

<?php if ($success_message): ?>
<p class="success"><?= $success_message ?></p>
<?php endif; ?>

<form method="POST" action="">

<label>Client ID:
<input type="text" name="client_id" value="<?= $client_id ?>">
</label>
<span class="error"><?= $errors["client_id"] ?? '' ?></span>

<label>First Name:
<input type="text" name="first_name" value="<?= $first_name ?>">
</label>
<span class="error"><?= $errors["first_name"] ?? '' ?></span>

<label>Last Name:
<input type="text" name="last_name" value="<?= $last_name ?>">
</label>
<span class="error"><?= $errors["last_name"] ?? '' ?></span>

<label>Email:
<input type="text" name="email" value="<?= $email ?>">
</label>
<span class="error"><?= $errors["email"] ?? '' ?></span>

<label>Phone Number:
<input type="text" name="phone" value="<?= $phone ?>" placeholder="1234567890">
</label>
<span class="error"><?= $errors["phone"] ?? '' ?></span>

<label>Address:
<input type="text" name="address" value="<?= $address ?>">
</label>
<span class="error"><?= $errors["address"] ?? '' ?></span>

<label>City:
<input type="text" name="city" value="<?= $city ?>">
</label>
<span class="error"><?= $errors["city"] ?? '' ?></span>

<label>State:
<input type="text" name="state" value="<?= $state ?>" maxlength="2" placeholder="CA">
</label>
<span class="error"><?= $errors["state"] ?? '' ?></span>

<label>Zip Code:
<input type="text" name="zip" value="<?= $zip ?>" maxlength="5" placeholder="90210">
</label>
<span class="error"><?= $errors["zip"] ?? '' ?></span>

<label>Client Type:
<select name="client_type">
<option value="">Select Type</option>
<option value="Individual" <?= ($client_type == "Individual" ? "selected" : "") ?>>Individual</option>
<option value="Business" <?= ($client_type == "Business" ? "selected" : "") ?>>Business</option>
</select>
</label>
<span class="error"><?= $errors["client_type"] ?? '' ?></span>

<button type="submit">Update</button>

</form>

</body>
</html>