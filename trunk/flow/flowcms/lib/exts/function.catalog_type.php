<?php
function smarty_function_catalog_type ($params, &$smarty1){
	global $db;
	global $smarty;
	global $cfg;
	if (empty($params['catalog_id'])) {
		print "function article_detail required catalog_id";
		return ;
	}
	extract($params);
	$tbl_columns=$cfg['tbl_columns'];
	$sql = "select type from $tbl_columns where id = $catalog_id";
	$type = $db->Getone($sql);
	if (empty($assign)){
		print $type;
	}
	else {
		$smarty->assign($assign,$type);
	}
	
}
?>