<?php
define('in_flow',true);
@include_once ('../inc/global.inc.php');
	if (!GetUid()){
		exit;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Frameset//EN">
<HTML><HEAD><TITLE>知水内容管理系统 1.0</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<SCRIPT type=text/javascript>
	var expire = new Date();
	expire.setTime(expire.getTime() + 24 * 3600 * 1000);
	expire = expire.toGMTString();
	document.cookie = 'wes_w=' + screen.width + ';expires=' + expire;
	document.cookie = 'wes_h=' + screen.height + ';expires=' + expire;
</SCRIPT>
<META content="MSHTML 6.00.2900.2180" name=GENERATOR>
</HEAD>
<FRAMESET border=0 frameSpacing=0 rows=115,* frameBorder=no>
	<FRAME name=topFrame src="title.html" noResize scrolling=no>
	<FRAMESET name=mainframeset frameBorder=0 cols=200,10,*>
		<FRAME border=0 name=leftFrame src="tree.html" frameBorder=0 scrolling=auto>
		<FRAME name=showhide src="go_left.html" frameBorder=0 noResize scrolling=no>
		<FRAME name=mainFrame src="control.php" frameBorder=no scrolling=auto>
	</FRAMESET>
</FRAMESET>
<noframes>
</noframes>
</HTML>




