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
						<th>Pay Date</th>
						<th>Gross Salary</th>
						<th>Income Tax</th>
						<th>NIC Employee</th>
						<th>NIC Employer</th>
						<th>SMP</th>
						<th>Net Pay</th>
						<th>Paid On</th>
					</tr>
				</thead>
				<tbody id="salary-listing">
				<?php 
						$x = 0;
						foreach( $details as $key=>$val ){ ?>
						<tr>
							<td data-title="#">
								<?php echo ++$x;?>
							</td>
							<td data-title="financial year">
								<?php echo $val["FinancialYear"];?>
							</td>
							<td data-title="Pay Date">
								<?php echo cDate($val["PayDate"]);?>
							</td>
							<td data-title="Gross Salary" class="text-right">
								<?php echo numberFormat($val["GrossSalary"]);?>
							</td>
							<td data-title="Income Tax" class="text-right">
								<?php echo numberFormat($val["IncomeTax"]);?>
							</td>
							<td data-title=" NIC Employee" class="text-right">
								<?php echo numberFormat($val["NIC_Employee"]);?>
							</td>
							<td data-title=" Employer NIC" class="text-right">
								<?php echo numberFormat($val["Employeer_NIC"]);?>
							</td>
							<td data-title=" SMP" class="text-right">
								<?php echo numberFormat($val["SMP"]);?>
							</td>
							<td data-title="Net Pay" class="text-right">
								<?php echo numberFormat($val["NetPay"]);?>
							</td>
							<td>
								<?php echo cDate($val["PaidDate"]);?>
							</td>
						</tr>	
				<?php } ?>
			</table>
		</div>
<?php } ?>