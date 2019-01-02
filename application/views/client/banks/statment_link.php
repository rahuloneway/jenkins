<?php //echo "<pre>";print_r($statment); echo "</pre>"; $statment[0]->ID; echo count($statment); ?>
<?php $page = 1; $total =  count($statment);?>
<div class="table_b_updte table-responsive">
	<input type="hidden" name="statmentId" id="statmentId" value="<?php echo $statment[0]->ID; ?>">
	<input type="hidden" name="statmentType" id="statmentType" value="<?php echo $linkType; ?>">
	<input type="hidden" name="statmentAmount" id="statmentAmount" value="<?php if($statment[0]->MoneyOut != 0){ echo $statment[0]->MoneyOut; } else{ echo $statment[0]->MoneyIn;} ?>">
	<table class="table-striped tble_colr_txt">
		<thead>
			<tr class="salary-table">				
				<th>
					#
				</th>
				<th width="8%">
					<?php echo $this->lang->line('BANK_TABLE_COLUMN_BANK'); ?>
				</th>
				<th width="10%">
					<?php echo $this->lang->line('BANK_TABLE_COLUMN_DATE'); ?>
				</th>
				<th>
					<?php echo $this->lang->line('BANK_TABLE_COLUMN_TYPE'); ?>
				</th>
				<th width="9%">
					<?php echo $this->lang->line('BANK_TABLE_COLUMN_DESCRIPTION'); ?>
				</th>
			   <th width="10%">
					<?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_OUT'); ?>
				</th>
				<th width="8%">
					<?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_IN'); ?>
				</th>
			   <th width="8%">
					<?php echo $this->lang->line('BANK_TABLE_COLUMN_BALANCE'); ?>
				</th>
				<?php if (!empty($aaccess)) { ?>
					<th width="8%">
						<?php
						echo $this->lang->line('BANK_TABLE_COLUMN_CHECK');
						?>
					</th>
				<?php } ?>
				<th width="10%">
					<?php echo $this->lang->line('BANK_TABLE_COLUMN_MAIN_CATEGORY'); ?>
				</th>
				<th width="10%">
					<a href="<?php echo $this->encrypt->encode('SORT_BY_CATEGORY'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_CATEGORY'); ?>" class="sort color">
						<?php echo $this->lang->line('BANK_TABLE_COLUMN_CATEGORY'); ?>
						<?php
							//getSortDirection($order, 'SORT_BY_CATEGORY', $asc_order_value);
						?>
					</a>
				</th> 
				<th width="10%">
					<?php echo $this->lang->line('BANK_TABLE_COLUMN_ACTIONS'); ?>
				</th>				
			</tr>
		</thead>
		<tbody id="link-bank-listing">
			<tr>							
				<td>1</td>
				<td><?php echo $statment[0]->bankId; ?></td>
				<td id= "bankTransactionDate"><?php echo $statment[0]->TransactionDate; ?></td>
				<td><?php echo $statment[0]->Type; ?></td>
				<td><?php echo $statment[0]->Description; ?></td>
				<td class="text-right"><?php if($statment[0]->MoneyOut != 0) echo $statment[0]->MoneyOut; ?></td>
				<td class="text-right"><?php if($statment[0]->MoneyIn != 0) echo $statment[0]->MoneyIn; ?></td>
				<td class="text-right"><?php if($statment[0]->Balance != 0) echo $statment[0]->Balance; ?></td>				
				<td class="exParentCattd text-right" style="width:15%;">
					<?php echo getCategoryParentName($statment[0]->Category); ?>
				</td>
				<td class="exParentCattd text-right" style="width:15%;">	
					<?php echo getCategoryName($statment[0]->Category); ?>
				</td>	
				<td class="linkedTo" style="width:17%;">					
				</td>				
			</tr>
		</tbody>
	</table>
</div>

<!--<input class="linkRefrence" type="hidden" value="" name="linkRefrence[]">
<div class="input-group drop-downs srch-div">
	<input type="textbox" placeholder="Chris" name="linkbankstatment" required="required" class="form-control linkbankstatment">
	<span class="glyphicon glyphicon-search ico" aria-hidden="true"></span>
	<div class="div-position" id="clientList" style="display:none">
		<ul id="clientUl">
		</ul>
	</div>
</div>-->


