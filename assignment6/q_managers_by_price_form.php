<?php $title="Managers by minimum product price"; require 'inc/header.php'; ?>
<div class="card">
  <h2>Managers who manage at least one product with price ≥ threshold</h2>
  <form action="q_managers_by_price_results.php" method="get">
    <label>Minimum price: <input type="number" step="0.01" name="min_price" value="50" required></label>
    <button type="submit">Search</button>
  </form>
</div>
<?php require 'inc/footer.php'; ?>
