<?php
function smarty_function_current_position ($params, &$smarty1){
	global $db;
	global $smarty;
	global $cfg;
	$tbl_columns=$cfg['tbl_columns'];
	if (empty($params['root_id'])) {
		$root_id=0;
	}
	if (empty($params['delim'])) {
		$delim="&gt;";
	}	
	if (isset($params['url']) && !isset($params['uri'])){
		$params['uri']=$params['url'];
	}
	if (empty($params['uri'])) {
		$uri="redir.php?catalog_id=";
	}		
	if (!isset($params['catalog_id'])) {
		print "function current_position required catalog_id";
		return ;
	}
	extract($params);
	$root_id=intval($root_id);
	$catalog=array();
	$catalogs=array();
	$catalog['pcatalog']=intval($catalog_id);
	$stmt = $db->Prepare("select id,name,pcatalog from $tbl_columns where id = ? limit 1");
	
	while ($catalog['pcatalog']!=0){
		$rs=$db->Execute($stmt,$catalog['pcatalog']);
		if (!$rs){
			break;
		}
		$catalog=$rs->FetchRow();
		array_push($catalogs,$catalog);
		if ($catalog['id']==$root_id){
			break;
		}
	}	
	
	while ($catalog = array_pop($catalogs)){
		
		print ("<a href = $uri".$catalog['id']."> ".$catalog['name']."</a>");
		if ($catalogs){
			print "&nbsp;".$delim."&nbsp;";
		}
	}
	
	
}
?>