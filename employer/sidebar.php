<?php
if (!function_exists('get_initials_avatar')) {
    require_once __DIR__ . '/../constants/settings.php';
}

$current_script = basename($_SERVER['PHP_SELF']);
$user_display_name = !empty($compname) ? $compname : 'Company / Task Lister';

if (empty($logo)) {
    $sidebar_avatar_url = get_initials_avatar($user_display_name, '', 120);
} else {
    $sidebar_avatar_url = 'data:image/jpeg;base64,' . base64_encode($logo);
}
?>
<div class="admin-sidebar employee-sidebar-card employer-sidebar-card">
	<div class="admin-user-item for-employer">
		<div class="image employee-avatar-wrapper">	
			<img class="img-circle autofit2 employee-avatar-img" alt="<?php echo htmlspecialchars($user_display_name); ?>" title="<?php echo htmlspecialchars($user_display_name); ?>" src="<?php echo $sidebar_avatar_url; ?>" />
		</div>
		
		<h4 class="employee-name"><?php echo htmlspecialchars($user_display_name); ?></h4>
		<p class="user-role"><i class="fa fa-building-o" style="color: #2563eb;"></i> Task Lister & Employer</p>
	</div>
	
	<div class="admin-user-action text-center">
		<a href="post-job" class="btn btn-primary btn-cv-view">
			<i class="fa fa-plus-circle"></i> Post a Task
		</a>
	</div>
	
	<ul class="admin-user-menu clearfix">
		<li class="<?php echo ($current_script == 'index.php' || $current_script == '') ? 'active' : ''; ?>">
			<a href="./"><i class="fa fa-user"></i> Profile</a>
		</li>
		<li class="<?php echo ($current_script == 'change-password.php') ? 'active' : ''; ?>">
			<a href="change-password"><i class="fa fa-key"></i> Change Password</a>
		</li>
		<li>
			<a href="../lister/<?php echo htmlspecialchars($myid); ?>"><i class="fa fa-briefcase"></i> Company Overview</a>
		</li>
		<li class="<?php echo ($current_script == 'my-jobs.php' || $current_script == 'edit-job.php' || $current_script == 'view-applicants.php') ? 'active' : ''; ?>">
			<a href="my-jobs"><i class="fa fa-bookmark"></i> Posted Jobs</a>
		</li>
		<li>
			<a href="../logout"><i class="fa fa-sign-out"></i> Logout</a>
		</li>
	</ul>
</div>
