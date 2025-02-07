<?php

//include_once($_SERVER["DOCUMENT_ROOT"] . '/Common/auth_token.php');
	
/*if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
	//echo "called directly";
	$token = decodeToken();
	if (is_null($token)){
		send404();
	}	
} else {
	//echo "included/required";
	$called_from = basename($_SERVER["SCRIPT_FILENAME"]);
	$callers = ['alert_mail.php','report_mail.php'];

	if (in_array($called_from, $callers) == FALSE){
		send404();
	}
}*/




//include_once($_SERVER["DOCUMENT_ROOT"] . '/Common/config.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';


function enviar($address = "", $subject = "", $body = "")
{
	try {
		$mail = new PHPMailer(true);
		$mail->CharSet = 'UTF-8';
		//$mail->SMTPDebug = 3;
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls';
		//$mail->AuthType = 'PLAIN';
		$mail->SMTPOptions = [
		'ssl' => [
			'verify_peer' => true,
			'verify_peer_name' => false,
			'allow_self_signed' => false
			]
		];

		$mail->Username = SMTP_USER;
		$mail->Password = SMTP_PASS;
		$mail->From = $address;
		$mail->FromName = 'infoNAME'; //Modificalo
		$mail->IsHTML(true);

		$mail->AddAddress("myadressrecive@gmail.com"); //Modificalo
		$mail->Subject = $subject;

		// if(!empty($conCopia)){
			// $mail->AddAddress($conCopia);
		// }

		
		$mail->Body = $body;
		$status = $mail->Send();
		return $status;
		
	} catch (phpmailerException $e) {	
		//echo $e->errorMessage(); //Pretty error messages from PHPMailer
		return false;
	} catch (Exception $e) {
		//echo $e->getMessage(); //Boring error messages from anything else!
		return false;
	}
}
?>