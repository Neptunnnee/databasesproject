<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
try{
  $stmt=$pdo->prepare("INSERT INTO Smartwatch_bands(product_id,length) VALUES(?,?)");
  $stmt->execute([$_POST['product_id'],$_POST['length']]);
  flash("Smartwatch band added.");
}catch(Throwable $e){flash("Error: ".$e->getMessage(),false);}
back_link();
