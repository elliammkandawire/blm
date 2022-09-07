<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

     // Load Composer's autoloader
    //require 'vendor/autoload.php';
	require_once '../../dist/PHPMailer-master/src/PHPMailer.php';
	require_once '../../dist/PHPMailer-master/src/SMTP.php';
	require_once '../../dist/PHPMailer-master/src/Exception.php';
	
    function sendmail($email,$subject,$message,$location)
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
				  $mail->AddAttachment($location);
                  $mail->Subject    = $subject;
                  $mail->MsgHTML($body);
                  $mail->AddAddress($email);
                  if(!$mail->Send()) {
                      return 0;
                  } else {
                        return 1;
                 }
    }
?>