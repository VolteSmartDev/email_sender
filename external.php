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
    //$emailList=leafTrim($_POST['emailList']);
    $messageType=leafTrim($_POST['messageType']);
    $messageLetter=leafTrim($_POST['messageLetter']);
    $encoding = $_POST['encode'];
    $charset = $_POST['charset'];
    $interType = $_POST['interType'];
    $interval = $_POST['interval'];
    // $smtpGood = $_POST['smtpGood'];
    $multiThreads = $_POST['multiThreads'];
    $emailList = $_POST['emails'];
    $smtpList = $_POST['smtps'];
    if(empty($smtpList)){
        json_succ(['list' => []]);
    }
    $count = 0;
    //$emails = array_slice($emailList, 0, $multiThreads);
    //$smtps = array_slice($smtpList, 0, $multiThreads);
    foreach ($smtpList as $smtp) {
        $email = $emailList[$count];        
        try {
            list($host, $username, $password, $port) = explode(':', $smtp);                    
            $mail = new PHPMailer(true);

            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->SMTPDebug = 1;                                           //Alternative to above constant
            $mail->Debugoutput = 'html';                        //Ask for HTML-friendly debug output
            $mail->Debugoutput = function($str, $level) {
                file_put_contents('smtp.log', gmdate('Y-m-d H:i:s'). "\t$level\t$str\n", FILE_APPEND | LOCK_EX);
            };
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $host;                                  //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $username;                              //SMTP username
            $mail->Password   = $password;                               //SMTP password
            $mail->Port       = $port;                                    
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption//TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->CharSet = 'UTF-8';

            if($mail->Port == 465){
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            }else{
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->setFrom($username, $senderName);
            $mail->isHTML(true);            
            $list = [];
            //$emails = array_slice($emails, 0, $multiThreads);
            // var_dump($_FILES);
            // for($i=0; $i<count($_FILES['attachment']['name']); $i++) {
            //     if ($_FILES['attachment']['tmp_name'][$i] != ""){
            //         $mail->AddAttachment($_FILES['attachment']['tmp_name'][$i],$_FILES['attachment']['name'][$i]);
            //     }
            // }            

            //foreach ($emails as $email) {
                // $mail->addAddress($user['email'], $user['name']);
                $mail->addAddress($email); 
                $mail->addReplyTo($replyTo, $senderName);
                $mail->Subject = leafClear($subject,$email);
                $mail->Body    = leafClear($messageLetter,$email);
                // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                try {
                    if($mail->send())
                        $list[] = $email;
                    //echo "Message sent to: ({$user['email']})\n";
                } catch (Exception $e) {
                    json_succ(['list' => [], 'msg' => $e->getMessage(),'smtp'=>$smtp]);
                }
                $mail->clearAddresses();
            //}
            json_succ(['list' => $list]);
        } catch (Exception $e) {
            json_succ(['list' => [], 'msg' => $e->getMessage()]);
        }
        $count++;
    }
    //$mail->smtpClose(); //use for multi users
    $mail->smtpClose();
}
json_fail();
