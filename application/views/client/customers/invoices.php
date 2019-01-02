<?php 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$this->load->view('client/header', array('page' => 'customers', 'title' => $title));
error_reporting(0);
?>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum">
			<div class="panel-body row">
				<div class="col-md-3 col-sm-12 col-xs-12 invoice_field top_space">
					<h4><?php echo $this->lang->line('CUSTOMERS_PAGE_TITLE'); ?></h4>
				</div>
				<div class="col-md-3 col-sm-12 col-xs-12 invoice_field pull-right top_space">
					<a href="<?php echo site_url('customers'); ?>" class="btn  btn_grey pull-right">
						<i class="glyphicon glyphicon-chevron-left"></i>&nbsp;Back to Customer
					</a>
				</div>
			</div>
            <?php echo $this->session->flashdata('customersMessage'); ?>
			<div class="clearfix"></div>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row">                   
					<div class="col-md-2  col-sm-3 col-xs-12 btn_centre_bot"> 
						<b> Total Invoice Amount: </b>
					</div>
					 <div class="col-md-2  col-sm-3 col-xs-12 btn_centre_bot"> 
						<?php  echo numberFormat($InvoiceTotal); ?>
					</div>
					<div class="col-md-2  col-sm-3 col-xs-12 btn_centre_bot"> 
						<b> Paid Invoices Amount: </b>
					</div>
					 <div class="col-md-2  col-sm-3 col-xs-12 btn_centre_bot"> 
						<?php  echo numberFormat($PaidInvoiceTotal);  ?>
					</div>
				</div>
				<div class="panel-body row">                   
					
				</div>					
			</div>
            
			<?php echo form_open('clients/customers/customerInvoiceSearch', array('name' => 'invoice-search', 'id' => 'invoice-search')); ?>
                    <input type="hidden" name="page" value="<?php   echo @$page; ?>" />
                    <input type="hidden" name="id" value=" <?php   echo @$id; ?>" />
                    <input type="hidden" name="year" value=" <?php   echo @$year=0; ?>" />
					
					<div class="panel panel-default panel_custom panel-inv">
                        <div class="panel-body row">
                            <div class="col-md-3 col-sm-12 col-xs-12 invoice_field">
                                <div class="wid-30" ><label><?php echo $this->lang->line('CLIENT_INVOICE_FORM_LABEL_ID'); ?></label></div>
                                <div class="wid-70" >
                                    <input type="text" class="form-control" name="sInvoiceNumber" id="sInvoiceNumber" placeholder="Invoice ID" value="<?php echo $search['InvoiceNumber']; ?>">
                                </div>
                                <div class="clr" ></div>
                            </div>

                            <div class="col-md-3 col-sm-12 col-xs-12  invoice_field">
                                <div class="wid-40" ><label><?php echo $this->lang->line('CLIENT_INVOICE_FORM_LABEL_NAME'); ?></label>  </div>
                                <div class="wid-60" >
                                    <input type="text" class="form-control" name="sCustomerName" id="sCustomerName" placeholder="Name" value="<?php echo $search['CustomerName']; ?>">  </div>
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
                                <!--a href="<?php echo site_url(); ?>client/clean" type="button" class="btn  btn_search reset">
                                    <span class="glyphicon glyphicon-refresh"></span>
                                    <?php echo $this->lang->line('BUTTON_RESET'); ?>
                                </a-->
                                <div class="wid-20" >&nbsp;</div>
                                <div class="clr" ></div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
			
            <div class="clearfix"></div>
           			            
            <div class="table-responsive">
                        <table>
                            <thead>
                                <tr class="salary-table">
                                    <th>#</th>

                                    <th>
                                        <a href="<?php echo $this->encrypt->encode('SORT_BY_ID'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_INVOICE'); ?>">
                                            <?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_INVOICE'); ?>
                                            <?php
                                            getSortDirection($order, 'SORT_BY_ID', $asc_order_value);
                                            ?>
                                        </a>

                                    </th>
                                    <th>
                                        <a href="<?php echo $this->encrypt->encode('SORT_BY_NAME'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_NAME'); ?>" class="sort color">
                                            <?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_NAME'); ?>
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
                                    <?php if ($vat_listing->Type != ''): ?>
                                        <th>
                                            <?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_FLAT_RATE'); ?>
                                        </th>
                                        <th>
                                            <?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_SALES'); ?>
                                        </th>
                                    <?php endif; ?>
                                    <th>
                                        <a href="<?php echo $this->encrypt->encode('SORT_BY_CDATE'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_CDATE'); ?>" class="sort color">
                                            <?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_CDATE'); ?>
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
                                <?php $this->load->view('client/invoices/invoice_listing', $Invoices); ?>
                            </tbody>
                        </table>
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
	
</section>




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