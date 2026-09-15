<?php
session_start();
if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    if ($_SESSION['role'] == "employee") {
        header("location:employee/applied-jobs.php");
    } else {
        header("location:employer/my-jobs.php");
    }
} else {
    header("location:login.php");
}
