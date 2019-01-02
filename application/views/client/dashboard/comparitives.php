<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	$config = settings();
	$filed_years = $statistics['filed_years'];
	$TBDDYears = TBDropDownYears();
	for( $i=$TBDDYears["end"];$i >= $TBDDYears["start"]; $i-- ){
		$arrYear = TBListYearsDD( $i );
		$arrYears[$arrYear["value"]] = $arrYear["title"];
		unset($arrYear);
	}
	$current_year_filed 	= 	array_search($arrYears[$acc_year]-1,$arrYears);
	$previous_year_filed 	= 	array_search($arrYears[$acc_year]-2,$arrYears);
	//pr($filed_years);//$search['AccountingYear']
	//prd($arrYears);//$search['AccountingYear']
	$current_filed_flag = 0;
	$previous_filed_flag = 0;
	foreach($filed_years as $key=>$val)
	{
		if($val['year'] == $current_year_filed)
		{
			$current_filed_flag = 1;
		}
		
		if($val['year'] == $previous_year_filed)
		{
			$previous_filed_flag = 1;
		}
	}
	
	
	//prd($statistics['filed_years']);
	//echo '<pre>';print_r($statistics);echo '</pre>'; die;
	
	/* Check if year is filed or not */
	
	/* PREVIOUS YEAR CALCULATIONS */
	$previous_expenses 	= 	$statistics['previous_year_corporation_taxes']['expense'] + $statistics['previous_year_corporation_taxes']['depreciation'];
	$previous_admin_cost 	= 	$previous_expenses + $statistics['previous_year_corporation_taxes']['entertainment'];
	
	$previous_operating_profit = $statistics['previous_year_corporation_taxes']['income'] - $previous_admin_cost;
	
	$previous_npbt = $previous_operating_profit - $statistics['previous_year_corporation_taxes']['assets'] + $statistics['previous_year_corporation_taxes']['depreciation']+$statistics['previous_year_corporation_taxes']['entertainment'];
	$previous_corporation_tax = ($config['Corporation_tax']/100)*$previous_npbt;
	
	$previous_npat = $previous_operating_profit - $previous_corporation_tax;
	
	if($current_filed_flag || $previous_filed_flag)
	{
		$previous_acc_profit = $previous_npat - $statistics['previous_year_profit_bf'];
	}else{
		$previous_acc_profit = $previous_npat;
	}
	
	$previous_dividend_avail = $previous_acc_profit - $statistics['comparitive_dividend']['previous_year'];

	/* CURRENT YEAR CALCULATIONS */
	$current_expenses = $statistics['current_year_corporation_taxes']['expense'] + $statistics['current_year_corporation_taxes']['depreciation'];
	$current_admin_cost 	= 	$current_expenses + $statistics['current_year_corporation_taxes']['entertainment'];
	
	$current_operating_profit = $statistics['current_year_corporation_taxes']['income'] - $current_admin_cost;
	
	$current_npbt = $current_operating_profit - $statistics['current_year_corporation_taxes']['assets'] + $statistics['current_year_corporation_taxes']['depreciation']+$statistics['current_year_corporation_taxes']['entertainment'];
	 
	$current_corporation_tax = ($config['Corporation_tax']/100)*$current_npbt;
	
	$current_npat = $current_operating_profit - $current_corporation_tax;
	
	if($current_filed_flag)
	{
		$current_acc_profit = $current_npat - $statistics['current_year_profit_bf'];
	}else{
		$current_acc_profit = $current_npat + ($previous_acc_profit- $statistics['comparitive_dividend']['previous_year']);
		//$current_acc_profit = $current_npat + ($previous_npat - $statistics['comparitive_dividend']['previous_year']);
	}
	$current_dividend_avail = ($current_acc_profit) - negativeNumber($statistics['comparitive_dividend']['current_year']);
	
	
	
	//$previous_dividend_avail = 
