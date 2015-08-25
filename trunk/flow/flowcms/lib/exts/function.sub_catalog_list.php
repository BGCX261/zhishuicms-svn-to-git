<?php
function smarty_function_sub_catalog_list($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_columns=$cfg['tbl_columns'];
	$tbl_article=$cfg['tbl_article'];	
	if (empty($params['template'])) {
		$template="sub_catalog_list.html";
	}

	if (!isset($params['catalog_id'])) {
		print "function sub_catalog_list required catalog_id";
		return ;
	}
	extract($params);
	$catalog_id=intval($catalog_id);
	
	$cache_id = $catalog_id.'-'.$page.'-'.$count.'-'.$mode.'-'.$with_catalog;
	
	if($smarty->is_cached($template,$cache_id)) {
		$smarty->display($template,$cache_id);
		return;
	}	
	
	
	$sql="select id,name,type,show_position,show_in_guide,url from $tbl_columns where pcatalog = $catalog_id and show_in_guide = 1";
	
	if (isset($type)){
		$sql = $sql." and type = ".intval($type);
	}
	if (isset($show_position)){
		$sql = $sql." and show_position = ".intval($show_position);
	}
	if (isset($type)){
		$sql = $sql." and show_in_guide = ".intval($show_in_guide);
	}
	$rs=$db->Execute($sql);
	$columns = array();
	while ($arr=$rs->FetchRow()){
		array_push($columns,$arr);
	}

	if (isset($assign)){
		$smarty->assign($assign,$columns);		
	}
	else {
		$smarty->assign("columns",$columns);
		$smarty->assign("params",$params);
		$smarty->display($template,$cache_id);
	}
}

?>