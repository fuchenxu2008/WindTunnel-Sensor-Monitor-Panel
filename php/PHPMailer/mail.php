<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;
if (!isset($recipient)) {
    $recipient = '553597230@qq.com';
}
if (!isset($content)) {
    $content = 'Test Message';
}
//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.163.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'fuchenxu2008';                 // SMTP username
    $mail->Password = 'Ccy700221';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('fuchenxu2008@163.com', 'Surf Data');
// $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
$mail->addAddress($recipient);               // Name is optional
// $mail->addReplyTo('fuchenxu2008@163.com', 'Information');
// $mail->addCC('cc@example.com');
// $mail->addBCC('bcc@example.com');

$mail->addAttachment($attachment);         // Add attachments
// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Surf Data';
$mail->Body    = $content;
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>
