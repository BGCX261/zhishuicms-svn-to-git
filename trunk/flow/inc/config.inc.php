<?php

	@session_cache_expire(2160);
	Global $cfg;
	Global $object_id;
	$cfg=array();
	$cfg['basetitle'] = "֪ˮ���ŷ���ϵͳ" ;
	$cfg['db_host'] = "localhost";
	//$cfg['db_host'] = "10.71.98.105";
	$cfg['db_dbname'] = "qsc";
	$cfg['db_username'] = "flow";
	$cfg['db_password'] = "flow";
	$cfg['tbp'] = "tbl";
	
	$cfg['flow_basedir'] = '/flow/';             //�����Ŀ¼url
	$cfg['flow_local_basedir'] = "D:\\work\\webroot\\flow\\";             //���򱾵ظ�Ŀ¼
	
	
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
	
	$cfg['debug'] = $debug_switch || false;                      //��ʾsmartyģ��ȱʧ
	$cfg['adodb_debug'] = $debug_switch || false;                //adodb������Ϣ
	$cfg['smarty_debug'] = $debug_switch || false;			    //smarty������Ϣ
	$cfg['adodb_error_reporting'] = 0;          //����Ϊ0����ʾ�κδ���;1��ʾ���󣬲��˳�
	$cfg['mysql_charset'] = "gbk";
	$cfg['refresh_time'] = 60;                       //����ˢ�¼��ʱ��  ��λs
	
	
	//begin of fckeditor-filemanager
	$cfg['UpLoadFile_Enable'] = true;            //�����ϴ��ļ�
	$cfg['UserFilesPath'] = $cfg['flow_basedir']."Flow-Uploads/"; //�ϴ��ļ�·����url)����ڸ�Ŀ¼��·��
	$cfg['UserFilesAbsolutePath'] = '';            //�ϴ��ļ�·��������������·��)
	//end of fckeditor-filemanager
	
	
	
	
	$cfg['AttachmentDir'] = $cfg['flow_local_basedir'].'Flow-Uploads/';    //�ϴ��ļ�·���������������ļ�·�����������ļ�ϵͳ����·��������ڸ�Ŀ¼
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
		$cfg['smarty_cache'] = 0;         		    //0,1,2;0�����档1��2���棻
		$cfg['smarty_template']='template/';        //smartyģ��·��
		
		
		$cfg['adodb_cache'] = false;                 //ʹ�����ݿ⻺��
		$cfg['cache_time'] = 0;				   		 //smarty�����ݿ⻺��ʱ��
		$cfg['adodb_cache_dir'] = "../adodb-cache";  //adodb ����Ŀ¼��Ҫ����дȨ��
	}
	
	
	
	else {
		$cfg['smarty_cache'] = 1;         		    //0,1,2;0�����档1��2���棻
		$cfg['smarty_template']='template/';        //smartyģ��·��
		
		
		$cfg['adodb_cache'] = true;                 //ʹ�����ݿ⻺��
		$cfg['cache_time'] = 36000;			    //smarty�����ݿ⻺��ʱ�� 60*60 ����1Сʱ
		$cfg['adodb_cache_dir'] = "./adodb-cache";
	}

?>