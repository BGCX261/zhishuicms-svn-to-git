<?php
define('in_flow',true);
@include_once ('../inc/global.inc.php');
init_adodb();
init_smarty();
global $smarty,$cfg, $db;
if (!isset($_GET['type'])){
	$type=1;
}
else {
	$type=$_GET['type'];
}

if (!isset($_GET['catalog_id'])){
	$catalog_id=1;
}
else {
	$catalog_id=$_GET['catalog_id'];
}

$smarty->assign("type",$type);    //��ѡ��Ŀ����
$smarty->assign("catalog_id",$catalog_id); //����Ȩ�޺�����չ������
$smarty->display("catalog_picker.html");
?>

