<?php

require_once('phpmail/PHPMailer.php');
require_once('phpmail/SMTP.php');
require_once('phpmail/MailException.php');

class email {

    public static function sendEmail(string $email, string $title, string $body) {
        $mail = new PHPMailer(true);

        try {
            //$mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "";
            $mail->Password = "";
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;

            $mail->setFrom('lentokunph@gmail.com', 'CharitEase');
            //$mail->addAddress('baluyotjamesallen@gmail.com');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = $body;
            //$mail->Subject = 'Subject';
            //$mail->Body = 'HTML message body in <b>bold</b> ';
            //$mail->AltBody = 'Body in plain text for non-HTML mail clients';
            $mail->send();
            echo "Mail has been sent successfully!";
        } catch (MailException $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
