<?php

function rand12($length = 1, $special = null) {
    $characters = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    if(!empty($special)) {
        $randomString .= $special;
    }

    return $randomString;
}

function to_upper($name){
    $name=ucwords(strtolower($name));
    $arr=explode('-', $name);
    $name=array();
    foreach($arr as $v)
    {
        $name[]=ucfirst($v);
    }
    $name=implode('-', $name);
    return $name;
}

function short_text($str, $limit)
{
    $charset = 'UTF-8';
    if(mb_strlen($str, $charset) > $limit) {
        return $string = mb_substr($str, 0, $limit, $charset) . '...';
    }else{
        return $str;
    }
}

function padZero($str, $pad_length){
    $pad_char = 0;
    $str = str_pad($str, $pad_length, $pad_char, STR_PAD_LEFT);
    return $str;
}

function delMiltiSpace($input){
    return $output = preg_replace('/\s+/', ' ',$input);
}

function replacePhone($input){
    $str = substr($input, -4);
    return "XXXXXX".$str;
}

function xmlDecode($xml, $array = false){

    $xml = simplexml_load_string($xml);
    $json = json_encode($xml);
    $array = json_decode($json,TRUE);

    if($array){
        return $array;
    }

    return $json;
}

function respond($status = false, $data = [], $errors = [], $msg = [])
{
    return ['status' => $status, 'data' => $data, 'message' => $msg, 'errors' => $errors];
}

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';

    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

function datetime(){
    return date('Y-m-d H:i:s');
}

function genDate($change, $full=true){

    $date = date('Y-m-d H:i:s', strtotime($change, strtotime(date('Y-m-d H:i:s'))));
    $datestr = strtotime($date);
    if(!$full){
        $date = date('Y-m-d', $datestr);
    }

    return $date;

}

function changeDate($change, $time, $full=true){

    $date = date('Y-m-d H:i:s', strtotime($change, strtotime($time)));
    $datestr = strtotime($date);
    if(!$full){
        $date = date('Y-m-d', $datestr);
    }

    return $date;

}

function genDateFilter($change, $start_time='12:00:00'){

    $s_change = '0 day';
    if(date('H:i:s') <= $start_time){
        // $s_change = '-1 day';
    }

    $x_date = date('Y-m-d', strtotime($s_change, strtotime(date('Y-m-d'))));

    $date = date('Y-m-d', strtotime($change, strtotime($x_date)));

    return $date;

}

function setCommissionSelect(){

    $comm = [];
    for($i=0;$i<=1;$i+=0.05){
        $key = $i;
        $val = number_format($i, 2);
        $comm["$key"] = $val;
    }

    return $comm;

}

function setShareSelect(){

    $value = [];
    $value[0] = 0;
    for($i=100;$i>=0;$i-=1){
        $key = $i;
        $val = number_format($i, 0);
        $value["$key"] = $val;
    }

    return $value;

}

function phoneReplace($phone, $str = "x"){

    $replace = substr($phone, 6, 4);
    $rep = "";
    for($i=1;$i<=6;$i++){
        $rep .= $str;
    }
    return $rep.$replace;

}

/**
 * simple method to encrypt or decrypt a plain text string
 * initialization vector(IV) has to be the same when encrypting and decrypting
 *
 * @param string $action: can be 'encrypt' or 'decrypt'
 * @param string $string: string to encrypt or decrypt
 *
 * @return string
 */
function encrypter($action, $string, $key = "YhRk5T52R8JSZwsJlzOzk29Do2mDUCyUJu46WDJF04bjkqAlG6", $salt = "7VeEdxUoBcxHCeFHM4oU4EhbmhBTRr3zLbgv") {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = $key;
    $secret_iv = $salt;
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function line_notify($message, $token="", $img=""){
    // define('LINE_API',"https://notify-api.line.me/api/notify");

    if($token==""){
        $token = "";
    }

    $queryData['message'] = $message;
    if($img!=""){
        $queryData['imageFile'] = $img;
    }

    $queryData = http_build_query($queryData,'','&');

    $ch = curl_init("https://notify-api.line.me/api/notify");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: '.strlen($queryData),
            'Authorization: Bearer '.$token,
        )
    );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $queryData);

    $response = curl_exec($ch);
    $res = json_decode($response);

    curl_close($ch);
    return $res;
}

function pusherSend($message, $channel, $event){

    require '../vendor/autoload.php';

    $options = array(
        'cluster' => env('PUSHER_CLUSTER'),
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options
    );

    $data['message'] = $message;
    $pusher->trigger($channel, $event, $data);

}

function current_millis() {
    list($usec, $sec) = explode(" ", microtime());
    return round(((float)$usec + (float)$sec) * 1000);
}