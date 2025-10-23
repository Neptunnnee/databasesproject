<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
try{
  $stmt=$pdo->prepare("INSERT INTO Product(brand,price) VALUES(?,?)");
  $stmt->execute([$_POST['brand'],$_POST['price']]);
  flash("Product added.");
}catch(Throwable $e){flash("Error: ".$e->getMessage(),false);}
back_link();
