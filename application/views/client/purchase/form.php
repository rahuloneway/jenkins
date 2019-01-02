<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$config = settings();
$vat_percent = '';
$var = '';

if (empty($vat_listing->Type)) {
    $vat_percent = 0;
    $var = 0;
} else {
    $vat_percent = $config['VAT_percentage'];
    $var = 1;
}
?>
<?php echo form_open('', array('id' => 'invoiceForm')); ?>
<?php
if (!isset($item)) {
    $item['UserID'] = 0;
    $item['Name'] = '';
    $item['Address'] = '';
    $item['InvoiceID'] = '';
    $item['DueDate'] = '';
    $item['InvoiceDate'] = '';
    $item['BankDetail'] = 0;
}
$style = '';
if (!isset($task)) {
    $task = 'addinvoice';
    $style = 'style="display:block;"';
} elseif ($task == 'changeInvoiceStatus') {
    $style = 'style="display:none;"';
}

/* Check if bank details are added or not */
if ($item['BankDetail'] == 0) {
    $checked = "";
} else {
    $checked = "checked='checked'";
}
$add_btn = "Add Item";
if ($item['InvoiceDate']=='01-01-1970' || $item['InvoiceDate']=='30-11--0001' || $item['InvoiceDate']=='') {
    $invoice_date = '';
} else {
    $invoice_date = $item['InvoiceDate'];
}

