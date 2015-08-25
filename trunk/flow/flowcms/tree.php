<?php 
header('Content-Type: text/xml'); 
define('in_flow',true);
@include_once ('../inc/global.inc.php');
init_adodb();
init_smarty();
global $smarty,$cfg, $db;
echo '<?xml version="1.0" encoding="gb2312" ?>';
echo "\n\n"; 
echo '<tree>';
echo "\n";
outputtree($_GET['catalog'],1);
echo '</tree>';
echo "\n";
function whiteblank($num){
	for ($i=0;$i<$num;$i++)
		echo "\t";
}
function outputtree($catalog,$deep){
	global $smarty,$cfg, $db;
	$tbl_columns=$cfg['tbl_columns'];
	$sql = "select id,name,type from $tbl_columns where pcatalog = $catalog";
	$rs= $db->Execute($sql);
	if (!isset($_GET['mode'])){
		$_GET['mode']="list";
	}
	while ($arr = $rs->FetchRow()){
		 whiteblank($deep);
		 if ($_GET['mode']=="pick"){
		 	$action='"javascript:pick('.$arr['id'].','.$arr['type'].')" target="picker" />';
		 }
		 else {
		 	$action='"control.php?act=browse_catalog&amp;catalog_id='.$arr['id'].'" target="mainFrame"/>';
		 }
		 if ($arr['type']==10){
		 	echo '<tree text="'.str_replace('"',"&quot;",$arr['name']).'['.$arr['id'].']'.'" src="tree.php?mode='.$_GET['mode'].'&amp;catalog='.$arr['id'].'" action='.$action;
		 }
		 else {
		 	echo '<tree text="'.str_replace('"',"&quot;",$arr['name']).'['.$arr['id'].']'.'" action='.$action;
		 }
		
		echo "\n";
	}
}
?>