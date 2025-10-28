<?php
$title="Products with no manager"; require 'inc/db_bootstrap.php';
$sql="SELECT p.product_id, p.brand, p.price
      FROM Product p
      WHERE NOT EXISTS (SELECT 1 FROM Manages m WHERE m.product_id=p.product_id)
      ORDER BY p.product_id";
$rows=$pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
require 'inc/header.php';
?>
<div class="card">
  <h2>Products with no manager</h2>
  <?php if(!$rows): ?><p>All products currently have managers.</p>
  <?php else: ?>
  <table class="table"><thead><tr><th>ID</th><th>Brand</th><th>Price</th><th></th></tr></thead><tbody>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?= (int)$r['product_id'] ?></td>
        <td><?= htmlspecialchars($r['brand']) ?></td>
        <td><?= htmlspecialchars($r['price']) ?></td>
        <td><a href="product_detail.php?id=<?= (int)$r['product_id'] ?>">Details</a></td>
      </tr>
    <?php endforeach; ?>
  </tbody></table>
  <?php endif; ?>
  <p><a href="index.php">← Back home</a></p>
</div>
<?php require 'inc/footer.php'; ?>
