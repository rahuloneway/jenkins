<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$total_net_value = 0;
$total_inv_vat = 0;
$total_inv_amount = 0;
$total_inv_due_vat = 0;

$color = 0;

$bHeadTopBottomRight = "border-top:1px solid #000;border-right:1px solid #d5d5d5;border-bottom:1px solid #000;";
$bleft = "border-left:1px solid #000;";
$bRight = "border-right:1px solid #000;";
$bRightLight = "border-right:1px solid #d5d5d5;";
$bTopBottom = "border-top:1px solid #000;border-bottom:1px solid #000;";

$user = $this->session->userdata('user');
?>
<h3 align="center" ><?php echo companyName($user["CompanyID"]); ?> </h3>
<h3 align="center" ><?php echo $pdfTitle; ?> </h3>
<table cellpadding="5" >
    <thead>
        <tr bgcolor="#458ACE" color="#fff" >
            <th align="center" style="<?php echo $bTopBottom . $bRightLight . $bleft; ?>" >
                <?php echoLangVar("VAT_SUMMARY_POPUP_INV_DATE_COL_LABEL"); ?>
            </th>
            <th align="center" style="<?php echo $bHeadTopBottomRight; ?>" >
                <?php echoLangVar("VAT_SUMMARY_POPUP_CLIENT_NAME_COL_LABEL"); ?>
            </th>
            <th align="center" style="<?php echo $bHeadTopBottomRight; ?>" >
                <?php echoLangVar("VAT_SUMMARY_POPUP_INV_NO_COL_LABEL"); ?>
            </th>
            <th align="center" style="<?php echo $bHeadTopBottomRight; ?>" >
                <?php echoLangVar("VAT_SUMMARY_POPUP_ANET_VALUE_COL_LABEL"); ?>
            </th>
            <th align="center" style="<?php echo $bHeadTopBottomRight; ?>" >
                <?php echoLangVar("VAT_SUMMARY_POPUP_BVAT_VALUE_COL_LABEL"); ?>
            </th>
            <th align="center" style="<?php echo $bHeadTopBottomRight; ?>" >
                <?php echoLangVar("VAT_SUMMARY_POPUP_FULL_AMOUNT_COL_LABEL"); ?>
            </th>
            <th align="center" style="<?php echo $bTopBottom . $bRight; ?>"  >
                <?php echoLangVar("VAT_SUMMARY_POPUP_INV_VAT_COL_LABEL"); ?>
            </th>
        </tr>
    </thead>
        <?php if ($quarterDetails) { ?>
        <tbody>
            <?php
            //  if(false){
            $i = 1;
            foreach ($quarterDetails as $qDetails) {
                // echo "<pre>";print_R($qDetails);

                if ($i % 2 == 0) {
                    $color = '#E1E1E1';
                } else {
                    $color = '#fff';
                }

                if ($qDetails->InvoiceTotal < 0) {
                    if ($qDetails->SubTotal >= 0)
                        $qDetails->SubTotal = $qDetails->SubTotal * -1;
                    if ($qDetails->Tax >= 0)
                        $qDetails->Tax = $qDetails->Tax * -1;
                }
                ?>
                <tr bgcolor="<?php echo $color; ?>" >
                    <td align="center" style="<?php echo $bRight . $bleft; ?>" ><?php echo vDate($qDetails->PaidOn); ?></td>
                    <td align="center" style="<?php echo $bRight; ?>" ><?php echo $qDetails->Name; ?></td>
                    <td align="center" style="<?php echo $bRight; ?>" ><?php echo $qDetails->InvoiceNumber; ?></td>
                    <td align="center" style="<?php echo $bRight; ?>" ><?php echo numberFormat($qDetails->SubTotal); ?></td>
                    <td align="center" style="<?php echo $bRight; ?>" ><?php echo numberFormat($qDetails->Tax); ?></td>
                    <td align="center" style="<?php echo $bRight; ?>" ><?php echo numberFormat($qDetails->InvoiceTotal); ?></td>
                    <td align="center" style="<?php echo $bRight; ?>" >
                        <?php
                        if ($vat_listing->Type == 'flat' || $vat_listing->Type == 'stand') {
                            if ($qDetails->InvoiceTotal != 0 && $qDetails->PaidOn != '') {
                                if (strtotime(cDate($qDetails->PaidOn)) < strtotime(cDate($user['EndDate']))) {
                                    $flateRate = $qDetails->FlatRate;
                                } else {
                                    $flateRate = $qDetails->FlatRate;
                                }
                                $showFlateRate = numberFormat($flateRate);
                            } else {
                                $showFlateRate = 0.00;
                            }
                        } else {
                            $flateRate = ($qDetails->InvoiceTotal * $user['PercentRate']) / 100;
                            $showFlateRate = numberFormat($flateRate);
                        }
                        echo $showFlateRate;
                        ?>
                    </td>
                </tr>
                <?php
                $total_net_value += $qDetails->SubTotal;
                $total_inv_vat += $qDetails->Tax;
                $total_inv_amount += $qDetails->InvoiceTotal;
                $total_inv_due_vat = $total_inv_due_vat + $flateRate;
                $i++;
            }
            ?>
        </tbody>
    <?php } ?>
    <?php if ($quarterDetails && $total_inv_amount != 0) { ?>
        <tfoot>
            <tr bgcolor="#f1f1f1" color="#000" >
                <td align="center" style="<?php echo $bTopBottom . $bleft; ?>" ></td>
                <td align="center" style="<?php echo $bTopBottom . $bRight; ?>" ></td>
                <td align="center" style="<?php echo $bTopBottom . $bRight; ?>" ><strong><?php echoLangVar("VAT_TOTAL_VALUE"); ?>:</strong></td>
                <td align="center" style="<?php echo $bTopBottom . $bRight; ?>" ><strong><?php echo numberFormat($total_net_value); ?></strong></td>
                <td align="center" style="<?php echo $bTopBottom . $bRight; ?>" ><strong><?php echo numberFormat($total_inv_vat); ?></strong></td>
                <td align="center" style="<?php echo $bTopBottom . $bRight; ?>" ><strong><?php echo numberFormat($total_inv_amount); ?></strong></td>
                <td align="center" style="<?php echo $bTopBottom . $bRight; ?>" ><strong><?php echo numberFormat($total_inv_due_vat); ?></strong></td>
            </tr>
        </tfoot>
    <?php } else { ?>
        <tfoot>
            <tr>
                <td colspan="7">
                    <?php echo $this->lang->line("NO_INVOICES_IN_QUARTER_DETAILS"); ?>
                </td>
            </tr>
        </tfoot>
    <?php } ?>
