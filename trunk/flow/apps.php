<?php
@include_once ('inc/global.inc.php');
init_adodb();
init_smarty();
global $smarty;
global $cfg;
global $db;
$tbl_article=$cfg['tbl_article'];	
$tbl_commnet=$cfg['tbl_commnet'];
$tbl_columns=$cfg['tbl_columns'];
if (isset($_GET['refresh'])){
		$smarty->myClearCache();
		$cfg['adodb_cache'] = false;
}
switch ($_GET['cmd']){
	case "showcommnet":
		$object_id = intval($_GET['object_id']);
		$sql = "select id,catalog,heading,content from $tbl_article where id = $object_id";
		$article = $db->GetRow($sql);
		$catalog_id = $article['catalog'];
		if (!allow_comment($catalog_id)){
			alert("该栏目不允许评论");
			exit;
		}
		$template=get_template($catalog_id,"comment_template");
		$sql = "select id,article_id,username,time,ip,content from $tbl_commnet where article_id = $object_id and status = 1 order by time desc";
		$rs = $db->Execute($sql);
		$comments=$rs->GetRows();
		$comments_count=$rs->RecordCount();
		$smarty->assign("comments_count",$comments_count);
		$smarty->assign("article",$article);
		$smarty->assign("comments",$comments);
		$smarty->display($template);		
		break;
	
		
	case "postcommnet":
		
	    if (isset($_POST['postflag']) && $_POST['postflag']=='1'){        //add new comment
			if (!empty($_GET['object_id'])){
	    		$article_id=intval($_GET['object_id']);
	    	}
	    	else if (!empty($_POST['object_id'])){
	    		$article_id=intval($_POST['object_id']);
	    	}
	    	else {
	    		alert("参数错误");
	    		exit;
	    	}
			$sql = "select id,catalog,heading,content from $tbl_article where id = $article_id";
			$article = $db->GetRow($sql);
			$catalog_id = $article['catalog'];
			$allow_comment=allow_comment($catalog_id);
			if (!$allow_comment){
				alert("该栏目不允许评论");
				exit;
			}
			
	    	if (!isset($_POST['content']) || strlen($_POST['content'])<=1) {
	    		msg("请输入评论内容！");    
	    		goback();
	    		exit;		
	    	}
	    	if ($allow_comment==1){
	    		$data['status']=1;
	    	}
	    	else {
	    		$data['status']=0;
	    	}
	    	
	    	$data['username']=$_POST['username'];
	    	$data['content']=$_POST['content'];
	    	$data['time']=date("Y-m-d H:m:s",time()+28800);
	    	$data['article_id']=$article_id;
			$rs = $db->Execute("select * from $tbl_commnet where 0");
			$sql =  $db->GetInsertSQL($rs, $data);
	    	if ($db->Execute($sql)) {
	    		$_POST['postflag']='0';
	    		alert("添加评论成功，感谢您的参与！");
	    	}
	    	else {
	    		alert("评论失败");
	    	}
	    }
		break;
		
	case "showcheckno":
		break;

	case "set_disagree"	:
		$id = intval($_GET['object_id']);
		$sql = "update $tbl_article set disagree=disagree+1 where id = $id";
		$db->Execute($sql);
		alert("操作成功");
		break;
	case "set_approve" :
		$id = intval($_GET['object_id']);
		$sql = "update $tbl_article set approve=approve+1 where id = $id";
		$db->Execute($sql);
		alert("操作成功");
		break;		
		
}

function allow_comment($catalog_id){
	global $db;
	global $cfg;
	$tbl_columns=$cfg['tbl_columns'];
	$sql = "select allow_comment from $tbl_columns where id = $catalog_id limit 1";
	return $db->GetOne($sql);
}


function get_template($catalog_id, $template_name)
{
  global $db;
  global $cfg;
  $tbl_columns = $cfg['tbl_columns'];
  $data['pcatalog'] = $catalog_id;
  $data['tpl'] = '';
  $sql = "select pcatalog, ". $template_name . " as tpl from  $tbl_columns
          where id=";
  while ((int)$data['pcatalog'] > 0 && !$data['tpl'])
  {
  	if ($cfg['adodb_cache']){
  		$data = $db->CacheGetRow($sql . $data['pcatalog']);
  	}
  	else {
      $data = $db->GetRow($sql . $data['pcatalog']);
  	}
  }
  return $data['tpl'];
}

?>