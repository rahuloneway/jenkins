<?php
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	if (!defined('constant')) define('SITE_URL',config_item('base_url'));
?>
<?php if( ENVIRONMENT == 'development'){?>
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: <?php echo $severity; ?></p>
<p>Message:  <?php echo $message; ?></p>
<p>Filename: <?php echo $filepath; ?></p>
<p>Line Number: <?php echo $line; ?></p>

</div>
<?php 
}
else {?>

<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Syntax Error</title>
	<!-- Bootstrap -->
	<link href="<?php echo SITE_URL;?>assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo SITE_URL;?>assets/css/style.css" rel="stylesheet" type="text/css">
	<link href="<?php echo SITE_URL;?>assets/css/style_table.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<header>
			<div class="container-fluid head">
				<img src="<?php echo SITE_URL;?>assets/images/logo.png"/>
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
								
								<img src="<?php echo SITE_URL;?>assets/images/encryption-series-part-2-large.png" alt="file">
								
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
<?php } ?>