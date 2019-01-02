<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$user = $this->session->userdata('user');
// echo '<pre>';print_r($vat_listing);echo '</pre>';die;
// echo '<pre>';print_r($Invoices);echo '</pre>';
// echo '<pre>';print_r($user);echo '</pre>';
/* Calulations for VAT listing */
$acceptSalesZero = true;
$acceptVATZero = true;
for ($i = 1; $i <= 4; $i++) {
    $total_sales[$i] = 0;
    $total_vat[$i] = 0;
}

$quaters = vatQuaters($user['Params']['VATQuaters']);

$VATquaters = getVatQuarters();
if ($VATquaters) {
    $quaters = $VATquaters;
}

$today = time();

 //echo '<pre>';print_r($PaidVatQuarters);echo '</pre>';
if (!empty($PaidVatQuarters)) {
    foreach ($PaidVatQuarters as $key => $PaidVatQuarter) {
        $total_sales[$key] = $PaidVatQuarter->totalSales;
        $total_vat[$key] = $PaidVatQuarter->totalDue;
    }
}
// echo "<pre>";print_r( $VATitems );die();
if (!empty($VATitems) && count($VATitems) > 0) {
    foreach ($VATitems as $key => $val) {
        if ($val->Status != 3) {
            continue;
        }

        $date = cDate($val->PaidOn);
        if (strtotime($date) >= strtotime($quaters[1]['FIRST']) && strtotime($date) <= strtotime($quaters[1]['SECOND'])) {
            if (isset($PaidVatQuarters[1]) && !empty($PaidVatQuarters[1])) {
                continue;
            }
            $total_sales[1] += $val->InvoiceTotal;
            $total_vat[1] += calculate_due_vat($vat_listing->Type, $val, $date, $user);
        } elseif (strtotime($date) >= strtotime($quaters[2]['FIRST']) && strtotime($date) <= strtotime($quaters[2]['SECOND'])) {
            if (isset($PaidVatQuarters[2]) && !empty($PaidVatQuarters[2])) {
                continue;
            }
            $total_sales[2] += $val->InvoiceTotal;
            $total_vat[2] += calculate_due_vat($vat_listing->Type, $val, $date, $user);
        } elseif (strtotime($date) >= strtotime($quaters[3]['FIRST']) && strtotime($date) <= strtotime($quaters[3]['SECOND'])) {
            if (isset($PaidVatQuarters[3]) && !empty($PaidVatQuarters[3])) {
                continue;
            }
            $total_sales[3] += $val->InvoiceTotal;
            $total_vat[3] += calculate_due_vat($vat_listing->Type, $val, $date, $user);
        } elseif (strtotime($date) >= strtotime($quaters[4]['FIRST']) && strtotime($date) <= strtotime($quaters[4]['SECOND'])) {
            if (isset($PaidVatQuarters[4]) && !empty($PaidVatQuarters[4])) {
                continue;
            }
            $total_sales[4] += $val->InvoiceTotal;
            $total_vat[4] += calculate_due_vat($vat_listing->Type, $val, $date, $user);
        }
    }
}

if ($vat_listing->Type != 'flat') {

    if (!empty($EXPitems) && count($EXPitems) > 0) {
        foreach ($EXPitems as $eKey => $eVal) {
            if ($eVal->Status != 3) {
                continue;
            }

            $date = cDate($eVal->PaidOn);
            if (strtotime($date) >= strtotime($quaters[1]['FIRST']) && strtotime($date) <= strtotime($quaters[1]['SECOND'])) {
                if (isset($PaidVatQuarters[1]) && !empty($PaidVatQuarters[1])) {
                    continue;
                }
                $total_vat[1] -= $eVal->TotalVATAmount;
            } elseif (strtotime($date) >= strtotime($quaters[2]['FIRST']) && strtotime($date) <= strtotime($quaters[2]['SECOND'])) {
                if (isset($PaidVatQuarters[2]) && !empty($PaidVatQuarters[2])) {
                    continue;
                }
                $total_vat[2] -= $eVal->TotalVATAmount;
            } elseif (strtotime($date) >= strtotime($quaters[3]['FIRST']) && strtotime($date) <= strtotime($quaters[3]['SECOND'])) {
                if (isset($PaidVatQuarters[3]) && !empty($PaidVatQuarters[3])) {
                    continue;
                }
                $total_vat[3] -= $eVal->TotalVATAmount;
            } elseif (strtotime($date) >= strtotime($quaters[4]['FIRST']) && strtotime($date) <= strtotime($quaters[4]['SECOND'])) {
                if (isset($PaidVatQuarters[4]) && !empty($PaidVatQuarters[4])) {
                    continue;
                }
                $total_vat[4] -=$eVal->TotalVATAmount;
            }
        }
    }
}
?>
<table class="table table-striped" >
    <thead>
        <tr>
            <th><?php echo $this->lang->line('INVOICE_VAT_COLUMN_PERIODS'); ?></th>
            <th><?php echo $this->lang->line('INVOICE_VAT_COLUMN_FROM'); ?></th>
            <th><?php echo $this->lang->line('INVOICE_VAT_COLUMN_TO'); ?></th>
            <th><?php echo $this->lang->line('INVOICE_VAT_COLUMN_TOTAL_SALES'); ?></th>
            <th><?php echo $this->lang->line('INVOICE_VAT_COLUMN_DUE'); ?></th>
            <th><?php echo $this->lang->line('INVOICE_VAT_COLUMN_STATUS'); ?></th>
            <th><?php echo $this->lang->line('INVOICE_VAT_COLUMN_ACTION'); ?></th>
        </tr>
    </thead>
    <tbody>
