<?php if (!isset($title)) { $title = "Search Module"; } ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title) ?></title>
  <link rel="stylesheet" href="assets/style.css">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <link rel="stylesheet"
        href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>

  <script src="assets/autocomplete.js"></script>
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
