 <?php
require('index.inc.php');
checkget();
$now = date("Y-m-d H:i:s");
@$str="INSERT INTO `comm` ( `username` , `comm` , `time` , `ip`  ) 
				VALUES ( '".check($_POST['username'])."', '".check($_POST['body'])."', '".date("Y-m-d H:i:s")."', '".$_SERVER['REMOTE_ADDR']."')";
if ($db->query($str)){
	msg("���۳ɹ�");
	goback();
}
else {
	msg("����ʧ��");
	goback();
}

?>
