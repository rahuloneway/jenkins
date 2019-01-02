<section>
<?php echo $this->session->flashdata('uploadError');?>
<?php
	if($form_type == 'credit')
	{
		$uploadClass = 'uploadCredit';
	}else{
		$uploadClass = 'uploadExpense';
	}
?>
<div class="panel panel-default panel_custom">
	<?php echo form_open_multipart($form_link,array('id'=>$form_id,'name'=>$form_id));?>
	<div class="panel-body">
		<div class="col-md-4 col-sm-6 col-md-offset-2">
			<div class="browse-file">
				<input type="file" name="file" id="file" class="filestyle" data-buttonName="btn-primary"accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"/>
				<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
			</div>
		</div>
		<div class="col-md-2 col-sm-6">
			<a href="#"class="btn btn-primary <?php echo $uploadClass;?>">
				<i class="glyphicon glyphicon-upload"></i><?php echo $this->lang->line('BUTTON_UPLOAD');?> 
			</a>
		</div>
		<?php if($form_type == 'expense'):?>
		<div class="browse-file col-md-2 col-sm-6">
			<a href="<?php echo site_url().'clients/expenses/expenseTemplate'?>" class="btn btn-primary">
				<i class="glyphicon glyphicon-download-alt"></i><?php echo $this->lang->line('EXPENSE_PAGE_BUTTON_EXPENSE');?>
			</a>
		</div>
		<?php elseif($form_type == 'credit'):?>
		<div class="browse-file col-md-2 col-sm-6">
			<a href="<?php echo site_url().'clients/expenses/credit_template';?>" class="btn btn-primary">
				<i class="glyphicon glyphicon-download-alt"></i><?php echo $this->lang->line('EXPENSE_PAGE_BUTTON_CREDIT');?>
			</a>
		</div>
		<?php endif;?>
	</div>
	<?php echo form_close(); ?>
</div>
</section>
<br/><br/>
<div class="modal-footer">
	<div class="pull-right col-md-6">
		<a data-dismiss="modal" class="btn btn-danger btn-sm spacer" href="#">
			<i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CANCEL');?>
		</a>
	</div>
</div>