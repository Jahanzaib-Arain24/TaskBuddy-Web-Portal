<?php
require_once __DIR__ . '/../constants/settings.php';
require_once __DIR__ . '/../constants/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in as admin, redirect to admin dashboard
if (isset($_SESSION['logged']) && $_SESSION['logged'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("location:./");
    exit();
}

$error_msg = "";
if (isset($_GET['err'])) {
    if ($_GET['err'] == 'invalid') {
        $error_msg = "Invalid admin username or password. Access restricted to authorized personnel.";
    } elseif ($_GET['err'] == 'rate_limit') {
        $error_msg = "Too many failed login attempts. For security, control center access is temporarily locked. Please try again after 15 minutes.";
    } elseif ($_GET['err'] == 'access_denied') {
        $error_msg = "Access Denied: Only Administrator accounts can access this portal.";
    } elseif ($_GET['err'] == 'csrf') {
        $error_msg = "Session expired or invalid security token. Please try again.";
    } elseif ($_GET['err'] == 'empty') {
        $error_msg = "Please enter both admin username/email and password.";
    }
}
$success_msg = "";
if (isset($_GET['r']) && $_GET['r'] == 'logout') {
    $success_msg = "You have been successfully logged out of the Admin Control Center.";
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Control Center Login | TaskBuddy</title>
	
	<link rel="shortcut icon" href="../images/ico/favicon.png">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css" media="screen">
	<link href="../icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
		
		* {
			box-sizing: border-box;
		}
		body {
			background: #090d16 radial-gradient(circle at 50% 20%, #1e1b4b 0%, #090d16 70%);
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			margin: 0;
			padding: 24px;
			color: #1e293b;
		}
		.admin-login-wrapper {
			width: 100%;
			max-width: 440px;
			animation: slideUp 0.35s ease-out;
		}
		@keyframes slideUp {
			from { opacity: 0; transform: translateY(16px); }
			to { opacity: 1; transform: translateY(0); }
		}
		.admin-login-card {
			background: #ffffff;
			border-radius: 20px;
			box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.12);
			overflow: hidden;
		}
		.admin-header-box {
			background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
			padding: 34px 28px 30px 28px;
			text-align: center;
			border-bottom: 1px solid rgba(255, 255, 255, 0.08);
			position: relative;
		}
		.admin-icon-circle {
			width: 62px;
			height: 62px;
			background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
			color: #ffffff !important;
			border-radius: 16px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-size: 28px;
			box-shadow: 0 10px 25px rgba(99, 102, 241, 0.45);
			margin-bottom: 16px;
		}
		.admin-brand-heading {
			color: #ffffff !important;
			font-size: 23px !important;
			font-weight: 800 !important;
			margin: 0 0 6px 0 !important;
			letter-spacing: -0.4px;
			text-shadow: 0 2px 4px rgba(0,0,0,0.3);
		}
		.admin-brand-sub {
			color: #94a3b8 !important;
			font-size: 13.5px !important;
			font-weight: 500 !important;
			margin: 0 !important;
		}
		.admin-card-content {
			padding: 30px 30px 34px 30px;
			background: #ffffff;
		}
		.admin-field-group {
			margin-bottom: 20px;
		}
		.admin-field-label {
			display: flex;
			align-items: center;
			justify-content: space-between;
			font-size: 12.5px;
			font-weight: 700;
			color: #334155;
			margin-bottom: 8px;
			text-transform: uppercase;
			letter-spacing: 0.6px;
		}
		.admin-input-container {
			position: relative;
			display: flex;
			align-items: center;
		}
		.admin-input-icon {
			position: absolute;
			left: 15px;
			color: #64748b;
			font-size: 15px;
			pointer-events: none;
		}
		.admin-custom-input {
			width: 100%;
			height: 48px;
			padding: 0 44px 0 42px;
			border: 1.5px solid #cbd5e1;
			border-radius: 10px;
			font-size: 14.5px;
			color: #0f172a;
			background: #f8fafc;
			font-weight: 500;
			transition: all 0.2s ease;
		}
		.admin-custom-input:focus {
			border-color: #4f46e5;
			background: #ffffff;
			box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
			outline: none;
		}
		.admin-eye-toggle {
			position: absolute;
			right: 12px;
			color: #64748b;
			cursor: pointer;
			font-size: 15px;
			padding: 6px;
			border-radius: 6px;
			transition: color 0.15s;
		}
		.admin-eye-toggle:hover {
			color: #0f172a;
			background: #f1f5f9;
		}
		.admin-login-button {
			width: 100%;
			height: 50px;
			background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
			color: #ffffff !important;
			border: none;
			border-radius: 10px;
			font-size: 15.5px;
			font-weight: 700;
			cursor: pointer;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 10px;
			box-shadow: 0 10px 22px -4px rgba(79, 70, 229, 0.45);
			transition: all 0.2s ease;
			margin-top: 26px;
		}
		.admin-login-button:hover {
			background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%) !important;
			box-shadow: 0 12px 26px -4px rgba(79, 70, 229, 0.55);
			transform: translateY(-1px);
			color: #ffffff !important;
		}
		.admin-footer-link {
			display: block;
			text-align: center;
			margin-top: 22px;
			color: #64748b;
			font-size: 13.5px;
			font-weight: 600;
			text-decoration: none;
			transition: color 0.2s;
		}
		.admin-footer-link:hover {
			color: #4f46e5;
			text-decoration: none;
		}
	</style>
