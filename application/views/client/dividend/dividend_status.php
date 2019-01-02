<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
/* These variables are for edit form operation */
if (!isset($item)) {
    $item = Array(
        'DID' => '',
        'ShareholderID' => '',
        'DividendDate' => '',
        'GrossAmount' => '',
        'PaidByDirectorLoan' => 0,
        'Status' => 0
    );
}
$user = $this->session->userdata('user');
?>
<?php echo form_open('', array('id' => 'dividendForm')); ?>
<section>
    <div class="container-fluid ">
        <div class="account_sum" style="border: none;">
            <div class="panel panel-default panel_custom inv_blue">
                <div class="panel-body row text-center">
                    <div class="col-md-6 bodr_rgt">
                        <strong><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_SIGNATORY'); ?> : </strong><span><?php echo implode(', ', $Directors); ?></span>
                    </div>	
                    <div class="col-md-6 bodr_rgt">
                        <strong><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_TOTAL_SHARES'); ?></strong>
                        <span><?php echo $shares; ?></span>
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
                            echo $Name;
                            ?>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-3 spac_inpu_text">
                        <div class="wid-40">
                            <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DATE_OF_DIV'); ?></label>
                        </div>
                        <div class="wid-60">
                            <?php echo cDate($item['DividendDate']); ?>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-3 spac_inpu_text">
                        <div class="wid-40">
                            <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_AMOUNT'); ?></label>
                        </div>
                        <div class="wid-60">
                            &pound;
                            <?php echo number_format($item['NetAmount'], 2, '.', ','); ?>
                        </div>
                    </div> 
                    <div class="col-md-5 col-md-offset-3 spac_inpu_text">
                        <div class="wid-40">
                            <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_NO_SHARES'); ?></label>
                        </div>
                        <div class="wid-60">
                            <?php echo $item['TotalShares']; ?>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-3 spac_inpu_text">
                        <div class="wid-40">
                            <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DIV_PER_SHARE'); ?></label>
                        </div>
                        <div class="wid-60">
                            &pound; <?php echo round($item['NetAmount'] / $item['TotalShares']); ?>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-3 spac_inpu_text">
                        <div class="wid-40">
                            <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_PAID_DATE'); ?></label>
                        </div>
                        <div class="wid-60">
                            <?php echo cDate($item['PaidOn']); ?>
                        </div>
                    </div>
                    <?php if ($item['DesignationType'] == 'D'): ?>
                        <div class="Dirc-box">
                            <div class="col-md-5 col-md-offset-3 spac_inpu_text" >
                                <div class="wid-40">
                                    <label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_PAID_VIA_DIR'); ?></label>
                                </div>
                                <div class="wid-60">
                                    <?php
                                    if ($item['PaidByDirectorLoan'] == '0') {
                                        echo 'No';
                                    } else {
                                        echo 'Yes';
                                    }
                                    ?>
                                </div>
                            </div> 
                        </div>
                    <?php endif; ?>
                    <!--div class="col-md-5 col-md-offset-3 spac_inpu_text">
                            <div class="wid-40">
                                    <label>Is Paid?</label>
                            </div>
                            <div class="wid-60">
                                    <input type="checkbox" name="IsPaid" value="1" <?php echo ($item['Status'] == 2) ? 'checked="checked"' : ''; ?>>
                                    <span>Paid</span>
                            </div>
                    </div -->

                </div>
                <div class="clr"></div>
            </div>
        </div>
    </div>
</section>
<div class="modal-footer">
    <div class="col-md-12 spac_inpu_text text-right">
        <button type="button" class="btn btn-danger btn-sm btn-dsb" data-dismiss="modal">
            <i class="glyphicon glyphicon-remove-sign"></i>
            <?php echo $this->lang->line('BUTTON_CLOSE'); ?>
        </button>
    </div>
</div>
<input type="hidden" name="did" value="<?php echo $this->encrypt->encode($item['DID']); ?>"/>
<?php echo form_close(); ?>