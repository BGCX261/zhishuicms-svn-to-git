function fail(){
		$("login_back").style.visibility="hidden";
		$("loading").style.visibility="hidden";	
		$("username").disabled=null;
		$("password").disabled=null;
		$("test").innerHTML="登录中....."
}

function showInfo(go_url){
    try{
        if($F("username") && $F("password") && go_url){
            var xmlHttp=   new Ajax.Request(go_url, {method: "POST",
                parameters:Form.serialize(document.forms[0]),
                onComplete:function(){
                   if (xmlHttp.transport.responseText == "success"){
					   $("test").innerHTML="登录成功!";
				   		setTimeout("window.location.href='index.php';",500);
						}
					else {
						$("test").innerHTML="登录失败.请重试！";
						setTimeout("fail()",1500);
					}
					},
                onFailure:function(){
						alert("服务器无响应,请重试"+xmlHttp.transport.status);
						fail();						
					}
			}
			);
			$("username").disabled="disabled";
			$("password").disabled="disabled";
        }
    } catch (err) {
        alert("数据库服务器无响应") ;
    }
}