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

	<title>Task Buddy - Contact Us</title>
	<meta name="description" content="Online Task Management / Task Portal" />
	<meta name="keywords" content="job, work, resume, applicants, application, employee, employer, hire, hiring, human resource management, hr, online job management, company, worker, career, recruiting, recruitment" />
	<meta name="author" content="BwireSoft">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta property="og:image" content="http://<?php echo "$actual_link"; ?>/images/banner.jpg" />
    <meta property="og:image:secure_url" content="https://<?php echo "$actual_link"; ?>/images/banner.jpg" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="300" />
    <meta property="og:image:alt" content="Task Buddy Tasks" />
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
							<li><a href="tasks">Tasks List</a></li>
							<li><a href="task-seekers">Task Seekers</a></li>
							<li><a href="task-listers">Task Listers</a></li>
							<li class="active"><a href="contact">Contact Us</a></li>
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

			<div class="tb-contact-hero">
				<div class="container">
					<div class="tb-contact-hero-badge"><i class="fa fa-life-ring"></i> Support Center 24/7</div>
					<h1>We're Here To Help You Succeed</h1>
					<p>Have questions about posting tasks, finding work, or need assistance? Reach out to our dedicated team anytime.</p>
				</div>
			</div>

			<div class="tb-contact-cards-section">
				<div class="container">
					<div class="row">
						<div class="col-sm-4 mb-20">
							<div class="tb-contact-card">
								<div class="tb-c-icon-circle" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important; color: #ffffff !important;"><i class="fa fa-map-marker" style="color: #ffffff !important; font-size: 24px !important;"></i></div>
								<h4>Our Office</h4>
								<p>Latifabad Unit 11, Hyderabad, Sindh, Pakistan</p>
								<a href="https://maps.google.com" target="_blank" class="tb-c-link">Get Directions <i class="fa fa-arrow-right"></i></a>
							</div>
						</div>
						<div class="col-sm-4 mb-20">
							<div class="tb-contact-card">
								<div class="tb-c-icon-circle" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important; color: #ffffff !important;"><i class="fa fa-phone" style="color: #ffffff !important; font-size: 24px !important;"></i></div>
								<h4>Call or WhatsApp</h4>
								<p>Mon – Sat, 9:00 AM to 8:00 PM</p>
								<a href="tel:+923123358542" class="tb-c-link">+92 312 3358542 <i class="fa fa-arrow-right"></i></a>
							</div>
						</div>
						<div class="col-sm-4 mb-20">
							<div class="tb-contact-card">
								<div class="tb-c-icon-circle" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important; color: #ffffff !important;"><i class="fa fa-envelope-o" style="color: #ffffff !important; font-size: 24px !important;"></i></div>
								<h4>Direct Email</h4>
								<p>Average response time within 2 hours</p>
								<a href="mailto:arainjhanzaib@gmail.com" class="tb-c-link">arainjhanzaib@gmail.com <i class="fa fa-arrow-right"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="tb-contact-main-section">
				<div class="container">
					<div class="row">
						<div class="col-md-7 mb-30">
							<div class="tb-contact-form-box">
								<h3>Send Us a Message</h3>
								<p>Fill out the form below and our team will get back to you promptly.</p>
								
								<?php include 'constants/check_reply.php'; ?>

								<form class="contact-form-wrapper" data-toggle="validator" action="app/send-message.php" method="POST" autocomplete="off">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group tb-modern-group">
												<label for="inputName"><i class="fa fa-user"></i> Your Full Name</label>
												<input id="inputName" name="fullname" type="text" class="form-control tb-modern-input" placeholder="e.g. Muhammad Jahanzaib" data-error="Your name is required" required>
												<div class="help-block with-errors"></div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group tb-modern-group">
												<label for="inputEmail"><i class="fa fa-envelope-o"></i> Your Email Address</label>
												<input id="inputEmail" name="email" type="email" class="form-control tb-modern-input" placeholder="e.g. arainjhanzaib@gmail.com" data-error="Valid email is required" required>
												<div class="help-block with-errors"></div>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="form-group tb-modern-group">
												<label for="inputMessage"><i class="fa fa-comment-o"></i> Message Details</label>
												<textarea id="inputMessage" name="message" class="form-control" style="border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px; min-height: 140px; box-shadow: none; font-size: 14px;" placeholder="Tell us how we can help you with your task or account..." data-minlength="20" data-error="Please write at least 20 characters" required></textarea>
												<div class="help-block with-errors"></div>
											</div>
										</div>
										<div class="col-sm-12">
											<button type="submit" class="tb-auth-submit-btn" style="height: 48px; border-radius: 10px; background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important; color: #ffffff !important;">
												<span style="color: #ffffff !important; font-weight: 700 !important; font-size: 15px !important;">Send Message</span>
												<i class="fa fa-paper-plane" style="color: #ffffff !important;"></i>
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>

						<div class="col-md-5 mb-30">
							<div class="tb-faq-container">
								<h3>Frequently Asked Questions</h3>
								<div class="tb-faq-item">
									<div class="tb-faq-question"><i class="fa fa-question-circle"></i> How does TaskBuddy work?</div>
									<p class="tb-faq-answer">TaskBuddy connects local homeowners & businesses with skilled taskers for everyday jobs like plumbing, electrical work, cleaning, errands, and repairs.</p>
								</div>
								<div class="tb-faq-item">
									<div class="tb-faq-question"><i class="fa fa-question-circle"></i> How do task seekers get hired?</div>
									<p class="tb-faq-answer">Create a free Task Seeker profile, list your skills & city, then browse available tasks and apply directly with 1 click.</p>
								</div>
								<div class="tb-faq-item">
									<div class="tb-faq-question"><i class="fa fa-question-circle"></i> Is posting a task free?</div>
									<p class="tb-faq-answer">Yes! Task Listers can register and post small gigs, part-time jobs, or emergency home repair tasks completely free of charge.</p>
								</div>
								<div class="tb-faq-item">
									<div class="tb-faq-question"><i class="fa fa-question-circle"></i> Which cities are supported?</div>
									<p class="tb-faq-answer">We currently support tasks across Pakistan including Hyderabad, Karachi, Lahore, Islamabad, Rawalpindi, Faisalabad, and Multan.</p>
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

