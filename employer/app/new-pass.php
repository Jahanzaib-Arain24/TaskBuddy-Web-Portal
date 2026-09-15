<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$raw_password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($raw_password)) {
    header("location:../change-password.php");
    exit();
}

$new_password = password_hash($raw_password, PASSWORD_BCRYPT);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE tbl_users SET login = :newpassword WHERE member_no = :myid");
    $stmt->bindParam(':newpassword', $new_password);
    $stmt->bindParam(':myid', $myid);
    $stmt->execute();
    
    header("location:../change-password.php?r=9564");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    header("location:../change-password.php?r=failed");
    exit();
}
?>