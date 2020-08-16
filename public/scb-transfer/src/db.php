<?php
ini_set('session.gc_maxlifetime', 2678400);
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once __DIR__ . '/config.php';

$u = (isset($_GET['u'])) ? $_GET['u'] : ((isset($_SESSION['u'])) ? $_SESSION['u'] : null);
//if(empty($u)){
//    echo "Bank Request!";
//    exit;
//}

$_SESSION['u'] = $u;
$sess_u = $_SESSION['u'];

if(empty($sess_u)){
    echo "Bank Request!";
    exit;
}

exit;

try {
    $conn = new PDO("mysql:host=" . $config['db']['host'] . ";dbname=" . $config['db']['dbname'] . ";charset=utf8", $config['db']['user'], $config['db']['pass']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db = $conn;
} catch (PDOException $e) {
    print "Error! ####: " . $e->getMessage() . "<br/>";
    exit();
}

function query($sql = '', $prepare = '')
{
    global $db;
    $result = $db->prepare($sql);
    $result->execute($prepare);
    return $result;
}

$b = query("SELECT * FROM ai_bank_acc WHERE bank_username = '".$sess_u."' AND bank_id = '6'", [])->fetch();
if(empty($b['deviceId'])){
    echo "Bank Request Token!";
    exit;
}
$config['bank'] = [
    'account_number' => $b['bank_number'],
    'deviceId' => $b['deviceId'],
    'ApiRefresh' => $b['api-refresh']
];

include_once __DIR__ . '/func.php';