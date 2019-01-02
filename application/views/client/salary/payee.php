<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $sn = ($this->uri->segment(4) == '')?1:$this->uri->segment(4)+1;?>
<?php $user = $this->session->userdata('user');?>
<?php 
	if(isset($user['AccountantAccess']))
	{
		$access = 1;
	}else{
		$access = 0;
	}
?>
	<div class="clear"></div>
	
	<div class="panel panel-default panel_custom panel-inv">
		<div class="panel-body row">
			<div class="col-md-4 col-md-offset-4 col-sm-12 col-xs-12">
				<div class="wid-30">
					<label> Financial Year:</label>
				</div>
				<div class="wid-70 date_input">
					<?php 
						$curf = currentFinancialYear();						
						$year = financial_year();
						unset($year[0]);
						echo form_dropdown('payeefinancialyear',$year,$curf,'id = "payeefinancialyear" class="form-control"');
					?>
				</div>
				<div class="clr"></div>
			</div>
		</div>
	</div>
	<div class="row padding-for-space_btn">
		<?php if($access == 1):?>
		<div class="col-md-4">
			<a class="btn btn-inverse addPayee" type="button" href="#" data-target="">
				<span class="glyphicon glyphicon-plus"> </span>Add Payee Detail
			</a>
		<!--/div>
		<div class="col-md-2 pull-left text-right operation-btns" -->
			<?php 
				if(count($payee) > 0)
				{
					$class = "";
				}else{
					$class = "hide";
				}
			?>
			<a class=" btn btn-success editPayee editnoshow <?php echo $class;?>" style="font-size: 16px;padding: 4px 12px;">
				<i class="glyphicon glyphicon-edit"></i>Edit payee details
			</a>
			
		</div>
		<?php endif;?>
	</div>
	<div class="table-responsive">
		<table>
			<thead>
				<tr class="salary-table">
					<th>
						#
					</th>
					<th>
						Quarter
					</th>
					<th>
						Income Tax
					</th>
					<th>
						NIC Employee
					</th>
					<th>
						NIC Employer
					</th>
					<th style="background-color: #2685e1;color: #fff;border-radius: initial;">
						Total
					</th>
					<th>
						Payment Reference
					</th>
					<th>
						HMRC refunds
					</th>
					<th>
						Quarter
					</th>
					<?php if($access == 1):?>
					<th>
						Actions
					</th>
					<?php else:?>
					<th>
						Status
					</th>
					<?php endif;?>
					<th>
						Paid Date
					</th>
				</tr>
			</thead>
			<tbody id="payeeListing">
				<?php $this->load->view('client/salary/payee_list');?>
			</tbody>
		</table>
	</div>
	<div class="row padding-for-space_btn">
		<?php if($access == 1):?>
		<div class="col-md-12">
			<a class="btn btn-inverse addPayee" type="button" href="#" data-target="">
				<span class="glyphicon glyphicon-plus"> </span>Add Payee Detail
			</a>
		<!--/div>
		<div class="col-md-2 pull-right text-right operation-btns"-->
			<?php if(count($payee) > 0):?>
			<a class=" btn btn-success editPayee editnoshow" style="font-size: 16px;padding: 4px 12px;">
				<i class="glyphicon glyphicon-edit"></i>Edit payee details
			</a>
			<?php endif;?>
		</div>
		<?php endif;?>
	</div>
</div>
<div class="modal fade modal-payee" id="modal-invoice"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE');?></span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					<?php echo $this->lang->line('PAYEE_NEW_DETAIL_TITLE');?>
				</h4>
			</div>
			<div class="modal-body">
				<?php ?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
