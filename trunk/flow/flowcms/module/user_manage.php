<?php
$tbl_user=$cfg['tbl_user'];
$tbl_adodbseq=$cfg['tbl_adodbseq'];
if (isset($_GET['cmd']) ){
	$cmd = $_GET['cmd'];
}
else if (isset($_POST['cmd']) ){
	$cmd = $_POST['cmd'];
}
else {
	$cmd = "list";
}

if ($cmd=="list"){	

	if (isset($_POST['keywords'])){
		$sql = "select id,username,name,branch,duty from $tbl_user where  (`duty` LIKE '%".$_POST['keywords']."%' or `branch` LIKE '%".$_POST['keywords']."%' or `username` LIKE '%".$_POST['keywords']."%' or `name` LIKE '%".$_POST['keywords']."%') order by id";
	}
	else {
		$sql = "select id,username,name,branch,duty from $tbl_user order by id";
	}
	$rs = $db->Execute($sql);
	if (!$rs){
		exit;
	}
	$users=array();

	
	if (isset($_GET['page'])){
		$page = intval($_GET['page']);
	}
	else {
		$page = 0;
	}
	$count = 18;
	$users_count = $rs->RecordCount();
	$users_pages = intval($users_count / $count);
	if (($users_count % $count)!=0){
		$users_pages++;
	}	
	$co = 0;
	$start = $page*$count;
	$finish = $start + $count; 
	
	
	while ($arr=$rs->FetchRow()){
		
		if ($co>=$start && $co<$finish){
			array_push($users,$arr);
		} 
		else if ($co >= $finish){
			break;
		}
		$co++;		
	}
	

	$smarty->assign("pagecount",$users_pages);
	$smarty->assign("pagenum",$page);
	$smarty->assign("users_count",$users_count);
	$smarty->assign("users",$users);
	$smarty->display("user_list.html");
		
	
}
else if ($cmd=="edit"){	
	if (isset($_GET['uid'])){
		$uid=$_GET['uid'];
	}
	else if (isset($_POST['id'])){
		$uid=$_POST['id'];
	}
	else {
		$uid=GetUid();
	}
	if (empty($_POST['postflag'])){
		$sql = "select * from $tbl_user where id = $uid";
		$data=$db->GetRow($sql);
		$smarty->assign("data",$data);
		$smarty->assign("cmd","edit");
		$smarty->display("user_edit.html");
	}
	else {
		if ($_POST['password']!=$_POST['password1']){
			alert("���벻һ��!");
			exit;
		}
		if (strlen(trim($_POST['password']))==0){
			unset($_POST['password']);
		}
		else{
			$_POST['password']=MD5($_POST['password']);
		}
		$sql = "select * from $tbl_user where id = $uid";
		$rs = $db->Execute($sql);
		if (! isset($_POST['lock_flag'])){
			$_POST['lock_flag']="0";
		}
		$sql = $db->GetUpdateSQL($rs, $_POST);
		if (!$sql){
			alert("û���κθĶ�");
			frame_redirection("mainFrame","control.php?act=user_manage&cmd=list");
			exit;		
		}
		if ($db->Execute($sql)){
			alert("������Ϣ���³ɹ���");
			frame_redirection("mainFrame","control.php?act=user_manage&cmd=list");
		}
		else {
			alert("������Ϣ����ʧ�ܣ�");
		}
	}
}
else if ($cmd=="add"){
	if (empty($_POST['postflag'])){

		$data['id']="0";
		$smarty->assign("data",$data);
		$smarty->assign("cmd","add");
		$smarty->display("user_edit.html");
	}
	else {
		if ($_POST['password']!=$_POST['password1']){
			alert("���벻һ��!");
			exit;
		}
		if (strlen(trim($_POST['password']))==0){
			alert("���벻��Ϊ��!");
			exit;			
		}
		$_POST['password']=MD5($_POST['password']);
		$sql = "select * from $tbl_user where id = 0";
		$rs = $db->Execute($sql);
		if (! isset($_POST['lock_flag'])){
			$_POST['lock_flag']="0";
		}
		unset($_POST['id']);
		$sql = $db->GetInsertSQL($rs, $_POST);
		if (!$sql){
			alert("û���κθĶ�");
			frame_redirection("mainFrame","control.php?act=user_manage&cmd=list");
			exit;		
		}
		if ($db->Execute($sql)){
			alert("�û���ӳɹ���");
			frame_redirection("mainFrame","control.php?act=user_manage&cmd=list");
		}
		else {
			alert("�û����ʧ�ܣ�");
		}
	}
}

else if ($cmd=="delete"){
		$cause=" ( 0";
		$user_ids=explode(',',$_GET['uid']);
		foreach($user_ids as $id){
			$id = intval($id);
			$cause= $cause." or id = ".$id;
		}
		$cause=$cause.")";			
		$sql = "delete from $tbl_user where $cause";
		if ($db->Execute($sql)){
			alert("�û�ɾ���ɹ�");
		}
		else {
			alert("�û�ɾ��ʧ��");
		}		
		reload_frame('mainFrame');		
}

?>