</table>

<?php if ($EXPitems) { ?>
    <br/><br/>
    <br/><br/>
    <table cellpadding="5"  >
        <thead>
            <tr bgcolor="#458ACE" color="#fff"  >
                <th align="center" style="<?php echo $bTopBottom . $bRightLight . $bleft; ?>" >
                    <?php echoLangVar("VAT_SUMMARY_POPUP_EXP_NO_COL_LABEL"); ?>
                </th>
                <th align="center" style="<?php echo $bHeadTopBottomRight; ?>" >
                    <?php echoLangVar("VAT_SUMMARY_POPUP_CDATE_COL_LABEL"); ?>
                </th>
                <th align="center" style="<?php echo $bHeadTopBottomRight; ?>" >
                    <?php echoLangVar("VAT_SUMMARY_POPUP_PDATE_COL_LABEL"); ?>
                </th>
                <th align="center" style="<?php echo $bHeadTopBottomRight; ?>" >
                    <?php echoLangVar("VAT_TOTAL_VALUE"); ?>
                </th>
                <th align="center" style="<?php echo $bTopBottom . $bRight; ?>"  >
                    <?php echoLangVar("VAT_SUMMARY_POPUP_EXP_VAT_COL_LABEL"); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_net_exp = 0;
            $total_exp_vat = 0;
            $k = 1;
            foreach ($EXPitems as $EXPitem) {
                if ($k % 2 == 0) {
                    $color = '#E1E1E1';
                } else {
                    $color = '#fff';
                }

                // echo "<pre>";print_R($EXPitem);die();
                ?>
                <tr bgcolor="<?php echo $color; ?>" >
                    <td align="center" style="<?php echo $bRight . $bleft; ?>" ><?php echo $EXPitem->ExpenseNumber; ?></td>
                    <td align="center" style="<?php echo $bRight; ?>" ><?php echo vDate($EXPitem->AddedOn); ?></td>
                    <td align="center" style="<?php echo $bRight; ?>" ><?php echo vDate($EXPitem->PaidOn); ?></td>
                    <td align="center" style="<?php echo $bRight; ?>" ><?php echo numberFormat($EXPitem->TotalAmount); ?></td>
                    <td align="center" style="<?php echo $bRight; ?>" ><?php echo numberFormat($EXPitem->TotalVATAmount); ?></td>
                </tr>
                <?php
                $total_net_exp += $EXPitem->TotalAmount;
                $total_exp_vat += $EXPitem->TotalVATAmount;
                $k++;
            }
            ?>
        </tbody>
        <tfoot>
            <tr bgcolor="#f1f1f1" color="#000" >
                <td align="center" style="<?php echo $bTopBottom . $bleft; ?>" ></td>
                <td align="center" style="<?php echo $bTopBottom . $bRight; ?>" ></td>
                <td align="center" style="<?php echo $bTopBottom . $bRight; ?>" ><strong><?php echoLangVar("VAT_TOTAL_VALUE"); ?>:</strong></td>
                <td align="center" style="<?php echo $bTopBottom . $bRight; ?>" ><strong><?php echo numberFormat($total_net_exp); ?></strong></td>
                <td align="center" style="<?php echo $bTopBottom . $bRight; ?>" ><strong><?php echo numberFormat($total_exp_vat); ?></strong></td>
            </tr>
        </tfoot>
    </table>
    <br/><br/>
    <table cellpadding="5"  >
        <thead>
            <tr bgcolor="#f1f1f1" color="#000" >
                <td align="right" width="80%" style="<?php echo $bTopBottom . $bleft . $bRight; ?>" >
                    <strong><?php echoLangVar("VAT_TOTAL_DUE"); ?>: &nbsp; &nbsp;</strong>
                </td>
                <td align="center" width="20%" style="<?php echo $bTopBottom . $bRight; ?>" >
                    <strong><?php echo numberFormat($total_inv_due_vat - $total_exp_vat); ?></strong>
                </td>
            </tr>
        </thead>
    </table>
<?php } ?>
