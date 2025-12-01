<?php
// Real Estate Report by Savian Walker
// Midtown Atlanta active listings by property type

$dsn  = "mysql:host=localhost;dbname=realestate;charset=utf8mb4";
$user = "root";
$pass = "";

$error = "";
$rows  = [];

function e($v){
    return htmlspecialchars($v ?? "", ENT_QUOTES, "UTF-8");
}

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $sql = "
        SELECT 
            p.property_id,
            p.address,
            p.city,
            p.state,
            p.listing_price,
            p.status,
            t.type_name,
            r.region,
        FROM properties AS p
        JOIN property_types AS t
            ON p.type_id = t.type_id
        JOIN regions AS r
            ON p.region_number = r.region_number
        WHERE p.status = 'active'
          AND r.region = 'Midtown Atlanta'
        ORDER BY t.type_name, p.listing_price DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();

} catch (PDOException $e) {
    $error = "Database error.";
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Laker Realty - Midtown Atlanta Listings (Savian Walker)</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{background:#0b1220;color:#e5e7eb;font-family:Arial,sans-serif;margin:0}
    header{background:#0f172a;border-bottom:1px solid #1f2a44;padding:10px 0}
    .container{max-width:900px;margin:0 auto;padding:0 20px}
    .nav{display:flex;align-items:center;justify-content:space-between}
    .left{display:flex;align-items:center;gap:10px}
    .left img{height:48px;border-radius:6px}
    .card{background:#0f172a;border:1px solid #1f2a44;padding:16px;border-radius:8px;margin-top:20px}
    h1{color:#60a5fa;margin-top:16px}
    h2{color:#93c5fd;margin-top:20px}
    table{width:100%;border-collapse:collapse;margin-top:10px;font-size:0.9rem}
    th,td{border-bottom:1px solid #1f2937;padding:8px;text-align:left}
    th{background:#020617}
    tr:nth-child(even){background:#020617}
    .price{text-align:right;white-space:nowrap}
    footer{border-top:1px solid #1f2a44;color:#94a3b8;text-align:center;padding:16px 0;margin-top:30px}
    a{color:#e5e7eb;text-decoration:none}
  </style>
</head>
<body>
<header>
  <div class="container nav">
    <div class="left">
      <img src="assets/img/logo.png" alt="Laker Realty logo">
      <strong>Laker Realty</strong>
    </div>
    <nav>
      <a href="index.html" style="margin-right:12px">Home</a>
      <a href="forms.html" style="margin-right:12px">Forms</a>
      <a href="reports.html">Reports</a>
    </nav>
  </div>
</header>

<main class="container">
  <h1>Midtown Atlanta Active Listings</h1>
  <div class="card">
    <p>Active Midtown Atlanta properties listed by <strong>Savian Walker</strong>, organized by property type.</p>
  </div>

  <?php if ($error): ?>
    <div class="card" style="color:#ffb4b4"><?php echo e($error); ?></div>
  <?php endif; ?>

  <?php
    // Group rows by property type
    $grouped = [];
    foreach ($rows as $r) {
        $grouped[$r["type_name"]][] = $r;
    }
  ?>

  <?php if (!$rows): ?>
    <div class="card">
      <p>No active listings found for Midtown Atlanta.</p>
    </div>
  <?php else: ?>
    <?php foreach ($grouped as $type => $listings): ?>
      <div class="card">
        <h2><?php echo e($type); ?></h2>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Address</th>
              <th>City / State</th>
              <th>Region</th>
              <th class="price">Listing Price</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($listings as $r): ?>
              <tr>
                <td><?php echo e($r['property_id']); ?></td>
                <td><?php echo e($r['address']); ?></td>
                <td><?php echo e($r['city']); ?>, <?php echo e($r['state']); ?></td>
                <td><?php echo e($r['region']); ?></td>
                <td class="price">$<?php echo number_format((float)$r['listing_price'], 2); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

</main>

<footer>
  <div class="container">© <span id="year"></span> Laker Realty • All rights reserved</div>
</footer>

<script>
  document.getElementById('year').textContent = new Date().getFullYear();
</script>

</body>
</html>
