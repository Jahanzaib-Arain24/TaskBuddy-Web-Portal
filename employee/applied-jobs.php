<!doctype html>
<html lang="en">
<?php 
require '../constants/settings.php'; 
require 'constants/check-login.php';

if ($user_online == "true") {
if ($myrole == "employee") {
}else{
header("location:../");		
}
}else{
header("location:../");	
}

if (isset($_GET['page'])) {
$page = $_GET['page'];
if ($page=="" || $page=="1")
{
$page1 = 0;
$page = 1;
}else{
$page1 = ($page*10)-10;
}					
}else{
$page1 = 0;
$page = 1;	
}
?>
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>TaskBuddy - Applied Task</title>
	<meta name="description" content="Online Job Management / Job Portal" />
	<meta name="keywords" content="job, work, resume, applicants, application, employee, employer, hire, hiring, human resource management, hr, online job management, company, worker, career, recruiting, recruitment" />
	<meta name="author" content="BwireSoft">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta property="og:image" content="http://<?php echo "$actual_link"; ?>/images/banner.jpg" />
    <meta property="og:image:secure_url" content="https://<?php echo "$actual_link"; ?>/images/banner.jpg" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="300" />
    <meta property="og:image:alt" content="Bwire Jobs" />
    <meta property="og:description" content="Online Job Management / Job Portal" />

	<link rel="shortcut icon" href="../images/ico/favicon.png">


	<link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css" media="screen">	
	<link href="../css/animate.css" rel="stylesheet">
	<link href="../css/main.css" rel="stylesheet">
	<link href="../css/component.css" rel="stylesheet">
	

	<link rel="stylesheet" href="../icons/linearicons/style.css">
	<link rel="stylesheet" href="../icons/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../icons/simple-line-icons/css/simple-line-icons.css">
	<link rel="stylesheet" href="../icons/ionicons/css/ionicons.css">
	<link rel="stylesheet" href="../icons/pe-icon-7-stroke/css/pe-icon-7-stroke.css">
	<link rel="stylesheet" href="../icons/rivolicons/style.css">
	<link rel="stylesheet" href="../icons/flaticon-line-icon-set/flaticon-line-icon-set.css">
	<link rel="stylesheet" href="../icons/flaticon-streamline-outline/flaticon-streamline-outline.css">
	<link rel="stylesheet" href="../icons/flaticon-thick-icons/flaticon-thick.css">
	<link rel="stylesheet" href="../icons/flaticon-ventures/flaticon-ventures.css">


	<link href="../css/style.css" rel="stylesheet">
	<link href="../css/custom_premium.css?v=2026.2" rel="stylesheet">

	
