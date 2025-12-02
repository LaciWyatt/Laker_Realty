<?php
// Laker Realty — Milestone 3 

require_once __DIR__ . '/db conn.php'; // creates $pdo

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

// --------------------
// Allowed Values
// --------------------
$propertytype = ['Single Family','Condo','Townhouse','Multi-Family','Commercial','Land'];
$bedrooms_allowed = ['1','2','3','4'];

// --------------------
// Variables (INPUTS)
// --------------------

// Query form (GET)
$qcity         = $_GET['qcity']       ?? '';
$SalePrice     = $_GET['SalesPrice']  ?? '';
$property_type = $_GET['type']        ?? '';
$bedrooms      = $_GET['bedrooms']    ?? '';

// Update form (POST) - clients table
$client_id         = $_POST['client_id']   ?? '';
$client_first_name = $_POST['first_name']  ?? '';
$client_last_name  = $_POST['last_name']   ?? '';
$client_email      = $_POST['email']       ?? '';
$phone             = $_POST['phone']       ?? '';
$address           = $_POST['address']     ?? '';
$ucity             = $_POST['ucity']       ?? '';
$state             = $_POST['state']       ?? '';
$zip               = $_POST['zip']         ?? '';
$client_type       = $_POST['client_type'] ?? '';

// Insert form (POST) - properties + agents
$ListingPrice     = $_POST['ListingPrice']     ?? '';
$PropertyAddress  = $_POST['PropertyAddress']  ?? '';
$PropertyCity     = $_POST['PropertyCity']     ?? '';
$PropertyState    = $_POST['PropertyState']    ?? '';
$PropertyZipCode  = $_POST['PropertyZipCode']  ?? '';
$AgentFName       = $_POST['AgentFName']       ?? '';
$AgentLName       = $_POST['AgentLName']       ?? '';
$OfficeName       = $_POST['OfficeName']       ?? '';
$Email            = $_POST['Email']           ?? '';
$PropertyTypeName = $_POST['type']            ?? '';
$regionNumber     = $_POST['regionNumber']    ?? ''; // optional, can be NULL

// For displaying DB results
$query_results = [];
$updated_client = null;
$inserted_property = null;

// ===========================================================
// 1. QUERY FORM: validate and run prepared SELECT 
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
        // Build SQL using prepared statements
        $sql = "SELECT p.property_id,
                       p.address,
                       p.city,
                       p.state,
                       p.zip_code,
                       p.bedrooms,
                       p.listing_price,
                       p.listing_date,
                       pt.type_name,
                       a.first_name AS agent_first,
                       a.last_name  AS agent_last
                FROM properties p
                LEFT JOIN property_types pt ON p.type_id = pt.type_id
                LEFT JOIN agents a ON p.agent_id = a.agent_id
                WHERE 1=1";
        $params = [];

        if ($qcity !== '') {
            $sql .= " AND p.city LIKE :city";
            $params[':city'] = $qcity . '%';
        }
        if ($SalePrice !== '') {
            $sql .= " AND p.listing_price <= :price";
            $params[':price'] = $SalePrice;
        }
        if ($property_type !== '') {
            $sql .= " AND pt.type_name = :ptype";
            $params[':ptype'] = $property_type;
        }
        if ($bedrooms !== '') {
            $sql .= " AND p.bedrooms >= :bedrooms";
            $params[':bedrooms'] = (int)$bedrooms;
        }

        $sql .= " ORDER BY p.listing_price ASC LIMIT 100";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $query_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $query_msg = "Query executed successfully. " . count($query_results) . " result(s) found.";
        } catch (PDOException $ex) {
            $query_errors[] = "Database error while searching: " . htmlspecialchars($ex->getMessage());
        }
    }
}

