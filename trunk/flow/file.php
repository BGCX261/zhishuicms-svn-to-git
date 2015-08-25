<?php
	@require ('inc/global.inc.php');
	@require ("flowcms/lib/editor/editor/filemanager/connectors/php/config.php");

	init_adodb();
	global $cfg, $db;
	$tbl_attachment=$cfg['tbl_attachment'];
	if (!isset($_GET['cmd']) && !isset($_POST['cmd']) ){
		$cmd="preview";
	}
	if (!isset($_GET['id']) && !isset($_POST['id'])){
		exit;
	}
	extract($_GET);
	extract($_POST);

	
	
	if ($cmd=="preview" || $cmd=="download"){
		$id = intval($id);
		$sql = "select formername,filename,mime,dt from $tbl_attachment where id = $id";
		$attachment = $db->GetRow($sql);
		if (!$attachment){
			alert("文件不存在");
			exit;
		}
		$dt=$attachment['dt'];
		$file_dir=$cfg['AttachmentDir'].date("Y-m",strtotime($attachment['dt']))."/";
		//if (!file_exists(Server_MapPath($file_dir.$attachment['filename']))) { 
		//	$file_dir=getFileType($attachment['mime']).date("Y-m",strtotime($attachment['dt']))."/";
		//}
		//if (!file_exists(Server_MapPath($file_dir.$attachment['filename']))) { 
		//	$file_dir=$cfg['_AttachmentDir'].date("Y-m",strtotime($attachment['dt']))."/";
		//}		

		if (strlen(trim($attachment['filename']))!=0){
			output_file($cmd,$attachment['filename'],$file_dir,$attachment['formername'],$attachment['mime_type']);
		}
		else {
			output_file($cmd,$attachment['filename'],$file_dir,$attachment['formername']);
		}
	}

	function output_file($cmd,$file_name,$file_dir,$formername,$mime='application/octet-stream'){
		$path=$file_dir.$file_name;
		if (!file_exists($path)) { 
			echo("文件不存在");
			exit;
		}
		else {
			$file = fopen($path,"r");
			$filesize = filesize($path);
			Header("Content-type: $mime");
			Header("Accept-Ranges: bytes");
			Header("Accept-Length: ".$filesize);
			if ($cmd=="download"){
				Header("Content-Disposition: attachment; filename=" . $formername);
			}
			echo fread($file,$filesize);
			fclose($file);
		} 
	}
	
?>