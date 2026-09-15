<?php
session_start();
if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    if ($_SESSION['role'] == "employer") {
        header("location:employer/post-job.php");
    } else {
        header("location:employee/");
    }
} else {
    header("location:login.php");
}
