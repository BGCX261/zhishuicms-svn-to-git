<?php 
    @include('adodb/adodb-exceptions.inc.php'); 
    @include('adodb/adodb.inc.php'); 
    @include('adodb/tohtml.inc.php'); 
    @include('adodb/adodb-pager.inc.php');  //分页函数

    function init_adodb(){
    	
	    global $db ;
	    global $cfg;
	    global $ADODB_CACHE_DIR;    
	    error_reporting($cfg['adodb_error_reporting']);      
	    $ADODB_CACHE_DIR = $cfg['adodb_cache_dir'];
	    $db = ADONewConnection("mysqli"); 
	    $db->debug = $cfg['adodb_debug']; 
	    $db->PConnect($cfg['db_host'], $cfg['db_username'], $cfg['db_password'], $cfg['db_dbname']);
	    
	    $db->cacheSecs = $cfg['cache_time'];
	    $db->Execute('set names '.$cfg['mysql_charset']);
	    //$db->Execute('SET max_sort_length = 2000000');
	    
    }
    function db_error($msg="数据库出错<br>",$sql=""){
    	echo $msg;
    	echo $sql."<br>";
    	exit;
    }
?>