<div class="panel panel-default panel_custom" id="linkaction" >
	<div class="panel-body row">
		<div class="col-md-3 pull-left">
			
		</div>
		
		<div class="col-md-3 pull-left">
		<?php	$link = $this->encrypt->encode($statment[0]->ID);
			if(strpos(categoryNameTrialCat($statment[0]->Category), 'Customer') !== false){  ?>
			<a class="btn btn-primary btn-xs color createBankInvoice" href="<?php echo $link; ?>">CREATE INVOICE</a>
			<?php }
			
			if(strpos(categoryNameTrialCat($statment[0]->Category), 'Shareholder') !== false){  ?>
			<a class="btn btn-success btn-xs color createBankDividend" href="<?php echo $link; ?>">CREATE DIVIDEND</a>
			<?php } ?>
		</div>
		
		<div class="col-md-3 pull-left">
			<?php if($linkType == 'All'){ ?>
				<a href="javascript:void(0);" class="btn btn-inverse nextBankStatmentToLink" data-page="<?php echo $page;?>" data-total="<?php echo $total; ?>">                                    
					Link &Next
				</a>
			<?php } else { ?>
				<a href="javascript:void(0);" class="btn btn-inverse closeBankStatmentToLink"  data-page="<?php echo $page;?>" data-total="<?php echo $total; ?>" data-id="" data-sid="">                                    
					Link &Close
				</a>
			<?php } ?>
			
		</div>
	</div>
</div>

