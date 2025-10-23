<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
try{
  $stmt=$pdo->prepare("INSERT INTO Cable_organizers(product_id,width) VALUES(?,?)");
  $stmt->execute([$_POST['product_id'],$_POST['width']]);
  flash("Cable organizer added.");
}catch(Throwable $e){flash("Error: ".$e->getMessage(),false);}
back_link();
