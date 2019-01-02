<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style>
    .selectCls {
        width: 80% !important;
    }
    #bankId {
        float: left;
        margin-right: 14px;
        width: 177px;
    }
    .btn.btn-primary.btn-sm.showmodal.ad-bnk {
        padding: 6px 10px;
    }
</style>
<section>
	<?php echo $this->session->flashdata('bankUploadError');?>
	<div class="row data_opn">
		<?php echo form_open_multipart(site_url().'clients/banks/upload',array('id'=>'bankStatements','name'=>'bankStatements'));?>
        <div class="panel panel-default panel_custom">
                <div class="panel-body row" style="padding:20px 10px">
                    <div class="col-md-5 col-sm-7 col-xs-12 padding_field">
                        <div class="wid-29">
                            <label><?php echo $this->lang->line('BANK_TABLE_COLUMN_SELECTBANK'); ?></label>
                        </div>
                        <div class="wid-70">
                            <select class="form-control" name="bankId" id="bankId" required="required">
                                <option value="">--Select Bank Name--</option>
                                <?php 
                                if(isset($getbanks)) { 
									foreach ($getbanks as $bank) {								
                                    ?>
                                    <option <?php
                                    if (isset($_SESSION['bankId'])) {
                                        if ($_SESSION['bankID'] == $bank->BID) {
                                            ?> select="selected" <?php
                                        }
                                     }?> value="<?php echo $bank->BID; ?>"><?php echo $bank->Name; ?></option>
                                        <?php
                                 
									}
								}?>
                            </select>	
                            <a class="btn btn-primary btn-sm showmodal ad-bnk" id="uploadAddBank" href="javascript:;">
                                <i class="fa fa-plus"></i>
                                Add Bank
                            </a>

                            <div class="alert alert-danger error-field"  id="bankiderror" style="display: none;">
                                <i class="glyphicon glyphicon-exclamation-sign"></i>
                                This field is required
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-6 col-xs-6 padding_field">
                    	
                        <div class="col-md-10">
                        <div class="browse-file">
                        <input type="file" name="file" id="fileTest" class="filestyle" data-buttonName="btn-primary"accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                        <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
                        </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                        <a href="#"class="btn btn-primary uploadStatement">
                        <i class="glyphicon glyphicon-upload"></i><?php echo $this->lang->line('BUTTON_UPLOAD');?> 
                        </a>
                        </div>
                        <!--<div class="browse-file col-md-3 col-sm-6">
                        <a href="<?php echo site_url().'clients/banks/template_one';?>" class="btn btn-primary">
                        <i class="glyphicon glyphicon-download-alt"></i><?php echo $this->lang->line('BANK_UPLOAD_BUTTON_ONE');?>
                        </a>
                        </div>
                        <div class="browse-file col-md-3 col-sm-6">
                        <a href="<?php echo site_url().'clients/banks/template_two';?>" class="btn btn-primary">
                        <i class="glyphicon glyphicon-download-alt"></i><?php echo $this->lang->line('BANK_UPLOAD_BUTTON_TWO');?>
                        </a>
                        </div>-->
                    </div>
                    
                </div>	
                
                
                
            </div>
        
           
       
		<?php echo form_close();?>
	</div>
</section>
<div class="modal-footer">
	<div class="pull-right col-md-6">
		<a data-dismiss="modal" class="btn btn-danger btn-sm spacer" href="#">
			<i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CANCEL');?>
		</a>
	</div>
</div>

<div class="modal fade modal-bank asdf" id="modal-bank-upload" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" id="savebanksubmodel">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"> Add Bank</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(site_url() . 'clients/banks/saveBank', array('id' => 'savebankform')); ?>
                <input type="hidden" name="uploadstatment" id="uploadstatment" value="uploadstatment" />
                <div class="border_box">
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>Bank Name:</label>
                        </div>
                        <div class="wid-70">
                            <input id="BankName" class="form-control" type="text" value="" placeholder="" name="BankName">
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>Sort Code :</label>
                        </div>
                        <div class="wid-70">
                            <input id="ShortCode" class="form-control validNumber" type="text" maxlength="6" value="" placeholder="" name="ShortCode">
                        </div>
                    </div>
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>Account Number:</label>
                        </div>
                        <div class="wid-70">
                            <input id="AccountNumber" class="form-control validNumber" type="text" maxlength="8" value="" placeholder="" name="AccountNumber">
                        </div>
                    </div>
                    <input  class="btn btn-success btn-sm spacer" id="savebank"  type="button" value="Save">
                    <div class="clr"></div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
