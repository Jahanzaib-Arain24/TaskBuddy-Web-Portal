<?php
require_once __DIR__ . '/../../constants/db_config.php';
require_once __DIR__ . '/../constants/check-login.php';

if (!isset($_FILES['image']['tmp_name']) || empty($_FILES['image']['tmp_name']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    header("location:../");
    exit();
}

$tmp_file = $_FILES['image']['tmp_name'];
$file_size = $_FILES['image']['size'];

// Enforce max 2MB size limit
if ($file_size > (2 * 1024 * 1024)) {
    header("location:../?r=3478");
    exit();
}

// Strict MIME type verification using finfo and getimagesize
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $tmp_file);
finfo_close($finfo);

$allowed_mimes = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
    'image/gif' => 'gif'
];

if (!isset($allowed_mimes[$mime_type])) {
    header("location:../?r=3478");
    exit();
}

// Verify actual image structure with getimagesize
$image_info = @getimagesize($tmp_file);
if ($image_info === false) {
    header("location:../?r=3478");
    exit();
}

$extension = $allowed_mimes[$mime_type];
$upload_dir = __DIR__ . '/../../uploads/avatars/';
if (!is_dir($upload_dir)) {
    @mkdir($upload_dir, 0755, true);
}

// Save securely to filesystem
$safe_filename = 'AV_' . preg_replace('/[^a-zA-Z0-9]/', '', $myid) . '_' . time() . '.' . $extension;
@copy($tmp_file, $upload_dir . $safe_filename);

$image = file_get_contents($tmp_file);

try {
    $conn = get_db_connection();

    $stmt = $conn->prepare("UPDATE tbl_users SET avatar = :avatar WHERE member_no = :myid");
    $stmt->bindParam(':avatar', $image, PDO::PARAM_LOB);
    $stmt->bindParam(':myid', $myid);
    $stmt->execute();

    $_SESSION['avatar'] = $image;
    header("location:../");
    exit();
} catch (PDOException $e) {
    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    header("location:../");
    exit();
}
?>