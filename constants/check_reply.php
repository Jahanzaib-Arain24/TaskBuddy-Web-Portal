<?php
require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/sanitizer.php';

if (isset($_GET['r'])) {
    $error_code = sanitize_alphanumeric($_GET['r'], '_-');

    $known_messages = [
        '1123' => [
            'description' => '<strong><i class="fa fa-check-circle" style="font-size:16px;"></i> Account Created Successfully!</strong> Welcome to TaskBuddy. Your profile is ready &mdash; <a href="login.php" style="color:#065f46; font-weight:700; text-decoration:underline; margin-left:4px;">Sign In Now &rarr;</a>',
            'type' => 'success'
        ],
        'rate_limited' => [
            'description' => '<i class="fa fa-exclamation-triangle"></i> <strong>Too many attempts.</strong> For security reasons, please wait a few minutes before trying again.',
            'type' => 'danger'
        ],
        'invalid_email' => [
            'description' => '<i class="fa fa-exclamation-circle"></i> Please provide a valid email address.',
            'type' => 'warning'
        ],
        'weak_password' => [
            'description' => '<i class="fa fa-exclamation-circle"></i> Password must be at least 6 characters long.',
            'type' => 'warning'
        ],
        'invalid_csrf' => [
            'description' => '<i class="fa fa-exclamation-triangle"></i> Session expired or security token mismatch. Please try again.',
            'type' => 'danger'
        ],
        '0927' => [
            'description' => '<i class="fa fa-info-circle"></i> <strong>Email already registered.</strong> This email address is already associated with an account. <a href="login.php" style="font-weight:700; text-decoration:underline; margin-left:4px;">Sign in instead &rarr;</a>',
            'type' => 'warning'
        ],
        '0346' => [
            'description' => '<i class="fa fa-exclamation-triangle"></i> <strong>Invalid Credentials:</strong> Incorrect email or password. Please try again.',
            'type' => 'danger'
        ]
    ];

    if (isset($known_messages[$error_code])) {
        $msg = $known_messages[$error_code];
        $type = $msg['type'];
        $alertClass = ($type === 'success') ? 'alert-success' : (($type === 'warning') ? 'alert-warning' : 'alert-danger');
        print '<div class="alert ' . $alertClass . '" style="border-radius:12px; font-size:13.5px; font-weight:500; padding:12px 16px; margin-bottom:20px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">' . $msg['description'] . '</div>';
    } else {
        try {
            $conn = get_db_connection();
            $stmt = $conn->prepare("SELECT * FROM tbl_alerts WHERE code = :errorcode LIMIT 1");
            $stmt->bindParam(':errorcode', $error_code);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $description = $row['description'];
                $type = $row['type'];
                $alertClass = ($type === 'success') ? 'alert-success' : (($type === 'warning') ? 'alert-warning' : 'alert-danger');
                print '<div class="alert ' . $alertClass . '" style="border-radius:12px; font-size:13.5px; font-weight:500; padding:12px 16px; margin-bottom:20px; box-shadow:0 4px 12px rgba(0,0,0,0.05);">' . escape_html($description) . '</div>';
            }
        } catch (PDOException $e) {
            error_log("Alerts query error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
        }
    }
}
?>