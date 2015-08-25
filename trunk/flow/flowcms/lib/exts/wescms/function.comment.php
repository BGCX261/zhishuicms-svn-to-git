<?php



function smarty_function_comment($params, &$smarty)
{
	
	if (isset($params["article_id"]) && substr($_SERVER['REMOTE_ADDR'],0,3)=="10."){
		$smarty->assign("article_id",$params["article_id"]);
		$smarty->display("comment/simple_comment.html");
	}
}

	
?>