?>
<?php
if (!empty($bank_statement_id)) {
    ?>
    <div class="col-md-12">
        <a href="#" class="btn btn-success btn-xs pull-right preview_invoices">
            <?php echo $this->lang->line('INVOICE_PAGE_LABLE_VIEW_INVOICES'); ?>
        </a>
    </div>
    <div class="clr"></div>
    <?php
}
?>
<div class="panel panel-default panel_custom ">
    <div class="panel-body row">
        <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="col-md-5 no-padding">
                <b><?php echo $this->lang->line('CLIENT_PURCHASE_FORM_LABEL_SUPPLIER'); ?></b>
            </div>
            <div class="col-md-7 no-padding">
                <?php echo form_dropdown('customer', $users, $item['UserID'], 'id="customer"'); ?>
            </div>
        </div>
        <div class="col-md-2 col-sm-3 col-xs-12" style="width: 150px">
            <a href="#addcustomer" class="btn btn-primary btn-sm" id="addcustomer" <?php echo $style; ?>>
                <i class="fa fa-plus"></i><?php echo $this->lang->line('CLIENT_PURCHASE_FORM_LABEL_ADD_SUPPLIER'); ?>
            </a>
        </div>&nbsp;
		<div id="addCustomerDetail">
			<div class="col-md-3 col-sm-3 col-xs-12">
				<div class="col-md-5 text-padding ">
					<label><?php echo $this->lang->line('PURCHASE_PAGE_LABLE_SUPPLIER_NAME'); ?></label>
				</div>
				<div class="col-md-7">
					<input type="text" name="customerName" class="form-control" id="customerName" placeholder=" " value="<?php echo $item['Name']; ?>"/>
				</div>
			</div>	
			<div class="col-md-3 col-sm-3 col-xs-12 no-padding">
				<div class="col-md-5 text-padding ">
					<label><?php echo $this->lang->line('PURCHASE_PAGE_LABLE_SUPPLIER_ADDRESS'); ?></label>
				</div>
				<div class="col-md-7 col-sm-3 col-xs-12 no-padding">
					<div class="textarea">
						<textarea name="customerAddress" id="customerAddress" class="form-control"><?php echo $item['Address']; ?></textarea>
					</div>
				</div>
			</div>
			<?php
			if($item['Status']==333)
			{
			  ?>
			<input type="hidden" name="Status" value="<?php echo $item['Status']; ?>">
			<div class="col-md-3 col-sm-3 col-xs-12">
				<div class="col-md-5 text-padding ">
					<label><?php echo 'Paid Date'; ?></label>
				</div>
				<div class="col-md-7">
					<input type="text" name="PaidOn" class="datepicker form-control" id="paidDate" placeholder=" " value="<?php
					if ($item['PaidOn'] == '01-01-1970' || $item['PaidOn'] == '30-11--0001' || $item['PaidOn'] == '') {
						echo '';
					} else {
						echo $item['PaidOn'];
					}
					?>"/>
				</div>
			</div>	
			<?php
			}
			?>
		</div>	
    </div>
	<!--div class="panel-body row" id="addCustomerDetail">
        <div class="col-md-4 col-sm-3 col-xs-12">
            <div class="col-md-5 text-padding ">
                <label><?php echo $this->lang->line('PURCHASE_PAGE_LABLE_SUPPLIER_NAME'); ?></label>
            </div>
            <div class="col-md-7">
                <input type="text" name="customerName" class="form-control" id="customerName" placeholder=" " value="<?php echo $item['Name']; ?>"/>
            </div>
        </div>	
        <div class="col-md-4 col-sm-3 col-xs-12 no-padding">
            <div class="col-md-5 text-padding ">
                <label><?php echo $this->lang->line('PURCHASE_PAGE_LABLE_SUPPLIER_ADDRESS'); ?></label>
            </div>
            <div class="col-md-7 col-sm-3 col-xs-12 no-padding">
                <div class="textarea">
                    <textarea name="customerAddress" id="customerAddress" class="form-control"><?php echo $item['Address']; ?></textarea>
                </div>
            </div>
        </div>
        <?php
        if($item['Status']==3)
        {
          ?>
        <input type="hidden" name="Status" value="<?php echo $item['Status']; ?>">
        <div class="col-md-4 col-sm-3 col-xs-12">
            <div class="col-md-5 text-padding ">
                <label><?php echo 'Paid Date'; ?></label>
            </div>
            <div class="col-md-7">
                <input type="text" name="PaidOn" class="datepicker form-control" id="paidDate" placeholder=" " value="<?php
                if ($item['PaidOn'] == '01-01-1970' || $item['PaidOn'] == '30-11--0001' || $item['PaidOn'] == '') {
                    echo '';
                } else {
                    echo $item['PaidOn'];
                }
                ?>"/>
            </div>
        </div>	
        <?php
        }
        ?>
    </div-->
	<div class="panel-body row">
		<div class="col-md-4 col-sm-4 col-xs-12 no-padding">
			<div class="col-md-5 text-padding" >
				<b><?php echo $this->lang->line('INVOICE_PAGE_LABLE_SUPLIER_INVOICE_NO'); ?></b>
			</div>
			<div class="col-md-7 no-padding">
				<input type="text" name="supilerInvoiceNumber"class="form-control pull-left" id="supilerInvoiceNumber" value="<?php if( $item['SupilerInvoiceNumber'] !='' ){echo $item['SupilerInvoiceNumber'];}?>"/>
			</div>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-12 no-padding">
			<div class="col-md-5 text-padding" >
				<b><?php echo $this->lang->line('INVOICE_PAGE_LABLE_DUE_DATE'); ?></b>
			</div>
			<div class="col-md-7 no-padding">
				<input type="text" name="invoiceDate"class="datepicker form-control pull-left" id="invoiceDate" value="<?php if( $item['DueDate']=='01-01-1970' || $item['DueDate']=='30-11--0001' || $item['DueDate']=='' ){echo '';}else{echo $item['DueDate'];}?>"/>
			</div>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-12 no-padding">
			<div class="col-md-5 text-padding text-right">
				<b><?php echo $this->lang->line('CLIENT_PURCHASE_TABLE_LABEL_DDATE'); ?></b>
			</div>
			<div class="col-md-7">
				<input type="text" name="InvoiceDate"class="datepicker form-control pull-left" id="CurrentDate" value="<?php echo $invoice_date; ?>"/>
			</div>
		</div>	
		<div class="col-md-2 col-sm-3 col-xs-12">
			<input type="checkbox" name="bankdetail" id="bankdetail" class="pull-left no-padding"<?php echo $checked; ?>/>
			<label><?php echo $this->lang->line('INVOICE_PAGE_LABLE_ADD_BANK_DETAIL'); ?></label>
		</div>	
	</div>    
