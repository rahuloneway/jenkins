<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $user = $this->session->userdata('user');?>
<br/>
<?php echo form_open(site_url().'clients/salary/save_payee',array('id'=>'payeForm','name'=>'payeForm'));?>
<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="col-md-3">
		<label> Financial Year:</label>
	</div>
	<div class="col-md-3">
		<?php 
			$year = financial_year();
			$curf = currentFinancialYear();
			echo form_dropdown('newpayeefinancialyear',$year,$curf,'id = "newpayeefinancialyear" class="form-control"');
		?>
	</div>
	<div class="clr"></div>
</div>
<div class="clr"></div><br/><br/>
<div class="table-responsive">
	<table>
		<thead>
			<tr>
				<th>
					#
				</th>
				<th>
					<a href="#">Quarter </a>
				</th>
				<th>
					<a href="#"> Income Tax </a>
				</th>
				<th>
					<a href="#">NIC Employee</a>
				</th>
				<th>
					<a href="#">NIC Employer</a>
				</th>
				<th>
					<a href="#">Total £ <a>
				</th>
				<th>
					<a href="#">Payment Reference </a>
				</th>
				<th>
					<a href="#">HMRC refunds</a>
				</th>
				<th>
					<a href="#">Pay Between </a>
				</th>
			</tr>
		</thead>
		
		<tbody id="newpayeeListing">
			<?php $this->load->view('client/salary/payee_listing');?>
		</tbody>
	</table>
</div>
<div class="clr"></div><br/>
<div class="modal-footer">
	<div class="col-md-12">
		<a type="button" class="btn btn-primary btn-sm saveQuarters">
			<i class="glyphicon glyphicon-floppy-save"></i>
			<?php echo $this->lang->line('BUTTON_SAVE');?>
		</a>
		<a type="button" class="btn btn-danger btn-sm "data-dismiss="modal">
			<i class="glyphicon glyphicon-remove-sign"></i>
			<?php echo $this->lang->line('BUTTON_CANCEL');?>
		</a>
	</div>
</div>
<?php echo form_close();?>
