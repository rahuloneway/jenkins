<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$this->load->view('client/header', array('page' => 'expenses', 'title' => $title));
$search = $this->session->userdata('ExpenseSearch');
if ($search == '') {
    $search = array(
        'EmployeeID' => 0,
        'Month' => 0,
        'Year' => 0,
    );
}

$asc_order_value = array(
    'SORT_BY_EXPENSE' => 'e.ExpenseNumber ASC',
    'SORT_BY_NAME' => 'CONCAT(ce.FirstName," ",ce.LastName) ASC',
    'SORT_BY_MONTH' => 'e.ExpenseDate ASC',
    'SORT_BY_MILES' => 'e.TotalMiles ASC',
    'SORT_BY_AMOUNT' => 'e.TotalAmount ASC',
    'SORT_BY_FILES' => 'e.FileID ASC',
    'SORT_BY_STATUS' => 'e.Status ASC'
);
$order = $this->session->userdata('expenseSortingOrder');
$user = $this->session->userdata('user');
$config = settings();
//pr($config);//die;
?>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum">
            <h4><?php echo $this->lang->line('EXPENSE_PAGE_TITLE'); ?></h4>
            <?php echo $this->session->flashdata('expenseError'); ?>
            <div class="panel panel-default panel_custom">
                <div class="panel-body ">
                    <?php
                    echo form_open(site_url() . 'clients/expenses/search', array('name' => 'expense-search'));
                    ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-12 padding_field">
                            <div class="wid-30" >
                                <label><?php echo $this->lang->line('EXPENSE_PAGE_LABEL_EMP'); ?></label>
                            </div>
                            <div class="wid-70" >
                                <?php
                                //print_r($employees);
                                echo form_dropdown('EmployeeID', $employees, $search['EmployeeID'], 'id="EmployeeID" class="form-control"');
                                ?>
                            </div>
                        </div>
                        <div class="col-md-3  col-sm-3 col-xs-12 padding_field">
                            <div class="wid-30">
                                <label><?php echo $this->lang->line('EXPENSE_PAGE_LABEL_MONTH'); ?></label>
                            </div>
                            <div class="wid-70" > 
                                <?php echo form_dropdown('Month', month(), $search['Month'], 'class="form-control" id="Month"'); ?>
                            </div>
                        </div>
                        <div class="col-md-3  col-sm-3 col-xs-12 padding_field">
                            <div class="col-md-6">
                                <label><?php echo $this->lang->line('EXPENSE_PAGE_LABEL_FIN_YEAR'); ?></label>
                            </div>
                            <div class="col-md-6 no-padding"> 
                                <?php echo form_dropdown('Year', year(), $search['Year'], 'class="form-control" id="Year"'); ?>
                            </div>
                        </div>
                        <div class="col-md-3  col-sm-3 col-xs-12 text-right padding_field btn_float pull-right ">
                            <button type="submit" class="btn  btn_search" value="submit">
                                <i class="fa fa-search"></i><?php echo $this->lang->line('BUTTON_SEARCH'); ?>
                            </button> 
                            <a href="<?php echo base_url()."nextexpenses"?>" type="button" class="btn  btn_search ">
                               Next <span class="glyphicon glyphicon-fast-forward"></span>
                            </a>
                            <a href="#" type="button" class="btn  btn_search reset">
                                <span class="glyphicon glyphicon-refresh"></span> <?php echo $this->lang->line('BUTTON_RESET'); ?> 
                            </a>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-3  col-sm-4 col-xs-12 btn_centre">
                        <a href="#" type="button" class="btn btn-inverse openExpenseForm">
                            <span class="glyphicon glyphicon-plus"> </span><?php echo $this->lang->line('EXPENSE_PAGE_LABEL_MONTHLY_EXP'); ?>
                        </a> 
                    </div>
                    <div class="col-md-2  col-sm-4 col-xs-12 btn_centre">
                        <a href="#" type="button" class="btn btn-inverse creditCard">
                            <span class="glyphicon glyphicon-plus"> </span><?php echo $this->lang->line('EXPENSE_PAGE_LABEL_CREDIT_STATEMENT'); ?>
                        </a> 
                    </div>
                    <div class="col-md-8 col-xs-12 ePagination">
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr class="salary-table">
                            <th>
                                #
                            </th>
                            <th>
                                <a href="<?php echo $this->encrypt->encode('SORT_BY_EXPENSE'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_EXPENSE'); ?>" class="sort color">
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_EX_ID'); ?>
                                    <?php
                                    getSortDirection($order, 'SORT_BY_EXPENSE', $asc_order_value);
                                    ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?php echo $this->encrypt->encode('SORT_BY_NAME'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_NAME'); ?>" class="sort color">
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_EMP_NAME'); ?>
                                    <?php
                                    getSortDirection($order, 'SORT_BY_NAME', $asc_order_value);
                                    ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?php echo $this->encrypt->encode('SORT_BY_MONTH'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_MONTH'); ?>" class="sort color">
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MONTH'); ?>
                                    <?php
                                    getSortDirection($order, 'SORT_BY_MONTH', $asc_order_value);
                                    ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?php echo $this->encrypt->encode('SORT_BY_MILES'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_MILES'); ?>" class="sort color">
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MILEAGE'); ?>
                                    <?php
                                    getSortDirection($order, 'SORT_BY_MILES', $asc_order_value);
                                    ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?php echo $this->encrypt->encode('SORT_BY_AMOUNT'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_AMOUNT'); ?>" class="sort color">
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_TOTAL_AMOUNT'); ?>
                                    <?php
                                    getSortDirection($order, 'SORT_BY_AMOUNT', $asc_order_value);
                                    ?>
                                </a>
                            </th>
                            <?php
                            if ($user['VAT_TYPE'] == 'stand'):
                                ?>
                                <th>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_VAT_PAID'); ?>
                                </th>
                                <?php
                            endif;
                            ?>
                            <th>
                                <a href="<?php echo $this->encrypt->encode('SORT_BY_FILES'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_FILES'); ?>" class="sort color">
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_ADD_FROM'); ?>
                                    <?php
                                    getSortDirection($order, 'SORT_BY_FILES', $asc_order_value);
                                    ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?php echo $this->encrypt->encode('SORT_BY_STATUS'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_STATUS'); ?>" class="sort color">
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_STATUS'); ?>
                                    <?php
                                    getSortDirection($order, 'SORT_BY_STATUS', $asc_order_value);
                                    ?>
                                </a>
                            </th>
                            <th>
                                <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_PAID_DATE'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_ACTION'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="expense-listing">
                        <?php $this->load->view('client/expenses/expense_listing'); ?>
                    </tbody>
                </table>
            </div>

            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-3  col-sm-4 col-xs-12 btn_centre">
                        <a href="#" type="button" class="btn btn-inverse openExpenseForm">
                            <span class="glyphicon glyphicon-plus"> </span><?php echo $this->lang->line('EXPENSE_PAGE_LABEL_MONTHLY_EXP'); ?>
                        </a> 
                    </div>
                    <div class="col-md-2  col-sm-4 col-xs-12 btn_centre">
                        <a href="#" type="button" class="btn btn-inverse creditCard">
                            <span class="glyphicon glyphicon-plus"> </span><?php echo $this->lang->line('EXPENSE_PAGE_LABEL_CREDIT_STATEMENT'); ?>
                        </a> 
                    </div>
                    <div class="col-md-10 col-xs-12 ePagination">
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade modal-expenses" id="modal-expenses"tabindex="-1" role="modal" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
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
<div id="dialog"></div>
<?php $this->load->view('client/footer'); ?>