<?php
function smarty_function_subject_list($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_subject=$cfg['tbl_subject'];
	if (empty($params['template'])) {
		$template="subject_list.html";
	}
	if (empty($params['mode'])) {
		$mode="latest";
	}
	
	
	if (!isset($params['catalog_id'])) {
		print "function subject_list required catalog_id";
		return ;
	}
	extract($params);
	if ($mode =="latest"){
		$order="dt";
	}
	else {
		$order = "visit_count";
	}
	
	$catalog_id=intval($catalog_id);
	
	$sql="select id,title,visit_count,intro,dt,owner,url,highlight,picture_id,sticky from $tbl_subject where pcatalog = $catalog_id order by $order desc";
	
	$rs=$db->Execute($sql);
	$columns = array();
	while ($arr=$rs->FetchRow()){
		array_push($columns,$arr);
	}

	if (isset($assign)){
		$smarty->assign($assign,$columns);		
	}
	else {
		$smarty->assign("subjects",$columns);
		$smarty->assign("params",$params);
		$smarty->display($template);
	}
}

?>