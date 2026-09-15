<?php
$page_title = "Task & Applicants Detail";
require_once __DIR__ . '/header.php';

$conn = get_db_connection();

$job_id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($job_id)) {
    echo '<div class="alert alert-danger" style="border-radius: 8px;">Invalid Task ID requested. <a href="jobs">Go back to tasks</a>.</div>';
    require_once __DIR__ . '/footer.php';
    exit();
}

// Fetch Job Details with Company Info
$stmt = $conn->prepare("SELECT j.*, u.first_name as company_name, u.email as company_email, u.phone as company_phone, u.city as company_city, u.country as company_country, u.street as company_street, u.website as company_website, u.about as company_about, u.avatar as company_avatar
                        FROM tbl_jobs j
                        LEFT JOIN tbl_users u ON j.company = u.member_no
                        WHERE j.job_id = :jid LIMIT 1");
$stmt->bindParam(':jid', $job_id);
$stmt->execute();
$job = $stmt->fetch();

if (!$job) {
    echo '<div class="alert alert-danger" style="border-radius: 8px;">Task not found in the database. <a href="jobs">Go back to tasks</a>.</div>';
    require_once __DIR__ . '/footer.php';
    exit();
}

// Fetch all applicants for this job
$appStmt = $conn->prepare("SELECT a.*, u.first_name, u.last_name, u.email, u.phone, u.city, u.country, u.education, u.title as user_headline, u.avatar
                           FROM tbl_job_applications a
                           LEFT JOIN tbl_users u ON a.member_no = u.member_no
                           WHERE a.job_id = :jid
                           ORDER BY a.id DESC");
$appStmt->bindParam(':jid', $job_id);
$appStmt->execute();
$applicants = $appStmt->fetchAll();

// Applicant Status counts for this job
$totalApps = count($applicants);
$pendingCount = 0;
$shortlistCount = 0;
$selectedCount = 0;
$rejectedCount = 0;

foreach ($applicants as $app) {
    $st = $app['status'] ?: 'Pending';
    if ($st == 'Shortlisted') $shortlistCount++;
    elseif ($st == 'Selected' || $st == 'Hired') $selectedCount++;
    elseif ($st == 'Rejected') $rejectedCount++;
    else $pendingCount++;
}
?>

<div style="margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
	<div>
		<a href="jobs" class="btn btn-sm btn-default" style="border-radius: 6px; margin-bottom: 10px; font-weight: 600;">
			<i class="fa fa-arrow-left"></i> Back to Tasks
		</a>
		<h2 style="font-weight: 800; color: #0f172a; margin: 0 0 6px 0; font-size: 26px;">
			<?php echo htmlspecialchars($job['title']); ?>
		</h2>
		<p style="color: #64748b; margin: 0; font-size: 14.5px;">
			Task ID: <span style="font-family: monospace; font-weight: 700; color: #4f46e5;"><?php echo htmlspecialchars($job['job_id']); ?></span> &bull; 
			Posted on <?php echo htmlspecialchars($job['date_posted'] ?: 'N/A'); ?> &bull; 
			Closing: <?php echo htmlspecialchars($job['closing_date'] ?: 'Open'); ?>
		</p>
	</div>

	<div style="display: flex; gap: 8px;">
		<a href="../explore-job.php?jobid=<?php echo urlencode($job['job_id']); ?>" target="_blank" class="btn btn-sm btn-default" style="border-radius: 6px; font-weight: 600;">
			<i class="fa fa-external-link"></i> Live Public Page
		</a>
		<a href="app/actions.php?action=delete_job&id=<?php echo urlencode($job['job_id']); ?>" onclick="return confirm('Are you sure you want to delete this task? All applications will be lost.');" class="btn btn-sm btn-danger" style="border-radius: 6px; font-weight: 600;">
			<i class="fa fa-trash"></i> Delete Task
		</a>
	</div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'status_updated'): ?>
	<div class="alert alert-success" style="border-radius: 8px;">
		<i class="fa fa-check-circle"></i> Applicant status updated successfully.
	</div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
	<div class="alert alert-success" style="border-radius: 8px;">
		<i class="fa fa-check-circle"></i> Applicant record removed successfully.
	</div>
<?php endif; ?>

<!-- Top Details: Lister & Task Info -->
<div class="row">
	<div class="col-md-7">
		<div class="admin-table-card" style="padding: 24px;">
			<h4 style="font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
				<i class="fa fa-info-circle text-primary"></i> Task Specifications
			</h4>
			
			<div class="row" style="margin-bottom: 15px;">
				<div class="col-sm-4" style="margin-bottom: 10px;">
					<span style="color: #64748b; font-size: 12px; display: block; text-transform: uppercase; font-weight: 700;">Category</span>
					<strong style="color: #0f172a; font-size: 14px;"><span class="label label-info"><?php echo htmlspecialchars($job['category']); ?></span></strong>
				</div>
				<div class="col-sm-4" style="margin-bottom: 10px;">
					<span style="color: #64748b; font-size: 12px; display: block; text-transform: uppercase; font-weight: 700;">Task Type</span>
					<strong style="color: #0f172a; font-size: 14px;"><span class="label label-default"><?php echo htmlspecialchars($job['type']); ?></span></strong>
				</div>
				<div class="col-sm-4" style="margin-bottom: 10px;">
					<span style="color: #64748b; font-size: 12px; display: block; text-transform: uppercase; font-weight: 700;">Location</span>
					<strong style="color: #0f172a; font-size: 14px;"><?php echo htmlspecialchars($job['city'] . ', ' . $job['country']); ?></strong>
				</div>
			</div>

			<div style="margin-bottom: 15px;">
				<span style="color: #64748b; font-size: 12px; display: block; text-transform: uppercase; font-weight: 700;">Experience / Requirement Level</span>
				<p style="color: #334155; margin: 4px 0 0 0;"><?php echo htmlspecialchars($job['experience'] ?: 'Not specified'); ?></p>
			</div>

			<div style="margin-bottom: 15px;">
				<span style="color: #64748b; font-size: 12px; display: block; text-transform: uppercase; font-weight: 700;">Task Description</span>
				<div style="color: #334155; font-size: 13.5px; line-height: 1.6; background: #f8fafc; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 6px;">
					<?php echo format_task_text($job['description']); ?>
				</div>
			</div>

			<?php if (!empty($job['responsibility'])): ?>
				<div style="margin-bottom: 15px;">
					<span style="color: #64748b; font-size: 12px; display: block; text-transform: uppercase; font-weight: 700;">Responsibilities</span>
					<div style="color: #334155; font-size: 13.5px; line-height: 1.6; background: #f8fafc; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 6px;">
						<?php echo format_task_text($job['responsibility']); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if (!empty($job['requirements'])): ?>
				<div>
					<span style="color: #64748b; font-size: 12px; display: block; text-transform: uppercase; font-weight: 700;">Requirements</span>
					<div style="color: #334155; font-size: 13.5px; line-height: 1.6; background: #f8fafc; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0; margin-top: 6px;">
						<?php echo format_task_text($job['requirements']); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div class="col-md-5">
		<!-- Lister Card -->
		<div class="admin-table-card" style="padding: 24px; margin-bottom: 20px;">
			<h4 style="font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
				<i class="fa fa-building text-primary"></i> Lister / Company Details
			</h4>

			<div style="display: flex; align-items: center; gap: 15px; margin-bottom: 16px;">
				<div style="width: 52px; height: 52px; border-radius: 10px; background: #eff6ff; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #3b82f6; font-weight: 700; flex-shrink: 0; border: 1px solid #dbeafe;">
					<?php if (!empty($job['company_avatar'])): ?>
						<img src="data:image/jpeg;base64,<?php echo base64_encode($job['company_avatar']); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
					<?php else: ?>
						<i class="fa fa-building-o"></i>
					<?php endif; ?>
				</div>
				<div>
					<a href="user-detail?id=<?php echo urlencode($job['company']); ?>" style="font-weight: 800; color: #0f172a; font-size: 16px; text-decoration: none;">
						<?php echo htmlspecialchars($job['company_name'] ?: 'Unknown Lister'); ?>
					</a>
					<div style="font-size: 12px; color: #64748b; font-family: monospace;">Member ID: <?php echo htmlspecialchars($job['company']); ?></div>
				</div>
			</div>

			<table class="table" style="font-size: 13px; margin: 0;">
				<tr>
					<td style="border: none; padding: 6px 0; color: #64748b; width: 100px;"><strong>Email:</strong></td>
					<td style="border: none; padding: 6px 0;"><?php echo htmlspecialchars($job['company_email'] ?: 'N/A'); ?></td>
				</tr>
				<tr>
					<td style="border: none; padding: 6px 0; color: #64748b;"><strong>Phone:</strong></td>
					<td style="border: none; padding: 6px 0;"><?php echo htmlspecialchars($job['company_phone'] ?: 'N/A'); ?></td>
				</tr>
				<tr>
					<td style="border: none; padding: 6px 0; color: #64748b;"><strong>Location:</strong></td>
					<td style="border: none; padding: 6px 0;"><?php echo htmlspecialchars($job['company_city'] . ', ' . $job['company_country']); ?></td>
				</tr>
				<?php if (!empty($job['company_website'])): ?>
				<tr>
					<td style="border: none; padding: 6px 0; color: #64748b;"><strong>Website:</strong></td>
					<td style="border: none; padding: 6px 0;"><a href="<?php echo htmlspecialchars($job['company_website']); ?>" target="_blank" style="color: #4f46e5;"><?php echo htmlspecialchars($job['company_website']); ?></a></td>
				</tr>
				<?php endif; ?>
			</table>

			<div style="margin-top: 14px; display: flex; gap: 8px;">
				<a href="user-detail?id=<?php echo urlencode($job['company']); ?>" class="btn btn-xs btn-primary" style="border-radius: 4px; font-weight: 600;">
					<i class="fa fa-user"></i> Full Lister Profile
				</a>
				<a href="../company.php?ref=<?php echo urlencode($job['company']); ?>" target="_blank" class="btn btn-xs btn-default" style="border-radius: 4px; font-weight: 600;">
					<i class="fa fa-external-link"></i> Public Page
				</a>
			</div>
		</div>

		<!-- Application Status Breakdown for this Task -->
		<div class="admin-table-card" style="padding: 20px;">
			<h4 style="font-weight: 800; color: #0f172a; margin-top: 0; margin-bottom: 14px;">
				<i class="fa fa-bar-chart text-primary"></i> Application Pipeline (<?php echo $totalApps; ?>)
			</h4>

			<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
				<div style="background: #fef3c7; border: 1px solid #fde68a; border-radius: 8px; padding: 12px; text-align: center;">
					<div style="font-size: 20px; font-weight: 800; color: #92400e;"><?php echo $pendingCount; ?></div>
					<div style="font-size: 11.5px; font-weight: 700; color: #b45309; text-transform: uppercase;">Pending / Review</div>
				</div>
				<div style="background: #f3e8ff; border: 1px solid #e9d5ff; border-radius: 8px; padding: 12px; text-align: center;">
					<div style="font-size: 20px; font-weight: 800; color: #7e22ce;"><?php echo $shortlistCount; ?></div>
					<div style="font-size: 11.5px; font-weight: 700; color: #7e22ce; text-transform: uppercase;">Shortlisted</div>
				</div>
				<div style="background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px; text-align: center;">
					<div style="font-size: 20px; font-weight: 800; color: #166534;"><?php echo $selectedCount; ?></div>
					<div style="font-size: 11.5px; font-weight: 700; color: #15803d; text-transform: uppercase;">Selected / Hired</div>
				</div>
				<div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px; text-align: center;">
					<div style="font-size: 20px; font-weight: 800; color: #991b1b;"><?php echo $rejectedCount; ?></div>
					<div style="font-size: 11.5px; font-weight: 700; color: #b91c1c; text-transform: uppercase;">Rejected</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Applicants List Table -->
<div class="admin-table-card">
	<div class="admin-table-header">
		<h4><i class="fa fa-users text-primary"></i> Applicants & Bidders (<?php echo count($applicants); ?>)</h4>
	</div>

	<div class="table-responsive">
		<table class="table admin-table">
			<thead>
				<tr>
					<th>Applicant</th>
					<th>Contact Details</th>
					<th>Location</th>
					<th>Applied Date</th>
					<th>Status</th>
					<th>Update Status</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($applicants)): ?>
					<tr><td colspan="7" class="text-center text-muted" style="padding: 35px !important;">No bids/applications received for this task yet.</td></tr>
				<?php else: ?>
					<?php foreach($applicants as $app): 
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
								<div style="display: flex; align-items: center; gap: 10px;">
									<div style="width: 36px; height: 36px; border-radius: 50%; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex-shrink: 0;">
										<?php if (!empty($app['avatar'])): ?>
											<img src="data:image/jpeg;base64,<?php echo base64_encode($app['avatar']); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
										<?php else: ?>
											<?php echo strtoupper(substr($app['first_name'] ?: 'U', 0, 1)); ?>
										<?php endif; ?>
									</div>
									<div>
										<a href="user-detail?id=<?php echo urlencode($app['member_no']); ?>" style="font-weight: 700; color: #0f172a; text-decoration: none;">
											<?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?>
										</a>
										<div style="font-size: 11.5px; color: #64748b; font-family: monospace;"><?php echo htmlspecialchars($app['member_no']); ?></div>
										<?php if (!empty($app['user_headline'])): ?>
											<div style="font-size: 11.5px; color: #475569;"><?php echo htmlspecialchars($app['user_headline']); ?></div>
										<?php endif; ?>
									</div>
								</div>
							</td>
							<td>
								<div style="font-size: 13px; color: #0f172a;"><?php echo htmlspecialchars($app['email']); ?></div>
								<div style="font-size: 12px; color: #64748b;"><i class="fa fa-phone"></i> <?php echo htmlspecialchars($app['phone'] ?: 'N/A'); ?></div>
							</td>
							<td>
								<?php echo htmlspecialchars($app['city'] ?: ($app['country'] ?: 'N/A')); ?>
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
									<a href="user-detail?id=<?php echo urlencode($app['member_no']); ?>" class="btn btn-xs btn-info" style="border-radius: 4px;" title="Full User Profile & History">
										<i class="fa fa-user"></i>
									</a>
									<a href="../employee-detail.php?empid=<?php echo urlencode($app['member_no']); ?>" target="_blank" class="btn btn-xs btn-default" style="border-radius: 4px;" title="Public Seeker Profile">
										<i class="fa fa-external-link"></i>
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

<?php require_once __DIR__ . '/footer.php'; ?>
