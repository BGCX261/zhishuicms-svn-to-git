	<?php
	$tbl_columns=$cfg['tbl_columns'];
	$tbl_article=$cfg['tbl_article'];
	$tbl_adodbseq=$cfg['tbl_adodbseq'];

	$sql = "select id,name,type from $tbl_columns where pcatalog = ".intval($_GET['pcatalog_id'])." order by sort_order";
	$rs = $db->Execute($sql);
	if (!$rs){
		//dbalert("DbError..catalog_list");
		exit;
	}
	$catalogs=array();

	
	if (isset($_GET['page'])){
		$page = intval($_GET['page']);
	}
	else {
		$page = 0;
	}
	$count = 18;
	$catalogs_count = $rs->RecordCount();
	$catalogs_pages = intval($catalogs_count / $count);
	if (($catalogs_count % $count)!=0){
		$catalogs_pages++;
	}	
	$co = 0;
	$start = $page*$count;
	$finish = $start + $count; 
	
	
	while ($arr=$rs->FetchRow()){
		if ($co>=$start && $co<$finish){
			array_push($catalogs,$arr);
		} 
		else if ($co >= $finish){
			break;
		}
		$co++;		
	}
	

	$smarty->assign("pagecount",$catalogs_pages);
	$smarty->assign("pagenum",$page);
	$smarty->assign("catalogcount",$catalogs_count);
	$smarty->assign("pcatalog_id",$_GET['pcatalog_id']);
	$smarty->assign("catalogs",$catalogs);
	$smarty->display("catalog_list.html");
	
	
?>