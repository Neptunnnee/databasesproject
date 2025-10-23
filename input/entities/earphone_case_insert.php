<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
try{
  $stmt=$pdo->prepare("INSERT INTO Earphone_case(product_id,colour) VALUES(?,?)");
  $stmt->execute([$_POST['product_id'],$_POST['colour']]);
  flash("Earphone case added.");
}catch(Throwable $e){flash("Error: ".$e->getMessage(),false);}
back_link();
