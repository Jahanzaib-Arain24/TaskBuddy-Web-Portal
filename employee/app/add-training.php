<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$training    = ucwords(trim($_POST['training'] ?? ''));
$institution = ucwords(trim($_POST['institution'] ?? ''));
$timeframe   = ucwords(trim($_POST['timeframe'] ?? ''));

$certificate = null;
if (!empty($_FILES['certificate']['tmp_name']) && is_uploaded_file($_FILES['certificate']['tmp_name'])) {
    if ($_FILES["certificate"]["size"] > 5000000) {
        header("location:../training.php?r=2290");
        exit();
    }
    $certificate = file_get_contents($_FILES['certificate']['tmp_name']);
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO tbl_training (member_no, training, institution, timeframe, certificate) VALUES (:member, :training, :institution, :timeframe, :certificate)");
    $stmt->bindParam(':member', $myid);
    $stmt->bindParam(':training', $training);
    $stmt->bindParam(':institution', $institution);
    $stmt->bindParam(':timeframe', $timeframe);
    $stmt->bindParam(':certificate', $certificate, PDO::PARAM_LOB);
    $stmt->execute();

    header("location:../training.php?r=1964");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in add-training: " . $e->getMessage());
    header("location:../training.php?r=1964");
    exit();
}
?>