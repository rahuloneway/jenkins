<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	//echo '<pre>';print_r($quarter_range);echo '</pre>';die;
	if(is_array($quarter_range))
	{
		//$totalRecord = count($quarter_range);
		
		/* Collect already added quarters in single array */
		$existed_quarters = array();
		foreach($quarter_range as $key=>$val)
		{
			$existed_quarters[] = $val->Quarter;
		}
		//echo '<pre>';print_r($existed_quarters);echo '</pre>';
		$final_quarter = array();
		foreach($quarters as $key=>$val)
		{
			if(!in_array($key,$existed_quarters))
			{
				$final_quarter[] =	array(
					'value'	=>	$val,
					'key'	=>	$key,
				);
			}
		}
	}else{
		//$totalRecord = $quarter_range;
		$final_quarter = array();
		foreach($quarters as $key=>$val)
		{
			$final_quarter[] =	array(
				'value'	=>	$val,
				'key'	=>	$key,
			);
		}
	}
	//echo '<pre>';print_r($final_quarter);echo '</pre>';
	$quarters = $final_quarter;
	//echo '<pre>';print_r($quarters);echo '</pre>';
	unset($quarters[0]);
	//echo '<pre>';print_r($quarters);echo '</pre>';
	$counter = count($quarters);
	$prev_year = $start_year;
	$start_date = array(
		'1'	=>	'05-07-'.$prev_year,
		'2'	=>	'05-10-'.$prev_year,
		'3'	=>	'05-01-'.($prev_year+1),
		'4'	=>	'05-04-'.($prev_year+1)
	);
	
	$end_date = array(
		'1'	=>	'19-07-'.$prev_year,
		'2'	=>	'19-10-'.$prev_year,
		'3'	=>	'19-01-'.($prev_year+1),
		'4'	=>	'19-04-'.($prev_year+1)
	);
	//echo '<pre>';print_r($start_date);echo '</pre>';
	foreach($quarters as $x=>$val)
	{
?>
<tr>
	<td data-title="#">
		<?php echo $x;?>
	</td>
	<td data-title="Quarter">
		<?php echo $quarters[$x]['value'];?>
		<input type="hidden" name="quarters[]" value="<?php echo $quarters[$x]['key'];?>"/>
	</td>
	<td data-title="Income Tax ">
		<input type="text"class="form-control validNumber" name="IncomeTax[]" >
	</td>
	<td data-title="NIC Employee">
		<input type="text"class="form-control validNumber" name="NIC_Employee[]">
	</td>
	<td data-title="NIC Employee">
		<input type="text"class="form-control validNumber" name="NIC_Employer[]">
	</td>
	<td data-title="Total £">
		 <input type="text"class="form-control validNumber" name="Total[]">
	</td>
	<td data-title="PAYE Office Ref">
		<input type="text"class="form-control PayeReference" name="PayeOfficeReference[]" maxlength="17">
	</td>
	<td data-title="HMRC refunds">
		<input type="text"class="form-control validNumber" name="HMRC_Refunds[]">
	</td>
	<td data-title="Pay Between">
		<input type="text"placeholder="Start"class="form-control pDatepicker input-37" name="start_date[]" value="<?php echo trim($start_date[$quarters[$x]['key']]);?>" readonly />
		<span style="float:left; padding:4px 5px;" class="mid-lbl">-to-</span>
		<input type="text"placeholder="End" class="form-control pDatepicker input-37"name="end_date[]" value="<?php echo trim($end_date[$quarters[$x]['key']]);?>" readonly />
		<div class="clr"></div>
	</td>
</tr>
<?php
	}
	
	if($counter == 0)
	{
		echo '<tr>';
			echo '<td colspan="9">';
				echo '<div class="alert alert-info text-center">'.$this->lang->line('PAYEE_QUARTER_RECORD_ALREADY_ADDED').'</div>';
			echo '</td>';
		echo '</tr>';
	}
?>