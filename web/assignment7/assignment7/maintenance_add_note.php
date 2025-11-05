<?php
require_once(__DIR__ . '/inc/db_bootstrap.php'); // gives $pdo
require_once(__DIR__ . '/inc/auth.php');         // auth_check()

function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$msg = '';
$note_val = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = $_POST['auth_user'] ?? '';
  $p = $_POST['auth_pass'] ?? '';
  $note_val = $_POST['note'] ?? '';

  if (!auth_check($pdo, $u, $p)) {
    http_response_code(403);
    $msg = 'Invalid username or password.';
  } elseif (trim($note_val) === '') {
    $msg = 'Note cannot be empty.';
  } else {
    $stmt = $pdo->prepare("INSERT INTO a7_notes (note, created_by) VALUES (?, ?)");
    $stmt->execute([$note_val, $u]);
    echo "<!doctype html><meta charset='utf-8'><title>Note added</title>";
    echo "<p>✅ Note added by <b>".h($u)."</b>.</p>";
    echo "<p><a href='notes_list.php'>Back to list</a></p>";
    exit;
  }
}
?>
<!doctype html><meta charset="utf-8"><title>Add Note (protected)</title>
<h2>Add Note (protected)</h2>
<?php if ($msg): ?>
  <p style="color:#c00;"><?= h($msg) ?></p>
<?php endif; ?>
<form method="post">
  <label>Note:<br>
    <textarea name="note" rows="5" cols="60" required><?= h($note_val) ?></textarea>
  </label>
  <div style="margin:1rem 0;padding:.75rem;border:1px dashed #99a;border-radius:.5rem;">
    <strong>Admin authentication</strong><br>
    <label>Username <input name="auth_user" required></label><br>
    <label>Password <input type="password" name="auth_pass" required></label>
  </div>
  <button type="submit">Add note</button>
</form>
<p><a href="notes_list.php">Back to list</a></p>
