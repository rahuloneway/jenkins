<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('accountant/header',array('page'=>$page,'title'=>$title));?>
<?php
	if(count($item) <= 0)
	{
		$item = array(
			'ID'			=>	'',
			'FirstName'		=>	'',
			'LastName'		=>	'',
			'ContactNo'		=>	'',
			'Email'			=>	'',
			'ContactNo'		=>	'',
			'Status'		=>	0,
			'Activation'	=>	0
		);
		$item['Params'] = array(
			'Salutation'		=>	'',
			'DOB'				=>	'',
			'ImageLink'			=>	'',
			'DigitalSignature'	=>	'',
			'EmploymentLevel'	=>	'',
		);
		
	}
	
	if($action == 'edit')
	{
		$style = "style='display:block;'";
	}else{
		$style = "";
	}
	
	//echo '<pre>';print_r($item);echo '</pre>';die;
	/* Check if logged in user is Director or not */
	
	if(categoryName($item['Params']['EmploymentLevel']) != 'Director')
	{
		$sig_class  = "hide";
	}else{
		$sig_class  = "";
	}
	
	
	$name = explode('/',$item['Params']['ImageLink']);
	$name = end($name);
	if(!empty($name))
	{
		if(!file_exists('assets/uploads/signatures/'.$name))
		{
			$link = 'assets/images/default.jpg';
		}else{
			$link = $item['Params']['ImageLink'];
		}
	}else{
		$link = 'assets/images/default.jpg';
	}
	$user_level = $this->session->userdata('user');
?>
<?php
	echo form_open_multipart($form_link,array('id'=>$form_id));
