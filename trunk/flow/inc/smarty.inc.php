<?php
if (defined("in_flow")){
	$const_smartyPath="../smarty/";
}
else {
	$const_smartyPath="smarty/";
}
@require_once($const_smartyPath."libs/Smarty.class.php");
class MySmarty extends Smarty {
	function MySmarty() {
		global $const_smartyPath;
		global $cfg;
		$this->Smarty();
		$this->template_dir = $cfg['smarty_template'];
		$this->compile_dir = $const_smartyPath.'templates_c/';
		$this->config_dir = $const_smartyPath.'configs/';
		$this->cache_dir = $const_smartyPath.'cache/';
		$this->debugging = $cfg['smarty_debug'];
		$this->debugging_ctrl = "NONE";
		$this->compile_id = "flow";
		$this->security = true;
		//$this->plugins_dir = $this->plugins_dir.",".$cfg['flow_basedir']."flowcms/lib/exts/";
		if (defined("in_flow")){
			array_push($this->plugins_dir,"../flowcms/lib/exts");
		}
		else {
			array_push($this->plugins_dir,"../../flowcms/lib/exts");
		}		
		
	}
	function mySetCache(){
		global $cfg;
		$this->caching = $cfg['smarty_cache'];
		$this->cache_lifetime= $cfg['cache_time'];
		$this->assign("indexCache",1);
	}
	function myClearCache(){
		$this->caching = 0 ;
		$this->clear_all_cache();
		$this->assign("indexCache",0);
	}
}

function init_smarty(){
	Global $smarty;
	Global $cfg;
	if (!$smarty) {
		$smarty=new MySmarty();
		$smarty->mySetCache();
	}
}
?>