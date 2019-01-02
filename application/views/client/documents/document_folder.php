<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php echo form_open(site_url().'clients/documents/saveFolder',array('id'=>'create-folder','name'=>'create-folder'));?>
<div class="cold-md-12">
	<div class="col-md-4">
		<label><?php echo $this->lang->line('DOCUMENT_LABEL_FOLDER_NAME');?></label>
	</div>
	<div class="col-md-8">
		<input type="text" name="FolderName" class="form-control" id="folder"/>
	</div>
	<div class="clr"></div>
	<br/>
	<div class="col-md-4">
		<label><?php echo $this->lang->line('DOCUMENT_LABEL_FOLDER_NAME');?></label>
	</div>
	<div class="col-md-8">
		<?php 
			echo form_dropdown('ParentFolder',folder('parent'),'','id="ParentFolder" class="form-control"');
		?>
	</div>
	<div class="clr"></div><br/>
</div>
<div class="modal-footer">
	<a class="btn btn-primary btn-sm createFolder">
		<?php echo $this->lang->line('DOCUMENT_LABEL_CREATE_FOLDER');?>
	</a>
	<a class="btn btn-danger btn-sm" data-dismiss="modal">
		<?php echo $this->lang->line('BUTTON_CANCEL');?>
	</a>
</div>
<?php echo form_close();?>