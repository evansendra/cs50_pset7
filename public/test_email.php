<?php

require_once 'PHPMailer/class.phpmailer.php';
require("../includes/config.php");

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 2; // enable debuggin info

$mail->SMTPAuth = true;
$mail->SMTPSecure = "tls";
$mail->Host = "smtp.gmail.com";
$mail->Port = 587;

$mail->Username = "public@evansendra.com";
$mail->Password = "32297112693192";

$mail->SetFrom("public@evansendra.com");

$mail->AddAddress("code@evansendra.com");

$mail->Subject = "CS50 Email Test";

$mail->Body = "Hello, world";

if ( $mail->Send() )
{
	echo "Mail sent!";
}
else
{
	apologize("Message not sent!");
}

?>