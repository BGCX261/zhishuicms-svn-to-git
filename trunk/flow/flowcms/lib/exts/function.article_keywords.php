<?php
function smarty_function_article_keywords($params, &$smarty1){
	global $db;
	global $cfg;
	global $smarty;
	$tbl_keywords=$cfg['tbl_keywords'];
	$tbl_object_keywords=$cfg['tbl_object_keywords'];
	if (empty($params['article_id'])) {
		return ;
	}	
	else {
		$article_id=$params['article_id'];
	}
	$sql = "select keyword from $tbl_keywords where keyword_id =
		(select keyword_id from $tbl_object_keywords where object_id=$article_id order by id desc limit 1)";
	$keyword= $db->GetOne($sql);
	$smarty->assign($params['assign'],$keyword);
}
?>