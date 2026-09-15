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

	<title>Task Buddy</title>
	<meta name="description" content="Online Job Management / Job Portal" />
	<meta name="keywords" content="Task, work, resume, applicants, application, employee, employer, hire, hiring, human resource management, hr, online job management, company, worker, career, recruiting, recruitment" />
	<meta name="author" content="BwireSoft">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta property="og:image" content="http://<?php echo "$actual_link"; ?>/images/banner.jpg" />
    <meta property="og:image:secure_url" content="https://<?php echo "$actual_link"; ?>/images/banner.jpg" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="300" />


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
	height:70px;
	width:400px;
    object-fit:cover; 
  }
  
      .autofit3 {
	height:80px;
	width:100px;
    object-fit:cover; 
  }
  

  </style>
<body class="home">


	<div id="introLoader" class="introLoading"></div>

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
							<li class="active"><a href="./">Home</a></li>
							<li><a href="tasks">Tasks List</a></li>
							<li><a href="task-seekers">Task Seekers</a></li>
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
		
			<div class="hero">
				<div class="container text-center">
					<div class="hero-content">
						<h1 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0s">Find The Perfect <span class="highlight">Task & Opportunity</span></h1>
						<p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">Discover verified tasks, connect with trusted employers, and build your career on Task Buddy.</p>
						
						<div class="tb-hero-search-wrapper wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
							<form action="tasks" method="GET" class="tb-hero-search-form">
								<div class="tb-search-col tb-search-col-cat">
									<i class="fa fa-th-large tb-search-icon"></i>
									<select class="tb-search-select" name="category">
										<option value="">All Local Categories</option>
										<?php
										require_once 'constants/db_config.php';
										try {
											$conn_hero = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
											$conn_hero->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
											$stmt_c = $conn_hero->prepare("SELECT * FROM tbl_categories ORDER BY category");
											$stmt_c->execute();
											$result_cats = $stmt_c->fetchAll();
											foreach($result_cats as $row_c) {
												echo '<option value="'.$row_c['category'].'">'.$row_c['category'].'</option>';
											}
										} catch(PDOException $e) {
											error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
										}
										?>
									</select>
								</div>
								
								<div class="tb-search-divider"></div>
								
								<div class="tb-search-col tb-search-col-country">
									<i class="fa fa-map-marker tb-search-icon"></i>
									<select class="tb-search-select" name="country">
										<option value="Pakistan" selected>🇵🇰 Pakistan (Local)</option>
										<option value="">All Countries</option>
										<?php
										try {
											$stmt_cn = $conn_hero->prepare("SELECT * FROM tbl_countries WHERE country_name != 'Pakistan' ORDER BY country_name");
											$stmt_cn->execute();
											$result_cnts = $stmt_cn->fetchAll();
											foreach($result_cnts as $row_cn) {
												echo '<option value="'.$row_cn['country_name'].'">'.$row_cn['country_name'].'</option>';
											}
										} catch(PDOException $e) {
											error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
										}
										?>
									</select>
								</div>
								
								<div class="tb-search-col tb-search-col-btn">
									<input type="hidden" name="search" value="✓">
									<button type="submit" class="tb-search-btn"><i class="fa fa-search"></i> Search Tasks</button>
								</div>
							</form>
						</div>

						<div class="hero-tags wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.4s">
							<span>Popular:</span>
							<a href="tasks?category=Cleaning+Services&country=Pakistan&search=%E2%9C%93" class="hero-tag-pill">Cleaning</a>
							<a href="tasks?category=Maintenance+and+Repair&country=Pakistan&search=%E2%9C%93" class="hero-tag-pill">Plumbing & Repairs</a>
							<a href="tasks?category=Errands+and+Delivery&country=Pakistan&search=%E2%9C%93" class="hero-tag-pill">Errands</a>
							<a href="tasks?category=Home+Improvement&country=Pakistan&search=%E2%9C%93" class="hero-tag-pill">Home Improvement</a>
							<a href="tasks" class="hero-tag-pill">All Tasks</a>
							<a href="task-seekers" class="hero-tag-pill"><i class="fa fa-users"></i> Find Taskers</a>
						</div>
					</div>
				</div>
			</div>


			
	
			
			<div class="bg-light pt-80 pb-80">
			
				<div class="container">
				
					<div class="row" id="latest-tasks">
						
						<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
						
							<div class="section-title">
							
								<h2>Latest Tasks</h2>
								
							</div>
						
						</div>
					
					</div>
					
					<div class="row">
						
						<div class="col-md-12">
						
							<div class="recent-job-wrapper alt-stripe mr-0">
							<?php
							require 'constants/db_config.php';
							try {
								$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
								$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

								// Pagination: 8 tasks per page
								$limit = 8;
								$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
								if ($page < 1) $page = 1;
								$offset = ($page - 1) * $limit;

								$countStmt = $conn->query("SELECT COUNT(*) FROM tbl_jobs");
								$total_records = (int)$countStmt->fetchColumn();
								$total_pages = ceil($total_records / $limit);

								$stmt = $conn->prepare("SELECT * FROM tbl_jobs ORDER BY enc_id DESC LIMIT :limit OFFSET :offset");
								$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
								$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
								$stmt->execute();
								$result = $stmt->fetchAll();

								foreach($result as $row) {
									$jobcity = $row['city'];
									$jobcountry = $row['country'];
									$type = $row['type'];
									$title = $row['title'];
									$closingdate = $row['closing_date'] ?? '';
									$company_id = $row['company'];
									$dObj = parse_flexible_date($closingdate);
									$post_date = $dObj->format('d');
									$post_month = $dObj->format('F');
									$post_year = $dObj->format('Y');
												   
									$stmtb = $conn->prepare("SELECT * FROM tbl_users WHERE member_no = :comp_id and role = 'employer'");
									$stmtb->bindParam(':comp_id', $company_id);
									$stmtb->execute();
									$resultb = $stmtb->fetchAll();
									$complogo = null;
									$thecompname = 'Verified Lister';
									foreach($resultb as $rowb) {
										$complogo = $rowb['avatar'];
										$thecompname = $rowb['first_name'];	
									}
									
									$sta = '';
									if ($type == "Freelance") {
										$sta = '<div class="job-label label label-success">Freelance</div>';
									} else if ($type == "Part-time") {
										$sta = '<div class="job-label label label-danger">Part-time</div>';
									} else if ($type == "Full-time") {
										$sta = '<div class="job-label label label-warning">Full-time</div>';
									} else {
										$sta = '<div class="job-label label label-info">'.htmlspecialchars($type).'</div>';
									}
							?>
							<a class="recent-job-item clearfix" target="_blank" href="task/<?php echo $row['job_id']; ?>">
								<div class="GridLex-grid-middle">
									<div class="GridLex-col-5_xs-12">
										<div class="job-position">
											<div class="image">
											<?php 
											if (empty($complogo)) {
												$logoSrc = get_initials_avatar($thecompname);
												print '<img alt="image" src="'.$logoSrc.'"/>';
											} else {
												echo '<img alt="image" title="'.$thecompname.'" src="data:image/jpeg;base64,'.base64_encode($complogo).'"/>';	
											}
											?>
											</div>
											<div class="content">
												<h4><?php echo "$title"; ?></h4>
												<p><?php echo "$thecompname"; ?></p>
											</div>
										</div>
									</div>
									<div class="GridLex-col-4_xs-7_xss-12 mt-10-xss text-center">
										<div class="job-location">
											<i class="fa fa-map-marker text-primary"></i> <?php echo "$jobcountry" ?> - <?php echo "$jobcity" ?>
										</div>
									</div>
									<div class="GridLex-col-3_xs-5_xss-12 text-right text-left-xs mt-10-xss" style="display: flex; flex-direction: column; align-items: flex-end; justify-content: center;">
										<div style="margin-bottom: 6px;">
											<?php echo "$sta"; ?>
										</div>
										<span class="font12 block spacing1 font400" style="white-space: nowrap !important; display: block; color: #64748b; font-weight: 600; text-align: right;">Due - <?php echo "$post_month $post_date, $post_year"; ?></span>
									</div>
								</div>
							</a>
								
							<?php
								}
							} catch(PDOException $e) { 
								error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
							}
							?>
							</div>
							
							<?php if (isset($total_pages) && $total_pages > 1): ?>
							<div class="pager-wrapper text-center" style="margin: 35px 0 15px 0;">
								<ul class="pager-list">
									<?php
									$prevpage = $page - 1;
									$nextpage = $page + 1;
									
									if ($page > 1) {
										echo '<li class="paging-nav"><a href="./?page='.$prevpage.'#latest-tasks" title="Previous Page"><i class="fa fa-chevron-left"></i></a></li>';
									} else {
										echo '<li class="paging-nav disabled"><a href="javascript:void(0);"><i class="fa fa-chevron-left"></i></a></li>';
									}

									for ($b = 1; $b <= $total_pages; $b++) {
										$activeStyle = ($b == $page) ? ' style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important; color: #ffffff !important; border-color: #4f46e5 !important; font-weight: 700 !important;"' : '';
										echo '<li class="paging-nav"><a'.$activeStyle.' href="./?page='.$b.'#latest-tasks">'.$b.'</a></li>';
									}

									if ($page < $total_pages) {
										echo '<li class="paging-nav"><a href="./?page='.$nextpage.'#latest-tasks" title="Next Page"><i class="fa fa-chevron-right"></i></a></li>';
									} else {
										echo '<li class="paging-nav disabled"><a href="javascript:void(0);"><i class="fa fa-chevron-right"></i></a></li>';
									}
									?>
								</ul>
							</div>
							<?php endif; ?>
							
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
<script type="text/javascript" src="js/jquery-filestyle.min.js"></script>
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