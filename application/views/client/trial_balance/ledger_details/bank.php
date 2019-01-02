<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	//echo "<pre>"; print_r($details ); echo "</pre>";	
	
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
							###
						</th>
						<th width="13%">
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_SELECTBANK');?>
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
						<th>
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_MAIN_CATEGORY');?>
						</th>
						<th width="100">
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_CATEGORY');?>
						</th>
						<th width="10%">
							<?php echo $this->lang->line('BANK_TABLE_COLUMN_ACTIONS'); ?>
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
							echo '<td>'.$val["bankName"].'</td>';
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
							//echo '<td>'.getCategoryName($val["Category"]).'</td>';
							echo '<td style="width:17%;" class="exParentCattd">';
							$parentcat = getCategoryParentName($val["Category"]);
							echo '<select id="ExpenseCategory[]" class="form-control ExpenseCategory exParentCat" name="ExpenseParentCategory[]" disabled="disabled">';
							echo '<option selected="selected" value="0">'.$parentcat.'</option>';
							echo '</select>';
							echo '</td>';
							echo '<td class="exChildCattd sm-width-box">';
							echo '<select id="Category[]" class="form-control category sm-width-box tdtab" name="Category[]" disabled="disabled">';
							echo '<option selected="selected" value="0">'.getCategoryName($val["Category"]).'</option>';
							echo '</select>';
							echo '</td>';
							echo '<td>';								
								echo '&nbsp;<a href="javascript:void(0);" data-id="'.$val["ID"].'" class="editBankStatment btn-primary btn-xs color pointer">Edit</a>';
							echo '</td>';
						echo '</tr>';
					}
					?>
				</tbody>
			</table>
		</div>
<?php } ?>

<script>
	$(document).on('click', '.editBankStatment', function (e) { 		
		//$(this).parent('td').prev('td').prev('td').children().prop('disabled', false); 
		var id = $(this).data('id');		
		var td = $(this).parent('td').prev('td').prev('td');
		$(this).parent('td').prev('td').children().prop('disabled', false);
		$(this).parent('td').html('<a href="javascript:void(0)" class="saveBankStatment btn-primary btn-xs color pointer" data-id="'+id+'" >Save</a>');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url(); ?>getParentCategory",
			data: {'CatType':'BANK'},
			beforeSend: function () {                
			},
			success: function (msg) {
				$(td).html(msg);
			}
		});
	});
	
	$(document).on('change', '.exParentCat', function (e) { 
		var parentid = $(this).val();  
		var td 		 = $(this).parent().next('td'); 
		
		$.ajax({
			type: "POST",
			url: "<?php echo site_url(); ?>getParentCategoryChild",
			data: {'parentid': parentid},
			beforeSend: function () {                
			},
			success: function (msg) { 
				$(td).html(msg);
			}
		});
	});
	
	$(document).on('click', '.saveBankStatment', function (e) { 
		e.preventDefault();
		var id = $(this).data('id');		
		var parent = $(this).parent('td').prev('td').prev('td').children().find(":selected").val(); 
		var child = $(this).parent('td').prev('td').children().find(":selected").val(); 
		var td = $(this).parent('td');		
		if(parent == '-1' || child == '0' || child == '-1'){
			var title = 'Message';
			var text = '<?php echo $this->lang->line('SELECT_CATEGORY_ERROR');?>';
			dialogBox(title,text);
			return false;
		}else{
			$(this).parent('td').prev('td').prev('td').children().prop('disabled', true); 
			$(this).parent('td').prev('td').children().prop('disabled', true);
		}
		$.ajax({
			type: "POST",
			url: "<?php echo site_url(); ?>saveeditdbankstatment",
			data: {'id': id, 'catId' : child, 'parentId' : parent},
			beforeSend: function () {   	
			},
			success: function (msg) { 					
				//initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                //location.reload(true);
				$(td).html('&nbsp;<a href="javascript:void(0)" class="editBankStatment btn-primary btn-xs color pointer" data-id="'+id+'" >Edit</a>');
			}
		});
	});
</script>
