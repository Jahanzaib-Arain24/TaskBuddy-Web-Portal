<?php
if (!function_exists('get_initials_avatar')) {
    require_once __DIR__ . '/../constants/settings.php';
}

$current_script = basename($_SERVER['PHP_SELF']);
$req_path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$cur_slug = basename($req_path);

$user_display_name = trim(($myfname ?? '') . ' ' . ($mylname ?? ''));
if (empty($user_display_name)) {
    $user_display_name = 'Muhammad Jahanzaib';
}
$user_display_title = !empty($mytitle) ? $mytitle : 'Task Seeker & Specialist';

if (empty($myavatar)) {
    $sidebar_avatar_url = get_initials_avatar($myfname ?? 'M', $mylname ?? 'J', 120);
} else {
    $sidebar_avatar_url = 'data:image/jpeg;base64,' . base64_encode($myavatar);
}
?>
<div class="admin-sidebar employee-sidebar-card">
	<div class="admin-user-item">
		<div class="image employee-avatar-wrapper">	
			<img class="img-circle autofit2 employee-avatar-img" alt="<?php echo htmlspecialchars($user_display_name); ?>" title="<?php echo htmlspecialchars($user_display_name); ?>" src="<?php echo $sidebar_avatar_url; ?>" />
		</div>
		
		<h4 class="employee-name"><?php echo htmlspecialchars($user_display_name); ?></h4>
		<p class="user-role"><?php echo htmlspecialchars($user_display_title); ?></p>
	</div>
	
	<div class="admin-user-action text-center">
		<a target="_blank" href="my_cv" class="btn btn-primary btn-cv-view">
			<i class="fa fa-file-text-o"></i> View My CV
		</a>
	</div>
	
	<ul class="admin-user-menu clearfix">
		<li class="<?php echo ($current_script == 'index.php' || $cur_slug == 'employee' || $cur_slug == '') ? 'active' : ''; ?>">
			<a href="./"><i class="fa fa-user"></i> Profile</a>
		</li>
		<li class="<?php echo ($current_script == 'change-password.php' || $cur_slug == 'change-password') ? 'active' : ''; ?>">
			<a href="change-password"><i class="fa fa-key"></i> Change Password</a>
		</li>
		<li class="<?php echo ($current_script == 'qualifications.php' || $cur_slug == 'qualifications') ? 'active' : ''; ?>">
			<a href="qualifications"><i class="fa fa-trophy"></i> Professional Qualifications</a>
		</li>
		<li class="<?php echo ($current_script == 'language.php' || $cur_slug == 'language') ? 'active' : ''; ?>">
			<a href="language"><i class="fa fa-language"></i> Language Proficiency</a>
		</li>
		<li class="<?php echo ($current_script == 'training.php' || $cur_slug == 'training') ? 'active' : ''; ?>">
			<a href="training"><i class="fa fa-gears"></i> Training & Workshop</a>
		</li>
		<li class="<?php echo ($current_script == 'referees.php' || $cur_slug == 'referees') ? 'active' : ''; ?>">
			<a href="referees"><i class="fa fa-users"></i> Referees</a>
		</li>
		<li class="<?php echo ($current_script == 'academic.php' || $cur_slug == 'academic') ? 'active' : ''; ?>">
			<a href="academic"><i class="fa fa-graduation-cap"></i> Academic Qualifications</a>
		</li>
		<li class="<?php echo ($current_script == 'experience.php' || $cur_slug == 'experience') ? 'active' : ''; ?>">
			<a href="experience"><i class="fa fa-briefcase"></i> Working Experience</a>
		</li>
		<li class="<?php echo ($current_script == 'attachments.php' || $cur_slug == 'attachments') ? 'active' : ''; ?>">
			<a href="attachments"><i class="fa fa-folder-open"></i> Other Attachments</a>
		</li>
		<li class="<?php echo ($current_script == 'applied-jobs.php' || $cur_slug == 'applied-jobs') ? 'active' : ''; ?>">
			<a href="applied-jobs"><i class="fa fa-bookmark"></i> Applied Jobs</a>
		</li>
		<li>
			<a href="../logout"><i class="fa fa-sign-out"></i> Logout</a>
		</li>
	</ul>
</div>
