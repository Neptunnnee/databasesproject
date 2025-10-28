<?php
$included = false;
$paths = [
  __DIR__ . '/../../assignment5/db.php', // common when A5 and A6 are siblings
  __DIR__ . '/../../db.php',
  __DIR__ . '/../db.php',
  __DIR__ . '/../../../db.php',
];
foreach ($paths as $p) { if (is_file($p)) { require_once $p; $included = true; break; } }
if (!$included) { http_response_code(500); echo "Adjust inc/db_bootstrap.php to include your db.php"; exit; }

if (!isset($pdo)) {
  if (function_exists('get_pdo')) { $pdo = get_pdo(); }
  else { http_response_code(500); echo "db.php must set \$pdo or expose get_pdo()."; exit; }
}
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
