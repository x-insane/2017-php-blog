<?php
require_once( dirname(__FILE__) . "/../../config.php");
require_once( dirname(__FILE__) . "/../../functions.php");
header("Content_Type:text/html;charset=utf-8");
global $the_cat,$level;
if(isset($level))
{
	if(($level & 7) == 0)
		exit();
}
else
{
	exit();
}
$cid = isset($_POST["cid"])? $_POST["cid"] : $_GET["cid"];
$parent = isset($_POST["parent"])? $_POST["parent"] : $_GET["parent"];
if($the_cat->is($cid) && ($parent==0 || $the_cat->is($parent)))
	$the_cat->setparent($cid,$parent);
$the_cat->get("manage",0,1,1);