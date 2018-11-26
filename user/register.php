<?php
session_start();
require_once("../ini.php");
require_once("../lib/ajax.php");
require_once("../lib/testfun.php");
require_once("../lib/mysql.php");
header("Content_Type:application/json;charset=utf-8");
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if($_POST["vcode"] != $_SESSION['authnum_session'])
	{
		sendfailure("验证码错误！");
		exit();
	}
	$name = $_POST["name"];
	if(test_name($name) == "wrong_name")
		sendfailure("注册失败：用户名非法！");
	if(test_name($name) == "name_include")
		sendfailure("注册失败：用户名已被注册！");
	$pwd = $_POST["pwd"];
	$pwd = addslashes($pwd);
	$sex = $_POST["sex"];
	if(!($sex=="男"||$sex=="女"||$sex=="保密"))
		$sex = "保密";
	$mailbox = $_POST["mailbox"];
	if($mailbox != "")
	{
		if(test_mailbox($mailbox) != "mailbox_ok")
			sendfailure("注册失败：邮箱错误！");
	}
	$qq = $_POST["qq"];
	if($qq != "")
	{
		if(test_qq($qq) != "qq_ok")
			sendfailure("注册失败：QQ号错误！");	
	}
	$tel = $_POST["tel"];
	if($tel != "")
	{
		if(test_tel($tel) != "tel_ok")
			sendfailure("注册失败：手机号错误！");
	}
	$ques = $_POST["ques"];
	$ques = addslashes($ques);
	$ans = $_POST["ans"];
	$ans = addslashes($ans);
	$true_name = $_POST["true_name"];
	if($true_name != "")
	{
		if(test_true_name($true_name) != "true_name_ok")
			sendfailure("注册失败：真实姓名错误！");
	}
	$invite_code = $_POST["invite_code"];
	if($invite_code != "")
	{
		if(test_code($invite_code) != "code_ok")
			sendfailure("注册失败：邀请码错误！");
	}
	if(!isset($_SESSION['authnum_session']))
	{
		sendfailure("不明错误！");
		exit();
	}
	date_default_timezone_set('Etc/GMT-8');
	$now = date('Y-m-d H:i:s',time());
	$mysql = new mysql_user;
	if($mysql->query("INSERT INTO
		user (name,pwd,pwdwrongtimes,level,sex,mailbox,tel,qq,ques,ans,applist,true_name,invite_code,lastlogintime)
		VALUES('$name','$pwd',0,'normal','$sex','$mailbox','$tel','$qq','$ques','$ans','{}','$true_name','$invite_code','$now')"))
	{
		$result = $mysql->query("SELECT id FROM user WHERE name='$name'");
		$item = $result->fetch_array(MYSQLI_BOTH);
		$id = $item["id"];
		$_SESSION["userid"] = $id;
		if(isset($_SESSION["url"]))
			$url=$_SESSION["url"];
		else
			$url = $webpath."message/";
		$result = array("result"=>"ok","id"=>"$id","url"=>$url);
		$json = json_encode($result);
		echo $json;
	}
	else
	{
		sendfailure("注册失败：数据库错误！");
	}
}
else
{
	if(isset($_GET["request"]))
	{
		$request = $_GET["request"];
		if($request == "test_name")
		{
			if(isset($_GET["name"]))
			{
				$result = array("result"=>test_name($_GET["name"]));
				$json = json_encode($result);
				echo $json;
			}
		}
		elseif ($request == "test_code")
		{
			if(isset($_GET["code"]))
			{
				$result = array("result"=>test_code($_GET["code"]));
				$json = json_encode($result);
				echo $json;
			}
		}
	}
}
?>