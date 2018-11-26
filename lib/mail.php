<?php
function sendmail($to,$subject,$content,$from="admin@mail.gotohope.cn")
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/mail/class.smtp.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/mail/class.phpmailer.php");
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->SMTPAuth=true;
	$mail->Host = 'smtp.qq.com'; // 使用QQ邮箱
	$mail->SMTPSecure = 'ssl';
	$mail->Port = 465;
	$mail->Helo = 'smtp.qq.com Server';
	$mail->Hostname = ''; // 要显示的邮箱地址
	$mail->CharSet = 'UTF-8';
	$mail->FromName = ''; // 昵称
	$mail->Username =''; // 用户名
	$mail->Password = ''; // 密码或授权码
	$mail->From = $from;
	$mail->isHTML(true);
	$mail->addAddress($to,$to);
	$mail->Subject = $subject;
	$mail->Body = $content;
	return $mail->send();
}
