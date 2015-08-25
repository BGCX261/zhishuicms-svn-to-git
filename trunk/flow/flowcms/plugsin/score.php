<?php
	$tbl_user=$cfg['tbl_user'];
	$tbl_adodbseq=$cfg['tbl_adodbseq'];
	$tbl_article=$cfg['tbl_article'];
	$cause="";
	$article_cause="";
	if (isset($_POST['keywords']) && strlen(trim($_POST['keywords'])) !=0 ){
		$cause = "and ( `username` LIKE '%".$_POST['keywords']."%' or `name` LIKE '%".$_POST['keywords']."%')";
	}
	
	if (isset($_POST['branch'])  && strlen(trim($_POST['branch'])) !=0){
		$cause = $cause." AND branch = '".$_POST['branch']."'";
	}
	if (isset($_POST['date_from']) && strlen(trim($_POST['date_from'])) !=0){	
		$article_cause = $article_cause." AND dt >= '".$_POST['date_from']."'";
	}
	if (isset($_POST['date_to']) && strlen(trim($_POST['date_to'])) !=0){	
		$article_cause = $article_cause."  AND dt <= '".$_POST['date_to']."'";
	}	
	
	
	$sql="SELECT * 
		FROM (
		
		SELECT * 
		FROM (
		
		SELECT count( id ) AS count, ownerid, sum( score ) AS sum_score
		FROM $tbl_article
		$article_cause
		GROUP BY ownerid
		) AS a
		RIGHT JOIN (
		SELECT DISTINCT name,username,branch,duty,id
		FROM $tbl_user where 1
		$cause
		) AS b ON ( a.ownerid = b.id ) 
		) AS tbl
		order by sum_score desc";
	
	
	
	$rs = $db->Execute($sql);
	if (!$rs){
		exit;
	}
	$users=array();

	
	if (isset($_GET['page'])){
		$page = intval($_GET['page']);
	}
	else {
		$page = 0;
	}
	$count = 18;
	$users_count = $rs->RecordCount();
	$users_pages = intval($users_count / $count);
	if (($users_count % $count)!=0){
		$users_pages++;
	}	
	$co = 0;
	$start = $page*$count;
	$finish = $start + $count; 
	
	
	while ($arr=$rs->FetchRow()){
		
		if ($co>=$start && $co<$finish){
			array_push($users,$arr);
		} 
		else if ($co >= $finish){
			break;
		}
		$co++;		
	}
	

	$smarty->assign("pagecount",$users_pages);
	$smarty->assign("pagenum",$page);
	$smarty->assign("users_count",$users_count);
	$smarty->assign("users",$users);
	$smarty->display("plugsin/score.html");
?>