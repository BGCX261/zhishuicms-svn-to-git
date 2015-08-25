<?php
function smarty_function_enum_options($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_enum_value=$cfg['tbl_enum_value'];
	$tbl_enum=$cfg['tbl_enum'];

	if (empty($params['enum'])) {
		print "required enum";
		return ;
	}
	if (empty($params['separator'])) {
		$separator="";
	}				
	extract($params);

	$sql = "select value,name,discribe from $tbl_enum_value where enumid = (select id from $tbl_enum where name = '$enum')";
	$rs = $db->Execute($sql);
	while ($arr=$rs->FetchRow()){
		  if ($selected==$arr['value']){
			  echo "<label> 
	  					<option value=\"".$arr['value']."\" selected=\"selected\">".$arr['name']."</option>
	  				</label>
	  				";
		  }
		  else {
			  echo "<label> 
			  			<option value=\"".$arr['value']."\">".$arr['name']."</option>
	  				</label>
	  				";		  	
		  }
		  echo $separator;
	}
	

}
?>
