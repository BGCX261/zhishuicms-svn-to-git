<?php
	@require ('inc/global.inc.php');
	@require ("flowcms/lib/editor/editor/filemanager/connectors/php/config.php");

	init_adodb();
	global $cfg, $db;
	$tbl_attachment=$cfg['tbl_attachment'];

	extract($_GET);
	extract($_POST);
	
		$sql = "select id,formername,filename,mime,dt,cache from $tbl_attachment order by id desc";
		$rs = $db->Execute($sql);
		echo "start";
		while ($arr=$rs->FetchRow($rs)){
			$arr['cache']=0;
			if ($arr['cache']==0){
				$dt=$arr['dt'];
				$file_dir=$cfg['AttachmentDir'].date("Y-m",strtotime($attachment['dt']))."/";
				if (!file_exists(Server_MapPath($file_dir.$arr['filename']))) { 
					$file_dir=getFileType($arr['mime']).date("Y-m",strtotime($arr['dt']))."/";
				}
				if (!file_exists(Server_MapPath($file_dir.$arr['filename']))) { 
					$file_dir=$cfg['_AttachmentDir'].date("Y-m",strtotime($arr['dt']))."/";
				}		
		
				if (file_exists(Server_MapPath($file_dir.$arr['filename']))){
					$file_name=$arr['filename'];
					$path=Server_MapPath($file_dir.$file_name);
					$file = fopen($path,"r");
					$filesize = filesize($path);
					$concent= addslashes(fread($file,$filesize));
					fclose($file);
					$id = $arr['id'];
		
					echo $id."#<br>";
					flush ();
					
					$sql ="update $tbl_attachment set cache = 1,size = $filesize where id = $id";
					if (!$db->Execute($sql)){
						echo "fail";
					}
					$concent=bin2hex($concent);
					$sql = "UPDATE tblattachment SET concent='$concent ' where id= $id";
					if (!$db->Execute($sql)){
						echo "fail";
					}
					/*
					$where="id = $id";
					echo $where;
					if ($db->UpdateBlobFile($tbl_attachment,"concent",$path,$where)){
						echo "success";
					}
					else {
						echo "fail,fail".$path;
					}
*/
				}
			}
			else {
					echo "pass:".$arr['id']."<br>";	
			}
	
		}
	


	
	
	
	function getFileType($fileType){
		global $Config;
		if ( strlen( $Config['FileTypesAbsolutePath'][$fileType] ) > 0 )
			return $Config['FileTypesAbsolutePath'][$fileType] ;

		// Map the "UserFiles" path to a local directory.
		return $Config['FileTypesPath'][$fileType]  ;	
	}
	function Server_MapPath( $path )
	{
		// This function is available only for Apache
		if ( function_exists( 'apache_lookup_uri' ) )
		{
			$info = apache_lookup_uri( $path ) ;
			return $info->filename . $info->path_info ;
		}
	
		// This isn't correct but for the moment there's no other solution
		// If this script is under a virtual directory or symlink it will detect the problem and stop
		return GetRootPath() . $path ;
	}
	function output_file($cmd,$file_name,$file_dir,$formername,$mime='application/octet-stream'){
		$path=Server_MapPath($file_dir.$file_name);
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