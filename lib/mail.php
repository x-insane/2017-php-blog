<?php
function sendmail($to,$subject,$content,$from="admin@mail.gotohope.cn")
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/mail/class.smtp.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/mail/class.phpmailer.php");
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->SMTPAuth=true;
	$mail->Host = 'smtp.qq.com';
	$mail->SMTPSecure = 'ssl';
	$mail->Port = 465;
	$mail->Helo = 'Gotohope.cn smtp.qq.com Server';
	$mail->Hostname = 'mail.gotohope.cn';
	$mail->CharSet = 'UTF-8';
	$mail->FromName = '希望工作室';
	$mail->Username ='1432337579';
	$mail->Password = 'mmlztdfizcxjhebc';
	$mail->From = $from;
	$mail->isHTML(true);
	$mail->addAddress($to,$to);
	$mail->Subject = $subject;
	$mail->Body = $content;
	return $mail->send();
}
?>