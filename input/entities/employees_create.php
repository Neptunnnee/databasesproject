<?php require __DIR__."/../db.php"; require __DIR__."/../common.php"; ?>
<!doctype html><html><body>
<h1>Add Employee</h1>
<form method="post" action="employees_insert.php">
  <label>Name <input name="name" required></label><br><br>
  <button>Add</button>
</form>
<?php back_link(); ?>
</body></html>
