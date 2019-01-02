<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$user = $this->session->userdata('user');
$acc_id = clientAccess();
if ($acc_id != 0) {
    $access = 1;
} else {
    $access = 0;
}
?>
<section>

    <div class="panel panel-default panel_custom">
        <div class="panel-body row">
            <div class="col-md-4  col-sm-3 col-xs-12 padding_field padd_bott">
                <div class="wid-40">
                    <label><?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_EMP_NAME'); ?></label>
                </div>
                <div class="wid-60 marg_bott">
                    <?php echo getEmployeeInfo($item['EmployeeID']); ?>
                </div>
            </div>
            <div class="col-md-4  col-sm-3 col-xs-12 padding_field padd_bott">
                <div class="wid-30">
                    <label><?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MONTH'); ?></label>
                </div>
                <div class="wid-70 marg_bott" > 
                    <?php echo date("M", mktime(0, 0, 0, $item['Month'], 1, 0)); ?>
                </div>
            </div>
            <div class="col-md-4  col-sm-3 col-xs-12 padding_field padd_bott">
                <div class="wid-30">
                    <label><?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_YEAR'); ?></label>
                </div>
                <div class="wid-70 " > 
                    <?php echo $item['Year']; ?>
                </div>
            </div>
        </div>
    </div>
    <?php if (count($item['ExpenseItems']) != 0): ?>
        <div class="panel ">
            <div class="panel-body padding-zero">
                <div class="popup">
                    <div class="clr"></div>
                    <table class="tbl-editable" id="invoiceTable">
                        <thead class="tbody_bg">
                            <tr>
                                <th>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_ITEM'); ?>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_DATE'); ?>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_CATEGORY'); ?>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_AMOUNT'); ?>
                                </th>
                                <?php if ($user['VAT_TYPE'] == 'stand'): ?>
                                    <th>
                                        <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_VAT_PAID'); ?>
                                    </th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $expense_total = 0;
                            $vat_amount = 0;
                            foreach ($item['ExpenseItems'] as $key => $val):
                                ?>
                                <tr>
                                    <td class="sno">
                                        <?php echo ($key + 1); ?>
                                    </td>
                                    <td>
                                        <?php echo cDate($val->ItemDate); ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo categoryName($val->Category);
                                        ?>
                                    </td>
                                    <td width="100px" align="right">
                                        <?php echo numberFormat($val->Amount); ?>
                                    </td>
                                    <?php if ($user['VAT_TYPE'] == 'stand'): ?>
                                        <td>
                                            <?php
                                            echo numberFormat($val->VATAmount);
                                            $vat_amount += $val->VATAmount;
                                            ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>	
                                <?php
                                $expense_total += $val->Amount;
                            endforeach;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="well text-right text_right">
                                    <span class="text-right">
                                        <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_TOTAL'); ?>
                                    </span>
                                </td>
                                <td class="well text-right">
                                    <?php echo numberFormat($expense_total); ?>
                                </td>
                                <?php if ($user['VAT_TYPE'] == 'stand'): ?>
                                    <td class="well text-left">
                                        <?php echo numberFormat($vat_amount); ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (count($item['ExpenseMileage']) != 0): ?>
        <div class="panel ">
            <div class="panel-body padding-zero">
                <div class="popup">
                    <table class="tbl-editable" id="invoiceTable">
                        <thead class="tbody_bg">
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
                        <tbody>
                            <?php
                            $total_miles = 0;
                            $mileage_expensed = 0;
                            $car_miles_count = 0;
                            foreach ($item['ExpenseMileage'] as $key => $val):
                                ?>
                                <tr id="r1">
                                    <td class="sno">
                                        <?php echo $key + 1; ?>
                                    </td>
                                    <td width="200px">
                                        <?php echo cDate($val->ItemDate); ?>
                                    </td>
                                    <td>
                                        <?php echo $val->LocationFrom; ?>
                                    </td>
                                    <td>
                                        <?php echo $val->LocationTo; ?>
                                    </td>
                                    <td>
                                        <?php echo categoryName($val->Category); ?>		
                                    </td>
                                    <td>
                                        <?php echo $val->Purpose; ?>
                                    </td>
                                    <td width="50px" class="text-right">
                                        <?php echo $val->Miles; ?>
                                    </td>
                                </tr>
                                <?php
                                $mileage_expensed += $val->Amount;
                                $total_miles += $val->Miles;
                                if ($val->Category == 32) {
                                    $car_miles_count += $val->Miles;
                                }
                            endforeach;
                            $car_miles = $item['Miles'] + $car_miles_count;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="well">
                                    <label>
                                        <?php
                                        if ($car_miles != 0):
                                            echo sprintf($this->lang->line('EXPENSE_TABLE_COLUMN_MILES_LOGGED'), $car_miles);
                                        endif;
                                        ?>
                                    </label>
                                </td>
                                <td class="well text-right text_right">
                                    <span>
                                        <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_TOTAL_MILES'); ?>
                                    </span>
                                </td>
                                <td class="well text-right">
                                    <span><?php echo $total_miles; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="well text-right text_right">
                                    <span>
                                        <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MILEAGE_EXPENSED'); ?>
                                    </span>
                                </td>
                                <td class="well text-right">
                                    <span><?php echo numberFormat($mileage_expensed); ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="well text-right text_right">
                                    <span>
                                        <?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_TOTAL_EXPENSES'); ?>
                                    </span>
                                </td>
                                <td class="well text-left">
                                    <span><?php echo numberFormat($item['TotalAmount']); ?></span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="clr"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php echo form_close(); ?>