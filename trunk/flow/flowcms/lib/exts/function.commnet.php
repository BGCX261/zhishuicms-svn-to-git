<?php
function smarty_function_commnet($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_commnet=$cfg['tbl_commnet'];
	$tbl_article=$cfg['tbl_article'];
	$tbl_columns=$cfg['tbl_columns'];
	if (empty($params["article_id"])){
		return;
	}
	$object_id=intval($params["article_id"]);
	if (!allow_comment($object_id)){
		return ;
	}
	

}
?>