<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$this->load->view('client/header', array('page' => $page, 'title' => $title));
$user = $this->session->userdata('user');
if (isset($user['AccountantAccess'])) {
    $access = 1;
} else {
    $access = 0;
}
$search = $this->session->userdata('SalarySearch');
if (empty($search) || count($search) == 0) {
    $search = array(
        'EID' => 0,
        'FinancialYear' => currentFinancialYear()
    );
}

$asc_order_value = array(
    'SORT_BY_PAID_DATE' => 's.PaidDate ASC'
);
$order = $this->session->userdata('SalarySortingOrder');
$AddPaye = $this->session->userdata('AddPayese');
if ($AddPaye) {
    $ts = $AddPaye['ts'];
} else {
    $ts = 0;
}
?>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum">
            <!-- h4>List of Invoices</h4 -->
            <?php echo $this->session->flashdata('payUploadError'); ?>
            <ul class="nav nav-tabs">
                <?php
                if ($ts == 1) {
                    ?>
                    <li <?php echo ($tab == 1) ? 'class="active"' : ''; ?>>
                        <a data-toggle="tab" href="#sectionA">Salary</a>
                    </li>
                    <li <?php echo ($tab == 0) ? 'class="active"' : ''; ?>>
                        <a data-toggle="tab" href="#sectionB" id="payeide">PAYE Liabilities</a>
                    </li>
                <?php } else { ?>
                    <li <?php echo ($tab == 0) ? 'class="active"' : ''; ?>>
                        <a data-toggle="tab" href="#sectionA">Salary</a>
                    </li>
                    <li <?php echo ($tab == 1) ? 'class="active"' : ''; ?>>
                        <a data-toggle="tab" href="#sectionB" id="payeide">PAYE Liabilities</a>
                    </li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <?php
                if ($ts == 1) {
                    ?>
                    <div id="sectionA" class="tab-pane fade in <?php echo ($tab == 1) ? 'active' : ''; ?>">
                    <?php } else { ?>
                        <div id="sectionA" class="tab-pane fade in <?php echo ($tab == 0) ? 'active' : ''; ?>">	
                        <?php } ?>
                        <?php if ($access == 1): ?>
                            <?php echo form_open_multipart(site_url() . 'clients/salary/upload', array('id' => 'payStatements', 'name' => 'payStatements')); ?>
                            <div class="panel panel-default panel_custom">
                                <div class="panel-body">
                                    <div class="col-md-6">
                                        <div class="browse-file col-md-8 pull-right">
                                            <input type="file" name="file" id="file" class="filestyle" data-buttonName="btn-primary"accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"/>
                                            <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
                                        </div>
                                    </div>
                                    <div class="browse-file col-md-6">
                                        <a href="#"class="btn btn-primary uploadPay">
                                            <i class="glyphicon glyphicon-upload"></i>Upload 
                                        </a>
                                        <!--&nbsp;&nbsp;
                                        <a href="<?php echo site_url() . 'clients/salary/template' ?>" class="btn btn-primary">
                                            <i class="glyphicon glyphicon-download-alt"></i>Salary template  
                                        </a>-->
                                    </div>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        <?php endif; ?>
                        <div class="panel panel-default panel_custom panel-inv">
                            <div class="panel-body row">
                                <div class="col-md-3 col-md-offset-1 col-sm-12 col-xs-12  ">
                                    <div class="wid-30" >
                                        <label>Employee:</label>  
                                    </div>
                                    <div class="wid-70" >
                                        <?php
                                        unset($employees[0]);
                                        unset($employees[0]);
                                        $financial_year = financial_year();
										//	echo "<pre>"; print_r($financial_year); echo "</pre>";
										//	echo "<pre>"; print_r($search); echo "</pre>";
                                        unset($financial_year[0]);
                                        ?>
										<?php //print_r($employees); ?>
                                        <?php echo form_dropdown('employees', $employees, $search['EID'], 'id = "employees"'); ?> 
                                    </div>
                                    <div class="clr" ></div>
                                </div>
                                <div class="col-md-4 col-md-offset-3 col-sm-12 col-xs-12">
                                    <div class="wid-30" >
                                        <label> Financial Year:</label> 
                                    </div>
                                    <div class="wid-70 date_input" >
                                        <?php echo form_dropdown('financialyear', $financial_year, $search['FinancialYear'], 'id = "financialyear"'); ?>
                                    </div>
                                    <div class="clr" ></div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr class="salary-table">
                                        <th>#</th>
                                        <th>Financial year</th>
                                        <th>
                                            <a href="<?php echo $this->encrypt->encode('SORT_BY_PAID_DATE'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_PAY_DATE'); ?>" class="sort color">
                                                Pay Date
                                                <?php
                                                getSortDirection($order, 'SORT_BY_PAID_DATE', $asc_order_value);
                                                ?>
                                            </a>
                                        </th>
                                        <th>Gross Salary</th>
                                        <th>Income Tax</th>
                                        <th>NIC Employee</th>
                                        <th>NIC Employer</th>
                                        <th>SMP</th>
                                        <th>
                                            Net Pay
                                        </th>
                                        <th>

                                            <a href="<?php echo $this->encrypt->encode('SORT_BY_PAID_ON'); ?>" data-toggle="tooltip" data-placement="top" title="Sort by Paid on date" class="sort color">
                                                Paid On
                                                <?php
                                                getSortDirection($order, 'SORT_BY_PAID_ON', $asc_order_value);
                                                ?>
                                            </a>
                                        </th>
                                        <?php if ($access == 1): ?>
                                            <th>Action</th>
                                        <?php else: ?>
                                            <th>Status</th>
                                        <?php endif; ?>

                                    </tr>
                                </thead>
                                <tbody id="salary-listing">
                                    <?php
                                    /*
                                      echo '<tr>';
                                      echo '<td colspan="10">';
                                      echo '<div class="alert alert-info text-center">';
                                      echo $this->lang->line('SALARY_NO_RECORD_FOUND');
                                      echo '</div>';
                                      echo '</td>';
                                      echo '</tr>';
                                     */
                                    ?>
                                    <?php $this->load->view('client/salary/salary_listing', $items); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                    if ($ts == 1) {
                        ?>
                        <div id="sectionB" class="tab-pane <?php echo ($tab == 0) ? 'active' : 'fade'; ?>" id="container">
                        <?php } else {
                            ?>
                            <div id="sectionB" class="tab-pane <?php echo ($tab == 1) ? 'active' : 'fade'; ?>" id="container">
                            <?php } ?>

                            <?php
                            $this->load->view('client/salary/payee');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            </section>
            <div id="dialog"></div>	
            <?php $this->load->view('client/footer'); ?>