<?php
session_start();
require_once("../ini.php");
require_once("../lib/ajax.php");
require_once("../lib/testfun.php");
require_once("../lib/mysql.php");
require_once("../lib/rand.php");
require_once("../lib/mail.php");
$mysql = new mysql_user;
if($_SERVER["REQUEST_METHOD"] != "POST")
{
	if(isset($_SESSION["userid"]) && isset($_GET["request"]))
	{
		if($_GET["request"] == "unbind")
		{
			$mysql->unsetcode($_SESSION["userid"],"mailbox_bindcode");
			sendok();
		}
	}
	if(isset($_GET["token"]) && isset($_GET["id"]))
	{
		$uid = addslashes($_GET["id"]);
		$token = $_GET["token"];
		if($mysql->getcodestatus($uid,"mailbox_bindcode") == "pass")
		{
			header("Location:bindmailbox.html?result=already_verified");
			exit();
		}
		$result = $mysql->verifycode($uid,"mailbox_bindcode",$token,true);
		if($result == "pass")
		{
			header("Location:bindmailbox.html?result=success");
			exit();
		}
		header("Location:bindmailbox.html?result=$result");
		exit();
	}
	header("Location:bindmailbox.html?result=failure");
	exit();
}
header("Content_Type:application/json;charset=utf-8");
if(!isset($_SESSION["userid"]))
	sendfailure("not_login");
$uid = $_SESSION["userid"];
$mailbox = $_POST["mailbox"];
if($mailbox == "")
	sendfailure("null_mailbox");
if(test_mailbox($mailbox) != "mailbox_ok")
	sendfailure("wrong_mailbox");
if($mysql->getcodestatus($uid,"mailbox_bindcode") == "pass")
	sendfailure("bound");
$re = $mysql->query("SELECT * from verifylist WHERE content='$mailbox' AND pass=TRUE AND id!='$uid'");
$rs = $re->fetch_array(MYSQLI_BOTH);
if($rs)
	sendfailure("mailbox_has_been_bound");
if(!$mysql->query("UPDATE user SET mailbox='$mailbox' WHERE id='$uid'"))
	sendfailure("mysql_error");
$bindcode = getRandString(32);
$re = $mysql->setcode("mailbox_bindcode",$bindcode,$mailbox);
if($re != "ok")
	sendfailure($re);
$link = $webpath."user/bindmailbox.php?id=$uid&token=$bindcode";
if(sendmail($mailbox,"绑定邮箱","您正在为希望工作室的用户 ".$mysql->getnamebyid($uid)." 绑定邮箱，如果是您本人操作，请点击链接：<a href='".$link."'>$link</a>"))
	sendok();
else
{
	$mysql->unsetcode($uid,"mailbox_bindcode");
	sendfailure("发送邮件失败！");
}
?>