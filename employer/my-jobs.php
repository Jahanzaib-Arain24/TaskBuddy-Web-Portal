<!doctype html>
<html lang="en">
<?php 
require '../constants/settings.php'; 
require 'constants/check-login.php';

if ($user_online == "true") {
if ($myrole == "employer") {
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
$page1 = ($page*5)-5;
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

	<title>Taskbuddy  Jobs - My Jobs</title>
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
									
										<h2 class="admin-title-heading"><i class="fa fa-bookmark"></i> Posted Tasks & Jobs</h2>
										<p class="admin-title-sub">Manage and view applications for all your active tasks</p>
										
									</div>
									<?php require 'constants/check_reply.php'; ?>
									<div class="job-item-grid-wrapper">
					
										<div class="GridLex-gap-20">
										
											<div class="GridLex-grid-noGutter-equalHeight">
									<?php
										require '../constants/db_config.php';
										try {
                                        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
                                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $page1_int = (int)$page1;
                                        $stmt = $conn->prepare("SELECT * FROM tbl_jobs WHERE company = :myid ORDER BY enc_id DESC LIMIT $page1_int,6");
                                        $stmt->bindParam(':myid', $myid);
                                        $stmt->execute();
                                        $result = $stmt->fetchAll();

                                        if (empty($result)) {
                                            ?>
                                            <div class="GridLex-col-12_sm-12_xs-12">
                                                <div style="text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(15,23,42,0.03);">
                                                    <div style="width: 70px; height: 70px; background: #eff6ff; color: #2563eb; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 16px;">
                                                        <i class="fa fa-folder-open-o"></i>
                                                    </div>
                                                    <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">No Tasks Posted Yet</h3>
                                                    <p style="color: #64748b; max-width: 420px; margin: 0 auto 20px; font-size: 14px;">You haven't posted any tasks or jobs yet. Post a new task to connect with qualified taskers.</p>
                                                    <a href="post-job" class="btn btn-primary" style="border-radius: 8px; font-weight: 600; padding: 10px 22px;">
                                                        <i class="fa fa-plus-circle"></i> Post a New Task
                                                    </a>
                                                </div>
                                            </div>
                                            <?php
                                        }
    
                                        foreach($result as $row)
                                        {
										   $jobcity = $row['city'];
										   $jobcountry = $row['country'];
										   $type = $row['type'];
										   $title = $row['title'];
										   $deadline = $row['closing_date'];
										   $dObj = parse_flexible_date($deadline);
										   $formatted_deadline = $dObj->format('F d, Y');

										   if ($type == "Freelance") {
											 $sta = '<span class="label label-success" style="background:#10b981; font-size:11px; font-weight:600; padding:4px 9px; border-radius:12px;">Freelance</span>';
										   } elseif ($type == "Part-time") {
											 $sta = '<span class="label label-danger" style="background:#ef4444; font-size:11px; font-weight:600; padding:4px 9px; border-radius:12px;">Part-time</span>';
										   } else {
											 $sta = '<span class="label label-warning" style="background:#f59e0b; font-size:11px; font-weight:600; padding:4px 9px; border-radius:12px;">Full-time</span>';
										   }

										   // Count applicants for this task
										   $stmtApp = $conn->prepare("SELECT COUNT(*) FROM tbl_job_applications WHERE job_id = :jobid");
										   $stmtApp->bindParam(':jobid', $row['job_id']);
										   $stmtApp->execute();
										   $app_count = (int)$stmtApp->fetchColumn();
										   ?>
										   <div class="GridLex-col-6_sm-12_xs-12" style="margin-bottom: 22px;">
												
												<div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 14px rgba(15,23,42,0.04); padding: 22px; display: flex; flex-direction: column; justify-content: space-between; height: 100%; transition: all 0.25s ease;">
													
													<!-- Header row: Category & Type Badge -->
													<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; gap: 8px;">
														<span style="font-size: 11.5px; font-weight: 600; background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 20px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 60%;">
															<i class="fa fa-tag text-primary"></i> <?php echo htmlspecialchars($row['category'] ?: 'Task'); ?>
														</span>
														<div>
															<?php echo $sta; ?>
														</div>
													</div>
													
													<!-- Task Title & Information -->
													<div style="margin-bottom: 16px;">
														<h4 style="font-size: 16.5px; font-weight: 700; margin: 0 0 10px 0; line-height: 1.4;">
															<a target="_blank" href="../task/<?php echo $row['job_id']; ?>" style="color: #0f172a; text-decoration: none;">
																<?php echo htmlspecialchars($title); ?>
															</a>
														</h4>
														
														<div style="display: flex; flex-direction: column; gap: 6px; font-size: 12.5px; color: #64748b;">
															<div style="display: flex; align-items: center; gap: 6px;">
																<i class="fa fa-map-marker text-danger" style="width: 14px; text-align: center;"></i>
																<span><strong style="color: #334155;"><?php echo htmlspecialchars($jobcountry); ?></strong> - <?php echo htmlspecialchars($jobcity); ?></span>
															</div>
															<div style="display: flex; align-items: center; gap: 6px;">
																<i class="fa fa-clock-o text-primary" style="width: 14px; text-align: center;"></i>
																<span>Deadline: <strong style="color: #334155;"><?php echo htmlspecialchars($formatted_deadline); ?></strong></span>
															</div>
															<div style="display: flex; align-items: center; gap: 6px;">
																<i class="fa fa-briefcase text-success" style="width: 14px; text-align: center;"></i>
																<span>Experience: <?php echo htmlspecialchars($row['experience'] ?: 'Any'); ?></span>
															</div>
														</div>
													</div>
													
													<!-- Action Buttons Bottom Bar -->
													<div style="padding-top: 14px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-wrap: wrap;">
														<a target="_blank" href="view-applicants?jobid=<?php echo $row['job_id']; ?>" class="btn btn-xs btn-primary" style="border-radius: 8px; font-weight: 600; padding: 6px 12px; background: #2563eb; border: none; font-size: 12px;">
															<i class="fa fa-users"></i> Applicants (<?php echo $app_count; ?>)
														</a>
														
														<div style="display: flex; gap: 4px;">
															<a target="_blank" href="../task/<?php echo $row['job_id']; ?>" class="btn btn-xs btn-default" style="border-radius: 6px; color: #475569;" title="Preview Task">
																<i class="fa fa-external-link"></i>
															</a>
															<a href="edit-job?jobid=<?php echo $row['job_id']; ?>" class="btn btn-xs btn-default" style="border-radius: 6px; color: #475569; font-weight: 600;" title="Edit Task">
																<i class="fa fa-pencil"></i> Edit
															</a>
															<a onclick="return confirm('Are you sure you want to delete this task?')" href="app/drop-job.php?id=<?php echo $row['job_id']; ?>" class="btn btn-xs btn-danger" style="border-radius: 6px; background: #fee2e2; color: #dc2626; border: 1px solid #fecaca;" title="Delete Task">
																<i class="fa fa-trash"></i>
															</a>
														</div>
													</div>
													
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
								require '../constants/db_config.php';
								try {
                                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $conn->prepare("SELECT COUNT(*) as total FROM tbl_jobs WHERE company = :myid");
                                $stmt->bindParam(':myid', $myid);
                                $stmt->execute();
                                $resCount = $stmt->fetch(PDO::FETCH_ASSOC);
                                $total_records = $resCount['total'] ?? 0;
	                            }catch(PDOException $e)
                                {
                                    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
                                }
										
								$records = ceil($total_records/6);
				                if ($records > 1) {
								?>
								<div class="pager-wrapper">
						            <ul class="pager-list">
								<?php
								$prevpage = $page - 1;
								$nextpage = $page + 1;
								
								print '<li class="paging-nav ' . ($page == "1" ? 'disabled' : '') . '"><a ' . ($page == "1" ? '' : 'href="my-jobs.php?page='.$prevpage.'"') . '><i class="fa fa-chevron-left"></i></a></li>';
					            for ($b=1;$b<=$records;$b++)
                                 {
		                        ?><li class="paging-nav <?php if ($b == $page) { echo 'active'; } ?>"><a <?php if ($b == $page) { print ' style="background-color:#2563eb; color:white;" '; } ?> href="my-jobs.php?page=<?php echo "$b"; ?>"><?php echo $b." "; ?></a></li><?php
                                 }	
								 print '<li class="paging-nav ' . ($page == $records ? 'disabled' : '') . '"><a ' . ($page == $records ? '' : 'href="my-jobs.php?page='.$nextpage.'"') . '><i class="fa fa-chevron-right"></i></a></li>';
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


</body>



</html>