</div>
<div class="clr"></div>
<div class="popup">
    <div class="clr"></div>
    <table id="invoiceTable" class="tbl-editable">
        <thead>
            <tr>
                <th><?php echo $this->lang->line('INVOICE_PAGE_LABLE_ITEM'); ?></th>
                <th><?php echo $this->lang->line('INVOICE_PAGE_LABLE_DESCRIPTION'); ?></th>
				<th><?php echo $this->lang->line('BANK_TABLE_COLUMN_MAIN_CATEGORY'); ?></th>
				<th><?php echo $this->lang->line('BANK_TABLE_COLUMN_CATEGORY'); ?></th>
                <th width="80">
                    <?php echo $this->lang->line('CLIENT_PURCHASE_LABLE_QUANTITY'); ?>
                </th>
                <th><?php echo $this->lang->line('CLIENT_PURCHASE_LABLE_UNIT_PRICE'); ?></th>
                <?php
                if ($var == 0) {
                    ?>
                    <th></th>
                <?php } else {
                    ?>
                    <th><?php echo 'VAT %'; ?></th>
                <?php } ?>
                <th colspan="2"><?php echo $this->lang->line('INVOICE_PAGE_LABLE_AMOUNT_GBP'); ?></th>
            </tr>
        </thead>
        <tbody id="addItems">
            <?php
            $sub_total = 0;
            $vat_total = 0;
            $total = 0;
        
            if (isset($item['InvoiceItems'])) {
                $i = 1;
                foreach ($item['InvoiceItems'] as $key => $val) {					
                    $sub_total += $val->Quantity * $val->UnitPrice;
                    $vat_total += ($val->Quantity * $val->UnitPrice) * $vat_percent / 100;
                    $total = $sub_total + $vat_total;
                    ?>
                <input type="hidden" name="InvoiceID" value="<?php echo $this->encrypt->encode($item['InvoiceID']); ?>"/>
                <input type="hidden" name="ItemID[]" value="<?php echo $this->encrypt->encode($val->ItemID); ?>"/>
                <tr id="r<?php echo $i; ?>">
                    <td class="sno"><?php echo $i; ?></td>
                    <td class="form-group has-feedback">
                        <input type="text" name="description[]" class="form-control description_error" value="<?php echo $val->Description; ?>"/>
                    </td>
					<td style="width:17%;" class="exParentCattd">
						<?php $parentcat = getCategoryParent('GEN'); ?>
						<select id="ExpenseCategory[]" class="form-control ExpenseCategory exParentCat" name="ExpenseParentCategory[]">
							<option selected="selected" value="0">--Select Category--</option>
							<?php foreach($parentcat as $parentcat){ ?>             
							<option value="<?php echo $parentcat->id;?>"><?php  echo $parentcat->title;?></option>
							<?php } ?>
						</select>
					</td>
					<td class="exChildCattd sm-width-box">
						<select id="Category[]" class="form-control category sm-width-box tdtab" name="Category[]">
							<option selected="selected" value="<?php echo $item['InvoiceItems'][0]->Category; ?>"><?php echo getCategoryName($item['InvoiceItems'][0]->Category); ?></option>
						</select>
					</td>
                    <td class="form-group has-feedback">
                        <input type="text" name="quantity[]" class="form-control quantity_error validNumber working_hours" maxlength="10" value="<?php echo $val->Quantity;?>"/>
                    </td>
                    <td>
                        <input type="text" name="unitprice[]" class="input-sm validNumber form-control hourly_rate" maxlength="10" value="<?php echo round($val->UnitPrice,2);?>"/>
                    </td>
                    <?php
                    if ($var == 0) {
                        ?>
                        <td class="purchasetab">
                            <input type="hidden" name="vat[]"class="sm-width validNumber form-control"value="0" maxlength="5" value="<?php echo $val->Tax; ?>"/>
                        </td>
                    <?php } else { ?>
                        <td class="purchasetab">
                            <input type="text" name="vat[]"class="sm-width validNumber form-control"value="<?php echo $config['VAT_percentage']; ?>"maxlength="5" value="<?php echo $val->Tax; ?>"/>
                        </td>
                    <?php } ?> 
                    <td class="text-right gbp" id="gbp<?php echo $i; ?>" style="width:80px;">
                        <?php echo numberFormat($val->Quantity * $val->UnitPrice); ?>
                    </td>
                    <td>
                        <a class="btn removeInvoiceItem" <?php echo $style; ?>  id="<?php echo $val->ItemID; ?>"><i class="fa fa-times"></i> </a>
                    </td>
                </tr>
                <?php
                $i++;
            }
        } else {
            ?>
            <?php
            for ($i = 1; $i <= 1; $i++) {
                ?>
                <tr id="r<?php echo $i; ?>">
                    <td class="sno"><?php echo $i; ?></td>
                    <td class="form-group has-feedback">
                        <input type="text" name="description[]" class="form-control description_error"/>
                    </td>
					<td style="width:17%;" class="exParentCattd">
						<?php $parentcat = getCategoryParent('GEN'); ?>
						<select id="ExpenseCategory[]" class="form-control ExpenseCategory exParentCat" name="ExpenseParentCategory[]">
							<option selected="selected" value="0">--Select Category--</option>
							<?php foreach($parentcat as $val){ ?>             
							<option value="<?php echo $val->id;?>"><?php  echo $val->title;?></option>
							<?php } ?>
						</select>
					</td>
					<td class="exChildCattd sm-width-box">
						<select id="Category[]" class="form-control category sm-width-box tdtab" name="Category[]">
							<option selected="selected" value="0">--Select Category--</option>
						</select>
					</td>
                    <td class="form-group has-feedback">
                        <input type="text" name="quantity[]" class="form-control validNumber quantity_error working_hours" min="1" maxlength="10"/>
                    </td>
                    <td>
                        <input type="text" name="unitprice[]" class="input-sm validNumber form-control hourly_rate" maxlength="10" />
                        <div id="unitprice_error" style="display:none"></div>
                    </td>
                    <?php
                    if ($var == 0) {
                        ?>
                         <td class="purchasetab">
                            <input type="hidden" name="vat[]"class="sm-width validNumber form-control"value="0" maxlength="5"/>
                        </td>

                    <?php } else { ?>    
                         <td class="purchasetab">
                            <input type="text" name="vat[]"class="sm-width validNumber form-control"value="<?php echo $config['VAT_percentage']; ?>"maxlength="5"/>
                        </td>
                    <?php } ?>
                    <td class="text-right gbp" id="gbp<?php echo $i; ?>" style="width:80px;">
                        0.0
                    </td>
                    <td>
                        <a class="btn removeInvoiceItem"><i class="fa fa-times"></i></a>
                    </td>
                </tr>
                <?php
            } //Closing for loop
        } //closing else body
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">
                    <a href="#" class="btn btn-primary btn-sm" id="add-invoice-item" <?php echo $style; ?>>
                        <i class="fa fa-plus"></i><?php echo $add_btn; ?></a>
                </td>
                <td  class="well text-right">
                    <p class="bold"><?php echo $this->lang->line('INVOICE_PAGE_LABLE_SUBTOTAL'); ?></p>
                    <?php
                    if ($var == 0) {
                        ?> 
                    <?php } else { ?>
                        <p class="bold"><?php echo $this->lang->line('INVOICE_PAGE_LABLE_VAT'); ?></p>
                    <?php } ?>
                    <p class="bold"><?php echo $this->lang->line('INVOICE_PAGE_LABLE_TOTAL'); ?></p>
                </td>
                <td class="well text-right">
                    <p id="subtotal" class="bold">
                        &nbsp;&nbsp;&nbsp;<?php echo numberFormat($sub_total); ?>
                    </p>
                    <?php
                    if ($var == 0) {
                        ?>
                        <p id="totalvat" class="bold" style="display:none;">
                            &nbsp;<?php echo numberFormat($vat_total); ?>
                        </p>
                    <?php } else { ?>
                        <p id="totalvat" class="bold">
                            &nbsp;<?php echo numberFormat($vat_total); ?>
                        </p>
                    <?php } ?>
                    <p id="grandTotal" class="bold">
                        &nbsp;<?php echo numberFormat($total); ?>
                    </p>
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
        <?php if ($task == 'create'): ?>
            <a href="<?php echo $this->encrypt->encode('ACTION_DELETE/' . $item['InvoiceID'] . '/' . $this->uri->segment(3)); ?>" class="btn btn-danger btn-sm spacer" id="delete-invoice">
                <i class="fa fa-close"></i><?php echo $this->lang->line('BUTTON_DELETE'); ?>
            </a>
        
            <?php
            if ($item['Status'] == 1) {
                ?>
                <a href="<?php echo $this->encrypt->encode('ACTION_UPDATE/' . $item['InvoiceID'] . '/' . $this->uri->segment(3)); ?>" class="btn btn-primary btn-sm spacer" id="update-invoice">
                    <i class="fa fa-file-text"></i><?php echo $this->lang->line('BUTTON_UPDATE'); ?>
                </a>
                <a href="#" class="btn btn-success btn-sm spacer" id="uCreateInvoice">
                    <i class="fa fa-file-text"></i><?php echo $this->lang->line('BUTTON_CREATE_AND_FINISH'); ?>
                </a>
                <?php
            } else {
                ?>
                <a href="#" class="btn btn-success btn-sm spacer" id="uCreateInvoice">
                    <i class="fa fa-file-text"></i><?php echo 'Update'; ?>
                </a>
                <?php
            }
            ?>
        
        <?php elseif ($task == 'addinvoice' || $task == 'addCreditnote'): ?>
            <a href="#" class="btn btn-primary btn-sm spacer" id="save-invoice">
                <i class="glyphicon glyphicon-floppy-save"></i><?php echo $this->lang->line('BUTTON_DRAFT'); ?>
            </a>
            <a href="#" class="btn btn-success btn-sm spacer" id="create-invoice">
                <i class="fa fa-file-text"></i><?php echo $this->lang->line('BUTTON_CREATE_AND_FINISH'); ?>
            </a>
        <?php elseif ($task == 'copyInvoice'): ?>
            <a href="<?php echo $this->encrypt->encode('ACTION_COPY/' . $item['InvoiceID'] . '/' . $this->uri->segment(3)); ?>" class="btn btn-primary btn-sm spacer" id="copy-invoice">
                <i class="glyphicon glyphicon-floppy-save"></i><?php echo $this->lang->line('BUTTON_SAVE'); ?>
            </a>
            <a href="#" class="btn btn-success btn-sm spacer" id="eCreateInvoice">
                <i class="fa fa-file-text"></i><?php echo $this->lang->line('BUTTON_CREATE_AND_FINISH'); ?>
            </a>
        <?php endif; ?>
        <a href="#" class="btn btn-danger btn-sm spacer" data-dismiss="modal">
            <i class="glyphicon glyphicon-remove-sign"></i><?php echo $this->lang->line('BUTTON_CANCEL'); ?>
        </a>
    </div>
</div>
<input type="hidden" name="delinvoiceId" id="delinvoiceId" class="delinvoiceId" value=""/>
<input type="hidden" name="bank_statement_id" value="<?php echo $bank_statement_id; ?>"/>
<input type="hidden" name="ajax_add" value="<?php echo $ajax_add; ?>"/>
<input type="hidden" name="invoice_type" value="<?php echo $invoice_type; ?>"/>
<input type="hidden" name="bank_paid_date" value="<?php echo $bank_paid_date; ?>"/>
<?php echo form_close(); ?>