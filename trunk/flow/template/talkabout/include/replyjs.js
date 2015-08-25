function reply_submitlog()
{
	document.logfrm.url.value = document.location.href;
	//document.logfrm.submit();
}
function reply_quote(id)
{
	reply_clearfield();
	document.formpost.body.focus();
	document.formpost.body.value+="[quote]"+id+"[/quote]\n";
	document.formpost.body.focus();
}
function reply_setDialog(URLStr) 
{
	window.showModelessDialog(URLStr,window,'dialogWidth:500px;dialogHeight:400px;status:no;scroll:no;help:no;');
}
function reply_report(userid, postid, board)
{
	/*
	 * $a.get("userid")
	 * $a.get("postid")
	 * $board
	 */
	reply_setDialog("http://comment.news.163.com/reply/report.jsp?appellee="+userid+"&postid="+postid+"&board="+board);
}
var cookies = new String(document.cookie);
function reply_getCookie(namex)
{
	var cookieHeader = namex + "=";
	var beginPosition = cookies.indexOf(cookieHeader);
	if (beginPosition != -1) 
	{
		var acookie = cookies.substring(beginPosition + cookieHeader.length);
		if (acookie.indexOf(";")>-1)
		{
			acookie = acookie.substring(0, acookie.indexOf(";"));
		}
		return acookie;
	}
	return "";
}
function reply_checksubmit()
{
	if(document.formpost.body.value==tip || document.formpost.body.value.length==0)
	{
		alert('请填写内容');
		return false;
	}
	//document.formpost.url.value = window.location.href;
	document.formpost.url.value = "http://comment.news.163.com/"+theboard+"/"+thepostid+".html";
	return true;
}
function reply_clearfield()
{
	if (document.formpost.body.value == tip)
	{
		document.formpost.body.value = "";
	}
}
function reply_setHidden(board, postid, count)
{
	document.formpost.board.value = board;
	document.formpost.threadid.value = postid;
	try {document.getElementById("replycount001").innerHTML = count;}catch(e){}
	try {document.getElementById("replycount002").innerHTML = count;}catch(e){}
	try {document.getElementById("replycount003").innerHTML = count;}catch(e){}
	try {document.getElementById("replycount").innerHTML = count;}catch(e){}
	try {document.getElementById("replycounttop").innerHTML = count;}catch(e){}
	try {document.getElementById("replycountbottom").innerHTML = count;}catch(e){}
}
function reply_allReply()
{
	window.open("http://comment.news.163.com/"+theboard+"/"+thepostid+".html");
}
function reply_newReply()
{
	window.open("http://comment.news.163.com/"+theboard+"/"+thepostid+".html");
}
function myrecord()
{
	window.open("http://comment.news.163.com/viewReplyTree.jsp?userid="+reply_getCookie("NETEASE_SSN"));
}
var tip = "我来评两句！";
var commentnum = reply_getCookie("NTES_SESS");
if(commentnum!="")
{
	try{
		document.getElementById("loginbox").style.display='none';
	}catch(e){}
}
else
{
	document.formpost.body.value=tip;
}
out_nickname		= reply_getCookie("nickname");
out_ip				= reply_getCookie("ip");
out_board			= reply_getCookie("board");
out_postid			= reply_getCookie("postid");
out_ptime			= reply_getCookie("ptime");
out_body			= reply_getCookie("body");
out_userid			= reply_getCookie("NETEASE_SSN");
try {document.logfrm.username.value = out_userid;}catch(e){}