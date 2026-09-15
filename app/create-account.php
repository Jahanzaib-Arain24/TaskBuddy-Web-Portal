<?php
require_once __DIR__ . '/../constants/settings.php';
require_once __DIR__ . '/../constants/csrf.php';
require_once __DIR__ . '/../constants/rate_limiter.php';
require_once __DIR__ . '/../constants/sanitizer.php';

date_default_timezone_set(!empty($default_timezone) ? $default_timezone : 'Asia/Karachi');

if (isset($_POST['reg_mode'])) {
    $account_type = isset($_POST['acctype']) ? sanitize_alphanumeric($_POST['acctype']) : '101';
    $role = ($account_type == "101") ? "Employee" : "Employer";

    // Check rate limiting on registration (max 8 registration attempts per 15 min per IP)
    if (!check_rate_limit('registration_attempt', 8, 900)) {
        header("location:../register.php?p=$role&r=rate_limited");
        exit();
    }

    $csrf = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
    if (!empty($csrf) && !validate_csrf_token($csrf)) {
        header("location:../register.php?p=$role&r=invalid_csrf");
        exit();
    }
    checkemail();	
} else {
    header("location:../");
    exit();
}

function checkemail() {
    $raw_email = isset($_POST['email']) ? $_POST['email'] : '';
    $account_type = isset($_POST['acctype']) ? sanitize_alphanumeric($_POST['acctype']) : '101';
    $role = ($account_type == "101") ? "Employee" : "Employer";

    $email = validate_email_address($raw_email);
    if (!$email) {
        header("location:../register.php?p=$role&r=invalid_email");
        exit();
    }

    $raw_password = isset($_POST['password']) ? (string)$_POST['password'] : '';
    if (strlen($raw_password) < 6) {
        header("location:../register.php?p=$role&r=weak_password");
        exit();
    }

    try {
        require_once '../constants/db_config.php';
        $conn = get_db_connection();
        
        $stmt = $conn->prepare("SELECT email FROM tbl_users WHERE LOWER(email) = LOWER(:email) LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        if (count($result) > 0) {
            record_rate_limit_attempt('registration_attempt', 900);
            header("location:../register.php?p=$role&r=0927");
            exit();
        } else {
            if ($account_type == "101") {
                register_as_employee($email, $raw_password);
            } else {
                register_as_employer($email, $raw_password);
            }
        }
    } catch (PDOException $e) {
        error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
        header("location:../register.php?p=$role&r=4568");
        exit();
    }
}

function register_as_employee($email, $raw_password) {
    try {
        require_once '../constants/db_config.php';
        require_once '../constants/uniques.php';
        $role = 'employee';
        $last_login = date('d-m-Y h:i A [T P]');
        $member_no = 'EM' . get_rand_numbers(9);
        $fname = ucwords(sanitize_input($_POST['fname']));
        $lname = ucwords(sanitize_input($_POST['lname']));
        $login = password_hash($raw_password, PASSWORD_BCRYPT);
        
        $conn = get_db_connection();
        $stmt = $conn->prepare("INSERT INTO tbl_users (first_name, last_name, email, last_login, login, role, member_no) 
        VALUES (:fname, :lname, :email, :lastlogin, :login, :role, :memberno)");
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':lastlogin', $last_login);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':memberno', $member_no);
        $stmt->execute();
        
        reset_rate_limit('registration_attempt');
        header("location:../register.php?p=Employee&r=1123");
        exit();
    } catch (PDOException $e) {
        error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
        header("location:../register.php?p=Employee&r=4568");
        exit();
    }
}

function register_as_employer($email, $raw_password) {
    try {
        require_once '../constants/db_config.php';
        require_once '../constants/uniques.php';
        $role = 'employer';
        $last_login = date('d-m-Y h:i A [T P]');
        $comp_no = 'CM' . get_rand_numbers(9);
        $cname = ucwords(sanitize_input($_POST['company']));
        $ctype = ucwords(sanitize_input($_POST['type']));
        $login = password_hash($raw_password, PASSWORD_BCRYPT);
        
        $conn = get_db_connection();
        $stmt = $conn->prepare("INSERT INTO tbl_users (first_name, title, email, last_login, login, role, member_no) 
        VALUES (:fname, :title, :email, :lastlogin, :login, :role, :memberno)");
        $stmt->bindParam(':fname', $cname);
        $stmt->bindParam(':title', $ctype);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':lastlogin', $last_login);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':memberno', $comp_no);
        $stmt->execute();
        
        reset_rate_limit('registration_attempt');
        header("location:../register.php?p=Employer&r=1123");
        exit();
    } catch (PDOException $e) {
        error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
        header("location:../register.php?p=Employer&r=4568");
        exit();
    }
}
?>