<?php
session_start();
require_once("../lib/ajax.php");
require_once("../lib/testfun.php");
if(isset($_GET["url"]))
{
	if(test_url($_GET["url"]))
	{
		$_SESSION["url"] = $_GET["url"];
		sendok();
	}
	else
		sendfailure("wrong_url");
}
else
	sendfailure("need_url");
?>