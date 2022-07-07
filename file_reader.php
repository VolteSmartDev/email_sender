<?php
error_reporting(0);
require 'inc/function.php';

if(!empty($_POST)){
    $file = 'emails.txt';
    $fh = fopen($file, 'r');
    $emails = array();
    while (!feof($fh))
    {
        $emails[] = fgets($fh);   
    }
    $file = 'smtps.txt';
    $fh = fopen($file, 'r');
    $smtps = array();
    while (!feof($fh))
    {
        $smtps[] = fgets($fh);   
    }
    fclose($fh);    
    
    json_succ(['emails' => $emails,'smtps' => $smtps]); 
}
?>
