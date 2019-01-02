<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$total_net_value = 0;
$total_inv_vat = 0;
$total_inv_amount = 0;
$total_inv_due_vat = 0;

$user = $this->session->userdata('user');

?>
<div id="messgaeDiv"></div>
<ul class="nav nav-tabs">
                <li class="tab-bg active">
                    <a data-toggle="tab" href="#static_tab">
                        <?php echo 'Static'; ?>
                    </a>
                </li>
				<li class="tab-bg">
                    <a data-toggle="tab" href="#dynamic_tab">
                        <?php echo 'Dynamic'; ?>
                    </a>
                </li>               
            </ul>
<div class="tab-content">
 <div id="static_tab" class="tab-pane fade in active">
<section>
    <div class="container-fluid ">
        <div class="table-responsive inv_info_n">
            <table class="table table-striped" >
                <thead class="tbody_bg" >
                    <tr>
                        <th>
                            <?php echo $this->lang->line('VAT_SUMMARY_POPUP_INV_DATE_COL_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo $this->lang->line('VAT_SUMMARY_POPUP_CLIENT_NAME_COL_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo $this->lang->line('VAT_SUMMARY_POPUP_INV_NO_COL_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo $this->lang->line('VAT_SUMMARY_POPUP_ANET_VALUE_COL_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo $this->lang->line('VAT_SUMMARY_POPUP_BVAT_VALUE_COL_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo $this->lang->line('VAT_SUMMARY_POPUP_FULL_AMOUNT_COL_LABEL'); ?>
                        </th>
                        <th>
                            <?php echo $this->lang->line('VAT_SUMMARY_POPUP_INV_VAT_COL_LABEL'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($quarterDetails) {
                        //  if(false){
                        foreach ($quarterDetails as $qDetails) {
                            // echo "<pre>";print_R($qDetails);
                            if ($qDetails->InvoiceTotal < 0) {
                                if ($qDetails->SubTotal >= 0)
                                    $qDetails->SubTotal = $qDetails->SubTotal * -1;
                                if ($qDetails->Tax >= 0)
                                    $qDetails->Tax = $qDetails->Tax * -1;
                            }
                            ?>
                            <tr>
                                <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_INV_DATE_COL_LABEL"); ?>" ><?php echo vDate($qDetails->PaidOn); ?></td>
                                <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_CLIENT_NAME_COL_LABEL"); ?>" ><?php echo $qDetails->Name; ?></td>
                                <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_INV_NO_COL_LABEL"); ?>" ><?php echo $qDetails->InvoiceNumber; ?></td>
                                <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_ANET_VALUE_COL_LABEL"); ?>" ><?php echo numberFormat($qDetails->SubTotal); ?></td>
                                <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_BVAT_VALUE_COL_LABEL"); ?>"><?php echo numberFormat($qDetails->Tax); ?></td>
                                <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_FULL_AMOUNT_COL_LABEL"); ?>"><?php echo numberFormat($qDetails->InvoiceTotal); ?></td>
                                <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_INV_VAT_COL_LABEL"); ?>">
                                    <?php
                                    if ($vat_listing->Type == 'flat' || $vat_listing->Type == 'stand') {
                                        if ($qDetails->InvoiceTotal != 0 && $qDetails->PaidOn != '') {
                                            if (strtotime(cDate($qDetails->PaidOn)) < strtotime(cDate($user['EndDate']))) {
                                                $flateRate = $qDetails->FlatRate;
                                            } else {
                                                $flateRate = $qDetails->FlatRate;
                                            }
                                        } else {
                                            $flateRate = 0.00;
                                        }
                                    } else {
                                        $flateRate = $qDetails->Tax;
                                    }
                                    $showFlateRate = numberFormat($flateRate);
                                    echo $showFlateRate;
                                    ?>
                                </td>

                            </tr>

                            <?php
                            $total_net_value += $qDetails->SubTotal;
                            $total_inv_vat += $qDetails->Tax;
                            $total_inv_amount += $qDetails->InvoiceTotal;
                            $total_inv_due_vat = $total_inv_due_vat + $flateRate;
                        }
                        ?>

                        <tr class="total_show">
                            <td data-title="" ></td>
                            <td data-title="" ></td>
                            <td data-title="" ><?php echo $this->lang->line('VAT_TOTAL_VALUE'); ?>:</td>
                            <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_BVAT_VALUE_COL_LABEL"); ?>" ><?php echo numberFormat($total_net_value); ?></td>
                            <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_ANET_VALUE_COL_LABEL"); ?>"><?php echo numberFormat($total_inv_vat); ?></td>
                            <td data-title="<?php echoLangVar("VAT_TOTAL_VALUE"); ?> <?php echoLangVar("VAT_SUMMARY_POPUP_FULL_AMOUNT_COL_LABEL"); ?>"><?php echo numberFormat($total_inv_amount); ?></td>
                            <td data-title="<?php echoLangVar("VAT_TOTAL_VALUE"); ?> <?php echoLangVar("VAT_SUMMARY_POPUP_INV_VAT_COL_LABEL"); ?>"><?php echo numberFormat($total_inv_due_vat); ?></td>
                        </tr>

                    <?php } else { ?>

                        <tr>
                            <td colspan="7" data-title="No invoices found" >
                                <div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;
                                    <?php echo $this->lang->line("NO_INVOICES_IN_QUARTER_DETAILS"); ?>
                                </div>						
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
        <?php if ($EXPitems) { ?>
            <div class="clear" ><br/><br/></div>
            <div class="table-responsive inv_info_n">
                <table class="table table-striped" >
                    <thead class="tbody_bg" >
                        <tr>
                            <th><?php echoLangVar("VAT_SUMMARY_POPUP_EXP_NO_COL_LABEL"); ?></th>
                            <th><?php echoLangVar("VAT_SUMMARY_POPUP_CDATE_COL_LABEL"); ?></th>
                            <th><?php echoLangVar("VAT_SUMMARY_POPUP_PDATE_COL_LABEL"); ?></th>
                            <th><?php echoLangVar("VAT_TOTAL_VALUE"); ?></th>
                            <th><?php echoLangVar("VAT_SUMMARY_POPUP_EXP_VAT_COL_LABEL"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_net_exp = 0;
                        $total_exp_vat = 0;

                        foreach ($EXPitems as $EXPitem) {
                            // echo "<pre>";print_R($EXPitem);die();
                            ?>
                            <tr>
                                <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_EXP_NO_COL_LABEL"); ?>" ><?php echo $EXPitem->ExpenseNumber; ?></td>
                                <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_CDATE_COL_LABEL"); ?>" ><?php echo vDate($EXPitem->AddedOn); ?></td>
                                <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_PDATE_COL_LABEL"); ?>" ><?php echo vDate($EXPitem->PaidOn); ?></td>
                                <td data-title="<?php echoLangVar("VAT_TOTAL_VALUE"); ?>" ><?php echo numberFormat($EXPitem->TotalAmount); ?></td>
                                <td data-title="<?php echoLangVar("VAT_SUMMARY_POPUP_EXP_VAT_COL_LABEL"); ?>"><?php echo numberFormat($EXPitem->TotalVATAmount); ?></td>
                            </tr>

                            <?php
                            $total_net_exp += $EXPitem->TotalAmount;
                            $total_exp_vat += $EXPitem->TotalVATAmount;
                        }
                        ?>

                        <tr class="total_show">
                            <td data-title="" ></td>
                            <td data-title="" ></td>
                            <td data-title="" ><?php echoLangVar("VAT_TOTAL_VALUE"); ?>:</td>
                            <td data-title="<?php echoLangVar("VAT_TOTAL_VALUE"); ?>"><?php echo numberFormat($total_net_exp); ?></td>
                            <td data-title="<?php echoLangVar("VAT_TOTAL_VALUE"); ?> <?php echoLangVar("VAT_SUMMARY_POPUP_EXP_VAT_COL_LABEL"); ?>"><?php echo numberFormat($total_exp_vat); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="clear" ></div>
            <div class="table-responsive inv_info_n stotal_TB">
                <table class="table" >
                    <tr class="total_standard">
                        <td width="88%" style="text-align:right" data-title="" >
                            <?php echoLangVar("VAT_TOTAL_DUE"); ?>: &nbsp; &nbsp;</td>
                        <td width="12%" data-title="<?php echoLangVar("VAT_TOTAL_DUE"); ?> :" >
                            <?php echo numberFormat($total_inv_due_vat - $total_exp_vat); ?>
                        </td>
                    </tr>
                </table>
            </div>
        <?php } ?>
    </div>
    <br/>
    <div class="modal-footer">
        <a href="#" class="btn btn-primary btn-sm spacer" data-dismiss="modal">
            <?php echo $this->lang->line('CLIENT_CLOSE_BUTTON'); ?>
        </a>
    </div>

</section>
</div>
<div id="dynamic_tab" class="tab-pane fade">
	<section>
	 <?php $this->load->view('client/invoices/vat_summary_popup',$quarterDetails); ?>
	</section>
</div>
</div>