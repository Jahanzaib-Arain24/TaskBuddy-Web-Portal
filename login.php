<!doctype html>
<html lang="en">
<?php 
include 'constants/settings.php'; 
include 'constants/check-login.php';
?>
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Task Buddy - Login</title>
	
	<meta name="description" content="Online Task Management / Task Portal" />
	<meta name="keywords" content="job, work, resume, applicants, application, employee, employer, hire, hiring, human resource management, hr, online job management, company, worker, career, recruiting, recruitment" />
	<meta name="author" content="BwireSoft">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta property="og:image" content="http://<?php echo "$actual_link"; ?>/images/banner.jpg" />
    <meta property="og:image:secure_url" content="https://<?php echo "$actual_link"; ?>/images/banner.jpg" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="300" />
    <meta property="og:image:alt" content="Task Buddy" />
    <meta property="og:description" content="Online Task Management / Task Portal" />

	<link rel="shortcut icon" href="images/ico/favicon.png">

	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" media="screen">	
	<link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/component.css" rel="stylesheet">
	
	<link rel="stylesheet" href="icons/linearicons/style.css">
	<link rel="stylesheet" href="icons/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="icons/simple-line-icons/css/simple-line-icons.css">
	<link rel="stylesheet" href="icons/ionicons/css/ionicons.css">
	<link rel="stylesheet" href="icons/pe-icon-7-stroke/css/pe-icon-7-stroke.css">
	<link rel="stylesheet" href="icons/rivolicons/style.css">
	<link rel="stylesheet" href="icons/flaticon-line-icon-set/flaticon-line-icon-set.css">
	<link rel="stylesheet" href="icons/flaticon-streamline-outline/flaticon-streamline-outline.css">
	<link rel="stylesheet" href="icons/flaticon-thick-icons/flaticon-thick.css">
	<link rel="stylesheet" href="icons/flaticon-ventures/flaticon-ventures.css">

	<link href="css/style.css" rel="stylesheet">
	<link href="css/custom_premium.css?v=2026.2" rel="stylesheet">

 <script type="text/javascript">
   function update(str)
   {

	if(document.getElementById('mymail').value == "")
   {
	alert("Please enter your email");

    }else{
		  document.getElementById("data").innerHTML = "Please wait...";
      var xmlhttp;

      if (window.XMLHttpRequest)
      {
        xmlhttp=new XMLHttpRequest();
      }
      else
      {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }	

      xmlhttp.onreadystatechange = function() {
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
        {
          document.getElementById("data").innerHTML = xmlhttp.responseText;
        }
      }

      xmlhttp.open("GET","app/reset-pw.php?opt="+str, true);
      xmlhttp.send();
}

  }
  
   function reset_text()
   {  
   document.getElementById('mymail').value = "";
   document.getElementById("data").innerHTML = "";
   }

   function togglePwd(id, btn) {
       var inp = document.getElementById(id);
       if (!inp) return;
       if (inp.type === 'password') {
           inp.type = 'text';
           btn.innerHTML = '<i class="fa fa-eye-slash"></i>';
       } else {
           inp.type = 'password';
           btn.innerHTML = '<i class="fa fa-eye"></i>';
       }
   }
</script>
</head>


