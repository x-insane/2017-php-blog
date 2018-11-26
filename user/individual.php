<?php
session_start();
require_once("../lib/ajax.php");
require_once("../lib/testfun.php");
require_once("../lib/mysql.php");
header("Content_Type:application/json;charset=utf-8");
if(!isset($_SESSION["userid"]))
	sendfailure("not_login");
$mysql = new mysql_user;
$userid = $_SESSION["userid"];
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$name = $_POST["name"];
	if(test_name($name) != "name_include")
		sendfailure("wrong_name");
	if($mysql->getnamebyid($userid) != $name)
		sendfailure("服务器拒绝了你的请求：用户名认证失败。建议刷新页面或者联系管理员。");
	$sex = $_POST["sex"];
	$mailbox = $_POST["mailbox"];
	$tel = $_POST["tel"];
	$qq = $_POST["qq"];
	$true_name = $_POST["true_name"];
	if(test_sex($sex) != "sex_ok")
		sendfailure("服务器拒绝了你的请求：性别输入不合法！");
	if($mailbox!=""&&test_mailbox($mailbox)!="mailbox_ok")
		sendfailure("服务器拒绝了你的请求：邮箱输入不合法！");
	if($tel!=""&&test_tel($tel) != "tel_ok")
		sendfailure("服务器拒绝了你的请求：电话号码输入不合法！");
	if($qq!=""&&test_qq($qq) != "qq_ok")
		sendfailure("服务器拒绝了你的请求：QQ号输入不合法！");
	if($true_name!=""&&test_true_name($true_name) != "true_name_ok")
		sendfailure("服务器拒绝了你的请求：真实姓名输入不合法！");
	if($mysql->query("UPDATE user SET sex='$sex',mailbox='$mailbox',tel='$tel',qq='$qq',true_name='$true_name' WHERE id='$userid'"))
		sendok();
	else
		sendfailure("mysql_error");
}
else
{
	if(isset($_GET["request"]))
	{
		if($_GET["request"] == "basedata")
		{
			$re = $mysql->query("SELECT * from user WHERE id='$userid'");
			$rs = $re->fetch_array(MYSQLI_BOTH);
			$result = array("result"=>"ok","name"=>$rs["name"],
					"sex"=>$rs["sex"],"mailbox"=>$rs["mailbox"],
					"tel"=>$rs["tel"],"qq"=>$rs["qq"],
					"true_name"=>$rs["true_name"],
					"ltime"=>$rs["lastlogintime"],
					"rtime"=>$rs["regtime"],
					"applist"=>json_decode($rs["applist"]));
			echo json_encode($result);
			exit();
		}
		else if($_GET["request"] == "securitydata")
		{
			$re = $mysql->query("SELECT * from user WHERE id='$userid'");
			$rs = $re->fetch_array(MYSQLI_BOTH);
			// if($rs["mailbox_bindcode"]=="pass")
			// 	$bind = "bound";
			// else if($rs["mailbox_bindcode"]==null)
			// 	$bind = "no_bind";
			// else
			// 	$bind = "binding";
			$status = $mysql->getcodestatus($userid,"mailbox_bindcode");
			if($status == "pass")
			{
				$result = array("result"=>"ok","mailbox"=>$mysql->getcodecontent($userid,"mailbox_bindcode"),
						"ques"=>$rs["ques"],"bind"=>"bound");
			}
			else if($status == "waiting")
			{
				$result = array("result"=>"ok","mailbox"=>$rs["mailbox"],
						"ques"=>$rs["ques"],"bind"=>"binding");
			}
			else
			{
				$result = array("result"=>"ok","mailbox"=>$rs["mailbox"],
						"ques"=>$rs["ques"],"bind"=>"no_bind");
			}
			echo json_encode($result);
			exit();
		}
	}
}
?>