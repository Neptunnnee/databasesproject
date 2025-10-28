<?php
$title="Brand averages — Results"; require 'inc/db_bootstrap.php';
$min_avg=(float)($_GET['min_avg']??0); $min_items=(int)($_GET['min_items']??1);
$sql="SELECT brand, AVG(price) AS avg_price, COUNT(*) AS items
      FROM Product GROUP BY brand
      HAVING AVG(price) >= :min_avg AND COUNT(*) >= :min_items
      ORDER BY avg_price DESC, brand";
$stmt=$pdo->prepare($sql); $stmt->execute([':min_avg'=>$min_avg,':min_items'=>$min_items]);
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC); require 'inc/header.php';
?>
<div class="card">
  <h2>Results (avg ≥ <?= htmlspecialchars($min_avg) ?>, items ≥ <?= htmlspecialchars($min_items) ?>)</h2>
  <?php if(!$rows): ?><p>No brands matched.</p>
  <?php else: ?>
  <table class="table"><thead><tr><th>Brand</th><th>Average price</th><th>Items</th></tr></thead><tbody>
    <?php foreach($rows as $r): ?>
      <tr><td><?= htmlspecialchars($r['brand']) ?></td>
          <td><?= htmlspecialchars(number_format($r['avg_price'],2)) ?></td>
          <td><?= (int)$r['items'] ?></td></tr>
    <?php endforeach; ?>
  </tbody></table>
  <?php endif; ?>
  <p><a href="q_brand_avg_form.php">← Back to form</a></p>
</div>
<?php require 'inc/footer.php'; ?>
