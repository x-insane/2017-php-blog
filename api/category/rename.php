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
$cid = $_POST["cid"];
$name = addslashes($_POST["name"]);
if($the_cat->is($cid))
{
	$re = $the_cat->rename($cid,$name);
	if($re != null)
		echo "<p id=\"cat-error-tip\" style=\"color: red\">$re</p>";
}
$the_cat->get("manage",0,1,1);