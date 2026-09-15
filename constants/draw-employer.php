<form name="frm" action="app/create-account.php" method="POST" autocomplete="off" onsubmit="return val();">
	<?php require_once __DIR__ . '/csrf.php'; echo csrf_field(); ?>
	<div class="tb-auth-card">
		<div class="tb-auth-card-header text-center">
			<div class="tb-auth-badge employer"><i class="fa fa-briefcase"></i> Task Lister / Employer Registration</div>
			<h3 class="tb-auth-title">Hire Top Local Taskers Quickly</h3>
			<p class="tb-auth-subtitle">Post tasks, get quick bids from verified helpers & get your work done with peace of mind.</p>
		</div>

		<div class="tb-role-switch-container">
			<a href="register?p=Employee" class="tb-role-btn"><i class="fa fa-wrench"></i> Task Seeker</a>
			<a href="register?p=Employer" class="tb-role-btn active"><i class="fa fa-building"></i> Task Lister</a>
		</div>

		<div class="tb-auth-card-body">
			<div class="form-group tb-modern-group">
				<label><i class="fa fa-building-o"></i> Business / Lister Name</label>
				<input class="form-control tb-modern-input" placeholder="e.g. Apex Home Care, Local Household, or Lister Name" name="company" required type="text">
			</div>

			<div class="form-group tb-modern-group">
				<label><i class="fa fa-tags"></i> Task / Service Category</label>
				<input class="form-control tb-modern-input" placeholder="e.g. Home Services, Maintenance, Renovation, Electrician" name="type" required type="text">
			</div>

			<div class="form-group tb-modern-group">
				<label><i class="fa fa-envelope-o"></i> Official / Work Email Address</label>
				<input class="form-control tb-modern-input" placeholder="e.g. contact@business.com" name="email" required type="email">
			</div>

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group tb-modern-group">
						<label><i class="fa fa-lock"></i> Password</label>
						<div class="tb-password-wrapper">
							<input class="form-control tb-modern-input" id="emprPassword" placeholder="Min 8 characters" name="password" required type="password">
							<span class="tb-pwd-toggle" onclick="togglePwd('emprPassword', this)"><i class="fa fa-eye"></i></span>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group tb-modern-group">
						<label><i class="fa fa-check-circle-o"></i> Confirm Password</label>
						<div class="tb-password-wrapper">
							<input class="form-control tb-modern-input" id="emprConfirmPassword" placeholder="Re-type password" name="confirmpassword" required type="password">
							<span class="tb-pwd-toggle" onclick="togglePwd('emprConfirmPassword', this)"><i class="fa fa-eye"></i></span>
						</div>
					</div>
				</div>
			</div>

			<input type="hidden" name="acctype" value="102">

			<div class="tb-terms-hint">
				By registering, you agree to TaskBuddy's <a href="contact">Terms of Service</a> & <a href="contact">Privacy Policy</a>.
			</div>

			<button type="submit" name="reg_mode" class="tb-auth-submit-btn employer" style="background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%) !important; color: #ffffff !important;">
				<span style="color: #ffffff !important; font-weight: 700 !important; font-size: 15px !important;">Create Lister Account</span>
				<i class="fa fa-arrow-right" style="color: #ffffff !important;"></i>
			</button>

			<div class="tb-auth-card-footer text-center">
				Already have an account? <a href="login" class="tb-auth-login-link">Sign in here</a>
			</div>
		</div>
	</div>
</form>