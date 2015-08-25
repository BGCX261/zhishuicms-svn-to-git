<?php
function smarty_function_customize_page ($params, &$smarty1){
	global $db;
	global $smarty;
	global $cfg;
	$tbl_customise=$cfg['tbl_customise'];
	
	if (empty($params['template'])) {
		$template="customize_page.html";
	}
	if (empty($params['short_mode'])) {
		$short_mode=0;
	}	
	if (empty($params['catalog_id'])) {
		print "function article_detail required catalog_id";
		return ;
	}	
	extract($params);
	if($smarty->is_cached($template,$catalog_id)) {
		$smarty->display($template,$catalog_id);
		return;
	}	
	if ($short_mode==1){
		$col="summary";
	}
	else {
		$col="content";
	}
	$sql = "select $col as content from $tbl_customise where col = $catalog_id order by dt desc limit 1";
	$content = $db->GetRow($sql);
	$smarty->assign("content",$content);
	$smarty->display($template,$catalog_id);
}
?>