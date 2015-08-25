<?php

function GetFatherCatalog($id){
	global $cfg;
	global $db;
	
	$tbl_columns=$cfg['tbl_columns'];
	
	$stmt = $db->Prepare("select pcatalog from $tbl_columns where id = ?");
	$catalogs= array();
	array_push($catalogs,$id);
	while ($id>0){
		$rs=$db->Execute($stmt,$id);
		$arr=$rs->FetchRow();
		$id = $arr['pcatalog'];
		array_push($catalogs,$id);
	}
	return $catalogs;
}

function checkpower($catalog_id=0,$uid=0,$checkpoint=0){
	global $cfg;
	global $db;
	$tbl_privilege=$cfg['tbl_privilege'];
	$tbl_user=$cfg['tbl_user'];
	$sql = "select user_type from $tbl_user where id = $uid";
	$user_type=$db->GetOne($sql);
	if ($user_type ==1){
		return true;
	}
	
	$catalogs=GetFatherCatalog($catalog_id);
	$groups = GetGroups($uid);
	$stmt = $db->Prepare("select privilege from $tbl_privilege where group_id  = ? and catalog_id = ?");
	$pass=false;
	foreach($catalogs as $catalog){
		foreach($groups as $group){
			$rs=$db->Execute($stmt,array($group,$catalog));
			unset($arr);
			$arr=$rs->FetchRow();
			if ($arr && ($catalog_id == -1 || $arr['privilege']>=$checkpoint)){
				$pass=true;
				return true;			
			}
		}
	}
	return false;	
	
}


	switch ($_GET['act']){
		case "browse_catalog":
			if (!checkpower(intval($_GET['catalog_id']),GetUid(),1)){
				alert("没有权限进行此操作");
				exit;
			}
			break;
	
		case "article_edit":
			if (!isset($_GET['cmd']) ){
				$cmd = "article_edit";
			}
			else {
				$cmd = $_GET['cmd'];
			}
			$catalog_id=intval($_GET['catalog_id']);
			if ($cmd =="article_add" || $cmd =="article_edit"){
				if (!checkpower($catalog_id,GetUid(),1)){
					alert("没有权限进行此操作");
					exit;
				}
			}
			else if ($cmd=="link" ){
				if (!checkpower($catalog_id,GetUid(),1)){
					alert("没有权限进行此操作");
					exit;
				}	
				if (!checkpower($catalog_id,GetUid(),2)){
					alert("没有权限对目标栏目进行操作，本操作要求具有目标栏目的编辑权限");
					exit;
				}								
			}
			else if ($cmd=="move" || $cmd=="copy"){
				if (!checkpower($catalog_id,GetUid(),1)){
					alert("没有权限进行此操作");
					exit;
				}
				if (!checkpower(intval($_GET['target_catalog']),GetUid(),2)){
					alert("没有权限对目标栏目进行操作，本操作要求具有目标栏目的编辑权限");
					exit;
				}						
			}
			else {
			if (!checkpower($catalog_id,GetUid(),2)){
					alert("没有权限进行此操作...".intval($_GET['catalog_id']));
					exit;
				}			
			}
		
			break;		
			
		case "catalog_list":
			break;
	
		case "catalog_edit":
			if (isset($_GET['cmd']) ){
				$cmd = $_GET['cmd'];
			}
			else  if (isset($_POST['cmd']) ){
				$cmd = $_POST['cmd'];
			}
			else {
				$cmd = "catalog_edit";
			}			
			if (!checkpower(intval($_GET['catalog_id']),GetUid(),3)){
				alert("没有权限进行此操作");
				exit;
			}				
			break;		
			
		case "user_manage":
			if (isset($_GET['cmd']) ){
				$cmd = $_GET['cmd'];
			}
			else if (isset($_POST['cmd']) ){
				$cmd = $_POST['cmd'];
			}
			else {
				$cmd = "list";
			}
			
			if (!($cmd == "edit" && !isset($_GET['uid']) && !isset($_POST['id']))){
				if (!checkpower(-1,GetUid(),8)){
					alert("没有权限进行此操作");
					exit;
				}
			}
			break;			
			
		case "group_manage":
			if (!checkpower(-1,GetUid(),6)){
					alert("没有权限进行此操作");
					exit;
			}
			break;			
	
		case "privilege_manage":
			if (!checkpower(-1,GetUid(),12)){
					alert("没有权限进行此操作");
					exit;
			}
			break;					
	}



?>