<div class="table-responsive">
	<?php  if($linkType == 'Customer' || $linkType == 'Supplier'){ ?>
		<table>
			<thead>
				<tr class="salary-table">
					<th> </th>
					<th>#</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_ID'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_INVOICE'); ?>" class="sort color">
							<?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_INVOICE'); ?>
							<?php
								//getSortDirection($order, 'SORT_BY_ID', $asc_order_value);
							?>
						</a>
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_NAME'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_NAME'); ?>" class="sort color">
							<?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_NAME'); ?>
							<?php
								//getSortDirection($order, 'SORT_BY_NAME', $asc_order_value);
							?>
						</a>
					</th>
					<th>
						<?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_AMOUNT'); ?>
					</th>
					<?php if (empty($vat_listing->Type) && empty($vat_listing->PercentRateAfterEndDate) && empty($vat_listing->PercentRate)) { ?>

					<?php } else { ?>
						<th>
							<?php echo $this->lang->line('CLIENT_INVOICE_LABLE_VAT_TWO'); ?>
						</th>
					<?php } ?>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_AMOUNT'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_TOTAL'); ?>" class="sort color">
							<?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_TOTAL_AMOUNT'); ?>
							<?php
								//getSortDirection($order, 'SORT_BY_AMOUNT', $asc_order_value);
							?>
						</a>
					</th>
					<?php if ($vat_listing->Type != ''): ?>
						<th>
							<?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_FLAT_RATE'); ?>
						</th>
						<th>
							<?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_SALES'); ?>
						</th>
					<?php endif; ?>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_CDATE'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_CDATE'); ?>" class="sort color">
							<?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_CDATE'); ?>
							<?php
								//getSortDirection($order, 'SORT_BY_CDATE', $asc_order_value);
							?>
						</a>
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_DDATE'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_IN_DDATE'); ?>" class="sort color">
							<?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_DDATE'); ?>
							<?php
								//getSortDirection($order, 'SORT_BY_DDATE', $asc_order_value);
							?>
						</a>
					</th>
					<th>
						<?php echo $this->lang->line('INVOICE_PAGE_LABLE_PAID_DATE'); ?>
					</th>
					<!--<th>
						<?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_STATUS'); ?>
					</th>
					<th>
						<?php echo $this->lang->line('CLIENT_INVOICE_TABLE_LABEL_ACTION'); ?>
					</th>-->
				</tr>
			</thead>
			<tbody class="invoiceListing">
			<?php 			
				$sn = 1;
				if(!empty($invoiceList)){
					foreach ($invoiceList as $key => $val) {		
						if ($val->InvoiceTotal < 0) {
							$class = "class='less-amount'";
						} else {
							$class = "";
						}
						echo '<tr ' . $class . '>';
						echo '<td><input class="linkCheckbox" name="linkCheckbox" value="$val->InvoiceID" type="checkbox" data-id="'.$val->InvoiceID.'" data-name="'.$val->InvoiceNumber.'"></td>';
						echo '<td ' . $class . '>';
						echo $sn;
						echo '</td>';
						echo '<td class="item-id">';
						$link = $this->encrypt->encode($val->InvoiceID);

						if ($val->Status == 1) {
							$class = "class='createInvoice'";
						} elseif ($val->Status == 2) {
							$class = "class='markPaid'";
						} elseif ($val->Status == 3) {
							$class = " class='showPaid' ";
						}
						echo '<a href="' . $link . '" ' . $class . '>';
						echo $val->InvoiceNumber;
						echo '</a>';
						echo '</td>';
						echo '<td>';
						echo $val->Name;
						echo '</td>';
						echo '<td class="text-right">';
						if ($val->InvoiceTotal == 0) {
							echo numberFormat($val->InvoiceTotal);
						} elseif ($val->InvoiceTotal < 0) {
							echo numberFormat(($val->InvoiceTotal + $val->Tax));
						} else {
							echo numberFormat(($val->InvoiceTotal - $val->Tax));
						}
						//echo $val->SubTotal;
						echo '</td>';
						if (empty($vat_listing->Type) && empty($vat_listing->PercentRateAfterEndDate) && empty($vat_listing->PercentRate)) {
							
						} else {
							echo '<td class="text-right">';
							if ($val->InvoiceTotal != 0) {
								echo numberFormat($val->Tax);
							} else {
								echo numberFormat('0');
							}
							echo '</td>';
						}
						echo '<td class="text-right">';
						echo numberFormat($val->InvoiceTotal);
						echo '</td>';
						if ($vat_listing->Type != '') {
							echo '<td class="text-right">';
							echo numberFormat($val->FlatRate);
							echo '</td>';
							echo '<td class="text-right">';
							echo numberFormat($val->NetSales);
							echo '</td>';
						}
						echo '<td>';
						if (strtotime($val->InvoiceDate) != '') {
							echo cDate($val->InvoiceDate);
						} else {
							echo '';
						}
						//echo cDate($val->InvoiceDate);
						echo '</td>';
						echo '<td>';
						if (strtotime($val->DueDate) != '') {
							echo cDate($val->DueDate);
						} else {
							echo '';
						}
						echo '</td>';
						echo '<td>';
						if (strtotime($val->PaidOn) != '') {
							echo cDate($val->PaidOn);
						} else {
							echo '';
						}
						echo '</td>';
						/*echo '<td>';
							if ($val->Status == 1) {
								$link = '<span class="btn btn-xs btn-primary">DRAFT</span>';
							} elseif ($val->Status == 2) {
								if ($access) {
									$link = $this->encrypt->encode('ACTION_PAID/' . $val->InvoiceID . '/' . $page);
									$tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_IN_PAID') . '"';
									$link = '<a class="btn btn-xs btn-info color changeToPaid" href="' . $link . '" ' . $tooltip . '>CREATED</a>';
								} else {
									$link = '<span class="btn btn-xs btn-info color">CREATED</span>';
								}
							} elseif ($val->Status == 3) {
								$link = '<span class="btn btn-xs btn-success pointer">PAID</span>';
							} else {
								$link = '';
							}
							echo $link;
						echo '</td>';*/
						//echo '<td>';
							//echo '&nbsp;<a href="javascript:void(0);" data-id="'.$val->InvoiceID.'" data-name="'.$val->InvoiceNumber.'" class="linkThis btn-primary btn-xs color pointer">Link</a>';
						//echo '</td>';
						echo '</tr>';
						$sn++;
					}
				}else{
					echo "<tr> <td>No records found </td></tr>";
				}
				?>
			</tbody>
		</table>
	<?php } ?>
	
	<?php if($linkType == 'Shareholder'){ 
		//echo "<pre>"; print_r($invoiceList); echo "</pre>";  ?>
		<table>
			<thead>
				<tr class="salary-table">
					<th>#</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_DIVIDEND_VOUCHER');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_DIVIDEND_VOUCHER');?>" class="sort color">
							<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DIV_NUMBER');?>
							<?php
								getSortDirection($order,'SORT_BY_DIVIDEND_VOUCHER',$asc_order_value);
							?>
						</a>
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_DATE');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_DIVIDEND_DATE');?>" class="sort color">
							<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DATE');?>
							<?php
								getSortDirection($order,'SORT_BY_DATE',$asc_order_value);
							?>
						</a>
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_SHARERNAME');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_SHARERNAME');?>" class="sort color">
							<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_SHARE_NAME');?>
							<?php
								getSortDirection($order,'SORT_BY_SHARERNAME',$asc_order_value);
							?>
						</a>
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_NET_AMOUNT');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_NET_AMOUNT');?>" class="sort color">
							<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_NET_AMOUNT');?>
						</a>
						<?php
							getSortDirection($order,'SORT_BY_NET_AMOUNT',$asc_order_value);
						?>
					</th>
					<th>
						<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DIV_PER_SHARE');?>
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_TAX_AMOUNT');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_TAX_AMOUNT');?>" class="sort color">
							<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_TAX_CREDIT');?>
						</a>
						<?php
							getSortDirection($order,'SORT_BY_TAX_AMOUNT',$asc_order_value);
						?>
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_GROSS_AMOUNT');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_GROSS_AMOUNT');?>" class="sort color">
							<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_GROSS_DIV');?>
							<?php
								getSortDirection($order,'SORT_BY_GROSS_AMOUNT',$asc_order_value);
							?>
						</a>
					</th>
					<th>
						<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DIV_STATUS');?>
					</th>
					<!--<th>
						<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_PAID_DATE');?>
					</th>
					<th>
						<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_ACTION');?>
					</th>-->
				</tr>
			</thead>					
			<tbody class="invoiceListing">
			<?php 			
				$sn = 1;
				if(!empty($invoiceList)){
					foreach ($invoiceList as $key => $val) {	
						
						echo '<tr>';
						echo '<td><input class="linkCheckbox" name="linkCheckbox" value="$val->DID" type="checkbox" data-id="'.$val->DID.'" data-name="'.$val->VoucherNumber.'"></td>';
						echo '<td class="item-id">';
						$link = $this->encrypt->encode($val->DID);

						if ($val->Status == 1) {
							$class = "class='createInvoice'";
						} elseif ($val->Status == 2) {
							$class = "class='markPaid'";
						} elseif ($val->Status == 3) {
							$class = " class='showPaid' ";
						}
						echo '<a href="' . $link . '" ' . $class . '>';
						echo $val->VoucherNumber;
						echo '</a>';
						echo '</td>';
						echo '<td>';
						echo cDate($val->DividendDate);
						echo '</td>';
						echo '<td>';
						echo $val->ShareholderName;
						echo '</td>';
						echo '<td class="text-right">';
						echo '&pound; ' . number_format($val->NetAmount, 2, '.', ',');
						echo '</td>';
						echo '<td class="text-right">';
						if ($val->TotalShares == 0) {
							echo '0';
						} else {
							echo '&pound; ' . number_format($val->NetAmount / $val->TotalShares, 2, '.', ',');
						}

						echo '</td>';
						echo '<td class="text-right">';
						echo '&pound; ' . number_format($val->TaxAmount, 2, '.', ',');
						echo '</td>';
						echo '<td class="text-right">';
						echo '&pound; ' . number_format($val->GrossAmount, 2, '.', ',');
						echo '</td>';					
						echo '<td>';
						echo cDate($val->PaidOn);
						echo '</td>';					
						/*echo '<td>';
							if ($val->Status == 1) {
								$link = '<span class="btn btn-xs btn-primary">DRAFT</span>';
							} elseif ($val->Status == 2) {
								if ($access) {
									$link = $this->encrypt->encode('ACTION_PAID/' . $val->InvoiceID . '/' . $page);
									$tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_IN_PAID') . '"';
									$link = '<a class="btn btn-xs btn-info color changeToPaid" href="' . $link . '" ' . $tooltip . '>CREATED</a>';
								} else {
									$link = '<span class="btn btn-xs btn-info color">CREATED</span>';
								}
							} elseif ($val->Status == 3) {
								$link = '<span class="btn btn-xs btn-success pointer">PAID</span>';
							} else {
								$link = '';
							}
							echo $link;
						echo '</td>';*/
						//echo '<td>';
							//echo '&nbsp;<a href="javascript:void(0);" data-id="'.$val->InvoiceID.'" data-name="'.$val->InvoiceNumber.'" class="linkThis btn-primary btn-xs color pointer">Link</a>';
						//echo '</td>';
						echo '</tr>';
						$sn++;
					} 
				}else{
					echo "<tr> <td>No records found </td></tr>";
				}
				?>
			</tbody>
		</table>
	<?php } ?>
	<?php if($linkType == 'Employee'){ 
	//echo "<pre>"; print_r($invoiceList); echo "</pre>";  die('client bank statment_link view 452'); ?>
		<table>
			<thead>
				<tr class="salary-table">
					<th>
						#
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_EXPENSE'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_EXPENSE'); ?>" class="sort color">
							<?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_EX_ID'); ?>
							<?php
							getSortDirection($order, 'SORT_BY_EXPENSE', $asc_order_value);
							?>
						</a>
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_NAME'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_NAME'); ?>" class="sort color">
							<?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_EMP_NAME'); ?>
							<?php
							getSortDirection($order, 'SORT_BY_NAME', $asc_order_value);
							?>
						</a>
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_MONTH'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_MONTH'); ?>" class="sort color">
							<?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MONTH'); ?>
							<?php
							getSortDirection($order, 'SORT_BY_MONTH', $asc_order_value);
							?>
						</a>
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_MILES'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_MILES'); ?>" class="sort color">
							<?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MILEAGE'); ?>
							<?php
							getSortDirection($order, 'SORT_BY_MILES', $asc_order_value);
							?>
						</a>
					</th>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_AMOUNT'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_AMOUNT'); ?>" class="sort color">
							<?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_TOTAL_AMOUNT'); ?>
							<?php
							getSortDirection($order, 'SORT_BY_AMOUNT', $asc_order_value);
							?>
						</a>
					</th>
					<?php
					if ($user['VAT_TYPE'] == 'stand'):
						?>
						<th>
							<?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_VAT_PAID'); ?>
						</th>
						<?php
					endif;
					?>
					<th>
						<a href="<?php echo $this->encrypt->encode('SORT_BY_FILES'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_FILES'); ?>" class="sort color">
							<?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_ADD_FROM'); ?>
							<?php
							getSortDirection($order, 'SORT_BY_FILES', $asc_order_value);
							?>
						</a>
					</th>					
				</tr>
			</thead>                    					
			<tbody class="invoiceListing">
			<?php 			
				$sn = 1;
				if(!empty($invoiceList)){
					foreach ($invoiceList as $key => $val) {				
						echo '<tr>';
						echo '<td><input class="linkCheckbox" name="linkCheckbox" value="$val->DID" type="checkbox" data-id="'.$val->ID.'" data-name="'.$val->ExpenseNumber.'"></td>';
						echo '<td class="item-id">';
						if ($val->Status == 1) {
							if ($val->ExpenseType == 'CREDITCARD') {
								$class = "editExpense creditcard";
							} else {
								$class = "editExpense";
							}
						} else {
							if ($val->ExpenseType == 'CREDITCARD') {
								$class = "viewExpense creditcard";
							} else {
								$class = "viewExpense";
							}
						}
						$href = $this->encrypt->encode($val->ID);
						echo '<a href="' . $href . '" class="' . $class . '">' . $val->ExpenseNumber . '</a>';
						echo '</td>';
						echo '<td>';
						echo $val->EmployeeName;
						echo '</td>';
						echo '<td>';
						echo date("M", mktime(0, 0, 0, $val->Month, 1, 0)) . ' \'' . substr($val->Year, -2);
						;
						echo '</td>';
						echo '<td>';
						echo $val->TotalMiles;
						echo '</td>';
						echo '<td class="text-right">';
						echo numberFormat($val->TotalAmount);
						echo '</td>';
						if ($user['VAT_TYPE'] == 'stand') {
							echo '<td class="text-right">';
							echo numberFormat($val->TotalVATAmount);
							echo '</td>';
						}
						echo '<td>';
						if ($val->FileID == 0) {
							echo '<span class="label label-danger">Added manually</span>';
						} else {
							echo getFileName($val->FileID);
						}
						echo '</td>';					
						
						echo '</tr>';
						$sn++;
					}
				}else{
					echo "<tr> <td>No records found </td></tr>";
				}				
				?>
			</tbody>
		</table>
	<?php } ?>
</div>