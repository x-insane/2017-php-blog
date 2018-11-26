<?php
function test_name($name)
{
	$mysql = new mysql_user;
	if(!preg_match("/^[\x80-\xff\x28\x290-9A-Za-z]{1,26}$/",$name))
		return "wrong_name";//用户名$name不合法
	else
	{
		$result = $mysql->query("SELECT name FROM user WHERE name='$name'");
		if($result->fetch_array(MYSQLI_BOTH))
			return "name_include";
		else
			return "name_exclude";
	}
}
function test_sex($sex)
{
	if($sex=="男"||$sex=="女"||$sex=="保密")
		return "sex_ok";
	else
		return "wrong_sex";
}
function test_mailbox($mailbox)
{
	if(preg_match("/^[\w!#$%&'*+\/=?^_`{|}~-]+(?:\.[\w!#$%&'*+\/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/",$mailbox))
		return "mailbox_ok";
	else
		return "wrong_mailbox";
}
function test_qq($qq)
{
	if(preg_match("/^[0-9]{5,10}$/",$qq))
		return "qq_ok";
	else
		return "wrong_qq";
}
function test_tel($tel)
{
	if(preg_match("/^[0-9]{11}$/",$tel))
		return "tel_ok";
	else
		return "wrong_tel";
}
function test_true_name($true_name)
{
	if(preg_match("/^[\x80-\xff]{6,18}$/",$true_name))
		return "true_name_ok";
	else
		return "wrong_true_name";
}
function test_code($code)
{
	$mysql = new mysql_user;
	if(!preg_match("/^[a-zA-Z0-9]{16}$/",$code))
		return "wrong_code";
	else
	{
		$result = $mysql->query("SELECT * FROM invite_code WHERE code='$code'");
		if($result->fetch_array(MYSQLI_BOTH))
			return "code_ok";
		else
			return "wrong_code";
	}
}
function test_url($url)
{
    if(preg_match('/^http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url))
        return true;
    if(preg_match('/^[\w.\/]+$/',$url))
    	return true;
    return false;
}
?>