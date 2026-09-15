<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$id = isset($_POST['courseid']) ? $_POST['courseid'] : '';
$country = isset($_POST['country']) ? trim($_POST['country']) : '';
$course = isset($_POST['course']) ? ucwords(trim($_POST['course'])) : '';
$institution = isset($_POST['institution']) ? ucwords(trim($_POST['institution'])) : '';
$timeframe = isset($_POST['timeframe']) ? ucwords(trim($_POST['timeframe'])) : '';

$has_file = isset($_FILES['certificate']['tmp_name']) && !empty($_FILES['certificate']['tmp_name']);

if ($has_file && $_FILES["certificate"]["size"] > 1000000) {
    header("location:../qualifications.php?r=2290");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($has_file) {
        $certificate = file_get_contents($_FILES['certificate']['tmp_name']);
        $stmt = $conn->prepare("UPDATE tbl_professional_qualification SET country = :country, institution = :institution, title = :course, timeframe = :timeframe, certificate = :certificate WHERE id = :id AND member_no = :myid");
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':institution', $institution);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':timeframe', $timeframe);
        $stmt->bindParam(':certificate', $certificate, PDO::PARAM_LOB);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':myid', $myid);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("UPDATE tbl_professional_qualification SET country = :country, institution = :institution, title = :course, timeframe = :timeframe WHERE id = :id AND member_no = :myid");
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':institution', $institution);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':timeframe', $timeframe);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':myid', $myid);
        $stmt->execute();
    }

    header("location:../qualifications.php?r=6734");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    header("location:../qualifications.php?r=0011");
    exit();
}
?>