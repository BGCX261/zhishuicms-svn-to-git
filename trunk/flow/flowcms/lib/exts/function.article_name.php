<?php
function smarty_function_article_name($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_article=$cfg['tbl_article'];	
	if (empty($params['id'])) {
		print "function article_name required id";
		return ;
	}
	extract($params);
	$sql="select heading from $tbl_article where status=1 and id =".intval($params['id'])." limit 1";
	$article_name = $db->GetOne($sql);
	if (isset($assign)){
		$smarty->assign($assign,$article_name);
	}
	else {
		echo $article_name;
	}

}
?>
