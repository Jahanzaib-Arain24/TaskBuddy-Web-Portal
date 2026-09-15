<?php
$page_title = "Manage Users";
require_once __DIR__ . '/header.php';

$conn = get_db_connection();
$filter_role = isset($_GET['role']) ? trim($_GET['role']) : '';
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

$query = "SELECT * FROM tbl_users WHERE role != 'admin'";
$params = [];

if (!empty($filter_role)) {
    $query .= " AND role = :role";
    $params[':role'] = $filter_role;
}

if (!empty($search)) {
    $query .= " AND (first_name LIKE :q1 OR last_name LIKE :q2 OR email LIKE :q3 OR member_no LIKE :q4)";
    $searchTerm = "%$search%";
    $params[':q1'] = $searchTerm;
    $params[':q2'] = $searchTerm;
    $params[':q3'] = $searchTerm;
    $params[':q4'] = $searchTerm;
}

$query .= " ORDER BY member_no DESC";

$stmt = $conn->prepare($query);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->execute();
$users = $stmt->fetchAll();
?>

<div style="margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
	<div>
		<h2 style="font-weight: 800; color: #0f172a; margin: 0 0 6px 0; font-size: 26px;">User Management</h2>
		<p style="color: #64748b; margin: 0; font-size: 14.5px;">View, search, and manage registered Task Seekers and Task Listers.</p>
	</div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
	<div class="alert alert-success" style="border-radius: 8px;">
		<i class="fa fa-check-circle"></i> User and associated data deleted successfully.
	</div>
<?php endif; ?>

<div class="admin-table-card">
	<div class="admin-table-header">
		<div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
			<a href="users" class="btn btn-sm <?php echo empty($filter_role) ? 'btn-primary' : 'btn-default'; ?>" style="border-radius: 6px; font-weight: 600;">All Users (<?php echo count($users); ?>)</a>
			<a href="users?role=employee" class="btn btn-sm <?php echo ($filter_role == 'employee') ? 'btn-primary' : 'btn-default'; ?>" style="border-radius: 6px; font-weight: 600;">Task Seekers</a>
			<a href="users?role=employer" class="btn btn-sm <?php echo ($filter_role == 'employer') ? 'btn-primary' : 'btn-default'; ?>" style="border-radius: 6px; font-weight: 600;">Task Listers</a>
		</div>
		
		<form method="GET" action="users" style="display: flex; gap: 8px; margin: 0;">
			<?php if (!empty($filter_role)): ?>
				<input type="hidden" name="role" value="<?php echo htmlspecialchars($filter_role); ?>">
			<?php endif; ?>
			<input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search name, email, ID..." class="form-control input-sm" style="width: 240px; border-radius: 6px;">
			<button type="submit" class="btn btn-sm btn-default" style="border-radius: 6px;"><i class="fa fa-search"></i></button>
		</form>
	</div>

	<div class="table-responsive">
		<table class="table admin-table">
			<thead>
				<tr>
					<th>User / Member ID</th>
					<th>Email</th>
					<th>Role</th>
					<th>Phone</th>
					<th>Location</th>
					<th>Last Login</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($users)): ?>
					<tr><td colspan="7" class="text-center text-muted" style="padding: 30px !important;">No users found matching your search.</td></tr>
				<?php else: ?>
					<?php foreach($users as $u): ?>
						<tr>
							<td>
								<a href="user-detail?id=<?php echo urlencode($u['member_no']); ?>" style="color: #0f172a; font-size: 14px; font-weight: 700; text-decoration: none;">
									<?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?>
								</a>
								<div style="font-size: 11.5px; color: #64748b; font-family: monospace;"><?php echo htmlspecialchars($u['member_no']); ?></div>
							</td>
							<td><?php echo htmlspecialchars($u['email']); ?></td>
							<td>
								<span class="admin-badge-role admin-badge-<?php echo htmlspecialchars($u['role']); ?>">
									<?php echo ($u['role'] == 'employer') ? 'Task Lister' : 'Task Seeker'; ?>
								</span>
							</td>
							<td><?php echo htmlspecialchars($u['phone'] ?: 'N/A'); ?></td>
							<td><?php echo htmlspecialchars($u['city'] ?: ($u['country'] ?: 'N/A')); ?></td>
							<td style="font-size: 12px; color: #64748b;"><?php echo htmlspecialchars($u['last_login'] ?: 'Never'); ?></td>
							<td>
								<div style="display: flex; gap: 5px;">
									<a href="user-detail?id=<?php echo urlencode($u['member_no']); ?>" class="btn btn-xs btn-primary" style="border-radius: 4px;" title="View Profile & Application History">
										<i class="fa fa-eye"></i>
									</a>
									<?php if ($u['role'] == 'employee'): ?>
										<a href="../employee-detail.php?empid=<?php echo urlencode($u['member_no']); ?>" target="_blank" class="btn btn-xs btn-default" style="border-radius: 4px;" title="Public Profile"><i class="fa fa-external-link"></i></a>
									<?php elseif ($u['role'] == 'employer'): ?>
										<a href="../company.php?ref=<?php echo urlencode($u['member_no']); ?>" target="_blank" class="btn btn-xs btn-default" style="border-radius: 4px;" title="Public Company Page"><i class="fa fa-external-link"></i></a>
									<?php endif; ?>
									
									<a href="app/actions.php?action=delete_user&id=<?php echo urlencode($u['member_no']); ?>" onclick="return confirm('Are you sure you want to delete this user? All their tasks/applications will also be removed.');" class="btn btn-xs btn-danger" style="border-radius: 4px;" title="Delete User">
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
