<?php
require_once __DIR__ . '/constants/check-login.php';
require_once __DIR__ . '/../constants/settings.php';
require_once __DIR__ . '/../constants/db_config.php';
require_once __DIR__ . '/../constants/csrf.php';

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' : ''; ?>Admin Control Panel | TaskBuddy</title>
	
	<link rel="shortcut icon" href="../images/ico/favicon.png">
	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css" media="screen">
	<link href="../icons/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="../css/main.css" rel="stylesheet">
	<link href="../css/style.css" rel="stylesheet">
	<link href="../css/custom_premium.css?v=2026.2" rel="stylesheet">
	
	<style>
		:root {
			--adm-primary: #4f46e5;
			--adm-primary-hover: #4338ca;
			--adm-dark: #0f172a;
			--adm-slate: #1e293b;
			--adm-bg: #f8fafc;
			--adm-card-border: #e2e8f0;
			--adm-text: #334155;
		}
		body {
			background: var(--adm-bg);
			font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			color: var(--adm-text);
			margin: 0;
			padding: 0;
		}
		.admin-topbar {
			background: #ffffff;
			border-bottom: 1px solid var(--adm-card-border);
			box-shadow: 0 1px 3px rgba(0,0,0,0.03);
			padding: 10px 0;
			position: sticky;
			top: 0;
			z-index: 1000;
		}
		.admin-topbar .container-fluid::before,
		.admin-topbar .container-fluid::after {
			display: none !important;
			content: none !important;
		}
		.admin-topbar-row {
			display: flex !important;
			align-items: center !important;
			justify-content: space-between !important;
			flex-wrap: nowrap !important;
			width: 100% !important;
			gap: 15px !important;
		}
		.admin-topbar-left {
			display: flex !important;
			align-items: center !important;
			gap: 20px !important;
			flex-shrink: 1 !important;
			min-width: 0 !important;
		}
		.admin-topbar-right {
			display: flex !important;
			align-items: center !important;
			gap: 10px !important;
			flex-shrink: 0 !important;
			white-space: nowrap !important;
		}
		.admin-brand {
			display: inline-flex;
			align-items: center;
			gap: 10px;
			font-weight: 800;
			font-size: 20px;
			color: var(--adm-dark);
			text-decoration: none !important;
			flex-shrink: 0;
		}
		.admin-brand i {
			color: var(--adm-primary);
		}
		.admin-badge-pill {
			background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
			color: #ffffff;
			font-size: 10.5px;
			font-weight: 800;
			padding: 3px 9px;
			border-radius: 20px;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25);
		}
		.admin-nav {
			display: flex !important;
			align-items: center !important;
			gap: 2px !important;
			list-style: none !important;
			margin: 0 !important;
			padding: 0 !important;
			flex-wrap: nowrap !important;
			float: none !important;
		}
		.admin-nav li {
			display: inline-flex !important;
			float: none !important;
			margin: 0 !important;
		}
		.admin-nav a {
			display: inline-flex !important;
			align-items: center !important;
			gap: 6px !important;
			padding: 7px 11px !important;
			border-radius: 8px !important;
			color: #64748b !important;
			font-weight: 600 !important;
			font-size: 13px !important;
			text-decoration: none !important;
			white-space: nowrap !important;
			transition: all 0.2s ease !important;
		}
		.admin-nav a:hover {
			background: #f1f5f9;
			color: var(--adm-dark);
			text-decoration: none;
		}
		.admin-nav a.active {
			background: #eef2ff;
			color: var(--adm-primary);
			font-weight: 700;
		}
		.admin-user-pill {
			display: inline-flex;
			align-items: center;
			gap: 6px;
			padding: 3px 9px;
			background: #f8fafc;
			border: 1px solid #e2e8f0;
			border-radius: 20px;
			font-size: 11.5px;
			font-weight: 600;
			color: var(--adm-dark);
		}
		.admin-user-avatar {
			width: 20px;
			height: 20px;
			background: var(--adm-primary);
			color: #ffffff;
			border-radius: 50%;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			font-size: 10px;
			font-weight: 700;
		}
		.admin-stat-box {
			background: #ffffff;
			border-radius: 14px;
			padding: 22px 24px;
			border: 1px solid var(--adm-card-border);
			box-shadow: 0 2px 4px rgba(0,0,0,0.02);
			display: flex;
			align-items: center;
			gap: 18px;
			margin-bottom: 24px;
			transition: transform 0.2s ease, box-shadow 0.2s ease;
		}
		.admin-stat-box:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 20px -5px rgba(0,0,0,0.06);
		}
		.admin-stat-icon-wrap {
			width: 54px;
			height: 54px;
			border-radius: 12px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 22px;
			flex-shrink: 0;
		}
		.admin-stat-number {
			font-size: 26px;
			font-weight: 800;
			color: var(--adm-dark);
			line-height: 1.1;
		}
		.admin-stat-label {
			color: #64748b;
			font-size: 13px;
			font-weight: 600;
			margin-top: 4px;
		}
		.admin-table-card {
			background: #ffffff;
			border-radius: 14px;
			border: 1px solid var(--adm-card-border);
			box-shadow: 0 2px 4px rgba(0,0,0,0.02);
			margin-bottom: 30px;
			overflow: hidden;
		}
		.admin-table-header {
			padding: 18px 24px;
			border-bottom: 1px solid var(--adm-card-border);
			display: flex;
			align-items: center;
			justify-content: space-between;
			flex-wrap: wrap;
			gap: 12px;
			background: #ffffff;
		}
		.admin-table-header h4 {
			margin: 0;
			font-size: 16.5px;
			font-weight: 700;
			color: var(--adm-dark);
		}
		.admin-table th {
			background: #f8fafc;
			color: #475569;
			font-weight: 700;
			font-size: 12px;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			border-top: none !important;
			padding: 13px 20px !important;
			border-bottom: 1px solid var(--adm-card-border) !important;
		}
		.admin-table td {
			padding: 14px 20px !important;
			vertical-align: middle !important;
			border-top: 1px solid #f1f5f9 !important;
			font-size: 13.5px;
			color: #334155;
		}
		.admin-badge-role {
			padding: 3px 9px;
			border-radius: 6px;
			font-size: 12px;
			font-weight: 700;
			display: inline-block;
		}
		.admin-badge-employer { background: #dbeafe; color: #1e40af; }
		.admin-badge-employee { background: #dcfce7; color: #166534; }
		.admin-badge-admin { background: #fef3c7; color: #92400e; }
		
		.admin-badge-status {
			display: inline-flex;
			align-items: center;
			gap: 5px;
			padding: 4px 10px;
			border-radius: 20px;
			font-size: 11.5px;
			font-weight: 700;
			letter-spacing: 0.2px;
		}
		.status-pending { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
		.status-shortlisted { background: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff; }
		.status-selected { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
		.status-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
		.status-under-review { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
	</style>
</head>
<body>

<header class="admin-topbar">
	<div class="container-fluid" style="max-width: 1400px; padding: 0 25px;">
		<div class="admin-topbar-row">
			<div class="admin-topbar-left">
				<a href="./" class="admin-brand">
					<img src="../logo2.png" alt="TaskBuddy" style="height: 38px; width: auto; object-fit: contain;" />
				</a>
				
				<ul class="admin-nav hidden-xs">
					<li><a href="./" class="<?php echo ($current_page == 'index.php' || $current_page == 'index' || $current_page == '') ? 'active' : ''; ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
					<li><a href="users" class="<?php echo ($current_page == 'users.php' || $current_page == 'users' || $current_page == 'user-detail.php' || $current_page == 'user-detail') ? 'active' : ''; ?>"><i class="fa fa-users"></i> Users</a></li>
					<li><a href="jobs" class="<?php echo ($current_page == 'jobs.php' || $current_page == 'jobs' || $current_page == 'job-detail.php' || $current_page == 'job-detail') ? 'active' : ''; ?>"><i class="fa fa-briefcase"></i> Tasks</a></li>
					<li><a href="applications" class="<?php echo ($current_page == 'applications.php' || $current_page == 'applications') ? 'active' : ''; ?>"><i class="fa fa-paper-plane"></i> Applications</a></li>
					<li><a href="categories" class="<?php echo ($current_page == 'categories.php' || $current_page == 'categories') ? 'active' : ''; ?>"><i class="fa fa-tags"></i> Categories</a></li>
					<li><a href="countries" class="<?php echo ($current_page == 'countries.php' || $current_page == 'countries') ? 'active' : ''; ?>"><i class="fa fa-globe"></i> Countries</a></li>
					<li><a href="alerts" class="<?php echo ($current_page == 'alerts.php' || $current_page == 'alerts') ? 'active' : ''; ?>"><i class="fa fa-bell-o"></i> Alerts</a></li>
				</ul>
			</div>
			
			<div class="admin-topbar-right">
				<a href="../" target="_blank" class="btn btn-xs btn-default" style="border-radius: 6px; font-weight: 600; font-size: 11.5px; padding: 4px 9px;">
					<i class="fa fa-external-link"></i> Live Site
				</a>
				
				<div class="admin-user-pill">
					<span class="admin-user-avatar"><i class="fa fa-shield"></i></span>
					<span><?php echo htmlspecialchars($admin_name); ?></span>
				</div>
				
				<a href="logout" class="btn btn-xs btn-danger" style="border-radius: 6px; font-weight: 600; font-size: 11.5px; padding: 4px 9px;" title="Logout from Admin">
					<i class="fa fa-sign-out"></i> Logout
				</a>
			</div>
		</div>
	</div>
</header>
<div style="padding: 28px 0 60px 0; min-height: calc(100vh - 350px);">
	<div class="container-fluid" style="max-width: 1400px; padding: 0 25px;">
