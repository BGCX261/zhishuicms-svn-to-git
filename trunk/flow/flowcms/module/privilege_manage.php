<?php
	$tbl_columns 	= $cfg['tbl_columns'];
	$tbl_group 		= $cfg['tbl_group'];
	$tbl_privilege 	= $cfg['tbl_privilege'];
	if (empty($_POST['postflag'])){
		if (empty($_GET['group_id'])){
			$group_id=$db->GetOne("select id from $tbl_columns limit 1");
		}
		else {
			$group_id=intval($_GET['group_id']);
		}
		$sql = "select id,name from $tbl_group";
		$rs = $db->Execute($sql);
		$groups = $rs->GetRows();
		
		$sql = "select privilege from $tbl_privilege where group_id = $group_id and catalog_id = -1";
		$rs = $db->Execute($sql);
		$privileges="";
		while ($arr = $rs->FetchRow()){
			$privileges=$privileges.",".$arr['privilege'];
		}
		$smarty->assign("privileges",$privileges);
		$smarty->assign("groups",$groups);
		$smarty->assign("group_id",$group_id);
		$smarty->display("privilege_manage.html");
		
		
	}
	else {
		if (empty($_POST['gid'])){
			alert("参数错误");
			exit;
		}
		$db->BeginTrans();
		$sql="delete from $tbl_privilege where catalog_id = -1 and group_id=".intval($_POST['gid']);
		$db->Execute($sql);
		$stmt = $db->Prepare("insert into $tbl_privilege (group_id,catalog_id,privilege) values ( ? ,-1 , ?)");
						
		foreach ($_POST as $key => $value){
			$key=explode("_",$key);
			print_r($key);
			if ($key[0]=="privilege"){
				if ($value>0){
					if (!$db->Execute($stmt,array($_POST['gid'],$value))){
						alert("用户组权限编辑失败!");
						$db->RollbackTrans();
						exit;
					}
				}
			}
		}
	alert("用户组权限编辑成功!");
	$db->CommitTrans();								
		
	}

	

?>