<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
$tech=$pdo->query("SELECT e.emp_id id, e.name label
                   FROM Tech_Manager tm JOIN Employees e ON e.emp_id=tm.emp_id
                   ORDER BY e.name, e.emp_id")->fetchAll();
$prods=$pdo->query("SELECT product_id id, CONCAT(brand,' (#',product_id,')') label FROM Product ORDER BY brand, product_id")->fetchAll();
?>
<!doctype html><html><body>
<h1>Link Sources</h1>
<form method="post" action="sources_insert.php">
  <label>Tech Manager
    <select name="manager_id" required><?php select_opts($tech,'id','label'); ?></select>
  </label><br><br>
  <label>Product
    <select name="product_id" required><?php select_opts($prods,'id','label'); ?></select>
  </label><br><br>
  <button>Add</button>
</form>
<?php back_link(); ?>
</body></html>
