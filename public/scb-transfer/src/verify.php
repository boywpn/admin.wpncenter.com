<?php

include_once __DIR__ . '/db.php';
if (isset($_POST)) {
    $d = $_POST;
    $bankID = $d['bankID'];
    $accnum = $d['accnum'];
    $amount = $d['amount'];
    if (!isset($bankID) || $bankID == "") {
        echo json_encode(['status' => ['description' => 'เลขธนาคารไม่ถูกต้อง']]);
        exit;
    }
    if (!isset($accnum) || $accnum == "") {
        echo json_encode(['status' => ['description' => 'เลขบัญชีไม่ถูกต้อง']]);
        exit;
    }
    if (!isset($amount) || $amount == "") {
        echo json_encode(['status' => ['description' => 'จำนวนเงินไม่ถูกต้อง']]);
        exit;
    }
    $v = $bank->getVerify($accnum, $bankID, $amount);
    $v['data']['accnum'] = $bank->accnum;
    echo json_encode($v);
}