<?php
// echo '<pre>';print_r($quaters);echo '</pre>';die();

$x = 1;
if (!empty($quaters)) {
    foreach ($quaters as $key => $val) {
        ?>
                <tr>
                    <td data-title="VAT Periods" >
                <?php if (fPrice($total_sales[$x]) > fPrice(0)) { ?>
                            <a data-quarter="<?php echo $x; ?>" href="<?php echo $this->encrypt->encode($x); ?>" class="vatDetailsLink" >Q<?php echo $x; ?></a>
                <?php } else { ?>
                            Q<?php echo $x; ?>
                <?php } ?>
                    </td>
                    <td id="from_date_<?php echo $x; ?>" data-title="From" ><?php echo cDate($val['FIRST']); ?></td>
                    <td id="to_date_<?php echo $x; ?>" data-title="To" ><?php echo cDate($val['SECOND']); ?></td>
                    <td data-title="Total Value of Sales" >
                        <?php
                        echo numberFormatSigned($total_sales[$x]);
                        ?>
                    </td>
                    <td data-title="VAT Due" class="text-right">
                        <?php
                        echo numberFormatSigned($total_vat[$x]);
                        ?>
                    </td>
                    <td data-title="Status">
                        <?php if (isset($PaidVatQuarters[$x]) && !empty($PaidVatQuarters[$x])) { ?>
                            <span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('TOOLTIP_VAT_ALREADY_PAID'); ?>" >
                            <?php echo $this->lang->line('CLIENT_INVOICE_VAT_PAID_LABEL'); ?>
                            </span>										
        <?php
        } else {
            // $val['SECOND'] = "2015-02-01";
            $acceptSalesZero = false;
            $acceptVATZero = false;
            if ($vat_listing->Type != 'flat') {
                $acceptSalesZero = true;
                if ($total_vat[$x] != 0) {
                    $acceptVATZero = true;
                }
            } else {
                $acceptVATZero = true;
                if ($total_sales[$x] > 0) {
                    $acceptSalesZero = true;
                }
            }

            //if( $acceptSalesZero && $acceptVATZero ){
            if (1) {

                // if($total_sales[$x] > 0){
                if (strtotime($val['SECOND']) < $today && isset($user['AccountantAccess'])) { // can "Mark as Paid" only if today is not in current Quarter
                    ?>
                                    <a href="<?php echo $this->encrypt->encode($x); ?>" class="btn btn-info btn-xs color markVATPaid" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('TOOLTIP_VAT_MARK_AS_PAID'); ?>">
                                    <?php echo $this->lang->line('CLIENT_INVOICE_VAT_SUBMITTED_LABEL'); ?>
                                    </a>
                                <?php } else { ?>
                                    <span class="btn btn-info btn-xs color" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('TOOLTIP_VAT_SUBMITTED'); ?>" >
                                    <?php echo $this->lang->line('CLIENT_INVOICE_VAT_SUBMITTED_LABEL'); ?>
                                    </span>	
                                <?php
                                }
                            }
                        }
                        ?>
                    </td>
                    <td data-title="Action">
                        <?php
                        if ($acceptSalesZero && $acceptVATZero) {
                            // if($total_sales[$x] > 0){ 
                            ?>
                            <a target="_blank" href="<?php echo site_url() . 'client/getQuarterPDF/' . $this->encrypt->encode($x); ?>" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('TOOLTIP_VAT_PDF_DOWNLOAD'); ?>" >
                                <i class="fa fa-file-pdf-o"></i>
                            </a>
                        <?php } ?>
                    </td>
                </tr>
                        <?php
                        $x++;
                    }
                } else {
                    ?>
            <tr>
                <td colspan="6">
                    <div class="alert alert-info"><?php echo $this->lang->line('INVOICE_VAT_NO_RECORD'); ?></div>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>