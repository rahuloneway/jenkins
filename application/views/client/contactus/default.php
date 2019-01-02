<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	$this->load->view('client/header',array('page'=>$page,'title'=>$title));
?>
<section class="grey-body">
	<div class="container-fluid ">
		<div class="account_sum">
			<h4><?php echo $this->lang->line('LABEL_CONTACT_US');?></h4>
			<?php echo $this->session->flashdata('contactMessage');?>
			<div class="border_box " style="margin:20px 0;">
				<?php echo form_open(site_url().'save_form_contactus',array('name'=>'contactUs','id'=>'contactUs'));?>
				<div class="divdnd col-md-12">
					<div class="col-md-12 spac_inpu_text">
						<div class="col-md-4">
							<label class="pull-right"><?php echo $this->lang->line('LABEL_REASON');?></label>
						</div>
						<div class="col-md-4">
							<?php echo form_dropdown('Reason',$reason,'','class="form-control reason required" id="reason"');?>
						</div>
					</div>
					<div class="col-md-12 spac_inpu_text">
						<div class="col-md-4">
							<label class="pull-right"><?php echo $this->lang->line('LABEL_DESCRIPTION');?></label>
						</div>
						<div class="col-md-6">
							<textarea class="form-control required" rows="2" cols="10" placeholder="" name="Description"></textarea>
						</div>
					</div> 
					<div class="col-md-6 spac_inpu_text text-right">
						<button type="submit" class="btn btn-primary addMessage">
							<i class="glyphicon glyphicon-send"></i>&nbsp;<?php echo $this->lang->line('BUTTON_SEND');?>
						</button>
						<a href="<?php echo site_url();?>" class="btn btn-danger ">
							<i class="fa fa-close"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CANCEL');?> 
						</a>
					</div>
				</div>
				<div class="clr"></div>
				<?php echo form_close();?>
			</div>   
		</div>
	</div>
</section>
<div id="dialog"></div>	
<?php $this->load->view('client/footer');?>