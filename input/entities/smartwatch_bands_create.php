<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
$products=$pdo->query("SELECT p.product_id id, CONCAT(p.brand,' (#',p.product_id,')') label
                       FROM Product p
                       WHERE p.product_id NOT IN (SELECT product_id FROM Smartwatch_bands)
                       ORDER BY p.brand, p.product_id")->fetchAll();
?>
<!doctype html><html><body>
<h1>Add Smartwatch Band</h1>
<form method="post" action="smartwatch_bands_insert.php">
  <label>Product
    <select name="product_id" required><?php select_opts($products,'id','label'); ?></select>
  </label><br><br>
  <label>Length <input name="length" type="number" min="1" required></label><br><br>
  <button>Add</button>
</form>
<?php back_link(); ?>
</body></html>
