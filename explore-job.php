<!doctype html>
<html lang="en">
<?php 
require 'constants/settings.php'; 
require 'constants/check-login.php';
require 'constants/db_config.php'; 

if (isset($_GET['jobid'])) {

$jobid = $_GET['jobid'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
    $stmt = $conn->prepare("SELECT * FROM tbl_jobs WHERE job_id = :jobid");
	$stmt->bindParam(':jobid', $jobid);
    $stmt->execute();
    $result = $stmt->fetchAll();
	$rec = count($result);
	if ($rec == "0") {
	header("location:./");	
	}else{

    foreach($result as $row)
    {
	$jobtitle = $row['title'];
	$jobcity = $row['city'];
	$jobcountry = $row['country'];
	$jobcategory = $row['category'];
	$jobtype = $row['type'];
	$experience = $row['experience'];
	$jobdescription = $row['description'];
	$jobrespo = $row['responsibility'];
	$jobreq = $row['requirements'];
	$closingdate = $row['closing_date'];
	$opendate = $row['date_posted'];
	$compid = $row['company'];
	if ($jobtype == "Freelance") {
	$sta = '<span class="label label-success">Freelance</span>';
											  
	}
	if ($jobtype == "Part-time") {
	$sta = '<span class="label label-danger">Part-time</span>';
											  
	}
	if ($jobtype == "Full-time") {
	$sta = '<span class="label label-warning">Full-time</span>';
											  
	}

	
	}
	}

					  
	}catch(PDOException $e)
    {
        error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    }


}else{
header("location:./");	
}


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
$stmt = $conn->prepare("SELECT * FROM tbl_users WHERE member_no = :compid");
$stmt->bindParam(':compid', $compid);
$stmt->execute();
$result = $stmt->fetchAll();


    foreach($result as $row)
    {
    $compname = $row['first_name'];
	$complogo = $row['avatar'];
	$compbout = $row['about'];
	}

					  
	}catch(PDOException $e)
    {
        error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    }
	

$today_date = time();
$dObj = parse_flexible_date($closingdate ?? '');
$last_date = $dObj->format('Y/m/d');
$post_date = $dObj->format('d');
$post_month = $dObj->format('F');
$post_year = $dObj->format('Y');
$conv_date = $dObj->getTimestamp();

if ($today_date > $conv_date){
$jobexpired = true;
}else{
$jobexpired = false;
}
?>


