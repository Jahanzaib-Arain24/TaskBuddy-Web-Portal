<!doctype html>
<html lang="en">
<?php 
require 'constants/settings.php'; 
require 'constants/check-login.php';
require 'constants/db_config.php';
if (isset($_GET['empid'])) {
$empid = $_GET['empid'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE role = 'employee' AND member_no = :empid");
	$stmt->bindParam(':empid', $empid);
    $stmt->execute();
    $result = $stmt->fetchAll();
	$rec = count($result);
	if ($rec == "0") {
	header("location:./");	
	}else{

    foreach($result as $row)
    {
	$myfname = $row['first_name'];
	$mylname = $row['last_name'];
	$bdate = $row['bdate'];
	$bmonth = $row['bmonth'];
	$byear = $row['byear'];
	$mycountry = $row['country'];
	$mycity = $row['city'];
	$myphone = $row['phone'];
	$about = $row['about'];
	$empavatar = $row['avatar'];
	$current_year = (int)date('Y');
	$byear_int = (!empty($byear) && is_numeric($byear)) ? (int)$byear : 0;
	$myage = ($byear_int > 1900 && $byear_int <= $current_year) ? ($current_year - $byear_int) : "";
	$myedu = $row['education'];
	$mytitle = $row['title'];
	$mymail = $row['email'];
	}
	
	}

					  
	}catch(PDOException $e)
    {
        error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    }


	
}else{
header("location:./");	
}

