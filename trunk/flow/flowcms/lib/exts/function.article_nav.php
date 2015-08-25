<?php
function smarty_function_article_nav($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_article=$cfg['tbl_article'];
	if (empty($params['template'])) {
		$template="article_nav.html";
	}
	if (empty($params['direction'])) {
		$direction="new";
	}
	if (empty($params['mode'])) {
		$mode="summary";
	}
	if (empty($params['id'])) {
		print "function article_detail required id";
		return ;
	}
	extract($params);
	$id = intval($id);
	
	
	if($smarty->is_cached($template,$id)) {
		$smarty->display($template,$id);
		return;
	}			
	
	
	$sql = "select catalog,dt from $tbl_article where id = $id";
	$arr = $db->GetRow($sql);
	$catalog_id = $arr['catalog'];
	$nowdata=$arr['dt'];
	
	if ($mode=="summary"){
		$content = "id,heading,dt,author,visitcount,sticky,highlight,catalog";	
	}
	else {
		$content = "*";
	}
		
	if ($direction=="new" || $direction="both"){
		$sql = "select $content from $tbl_article where catalog = $catalog_id and status=1 and dt > '$nowdata' order by dt limit 1";
		$article_new = $db->GetRow($sql);
	}
	if ($direction=="old" || $direction="both"){
		$sql = "select $content from $tbl_article where catalog = $catalog_id and  status=1 and dt < '$nowdata' order by dt desc limit 1";
		$article_old = $db->GetRow($sql);
	}
	
	$smarty->assign("article_new",$article_new);
	$smarty->assign("article_old",$article_old);
	$smarty->display($template,$id);
	$smarty->clear_assign("article_new");
	$smarty->clear_assign("article_old");

}
?>