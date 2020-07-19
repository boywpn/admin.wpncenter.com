<?php

include_once __DIR__ . '/db.php';

if (isset($_POST)) {
    $d = $_POST;
    $bankID = $d['bankID'];
    $accnum = $d['accnum'];
    $amount = $d['amount'];
    if (!isset($bankID) || $bankID == "") {
        echo json_encode(['status' => false, 'msg' => 'เลขธนาคารไม่ถูกต้อง']);
        exit;
    }
    if (!isset($accnum) || $accnum == "") {
        echo json_encode(['status' => false, 'msg' => 'เลขบัญชีไม่ถูกต้อง']);
        exit;
    }
    if (!isset($amount) || $amount == "") {
        echo json_encode(['status' => false, 'msg' => 'จำนวนเงินไม่ถูกต้อง']);
        exit;
    }
    $v = $bank->Transfer($accnum, $bankID, $amount);
    if ($v['status'] === true) {
        // query("INSERT INTO transaction (`action`,amount) VALUES (?,?);", ['โอนเงินออกไปยัง ' . $accnum, $amount]);
    }
    echo json_encode($v);
}