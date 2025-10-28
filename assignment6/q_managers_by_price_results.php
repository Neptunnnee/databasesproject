<?php
$title="Managers by minimum product price — Results";
require 'inc/db_bootstrap.php';
$min = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$sql = "SELECT DISTINCT e.emp_id, e.name
        FROM Business_Manager bm
        JOIN Employees e ON e.emp_id=bm.emp_id
        JOIN Manages m   ON m.emp_id=e.emp_id
        JOIN Product p   ON p.product_id=m.product_id
        WHERE p.price >= :min
        ORDER BY e.emp_id";
$stmt=$pdo->prepare($sql); $stmt->execute([':min'=>$min]); $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
require 'inc/header.php';
?>
<div class="card">
  <h2>Results (min price ≥ <?= htmlspecialchars($min) ?>)</h2>
  <?php if(!$rows): ?><p>No managers matched.</p>
  <?php else: ?>
  <table class="table"><thead><tr><th>ID</th><th>Name</th></tr></thead><tbody>
    <?php foreach($rows as $r): ?>
      <tr><td><?= (int)$r['emp_id'] ?></td><td><?= htmlspecialchars($r['name']) ?></td></tr>
    <?php endforeach; ?>
  </tbody></table>
  <?php endif; ?>
  <p><a href="q_managers_by_price_form.php">← Back to form</a></p>
</div>
<?php require 'inc/footer.php'; ?>
