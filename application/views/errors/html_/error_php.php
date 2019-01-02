<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>
		<!-- Bootstrap -->
		<link href="<?php echo site_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo site_url();?>assets/fonts/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="<?php echo site_url();?>assets/css/bootstrap-theme.min.css">
		<link href="<?php echo site_url();?>assets/css/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="<?php echo site_url();?>assets/css/jquery-ui.css">
		<link href="<?php echo site_url();?>assets/css/style_table.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<header>
			<div class="container-fluid head">
				<img src="<?php echo site_url();?>assets/images/logo.png"/>
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
							<div class="col-md-6 text-center col-md-offset-3 ">
								<img src="<?php echo site_url();?>assets/images/encryption-series-part-2-large.png" alt="file">
							</div>
							<div class="col-md-6 text-center col-md-offset-3">
								<h3>Sorry!</h3>
								<p>Unexpected error occured</p>
								<p>Please try one of the following pages:<a href="<?php echo site_url();?>">Home Page</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<footer class="copyright">
			<div class="container-fluid">
				<p>
					&copy; 2014 cash man - Powerd by<a href="<?php echo site_url();?>"> Xcelance Web Solutions.</a> All Rights Reserved.
				</p>
				<ul class=" pull-right list">
					<li><a href="#">Privacy Policy </a></li>
					<li><a href="#">Terms and Conditions</a></li>
				</ul>
			</div>
		</footer>
	</body>
</html>