<?php
/**
 * Created by PhpStorm.
 * User: Boy Developer
 * Date: 2/24/2020
 * Time: 3:23 AM
 */

$user = $_GET['u'];
$pass = $_GET['p'];

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        #outerdiv
        {
            width: 45px;
            height: 20px;
            overflow:hidden;
            position:relative;
        }
        #outerdiv iframe
        {
            position: absolute;
            top: -35px;
            left: -731px;
        }

        @media screen and (max-width:767px) {
            #outerdiv iframe
            {
                position: absolute;
                top: -26px;
                left: -795px;
            }
        }

        @media screen and (max-width:979px) {
            #outerdiv iframe
            {
                position: absolute;
                top: -26px;
                left: -795px;
            }
        }

        @media screen and (max-width:1030px) {
            #outerdiv iframe
            {
                position: absolute;
                top: -26px;
                left: -795px;
            }
        }
    </style>
</head>
<body>
<div id="outerdiv">
    <iframe id="fGcb" src="https://admin.wpncenter.com/glogin/" frameborder="0" width="1200px" scrolling="no" style="overflow: hidden; height: 1950px !important;"></iframe>
    <image id="theimage"></image>
</div>
<form id="frmGcb" action="https://bbbs.bacc1688.com/Home/Login" method="post">
    <input type="hidden" name="clubEname" value="<?php echo $user?>">
    <input type="hidden" name="password" value="<?php echo $pass?>">
    <input type="text" name="inputImgCode" value="">

    <input type="button" onclick="login()" value="Login Ajax">
<!--    <input type="submit" value="Login">-->
</form>
<!-- jQuery -->
<script src="//code.jquery.com/jquery.js"></script>
<script>

    function login() {
        $.ajax({
            type: "POST",
            url: "https://bbbs.bacc1688.com/Home/Login",
            data: $("form#frmGcb").serialize(),
            xhrFields: {
                withCredentials: true
            },
            //async: true,
            //dataType: "text/plain",
            headers: {"Origin": "https://bbbs.bacc1688.com", "Referer": "https://bbbs.bacc1688.com/Home/Index"},
            success: function (data) {
                console.log(data);
            },
            fail: function ($xhr) {
                var data = $xhr.responseJSON;
                console.log(data);
            },
            done: function ($e) {
                console.log($e);
            }

        });
        setTimeout(() => {  reLoad('https://bbbs.bacc1688.com/') }, 1500);
    }

    function reLoad(url){

        if (navigator.userAgent.match(/Android/i) ||
            navigator.userAgent.match(/webOS/i) ||
            navigator.userAgent.match(/iPhone/i) ||
            navigator.userAgent.match(/iPad/i) ||
            navigator.userAgent.match(/iPod/i) ||
            navigator.userAgent.match(/BlackBerry/i) ||
            navigator.userAgent.match(/Windows Phone/i)
        ) {
            window.location.href = url;
        } else {
            window.location = url;
        }

    }

</script>
</body>
</html>
