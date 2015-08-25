<?php
define('in_flow',true);
@include_once ('../inc/global.inc.php');
init_adodb();
init_smarty();
global $smarty,$cfg, $db;
$smarty->display("header.html");
if (!isset($_GET['act'])){
	require("module/default.php");
}

require('../inc/privilege_check.php');

switch ($_GET['act']){
	case "browse_catalog":
		if (!isset($_GET['catalog_id'])){
			require("module/default.php");		
		}
		else {
			require("module/browse_catalog.php");
		}
		break;

	case "article_edit":
		if (!isset($_GET['catalog_id']) && !isset($_GET['article_id'])){
			require("module/default.php");		
		}
		else {
			require("module/article_edit.php");
		}
		break;		
		
	case "catalog_list":
		if (!isset($_GET['pcatalog_id'])){
			require("module/default.php");		
		}
		else {
			require("module/catalog_list.php");
		}
		break;

	case "catalog_edit":
		if (!isset($_GET['catalog_id']) && !isset($_GET['pcatalog_id'])){
			require("module/default.php");		
		}
		else {
			require("module/catalog_edit.php");
		}
		break;		
		
	case "user_manage":
		require("module/user_manage.php");
		break;			
		
	case "group_manage":
		require("module/group_manage.php");
		break;			

	case "privilege_manage":
		require("module/privilege_manage.php");
		break;					
		
	case "link_edit":
		require("module/link_edit.php");
		break;			
		
	case "score":
		require("plugsin/score.php");
		break;			
		
	default:
		require("module/default.php");		
		break;

}

?>