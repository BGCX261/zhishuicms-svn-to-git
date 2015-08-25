<?php
	$tbl_columns=$cfg['tbl_columns'];
	$tbl_article=$cfg['tbl_article'];
	$tbl_adodbseq=$cfg['tbl_adodbseq'];
	$tbl_articlelink=$cfg['tbl_articlelink'];
	$tbl_keywords=$cfg['tbl_keywords'];
	$tbl_object_keywords=$cfg['tbl_object_keywords'];
	if (!isset($_GET['cmd']) ){
		$cmd = "article_edit";
	}
	else {
		$cmd = $_GET['cmd'];
	}
	
	$sql = "select id,name,video from $tbl_columns where id = ".intval($_GET['catalog_id']);
	$column=$db->GetRow($sql);	

	
	if ($cmd =="article_add"){
		if (isset($_POST['step'])){
			$step=$_POST['step'];
		}
		else {
			$step="1";
		}
		if ($step=="1"){
	
			$object_id=GetSeq();
			$article['catalog']=intval($_GET['catalog_id']);
			$article['id']=intval($object_id);
			$article['editor']=GetUserName();
			$smarty->assign("data",$article);
			$smarty->assign("catalog",$column);
			$smarty->assign("flow_basedir",$cfg['flow_basedir']);
			$smarty->assign("cmd","article_add");
			$smarty->display("article_edit.html");
		}
		else if ($step=="2"){
			if (!isset($_POST['heading']) || strlen(trim($_POST['heading']))==0){
				alert("���������±���");
				exit;
			}
			$sql = "select * from $tbl_article where 0";
			if ($_POST['id']==0){
				$_POST['id']=NULL;
			}
			$_POST['ownerid']=GetUid();
			$_POST['dt']=$_POST['date'];
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
				exit;
			}			
			frame_redirection("mainFrame","control.php?act=browse_catalog&catalog_id=".$_GET['catalog_id']);
		}
	}
		
	else if ($cmd =="article_edit"){

		$object_id = intval($_GET['article_id']);
		if (isset($_POST['step'])){
			$step=$_POST['step'];
		}
		else {
			$step="1";
		}
		if ($step=="1"){
			$sql = "select * from $tbl_article where id = ".intval($_GET['article_id']);
			$article=$db->GetRow($sql);
			$sql = "select distinct $tbl_keywords.keyword from $tbl_keywords,$tbl_object_keywords where $tbl_object_keywords.keyword_id = $tbl_keywords.keyword_id and $tbl_object_keywords.object_id = ".intval($_GET['article_id']);
			$rs = $db->Execute($sql);
			$key="";
			$arr=$rs->FetchRow();
			if ($arr){
				$key=$arr['keyword'];
			}
			while ($arr=$rs->FetchRow()){
				$key=$key.'|'.$arr['keyword'];
			}
			$article['catalog']=intval($_GET['catalog_id']);
			$smarty->assign("keywords",$key);
			$smarty->assign("data",$article);
			$smarty->assign("catalog",$column);
			$smarty->assign("cmd","article_edit");
			$smarty->assign("flow_basedir",$cfg['flow_basedir']);
			$smarty->assign("act","article_edit");
			$smarty->display("article_edit.html");
		}
		else if ($step=="2"){
			$sql = "select * from $tbl_article where id = ".intval($_POST['id']);
			$rs = $db->Execute($sql);
			$_POST['dt']=$_POST['date'];
			if (! isset($_POST['highlight'])){
				$_POST['highlight']="0";
			}
			if (! isset($_POST['sticky'])){
				$_POST['sticky']="0";
			}			
			$sql1 = $db->GetUpdateSQL($rs, $_POST);
			
			$db->BeginTrans();
			if (strlen(trim($_POST['keywords'])) != 0){
				
				$keys=explode('|',$_POST['keywords']);
				$key_id=array();
				$sql = "delete from $tbl_object_keywords where object_id = ".intval($_POST['id']);
				if (!$db->Execute($sql)){
					alert("�ؼ��ָ���ʧ��2");
					$db->RollbackTrans();
					exit;
				}				
				foreach($keys as $key){
					if (strlen(trim($key))){
						$sql = "select keyword_id from $tbl_keywords where keyword = '".$key."'";
						$id=$db->GetOne($sql);
						if (! $id){
							$id=GetSeq();
							$sql = "insert into $tbl_keywords (keyword_id,keyword) values ($id,'$key')";
							if (!$db->Execute($sql)){
								alert("�ؼ��ָ���ʧ��1");
								$db->RollbackTrans();
								exit;
							}
						}
						array_push($key_id,$id);
					}
				}
	
				$stmt = $db->Prepare("insert into $tbl_object_keywords (class_id,object_id,keyword_id) values (1,".intval($_POST['id']).",?)");
				foreach($key_id as $keyid){
					if(!$db->Execute($stmt,$keyid)){
						alert("�ؼ��ָ���ʧ��3");
						$db->RollbackTrans();
						exit;
					}
				}
				//$db->CommitTrans();	
			}
			if (!$sql1){
				$db->CommitTrans();	
				frame_redirection("mainFrame","control.php?act=browse_catalog&catalog_id=".$_GET['catalog_id']);
				exit;		
			}			
			else if ($db->Execute($sql1)){
				$db->CommitTrans();	
				alert("���¸��³ɹ���");
				
				
			}
			else {
				$db->RollbackTrans();
				alert("���¸���ʧ�ܣ�");
				exit;
			}
			frame_redirection("mainFrame","control.php?act=browse_catalog&catalog_id=".$_GET['catalog_id']);
		}
	}
	
	
	else if ($cmd=="publish"){
		$db->BeginTrans();
		article_operation("status = 1","���·����ɹ�","���·���ʧ��");	
	}
	
	
	else if ($cmd=="unpublish"){
		$db->BeginTrans();
		article_operation("status = 0","����ȡ�������ɹ�","����ȡ������ʧ��");	
	}
	
	
	else if ($cmd=="delete"){
		$db->BeginTrans();
		$cause=" ( 0";
		$article_ids=explode(',',$_GET['article_id']);
		foreach($article_ids as $id){
			$id = intval($id);
			$cause= $cause." or article_id = ".$id;
		}
		$cause=$cause.")";			
		$sql = "delete from $tbl_articlelink where $cause";

		if (!$db->Execute($sql)){
			alert("����ɾ��ʧ��");
			$db->RollbackTrans();
		}
		article_operation("delete from $tbl_article ","����ɾ���ɹ�","����ɾ��ʧ��",1);
		reload_frame('mainFrame');			
		
		
		
		
		
		
		
		
		
		
		
	}
	
	
	else if ($cmd=="link"){
		
		$cause=" ( 0";
		$article_ids=explode(',',$_GET['article_id']);
		foreach($article_ids as $id){
			$id = intval($id);
			$cause= $cause." or id = ".$id;
		}
		$cause=$cause.")";			
	
		$sql = "select * from $tbl_article where $cause";
		$rs = $db->Execute($sql);
		
		if (!$rs){
			alert("���·���ʧ��");
			reload_frame('mainFrame');
			exit;			
		}
		$stmt = $db->Prepare("insert into $tbl_articlelink (article_id,catalog_id) values ( ? , ? )");
		while ($arr = $rs->FetchRow()){
			if (!$db->Execute($stmt, array($arr['id'],$_GET['target_catalog']))){
				alert("����".$arr['id']." ".$arr['heading']."ͬʱ����ʧ��");
			}
			else{
				alert("����".$arr['id']." ".$arr['heading']."ͬʱ�����ɹ�");
			}
		}
		alert("���·������");
		reload_frame('mainFrame');
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
		
		$sql = "select * from $tbl_article where $cause";
		$rs = $db->Execute($sql);
		
		if (!$rs){
			alert("���¸���ʧ��");
			reload_frame('mainFrame');
			exit;			
		}
		while ($arr = $rs->FetchRow()){
			$arr['id'] = NULL;
			$arr['catalog'] = $_GET['target_catalog'];
			$sql =  $db->GetInsertSQL($rs, $arr);
			if (!$db->Execute($sql)){
				alert("����".$arr['id']." ".$arr['heading']."����ʧ��");
			}
		}
		reload_frame('mainFrame');
	}
	
	
	else if ($cmd=="sticky"){
		article_operation("sticky = 1","�����ö��ɹ�","�����ö�ʧ��");
	}
	
	
	else if ($cmd=="unsticky"){
		article_operation("sticky = 0","����ȡ���ö��ɹ�","����ȡ���ö�ʧ��");
	}
	
	
	else if ($cmd=="highlight"){
		article_operation("highlight = 1","�������ø����ɹ�","�������ø���ʧ��");
	}
	
	
	else if ($cmd=="unhighlight"){
		article_operation("highlight = 0","����ȡ�������ɹ�","����ȡ������ʧ��");
	}
	
	
	else if ($cmd=="hide"){
		article_operation("status = 2","�������سɹ�","��������ʧ��");
	}
	
	
	else if ($cmd=="unhide"){	
		article_operation("status = 0","����ȡ�����سɹ�","����ȡ������ʧ��");
	}
	
	else{
		alert ("δ���������");
		exit;
	}
	
	function article_operation($sql,$s_msg="�����ɹ�",$f_msg="����ʧ��",$type=0){
		global $db;
		global $cfg;
		$tbl_columns=$cfg['tbl_columns'];
		$tbl_article=$cfg['tbl_article'];
		$cause=" ( 0";
		$article_ids=explode(',',$_GET['article_id']);
		foreach($article_ids as $id){
			$id = intval($id);
			$cause= $cause." or id = ".$id;
		}
		$cause=$cause.")";			
		if (!$type){
			$sql = "update $tbl_article set ".$sql." where $cause";
		}
		else {
			$sql = $sql." where $cause";
		}
		if ($db->Execute($sql)){
			$db->CommitTrans();	
			alert($s_msg);
		}
		else {			
			$db->RollbackTrans();
			alert($f_msg);
		}		
		reload_frame('mainFrame');				
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
?>