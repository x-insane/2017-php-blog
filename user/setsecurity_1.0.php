<?php
session_start();
require_once("../lib/ajax.php");
require_once("../lib/mysql.php");
header("Content_Type:application/json;charset=utf-8");
if(!isset($_SESSION["userid"]))
	sendfailure("not_login");
$mysql = new mysql_user;
$userid = $_SESSION["userid"];
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	$ques = nl2br(htmlentities(addslashes($_POST["ques"])));
	$ans = nl2br(htmlentities(addslashes($_POST["ans"])));
	if($ques != $_POST["ques"] || $ques == "")
		sendfailure("invalid_ques");
	if($ans != $_POST["ans"] || $ans == "")
		sendfailure("invalid_ans");
	if($mysql->query("UPDATE user SET ques='$ques',ans='$ans' WHERE id='$userid'"))
		sendmsg("ok");
	else
		sendfailure("mysql_error");
}
else
{
	$re = $mysql->query("SELECT ques FROM user WHERE id='$userid'");
	if($item = $re->fetch_array(MYSQLI_BOTH))
	{
		if($item["ques"] && $item["ques"]!="")
			echo json_encode(["result"=>"ok","ques"=>$item["ques"]]);
		else
			sendfailure("no_ques");
	}
	else
		sendfailure("invalid_user");
}
?>