<form name="frm" action="app/create-account.php" method="POST" autocomplete="off" onsubmit="return val();">
	<?php require_once __DIR__ . '/csrf.php'; echo csrf_field(); ?>
	<div class="tb-auth-card">
		<div class="tb-auth-card-header text-center">
			<div class="tb-auth-badge"><i class="fa fa-user-circle"></i> Task Seeker Registration</div>
			<h3 class="tb-auth-title">Start Earning With Your Skills</h3>
			<p class="tb-auth-subtitle">Create your seeker profile, find local gigs & get hired by verified clients.</p>
		</div>

		<div class="tb-role-switch-container">
			<a href="register?p=Employee" class="tb-role-btn active"><i class="fa fa-wrench"></i> Task Seeker</a>
			<a href="register?p=Employer" class="tb-role-btn"><i class="fa fa-building"></i> Task Lister</a>
		</div>

		<div class="tb-auth-card-body">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group tb-modern-group">
						<label><i class="fa fa-user"></i> First Name</label>
						<input class="form-control tb-modern-input" placeholder="e.g. Jahanzaib" name="fname" required type="text">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group tb-modern-group">
						<label><i class="fa fa-user-o"></i> Last Name</label>
						<input class="form-control tb-modern-input" placeholder="e.g. Arain" name="lname" required type="text">
					</div>
				</div>
			</div>

			<div class="form-group tb-modern-group">
				<label><i class="fa fa-envelope-o"></i> Email Address</label>
				<input class="form-control tb-modern-input" placeholder="e.g. jahanzaib@example.com" name="email" required type="email">
			</div>

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group tb-modern-group">
						<label><i class="fa fa-lock"></i> Password</label>
						<div class="tb-password-wrapper">
							<input class="form-control tb-modern-input" id="empPassword" placeholder="Min 8 characters" name="password" required type="password">
							<span class="tb-pwd-toggle" onclick="togglePwd('empPassword', this)"><i class="fa fa-eye"></i></span>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group tb-modern-group">
						<label><i class="fa fa-check-circle-o"></i> Confirm Password</label>
						<div class="tb-password-wrapper">
							<input class="form-control tb-modern-input" id="empConfirmPassword" placeholder="Re-type password" name="confirmpassword" required type="password">
							<span class="tb-pwd-toggle" onclick="togglePwd('empConfirmPassword', this)"><i class="fa fa-eye"></i></span>
						</div>
					</div>
				</div>
			</div>

			<input type="hidden" name="acctype" value="101">

			<div class="tb-terms-hint">
				By registering, you agree to TaskBuddy's <a href="contact">Terms of Service</a> & <a href="contact">Privacy Policy</a>.
			</div>

			<button type="submit" name="reg_mode" class="tb-auth-submit-btn" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important; color: #ffffff !important;">
				<span style="color: #ffffff !important; font-weight: 700 !important; font-size: 15px !important;">Create Seeker Account</span>
				<i class="fa fa-arrow-right" style="color: #ffffff !important;"></i>
			</button>

			<div class="tb-auth-card-footer text-center">
				Already have an account? <a href="login" class="tb-auth-login-link">Sign in here</a>
			</div>
		</div>
	</div>
</form>