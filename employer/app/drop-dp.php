<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE tbl_users SET avatar='' WHERE member_no = :myid");
    $stmt->bindParam(':myid', $myid);
    $stmt->execute();

    $stmt = $conn->prepare("SELECT avatar FROM tbl_users WHERE member_no = :myid");
    $stmt->bindParam(':myid', $myid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $_SESSION['avatar'] = $row['avatar'];
    }
    header("location:../");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    header("location:../");
    exit();
}
?>