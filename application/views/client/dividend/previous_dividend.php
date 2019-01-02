<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="table-responsive">
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>
					<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DIV_NUMBER');?>
				</th>
				<th>
					<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DATE');?>
				</th>
				<th>
					<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_SHARE_NAME');?>
				</th>
				<th>
					<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_NET_AMOUNT');?>
				</th>
				<th>
					<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_GROSS_DIV');?>
				</th>
			</tr>
		</thead>
		<tbody id="dividend-listing">
			<?php
				//echo '<pre>';print_r($items);echo '</pre>';
				$sn = 1;
				if(count($prev_dividends) <=0)
				{
					echo '<tr>';
						echo '<td colspan="11">';
							echo '<div class="alert alert-info text-center">';
								echo $this->lang->line('CLIENT_NO_RECORD_FOUND');
							echo '</div>';
						echo '</td>';
					echo '</tr>';
				}else{
					foreach($prev_dividends as $key=>$val)
					{
						echo '<tr>';
							echo '<td>';
								echo $sn;
							echo '</td>';
							echo '<td>';
								echo $val->VoucherNumber;
							echo '</td>';
							echo '<td>';
								echo cDate($val->DividendDate);
							echo '</td>';
							echo '<td>';
								echo $val->ShareholderName;
							echo '</td>';
							echo '<td>';
								echo '&pound; '.number_format($val->NetAmount,2,'.',',');
							echo '</td>';
							echo '<td>';
								echo '&pound; '.number_format($val->GrossAmount,2,'.',',');
							echo '</td>';
						echo '</tr>';
						$sn++;
					}
				}
				?>
		</tbody>
	</table>
</div>
<div class="modal-footer">
	<div class="pull-right col-md-6">
		<a data-dismiss="modal" class="btn btn-danger btn-sm spacer" href="#">
			<i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CLOSE');?>
		</a>
	</div>
</div>