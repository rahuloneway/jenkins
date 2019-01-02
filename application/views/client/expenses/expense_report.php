<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$this->load->view('client/header', array('page' => 'expense_report', 'title' => $title));
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


// echo '<pre>'; print_r($items); echo '</pre>'; 
// die('sk.bhdfcjh');
?>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum">
            <h4><?php echo $this->lang->line('EXPENSE_REPORT_PAGE_TITLE'); ?></h4>
            <?php echo $this->session->flashdata('expenseError'); ?>
            <div class="panel panel-default panel_custom">
                <div class="panel-body ">
                    <?php
                    echo form_open(site_url() . 'clients/expenses/searchExpenseReport', array('name' => 'expensereport-search'));
                    ?>
                    <div class="row">
						<div class="col-md-4  col-sm-4 col-xs-12 padding_field">
                            <div class="col-md-6">
                                <label><?php echo $this->lang->line('EXPENSE_PAGE_LABEL_FIN_YEAR'); ?></label>
                            </div>
                            <div class="col-md-6 no-padding"> 
                                <?php echo form_dropdown('Year', year(), $search['Year'], 'class="form-control" id="Year"'); ?>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 padding_field">
                            <div class="wid-50" >
                                <label><?php echo $this->lang->line('EXPENSE_CATEGORY_PAGE_TITLE'); ?></label>
                            </div>
                            <div class="wid-50" >
								<?php echo exCategories("GEN", "ExpensereportCategory", 0, 'class="form-control ExpenseCategory"'); ?>
                            </div>
                        </div>
                        <div class="col-md-4  col-sm-4 col-xs-12 text-right padding_field btn_float pull-right ">
                            <button type="submit" class="btn  btn_search" value="submit">
                                <i class="fa fa-search"></i><?php echo $this->lang->line('BUTTON_SEARCH'); ?>
                            </button> 
                            <a href="#" type="button" class="btn  btn_search resetreport">
                                <span class="glyphicon glyphicon-refresh"></span> <?php echo $this->lang->line('BUTTON_RESET'); ?> 
                            </a>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
			<div class="panel panel-default panel_custom">
				<div class="panel-body row ">
					<!--div class="col-md-3  col-sm-4 col-xs-12 btn_centre">
						<a href="#" type="button" class="btn btn-inverse openExpenseForm">
							<span class="glyphicon glyphicon-plus"> </span><?php echo $this->lang->line('EXPENSE_PAGE_LABEL_MONTHLY_EXP'); ?>
						</a> 
					</div>
					<div class="col-md-2  col-sm-4 col-xs-12 btn_centre">
						<a href="#" type="button" class="btn btn-inverse creditCard">
							<span class="glyphicon glyphicon-plus"> </span><?php echo $this->lang->line('EXPENSE_PAGE_LABEL_CREDIT_STATEMENT'); ?>
						</a> 
					</div-->
					<div class="browse-file col-md-2 pull-right">
                        <a target="_blank" href="<?php echo site_url() . 'expense_report_sheet'; ?>" class="btn btn-primary" id="uploadDocument">
                            <span class="glyphicon glyphicon-download-alt"></span> Download
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
                                Date
                            </th>
                            <th>
								Expense Number
                            </th>
                            <th>
								Category
                            </th>   
							<th>
								Description
                            </th>   
							<th>
								Miles
                            </th>
							<th>
								Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody id="expense-listing">
                        <?php $this->load->view('client/expenses/expense_report_listing'); ?>
                    </tbody>
                </table>
            </div>

            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-3  col-sm-4 col-xs-12 btn_centre">
                       <span class="label_total">Total Amount: </span>
					   <span class="res_total"><?php echo $totalAmount; ?></span>
                    </div>
                    <!--div class="col-md-2  col-sm-4 col-xs-12 btn_centre">
                        <?php //echo $totalAmount; ?>
                    </div-->
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