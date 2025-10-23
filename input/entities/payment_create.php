<?php require __DIR__."/../db.php"; require __DIR__."/../common.php"; ?>
<!doctype html><html><body>
<h1>Add Payment</h1>
<form method="post" action="payment_insert.php">
  <label>Amount <input name="amount" type="number" step="0.01" min="0" required></label><br><br>
  <button>Add</button>
</form>
<?php back_link(); ?>
</body></html>
