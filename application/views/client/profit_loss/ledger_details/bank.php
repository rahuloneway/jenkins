<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	$sn =1;
	
	if( !$details || empty($details) || count($details) <=0 ){
		echo "<div class='alert alert-danger text-center'><i class='glyphicon glyphicon-exclamation-sign'></i>&nbsp;";
			echo $this->lang->line("ERROR_LOADING_LEDGER_POPUP_DETAILS");
		echo "</div>";
	}else{ ?> 
		<div class="table_b_updte table-responsive">
			<table class="table-striped tble_colr_txt">
				<thead>
					<tr class="salary-table">
						<th>
							#
						</th>
						<th width="13%">
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_DATE');?>
						</th>
						<th>
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_TYPE');?>
						</th>
						<th width="250">
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_DESCRIPTION');?>
						</th>
						<th>
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_OUT');?>
						</th>
						<th>
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_IN');?>
						</th>
						<th>
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_BALANCE');?>
						</th>
						<th>
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_CHECK');?>
						</th>
						<th width="100">
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_CATEGORY');?>
						</th>	
					</tr>
				</thead>
				<tbody id="bank-listing">
					<?php 
					foreach( $details as $key=> $val){
						
						if($key == 0)
						{
							$balance_check = (float)$val["CheckBalance"] + (float)$val["MoneyIn"] - (float)$val["MoneyOut"];
						}
						
						echo '<tr>';
							echo '<td>'.$sn.'</td>';$sn++;
							echo '<td>'.date('jS F Y',strtotime($val["TransactionDate"])).'</td>';
							echo '<td>'.$val["Type"].'</td>';
							echo '<td>'.$val["Description"].'</td>';
							echo '<td class="text-right">'.numberFormat($val["MoneyOut"]).'</td>';
							echo '<td class="text-right">'.numberFormat($val["MoneyIn"]).'</td>';
							echo '<td class="text-right">';
								if(!empty($val["Balance"]))
								{
									echo '&pound; '.number_format($val["Balance"],2,'.',',');
								}
							echo '</td>';
							echo '<td class="text-right">'.'&pound; '.number_format($balance_check,2,'.',',').'</td>';
							echo '<td>'.categoryName($val["Category"]).'</td>';
						echo '</tr>';
					}
					?>
				</tbody>
			</table>
		</div>
<?php } ?>