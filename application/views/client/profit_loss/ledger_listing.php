<?php 
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	$TBYears = getTBYear();
	$TBYear = $TBYears[0]["value"];
?>

<!-- top Pagination div start -->
<?php if($pagination){ ?>
<div class="panel panel-default panel_custom">
	<div class="panel-body row pagintaion_body ">
		<div class="col-md-8 col-sm-8 col-xs-12 pull-right">
			<?php echo $pagination;?>
		</div>
	</div>
</div>
<?php } ?>
<!-- top Pagination div end -->


<!-- listing table start -->
<table class="table-ledger">
	<thead>
		<tr>
			<th class="wid-5">
				<a href="#" class="color"><?php echo $this->lang->line("TB_ROW_SRNO"); ?></a>
			</th>
			<th class="wid-5">
				<a href="#">Type</a>
			</th>
			<th class="wid-15">
				<a href="#">Category</a>
			</th>
			<th class="wid-15">
				<a href="#">Reference</a>
			</th>
			<th  class="wid-15">
				Transaction Date
			</th>
			<th class="wid-15">
				<a href="#"></a>
			</th>
			<th class="wid-10 text-center">
				<a href="#">Date</a>
			</th>
			<th class="wid-10 text-right">
				<a href="#">Amount</a>
			</th>
			<th class="wid-20">
				<a href="#">Assigned By</a>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php if( $items ){ 
			$PrAmount= 0;
			$count = 1;
			// prd($items);
			foreach($items as $item){ 
					
					$primary = $item["primary"];
					$PrAmount +=  $primary["trans_amount"];
					unset( $item["primary"] ); // remove primary details and prevent iteration in child loop
					
					$class ="";	
					if( $primary["trans_type"]=="CR" )
						$class .=' light_red  ';
					?>
					<tr data-toggle="collapse" data-target="#demo<?php echo $count; ?>" data-parent="#account-table" class="blue-accordian  collapsed <?php echo $class; ?> border-bottom">
						<td class="wid-5">
							<span class="glyphicon glyphicon-plus">
							</span>
							<span class="glyphicon glyphicon-minus">
							</span>
						</td>
						<td data-title="Type " class="wid-5 ">
							<?php echo  $primary["trans_type"]; ?>
						</td>
						<td class="wid-15 ">
							<?php echo $primary["source_name"]; ?>
						</td>
						<td class="wid-15 ">
							<?php echo $primary["details_url"]; ?>
						</td>
						<td class="wid-15">
							<?php 
								if($primary["source"] == 'BANK')
								{
									echo lDate(transaction_date($primary["itemId"]));
								}else{
									echo lDate($primary["addedOn"]);
								}
							?>
						</td>
						<td data-title="Type " class="wid-15 ">
							<?php echo $primary["type_name"]; ?>
						</td>
						<td class="wid-10 ">
							<?php echo lDate($primary["addedOn"]); ?>
						</td>
						<td class="wid-10 text-right">
							<?php echo numberFormatSigned($primary["trans_amount"]); ?>
						</td>
						<td data-title="Actioned By" class="wid-20" >
							<?php echo ucwords($primary["aAcess"]); ?>
						</td>
					</tr>
					<div class="clr"></div>
					<tr>
						<td colspan="9" class="no_padding">
							<table class="demo out collapse inner_table" id="demo<?php echo $count; ?>" border="1">
								<tbody>
									<?php 
									$totalItems=count($item);
									$countItems=1;
									foreach( $item as $childs){ 
										// pr( $childs );
										$class ="";
										
										if($countItems==$totalItems){
											$class .=' thick_border  ';	
										}
										
										// if negative amount show red
										if( $childs["trans_type"]=="CR" )
											$class .=' light_red ';	
										?>
										
										<tr class="border-bottom <?php echo  $class; ?>" >
											<td  class="wid-5">
												&nbsp;
											</td>
											<td data-title="Type "  class="wid-5 " >
												<?php echo  $childs["trans_type"]; ?>
											</td>
											<td data-title="Type "  class="wid-15 " >
												<?php echo $childs["source_name"]; ?>
											</td>
											<td data-title="Type "  class="wid-15 " >
												<!-- a <?php echo $childs["details_url"]; ?> --> 
													<?php echo $childs["source_name"]; ?>
												<!-- /a -->
											</td>
											<td class="wid-15">
												<?php 
													if($childs["source"] == 'BANK')
													{
														echo lDate(transaction_date($childs["itemId"]));
													}else{
														echo lDate($childs["addedOn"]);
													}
												?>
											</td>
											<td data-title="Type"  class="wid-15 " >
												<?php echo $childs["type_name"]; ?>
											</td>
											<td data-title="Type "  class="wid-10 " >
												<?php echo lDate($childs["addedOn"]); ?>
											</td>
											<td data-title="Type"  class="wid-10 text-right" >
												<?php echo numberFormatSigned($childs["trans_amount"]); ?>
											</td>
											<td data-title="Actioned By"  class="wid-20 ">
												<?php echo ucwords($childs["aAcess"]); ?>
											</td>
										</tr>
									<?php 
										$countItems++;
									} ?>
								</tbody>
							</table>
						</td>
					</tr>
					<div class="clr"></div>
			<?php 
				$count++;
			}?>
			<tr class="total_row border-top">
						<td colspan="6"></td>
						
						<td  class="text-right"><strong>Total</strong></td>
						<td data-title="2015" class="text-right">
							<strong><?php echo numberFormatSigned($PrAmount); ?></strong>
						</td>
						<td></td>
			</tr>

		<?php }else{ ?>
			<tr>
				<td colspan="9" >
					<div class="alert alert-info text-center">
						<?php echo $this->lang->line('NO_TB_RECORD_FOUND'); ?>
					</div>
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<!-- listing table end -->


<!-- bottom Pagination div start -->
<?php if($pagination){ ?>
<div class="panel panel-default panel_custom">
	<div class="panel-body row pagintaion_body ">
		<div class="col-md-8 col-sm-8 col-xs-12 pull-right">
			<?php echo $pagination;?>
		</div>
	</div>
</div>
<?php } ?>
<!-- bottom Pagination div end -->
			

			
		
	
