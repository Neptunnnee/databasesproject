<?php
require_once(__DIR__ . '/db_bootstrap.php');  // gives $pdo
require_once(__DIR__ . '/auth.php');          // auth_check(), auth_form()

// A write page must be POSTed with credentials. If not, show login form.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  auth_form('Please log in to continue.');
  exit;
}

// Validate credentials
$u = $_POST['auth_user'] ?? '';
$p = $_POST['auth_pass'] ?? '';
if (!auth_check($pdo, $u, $p)) {
  http_response_code(403);
  auth_form('Invalid username or password.');
  exit;
}
