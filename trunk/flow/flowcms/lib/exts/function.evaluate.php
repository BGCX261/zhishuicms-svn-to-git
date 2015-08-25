<?php
function smarty_function_evaluate($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_article=$cfg['tbl_article'];	
	
	if (empty($params['cmd'])) {
		print "function evaluate need cmd";
		return ;
	}
	if (empty($params['id'])) {
		print "function evaluate required id";
		return ;
	}
	
	
	extract($params);
	
	switch ($cmd){
		case "show_approve":
			$sql = "select approve from $tbl_article where id = $id";
			$approve=$db->GetOne($sql);
			echo $approve;
			break;
		case "show_disagree":
			$sql = "select disagree from $tbl_article where id = $id";
			$disagree=$db->GetOne($sql);
			echo $disagree;			
			break;
	}

}
?>