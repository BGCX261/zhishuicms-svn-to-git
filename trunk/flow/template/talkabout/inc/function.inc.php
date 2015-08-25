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
			msg("这是一次善意的提醒：请不要试图攻击本系统，你的操作已经被记录，如果重复发生攻击行为，我们将核实取消你的入学资格并报送公安机关处理。请慎重！");
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
			msg("请先完善个人信息！将您能够填写的项目全部填写完毕，再进行报名。");
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




学籍号	 studentno
姓名	name
曾用名	formname
性别	sex
出生日期	birthday
民族       nation
中考准考证号   examcard
政治面貌	   politics
户口所在地	   place
身份证号	   perid
特长	       strong_suit
家庭联系电话   home_tel
手机号码      mobile
毕业学校      school
担任职务      headship
所获奖励      encouragement
自我评价（300字以内）   self_evaluate
初二数学 语文 外语 班级名次 学年名次   res11,res12,res13,res14,res15
初三数学 语文 外语 班级名次 学年名次   res21,res22,res23,res24,res25

*/

?>