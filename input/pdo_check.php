<?php
require __DIR__."/db.php";
$stmt = $pdo->query("SELECT 1 AS ok");
$row = $stmt->fetch();
echo $row ? "DB OK" : "DB FAIL";
