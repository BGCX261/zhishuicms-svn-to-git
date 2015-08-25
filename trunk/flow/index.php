<?php
@include_once ('inc/global.inc.php');
init_adodb();
init_smarty();
global $smarty;
global $cfg;

if (isset($_GET['refresh'])){
		$smarty->myClearCache();
		$cfg['adodb_cache'] = false;
}
$smarty->display('home/index.html');

?>