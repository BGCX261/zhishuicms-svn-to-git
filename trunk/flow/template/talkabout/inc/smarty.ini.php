<?php
$const_smartyPath="smarty/";
require_once($const_smartyPath."libs/Smarty.class.php");
class MySmarty extends Smarty {
	function MySmarty() {
		global $const_smartyPath;
		$this->Smarty();
		$this->template_dir = $const_smartyPath.'templates/';
		$this->compile_dir = $const_smartyPath.'templates_c/';
		$this->config_dir = $const_smartyPath.'configs/';
		$this->cache_dir = $const_smartyPath.'cache/';
	}
	function mySetCache(){
		$this->caching = 1;
		$this->cache_lifetime= 300;
		$this->assign("indexCache",1);
	}
	function myClearCache(){
		$this->caching = 0 ;
		$this->clear_all_cache();
		$this->assign("indexCache",0);
	}
}
?>