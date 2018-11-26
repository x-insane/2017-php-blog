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
$cid = isset($_POST["cid"]) ? $_POST["cid"] : $_GET["cid"];
if($the_cat->is($cid))
{
	if(isset($_GET["force"]) || isset($_POST["force"]))
		$the_cat->del($cid,true);
	else
		$the_cat->del($cid);
	$the_cat->get("manage",0,1,1);
}
else
	$the_cat->get("manage",0,1,1);