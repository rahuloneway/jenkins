<?php  //$bulkEmailUserDetails = $this->session->userdata('bulkEmailUserDetails');  print_r($bulkEmailUserDetails); ?>
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    $this->load->view('accountant/header', array('page' => $page, 'title' => $title));
$Days_relation_with = $this->session->userdata('Days_relation_with');
$Status = $this->session->userdata('Status');
if (!empty($Days_relation_with) || !empty($Status)) {
    $Days_relation_with = $this->session->userdata('Days_relation_with');
    $Status = $this->session->userdata('Status');
} else {
    $Days_relation_with = $_POST['Days_relation_with'];
    $Status = $_POST['Status'];
}
?>
<section class="grey-body">
	<div class="container-fluid ">
		<div class="account_sum border_box_trial">
			<h4><?php echo $this->lang->line('EMAIL_TEMPLATE'); ?></h4>
			<div class="clr"></div>
			<?php echo $this->session->flashdata('templateDocumentError'); ?>
			<div class="panel panel-default panel_custom">
				<?php echo form_open_multipart(site_url() . 'accountant/email/sendMail', array('id' => 'frmbulkemail', 'name' => 'frmbulkemail')); ?>
				<section>
					<div class="row data_opn">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-2">
								</div>
								<div class="col-md-8">							
									<div class="col-md-4">
										<label><?php echo $this->lang->line('SELECT_TEMPLATE'); ?>  :</label>
										<select class="form-control" id="template_type" name="template_type">
											<option selected="selected" value="0">--Select Template--</option>
											<?php
											if(isset($templatename)){ 
												foreach ($templatename as $key => $value) { 
													echo '<option value="' . $value['Id'] . '">' . $value['Template_Name'] . '</option>';
												}
											}
											?>
										</select>
									</div>
									<div class="col-md-4">
										<label><?php echo $this->lang->line('ADD_TEMPLATE'); ?>  :</label>
										<button type="button" class="btn btn-primary  " id="showtempnamediv">
											<i class="fa fa-upload"></i> Add Template </button>
									</div>
									<div class="col-md-4" id="tempnamediv" style="display:none">
										<label><?php echo $this->lang->line('TEMPLATE_NAME'); ?>  :</label>
										<input type="text"  class="form-control" name="templateName" 
										value="<?php if(isset($post)){ echo $post['templateName'];} ?>" id="templateName" />
									</div> 
								</div>
								<div class="col-md-2">
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-2">
								</div>
								<div class="col-md-8">
									<label><?php echo $this->lang->line('SUBJECT'); ?>  :</label>
									<div class="clearfix"></div>
									<input type="text" value="" class="form-control" name="subject" subject/>
								</div>
								<div class="col-md-2">
								</div>
							</div>	
							</br>							
							<div class="row">
								<div class="col-md-2">
								</div>
								<div class="col-md-8">									
									<input type="hidden" id="mailType" name="mailType" value="<?php echo $mail_type ?>"/>
									<textarea class="form-control description" name="templateText" id="templateText">
									</textarea>					
								</div>
								<div class="col-md-2">
								</div>
							</div>
							</br>
							<div class="row">
								<div class="col-md-7">
								</div>
								<div class="col-md-3" align="right">
									<span id="saveandsend" style="display:none;">
										<input type="submit" class="btn btn-primary  btn-sm spacer" name="savesend" value="Save & Send" id="savesend">
									</span>
									<input type="submit" class="btn btn-primary  btn-sm spacer" name="send" value="Send" id="sendmail">
									  
									<a data-dismiss="modal" class="btn btn-danger" href="<?php echo site_url('email'); ?>">
									<i class="glyphicon glyphicon-remove-sign"></i>&nbsp;Cancel</a>				
								</div>
								<div class="col-md-2">
								</div>
							</div>
							
					</div>
				</section>
				<?php echo form_close(); ?>
			</div>             
		</div>          
	</div>        
</section>		
				
<?php $this->load->view('accountant/footer'); ?>
<?php echo '<script type="text/javascript" src="' . site_url() . 'assets/js/tinymce/tinymce.min.js"></script>' ?>
<script>	
	var site_url = '<?php echo site_url();?>';
	tinymce.init({
		selector: 'textarea',
		height: 300,
		theme: 'modern',
		statusbar: false,
		external_plugins: {
			'mention' : site_url+'assets/js/tinymce/plugins/mention/plugin.js'
		},
		content_css: site_url+'assets/js/tinymce/css/rte-content.css',
		//skin_url: 'http://stevendevooght.github.io/tinyMCE-mention/stylesheets/tinymce/skins/light',
		mentions: {
			source: [
			{ name: 'FIRSTNAME'},
			{ name: 'LASTNAME'},
			{ name: 'EMAIL'},
			{ name: 'COMPANY_NAME'},
			{ name: 'COMPANY_REG_NO'},					
			{ name: 'VAT_REG_NO'},
			{ name: 'VAT_QUARTER'},
			{ name: 'VAT_QUARTER_DETAILS'},
			{ name: 'BANK_STATEMENT_TO'},
			{ name: 'BANK_STATEMENT_FROM'},
			//{ name: 'CURRENT_CH_ACC_DUE_DATE'},
			//{ name: 'PREVIOUS_CH_ACC_DUE_DATE'},
			//{ name: 'CURRENT_ANNUAL_RETURN_DUE_DATE'},
			//{ name: 'PREVIOUS_ANNUAL_RETURN_DUE_DATE'},
			//{ name: 'CURRENT_VAT_QUARTERS_START_DATE'},
			//{ name: 'PREVIOUS_VAT_QUARTERS_END_DATE'},
			//{ name: 'SIGNATURE'},			
			{ name: 'SITE_TITLE'},
			{ name: 'COPY_RIGHT_TEXT'},
			]
		}
	});
	
	$(document).on('click', '#showtempnamediv', function(e) {		
		$('#tempnamediv').show();
		$("#saveandsend").show();
	});
	
</script>
<style>
	.rte-autocomplete { 
		cursor:pointer; 
		height:200px; 
		overflow-y:scroll;
	}    
</style>
