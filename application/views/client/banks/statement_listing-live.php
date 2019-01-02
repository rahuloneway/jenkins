<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$access = clientAccess();
$sn = 1;
$temp_statement_record = $this->session->userdata('temp_statement_record');
//echo '<pre>';print_r($temp_statement_record);echo '</pre>';die('d');
if (!empty($temp_statement_record)) {
    $temp_statement_record = json_decode($temp_statement_record);
    foreach ($temp_statement_record as $key => $val) {
        $items[$key]->AssociatedWith = $val->ItemID;
        $items[$key]->StatementType = $val->ItemType;
    }
}
$updated_statements = json_encode($items);
$_SESSION['bank_statements'] = $updated_statements;
//echo "<pre>";print_r($items);die('dd');
if (!empty($items)) { //echo count($items);die;
    foreach ($items as $key => $val) {
        $href = $this->encrypt->encode($key);
        if ($val->AssociatedWith == 0) {
            ?>
            <tr>
                <td width="2%">
            <?php
            echo $sn;
            echo '<input type="hidden" name="statement_key[]" class="statement_key" value="' . $href . '"/>';
            ?>

                </td>
                <td width="10%">
                    <input type="text" name="Date[]" class="form-control sDatepicker required" value="<?php echo cDate($val->TransactionDate); ?>"/>
                </td>
                <td class="sm-width-box">
                    <select name="Type[]" class="form-control selectCls required statementtype">
                        <option value="">--select--</option>
                        <option <?php if (isset($val->Type) == 'Payment') { ?> select="selected" <?php } ?> value="Payment">Payment</option>
                        <option <?php if (isset($val->Type) == 'Receipts') { ?> select="selected" <?php } ?> value="Receipts">Receipts
                        </option>
                        <option <?php if (isset($val->Type) == 'Chq') { ?> select="selected" <?php } ?> value="Chq">Chq</option>
                        <option <?php if (isset($val->Type) == 'Deposit') { ?> select="selected" <?php } ?> value="Deposit">Deposit</option>
                        <option <?php if (isset($val->Type) == 'Other') { ?> select="selected" <?php } ?> value="Other">Other</option>
                    </select>	
                </td>
                <td class="sm-width-box">
                    <input type="text" name="Description[]" class="form-control"value="<?php echo $val->Description; ?>"/>
                </td>
                <td class="sm-width">
                    <input type="text" name="MoneyOut[]" class="form-control MoneyOut validNumber"value="<?php echo emptyNumber($val->MoneyOut); ?>"/>
                </td>
                <td class="sm-width">
                    <input type="text" name="MoneyIn[]" class="form-control MoneyIn validNumber"value="<?php echo @emptyNumber($val->MoneyIn); ?>"/>
                </td>
                <td class="sm-width">
                    <input type="text" name="Balance[]" class="form-control balance validNumber" value="<?php echo emptyNumber($val->Balance); ?>"/>
                </td>                
                 <td style="width:17%;">
            <?php echo exCategories('BANK', "Category[]", $val->Category, 'class="form-control category sm-width-box"') ?>
                </td>
                <td class="sm-width-box">
                	<input type="text" name="maincategory[]" id="maincategory[]" class="form-control tdtab" value="" readonly="readonly"/>
                </td>
                <td width="16%">
                    <?php
                    if ($access) {
                        if ($val->StatementType == 'I' && $val->AssociatedWith == 0) {
                            echo '<a class="btn btn-primary btn-xs color createBankInvoice" href="' . $href . '">CREATE INVOICE</a>';
                        } elseif ($val->StatementType == 'D' && $val->AssociatedWith == 0) {
                            echo '<a class="btn btn-success btn-xs color createBankDividend" href="' . $href . '">CREATE DIVIDEND</a>';
                        } else {
                            echo itemNumber($val->AssociatedWith, $val->StatementType);
                        }
                        ?>
                            <?php
                            echo '<a class="btn btn-primary btn-xs color createBankInvoice hide" href="' . $href . '">CREATE INVOICE</a>';
                            echo '<a class="btn btn-success btn-xs color createBankDividend hide" href="' . $href . '">CREATE DIVIDEND</a>';
                        } else {
                            echo itemNumber($val->AssociatedWith, $val->StatementType);
                        }
                        ?>
                    <?php
                    if ($key > 0 && $page1 == 'addmanual') {
                        echo '<a class="btn removeStatementItem"><i class="fa fa-times"></i></a>';
                    }
                    ?>
                </td>
            </tr>
                    <?php
                } else {
                    ?>
            <tr>
                <td>
            <?php
            echo $sn;
            echo '<input type="hidden" name="statement_key[]" class="statement_key" value="' . $href . '"/>';
            ?>
                </td>
                <td>
                    <?php
                    echo cDate($val->TransactionDate);
                    echo '<input type="hidden" name="Date[]" class="form-control sDatepicker sm-width-box"value="' . cDate($val->TransactionDate) . '"/>';
                    ?>
                </td>
                <td>
                    <?php
                    echo $val->Type;
                    echo '<input type="hidden" name="Type[]" class="form-control sm-width-box" value="' . $val->Type . '"/>';
                    ?>
                </td>
                <td>
                    <?php
                    echo $val->Description;
                    echo '<input type="hidden" name="Description[]" class="form-control" value="' . $val->Description . '"/>';
                    ?>
                </td>
                <td>
                    <?php
                    echo numberFormat($val->MoneyOut);
                    echo '<input type="hidden" name="MoneyOut[]" class="form-control" value="' . $val->MoneyOut . '"/>';
                    ?>
                </td>
                <td>
                    <?php
                    echo numberFormat($val->MoneyIn);
                    echo '<input type="hidden" name="MoneyIn[]" class="form-control" value="' . $val->MoneyIn . '"/>';
                    ?>
                </td>
                <td>
                    <?php
                    echo numberFormat($val->Balance);
                    echo '<input type="hidden" name="Balance[]" class="form-control" value="' . $val->Balance . '"/>';
                    ?>
                </td>
                <td>
                    <?php
                    echo categoryName($val->Category);
                    echo '<input type="hidden" name="Category[]" class="form-control" value="' . $val->Category . '" />';
                    ?>
                </td>
                <td>
                    <?php
                    echo itemNumber($val->AssociatedWith, $val->StatementType);
                    ?>
                </td>
            </tr>
                    <?php
                }
                $sn++;
            }
        }
        ?>
	

