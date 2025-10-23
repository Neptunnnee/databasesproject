<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
$emps=$pdo->query("SELECT emp_id id, name label FROM Employees ORDER BY name, emp_id")->fetchAll();
$prods=$pdo->query("SELECT product_id id, CONCAT(brand,' (#',product_id,')') label FROM Product ORDER BY brand, product_id")->fetchAll();
?>
<!doctype html><html><body>
<h1>Link Manages</h1>
<form method="post" action="manages_insert.php">
  <label>Employee
    <select name="emp_id" required><?php select_opts($emps,'id','label'); ?></select>
  </label><br><br>
  <label>Product
    <select name="product_id" required><?php select_opts($prods,'id','label'); ?></select>
  </label><br><br>
  <label>Since Date <input type="date" name="since_date" required></label><br><br>
  <button>Add</button>
</form>
<?php back_link(); ?>
</body></html>
