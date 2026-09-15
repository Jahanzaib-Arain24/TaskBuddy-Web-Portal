<!doctype html>
<html lang="en">
<?php 
require '../constants/settings.php'; 
require 'constants/check-login.php';

if (isset($_GET['page'])) {
$page = $_GET['page'];
if ($page=="" || $page=="1")
{
$page1 = 0;
$page = 1;
}else{
$page1 = ($page*16)-16;
}					
}else{
$page1 = 0;
$page = 1;	
}

if ($user_online == "true") {
if ($myrole == "employer") {
}else{
header("location:../");		
}
}else{
header("location:../");	
}

if (!isset($_GET['jobid']) || empty($_GET['jobid'])) {
    header("location:my-jobs.php");
    exit();
}

$job_id = $_GET['jobid'];
$job_title = "";

require_once '../constants/db_config.php';
try {
    $conn = get_db_connection();
    $stmt = $conn->prepare("SELECT * FROM tbl_jobs WHERE job_id = :jobid AND company = :myid LIMIT 1");
    $stmt->bindParam(':jobid', $job_id);
    $stmt->bindParam(':myid', $myid);
    $stmt->execute();
    $job_row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job_row) {
        header("location:my-jobs.php");
        exit();
    }
    $job_title = $job_row['title'];
} catch(PDOException $e) {
    error_log("DB Error in " . __FILE__ . ":" . __LINE__ . " - " . $e->getMessage());
    header("location:my-jobs.php");
    exit();
}
?>
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Taskbuddy Jobs - Applicants for the job <?php echo "$job_title"; ?></title>
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
	<link rel="stylesheet" href="../icons/simple-line-icons/simple-line-icons.html">
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
	height:63px;
	width:63px;
    object-fit:cover; 
  }
  .btn-back-custom:hover {
    background: #f8fafc !important;
    border-color: #cbd5e1 !important;
    color: #0f172a !important;
    transform: translateX(-3px);
    box-shadow: 0 4px 12px rgba(15,23,42,0.08) !important;
  }
  .btn-view-profile-custom:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
    color: #ffffff !important;
    border-color: #2563eb !important;
    box-shadow: 0 4px 12px rgba(37,99,235,0.25) !important;
    transform: translateY(-2px);
  }
  .btn-view-profile-custom:hover i {
    transform: translateX(4px);
  }
  .btn-status-custom:hover {
    background: #f8fafc !important;
    border-color: #94a3b8 !important;
    color: #0f172a !important;
  }
  .custom-status-dropdown li a {
    transition: all 0.15s ease;
    text-decoration: none !important;
  }
  .custom-status-dropdown li a:hover {
    background: #f8fafc !important;
    transform: translateX(3px);
  }
  </style>

