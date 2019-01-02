<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$this->load->view('accountant/header', array('page' => $page, 'title' => $title));
?>
<?php
$user = $this->session->userdata('user');
if (count($items) == 0) {
    $items = array(
        'Invoice_listing' => '',
        'Expense_listing' => '',
        'Expense_report' => '',
        'Term_listing' => '',
        'Email_listing' => '',
        'Bulk_email_log_listing' => '',
        'Dividend_listing' => '',
        'Bank_listing' => '',
        'Journal_listing' => '',
        'Client_listing' => '',
        'Accountant_listing' => '',
        'Ledger_listing' => '100',
        'Salary_listing' => '',
        'Contact_email' => '',
        'Car_mileage_cost' => '',
        'Bike_mileage_cost' => '',
        'Bicycle_milege_cost' => '',
        'Travelling_distance' => '',
        'VAT_percentage' => '',
        'Expense_template_text_one' => '',
        'Expense_template_text_two' => '',
        'Signature_image_limit' => '',
        'Logo_image_limit' => '',
        'Corporation_tax' => '',
        'TAX_slab_start_date' => '',
        'TAX_slab_end_date' => '',
        'Car_mileage_overdue_cost' => '',
        'Bike_mileage_overdue_cost' => '',
        'Bicycle_mileage_overdue_cost' => '',
        'Tax_able_income' => array('0' => ''),
        'Financial_year' => array('0' => 0),
        'tax_free_dividend_allow' => '',
        'basic_dividend_task' => '',
        
    );
} else {
    $items['Tax_able_income'] = explode(',', $items['Tax_able_income']);
    $items['Financial_year'] = explode(',', $items['Financial_year']);
}
?>
<section class="grey-body">
    <?php
    echo form_open(site_url() . 'accountant/configuration/save', array('name' => 'configuration'));
    ?>
    <div class="container-fluid ">
        <div class="account_sum ">
            <div class="row">
                <div class="col-md-4 ">
                    <h4>Global Configuration</h4>

                </div>
                <div class="col-md-1 pull-right">
                    <button class="btn btn-success color">
                        <i class="fa fa-floppy-o"></i>Save
                    </button>
                </div>
                <div class="clr"></div><br/>
                <?php echo $this->session->flashdata('configErrors'); ?>
            </div>
            <div class="clr"></div>
            <div class="row border_box_trial margin-box">
                <div class="col-md-12 border_box_trial no_padding">
                    <!-- tabs left -->
                    <div class="tabbable tabs-left ">
                        <ul class="nav nav-tabs col-md-2 no_padding" id="myTab">
                            <li class="active">
                                <a href="#a" data-toggle="tab">Page Listing</a>
                            </li>
                            <li>
                                <a href="#b" data-toggle="tab">Email</a>
                            </li>
                            <li>
                                <a href="#c" data-toggle="tab">Mileage</a>
                            </li>
                            <li>
                                <a href="#d" data-toggle="tab">Govt. Taxes</a>
                            </li>
                             <li>
                                <a href="#i" data-toggle="tab">Dividend</a>
                            </li>
                            <li>
                                <a href="#e" data-toggle="tab">Expense template</a>
                            </li>
                            <li>
                                <a href="#f" data-toggle="tab">Image </a>
                            </li>
                            <li class="tc">
                                <a href="#h" data-toggle="tab"><?php echo $this->lang->line('MAIL_CONFIGURATION'); ?></a>
                            </li>
                            <li class="tc">
                                <a href="#g" data-toggle="tab"><?php echo $this->lang->line('EMAIL_TEMPLATE'); ?></a>
                            </li>
							<li>
                                <a href="#vc" data-toggle="tab">VAT Credential</a>
                            </li>
                        </ul>
                        <div class="tab-content col-md-10 no_padding">
                            <div class="tab-pane active fade in" id="a">
                                <div class="border_box row">
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Invoice Listing pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Invoice_listing" class="form-control validNumber" value="<?php echo $items['Invoice_listing']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Expense Listing pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Expense_listing" class="form-control validNumber"value="<?php echo $items['Expense_listing']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Expense Report pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Expense_report" class="form-control validNumber"value="<?php echo $items['Expense_report']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Dividend Listing pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Dividend_listing" class="form-control validNumber"value="<?php echo $items['Dividend_listing']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Bank Statements Listing pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Bank_listing" class="form-control validNumber"value="<?php echo $items['Bank_listing']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Journal Listing pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Journal_listing" class="form-control validNumber"value="<?php echo $items['Journal_listing']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Client Listing pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Client_listing" class="form-control validNumber"value="<?php echo $items['Client_listing']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Accountant Listing pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Accountant_listing" class="form-control validNumber"value="<?php echo $items['Journal_listing']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Salary & Payee Listing pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Salary_listing" class="form-control validNumber"value="<?php echo $items['Journal_listing']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Ledger pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Ledger_listing" class="form-control validNumber"value="<?php echo $items['Ledger_listing']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Terms and conditions pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Term_listing" class="form-control validNumber"value="<?php echo $items['Term_listing']; ?>">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6 spac_inpu_text">
                                         <div class="wid-60">
                                             <label><?php echo $this->lang->line('ACCOUNTANT_CONFIGURATION_ACTION_LOG_PAGINATION_LIMIT'); ?></label>
                                         </div>
                                         <div class="wid-40">
                                             <input type="text" placeholder="" name="Action_log_listing" class="form-control validNumber"value="<?php
                if (empty($items['Action_log_listing'])) {
                    echo '25';
                } else {
                    echo $items['Action_log_listing'];
                };
                ?>">
                                         </div>
                                     </div>-->
                                    <!--<div class="col-md-6 spac_inpu_text">
                                        <div class="wid-60">
                                            <label>Bulk email logs pagination limit</label>
                                        </div>
                                        <div class="wid-40">
                                            <input type="text" placeholder="" name="Bulk_email_log_listing" class="form-control validNumber"value="<?php echo $items['Bulk_email_log_listing']; ?>">
                                        </div>
                                    </div>-->
                                    <!-- <div class="col-md-6 spac_inpu_text">
                                         <div class="wid-60">
                                             <label><?php echo $this->lang->line('ACCOUNTANT_CONFIGURATION_EMAIL_PAGINATION_LIMIT'); ?></label>
                                         </div>
                                         <div class="wid-40">
                                             <input type="text" placeholder="" name="Email_listing" class="form-control validNumber"value="<?php
                                    if (empty($items['Email_listing'])) {
                                        echo '25';
                                    } else {
                                        echo $items['Email_listing'];
                                    }
                ?>">
                                         </div>
                                     </div>-->

                                </div>
                            </div>
                            <div class="tab-pane fade in" id="b">
                                <div class="border_box row">
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-50">
                                            <label>Contact us email settings</label>
                                        </div>
                                        <div class="wid-50">
                                            <input type="text" placeholder="" name="Contact_email"class="form-control"value="<?php echo $items['Contact_email']; ?>">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade in " id="c">
                                <div class="border_box row">
                                    <div class="col-md-12">
                                        <label>Tax: rates per bussiness mile</label>
                                    </div>
                                    <div class="col-md-12">
                                        <table style="width:100%" border="0">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Types of vehicle
                                                    </th>
                                                    <th>
                                                        First 10,000 miles
                                                    </th>
                                                    <th>
                                                        Above 10,000 miles
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        Cars and vans
                                                    </td>
                                                    <td>
                                                        <input type="text" placeholder="" name="Car_mileage_cost"class="form-control validNumber"value="<?php echo $items['Car_mileage_cost']; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" placeholder="" name="Car_mileage_overdue_cost" class="form-control validNumber"value="<?php echo $items['Car_mileage_overdue_cost']; ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Motorcycles
                                                    </td>
                                                    <td>
                                                        <input type="text" placeholder="" name="Bike_mileage_cost" class="form-control validNumber bike_one"value="<?php echo $items['Bike_mileage_cost']; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" placeholder="" name="Bike_mileage_overdue_cost" class="form-control validNumber bike_two"value="<?php echo $items['Bike_mileage_overdue_cost']; ?>" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Bikes
                                                    </td>
                                                    <td>
                                                        <input type="text" placeholder="" name="Bicycle_milege_cost" class="form-control validNumber cycle_one"value="<?php echo $items['Bicycle_milege_cost']; ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" placeholder="" name="Bicycle_mileage_overdue_cost" class="form-control validNumber cycle_two"value="<?php echo $items['Bicycle_mileage_overdue_cost']; ?>" readonly>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="clr"></div><br/><br/>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-50">
                                            <label>Maximum Distance</label>
                                        </div>
                                        <div class="wid-50">
                                            <input type="text" placeholder="" name="Travelling_distance" class="form-control validNumber"value="<?php echo $items['Travelling_distance']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane animate" id="d">
                                <div class="border_box row">
                                    <div class="col-md-12 spac_inpu_text">
                                        <div class="col-md-4">
                                            <label>VAT %</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" placeholder=""class="form-control validNumber" name="VAT_percentage"value="<?php echo $items['VAT_percentage']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12 spac_inpu_text">
                                        <div class="col-md-4">
                                            <label>Corporation tax</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" placeholder=""class="form-control validNumber" name="Corporation_tax"value="<?php echo $items['Corporation_tax']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12 spac_inpu_text tax-income">
                                        <?php if (count($items['Tax_able_income']) == 0): ?>
                                            <div class="taxable-income">
                                                <div class="col-md-4">
                                                    <label>Individual taxable income (upto 20%)</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" placeholder=""class="form-control" name="Tax_able_income[]"value="<?php echo $items['Tax_able_income'][0]; ?>"/>
                                                </div>
                                                <div class="col-md-1">
                                                    <label>Year</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <?php
                                                    $year = financial_year();
                                                    echo form_dropdown('Financial_year[]', $year, $items['Financial_year'][0], 'class="form-control"');
                                                    ?>
                                                </div>
                                                <div class="col-md-1 hide">
                                                    <a href="#" class="remove-item btn btn-danger btn-xs">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                </div>
                                                <div class="clr"></div><br/>
                                            </div>
                                        <?php else: ?>
                                            <?php
                                            foreach ($items['Tax_able_income'] as $key => $val) {
                                                ?>
                                                <div class="taxable-income">
                                                    <div class="col-md-4">
                                                        <label>Individual taxable income (upto 20%)</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" placeholder=""class="form-control" name="Tax_able_income[]"value="<?php echo $items['Tax_able_income'][$key]; ?>"/>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label>Year</label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php
                                                        $year = financial_year();
                                                        echo form_dropdown('Financial_year[]', $year, $items['Financial_year'][$key], 'class="form-control"');
                                                        ?>
                                                    </div>
                                                    <div class="col-md-1 hide">
                                                        <a href="#" class="remove-item btn btn-danger btn-xs">
                                                            <i class="fa fa-close"></i>
                                                        </a>
                                                    </div>
                                                    <div class="clr"></div><br/>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-12">
                                        <a href="#" class="pull-right btn btn-success add-item">Add Item</a>
                                    </div>
                                    <div class="clr"></div><br/>
                                </div>
                            </div>
							<div class="tab-pane animate" id="vc">
                                <div class="border_box row">
                                    <div class="col-md-12 spac_inpu_text">
                                        <div class="col-md-4">
                                            <label>User ID</label>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" placeholder=""class="form-control" name="Vat_userid"value="<?php echo $items['Vat_userid']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12 spac_inpu_text">
                                        <div class="col-md-4">
                                         <label>Password</label>
                                        </div>
                                        <div class="col-md-4">
                                         <input type="text" placeholder=""class="form-control" name="Vat_pass"
                                            value="<?php echo $items['Vat_pass']; ?>"/>
                                        </div>                                         
                                    </div> 
                                    <div class="clr"></div><br/>
                                </div>
                            </div>
                            <div class="tab-pane fade in animate" id="e">
                                <div class="border_box row">
                                    <div class="col-md-12 spac_inpu_text">
                                        <div class="col-md-3">
                                            <label>Text One</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="" name="Expense_template_text_one" class="form-control"value="<?php echo $items['Expense_template_text_one']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12 spac_inpu_text">
                                        <div class="col-md-3">
                                            <label>Text Two</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" placeholder="" name="Expense_template_text_two" class="form-control"value="<?php echo $items['Expense_template_text_two']; ?>"/>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade in animate" id="f">
                                <div class="border_box row">
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="col-md-6">
                                            <label>Signature image file size limit</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" placeholder="" name="Signature_image_limit" class="form-control input-xs pull-left"value="<?php echo $items['Signature_image_limit']; ?>"/>
                                            &nbsp;<b>KB</b>
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="col-md-5">
                                            <label>Logo image file size limit</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" placeholder="" name="Logo_image_limit" class="form-control input-xs pull-left" value="<?php echo $items['Logo_image_limit']; ?>"/>
                                            &nbsp;<b>KB</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="tab-pane fade in" id="i">
                                <div class="border_box row">
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-50">
                                            <label>Tax free Dividend Allowance</label>
                                        </div>
                                        <div class="wid-50">
                                            <input type="text" placeholder="" name="tax_free_dividend_allow"class="form-control validNumber"value="<?php echo $items['tax_free_dividend_allow']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 spac_inpu_text">
                                        <div class="wid-50">
                                            <label>Basic Dividend Tax %</label>
                                        </div>
                                        <div class="wid-50">
                                            <input type="text" placeholder="" name="basic_dividend_tax" class="form-control"value="<?php echo $items['basic_dividend_tax']; ?>">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade in" id="h">
                                <div class="border_box row">
                                    <div class="col-md-12 spac_inpu_text">
                                        <div class="wid-120">
                                            <div class="alert alert-warning fade in col-md-8">
                                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                                <strong><?php echo $this->lang->line('BUTTON_WARNING'); ?></strong> <?php echo $this->lang->line('MAIL_CONFIGURATION_WARNING_MESSAGE'); ?>
                                                <a class="btn  btn_search reset" type="button" href="<?php echo "configuration/resetemailconfig" ?>">
                                                    <span class="glyphicon glyphicon-refresh"></span><?php echo $this->lang->line('BUTTON_RESET'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-6 spac_inpu_text">

                                        <div class="wid-50">
                                            <label><?php echo $this->lang->line('MAIL_CONFIGURATION_SIGNATURE'); ?></label>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="wid-50">
                                            <input type="text" placeholder="" name="email_setting"class="form-control" value="<?php echo $emailsignature[0]->Email_Signature; ?>">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12 spac_inpu_text">
                                        <div class="wid-50">
                                            <label><?php echo $this->lang->line('MAIL_CONFIGURATION_RESEND_PASSWORD'); ?></label>
                                        </div>
                                        <div class="wid-80">
                                            <textarea class="form-control description" name="email_text" id="email_text">
                                                <?php
                                                if (!empty($emailsignature[0]->Email_Text)) {
                                                    echo $emailsignature[0]->Email_Text;
                                                } else {
                                                    echo $this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_RESET_MESSAGE');
                                                }
                                                ?>
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="col-md-12 spac_inpu_text">
                                        <div class="wid-50">
                                            <label><?php echo $this->lang->line('MAIL_CONFIGURATION_ACCOUNT_CREATED'); ?></label>
                                        </div>
                                        <div class="wid-80">
                                            <textarea class="form-control description" name="email_text_created_a" id="email_text">
                                                <?php
                                                if (!empty($emailsignature[0]->Email_Text_Created)) {
                                                    echo $emailsignature[0]->Email_Text_Created;
                                                } else {
                                                    echo $this->lang->line('ACCOUNTANT_NEW_ACCOUNT_MESSAGE');
                                                }
                                                ?>
                                            </textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade in" id="g">
                                <div class="border_box row">
                                    <div class="panel panel-default panel_custom">
                                        <div class="panel-body row ">
                                            <div class="col-md-3 pull-left">
                                                <a id="uploadtermStatements" href="#" class="btn btn-inverse config-add-email-template">
                                                    <i class="glyphicon glyphicon-plus"></i>
                                                    <?php echo $this->lang->line('ADD_TEMPLATE'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div cclass="clear"></div><br/>
                                    <table id="example-advanced">
                                        <thead>
                                            <tr class="salary-table">
                                                <th width="200">
                                                    <?php echo $this->lang->line('TEMPLATE_NAME'); ?>
                                                </th>
                                                <th width="500">
                                                    <?php echo $this->lang->line('TEMPLATE_MESSAGE'); ?>
                                                </th>
                                                <th width="100">
                                                    <?php echo $this->lang->line('TEMPLATE_MESSAGE_ACTION'); ?>
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody class="client-listing">
                                            <?php $this->load->view('accountant/configuration/template_listing', $template); ?>
                                        </tbody>
                                    </table>
                                    <div class="panel panel-default panel_custom">
                                        <div class="panel-body row ">
                                            <div class="col-md-3 pull-left">
                                                <a id="uploadtermStatements" href="#" class="btn btn-inverse config-add-email-template">
                                                    <i class="glyphicon glyphicon-plus"></i>

                                                    <?php echo $this->lang->line('ADD_TEMPLATE'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /tabs -->
                </div>
            </div>
        </div>
    </div>
    <?php
    echo form_close();
    ?>
</section>

<script>
    tinymce.init({selector:'textarea'});
</script>
<?php $this->load->view('accountant/footer'); ?>
<div class="modal fade modal select-email-template" id="select-email-template" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
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
        </div>
    </div>
</div>
