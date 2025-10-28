<?php
$title="Products by campaign count — Results"; require 'inc/db_bootstrap.php';
$min=(int)($_GET['min_campaigns']??1);
$sql="SELECT p.product_id, p.brand, COUNT(*) AS campaign_count
      FROM Product p JOIN Promotes pr ON pr.product_id=p.product_id
      GROUP BY p.product_id, p.brand
      HAVING COUNT(*) >= :min
      ORDER BY campaign_count DESC, p.product_id";
$stmt=$pdo->prepare($sql); $stmt->execute([':min'=>$min]); $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
require 'inc/header.php';
?>
<div class="card">
  <h2>Results (campaigns ≥ <?= htmlspecialchars($min) ?>)</h2>
  <?php if(!$rows): ?><p>No products matched.</p>
  <?php else: ?>
  <table class="table"><thead><tr><th>ID</th><th>Brand</th><th>Campaigns</th><th></th></tr></thead><tbody>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?= (int)$r['product_id'] ?></td>
        <td><?= htmlspecialchars($r['brand']) ?></td>
        <td><?= (int)$r['campaign_count'] ?></td>
        <td><a href="product_detail.php?id=<?= (int)$r['product_id'] ?>">Details</a></td>
      </tr>
    <?php endforeach; ?>
  </tbody></table>
  <?php endif; ?>
  <p><a href="q_campaigns_form.php">← Back to form</a></p>
</div>
<?php require 'inc/footer.php'; ?>