<body class="not-transparent-header">

	<div class="container-wrapper">

		<header id="header">
			<nav class="navbar navbar-default navbar-fixed-top navbar-sticky-function">

				<div class="container">
					
					<div class="logo-wrapper">
						<div class="logo">
							<a href="./"><img src="../image/logo2.png" alt="Logo" /></a>
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
			
			<div class="section sm">
			
				<div class="container">
				
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 12px;">
						<div>
							<h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin: 0 0 6px 0;">
								<i class="fa fa-users text-primary"></i> Applicants for: <span style="color: #2563eb;"><?php echo htmlspecialchars($job_title); ?></span>
							</h2>
							<p style="color: #64748b; font-size: 13.5px; margin: 0;">Review applications, candidate CVs, and select or shortlist candidates.</p>
						</div>
						<a href="my-jobs" class="btn btn-default btn-sm btn-back-custom" style="display: inline-flex; align-items: center; gap: 8px; background: #ffffff; border: 1.5px solid #cbd5e1; color: #1e293b; font-weight: 700; font-size: 13px; padding: 8px 18px; border-radius: 25px; box-shadow: 0 2px 8px rgba(15,23,42,0.05); text-transform: none; transition: all 0.25s ease;">
							<i class="fa fa-arrow-left" style="color: #2563eb;"></i> Back to Posted Tasks
						</a>
					</div>

					<?php if (isset($_GET['status_updated'])): ?>
					<div class="alert alert-success alert-dismissible" role="alert" style="border-radius: 10px; font-weight: 600;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<i class="fa fa-check-circle"></i> Applicant status has been updated to: <strong><?php echo htmlspecialchars($_GET['status_updated']); ?></strong>
					</div>
					<?php endif; ?>
					
					<div class="employee-grid-wrapper">
					
						<div class="GridLex-gap-15-wrappper">
						
							<div class="GridLex-grid-noGutter-equalHeight">
							<?php
							$page1_int = (int)$page1;
							$stmt = $conn->prepare("SELECT * FROM tbl_job_applications WHERE job_id = :jobid ORDER BY id LIMIT $page1_int, 16");
							$stmt->bindParam(':jobid', $job_id);
                            $stmt->execute();
                            $result = $stmt->fetchAll();

                            if (empty($result)) {
                                ?>
                                <div class="GridLex-col-12_sm-12_xs-12">
                                    <div style="text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(15,23,42,0.03);">
                                        <div style="width: 70px; height: 70px; background: #eff6ff; color: #2563eb; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 16px;">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">No Applicants Yet</h3>
                                        <p style="color: #64748b; max-width: 420px; margin: 0 auto; font-size: 14px;">No task seekers have applied to this task yet. Check back soon as taskers browse active gigs.</p>
                                    </div>
                                </div>
                                <?php
                            }

							foreach($result as $row)
                            {
							$dObj = parse_flexible_date($row['application_date'] ?? '');
							$post_date = $dObj->format('d');
                            $post_month = $dObj->format('F');
                            $post_year = $dObj->format('Y');
                            $emp_id = $row['member_no'];
                            $app_status = $row['status'] ?? 'Pending';
							
							$stmtb = $conn->prepare("SELECT * FROM tbl_users WHERE role = 'employee' AND member_no = :empid");
							$stmtb->bindParam(':empid', $emp_id);
                            $stmtb->execute();
                            $resultb = $stmtb->fetchAll();
							
							foreach ($resultb as $rowb)
							{
								$empavatar = $rowb['avatar'];
								$emp_name = trim($rowb['first_name'] . ' ' . $rowb['last_name']);
								if (empty($empavatar)) {
									$emp_avatar_src = get_initials_avatar($rowb['first_name'] ?? 'T', $rowb['last_name'] ?? 'B', 120);
								} else {
									$emp_avatar_src = 'data:image/jpeg;base64,' . base64_encode($empavatar);
								}

								// Fetch highest academic degree if available
								$emp_degree = '';
								try {
									$stmtEdu = $conn->prepare("SELECT course, level FROM tbl_academic_qualification WHERE member_no = :empid ORDER BY id DESC LIMIT 1");
									$stmtEdu->bindParam(':empid', $emp_id);
									$stmtEdu->execute();
									$eduRow = $stmtEdu->fetch(PDO::FETCH_ASSOC);
									if ($eduRow) {
										$emp_degree = !empty($eduRow['course']) ? $eduRow['course'] : $eduRow['level'];
									}
								} catch (PDOException $e) {
									$emp_degree = '';
								}

								// Status badge
								if ($app_status == 'Selected' || $app_status == 'Hired') {
									$statusBadge = '<span class="label label-success" style="background:#dcfce7; color:#15803d; font-weight:700; padding:4px 9px; border-radius:12px; font-size:11px;"><i class="fa fa-check-circle"></i> Selected / Hired</span>';
								} elseif ($app_status == 'Shortlisted') {
									$statusBadge = '<span class="label" style="background:#f3e8ff; color:#7e22ce; font-weight:700; padding:4px 9px; border-radius:12px; font-size:11px;"><i class="fa fa-star"></i> Shortlisted</span>';
								} elseif ($app_status == 'Rejected') {
									$statusBadge = '<span class="label label-danger" style="background:#fee2e2; color:#b91c1c; font-weight:700; padding:4px 9px; border-radius:12px; font-size:11px;"><i class="fa fa-times-circle"></i> Rejected</span>';
								} else {
									$statusBadge = '<span class="label" style="background:#f1f5f9; color:#475569; font-weight:700; padding:4px 9px; border-radius:12px; font-size:11px;"><i class="fa fa-clock-o"></i> Under Review</span>';
								}
								?>
								<div class="GridLex-col-3_sm-4_xs-6_xss-12" style="margin-bottom: 24px;">
								
									<div style="background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 14px rgba(15,23,42,0.04); padding: 22px 18px 18px; text-align: center; display: flex; flex-direction: column; justify-content: space-between; height: 100%; position: relative;">
										
										<!-- Top status bar -->
										<div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
											<?php echo $statusBadge; ?>
											<span style="font-size: 11px; color: #94a3b8; font-weight: 600;"><i class="fa fa-calendar-o"></i> <?php echo "$post_month $post_date"; ?></span>
										</div>

										<!-- Avatar with verified checkmark -->
										<div style="position: relative; width: 85px; height: 85px; margin: 0 auto 12px;">
											<img class="img-circle" alt="<?php echo htmlspecialchars($emp_name); ?>" style="width: 85px; height: 85px; object-fit: cover; border: 3px solid #e2e8f0;" src="<?php echo $emp_avatar_src; ?>" />
											<span style="position: absolute; bottom: 0; right: 2px; background: #10b981; color: #ffffff; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 11px; border: 2px solid #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
												<i class="fa fa-check"></i>
											</span>
										</div>

										<!-- Name & Location -->
										<div style="margin-bottom: 12px;">
											<h4 style="font-size: 16px; font-weight: 700; color: #0f172a; margin: 0 0 4px;"><?php echo htmlspecialchars($emp_name); ?></h4>
											<p style="font-size: 12px; color: #64748b; margin: 0;"><i class="fa fa-map-marker text-primary"></i> <?php echo htmlspecialchars($rowb['city'] ?: $rowb['country']); ?>, <?php echo htmlspecialchars($rowb['country']); ?></p>
										</div>

										<!-- Tags: Profession & Education -->
										<div style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; align-items: center;">
											<?php if (!empty($rowb['title'])): ?>
											<span style="background: #f5f3ff; color: #6d28d9; font-size: 11.5px; font-weight: 600; padding: 4px 12px; border-radius: 20px; display: inline-block; max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
												<i class="fa fa-wrench"></i> <?php echo htmlspecialchars($rowb['title']); ?>
											</span>
											<?php endif; ?>

											<?php if (!empty($emp_degree)): ?>
											<span style="background: #ecfdf5; color: #047857; font-size: 11.5px; font-weight: 600; padding: 4px 12px; border-radius: 20px; display: inline-block; max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
												<i class="fa fa-graduation-cap"></i> <?php echo htmlspecialchars($emp_degree); ?>
											</span>
											<?php endif; ?>
										</div>

										<!-- Action & Status Controls -->
										<div style="padding-top: 14px; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 8px;">
											<a target="_blank" href="../employee-detail.php?empid=<?php echo $rowb['member_no']; ?>" class="btn btn-sm btn-block btn-view-profile-custom" style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #eff6ff; border: 1.5px solid #bfdbfe; color: #2563eb; font-weight: 700; border-radius: 12px; font-size: 13px; padding: 8px 12px; text-transform: none; transition: all 0.25s ease; box-shadow: 0 2px 6px rgba(37,99,235,0.06);">
												<span>View Profile</span> <i class="fa fa-arrow-right" style="font-size: 12px;"></i>
											</a>

											<!-- Status Update Dropdown -->
											<div class="dropdown">
												<button class="btn btn-sm btn-default dropdown-toggle btn-block btn-status-custom" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center; justify-content: space-between; background: #ffffff; border: 1.5px solid #cbd5e1; color: #334155; font-weight: 700; border-radius: 12px; font-size: 12.5px; padding: 7px 12px; text-transform: none; transition: all 0.2s ease; box-shadow: 0 1px 4px rgba(0,0,0,0.03);">
													<span style="display: inline-flex; align-items: center; gap: 7px;">
														<i class="fa fa-sliders" style="color: #4f46e5;"></i> Update Decision
													</span>
													<i class="fa fa-chevron-down" style="font-size: 10px; color: #94a3b8;"></i>
												</button>
												<ul class="dropdown-menu dropdown-menu-right custom-status-dropdown text-left" style="border-radius: 14px; box-shadow: 0 14px 35px rgba(15,23,42,0.12); border: 1px solid #e2e8f0; padding: 8px; min-width: 205px; margin-top: 6px;">
													<li style="padding: 4px 10px 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8;">Hiring Action</li>
													<li>
														<a href="app/update-applicant-status.php?appid=<?php echo $row['id']; ?>&jobid=<?php echo urlencode($job_id); ?>&status=Selected" style="padding: 8px 12px; border-radius: 8px; display: flex; align-items: center; gap: 10px; color: #059669; font-weight: 600; font-size: 12.5px;">
															<span style="width: 22px; height: 22px; border-radius: 50%; background: #dcfce7; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #15803d;"><i class="fa fa-check"></i></span>
															<span>Select / Hire</span>
														</a>
													</li>
													<li>
														<a href="app/update-applicant-status.php?appid=<?php echo $row['id']; ?>&jobid=<?php echo urlencode($job_id); ?>&status=Shortlisted" style="padding: 8px 12px; border-radius: 8px; display: flex; align-items: center; gap: 10px; color: #7e22ce; font-weight: 600; font-size: 12.5px;">
															<span style="width: 22px; height: 22px; border-radius: 50%; background: #f3e8ff; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #7e22ce;"><i class="fa fa-star"></i></span>
															<span>Shortlist Candidate</span>
														</a>
													</li>
													<li>
														<a href="app/update-applicant-status.php?appid=<?php echo $row['id']; ?>&jobid=<?php echo urlencode($job_id); ?>&status=Rejected" style="padding: 8px 12px; border-radius: 8px; display: flex; align-items: center; gap: 10px; color: #dc2626; font-weight: 600; font-size: 12.5px;">
															<span style="width: 22px; height: 22px; border-radius: 50%; background: #fee2e2; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #b91c1c;"><i class="fa fa-times"></i></span>
															<span>Reject Application</span>
														</a>
													</li>
													<li class="divider" style="margin: 6px 0; border-top: 1px solid #f1f5f9;"></li>
													<li>
														<a href="app/update-applicant-status.php?appid=<?php echo $row['id']; ?>&jobid=<?php echo urlencode($job_id); ?>&status=Pending" style="padding: 8px 12px; border-radius: 8px; display: flex; align-items: center; gap: 10px; color: #475569; font-weight: 600; font-size: 12px;">
															<span style="width: 22px; height: 22px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #64748b;"><i class="fa fa-undo"></i></span>
															<span>Reset to Under Review</span>
														</a>
													</li>
												</ul>
											</div>
										</div>

									</div>
									
								</div>
								<?php
							}
	                        }
							?>
							

								

								
							</div>
						
						</div>

					</div>
					
					<?php
					$total_records = 0;
					$stmt = $conn->prepare("SELECT COUNT(*) as total FROM tbl_job_applications WHERE job_id = :jobid");
					$stmt->bindParam(':jobid', $job_id);
					$stmt->execute();
					$resCount = $stmt->fetch(PDO::FETCH_ASSOC);
					$total_records = $resCount['total'] ?? 0;

					$records = ceil($total_records/16);
					if ($records > 1) {
					?>
					<div class="pager-wrapper">
						<ul class="pager-list">
					<?php
					$prevpage = $page - 1;
					$nextpage = $page + 1;
					
					print '<li class="paging-nav ' . ($page == "1" ? 'disabled' : '') . '"><a ' . ($page == "1" ? '' : 'href="view-applicants?jobid='.$job_id.'&page='.$prevpage.'"') . '><i class="fa fa-chevron-left"></i></a></li>';
					for ($b=1;$b<=$records;$b++)
					{
					?><li class="paging-nav <?php if ($b == $page) { echo 'active'; } ?>"><a <?php if ($b == $page) { print ' style="background-color:#2563eb; color:white;" '; } ?> href="view-applicants?jobid=<?php echo "$job_id"; ?>&page=<?php echo "$b"; ?>"><?php echo $b." "; ?></a></li><?php
					}	
					print '<li class="paging-nav ' . ($page == $records ? 'disabled' : '') . '"><a ' . ($page == $records ? '' : 'href="view-applicants?jobid='.$job_id.'&page='.$nextpage.'"') . '><i class="fa fa-chevron-right"></i></a></li>';
					?>
						</ul>	
					</div>
					<?php } ?>

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