?>
<table class="dashbord_table">
	<thead class="light-grrey-bg table2">
		<tr>
			<th>
				<a href="#"><?php echo $this->lang->line('COMPARITIVES_COLUMN_LABEL_COMPARITIVES');?></a>
			</th>
			<th>
				<a href="#"><?php echo $this->lang->line('COMPARITIVES_COLUMN_LABEL_CURRENT_YEAR');?>
				<?php echo $statistics['comp_current_year'];?></a>
			</th>
			<th>
				<a href="#"><?php echo $this->lang->line('COMPARITIVES_COLUMN_LABEL_PREV_YEAR');?> <?php echo ($statistics['comp_current_year']-1);?></a>
				 <!--br/><b><?php echo ($statistics['comp_current_year']-1);?></b--> 
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-title="COMARATIVES">
				 <b class="text-bold-color"><?php echo $this->lang->line('COMPARITIVES_COLUMN_LABEL_SALES');?></b>
			</td>
			<td data-title="Current Years" class="text-right" style="color:#585656">
				<b><?php echo numberFormat($statistics['current_year_sale']);?></b>
			</td>
			<td data-title="Comparative Year" class="text-right" style="color:#585656">
				 <b><?php echo numberFormat($statistics['previous_year_sale']);?></b>
			</td>
		</tr>
		<tr>
			<td data-title="COMARATIVES">
				<?php echo $this->lang->line('COMPARITIVES_COLUMN_LABEL_ADMIN_COST');?>
			</td>
			<td data-title="Current Years" class="text-right">
				<?php 
					echo numberFormat($current_admin_cost);
				?>
			</td>
			<td data-title="Comparative Year" class="text-right">
				<?php 
					echo numberFormat($previous_admin_cost);
				?>
			</td>
		</tr>
		<tr>
			<td data-title="COMARATIVES">
				<?php echo $this->lang->line('COMPARITIVES_COLUMN_LABEL_OPE_PROFIT');?>
			</td>
			<td data-title="Current Years" class="text-right">
				 <?php
					//echo '&pound; '.number_format($current_nbpit,2,'.',',');
					//echo numberFormat($current_operating_profit);
					if($current_operating_profit < 0)
					{
					echo'<b>('.numberFormat($current_operating_profit).')</b>';
					}
					else{
					echo numberFormat($current_operating_profit);
					}
				 ?>
			</td>
			<td data-title="Comparative Year" class="text-right">
				  <?php
					//echo numberFormat($previous_operating_profit);
					//echo numberFormat($previous_nbpit);
					if($previous_operating_profit < 0)
					{
						echo'<b>('.numberFormat($previous_operating_profit).')</b>';
					}
					else{
					echo numberFormat($previous_operating_profit);
					}
				 ?>
			</td>
		</tr>
		<tr>
			<td data-title="COMARATIVES" style="color:#585656">
				<b><?php echo $this->lang->line('COMPARITIVES_COLUMN_LABEL_POT_CALCULATION');?></b>
			</td>
			<td data-title="Current Years" style="color:#585656" class="text-right">
				<?php 
					if($current_corporation_tax < 0)
					{
						echo '<b>('.numberFormat($current_corporation_tax).')</b>';
					}else{
						echo '<b>'.numberFormat($current_corporation_tax).'</b>';
					}
					
				?>
			</td>
			<td style="color:#585656" class="text-right">
				<?php
					if($previous_corporation_tax < 0)
					{
						echo '<b>('.numberFormat($previous_corporation_tax).')</b>';
					}else{
						echo '<b>'.numberFormat($previous_corporation_tax).'</b>';
					}
					
				?>
			</td>
		</tr>
		<tr>
			<td data-title="COMARATIVES">
				<?php echo $this->lang->line('COMPARITIVES_COLUMN_LABEL_PROFIT_AFTER_TAX');?>
			</td>
			<td data-title="Current Years" class="text-right">
				<?php
					if($current_npat < 0)
					{
						echo '('.numberFormat($current_npat).')';
					}else{
						echo numberFormat($current_npat);
					}
				?>
			</td>
			<td data-title="Comparative Year" class="text-right">
				<?php
					if($previous_npat < 0)
					{
						echo '('.numberFormat($previous_npat).')';
					}else{
						echo numberFormat($previous_npat);
					}
				?>
			</td>
		</tr>
		<tr class="light-grey-bg">
			<td data-title="COMARATIVES">
				<?php echo $this->lang->line('COMPARITIVES_COLUMN_LABEL_ACC_PROFIT');?>
			</td>
			<td data-title="Current Years" class="text-right">
				<?php
					if($current_acc_profit < 0)
					{
						echo '('.numberFormat($current_acc_profit).')';
					}else{
						echo numberFormat($current_acc_profit);
					}
				?>
			</td>
			<td data-title="Comparative Year" class="text-right">
				 <?php
					if($previous_acc_profit < 0)
					{
						echo '('.numberFormat($previous_acc_profit).')';
					}else{
						echo numberFormat($previous_acc_profit);
					}
				?>
			</td>
		</tr>
		<tr>
			<td style="color:#585656">
				<b><?php echo $this->lang->line('COMPARITIVES_COLUMN_LABEL_DIV_TAKEN');?></b>
			</td>
			<td style="color:#585656" class="text-right">
				<b><?php echo numberFormat($statistics['comparitive_dividend']['current_year']);?></b>
			</td>
			<td style="color:#585656" class="text-right">
				<b><?php echo numberFormat($statistics['comparitive_dividend']['previous_year']);?></b>
			</td>
		</tr>
		<tr>
			<td style="color:#585656">
				<b><?php echo $this->lang->line('COMPARITIVES_COLUMN_LABEL_DIV_AVAIL');?></b>
			</td>
			<td style="color:#585656" class="text-right">
				<?php
					if($current_dividend_avail < 0)
					{
						echo '<b>('.numberFormat($current_dividend_avail).')<b/>';
					}
					else
					{
						echo '<b>'.numberFormat($current_dividend_avail).'<b/>';
					}
				?>
				<input type="hidden" name="net_dividend" value="<?php echo $current_dividend_avail;?>" id="div_avail"/>
			</td>
			<td class="text-right" style="color:#585656">
				<?php
					if($previous_dividend_avail < 0)
					{
						echo '<b>('.numberFormat($previous_dividend_avail).')<b/>';
					}
					else
					{
						echo '<b>'.numberFormat($previous_dividend_avail).'<b/>';
					}
				?>
			</td>
		</tr>
	</tbody>
</table>