<?php
$page_title = "Dashboard";
require_once __DIR__ . '/header.php';

$conn = get_db_connection();

// Aggregate stats
$totalTasks = (int)$conn->query("SELECT COUNT(*) FROM tbl_jobs")->fetchColumn();
$totalSeekers = (int)$conn->query("SELECT COUNT(*) FROM tbl_users WHERE role = 'employee'")->fetchColumn();
$totalListers = (int)$conn->query("SELECT COUNT(*) FROM tbl_users WHERE role = 'employer'")->fetchColumn();
$totalApplications = (int)$conn->query("SELECT COUNT(*) FROM tbl_job_applications")->fetchColumn();
$totalCategories = (int)$conn->query("SELECT COUNT(*) FROM tbl_categories")->fetchColumn();
$totalCountries = (int)$conn->query("SELECT COUNT(*) FROM tbl_countries")->fetchColumn();

// Recent 5 Tasks
$recentJobsStmt = $conn->query("SELECT j.*, u.first_name as company_name,
                                (SELECT COUNT(*) FROM tbl_job_applications a WHERE a.job_id = j.job_id) as app_count
                                FROM tbl_jobs j 
                                LEFT JOIN tbl_users u ON j.company = u.member_no 
                                ORDER BY j.enc_id DESC LIMIT 5");
$recentJobs = $recentJobsStmt->fetchAll();

// Recent 5 Users - ordered by member_no DESC
$recentUsersStmt = $conn->query("SELECT * FROM tbl_users WHERE role != 'admin' ORDER BY member_no DESC LIMIT 5");
$recentUsers = $recentUsersStmt->fetchAll();

// Recent 5 Applications
$recentAppsStmt = $conn->query("SELECT a.*, 
                                       u.first_name as applicant_fname, u.last_name as applicant_lname,
                                       j.title as job_title, j.category as job_category,
                                       c.first_name as company_name
                                FROM tbl_job_applications a
                                LEFT JOIN tbl_users u ON a.member_no = u.member_no
                                LEFT JOIN tbl_jobs j ON a.job_id = j.job_id
                                LEFT JOIN tbl_users c ON j.company = c.member_no
                                ORDER BY a.id DESC LIMIT 5");
$recentApps = $recentAppsStmt->fetchAll();
?>

<div style="margin-bottom: 28px;">
	<h2 style="font-weight: 800; color: #0f172a; margin: 0 0 6px 0; font-size: 26px;">Dashboard Overview</h2>
	<p style="color: #64748b; margin: 0; font-size: 14.5px;">Welcome back, <strong><?php echo htmlspecialchars($admin_name); ?></strong>! System statistics and recent platform activities.</p>
</div>

<!-- Stats Row -->
<div class="row">
	<div class="col-sm-6 col-md-3">
		<a href="jobs" style="text-decoration: none;">
			<div class="admin-stat-box">
				<div class="admin-stat-icon-wrap" style="background: #eff6ff; color: #3b82f6;">
					<i class="fa fa-briefcase"></i>
				</div>
				<div>
					<div class="admin-stat-number"><?php echo $totalTasks; ?></div>
					<div class="admin-stat-label">Total Tasks Posted</div>
				</div>
			</div>
		</a>
	</div>
	
	<div class="col-sm-6 col-md-3">
		<a href="users?role=employee" style="text-decoration: none;">
			<div class="admin-stat-box">
				<div class="admin-stat-icon-wrap" style="background: #f0fdf4; color: #16a34a;">
					<i class="fa fa-users"></i>
				</div>
				<div>
					<div class="admin-stat-number"><?php echo $totalSeekers; ?></div>
					<div class="admin-stat-label">Task Seekers</div>
				</div>
			</div>
		</a>
	</div>
	
	<div class="col-sm-6 col-md-3">
		<a href="users?role=employer" style="text-decoration: none;">
			<div class="admin-stat-box">
				<div class="admin-stat-icon-wrap" style="background: #fef2f2; color: #ef4444;">
					<i class="fa fa-building"></i>
				</div>
				<div>
					<div class="admin-stat-number"><?php echo $totalListers; ?></div>
					<div class="admin-stat-label">Task Listers</div>
				</div>
			</div>
		</a>
	</div>
	
	<div class="col-sm-6 col-md-3">
		<a href="applications" style="text-decoration: none;">
			<div class="admin-stat-box">
				<div class="admin-stat-icon-wrap" style="background: #faf5ff; color: #9333ea;">
					<i class="fa fa-paper-plane"></i>
				</div>
				<div>
					<div class="admin-stat-number"><?php echo $totalApplications; ?></div>
					<div class="admin-stat-label">Applications / Bids</div>
				</div>
			</div>
		</a>
	</div>
</div>

<!-- Quick Links / Shortcuts -->
<div class="row" style="margin-bottom: 25px;">
	<div class="col-md-12">
		<div style="background: #ffffff; border-radius: 12px; padding: 14px 18px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
			<span style="font-weight: 700; color: #0f172a; font-size: 13.5px;"><i class="fa fa-bolt text-warning"></i> Quick Management:</span>
			<div style="display: flex; gap: 8px; flex-wrap: wrap;">
				<a href="users" class="btn btn-sm btn-default" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-users text-primary"></i> Manage Users</a>
				<a href="jobs" class="btn btn-sm btn-default" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-briefcase text-success"></i> Manage Tasks</a>
				<a href="applications" class="btn btn-sm btn-default" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-paper-plane" style="color: #9333ea;"></i> Applications (<?php echo $totalApplications; ?>)</a>
				<a href="categories" class="btn btn-sm btn-default" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-tags text-info"></i> Categories (<?php echo $totalCategories; ?>)</a>
				<a href="countries" class="btn btn-sm btn-default" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-globe text-danger"></i> Countries (<?php echo $totalCountries; ?>)</a>
				<a href="alerts" class="btn btn-sm btn-default" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-bell-o text-warning"></i> Alerts</a>
			</div>
		</div>
	</div>
</div>

<!-- Recent Tables: Tasks & Users -->
<div class="row">
	<div class="col-md-7">
		<div class="admin-table-card">
			<div class="admin-table-header">
				<h4><i class="fa fa-briefcase text-primary"></i> Recent Tasks Posted</h4>
				<a href="jobs" class="btn btn-sm btn-default" style="font-size: 12px; font-weight: 600; border-radius: 6px;">View All Tasks</a>
			</div>
			<div class="table-responsive">
				<table class="table admin-table">
					<thead>
						<tr>
							<th>Task Title</th>
							<th>Lister</th>
							<th>Category</th>
							<th>Bids</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($recentJobs)): ?>
							<tr><td colspan="5" class="text-center text-muted" style="padding: 25px !important;">No tasks posted yet.</td></tr>
						<?php else: ?>
							<?php foreach($recentJobs as $job): ?>
								<tr>
									<td>
										<a href="job-detail?id=<?php echo urlencode($job['job_id']); ?>" style="font-weight: 700; color: #0f172a; text-decoration: none;">
											<?php echo htmlspecialchars($job['title']); ?>
										</a>
										<div style="font-size: 11.5px; color: #64748b;"><?php echo htmlspecialchars($job['city']) . ', ' . htmlspecialchars($job['country']); ?></div>
									</td>
									<td>
										<?php if (!empty($job['company'])): ?>
											<a href="user-detail?id=<?php echo urlencode($job['company']); ?>" style="color: #4f46e5; font-weight: 600;">
												<?php echo htmlspecialchars($job['company_name'] ?: 'Lister'); ?>
											</a>
										<?php else: ?>
											<span class="text-muted">Unknown</span>
										<?php endif; ?>
									</td>
									<td><span class="label label-info" style="border-radius: 4px;"><?php echo htmlspecialchars($job['category']); ?></span></td>
									<td>
										<a href="job-detail?id=<?php echo urlencode($job['job_id']); ?>" class="badge" style="background: #e0e7ff; color: #4338ca; font-weight: 700; padding: 3px 7px;">
											<?php echo (int)$job['app_count']; ?>
										</a>
									</td>
									<td>
										<a href="job-detail?id=<?php echo urlencode($job['job_id']); ?>" class="btn btn-xs btn-primary" style="border-radius: 4px;" title="Task Details"><i class="fa fa-eye"></i></a>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-md-5">
		<div class="admin-table-card">
			<div class="admin-table-header">
				<h4><i class="fa fa-users text-primary"></i> Recently Registered Users</h4>
				<a href="users" class="btn btn-sm btn-default" style="font-size: 12px; font-weight: 600; border-radius: 6px;">View All</a>
			</div>
			<div class="table-responsive">
				<table class="table admin-table">
					<thead>
						<tr>
							<th>User</th>
							<th>Role</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($recentUsers)): ?>
							<tr><td colspan="3" class="text-center text-muted" style="padding: 25px !important;">No users registered yet.</td></tr>
						<?php else: ?>
							<?php foreach($recentUsers as $u): ?>
								<tr>
									<td>
										<a href="user-detail?id=<?php echo urlencode($u['member_no']); ?>" style="font-weight: 700; color: #0f172a; text-decoration: none;">
											<?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?>
										</a>
										<div style="font-size: 11.5px; color: #64748b;"><?php echo htmlspecialchars($u['email']); ?></div>
									</td>
									<td>
										<span class="admin-badge-role admin-badge-<?php echo htmlspecialchars($u['role']); ?>">
											<?php echo ($u['role'] == 'employer') ? 'Lister' : 'Seeker'; ?>
										</span>
									</td>
									<td>
										<a href="user-detail?id=<?php echo urlencode($u['member_no']); ?>" class="btn btn-xs btn-primary" style="border-radius: 4px;" title="View Profile"><i class="fa fa-eye"></i></a>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- Recent Applications / Bids Row -->
