<?php

	@session_cache_expire(2160);
	Global $cfg;
	Global $object_id;
	$cfg=array();
	$cfg['basetitle'] = "知水新闻发布系统" ;
	$cfg['db_host'] = "localhost";
	//$cfg['db_host'] = "10.71.98.105";
	$cfg['db_dbname'] = "qsc";
	$cfg['db_username'] = "flow";
	$cfg['db_password'] = "flow";
	$cfg['tbp'] = "tbl";
	
	$cfg['flow_basedir'] = '/flow/';             //程序根目录url
	$cfg['flow_local_basedir'] = "D:\\work\\webroot\\flow\\";             //程序本地根目录
	
	
	$cfg['tbl_columns']=$cfg['tbp']."columns";
	$cfg['tbl_article']=$cfg['tbp']."article";
	$cfg['tbl_articlelink']=$cfg['tbp']."articlelink";
	$cfg['tbl_customise']=$cfg['tbp']."customise";
	$cfg['tbl_link']=$cfg['tbp']."link";
	$cfg['tbl_attachment']= $cfg['tbp']."attachment";
	$cfg['tbl_adodbseq'] = "adodbseq";
	$cfg['tbl_group'] = $cfg['tbp']."group";
	$cfg['tbl_privilege'] = $cfg['tbp']."privilege";
	$cfg['tbl_enum'] = $cfg['tbp']."enum";
	$cfg['tbl_enum_value'] = $cfg['tbp']."enum_value";
	$cfg['tbl_user'] = $cfg['tbp']."login";
	$cfg['tbl_usergroup'] = $cfg['tbp']."usergroup";
	$cfg['tbl_history'] = $cfg['tbp']."history";
	$cfg['tbl_subjectarticle'] = $cfg['tbp']."subjectarticle";
	$cfg['tbl_subject'] = $cfg['tbp']."subject";
	$cfg['tbl_keywords'] = $cfg['tbp']."keywords";
	$cfg['tbl_object_keywords'] = $cfg['tbp']."object_keywords";
	$cfg['tbl_commnet'] = $cfg['tbp']."commnet";
	$cfg['tbl_iplist'] = $cfg['tbp']."iplist";
	$cfg['tbl_refresh_time'] = $cfg['tbp']."refresh_time";
	
	
	$debug_switch=false;
	
	$cfg['debug'] = $debug_switch || false;                      //显示smarty模板缺失
	$cfg['adodb_debug'] = $debug_switch || false;                //adodb调试信息
	$cfg['smarty_debug'] = $debug_switch || false;			    //smarty调试信息
	$cfg['adodb_error_reporting'] = 0;          //参数为0不显示任何错误;1显示错误，并退出
	$cfg['mysql_charset'] = "gbk";
	$cfg['refresh_time'] = 60;                       //相邻刷新间隔时间  单位s
	
	
	//begin of fckeditor-filemanager
	$cfg['UpLoadFile_Enable'] = true;            //允许上传文件
	$cfg['UserFilesPath'] = $cfg['flow_basedir']."Flow-Uploads/"; //上传文件路径（url)相对于根目录的路径
	$cfg['UserFilesAbsolutePath'] = '';            //上传文件路径（服务器本地路径)
	//end of fckeditor-filemanager
	
	
	
	
	$cfg['AttachmentDir'] = $cfg['flow_local_basedir'].'Flow-Uploads/';    //上传文件路径（服务器本地文件路径）服务器文件系统绝对路径或相对于根目录
/*
	if (defined("in_flow")){
		$cfg['_AttachmentDir'] = '../'.$cfg['AttachmentDir'];
	}
	else {
		$cfg['_AttachmentDir'] = $cfg['AttachmentDir'] ;
	}
*/
	//$cfg['AttachmentDir'] = $cfg['flow_basedir'].$cfg['AttachmentDir'];
	
	
	$include_flag = 1023;
	
	if (defined("in_flow")){
		$cfg['smarty_cache'] = 0;         		    //0,1,2;0不缓存。1，2缓存；
		$cfg['smarty_template']='template/';        //smarty模板路径
		
		
		$cfg['adodb_cache'] = false;                 //使用数据库缓存
		$cfg['cache_time'] = 0;				   		 //smarty及数据库缓存时间
		$cfg['adodb_cache_dir'] = "../adodb-cache";  //adodb 缓存目录，要求有写权限
	}
	
	
	
	else {
		$cfg['smarty_cache'] = 1;         		    //0,1,2;0不缓存。1，2缓存；
		$cfg['smarty_template']='template/';        //smarty模板路径
		
		
		$cfg['adodb_cache'] = true;                 //使用数据库缓存
		$cfg['cache_time'] = 36000;			    //smarty及数据库缓存时间 60*60 缓存1小时
		$cfg['adodb_cache_dir'] = "./adodb-cache";
	}

?>
