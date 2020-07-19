<?php
ini_set('session.gc_maxlifetime', 2678400);
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once __DIR__ . '/config.php';

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

include_once __DIR__ . '/func.php';