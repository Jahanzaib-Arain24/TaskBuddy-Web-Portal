<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$country     = $_POST['country'] ?? '';
$course      = ucwords(trim($_POST['course'] ?? ''));
$institution = ucwords(trim($_POST['institution'] ?? ''));
$timeframe   = ucwords(trim($_POST['timeframe'] ?? ''));
$level       = $_POST['level'] ?? '';

$certificate = null;
if (!empty($_FILES['certificate']['tmp_name']) && is_uploaded_file($_FILES['certificate']['tmp_name'])) {
    if ($_FILES["certificate"]["size"] > 5000000) {
        header("location:../academic.php?r=2290");
        exit();
    }
    $certificate = file_get_contents($_FILES['certificate']['tmp_name']);
}

$transcript = null;
if (!empty($_FILES['transcript']['tmp_name']) && is_uploaded_file($_FILES['transcript']['tmp_name'])) {
    if ($_FILES["transcript"]["size"] > 5000000) {
        header("location:../academic.php?r=2490");
        exit();
    }
    $transcript = file_get_contents($_FILES['transcript']['tmp_name']);
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO tbl_academic_qualification (member_no, country, institution, course, level, timeframe, certificate, transcript) VALUES (:member, :country, :institution, :course, :level, :timeframe, :certificate, :transcript)");
    $stmt->bindParam(':member', $myid);
    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':institution', $institution);
    $stmt->bindParam(':course', $course);
    $stmt->bindParam(':level', $level);
    $stmt->bindParam(':timeframe', $timeframe);
    $stmt->bindParam(':certificate', $certificate, PDO::PARAM_LOB);
    $stmt->bindParam(':transcript', $transcript, PDO::PARAM_LOB);
    $stmt->execute();

    header("location:../academic.php?r=2303");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in add-academic-qualification: " . $e->getMessage());
    header("location:../academic.php?r=2303");
    exit();
}
?>