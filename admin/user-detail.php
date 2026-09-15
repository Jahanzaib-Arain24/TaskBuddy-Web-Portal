<?php
$page_title = "User Profile & History";
require_once __DIR__ . '/header.php';

$conn = get_db_connection();

$member_no = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($member_no)) {
    echo '<div class="alert alert-danger" style="border-radius: 8px;">Invalid Member ID requested. <a href="users">Go back to users</a>.</div>';
    require_once __DIR__ . '/footer.php';
    exit();
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM tbl_users WHERE member_no = :memno LIMIT 1");
$stmt->bindParam(':memno', $member_no);
$stmt->execute();
$user = $stmt->fetch();

if (!$user) {
    echo '<div class="alert alert-danger" style="border-radius: 8px;">User not found. <a href="users">Go back to users</a>.</div>';
    require_once __DIR__ . '/footer.php';
    exit();
}

$role = $user['role']; // 'employee' or 'employer'

// Fetch additional seeker details if employee
$academics = [];
$experiences = [];
$attachments = [];
$prof_quals = [];
$trainings = [];
$applications = [];

// Employer specifics
$posted_jobs = [];
$employer_all_bids = [];

if ($role === 'employee') {
    // Academics
    $q = $conn->prepare("SELECT * FROM tbl_academic_qualification WHERE member_no = :m ORDER BY id DESC");
    $q->execute([':m' => $member_no]);
    $academics = $q->fetchAll();

    // Experience
    $q = $conn->prepare("SELECT * FROM tbl_experience WHERE member_no = :m ORDER BY id DESC");
    $q->execute([':m' => $member_no]);
    $experiences = $q->fetchAll();

    // Attachments (CV / Certs)
    $q = $conn->prepare("SELECT id, member_no, title, issuer FROM tbl_other_attachments WHERE member_no = :m ORDER BY id DESC");
    $q->execute([':m' => $member_no]);
    $attachments = $q->fetchAll();

    // Applications submitted by this user
    $q = $conn->prepare("SELECT a.*, j.title as job_title, j.category as job_category, j.type as job_type, j.city as job_city, j.country as job_country,
                                c.first_name as company_name, c.member_no as company_member_no
                         FROM tbl_job_applications a
                         LEFT JOIN tbl_jobs j ON a.job_id = j.job_id
                         LEFT JOIN tbl_users c ON j.company = c.member_no
                         WHERE a.member_no = :m
                         ORDER BY a.id DESC");
    $q->execute([':m' => $member_no]);
    $applications = $q->fetchAll();

} elseif ($role === 'employer') {
    // Posted Tasks
    $q = $conn->prepare("SELECT j.*, (SELECT COUNT(*) FROM tbl_job_applications a WHERE a.job_id = j.job_id) as app_count
                         FROM tbl_jobs j
                         WHERE j.company = :m
                         ORDER BY j.enc_id DESC");
    $q->execute([':m' => $member_no]);
    $posted_jobs = $q->fetchAll();

    // All applications received for all jobs by this employer
    $q = $conn->prepare("SELECT a.*, u.first_name as applicant_fname, u.last_name as applicant_lname, u.email as applicant_email, u.phone as applicant_phone,
                                j.title as job_title, j.category as job_category
                         FROM tbl_job_applications a
                         INNER JOIN tbl_jobs j ON a.job_id = j.job_id
                         LEFT JOIN tbl_users u ON a.member_no = u.member_no
                         WHERE j.company = :m
                         ORDER BY a.id DESC");
    $q->execute([':m' => $member_no]);
    $employer_all_bids = $q->fetchAll();
}
?>

<div style="margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
	<div>
		<a href="users" class="btn btn-sm btn-default" style="border-radius: 6px; margin-bottom: 10px; font-weight: 600;">
			<i class="fa fa-arrow-left"></i> Back to Users
		</a>
		<h2 style="font-weight: 800; color: #0f172a; margin: 0 0 6px 0; font-size: 26px;">
			<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
		</h2>
		<p style="color: #64748b; margin: 0; font-size: 14.5px;">
			Member ID: <span style="font-family: monospace; font-weight: 700; color: #4f46e5;"><?php echo htmlspecialchars($user['member_no']); ?></span> &bull; 
			Role: <span class="admin-badge-role admin-badge-<?php echo htmlspecialchars($user['role']); ?>"><?php echo ($user['role'] == 'employer') ? 'Task Lister' : 'Task Seeker'; ?></span> &bull; 
			Last Login: <?php echo htmlspecialchars($user['last_login'] ?: 'Never'); ?>
		</p>
	</div>

	<div style="display: flex; gap: 8px;">
		<?php if ($role === 'employee'): ?>
			<a href="../employee-detail.php?empid=<?php echo urlencode($user['member_no']); ?>" target="_blank" class="btn btn-sm btn-default" style="border-radius: 6px; font-weight: 600;">
				<i class="fa fa-external-link"></i> Live Profile
			</a>
		<?php else: ?>
			<a href="../company.php?ref=<?php echo urlencode($user['member_no']); ?>" target="_blank" class="btn btn-sm btn-default" style="border-radius: 6px; font-weight: 600;">
				<i class="fa fa-external-link"></i> Live Company Page
			</a>
		<?php endif; ?>
		
		<a href="app/actions.php?action=delete_user&id=<?php echo urlencode($user['member_no']); ?>" onclick="return confirm('Are you sure you want to delete this user and all related records?');" class="btn btn-sm btn-danger" style="border-radius: 6px; font-weight: 600;">
			<i class="fa fa-trash"></i> Delete User
		</a>
	</div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'status_updated'): ?>
	<div class="alert alert-success" style="border-radius: 8px;">
		<i class="fa fa-check-circle"></i> Application status updated successfully.
	</div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
	<div class="alert alert-success" style="border-radius: 8px;">
		<i class="fa fa-check-circle"></i> Application record removed successfully.
	</div>
<?php endif; ?>

<!-- Top Info Cards -->
<div class="row">
	<div class="col-md-4">
		<!-- Profile Card -->
		<div class="admin-table-card" style="padding: 24px;">
			<div style="text-align: center; margin-bottom: 20px;">
				<div style="width: 80px; height: 80px; border-radius: 50%; background: #eff6ff; color: #3b82f6; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 800; border: 2px solid #dbeafe; overflow: hidden;">
					<?php if (!empty($user['avatar'])): ?>
						<img src="data:image/jpeg;base64,<?php echo base64_encode($user['avatar']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
					<?php else: ?>
						<?php echo strtoupper(substr($user['first_name'] ?: 'U', 0, 1)); ?>
					<?php endif; ?>
				</div>
				<h4 style="font-weight: 800; color: #0f172a; margin: 12px 0 4px 0; font-size: 18px;">
					<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
				</h4>
				<?php if (!empty($user['title'])): ?>
					<div style="font-size: 13.5px; color: #64748b; font-weight: 600;"><?php echo htmlspecialchars($user['title']); ?></div>
				<?php endif; ?>
				<div style="margin-top: 8px;">
					<span class="admin-badge-role admin-badge-<?php echo htmlspecialchars($user['role']); ?>">
						<?php echo ($user['role'] == 'employer') ? 'Task Lister (Employer)' : 'Task Seeker (Candidate)'; ?>
					</span>
				</div>
			</div>

			<table class="table" style="font-size: 13px; margin: 0;">
				<tr>
					<td style="border: none; padding: 6px 0; color: #64748b; width: 100px;"><strong>Email:</strong></td>
					<td style="border: none; padding: 6px 0; word-break: break-all;"><?php echo htmlspecialchars($user['email']); ?></td>
				</tr>
				<tr>
					<td style="border: none; padding: 6px 0; color: #64748b;"><strong>Phone:</strong></td>
					<td style="border: none; padding: 6px 0;"><?php echo htmlspecialchars($user['phone'] ?: 'N/A'); ?></td>
				</tr>
				<tr>
					<td style="border: none; padding: 6px 0; color: #64748b;"><strong>Location:</strong></td>
					<td style="border: none; padding: 6px 0;"><?php echo htmlspecialchars(($user['city'] ? $user['city'].', ' : '') . ($user['country'] ?: 'N/A')); ?></td>
				</tr>
				<?php if (!empty($user['street'])): ?>
				<tr>
					<td style="border: none; padding: 6px 0; color: #64748b;"><strong>Address:</strong></td>
					<td style="border: none; padding: 6px 0;"><?php echo htmlspecialchars($user['street']); ?></td>
				</tr>
				<?php endif; ?>
				<?php if (!empty($user['website'])): ?>
				<tr>
					<td style="border: none; padding: 6px 0; color: #64748b;"><strong>Website:</strong></td>
					<td style="border: none; padding: 6px 0;"><a href="<?php echo htmlspecialchars($user['website']); ?>" target="_blank" style="color: #4f46e5;"><?php echo htmlspecialchars($user['website']); ?></a></td>
				</tr>
				<?php endif; ?>
				<?php if (!empty($user['bdate']) && !empty($user['bmonth']) && !empty($user['byear'])): ?>
				<tr>
					<td style="border: none; padding: 6px 0; color: #64748b;"><strong>Date of Birth:</strong></td>
					<td style="border: none; padding: 6px 0;"><?php echo htmlspecialchars($user['bdate'] . ' ' . $user['bmonth'] . ' ' . $user['byear']); ?></td>
				</tr>
				<?php endif; ?>
				<?php if (!empty($user['gender'])): ?>
				<tr>
					<td style="border: none; padding: 6px 0; color: #64748b;"><strong>Gender:</strong></td>
					<td style="border: none; padding: 6px 0;"><?php echo htmlspecialchars($user['gender']); ?></td>
				</tr>
				<?php endif; ?>
			</table>

			<?php if (!empty($user['about'])): ?>
				<div style="margin-top: 18px; border-top: 1px solid #f1f5f9; padding-top: 14px;">
					<span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">About / Summary</span>
					<div style="color: #334155; font-size: 13px; line-height: 1.5; margin: 6px 0 0 0;">
						<?php echo format_task_text($user['about']); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="col-md-8">
		<?php if ($role === 'employee'): ?>
			<!-- Seeker Activity Summary -->
			<?php 
			$seekTotal = count($applications);
			$seekPending = 0; $seekShortlisted = 0; $seekSelected = 0; $seekRejected = 0;
			foreach ($applications as $a) {
				$s = $a['status'] ?: 'Pending';
				if ($s == 'Shortlisted') $seekShortlisted++;
				elseif ($s == 'Selected' || $s == 'Hired') $seekSelected++;
				elseif ($s == 'Rejected') $seekRejected++;
				else $seekPending++;
			}
			?>
			<div class="row" style="margin-bottom: 20px;">
				<div class="col-xs-6 col-sm-3">
					<div class="admin-stat-box" style="padding: 14px 16px; margin-bottom: 10px; border-left: 4px solid #6366f1;">
						<div>
							<div class="admin-stat-number" style="font-size: 20px; color: #4f46e5;"><?php echo $seekTotal; ?></div>
							<div class="admin-stat-label" style="font-size: 11.5px;">Applications Sent</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3">
					<div class="admin-stat-box" style="padding: 14px 16px; margin-bottom: 10px; border-left: 4px solid #9333ea;">
						<div>
							<div class="admin-stat-number" style="font-size: 20px; color: #7e22ce;"><?php echo $seekShortlisted; ?></div>
							<div class="admin-stat-label" style="font-size: 11.5px;">Shortlisted</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3">
					<div class="admin-stat-box" style="padding: 14px 16px; margin-bottom: 10px; border-left: 4px solid #10b981;">
						<div>
							<div class="admin-stat-number" style="font-size: 20px; color: #059669;"><?php echo $seekSelected; ?></div>
							<div class="admin-stat-label" style="font-size: 11.5px;">Selected / Hired</div>
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3">
					<div class="admin-stat-box" style="padding: 14px 16px; margin-bottom: 10px; border-left: 4px solid #ef4444;">
						<div>
							<div class="admin-stat-number" style="font-size: 20px; color: #dc2626;"><?php echo $seekRejected; ?></div>
							<div class="admin-stat-label" style="font-size: 11.5px;">Rejected</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Seeker Applications List -->
			<div class="admin-table-card">
				<div class="admin-table-header">
					<h4><i class="fa fa-paper-plane text-primary"></i> Application History & Statuses (<?php echo count($applications); ?>)</h4>
				</div>
				<div class="table-responsive">
					<table class="table admin-table">
						<thead>
							<tr>
								<th>Task</th>
								<th>Lister</th>
								<th>Applied Date</th>
								<th>Status</th>
								<th>Update Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($applications)): ?>
								<tr><td colspan="6" class="text-center text-muted" style="padding: 25px !important;">This user hasn't applied to any tasks yet.</td></tr>
							<?php else: ?>
								<?php foreach ($applications as $app): 
									$st = !empty($app['status']) ? $app['status'] : 'Pending';
									$st_class = 'status-pending';
									$st_icon = 'fa-clock-o';
									$st_label = 'Pending';
									if ($st == 'Shortlisted') {
										$st_class = 'status-shortlisted';
										$st_icon = 'fa-star';
										$st_label = 'Shortlisted';
									} elseif ($st == 'Selected' || $st == 'Hired') {
										$st_class = 'status-selected';
										$st_icon = 'fa-check-circle';
										$st_label = 'Selected';
									} elseif ($st == 'Rejected') {
										$st_class = 'status-rejected';
										$st_icon = 'fa-times-circle';
										$st_label = 'Rejected';
									} else {
										$st_class = 'status-pending';
										$st_icon = 'fa-clock-o';
										$st_label = ($st == 'Under Review') ? 'Under Review' : 'Pending';
									}
								?>
									<tr>
										<td>
											<a href="job-detail?id=<?php echo urlencode($app['job_id']); ?>" style="font-weight: 700; color: #0f172a; text-decoration: none;">
												<?php echo htmlspecialchars($app['job_title'] ?: 'Task #' . $app['job_id']); ?>
											</a>
											<div style="font-size: 11.5px; color: #64748b;">
												<span class="label label-info" style="font-size: 10px;"><?php echo htmlspecialchars($app['job_category'] ?: 'General'); ?></span>
												<span style="font-family: monospace;"><?php echo htmlspecialchars($app['job_id']); ?></span>
											</div>
										</td>
										<td>
											<?php if (!empty($app['company_member_no'])): ?>
												<a href="user-detail?id=<?php echo urlencode($app['company_member_no']); ?>" style="color: #4f46e5; font-weight: 600;">
													<?php echo htmlspecialchars($app['company_name'] ?: 'Lister'); ?>
												</a>
											<?php else: ?>
												<span class="text-muted">N/A</span>
											<?php endif; ?>
										</td>
										<td style="font-size: 12.5px; color: #475569; white-space: nowrap;">
											<?php echo htmlspecialchars($app['application_date']); ?>
										</td>
										<td>
											<span class="admin-badge-status <?php echo $st_class; ?>">
												<i class="fa <?php echo $st_icon; ?>"></i>
												<?php echo htmlspecialchars($st_label); ?>
											</span>
										</td>
										<td>
											<form method="POST" action="app/actions.php" style="margin: 0; display: inline-flex; align-items: center; gap: 4px;">
												<input type="hidden" name="action" value="update_application_status">
												<input type="hidden" name="application_id" value="<?php echo (int)$app['id']; ?>">
												<input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
												<input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
												<select name="status" onchange="this.form.submit()" class="form-control input-sm" style="height: 28px; padding: 2px 6px; font-size: 12px; border-radius: 4px; width: 120px;">
													<option value="Pending" <?php echo ($st == 'Pending' || $st == 'Under Review' || empty($st)) ? 'selected' : ''; ?>>Pending / Review</option>
													<option value="Shortlisted" <?php echo ($st == 'Shortlisted') ? 'selected' : ''; ?>>Shortlisted</option>
													<option value="Selected" <?php echo ($st == 'Selected' || $st == 'Hired') ? 'selected' : ''; ?>>Selected / Hired</option>
													<option value="Rejected" <?php echo ($st == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
												</select>
											</form>
										</td>
										<td>
											<div style="display: flex; gap: 5px;">
												<a href="job-detail?id=<?php echo urlencode($app['job_id']); ?>" class="btn btn-xs btn-primary" style="border-radius: 4px;" title="Task Details">
													<i class="fa fa-briefcase"></i>
												</a>
												<a href="app/actions.php?action=delete_application&id=<?php echo (int)$app['id']; ?>&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" onclick="return confirm('Delete this application record?');" class="btn btn-xs btn-danger" style="border-radius: 4px;" title="Delete Application">
													<i class="fa fa-trash"></i>
												</a>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Academics & Experience Tabs / Cards -->
			<div class="row">
				<div class="col-md-6">
					<div class="admin-table-card" style="padding: 18px 20px;">
						<h5 style="font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 12px;">
							<i class="fa fa-graduation-cap text-primary"></i> Academic Education (<?php echo count($academics); ?>)
						</h5>
						<?php if (empty($academics)): ?>
							<p style="color: #64748b; font-size: 13px; margin: 0;">No education records added.</p>
						<?php else: ?>
							<ul style="padding-left: 18px; margin: 0; font-size: 13px; color: #334155;">
								<?php foreach ($academics as $ac): ?>
									<li style="margin-bottom: 8px;">
										<strong><?php echo htmlspecialchars($ac['degree'] ?? ($ac['title'] ?? 'Degree')); ?></strong> - <?php echo htmlspecialchars($ac['institution']); ?>
										<div style="font-size: 11.5px; color: #64748b;"><?php echo htmlspecialchars(($ac['timeframe'] ?? '') . ' ' . ($ac['level'] ?? '')); ?></div>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-md-6">
					<div class="admin-table-card" style="padding: 18px 20px;">
						<h5 style="font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 12px;">
							<i class="fa fa-briefcase text-primary"></i> Experience (<?php echo count($experiences); ?>)
						</h5>
						<?php if (empty($experiences)): ?>
							<p style="color: #64748b; font-size: 13px; margin: 0;">No experience records added.</p>
						<?php else: ?>
							<ul style="padding-left: 18px; margin: 0; font-size: 13px; color: #334155;">
								<?php foreach ($experiences as $ex): ?>
									<li style="margin-bottom: 8px;">
										<strong><?php echo htmlspecialchars($ex['title']); ?></strong> at <?php echo htmlspecialchars($ex['institution']); ?>
										<div style="font-size: 11.5px; color: #64748b;"><?php echo htmlspecialchars($ex['start_date'] . ' - ' . $ex['end_date']); ?></div>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			</div>

		<?php elseif ($role === 'employer'): ?>
			<!-- Employer Posted Tasks & Incoming Applications -->
			<div class="admin-table-card">
				<div class="admin-table-header">
					<h4><i class="fa fa-briefcase text-primary"></i> Tasks Posted by this Lister (<?php echo count($posted_jobs); ?>)</h4>
				</div>
				<div class="table-responsive">
					<table class="table admin-table">
						<thead>
							<tr>
								<th>Task Title / ID</th>
								<th>Category</th>
								<th>Type</th>
								<th>Bids Received</th>
								<th>Posted Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($posted_jobs)): ?>
								<tr><td colspan="6" class="text-center text-muted" style="padding: 25px !important;">No tasks posted yet.</td></tr>
							<?php else: ?>
								<?php foreach ($posted_jobs as $pj): ?>
									<tr>
										<td>
											<a href="job-detail?id=<?php echo urlencode($pj['job_id']); ?>" style="font-weight: 700; color: #0f172a; text-decoration: none;">
												<?php echo htmlspecialchars($pj['title']); ?>
											</a>
											<div style="font-size: 11.5px; color: #64748b; font-family: monospace;"><?php echo htmlspecialchars($pj['job_id']); ?></div>
										</td>
										<td><span class="label label-info"><?php echo htmlspecialchars($pj['category']); ?></span></td>
										<td><span class="label label-default"><?php echo htmlspecialchars($pj['type']); ?></span></td>
										<td>
											<a href="applications?job_id=<?php echo urlencode($pj['job_id']); ?>" class="badge" style="background: #e0e7ff; color: #4338ca; font-weight: 700; padding: 4px 8px;">
												<?php echo (int)$pj['app_count']; ?> bids
											</a>
										</td>
										<td style="font-size: 12.5px; color: #64748b;"><?php echo htmlspecialchars($pj['date_posted'] ?: 'N/A'); ?></td>
										<td>
											<div style="display: flex; gap: 5px;">
												<a href="job-detail?id=<?php echo urlencode($pj['job_id']); ?>" class="btn btn-xs btn-primary" style="border-radius: 4px;" title="Task Details & Applicants">
													<i class="fa fa-eye"></i> View
												</a>
												<a href="app/actions.php?action=delete_job&id=<?php echo urlencode($pj['job_id']); ?>" onclick="return confirm('Delete this task?');" class="btn btn-xs btn-danger" style="border-radius: 4px;" title="Delete Task">
													<i class="fa fa-trash"></i>
												</a>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- All Incoming Applications Received by this Employer -->
			<div class="admin-table-card">
				<div class="admin-table-header">
					<h4><i class="fa fa-users text-primary"></i> All Applicants across Lister's Tasks (<?php echo count($employer_all_bids); ?>)</h4>
				</div>
				<div class="table-responsive">
					<table class="table admin-table">
						<thead>
							<tr>
								<th>Applicant</th>
								<th>Task Applied</th>
								<th>Contact</th>
								<th>Date</th>
								<th>Status</th>
								<th>Update</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if (empty($employer_all_bids)): ?>
								<tr><td colspan="7" class="text-center text-muted" style="padding: 25px !important;">No bids received across all tasks yet.</td></tr>
							<?php else: ?>
								<?php foreach ($employer_all_bids as $eab): 
									$st = !empty($eab['status']) ? $eab['status'] : 'Pending';
									$st_class = 'status-pending';
									$st_icon = 'fa-clock-o';
									$st_label = 'Pending';
									if ($st == 'Shortlisted') {
										$st_class = 'status-shortlisted';
										$st_icon = 'fa-star';
										$st_label = 'Shortlisted';
									} elseif ($st == 'Selected' || $st == 'Hired') {
										$st_class = 'status-selected';
										$st_icon = 'fa-check-circle';
										$st_label = 'Selected';
									} elseif ($st == 'Rejected') {
										$st_class = 'status-rejected';
										$st_icon = 'fa-times-circle';
										$st_label = 'Rejected';
									} else {
										$st_class = 'status-pending';
										$st_icon = 'fa-clock-o';
										$st_label = ($st == 'Under Review') ? 'Under Review' : 'Pending';
									}
								?>
									<tr>
										<td>
											<a href="user-detail?id=<?php echo urlencode($eab['member_no']); ?>" style="font-weight: 700; color: #0f172a; text-decoration: none;">
												<?php echo htmlspecialchars($eab['applicant_fname'] . ' ' . $eab['applicant_lname']); ?>
											</a>
											<div style="font-size: 11.5px; color: #64748b; font-family: monospace;"><?php echo htmlspecialchars($eab['member_no']); ?></div>
										</td>
										<td>
											<a href="job-detail?id=<?php echo urlencode($eab['job_id']); ?>" style="color: #4f46e5; font-weight: 600;">
												<?php echo htmlspecialchars($eab['job_title']); ?>
											</a>
										</td>
										<td style="font-size: 12px; color: #64748b;">
											<?php echo htmlspecialchars($eab['applicant_email']); ?><br>
											<?php echo htmlspecialchars($eab['applicant_phone'] ?: 'N/A'); ?>
										</td>
										<td style="font-size: 12.5px; color: #64748b; white-space: nowrap;">
											<?php echo htmlspecialchars($eab['application_date']); ?>
										</td>
										<td>
											<span class="admin-badge-status <?php echo $st_class; ?>">
												<i class="fa <?php echo $st_icon; ?>"></i>
												<?php echo htmlspecialchars($st_label); ?>
											</span>
										</td>
										<td>
											<form method="POST" action="app/actions.php" style="margin: 0; display: inline-flex; align-items: center; gap: 4px;">
												<input type="hidden" name="action" value="update_application_status">
												<input type="hidden" name="application_id" value="<?php echo (int)$eab['id']; ?>">
												<input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
												<input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
												<select name="status" onchange="this.form.submit()" class="form-control input-sm" style="height: 28px; padding: 2px 6px; font-size: 12px; border-radius: 4px; width: 120px;">
													<option value="Pending" <?php echo ($st == 'Pending' || $st == 'Under Review' || empty($st)) ? 'selected' : ''; ?>>Pending / Review</option>
													<option value="Shortlisted" <?php echo ($st == 'Shortlisted') ? 'selected' : ''; ?>>Shortlisted</option>
													<option value="Selected" <?php echo ($st == 'Selected' || $st == 'Hired') ? 'selected' : ''; ?>>Selected / Hired</option>
													<option value="Rejected" <?php echo ($st == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
												</select>
											</form>
										</td>
										<td>
											<div style="display: flex; gap: 5px;">
												<a href="user-detail?id=<?php echo urlencode($eab['member_no']); ?>" class="btn btn-xs btn-info" style="border-radius: 4px;" title="Applicant Profile">
													<i class="fa fa-user"></i>
												</a>
												<a href="app/actions.php?action=delete_application&id=<?php echo (int)$eab['id']; ?>&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" onclick="return confirm('Delete this application record?');" class="btn btn-xs btn-danger" style="border-radius: 4px;" title="Delete Application">
													<i class="fa fa-trash"></i>
												</a>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
