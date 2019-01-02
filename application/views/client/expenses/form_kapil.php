<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$acc_id = clientAccess();
if ($acc_id != 0) {
    $access = 1;
} else {
    $access = 0;
}

$paidDate = $item['PaidOn'];

if (count($item) <= 0) {
    $item['ID'] = '';
    $item['EmployeeID'] = 0;
    $item['Month'] = date('m');
    $item['Year'] = date('Y');
    $item['PaidOn'] = 0;
    $item['Miles'] = 0;
    $item['ExpenseType'] = 'EXPENSE';
} else {
    if (cDate($item['PaidOn']) == '') {
        $item['PaidOn'] = 0;
    } else {
        $item['PaidOn'] = 1;
    }
}

$user = $this->session->userdata('user');
if ($user['VAT_TYPE'] == 'stand') {
    $colspan_one = "";
    $colspan_two = "colspan='2'";
} else {
    $colspan_one = "colspan='2'";
    $colspan_two = "";
}
?>
<?php echo form_open($form_link, array('id' => $form_id)); ?>
<section>
    <div class="panel panel-default panel_custom">
        <div class="panel-body row">
            <div class="col-md-4  col-sm-3 col-xs-12 padding_field">
                <div class="wid-40">
                    <label><?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_EMP_NAME'); ?></label>
                </div>
                <div class="wid-60">
                    <?php
                    echo form_dropdown('eCustomer', $users, $item['EmployeeID'], 'id="eCustomer" class="required"');
                    ?>
                </div>
            </div>
            <div class="col-md-4  col-sm-3 col-xs-12 padding_field">
                <div class="wid-30">
                    <label><?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MONTH'); ?></label>
                </div>
                <div class="wid-70" >
                    <?php echo form_dropdown('Month', month(), $item['Month'], 'class="form-control required" id="Month"'); ?>
                </div>
            </div>
            <div class="col-md-4  col-sm-3 col-xs-12 padding_field">
                <div class="wid-30">
                    <label><?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_YEAR'); ?></label>
                </div>
                <div class="wid-70" >
                    <?php
                    $years = array('0' => 'Select Year');
                    for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++) {
                        if ($check != null) {
                            $years[] = $i;
                        } else {
                            $years[$i] = $i;
                        }
                    }

                    echo form_dropdown('Year', $years, $item['Year'], 'class="form-control" id="Year"');
                    ?>
                </div>
            </div>

            <?php
            if ($item['Status'] == 3) {
                ?>
                <input type="hidden" name="status" value="<?php echo $item['Status']; ?>">
                <div class="col-md-4  col-sm-3 col-xs-12 padding_field" style="float:right;">
                     <br>
                    <div class="wid-30">
                        <label><?php echo 'Date Paid'; ?></label>
                    </div>
                    <div class="wid-70" >
                    <input type="text" name="PaidOn" class="datepicker form-control" id="paidDate" value="<?php
                    if ($paidDate == '01-01-1970' || $paidDate == '30-11--0001' || $paidDate == '') {
                        echo '';
                    } else {
                       echo  date('d-m-Y', strtotime($paidDate));
                    }
                    ?>"/>
                    </div>
                </div>
                <?php
            }
            else{
                ?>
                <input type="hidden" name="PaidOn" class="datepicker form-control" value="">
                <?php
            }
            ?>
        </div>
    </div>
    <div class="panel panel-default panel_custom">
        <div class="panel-body">
            <div class="popup">
                <div class="clr"></div>
                <table class="tbl-editable" id="invoiceTable">
                    <thead>
                        <tr>
                            <th>
                                <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_ITEM'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_DATE'); ?>
                            </th>
							<th>
                                <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MAIN_CATEGORY'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_CATEGORY'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_DESCRIPTION'); ?>
                            </th>
                            <th <?php //echo $colspan_one; ?>>
                                <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_NET_AMOUNT'); ?>
                            </th>
                            <?php //if ($user['VAT_TYPE'] == 'stand'): ?>
                                <th <?php //echo $colspan_two; ?>>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_VAT_PRESENTAGE'); ?>
                                </th>
								<th <?php //echo $colspan_two; ?>>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_VAT_PAID'); ?>
                                </th>
                            <?php //endif; ?>
							<!--th <?php echo $colspan_one; ?>>
                                <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_AMOUNT'); ?>
                            </th--> <th> </th>
                        </tr>
                    </thead>
                    <tbody id="expenseListItem">
                        <?php $this->load->view('client/expenses/expense_items'); ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <a class="btn btn-primary btn-sm addExpenseItem" href="#">
                                    <i class="fa fa-plus"></i><?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_ADD_ITEM'); ?>
                                </a>
                            </td>
                            <td></td>
							<td></td>
                            <td class=" text-right text_right">
                                <p class="pull-right">
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_TOTAL'); ?>
                                </p>
                            </td>
                            <td class=" text-right">
                                <input type="text" class="sm-width validNumber TotalItemAmount form-control input_100percent" name="TotalItemAmount" readonly>
                            </td>
                            <?php //if ($user['VAT_TYPE'] == 'stand'): ?>
                                <td>
                                    <input type="text" class="sm-width validNumber form-control TotalVATAmount" name="TotalVATAmount" readonly>

                                </td>
                            <?php //endif; ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php if ($task != 'addCreditCard' && $item['ExpenseType'] != 'CREDITCARD'): ?>
                <div class="popup">
                    <table class="tbl-editable" id="invoiceTable">
                        <thead>
                            <tr>
                                <th>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_ITEM'); ?>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MILEAGE_DATE'); ?>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_FROM'); ?>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_TO'); ?>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_METHOD'); ?>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_PURPOSE'); ?>
                                </th>
                                <th colspan="2">
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MILES'); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="expenseMileageItem">
                            <?php $this->load->view('client/expenses/expense_mileage'); ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <a class="btn btn-primary btn-sm addExpenseMileage" href="#">
                                        <i class="fa fa-plus"></i><?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_ADD_ITEM'); ?>
                                    </a>
                                </td>
                                <td colspan="3">
                                    <label>Car miles logged so far : <span id="prevMiles"><?php echo $item['Miles']; ?></span> miles  in </label>

                                    <?php
                                    echo form_dropdown('mileage_year', year(), $item['Year'], 'class="wid-20 form-control required mileage_year" id="mileage_year"');
                                    ?>
                                </td>
                                <td class=" text-right text_right">
                                    <p>
                                        <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_TOTAL_MILES'); ?>
                                    </p>
                                    <br/>
                                    <p>
                                        <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MILEAGE_EXPENSED'); ?>
                                    </p>
                                </td>
                                <td class=" text-right">
                                    <input type="text" class="sm-width validNumber form-control totalMiles input_100percent" name="TotalMiles[]" readonly>
                                    <input type="text" class="sm-width validNumber form-control top_space MileageExpensed input_100percent" name="MileageExpensed[]" readonly>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
            <div class="clr"></div>
            <div class="row top_space col-md-12">
                <?php if ($access): ?>
                    <!--div class="col-md-7 col-sm-3 col-xs-12 padding_field">
                            <div class="col-md-3">
                                    <label>Mark as Paid:</label>
                            </div>
                            <div class="col-md-1">
                    <?php
                    if ($item['PaidOn'] == 0) {
                        $checked = "";
                    } else {
                        $checked = "checked='checked'";
                    }
                    ?>
                                    <input type="checkbox"name="ExpensePaid" <?php echo $checked; ?>>
                            </div>
                    </div -->
                <?php endif; ?>
                <div class="col-md-12 col-sm-3 col-xs-12 ">
                    <div class="pull-right">
                        <div class="wid-40">
                            <label><?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_TOTAL_EXPENSES'); ?></label>
                        </div>
                        <div class="wid-60 pull-right text-right">
                            <input type="text" class="form-control TotalExpenseAmount" name="TotalExpenseAmount" placeholder="" readonly />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal-footer">
    <div class="pull-right col-md-6">
        <?php if ($task != 'editExpense'): ?>
            <a id="save-expense" class="btn btn-primary btn-sm spacer" href="#">
                <i class="glyphicon glyphicon-floppy-save"></i>&nbsp;<?php echo $this->lang->line('BUTTON_DRAFT'); ?>
            </a>
            <a id="create-expense" class="btn btn-success btn-sm spacer" href="#">
                <i class="fa fa-file-text"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CREATE_AND_FINISH'); ?>
            </a>
        <?php else: ?>
            <!-- <a id="update-expense" class="btn btn-primary btn-sm spacer" href="#">
                 <i class="glyphicon glyphicon-floppy-save"></i>&nbsp;<?php echo $this->lang->line('BUTTON_UPDATE'); ?>
             </a> -->
            <a id="createUpdateExpense" class="btn btn-success btn-sm spacer" href="#">
                <i class="fa fa-file-text"></i>&nbsp;<?php echo 'Update'; ?>
            </a>
        <?php endif; ?>
        <a data-dismiss="modal" class="btn btn-danger btn-sm spacer" href="#">
            <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CANCEL'); ?>
        </a>
    </div>
</div>
<input type="hidden" name="id" value="<?php echo $this->encrypt->encode($item['ID']); ?>"/>
<input type="hidden" name="task" id="task" value=""/>
<?php echo form_close(); ?>