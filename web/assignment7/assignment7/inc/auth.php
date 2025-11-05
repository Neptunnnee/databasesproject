<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function auth_check(PDO $pdo, string $u, string $p): bool {
  $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE username = ?");
  $stmt->execute([$u]);
  $row = $stmt->fetch();
  return $row && password_verify($p, $row['password_hash']);
}

function auth_form(string $msg = ''): void {
  $safeMsg = htmlspecialchars($msg, ENT_QUOTES);
  echo "<h3>Admin Authentication</h3>";
  if ($safeMsg) echo "<p style='color:red;'>$safeMsg</p>";
  echo <<<HTML
  <form method="post">
    <label>User: <input name="auth_user" required></label><br><br>
    <label>Password: <input type="password" name="auth_pass" required></label><br><br>
    <button type="submit">Login</button>
  </form>
HTML;
}
