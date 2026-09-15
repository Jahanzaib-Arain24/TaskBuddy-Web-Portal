<?php
$page_title = "Applications & Bids";
require_once __DIR__ . '/header.php';

$conn = get_db_connection();

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';
$job_filter = isset($_GET['job_id']) ? trim($_GET['job_id']) : '';
$user_filter = isset($_GET['member_no']) ? trim($_GET['member_no']) : '';

// Overall statistics
$totalApps = (int)$conn->query("SELECT COUNT(*) FROM tbl_job_applications")->fetchColumn();
$pendingApps = (int)$conn->query("SELECT COUNT(*) FROM tbl_job_applications WHERE status = 'Pending' OR status = 'Under Review' OR status = '' OR status IS NULL")->fetchColumn();
$shortlistedApps = (int)$conn->query("SELECT COUNT(*) FROM tbl_job_applications WHERE status = 'Shortlisted'")->fetchColumn();
$selectedApps = (int)$conn->query("SELECT COUNT(*) FROM tbl_job_applications WHERE status = 'Selected' OR status = 'Hired'")->fetchColumn();
$rejectedApps = (int)$conn->query("SELECT COUNT(*) FROM tbl_job_applications WHERE status = 'Rejected'")->fetchColumn();

// Build query
$query = "SELECT a.*, 
                 u.first_name as applicant_fname, u.last_name as applicant_lname, u.email as applicant_email, u.phone as applicant_phone, u.city as applicant_city, u.country as applicant_country,
                 j.title as job_title, j.category as job_category, j.type as job_type, j.city as job_city, j.country as job_country,
                 c.first_name as company_name, c.member_no as company_member_no
          FROM tbl_job_applications a
          LEFT JOIN tbl_users u ON a.member_no = u.member_no
          LEFT JOIN tbl_jobs j ON a.job_id = j.job_id
          LEFT JOIN tbl_users c ON j.company = c.member_no
          WHERE 1=1";

$params = [];

if (!empty($status_filter)) {
    if ($status_filter == 'Pending') {
        $query .= " AND (a.status = 'Pending' OR a.status = 'Under Review' OR a.status = '' OR a.status IS NULL)";
    } elseif ($status_filter == 'Selected') {
        $query .= " AND (a.status = 'Selected' OR a.status = 'Hired')";
    } else {
        $query .= " AND a.status = :status";
        $params[':status'] = $status_filter;
    }
}

if (!empty($job_filter)) {
    $query .= " AND a.job_id = :job_id";
    $params[':job_id'] = $job_filter;
}

if (!empty($user_filter)) {
    $query .= " AND a.member_no = :member_no";
    $params[':member_no'] = $user_filter;
}

if (!empty($search)) {
    $query .= " AND (u.first_name LIKE :q1 OR u.last_name LIKE :q2 OR u.email LIKE :q3 OR j.title LIKE :q4 OR c.first_name LIKE :q5 OR a.job_id LIKE :q6 OR a.member_no LIKE :q7)";
    $searchTerm = "%$search%";
    $params[':q1'] = $searchTerm;
    $params[':q2'] = $searchTerm;
    $params[':q3'] = $searchTerm;
    $params[':q4'] = $searchTerm;
    $params[':q5'] = $searchTerm;
    $params[':q6'] = $searchTerm;
    $params[':q7'] = $searchTerm;
}

$query .= " ORDER BY a.id DESC";

$stmt = $conn->prepare($query);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->execute();
$applications = $stmt->fetchAll();
?>

<div style="margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
	<div>
		<h2 style="font-weight: 800; color: #0f172a; margin: 0 0 6px 0; font-size: 26px;">Applications & Bids Tracking</h2>
		<p style="color: #64748b; margin: 0; font-size: 14.5px;">Monitor who applied to which task, hiring progress, and applicant-employer connections.</p>
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

