<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>404 page not found</title>
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>assets/css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>assets/css/bootstrap-theme.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>assets/css/font-awesome.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>assets/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>assets/css/cashman.css"/>

	<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo SITE_URL;?>assets/js/cashman.js"></script>
	<!-- Add conditional Javascript files according to the webpage -->
	<script  id="script" type="text/javascript"></script>
</head>
<body>
<header>
  <div class="container-fluid padding-zero"> <img src="<?php echo SITE_URL;?>assets/images/logo.png" alt="Cashmann web application"/>
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
					<div class="col-md-6 text-center col-md-offset-4">
						<h1>4</h1>
						<div id="animated-example" class="animated tada">
							<img src="<?php echo SITE_URL;?>assets/images/cashman404.png" alt="404">
						</div>
						<h1>4</h1>
					</div>
					<div class="col-md-6 text-center col-md-offset-3">
						<h3>oops!</h3>
						<p>Page not found</p>
						<p>What you are looking for? does not exists on this server</p>
						<p>Get back to <a href="<?php echo SITE_URL;?>">Homepage</a></p>
					</div>
				</div>

				
			</div>
		</div>

	</div>
</section>
<?php
	$this->load->view('client/footer');
?>