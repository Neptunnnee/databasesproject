<?php
$title="Product Detail"; require 'inc/db_bootstrap.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT p.product_id, p.brand, p.price,
 (SELECT COUNT(*) FROM Manages m WHERE m.product_id=p.product_id) AS manager_count,
 (SELECT COUNT(*) FROM Promotes pr WHERE pr.product_id=p.product_id) AS campaign_count
 FROM Product p WHERE p.product_id=:id");
$stmt->execute([':id'=>$id]); $row=$stmt->fetch(PDO::FETCH_ASSOC);
require 'inc/header.php';
?>
<div class="card">
<?php if(!$row): ?>
  <h2>Not found</h2>
<?php else: ?>
  <h2>#<?= (int)$row['product_id'] ?> — <?= htmlspecialchars($row['brand']) ?></h2>
  <p>Price: <strong><?= htmlspecialchars($row['price']) ?></strong></p>
  <p>Managers: <?= (int)$row['manager_count'] ?> | Campaigns: <?= (int)$row['campaign_count'] ?></p>
  <a href="javascript:history.back()">← Back to results</a>
<?php endif; ?>
</div>
<?php require 'inc/footer.php'; ?>
