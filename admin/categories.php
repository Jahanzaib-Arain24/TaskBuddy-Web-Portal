<?php
$page_title = "Manage Categories";
require_once __DIR__ . '/header.php';

$conn = get_db_connection();

$categories = $conn->query("SELECT c.*, 
              (SELECT COUNT(*) FROM tbl_jobs j WHERE j.category = c.category) as task_count
              FROM tbl_categories c ORDER BY c.category ASC")->fetchAll();
?>

<div style="margin-bottom: 25px;">
	<h2 style="font-weight: 800; color: #0f172a; margin: 0 0 6px 0; font-size: 26px;">Category Management</h2>
	<p style="color: #64748b; margin: 0; font-size: 15px;">Add, view, and manage task categories available across TaskBuddy.</p>
</div>

<?php if (isset($_GET['msg'])): ?>
	<?php if ($_GET['msg'] == 'added'): ?>
		<div class="alert alert-success" style="border-radius: 8px;"><i class="fa fa-check-circle"></i> New category added successfully.</div>
	<?php elseif ($_GET['msg'] == 'deleted'): ?>
		<div class="alert alert-success" style="border-radius: 8px;"><i class="fa fa-check-circle"></i> Category deleted successfully.</div>
	<?php endif; ?>
<?php endif; ?>

<div class="row">
	<div class="col-md-4">
		<div class="admin-table-card" style="padding: 22px;">
			<h4 style="margin: 0 0 16px 0; font-weight: 700; color: #0f172a;"><i class="fa fa-plus-circle text-primary"></i> Add New Category</h4>
			<form method="POST" action="app/actions.php">
				<input type="hidden" name="action" value="add_category">
				<?php echo csrf_field(); ?>
				
				<div class="form-group">
					<label style="font-size: 13px; color: #475569; font-weight: 600;">Category Name</label>
					<input type="text" name="category" required placeholder="e.g. Electrical & Plumbing" class="form-control" style="border-radius: 6px;">
				</div>
				
				<button type="submit" class="btn btn-primary btn-block" style="border-radius: 6px; font-weight: 600; padding: 10px;">
					<i class="fa fa-plus"></i> Save Category
				</button>
			</form>
		</div>
	</div>

	<div class="col-md-8">
		<div class="admin-table-card">
			<div class="admin-table-header">
				<h4><i class="fa fa-tags text-primary"></i> Existing Categories (<?php echo count($categories); ?>)</h4>
			</div>
			<div class="table-responsive">
				<table class="table admin-table">
					<thead>
						<tr>
							<th style="width: 60px;">#</th>
							<th>Category Name</th>
							<th>Active Tasks</th>
							<th style="width: 90px;">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($categories)): ?>
							<tr><td colspan="4" class="text-center text-muted" style="padding: 25px !important;">No categories found.</td></tr>
						<?php else: ?>
							<?php foreach($categories as $idx => $cat): ?>
								<tr>
									<td style="color: #94a3b8; font-weight: 600;"><?php echo $idx + 1; ?></td>
									<td>
										<strong style="color: #0f172a; font-size: 14px;"><?php echo htmlspecialchars($cat['category']); ?></strong>
									</td>
									<td>
										<span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 700; border-radius: 6px;">
											<?php echo (int)$cat['task_count']; ?> tasks
										</span>
									</td>
									<td>
										<a href="app/actions.php?action=delete_category&id=<?php echo urlencode($cat['id']); ?>" onclick="return confirm('Are you sure you want to delete this category?');" class="btn btn-xs btn-danger" style="border-radius: 4px;" title="Delete">
											<i class="fa fa-trash"></i> Delete
										</a>
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
