<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$title  = ucwords(trim($_POST['title'] ?? ''));
$issuer = ucwords(trim($_POST['issuer'] ?? ''));
$certid = $_POST['attid'] ?? '';

$has_file = !empty($_FILES['certificate']['tmp_name']) && is_uploaded_file($_FILES['certificate']['tmp_name']);

if ($has_file && $_FILES["certificate"]["size"] > 5000000) {
    header("location:../attachments.php?r=2290");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($has_file) {
        $certificate = file_get_contents($_FILES['certificate']['tmp_name']);
        $stmt = $conn->prepare("UPDATE tbl_other_attachments SET title = :title, issuer = :issuer, attachment = :attachment WHERE id = :certid AND member_no = :myid");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':issuer', $issuer);
        $stmt->bindParam(':attachment', $certificate, PDO::PARAM_LOB);
        $stmt->bindParam(':certid', $certid);
        $stmt->bindParam(':myid', $myid);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE tbl_other_attachments SET title = :title, issuer = :issuer WHERE id = :certid AND member_no = :myid");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':issuer', $issuer);
        $stmt->bindParam(':certid', $certid);
        $stmt->bindParam(':myid', $myid);
        $stmt->execute();
    }

    header("location:../attachments.php?r=7764");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in update-attachment: " . $e->getMessage());
    header("location:../attachments.php?r=7764");
    exit();
}
?>