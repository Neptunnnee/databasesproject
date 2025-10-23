<?php require __DIR__."/../db.php"; require __DIR__."/../common.php"; ?>
<!doctype html><html><body>
<h1>Add Product</h1>
<form method="post" action="product_insert.php">
  <label>Brand <input name="brand" required></label><br><br>
  <label>Price <input name="price" type="number" step="0.01" min="0" required></label><br><br>
  <button>Add</button>
</form>
<?php back_link(); ?>
</body></html>
