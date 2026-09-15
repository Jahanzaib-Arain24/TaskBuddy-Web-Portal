<!doctype html>
<html lang="en">
<?php 
require 'constants/settings.php'; 
require 'constants/check-login.php';
require 'constants/db_config.php';

if (isset($_GET['ref'])) {

$company_id = $_GET['ref'];



    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE member_no = :memberno AND role = 'employer'");
	$stmt->bindParam(':memberno', $company_id);
    $stmt->execute();
    $result = $stmt->fetchAll();
	$rec = count($result);
	
	if ($rec == "0") {
	header("location:./");	
	}else{

    foreach($result as $row)
    {
		
    $compname = $row['first_name'];
	$compesta = $row['byear'];
    $compmail  = $row['email'];
	$comptype = $row['title'];
    $compphone = $row['phone'];
	$compcity = $row['city'];
	$compstreet = $row['street'];
	$compzip = $row['zip'];
    $compcountry = $row['country'];
    $compbout = $row['about'];
	$complogo = $row['avatar'];
	$compserv = $row['services'];
	$compexp = $row['expertise'];
	$compweb = $row['website'];
	$comppeopl = $row['people'];
	
	}
	
	}

					  
	}catch(PDOException $e)
    {
 
    }
	
}else{
header("location:./");
}

