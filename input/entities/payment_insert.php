<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
try{
  $stmt=$pdo->prepare("INSERT INTO Payment(amount) VALUES(?)");
  $stmt->execute([$_POST['amount']]);
  flash("Payment added.");
}catch(Throwable $e){flash("Error: ".$e->getMessage(),false);}
back_link();
