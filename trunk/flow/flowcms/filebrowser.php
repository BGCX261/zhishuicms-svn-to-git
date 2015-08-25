<?php
define('in_flow',true);
include_once ('../inc/global.inc.php');
init_adodb();
init_smarty();
global $smarty,$cfg, $db;
$tbl_attachment=$cfg['tbl_attachment'];

	if (isset($_GET['page'])){
		$page = intval($_GET['page']);
	}
	else {
		$page = 0;
	}
	$count = 10;
	if (isset($_GET['object_id'])){
		$object_id=intval($_GET['object_id']);
	}
	else if (isset($_POST['object_id'])){
		$object_id=intval($_POST['object_id']);
	}

	if (isset($_GET['class_id'])){
		$class_id=intval($_GET['class_id']);
	}
	else if (isset($_POST['class_id'])){
		$class_id=intval($_POST['class_id']);
	}	
	else {
		$class_id=1;
	}
	
	$sql = "select * from $tbl_attachment where catalog_id =$class_id and object_id =$object_id and mime = '".$_GET['Type']."'";
	$rs=$db->Execute($sql);
	$attachment_count = $rs->RecordCount();
	$attachment_pages = intval($attachment_count / $count);
	if (($attachment_count % $count)!=0){
		$attachment_pages++;
	}
	$attachments=array();
	$co = 0;
	$start = $page*$count;
	$finish = $start + $count; 
	
	if($rs){
			while ($arr=$rs->FetchRow()){	
				if ($co>=$start && $co<$finish){
					array_push($attachments,$arr);
				} 
				else if ($co >= $finish){
					break;
				}
				$co++;
			}
    }
	$smarty->assign("pagecount",$attachment_pages);
	$smarty->assign("pagenum",$page);
	$smarty->assign("attachments",$attachments);
	$smarty->assign("attachmentcount",$attachment_count);
	$smarty->assign("filetype",$_GET['Type']);
	$smarty->assign("type",$_GET['Type']);
	$smarty->assign("flow_basedir",$cfg['flow_basedir']);
	$smarty->assign("class_id",$class_id);
	$smarty->assign("object_id",$object_id);

	$smarty->display("filebrowse.html");
?>
