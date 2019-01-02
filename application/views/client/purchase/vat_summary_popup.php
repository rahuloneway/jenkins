<script>
$(document).ready(function(){
	var dvat_sales_outputs = $('#vat_sales_outputs').val();		
		var dvat_due_ac_ec = $('#vat_due_ac_ec').val();
		if(dvat_sales_outputs != "" || dvat_due_ac_ec != ""){
			var addtion_sales_due_ac = parseFloat(dvat_sales_outputs) + parseFloat(dvat_due_ac_ec);
			var adds = addtion_sales_due_ac.toFixed(2);			
		} 
		$('#total_val_due_period_ec_mem').val(adds);	
		var vat_reclaimed = $('#vat_reclaimed').val();		
		var fifth_variable = adds - parseFloat(vat_reclaimed);
		$('#net_vat_reclaimed').val(fifth_variable.toFixed(2));
});
</script>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

$user = $this->session->userdata('user');
//echo "<pre>"; print_r($user); echo "</pre>";
$q = $this->input->post("quarter");
$quarter_id = $this->encrypt->decode($q);
$PaidVatQuarters = $this->clients->VatSummary_getPaidVatQuarters();
$x = 1;
if ($quarterDetails) {
	foreach ($quarterDetails as $qDetails) {
		if ($qDetails->InvoiceTotal < 0) {
			if ($qDetails->SubTotal >= 0)
				$qDetails->SubTotal = $qDetails->SubTotal * -1;
			if ($qDetails->Tax >= 0)
				$qDetails->Tax = $qDetails->Tax * -1;
		}
		?>
	
			
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
				?>


		<?php
		$total_net_value += $qDetails->SubTotal;
		$total_inv_vat += $qDetails->Tax;
		$total_inv_amount += $qDetails->InvoiceTotal;
		$total_inv_due_vat = $total_inv_due_vat + $flateRate;		
		
	 $x++;
	}
	?>	

<?php } ?>
<?php
if($user['AcType'] == 'TYPE_CLI'){
	$readonly = 'readonly';		
}
else if ($PaidVatQuarters[$quarter_id]->accountant_submit == 1) { 
			$readonly = 'readonly';	
			$class = 'acc_';	
		}
else{
	$readonly = '';	
}
?>
<div class="container-fluid ">
    <div class="table-responsive inv_info_n">		
	<form name="vat_dymanic_data" method="post">
		<table class="table table-striped">	
			<thead class="tbody_bg">
				<tr>
					<th><?php echo 'S.No.'; ?></th>
					<th><?php echo 'Description '; ?></th>
					<th><?php echo 'HMRC Values'; ?></th>					
				</tr>
			</thead>
			<tbody>
			 <tr>
				<td>
				1
				</td>
				<td>
					VAT Due in this period on sales and other outputs
				</td>
				<td>					
					<input type="text" name="vat_sales_outputs" id="vat_sales_outputs" class="vatcnb validNumber vat_sales_outputs gess" value="<?php echo round($total_inv_due_vat,2); ?>" <?php echo $readonly; ?> />					
				</td>
			</tr>
			<tr>
				<td>
				2
				</td>
				<td>
				VAT Due in this period on acquisitions from other EC Member States			
				</td>
				<td>					
					<input type="text" name="vat_due_ac_ec" id ="vat_due_ac_ec" class="vatcnb validNumber vat_due_ac_ec gess" value="0" <?php echo $readonly; ?> />					
				</td>
			</tr>
			<tr>
				<td>
				3
				</td>
				<td>
				Total VAT due
				</td>
				<td>					
					<input type="text" name="total_val_due_period_ec_mem" id="total_val_due_period_ec_mem" class="validNumber total_val_due_period_ec_mem gess" value="" readonly />				
				</td>
			</tr>
			<tr>
				<td>
				4
				</td>
				<td>
				VAT recliamed in this period on purchases and other inputs (including acquisitions from the EC)
				</td>
				<td>					
					<input type="text" name="vat_reclaimed" id="vat_reclaimed" class="vatcnb validNumber vat_reclaimed gess" value="0" <?php echo $readonly; ?>/>				
				</td>
			</tr>	
			<tr>
				<td>
				5
				</td>
				<td>
				Net VAT to be paid to Customers or recliamed by you
				</td>
				<td>					
					<input type="text" name="net_vat_reclaimed" id="net_vat_reclaimed" class="vatcnb validNumber net_vat_reclaimed gess" value="" readonly />			
				</td>
			</tr>		
			<tr>
				<td>
				6
				</td>
				<td>
				Total value of sales and  all other outputs excluding any VAT.
				</td>
				<td>					
					<input type="text" name="total_val_sales_ex_vat" id="total_val_sales_ex_vat" class="vatcnb validNumber total_val_sales_ex_vat gess" value="<?php echo round($total_net_value,2); ?>" <?php echo $readonly; ?>/>
				</td>
			</tr>
			<tr>
				<td>
				7
				</td>
				<td>
				Total value of purchases and all other inputs exluding any VAT.
				</td>
				<td>					
					<input type="text" name="total_value_purchase_ex_vat" id="total_value_purchase_ex_vat" class="vatcnb validNumber total_value_purchase_ex_vat gess" value="0" <?php echo $readonly; ?>/>
				</td>
			</tr>	
			<tr>
				<td>
				8
				</td>
				<td>
				Total value of all supplies of goods and related costs, excluding any VAT, to other EC Member States
				</td>
				<td>					
					<input type="text" name="total_val_supp_ex_vat" class="vatcnb validNumber total_val_supp_ex_vat gess" id="total_val_supp_ex_vat" value="0" <?php echo $readonly; ?>/></div>
				</td>
			</tr>	
			<tr>
				<td>
				9
				</td>
				<td>
				Total value of acquisitions of goods and related costs excluding any VAT, to other EC Member States
				</td>
				<td>					
					<input type="text" name="total_val_ex_vat_gds" class="vatcnb validNumber ex_vat_gds gess" value="0" id="ex_vat_gds" <?php echo $readonly; ?>/>
				</td>
			</tr>
			<tr>	
			<td colspan="3">
				<?php 	
				if(!$user['AcType']) { ?>				
				<input type="hidden" name="quarter_val" value="<?php echo $q; ?>" class="quarter_val_sm"/>
				<!--div class="vat_due">
					<div class="output_vat"-->
					<?php if ($PaidVatQuarters[$quarter_id]->accountant_submit == 1) { ?>
                            <span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('TOOLTIP_VAT_ALREADY_PAID'); ?>" >
                            <?php echo $this->lang->line('CLIENT_INVOICE_VAT_PAID_LABEL'); ?>
                            </span>										
							<?php
							} else{ ?>				
							<input type="button" name="submit_all_cal" class="submit_all_cal btn btn-info btn-xs color" value="NOT SUBMITTED"/>
					<?php } ?>
					<!--/div>
				</div-->
				<?php } 
				?>
			</td>
			</tr>
			</tbody>			
		</table>
		</form>
		</div>
</div>