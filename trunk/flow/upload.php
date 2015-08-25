<form enctype="multipart/form-data" method="post" action="flowcms/lib/editor/editor/filemanager/connectors/php/connector.php?Type=Image&Command=FileUpload&CurrentFolder=%2F">
	<input type="file" name="file" id="file"/><input type="submit" value="ÉÏ´«"/>
</form>
<?php
//require ("flowcms/sys/fileupload/connector.php");

require ("flowcms/lib/editor/editor/filemanager/connectors/php/connector.php");

print_r($_FILES);



?>