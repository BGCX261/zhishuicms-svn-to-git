<?php
function smarty_function_enum_checkboxes($params, &$smarty1){
	global $smarty;
	global $db;
	global $cfg;
	$tbl_enum_value=$cfg['tbl_enum_value'];
	$tbl_enum=$cfg['tbl_enum'];

	if (empty($params['enum'])) {
		print "required enum";
		return ;
	}
	if (empty($params['name'])) {
		print "required name";
		return ;
	}
				
	if (empty($params['separator'])) {
		$separator="";
	}	
	
	extract($params);

	$sql = "select value,name,discribe from $tbl_enum_value where enumid = (select id from $tbl_enum where name = '$enum')";
	$rs = $db->Execute($sql);
	$selected=explode(',',$selected);
	while ($arr=$rs->FetchRow()){
		  if (in_array($arr['value'],$selected)){
			  echo "<label> 
	  					<input name=\"$name"."_".$arr['value']."\" type=\"checkbox\" value=\"".$arr['value']."\" checked=\"checked\" />".$arr['name']."
	  				</label>
	  				";
		  }
		  else {
			  echo "<label> 
			  			<input name=\"$name"."_".$arr['value']."\" type=\"checkbox\" value=\"".$arr['value']."\"/>".$arr['name']."
	  				</label>
	  				";		  	
		  }
		  echo $separator;
	}
	

}
?>
