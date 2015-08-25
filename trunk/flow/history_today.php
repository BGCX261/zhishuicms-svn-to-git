<?php
@include_once ('inc/global.inc.php');
init_adodb();
init_smarty();
global $smarty;
global $cfg;
$tbl_history = $cfg['tbl_history'];
$sql="select id,title,content,year,month,day from $tbl_history where id = ".intval($_GET['id']);
$event=$db->GetRow($sql);
$smarty->assign("history",$event);
$smarty->display("history_today.html")



?>