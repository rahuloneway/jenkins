<?php echo "--------------------"; if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	$sn =1;
	$row_one = 0;
	$row_two = 0;
	
	if( !$details || empty($details) || count($details) <=0 ){
		echo "<div class='alert alert-danger text-center'><i class='glyphicon glyphicon-exclamation-sign'></i>&nbsp;";
			echo $this->lang->line("ERROR_LOADING_LEDGER_POPUP_DETAILS");
		echo "</div>";
	}else{ ?>
		<table class="tbl-editable">
			<thead class="tbody_bg">
				<tr>
					<th><?php echo $this->lang->line('JOURNAL_COLUMN_ITEM');?></th>
					<th><?php echo $this->lang->line('JOURNAL_COLUMN_TYPE');?></th>
					<th><?php echo $this->lang->line('JOURNAL_COLUMN_CATEGORY');?></th>
					<th class="text-right"><?php echo $this->lang->line('JOURNAL_COLUMN_AMOUNT');?></th>
				</tr>
			</thead>
			<tbody id="journal-listing">
			<?php 
				$cr_total = 0;
				$db_total = 0;
				foreach($details as $key=>$val)
				{
					if($key % 2 == 0)
					{
						$class = "class='datacelltwo'";
					}
					
					if($val["JournalType"] == 'CR')
					{
						$cr_total += $val["Amount"];
					}else{
						$db_total += $val["Amount"];
					}
					
					echo '<tr '.$class.'>';
						echo '<td>';
							echo $sn;$sn++;
						echo '</td>';
						echo '<td>';
							echo $val["JournalType"];
						echo '</td>';
						echo '<td>';
							echo journal_cat_name($val["Category"]);
						echo '</td>';
						echo '<td class="text-right">';
							echo numberFormat($val["Amount"]);
						echo '</td>';
					echo '</tr>';
					if($row_one == 0)
					{
						$class = "class='datacellone'";
						$row_two = 0;
					}
				}
				echo '<tr class="datacelltwo">';
					echo '<td colspan="3"></td>';
					echo '<td class="text-right">';
						echo '<b>Total Credit :</b> &nbsp;&nbsp;'.numberFormat($cr_total);
						echo '<br/><b>Total debit Amount:</b> &nbsp;&nbsp;'.numberFormat($db_total);
					
						
					echo '</td>';
				echo '</tr>';
			?>
			</tbody>
		</table>
<?php } ?>