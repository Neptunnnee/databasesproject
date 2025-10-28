<?php $title="Products by minimum campaign count"; require 'inc/header.php'; ?>
<div class="card">
  <h2>Products referenced by ≥ N campaigns</h2>
  <form action="q_campaigns_results.php" method="get">
    <label>Min campaigns: <input type="number" name="min_campaigns" value="1" required></label>
    <button type="submit">Search</button>
  </form>
</div>
<?php require 'inc/footer.php'; ?>
