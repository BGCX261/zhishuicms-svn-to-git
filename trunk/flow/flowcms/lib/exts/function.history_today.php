<?php
function smarty_function_history_today($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_history = $cfg['tbl_history'];
	if (empty($params['count'])) {
		$count=5;
	}	
	if (empty($params['tcut'])) {
		if (isset($params['cut'])){
			$tcut=$params['cut'];
		}
		else {
			$tcut=500;
		}
		$params['tcut'] = $tcut;
	}	
	$params['cut'] = $params['tcut'];
	
	extract($params);
	$month=date('m');
	$day=date("d");
	$sql="select id,title,content  from $tbl_history where  month=$month and day=$day limit $count";
	
	$rs=$db->Execute($sql);
	$articleList=array();
	if($rs){
			while ($arr=$rs->FetchRow()){	
				array_push($articleList,$arr);
			}
    }

	if (isset($assign)){
		$smarty->assign($assign,$articleList);
	}
	else {
		$smarty->assign("distory_todays",$articleList);
		$smarty->assign("cut",$tcut);
		$smarty->assign("tcut",$tcut);
		$smarty->assign("params",$params);
		$smarty->display($template,$catalog_id);
	}   
}
	
?>