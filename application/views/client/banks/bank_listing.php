<?php
	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
	$access = clientAccess();
	$total_out = 0;
	$total_in = 0;
	$display_button = (count($items) == 0)?0:1;


	$user = $this->session->userdata('user');
	$aaccess=$user['AccountantAccess'];
	$j_date = get_filed_year();
	/* Previous statement value */

	if($access)
	{
		$colspan = 13;
		$bottom_colspan = 8;
	}else{
		$colspan = 9;
		$bottom_colspan = 6;
	}
	if(count($items) <= 0)
	{
		echo '<tr>';
			echo '<td colspan="'.$colspan.'"><div class="alert alert-info text-center">'.$this->lang->line('BANK_STATEMENT_NO_RECORDS').'</div></td>';
		echo '</tr>';
	}else{
		$sn = ($this->uri->segment(2) == '')?1:$this->uri->segment(2)+1;
		$balance_check = 0;
		//pr($items);
		//print_r($items); die();
		foreach($items as $key=>$val)
		{
			//echo "<pre>"; print_r($val); echo "</pre>";
			$total_out += $val->MoneyOut;
			$total_in += $val->MoneyIn;
			$href = $this->encrypt->encode($val->ID);
			if($key == 0)
			{
				if($page == 0)
				{
					$balance_check = (float)$val->CheckBalance + (float)$val->MoneyIn - (float)$val->MoneyOut;
				}else{
					//echo 'Previous Check Balance : '.get_previous_check_balance($page);
					$temp = get_previous_check_balance($page);
					$balance_check = $temp + $val->MoneyIn - $val->MoneyOut;
				}
			}else{
				$balance_check = $balance_check + $val->MoneyIn - $val->MoneyOut;
			}

			if(!empty($val->Balance))
			{
				if(negativeNumber(number_format($val->Balance,2,'.','')) != negativeNumber(number_format($balance_check,2,'.','')))
				{
					if(!empty($aaccess)){
						$style = "class='bg-red'";
					}
				}else{
					$style = "";
				}
			}else{
				$style = "";
			}

			echo '<tr '.$style.'>';			
				if($access)
				{
					if(strtotime($val->TransactionDate) >= strtotime($j_date))
					{
						echo '<td><input type="checkbox" class="statement-check" name="cb[]" value="'.$href.'"/></td>';
					}else{
						echo '<td></td>';
					}
				}
				echo '<td>'.$sn.'</td>';$sn++;
				echo '<td>'.$val->bankName.'</td>';
				echo '<td>'.date('jS F Y',strtotime($val->TransactionDate)).'</td>';
				echo '<td>'.$val->Type.'</td>';
				echo '<td>'.$val->Description.'</td>';
				echo '<td class="text-right">'.numberFormat($val->MoneyOut).'</td>';
				echo '<td class="text-right">'.numberFormat($val->MoneyIn).'</td>';
				echo '<td class="text-right">';
					if(!empty($val->Balance))
					{
						echo '&pound; '.number_format($val->Balance,2,'.',',');
					}
				echo '</td>';
				if(!empty($aaccess)){
					echo '<td class="text-right">'.'&pound; '.number_format($balance_check,2,'.',',').'</td>';
				}
				if($access)
				{
					/*if(strtotime($val->TransactionDate) >= strtotime($j_date))
					{
						$catDropBox = exCategories('BANK',"Category[]",$val->Category,'class="form-control updatStatemntCategory sm-width-box" data-id="'.$href.'"');						
						echo '<td>'.$catDropBox.'</td>';
					}else{
						echo '<td>'.categoryName($val->Category).'</td>';
					}*/
					//echo '<td>'.categoryNameTrialCat($val->Category).'</td>';
					echo '<td style="width:17%;" class="exParentCattd">';
					$parentcat = getCategoryParentName($val->Category);
					echo '<select id="ExpenseCategory[]" class="form-control ExpenseCategory exParentCat" name="ExpenseParentCategory[]" disabled="disabled">';
					echo '<option selected="selected" value="0">'.$parentcat.'</option>';
					echo '</select>';
					echo '</td>';
					echo '<td class="exChildCattd sm-width-box">';
					echo '<select id="Category[]" class="form-control category sm-width-box tdtab" name="Category[]" disabled="disabled">';
					echo '<option selected="selected" value="0">'.getCategoryName($val->Category).'</option>';
					echo '</select>';
					echo '</td>';
				}
				echo '<td>';
				if($access)
				{ 
					if($val->StatementType == 'I' && $val->AssociatedWith == 0)
					{
						//echo '<a class="btn btn-primary btn-xs color createBankInvoice" href="'.$href.'">CREATE INVOICE</a>';
					}elseif($val->StatementType && $val->AssociatedWith == 0){
						//echo '<a class="btn btn-success btn-xs color createBankDividend" href="'.$href.'">CREATE DIVIDEND</a>';
					}else{
						echo itemNumber($val->AssociatedWith,$val->StatementType);
					}
				}else{
					echo itemNumber($val->AssociatedWith,$val->StatementType);
				}	

				$class = "class='editBankStatment btn-primary btn-xs color pointer'";
				//if($user['AccountantAccess'] != '' && itemNumber($val->AssociatedWith,$val->StatementType) == ""){
				if($val->AssociatedWith == 0){					
					if(strpos(categoryNameTrialCat($val->Category), 'Customer') !== false || strpos(categoryNameTrialCat($val->Category), 'Supplier') !== false || strpos(categoryNameTrialCat($val->Category), 'Shareholder') !== false || strpos(categoryNameTrialCat($val->Category), 'Employee') !== false){
						if(strpos(categoryNameTrialCat($val->Category), 'Customer') !== false){
							$category = "Customer";
						}
						if(strpos(categoryNameTrialCat($val->Category), 'Supplier') !== false){
							$category = "Supplier";
						}
						if(strpos(categoryNameTrialCat($val->Category), 'Shareholder') !== false){
							$category = "Shareholder";
						}
						if(strpos(categoryNameTrialCat($val->Category), 'Employee') !== false){
							$category = "Employee";
						}						
						echo '&nbsp;<a href="javascript:void(0);" data-id="'.$val->ID.'" data-catid="'.$val->Category.'" data-category="'.$category.'" class="linkBankStatmentBtn btn-primary btn-xs color pointer">Link</a>';					
						echo '&nbsp;<a href="javascript:void(0);" data-id="'.$val->ID.'" class="editBankStatment btn-primary btn-xs color pointer">Edit</a>';
					}else{
						echo '&nbsp;<a href="javascript:void(0);" data-id="'.$val->ID.'" class="editBankStatment btn-primary btn-xs color pointer">Edit</a>';
					}
				}
				echo '</td>';				
			echo '</tr>';
		}
		echo '<tr class="salary-table">';
		echo '<td> </td>';
			echo '<td colspan="'.$bottom_colspan.'">';
				echo '<span class="pull-right">Total Out</span>';
				echo '<br/><span class="pull-right">Total In</span>';
				echo '<br/><span class="pull-right">Current Balance</span>';
				echo '</td>';
			echo '<td colspan="" class="text-right">';
				echo numberFormat($total_out);
				echo '<br/>'.numberFormat($total_in);
			echo '<br/>'.numberFormat($current_balance);
				echo '</td>';
			echo '<td colspan="3">';
			echo '</td>';
		echo '</tr>';
	}
?>