</head>

  <style>
  
    .autofit2 {
	height:80px;
	width:100px;
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
							<a href="../"><img src="../image/logo2.png" alt="Logo" /></a>
						</div>
					</div>
					
					<div id="navbar" class="navbar-nav-wrapper navbar-arrow">
					
												<ul class="nav navbar-nav" id="responsive-menu">
							<li><a href="../">Home</a></li>
							<li><a href="../tasks">Tasks List</a></li>
							<li><a href="../task-seekers">Task Seekers</a></li>
							<li><a href="../task-listers">Task Listers</a></li>
							<li><a href="../contact">Contact Us</a></li>
						</ul>
				
					</div>

					<div class="nav-mini-wrapper">
												<ul class="nav-mini sign-in">
							<li><a href="./" class="tb-nav-login-btn"><i class="fa fa-tachometer"></i> Dashboard</a></li>
							<li><a href="../logout" class="tb-nav-cta-btn" style="background: #ef4444 !important;"><i class="fa fa-sign-out"></i> Logout</a></li>
						</ul>
					</div>
				
				</div>
				
				<div id="slicknav-mobile"></div>
				
			</nav>

			
		</header>

		<div class="main-wrapper">
		
			<div class="admin-container-wrapper">

				<div class="container">
				
					<div class="GridLex-gap-15-wrappper">
					
						<div class="GridLex-grid-noGutter-equalHeight">
						
							<div class="GridLex-col-3_sm-4_xs-12">
							
								<?php include __DIR__ . '/sidebar.php'; ?>

							</div>
							
							<div class="GridLex-col-9_sm-8_xs-12">
							
								<div class="admin-content-wrapper">

									<div class="admin-section-title">
									
										<h2 class="admin-title-heading"><i class="fa fa-bookmark"></i> Applied Tasks & Jobs</h2>
										<p class="admin-title-sub">Track the status of your task applications and lister reviews</p>
					
									</div>
									
									<div class="resume-list-wrapper">
									
									<?php require 'constants/check_reply.php'; ?>
									<div class="recent-job-wrapper">
								  <?php
                                  require '../constants/db_config.php';
								  
								  try {
                                  $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                  $page1_int = (int)$page1;
                                  $stmt = $conn->prepare("SELECT * FROM tbl_job_applications WHERE member_no = :myid ORDER BY id DESC LIMIT $page1_int,10");
                                  $stmt->bindParam(':myid', $myid);
                                  $stmt->execute();
                                  $result = $stmt->fetchAll();
                                   foreach($result as $row)
                                   {
									$dObj = parse_flexible_date($row['application_date'] ?? '');
									$post_date = $dObj->format('d');
                                    $post_month = $dObj->format('F');
                                    $post_year = $dObj->format('Y');
								    $job_id = $row['job_id'];
								    $app_status = $row['status'] ?? 'Pending';
								
								    $stmtb = $conn->prepare("SELECT * FROM tbl_jobs WHERE job_id = :jobid");
								    $stmtb->bindParam(':jobid', $job_id);
                                    $stmtb->execute();
                                    $resultb = $stmtb->fetchAll();
									foreach($resultb as $rowb)
									{
									$job_title = $rowb['title'];
									$jobcountry = $rowb['country'];
									$jobtype = $rowb['type'];
                                    $compid = $rowb['company'];
									if ($jobtype == "Freelance") {
	                                $sta = '<div class="job-label label label-success" style="background:#10b981; font-weight:600; border-radius:12px; font-size:11px;">Freelance</div>';
	                                } elseif ($jobtype == "Part-time") {
	                                $sta = '<div class="job-label label label-danger" style="background:#ef4444; font-weight:600; border-radius:12px; font-size:11px;">Part-time</div>';
	                                } else {
	                                $sta = '<div class="job-label label label-warning" style="background:#f59e0b; font-weight:600; border-radius:12px; font-size:11px;">Full-time</div>';
	                                }	
									
									$stmtc = $conn->prepare("SELECT * FROM tbl_users WHERE member_no = :compid AND role = 'employer'");
									$stmtc->bindParam(':compid', $compid);
                                    $stmtc->execute();
                                    $resultc = $stmtc->fetchAll();
									$compname = 'Company / Lister';
									$complogo = null;
									foreach ($resultc as $rowc) {
										$compname = $rowc['first_name'];
										$complogo = $rowc['avatar'];	
									}
									
									// Status badge styling
									if ($app_status == 'Selected' || $app_status == 'Hired') {
										$statusBadge = '<span class="label label-success" style="background:#10b981; font-weight:600; padding:4px 9px; border-radius:12px; font-size:11px;"><i class="fa fa-check-circle"></i> Selected / Hired</span>';
									} elseif ($app_status == 'Shortlisted') {
										$statusBadge = '<span class="label" style="background:#8b5cf6; color:white; font-weight:600; padding:4px 9px; border-radius:12px; font-size:11px;"><i class="fa fa-star"></i> Shortlisted</span>';
									} elseif ($app_status == 'Rejected') {
										$statusBadge = '<span class="label label-danger" style="background:#ef4444; font-weight:600; padding:4px 9px; border-radius:12px; font-size:11px;"><i class="fa fa-times-circle"></i> Not Selected</span>';
									} else {
										$statusBadge = '<span class="label label-default" style="background:#64748b; color:white; font-weight:600; padding:4px 9px; border-radius:12px; font-size:11px;"><i class="fa fa-clock-o"></i> Under Review</span>';
									}
									
									if (empty($complogo)) {
										$comp_logo_src = get_initials_avatar($compname, '', 90);
									} else {
										$comp_logo_src = 'data:image/jpeg;base64,' . base64_encode($complogo);
									}
									?>
									<a target="_blank" href="../explore-job.php?jobid=<?php echo "$job_id"; ?>" class="recent-job-item clearfix" style="border-radius:12px; margin-bottom:12px; border:1px solid #e2e8f0; background:#ffffff; box-shadow:0 2px 8px rgba(15,23,42,0.03);">
								<div class="GridLex-grid-middle">
									<div class="GridLex-col-5_xs-12">
										<div class="job-position">
											<div class="image">
												<center><img class="autofit3" alt="<?php echo htmlspecialchars($compname); ?>" src="<?php echo $comp_logo_src; ?>"/></center>
											</div>
											<div class="content">
												<h4 style="font-weight:700; color:#0f172a;"><?php echo htmlspecialchars($job_title); ?></h4>
												<p style="color:#64748b;"><?php echo htmlspecialchars($compname); ?></p>
											</div>
										</div>
									</div>
									<div class="GridLex-col-3_xs-6_xss-12 mt-10-xss text-center">
										<div class="job-location" style="font-size:13px; color:#64748b;">
											<i class="fa fa-map-marker text-primary"></i> <?php echo htmlspecialchars($jobcountry); ?>
										</div>
										<div style="margin-top:4px;">
											<?php echo $statusBadge; ?>
										</div>
									</div>
									<div class="GridLex-col-4_xs-6_xss-12 text-right">
                                     <div style="display:inline-block; margin-bottom:4px;"><?php echo "$sta"; ?></div>
										<span class="font12 block spacing1 font400 text-right" style="color:#94a3b8;"><?php echo "$post_month $post_date, $post_year"; ?></span>
									</div>
								</div>
							</a>
							
							<?php
									}
								  
								  }
                                  }catch(PDOException $e)
                                  {
                                      error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
                                  } ?>
	
								  </div>

								<?php
								$total_records = 0;
								require '../constants/db_config.php';
								try {
                                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tbl_job_applications WHERE member_no = :myid");
                                $stmt->bindParam(':myid', $myid);
                                $stmt->execute();
                                $resCount = $stmt->fetch(PDO::FETCH_ASSOC);
                                $total_records = $resCount['total'] ?? 0;
                                }catch(PDOException $e)
                                {
                                    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
                                }
	
								$records = ceil($total_records/10);
				                if ($records > 1) {
								?>
								<div class="pager-wrapper">
						            <ul class="pager-list">
								<?php
								$prevpage = $page - 1;
								$nextpage = $page + 1;
								
								print '<li class="paging-nav ' . ($page == "1" ? 'disabled' : '') . '"><a ' . ($page == "1" ? '' : 'href="applied-jobs?page='.$prevpage.'"') . '><i class="fa fa-chevron-left"></i></a></li>';
					            for ($b=1;$b<=$records;$b++)
                                 {
		                        ?><li class="paging-nav <?php if ($b == $page) { echo 'active'; } ?>"><a <?php if ($b == $page) { print ' style="background-color:#2563eb; color:white;" '; } ?> href="applied-jobs?page=<?php echo "$b"; ?>"><?php echo $b." "; ?></a></li><?php
                                 }	
								 print '<li class="paging-nav ' . ($page == $records ? 'disabled' : '') . '"><a ' . ($page == $records ? '' : 'href="applied-jobs?page='.$nextpage.'"') . '><i class="fa fa-chevron-right"></i></a></li>';
								?>
						            </ul>	
					            </div>
								<?php } ?>

										
		
										
									</div>
									
									
								</div>

							</div>
							
						</div>

					</div>

				</div>
			
			</div>

			<?php include __DIR__ . '/../app/footer.php'; ?>
			
		</div>


	</div>
 
 

<div id="back-to-top">
   <a href="#"><i class="ion-ios-arrow-up"></i></a>
</div>

<script type="text/javascript" src="../js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="../js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/bootstrap-modalmanager.js"></script>
<script type="text/javascript" src="../js/bootstrap-modal.js"></script>
<script type="text/javascript" src="../js/smoothscroll.js"></script>
<script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="../js/jquery.waypoints.min.js"></script>
<script type="text/javascript" src="../js/wow.min.js"></script>
<script type="text/javascript" src="../js/jquery.slicknav.min.js"></script>
<script type="text/javascript" src="../js/jquery.placeholder.min.js"></script>
<script type="text/javascript" src="../js/bootstrap-tokenfield.js"></script>
<script type="text/javascript" src="../js/typeahead.bundle.min.js"></script>
<script type="text/javascript" src="../js/bootstrap3-wysihtml5.min.js"></script>
<script type="text/javascript" src="../js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="../js/jquery-filestyle.min.js"></script>
<script type="text/javascript" src="../js/bootstrap-select.js"></script>
<script type="text/javascript" src="../js/ion.rangeSlider.min.js"></script>
<script type="text/javascript" src="../js/handlebars.min.js"></script>
<script type="text/javascript" src="../js/jquery.countimator.js"></script>
<script type="text/javascript" src="../js/jquery.countimator.wheel.js"></script>
<script type="text/javascript" src="../js/slick.min.js"></script>
<script type="text/javascript" src="../js/easy-ticker.js"></script>
<script type="text/javascript" src="../js/jquery.introLoader.min.js"></script>
<script type="text/javascript" src="../js/jquery.responsivegrid.js"></script>
<script type="text/javascript" src="../js/customs.js"></script>

<script type="text/javascript" src="../js/fileinput.min.js"></script>
<script type="text/javascript" src="../js/customs-fileinput.js"></script>


</body>


</html>