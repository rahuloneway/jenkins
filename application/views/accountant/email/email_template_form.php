<?php if(isset($post)){ //print_r($post);
//echo $post['templateName'];
} ?>
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
			<h4><?php echo $this->lang->line('ADD_TEMPLATE'); ?></h4>
			<div class="clr"></div>
			<div id='errormsgdiv'>
				<?php echo $this->session->flashdata('templateDocumentError'); ?>
			</div>
			<div class="panel panel-default panel_custom">
				<section>
					<div class="row data_opn">
						<?php
						echo form_open(site_url() . 'accountant/Email/save', array('name' => 'fromEmailtemplate', 'id' => 'fromEmailtemplate'));
						?>
						<div class="col-md-12">
							<div class="col-md-2"> 
							</div>
							<div class="col-md-8">  
								<div class="col-md-6">
									<label><?php echo $this->lang->line('TEMPLATE_NAME'); ?>  :</label>
									<input type="text"  class="form-control" name="templateName" id="templateName" 
									value="<?php if(isset($post)){ echo $post['templateName'];} ?>" required="required"/>
								</div>  
								<!--<div class="col-md-6">
									<label><?php echo $this->lang->line('TEMPLATE_TYPE'); ?>  :</label>
									<select name="templateType" id="templateType" class="form-control" required="required"> 
										<option value="Bank"> Bank</option>
										<option value="Vat"> Vat</option>
									</select>
								</div>-->
								<div class="clearfix"></div><br/>
								<div class="col-md-12">
									<label><?php echo $this->lang->line('TEMPLATE_TEXT'); ?>:</label>
									<center>
										<div class="clearfix"></div>
										<textarea class="form-control description" name="templateText" id="templateText" required="required">
										</textarea>
									</center>
								</div>
							</div>
							<div class="col-md-2"> 
							</div>
							<!--<div class="col-md-3">
								<div class="article_series-box">
									<div class="panel panel-default panel_custom">
										<center>    
											<h4>
											<a href="#" class="series-3793" title="Template hint">
												Hint
											</a>
											</h4>
										</center>   
									</div>
									<ul style="float:left;list-style:none;">
										<li>First Name <br/><input type="text" value="FIRSTNAME" readonly="readonly" class="form-control"/></li>
										<li>Last Name <br/><input type="text" value="LASTNAME" readonly="readonly" class="form-control"/></li>
										<li>Company Name<br/><input type="text" value="COMPANY" readonly="readonly" class="form-control"/></li>
										<li>Email<br/><input type="text" value="EMAIL" readonly="readonly" class="form-control"/></li>
										<li>Signature<br/><input type="text" value="SIGNATURE" readonly="readonly" class="form-control"/></li>
									</ul>
								</div>
							</div>-->
							<div class="clearfix"></div>
							<br/> <br/>  
							
						</div>
						<div class="col-md-12">
							<div class="col-md-2"> 
							</div>
							<div class="col-md-8" align="right"> 
								<button type="submit" class="btn btn-primary btn-sm spacer">
								<i class="glyphicon glyphicon-floppy-disk"></i> Save </button>
								<a data-dismiss="modal" class="btn btn-danger" href="<?php echo site_url('email'); ?>">
								<i class="glyphicon glyphicon-remove-sign"></i>&nbsp;Cancel</a>
								</a>
							</div>
							<div class="col-md-2"> 
							</div>
							
						</div>
						<?php
						echo form_close();
						?>
				</section>
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
	
</script>
<style>
	.rte-autocomplete { 
		cursor:pointer; 
		height:200px; 
		overflow-y:scroll;
	}    
</style>