<div class="row">
	<div class="col-md-12">
		<div class="admin-table-card">
			<div class="admin-table-header">
				<h4><i class="fa fa-paper-plane text-primary"></i> Recent Applications & Bids</h4>
				<a href="applications" class="btn btn-sm btn-default" style="font-size: 12px; font-weight: 600; border-radius: 6px;">View All Applications</a>
			</div>
			<div class="table-responsive">
				<table class="table admin-table">
					<thead>
						<tr>
							<th>Applicant</th>
							<th>Task Applied</th>
							<th>Posted By (Lister)</th>
							<th>Applied Date</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($recentApps)): ?>
							<tr><td colspan="6" class="text-center text-muted" style="padding: 25px !important;">No applications submitted yet.</td></tr>
						<?php else: ?>
							<?php foreach($recentApps as $app): 
								$st = !empty($app['status']) ? $app['status'] : 'Pending';
								$st_class = 'status-pending';
								if ($st == 'Selected') $st_class = 'status-selected';
								elseif ($st == 'Rejected') $st_class = 'status-rejected';
								elseif ($st == 'Under Review') $st_class = 'status-under-review';
							?>
								<tr>
									<td>
										<a href="user-detail?id=<?php echo urlencode($app['member_no']); ?>" style="font-weight: 700; color: #0f172a; text-decoration: none;">
											<?php echo htmlspecialchars(($app['applicant_fname'] ?: 'Unknown') . ' ' . ($app['applicant_lname'] ?: '')); ?>
										</a>
										<div style="font-size: 11.5px; color: #64748b; font-family: monospace;"><?php echo htmlspecialchars($app['member_no']); ?></div>
									</td>
									<td>
										<a href="job-detail?id=<?php echo urlencode($app['job_id']); ?>" style="color: #4f46e5; font-weight: 600;">
											<?php echo htmlspecialchars($app['job_title'] ?: 'Task #' . $app['job_id']); ?>
										</a>
										<div style="font-size: 11.5px; color: #64748b;"><?php echo htmlspecialchars($app['job_category']); ?></div>
									</td>
									<td>
										<?php echo htmlspecialchars($app['company_name'] ?: 'N/A'); ?>
									</td>
									<td style="font-size: 12.5px; color: #64748b; white-space: nowrap;">
										<?php echo htmlspecialchars($app['application_date']); ?>
									</td>
									<td>
										<span class="admin-badge-status <?php echo $st_class; ?>">
											<?php echo htmlspecialchars($st); ?>
										</span>
									</td>
									<td>
										<div style="display: flex; gap: 5px;">
											<a href="job-detail?id=<?php echo urlencode($app['job_id']); ?>" class="btn btn-xs btn-primary" style="border-radius: 4px;" title="Task Details & Applicants">
												<i class="fa fa-briefcase"></i>
											</a>
											<a href="user-detail?id=<?php echo urlencode($app['member_no']); ?>" class="btn btn-xs btn-info" style="border-radius: 4px;" title="User Details">
												<i class="fa fa-user"></i>
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
	</div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
