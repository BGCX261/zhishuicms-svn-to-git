<?php
	$tbl_columns=$cfg['tbl_columns'];
	$tbl_article=$cfg['tbl_article'];
	$tbl_adodbseq=$cfg['tbl_adodbseq'];
	$tbl_link=$cfg['tbl_link'];
	if (!isset($_GET['cmd']) ){
		$cmd = "link_edit";
	}
	else {
		$cmd = $_GET['cmd'];
	}
	
	$sql = "select id,name from $tbl_columns where id = ".intval($_GET['catalog_id']);
	$column=$db->GetRow($sql);	

	
	if ($cmd =="link_add"){
		
		if (!isset($_POST['postflag'])){
	
			$sql="select id from $tbl_adodbseq ";
			
			$object_id=$db->GetOne($sql);
			$sql="update $tbl_adodbseq set id = id+1";
			$db->Execute($sql);
			$article['catalog']=intval($_GET['catalog_id']);
			$article['id']=intval($object_id);
			$smarty->assign("data",$article);
			$smarty->assign("catalog",$column);
			$smarty->assign("flow_basedir",$cfg['flow_basedir']);
			$smarty->assign("cmd","link_add");
			$smarty->display("link_edit.html");
		}
		else {
			$sql = "select * from $tbl_link where 0";
			if ($_POST['id']==0){
				$_POST['id']=NULL;
			}
			$rs = $db->Execute($sql);
			$sql =  $db->GetInsertSQL($rs, $_POST);
			if (!$sql){
				alert("�������ʧ�ܣ�");
				exit;			
			}
			if ($db->Execute($sql)){
				alert("������ӳɹ���");
			}
			else {
				alert("�������ʧ�ܣ�");
			}			
			frame_redirection("mainFrame","control.php?act=browse_catalog&catalog_id=".$_GET['catalog_id']);
		}
	}
		
	else if ($cmd =="link_edit"){
		
		$object_id = intval($_GET['article_id']);

		$sql="select * from $tbl_link where id = $object_id";
		if (!$_POST['postflag']){
			$data=$db->GetRow($sql);
			if (!$data){
				db_error("browse_catalog.!data",$sql);
			}			
			
			$article['catalog']=intval($_GET['catalog_id']);
			$smarty->assign("data",$data);
			$smarty->assign("catalog",$catalog_id);
			$smarty->assign("cmd","link_edit");
			$smarty->assign("flow_basedir",$cfg['flow_basedir']);
			$smarty->assign("act","link_edit");
			$smarty->display("link_edit.html");
		}
		else {
			$sql = "select * from $tbl_link where id = ".intval($_POST['id']);
			$rs = $db->Execute($sql);
			$sql = $db->GetUpdateSQL($rs, $_POST);
			if (!$sql){
				alert("û���κθĶ�");
				frame_redirection("mainFrame","control.php?act=browse_catalog&catalog_id=".$_GET['catalog_id']);
				exit;		
			}
			if ($db->Execute($sql)){
				alert("���Ӹ��³ɹ���");
			}
			else {
				alert("���Ӹ���ʧ�ܣ�");
			}
			frame_redirection("mainFrame","control.php?act=browse_catalog&catalog_id=".$_GET['catalog_id']);
		}
	}
	
	
	else if ($cmd=="publish"){
		article_operation("status = 1","���·����ɹ�","���·���ʧ��");	
	}
	
	
	else if ($cmd=="unpublish"){
		article_operation("status = 0","����ȡ�������ɹ�","����ȡ������ʧ��");	
	}
	
	
	else if ($cmd=="delete"){
		article_operation("delete from $tbl_link ","����ɾ���ɹ�","����ɾ��ʧ��",1);	
	}
	
	

	
	else if ($cmd=="move"){
		article_operation("catalog = ".$_GET['target_catalog'],"�����ƶ��ɹ�","�����ƶ�ʧ��");	
	}
	
	
	else if ($cmd=="copy"){
		$cause=" ( 0";
		$article_ids=explode(',',$_GET['article_id']);
		foreach($article_ids as $id){
			$id = intval($id);
			$cause= $cause." or id = ".$id;
		}
		$cause=$cause.")";			
		
		$sql = "select * from $tbl_link where $cause";
		$rs = $db->Execute($sql);
		if (!$rs){
			alert("���Ӹ���ʧ��");
			reload_frame('mainFrame');
			exit;			
		}
		while ($arr = $rs->FetchRow()){
			unset($arr['id']);
			$arr['catalog'] = $_GET['target_catalog'];
			$sql =  $db->GetInsertSQL($rs, $arr);
			if (!$db->Execute($sql)){
				alert("����".$arr['id']." ".$arr['name']."����ʧ��");
			}
		}
		alert("���Ӹ������");
		reload_frame('mainFrame');
	}
	
		
	else{
		alert ("δ���������");
		exit;
	}
	
	function article_operation($sql,$s_msg="�����ɹ�",$f_msg="����ʧ��",$type=0){
		global $db;
		global $cfg;
		$tbl_link=$cfg['tbl_link'];
		$cause=" ( 0";
		$article_ids=explode(',',$_GET['article_id']);
		foreach($article_ids as $id){
			$id = intval($id);
			$cause= $cause." or id = ".$id;
		}
		$cause=$cause.")";			
		if (!$type){
			$sql = "update $tbl_link set ".$sql." where $cause";
		}
		else {
			$sql = $sql." where $cause";
		}
		if ($db->Execute($sql)){
			alert($s_msg);
		}
		else {
			alert($f_msg);
		}		
		reload_frame('mainFrame');				
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
?>