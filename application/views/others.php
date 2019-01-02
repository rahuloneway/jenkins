<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CashMan|webApp login</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/font-awesome.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/cashman.css"/>
	<script type="text/javascript" src="<?php echo site_url();?>assets/js/jquery-1.11.1.min.js"></script>
	<script>
		$(document).ready(function(){	
			var i = '<i class="fa fa-exclamation-circle"></i>&nbsp;';
			checkForm();
			$('#recoverPassword').submit(function(){
				checkForm();
			});
			
			$('#resetPassword').submit(function(){
				if($('#rNewPassword').val() == '')
				{
					$('.rNewPassword').css('display','block');
					$('.rNewPassword').html(i+'<?php echo $this->lang->line('RESET_ERROR_EMPTY_NEW_PASSWORD');?>');
					return false;
					//$('#rNewPassword').focus();
				}else{
					$('.rNewPassword').css('display','none');
				}
				
				if($('#rConfirmPassword').val() == '')
				{
					$('.rConfirmPassword').css('display','block');
					$('.rConfirmPassword').html(i+'<?php echo $this->lang->line('RESET_ERROR_EMPTY_CONFIRM_PASSWORD');?>');
					return false;
				}if($('#rConfirmPassword').val() != $('#rNewPassword').val()){
					$('.rConfirmPassword').css('display','block');
					$('.rConfirmPassword').html(i+'<?php echo $this->lang->line('RESET_ERROR_WRONG_CONFIRM_PASSWORD');?>');
					return false;
				}else{
					$('.rConfirmPassword').css('display','none');
				}
				
				if($('#SecurityQuestions').val() == 0)
				{
					$('.rQuestion').css('display','block');
					$('.rQuestion').html(i+'<?php echo $this->lang->line('RESET_ERROR_EMPTY_QUESTION');?>');
					return false;
				}else{
					$('.rQuestion').css('display','none');
				}
				
				if($('#answer').val() == 0)
				{
					$('.answer').css('display','block');
					$('.answer').html(i+'<?php echo $this->lang->line('RESET_ERROR_EMPTY_ANSWER');?>');
					return false;
				}else{
					$('.answer').css('display','none');
				}
			});
			
			$('#rNewPassword').on('focusout',function(){
				if($('#rNewPassword').val() == '')
				{
					$('.rNewPassword').css('display','block');
					$('.rNewPassword').html(i+'<?php echo $this->lang->line('RESET_ERROR_EMPTY_NEW_PASSWORD');?>');
					return false;
				}else{
					$('.rNewPassword').css('display','none');
				}
			});
			
			$('#rConfirmPassword').on('focusout',function(){
			//alert($('#rConfirmPassword').val());
				if($('#rConfirmPassword').val() == '')
				{
					$('.rConfirmPassword').css('display','block');
					$('.rConfirmPassword').html(i+'<?php echo $this->lang->line('RESET_ERROR_EMPTY_CONFIRM_PASSWORD');?>');
					return false;
				}if($('#rConfirmPassword').val() != $('#rNewPassword').val()){
					$('.rConfirmPassword').css('display','block');
					$('.rConfirmPassword').html(i+'<?php echo $this->lang->line('RESET_ERROR_WRONG_CONFIRM_PASSWORD');?>');
					return false;
				}else{
					$('.rConfirmPassword').css('display','none');
				}
			});
			
			$('#SecurityQuestions').on('focusout',function(){
				if($('#SecurityQuestions').val() == 0)
				{
					$('.rQuestion').css('display','block');
					$('.rQuestion').html(i+'<?php echo $this->lang->line('RESET_ERROR_EMPTY_QUESTION');?>');
					return false;
				}else{
					$('.rQuestion').css('display','none');
				}
			});
			
			$('#answer').on('focusout',function(){
				if($('#answer').val() == 0)
				{
					$('.answer').css('display','block');
					$('.answer').html(i+'<?php echo $this->lang->line('RESET_ERROR_EMPTY_ANSWER');?>');
					return false;
				}else{
					$('.answer').css('display','none');
				}
			});
		});
		
		function checkForm()
		{
			$('#email').on('change',function(e){
				var email = $('#email').val();
				var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				if(email == '')
				{
					$('.clearMsg').css('display','block');
					$('.clearMsg').html('<i class="fa fa-exclamation-circle"></i>&nbsp;<?php echo $this->lang->line('RECOVERY_EMAIL_EMPTY_ERROR');?>');
					$('#email').focus();
				}else if(re.test(email) == false){
					$('.clearMsg').css('display','block');
					$('.clearMsg').html('<i class="fa fa-exclamation-circle"></i>&nbsp;<?php echo $this->lang->line('RECOVERY_EMAIL_VALID_ERROR');?>');
					$('#email').focus();
				}else{
					$('.clearMsg').html('');
					$('.clearMsg').css('display','none');
				}
			});
		}
		
	</script>
</head>
<body>
<header>
  <div class="container-fluid head"> <img src="<?php echo site_url();?>assets/images/logo.png" /> </div>
