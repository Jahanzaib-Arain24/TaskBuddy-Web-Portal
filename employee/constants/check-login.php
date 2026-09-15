<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../constants/settings.php';

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    $myid = $_SESSION['myid'] ?? '';
    $myfname = $_SESSION['myfname'] ?? '';
    $mylname = $_SESSION['mylname'] ?? '';
    $mygender = $_SESSION['gender'] ?? '';
    $myemail = $_SESSION['myemail'] ?? '';
    $mydate = $_SESSION['mydate'] ?? '';
    $mymonth = $_SESSION['mymonth'] ?? '';
    $myyear = $_SESSION['myyear'] ?? '';
    $myphone = $_SESSION['myphone'] ?? '';
    $myedu = $_SESSION['myedu'] ?? '';
    $mytitle = $_SESSION['mytitle'] ?? '';
    $mycity = $_SESSION['mycity'] ?? '';
    $mystreet = $_SESSION['mystreet'] ?? '';
    $myzip = $_SESSION['myzip'] ?? '';
    $mycountry = $_SESSION['mycountry'] ?? '';
    $mydesc = $_SESSION['mydesc'] ?? '';
    $myavatar = !empty($_SESSION['avatar']) ? $_SESSION['avatar'] : null;
    $mylogin = $_SESSION['lastlogin'] ?? '';
    $myrole = $_SESSION['role'] ?? '';
    $user_online = true;	
} else {
    $user_online = false;
    $myavatar = null;
}
?>