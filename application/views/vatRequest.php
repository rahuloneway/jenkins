<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>Cashmann|webApp login</title>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>assets/css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>assets/css/font-awesome.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>assets/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>assets/css/cashman.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url();?>assets/css/jquery-ui.min.css"/>
	
	<noscript>please enable the JavaScript of the browser</noscript>
</head>
<body>
	<header class="container-fluid head">
		<img src="<?php echo base_url();?>assets/images/logo.png" />
	</header>
	<section class="home_middle grey-body">
		<div class="container">
			<div class="container">
				<div class="row">
					 <div class="col-sm-12 col-md-12 col-xs-12 " >
						<?php if($acction == 'accept'){ ?>
						<h3><?php echo "Thank you for accepting vat submision request for ".companyName($companyID)."quarter ".$quarter.". This will be submited sortly.";  ?> </h3>
						<?php }else {?>
						<h3><?php echo "You denied vat submision request for ".companyName($companyID)."quarter ".$quarter.".";?> </h3>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
		<div id="dialog"></div>
	</section>
	<footer class="copyright">
		<div class="container-fluid">
			<p>&copy; 2014 CASHMANN All Rights Reserved.</p>
				<ul class=" pull-right list">
					<li><a href="#">Privacy Policy  </a></li>
					<li><a href="#">Terms and Conditions</a></li>
				</ul>
		</div>
	</footer>
</body>
</html>