// ===========================================================
// 2. UPDATE FORM: validate and run prepared UPDATE 
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

    if (!req($phone) || !preg_match("/^[0-9-]{10,15}$/", $phone)) {
        $update_errors['phone'] = "Phone number must be 10–15 digits (numbers and dashes).";
    }

    if (!req($address)) {
        $update_errors['address'] = "Address is required.";
    }

    if ($ucity !== '' && !is_city($ucity)) {
        $update_errors['ucity'] = "City must contain letters, spaces, hyphens, or apostrophes.";
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
        try {
            $sql = "UPDATE clients
                    SET first_name  = :first_name,
                        last_name   = :last_name,
                        email       = :email,
                        phone       = :phone,
                        address     = :address,
                        city        = :city,
                        state       = :state,
                        zip_code    = :zip,
                        client_type = :client_type
                    WHERE client_id = :client_id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':first_name'  => $client_first_name,
                ':last_name'   => $client_last_name,
                ':email'       => $client_email,
                ':phone'       => $phone,
                ':address'     => $address,
                ':city'        => $ucity,
                ':state'       => strtoupper($state),
                ':zip'         => $zip,
                ':client_type' => $client_type,
                ':client_id'   => (int)$client_id
            ]);

            if ($stmt->rowCount() > 0) {
                $update_msg = "Client updated successfully (Client ID: " . (int)$client_id . ").";

                // Retrieve the updated row to show confirmation
                $check = $pdo->prepare("SELECT * FROM clients WHERE client_id = :id");
                $check->execute([':id' => (int)$client_id]);
                $updated_client = $check->fetch(PDO::FETCH_ASSOC);
            } else {
                $update_msg = "Update completed, but no rows changed. Check the Client ID.";
            }
        } catch (PDOException $ex) {
            $update_errors[] = "Database error while updating: " . htmlspecialchars($ex->getMessage());
        }
    }
}

// ===========================================================
// 3. INSERT FORM: validate and run prepared INSERT 
// ===========================================================

