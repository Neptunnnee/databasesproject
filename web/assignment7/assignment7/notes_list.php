<?php
require_once(__DIR__ . '/inc/db_bootstrap.php');
function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$rows = $pdo->query("SELECT id, note, created_by, created_at FROM a7_notes ORDER BY id DESC")->fetchAll();
?>
<!doctype html><meta charset="utf-8"><title>A7 Notes (read-only)</title>
<h2>A7 Notes (read-only)</h2>
<p><a href="maintenance_add_note.php">Add a note (protected)</a></p>
<table border="1" cellpadding="6" cellspacing="0">
  <tr><th>ID</th><th>Note</th><th>Created by</th><th>Created at</th><th>Actions</th></tr>
  <?php foreach($rows as $r): ?>
    <tr>
      <td><?= (int)$r['id'] ?></td>
      <td><?= nl2br(h($r['note'])) ?></td>
      <td><?= h($r['created_by']) ?></td>
      <td><?= h($r['created_at']) ?></td>
      <td><a href="maintenance_delete_note.php?id=<?= (int)$r['id'] ?>">Delete</a></td>
    </tr>
  <?php endforeach; ?>
</table>
