<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:login.php");
    exit();
}

$admin_id = isset($_SESSION['myid']) ? $_SESSION['myid'] : '';
$admin_name = isset($_SESSION['myfname']) ? $_SESSION['myfname'] : 'Admin';
$admin_email = isset($_SESSION['myemail']) ? $_SESSION['myemail'] : '';
?>
