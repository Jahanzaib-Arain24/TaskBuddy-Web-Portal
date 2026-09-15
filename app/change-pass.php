<?php
session_start();
$usermail = isset($_SESSION['resetmail']) ? $_SESSION['resetmail'] : '';
$raw_password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($usermail) || empty($raw_password)) {
    header("location:../login.php");
    exit();
}

$new_password = password_hash($raw_password, PASSWORD_BCRYPT);

require '../constants/db_config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE tbl_users SET login = :newlogin WHERE email = :email");
    $stmt->bindParam(':newlogin', $new_password);
    $stmt->bindParam(':email', $usermail);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM tbl_tokens WHERE email = :email");
    $stmt->bindParam(':email', $usermail);
    $stmt->execute();
    
    $_SESSION['resetmail'] = "";
    header("location:../login.php?r=3091");
    exit();

} catch (PDOException $e) {
    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    header("location:../login.php");
    exit();
}
?>
