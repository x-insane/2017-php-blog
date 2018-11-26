<?php
function sendmsg($tip)
{
	$result = array("result"=>$tip);
	$json = json_encode($result);
	echo $json;
	exit();
}
function sendfailure($tip)
{
	sendmsg($tip);
}
function sendok()
{
	sendmsg("ok");
}
?>