<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$this->load->view('client/header', array('page' => $page, 'title' => $title));
$access = clientAccess();
if (empty($pagination)) {
    if ($access == 0) {
        $view = 0;
    } else {
        $view = 1;
    }
} else {
    $view = 1;
}
$user = $this->session->userdata('user');
$search = $this->session->userdata('BankSearch');
$aaccess = $user['AccountantAccess'];
// echo '<pre>';
// print_r($aaccess);
// die();
if ($search == '') {
    $search = array(
        'Description' => '',
        'StartDate' => '',
        'EndDate' => '',
        'Category' => 0,
        'FinancialYear' => 0
    );
}
$display_button = (count($items) == 0) ? 0 : 1;
$asc_order_value = array(
    'SORT_BY_CATEGORY' => 's.Category ASC'
);
$order = $this->session->userdata('BankSortingOrder');
$TBYears = getTBYear();
// prd( $TBYears );
$TBYear = 0;
$TBPrevYear = $TBYears[1]["value"];
?>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum">
            <h4><?php echo $this->lang->line('BANK_PAGE_LABEL_TITLE'); ?></h4>
            <?php echo $this->session->flashdata('bankError'); ?>
            <div class="panel panel-default panel_custom">
                <?php
                echo form_open(site_url() . 'clients/banks/search', array('name' => 'bankSearch'));
                ?>
                <div class="panel-body row">
                    <div class="col-md-4 col-sm-5 col-xs-12 padding_field">
                        <div class="wid-30">
                            <label><?php echo $this->lang->line('BANK_PAGE_LABEL_DESCRIPTION'); ?></label>
                        </div>
                        <div class="wid-70">
                            <input type="text" placeholder="Description" name="Description" class="form-control input-type input_100percent" value="<?php echo $search['Description']; ?>" id="Description"/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-5 col-xs-12 padding_field">
                        <div class="wid-30">
                            <label><?php echo $this->lang->line('BANK_PAGE_LABEL_DATE'); ?></label>
                        </div>
                        <div class="wid-70 date_input41">
                            <input type="text" placeholder="Start" name="StartDate" class="form-control sDatepicker input_100percent" style="float:left;"value="<?php echo $search['StartDate']; ?>"/>

                            <span style="float:left; padding:4px 5px;" class="mid-lbl">-to-</span>
                            <input type="text" name="EndDate" placeholder="End" class="form-control sDatepicker input_100percent" style="float:left;"value="<?php echo $search['EndDate']; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-5 col-xs-12 padding_field ">
                        <div class="wid-30">
                            <label><?php echo $this->lang->line('BANK_PAGE_LABEL_CATEGORY'); ?></label>
                        </div>
                        <div class="wid-70">
                            <?php echo exCategories("BANK", "Category", $search['Category'], 'class="form-control input_100percent" id="Category"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-5 col-xs-12 padding_field padding-top">
                        <div class="wid-30">
                            <label><?php echo $this->lang->line('BANK_PAGE_LABEL_FINANCIAL_YEAR'); ?></label>
                        </div>
                        <div class="wid-70">
                            <?php
                            $TBDDYears = TBDropDownYears();
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
                        <button type="submit" class="btn btn_search" value="submit">
                            <i class="fa fa-search"></i><?php echo $this->lang->line('BUTTON_SEARCH'); ?>
                        </button>
                        <a href="#" type="button" class="btn btn_search reset">
                            <span class="glyphicon glyphicon-refresh"></span><?php echo $this->lang->line('BUTTON_RESET'); ?>
                        </a>
                    </div>
                </div>
                <?php
                echo form_close();
                ?>
            </div>

            <div class="panel panel-default panel_custom">
                <div class="panel-body row">
                    <div class="col-md-4 col-sm-5 col-xs-12 padding_field">
                        <div class="wid-30">
                            <label><?php echo $this->lang->line('BANK_TABLE_COLUMN_SELECTBANK'); ?></label>
                        </div>
                        <div class="wid-70">
                            <select class="form-control" name="bankId" id="clientbankid">
                                <?php
                                foreach ($getbanks as $bank) {
                                    ?>
                                    <option value="<?php echo $bank->BID; ?>"><?php echo $bank->Name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>	
                        </div>
                    </div>
                </div>	
            </div>

            <?php if ($view): ?>
                <?php
                echo form_open(site_url() . 'clients/banks/delete_statements', array('id' => 'statementDelete'));
                ?>
                <div class="panel panel-default panel_custom">
                    <div class="panel-body row">
                        <?php if ($access): ?>
                            <div class="col-md-2 pull-left">
                                <a href="#" class="btn btn-inverse uploadStatements">
                                    <i class="fa fa-upload"></i>
                                    <?php echo $this->lang->line('BANK_UPLOAD_BUTTON'); ?>
                                </a>
                            </div>
                            <div class="col-md-2  col-sm-4 col-xs-12 btn_centre">
                                <a href="<?php echo site_url('bank_statements/add'); ?>" type="button" class="btn btn-inverse openBankSatementAddForm">
                                    <span class="glyphicon glyphicon-plus"></span>
                                    <?php echo $this->lang->line('BANKSTATEMENT_ADD_BUTTON'); ?>
                                </a> 
                            </div>
                        <?php endif; ?>
                        <div class="col-md-5 pull-right bPagination">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($access): ?>
                <?php if ($display_button): ?>
                    <br/>
                    <div class="col-md-12 no-gutter">
                        <input type="checkbox" name="Statements" id="Statements"class="pull-left" style="margin-left:10px;margin-right:15px;"/>
                        &nbsp;&nbsp;&nbsp;
                        <a href="#" class="btn btn-danger btn-sm delete-statement pull-left" disabled>
                            <i class="glyphicon glyphicon-trash"></i>
                        </a>
                    </div>
                    <br/>
                <?php endif; ?>
            <?php endif; ?>

            <div class="table_b_updte table-responsive">

                <table class="table-striped tble_colr_txt">
                    <thead>
                        <tr class="salary-table">
                            <?php if ($access): ?>
                                <th>

                                </th>
                            <?php endif; ?>
                            <th>
                                #
                            </th>
                            <th width="8%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_BANK'); ?>
                            </th>
                            <th width="8%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_DATE'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_TYPE'); ?>
                            </th>
                            <th width="20%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_DESCRIPTION'); ?>
                            </th>
                           <th width="9%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_OUT'); ?>
                            </th>
                            <th width="8%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_IN'); ?>
                            </th>
                           <th width="8%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_BALANCE'); ?>
                            </th>
                            <?php if (!empty($aaccess)) { ?>
                                <th width="8%">
                                    <?php
                                    echo $this->lang->line('BANK_TABLE_COLUMN_CHECK');
                                    ?>
                                </th>
                            <?php } ?>
							<th width="10%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_MAIN_CATEGORY'); ?>
                            </th>
                            <th width="10%">
                                <a href="<?php echo $this->encrypt->encode('SORT_BY_CATEGORY'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_CATEGORY'); ?>" class="sort color">
                                    <?php echo $this->lang->line('BANK_TABLE_COLUMN_CATEGORY'); ?>
                                    <?php
                                    getSortDirection($order, 'SORT_BY_CATEGORY', $asc_order_value);
                                    ?>
                                </a>
                            </th>
                            <th width="10%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_ACTIONS'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="bank-listing">
                        <?php $this->load->view('client/banks/bank_listing', $items); ?>
                    </tbody>
                </table>
            </div>
            <?php if ($view): ?>
                <div class="panel panel-default panel_custom">
                    <div class="panel-body row">
                        <?php if ($access): ?>
                            <div class="col-md-2 pull-left">
                                <a href="#" class="btn btn-inverse uploadStatements">
                                    <i class="fa fa-upload"></i>
                                    <?php echo $this->lang->line('BANK_UPLOAD_BUTTON'); ?>
                                </a>
                            </div>
							<!--div class="col-md-3 pull-left">
                                <a href="#" class="btn btn-inverse linkBankStatmentBtn" data-id="All">                                    
                                    Link All
                                </a>
                            </div-->
                        <?php endif; ?>
                        <div class="col-md-5 pull-right bPagination">
                            <?php echo $pagination; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php echo form_close(); ?>
        </div>
    </div>
</section>
<div class="modal fade modal-statements" id="modal-statements"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" id="uploadstatementsmodel">
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

<div class="modal fade modal-filter-excel" id="modal-filter-excel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
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
                        <a href="javascript:;" class="btn btn-primary filterExcel" id="filterExcel">
                            <i class="glyphicon glyphicon-upload"></i><?php echo $this->lang->line('BUTTON_UPLOAD'); ?>
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

    <div class="modal fade" id="modal-add-bankstatement" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
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
</div>

<div class="modal fade modal-linkto" id="modal-linkto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                                
            </div>
            <div class="clr"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>


<script>

    (function () {
        var previous;
        $(".updatStatemntCategory").focus(function () {
            previous = this.value;
        }).change(function () {
            $this = $(this);
            newCat = $this.val();
            statementId = $this.attr('data-id');

            if (newCat == '' && statementId == '' && previous == '')
            {
                return false;
            }
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>clients/banks/update_statement_category/",
                data: {'previous_cat': previous, 'new_Cat': newCat, 'dataId': statementId},
                beforeSend: function () {
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error: function (msg) {
                    hideSpinner();
                },
                success: function (msg) {
                    hideSpinner();
                    window.onbeforeunload = false;
                    //window.location = '<?php echo site_url() . 'clients/banks/before_upload' ?>';
                    //location.reload(true);
                }
            });
        });
    })();

</script>
<script type="text/javascript" src="<?php echo site_url(); ?>assets/js/jquery.form.js"></script>
<?php $this->load->view('client/footer'); ?>
