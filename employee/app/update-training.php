<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$training    = ucwords(trim($_POST['training'] ?? ''));
$institution = ucwords(trim($_POST['institution'] ?? ''));
$timeframe   = ucwords(trim($_POST['timeframe'] ?? ''));
$training_id = $_POST['trainingid'] ?? '';

$has_file = !empty($_FILES['certificate']['tmp_name']) && is_uploaded_file($_FILES['certificate']['tmp_name']);

if ($has_file && $_FILES["certificate"]["size"] > 5000000) {
    header("location:../training.php?r=2290");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($has_file) {
        $certificate = file_get_contents($_FILES['certificate']['tmp_name']);
        $stmt = $conn->prepare("UPDATE tbl_training SET training = :training, institution = :institution, timeframe = :timeframe, certificate = :certificate WHERE id = :trainid AND member_no = :myid");
        $stmt->bindParam(':training', $training);
        $stmt->bindParam(':institution', $institution);
        $stmt->bindParam(':timeframe', $timeframe);
        $stmt->bindParam(':certificate', $certificate, PDO::PARAM_LOB);
        $stmt->bindParam(':trainid', $training_id);
        $stmt->bindParam(':myid', $myid);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE tbl_training SET training = :training, institution = :institution, timeframe = :timeframe WHERE id = :trainid AND member_no = :myid");
        $stmt->bindParam(':training', $training);
        $stmt->bindParam(':institution', $institution);
        $stmt->bindParam(':timeframe', $timeframe);
        $stmt->bindParam(':trainid', $training_id);
        $stmt->bindParam(':myid', $myid);
        $stmt->execute();
    }

    header("location:../training.php?r=5790");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in update-training: " . $e->getMessage());
    header("location:../training.php?r=5790");
    exit();
}
?>