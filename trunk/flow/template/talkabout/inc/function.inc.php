<?php
function jump($url,$interval=10){
	echo("
	<script language=\"javascript\">
	function  jump(){
		location.href='".$url."';
	}
	setTimeout('jump()',$interval);
	</script>
	"
	);
exit;
}
function jumpp($url,$interval=10){
	?>
	<script language="javascript">
	function  jump(){
		parent.location.href='<?=$url?>';
	}
	setTimeout('jump()',<?=$interval?>);
	</script>
<?php
exit;
}
function GetDeploy($name){
	global $_COOKIE;
	if(!isset($_COOKIE['deploy']) || strpos($_COOKIE['deploy'],"\t".$name."\t")===false){
		$type='fold';
		$style='';
	}else{
		$type='open';
		$style='display:none;';
	}
	return array($type,$style);
}



//----------zhaosheng new


function get_power($id,$psd){
	$str="select powerpoint from user where id = '$id' and password= '$psd'";
	Global $db;
	$db->query($str);
	if ($db->num_rows() != 1){
		jumpp('login.php');
	}
	$db->next_record();
	return explode('|',$db->value("powerpoint"));
}

function get_class_no($id,$psd){
	$str="select powerpoint from user where id = '$id' and password= '$psd'";
	Global $db;
	$db->query($str);
	if ($db->num_rows() != 1){
		jumpp('login.php');
	}
	$db->next_record();
	return $db->value("powerpoint");
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
function login($user,$psd){
//	Global $db;
    $db= new DB_Sql();
	$psd = check($psd);
	$user = check($user);
	$str="SELECT * 
		FROM user 
		WHERE username = '".$user."'
				AND password = '".$psd."'";
    $db->query($str);
	if ($db->num_rows() == 1){
		$db->next_record();
		$_SESSION['uid'] = $db->f('id');
		$_SESSION['username'] = $user;
		$_SESSION['psd'] = $psd;
		$_SESSION['admin_time'] = date("Y-m-d H:i:s");
		return True;
	}
	else
		return False;
}
function loginbyother($user,$psd){
//	Global $db;
    $db= new DB_Sql();
	$user = check($user);
	$str="SELECT * 
		FROM user 
		WHERE username = '".$user."'
				AND psd = '".$psd."'";
    $db->query($str);
	if ($db->num_rows() == 1){
		$db->next_record();
		$_SESSION['uid'] = $db->f('id');
		$_SESSION['username'] = $user;
		$_SESSION['psd'] = $psd;
		$_SESSION['admin_time'] = date("Y-m-d H:i:s");
		return True;
	}
	else
		return False;
}
function logout(){
	if (isset($_SESSION['uid']))
	unset($_SESSION['uid']);
	if (isset($_SESSION['username']))
	unset($_SESSION['username']);
	if (isset($_SESSION['psd']))
	unset($_SESSION['psd']);
	if (isset($_SESSION['admin_time']))
	unset($_SESSION['admin_time']);
	setcookie("dw_user_cookie_name", "",-3600);
	jump('login.php');
}

function admin_time(){
	if (!isset($_SESSION['admin_time']))
		return False;
	else{
		$now = date("Y-m-d H:i:s");
		$n = explode(' ',$now);
		$m = explode(' ',$_SESSION['admin_time']);
		if ($n[0] != $m[0])
			return False;
		else {
			$a = explode(':',$n[1]);
			$b = explode(':',$m[1]);
			return (30-(($a[0]*60+$a[1])-($b[0]*60+$b[1])));
		}
	}
}

function get_userid(){
	return $_SESSION['uid'];
}
function get_username(){
	return $_SESSION['username'];
}


function msg($msg){
		echo("
		  <script language=\"javascript\">
				alert(\"$msg\");
          </script>
	");
}

function goback(){
	echo ("
		  <script language=\"javascript\">
				history.go(-1);
          </script>
	");
}
function goback2(){
	echo ("
		  <script language=\"javascript\">
				history.go(-2);
          </script>
	");
}

function checkget(){
	
	foreach ($_GET as $check1){
		$check2=check($check1);
		if ($check1!=$check2 || strlen($check1)!=strlen($check2)){
			msg("����һ����������ѣ��벻Ҫ��ͼ������ϵͳ����Ĳ����Ѿ�����¼������ظ�����������Ϊ�����ǽ���ʵȡ�������ѧ�ʸ񲢱��͹������ش��������أ�");
			@$str="INSERT INTO `attacklog` ( `uid` , `dt` , `ip` , `key` , `memo`  , `httphost` ) 
				VALUES ( '".$_SESSION['uid']."', '".date("Y-m-d H:i:s")."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['SCRIPT_FILENAME']."', '', '".$_SERVER['HTTP_HOST']."')";
  	   		@$db= new DB_Sql();
			@$db->query($str);
	  	    exit;
		}
	}
}


function checkinfofull(){
	if (isset($_SESSION['uid'])){
		$db=new DB_Sql();
		$str="select name,home_tel,mobile  from user where id =".$_SESSION['uid'];
		$db->query($str);
		$db->next_record();
		$name1=$db->value('name');
		$hometel=$db->value('home_tel');
		$mob=$db->value('mobile');
		if (strlen(trim($name1))==0 ||(strlen(trim($hometel))==0&&strlen(trim($mob))==0)){
			msg("�������Ƹ�����Ϣ�������ܹ���д����Ŀȫ����д��ϣ��ٽ��б�����");
			jump("adminindex.php?type=editor_personal_info");
		}
	}
	else {
		jump("login.php");
	}
}

/*

bm
view_bm_state
edit_bm_info
print_apply
print_examcard

view_bm_status
check

start_bm
finish_bm

new_exam
view_exam
edit_exam
del_exam
clear_exam

manager




ѧ����	 studentno
����	name
������	formname
�Ա�	sex
��������	birthday
����       nation
�п�׼��֤��   examcard
������ò	   politics
�������ڵ�	   place
���֤��	   perid
�س�	       strong_suit
��ͥ��ϵ�绰   home_tel
�ֻ�����      mobile
��ҵѧУ      school
����ְ��      headship
������      encouragement
�������ۣ�300�����ڣ�   self_evaluate
������ѧ ���� ���� �༶���� ѧ������   res11,res12,res13,res14,res15
������ѧ ���� ���� �༶���� ѧ������   res21,res22,res23,res24,res25

*/

?>