<!doctype html>
<html lang="en">
<?php 
require 'constants/settings.php'; 
require 'constants/check-login.php';

if (isset($_GET['page'])) {
$page = $_GET['page'];
if ($page=="" || $page=="1")
{
$page1 = 0;
$page = 1;
}else{
$page1 = ($page*8)-8;
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
	<title>Task Buddy - Task Seeker</title>
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
	
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" media="screen">	
	<link href="css/animate.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<link href="css/component.css" rel="stylesheet">
	
	<link rel="stylesheet" href="icons/linearicons/style.css">
	<link rel="stylesheet" href="icons/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="icons/simple-line-icons/simple-line-icons.html">
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
	height:63px;
	width:63px;
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

			<div class="section sm">
			
				<div class="container">
				
					<div class="sorting-wrappper">
			
						<div class="sorting-header">
							<h3 class="sorting-title">Task Seekers</h3>
						</div>
						
					</div>
					
					<div class="employee-grid-wrapper">
					
						<div class="GridLex-gap-20-wrappper">
						
							<div class="GridLex-grid-noGutter-equalHeight">
							<?php
							require 'constants/db_config.php';
							
							try {
                            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $page1_int = (int)$page1;
                            $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE role = 'employee' ORDER BY first_name LIMIT $page1_int,8");
                            $stmt->execute();
                            $result = $stmt->fetchAll();
                            foreach($result as $row)
                            {
								$empavatar = $row['avatar'];
								?>
								<div class="GridLex-col-3_sm-4_xs-6_xss-12">
								
									<div class="employee-grid-item tb-seeker-card">
										
										<a target="_blank" href="seeker/<?php echo $row['member_no']; ?>" class="clearfix">
											
											<div class="image clearfix">
												<div class="tb-avatar-wrapper">
												<?php 
												if (empty($empavatar)) {
													$avSvg = get_initials_avatar($row['first_name'], $row['last_name']);
													print '<img class="img-circle tb-seeker-avatar" src="' . $avSvg . '" alt="' . htmlspecialchars($row['first_name']) . '" />';
												}else{
													echo '<img class="img-circle tb-seeker-avatar" alt="image" src="data:image/jpeg;base64,'.base64_encode($empavatar).'"/>';	
												}
												?>
													<span class="tb-verified-tick" title="Verified Task Seeker"><i class="fa fa-check"></i></span>
												</div>
											</div>
											
											<div class="content">
											
												<h4 class="tb-seeker-name"><?php echo $row['first_name'] ?> <?php echo $row['last_name'] ?></h4>
												<p class="location"><i class="fa fa-map-marker text-primary"></i> <?php echo !empty($row['city']) ? $row['city'].', ' : ''; ?><?php echo !empty($row['country']) ? $row['country'] : 'Pakistan'; ?></p>
												
												<div class="tb-seeker-tags">
													<span class="tb-tag-skill"><i class="fa fa-wrench"></i> <?php echo !empty($row['title']) ? $row['title'] : 'Handyman & Repairs'; ?></span>
													<span class="tb-tag-edu"><i class="fa fa-graduation-cap"></i> <?php echo !empty($row['education']) ? $row['education'] : 'Vocational'; ?></span>
												</div>
												
												<div class="tb-seeker-footer">
													<span class="tb-btn-profile">View Profile <i class="fa fa-arrow-right"></i></span>
												</div>
												
											</div>
										
										</a>
										
									</div>
								
								</div>
								<?php
 
                         	}

					  
	                        }catch(PDOException $e)
                            {
                                error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
                            }
							
							?>
	
							
							</div>
						
						</div>

					</div>
					
					<?php
					$total_records = 0;
					try {
						$connTotal = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
						$connTotal->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$total_records = (int)$connTotal->query("SELECT COUNT(*) FROM tbl_users WHERE role = 'employee'")->fetchColumn();
					} catch(PDOException $e) {
						error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
					}

					$records = ceil($total_records / 8);
					if ($records > 1) {
					?>
					<div class="pager-wrapper">
						<ul class="pager-list">
							<?php
							$prevpage = $page - 1;
							$nextpage = $page + 1;

							print '<li class="paging-nav" ';
							if ($page == "1") {
								print 'class="disabled"';
							}
							print '><a ';
							if ($page == "1") {
								print 'href="javascript:void(0);"';
							} else {
								print 'href="task-seekers?page=' . $prevpage . '"';
							}
							print '><i class="fa fa-chevron-left"></i></a></li>';

							for ($b = 1; $b <= $records; $b++) {
								$activeStyle = ($b == $page) ? ' style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important; color: white !important; border-color: #4f46e5 !important; font-weight: 700;" ' : '';
								echo '<li class="paging-nav"><a ' . $activeStyle . ' href="task-seekers?page=' . $b . '">' . $b . '</a></li>';
							}

							print '<li class="paging-nav"';
							if ($page == $records) {
								print 'class="disabled"';
							}
							print '><a ';
							if ($page == $records) {
								print 'href="javascript:void(0);"';
							} else {
								print 'href="task-seekers?page=' . $nextpage . '"';
							}
							print '><i class="fa fa-chevron-right"></i></a></li>';
							?>
						</ul>
					</div>
					<?php } ?>

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