<?php 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$this->load->view('client/header', array('page' => 'customers', 'title' => $title));
error_reporting(0);
?>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum">
            <h4><?php echo $this->lang->line('CUSTOMERS_PAGE_TITLE'); ?></h4>
            <?php echo $this->session->flashdata('customersMessage'); ?>
            <div class="panel panel-default panel_custom">
                <div class="panel-body ">
                    <div class="row">
                        <div class="col-md-6 col-sm-3 col-xs-12 padding_field">
                            <div class="wid-30">
                                <label>Customer Name</label>
                            </div>
                            <div class="wid-40">
                                <?php
                                echo form_open(site_url() . 'customers', array('name' => 'customer-search', 'id' => 'customer-search'));
                                ?>
                                <select class="form-control" id="customer" name="customer">
                                    <option selected="selected" value="0">Select Customer</option>
                                    <?php
                                    if (!empty($customerlist)) {
                                        foreach ($customerlist as $key => $value) {
                                            if ($value['Id'] == $customerId) {
                                                $sel = 'selected="selected"';
                                            } else {
                                                $sel = '';
                                            }
                                            echo '<option ' . $sel . ' value="' . $value['Id'] . '" >' . $value['customername'] . '</option>';
                                        }
                                    } else {
                                        
                                    }
                                    ?>
                                </select>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="clearfix"></div>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-2  col-sm-3 col-xs-12 btn_centre_bot"> 
                        <a href="#" type="button" class="btn btn-inverse add-customers">
                            <span class="glyphicon glyphicon-plus"></span>
                            Add Customer			
                        </a> 
                    </div>
                    <div class="col-md-8 pull-right bPagination">
                        <?php
                        echo $pagination . '<br/>';
                        ?>
                    </div>
                </div>
            </div>
            <div class="listing-container"> 
                <div id="reset-position" class="table-responsive">  
                    <table id="example-advanced">
                        <thead>
                            <tr class="table-header">
								<th><?php echo $this->lang->line('CUSTOMERS_ID'); ?></th>
								<!--th><center><?php echo $this->lang->line('CUSTOMERS_NAME'); ?></center></th-->
								<th><center><?php echo $this->lang->line('CUSTOMERS_COMPANY'); ?></center></th>
								<th><center><?php echo $this->lang->line('CUSTOMERS_EMAIL'); ?></center></th>
								<th><center><?php echo $this->lang->line('CUSTOMERS_MOBILE'); ?></center></th>
								<th><center><?php echo $this->lang->line('CUSTOMERS_DATE'); ?></center></th>
								<th><center><?php echo $this->lang->line('CUSTOMERS_ADD_STATUS'); ?></center></th>
								<th><center><?php echo $this->lang->line('CUSTOMERS_ACTION'); ?></center></th>
							</tr>
                        </thead>
                        <tbody id="client-listing">
                            <?php $this->load->view('client/customers/listing', $data); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-2  col-sm-3 col-xs-12 btn_centre_bot"> 
                        <a href="#" type="button" class="btn btn-inverse add-customers">
                            <span class="glyphicon glyphicon-plus"></span>
                            Add Customer
                        </a> 
                    </div>
                    <div class="col-md-8 pull-right bPagination">
                        <?php
                        echo $pagination . '<br/>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	
	<div class="modal fade modal-invoice" id="modal-invoice" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
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
	
</section>


<?php $this->load->view('client/footer'); ?>