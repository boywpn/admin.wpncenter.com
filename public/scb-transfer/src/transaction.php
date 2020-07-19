<?php

include_once __DIR__ . '/db.php';
if (!isLogin()) {
    echo "<script type='text/javascript'>window.location = 'index.php'</script>";
    exit;
}
$d = $_GET;
if (isset($d['order'])) {
    $order = $d['order'];
    if ($order != "desc" && $order != "asc") {
        exit;
    }
}
$sort = "";

if (isset($d['sort'])) {
    $sort = "created_at";
}
$search = "";
$sea = "";
if (isset($d['search'])) {
    $search = $d['search'];
    if ($search != "") {
        $sea = "LIKE '%$search%'";
    }
}
echo json_encode(query("SELECT * FROM transaction where `action` != '' $sea", [])->fetchAll(), true);