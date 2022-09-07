<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

     // Load Composer's autoloader
    //require 'vendor/autoload.php';
	require_once '../../dist/PHPMailer-master/src/PHPMailer.php';
	require_once '../../dist/PHPMailer-master/src/SMTP.php';
	require_once '../../dist/PHPMailer-master/src/Exception.php';
	
    function sendmail($to,$subject,$message,$name)
    {
                  $mail             = new PHPMailer();
                  $body             = $message;
				  //$mail->SMTPDebug = SMTP::DEBUG_SERVER; 
                  $mail->IsSMTP();
                  $mail->SMTPAuth   = true;
                  $mail->Host       = "smtp.gmail.com";
                  $mail->Port       = 587;
                  $mail->Username   = "banjamw21@gmail.com";
                  $mail->Password   = "banja@2021";
                  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                  $mail->SetFrom('banjamw21@gmail.com', 'Banja La Mtsogolo');
                  $mail->AddReplyTo("banjamw21@gmail.com", 'Banja La Mtsogolo');
				  $mail->AddEmbeddedImage("../../dist/img/blm_logo.png", "brand-logo");
                  $mail->Subject    = $subject;
				  $mail->isHTML(true);
                  $mail->MsgHTML($body);
                  $mail->AddAddress($to);
                  if(!$mail->Send()) {
                      return 0;
                  } else {
                        return 1;
                 }
    }
?>