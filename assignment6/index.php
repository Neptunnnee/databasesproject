<?php $title="Search Module — Home"; require 'inc/header.php'; ?>
<div class="card">
  <h1>Assignment 6: Search Component</h1>
  <p>Pick a query. Each form → list → detail page.</p>
  <div class="grid">
    <div class="card">
      <h3>Managers managing products priced ≥ threshold</h3>
      <p>Params: <span class="badge">min_price</span></p>
      <a href="q_managers_by_price_form.php">Open form →</a>
    </div>
    <div class="card">
      <h3>Brands with avg price ≥ threshold & ≥ K items</h3>
      <p>Params: <span class="badge">min_avg</span> <span class="badge">min_items</span></p>
      <a href="q_brand_avg_form.php">Open form →</a>
    </div>
    <div class="card">
      <h3>Products referenced by ≥ N campaigns</h3>
      <p>Param: <span class="badge">min_campaigns</span></p>
      <a href="q_campaigns_form.php">Open form →</a>
    </div>
    <div class="card">
      <h3>Products with no manager</h3>
      <a href="q_unmanaged_products.php">View list →</a>
    </div>
  </div>
</div>
<?php require 'inc/footer.php'; ?>
