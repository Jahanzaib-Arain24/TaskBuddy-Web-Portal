<?php
require_once __DIR__ . '/../constants/check-login.php';
require_once __DIR__ . '/../../constants/db_config.php';
require_once __DIR__ . '/../../constants/csrf.php';

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

// Validate CSRF for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!validate_csrf_token($csrf)) {
        header("location:../?err=csrf");
        exit();
    }
}

$conn = get_db_connection();

try {
    if ($action === 'delete_user') {
        $member_no = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!empty($member_no) && $member_no !== $admin_id) {
            // Delete user and related records
            $stmt = $conn->prepare("DELETE FROM tbl_users WHERE member_no = :memno");
            $stmt->bindParam(':memno', $member_no);
            $stmt->execute();

            $stmt2 = $conn->prepare("DELETE FROM tbl_jobs WHERE company = :memno");
            $stmt2->bindParam(':memno', $member_no);
            $stmt2->execute();

            $stmt3 = $conn->prepare("DELETE FROM tbl_job_applications WHERE member_no = :memno");
            $stmt3->bindParam(':memno', $member_no);
            $stmt3->execute();
        }
        header("location:../users?msg=deleted");
        exit();

    } elseif ($action === 'delete_job') {
        $job_id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!empty($job_id)) {
            $stmt = $conn->prepare("DELETE FROM tbl_jobs WHERE job_id = :jobid");
            $stmt->bindParam(':jobid', $job_id);
            $stmt->execute();

            $stmt2 = $conn->prepare("DELETE FROM tbl_job_applications WHERE job_id = :jobid");
            $stmt2->bindParam(':jobid', $job_id);
            $stmt2->execute();
        }
        header("location:../jobs?msg=deleted");
        exit();

    } elseif ($action === 'add_category') {
        $category = isset($_POST['category']) ? trim($_POST['category']) : '';
        if (!empty($category)) {
            $stmt = $conn->prepare("INSERT INTO tbl_categories (category) VALUES (:category)");
            $stmt->bindParam(':category', $category);
            $stmt->execute();
        }
        header("location:../categories?msg=added");
        exit();

    } elseif ($action === 'delete_category') {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM tbl_categories WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
        header("location:../categories?msg=deleted");
        exit();

    } elseif ($action === 'add_country') {
        $country = isset($_POST['country_name']) ? trim($_POST['country_name']) : '';
        if (!empty($country)) {
            $stmt = $conn->prepare("INSERT INTO tbl_countries (country_name) VALUES (:cnt)");
            $stmt->bindParam(':cnt', $country);
            $stmt->execute();
        }
        header("location:../countries?msg=added");
        exit();

    } elseif ($action === 'delete_country') {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $stmt = $conn->prepare("DELETE FROM tbl_countries WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }
        header("location:../countries?msg=deleted");
        exit();

    } elseif ($action === 'update_alert') {
        $code = isset($_POST['code']) ? trim($_POST['code']) : '';
        $desc = isset($_POST['description']) ? trim($_POST['description']) : '';
        $type = isset($_POST['type']) ? trim($_POST['type']) : 'info';
        if (!empty($code)) {
            $stmt = $conn->prepare("UPDATE tbl_alerts SET description = :desc, type = :type WHERE code = :code");
            $stmt->bindParam(':desc', $desc);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':code', $code);
            $stmt->execute();
        }
        header("location:../alerts?msg=updated");
        exit();
    } elseif ($action === 'update_application_status') {
        $app_id = isset($_POST['application_id']) ? (int)$_POST['application_id'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
        $new_status = isset($_POST['status']) ? trim($_POST['status']) : (isset($_GET['status']) ? trim($_GET['status']) : '');
        $allowed = ['Pending', 'Under Review', 'Shortlisted', 'Selected', 'Rejected'];
        
        if ($app_id > 0 && in_array($new_status, $allowed)) {
            $stmt = $conn->prepare("UPDATE tbl_job_applications SET status = :st WHERE id = :id");
            $stmt->bindParam(':st', $new_status);
            $stmt->bindParam(':id', $app_id);
            $stmt->execute();
        }
        
        $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : (isset($_GET['redirect']) ? $_GET['redirect'] : '../applications.php?msg=status_updated');
        header("location:" . $redirect);
        exit();

    } elseif ($action === 'delete_application') {
        $app_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($app_id > 0) {
            $stmt = $conn->prepare("DELETE FROM tbl_job_applications WHERE id = :id");
            $stmt->bindParam(':id', $app_id);
            $stmt->execute();
        }
        $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '../applications.php?msg=deleted';
        header("location:" . $redirect);
        exit();
    }
} catch (PDOException $e) {
    error_log("Admin action error: " . $e->getMessage());
    header("location:../?err=failed");
    exit();
}

header("location:../");
exit();
?>
