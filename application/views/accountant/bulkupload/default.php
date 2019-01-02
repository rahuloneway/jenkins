<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('accountant/header', array('page' => $page, 'title' => $title)); 
//$access = clientAccess();
//echo "<pre>"; print_r($items); echo "</pre>";
if (empty($pagination)) {
    // if ($access == 0) {
        // $view = 0;
    // } else {
        // $view = 1;
    // }
} else {
    $view = 1;
}

$search = $this->session->userdata('Bulk_BankSearch');

if ($search == '') {
    $search = array(
        'companyname' => '',
        'client_name' => '',
        'StartDate' => '',
        'EndDate' => '',       
        'FinancialYear' => 0
    );
}
$display_button = (count($items) == 0) ? 0 : 1;
// $asc_order_value = array(
    // 'SORT_BY_CATEGORY' => 's.Category ASC'
// );
$order = $this->session->userdata('BankSortingOrder');
$TBYears = getTBYear();
$TBYear = 0;
$TBPrevYear = $TBYears[1]["value"];

?>
<style>
.date_input41 input.form-control {
    max-width: 160px;
  
}
.alerts.alert-dangers {
    float: left;
    font-size: 10px;
    height: 100px;
    width: 1270px;
    word-wrap: break-word;
}
</style>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum">
            <h4><?php echo $this->lang->line('BULK_BANK_PAGE_LABEL_TITLE'); ?></h4>
            <?php echo $this->session->flashdata('bankError'); ?>
            <div class="clearfix"></div>
            <div class="panel panel-default panel_custom">
                <?php
               echo form_open(site_url() . 'accountant/bulkupload/search', array('name' => 'bulk_bankSearch'));
                ?>
                <div class="panel-body row">
                    <div class="col-md-4s col-sm-5 col-xs-12 padding_field">
                        <div class="wid-30">
                            <label><?php echo $this->lang->line('BULK_UPLOAD_CLIENT_NAME'); ?></label>
                        </div>
                        <div class="wid-70">
                            <input type="text" placeholder="Client Name" name="client_name" class="form-control input-type input_100percent" value="<?php echo $search['client_name']; ?>" id="client_name"/>
                        </div>
                    </div>                   
                    <div class="col-md-4s col-sm-5 col-xs-12 padding_field ">
                        <div class="wid-30">
                            <label><?php echo $this->lang->line('BULK_UPLOAD_COMPANY_NAME'); ?></label>
                        </div>
                        <div class="wid-70">
                             <input type="text" placeholder="Company Name" name="companyname" class="form-control input-type input_100percent" value="<?php echo $search['companyname']; ?>" id="companyname"/>
                        </div>
                    </div>
					 <div class="col-md-4s col-sm-5 col-xs-12 padding_field padding-top">
                        <div class="wid-30">
                            <label><?php echo $this->lang->line('BANK_PAGE_LABEL_DATE'); ?></label>
                        </div>
                        <div class="wid-70 date_input41">
                            <input type="text" placeholder="Start" name="StartDate" class="form-control sDatepicker input_100percent" style="float:left;"value="<?php echo $search['StartDate']; ?>"/>

                            <span style="float:left; padding:4px 5px;" class="mid-lbl">-to-</span>
                            <input type="text" name="EndDate" placeholder="End" class="form-control sDatepicker input_100percent" style="float:left;"value="<?php echo $search['EndDate']; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-4s col-sm-5 col-xs-12 padding_field padding-top">
                        <div class="wid-30">
                            <label><?php echo $this->lang->line('BANK_PAGE_LABEL_FINANCIAL_YEAR'); ?></label>
                        </div>
                        <div class="wid-70">
                            <?php
                             $TBDDYears = @Bulk_TBDropDownYears();							
                            for ($i = $TBDDYears["end"]; $i >= $TBDDYears["start"]; $i--) {
                                $arrYear = TBListYearsDD($i);
                                $arrYears[$arrYear["value"]] = $arrYear["title"];
                                unset($arrYear);
                            }

                            $arrYears[0] = '-- Select Year --';
                            asort($arrYears);
                            echo genericList("FinancialYear", $arrYears, $search['FinancialYear'], "TBYear");
                            ?>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-3 col-xs-12 text-right padding_field btn_float pull-right padding-top">
                        <button type="submit" class="btn btn_search bulkbtn_trigger" value="submit">
                            <i class="fa fa-search"></i><?php echo $this->lang->line('BUTTON_SEARCH'); ?>
                        </button>
                        <a href="#" type="button" class="btn btn_search bulk_reset">
                            <span class="glyphicon glyphicon-refresh"></span><?php echo $this->lang->line('BUTTON_RESET'); ?>
                        </a>
                    </div>
                </div>
                <?php
                echo form_close();
                ?>
            </div>
            <?php //if ($view): ?>
                <?php
                echo form_open(site_url() . 'accountant/bulkupload/delete_statements', array('id' => 'bulkstatementDelete'));
                ?>
               <div class="panel panel-default panel_custom">
                    <div class="panel-body row">
                        <?php //if ($access): ?>
                            <div class="col-md-3 pull-left">
                                <a href="#" class="btn btn-inverse upload-acc-statement">
                                    <i class="fa fa-upload"></i>
                                    <?php echo $this->lang->line('BANK_UPLOAD_BUTTON'); ?>
                                </a>
                            </div>

                        <?php //endif; ?>
						<?php 
						$errors = array_filter($items);
						if (!empty($errors)) {
						?>
                        <div class="col-md-5 pull-right bPagination">
                            <?php echo $pagination; ?>
                        </div>
						<?php } ?>
                    </div>
                </div>
            <?php //endif; ?>

            <?php //if ($access): ?>
                <?php //if ($display_button): ?>
                    <br/>
                    <div class="col-md-12 no-gutter">
                        <input type="checkbox" name="Statements" id="BulkStatements"class="pull-left" style="margin-left:10px;margin-right:15px;"/>
                        &nbsp;&nbsp;&nbsp;
                        <a href="#" class="btn btn-danger btn-sm bulkdelete-statement pull-left" disabled>
                            <i class="glyphicon glyphicon-trash"></i>
                        </a>
                    </div>
                    <br/>
                <?php //endif; ?>
            <?php //endif; ?>

            <div class="table_b_updte table-responsive">

                <table class="table-striped tble_colr_txt">
                    <thead>
                        <tr class="salary-table">
                            <?php //if ($access): ?>
                                <th>

                                </th>
                            <?php //endif; ?>
                            <th>
                                #
                            </th>
                            <th width="13%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_DATE'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_TYPE'); ?>
                            </th>
                            <th width="250">
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
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_CHECK'); ?>
                            </th>
                            <th width="100">
                                <a href="<?php echo $this->encrypt->encode('SORT_BY_CATEGORY'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_CATEGORY'); ?>" class="sort color">
                                    <?php echo $this->lang->line('BANK_TABLE_COLUMN_CATEGORY'); ?>
                                    <?php
                                    //getSortDirection($order, 'SORT_BY_CATEGORY', $asc_order_value);
                                    ?>
                                </a>
                            </th>
                            <!--th width="100">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_ACTIONS'); ?>
                            </th-->
                        </tr>
                    </thead>
                    <tbody id="bulk-bank-listing">
                        <?php $this->load->view('accountant/bulkupload/bulk_bank_listing', $items); ?>
                    </tbody>
                </table>
            </div>
            <?php //if ($view): ?>
                <div class="panel panel-default panel_custom">
                    <div class="panel-body row">
                        <?php //if ($access): ?>
                            <div class="col-md-3 pull-left">
                                <a href="#" class="btn btn-inverse upload-acc-statement">
                                    <i class="fa fa-upload"></i>
                                    <?php echo $this->lang->line('BANK_UPLOAD_BUTTON'); ?>
                                </a>
                            </div>
                        <?php //endif; ?>
						<?php 
						$errors = array_filter($items);
						if (!empty($errors)) {
						?>
                        <div class="col-md-5 pull-right bPagination">
                            <?php echo $pagination; ?>
                        </div>
						<?php } ?>
                    </div>
                </div>
            <?php //endif; ?>
            <?php echo form_close(); ?>
        </div>
    </div>
</section>
<div class="modal fade modal-statements" id="modal-acc-statements" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 90%; height: auto; max-height: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php //echo $this->lang->line('CLIENT_INVOICE_NEW_EXPENSE'); ?></h4>
            </div>
            <div class="modal-body"></div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="dialog"></div>
<?php $this->load->view('accountant/footer'); ?>