if (isset($_POST['inputSubmit'])) {

    if (!req($PropertyAddress)) {
        $insert_errors['PropertyAddress'] = "Address is required.";
    }

    if (!req($PropertyCity) || !is_city($PropertyCity)) {
        $insert_errors['PropertyCity'] = "Valid city is required.";
    }

    if (!req($PropertyState) || !preg_match('/^[A-Za-z]{2}$/', $PropertyState)) {
        $insert_errors['PropertyState'] = "State must be 2 letters.";
    }

    if (!req($PropertyZipCode) || !is_zip5($PropertyZipCode)) {
        $insert_errors['PropertyZipCode'] = "ZIP must be 5 digits.";
    }

    if (!req($ListingPrice) || !is_numeric($ListingPrice)) {
        $insert_errors['ListingPrice'] = "Listing price is required and must be numeric.";
    }

    if (!req($AgentFName) || !is_name($AgentFName)) {
        $insert_errors['AgentFName'] = "Valid first name required.";
    }

    if (!req($AgentLName) || !is_name($AgentLName)) {
        $insert_errors['AgentLName'] = "Valid last name required.";
    }

    if (!req($OfficeName)) {
        $insert_errors['OfficeName'] = "Office name is required.";
    }

    if (!req($Email) || !filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $insert_errors['Email'] = "Valid email required.";
    }

    if (!req($PropertyTypeName)) {
        $insert_errors['type'] = "Property type is required.";
    }

    if (empty($insert_errors)) {
        try {
            $pdo->beginTransaction();

            // 1) Ensure the agent exists (by email). If not, insert.
            $agent_id = null;
            $findAgent = $pdo->prepare("SELECT agent_id FROM agents WHERE email = :email LIMIT 1");
            $findAgent->execute([':email' => $Email]);
            $rowAgent = $findAgent->fetch(PDO::FETCH_ASSOC);

            if ($rowAgent) {
                $agent_id = $rowAgent['agent_id'];
            } else {
                $insAgent = $pdo->prepare("INSERT INTO agents (first_name, last_name, email, office_name)
                                           VALUES (:fn, :ln, :email, :office)");
                $insAgent->execute([
                    ':fn'    => $AgentFName,
                    ':ln'    => $AgentLName,
                    ':email' => $Email,
                    ':office'=> $OfficeName
                ]);
                $agent_id = $pdo->lastInsertId();
            }

            // 2) Resolve property type to type_id (insert if new)
            $type_id = null;
            $findType = $pdo->prepare("SELECT type_id FROM property_types WHERE type_name = :tname LIMIT 1");
            $findType->execute([':tname' => $PropertyTypeName]);
            $rowType = $findType->fetch(PDO::FETCH_ASSOC);

            if ($rowType) {
                $type_id = $rowType['type_id'];
            } else {
                $insType = $pdo->prepare("INSERT INTO property_types (type_name, description)
                                          VALUES (:tname, :desc)");
                $insType->execute([
                    ':tname' => $PropertyTypeName,
                    ':desc'  => $PropertyTypeName . " (added via Forms Page)"
                ]);
                $type_id = $pdo->lastInsertId();
            }

            // 3) Insert into properties (many columns allowed to be NULL for this assignment)
            $insProp = $pdo->prepare("
                INSERT INTO properties
                (address, city, state, zip_code, type_id, region_number,
                 bedrooms, bathrooms, square_feet, year_built,
                 listing_price, listing_date, status, agent_id,
                 description, image_url)
                VALUES
                (:address, :city, :state, :zip, :type_id, :region_number,
                 NULL, NULL, NULL, NULL,
                 :listing_price, :listing_date, :status, :agent_id,
                 :description, NULL)
            ");

            $insProp->execute([
                ':address'       => $PropertyAddress,
                ':city'          => $PropertyCity,
                ':state'         => strtoupper($PropertyState),
                ':zip'           => $PropertyZipCode,
                ':type_id'       => $type_id,           
                ':region_number' => ($regionNumber === '' ? null : $regionNumber),
                ':listing_price' => $ListingPrice,
                ':listing_date'  => date('Y-m-d'),
                ':status'        => 'Available',
                ':agent_id'      => $agent_id,
                ':description'   => 'Added via Forms Page'
            ]);

            $newPropertyId = $pdo->lastInsertId();
            $insert_msg = "New listing added successfully. Property ID: " . $newPropertyId;

            // Retrieve inserted property to show confirmation
            $getProp = $pdo->prepare("SELECT p.*, pt.type_name, a.first_name AS agent_first, a.last_name AS agent_last
                                      FROM properties p
                                      LEFT JOIN property_types pt ON p.type_id = pt.type_id
                                      LEFT JOIN agents a ON p.agent_id = a.agent_id
                                      WHERE p.property_id = :pid");
            $getProp->execute([':pid' => $newPropertyId]);
            $inserted_property = $getProp->fetch(PDO::FETCH_ASSOC);

            $pdo->commit();
        } catch (PDOException $ex) {
            $pdo->rollBack();
            $insert_errors[] = "Database error while inserting: " . htmlspecialchars($ex->getMessage());
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Laker Realty - Forms Page (Milestone 3)</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
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
        .form-container{
            background: #ffff;
        }
        .errors { color: #ffdddd; background:#660000; padding:10px; width:80%; margin:0 auto 10px; }
        .errors ul { margin:0; padding-left:20px; }
        .success { color:#003300; background:#ccffcc; padding:10px; width:80%; margin:0 auto 10px; }
    </style>
</head>
<body>

<header>
    <h1>Laker Realty Group</h1>
    <img src="/logo.png" alt="Laker Realty logo" width="400" height="250">
    <nav aria-label="Primary">
        <a href="IndexHome.html">Home</a> |
        <a href="Forms Page.php" aria-current="page">Forms</a> |
        <a href="Reports.html">Reports</a>
    </nav>
</header>

<p style="text-align:center;">Search, Update, or Add Information Using the Forms Below.</p>

<!-- =========================== -->
<!--  QUERY FORM + RESULTS      -->
<!-- =========================== -->
<h3>Query Form <!--(Properties)--> </h3>

<?php if (!empty($query_errors)): ?>
<div class="errors">
    <strong>Please fix:</strong>
    <ul>
        <?php foreach ($query_errors as $err) echo "<li>" . htmlspecialchars($err) . "</li>"; ?>
    </ul>
</div>
<?php elseif (isset($_GET['searchSubmit'])): ?>
<div class="success"><?= htmlspecialchars($query_msg) ?></div>
<?php endif; ?>

<form method="GET">
    <input type="hidden" name="action" value="query">

    <label>City:</label>
    <input type="text" name="qcity" value="<?= htmlspecialchars($qcity) ?>" placeholder="Enter City"><br>

    <label>Max Sales Price:</label>
    <input type="number" name="SalesPrice" value="<?= htmlspecialchars($SalePrice) ?>" placeholder="Enter Max Sales Price"><br>

    <label>Property Type:</label>
    <select name="type">
        <option value="">Any</option>
        <?php foreach ($propertytype as $opt): ?>
            <option value="<?= htmlspecialchars($opt) ?>" <?= ($property_type == $opt ? "selected" : "") ?>>
                <?= htmlspecialchars($opt) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Bedrooms:</label>
    <select name="bedrooms">
        <option value="">Any</option>
        <option value="1" <?= ($bedrooms == '1' ? "selected" : "") ?>>1+</option>
        <option value="2" <?= ($bedrooms == '2' ? "selected" : "") ?>>2+</option>
        <option value="3" <?= ($bedrooms == '3' ? "selected" : "") ?>>3+</option>
        <option value="4" <?= ($bedrooms == '4' ? "selected" : "") ?>>4+</option>
    </select><br>

    <div style="text-align:center;">
        <button type="submit" name="searchSubmit">Search</button>
    </div>
</form>

<?php if (!empty($query_results)): ?>
<table border='1' style='width:90%; margin:auto; border-collapse:collapse;'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Address</th>
            <th>City/State/ZIP</th>
            <th>Type</th>
            <th>Bedrooms</th>
            <th>Price</th>
            <th>Listing Date</th>
            <th>Agent</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($query_results as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['property_id'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['address'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['city'] ?? '') . ', ' . htmlspecialchars($row['state'] ?? '') . ' ' . htmlspecialchars($row['zip_code'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['type_name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['bedrooms'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['listing_price'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['listing_date'] ?? '') ?></td>
            <td><?= htmlspecialchars(($row['agent_first'] ?? '') . ' ' . ($row['agent_last'] ?? '')) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<hr>

<!-- =========================== -->
<!--  UPDATE FORM + CONFIRM      -->
<!-- =========================== -->
<h3>Update Form <!--(Clients)--></h3>

<?php if (!empty($update_errors)): ?>
<div class="errors">
    <strong>Please fix:</strong>
    <ul>
        <?php foreach ($update_errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?>
    </ul>
</div>
<?php elseif (isset($_POST['updateSubmit'])): ?>
<div class="success"><?= htmlspecialchars($update_msg) ?></div>
<?php if (!empty($updated_client)): ?>
<table>
    <thead>
        <tr>
            <th>Client ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Zip</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= htmlspecialchars($updated_client['client_id']) ?></td>
            <td><?= htmlspecialchars($updated_client['first_name']) ?></td>
            <td><?= htmlspecialchars($updated_client['last_name']) ?></td>
            <td><?= htmlspecialchars($updated_client['email']) ?></td>
            <td><?= htmlspecialchars($updated_client['phone']) ?></td>
            <td><?= htmlspecialchars($updated_client['address']) ?></td>
            <td><?= htmlspecialchars($updated_client['city']) ?></td>
            <td><?= htmlspecialchars($updated_client['state']) ?></td>
            <td><?= htmlspecialchars($updated_client['zip_code']) ?></td>
        </tr>
    </tbody>
</table>
<?php endif; ?>
<?php endif; ?>

<form method="POST">
    <label>Client ID:</label>
    <input type="text" name="client_id" value="<?= htmlspecialchars($client_id) ?>" placeholder="Enter Client ID"><br>

    <label>Client First Name:</label>
    <input type="text" name="first_name" value="<?= htmlspecialchars($client_first_name) ?>" placeholder="Enter First Name"><br>

    <label>Client Last Name:</label>
    <input type="text" name="last_name" value="<?= htmlspecialchars($client_last_name) ?>" placeholder="Enter Last Name"><br>

    <label>Email:</label>
    <input type="text" name="email" value="<?= htmlspecialchars($client_email) ?>" placeholder="Enter Email"><br>

    <label>Phone Number:</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" placeholder="Enter Number"><br>

    <label>Address:</label>
    <input type="text" name="address" value="<?= htmlspecialchars($address) ?>" placeholder="Enter Address"><br>

    <label>City:</label>
    <input type="text" name="ucity" value="<?= htmlspecialchars($ucity) ?>" placeholder="Enter City"><br>

    <label>State:</label>
    <input type="text" name="state" value="<?= htmlspecialchars($state) ?>" placeholder="GA"><br>

    <label>Zip Code:</label>
    <input type="text" name="zip" value="<?= htmlspecialchars($zip) ?>" placeholder="Enter Zip Code"><br>

    <label>Client Type:</label>
    <select name="client_type">
        <option value="">Select</option>
        <option value="Seller" <?= ($client_type=="Seller"?"selected":"") ?>>Seller</option>
        <option value="Buyer" <?= ($client_type=="Buyer" ?"selected":"") ?>>Buyer</option>
        <option value="Both" <?= ($client_type=="Both" ?"selected":"") ?>>Both</option>
    </select><br>

    <div style="text-align:center;">
        <button type="submit" name="updateSubmit">Update</button>
    </div>
</form>

<hr>

<!-- =========================== -->
<!--  INSERT FORM + CONFIRM      -->
<!-- =========================== -->
<h3>Insert Form <!--(New Listing)--></h3>

<?php if (!empty($insert_errors)): ?>
<div class="errors">
    <strong>Please fix:</strong>
    <ul>
        <?php foreach($insert_errors as $msg) echo "<li>" . htmlspecialchars($msg) . "</li>"; ?>
    </ul>
</div>
<?php elseif (isset($_POST['inputSubmit'])): ?>
<div class="success"><?= htmlspecialchars($insert_msg) ?></div>
<?php if (!empty($inserted_property)): ?>
<table>
    <thead>
        <tr>
            <th>Property ID</th>
            <th>Listing Price</th>
            <th>Address</th>
            <th>City</th>
            <th>Type</th>
            <th>Agent</th>
            <th>Status</th>
            <th>Listing Date</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= htmlspecialchars($inserted_property['property_id']) ?></td>
            <td><?= htmlspecialchars($inserted_property['listing_price']) ?></td>
            <td><?= htmlspecialchars($inserted_property['address']) ?></td>
            <td><?= htmlspecialchars($inserted_property['city']) ?></td>
            <td><?= htmlspecialchars($inserted_property['type_name']) ?></td>
            <td><?= htmlspecialchars($inserted_property['agent_first'] . ' ' . $inserted_property['agent_last']) ?></td>
            <td><?= htmlspecialchars($inserted_property['status']) ?></td>
            <td><?= htmlspecialchars($inserted_property['listing_date']) ?></td>
        </tr>
    </tbody>
</table>
<?php endif; ?>
<?php endif; ?>

<form method="POST">
    <label>Select Property Type:</label>
    <select name="type">
        <option value="">Select</option>
        <?php foreach($propertytype as $opt): ?>
            <option value="<?= htmlspecialchars($opt) ?>" <?= ($PropertyTypeName==$opt ? "selected" : "") ?>>
                <?= htmlspecialchars($opt) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Listing Price:</label>
    <input type="text" name="ListingPrice" value="<?= htmlspecialchars($ListingPrice) ?>" placeholder="Enter Listing Price"><br>

    <label>New Address:</label>
    <input type="text" name="PropertyAddress" value="<?= htmlspecialchars($PropertyAddress) ?>" placeholder="Enter Address"><br>

    <label>City of Property:</label>
    <input type="text" name="PropertyCity" value="<?= htmlspecialchars($PropertyCity) ?>" placeholder="Enter City"><br>

    <label>State:</label>
    <input type="text" name="PropertyState" value="<?= htmlspecialchars($PropertyState) ?>" placeholder="GA"><br>

    <label>Zip Code:</label>
    <input type="text" name="PropertyZipCode" value="<?= htmlspecialchars($PropertyZipCode) ?>" placeholder="Enter Zip Code"><br>

	<label>Regions:</label>
	<select name="regionNumber">
		<option value="">None</option>
		<option value="1">Metro Atlanta</option>
		<option value="2">Coastal Georgia</option>
		<option value="3">North Georgia Mountains</option>
		<option value="4">West & Central Georgia</option>
		<option value="5">Northeast Georgia</option>
		<option value="6">South Georgia</option>
		</select>
		<br>
		

    <label>Agent First Name:</label>
    <input type="text" name="AgentFName" value="<?= htmlspecialchars($AgentFName) ?>" placeholder="Enter First Name"><br>

    <label>Agent Last Name:</label>
    <input type="text" name="AgentLName" value="<?= htmlspecialchars($AgentLName) ?>" placeholder="Enter Last Name"><br>

    <label>Office Name:</label>
    <input type="text" name="OfficeName" value="<?= htmlspecialchars($OfficeName) ?>" placeholder="Enter Office Name"><br>

    <label>Email:</label>
    <input type="text" name="Email" value="<?= htmlspecialchars($Email) ?>" placeholder="Enter Agent Email"><br>

    <div style="text-align:center;">
        <button type="submit" name="inputSubmit">Add Listing</button>
    </div>
</form>

<footer>
    <p>&copy; <?= date('Y') ?> Laker Realty • All rights reserved</p>
</footer>

</body>
</html>
