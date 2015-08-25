<?php
$tbl_user=$cfg['tbl_user'];
$tbl_group=$cfg['tbl_group'];
$tbl_adodbseq=$cfg['tbl_adodbseq'];
$tbl_usergroup=$cfg['tbl_usergroup'];
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
		$sql = "select id,name,descri from $tbl_group where  (`name` LIKE '%".$_POST['keywords']."%' or `descri` LIKE '%".$_POST['keywords']."%') order by id";
	}
	else {
		$sql = "select id,name,descri from $tbl_group order by id";
	}
	$rs = $db->Execute($sql);
	if (!$rs){
		exit;
	}
	$groups=array();

	
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
			array_push($groups,$arr);
		} 
		else if ($co >= $finish){
			break;
		}
		$co++;		
	}
	

	$smarty->assign("pagecount",$users_pages);
	$smarty->assign("pagenum",$page);
	$smarty->assign("users_count",$users_count);
	$smarty->assign("groups",$groups);
	$smarty->display("group_list.html");
		
	
}
else if ($cmd=="edit"){	
	if (isset($_GET['gid'])){
		$gid=$_GET['gid'];
	}
	else if (isset($_POST['id'])){
		$gid=$_POST['id'];
	}
	else {
		alert("参数错误");
		exit;
	}
	if (empty($_POST['postflag'])){
		$sql = "select * from $tbl_group where id = $gid";
		$data=$db->GetRow($sql);
		$smarty->assign("data",$data);
		$smarty->assign("cmd","edit");
		$smarty->display("group_edit.html");
	}
	else {
		$sql = "select * from $tbl_group where id = $gid";
		$rs = $db->Execute($sql);
		$sql = $db->GetUpdateSQL($rs, $_POST);
		if (!$sql){
			alert("没有任何改动");
			exit;		
		}
		if ($db->Execute($sql)){
			alert("用户组更新成功！");
			frame_redirection("mainFrame","control.php?act=group_manage&cmd=list");
		}
		else {
			alert("用户组更新失败！");
		}
	}
}
else if ($cmd=="add"){
	if (empty($_POST['postflag'])){

		$data['id']="0";
		$smarty->assign("data",$data);
		$smarty->assign("cmd","add");
		$smarty->display("group_edit.html");
	}
	else {
		$sql = "select * from $tbl_group where id = 0";
		$rs = $db->Execute($sql);
		unset($_POST['id']);
		$sql = $db->GetInsertSQL($rs, $_POST);
		if (!$sql){
			alert("没有任何改动");
			frame_redirection("mainFrame","control.php?act=group_manage&cmd=list");
			exit;		
		}
		if ($db->Execute($sql)){
			alert("用户组添加成功！");
			frame_redirection("mainFrame","control.php?act=group_manage&cmd=list");
		}
		else {
			alert("用户组添加失败！");
		}
	}
}

else if ($cmd=="delete"){
		$cause=" ( 0";
		$user_ids=explode(',',$_GET['gid']);
		foreach($user_ids as $id){
			$id = intval($id);
			$cause= $cause." or id = ".$id;
		}
		$cause=$cause.")";			
		$sql = "delete from $tbl_group where $cause";
		if ($db->Execute($sql)){
			alert("用户组删除成功");
		}
		else {
			alert("用户组删除失败");
		}		
		reload_frame('mainFrame');		
}


else if ($cmd=="member"){
	if (empty($_POST['postflag'])){
		$sql="select id,name,descri from $tbl_group where id =".intval($_GET['gid']);
		$group=$db->GetRow($sql);
		$sql="select $tbl_user.id ,$tbl_user.username,$tbl_user.name from $tbl_user where $tbl_user.id in (select userid from $tbl_usergroup where groupid =".intval($_GET['gid']).")";
		$rs=$db->Execute($sql);
		$current_users=$rs->GetRows();
		$sql="select $tbl_user.id ,$tbl_user.username,$tbl_user.name from $tbl_user ";
		$rs=$db->Execute($sql);
		$all_users=$rs->GetRows();		
		$smarty->assign("group",$group);
		$smarty->assign("current_users",$current_users);
		$smarty->assign("all_users",$all_users);
		$smarty->display("group_user_choose.html");
	}
	else{
		$users=explode(',',$_POST['users']);
		$db->BeginTrans();
		$sql="delete from $tbl_usergroup where groupid=".intval($_POST['gid']);
		$db->Execute($sql);
		$stmt = $db->Prepare("insert into $tbl_usergroup (groupid,userid) values (? , ?)");
		foreach($users as $id){
			$id = intval($id);
			if ($id>0){
				if (!$db->Execute($stmt,array($_POST['gid'],$id))){
					alert("组成员编辑失败!");
					$db->RollbackTrans();
					exit;
				}
			}
		}
		alert("组成员编辑成功!");
		$db->CommitTrans();	
		frame_redirection("mainFrame","control.php?act=group_manage&cmd=list");
	}
}






















?>