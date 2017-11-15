<?php
function enviarCorreo($email,$mensaje,$usuario="Usuario")
{
  require 'PHPMailer-master\PHPMailerAutoload.php';

  $mail = new PHPMailer;

  //$mail->SMTPDebug = 3;                               // Enable verbose debug output

  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = 'smtp.yandex.com';                   // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = 'alquimerico@yandex.com';              // SMTP username
  $mail->Password = 'passwordDemon';                 // SMTP password
  $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
  $mail->Port = 465;                                    // TCP port to connect to

  $mail->setFrom('alquimerico@yandex.com', 'SquareDay');
  $mail->addAddress($email, $usuario);     // Add a recipient
  //$mail->addAddress('ellen@example.com');               // Name is optional
  $mail->addReplyTo('alquimerico@yandex.com', 'SquareDay');
  //$mail->addCC('cc@example.com');
  //$mail->addBCC('bcc@example.com');

  //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
  //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
  //$mail->isHTML(true);                                  // Set email format to HTML

  $mail->Subject = 'SquareDay: Account Verification ';
  $mail->Body    = $mensaje;
  $mail->AltBody = $mensaje;

  if(!$mail->send()) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
  } else {
      echo 'Message has been sent';
  }

}
?>
