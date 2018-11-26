<?php
require_once( dirname(__FILE__) . "/../../config.php");
require_once( dirname(__FILE__) . "/../../functions.php");
header("Content_Type:application/json;charset=utf-8");
global $the_cat;
if(!$the_cat->is($_GET["cid"]))
	echo json_encode(array("result"=>"wrong_id"));
if($the_cat->is_empty($_GET["cid"]))
	echo json_encode(array("result"=>"ok"));
else
	echo json_encode(array("result"=>"not_empty"));