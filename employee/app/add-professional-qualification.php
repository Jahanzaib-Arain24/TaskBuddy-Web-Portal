<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$country     = $_POST['country'] ?? '';
$course      = ucwords(trim($_POST['course'] ?? ''));
$institution = ucwords(trim($_POST['institution'] ?? ''));
$timeframe   = ucwords(trim($_POST['timeframe'] ?? ''));

$certificate = null;
if (!empty($_FILES['certificate']['tmp_name']) && is_uploaded_file($_FILES['certificate']['tmp_name'])) {
    if ($_FILES["certificate"]["size"] > 5000000) {
        header("location:../qualifications.php?r=2290");
        exit();
    }
    $certificate = file_get_contents($_FILES['certificate']['tmp_name']);
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO tbl_professional_qualification (member_no, country, institution, title, timeframe, certificate) VALUES (:memberno, :country, :institution, :title, :timeframe, :certificate)");
    $stmt->bindParam(':memberno', $myid);
    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':institution', $institution);
    $stmt->bindParam(':title', $course);
    $stmt->bindParam(':timeframe', $timeframe);
    $stmt->bindParam(':certificate', $certificate, PDO::PARAM_LOB);
    $stmt->execute();

    header("location:../qualifications.php?r=2305");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in add-professional-qualification: " . $e->getMessage());
    header("location:../qualifications.php?r=2305");
    exit();
}
?>