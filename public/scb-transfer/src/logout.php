<?php
include_once __DIR__ . '/db.php';

session_unset();

session_destroy();

alert("ออกจากระบบสำเร็จ", "success", '../index.php');