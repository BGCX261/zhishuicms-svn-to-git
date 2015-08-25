<?php
function smarty_function_source_list($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_columns=$cfg['tbl_columns'];
	$tbl_article=$cfg['tbl_article'];	
	$tbl_articlelink=$cfg['tbl_articlelink'];
	if (empty($params['template'])) {
		$template="source_list.html";
	}
	if (empty($params['count'])) {
		$count=5;
	}	
	if (empty($params['date'])) {
		$date=30;
	}	
	if (empty($params['keyword'])) {
		$keyword="ѧԺ";
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
	if (empty($params['with_catalog'])) {
		$with_catalog=0;
	}		
	extract($params);
	$cache_id = $catalog_id.'-'.$count;
	if($smarty->is_cached($template,$cache_id)) {
		$smarty->display($template,$cache_id);
		return;
	}			

	$sql = "SELECT source, count( source ) AS count FROM $tbl_article WHERE STATUS =1 AND dt >= '".date("Y-m-d",time()-$date*24*3600)."' AND source LIKE '%$keyword%' ";

	if (isset ($catalog_id)){
		if (is_array($catalog_id)){
			$catalog_ids=$catalog_id;
		}
		else {
			$catalog_ids=explode(',',$catalog_id);
		}
		$sql = $sql." and (0 ";
		foreach($catalog_ids as $id){
			$id = intval($id);
			$sql= $sql." or $tbl_article.catalog = ".$id;
		}
		$sql = $sql." )";
	}


	$sql = $sql." GROUP BY source ORDER BY count( source ) DESC LIMIT $count ";
	$rs=$db->Execute($sql);
	

	$articleList=array();
	if($rs){
			while ($arr=$rs->FetchRow()){	
				$arr['heading']=mb_substr($arr['heading'], 0, $cut,$cfg['mysql_charset']);	
				array_push($articleList,$arr);
			}
    }
	if (isset($assign)){
		$smarty->assign($assign,$articleList);
	}
	else {
		$smarty->assign("articles",$articleList);
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