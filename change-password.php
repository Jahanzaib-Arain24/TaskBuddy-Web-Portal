<?php
require 'constants/settings.php';
require 'constants/check-login.php';

if ($user_online == "true") {
    if ($myrole == "employee") {
        header("location:employee/change-password.php");
        exit();
    } else if ($myrole == "employer") {
        header("location:employer/change-password.php");
        exit();
    } else {
        header("location:./");
        exit();
    }
} else {
    header("location:login.php");
    exit();
}
?>
