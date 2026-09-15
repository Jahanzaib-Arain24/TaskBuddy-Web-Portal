<?php
$page_title = "Manage Alerts";
require_once __DIR__ . '/header.php';

$conn = get_db_connection();
$alerts = $conn->query("SELECT * FROM tbl_alerts ORDER BY code ASC")->fetchAll();
?>

<div style="margin-bottom: 25px;">
	<h2 style="font-weight: 800; color: #0f172a; margin: 0 0 6px 0; font-size: 26px;">System Alerts Management</h2>
	<p style="color: #64748b; margin: 0; font-size: 15px;">View and edit flash message alerts shown across registration, login, and dashboard actions.</p>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
	<div class="alert alert-success" style="border-radius: 8px;"><i class="fa fa-check-circle"></i> Alert message updated successfully.</div>
<?php endif; ?>

<div class="admin-table-card">
	<div class="admin-table-header">
		<h4><i class="fa fa-bell-o text-primary"></i> Configured System Alerts (<?php echo count($alerts); ?>)</h4>
	</div>
	<div class="table-responsive">
		<table class="table admin-table">
			<thead>
				<tr>
					<th style="width: 100px;">Code</th>
					<th>Alert Description / Message</th>
					<th style="width: 130px;">Alert Type</th>
					<th style="width: 100px;">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($alerts)): ?>
					<tr><td colspan="4" class="text-center text-muted" style="padding: 25px !important;">No alerts found.</td></tr>
				<?php else: ?>
					<?php foreach($alerts as $al): ?>
						<tr>
							<td>
								<span class="badge" style="background: #0f172a; color: #ffffff; font-family: monospace; padding: 5px 8px; border-radius: 4px;">
									#<?php echo htmlspecialchars($al['code']); ?>
								</span>
							</td>
							<td>
								<form method="POST" action="app/actions.php" style="margin: 0; display: flex; gap: 10px; align-items: center;">
									<input type="hidden" name="action" value="update_alert">
									<input type="hidden" name="code" value="<?php echo htmlspecialchars($al['code']); ?>">
									<?php echo csrf_field(); ?>
									
									<input type="text" name="description" value="<?php echo htmlspecialchars($al['description']); ?>" class="form-control input-sm" style="border-radius: 6px; flex: 1;">
							</td>
							<td>
									<select name="type" class="form-control input-sm" style="border-radius: 6px;">
										<option value="success" <?php echo ($al['type'] == 'success') ? 'selected' : ''; ?>>Success (Green)</option>
										<option value="warning" <?php echo ($al['type'] == 'warning') ? 'selected' : ''; ?>>Warning (Yellow)</option>
										<option value="danger" <?php echo ($al['type'] == 'danger') ? 'selected' : ''; ?>>Danger (Red)</option>
										<option value="info" <?php echo ($al['type'] == 'info') ? 'selected' : ''; ?>>Info (Blue)</option>
									</select>
							</td>
							<td>
									<button type="submit" class="btn btn-xs btn-primary" style="border-radius: 4px; padding: 5px 12px; font-weight: 600;">
										<i class="fa fa-save"></i> Save
									</button>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
