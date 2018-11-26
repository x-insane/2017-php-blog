<?php
session_start();
require_once("../ini.php");
require_once("../lib/ajax.php");
require_once("../lib/testfun.php");
require_once("../lib/mysql.php");
header("Content_Type:application/json;charset=utf-8");
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$name = $_POST["name"];
	$pwd = $_POST["pwd"];
	$pwd = addslashes($pwd);
	$tname = test_name($name);
	if($tname == "wrong_name")
	{
		sendfailure("登陆失败：用户名不合法！");
		exit();
	}
	else if($tname == "name_exclude")
	{
		sendfailure("登陆失败：没有该用户！");
		exit();
	}
	else if($tname != "name_include")
	{
		sendfailure("登陆失败：不明原因，请联系管理员，代码 LOGIN_TEST_NAME_OUT！");
		exit();
	}
	$mysql = new mysql_user;
	$re = $mysql->query("SELECT pwd,id,pwdwrongtimes FROM user WHERE name='$name'");
	if($item = $re->fetch_array(MYSQLI_BOTH))
	{
		if($item["pwdwrongtimes"] >= 3)
		{
			if(!isset($_POST["vcode"]) || $_POST["vcode"]=="")
				sendfailure("needcode");
			if(!isset($_SESSION["authnum_session"]) || $_SESSION["authnum_session"]!=$_POST["vcode"])
				sendfailure("wrong_vcode");
		}
		if($item["pwd"] == $pwd)
		{
			$_SESSION["userid"] = $item["id"];
			date_default_timezone_set('Etc/GMT-8');
			$now = date('Y-m-d H:i:s',time());
			$mysql->query("UPDATE user SET pwdwrongtimes=0 WHERE name='$name'");
			$mysql->query("UPDATE user SET lastlogintime='$now' WHERE name='$name'");
			if(isset($_SESSION["url"]))
				$url=$_SESSION["url"];
			else
				$url = $webpath."user/individual.html";
			$result = array("result"=>"ok","url"=>$url);
			$json = json_encode($result);
			echo $json;
			exit();
		}
		else
		{
			$mysql->query("UPDATE user SET pwdwrongtimes=pwdwrongtimes+1 WHERE name='$name'");
			sendfailure("密码错误！");
			exit();
		}
	}
	else
	{
		sendfailure("登陆失败：不明原因，请联系管理员，代码 LOGIN_MYSQL_OUT！");
		exit();	
	}
}
?>