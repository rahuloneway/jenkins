<section>
<?php echo $this->session->flashdata('uploadError');?>
<div class="panel panel-default panel_custom">
	<?php echo form_open_multipart($form_link,array('id'=>$form_id,'name'=>$form_id));?>
	<div class="panel-body">
		<div class="col-md-4 col-sm-6 col-md-offset-2">
			<div class="browse-file">
				<input type="file" name="file" id="file" class="filestyle" data-buttonName="btn-primary" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"/>
				<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
			</div>
		</div>
		<div class="col-md-2 col-sm-6">
			<a href="#"class="btn btn-primary uploadJournal">
				<i class="glyphicon glyphicon-upload"></i><?php echo $this->lang->line('BUTTON_UPLOAD');?> 
			</a>
		</div>
		
		<div class="browse-file col-md-2 col-sm-6">
			<a href="<?php echo site_url().'clients/journals/journalTemplate'?>" class="btn btn-primary">
				<i class="glyphicon glyphicon-download-alt"></i><?php echo $this->lang->line('JOURNAL_PAGE_BUTTON_EXPENSE');?>
			</a>
		</div>		
		
	</div>
	<?php echo form_close();?>
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