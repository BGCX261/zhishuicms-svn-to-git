<style type="text/css">
<!--
.STYLE1 {font-size: 12px}
-->
</style>
<?php
	session_start();
	if(session_is_registered("anyu")){
		$abc="<div class=STYLE1>请记住你的暗语哦~只属于你和你的TA^_^<br>".$_SESSION["anyu"];
		//$abc = iconv("UTF-8","gb2312",$abc);
		echo $abc;
		exit(0);
	}
	else {

?>
<html>
<head>
<title> 我们相约－心跳时刻！</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<form id="form1" name="form1" method="post" action="">
  <table width="0%" border="0" cellpadding="0" cellspacing="0" class="STYLE1">
    <tr>
      <td rowspan="2">想邀请的TA：</td>
      <td colspan="2"><label>
        <input name="to" type="text" id="to" />
      </label></td>
    </tr>
    <tr>
      <td colspan="2">最好是名字哦！ID也行，如果不会误会的话...</td>
    </tr>
    <tr>
      <td rowspan="2">TA的联系方式：</td>
      <td colspan="2"><label>
        <input name="contact" type="text" id="contact" size="60" />
      </label></td>
    </tr>
    <tr>
      <td colspan="2">尽量详细,比如：蓝田某舍××××室，手机13××××××××（一定要有手机哦^_^）</td>
    </tr>
    <tr>
      <td>你的祝福：</td>
      <td colspan="2"><label>
        <textarea name="content" cols="40" rows="7" id="content"></textarea>
      </label></td>
    </tr>
    <tr>
      <td rowspan="2">你是TA的哪个TA?</td>
      <td colspan="2"><label>
        <input name="from" type="text" id="from" />
      （你的名字）</label></td>
    </tr>
    <tr>
      <td colspan="2"><label>
        <input name="mobile" type="text" id="mobile" />
      </label>        （你的电话）</td>
    </tr><tr><td>&nbsp;</td><td><label>
    <input type="submit"  value="填好了！" />
    </label>
        <label></label></td>
      <td><input type="reset" value="填错了？" /></td>
    </tr>
  </table>
</form>
</body>
</html>



<?php } ?>


<?php
	if($_GET[id] && $_POST[to]){
		$db = mysql_connect("localhost","abc_ajax","abc_ajaxabc_ajax");
		mysql_query('set names utf8', $db);
		mysql_select_db("abc_ajax");
		
		$query = "UPDATE `abc_ajax`.`ajax` SET `mobile` = '{$_POST[mobile]}',
`to` = '{$_POST[to]}' , `contact` = '{$_POST[contact]}' , `content` = '{$_POST[content]}' , `from` = '{$_POST[from]}' WHERE `ajax`.`id` ='{$_GET[id]}' LIMIT 1 ;";

		if( mysql_query($query)){
					$query = "select * from `ajax` where `id`={$_GET[id]}";
					$results = mysql_query($query);
					$result = mysql_fetch_row($results);
					session_register("anyu");
					$_SESSION['anyu'] = $result[6];
					echo "<script>window.location.href='dance.php';</script>";
			
		}
		//echo $result[1];
		
	}
?>