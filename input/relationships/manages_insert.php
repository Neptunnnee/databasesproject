<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
try{
  $stmt=$pdo->prepare("INSERT INTO Manages(emp_id,product_id,since_date) VALUES(?,?,?)");
  $stmt->execute([$_POST['emp_id'],$_POST['product_id'],$_POST['since_date']]);
  flash("Manage link added.");
}catch(Throwable $e){flash("Error: ".$e->getMessage(),false);}
back_link();