<head>
	<base href="/">

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Task Buddy - <?php echo "$jobtitle"; ?></title>
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
	   
	var txt;
    var r = confirm("Are you sure you want to apply this job , you can not UNDO");
    if (r == true) {
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

      xmlhttp.open("GET","app/apply-job.php?opt="+str, true);
      xmlhttp.send();
    } else {

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

			
		</header>
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
		<div class="main-wrapper">
			
			<div class="section sm tb-detail-page-container">
			
				<div class="container">
				
					<div class="row">
						
						<div class="col-md-10 col-md-offset-1">
						
							<div class="job-detail-wrapper">
							
								<div class="tb-detail-hero-card text-center">
											
									<h1 class="tb-detail-hero-title"><?php echo "$jobtitle"; ?></h1>
								
									<div class="tb-detail-hero-subtitle">
										<span>Posted by <a target="_blank" href="lister/<?php echo "$compid"; ?>"><strong><?php echo "$compname"; ?></strong></a></span>
										<span style="display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600;">
											<i class="fa fa-folder-open-o text-primary"></i> <?php echo htmlspecialchars($jobcategory ?? 'General'); ?>
										</span>
										<?php echo "$sta"; ?>
									</div>
									
									<div class="tb-meta-grid">
										<div class="tb-meta-box">
											<div class="tb-meta-icon"><i class="fa fa-map-marker"></i></div>
											<div class="tb-meta-body">
												<div class="tb-meta-label">Location</div>
												<div class="tb-meta-val" title="<?php echo htmlspecialchars("$jobcity, $jobcountry"); ?>"><?php echo "$jobcity, $jobcountry"; ?></div>
											</div>
										</div>
										<div class="tb-meta-box">
											<div class="tb-meta-icon"><i class="fa fa-calendar-check-o"></i></div>
											<div class="tb-meta-body">
												<div class="tb-meta-label">Deadline</div>
												<div class="tb-meta-val"><?php echo "$post_month $post_date, $post_year"; ?></div>
											</div>
										</div>
										<div class="tb-meta-box">
											<div class="tb-meta-icon"><i class="fa fa-briefcase"></i></div>
											<div class="tb-meta-body">
												<div class="tb-meta-label">Experience</div>
												<div class="tb-meta-val"><?php echo !empty($experience) ? htmlspecialchars($experience) : 'Not specified'; ?></div>
											</div>
										</div>
										<div class="tb-meta-box">
											<div class="tb-meta-icon"><i class="fa fa-clock-o"></i></div>
											<div class="tb-meta-body">
												<div class="tb-meta-label">Posted Date</div>
												<div class="tb-meta-val"><?php echo "$opendate"; ?></div>
											</div>
										</div>
									</div>
									
								</div>
					
								<div class="tb-section-card">
									<h3 class="tb-section-card-title">
										<span class="tb-icon-circle"><i class="fa fa-building-o"></i></span> 
										About The Lister
									</h3>
									<div class="tb-company-overview-card">
										<div class="tb-company-overview-logo">
											<?php 
											if (empty($complogo)) {
												$overviewLogoSrc = get_initials_avatar($compname, '', 100);
												print '<img alt="'.htmlspecialchars($compname).'" title="'.htmlspecialchars($compname).'" src="'.$overviewLogoSrc.'" />';
											} else {
												echo '<img alt="image" title="'.htmlspecialchars($compname).'" src="data:image/jpeg;base64,'.base64_encode($complogo).'" />';	
											}
											?>
										</div>
										<div class="tb-company-overview-info">
											<h4>
												<a target="_blank" href="lister/<?php echo "$compid"; ?>"><?php echo "$compname"; ?></a>
												<span style="font-size: 11px; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 4px; margin-left: 6px; font-weight: 600;"><i class="fa fa-check-circle"></i> Verified Lister</span>
											</h4>
											<div class="tb-section-card-content">
												<p><?php echo format_task_text($compbout); ?></p>
											</div>
										</div>
									</div>
								</div>
								
								<div class="tb-section-card">
									<h3 class="tb-section-card-title">
										<span class="tb-icon-circle"><i class="fa fa-file-text-o"></i></span>
										Task Description & Summary
									</h3>
									<div class="tb-section-card-content">
										<?php echo format_task_text($jobdescription); ?>
									</div>
								</div>

								<div class="tb-section-card">
									<h3 class="tb-section-card-title">
										<span class="tb-icon-circle"><i class="fa fa-tasks"></i></span>
										Key Responsibilities & Scope
									</h3>
									<div class="tb-section-card-content">
										<?php echo format_task_text($jobrespo); ?>
									</div>
								</div>

								<div class="tb-section-card">
									<h3 class="tb-section-card-title">
										<span class="tb-icon-circle"><i class="fa fa-map-marker"></i></span>
										Address & Contact Information
									</h3>
									<div class="tb-section-card-content">
										<?php echo format_task_text($jobreq); ?>
									</div>
								</div>
								
								<div class="apply-job-wrapper">
								<?php
						        if ($user_online == true) {
								if ($jobexpired == true) {
								print '<button class="btn btn-primary disabled btn-hidden btn-lg collapsed"><i class="flaticon-line-icon-set-calendar"></i> This job is expired</button>';
								}else{
								if ($myrole == "employee") {
                                print '<button';?> onclick="update(this.value)" <?php print ' value="'.$jobid.'" class="btn btn-primary btn-hidden btn-lg collapsed"><i class="flaticon-line-icon-set-pencil"></i> Apply this job</button>';
								}else{
								print '<button class="btn btn-primary disabled btn-hidden btn-lg collapsed"><i class="flaticon-line-icon-set-padlock"></i> Login as employee to apply</button>';
								}	
								}

								
								}else{
									
								print '<button class="btn btn-primary disabled btn-hidden btn-lg collapsed"><i class="flaticon-line-icon-set-padlock"></i> Login to apply this job</button>';	
								}
								
								?>
								
								<p id="data"></p>

								</div>
								
								<div class="tab-style-01">
								
									<ul class="nav" role="tablist">
										<li role="presentation" class="active"><h4><a href="#relatedTask1" role="tab" data-toggle="tab">More Tasks from <?php echo "$compname"; ?></a></h4></li>
									</ul>

									<div class="tab-content">
										<div role="tabpanel" class="tab-pane fade in active" id="relatedTask1">
											<div class="tab-content-inner">
							<div class="recent-job-wrapper alt-stripe mr-0">
							<?php
							require 'constants/db_config.php';
							try {
                            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $stmt = $conn->prepare("SELECT * FROM tbl_jobs WHERE company = :compid AND job_id != :jobid ORDER BY rand() LIMIT 5");
							$stmt->bindParam(':compid', $compid);
							$stmt->bindParam(':jobid', $jobid);
                            $stmt->execute();
                            $result = $stmt->fetchAll();
  

                            foreach($result as $row) {
							$dObj = parse_flexible_date($row['closing_date'] ?? '');
							$post_date = $dObj->format('d');
                            $post_month = $dObj->format('F');
                            $post_year = $dObj->format('Y');
                            $jobtype = $row['type'];
							
							$jobtype = $row['type'];
							if ($jobtype == "Freelance") {
	                        $sta = '<div class="job-label label label-success">
									Freelance
									</div>';
											  
	                        }
	                        if ($jobtype == "Part-time") {
	                        $sta = '<div class="job-label label label-danger">
									Part-time
									</div>';
											  
	                        }
	                        if ($jobtype == "Full-time") {
	                              $sta = '<div class="job-label label label-warning">
									Full-time
									</div>';
											  
	                        }
							
							?>
																											<a href="task/<?php echo $row['job_id']; ?>" class="recent-job-item clearfix">
														<div class="GridLex-grid-middle">
															<div class="GridLex-col-6_sm-12_xs-12">
																<div class="job-position">
																	<div class="image">
																	 <?php 
										                            if (empty($complogo)) {
										                            $relatedAvatar = get_initials_avatar($compname, '', 90);
										                            print '<center><img class="autofit3" alt="'.htmlspecialchars($compname).'" src="'.$relatedAvatar.'"/></center>';
										                            }else{
										                            echo '<center><img class="autofit3" alt="image" title="'.$compname.'" width="180" height="100" src="data:image/jpeg;base64,'.base64_encode($complogo).'"/></center>';	
										                            }
										                             ?>
																	</div>
																	<div class="content">
																		<h4><?php echo $row['title']; ?></h4>
																		<p><?php echo "$compname"; ?></p>
																	</div>
																</div>
															</div>
															<div class="GridLex-col-3_sm-8-xs-8_xss-12 mt-10-xss">
																<div class="job-location">
																	<i class="fa fa-map-marker text-primary"></i> <?php echo $row['country']; ?>
																</div>
															</div>
															<div class="GridLex-col-3_sm-4_xs-4_xss-12">
                                                             <?php echo "$sta"; ?>
																<span class="font12 block spacing1 font400 text-center"> Due - <?php echo "$post_month"; ?> <?php echo "$post_date"; ?>, <?php echo "$post_year"; ?></span>
															</div>
														</div>
													</a>
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

									</div>
									
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