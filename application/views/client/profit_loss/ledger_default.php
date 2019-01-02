<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	$this->load->view('client/header',array('page'=>$page,'title'=>$title));
	$user = $this->session->userdata('user');
	if(isset($user['AccountantAccess']))
	{
		$access = 1;
	}else{
		$access = 0;
	}
	
	$TBYears = getTBYear();
	// prd( $TBYears );
	$TBYear = $TBYears[0]["value"];
	
?>
<section class="grey-body">
	<div class="container-fluid">
		<div class="account_sum">
			<!-- h4>List of Invoices</h4 -->
			<?php 
				echo form_open(site_url().'ledger_accounts',array('name'=>"ledgerFilters", "id"=>"ledgerFilters"));
			?>
			<div class="row">
				<div class="col-md-5 col-sm-12 col-xs-12 invoice_field  top_space">
					<div class="width-35  col-sm-12 col-xs-12 invoice_field ">
						<h4>Ledger Account:</h4>
					</div>
					<div class="wid-60 col-sm-12 col-xs-12 invoice_field ">
						<?php 
							echo form_dropdown('JournalCategories',pl_categories(), $this->encrypt->decode( $TBcatID ) ,'class="form-control JournalCategories " id="JournalCategories" ');
						?>
					</div>
					
					
				</div>
				<div class="col-md-3 col-sm-12 col-xs-12 invoice_field pull-right top_space">
					<a href="<?php echo site_url().'profit'; ?>" class="btn  btn_grey pull-right">
						<i class="glyphicon glyphicon-chevron-left"></i>&nbsp;Back to Profit and loss
					</a>
				</div>
			</div>
			<div class="panel panel-default panel_custom panel-inv top_space_20">
				<div class="panel-body row">
					<!-- div class="col-md-3 col-sm-12 col-xs-12 invoice_field">
						<div class="wid-30">
							<label>Type :</label>
						</div>
						<div class="wid-70">
							<?php echo $sourceDD; ?>
						</div>
						<div class="clr">
						</div>
					</div -->
					<div class="col-md-3 col-sm-12 col-xs-12 invoice_field">
						<div class="wid-30">
							<label>Category :</label>
						</div>
						<div class="wid-70">
							<?php echo $sourceDD; ?>
						</div>
						<div class="clr">
						</div>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12 invoice_field">
						<div class="wid-50">
							<label>Company Accounting Year :</label>
						</div>
						<div class="wid-50">
							<?php							
							$TBDDYears = TBDropDownYears();
							for( $i=$TBDDYears["end"];$i >= $TBDDYears["start"]; $i-- ){
								$arrYear = TBListYearsDD( $i );
								$arrYears[$arrYear["value"]] = $arrYear["title"];
								unset($arrYear);
							}
							echo genericList("TBYear", $arrYears, $TBYear , "TBYear");
						?>
						</div>
						<div class="clr">
						</div>
					</div>
					
					<!-- div class="col-md-4 col-sm-12 col-xs-12 invoice_field">
							<div class="wid-20 text-center"><label>Date:</label> </div>
							<div class="wid-80 date_input">
								<input type="text" value="" name="sCreatedStart" id="sCreatedStart" placeholder="Start" class="form-control datepicker">
								<span class="mid-lbl">-to-</span>
								<input type="text" value="" placeholder="End" name="sCreatedEnd" id="sCreatedEnd" class="form-control datepicker">
							</div>
							<div class="clr"></div>
					</div -->
				
					<div class="col-md-2 col-sm-12 col-xs-12 padding_field btn_float pull-right text-center">
						<input type="hidden" name="filter" value="search" pmbx_context="C468455F-7556-43AB-86AE-EB0651A47FF5">
						<button type="submit" class="btn btn_search" value="submit">
						<i class="fa fa-search"></i>Search </button>
						<a href="<?php echo site_url().'clean' ;?>" type="button" class="btn btn_search reset">
							<span class="glyphicon glyphicon-refresh"></span>
							Reset 
						</a>
						<!--div class="wid-20">
							 &nbsp;
						</div-->
						<div class="clr">
						</div>
					</div>
				</div>
			</div>
			<?php echo form_close();?>
			<!-- Load the listing view -->
			<?php $this->load->view('client/trial_balance/ledger_listing'); ?> 
			
			<div class="clr"></div>
		</div>
	</div>
	<div class="modal fade modal-ledger" id="modal-ledger" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE');?></span>
					</button>
					<h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('CLIENT_INVOICE_NEW_INVOICE_TITLE');?></h4>
				</div>
				<div class="modal-body"></div>
				<div class="clearfix"></div>
				<br/>
				<!-- div class="modal-footer">
					<a href="#" class="btn btn-primary btn-sm spacer" data-dismiss="modal">
						<?php echo $this->lang->line('CLIENT_CLOSE_BUTTON');?>
					</a>
				</div -->
			</div>
		</div>
	</div>
	<div id="dialog" style="display:none;"></div>
</section>
<div id="dialog"></div>	
<?php $this->load->view('client/footer');?>