?>
<section class="grey-body">
	<div class="container-fluid ">
		<div class="account_sum">
			<h4><?php echo $form_title;?></h4>
			<br/>
			<?php echo $this->session->flashdata('accountantError');?>
			<div class="tab-content add-accountant">
				<fieldset class="margin-box">
					<legend>Accountant Detail</legend>
					<br/>
					<div class="col-md-5 spc_below">
						<div class="wid-30">
							<label>Title:</label>
						</div>
						<div class="wid-70">
							<?php echo salutationList('salutation',$item['Params']['EmploymentLevel']);?>
						</div>
					</div>
					<div class="clr"></div>
					<div class="col-md-5 spc_below">
						<div class="wid-30">
							<label>First Name :</label>
						</div>
						<div class="wid-70">
							<input type="text" placeholder="" class="form-control required" name="FirstName" value="<?php echo $item['FirstName'];?>"/>
						</div>
					</div>
					<div class="col-md-5 col-md-offset-2 spc_below">
						<div class="wid-30">
							<label>Last Name :</label>
						</div>
						<div class="wid-70">
							<input type="text" placeholder="" class="form-control required" name="LastName" value="<?php echo $item['LastName'];?>"/>
						</div>
					</div>
					<div class="col-md-5 spc_below">
						<div class="wid-30">
							<label>Email :</label>
						</div>
						<div class="wid-70">
							<input type="email" placeholder="" class="form-control required email" name="Email"value="<?php echo $item['Email'];?>"/>
						</div>
					</div>
					<div class="col-md-5 col-md-offset-2 spc_below">
						<div class="wid-30">
							<label>Date Of Birth:</label>
						</div>
						<div class="wid-70">
							<input type="text" placeholder="" class="form-control datepicker" id="DOB" name="DOB"value="<?php echo cDate($item['Params']['DOB']);?>"/>
						</div>
					</div>
					<div class="clr"></div>
					<div class="col-md-5 spc_below">
						<div class="wid-30">
							<label>Contact No:</label>
						</div>
						<div class="wid-70">
							<input type="text" placeholder="" class="form-control validNumber ContactNumber" name="ContactNumber" value="<?php echo $item['ContactNo'];?>" maxlength="11"/>
						</div>
					</div>
					<br/>
				</fieldset>
				<?php 
				if($user_level["UserParams"]["EmploymentLevel"] == 86)
				{
					?>
					<fieldset class="margin-box">
						<legend>Access</legend>
						<br/>
						<div class="row">
							<div class="col-md-5 spc_below">
								<div class="wid-30">
									<label>Employment level :</label>
								</div>
								<div class="wid-70">
									<?php echo exCategories('EMPL',"EmploymentLevel",$item['Params']['EmploymentLevel'],'class="form-control"')?>
								</div>
							</div>
							<?php if($action == 'edit' && $item['Activation'] == 1):?>
							<?php
								if($item['Status'])
								{
									$checked = 'checked';
								}else{
									$checked = '';
								}
							?>
							<div class="col-md-7 spc_below">
								<div class="wid-30">
									<label>Active:</label>
								</div>
								<div class=" wid-70">
									<div class="onoffswitch">
										<input type="checkbox" name="Status" class="onoffswitch-checkbox" id="myonoffswitch" <?php echo $checked;?>/>
										<label class="onoffswitch-label" for="myonoffswitch">
											<span class="onoffswitch-inner"></span>
											<span class="onoffswitch-switch"></span>
										</label>
									</div>
								</div>
							</div>
							<?php endif;?>
						</div>
					</fieldset>
					<?php
				}
				?>
				
				<fieldset class="margin-box signature <?php echo $sig_class;?>">
					<legend>Signature</legend>
					<br/>
					<div class="col-md-5 spc_below">
						<div class="browse-file">
							<input type="file" name="file" id="file" class="filestyle" data-buttonName="btn-primary"/>
							<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
						</div>
						<br/>
						<?php
							echo $this->lang->line('CASHMAN_SIGNATURE_IMAGE_REQUIREMENT');
						?>
					</div>
					<div class="col-md-7">
						<div class="wid-30">
							<label>Signature :</label>
						</div>
						<div class="wid-70">
							<textarea cols="40" rows="1"class="form-control" name="DigitalSignature"><?php echo $item['Params']['DigitalSignature'];?></textarea>
						</div>
					</div>
					<div class="clr"></div>
					<div class="col-md-12 showImage" <?php echo $style;?>>
						<img src="<?php echo site_url().$link;?>" id="imgPath" width="120px" height="60px" class="thumbnail"/>
						
					</div>
					<div class="clr"></div>
					<br/>
				</fieldset>
				<div class="clr"></div>
				<div class="col-md-12">
					<div class="pull-right">
						<?php if($action == 'add'):?>
						<a class="btn btn-primary btn-sm spacer saveAccountant" href="#">
							<i class="glyphicon glyphicon-floppy-save"></i>&nbsp;Save
						</a>
						
						<a class="btn btn-success btn-sm spacer createAccountant" href="#">
							<i class="fa fa-file-text"></i>&nbsp;Create
						</a>
						
						<?php else:?>
						<a class="btn btn-primary btn-sm spacer updateAccountant" href="#">
							<i class="glyphicon glyphicon-floppy-save"></i>&nbsp;Update
						</a>
						<?php if($item['Activation'] != 1):?>
						<a class="btn btn-success btn-sm spacer upcreateAccountant" href="#">
							<i class="fa fa-file-text"></i>&nbsp;Create
						</a>
						<?php endif;?>
						<?php endif;?>
						<a href="<?php echo site_url().'accountants';?>" data-dismiss="modal" class="btn btn-danger btn-sm spacer">
							<i class="glyphicon glyphicon-remove-sign"></i>&nbsp;Cancel
						</a>
					</div>
				</div>
				<div class="clr"></div>
			</div>
		</div>
	</div>
</section>
<input type="hidden" name="task" id="task" value="<?php echo $task;?>"/>
<input type="hidden" name="ID" id="id" value="<?php echo $this->encrypt->encode($item['ID']);?>"/>
<?php if($action == 'edit'):?>
<?php if(!empty($item['Params']['ImageLink'])):?>
<input type="hidden" name="image_link" value="<?php echo $link;?>"/>
<?php else:?>
<input type="hidden" name="image_link" value=""/>
<?php endif;?>
<?php endif;?>
<?php
	echo form_close();
?>
<div id="dialog"></div>
<?php $this->load->view('accountant/footer');?>