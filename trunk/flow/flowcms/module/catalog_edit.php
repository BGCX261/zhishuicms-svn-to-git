<?php
	$tbl_columns 	= $cfg['tbl_columns'];
	$tbl_article	= $cfg['tbl_article'];
	$tbl_adodbseq 	= $cfg['tbl_adodbseq'];
	$tbl_group 		= $cfg['tbl_group'];
	$tbl_privilege 	= $cfg['tbl_privilege'];
	if (isset($_GET['cmd']) ){
		$cmd = $_GET['cmd'];
	}
	else  if (isset($_POST['cmd']) ){
		$cmd = $_POST['cmd'];
	}
	else {
		$cmd = "catalog_edit";
	}
	
	if ($cmd =="catalog_edit"){
		$sql = "select * from $tbl_columns where id = ".intval($_GET['catalog_id'])." limit 1";
		$catalog=$db->GetRow($sql);
	
		if (isset($_POST['step'])){
			$step=$_POST['step'];
		}
		else {
			$step="1";
		}
		if ($step=="1"){
		
			if (strlen(trim($catalog['url']))!=0 && $catalog['url']!=NULl){
				$catalog['label_link']=1;
			}

			
			if ($catalog['allow_comment']==0){
				$catalog['allow_comment']=0;
				$catalog['comment_need_check']=0;
			}
			else if ($catalog['allow_comment']==1){
				$catalog['allow_comment']=1;
				$catalog['comment_need_check']=0;			
			}
			else if ($catalog['allow_comment']==2){
				$catalog['allow_comment']=1;
				$catalog['comment_need_check']=1;			
			}
			
			
			$smarty->assign("data",$catalog);
			$smarty->assign("cmd","catalog_edit");
			$smarty->assign("act","catalog_edit");
			$smarty->assign("catalog_id",$_GET['catalog_id']);
			$smarty->assign("id",$_GET['catalog_id']);
			$smarty->display("catalog_edit.html");
		}
		else if ($step=="2"){
			$sql = "select * from $tbl_columns where id = ".intval($_POST['id']);
			$rs = $db->Execute($sql);
			if (! isset($_POST['label_link'])){
				$_POST['label_link']="0";
				$_POST['url']=NULL;
			}
			if (! isset($_POST['show_in_guide'])){
				$_POST['show_in_guide']="0";
			}			
			
			if (! isset($_POST['video'])){
				$_POST['video']="0";
			}
									
			if (! isset($_POST['allow_comment'])){
				$_POST['allow_comment']=0;
			}
			else if (isset($_POST['allow_comment'])){
				if (isset($_POST['comment_need_check'])){
					$_POST['allow_comment']=2;
				}
				else {
					$_POST['allow_comment']=1;
				}
			}
			
			$sql = $db->GetUpdateSQL($rs, $_POST);
			if (!$sql){
				alert("没有任何改动");
				frame_redirection("mainFrame","control.php?act=catalog_list&pcatalog_id=".$_POST['pcatalog']);
				exit;		
			}
			if ($db->Execute($sql)){
				alert("栏目修改成功！");
			}
			else {
				alert("栏目修改失败！");
			}
			frame_redirection("mainFrame","control.php?act=catalog_list&pcatalog_id=".$_POST['pcatalog']);
		}
		
	}
		
	else if ($cmd =="catalog_add"){
		$sql="select id from $tbl_adodbseq ";
		$id=$db->GetOne($sql);
		$sql="update $tbl_adodbseq set id = id+1";
		$db->Execute($sql);
	
		if (isset($_POST['step'])){
			$step=$_POST['step'];
		}
		else {
			$step="1";
		}
		if ($step=="1"){
			$catalog_types[10]="栏目组";
			$catalog_types[9]="专题";
			$catalog_types[8]="投票";
			$catalog_types[7]="自定义页面";
			$catalog_types[6]="链接";
			$catalog_types[3]="投稿";
			$catalog_types[1]="文章";
		
			$data['id']=$id;
			$data['pcatalog']=$_GET['pcatalog_id'];
			$smarty->assign("catalog_types",$catalog_types);			
			$smarty->assign("cmd","catalog_add");
			$smarty->assign("act","catalog_edit");
			$smarty->assign("catalog_id",$_GET['pcatalog_id']);
			$smarty->assign("data",$data);
			$smarty->assign("pcatalog_id",$_GET['pcatalog_id']);
			$smarty->display("catalog_edit.html");
		}
		else if ($step=="2"){
			
			if (!isset($_POST['pcatalog']) || empty($_POST['id'])){
				exit;
			}
			$sql = "select * from $tbl_columns where id = ".intval($_POST['id']);
			$rs = $db->Execute($sql);
			if (! isset($_POST['label_link'])){
				$_POST['label_link']="0";
				$_POST['url']=NULL;
			}
			if (! isset($_POST['show_in_guide'])){
				$_POST['show_in_guide']="0";
			}
			if (! isset($_POST['video'])){
				$_POST['video']="0";
			}			
			
			if (! isset($_POST['allow_comment'])){
				$_POST['allow_comment']=0;
			}
			else if (isset($_POST['allow_comment'])){
				if (isset($_POST['comment_need_check'])){
					$_POST['allow_comment']=2;
				}
				else {
					$_POST['allow_comment']=1;
				}
			}			
			
			
			$sql = "select max(sort_order) from  $tbl_columns where pcatalog=".$_POST['pcatalog'];
			$max=intval($db->GetOne($sql))+1;
			$_POST['sort_order']=$max;	
			$sql = $db->GetInsertSQL($rs, $_POST);
			if (!$sql){
				alert("没有任何改动");
				frame_redirection("mainFrame","control.php?act=catalog_list&pcatalog_id=".$_POST['pcatalog']);
				exit;		
			}
			if ($db->Execute($sql)){
				alert("栏目添加成功！");
			}
			else {
				alert("栏目添加失败！");
			}
			frame_redirection("mainFrame","control.php?act=catalog_list&pcatalog_id=".$_POST['pcatalog']);
		}	
		
	}
	else if ($cmd =="catalog_delete"){
		$ids=explode(",",$_GET['catalog_id']);
		$db->BeginTrans();
		foreach($ids as $id){
			catalog_delete($id);
		}
		$db->CommitTrans();	
		alert("栏目删除成功!");
		frame_redirection("mainFrame","control.php?act=catalog_list&pcatalog_id=".$_GET['pcatalog']);
	}
	
	
	
	
	
	else if ($cmd =="catalog_privilege"){
		
		$sql = "select id,name from $tbl_columns where id=".$_GET['catalog_id'];
		$catalog=$db->GetRow($sql);
		
		
		if (isset($_POST['step'])){
			$step=$_POST['step'];
		}
		else {
			$step="1";
		}
		if ($step=="1"){

			$sql = "select id,name from $tbl_group";
			$rs = $db->Execute($sql);
			$groups = $rs->GetRows();
		
			$sql="select group_id,privilege from $tbl_privilege where catalog_id =".$catalog['id'];
			$rs = $db->Execute($sql);
			$privileges = $rs->GetRows();//[0]组名称 [1]权限值 [2]当前用户无权编辑该组权限
			$smarty->assign("catalog",$catalog);
			$smarty->assign("privileges",$privileges);
			$smarty->assign("groups",$groups);
			$smarty->assign("privileges",$privileges);
			$smarty->display("catalog_privilege_edit.html");
		}
		else if ($step=="2"){

			foreach($_POST as $groups => $pri){
				$group =explode("_",$groups);
				if ($group[0]=="g"){
					$group_id=intval($group[1]);
					$sql = "select privilege from $tbl_privilege where group_id = $group_id and catalog_id = ".$_POST['catalog_id'];
					$f_pri = $db->GetRow($sql);
				
					if (!$f_pri){
						$sql = "insert into $tbl_privilege (group_id,catalog_id,privilege) values ( $group_id ,".$_POST['catalog_id'].",$pri)";
						$db->Execute($sql);
					}
					else if ($f_pri['privilege'] != $pri){
						$sql = "update $tbl_privilege set privilege = $pri where group_id = $group_id and catalog_id = ".$_POST['catalog_id'];
						$db->Execute($sql);
					}
				}
			}
			alert("更改成功！");
			frame_redirection("mainFrame","control.php?act=catalog_list&pcatalog_id=".$_POST['pcatalog']);
		}			
		

	}
	else if ($cmd =="catalog_order_adjust"){
		$id = intval($_GET['catalog_id']);
		$pid = intval($_GET['pcatalog_id']);
		if ($_GET['dir']=="up"){
			$sql= "select sort_order from $tbl_columns where id = $id";
			$sort_order = $db->GetOne($sql);
			$sql="select sort_order from $tbl_columns where sort_order<$sort_order and pcatalog=$pid order by sort_order desc limit 1";
			$less_sort_order=$db->GetOne($sql);
			if ($less_sort_order){
				$sql = "update $tbl_columns set sort_order=$sort_order where sort_order=$less_sort_order and pcatalog=$pid";
				$db->Execute($sql);
				$sql = "update $tbl_columns set sort_order=$less_sort_order where id = $id";
				$db->Execute($sql);
			}
		}
		else {
			$sql= "select sort_order from $tbl_columns where id = $id";
			$sort_order = $db->GetOne($sql);
			$sql="select sort_order from $tbl_columns where sort_order>$sort_order and pcatalog=$pid order by sort_order limit 1";
			$less_sort_order=$db->GetOne($sql);
			if ($less_sort_order){
				$sql = "update $tbl_columns set sort_order=$sort_order where sort_order=$less_sort_order and pcatalog=$pid";
				$db->Execute($sql);
				$sql = "update $tbl_columns set sort_order=$less_sort_order where id = $id";
				$db->Execute($sql);
			}
					}
		frame_redirection("mainFrame","control.php?act=catalog_list&pcatalog_id=$pid");
		
	}
	
	else{
		alert ("未定义操作！");
		exit;
	}
	
	function catalog_delete($id){
		global $cfg;
		global $db;
		$tbl_columns=$cfg['tbl_columns'];
		$tbl_article=$cfg['tbl_article'];
		$sql="select id from $tbl_columns where pcatalog = $id";
		$rs=$db->Execute($sql);
		if (! $rs){
			$db->RollbackTrans();
			alert("栏目删除失败!");
			exit;
		}
	
		if ($rs){
			while ($arr= $rs->FetchRow()){
				catalog_delete($arr['id']);
			}
		}
		$sql="delete from $tbl_article where catalog = $id";
		if (!$db->Execute($sql)){
			$db->RollbackTrans();
			alert("栏目删除失败!");
			exit;			
		}
		$sql="delete from $tbl_columns where id = $id";
		if (!$db->Execute($sql)){
			$db->RollbackTrans();
			alert("栏目删除失败!");
			exit;				
		}
	}

	
?>