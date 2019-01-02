<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<section>
    <?php echo $this->session->flashdata('clientUploadError'); ?>
    <center>
        <div class="row data_opn">
            <?php echo form_open_multipart(site_url() . 'accountant/bulkclient/upload', array('id' => 'bulkuploadClients', 'name' => 'bulkuploadClients')); ?>
            <div class="col-md-3"></div>
            <div class="col-md-4">
                <div class="browse-file">
                    <input type="file" name="file" id="file" class="filestyle" data-buttonName="btn-primary" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
					<div class="alert alert-danger error-field templateName needthis" style="display:none;"><?php echo $this->lang->line('BULK_CLIENT_UPLOAD_DIALOG_TEXT'); ?></div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <button type="submit" class="btn btn-primary btn-sm spacer" id="uploadBclient">
                    <i class="glyphicon glyphicon-upload"></i> Upload            
                </button>
            </div>   
			<div class="col-md-2 col-sm-6">
                <a class="btn btn-primary bulk-client-template" href="<?php echo site_url(); ?>accountant/bulkclient/bulkclientTemplate">
                    <i class="glyphicon glyphicon-download-alt"></i>Bulk Client Template        
                </a>
            </div>	
         <div class="clearfix"/></div><br/>
        <div id="progress-div" style="display:none;"><div id="progress-bar"></div></div>
        <div id="targetLayer"></div>
        <div class="clearfix"/></div>
        <div id="loader-icon" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/LoaderIcon.gif" />
        </div>

        <?php echo form_close(); ?>
        </div>
    </center>
</section>
<div class="modal-footer">
    <div class="pull-right col-md-6">
        <a data-dismiss="modal" class="btn btn-danger btn-sm spacer" href="#">
            <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CANCEL'); ?>
        </a>
    </div>
</div>
