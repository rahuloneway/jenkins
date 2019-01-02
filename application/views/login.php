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
	<script src="<?php echo site_url();?>assets/js/jquery-1.11.1.min.js"></script> 
	<script src="<?php echo site_url();?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo site_url();?>assets/js/jquery-ui.min.js"></script>
	<script>
		function checkForm(task)
		{
			$('.umsg').css('display','none');
			$('.pmsg').slideUp('slow').css('display','none');
			if($('#uname').val() == '')
			{	
				$('.umsg').css('display','block');
				$('.umsg').html('<?php echo $this->lang->line('EMPTY_USERID_ERROR');?>');
				//$('#uname').focus();
				return false;
			}	
			if($('#password').val() == '')
			{
				$('.pmsg').css('display','block');
				$('.pmsg').html('<?php echo $this->lang->line('EMPTY_PASSWORD_ERROR');?>');
				//$('#password').focus();
				return false;
			}
		}
		
		function dialogBox(tt,txt)
		{
			$('#dialog').html(txt);
			$('#dialog').dialog({ 
				autoOpen : true,
				title: tt,
				modal: true,
				minWidth: 500,
				draggable: false,
				buttons: {
					Ok: function() {
						$(this).dialog('close');
					}
				}
			});	
		}
		$(document).on('submit','#login',function(e){
			checkForm();
			
		});
	</script>
	<noscript>please enable the JavaScript of the browser</noscript>
</head>
<body>
	<header class="container-fluid head">
		<img src="<?php echo base_url();?>assets/images/logo.png" />
	</header>
	<section class="home_middle grey-body">
		<div class="container-fluid">
			<div class="container">
				<div class="row">
					 <div class="col-sm-6 col-md-5 col-xs-12 login_with-us ">
						<h1><?php echo $this->lang->line('LOGIN_FORM_TITLE');?></h1>
						<?php echo $this->session->flashdata('loginMessage');?>
						<br/>
						<?php
							echo form_open('',array('name'=>'loginform','id'=>'loginform','class'=>'form-signin','onSubmit'=>'return checkForm();'));
						?>
						<input type="text" name="uname" id="uname" value="<?php echo set_value('uname');?>" class="form-control" placeholder="Email"/>
						<?php echo form_error('uname');?>
						<div class="alert alert-danger umsg"></div>
						<input type="password" class="form-control" name="password" id="password" value="<?php echo set_value('password');?>" placeholder="Password"/>
						<?php echo form_error('password');?>
						<div class="alert alert-danger pmsg"></div>
						<div>
							<input type="hidden" name="action" value="<?php echo $formAction;?>" />
							<input type="submit" name="loginForm" id="login" value="Login" class="btn btn-search"/>
							<a href="<?php echo base_url();?>home/password_recovery" class="forgot_link">
								<?php echo $this->lang->line('LOGIN_FORM_LABEL_FORGET');?>
							</a>
						</div>
						<?php echo form_close();?>
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