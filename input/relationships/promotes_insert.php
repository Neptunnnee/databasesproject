<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
try{
  $stmt=$pdo->prepare("INSERT INTO Promotes(manager_id,product_id,campaign_name) VALUES(?,?,?)");
  $stmt->execute([$_POST['manager_id'],$_POST['product_id'],$_POST['campaign_name']]);
  flash("Promote link added.");
}catch(Throwable $e){flash("Error: ".$e->getMessage(),false);}
back_link();
