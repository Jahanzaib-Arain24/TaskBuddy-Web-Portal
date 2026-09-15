<?php
require '../constants/settings.php';
date_default_timezone_set(!empty($default_timezone) ? $default_timezone : 'Asia/Karachi');
$apply_date = date('m/d/Y');

session_start();
if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    $myid = $_SESSION['myid'];	
    $myrole = $_SESSION['role'];
    $opt = isset($_GET['opt']) ? $_GET['opt'] : '';

    if ($myrole == "employee" && !empty($opt)) {
        include '../constants/db_config.php';

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT id FROM tbl_job_applications WHERE member_no = :memberno AND job_id = :jobid");
            $stmt->bindParam(':memberno', $myid);
            $stmt->bindParam(':jobid', $opt);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $rec = count($result);
            
            if ($rec == 0) {
                try {
                    $stmt = $conn->prepare("INSERT INTO tbl_job_applications (member_no, job_id, application_date)
                    VALUES (:memberno, :jobid, :appdate)");
                    $stmt->bindParam(':memberno', $myid);
                    $stmt->bindParam(':jobid', $opt);
                    $stmt->bindParam(':appdate', $apply_date);
                    $stmt->execute();
                    
                    print '<br>
                     <div class="alert alert-success" style="border-radius: 8px; font-weight: 500; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                      <span><i class="fa fa-check-circle"></i> You have successfully applied for this task.</span>
                      <a href="applied-jobs.php" class="btn btn-sm btn-primary" style="padding: 4px 12px; font-size: 12px; border-radius: 6px;"><i class="fa fa-bookmark"></i> View Applied Tasks</a>
                     </div>
                     ';
                } catch (PDOException $e) {
                    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
                }
            } else {
                print '<br>
                 <div class="alert alert-warning" style="border-radius: 8px; font-weight: 500; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                  <span><i class="fa fa-exclamation-triangle"></i> You have already applied for this task before.</span>
                  <a href="applied-jobs.php" class="btn btn-sm btn-default" style="padding: 4px 12px; font-size: 12px; border-radius: 6px;"><i class="fa fa-bookmark"></i> Check Status</a>
                 </div>
                 ';
            }
        } catch (PDOException $e) {
            error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
        }
    }
}
?>