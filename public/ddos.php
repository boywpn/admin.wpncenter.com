<?php

function curlddos(){

    $curl = curl_init();

    $arr = [
        'action' => 'checkBalance',
        'username' => 'b11boya1001'
    ];

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://api.xxzzaa.com/api/member",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($arr),
        CURLOPT_HTTPHEADER => array(
            "token: U0NIMXZVNlVwVUVldlh5bElXTWJ1dz09",
            "Content-Type: application/json",
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;

}

echo curlddos();