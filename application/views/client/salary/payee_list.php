<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php 
	$user = $this->session->userdata('user');
	$delete_access = accountant_role(clientAccess());
	if(isset($user['AccountantAccess']))
	{
		$access = 1;
	}else{
		$access = 0;
	}
	
	$j_date = get_filed_year();
	
	if(count($payee) <= 0)
	{
		echo '<tr class="norecs">';
			echo '<td colspan="11">';
				echo '<div class="alert alert-info text-center">';
					echo $this->lang->line('PAYEE_NO_RECORD_FOUND');
				echo '</div>';
			echo '</td>';
		echo '</tr>';
	}else{
		$sn = 1;
		foreach($payee as $key=>$val)
		{
			echo '<tr>';
				echo '<td>'.$sn.'</td>';$sn++;
				echo '<td>'.$quarters[$val->Quarter].'</td>';
				echo '<td class="text-right">'.numberFormat($val->IncomeTax).'</td>';
				echo '<td class="text-right">'.numberFormat($val->NIC_Employee).'</td>';
				echo '<td class="text-right">'.numberFormat($val->NIC_Employer).'</td>';
				echo '<td style="background-color: #DEDDDE;" class="text-right">'.numberFormat($val->Total).'</td>';
				echo '<td >'.$val->PayeeOfficeReference.'</td>';
				echo '<td class="text-right">'.numberFormat($val->HMRC_Refunds).'</td>';
				echo '<td>'.date('jS F Y',strtotime($val->StartDate)).' - '.date('jS F Y',strtotime($val->EndDate)).'</td>';
				if($access == 1)
				{
				echo '<td>';
					if($val->Status == 0)
					{
						$href = $this->encrypt->encode('ACTION_PAID/'.$val->ID);
						$tooltip = 'data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_PAYEE_PAID').'"';
						echo '&nbsp;<a href= "'.$href.'" rel = "'.$val->ID.'" class="btn btn-primary btn-xs color paidPayee" '.$tooltip.'>';
							echo 'UNPAID';
						echo '</a>';
						$href = $this->encrypt->encode('ACTION_DELETE/'.$val->ID);
						$tooltip = 'data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_PAYEE_DELETE').'"';
						echo '<a href= "'.$href.'"class=" color deletePayee" '.$tooltip.'>';
							echo '<i class="fa fa-times"></i>';
						echo '</a>';
					}elseif($val->Status == 1){
						echo '<span class="btn btn-danger btn-xs color" id="'.$val->ID.'">PAID</span>';
						if($delete_access)
						{
							if(strtotime($val->PaidDate) > strtotime($j_date))
							{
								$href = $this->encrypt->encode('ACTION_DELETE/'.$val->ID);
								$tooltip = 'data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_PAYEE_DELETE').'"';
								echo '<a href= "'.$href.'"class=" color deletePayee" '.$tooltip.'>';
									echo '<i class="fa fa-times"></i>';
								echo '</a>';
							}
						}
					}
					
				echo '</td>';
				}else{
					echo '<td>';
						if($val->Status == 0)
						{
							echo '<span class="btn btn-primary btn-xs color">UNPAID</span>';
						}else{
							echo '<span class="btn btn-success btn-xs color">PAID</span>';
							
						}
					echo '</td>';
				}
				echo '<td class="paid_datiy" id="'.$val->ID.'">'.cDate($val->PaidDate).'</td>';
			echo '</tr>';
			echo '<input type="hidden" name="payeeEdit[]" value="'.$this->encrypt->encode($val->ID).'"/>';
			
		}
		
	}
	
?>