</head>
<body>

<div class="admin-login-wrapper">
	<div class="admin-login-card">
		<div class="admin-header-box">
			<div class="admin-icon-circle">
				<i class="fa fa-shield"></i>
			</div>
			<h2 class="admin-brand-heading">TaskBuddy Admin</h2>
			<p class="admin-brand-sub">System Administration & Control Center</p>
		</div>

		<div class="admin-card-content">
			<?php if (!empty($error_msg)): ?>
				<div class="alert alert-danger" style="border-radius: 8px; font-size: 13px; font-weight: 500; margin-bottom: 18px; padding: 11px 14px;">
					<i class="fa fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_msg); ?>
				</div>
			<?php endif; ?>

			<?php if (!empty($success_msg)): ?>
				<div class="alert alert-success" style="border-radius: 8px; font-size: 13px; font-weight: 500; margin-bottom: 18px; padding: 11px 14px;">
					<i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($success_msg); ?>
				</div>
			<?php endif; ?>

			<form method="POST" action="app/auth.php" autocomplete="off">
				<?php echo csrf_field(); ?>
				
				<div class="admin-field-group">
					<label class="admin-field-label">
						<span><i class="fa fa-envelope-o"></i> Admin Username / Email</span>
					</label>
					<div class="admin-input-container">
						<i class="fa fa-user admin-input-icon"></i>
						<input type="text" name="email" id="adminEmailInput" class="admin-custom-input" placeholder="Enter admin username or email" required autofocus>
					</div>
				</div>

				<div class="admin-field-group">
					<label class="admin-field-label">
						<span><i class="fa fa-lock"></i> Admin Password</span>
					</label>
					<div class="admin-input-container">
						<i class="fa fa-key admin-input-icon"></i>
						<input type="password" name="password" id="adminPasswordInput" class="admin-custom-input" placeholder="Enter admin password" required>
						<span class="admin-eye-toggle" onclick="toggleAdminPassword()"><i class="fa fa-eye" id="eyeIcon"></i></span>
					</div>
				</div>

				<button type="submit" class="admin-login-button">
					<span>Sign In to Control Center</span>
					<i class="fa fa-arrow-right"></i>
				</button>

				<a href="../" class="admin-footer-link">
					<i class="fa fa-arrow-left"></i> Return to Main Website
				</a>
			</form>
		</div>
	</div>
</div>

<script>
function toggleAdminPassword() {
	var input = document.getElementById('adminPasswordInput');
	var icon = document.getElementById('eyeIcon');
	if (input.type === 'password') {
		input.type = 'text';
		icon.className = 'fa fa-eye-slash';
	} else {
		input.type = 'password';
		icon.className = 'fa fa-eye';
	}
}
</script>

</body>
</html>
