<?php
function smarty_function_link_list ($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_link=$cfg['tbl_link'];	
	if (empty($params['template'])) {
		$template="link_list.html";
	}
	if (empty($params['count'])) {
		$count=5;
	}	
	if (empty($params['catalog_id'])) {
		print "function article_detail required catalog_id";
		return ;
	}
	extract($params);
	if($smarty->is_cached($template,$catalog_id)) {
		$smarty->display($template,$catalog_id);
		return;
	}
	$sql = "select name , url , descri ,logo ,sort_order from $tbl_link where catalog = $catalog_id order by sort_order desc limit $count";
	$rs=$db->Execute($sql);
	$links=array();
	while ($arr=$rs->FetchRow()){
		array_push($links,$arr);
	}
	$rs->close();
	$smarty->assign("links",$links);
	$smarty->display($template,$catalog_id);
	
}
?>