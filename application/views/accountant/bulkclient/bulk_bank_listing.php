<?php
	//echo "<pre>"; print_r($items); echo "</pre>"; die;
	$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
	//$access = clientAccess();
	//echo "<pre>"; print_r($access); echo "</pre>";
	$total_out = 0;
	$total_in = 0;
	$display_button = (count($items) == 0)?0:1;
	
	
	$user = $this->session->userdata('user');
	$j_date = get_filed_year();
	
	/* Previous statement value */
	//if($access)
	//{
		$colspan = 11;
		$bottom_colspan = 8;
	//}else{
		//$colspan = 9;
		//$bottom_colspan = 7;
	//}
	if(count($items) <= 0)
	{
		echo '<tr>';
			echo '<td colspan="'.$colspan.'"><div class="alert alert-info text-center">'.$this->lang->line('BANK_STATEMENT_NO_RECORDS').'</div></td>';
		echo '</tr>';
	}else{
		$sn = ($this->uri->segment(2) == '')?1:$this->uri->segment(2)+1;
		$balance_check = 0;
		//pr($items);
		foreach($items as $key=>$val)
		{
			$total_out += $val->MoneyOut;
			$total_in += $val->MoneyIn;
			$href = $this->encrypt->encode($val->ID);
			$descpt = $val->Description;
			
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
					$style = "class='bg-red'";
				}else{
					$style = "";
				}
			}else{
				$style = "";
			}
			
			echo '<tr '.$style.'>';
				//if($access)
				//{
					if(strtotime($val->TransactionDate) >= strtotime($j_date))
					{
						echo '<td><input type="checkbox" class="statement-check" name="cb[]" value="'.$href.'"/></td>';
					}else{
						echo '<td></td>';
					}
			//	}
				
				echo '<td>'.$sn.'</td>';$sn++;
				echo '<td>'.date('jS F Y',strtotime($val->TransactionDate)).'</td>';
				echo '<td>'.$val->Type.'</td>';
				echo '<td>'.excerpt_limit_words($descpt,9).'</td>';
				echo '<td class="text-right">'.numberFormat($val->MoneyOut).'</td>';
				echo '<td class="text-right">'.numberFormat($val->MoneyIn).'</td>';
				echo '<td class="text-right">';
					if(!empty($val->Balance))
					{
						echo '&pound; '.number_format($val->Balance,2,'.',',');
					}
				echo '</td>';
				echo '<td class="text-right">'.'&pound; '.number_format($balance_check,2,'.',',').'</td>';
				echo '<td>'.categoryName($val->Category).'</td>';
				// echo '<td>';
					// if($access)
					// {
						// if($val->StatementType == 'I' && $val->AssociatedWith == 0)
						// {
							// echo '<a class="btn btn-primary btn-xs color createBankInvoice" href="'.$href.'">CREATE INVOICE</a>';
						// }elseif($val->StatementType && $val->AssociatedWith == 0){
							// echo '<a class="btn btn-success btn-xs color createBankDividend" href="'.$href.'">CREATE DIVIDEND</a>';
						// }else{
							// echo itemNumber($val->AssociatedWith,$val->StatementType);
						// }
					// }else{
						// echo itemNumber($val->AssociatedWith,$val->StatementType);
					// }
				// echo '</td>';
			echo '</tr>';
		}
		echo '<tr class="salary-table">';
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
			echo '<td colspan="2">';
			echo '</td>';
		echo '</tr>';
	}
?>