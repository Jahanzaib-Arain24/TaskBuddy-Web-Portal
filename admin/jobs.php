<?php
$page_title = "Manage Tasks";
require_once __DIR__ . '/header.php';

$conn = get_db_connection();
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$query = "SELECT j.*, u.first_name as company_name, 
         (SELECT COUNT(*) FROM tbl_job_applications a WHERE a.job_id = j.job_id) as app_count
         FROM tbl_jobs j 
         LEFT JOIN tbl_users u ON j.company = u.member_no";

if (!empty($search)) {
    $query .= " WHERE (j.title LIKE :q1 OR j.category LIKE :q2 OR j.city LIKE :q3 OR u.first_name LIKE :q4)";
}

$query .= " ORDER BY j.enc_id DESC";

$stmt = $conn->prepare($query);
if (!empty($search)) {
    $searchTerm = "%$search%";
    $stmt->bindValue(':q1', $searchTerm);
    $stmt->bindValue(':q2', $searchTerm);
    $stmt->bindValue(':q3', $searchTerm);
    $stmt->bindValue(':q4', $searchTerm);
}
$stmt->execute();
$jobs = $stmt->fetchAll();
?>

<div style="margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
	<div>
		<h2 style="font-weight: 800; color: #0f172a; margin: 0 0 6px 0; font-size: 26px;">Task Management</h2>
		<p style="color: #64748b; margin: 0; font-size: 15px;">View and manage all tasks posted on TaskBuddy.</p>
	</div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
	<div class="alert alert-success" style="border-radius: 8px;">
		<i class="fa fa-check-circle"></i> Task and associated applications deleted successfully.
	</div>
<?php endif; ?>

<div class="admin-table-card">
	<div class="admin-table-header">
		<div>
			<strong style="color: #0f172a;">All Tasks (<?php echo count($jobs); ?>)</strong>
		</div>
		
		<form method="GET" action="jobs" style="display: flex; gap: 8px; margin: 0;">
			<input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search task title, category, lister..." class="form-control input-sm" style="width: 250px; border-radius: 6px;">
			<button type="submit" class="btn btn-sm btn-default" style="border-radius: 6px;"><i class="fa fa-search"></i></button>
		</form>
	</div>

	<div class="table-responsive">
		<table class="table admin-table">
			<thead>
				<tr>
					<th>Task Title / ID</th>
					<th>Lister</th>
					<th>Category</th>
					<th>Type</th>
					<th>Location</th>
					<th>Applications</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($jobs)): ?>
					<tr><td colspan="7" class="text-center text-muted" style="padding: 30px !important;">No tasks found.</td></tr>
				<?php else: ?>
					<?php foreach($jobs as $job): ?>
						<tr>
							<td>
								<a href="job-detail?id=<?php echo urlencode($job['job_id']); ?>" style="color: #0f172a; font-size: 14px; font-weight: 700; text-decoration: none;">
									<?php echo htmlspecialchars($job['title']); ?>
								</a>
								<div style="font-size: 11.5px; color: #64748b; font-family: monospace;"><?php echo htmlspecialchars($job['job_id']); ?></div>
							</td>
							<td>
								<?php if (!empty($job['company'])): ?>
									<a href="user-detail?id=<?php echo urlencode($job['company']); ?>" style="color: #4f46e5; font-weight: 600;">
										<?php echo htmlspecialchars($job['company_name'] ?: 'Unknown Lister'); ?>
									</a>
								<?php else: ?>
									<span class="text-muted">Unknown Lister</span>
								<?php endif; ?>
							</td>
							<td><span class="label label-info" style="border-radius: 4px;"><?php echo htmlspecialchars($job['category']); ?></span></td>
							<td><span class="label label-default" style="border-radius: 4px;"><?php echo htmlspecialchars($job['type']); ?></span></td>
							<td><?php echo htmlspecialchars($job['city'] . ', ' . $job['country']); ?></td>
							<td>
								<a href="job-detail?id=<?php echo urlencode($job['job_id']); ?>" style="text-decoration: none;">
									<span class="badge" style="background: #e0e7ff; color: #4338ca; font-weight: 700; border-radius: 10px; padding: 4px 8px;">
										<?php echo (int)$job['app_count']; ?> bids
									</span>
								</a>
							</td>
							<td>
								<div style="display: flex; gap: 5px;">
									<a href="job-detail?id=<?php echo urlencode($job['job_id']); ?>" class="btn btn-xs btn-primary" style="border-radius: 4px;" title="Task Details & Applicants">
										<i class="fa fa-eye"></i>
									</a>
									<a href="../explore-job.php?jobid=<?php echo urlencode($job['job_id']); ?>" target="_blank" class="btn btn-xs btn-default" style="border-radius: 4px;" title="Live Task Page">
										<i class="fa fa-external-link"></i>
									</a>
									<a href="app/actions.php?action=delete_job&id=<?php echo urlencode($job['job_id']); ?>" onclick="return confirm('Are you sure you want to delete this task?');" class="btn btn-xs btn-danger" style="border-radius: 4px;" title="Delete Task">
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
