<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$this->load->view('client/header', array('page' =>$page, 'title' => $title));
$search = $this->session->userdata('InvoiceSearch');
if ($search == '') {
    $search = array(
        'InvoiceNumber' => '',
        'CustomerName' => '',
        'Status' => 0,
        'sCreatedStart' => '',
        'sCreatedEnd' => '',
        'sDueStart' => '',
        'sDueEnd' => '',
        'invoice_financialyear' => ''
    );
}
$asc_order_value = array(
    'SORT_BY_ID' => 'i.InvoiceNumber ASC',
    'SORT_BY_NAME' => 'CONCAT(u.FirstName," ",u.LastName) ASC',
    'SORT_BY_AMOUNT' => 'i.InvoiceTotal ASC',
    'SORT_BY_CDATE' => 'i.AddedOn ASC',
    'SORT_BY_DDATE' => 'i.DueDate ASC'
);
$order = $this->session->userdata('invoiceSortingOrder');
$user = $this->session->userdata('user');
$TBYears = getTBYear();
//prd( $user);
$TBYear = $TBYears[0]["value"];
$TBPrevYear = $TBYears[1]["value"];
?>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum">
            <div class="alert clientErrors"><?php echo $this->session->flashdata('actionMessage'); ?></div>
            <!-- h4>VAT Summary</h4 -->
            <ul class="nav nav-tabs">
                <li class="tab-bg active">
                    <a data-toggle="tab" href="#invoices_tab">
                        <?php echo $this->lang->line('PURCHASE_PAGE_LABLE_PURCHASE'); ?>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="invoices_tab" class="tab-pane fade in active">
            <!-- h4><?php echo $this->lang->line('CLIENT_INVOICE_FORM_LABEL_LISTING'); ?></h4 -->
                    <?php echo form_open('clients/purchase/purchaseSearch', array('name' => 'invoice-search', 'id' => 'invoice-search')); ?>
                    <div class="panel panel-default panel_custom panel-inv">
                        <div class="panel-body row">
                            <div class="col-md-3 col-sm-12 col-xs-12 invoice_field">
                                <div class="wid-30" ><label><?php echo $this->lang->line('CLIENT_PURCHASE_FORM_LABEL_ID'); ?></label></div>
                                <div class="wid-70" >
                                    <input type="text" class="form-control" name="sInvoiceNumber" id="sInvoiceNumber" placeholder="Purchase ID" value="<?php echo $search['InvoiceNumber']; ?>">
                                </div>
                                <div class="clr" ></div>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12  invoice_field">
                                <div class="wid-40" ><label><?php echo $this->lang->line('CLIENT_PURCHASE_FORM_LABEL_NAME'); ?></label>  </div>
                                <div class="wid-60" >
                                    <input type="text" class="form-control" name="sCustomerName" id="sCustomerName" placeholder="Supplier Name" value="<?php echo $search['CustomerName']; ?>">  </div>
                                <div class="clr" ></div>
                            </div>
                            <div class="col-md-2 col-sm-12 col-xs-12  invoice_field">
                                <div class="wid-30"><label><?php echo $this->lang->line('CLIENT_INVOICE_FORM_LABEL_STATUS'); ?></label></div>
                                <div class="wid-60">
                                    <?php echo genericList('sInvoiceStatus', '', $search['Status'], 'sInvoiceStatus'); ?>
                                </div>
                                <div class="clr" ></div>
                            </div>

                            <div class="col-md-4 col-sm-6 col-xs-12 padding_field">
                                <div class="wid-50">
                                    <label>
                                        <?php echo $this->lang->line('JOURNAL_LABEL_ACCOUNTING_YEAR'); ?>
                                    </label>
                                </div>
                                <div class="wid-50">
                                    <?php
                                    $TBDDYears = TBDropDownYears();
                                    for ($i = $TBDDYears["end"]; $i >= $TBDDYears["start"]; $i--) {
                                        $arrYear = TBListYearsDD($i);
                                        $arrYears[$arrYear["value"]] = $arrYear["title"];
                                        unset($arrYear);
                                    }
                                    $arrYears[0] = '- Select Year -';
                                    asort($arrYears);
                                    echo genericList("invoice_financialyear", $arrYears, $search['invoice_financialyear'], "invoice_financialyear");
                                    ?>
                                </div>
                            </div>

                        </div>
                        <div class="panel-body row">
                            <div class="col-md-3 col-sm-12 col-xs-12 invoice_field">
                                <div class="wid-30" ><label><?php echo $this->lang->line('CLIENT_INVOICE_FORM_LABEL_DDATE'); ?></label></div>
                                <div class="wid-70 date_input" >
                                    <input type="text" class="form-control sdatepicker" id="sDueStart" name="sDueStart"placeholder="Start"value="<?php echo $search['sDueStart']; ?>"/>
                                    <span class="mid-lbl"><?php echo $this->lang->line('CLIENT_INVOICE_FORM_LABEL_TO'); ?></span>
                                    <input type="text" class="form-control sdatepicker" id="sDueEnd" name="sDueEnd" placeholder="End"value="<?php echo $search['sDueEnd']; ?>"/>
                                </div>
                                <div class="clr" ></div>
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12 invoice_field">
                                <div class="wid-30" ><label><?php echo $this->lang->line('CLIENT_INVOICE_FORM_LABEL_CDATE'); ?></label> </div>
                                <div class="wid-70 date_input" >
                                    <input type="text" class="form-control sdatepicker"  placeholder="Start" id="sCreatedStart"name="sCreatedStart"value="<?php echo $search['sCreatedStart']; ?>"/>
                                    <span class="mid-lbl" ><?php echo $this->lang->line('CLIENT_INVOICE_FORM_LABEL_TO'); ?></span>
                                    <input type="text" class="form-control sdatepicker" id="sCreatedEnd"name="sCreatedEnd"placeholder="End"value="<?php echo $search['sCreatedEnd']; ?>"/>
                                </div>
                                <div class="clr" ></div>
                            </div>

                            <div class="col-md-2 col-sm-12 col-xs-12 text-right padding_field btn_float pull-right text-center">
                                <input type="hidden" name="filter" value="search"/>
                                <button type="submit" class="btn  btn_search" value="submit">
                                    <i class="fa fa-search"></i>Search
                                </button> 
                                <a href="<?php echo site_url(); ?>client/clean" type="button" class="btn  btn_search reset">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                    <?php echo $this->lang->line('BUTTON_RESET'); ?>
                                </a>
                                <div class="wid-20" >&nbsp;</div>
                                <div class="clr" ></div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>

                    <div class="panel panel-default panel_custom">
                        <div class="panel-body row ">
                            <div class="col-md-2  col-sm-6 col-xs-12 btn_centre">
                                <a href="#addinvoice" class="btn btn-inverse addInvoice">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <?php echo $this->lang->line('CLIENT_BUTTON_ADD_PURCHASE'); ?>
                                </a>
                            </div>
                            <div class="col-md-2  col-sm-6 col-xs-12 btn_centre">
                                <a href="#addCrediteNote" class="btn btn-inverse addCrediteNote">
                                    <i class="glyphicon glyphicon-plus"></i>
                                     <?php echo $this->lang->line('CLIENT_BUTTON_ADD_PURCHASE_DEBIT_NOTE'); ?>
                                </a>
                            </div>
                            <div class="col-md-8 col-sm-6 col-xs-12">
                                <?php
                                echo $pagination . '<br/>';
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr class="salary-table">
                                    <th>#</th>

                                    <th>
                                        <a href="<?php echo $this->encrypt->encode('SORT_BY_ID'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_INVOICE'); ?>" class="sort color">
                                            <?php echo $this->lang->line('CLIENT_PURCHASE_TABLE_LABEL_PURCHASE'); ?>
                                            <?php
                                            getSortDirection($order, 'SORT_BY_ID', $asc_order_value);
                                            ?>
                                        </a>

                                    </th>
                                    <th>
                                        <a href="<?php echo $this->encrypt->encode('SORT_BY_NAME'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_NAME'); ?>" class="sort color">
                                            <?php echo $this->lang->line('CLIENT_PUCHASE_TABLE_LABEL_NAME'); ?>
                                            <?php
                                            getSortDirection($order, 'SORT_BY_NAME', $asc_order_value);
                                            ?>
                                        </a>
                                    </th>
                                    <th>
                                        <?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_AMOUNT'); ?>
                                    </th>
                                    <?php if (empty($vat_listing->Type) && empty($vat_listing->PercentRateAfterEndDate) && empty($vat_listing->PercentRate)) { ?>

                                    <?php } else { ?>
                                        <th>
                                            <?php echo $this->lang->line('CLIENT_INVOICE_LABLE_VAT_TWO'); ?>
                                        </th>
                                    <?php } ?>
                                    <th>
                                        <a href="<?php echo $this->encrypt->encode('SORT_BY_AMOUNT'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_TOTAL'); ?>" class="sort color">
                                            <?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_TOTAL_AMOUNT'); ?>
                                            <?php
                                            getSortDirection($order, 'SORT_BY_AMOUNT', $asc_order_value);
                                            ?>
                                        </a>
                                    </th>
                                  
                                    <th>
                                        <a href="<?php echo $this->encrypt->encode('SORT_BY_CDATE'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_CDATE'); ?>" class="sort color">
                                            <?php echo $this->lang->line('CLIENT_PURCHASE_TABLE_LABEL_DDATE'); ?>
                                            <?php
                                            getSortDirection($order, 'SORT_BY_CDATE', $asc_order_value);
                                            ?>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="<?php echo $this->encrypt->encode('SORT_BY_DDATE'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_DDATE'); ?>" class="sort color">
                                            <?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_DDATE'); ?>
                                            <?php
                                            getSortDirection($order, 'SORT_BY_DDATE', $asc_order_value);
                                            ?>
                                        </a>
                                    </th>
                                    <th>
                                        <?php echo $this->lang->line('INVOICE_PAGE_LABLE_PAID_DATE'); ?>
                                    </th>
                                    <th>
                                        <?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_STATUS'); ?>
                                    </th>
                                    <th>
                                        <?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_ACTION'); ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="invoiceListing">
                                <?php $this->load->view('client/purchase/purchase_listing', $Invoices); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="panel panel-default panel_custom">
                        <div class="panel-body row ">
                            <div class="col-md-2  col-sm-4 col-xs-12 btn_centre"> 
                                <a href="#addinvoice" class="btn btn-inverse addInvoice">
                                    <i class="glyphicon glyphicon-plus"></i>
                                    <?php echo $this->lang->line('CLIENT_BUTTON_ADD_PURCHASE'); ?>
                                </a>
                            </div>
                            <div class="col-md-2  col-sm-6 col-xs-12 btn_centre">
                                <a href="#addCrediteNote" class="btn btn-inverse addCrediteNote">
                                    <i class="glyphicon glyphicon-plus"></i>
                                      <?php echo $this->lang->line('CLIENT_BUTTON_ADD_PURCHASE_DEBIT_NOTE'); ?>
                                </a>
                            </div>
                            <div class="col-md-8">
                                <?php
                                echo $pagination . '<br/>';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="vat_summary_tab" class="tab-pane fade " id="container">
                    <div class="panel panel-default panel_custom panel-inv">
                        <div class="panel-body row">
                            <div class="col-md-4 col-md-offset-2 col-sm-12 col-xs-12  invoice_field">
                                <div class="wid-30" ><label><?php echo $this->lang->line('CLIENT_VAT_FINANCIAL_YEAR_LABEL'); ?>:</label></div>
                                <div class="wid-70" >
                                    <?php echo @vatYearDropDown("vatYear", NULL, 0, "vatYear"); ?>
                                </div>
                                <div class="clr" ></div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default panel_custom inv_blue">
                        <div class="panel-body row text-center">

                            <?php
                            if ($vat_listing->Type == 'flat') {
                                $colmdspan1 = 3;
                                $colmdspan2 = 4;
                                $colmdspan3 = 5;
                            } else {
                                $colmdspan1 = 6;
                                $colmdspan3 = 6;
                            }
                            ?>

                            <div class="col-md-<?php echo $colmdspan1; ?> bodr_rgt">
                                <!-- i class="fa fa-plus pull-left showSummary" data-toggle="collapse" href="#vatSummary" aria-expanded="false" aria-controls="vatSummary"></i -->
                                <strong><?php echo $this->lang->line('INVOICE_PAGE_LABLE_VAT_PERCENT'); ?></strong><span>20</span>
                            </div>	
                            <?php if ($vat_listing->Type == 'flat'): ?>
                                <div class="col-md-<?php echo $colmdspan2; ?> bodr_rgt">
                                    <strong><?php echo $this->lang->line('INVOICE_PAGE_LABLE_FLAT_RATE'); ?></strong>
                                    <span><?php echo $user['PercentRate']; ?></span>
                                </div>
                                <div class="col-md-<?php echo $colmdspan3; ?> bodr_rgt">
                                    <strong><?php echo $this->lang->line('INVOICE_PAGE_LABLE_FERST_END_DATE'); ?></strong>
                                    <span><?php echo cDate($user['EndDate']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="table-responsive inv_info_n " id="vatSummary"><!-- .collapse -->
                        <?php $this->load->view('client/invoices/vat_summary'); ?>
                    </div><br/>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-invoice" id="modal-invoice"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('CLIENT_INVOICE_NEW_INVOICE_TITLE'); ?></h4>
                </div>
                <div class="modal-body"></div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div id="dialog" style="display:none;"></div>
</section>
<?php $this->load->view('client/footer'); ?>