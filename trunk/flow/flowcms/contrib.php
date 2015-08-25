<?php
	if (!isset($_GET['cmd']) || (!isset($_GET['catalog_id']) && !isset($_POST['catalog_id']))){
		echo "参数错误";
		exit;
	}
	define('in_flow',true);
	@include_once ('../inc/global.inc.php');
	init_adodb();
	init_smarty();
	global $smarty,$cfg, $db;
	$tbl_columns = $cfg['tbl_columns'];
	$tbl_article=$cfg['tbl_article'];
	$tbl_adodbseq=$cfg['tbl_adodbseq'];
	$tbl_articlelink=$cfg['tbl_articlelink'];
	$tbl_keywords=$cfg['tbl_keywords'];
	$tbl_object_keywords=$cfg['tbl_object_keywords'];	
	if (isset($_GET['catalog_id'])){
		$catalog=intval($_GET['catalog_id']);
	}
	else {
		$catalog=intval($_POST['catalog_id']);
	}
	$sql="select id,name,contrib from $tbl_columns where id = $catalog";
	$column=$db->GetRow($sql);
	if (! $column || !$column['contrib']){
		alert("当前栏目不允许投稿");
		exit;
	}
	if (!isset($_POST['postflag'])){
			$object_id=GetSeq();
			$article['catalog']=$catalog;
			$article['id']=intval($object_id);
			$smarty->assign("data",$article);
			$smarty->assign("catalog",$column);
			$smarty->assign("flow_basedir",$cfg['flow_basedir']);
			$smarty->display("contribute_edit.html");
	}
	else {
			if (!isset($_POST['heading']) || strlen(trim($_POST['heading']))==0){
				alert("必须有文章标题");
				exit;
			}
			$sql = "select * from $tbl_article where 0";
			$rs = $db->Execute($sql);	
			$_POST['dt']=date("Y-m-d");		
			$_POST['catalog']=$catalog;
			if ($_POST['id']==0){
				$_POST['id']=NULL;
			}
			
			else {
				$sql = "select id from $tbl_article where id = ".intval($_POST['id']);
				if ($db->GetOne($sql)){
					$sql =  $db->GetUpdateSQL($rs, $_POST);
				}
				else {
					$sql =  $db->GetInsertSQL($rs, $_POST);
				}
					
			}
			
			
			if (!$sql){
				alert("投稿失败！");
				exit;			
			}
			if ($db->Execute($sql)){
				alert("投稿成功！本站编辑将尽快审核。");
			}
			else {
				alert("投稿失败！");
			}					
	}
	

?>