<?php
require_once( dirname(__FILE__) . "/../../config.php");
require_once( dirname(__FILE__) . "/../../functions.php");
header("Content_Type:text/html;charset=utf-8");
global $level,$blog_db,$the_cat;
if(isset($_POST["redirect"]))
	$redirect = $_POST["redirect"];
else
	$redirect = "radio";
function get_cat_data($checkedid=null)
{
	global $redirect,$the_cat;
	if(!$checkedid)
		$checkedid = 1;
	$the_cat->get($redirect,0,$checkedid,1);
}
function add_cat_error($info,$checkedid=null)
{
	get_cat_data($checkedid);
	?><p id="cat-error-tip" style="color: red"><?php echo $info; ?></p><?php
}
$parent = $_POST["parent"];
$name = addslashes($_POST["name"]);
$old = $_POST["checked"];
if(!$the_cat->is($old))
	$old = 1;
if(isset($level) && $level & 3)
{
	if($parent != 0 && !$the_cat->is($parent))
	{
		add_cat_error("上级目录错误",$old);
		exit();
	}
	$re = $the_cat->add($parent,$name);
	if(is_numeric($re))
		get_cat_data($re);
	else
		add_cat_error($re,$old);
}
else
	add_cat_error("权限不足",$old);