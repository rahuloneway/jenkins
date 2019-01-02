<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Database Error</title>
	<!-- Bootstrap -->
	<link href="<?php echo SITE_URL;?>assets/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo SITE_URL;?>assets/css/bootstrap-theme.min.css">
	<link href="<?php echo SITE_URL;?>assets/css/style.css" rel="stylesheet" type="text/css">
	<link href="<?php echo SITE_URL;?>assets/css/style_table.css" rel="stylesheet" type="text/css">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
	</head>
	<body>
		<header>
		<div class="container-fluid head">
			<img src="<?php echo SITE_URL;?>assets/images/logo.png"/>
			</div>
		</div>
		</header>
		<section class="grey-body">
			<div class="container-fluid ">
				<div class="account_sum">
					<div class="row">
						<!-- <div class="col-md-6">
							<img src="images/cashman404.png" alt="404">
						</div> -->
						<div class="col-md-12 text-center error-page">
							<div class="col-md-6 text-right col-md-offset-3 ">
								<img src="<?php echo SITE_URL;?>assets/images/images.png" alt="file">
								
							</div>
							<div class="col-md-6 text-center col-md-offset-3">
								<h3>Sorry!</h3>
								<p>Unexpected error occurred. Try again</p>
								<p>Please try one of the following pages:<a href="<?php echo SITE_URL;?>">Home Page</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<footer class="copyright">
			<div class="container-fluid">
				<p>
					&copy; 2014 cash man.</a> All Rights Reserved.
				</p>
				<ul class=" pull-right list">
					<li><a href="#">Privacy Policy </a></li>
					<li><a href="#">Terms and Conditions</a></li>
				</ul>
			</div>
		</footer>
	</body>
</html>