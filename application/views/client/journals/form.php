<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	$TBYears 	= 	getTBYear();
	$TBYear 	= 	$TBYears[0]["value"];
	$TBPrevYear = 	$TBYears[1]["value"];
	$type = array(
		'DB'	=> 'DB',
		'CR'	=> 'CR'
	);
?>
<?php //echo "<pre>"; print_r($item); echo "</pre>";?>
<section>
	<?php echo form_open(site_url().'clients/journals/save',array('id'=>'journalEntry'));?>
	<div class="container-fluid ">
		<div class="panel panel-default panel_custom ">
			<div class="panel-body row">
				<div class="col-md-3 col-sm-6 col-xs-12 padding_field">
					<label><?php echo $this->lang->line('JOURNAL_LABEL_ACCOUNTING_YEAR');?></label>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 padding_field">
					<?php
						$filed_year = check_filed_account();
						if(empty($filed_year))
						{
							$TBDDYears = TBDropDownYears();
							for( $i=$TBDDYears["end"];$i >= $TBDDYears["start"]; $i-- )
							{
								$arrYear = TBListYearsDD( $i );
								$arrYears[$arrYear["value"]] = $arrYear["title"];
								unset($arrYear);
							}
							//echo "<pre>"; print_r($arrYears); echo "</pre>";
							if(!isset($item['FinancialYear'])){
							echo genericList("journal_financialyear", $arrYears, $TBYear , "journal_financialyear");
							}else{
								echo genericList("journal_financialyear", $arrYears, $item['FinancialYear'] , "journal_financialyear");	
							}
						}
						else
						{
							$filed_year = explode("/",$filed_year[0]["year"]);
							
							$TBDDYears = TBDropDownYears();
							
							for( $i=$TBDDYears["end"];$i >= $TBDDYears["start"]; $i-- )
							{
								$arrYear = TBListYearsDD( $i );
								if($arrYear["title"] > $filed_year[1])
								{
									$arrYears[$arrYear["value"]] = $arrYear["title"];
									unset($arrYear);
								}
							}
							//echo genericList("journal_financialyear", $arrYears, $TBYear , "journal_financialyear");
							if(!$item['FinancialYear']){
							echo genericList("journal_financialyear", $arrYears, $TBYear , "journal_financialyear");
							}else{
								echo genericList("journal_financialyear", $arrYears, $item['FinancialYear'], "journal_financialyear");	
							}
						}
					?>
				</div>
			</div> 
		</div>
		<div class="clr"> </div>
		<div class="tabl_journal">
			<div class="clr"></div>
			<table class="tbl-editable">
				<thead class="tbody_bg">
					<tr>
						<th><?php echo $this->lang->line('JOURNAL_COLUMN_ITEM');?></th>
						<th><?php echo $this->lang->line('JOURNAL_COLUMN_TYPE');?></th>
						<!--th><?php echo $this->lang->line('JOURNAL_COLUMN_CATEGORY');?></th-->
						<th><?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_MAIN_CATEGORY'); ?></th>
                        <th><?php echo $this->lang->line('EXPENSE_TABLE_COLUMN_CATEGORY'); ?> </th>
						<th><?php echo $this->lang->line('JOURNAL_COLUMN_NARRATION');?></th>
						<th><?php echo $this->lang->line('JOURNAL_COLUMN_AMOUNT');?></th>
						<th class="del" >
							<?php echo $this->lang->line('TABLE_COLUMN_ACTION');?>
						</th>
					</tr>
				</thead>
				<tbody id="jurnal-items">
					<?php 
					if( !isset($item['JournalType'])){  ?>
					<tr>
						<td class="sno">1.</td>
						<td width="10%">
							<?php echo form_dropdown('Type[]',$type,'','class="form-control journal_type"');?>
						</td>
						<td width="30%">
						<?php //echo form_dropdown('JournalCategories[]',pl_categories(),'','class="form-control JournalCategories wid-60"'); ?>
						<?php $parentcat = getCategoryParent('BANK'); ?>    
							<select class="form-control JournalCategories wid-60 jnParentCat" name="JournalCategories[]">
								<option selected="selected" value="0">--Select Category--</option>
								<?php foreach($parentcat as $val){ ?>             
									<option value="<?php echo $val->id;?>"><?php  echo $val->title;?></option>
								<?php } ?>
							</select>
						</td>
						<td width="30%" class="jnChildCatTd">
							<select id="JournalChildCategory[]" class="form-control" name="JournalChildCategory[]">
								<option selected="selected" value="0">--Select Category--</option>
							</select>
						</td>
						<td>
							<input type="text" name="J_Narration[]" value=" " class="form-control input-type s-inputs j_Narration"/>
						</td>
						<td data-title="Amount">
							<input type="text"name="J_Amount[]"class="form-control input-type s-input validNumber j_amount"/>
						</td>
						<td>
							<a href="#" class="btn remove-item hide">
								<i class="fa fa-times"></i>
							</a>
						</td>
					</tr>
					<?php } else { ?>
					<?php foreach($item['JournalType'] as $key=>$val){?>
					<tr>
						<td class="sno">
							<?php echo ($key+1);?>
						</td>
						<td>
							<?php echo form_dropdown('Type[]',$type,$val->JournalType,'class="form-control journal_type wow"');?>
						</td>
						<td width="30%">
						<?php //echo form_dropdown('JournalCategories[]',pl_categories(),'','class="form-control JournalCategories wid-60"'); ?>
						<?php $parentcat = getCategoryParent('BANK'); ?>    
							<select class="form-control JournalCategories wid-60 jnParentCat" name="JournalCategories[]">
								<option selected="selected" value="0">--Select Category--</option>
								<?php foreach($parentcat as $val){ ?>             
									<option value="<?php echo $val->id;?>"><?php  echo $val->title;?></option>
								<?php } ?>
							</select>
						</td>
						<td width="30%" class="jnChildCatTd">
							<select id="JournalChildCategory[]" class="form-control" name="JournalChildCategory[]">
								<option selected="selected" value="0">--Select Category--</option>
							</select>
						</td>
						<td data-title="Narration">
							<input type="text" name="J_Narration[]" value="<?php echo $val->Narration;?>" class="form-control input-type s-inputs j_Narration"/>
						</td>
						<td data-title="Amount">
							<input type="text"name="J_Amount[]" value="<?php echo $val->Amount;?>" class="form-control input-type s-input validNumber j_amount"/>
						</td>
						<td>
							<a href="#" class="btn remove-item hide">
								<i class="fa fa-times"></i>
							</a>
						</td>
					</tr>
					<?php 
					} } ?>
				</tbody>
				<tfoot>
					<tr>
						
						<td class="well text-right" colspan="3">
							<a class="btn btn-inverse add-entry pull-left" type="button" href="#">
								<span class="glyphicon glyphicon-plus"></span> 
								<?php echo $this->lang->line('BUTTON_ADD_ITEM');?>
							</a>
						</td>
						<td class="well text-right">
							Total Debit Amount
							<br/>
							Total Credit Amount
						</td>
						<td class="well text-left" colspan="3">
							<span class="total_debit_amount"></span>
							
							<br/>
							<span class="total_credit_amount"></span>
						</td>
					</tr>
				</tfoot>
			</table>
			<div class="clr"></div>
		</div>
		<div class="clr"></div><br/>
		<div class="col-md-12">
			<div class="pull-right">
				<a type="button" class="btn btn-success btn-sm save-entry">
					<i class="glyphicon glyphicon-floppy-disk"></i>
					<?php echo $this->lang->line('BUTTON_CREATE_AND_FINISH');?>
				</a>
				<a type="button" class="btn btn-sm btn-danger"data-dismiss="modal">
					<i class="fa fa-close"></i><?php echo $this->lang->line('BUTTON_CANCEL');?>
				</a>
			</div>
		</div>
	</div>
</section>