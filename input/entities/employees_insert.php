<?php require __DIR__."/../db.php"; require __DIR__."/../common.php";
try{
  $stmt=$pdo->prepare("INSERT INTO Employees(name) VALUES(?)");
  $stmt->execute([$_POST['name']]);
  flash("Employee added.");
}catch(Throwable $e){flash("Error: ".$e->getMessage(),false);}
back_link();
