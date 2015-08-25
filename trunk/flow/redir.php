<?php
@include_once ('inc/global.inc.php');
init_adodb();
init_smarty();
global $smarty;
global $cfg;
global $db;
if (isset($_GET['refresh']) && refresh()){
		$smarty->myClearCache();
		$cfg['adodb_cache'] = false;
}
$_GET['object_id'] = intval($_GET['object_id']);
if (!empty($_GET['object_id'])){
	$id = $_GET['object_id'];
	$tbl_article=$cfg['tbl_article'];
	$sql = "update $tbl_article set visitcount = visitcount+1 where id = $id";
	$db->Execute($sql);	
}
run();
function run(){
  $catalog_id = intval($_GET['catalog_id']);
  if (!$catalog_id){
    return 0;
  }

  $object_id = intval($_GET['object_id']);
  if ($object_id)
  {
    return run_content($catalog_id, $object_id);
  }
  else
    return run_catalog($catalog_id);
}

function run_content($catalog_id, $object_id)
{
  global $db, $smarty;
 
  $catalog_data = get_catalog_info($catalog_id);
 
  if (!$catalog_data)
  {
    header("HTTP/1.0 404 Not Found");
    return 0;
  }

  $catalog_type = $catalog_data['type'];
  $smarty->assign_by_ref('catalog', $catalog_data);

  switch ($catalog_type)
  {
    case 1:
      run_article_content($catalog_data, $object_id);
      break;
    case 10:
      run_category_content($catalog_data, $object_id);
      break;
    default:
  }
}

 
function run_catalog($catalog_id)
{

  global $db, $smarty;
  $catalog_data = get_catalog_info($catalog_id);
  
  if (!$catalog_data)
  {
    header("HTTP/1.0 404 Not Found");
    print '<html><head><title>Not Found</title></head><body><p>the object you requested not found</p></body></html>';
    return 0;
  }
  if (strlen(trim($catalog_data['url']))!=0){
	echo ("
		<script language='JavaScript'>
			<!--
				window.location.href='".trim($catalog_data['url'])."';
			//-->
		</script>
		");
	exit;
  }
  
  $catalog_type = $catalog_data['type'];
  $smarty->assign_by_ref('catalog', $catalog_data);

  switch ($catalog_type)
  {
    case 1:                                    //普通文章
      run_article_catalog($catalog_data);
      break;
 
    case 3:                                   //投稿 同普通文章
      run_article_catalog($catalog_data);
      break;     
      
    case 10:								//栏目组
      run_category_catalog($catalog_data);
      break;
    default:
  }
}

function run_article_catalog($catalog_data)
{
  global $db, $smarty,$cfg;
  $template_list = get_template($catalog_data['id'], 'template_list');
  $smarty->assign('page_title', $catalog_data['name']);
  $smarty->display($template_list,$_SERVER['QUERY_STRING']);
  if ($cfg['debug']){
  	echo "template".$template_list;
  }
}


function run_category_catalog($catalog_data)
{
  global $conn, $smarty;
  $template_portal = get_template($catalog_data['id'], 'template_portal');
  $smarty->assign('page_title', $catalog_data['name']);
  $smarty->display($template_portal,$_SERVER['QUERY_STRING']);
  if ($cfg['debug']){
  	echo "template".$template_portal;
  }  
}

function run_article_content($catalog_data, $object_id)
{
  global $smarty;
  $template_content = get_template($catalog_data['id'], 'template_content');
  $url = trim(get_article_url($object_id));
  if (strlen($url)!=0){
    echo ("
		<script language='JavaScript'>
			<!--
				window.location.href='$url';
			//-->
		</script>
		");
	exit;
  }
  $smarty->display($template_content,$_SERVER['QUERY_STRING']);
  if ($cfg['debug']){
  	echo "template".$template_content;
  }  
}


function run_category_content($catalog_data, $object_id)
{
  global $smarty;
  $template_content = get_template($catalog_data['id'], 'template_content');
  $smarty->display($template_content,$_SERVER['QUERY_STRING']);
  if ($cfg['debug']){
  	echo "template".$template_content;
  }    
}

function &get_catalog_info($catalog_id)
{
  global $db;
  global $cfg;
  $tbl_columns = $cfg['tbl_columns'];
  $sql = "select * from  $tbl_columns where id=" . $catalog_id;
  if ($cfg['adodb_cache']){
  	$catalog_data = $db->CacheGetRow($sql);
  }
  else {
  	$catalog_data = $db->GetRow($sql);
  }
  return $catalog_data;
}

function get_template($catalog_id, $template_name)
{
  global $db;
  global $cfg;
  $tbl_columns = $cfg['tbl_columns'];
  $data['pcatalog'] = $catalog_id;
  $data['tpl'] = '';
  $sql = "select pcatalog, ". $template_name . " as tpl from  $tbl_columns
          where id=";
  while ((int)$data['pcatalog'] > 0 && !$data['tpl'])
  {
  	if ($cfg['adodb_cache']){
  		$data = $db->CacheGetRow($sql . $data['pcatalog']);
  	}
  	else {
      $data = $db->GetRow($sql . $data['pcatalog']);
  	}
  }
  return $data['tpl'];
}

function get_article_url($object_id){
	global $db;
  	global $cfg;
  	$tbl_article=$cfg['tbl_article'];
  	$sql = "select link from $tbl_article where id = $object_id";
  	return $db->GetOne($sql);
}
?>