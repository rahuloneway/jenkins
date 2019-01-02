<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php echo form_open(site_url('clients/banks/saveBankStatement'), array('id' => 'saveBankStatementForm')); ?>
<div class="panel panel-default panel_custom ">
    <div class="panel-body row">
        <div class="col-md-2 col-sm-3 col-xs-12 no-padding">
            <div class="col-md-5 text-padding" >
                <b><?php echo $this->lang->line('BANK_PAGE_LABEL_DATE'); ?></b>
            </div>
            <div class="col-md-7 no-padding">
                <input type="text" name="TransactionDate" class="datepicker form-control pull-left" id="TransactionDate" value=""/>
            </div>
        </div>
        <div class="col-md-6 col-sm-3 col-xs-12">
            <div class="col-md-4 text-padding">
                <b><?php echo $this->lang->line('BANK_PAGE_LABEL_CATEGORY'); ?></b>
            </div>
            <div class="col-md-6">
                <?php echo form_dropdown('category', $categories, '', 'id="bankCategory"'); ?>
            </div>
        </div>        
        <div class="col-md-4 col-sm-3 col-xs-12">
            <div class="col-md-4 text-padding">
                <b><?php echo $this->lang->line('BANK_PAGE_LABEL_TYPE'); ?></b>
            </div>
            <div class="col-md-6">
                <?php echo form_dropdown('type', $types, '', 'id="type"'); ?>
            </div>
        </div>  
    </div>
</div>
<div class="clr"></div>
<div class="popup">
    <div class="clr"></div>
    <table id="invoiceTable" class="tbl-editable">
        <thead>
            <tr>
                <th width="300"><?php echo $this->lang->line('BANK_PAGE_LABEL_DESCRIPTION'); ?></th>
                <th><?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_OUT'); ?></th>
                <th><?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_IN'); ?></th>
                <th><?php echo $this->lang->line('BANK_TABLE_COLUMN_BALANCE'); ?></th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody id="addItems">
            <tr id="r">
                <td class="form-group has-feedback">
                    <input type="text" name="description" id="description" class="form-control description_error"/>
                </td>
                <td class="form-group has-feedback">
                    <input type="text" name="money_out"  id="moneyOut" class="form-control validNumber" min="1" maxlength="10"/>
                </td>
                <td>
                    <input type="text" name="money_in"  id="moneyIn" class="validNumber form-control" maxlength="10" />
                </td>   
                <td>
                    <input type="text" name="balance"  id="Balance" class="validNumber form-control" value="" maxlength="10"/>
                </td>	
                <td colspan="2"></td>		
            </tr>				
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">
                </td>                
                <td class="well text-right"></td>
            </tr>
        </tfoot>
    </table>
</div>
<br/><br/>
<div class="modal-footer">
    <div class="progress pull-left" style="display:none;">
        <img src="<?php echo site_url(); ?>/assets/images/progress.gif"/>
    </div>
    <div class="pull-right col-md-6">
        <a href="#" class="btn btn-success btn-sm spacer" id="createBankStatementBtnd">
            <i class="fa fa-file-text"></i><?php echo $this->lang->line('BUTTON_CREATE_AND_FINISH'); ?>
        </a>
        <a href="#" class="btn btn-danger btn-sm spacer" data-dismiss="modal">
            <i class="glyphicon glyphicon-remove-sign"></i><?php echo $this->lang->line('BUTTON_CANCEL'); ?>
        </a>
    </div>
</div>
<?php echo form_close(); ?>


