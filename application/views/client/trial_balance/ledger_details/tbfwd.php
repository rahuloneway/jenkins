<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	if( !$details || empty($details) || count($details) <=0 ){
		echo "<div class='alert alert-danger text-center'><i class='glyphicon glyphicon-exclamation-sign'></i>&nbsp;";
			echo $this->lang->line("ERROR_LOADING_LEDGER_POPUP_DETAILS");
		echo "</div>";
	}else{ ?>
		<div class="table-responsive">
			<table>
				<thead>
					<tr class="salary-table">
						<th>#</th>
						<th>Financial year</th>
						<th>Date</th>
						<th class="text-right">Category</th>
						<th class="text-right">Amount</th>
					</tr>
				</thead>
				<tbody id="salary-listing">
				<?php 
						$x = 0;
						foreach( $details as $key=>$val ){ ?>
						<tr>
							<td data-title="#">
								<?php echo ++$x; ?>
							</td>
							<td data-title="financial year">
								<?php echo $val["year"]; ?>
							</td>
							<td data-title="Date">
								<?php echo cDate($val["addedOn"]); ?>
							</td>
							<td data-title="Category" class="text-right">
								<?php echo $val["catTitle"]; ?>
							</td>
							<td data-title="Amount" class="text-right">
								<?php echo numberFormat($val["amount"]); ?>
							</td>
						</tr>	
				<?php } ?>
			</table>
		</div>
<?php 
		/* prd( $details );
		echo "<div class='alert alert-info text-center'><i class='glyphicon glyphicon-exclamation-sign'></i>&nbsp;";
			echo "This part is still under development!";
		echo "</div>";
		*/

} ?>