<script src="js/validator.min.js"></script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="js/infobox.js"></script>

<script>
	function initialize() {


var styles = [{"featureType":"all","elementType":"labels","stylers":[{"lightness":63},{"hue":"#ff0000"}]},{"featureType":"administrative","elementType":"all","stylers":[{"hue":"#000bff"},{"visibility":"on"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"color":"#4a4a4a"},{"visibility":"on"}]},{"featureType":"administrative","elementType":"labels.text","stylers":[{"weight":"0.01"},{"color":"#727272"},{"visibility":"on"}]},{"featureType":"administrative.country","elementType":"labels","stylers":[{"color":"#ff0000"}]},{"featureType":"administrative.country","elementType":"labels.text","stylers":[{"color":"#ff0000"}]},{"featureType":"administrative.province","elementType":"geometry.fill","stylers":[{"visibility":"on"}]},{"featureType":"administrative.province","elementType":"labels.text","stylers":[{"color":"#545454"}]},{"featureType":"administrative.locality","elementType":"labels.text","stylers":[{"visibility":"on"},{"color":"#737373"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text","stylers":[{"color":"#7c7c7c"},{"weight":"0.01"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text","stylers":[{"color":"#404040"}]},{"featureType":"landscape","elementType":"all","stylers":[{"lightness":16},{"hue":"#ff001a"},{"saturation":-61}]},{"featureType":"poi","elementType":"labels.text","stylers":[{"color":"#828282"},{"weight":"0.01"}]},{"featureType":"poi.government","elementType":"labels.text","stylers":[{"color":"#4c4c4c"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"hue":"#00ff91"}]},{"featureType":"poi.park","elementType":"labels.text","stylers":[{"color":"#7b7b7b"}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"color":"#999999"},{"visibility":"on"},{"weight":"0.01"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"hue":"#ff0011"},{"lightness":53}]},{"featureType":"road.highway","elementType":"labels.text","stylers":[{"color":"#626262"}]},{"featureType":"transit","elementType":"labels.text","stylers":[{"color":"#676767"},{"weight":"0.01"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#0055ff"}]}];

var loc, map, marker, infobox;

var styledMap = new google.maps.StyledMapType(styles,  {name: "Styled Map"});

loc = new google.maps.LatLng($("#map").attr("data-lat"), $("#map").attr("data-lon"));

map = new google.maps.Map(document.getElementById("map"), {
	zoom: 14,
	center: loc,
	scrollwheel: false,

	navigationControl: false,
	scaleControl: false,
	mapTypeControl:false,
	streetViewControl: false,
	mapTypeControlOptions: {
		mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
	},
	mapTypeId: google.maps.MapTypeId.ROADMAP,
});

map.mapTypes.set('map_style', styledMap);
map.setMapTypeId('map_style');

marker = new google.maps.Marker({
	map: map,
	position: loc,

	icon:'images/map-marker/00.png',
	visible: true

});

infobox = new InfoBox({
	content: document.getElementById("infobox"),
	disableAutoPan: true,
	pixelOffset: new google.maps.Size(0, -50),
	zIndex: null,
	alignBottom: true,
	isHidden: false,
	closeBoxURL: "images/infobox-close.png",
	closeBoxClass:"infoBox-close",
	infoBoxClearance: new google.maps.Size(1, 1)
});

openInfoBox(marker);

google.maps.event.addListener(marker, 'click', function() {
	openInfoBox(this);
});

function openInfoBox(thisMarker){
	map.panTo(loc);
	map.panBy(0,-80);
	infobox.open(map, thisMarker);
}

var center;
function calculateCenter() {
	center = map.getCenter();
}
google.maps.event.addDomListener(map, 'idle', function() {
	calculateCenter();
});
google.maps.event.addDomListener(window, 'resize', function() {
	map.setCenter(center);
});

}
google.maps.event.addDomListener(window, 'load', initialize);
</script>

</body>

</html>