<body class="not-transparent-header">

	<div class="container-wrapper">


		<header id="header">


			<nav class="navbar navbar-default navbar-fixed-top navbar-sticky-function">

				<div class="container">
					
					<div class="logo-wrapper">
						<div class="logo">
							<a href="./"><img src="logo2.png" alt="Logo" /></a>
						</div>
					</div>
					
					<div id="navbar" class="navbar-nav-wrapper navbar-arrow">
					
						<ul class="nav navbar-nav" id="responsive-menu">
							<li><a href="./">Home</a></li>
							<li><a href="tasks">Tasks List</a></li>
							<li><a href="task-seekers">Task Seekers</a></li>
							<li><a href="task-listers">Task Listers</a></li>
							<li><a href="contact">Contact Us</a></li>
						</ul>
				
					</div>

					<div class="nav-mini-wrapper">
						<ul class="nav-mini sign-in">
							<li><a href="login" class="tb-nav-login-btn"><i class="fa fa-sign-in"></i> Sign In</a></li>
							<li><a href="register" class="tb-nav-cta-btn"><i class="fa fa-user-plus"></i> Join TaskBuddy</a></li>
						</ul>
					</div>
				
				</div>
				
				<div id="slicknav-mobile"></div>
				
			</nav>
	
			<div id="registerModal" class="modal fade login-box-wrapper" tabindex="-1" style="display: none;" data-backdrop="static" data-keyboard="false" data-replace="true">
			
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title text-center">Create your account for free</h4>
				</div>
				
				<div class="modal-body">
				
					<div class="row gap-20">
					
						<div class="col-sm-6 col-md-6">
							<a href="register?p=Employer" class="btn btn-facebook btn-block mb-5-xs">Register as Task Lister</a>
						</div>
						<div class="col-sm-6 col-md-6">
							<a href="register?p=Employee" class="btn btn-facebook btn-block mb-5-xs">Register as Task Seeker</a>
						</div>

					</div>
				
				</div>
				
				<div class="modal-footer text-center">
					<button type="button" data-dismiss="modal" class="btn btn-primary btn-inverse">Close</button>
				</div>
				
			</div>



			
		</header>


		<div class="main-wrapper">

			<div class="login-container-wrapper" style="padding-top: 40px;">	
	
				<div class="container">
				
					<div class="row">
					
						<div class="col-md-10 col-md-offset-1">
						
							<div class="row">

								<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                                <?php
								include 'constants/check_reply.php';	
								?>
                                <form name="frm" action="app/auth.php" method="POST" autocomplete="off">
                                <?php require_once 'constants/csrf.php'; echo csrf_field(); ?>
                                <div class="tb-auth-card">
									<div class="tb-auth-card-header text-center">
										<div class="tb-auth-badge"><i class="fa fa-sign-in"></i> Welcome Back</div>
										<h3 class="tb-auth-title">Sign In to TaskBuddy</h3>
										<p class="tb-auth-subtitle">Access your task dashboard, track applications & manage gigs.</p>
									</div>

									<div class="tb-auth-card-body">
										<!-- Quick Demo Auto-Fill Suggestions -->
										<div style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; margin-bottom: 20px;">
											<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 9px;">
												<span style="font-size: 11px; font-weight: 800; color: #334155; text-transform: uppercase; letter-spacing: 0.6px; display: inline-flex; align-items: center; gap: 5px;">
													<i class="fa fa-bolt" style="color: #f59e0b;"></i> Demo Credentials
												</span>
												<span style="font-size: 10.5px; color: #64748b; font-weight: 600;">1-Click Auto Fill</span>
											</div>
											<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
												<button type="button" onclick="fillDemo('asad@gmail.com', 'pakistan123#', this)" style="border: 1px solid #cbd5e1; background: #ffffff; border-radius: 8px; padding: 8px 10px; cursor: pointer; text-align: left; transition: all 0.2s ease; display: flex; flex-direction: column; gap: 2px;">
													<div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
														<span style="font-size: 12px; font-weight: 700; color: #0f172a;"><i class="fa fa-user" style="color: #10b981;"></i> Seeker</span>
														<span style="background: #dcfce7; color: #166534; font-size: 9.5px; font-weight: 700; padding: 1px 6px; border-radius: 4px;">Fill</span>
													</div>
													<div style="font-size: 10.5px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">asad@gmail.com</div>
												</button>
												
												<button type="button" onclick="fillDemo('ali@gmail.com', 'pakistan123#', this)" style="border: 1px solid #cbd5e1; background: #ffffff; border-radius: 8px; padding: 8px 10px; cursor: pointer; text-align: left; transition: all 0.2s ease; display: flex; flex-direction: column; gap: 2px;">
													<div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
														<span style="font-size: 12px; font-weight: 700; color: #0f172a;"><i class="fa fa-building" style="color: #4f46e5;"></i> Lister</span>
														<span style="background: #dbeafe; color: #1e40af; font-size: 9.5px; font-weight: 700; padding: 1px 6px; border-radius: 4px;">Fill</span>
													</div>
													<div style="font-size: 10.5px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">ali@gmail.com</div>
												</button>
											</div>
										</div>

										<div class="form-group tb-modern-group">
											<label><i class="fa fa-envelope-o"></i> Email Address</label>
											<input class="form-control tb-modern-input" id="loginEmail" placeholder="e.g. yourname@example.com" name="email" required type="email">
										</div>

										<div class="form-group tb-modern-group">
											<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:7px;">
												<label style="margin:0;"><i class="fa fa-lock"></i> Password</label>
												<a data-toggle="modal" onclick="reset_text()" href="#forgotPasswordModal" style="font-size:12.5px; color:var(--primary); font-weight:600;">Forgot Password?</a>
											</div>
											<div class="tb-password-wrapper">
												<input class="form-control tb-modern-input" id="loginPassword" placeholder="Enter your password" name="password" required type="password">
												<span class="tb-pwd-toggle" onclick="togglePwd('loginPassword', this)"><i class="fa fa-eye"></i></span>
											</div>
										</div>

										<button type="submit" class="tb-auth-submit-btn" style="margin-top: 24px; background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important; color: #ffffff !important;">
											<span style="color: #ffffff !important; font-weight: 700 !important; font-size: 15px !important;">Sign In</span>
											<i class="fa fa-arrow-right" style="color: #ffffff !important;"></i>
										</button>

										<div class="tb-auth-card-footer text-center">
											Don't have an account? <a href="register" class="tb-auth-login-link">Register for free</a>
										</div>
									</div>
								</div>
								</form>
									
								</div>
							
							</div>
							
						</div>
						
					</div>
					
				</div>
			
			</div>

			<!-- Modern Responsive Restore Password Modal -->
			<div id="forgotPasswordModal" class="modal fade tb-auth-modal-root" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog tb-auth-modal-dialog" role="document">
					<div class="modal-content tb-auth-modal-content">
						<div class="modal-header tb-auth-modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
							<h4 class="modal-title text-center"><i class="fa fa-key" style="color: #4f46e5; margin-right: 6px;"></i> Restore Password</h4>
						</div>

						<div class="modal-body tb-auth-modal-body">
							<p class="tb-modal-subtext">Enter the email address associated with your account. We will send you a secure link to reset your password.</p>
							
							<div class="form-group tb-modern-group" style="margin-bottom: 0;"> 
								<label class="tb-modal-input-label"><i class="fa fa-envelope-o"></i> Email Address</label>
								<input id="mymail" autocomplete="off" name="email" class="form-control tb-modern-input" placeholder="e.g. yourname@example.com" type="email" required> 
							</div>
							
							<div id="data" style="margin-top: 12px;"></div>
						</div>
						
						<div class="modal-footer tb-auth-modal-footer">
							<button type="button" data-dismiss="modal" class="btn btn-default tb-modal-btn-cancel">Cancel</button>
							<button onclick="update(mymail.value)" type="button" class="btn btn-primary tb-modal-btn-submit">Restore Password</button>
						</div>
					</div>
				</div>
			</div>

			<?php include 'app/footer.php'; ?>
			
		</div>


	</div> 


