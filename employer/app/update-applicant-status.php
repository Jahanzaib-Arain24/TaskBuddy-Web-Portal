<?php
require '../../constants/db_config.php';
require '../constants/check-login.php';

$app_id = isset($_GET['appid']) ? (int)$_GET['appid'] : 0;
$job_id = isset($_GET['jobid']) ? trim($_GET['jobid']) : '';
$new_status = isset($_GET['status']) ? trim($_GET['status']) : 'Pending';

$allowed_statuses = ['Pending', 'Shortlisted', 'Selected', 'Rejected'];
if (!in_array($new_status, $allowed_statuses)) {
    $new_status = 'Pending';
}

if ($app_id > 0 && !empty($job_id)) {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Security check: verify the job belongs to this employer
        $stmtJob = $conn->prepare("SELECT job_id FROM tbl_jobs WHERE job_id = :jobid AND company = :myid");
        $stmtJob->bindParam(':jobid', $job_id);
        $stmtJob->bindParam(':myid', $myid);
        $stmtJob->execute();
        
        if ($stmtJob->rowCount() > 0) {
            $stmtUp = $conn->prepare("UPDATE tbl_job_applications SET status = :status WHERE id = :appid AND job_id = :jobid");
            $stmtUp->bindParam(':status', $new_status);
            $stmtUp->bindParam(':appid', $app_id);
            $stmtUp->bindParam(':jobid', $job_id);
            $stmtUp->execute();
            header("location:../view-applicants.php?jobid=" . urlencode($job_id) . "&status_updated=" . urlencode($new_status));
            exit();
        }
    } catch(PDOException $e) {
        error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    }
}

header("location:../view-applicants.php?jobid=" . urlencode($job_id));
exit();
