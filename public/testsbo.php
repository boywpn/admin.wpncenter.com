<?php
/**
 * Created by PhpStorm.
 * User: Boy Developer
 * Date: 4/21/2020
 * Time: 12:10 AM
 */

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "http://admin.wpncenter.com/games/sbo/member/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => array('user_game' => 'sboshs0003','game_type' => 'SportsBook')
));

$response = curl_exec($curl);

$res = json_decode($response, true);

curl_close($curl);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        .body-inner {
            margin: 0px auto;
            padding-top: 120px;
        }
    </style>
</head>
<body>

<iframe id="frame" src="<?php echo $res['playUrl']?>" scrolling="no" style="width: 100%; height: 900px" frameborder="0"></iframe>

<!--<script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>-->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

<script>
    // var $head = $("#frame").contents().find("head");
    //
    // $head.append($("<link/>", {
    //     rel: "stylesheet",
    //     href: "https://admin.wpncenter.com/sbocss.css",
    //     type: "text/css"
    // }));
    // $('iframe#frame').load(function() {
    //     this.style.height = this.contentWindow.document.body.offsetHeight + 'px';
    // });
</script>
</body>
</html>
