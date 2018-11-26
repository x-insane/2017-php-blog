<?php
require_once( dirname(__FILE__) . "/../../config.php");
require_once( dirname(__FILE__) . "/../../functions.php");
header("Content_Type:text/html;charset=utf-8");
global $the_cat;
if(isset($_POST["cid"]) && $the_cat->is($_POST["cid"]))
	$the_cat->get("option",0,1,1,$_POST["cid"]);
else if(isset($_GET["cid"]) && $the_cat->is($_GET["cid"]))
	$the_cat->get("option",0,1,1,$_GET["cid"]);
else
	$the_cat->getoption();