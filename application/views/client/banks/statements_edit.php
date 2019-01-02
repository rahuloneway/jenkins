<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('client/header', array('page' => $page, 'title' => $title));
?>
<script>
    window.onbeforeunload = function () {
        var msg = '<?php echo $this->lang->line('BANK_UPLOAD_PAGE_UNLOAD_MESSAGE'); ?>';
        return msg;
    }
</script>
<?php
$access = clientAccess();
//echo '<pre>';print_r($items);echo '</pre>';
?>
<section class="ajax_loaded_data"></section>
<section class="grey-body ">
    <div class="container-fluid">
        <div class="account_sum">
            <?php echo $this->session->flashdata('bankUploadError'); ?>
            <?php
            if (isset($_SESSION['bankMessage'])) {
                echo $_SESSION['bankMessage'];
            }
            ?>
            <?php echo form_open(site_url() . 'clients/banks/save_statements', array('id' => 'updateStatements')); ?>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row">
                    <div class="col-md-6 col-sm-7 col-xs-12 padding_field">
                        <div class="wid-35">
                            <label><?php echo $this->lang->line('BANK_TABLE_COLUMN_SELECTBANK'); ?></label>
                        </div>
                        <div class="wid-50"> 
                            <select class="form-control required" name="bankId" id="bankId">
                                <option value="">--Select Bank Name--</option>
                                <?php 
                                if(isset($getbanks)) { 
									foreach ($getbanks as $bank) { ?>
                                    <option 
										<?php if (isset($_SESSION['bankID'])) { if ($_SESSION['bankID'] == $bank->BID) {?> selected="selected" <?php  } }?> 
                                    	value="<?php echo $bank->BID; ?>"><?php echo $bank->Name; ?>
                                    </option>
                                        <?php                                 
									}
								}?>
                            </select>	
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary btn-sm showmodal ad-bnk" href="#">
                                <i class="fa fa-plus"></i>
                                Add Bank
                            </a>
                            <div class="alert alert-danger error-field"  id="bankiderror" style="display: none;">
                                <i class="glyphicon glyphicon-exclamation-sign"></i>
                                This field is required
                            </div>
                        </div>

                    </div>
                </div>	
            </div>
            <div class="table-responsive">
                <table id="statementsListingTable">
                    <thead>
                        <tr class="salary-table">
                            <th>
                                #
                            </th>
                            <th width="10%">
                                <?php  echo $this->lang->line('BANK_TABLE_COLUMN_DATE'); ?>
                            </th>
                            <th width="10%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_TYPE'); ?>
                            </th>
                            <th width="30%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_DESCRIPTION'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_OUT'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_IN'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_BALANCE'); ?>
                            </th>                            
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_MAIN_CATEGORY'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_CATEGORY'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_ACTIONS'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="statement-listing">
                        <?php $this->load->view('client/banks/statement_listing', $items); ?>
                    </tbody>
                </table>
            </div>
            <br/><br/>
            <div class="col-md-12">
                <?php if ($page1 == 'addmanual') { ?>
                    <div class="pull-left">
                        <a class="btn btn-success btn-sm addNewRow" href="javascript:;">
                            <i class="glyphicon glyphicon-plus"></i>&nbsp;<?php echo $this->lang->line('BUTTON_ADD_NEW_ROW'); ?>
                        </a>
                    </div>	
                <?php } ?>	
                <div class="pull-right">
                    <a class="btn btn-success btn-sm finish" href="#">
                        <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_SAVE_AND_FINISH'); ?>
                    </a>
                    <?php
                    if ($page1 == 'addmanual') {
                        $class = "cancel-manaualuoloadstatements";
                    } else {
                        $class = "cancel-upload";
                    }
                    ?>
                    <a data-dismiss="modal" class="btn btn-danger btn-sm spacer <?php echo $class; ?>" href="#">
                        <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CANCEL'); ?>
                    </a>
                </div>
            </div>
            <?php echo form_close(); ?>
            <br/>
            <div class="clr"></div>
        </div>
    </div>    
</section>
<div id="categoriesDropDown" style="display:none" disabled="disabled">
	<?php echo exCategories('BANK', "CategoryDrop[]", '', 'class="form-control sm-width-box newCategoryDrop"') ?>
</div>
<div class="modal fade modal-statements" id="modal-statements" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('CLIENT_INVOICE_NEW_EXPENSE'); ?></h4>
            </div>
            <div class="modal-body"></div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="modal fade modal-view-items" id="modal-view-items" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="modal fade modal-bank" id="modal-bank" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"> Add Bank</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open(site_url() . 'clients/banks/saveBank', array('id' => 'savebankform')); ?>
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
</div>
<div id="dialog"></div>	

<div class="modal fade modal-customer-form" id="modal-customer-form" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width:50%; height: auto; max-height: 100%;">
        <form id="frm-customers" name="frm-customers" method="POST" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body"></div>
                <div class="clearfix"></div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <a id="create-customers" class="btn btn-success btn-sm spacer" href="#">
                        </a>
                        <a href="#" class="btn btn-danger " data-dismiss="modal">
                            <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;Close</a>
                    </div>
                    <div class="pull-right">

                    </div>
                </div>
            </div>
        </form>

    </div>
    <div id="dialog"></div>	
</div>

<?php $this->load->view('client/footer'); ?>
<style>
    #bankId {
        float: left;
        margin-right: 14px;
        width: 177px;
    }
    .btn.btn-primary.btn-sm.showmodal.ad-bnk {
        padding: 6px 10px;
    }
</style>
