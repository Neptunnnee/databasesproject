<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
$products=$pdo->query("SELECT p.product_id id, CONCAT(p.brand,' (#',p.product_id,')') label
                       FROM Product p
                       WHERE p.product_id NOT IN (SELECT product_id FROM Earphone_case)
                       ORDER BY p.brand, p.product_id")->fetchAll();
?>
<!doctype html><html><body>
<h1>Add Earphone Case</h1>
<form method="post" action="earphone_case_insert.php">
  <label>Product
    <select name="product_id" required><?php select_opts($products,'id','label'); ?></select>
  </label><br><br>
  <label>Colour <input name="colour" required></label><br><br>
  <button>Add</button>
</form>
<?php back_link(); ?>
</body></html>
