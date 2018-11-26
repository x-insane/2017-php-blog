<?php
session_start();
require_once("../ini.php");
require_once("../lib/ajax.php");
require_once("../lib/rand.php");
require_once("../lib/mysql.php");
require_once("../lib/mail.php");
header("Content_Type:application/json;charset=utf-8");
$mysql = new mysql_user;
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(!isset($_SESSION["reinitpwd_userid"]))
		sendfailure("invalid_request");
	if(!isset($_POST["method"]))
		$method = "setpwd";
	$userid = $_SESSION["reinitpwd_userid"];
	$method = $_POST["method"];
	if($method == "ques_ans")
	{
		$ans = $_POST["ans"];
		$re = $mysql->query("SELECT ans FROM user WHERE id='$userid'");
		if($item = $re->fetch_array(MYSQLI_BOTH))
		{
			if($item["ans"] == $ans)
			{
				$_SESSION["reinitpwd_verified"] = true;
				sendmsg("ok");
			}
			else
				sendfailure("wrong_ans");
		}
		else
			sendfailure("mysql_error");
	}
	else if($method == "mailbox")
	{
		// $re = $mysql->query("SELECT mailbox FROM user WHERE id='$userid'");
		// $item = $re->fetch_array(MYSQLI_BOTH);
		$mailbox = $mysql->getcodecontent($userid,"mailbox_bindcode");
		if($mailbox)
		{
			$code = getRandString(32);
			$link = $webpath."user/reinitpwd.php?id=$userid&token=$code";
			if(sendmail($mailbox,"找回密码","您正在为希望工作室的用户 ".$mysql->getnamebyid($userid)." 重置密码，如果是您本人操作，请点击链接：<a href='".$link."'>$link</a>"))
			{
				// $re = $mysql->query("UPDATE user SET mailbox_verifycode='$code' WHERE id='$userid'");
				$mysql->setcode("reinitpwd",$code,null,$userid);
				unset($_SESSION["reinitpwd_userid"]);
				sendmsg("ok");
			}
			else
				sendfailure("sendmail_failure");
		}
	}
	else if($method == "setpwd")
	{
		if(isset($_SESSION["reinitpwd_verified"]))
		{
			unset($_SESSION["reinitpwd_userid"]);
			unset($_SESSION["reinitpwd_verified"]);
			$newpwd = addslashes($_POST["newpwd"]);
			if($mysql->query("UPDATE user SET pwd='$newpwd' WHERE id='$userid'"))
				sendmsg("ok");
			else
				sendfailure("mysql_error");
		}
		$newpwd = addslashes($_POST["newpwd"]);
		$uid = $_SESSION["reinitpwd_userid"];
		unset($_SESSION["reinitpwd_userid"]);
		unset($_SESSION["reinitpwd_verified"]);
		if($mysql->query("UPDATE user SET pwd='$newpwd' WHERE id=$uid"))
			sendok();
		else
			sendfailure("mysql_error");
	}
}
else
{
	if(isset($_GET["token"]) && isset($_GET["id"]))
	{
		$id = addslashes($_GET["id"]);
		// $re = $mysql->query("SELECT mailbox_verifycode FROM user WHERE id='$id'");
		// $item = $re->fetch_array(MYSQLI_BOTH);
		$code = $mysql->getcode($id,"reinitpwd");
		if($_GET["token"]==$code)
		{
			// if($mysql->query("UPDATE user SET mailbox_verifycode=NULL WHERE id='$id'"))
			// {
			$mysql->unsetcode($id,"reinitpwd");
				$_SESSION["reinitpwd_userid"] = $id;
				$_SESSION["reinitpwd_verified"] = true;
				header("Location:resetpwd.html");
				exit();
			// }
		}
		header("Location:resetpwd.html?statu=5");
		exit();
	}
	if(!isset($_GET["request"]))
		sendfailure("no_request");
	$request = $_GET["request"];
	if($request == "getstatu")
	{
		if(isset($_SESSION["reinitpwd_userid"]))
		{
			$id = $_SESSION["reinitpwd_userid"];
			$re = $mysql->query("SELECT ques,name FROM user WHERE id='$id'");
			$item = $re->fetch_array(MYSQLI_BOTH);
			$mailbox = $mysql->getcodecontent($id,"mailbox_bindcode");
			if($mailbox && $item)
			{
				$result = array("mailbox"=>$mailbox,"ques"=>$item["ques"],"name"=>$item["name"]);
				if(isset($_SESSION["reinitpwd_verified"]))
					$result["result"]="set password";
				else
					$result["result"]="need method";
				echo json_encode($result);
			}
		}
		else
			sendmsg("prepare");
			
	}
	else if($request == "binduser")
	{
		if(!isset($_SESSION["authnum_session"]) ||
			$_SESSION["authnum_session"] != $_GET["vcode"])
		{
			sendfailure("wrong_vcode");
		}
		$name = addslashes($_GET["name"]);
		$uid = $mysql->getidbyname($name);
		if(!$uid)
			sendfailure("name_exclude");
		$_SESSION["reinitpwd_userid"] = $uid;
		sendmsg("ok");
	}
	else if($request == "unbinduser")
	{
		unset($_SESSION["reinitpwd_userid"]);
		unset($_SESSION["reinitpwd_verified"]);
		sendmsg("ok");
	}
	else
		sendfailure("undefined_request");
}
?>