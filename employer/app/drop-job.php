<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$job_id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($job_id)) {
    header("location:../my-jobs.php");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("DELETE FROM tbl_jobs WHERE job_id = :jobid AND company = :myid");
    $stmt->bindParam(':jobid', $job_id);
    $stmt->bindParam(':myid', $myid);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM tbl_job_applications WHERE job_id = :jobid");
    $stmt->bindParam(':jobid', $job_id);
    $stmt->execute();

    header("location:../my-jobs.php?r=0173");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    header("location:../my-jobs.php");
    exit();
}
?>