<?php
define('in_flow',true);
@include_once ('../inc/global.inc.php');
include ('lib/charset.php');
init_adodb();
init_smarty();
global $smarty,$cfg, $db;
if (!isset($_POST['postflag'])){
	$pic=rand(1,17);
	$smarty->assign("picid",$pic);
	$smarty->display("login.html");
}
else{
	$_POST['username']=Charset::convert($_POST['username'],"utf8","gbk");
	if (login($_POST['username'],MD5($_POST['password'])) == true ){
		echo "success";
		exit;
	}
	else {
		echo "fail";
		exit;
	}
}
?>
