function fail(){
		$("login_back").style.visibility="hidden";
		$("loading").style.visibility="hidden";	
		$("username").disabled=null;
		$("password").disabled=null;
		$("test").innerHTML="��¼��....."
}

function showInfo(go_url){
    try{
        if($F("username") && $F("password") && go_url){
            var xmlHttp=   new Ajax.Request(go_url, {method: "POST",
                parameters:Form.serialize(document.forms[0]),
                onComplete:function(){
                   if (xmlHttp.transport.responseText == "success"){
					   $("test").innerHTML="��¼�ɹ�!";
				   		setTimeout("window.location.href='index.php';",500);
						}
					else {
						$("test").innerHTML="��¼ʧ��.�����ԣ�";
						setTimeout("fail()",1500);
					}
					},
                onFailure:function(){
						alert("����������Ӧ,������"+xmlHttp.transport.status);
						fail();						
					}
			}
			);
			$("username").disabled="disabled";
			$("password").disabled="disabled";
        }
    } catch (err) {
        alert("���ݿ����������Ӧ") ;
    }
}