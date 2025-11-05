<?php
require_once(__DIR__ . '/inc/db_bootstrap.php');
require_once(__DIR__ . '/inc/auth.php'); // we'll verify creds manually here

function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = $_POST['auth_user'] ?? '';
  $p = $_POST['auth_pass'] ?? '';

  if (!auth_check($pdo, $u, $p)) {
    http_response_code(403);
    $msg = 'Invalid username or password.';
  } else {
    $stmt = $pdo->prepare("DELETE FROM a7_notes WHERE id = ?");
    $stmt->execute([$id]);
    echo "<!doctype html><meta charset='utf-8'><title>Deleted</title>";
    echo "<p>🗑️ Note #".(int)$id." deleted by <b>".h($u)."</b>.</p>";
    echo "<p><a href='notes_list.php'>Back to list</a></p>";
    exit;
  }
}

// show confirmation + auth form
?>
<!doctype html><meta charset="utf-8"><title>Delete Note (protected)</title>
<h2>Delete Note #<?= $id ?> (protected)</h2>
<?php if ($msg): ?><p style="color:#c00;"><?= h($msg) ?></p><?php endif; ?>
<form method="post">
  <p>Are you sure you want to delete note #<?= $id ?>?</p>
  <div style="margin:1rem 0;padding:.75rem;border:1px dashed #99a;border-radius:.5rem;">
    <strong>Admin authentication</strong><br>
    <label>Username <input name="auth_user" required></label><br>
    <label>Password <input type="password" name="auth_pass" required></label>
  </div>
  <button type="submit">Delete</button>
  <a href="notes_list.php">Cancel</a>
</form>
