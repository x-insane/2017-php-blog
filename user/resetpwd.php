<?php
session_start();
require_once("../lib/ajax.php");
require_once("../lib/mysql.php");
header("Content_Type:application/json;charset=utf-8");
if(!isset($_SESSION["userid"]))
	sendfailure("not_login");
$mysql = new mysql_user;
$id = $_SESSION["userid"];
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(!isset($_SESSION["authnum_session"]) || !isset($_POST["vcode"]) ||
		$_SESSION["authnum_session"] != $_POST["vcode"])
	{
		sendfailure("wrong_vcode");
	}
	$oldpwd = $_POST["oldpwd"];
	$oldpwd = addslashes($oldpwd);
	$newpwd = $_POST["newpwd"];
	$newpwd = addslashes($newpwd);
	$re = $mysql->query("SELECT pwd FROM user WHERE id='$id'");
	if($item = $re->fetch_array(MYSQLI_BOTH))
	{
		if($item["pwd"] == $oldpwd)
		{
			$mysql->query("UPDATE user SET pwd='$newpwd' WHERE id='$id'");
			if(isset($_SESSION["url"]))
			{
				$url=$_SESSION["url"];
				unset($_SESSION["url"]);
			}
			else
				$url = "";
			$result = array("result"=>"ok","url"=>$url);
			$json = json_encode($result);
			echo $json;
			exit();
		}
		else
		{
			sendfailure("wrong_pwd");
		}
	}
}
?>