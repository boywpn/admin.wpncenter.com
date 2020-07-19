<?php
include_once __DIR__ . '/db.php';

if (isset($_GET['pin'])) {
    $d = $_GET;
    if (trim($d['pin']) != $config['pin']) {
        echo "false";
        exit;
    }
    $_SESSION['login'] = true;
    echo "true";
    exit;
}
if (isset($_POST['password'])) {
    $d = $_POST;
    if (!isset($d['password'])) {
        alert("กรุณาใส่ password", "error", '../index.php');
        exit;
    }
    if (trim($d['password']) != $config['admin']) {
        alert("password ไม่ถูกต้อง", "error", '../index.php');
        exit;
    }
    $_SESSION['login'] = true;
    alert("เข้าสู่ระบบสำเร็จ", "success", '../transfer.php');
}