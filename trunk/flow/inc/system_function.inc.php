<?php
function jump($url,$top=0){
	if ($top){
		$top=".top";
	}
	else {
		$top="";
	}
	echo ("
		<script language='JavaScript'>
			<!--
				window$top.location.href='$url';
			//-->
		</script>
		");
	return ;
}

function check($str){
	if (is_string($str)){
		$str = trim($str);
		$str = str_replace(array('&',';'),array('&#38;','&#59;'),$str);
		$dw_key=array('|', "'", '"','~', '<', '>', '$', "\t", "\n", ' ');
		$R_key=array('&#124;','&#39;','&#34;','&#126;','&lt;','&gt;','&#36;','&nbsp;','','&nbsp;');
		$str = str_replace($dw_key,$R_key,$str);
	}
	return $str;
}

function alert($msg){
	$msg=str_replace(array("'"),array("\'"),$msg);
	echo ("
		<script language='JavaScript'>
			<!--
				alert('$msg!');
			//-->
		</script>
		");
}

function reload_frame($name){
	echo ("
		<script language='JavaScript'>
			<!--
				for(var i=0;i<top.frames.length;i++){
					if (top.frames[i].name=='$name'){
						top.frames[i].location.reload();
					}
				}
			//-->
		</script>
		");
}

function frame_redirection($name,$url){
	echo ("
		<script language='JavaScript'>
			<!--
				for(var i=0;i<top.frames.length;i++){
					if (top.frames[i].name=='$name'){
						top.frames[i].location='$url';
					}
				}
			//-->
		</script>
		");
}

function checkurl(){
	foreach ($_GET as $check1){
		$check2=check($check1);
		if ($check1!=$check2 || strlen($check1)!=strlen($check2)){
			@$str="INSERT INTO `attacklog` ( `uid` , `dt` , `ip` , `key` , `memo`  , `httphost` ) 
				VALUES ( '".$_SESSION['uid']."', '".date("Y-m-d H:i:s")."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['SCRIPT_FILENAME']."', '', '".$_SERVER['HTTP_HOST']."')";
  	   		@$db= new DB_Sql();
			@$db->query($str);
			echo "攻击提示";
	  	    exit;
		}
	}
}

function debug_exit($msg="No Debug Message!"){
	global $cfg;
	if ($cfg['debug']){
		echo($msg);
		exit;
	}
}
function dbalert($msg="数据库出错"){
	global $cfg;
	if ($cfg['debug']){
		echo($msg);
		exit;
	}	
}
function debug_noexit($msg="No Debug Message!"){
	global $cfg;
	if ($cfg['debug']){
		echo($msg);
	}
}
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


function login($user,$password,$isMD5=1){
	global $cfg;
	global $db;
	$tbl_user = $cfg['tbl_user'];
	if (!$isMD5){
		$password=MD5($password);
	}
	$sql = "select id from $tbl_user where username = '$user' and password = '$password'";
	$arr = $db->GetRow($sql);
	sleep(1); 
	if (!$arr){
		sleep(2); 
		return false;
	}
	$_SESSION['flow_uid']=$arr['id'];
	$_SESSION['flow_username']=$user;
	$_SESSION['flow_password']=$password;	
	return true;;

}

function GetUid(){
	if (!isset ($_SESSION['flow_uid'])){
		jump("login.php",1);
	}
	return $_SESSION['flow_uid'];
	
}
function GetGroups($id){
	global $cfg;
	global $db;
	$tbl_usergroup=$cfg['tbl_usergroup'];
	$sql = "select groupid from $tbl_usergroup where userid = $id";
	$rs = $db->Execute($sql);
	$groups=array();
	while ($arr=$rs->Fetchrow()){
		array_push($groups,$arr['groupid']);
	}
	return $groups;
}

function GetSeq(){
	global $cfg;
	global $db;
	$tbl_adodbseq=$cfg['tbl_adodbseq'];
	$sql="select id from $tbl_adodbseq ";	
	$object_id=$db->GetOne($sql);
	$sql="update $tbl_adodbseq set id = id+1";
	$db->Execute($sql);
	return $object_id;
	
}

function GetUserName(){
	global $cfg;
	global $db;
	$tbl_user=$cfg['tbl_user'];
	$uid=GetUid();
	$sql = "select name from $tbl_user where id = $uid";
	$name = $db->GetOne($sql);
	if (! $name){
		$name = "本站编辑";
	}
	return $name;
	
}

function iplist($ip,$type){
	global $cfg;
	global $db;
	$tbl_iplist=$cfg['tbl_iplist'];
	$sql = "select ip from $tbl_iplist where type = $type";
	$rs = $db->Execute($sql);
	while ($ips=$rs->FetchRow()){
		
		$ips=str_replace("*","[0-9]*",$ips['ip']);
		$ips=str_replace(".","\\.",$ips);
		$ips="/^".$ips."$/";
		if (preg_match($ips,$ip)){
			alert($ips);
			return true; 
		}
	}
	return false;
}
function refresh(){
	global $cfg;
	global $db;
	$tbl_refresh_time=$cfg['tbl_refresh_time'];
	$sql = "select refresh from $tbl_refresh_time limit 1";
	$old_time=$db->GetOne($sql);
	$old_time+=$cfg['refresh_time'];
	$now_time=time();
	if ($old_time < $now_time){
		$sql = "update $tbl_refresh_time set refresh=$now_time";
		$db->Execute($sql);
		return true;
	}
	else {
		alert("请勿在短时间内重复刷新页面，剩余时间".($old_time-$now_time)."秒");
		exit;
		return false;
	}
	
	
	
	
}


?>