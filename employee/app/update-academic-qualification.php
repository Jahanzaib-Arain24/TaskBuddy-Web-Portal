<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$id          = $_POST['courseid'] ?? '';
$country     = $_POST['country'] ?? '';
$course      = ucwords(trim($_POST['course'] ?? ''));
$institution = ucwords(trim($_POST['institution'] ?? ''));
$timeframe   = ucwords(trim($_POST['timeframe'] ?? ''));
$level       = $_POST['level'] ?? '';

$has_cert = !empty($_FILES['certificate']['tmp_name']) && is_uploaded_file($_FILES['certificate']['tmp_name']);
$has_trans = !empty($_FILES['transcript']['tmp_name']) && is_uploaded_file($_FILES['transcript']['tmp_name']);

if ($has_cert && $_FILES["certificate"]["size"] > 5000000) {
    header("location:../academic.php?r=2290");
    exit();
}

if ($has_trans && $_FILES["transcript"]["size"] > 5000000) {
    header("location:../academic.php?r=2490");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($has_cert && $has_trans) {
        $certificate = file_get_contents($_FILES['certificate']['tmp_name']);
        $transcript  = file_get_contents($_FILES['transcript']['tmp_name']);
        $stmt = $conn->prepare("UPDATE tbl_academic_qualification SET country = :country, institution = :institution, course = :course, level = :level, timeframe = :timeframe, certificate = :certificate, transcript = :transcript WHERE id = :aid AND member_no = :myid");
        $stmt->bindParam(':certificate', $certificate, PDO::PARAM_LOB);
        $stmt->bindParam(':transcript', $transcript, PDO::PARAM_LOB);
    } elseif ($has_cert && !$has_trans) {
        $certificate = file_get_contents($_FILES['certificate']['tmp_name']);
        $stmt = $conn->prepare("UPDATE tbl_academic_qualification SET country = :country, institution = :institution, course = :course, level = :level, timeframe = :timeframe, certificate = :certificate WHERE id = :aid AND member_no = :myid");
        $stmt->bindParam(':certificate', $certificate, PDO::PARAM_LOB);
    } elseif (!$has_cert && $has_trans) {
        $transcript = file_get_contents($_FILES['transcript']['tmp_name']);
        $stmt = $conn->prepare("UPDATE tbl_academic_qualification SET country = :country, institution = :institution, course = :course, level = :level, timeframe = :timeframe, transcript = :transcript WHERE id = :aid AND member_no = :myid");
        $stmt->bindParam(':transcript', $transcript, PDO::PARAM_LOB);
    } else {
        $stmt = $conn->prepare("UPDATE tbl_academic_qualification SET country = :country, institution = :institution, course = :course, level = :level, timeframe = :timeframe WHERE id = :aid AND member_no = :myid");
    }

    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':institution', $institution);
    $stmt->bindParam(':course', $course);
    $stmt->bindParam(':level', $level);
    $stmt->bindParam(':timeframe', $timeframe);
    $stmt->bindParam(':aid', $id);
    $stmt->bindParam(':myid', $myid);
    $stmt->execute();

    header("location:../academic.php?r=3214");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in update-academic-qualification: " . $e->getMessage());
    header("location:../academic.php?r=3214");
    exit();
}
?>