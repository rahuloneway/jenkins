<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$dt = '';
if (empty($vat_listing->Type)) {
    $dt = 0;
} else {
    $dt = 1;
}
?>
<table cellpadding="2">
    <thead>
        <tr>
            <td colspan="4">
                <h1><?php echo $item['Company_details']['Name']; ?></h1>

                <?php
                if (!empty($item['Company_details']['REG_AddressOne'])) {
                    echo $item['Company_details']['REG_AddressOne'] . '<br/>';
                }
                if (!empty($item['Company_details']['REG_AddressTwo'])) {
                    echo $item['Company_details']['REG_AddressTwo'] . '<br/>';
                }
                if (!empty($item['Company_details']['REG_AddressThree'])) {
                    echo $item['Company_details']['REG_AddressThree'] . '<br/>';
                }
                if (!empty($item['Company_details']['REG_PostalCode'])) {
                    echo $item['Company_details']['REG_PostalCode'] . '<br/>';
                }
                if (!empty($item['Company_details']['REG_PhoneNo'])) {
                    echo '<img src="' . site_url() . 'assets/images/phone.png" height="10"width="10" style="margin-top:10px;"/>&nbsp;' . $item['Company_details']['REG_PhoneNo'] . '<br/>';
                }
                if (!empty($CompanyEmail)) {
                    echo $CompanyEmail;
                }
                ?>

            </td>
            <td align="right" colspan="3">
                <?php
                if (isset($item['Company_details']['LogoLink'])) {
                    if (!empty($item['Company_details']['LogoLink'])) {
                        if (file_exists($item['Company_details']['LogoLink'])) {
                            echo '<img src="' . site_url() . $item['Company_details']['LogoLink'] . '"width="150" height="75"/><br/>';
                        }
                    }
                }
                ?>
                <?php echo $this->lang->line('INVOICE_PDF_TEXT_ONE'); ?><?php echo $item['InvoiceNumber']; ?>
                <br/><?php echo $this->lang->line('INVOICE_PDF_TEXT_TWO'); ?><?php echo cDate($item['InvoiceDate']); ?>
                <br/><?php echo $this->lang->line('INVOICE_PDF_TEXT_THREE'); ?><?php echo cDate($item['DueDate']); ?>
            </td>
        </tr>
        <tr style="border-top:1px solid #000;height:300px;">
            <td align="center"><br/><br/><br/><?php echo $this->lang->line('INVOICE_PDF_TEXT_FOUR'); ?></td>
            <td colspan="6">
                <br/><br/><br/>
                <?php echo $item['Name']; ?><br />
                <?php echo $item['Address']; ?><br/>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="7">
                <table cellpadding="5" rules="none" frame="box" >
                    <thead>
                        <tr bgcolor="#458ACE" color="#fff">
                            <th align="center" style="height:40px;">
                                <?php echo $this->lang->line('INVOICE_PDF_TEXT_FIVE'); ?>
                            </th>
                            <th colspan="2" align="center">
                                <?php echo $this->lang->line('INVOICE_PAGE_LABLE_DESCRIPTION'); ?>
                            </th>
                            <th align="center">
                                <?php echo $this->lang->line('CLIENT_INVOICE_LABLE_QUANTITY'); ?>
                            </th>
                            <th align="center">
                                <?php echo $this->lang->line('CLIENT_INVOICE_LABLE_UNIT_PRICE'); ?>
                            </th>

                            <th align="center">
                                <?php echo $this->lang->line('INVOICE_PAGE_LABLE_AMOUNT_GBP'); ?>
                            </th>
                            <!--th align="center">
                                    Amount GBP
                            </th -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $subtotal = 0;
                        $grandTotal = 0;
                        $proPrice = 0;
                        $totalVat = 0;
                        $i = 1;
                        foreach ($item['InvoiceItems'] as $key => $val) {
                            $proPrice += $val->Quantity * $val->UnitPrice;
                            $subtotal = ($val->Quantity * $val->UnitPrice) + ($val->Quantity * $val->UnitPrice * $val->Tax / 100);
                            $grandTotal += $subtotal;
                            $totalVat += ($val->Quantity * $val->UnitPrice * $val->Tax / 100);
                            if ($i % 2 == 0) {
                                $color = '#E1E1E1';
                            } else {
                                $color = '#fff';
                            }
                            echo '<tr bgcolor="' . $color . '">';
                            echo '<td align="center">';
                            echo $i;
                            $i++;
                            echo '</td>';
                            echo '<td colspan="2">';
                            echo $val->Description;
                            echo '</td>';
                            echo '<td align="center">';
                            echo $val->Quantity;
                            echo '</td>';
                            echo '<td align="right">';
                            echo '&#163;&nbsp;' . number_format($val->UnitPrice, 2, '.', ',');
                            echo '</td>';

                            echo '<td align="right">';
                            echo number_format($val->Quantity * $val->UnitPrice, 2, '.', ',');
                            echo '</td>';
                            /*
                              echo '<td align="right">';
                              echo '&#163;&nbsp;'.number_format((float)$subtotal, 2, '.',',');
                              echo '</td>';
                             */
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td align="right" colspan="5">
                                <p><?php echo $this->lang->line('INVOICE_PAGE_LABLE_SUBTOTAL'); ?></p>
                            </td>
                            <td align="right">
                                <p>&#163;&nbsp;<?php echo number_format((float) $proPrice, 2, '.', ','); ?></p>
                            </td>
                        </tr>
                        <?php if ($dt == 0) { ?>
                        <?php } else { ?>
                            <tr style="">
                                <td align="right" colspan="5" >
                                    <p><?php echo $this->lang->line('INVOICE_PAGE_LABLE_VAT'); ?></p>
                                </td>
                                <td align="right">
                                    <p>&#163;&nbsp;<?php echo number_format((float) $totalVat, 2, '.', ','); ?></p>
                                </td>
                            </tr>
                        <?php } ?>

                        <tr bgcolor="#458ACE" color="#fff">
                            <td align="right" colspan="5">
                                <p><?php echo $this->lang->line('INVOICE_PAGE_LABLE_TOTAL'); ?></p>
                            </td>
                            <td align="right">
                                <p><?php echo (($item['InvoiceTotal'] < 0) ? '-' : '') . ' ' . numberFormat($grandTotal); ?></p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <?php if (empty($item['Company_details']['VATRegistrationNo'])) { ?>
            <?php } else { ?>
                <td align="right">
                  <span width="10px"></span><?php echo $this->lang->line('INVOICE_PDF_TEXT_SIX'); ?>
                </td>
                <td colspan="6" align="left">
                    : <?php echo $item['Company_details']['VATRegistrationNo']; ?>
                </td>
            <?php } ?> 
        </tr>
        <?php if (count($item['Bank_Details']) != 0): ?>
            <tr>
                <td align="right">
                    <?php echo $this->lang->line('INVOICE_PDF_TEXT_SEVEN'); ?>
                </td>
                <td colspan="6" align="left">
                    : <?php echo $item['Bank_Details']['Name']; ?>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <?php echo $this->lang->line('INVOICE_PDF_TEXT_EIGHT'); ?>
                </td>
                <td colspan="6" align="left">
                    : <?php echo $item['Bank_Details']['ShortCode']; ?>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <?php echo $this->lang->line('INVOICE_PDF_TEXT_NINE'); ?>
                </td>
                <td colspan="6" align="left">
                    : <?php echo $item['Bank_Details']['AccountNumber']; ?>
                </td>
            </tr>
        <?php endif; ?>
        <tr >
            <td colspan="7" align="center" valign="middle" height="100">
                <br/><br/><br/>
                <p><?php echo $this->lang->line('INVOICE_PDF_TEXT_TEN'); ?><?php echo $item['RegistrationNumber']; ?></p>
                <p>
                    <?php echo $this->lang->line('INVOICE_PDF_TEXT_ELEVEN'); ?><?php echo $Country; ?>
                </p>
            </td>
        </tr>
    </tfoot>
</table>

