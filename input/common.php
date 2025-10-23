<?php
function h($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function flash($msg,$ok=true){$c=$ok?'#e7f7ed':'#fde2e1';$b=$ok?'#2e7d32':'#c62828';echo "<div style='margin:12px 0;padding:10px;border-left:4px solid $b;background:$c'>".h($msg)."</div>";}
function back_link(){echo "<p><a href='/input/maintenance.php'>&larr; Back to Maintenance</a></p>";}
function select_opts($rows,$valKey,$labelKey){
  foreach($rows as $r){$v=h($r[$valKey]);$l=h($r[$labelKey]);echo "<option value='$v'>$l</option>";}
}
