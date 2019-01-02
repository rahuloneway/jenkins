<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$this->load->view('accountant/header', array('page' => $page, 'title' => $title));
?>
<script>
    window.onbeforeunload = function() {
        var msg = '<?php echo $this->lang->line('BANK_UPLOAD_PAGE_UNLOAD_MESSAGE'); ?>';
        return msg;
    }
</script>
<?php
//$access = clientAccess();
//echo '<pre>';print_r($items);echo '</pre>'; die;
?>
<section class="ajax_loaded_data"></section>
<section class="grey-body ">
    <div class="container-fluid">
        <div class="account_sum">
            <?php echo $this->session->flashdata('bankUploadError'); ?>
            <div class="panel panel-default panel_custom">
                <?php
                echo form_open(site_url() . 'accountant/bulkupload/before_search', array('name' => 'bulk_beforebankSearch'));
                ?>
                <div class="panel-body row">                                   
                    <div class="col-md-4 col-sm-5 col-xs-12 padding_field ">
                        <div class="wid-30">
                            <label><?php echo $this->lang->line('BULK_UPLOAD_COMPANY_NAME'); ?></label>
                        </div>
                        <div class="wid-70">
                            <select name="companyname" class="before_cmp_nm">
                                <option value="">  Select Company  </option>
                                <?php
                                $bulksearch_before = $_SESSION['Bulk_BeforeBankSearch'];
                                $companyname = $this->cpanel->getCompanyIdfromCompanyName();
                                foreach ($companyname as $bulkcm) {
                                    ?>
                                    <option <?php if ($bulkcm['ClientID'] == $bulksearch_before['companyname']) echo 'selected=selected'; else echo ''; ?> value="<?php echo $bulkcm['ClientID']; ?>"><?php echo $bulkcm['Name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>					                   
                    <div class="col-md-2 col-sm-3 col-xs-12 text-right padding_field btn_float pull-right padding-top">
                        <button type="submit" class="btn btn_search" value="submit">
                            <i class="fa fa-search"></i><?php echo $this->lang->line('BUTTON_SEARCH'); ?>
                        </button>                    
                    </div>
                </div>
                <?php
                echo form_close();
                ?>
            </div>


            <?php echo form_open_multipart(site_url() . 'accountant/bulkupload/save_statements', array('id' => 'updateBulkStatements')); ?>
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" /> 
            <div class="table-responsive">
                <table>
                    <thead>

                        <tr class="salary-table">
                            <th>
                                #
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_DATE'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_TYPE'); ?>
                            </th>
                            <th>
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
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_CATEGORY'); ?>
                            </th>
                            <!--th>
                            <?php //echo $this->lang->line('BANK_TABLE_COLUMN_ACTIONS'); ?>
                            </th-->
                        </tr>
                    </thead>
                    <tbody id="statement-listing">
                        <?php $this->load->view('accountant/bulkupload/bulk_statement_listing', $items); ?>
                    </tbody>
                </table>
            </div>
            <br/><br/>
            <div class="col-md-12">
                <div class="pull-right">
                    <div class="col-md-4 col-sm-5 col-xs-12 padding_field ">
                        <div class="wid-30">                    
                            <div id="bulk-loader"></div>
                        </div>
                    </div>
                </div>
                <div class="pull-right">
                    <div class="col-md-12 col-sm-5 col-xs-12 padding_field ">
                        <button type="button" class="btn btn-primary btn-sm spacer bulk_finish" id="uploadTerm">
                            <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_SAVE_AND_FINISH'); ?>
                        </button>
                        <a data-dismiss="modal" class="btn btn-danger btn-sm spacer bulk-cancel-upload" href="#">
                            <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CANCEL'); ?>
                        </a>

                    </div>
                </div>

            </div>
            <?php echo form_close(); ?>
            <br/>
            <div class="clr"></div>
        </div>
    </div>
</section>
<div class="modal fade modal-statements" id="modal-statements"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
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
<div class="modal fade modal-view-items" id="modal-view-items"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
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
<div id="dialog"></div>	
<?php $this->load->view('accountant/footer'); ?>
