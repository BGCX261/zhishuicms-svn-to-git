<?php
function smarty_function_catalog_name ($params, &$smarty1){
	global $db;
	global $smarty;
	global $cfg;
	if (empty($params['catalog_id'])) {
		print "function article_detail required catalog_id";
		return ;
	}
	extract($params);
	$tbl_columns=$cfg['tbl_columns'];
	$sql = "select name from $tbl_columns where id = $catalog_id";
	$name = $db->Getone($sql);
	if (empty($assign)){
		print $name;
	}
	else {
		$smarty->assign($assign,$name);
	}
	
}
?>