<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$acc_id = clientAccess();
if ($acc_id != 0) {
    $access = 1;
} else {
    $access = 0;
}
if ($InvoiceItems['InvoiceItems'][0]->Tax == 0) {
    $vr = 0;
} else {
    $vr = 1;
}
?>
<div class="panel panel-default panel_custom ">
    <div class="panel-body">
        <div class="col-md-2 col-sm-6 col-xs-12">
            <b><?php echo $this->lang->line('CLIENT_INVOICE_LABLE_INVOICE_NUMBER'); ?></b>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <?php echo $InvoiceItems['InvoiceNumber']; ?>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
            <b><?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_NAME'); ?></b>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <?php echo $InvoiceItems['Name']; ?>
        </div>
        <br/>
        <?php if (count($InvoiceItems['Bank_Details']) != 0): ?>
            <div class="col-md-2 col-sm-6 col-xs-12 clr">
                <b><?php echo $this->lang->line('CLIENT_INVOICE_LABLE_BANK_DETAILS'); ?></b>
            </div>
            <div class="col-md-10 col-sm-6 col-xs-12">
                <p class="clr"><?php echo $this->lang->line('INVOICE_PDF_TEXT_SEVEN'); ?> : <?php echo $InvoiceItems['Bank_Details']['Name']; ?></p>
                <p class="clr"><?php echo $this->lang->line('INVOICE_PDF_TEXT_EIGHT'); ?><?php echo $InvoiceItems['Bank_Details']['ShortCode']; ?></p>
                <p class="clr"><?php echo $this->lang->line('INVOICE_PDF_TEXT_NINE'); ?><?php echo $InvoiceItems['Bank_Details']['AccountNumber']; ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="popup">
    <table class="tbl-editable">
        <thead>
            <tr>
                <th>
                    <?php echo $this->lang->line('CLIENT_INVOICE_LABLE_SNO'); ?>
                </th>
                <th>
                    <?php echo $this->lang->line('CLIENT_INVOICE_LABLE_DESCRIPTION'); ?>
                </th>
                <th>
                    <?php echo $this->lang->line('CLIENT_INVOICE_LABLE_QUANTITY'); ?>
                </th>
                <th>
                    <?php echo $this->lang->line('CLIENT_INVOICE_LABLE_UNIT_PRICE'); ?>
                </th>
                <th>
                    <?php
                    if ($vr == 0) {
                        
                    } else {
                        echo $this->lang->line('CLIENT_INVOICE_LABLE_VAT');
                    }
                    ?>
                </th>
                <th style="width:110px;">
                    <?php echo $this->lang->line('CLIENT_INVOICE_LABLE_GBP'); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $subtotal = 0;
            $grandTotal = 0;
            $proPrice = 0;
            $totalVat = 0;
            $i = 1;
            foreach ($InvoiceItems['InvoiceItems'] as $key => $val) {
                $proPrice += $val->Quantity * $val->UnitPrice;
                $subtotal = ($val->Quantity * $val->UnitPrice) + ($val->Quantity * $val->UnitPrice * $val->Tax / 100);
                $grandTotal += $subtotal;
                $totalVat += ($val->Quantity * $val->UnitPrice * $val->Tax / 100);
                echo '<tr>';
                echo '<td>';
                echo $i;
                $i++;
                echo '</td>';
                echo '<td>';
                echo $val->Description;
                echo '</td>';
                echo '<td>';
                echo $val->Quantity;
                echo '</td>';
                echo '<td>';
                echo numberFormat($val->UnitPrice);
                echo '</td>';
                echo '<td>';
                if ($vr == 0) {
                    
                } else {
                    echo $val->Tax;
                }
                echo '</td>';
                echo '<td class="text-right">';
                echo numberFormat($val->Quantity * $val->UnitPrice);
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">
                </td>
                <td  class="well text-right">
                    <p><?php echo $this->lang->line('CLIENT_INVOICE_LABLE_SUB_TOTAL'); ?></p>
                    <p><?php if ($vr == 0) {
                
            } else {
                echo $this->lang->line('CLIENT_INVOICE_LABLE_VAT_TWO');
            } ?></p>
                    <p><?php echo $this->lang->line('CLIENT_INVOICE_LABLE_TOTAL'); ?></p>
                </td>
                <td class="well text-right">
                    <p><?php echo numberFormat($proPrice); ?></p>
                    <p><?php if ($vr == 0) {
                
            } else {
                echo numberFormat($totalVat);
            } ?></p>
                    <p><?php echo numberFormat($grandTotal); ?></p>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<br/><br/>
