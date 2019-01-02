<?php //echo '<pre>';print_r($item);echo '</pre>';?>
<?php if(count($item) <= 0 || count($item['ExpenseMileage']) <= 0):?>
<tr id="r1">
	<td class="sno">
		1
	</td>
	<td width="200px">
		<input type="text" class="form-control exDatepicker m-required input_70percent mileage-date" name="MileageDate[]"/>
	</td>
	<td>
		<input type="text" class="sm-width form-control m-required input_100percent" name="LocationFrom[]">
	</td>
	<td>
		<input type="text" class="sm-width form-control m-required input_100percent" name="LocationTo[]">
	</td>
	<td>
		<?php echo exCategories('VECH','ExpenseMileage[]',0,'class="form-control ExpenseMileage input_100percent"');?>		
	</td>
	<td>
		<input type="text" class="sm-width form-control m-required input_100percent" name="Purpose[]">
	</td>
	<td width="150px" class="milestab">
		<input type="text" class="sm-width validNumber TotalMiles form-control input_100percent" name="Miles[]">
	</td>
	<td width="50px">
		<a class='btn removeMileageItem hide'>
			<i class='fa fa-times'></i>
		</a>
	</td>
</tr>
<?php else:?>
<?php foreach($item['ExpenseMileage'] as $key=>$val):?>
<tr id="r1">
	<td class="sno">
		<?php echo $key+1;?>
	</td>
	<td width="200px">
		<input type="text" class="form-control exDatepicker m-required input_100percent mileage-date" name="MileageDate[]"value="<?php echo cDate($val->ItemDate);?>" />
		<input type="hidden" name="expense_mileage_id[]" value="<?php echo $this->encrypt->encode($val->ID);?>"/>
	</td>
	<td>
		<input type="text" class="sm-width form-control m-required input_100percent" name="LocationFrom[]" value="<?php echo $val->LocationFrom;?>"/>
	</td>
	<td>
		<input type="text" class="sm-width form-control m-required input_100percent" name="LocationTo[]" value="<?php echo $val->LocationTo;?>">
	</td>
	<td>
		<?php echo exCategories('VECH','ExpenseMileage[]',$val->Category,'class="form-control ExpenseMileage input_100percent"');?>
	</td>
	<td>
		<input type="text" class="sm-width form-control m-required input_100percent" name="Purpose[]" value="<?php echo $val->Purpose;?>">
	</td>
	<td width="50px" class="milestab">
		<input type="text" class="sm-width validNumber TotalMiles form-control wid-80" name="Miles[]" value="<?php echo $val->Miles;?>">
	</td>
	<td width="50px">
		<a class='btn removeMileageItem' id="<?php echo $val->ID ?>"><i class='fa fa-times'></i></a>
	</td>
</tr>
<?php endforeach;?>
<?php endif;?>