if (isset($_GET['page'])) {
$page = $_GET['page'];
if ($page=="" || $page=="1")
{
$page1 = 0;
$page = 1;
}else{
$page1 = ($page*5)-5;
}					
}else{
$page1 = 0;
$page = 1;	
}
?>
<head>
	<base href="/">

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Task Buddy - <?php echo "$compname"; ?></title>
	<meta name="description" content="Online Task Management / Task Portal" />
	<meta name="keywords" content="job, work, resume, applicants, application, employee, employer, hire, hiring, human resource management, hr, online Task management, company, worker, career, recruiting, recruitment" />
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
							<li class="active"><a href="task-listers">Task Listers</a></li>
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

			
		</header>

		<div class="main-wrapper">
			
			<div class="section sm tb-detail-page-container">
			
				<div class="container">
				
					<div class="row">
						
							<div class="col-md-10 col-md-offset-1">
							
								<div class="company-detail-wrapper">
								
									<div class="tb-detail-hero-card text-center">
										
										<div style="width: 100px; height: 100px; margin: 0 auto 16px; border-radius: 16px; padding: 4px; background: #ffffff; box-shadow: 0 8px 24px rgba(79, 70, 229, 0.12); border: 2px solid #e0e7ff; overflow: hidden; display: flex; align-items: center; justify-content: center;">
										<?php 
										if (empty($complogo)) {
											$logoSrc = get_initials_avatar($compname);
											print '<img style="width:100%; height:100%; object-fit:cover; border-radius:12px;" alt="image" title="'.htmlspecialchars($compname).'" src="'.$logoSrc.'"/>';
										} else {
											echo '<img style="width:100%; height:100%; object-fit:cover; border-radius:12px;" alt="image" title="'.htmlspecialchars($compname).'" src="data:image/jpeg;base64,'.base64_encode($complogo).'"/>';	
										}
										?>
										</div>
										
										<h1 class="tb-detail-hero-title"><?php echo "$compname"; ?></h1>
									
										<div class="tb-detail-hero-subtitle">
											<span><i class="fa fa-map-marker text-primary"></i> <?php echo "$compcity, $compcountry"; ?></span>
											<span>•</span>
											<span><i class="fa fa-phone text-primary"></i> <?php echo "$compphone"; ?></span>
											<span style="background: #e0f2fe; color: #0284c7; padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 700; margin-left: 4px;">
												<i class="fa fa-check-circle"></i> Verified Task Lister
											</span>
										</div>
										
										<div class="tb-meta-grid">
											<div class="tb-meta-box">
												<div class="tb-meta-icon"><i class="fa fa-calendar-o"></i></div>
												<div class="tb-meta-body">
													<div class="tb-meta-label">Established In</div>
													<div class="tb-meta-val"><?php echo !empty($compesta) ? htmlspecialchars($compesta) : 'N/A'; ?></div>
												</div>
											</div>
											<div class="tb-meta-box">
												<div class="tb-meta-icon"><i class="fa fa-briefcase"></i></div>
												<div class="tb-meta-body">
													<div class="tb-meta-label">Industry Type</div>
													<div class="tb-meta-val"><?php echo !empty($comptype) ? htmlspecialchars($comptype) : 'General Services'; ?></div>
												</div>
											</div>
											<div class="tb-meta-box">
												<div class="tb-meta-icon"><i class="fa fa-users"></i></div>
												<div class="tb-meta-body">
													<div class="tb-meta-label">Team Size</div>
													<div class="tb-meta-val"><?php echo !empty($comppeopl) ? htmlspecialchars($comppeopl) : '1-10 Members'; ?></div>
												</div>
											</div>
											<div class="tb-meta-box">
												<div class="tb-meta-icon"><i class="fa fa-globe"></i></div>
												<div class="tb-meta-body">
													<div class="tb-meta-label">Official Website</div>
													<div class="tb-meta-val" style="overflow: hidden; text-overflow: ellipsis;">
														<?php if (!empty($compweb)): ?>
														<a target="_blank" rel="noopener noreferrer" href="<?php echo htmlspecialchars(format_external_url($compweb)); ?>"><?php echo htmlspecialchars($compweb); ?></a>
														<?php else: ?>
														<span>N/A</span>
														<?php endif; ?>
													</div>
												</div>
											</div>
										</div>
										
									</div>
						
									<div class="tb-section-card">
										<h3 class="tb-section-card-title">
											<span class="tb-icon-circle"><i class="fa fa-info-circle"></i></span>
											Company Background & Profile
										</h3>
										<div class="tb-section-card-content">
											<?php echo format_task_text($compbout); ?>
										</div>
									</div>

									<div class="tb-section-card">
										<h3 class="tb-section-card-title">
											<span class="tb-icon-circle"><i class="fa fa-cogs"></i></span>
											Services & Offerings
										</h3>
										<div class="tb-section-card-content">
											<?php echo format_task_text($compserv); ?>
										</div>
									</div>

									<div class="tb-section-card">
										<h3 class="tb-section-card-title">
											<span class="tb-icon-circle"><i class="fa fa-star-o"></i></span>
											Core Expertise & Highlights
										</h3>
										<div class="tb-section-card-content">
											<?php echo format_task_text($compexp); ?>
										</div>
									</div>

									
									<div class="section-title mb-40">
						
										<h4 class="text-left">Task offered at <?php echo "$compname"; ?></h4>
										
									</div>

									<div class="result-list-wrapper">
									<?php
									require 'constants/db_config.php';
									
									try {
                                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
                                    $stmt = $conn->prepare("SELECT * FROM tbl_jobs WHERE company = :compid ORDER BY enc_id DESC LIMIT 5");
                                    $stmt->bindParam(':compid', $company_id);
                                    $stmt->execute();
                                    $result = $stmt->fetchAll();

                                    foreach($result as $row)
                                    {
									$dObj = parse_flexible_date($row['closing_date'] ?? '');
									$post_date = $dObj->format('d');
                                    $post_month = $dObj->format('F');
                                    $post_year = $dObj->format('Y');
									$type = $row['type'];
									if ($type == "Freelance") {
									$sta = '<span class="job-label label label-success">Freelance</span>';
											  
									}
									if ($type == "Part-time") {
									$sta = '<span class="job-label label label-danger">Part-time</span>';
											  
									}
									if ($type == "Full-time") {
									$sta = '<span class="job-label label label-warning">Full-time</span>';
											  
									}
									
									?>
										<div class="job-item-list">
										
											<div class="image">
										<?php 
										if (empty($complogo)) {
										$taskLogoSrc = get_initials_avatar($compname, '', 100);
										print '<center><img class="autofit3" alt="'.htmlspecialchars($compname).'" title="'.htmlspecialchars($compname).'" src="'.$taskLogoSrc.'"/></center>';
										}else{
										echo '<center><img class="autofit3" alt="image" title="'.$compname.'" width="180" height="100" src="data:image/jpeg;base64,'.base64_encode($complogo).'"/></center>';	
										}
										 ?>
											</div>
											
											<div class="content">
												<div class="job-item-list-info">
												
													<div class="row">
													
														<div class="col-sm-7 col-md-8">
														
															<h4 class="heading"><?php echo $row['title']; ?></h4>
															<div class="meta-div clearfix mb-25">
															<span>at <a href="lister/<?php echo "$company_id"; ?>"><?php echo "$compname"; ?></a></span>
															<?php echo "$sta"; ?>
															</div>
															
															<p class="texing"><?php echo format_task_text($row['description']); ?></p>
														</div>
														
														<div class="col-sm-5 col-md-4">
														<ul class="meta-list">
															<li>
																<span>Country:</span>
																<?php echo $row['country']; ?>
															</li>
															<li>
																<span>City:</span>
																<?php echo $row['city']; ?>
															</li>
															<li>
																<span>Experience:</span>
																<?php echo $row['experience']; ?>
															</li>
															<li>
																<span>Deadline: </span>
																<?php echo "$post_month"; ?> <?php echo "$post_date"; ?>, <?php echo "$post_year"; ?>
															</li>
														</ul>
														</div>
														
													</div>
												
												</div>
											
												<div class="job-item-list-bottom">
												
													<div class="row">
													
														<div class="col-sm-7 col-md-8">
														<div class="sub-category">
															<a><?php echo $row['category']; ?></a>

														</div>
														</div>
														
													<div class="col-sm-5 col-md-4">
														<a target="_blank" href="task/<?php echo $row['job_id']; ?>" class="btn btn-primary">View This Task</a>
													</div>
														
													</div>
												
												</div>
											
											
											</div>
										
										</div>
										<?php
		
 
	                                }

					  
	                                }catch(PDOException $e)
                                    {

                                    }
	                                     ?>

									</div>
								<?php
								$total_records = 0;
								require_once 'constants/db_config.php';
								try {
                                    $conn = get_db_connection();
                                    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tbl_jobs WHERE company = :compid");
                                    $stmt->bindParam(':compid', $company_id);
                                    $stmt->execute();
                                    $countRow = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $total_records = $countRow ? (int)$countRow['total'] : 0;
	                            } catch(PDOException $e) {
                                    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
                                }
	
								$records = ceil($total_records / 5);
				                if ($records > 1) {
								    $prevpage = $page - 1;
								    $nextpage = $page + 1;
                                    echo '<div class="pager-wrapper"><ul class="pager-list">';
								    echo '<li class="paging-nav ' . ($page <= 1 ? 'disabled' : '') . '"><a ' . ($page > 1 ? 'href="lister/'.$company_id.'&page='.$prevpage.'"' : '') . '><i class="fa fa-chevron-left"></i></a></li>';
					                for ($b = 1; $b <= $records; $b++) {
                                        $activeStyle = ($b == $page) ? ' style="background:linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); color:white; border-color:#4f46e5;" ' : '';
                                        echo '<li class="paging-nav"><a ' . $activeStyle . ' href="lister/' . $company_id . '&page=' . $b . '">' . $b . '</a></li>';
                                    }	
								    echo '<li class="paging-nav ' . ($page >= $records ? 'disabled' : '') . '"><a ' . ($page < $records ? 'href="lister/'.$company_id.'&page='.$nextpage.'"' : '') . '><i class="fa fa-chevron-right"></i></a></li>';
                                    echo '</ul></div>';
					            }
								?>
									
							</div>
						
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


</body>


</html>