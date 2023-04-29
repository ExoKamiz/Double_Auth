<?php

namespace PHPMailer\src\PHPMailer;

namespace PHPMailer\src\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

//use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


// require './PHPMailer/src/Exception.php';
// require './PHPMailer/src/PHPMailer.php';
// require './PHPMailer/src/SMTP.php';

// use PHPMailer\PHPMailer\PHPMailer;

// // namespace PHPMailer\src\PHPMailer;
// // namespace PHPMailer\src\Exception;
// // use PHPMailer\PHPMailer\Exception;
// // use PHPMailer\PHPMailer\PHPMailer;


// // $m = new M();
// // $m→send_email('adres_odbiorcy@gmail.com', 'hasło jednorazowe');

class Mail
{
    public function send_email($address, $content)
    {
        try {
            $mail = new PHPMailer;
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAutoTLS = false;

            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->Port = 587;
            $mail->SMTPAuth = true; // Enable SMTP authentication

            $mail->SMTPsecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->Username = 'ahalaimahalai793@gmail.com'; // SMTP username
            $mail->Password = 'tdogxhyglgoozcyz'; // SMTP password

            $mail->SetFrom('ahalaimahalai793@gmail.com', 'Haslo logowania jednorazowego');
            $mail->FromName = 'OTP source';

            $mail->isHTML(true); // Set email format to HTML

            $mail->Subject = 'Your security code';
            $mail->Body = 'This is your authentication code <B>' . $content . '</B>';
            $mail->AltBody = 'This is your authentication code ' . $content . '';
            $mail->addAddress($address); // Add a recipient
            if (!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent';
            }
        } catch (Exception $e) {
            echo "Exception &nbsp" . $e->getMessage();
        }
    }
}