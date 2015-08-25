<?php
function smarty_function_enum_value($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_enum_value=$cfg['tbl_enum_value'];
	$tbl_enum=$cfg['tbl_enum'];

	if (empty($params['enum'])) {
		print "required enum";
		return ;
	}
	$enum=$params['enum'];
	if (isset($params['name'])) {
		$name=$params['name'];
		$sql = "select value from $tbl_enum_value where enumid = (select id from $tbl_enum where name = '$enum') and name='$name'";
		
	}
	else if (isset($params['value'])){
		$value=$params['value'];
		$sql = "select name from $tbl_enum_value where enumid = (select id from $tbl_enum where name = '$enum') and value='$value'";
			}
	echo ($db->GetOne($sql));	

}
?>