</header>
<section class=" home_middle grey-body">
	
	<?php
		if($task == 'password_recovery')
		{
	?>
			<div class="container-fluid">
				<div class="container">
					<div class="row login-row">
						<div class="col-sm-6 col-md-5 col-xs-12 login_with-us forgot_pwd">
							<?php echo form_open('',array('name'=>'recoverPassword','id'=>'recoverPassword'));?>
								<?php echo$this->session->flashdata('otherMessage');?>
								<h1><?php echo $this->lang->line('RECOVERY_EMAIL_TITLE');?></h1>
								<p><?php echo $this->lang->line('RECOVERY_EMAIL_DESCRIPTION');?></p>
								<span>
									<input type="email" name="email" id="email"  class="form-control" placeholder="Email"  value="<?php echo set_value('email');?>"/>
								</span>
								<br/>
								<div class="alert alert-danger clearMsg"></div>
								<div><?php echo validation_errors();?></div>
								<span>
									<input type="hidden" name="action" value="<?php echo $action;?>"/>
									<input type="submit" name="passwordRecovery" class="btn btn-lg btn-signin  btn-float" value="Get Recovery Link"/>
									<div class="cancel"><a href="<?php echo site_url();?>" ><?php echo $this->lang->line('BUTTON_CANCEL');?></a></div>
								</span>
							<? echo form_close();?>
						</div>
					</div>
				</div>
			</div>
	<?php
		}elseif($task == 'changePasswordForm'){
	?>
			<div class="col-sm-6 col-md-7 col-xs-12 login_with-us">
				<h1><?php echo $this->lang->line('RECOVERY_EMAIL_TITLE');?></h1>
				<div><?php echo validation_errors();?></div>
				<?php echo form_open('',array('name'=>'resetPassword','id'=>'resetPassword','class'=>'reset_pass'));?>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<label><?php echo $this->lang->line('RESET_PASSWORD_LABEL_NEW');?></label>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<input type="password" class="form-control" placeholder="Password" name="rNewPassword" id="rNewPassword" />
					<div class="rNewPassword alert alert-danger"></div>
				</div>
				<br/><div class="clr"></div><br/>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<label><?php echo $this->lang->line('RESET_PASSWORD_LABEL_CONFIRM');?></label>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<input type="password" class="form-control" placeholder=" Confirm Password" name="rConfirmPassword" id="rConfirmPassword"/>
					<div class="rConfirmPassword alert alert-danger"></div>
				</div><div class="clr"></div><br/>
				<!-- div class="col-md-4 col-sm-12 col-xs-12">
					<label><?php echo $this->lang->line('RESET_PASSWORD_LABEL_QUESTION');?></label>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<?php echo getSecurityQuestions();?>
					<div class="rQuestion alert alert-danger"></div>
				</div>
				<div class="clr"></div><br/>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<label><?php echo $this->lang->line('RESET_PASSWORD_LABEL_ANSWER');?></label>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<input type="text" class="form-control" placeholder="Answer Your Question" name="answer" id="answer"value="<?php echo set_value('answer');?>"/>
					<div class="answer alert alert-danger"></div>
				</div -->
				
				<div class="col-sm-12 col-md-12 col-xs-12">
					<input type="submit" name="resetPassword"class="btn btn-lg btn-signin" value="Change Password" />
				</div>
				<input type="hidden" name="action" value="<?php echo $action;?>"/>
				<input type="hidden" name="id" value="<?php echo $UserID;?>"/>
				<?php echo form_close();?>
				<div class="clr"></div><br/>
			</div>
	<?php
		}elseif($task == 'setPassword'){
	?>
			<div class="col-sm-6 col-md-7 col-xs-12 login_with-us">
				<h1><?php echo $this->lang->line('RESET_CLIENT_PASSWORD_TITLE');?></h1>
				<div><?php echo validation_errors();?></div>
				<?php echo form_open('',array('name'=>'setPassword','id'=>'setPassword','class'=>'reset_pass'));?>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<label><?php echo $this->lang->line('RESET_PASSWORD_LABEL_NEW');?></label>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<input type="password" class="form-control" placeholder="Password" name="rNewPassword" id="rNewPassword" />
					<div class="rNewPassword alert alert-danger"></div>
				</div>
				<br/><div class="clr"></div><br/>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<label><?php echo $this->lang->line('RESET_PASSWORD_LABEL_CONFIRM');?></label>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<input type="password" class="form-control" placeholder=" Confirm Password" name="rConfirmPassword" id="rConfirmPassword"/>
					<div class="rConfirmPassword alert alert-danger"></div>
				</div><div class="clr"></div><br/>
				<!-- div class="col-md-4 col-sm-12 col-xs-12">
					<label><?php //echo $this->lang->line('RESET_PASSWORD_LABEL_QUESTION');?></label>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<?php //echo getSecurityQuestions();?>
					<div class="rQuestion alert alert-danger"></div>
				</div>
				<div class="clr"></div><br/>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<label><?php //echo $this->lang->line('RESET_PASSWORD_LABEL_ANSWER');?></label>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<input type="text" class="form-control" placeholder="Answer Your Question" name="answer" id="answer"value="<?php //echo set_value('answer');?>"/>
					<div class="answer alert alert-danger"></div>
				</div -->
				
				<div class="col-sm-12 col-md-12 col-xs-12">
					<input type="submit" name="setPassword"class="btn btn-lg btn-signin" value="Change Password" />
				</div>
				<input type="hidden" name="action" value="<?php echo $action;?>"/>
				<input type="hidden" name="id" value="<?php echo $UserID;?>"/>
				<?php echo form_close();?>
				<div class="clr"></div><br/>
			</div>
	<?php
		}else{
			echo 'What you are looking for !!!!';
		}
	?>
	
</section>
<?php $this->load->view('client/footer');?>
</body>
</html>