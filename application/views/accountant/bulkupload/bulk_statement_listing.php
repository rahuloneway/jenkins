<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
//echo '<pre>';print_r($items);echo '</pre>'; die;
$sn = 1;
$temp_statement_record = $this->session->userdata('temp_statement_record');
if (!empty($temp_statement_record)) {
    $temp_statement_record = json_decode($temp_statement_record);
    foreach ($temp_statement_record as $key => $val) {
        $items[$key]->AssociatedWith = $val->ItemID;
        $items[$key]->StatementType = $val->ItemType;
    }
}
$updated_statements = json_encode($items);
$_SESSION['bulk_bank_statements'] = $updated_statements;


$category = bankCategory();
$bulksearch_before = $_SESSION['Bulk_BeforeBankSearch'];
$I = '';
$D = '';
foreach ($items as $key => $val) {
    $a = $val->AssociatedWith;
    $b = $bulksearch_before['companyname'];
    if ($a == $b) {
        $display = '';
    }
    if ($a != $b) {
        $display = 'display:none';
    }
    if ($b == '') {
        $display = '';
    }
    ?>
    <tr style="<?php echo $display; ?>">
        <td>
            <?php
            echo $sn;
            echo '<input type="hidden" name="statement_key[]" class="statement_key" value="' . $href . '"/>';
            ?>

        </td>
        <td class="xs-width-boxs">
            <input type="text" name="Date[]" class="form-control sDatepicker required sm-width-boxs"value="<?php echo cDate($val->TransactionDate); ?>"/>
        </td>
        <td class="xs-width-boxs">
            <input type="text" name="Type[]" class="form-control sm-width-boxs" value="<?php echo $val->Type; ?>"/>
        </td>
        <td class="lm-width-box">
            <input type="text" name="Description[]" class="form-control" value="<?php echo bulk_clean_desp($val->Description); ?>"/>
        </td>
        <td class="xs-width-box">
            <input type="text" name="MoneyOut[]" class="form-control MoneyOut validNumber" value="<?php echo emptyNumber($val->MoneyOut); ?>"/>
        </td>
        <td class="xs-width-box">
            <input type="text" name="MoneyIn[]" class="form-control MoneyIn validNumber" value="<?php echo emptyNumber($val->MoneyIn); ?>"/>
        </td>
        <td class="xs-width-box">
            <input type="text" name="Balance[]" class="form-control balance validNumber" value="<?php echo emptyNumber($val->Balance); ?>"/>
        </td>
        <td>
            <?php
            echo categoryName($val->Category);
            echo '<input type="hidden" name="Category[]" class="form-control" value="' . $val->Category . '" />';
            ?>
        </td>           
    </tr>
    <?php
    $sn++;
}
?>