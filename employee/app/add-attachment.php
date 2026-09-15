<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$title  = ucwords(trim($_POST['title'] ?? ''));
$issuer = ucwords(trim($_POST['issuer'] ?? ''));

$certificate = null;
if (!empty($_FILES['certificate']['tmp_name']) && is_uploaded_file($_FILES['certificate']['tmp_name'])) {
    if ($_FILES["certificate"]["size"] > 5000000) {
        header("location:../attachments.php?r=2290");
        exit();
    }
    $certificate = file_get_contents($_FILES['certificate']['tmp_name']);
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO tbl_other_attachments (member_no, title, issuer, attachment) VALUES (:member, :title, :issuer, :attachment)");
    $stmt->bindParam(':member', $myid);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':issuer', $issuer);
    $stmt->bindParam(':attachment', $certificate, PDO::PARAM_LOB);
    $stmt->execute();

    header("location:../attachments.php?r=6789");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in add-attachment: " . $e->getMessage());
    header("location:../attachments.php?r=6789");
    exit();
}
?>