<!-- Stats Widgets -->
<div class="row" style="margin-bottom: 20px;">
	<div class="col-xs-6 col-sm-4 col-md-2" style="width: 20%;">
		<a href="applications" style="text-decoration: none;">
			<div class="admin-stat-box" style="padding: 16px 18px; margin-bottom: 15px; border-left: 4px solid #6366f1; <?php echo (empty($status_filter) && empty($job_filter) && empty($user_filter)) ? 'box-shadow: 0 4px 12px rgba(99,102,241,0.18); border-color: #6366f1;' : ''; ?>">
				<div>
					<div class="admin-stat-number" style="font-size: 22px; color: #4f46e5;"><?php echo $totalApps; ?></div>
					<div class="admin-stat-label" style="font-size: 12px;">Total Bids</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col-xs-6 col-sm-4 col-md-2" style="width: 20%;">
		<a href="applications?status=Pending" style="text-decoration: none;">
			<div class="admin-stat-box" style="padding: 16px 18px; margin-bottom: 15px; border-left: 4px solid #f59e0b; <?php echo ($status_filter == 'Pending') ? 'box-shadow: 0 4px 12px rgba(245,158,11,0.18); border-color: #f59e0b;' : ''; ?>">
				<div>
					<div class="admin-stat-number" style="font-size: 22px; color: #d97706;"><?php echo $pendingApps; ?></div>
					<div class="admin-stat-label" style="font-size: 12px;">Pending / Review</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col-xs-6 col-sm-4 col-md-2" style="width: 20%;">
		<a href="applications?status=Shortlisted" style="text-decoration: none;">
			<div class="admin-stat-box" style="padding: 16px 18px; margin-bottom: 15px; border-left: 4px solid #9333ea; <?php echo ($status_filter == 'Shortlisted') ? 'box-shadow: 0 4px 12px rgba(147,51,234,0.18); border-color: #9333ea;' : ''; ?>">
				<div>
					<div class="admin-stat-number" style="font-size: 22px; color: #7e22ce;"><?php echo $shortlistedApps; ?></div>
					<div class="admin-stat-label" style="font-size: 12px;">Shortlisted</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col-xs-6 col-sm-4 col-md-2" style="width: 20%;">
		<a href="applications?status=Selected" style="text-decoration: none;">
			<div class="admin-stat-box" style="padding: 16px 18px; margin-bottom: 15px; border-left: 4px solid #10b981; <?php echo ($status_filter == 'Selected') ? 'box-shadow: 0 4px 12px rgba(16,185,129,0.18); border-color: #10b981;' : ''; ?>">
				<div>
					<div class="admin-stat-number" style="font-size: 22px; color: #059669;"><?php echo $selectedApps; ?></div>
					<div class="admin-stat-label" style="font-size: 12px;">Selected / Hired</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col-xs-6 col-sm-4 col-md-2" style="width: 20%;">
		<a href="applications?status=Rejected" style="text-decoration: none;">
			<div class="admin-stat-box" style="padding: 16px 18px; margin-bottom: 15px; border-left: 4px solid #ef4444; <?php echo ($status_filter == 'Rejected') ? 'box-shadow: 0 4px 12px rgba(239,68,68,0.18); border-color: #ef4444;' : ''; ?>">
				<div>
					<div class="admin-stat-number" style="font-size: 22px; color: #dc2626;"><?php echo $rejectedApps; ?></div>
					<div class="admin-stat-label" style="font-size: 12px;">Rejected</div>
				</div>
			</div>
		</a>
	</div>
</div>

