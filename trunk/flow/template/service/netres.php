<?php

$active_only = false;
//$active_only = true;
require_once("../head.php");

$tpl->loadTemplatefile("netres.htm");
$tpl->addBlockfile("head", "head", "head.htm");
$tpl->addBlockfile("tail", "tail", "../tail.htm");
$tpl->touchBlock("head");
$tpl->touchBlock("tail");

//叶首468*60的小广告
$tpl->setVariable("ad1", getAdvertise(2));
//栏目上766*80的大广告
$tpl->setVariable("ad2", getAdvertise(1));



require_once("../tail.php");

?>
