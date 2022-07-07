<?php

function halt($obj){
    echo '<pre>';
    var_dump($obj);
    exit;
}

function leafClear($text,$email){
    $e = explode('@', $email);
    $emailuser=$e[0];
    $emaildomain=$e[1];
    $text = str_replace("[-time-]", date("m/d/Y h:i:s a", time()), $text);
    $text = str_replace("[-email-]", $email, $text);
    $text = str_replace("[-emailuser-]", $emailuser, $text);
    $text = str_replace("[-emaildomain-]", $emaildomain, $text);
    $text = str_replace("[-randomletters-]", randString('abcdefghijklmnopqrstuvwxyz'), $text);
    $text = str_replace("[-randomstring-]", randString('abcdefghijklmnopqrstuvwxyz0123456789'), $text);
    $text = str_replace("[-randomnumber-]", randString('0123456789'), $text);
    $text = str_replace("[-randommd5-]", md5(randString('abcdefghijklmnopqrstuvwxyz0123456789')), $text);
    return $text;  
}
function leafTrim($string){
    $string=urldecode($string);
    return stripslashes(trim($string));
}
function randString($consonants) {
    $length=rand(12,25);
    $password = '';
    for ($i = 0; $i < $length; $i++) {
            $password .= $consonants[(rand() % strlen($consonants))];
    }
    return $password;
}

function leafMailCheck($email){
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) return true;
    else return false;
}

function json_succ($arr = []){
    $res = [
        'code' => 200,
        'data' => $arr
    ];
    echo json_encode($res);
    exit;
}

function json_fail($msg = 'Network Busy'){
    echo json_encode(['code' => 0, 'msg' => $msg]);
    exit;
}

//发送邮件 宝塔邮件系统发送
function send_mail($to, $title, $content) {
    $postdata = [
        'mail_from' => 'support@meilifj.com',
        'password' => 'Jbs.1234',
        'mail_to' => $to,
        'subject' => $title, //标题
        'content' => $content, //邮件内容
    ];
    $url = 'http://103.145.190.241:8888/mail_sys/send_mail_http.json';//宝塔的地址
    $res = curl_request($url,$postdata);
    $res = json_decode($res,true);
    if($res['status']){
        return true;
    }else{
        return false;
    }

}
//curl请求
function curl_request($url,$post='',$cookie='', $returnCookie=0){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    // curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
    if($post) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    if($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return curl_error($curl);
    }
    curl_close($curl);
    if($returnCookie){
        list($header, $body) = explode("\r\n\r\n", $data, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        $info['cookie']  = substr($matches[1][0], 1);
        $info['content'] = $body;
        return $info;
    }else{
        return $data;
    }
}

