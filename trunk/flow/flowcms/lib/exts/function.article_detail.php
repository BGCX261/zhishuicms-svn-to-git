<?php
function smarty_function_article_detail($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_article=$cfg['tbl_article'];	
	if (empty($params['template'])) {
		$template="article_detail.html";
	}
	if (empty($params['page'])) {
		$page=0;
	}	
	if (empty($params['wordsperpage'])) {
		$wordsperpage=0;
	}	
	if (empty($params['id'])) {
		print "function article_detail required id";
		return ;
	}
	extract($params);
	
	if((!isset($assign)) && $smarty->is_cached($template,$id)) {
		
		$smarty->display($template,$id);
		return;
	}
	
	$sql="select id,heading,summary,link,dt,author,visitcount,sticky,highlight,catalog,picture_id,content,page_count from $tbl_article where status=1 and id =".intval($params['id'])." limit 1";
	$article = $db->GetRow($sql);
	if (!$article){
		 header("HTTP/1.0 404 Not Found");
		 exit;
	}
	
	
	if (intval($wordsperpage) > 0){
		$length=strlen(trim($article['content']));
		$pages_count = intval ( $length / $wordsperpage);
		if (($length % $wordsperpage)!=0){
			$pages_count++;
		}
		
		
		$article['content']=mb_substr($article['content'],intval($page)*intval($wordsperpage),intval($wordsperpage),$cfg['mysql_charset']);
	}
	
	if (isset($assign)){
		$smarty->assign($assign,$article);
	}
	else {
		$smarty->assign("params",$params);
		$smarty->assign("page",$page);
		$smarty->assign("article",$article);
		$smarty->assign("pages_count",$pages_count);
		$smarty->display($template,$id);
 		if ($cfg['debug']){
  			echo "template:".$template;
  		}  		
		$smarty->clear_assign("article");
	}

}
?>