<?php

require_once __DIR__ . '/scb/scb.php';
function alert($msg, $status, $path = '')
{
    if ($path != "") {
        $path = 'window.location = "' . $path . '"';
    }
    $xx = "Good job!";
    if ($status == "error") {
        $xx = "Oops...";
    }
    echo '<body></body><script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript">Swal.fire({
  icon: "' . $status . '",
  title: "' . $xx . '",
  text: "' . $msg . '",
}).then((result) => {
  ' . $path . '
});</script>';
}
function isLogin()
{
    if (isset($_SESSION['login'])) {
        return true;
    } else {
        return false;
    }
}

function isAdmin()
{
    if (isset($_SESSION['admin'])) {
        return true;
    } else {
        return false;
    }
}
function ps($path)
{
    return "https://fasteasy.scbeasy.com:8888/portalserver/content/bbp/repositories/contentRepository/?path=" . $path;
}

if (isLogin()) {
    $bank = new SCB();
    $bank->setAccountNumber($config['bank']['account_number']);
    $bank->setLogin($config['bank']['deviceId'], $config['bank']['ApiRefresh']);
    $login = $bank->login();
}
function setSessionTime($_timeSecond)
{
    if (!isset($_SESSION['ses_time_life'])) {
        $_SESSION['ses_time_life'] = time();
    }
    if (isset($_SESSION['ses_time_life']) && time() - $_SESSION['ses_time_life'] > $_timeSecond) {
        if (count($_SESSION) > 0) {
            foreach ($_SESSION as $key => $value) {
                unset($$key);
                unset($_SESSION[$key]);
            }
        }
    }
}