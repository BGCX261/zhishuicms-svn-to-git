<?php

$active_only = false;
//$active_only = true;
require_once("../head.php");

$tpl->loadTemplatefile("netres.htm");
$tpl->addBlockfile("head", "head", "head.htm");
$tpl->addBlockfile("tail", "tail", "../tail.htm");
$tpl->touchBlock("head");
$tpl->touchBlock("tail");

//Ҷ��468*60��С���
$tpl->setVariable("ad1", getAdvertise(2));
//��Ŀ��766*80�Ĵ���
$tpl->setVariable("ad2", getAdvertise(1));



require_once("../tail.php");

?>