?>
<head>
	<base href="/">

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Task BuddyTask Buddy - <?php echo "$myfname"; ?> <?php echo "$mylname"; ?></title>
	<meta name="description" content="Online Task Management / Task Portal" />
	<meta name="keywords" content="job, work, resume, applicants, application, employee, employer, hire, hiring, human resource management, hr, online job management, company, worker, career, recruiting, recruitment" />
	<meta name="author" content="BwireSoft">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta property="og:image" content="http://<?php echo "$actual_link"; ?>/images/banner.jpg" />
    <meta property="og:image:secure_url" content="https://<?php echo "$actual_link"; ?>/images/banner.jpg" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="300" />
    <meta property="og:image:alt" content="Bwire Tasks" />
    <meta property="og:description" content="Online Task Management / Task Portal" />

	<link rel="shortcut icon" href="images/ico/favicon.png">
	
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
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

	
</head>

  <style>
  
    .autofit2 {
	height:110px;
	width:120px;
    object-fit:cover; 
  }
  
  </style>
  
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
							<li class="active"><a href="task-seekers">Task Seekers</a></li>
							<li><a href="task-listers">Task Listers</a></li>
							<li><a href="contact">Contact Us</a></li>
						</ul>
				
					</div>

					<div class="nav-mini-wrapper">
						<ul class="nav-mini sign-in">
						<?php
						if ($user_online == true) {
						print '
						    <li><a href="'.$myrole.'" class="tb-nav-login-btn"><i class="fa fa-tachometer"></i> Dashboard</a></li>
							<li><a href="logout" class="tb-nav-cta-btn" style="background: #ef4444 !important;"><i class="fa fa-sign-out"></i> Logout</a></li>';
						}else{
						print '
							<li><a href="login" class="tb-nav-login-btn"><i class="fa fa-sign-in"></i> Sign In</a></li>
							<li><a href="register" class="tb-nav-cta-btn"><i class="fa fa-user-plus"></i> Join TaskBuddy</a></li>';						
						}
						?>
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
			
			<div class="section sm tb-detail-page-container">
			
				<div class="container">
				
					<div class="row">
						
							<div class="col-md-10 col-md-offset-1">
							
								<div class="employee-detail-wrapper">
								
									<div class="tb-detail-hero-card text-center">
										
										<div style="width: 110px; height: 110px; margin: 0 auto 16px; border-radius: 50%; padding: 4px; background: #ffffff; box-shadow: 0 8px 24px rgba(79, 70, 229, 0.12); border: 2px solid #e0e7ff; overflow: hidden; display: flex; align-items: center; justify-content: center;">
										<?php 
										if (empty($empavatar)) {
											$avSvg = get_initials_avatar($myfname, $mylname);
											print '<img style="width:100%; height:100%; object-fit:cover; border-radius:50%;" src="'.$avSvg.'" alt="image" />';
										} else {
											echo '<img style="width:100%; height:100%; object-fit:cover; border-radius:50%;" alt="image" src="data:image/jpeg;base64,'.base64_encode($empavatar).'"/>';	
										}
										?>
										</div>
										
										<h1 class="tb-detail-hero-title"><?php echo "$myfname $mylname"; ?></h1>
									
										<div class="tb-detail-hero-subtitle">
											<span><i class="fa fa-map-marker text-primary"></i> <?php echo "$mycity, $mycountry"; ?></span>
											<span>•</span>
											<span><i class="fa fa-phone text-primary"></i> <?php echo "$myphone"; ?></span>
											<span style="background: #eef2ff; color: #4f46e5; padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 700; margin-left: 4px;">
												<i class="fa fa-check-circle"></i> Verified Task Seeker
											</span>
										</div>
										
										<div class="tb-meta-grid">
											<div class="tb-meta-box">
												<div class="tb-meta-icon"><i class="fa fa-birthday-cake"></i></div>
												<div class="tb-meta-body">
													<div class="tb-meta-label">Date of Birth</div>
													<div class="tb-meta-val"><?php echo "$bdate/$bmonth/$byear"; ?></div>
												</div>
											</div>
											<div class="tb-meta-box">
												<div class="tb-meta-icon"><i class="fa fa-user"></i></div>
												<div class="tb-meta-body">
													<div class="tb-meta-label">Age</div>
													<div class="tb-meta-val"><?php echo !empty($myage) ? "$myage years old" : "N/A"; ?></div>
												</div>
											</div>
											<div class="tb-meta-box">
												<div class="tb-meta-icon"><i class="fa fa-graduation-cap"></i></div>
												<div class="tb-meta-body">
													<div class="tb-meta-label">Qualification</div>
													<div class="tb-meta-val" title="<?php echo htmlspecialchars("$myedu in $mytitle"); ?>"><?php echo "$myedu"; ?><?php if (!empty($mytitle)) echo " in $mytitle"; ?></div>
												</div>
											</div>
											<div class="tb-meta-box">
												<div class="tb-meta-icon"><i class="fa fa-envelope-o"></i></div>
												<div class="tb-meta-body">
													<div class="tb-meta-label">Email</div>
													<div class="tb-meta-val" title="<?php echo htmlspecialchars($mymail); ?>"><?php echo "$mymail"; ?></div>
												</div>
											</div>
										</div>
										
									</div>
						
									<div class="tb-section-card">
										<h3 class="tb-section-card-title">
											<span class="tb-icon-circle"><i class="fa fa-user"></i></span>
											Professional Summary & About Me
										</h3>
										<div class="tb-section-card-content">
											<?php echo format_task_text($about); ?>
										</div>
									</div>
									
									<?php
									require_once 'constants/db_config.php';
									try {
										$conn_detail = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
										$conn_detail->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

										// 1. Education
										$stmt_edu = $conn_detail->prepare("SELECT * FROM tbl_academic_qualification WHERE member_no = :empid ORDER BY id");
										$stmt_edu->bindParam(':empid', $empid);
										$stmt_edu->execute();
										$res_edu = $stmt_edu->fetchAll();
										if (count($res_edu) > 0) {
											echo '<div class="tb-section-card">';
											echo '<h3 class="tb-section-card-title"><span class="tb-icon-circle"><i class="fa fa-graduation-cap"></i></span> Academic Education</h3>';
											foreach($res_edu as $row) {
												echo '<div class="tb-profile-item-card">';
												echo '<div class="tb-profile-item-header">';
												echo '<h5 class="tb-profile-item-title">'.htmlspecialchars($row['course']).'</h5>';
												echo '<span class="tb-profile-item-badge"><i class="fa fa-clock-o"></i> '.htmlspecialchars($row['timeframe']).'</span>';
												echo '</div>';
												echo '<div class="tb-profile-item-sub"><strong>Level:</strong> '.htmlspecialchars($row['level']).' &nbsp;•&nbsp; <span class="text-primary font600">'.htmlspecialchars($row['institution']).'</span>, '.htmlspecialchars($row['country']).'</div>';
												echo '<a target="_blank" class="btn btn-primary btn-sm" style="border-radius: 6px; padding: 4px 12px; font-size: 12px; margin-top: 4px;" href="view-certificate.php?id='.$row['id'].'"><i class="fa fa-eye"></i> View Certificate</a>';
												echo '</div>';
											}
											echo '</div>';
										}

										// 2. Experience
										$stmt_exp = $conn_detail->prepare("SELECT * FROM tbl_experience WHERE member_no = :empid ORDER BY id");
										$stmt_exp->bindParam(':empid', $empid);
										$stmt_exp->execute();
										$res_exp = $stmt_exp->fetchAll();
										if (count($res_exp) > 0) {
											echo '<div class="tb-section-card">';
											echo '<h3 class="tb-section-card-title"><span class="tb-icon-circle"><i class="fa fa-briefcase"></i></span> Work Experience</h3>';
											foreach($res_exp as $row) {
												echo '<div class="tb-profile-item-card">';
												echo '<div class="tb-profile-item-header">';
												echo '<h5 class="tb-profile-item-title">'.htmlspecialchars($row['title']).'</h5>';
												echo '<span class="tb-profile-item-badge"><i class="fa fa-calendar"></i> '.htmlspecialchars($row['start_date']).' to '.htmlspecialchars($row['end_date']).'</span>';
												echo '</div>';
												echo '<div class="tb-profile-item-sub"><span class="text-primary font600">'.htmlspecialchars($row['institution']).'</span>';
												if (!empty($row['supervisor'])) {
													echo ' &nbsp;•&nbsp; <span>Supervisor: '.htmlspecialchars($row['supervisor']).' ('.htmlspecialchars($row['supervisor_phone']).')</span>';
												}
												echo '</div>';
												if (!empty($row['duties'])) {
													echo '<div class="tb-profile-item-body">'.format_task_text($row['duties']).'</div>';
												}
												echo '</div>';
											}
											echo '</div>';
										}

										// 3. Training
										$stmt_trn = $conn_detail->prepare("SELECT * FROM tbl_training WHERE member_no = :empid ORDER BY id");
										$stmt_trn->bindParam(':empid', $empid);
										$stmt_trn->execute();
										$res_trn = $stmt_trn->fetchAll();
										if (count($res_trn) > 0) {
											echo '<div class="tb-section-card">';
											echo '<h3 class="tb-section-card-title"><span class="tb-icon-circle"><i class="fa fa-certificate"></i></span> Trainings & Workshops</h3>';
											foreach($res_trn as $row) {
												echo '<div class="tb-profile-item-card">';
												echo '<div class="tb-profile-item-header">';
												echo '<h5 class="tb-profile-item-title">'.htmlspecialchars($row['training']).'</h5>';
												echo '<span class="tb-profile-item-badge"><i class="fa fa-clock-o"></i> '.htmlspecialchars($row['timeframe']).'</span>';
												echo '</div>';
												echo '<div class="tb-profile-item-sub"><span class="text-primary font600">'.htmlspecialchars($row['institution']).'</span></div>';
												echo '<a target="_blank" class="btn btn-primary btn-sm" style="border-radius: 6px; padding: 4px 12px; font-size: 12px; margin-top: 4px;" href="view-certificate-b.php?id='.$row['id'].'"><i class="fa fa-eye"></i> View Certificate</a>';
												echo '</div>';
											}
											echo '</div>';
										}

										// 4. Professional Qualifications
										$stmt_pq = $conn_detail->prepare("SELECT * FROM tbl_professional_qualification WHERE member_no = :empid ORDER BY id");
										$stmt_pq->bindParam(':empid', $empid);
										$stmt_pq->execute();
										$res_pq = $stmt_pq->fetchAll();
										if (count($res_pq) > 0) {
											echo '<div class="tb-section-card">';
											echo '<h3 class="tb-section-card-title"><span class="tb-icon-circle"><i class="fa fa-star"></i></span> Professional Qualifications</h3>';
											foreach($res_pq as $row) {
												echo '<div class="tb-profile-item-card">';
												echo '<div class="tb-profile-item-header">';
												echo '<h5 class="tb-profile-item-title">'.htmlspecialchars($row['title']).'</h5>';
												echo '<span class="tb-profile-item-badge"><i class="fa fa-clock-o"></i> '.htmlspecialchars($row['timeframe']).'</span>';
												echo '</div>';
												echo '<div class="tb-profile-item-sub"><span class="text-primary font600">'.htmlspecialchars($row['institution']).'</span>, '.htmlspecialchars($row['country']).'</div>';
												echo '<a target="_blank" class="btn btn-primary btn-sm" style="border-radius: 6px; padding: 4px 12px; font-size: 12px; margin-top: 4px;" href="view-certificate-c.php?id='.$row['id'].'"><i class="fa fa-eye"></i> View Certificate</a>';
												echo '</div>';
											}
											echo '</div>';
										}

										// 5. Other Attachments
										$stmt_att = $conn_detail->prepare("SELECT * FROM tbl_other_attachments WHERE member_no = :empid ORDER BY id");
										$stmt_att->bindParam(':empid', $empid);
										$stmt_att->execute();
										$res_att = $stmt_att->fetchAll();
										if (count($res_att) > 0) {
											echo '<div class="tb-section-card">';
											echo '<h3 class="tb-section-card-title"><span class="tb-icon-circle"><i class="fa fa-paperclip"></i></span> Other Documents & Attachments</h3>';
											foreach($res_att as $row) {
												echo '<div class="tb-profile-item-card">';
												echo '<div class="tb-profile-item-header">';
												echo '<h5 class="tb-profile-item-title">'.htmlspecialchars($row['title']).'</h5>';
												echo '</div>';
												echo '<div class="tb-profile-item-sub">Issuer: <span class="text-primary font600">'.htmlspecialchars($row['issuer']).'</span></div>';
												echo '<a target="_blank" class="btn btn-primary btn-sm" style="border-radius: 6px; padding: 4px 12px; font-size: 12px; margin-top: 4px;" href="view-attachment.php?id='.$row['id'].'"><i class="fa fa-download"></i> View Attachment</a>';
												echo '</div>';
											}
											echo '</div>';
										}

										// 6. Languages
										$stmt_lng = $conn_detail->prepare("SELECT * FROM tbl_language WHERE member_no = :empid ORDER BY id");
										$stmt_lng->bindParam(':empid', $empid);
										$stmt_lng->execute();
										$res_lng = $stmt_lng->fetchAll();
										if (count($res_lng) > 0) {
											echo '<div class="tb-section-card">';
											echo '<h3 class="tb-section-card-title"><span class="tb-icon-circle"><i class="fa fa-language"></i></span> Language Proficiency</h3>';
											echo '<div class="tb-lang-prof-grid">';
											foreach($res_lng as $row) {
												echo '<div class="tb-lang-card-box">';
												echo '<div class="tb-lang-card-header">';
												echo '<i class="fa fa-globe text-primary"></i> '.htmlspecialchars($row['language']);
												echo '</div>';
												echo '<div class="tb-lang-card-levels">';
												
												echo '<div class="tb-lang-level-item">';
												echo '<span class="tb-lang-level-label">Speaking</span>';
												echo '<strong class="tb-lang-level-val">'.htmlspecialchars($row['speak']).'</strong>';
												echo '</div>';

												echo '<div class="tb-lang-level-item">';
												echo '<span class="tb-lang-level-label">Reading</span>';
												echo '<strong class="tb-lang-level-val">'.htmlspecialchars($row['reading']).'</strong>';
												echo '</div>';

												echo '<div class="tb-lang-level-item">';
												echo '<span class="tb-lang-level-label">Writing</span>';
												echo '<strong class="tb-lang-level-val">'.htmlspecialchars($row['writing']).'</strong>';
												echo '</div>';

												echo '</div>';
												echo '</div>';
											}
											echo '</div>';
											echo '</div>';
										}

										// 7. Referees
										$stmt_ref = $conn_detail->prepare("SELECT * FROM tbl_referees WHERE member_no = :empid ORDER BY id");
										$stmt_ref->bindParam(':empid', $empid);
										$stmt_ref->execute();
										$res_ref = $stmt_ref->fetchAll();
										if (count($res_ref) > 0) {
											echo '<div class="tb-section-card">';
											echo '<h3 class="tb-section-card-title"><span class="tb-icon-circle"><i class="fa fa-users"></i></span> Professional References</h3>';
											echo '<div class="tb-referee-grid">';
											foreach($res_ref as $row) {
												echo '<div class="tb-referee-card">';
												echo '<div class="tb-referee-avatar"><i class="fa fa-user"></i></div>';
												echo '<div class="tb-referee-info">';
												echo '<h5>'.htmlspecialchars($row['ref_name']).'</h5>';
												echo '<p>'.htmlspecialchars($row['ref_title']).' — <span class="text-primary font600">'.htmlspecialchars($row['institution']).'</span></p>';
												if (!empty($row['ref_mail'])) {
													echo '<p><i class="fa fa-envelope-o text-muted"></i> <a href="mailto:'.htmlspecialchars($row['ref_mail']).'">'.htmlspecialchars($row['ref_mail']).'</a></p>';
												}
												if (!empty($row['ref_phone'])) {
													echo '<p><i class="fa fa-phone text-muted"></i> <a href="tel:'.htmlspecialchars($row['ref_phone']).'">'.htmlspecialchars($row['ref_phone']).'</a></p>';
												}
												echo '</div>';
												echo '</div>';
											}
											echo '</div>';
											echo '</div>';
										}
									} catch(PDOException $e) {
										error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
									}
									?>

								</div>
								
	
							</div>
						
						</div>
						
					</div>
				
				</div>
			
			</div>

			<?php include 'app/footer.php'; ?>
		
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


</body>

</html>