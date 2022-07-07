<?php
error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'inc/Exception.php';
require 'inc/PHPMailer.php';
require 'inc/SMTP.php';
require 'inc/function.php';

if(!empty($_POST)){
    $senderEmail=leafTrim($_POST['senderEmail']);
    $senderName=leafTrim($_POST['senderName']);
    $replyTo=leafTrim($_POST['replyTo']);
    $subject=leafTrim($_POST['subject']);
    $emailList=leafTrim($_POST['emailList']);
    $messageType=leafTrim($_POST['messageType']);
    $messageLetter=leafTrim($_POST['messageLetter']);
    $encoding = $_POST['encode'];
    $charset = $_POST['charset'];
    $interType = $_POST['interType'];
    $interval = $_POST['interval'];
    $smtpGood = $_POST['smtpGood'];
    $multiThreads = $_POST['multiThreads'];
    $badSmtp = $_POST['badSmtp'];

    $list = [];
    // $emails=explode("\r\n", $emailList);
    $emails = $_POST['emails'];
    foreach($emails as $email){
        $to     = $email;
        $title  = leafClear($subject,$email);
        $content = leafClear($messageLetter,$email);
        if(send_mail($to, $title, $content)){
            $list[] = $email;
        }
    }
    json_succ(['list' => $list]);
    // $mail = new PHPMailer(true);
    // try {
    //     //Server settings
    //     $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    //     $mail->SMTPDebug = 1;                                           //Alternative to above constant
    //     $mail->Debugoutput = 'html';                        //Ask for HTML-friendly debug output
    //     $mail->Debugoutput = function($str, $level) {
    //         file_put_contents('smtp.log', gmdate('Y-m-d H:i:s'). "\t$level\t$str\n", FILE_APPEND | LOCK_EX);
    //     };
    //     $mail->isSMTP();                                            //Send using SMTP
    //     $mail->Host       = 'smtp.qq.com';                     //Set the SMTP server to send through
    //     $mail->Username   = '499283955@qq.com';                     //SMTP username
    //     $mail->Password   = 'imafrymygthnbicc';                               //SMTP password eslnbiyaeowxavbk
    //     $mail->Port       = 465;  

    //     $mail->Host       = 'mail.meilifj.com';                     //Set the SMTP server to send through
    //     $mail->Username   = 'support@meilifj.com';                     //SMTP username
    //     $mail->Password   = 'Jbs.1234';                               //SMTP password eslnbiyaeowxavbk
    //     $mail->Port       = 25;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    //     $mail->CharSet = 'UTF-8';
    //     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

    //     if($mail->Port == 465){
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    //         // $mail->SMTPOptions = array(
    //         //     'ssl' => array(
    //         //         'verify_peer' => false,
    //         //         'verify_peer_name' => false,
    //         //         'allow_self_signed' => true
    //         //     )
    //         // );
    //     }else if($mail->Port == 25) {
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    //     }else{
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //     }

    //     $mail->setFrom($senderEmail, $senderName);
    //     $mail->isHTML(true);    

    //     $emails=explode("\r\n", $emailList);
    //     $list = [];
    //     $emails = array_slice($emails, 0, 5);

    //     foreach ($emails as $email) {
    //         // $mail->addAddress($user['email'], $user['name']);
    //         $mail->addAddress($email); 
    //         $mail->addReplyTo($replyTo, $senderName);
    //         $mail->Subject = leafClear($subject,$email);
    //         $mail->Body    = leafClear($messageLetter,$email);
    //         // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    //         try {
    //             $mail->send();
    //             // echo "Message sent to: ({$user['email']})\n";
    //             $list[] = $email;
    //         } catch (Exception $e) {
    //             json_fail($e->getMessage());
    //         }
    //         $mail->clearAddresses();
    //     }
    //     json_succ(['list' => $list]);
    // } catch (Exception $e) {
    //     // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    //     json_fail($e->getMessage());
    // }
    // //$mail->smtpClose(); //use for multi users
    // $mail->smtpClose();
}
json_fail();
