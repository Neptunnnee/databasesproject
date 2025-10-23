<?php require __DIR__."/db.php"; require __DIR__."/common.php"; ?>
<!doctype html><html><head><meta charset="utf-8"><title>Maintenance</title></head><body>
<h1>Maintenance</h1>
<h2>Entities</h2>
<ul>
  <li><a href="entities/employees_create.php">Add Employee</a></li>
  <li><a href="entities/product_create.php">Add Product</a></li>
  <li><a href="entities/payment_create.php">Add Payment</a></li>
  <li><a href="entities/smartwatch_bands_create.php">Add Smartwatch Band</a></li>
  <li><a href="entities/earphone_case_create.php">Add Earphone Case</a></li>
  <li><a href="entities/cable_organizers_create.php">Add Cable Organizer</a></li>
</ul>
<h2>Relationships</h2>
<ul>
  <li><a href="relationships/manages_create.php">Link Manages (Employee ↔ Product)</a></li>
  <li><a href="relationships/sources_create.php">Link Sources (Tech Manager ↔ Product)</a></li>
  <li><a href="relationships/promotes_create.php">Link Promotes (Marketing Manager ↔ Product)</a></li>
</ul>
</body></html>
