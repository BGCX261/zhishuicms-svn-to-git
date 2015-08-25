<?php
function smarty_function_article_list($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_columns=$cfg['tbl_columns'];
	$tbl_article=$cfg['tbl_article'];	
	$tbl_articlelink=$cfg['tbl_articlelink'];
	if (empty($params['template'])) {
		$template="article_list.html";
	}
	if (empty($params['count'])) {
		$count=5;
	}	
	if (!isset($params['page'])) {
		$page=0;
	}	
	if (empty($params['mode'])) {
		$mode="latest";
	}	
	if (empty($params['tcut'])) {
		if (isset($params['cut'])){
			$tcut=$params['cut'];
			$params['tcut'] = $tcut;
		}
	}	
	else {
		$params['cut'] = $params['tcut'];
	}
	if (empty($params['with_catalog'])) {
		$with_catalog=0;
	}		
	if (empty($params['catalog_id'])) {
		print "function article_detail required catalog_id";
		return ;
	}
	extract($params);
	$cache_id = $catalog_id.'-'.$page.'-'.$count.'-'.$mode.'-'.$with_catalog;
	
	if($smarty->is_cached($template,$cache_id)) {
		$smarty->display($template,$cache_id);
		return;
	}			
	$sql="select id,heading,summary,link,dt,author,visitcount,sticky,highlight,catalog,picture_id,heading_color from $tbl_article where  status = 1 and ((0 ";
	
	if ($with_catalog==1){
		$sql="select $tbl_article.id, $tbl_article.heading, $tbl_article.summary, $tbl_article.link, $tbl_article.dt, $tbl_article.author, $tbl_article.visitcount, $tbl_article.sticky, $tbl_article.highlight, $tbl_article.catalog, $tbl_article.picture_id ,$tbl_columns.name as catalog_name from $tbl_article LEFT JOIN ($tbl_columns) ON ($tbl_article.catalog=$tbl_columns.id)	where $tbl_article.status=1 and  (( 0";
	}
	if (is_array($catalog_id)){
		$catalog_ids=$catalog_id;
	}
	else {
		$catalog_ids=explode(',',$catalog_id);
	}
	foreach($catalog_ids as $id){
		$id = intval($id);
		$sql= $sql." or $tbl_article.catalog = ".$id;
	}
	/*
	$sql=$sql.") or ($tbl_article.id in (select article_id from $tbl_articlelink where 0 ";

	foreach($catalog_ids as $id){
		$id = intval($id);
		$sql= $sql." or $tbl_articlelink.catalog_id = ".$id;
	}
	$sql=$sql."))) ";
	*/
	$sql = $sql."))";
	if (isset($picture_only) && $picture_only==1){
		$sql = $sql." and $tbl_article.picture_id > 0 ";
	}
	
	if (isset($period)){
		$sql = $sql." and $tbl_article.dt >= '".date("Y-m-d",time()-intval($period)*24*60*60)."'";
	}
	
	if ($mode=="hot"){
		if (isset($period)){
			$sql = $sql." order by $tbl_article.sticky desc ,$tbl_article.visitcount desc";
		}
		else {
			$sql = $sql." and $tbl_article.dt >= '".date("Y-m-d",time()-30*24*60*60)."'"." order by $tbl_article.sticky desc ,$tbl_article.visitcount desc";
		}
	}
	else if ($mode=="title"){
		$sql = $sql." order by $tbl_article.sticky desc ,$tbl_article.heading";
	}
	else {
		$sql = $sql." order by $tbl_article.sticky desc ,$tbl_article.dt desc";
	}
	
	$rs=$db->Execute($sql);
	
	$articles_count = $rs->RecordCount();
	$articles_pages = intval($articles_count / $count);
	if (($articles_count % $count)!=0){
		$articles_pages++;
	}
	$articleList=array();
	$co = 0;
	$start = intval($page)*intval($count);
	$finish = $start + intval($count); 
	
	if($rs){
			while ($arr=$rs->FetchRow()){	
				if ($co>=$start && $co<$finish){
					$arr['url']=$arr['link'];
					$arr['title']=	$arr['heading'];
					if (!empty($cut)){	
						$length = $cut;
					    if (strlen(trim($arr['heading'])) > $length) {
					        $length -= 3;
	    				    //$arr['heading'] = preg_replace('/\s+?(\S+)?$/', '', mb_substr($arr['heading'], 0, $length+1,$cfg['mysql_charset']));
							$arr['heading']=mb_substr($arr['heading'], 0, $length,$cfg['mysql_charset'])."...";
					    }	
					}
					if (!empty($arr['heading_color'])){
					//	$arr['heading']="<span style=\"color:".$arr['heading_color']."\">".$arr['heading']."</span>";
					}
					array_push($articleList,$arr);
				} 
				else if ($co >= $finish){
					break;
				}
				$co++;
			}
    }

	if (isset($assign)){
		$smarty->assign($assign,$articleList);
	}
	else {
		$smarty->assign("articles",$articleList);
		$smarty->assign("articles_count",$articles_count);
		$smarty->assign("articles_pages",$articles_pages);
		$smarty->assign("articles_catalog",$catalog_id);
		$smarty->assign("articles_page",$page);
		$smarty->assign("cut",$tcut);
		$smarty->assign("tcut",$tcut);
		$smarty->assign("params",$params);
		$smarty->display($template,$cache_id);
  		if ($cfg['debug']){
  			echo "template".$template;
  		}  
	}   
}

?>