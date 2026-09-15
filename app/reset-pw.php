<?php
$opt = $_GET['opt'];
require_once __DIR__ . '/../constants/settings.php';
require_once __DIR__ . '/../constants/db_config.php';
require_once __DIR__ . '/../constants/uniques.php';

    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE email = :email");
	$stmt->bindParam(':email', $opt);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $rec = count($result);
	
	if ($rec == "0") {
	    print '
	 <div class="alert alert-warning">
     No account is associated with email <strong>'.$opt.'</strong>
	 </div>
     ';
		
	}else{
    foreach($result as $row)
    {
	
    $myfname = $row['first_name'];
	$mylname = $row['last_name'];
	$mymail = $row['email'];
	$full_name = "$myfname $mylname";
	$idt = 'token'.get_rand_numbers(17).'';
    $token = md5($idt);

    $stmt_del = $conn->prepare("DELETE FROM tbl_tokens WHERE email = :email");
    $stmt_del->bindParam(':email', $opt);
    $stmt_del->execute();

    $stmt_ins = $conn->prepare("INSERT INTO tbl_tokens (email, token) VALUES (:email, :token)");
    $stmt_ins->bindParam(':email', $opt);
    $stmt_ins->bindParam(':token', $token);
    $stmt_ins->execute();

	$scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
	$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
	$def_link = $scheme . '://' . $host . '/reset.php?token=' . $token;

	$message = "Hello <b>" . htmlspecialchars($full_name) . "</b>,<br><br>We received a request to reset your <b>TaskBuddy</b> password.<br><br>Click the link below to set a new password:<br><a href='" . $def_link . "'>" . $def_link . "</a><br><br>If you did not request this, please ignore this email.";   
    
    $is_localhost = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '::1']) || strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false;

    $mail_sent = false;
    if (!empty($smtp_host) && !empty($smtp_user) && !empty($smtp_pass) && !$is_localhost) {
        try {
            require_once '../mail/PHPMailerAutoload.php';
            $mail = new PHPMailer;
            $mail->isSMTP();                                      
            $mail->Host = $smtp_host;
            $mail->SMTPAuth = true;                           
            $mail->Username = $smtp_user;               
            $mail->Password = $smtp_pass;                          
            $mail->SMTPSecure = 'tls';                            
            $mail->Port = 587;                                   

            $mail->setFrom($smtp_user, 'TaskBuddy Support');
            $mail->addAddress($mymail, $full_name);              
            $mail->isHTML(true);                                 
            $mail->Subject = 'Password Reset - TaskBuddy';
            $mail->Body    = $message;
            $mail->AltBody = strip_tags($message);
            $mail_sent = $mail->send();
        } catch (Exception $ex) {
            $mail_sent = false;
        }
    }

    if ($mail_sent) {
        print '
        <div class="alert alert-success" style="border-radius: 8px; padding: 15px; margin-top: 15px;">
            <i class="fa fa-check-circle"></i> A password reset link has been sent to <strong>'.htmlspecialchars($mymail).'</strong>. Please check your inbox.
        </div>';
    } else {
        // Localhost / Development Direct Reset Helper
        print '
        <div class="alert alert-info" style="border-radius: 8px; padding: 15px; margin-top: 15px; background: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1;">
            <p style="margin-bottom: 10px; font-weight: 600;"><i class="fa fa-key"></i> Password Reset Token Generated Successfully!</p>
            <p style="margin-bottom: 12px; font-size: 13px;">Since this is running in local environment (or SMTP is offline), you can click below directly to reset your password:</p>
            <a href="'.$def_link.'" class="btn btn-primary btn-sm" style="display: inline-block; background: #4f46e5; border-color: #4f46e5; color: #fff; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600;">
                <i class="fa fa-unlock-alt"></i> Reset Password Now
            </a>
        </div>';
    }
    }
} 
					  
	}catch(PDOException $e)
    {
        print '<div class="alert alert-danger">An unexpected database error occurred. Please try again.</div>';
    }
?>
