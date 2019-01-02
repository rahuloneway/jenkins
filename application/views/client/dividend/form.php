<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
/* These variables are for edit form operation */
if (!isset($item)) {
    $item = Array(
        'DID' => '',
        'ShareholderID' => '',
        'DividendDate' => '',
        'NetAmount' => '',
        'PaidByDirectorLoan' => 0,
        'Status' => 0,
        'Params' => array(),
        'PaidOn' => cDate(date('Y-m-d')),
    );
}
//echo '1 - '.$bank_paid_date.' | '.$ajax_add;
//echo 'Condition : '.(!empty($bank_paid_date) && $ajax_add != 'bank_ajax_add');
if (!empty($bank_paid_date)) {
    $item['PaidOn'] = cDate($bank_paid_date);
    $item['Status'] = 2;
}
$user = $this->session->userdata('user');
//echo '<pre>';print_r($item);echo '</pre>';
?>
<?php
$acc_id = clientAccess();
if ($acc_id != 0) {
    $access = 1;
} else {
    $access = 0;
}
?>
<?php echo form_open('', array('id' => 'dividendForm')); ?>
<section>
    <div class="container-fluid ">
        <div class="account_sum" style="border: none;padding: 0px;">
            <?php
            if (!empty($bank_statement_id)) {
                ?>
                <div class="col-md-12">
                    <a href="#" class="btn btn-success btn-xs pull-right view_preview_dividend">
                        <?php echo $this->lang->line('DIVIDEND_FORM_BUTTON_VIEW_DIV'); ?>
                    </a>
                </div>
                <div class="clr"></div>
                <?php
            }
            ?>
            <div class="panel panel-default panel_custom inv_blue">
                <div class="panel-body row text-center">
                    <div class="col-md-6 bodr_rgt">
                        <strong><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_SIGNATORY'); ?></strong><span><?php if($Directors != ''){ echo implode(', ', $Directors); } ?></span>
                    </div>	

                    <div class="col-md-6 bodr_rgt">
                        <strong><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_TOTAL_SHARES'); ?></strong>
                        <span id="tshares"><?php echo $shares; ?></span>
                    </div>
                </div>
            </div>
            <div class="border_box " style="margin:20px 0;">
                <div class="divdnd">
                    <div class="col-md-5 col-md-offset-3 spac_inpu_text">
                        <div class="wid-40">
                            <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_SHAREHOLDER'); ?></label>
                        </div>						
                        <div class="wid-60">
                            <?php
                            echo form_dropdown('ShareHolders', $share_holders, $item['ShareholderID'], 'class="form-control required" id="ShareHolders"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-3 spac_inpu_text">
                        <div class="wid-40">
                            <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DATE_OF_DIV'); ?></label>
                        </div>
                        <div class="wid-60">
                            <?php
                            if (!empty($bank_statement_id)) {
                                ?>
                                <input type="text" class="form-control dDatepicker required" name="dividendDate" id="dividendDate" placeholder="" value="<?php echo cDate($t_date); ?>"/>
                                <?php
                            } else {
                                ?>
                                <input type="text" class="form-control dDatepicker required" name="dividendDate" id="dividendDate" placeholder="" value="<?php echo cDate($item['DividendDate']); ?>"/>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-3 spac_inpu_text">
                        <div class="wid-40">
                            <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_AMOUNT'); ?></label>
                            <span class="glyphicon glyphicon-gbp pull-right"></span>
                        </div>
                        <div class="wid-60">
                            <?php
                            if (!empty($bank_statement_id)) {
                                ?>
                                <input type="text" class="form-control validNumber required" name="dividendAmount" id="dividendAmount" placeholder="" value="<?php echo $net_amount; ?>">
                                <?php
                            } else {
                                ?>
                                <input type="text" class="form-control validNumber required" name="dividendAmount" id="dividendAmount" placeholder="" value="<?php echo $item['NetAmount']; ?>">
                                <?php
                            }
                            ?>

                        </div>
                    </div> 
                    <div class="col-md-5 col-md-offset-3 spac_inpu_text showShareHolderFields">
                        <div class="wid-40">
                            <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_NO_SHARES'); ?></label>
                        </div>
                        <div class="wid-60" id="shareHoldersShares"></div>
                    </div>
                    <div class="col-md-5 col-md-offset-3 spac_inpu_text showShareHolderFields">
                        <div class="wid-40">
                            <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DIV_PER_SHARE'); ?></label>
                            <span class="glyphicon glyphicon-gbp pull-right"></span>
                        </div>
                        <div class="wid-60" id="dividendPerShare">
                            <?php
                            if (!empty($bank_statement_id)) {
                                //echo round($net_amount/$item['TotalShares']);
                            } elseif (!empty($item['NetAmount'])) {
                                echo round($item['NetAmount'] / $item['TotalShares']);
                            }
                            ?>
                        </div>
                    </div>
                    <div class="Dirc-box" id="payViaDirector">
                        <div class="col-md-5 col-md-offset-3 spac_inpu_text" >
                            <div class="wid-40">
                                <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_PAID_VIA_DIR'); ?></label>
                            </div>
                            <div class="wid-60">
                                <input type="radio" name="directorLoan" value="1" id="rd1" style="padding-right:10px;"/> &nbsp;
                                <label>Yes</label> &nbsp; &nbsp;
                                <input type="radio" name="directorLoan" value="0" id="rd2"style="padding-right:10px;"checked="true"/> &nbsp;
                                <label>No</label>
                            </div>
                        </div> 
                    </div>
                    <?php if ($access): ?>
                        <?php if (empty($bank_paid_date) && $ajax_add != 'bank_ajax_add'): ?>
                            <div class="col-md-5 col-md-offset-3 spac_inpu_text">
                                <div class="wid-40">
                                    <label><?php echo $this->lang->line('DIVIDEND_FORM_LABEL_PAID'); ?></label>
                                </div>
                                <div class="wid-60">
                                    <input type="checkbox" name="IsPaid" id="IsPaid" value="1" <?php echo ($item['Status'] == 2) ? 'checked="checked"' : ''; ?>>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-5 col-md-offset-3 spac_inpu_text paidDate">
                            <div class="wid-40">
                                <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_PAID_DATE'); ?></label>
                            </div>
                            <div class="wid-60">
                                <input type='text' name='paidDate'id='paidDate' class='form-control dDatepicker' readonly value="<?php echo cDate($item['PaidOn']); ?>"/>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-5 col-md-offset-3 spac_inpu_text text-right">
                        <?php if ($task == 'editDividend'): ?>
                            <button type="submit" class="btn btn-success btn-dsb updateDividend">
                                &nbsp;<?php echo $this->lang->line('DIVIDEND_FORM_BUTTON_UPDATE_VOUCHER'); ?>
                            </button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-success createDividend">
                                <i class="fa fa-file-text"></i>
                                &nbsp;<?php echo $this->lang->line('DIVIDEND_FORM_BUTTON_CREATE_VOUCHER'); ?>
                            </button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="glyphicon glyphicon-remove-sign"></i>
                            <?php echo $this->lang->line('BUTTON_CANCEL'); ?>
                        </button>
                    </div>
                </div>
                <div class="clr"></div>
            </div>

        </div>
    </div>
</section>
<div class="modal-footer">
    <div class="progress pull-left" style="display:none;">
        <img src="<?php echo site_url(); ?>/assets/images/progress.gif"/>
    </div>
</div>
<input type="hidden" name="addressParams" id="addressParams" value=""/>
<input type="hidden" name="shareholderaddress" id="shareholderaddress" value=""/>
<input type="hidden" name="did" value="<?php echo $this->encrypt->encode($item['DID']); ?>"/>
<input type="hidden" name="bank_statement_id" value="<?php echo $bank_statement_id; ?>"/>
<input type="hidden" name="ajax_add" value="<?php echo $ajax_add; ?>"/>
<input type="hidden" name="bank_paid_date" value="<?php echo $bank_paid_date; ?>"/>
<?php echo form_close(); ?>