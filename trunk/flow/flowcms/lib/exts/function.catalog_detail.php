<?php
function smarty_function_catalog_detail($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_columns=$cfg['tbl_columns'];
	if (empty($params['template'])) {
		$template="catalog_detail.html";
	}

	if (empty($params['catalog_id'])) {
		print "function article_detail required catalog_id";
		return ;
	}
	
	extract($params);

	if(!isset($assign) && $smarty->is_cached($template,$catalog_id)) {
		$smarty->display($template,$catalog_id);
		return;
	}		
	
	$sql="select id,name,type,show_position,show_in_guide,url from $tbl_columns where id =".intval($catalog_id)." limit 1";
	$catalog = $db->GetRow($sql);
	if (!$catalog){
		db_error(); 
	}

	if (isset($assign)){
		$smarty->assign($assign,$catalog);
	}
	else {
		$smarty->assign("catalog",$catalog);
		$smarty->display($template,$catalog_id);
		$smarty->clear_assign("catalog");
	}

}
?>