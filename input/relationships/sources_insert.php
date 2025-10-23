<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
try{
  $stmt=$pdo->prepare("INSERT INTO Sources(manager_id,product_id) VALUES(?,?)");
  $stmt->execute([$_POST['manager_id'],$_POST['product_id']]);
  flash("Source link added.");
}catch(Throwable $e){flash("Error: ".$e->getMessage(),false);}
back_link();
