<?php
	$tbl_columns=$cfg['tbl_columns'];
	$tbl_article=$cfg['tbl_article'];
	$tbl_customise=$cfg['tbl_customise'];
	$tbl_link=$cfg['tbl_link'];
	if (isset($_GET['catalog_id'])){
		$catalog_id=intval($_GET['catalog_id']);
	}
	else if (isset($_POST['catalog_id'])){
		$catalog_id=intval($_POST['catalog_id']);
	}
	else {
		exit;
	}
	$sql = "select id,pcatalog,name,type from $tbl_columns where id = $catalog_id";
	$column=$db->GetRow($sql);
	if (!$column){
		db_error("browse_catalog.!column",$sql);
	}
	
	
	//文章or 投稿
	if ($column['type']==1 or $column['type']==3 ){
		if (isset($_GET['page'])){
			$page = intval($_GET['page']);
		}
		else {
			$page = 0;
		}
		$count = 19;
		
		$sql = "select id,heading,dt,editor,visitcount,status,score from $tbl_article where catalog = $catalog_id";
		if (isset($_POST['keywords'])){
			$sql = $sql." and (`heading` LIKE '%".$_POST['keywords']."%' or `content` LIKE '%".$_POST['keywords']."%')";
		}
		$sql = $sql." order by id desc";
		$rs=$db->Execute($sql);
		$articles_count = $rs->RecordCount();
		$articles_pages = intval($articles_count / $count);
		if (($articles_count % $count)!=0){
			$articles_pages++;
		}
		$articles=array();
		$co = 0;
		$start = $page*$count;
		$finish = $start + $count; 
		
		if($rs){
				while ($arr=$rs->FetchRow()){	
					if ($co>=$start && $co<$finish){
						array_push($articles,$arr);
					} 
					else if ($co >= $finish){
						break;
					}
					$co++;
				}
	    }
		$smarty->assign("pagecount",$articles_pages);
		$smarty->assign("pagenum",$page);
		$smarty->assign("catalog",$column);
		$smarty->assign("articles",$articles);
		$smarty->assign("articlecount",$articles_count);
		$smarty->display("browse_catalog.html");
	}
	
	
	
	//自定义页面
	else if ($column['type']==7){
		$sql = "select * from $tbl_customise where col = $catalog_id";
		if (!isset($_POST['postflag'])){
			$data=$db->GetRow($sql);
			if (!$data){
				db_error("browse_catalog.!data",$sql);
			}
			$smarty->assign('data',$data);
			$smarty->assign("flow_basedir",$cfg['flow_basedir']);
			$smarty->display("customize_edit.html");
		}
		else {
			$rs = $db->Execute($sql);
			$sql =  $db->GetUpdateSQL($rs, $_POST);
			if (!$sql){
				alert("没有任何改动");
				exit;		
			}
			if ($db->Execute($sql)){
				alert("自定义页面修改成功！");
			}
			else {
				alert("自定义页面修改失败！");
			}			
		}
	}
	
	
	
	//链接
	else if ($column['type']==6){
		if (isset($_GET['page'])){
			$page = intval($_GET['page']);
		}
		else {
			$page = 0;
		}
		$count = 19;
		
		$sql = "select * from $tbl_link where catalog = $catalog_id";
		if (isset($_POST['keywords'])){
			$sql = $sql." and (`name` LIKE '%".$_POST['keywords']."%' or `descri` LIKE '%".$_POST['keywords']."%')";
		}
		$sql = $sql." order by id desc";
		$rs=$db->Execute($sql);
		$articles_count = $rs->RecordCount();
		$articles_pages = intval($articles_count / $count);
		if (($articles_count % $count)!=0){
			$articles_pages++;
		}
		$articles=array();
		$co = 0;
		$start = $page*$count;
		$finish = $start + $count; 
		
		if($rs){
				while ($arr=$rs->FetchRow()){	
					if ($co>=$start && $co<$finish){
						array_push($articles,$arr);
					} 
					else if ($co >= $finish){
						break;
					}
					$co++;
				}
	    }
		$smarty->assign("pagecount",$articles_pages);
		$smarty->assign("pagenum",$page);
		$smarty->assign("catalog",$column);
		$smarty->assign("articles",$articles);
		$smarty->assign("articlecount",$articles_count);
		$smarty->display("link_browse.html");

	}
	
	
	
	
?>