<div id="back-to-top">
   <a href="#"><i class="ion-ios-arrow-up"></i></a>
</div>

<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-modalmanager.js"></script>
<script type="text/javascript" src="js/bootstrap-modal.js"></script>
<script type="text/javascript" src="js/smoothscroll.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/jquery.waypoints.min.js"></script>
<script type="text/javascript" src="js/wow.min.js"></script>
<script type="text/javascript" src="js/jquery.slicknav.min.js"></script>
<script type="text/javascript" src="js/jquery.placeholder.min.js"></script>
<script type="text/javascript" src="js/bootstrap-tokenfield.js"></script>
<script type="text/javascript" src="js/typeahead.bundle.min.js"></script>
<script type="text/javascript" src="js/bootstrap3-wysihtml5.min.js"></script>
<script type="text/javascript" src="js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="js/jquery-filestyle.min.js"></script>
<script type="text/javascript" src="js/bootstrap-select.js"></script>
<script type="text/javascript" src="js/ion.rangeSlider.min.js"></script>
<script type="text/javascript" src="js/handlebars.min.js"></script>
<script type="text/javascript" src="js/jquery.countimator.js"></script>
<script type="text/javascript" src="js/jquery.countimator.wheel.js"></script>
<script type="text/javascript" src="js/slick.min.js"></script>
<script type="text/javascript" src="js/easy-ticker.js"></script>
<script type="text/javascript" src="js/jquery.introLoader.min.js"></script>
<script type="text/javascript" src="js/jquery.responsivegrid.js"></script>
<script type="text/javascript" src="js/customs.js"></script>
<script>
function fillDemo(email, pass, btn) {
	var emailEl = document.getElementById('loginEmail');
	var passEl = document.getElementById('loginPassword');
	if (emailEl && passEl) {
		emailEl.value = email;
		passEl.value = pass;
		
		emailEl.style.transition = 'all 0.3s ease';
		passEl.style.transition = 'all 0.3s ease';
		emailEl.style.borderColor = '#4f46e5';
		emailEl.style.boxShadow = '0 0 0 3px rgba(79, 70, 229, 0.15)';
		passEl.style.borderColor = '#4f46e5';
		passEl.style.boxShadow = '0 0 0 3px rgba(79, 70, 229, 0.15)';
		
		setTimeout(function() {
			emailEl.style.borderColor = '';
			emailEl.style.boxShadow = '';
			passEl.style.borderColor = '';
			passEl.style.boxShadow = '';
		}, 800);
	}
}
</script>

</body>

</html>