<!doctype html>
<html lang="en">
<?php 
require 'constants/settings.php'; 
require 'constants/check-login.php';
$fromsearch = false;

if (isset($_GET['search']) && $_GET['search'] == "✓") {

}else{

}

$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$page1 = ($page - 1) * $limit;

$cate = isset($_GET['category']) ? trim($_GET['category']) : '';
$country = isset($_GET['country']) ? trim($_GET['country']) : '';

if (!empty($cate) && !empty($country)) {
    $query1 = "SELECT * FROM tbl_jobs WHERE category = :cate AND country = :country ORDER BY enc_id DESC LIMIT $page1, $limit";
    $query2 = "SELECT * FROM tbl_jobs WHERE category = :cate AND country = :country ORDER BY enc_id DESC";
    $fromsearch = true;
    $slc_country = "$country";
    $slc_category = "$cate";
    $title = "$slc_category in $slc_country";
} elseif (!empty($cate)) {
    $query1 = "SELECT * FROM tbl_jobs WHERE category = :cate ORDER BY enc_id DESC LIMIT $page1, $limit";
    $query2 = "SELECT * FROM tbl_jobs WHERE category = :cate ORDER BY enc_id DESC";
    $fromsearch = true;
    $slc_country = "";
    $slc_category = "$cate";
    $title = "$slc_category Tasks";
} elseif (!empty($country)) {
    $query1 = "SELECT * FROM tbl_jobs WHERE country = :country ORDER BY enc_id DESC LIMIT $page1, $limit";
    $query2 = "SELECT * FROM tbl_jobs WHERE country = :country ORDER BY enc_id DESC";
    $fromsearch = true;
    $slc_country = "$country";
    $slc_category = "";
    $title = "Tasks in $slc_country";
} else {
    $query1 = "SELECT * FROM tbl_jobs ORDER BY enc_id DESC LIMIT $page1, $limit";
    $query2 = "SELECT * FROM tbl_jobs ORDER BY enc_id DESC";	
    $slc_country = "NULL";
    $slc_category = "NULL";	
    $title = "All Tasks";
}
?>

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Task Buddy - <?php echo "$title"; ?></title>
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
							<li class="active"><a href="tasks">Tasks List</a></li>
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
		
			<div class="second-search-result-wrapper">
			
				<div class="container">
				
					<div class="tb-hero-search-wrapper" style="box-shadow: 0 10px 30px rgba(0,0,0,0.25) !important;">
						<form action="tasks" method="GET" class="tb-hero-search-form" autocomplete="off">
							<div class="tb-search-col tb-search-col-cat">
								<i class="fa fa-th-large tb-search-icon"></i>
								<select class="tb-search-select" name="category" required>
									<option value="">Choose Category / Service</option>
									<?php
									require 'constants/db_config.php';
									try {
										$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
										$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

										$stmt = $conn->prepare("SELECT * FROM tbl_categories ORDER BY category");
										$stmt->execute();
										$result = $stmt->fetchAll();

										foreach($result as $row) {
											$cat = $row['category'];
											$selected = ($slc_category == $cat) ? ' selected' : '';
											echo '<option value="' . htmlspecialchars($cat) . '"' . $selected . '>' . htmlspecialchars($cat) . '</option>';
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
									<option value="Pakistan" <?php if ($slc_country == "Pakistan" || empty($slc_country)) { echo 'selected'; } ?>>🇵🇰 Pakistan (Local)</option>
									<option value="" <?php if ($slc_country == "" && isset($_GET['country'])) { echo 'selected'; } ?>>All Countries</option>
									<?php
									try {
										$stmt = $conn->prepare("SELECT * FROM tbl_countries WHERE country_name != 'Pakistan' ORDER BY country_name");
										$stmt->execute();
										$result = $stmt->fetchAll();

										foreach($result as $row) {
											$cnt = $row['country_name'];
											$selected = ($slc_country == $cnt) ? ' selected' : '';
											echo '<option value="' . htmlspecialchars($cnt) . '"' . $selected . '>' . htmlspecialchars($cnt) . '</option>';
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

				</div>
			
			</div>
			
			<div class="section sm">
			
				<div class="container">
				
					<div class="sorting-wrappper">
			
						<div class="sorting-header">
							<h3 class="sorting-title"><?php echo "$title"; ?></h3>
						</div>
						
		
					</div>
					
					<div class="result-wrapper">
					
						<div class="row">
						
							<div class="col-sm-12 col-md-12 mt-25">
							
								<div class="result-list-wrapper">
								<?php
								require 'constants/db_config.php';
								
								try {
                                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $conn->prepare($query1);
								if ($fromsearch == true) {
									if (!empty($slc_category)) { $stmt->bindParam(':cate', $slc_category); }
									if (!empty($slc_country)) { $stmt->bindParam(':country', $slc_country); }
								}
                                $stmt->execute();
                                $result = $stmt->fetchAll();
                                foreach($result as $row)
                                {
								$dObj = parse_flexible_date($row['closing_date'] ?? '');
								$post_date = $dObj->format('d');
                                $post_month = $dObj->format('F');
                                $post_year = $dObj->format('Y');
								$type = $row['type'];
								$compid = $row['company'];
								
								$stmtb = $conn->prepare("SELECT * FROM tbl_users WHERE member_no = :compid and role = 'employer'");
								$stmtb->bindParam(':compid', $compid);
                                $stmtb->execute();
                                $resultb = $stmtb->fetchAll();
                                $complogo = null;
                                $thecompname = 'Task Lister';
                                foreach($resultb as $rowb) {
								$complogo = $rowb['avatar'];
								$thecompname = $rowb['first_name'];	
								}
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
										$logoSrc = get_initials_avatar($thecompname, '', 100);
										echo '<center><img class="autofit3" alt="'.htmlspecialchars($thecompname).'" title="'.htmlspecialchars($thecompname).'" src="'.$logoSrc.'"/></center>';
										}else{
										echo '<center><img class="autofit3" alt="'.htmlspecialchars($thecompname).'" title="'.htmlspecialchars($thecompname).'" width="180" height="100" src="data:image/jpeg;base64,'.base64_encode($complogo).'"/></center>';	
										}
										?>
										</div>
										
										<div class="content">
											<div class="job-item-list-info">
											
												<div class="row">
												
													<div class="col-sm-7 col-md-8">
													
														<h4 class="heading"><?php echo $row['title']; ?></h4>
														<div class="meta-div clearfix mb-25">
															<span>at <a href="lister/<?php echo "$compid"; ?>"><?php echo "$thecompname"; ?></a></span>
															<?php echo "$sta"; ?>
														</div>
														
														<p class="texing character_limit"><?php echo format_task_text($row['description']); ?></p>
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
                                    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
                                } ?>
                                </div>
								
					
								<div class="pager-wrapper">
								
						        <ul class="pager-list">
								<?php
								$total_records = 0;
								require 'constants/db_config.php';
								
								try {
                                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $conn->prepare($query2);
								if ($fromsearch == true) {
									if (!empty($slc_category)) { $stmt->bindParam(':cate', $slc_category); }
									if (!empty($slc_country)) { $stmt->bindParam(':country', $slc_country); }
								}
                                $stmt->execute();
                                $result = $stmt->fetchAll();
 
                                foreach($result as $row)
                                {
		                        $total_records++;
                                }

					  
	                            }catch(PDOException $e)
                                {
                                    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
                                }
	
                                $records = ceil($total_records / 8);
				                if ($records > 1 ) {
								$prevpage = $page - 1;
								$nextpage = $page + 1;
								
								print '<li class="paging-nav" '; if ($page == "1") { print 'class="disabled"'; } print '><a '; if ($page == "1") { print 'href="javascript:void(0);"'; } else { print 'href="tasks?page='.$prevpage.''; ?> <?php if ($fromsearch == true) { print '&category='.$cate.'&country='.$country.'&search=✓'; }'';} print '"><i class="fa fa-chevron-left"></i></a></li>';
					            for ($b=1;$b<=$records;$b++)
                                 {
                                 
		                        ?><li class="paging-nav"><a <?php if ($b == $page) { print ' style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important; color: white !important; border-color: #4f46e5 !important; font-weight: 700;" '; } ?> href="tasks?page=<?php echo "$b"; ?><?php if ($fromsearch == true) { print '&category='.$cate.'&country='.$country.'&search=✓'; }?>"><?php echo $b." "; ?></a></li><?php
                                 }	
								 print '<li class="paging-nav"'; if ($page == $records) { print 'class="disabled"'; } print '><a '; if ($page == $records) { print 'href="javascript:void(0);"'; } else { print 'href="tasks?page='.$nextpage.''; ?> <?php if ($fromsearch == true) { print '&category='.$cate.'&country='.$country.'&search=✓'; }'';} print '"><i class="fa fa-chevron-right"></i></a></li>';
					             }

								
								?>

						            </ul>	
					
					                </div>
								
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