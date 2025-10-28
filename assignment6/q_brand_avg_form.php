<?php $title="Brands with average price ≥ threshold"; require 'inc/header.php'; ?>
<div class="card">
  <h2>Brands with average price ≥ threshold and at least K items</h2>
  <form action="q_brand_avg_results.php" method="get">
    <label>Min average price: <input type="number" step="0.01" name="min_avg" value="75" required></label>
    <label>Min items: <input type="number" name="min_items" value="2" required></label>
    <button type="submit">Search</button>
  </form>
</div>
<?php require 'inc/footer.php'; ?>
