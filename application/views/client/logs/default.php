<?php 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$this->load->view('client/header', array('page' => $page, 'title' => $title));
$user = $this->session->userdata('user');
if (isset($user['AccountantAccess'])) {
    $access = 1;
} else {
    $access = 0;
}
?>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum border_box_trial">
           <h4><?php echo $this->lang->line('ACTION_LOGS_PAGE_TITLE'); ?></h4>
            <div class="panel panel-default panel_custom panel-inv">
                <div class="panel-body row">
                    <div class="col-md-10 col-sm-12 col-xs-12  invoice_field">
                        <div class="col-md-2"><label><?php echo $this->lang->line('LOG_LIST_SEARCH'); ?></label></div>
                        <div class="wid-100">
                            <div class="wid-80 date_input41">
                                <?php if ($this->session->flashdata('message')) : ?>
                                    <p><?php echo $this->session->flashdata('message'); ?></p>
                                <?php endif; ?>
                                <?php echo form_open(site_url() . 'logs/searchLog', array('id' => 'from-log-search', 'name' => 'from-log-search')); ?>
                                <input type="text" value="" style="float:left;" class="form-control sDatepicker input_100percent" name="StartDate" placeholder="Start" id="log-date-from">
                                <span class="mid-lbl" style="float:left; padding:4px 5px;">-to-</span>
                                <input type="text" value="" style="float:left;" class="form-control sDatepicker input_100percent"  placeholder="End" name="EndDate" id="log-date-to">
                                <span class="mid-lbl" style="float:left; padding:4px 5px;"></span>
                                <div class="wid-20">
                                    <select class="form-control" id="logType" name="logType" style="width:145px;">
                                        <option value="">--Select Type--</option>
                                        <option value="BANK">Bank</option>
                                        <option value="DIVIDEND">Dividend</option>
                                        <option value="EXPENSE">Expense</option>
                                        <option value="INVOICE">Invoice</option>
                                        <option value="JOURNAL">Journal</option>
                                        <option value="LOGIN/LOGOUT">Login\Logout</option>
                                        <option value="NOTES">Notes</option>
                                        <option value="PAYROLL">Payroll</option>
                                        <option value="SALARY">Salary</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn_search btn-log-search" value="submit">
                                    <i class="fa fa-search"></i>Search
                                </button>
                                <a href="<?php echo site_url(); ?>logs" class="btn btn_search btn-reset" value="submit">
                                    <span class="glyphicon glyphicon-refresh"></span>Reset
                                </a>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>


                </div>
            </div>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-8 pull-right">
                        <?php
                        echo $pagination . '<br/>';
                        ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div id="TBListing" class="table-responsive" style="overflow-y:none;">
                <table>
                    <thead>
                        <tr class="salary-table" >
                            <th >
                    <center><a class="color" href="#"><?php echo $this->lang->line('LOG_LIST_S_NO'); ?>#</a></center>
                    </th>
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('LOG_LIST_DATE'); ?></a>
                    </th>
                    
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('LOG_LIST_DESCRIPTION'); ?></a>
                    </th>
                    <!--<th class="text-center">
                      <a class="color" href="#"><?php echo $this->lang->line('LOG_LIST_ID'); ?></a>
                    </th> -->
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('LOG_LIST_TYPE'); ?></a>
                    </th>
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('LOG_ITEM_ID'); ?></a>
                    </th>
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('LOG_LIST_NAME'); ?></a>
                    </th>
                    </tr>
                    </thead>
                    <tbody id="log-tbody">
                        <?php $this->load->view('client/logs/log_listing', $data); ?>
                    </tbody>
                </table>
            </div>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-8 pull-right">
                        <?php
                        echo $pagination . '<br/>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="dialog"></div>
<?php $this->load->view('client/footer'); ?>
<div class="modal fade modal-view-logs" id="modal-view-items" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 90%; height: auto; max-height: 100%;">
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
                <button type="button" class="btn btn-danger btn-sm spacer" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
