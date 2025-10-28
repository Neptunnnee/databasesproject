<?php if (!isset($title)) { $title = "Search Module"; } ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="nav">
        <a href="index.php">Home</a>
        <a href="q_managers_by_price_form.php">Managers by Min Price</a>
        <a href="q_brand_avg_form.php">Brand Avg ≥ threshold</a>
        <a href="q_campaigns_form.php">By Campaign Count</a>
        <a href="q_unmanaged_products.php">Unmanaged Products</a>
      </div>
    </div>