<div class="admin-table-card">
	<div class="admin-table-header">
		<div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
			<a href="applications" class="btn btn-sm <?php echo empty($status_filter) && empty($job_filter) && empty($user_filter) ? 'btn-primary' : 'btn-default'; ?>" style="border-radius: 6px; font-weight: 600;">All (<?php echo count($applications); ?>)</a>
			<a href="applications?status=Pending<?php echo !empty($search) ? '&q='.urlencode($search) : ''; ?>" class="btn btn-sm <?php echo ($status_filter == 'Pending') ? 'btn-primary' : 'btn-default'; ?>" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-clock-o text-warning"></i> Pending (<?php echo $pendingApps; ?>)</a>
			<a href="applications?status=Shortlisted<?php echo !empty($search) ? '&q='.urlencode($search) : ''; ?>" class="btn btn-sm <?php echo ($status_filter == 'Shortlisted') ? 'btn-primary' : 'btn-default'; ?>" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-star" style="color: #9333ea;"></i> Shortlisted (<?php echo $shortlistedApps; ?>)</a>
			<a href="applications?status=Selected<?php echo !empty($search) ? '&q='.urlencode($search) : ''; ?>" class="btn btn-sm <?php echo ($status_filter == 'Selected') ? 'btn-primary' : 'btn-default'; ?>" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-check text-success"></i> Selected (<?php echo $selectedApps; ?>)</a>
			<a href="applications?status=Rejected<?php echo !empty($search) ? '&q='.urlencode($search) : ''; ?>" class="btn btn-sm <?php echo ($status_filter == 'Rejected') ? 'btn-primary' : 'btn-default'; ?>" style="border-radius: 6px; font-weight: 600;"><i class="fa fa-times text-danger"></i> Rejected (<?php echo $rejectedApps; ?>)</a>
			
			<?php if (!empty($job_filter) || !empty($user_filter)): ?>
				<span class="label label-info" style="font-size: 12px; padding: 6px 10px; border-radius: 6px;">
					Filtered by <?php echo !empty($job_filter) ? 'Task: '.htmlspecialchars($job_filter) : 'User: '.htmlspecialchars($user_filter); ?>
					<a href="applications" style="color: #fff; margin-left: 6px;"><i class="fa fa-times"></i> Clear</a>
				</span>
			<?php endif; ?>
		</div>
		
		<form method="GET" action="applications" style="display: flex; gap: 8px; margin: 0;">
			<?php if (!empty($status_filter)): ?>
				<input type="hidden" name="status" value="<?php echo htmlspecialchars($status_filter); ?>">
			<?php endif; ?>
			<?php if (!empty($job_filter)): ?>
				<input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_filter); ?>">
			<?php endif; ?>
			<?php if (!empty($user_filter)): ?>
				<input type="hidden" name="member_no" value="<?php echo htmlspecialchars($user_filter); ?>">
			<?php endif; ?>
			<input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search applicant, task, lister..." class="form-control input-sm" style="width: 250px; border-radius: 6px;">
			<button type="submit" class="btn btn-sm btn-default" style="border-radius: 6px;"><i class="fa fa-search"></i></button>
		</form>
	</div>

	<div class="table-responsive">
		<table class="table admin-table">
			<thead>
				<tr>
					<th>Applicant (Task Seeker)</th>
					<th>Task Applied For</th>
					<th>Posted By (Lister)</th>
					<th>Applied Date</th>
					<th>Current Status</th>
					<th>Manage Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($applications)): ?>
					<tr><td colspan="7" class="text-center text-muted" style="padding: 35px !important;">No applications found matching the criteria.</td></tr>
				<?php else: ?>
					<?php foreach($applications as $app): 
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
								<a href="user-detail?id=<?php echo urlencode($app['member_no']); ?>" style="color: #0f172a; font-weight: 700; font-size: 14px; text-decoration: none;">
									<?php echo htmlspecialchars(($app['applicant_fname'] ?: 'Unknown') . ' ' . ($app['applicant_lname'] ?: '')); ?>
								</a>
								<div style="font-size: 11.5px; color: #64748b;">
									<span style="font-family: monospace;"><?php echo htmlspecialchars($app['member_no']); ?></span> &bull; 
									<span><?php echo htmlspecialchars($app['applicant_email'] ?: 'No email'); ?></span>
								</div>
								<div style="font-size: 11.5px; color: #64748b;">
									<i class="fa fa-phone"></i> <?php echo htmlspecialchars($app['applicant_phone'] ?: 'N/A'); ?>
								</div>
							</td>
							<td>
								<a href="job-detail?id=<?php echo urlencode($app['job_id']); ?>" style="color: #4f46e5; font-weight: 700; font-size: 13.5px; text-decoration: none;">
									<?php echo htmlspecialchars($app['job_title'] ?: 'Task #' . $app['job_id']); ?>
								</a>
								<div style="font-size: 11.5px; color: #64748b;">
									<span class="label label-info" style="font-size: 10px;"><?php echo htmlspecialchars($app['job_category'] ?: 'General'); ?></span>
									<span style="font-family: monospace; margin-left: 4px;"><?php echo htmlspecialchars($app['job_id']); ?></span>
								</div>
							</td>
							<td>
								<?php if (!empty($app['company_member_no'])): ?>
									<a href="user-detail?id=<?php echo urlencode($app['company_member_no']); ?>" style="color: #0f172a; font-weight: 600;">
										<?php echo htmlspecialchars($app['company_name'] ?: 'Lister'); ?>
									</a>
									<div style="font-size: 11.5px; color: #64748b; font-family: monospace;"><?php echo htmlspecialchars($app['company_member_no']); ?></div>
								<?php else: ?>
									<span class="text-muted">N/A</span>
								<?php endif; ?>
							</td>
							<td style="font-size: 12.5px; color: #475569; white-space: nowrap;">
								<i class="fa fa-calendar-o text-muted"></i> <?php echo htmlspecialchars($app['application_date']); ?>
							</td>
							<td>
								<span class="admin-badge-status <?php echo $st_class; ?>">
									<i class="fa <?php echo $st_icon; ?>"></i>
									<?php echo htmlspecialchars($st_label); ?>
								</span>
							</td>
							<td>
								<!-- Inline Status Update Form -->
								<form method="POST" action="app/actions.php" style="margin: 0; display: inline-flex; align-items: center; gap: 4px;">
									<input type="hidden" name="action" value="update_application_status">
									<input type="hidden" name="application_id" value="<?php echo (int)$app['id']; ?>">
									<input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
									<input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
									<select name="status" onchange="this.form.submit()" class="form-control input-sm" style="height: 28px; padding: 2px 6px; font-size: 12px; border-radius: 4px; width: 125px;">
										<option value="Pending" <?php echo ($st == 'Pending' || $st == 'Under Review' || empty($st)) ? 'selected' : ''; ?>>Pending / Review</option>
										<option value="Shortlisted" <?php echo ($st == 'Shortlisted') ? 'selected' : ''; ?>>Shortlisted</option>
										<option value="Selected" <?php echo ($st == 'Selected' || $st == 'Hired') ? 'selected' : ''; ?>>Selected / Hired</option>
										<option value="Rejected" <?php echo ($st == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
									</select>
								</form>
							</td>
							<td>
								<div style="display: flex; gap: 5px;">
									<a href="job-detail?id=<?php echo urlencode($app['job_id']); ?>" class="btn btn-xs btn-primary" style="border-radius: 4px;" title="Task Details & Applicants">
										<i class="fa fa-briefcase"></i>
									</a>
									<a href="user-detail?id=<?php echo urlencode($app['member_no']); ?>" class="btn btn-xs btn-info" style="border-radius: 4px;" title="User Profile & History">
										<i class="fa fa-user"></i>
									</a>
									<a href="app/actions.php?action=delete_application&id=<?php echo (int)$app['id']; ?>&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" onclick="return confirm('Are you sure you want to remove this application record?');" class="btn btn-xs btn-danger" style="border-radius: 4px;" title="Delete Application">
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

<?php require_once __DIR__ . '/footer.php'; ?>
