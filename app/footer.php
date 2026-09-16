<?php
$tb_root = (file_exists(__DIR__ . '/../logo-white.png') && !file_exists('logo-white.png')) ? '../' : '';
?>
<footer class="footer-wrapper tb-modern-footer">
	<div class="main-footer">
		<div class="container">
			<div class="row">
				<!-- Brand Column -->
				<div class="col-xs-12 col-sm-12 col-md-4 mb-30">
					<div class="footer-about-us">
						<a href="<?php echo $tb_root; ?>./" class="tb-footer-logo-link">
							<img src="<?php echo $tb_root; ?>logo-white.png" alt="TaskBuddy" class="tb-footer-logo" />
						</a>
						<p class="tb-footer-desc">
							TaskBuddy connects households and businesses with verified local taskers for home repairs, errands, cleaning, and handyman services across Pakistan.
						</p>
						<div class="tb-footer-trust-badges">
							<span class="tb-trust-pill"><i class="fa fa-shield"></i> Verified Taskers</span>
							<span class="tb-trust-pill"><i class="fa fa-lock"></i> Secure &amp; Safe</span>
							<span class="tb-trust-pill"><i class="fa fa-clock-o"></i> Fast Gigs</span>
						</div>
					</div>
				</div>

				<!-- Local Tasks -->
				<div class="col-xs-6 col-sm-4 col-md-3 mb-30">
					<h5 class="footer-title">Popular Tasks</h5>
					<ul class="footer-menu tb-footer-links">
						<li><a href="<?php echo $tb_root; ?>tasks?category=Cleaning+Services&country=Pakistan&search=%E2%9C%93"><i class="fa fa-angle-right"></i> Home Cleaning</a></li>
						<li><a href="<?php echo $tb_root; ?>tasks?category=Maintenance+and+Repair&country=Pakistan&search=%E2%9C%93"><i class="fa fa-angle-right"></i> Plumbing &amp; Repairs</a></li>
						<li><a href="<?php echo $tb_root; ?>tasks?category=Errands+and+Delivery&country=Pakistan&search=%E2%9C%93"><i class="fa fa-angle-right"></i> Errands &amp; Groceries</a></li>
						<li><a href="<?php echo $tb_root; ?>tasks?category=Home+Improvement&country=Pakistan&search=%E2%9C%93"><i class="fa fa-angle-right"></i> Home Improvement</a></li>
						<li><a href="<?php echo $tb_root; ?>tasks?category=Gardening+and+Landscaping&country=Pakistan&search=%E2%9C%93"><i class="fa fa-angle-right"></i> Gardening &amp; Lawn</a></li>
						<li><a href="<?php echo $tb_root; ?>tasks?category=Moving+and+Transport&country=Pakistan&search=%E2%9C%93"><i class="fa fa-angle-right"></i> Moving &amp; Transport</a></li>
					</ul>
				</div>

				<!-- Quick Links -->
				<div class="col-xs-6 col-sm-4 col-md-2 mb-30">
					<h5 class="footer-title">Platform</h5>
					<ul class="footer-menu tb-footer-links">
						<li><a href="<?php echo $tb_root; ?>./"><i class="fa fa-angle-right"></i> Home</a></li>
						<li><a href="<?php echo $tb_root; ?>tasks"><i class="fa fa-angle-right"></i> Browse Tasks</a></li>
						<li><a href="<?php echo $tb_root; ?>task-seekers"><i class="fa fa-angle-right"></i> Task Seekers</a></li>
						<li><a href="<?php echo $tb_root; ?>task-listers"><i class="fa fa-angle-right"></i> Task Listers</a></li>
						<li><a href="<?php echo $tb_root; ?>contact"><i class="fa fa-angle-right"></i> Contact &amp; Help</a></li>
						<li><a href="<?php echo $tb_root; ?>register"><i class="fa fa-angle-right"></i> Join Free</a></li>
					</ul>
				</div>

				<!-- Contact & Support Column -->
				<div class="col-xs-12 col-sm-4 col-md-3 mb-30">
					<h5 class="footer-title">Need Help?</h5>
					<div class="tb-footer-contact-box">
						<div class="tb-f-contact-item">
							<i class="fa fa-map-marker"></i>
							<div>
								<strong>Headquarters:</strong>
								<span>Latifabad Unit 11, Hyderabad, Pakistan.</span>
							</div>
						</div>
						<div class="tb-f-contact-item">
							<i class="fa fa-envelope-o"></i>
							<div>
								<strong>Direct Email:</strong>
								<a href="mailto:arainjhanzaib@gmail.com">arainjhanzaib@gmail.com</a>
							</div>
						</div>
						<div class="tb-f-contact-item">
							<i class="fa fa-phone"></i>
							<div>
								<strong>Phone Support:</strong>
								<a href="tel:+923123358542">+92 312 3358542</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="bottom-footer">
		<div class="container">
			<div class="tb-bottom-footer-row">
				<div class="tb-copyright-col">
					<p class="tb-copyright-text">
						&#169; <?php echo date('Y'); ?> <strong>TaskBuddy</strong> &nbsp;—&nbsp; Designed &amp; Developed by <strong>Muhammad Jahanzaib</strong>.
					</p>
				</div>
				<div class="tb-social-col">
					<ul class="tb-social-list">
						<li><a href="https://twitter.com" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a></li>
						<li><a href="https://facebook.com" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a></li>
						<li><a href="https://instagram.com" target="_blank" title="Instagram"><i class="fa fa-instagram"></i></a></li>
						<li><a href="https://linkedin.com" target="_blank" title="LinkedIn"><i class="fa fa-linkedin"></i></a></li>
						<li><a href="https://github.com" target="_blank" title="GitHub"><i class="fa fa-github"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</footer>

<script>
// TaskBuddy PWA Service Worker Registration
if ('serviceWorker' in navigator) {
	window.addEventListener('load', function() {
		var swPath = '<?php echo $tb_root; ?>sw.js';
		navigator.serviceWorker.register(swPath).then(function(reg) {
			console.log('TaskBuddy PWA ServiceWorker registered with scope:', reg.scope);
		}).catch(function(err) {
			console.log('TaskBuddy PWA ServiceWorker registration failed:', err);
		});
	});
}
</script>
