<?php
define('in_flow',true);
@include_once ('../inc/global.inc.php');
include ('lib/charset.php');
init_adodb();
init_smarty();
global $smarty,$cfg, $db;
$tbl_article=$cfg['tbl_article'];
if (!isset($_POST['postflag'])){
	$sql = "select id,heading,score,editor from $tbl_article where id = ".intval($_GET['article_id']);
	$arr = $db->GetRow($sql);
	$arr ['heading'] = Charset::convert($arr ['heading'],"gbk","utf8");
	$arr ['editor'] = Charset::convert($arr ['editor'],"gbk","utf8");
	$smarty->assign("article",$arr);			
	$smarty->display("givescore.html");
}
else {
	$privileges_check="givescore";
	$sql = "select catalog from $tbl_article where id = ".intval($_POST['article_id']);
	$catalog_id = $db->GetOne($sql);
	if (!checkpower($catalog_id,GetUid(),3)){
		echo intval($_POST['fscore']);
		exit;
	}
	else {
		$sql = "update $tbl_article set score = ".intval($_POST['score'])." where id = ".intval($_POST['article_id'])." limit 1";
		if ($db->Execute($sql)){
			echo intval($_POST['score']);
		}
		else {
			echo intval($_POST['fscore']);
		}
	}
}




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







?>