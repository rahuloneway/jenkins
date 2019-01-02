<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<table class="dashborad_table">
	<thead>
		<tr class="salary-table">
			<th>
				Client Name
			</th>
			<th>
				<a href="#" class="color">Company Name</a>
			</th>
			<th>
				<a href="#" class="color">Company Number</a>
			</th>
			<th>
				<a href="#" class="color">VAT Registration No.</a>
			</th>
			
			<!--  testing purpose >
			<th>
				<a href="#" class="color">Year</a>
			</th>
			<th>
				<a href="#" class="color">Quarter</a>
			</th>
			<  testing purpose -->
			
			<th>
				<a href="#" class="color">VAT Due</a>
			</th>
			<!-- th>
				<a href="#" class="color">Action</a>
			</th -->
		</tr>
	</thead>
	<tbody>
		<?php
			if(count($vatdue_items) == 0)
			{
				echo '<tr>';
					echo '<td>';
						echo $this->lang->line('ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND');
					echo '</td>';
				echo '</tr>';
			}else{
				foreach($vatdue_items as $key=>$val)
				{
					$val = (object) $val;
					// pr( $val );
					$due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->SECOND)));
					//$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
					
					$date_difference =  strtotime($due_date) - strtotime(date('Y-m-d'));
					//$date_difference = str_replace('-','',$date_difference);
					$date_difference = trim($date_difference)/(60*60*24);
					if($date_difference <= 30)
					{
						$bg_color = "class='bg-color-red'";
					}elseif($date_difference > 30 && $date_difference <= 60){
						$bg_color = "class='bg-color-amber'";
					}elseif($date_difference > 60){
						$bg_color = "class='bg-color-green'";
					}
					echo '<tr>';
					
						$id = $this->encrypt->encode($val->ID);
						echo '<td>';
							echo '<a href="'.site_url().'client_access/'.$id.'">'.$val->Name.'</a>';
						echo '</td>';
						echo '<td>';
							echo $val->CompanyName;
						echo '</td>';
						echo '<td>';
							echo $val->RegistrationNo;	
						echo '</td>';
						
						echo '<td>';
							echo $val->VATRegistrationNo;
						echo '</td>';
						
						/*
						echo '<td>';
							echo cDate($val->dEndDate);
						echo '</td>';

						// testing purpose
						echo '<td>';
							echo $val->year;
						echo '</td>';
						
						echo '<td>';
							echo $val->quarter;
						echo '</td>';
						// testing purpose */
						
						$end_date = cDate($val->SECOND);
						if(!empty($end_date))
						{
							echo '<td '.$bg_color.'>';
								echo ($end_date);
							echo '</td>';
							
						}else{
							echo '<td>';
								echo '';
							echo '</td>';
						}
						
						/*
						$filed="";
						$href=$this->encrypt->encode($val->CID);
						$ID=$this->encrypt->encode($val->ID);
						if(empty($filed))
						{
							echo '<td>';
								echo '<a href="'.$href.'" data-client="'.$ID.'" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_FILE_RETURN').'">';
									echo $this->lang->line('DASHBOARD_UNFILED_LABEL'); 
								echo '</a>';
							echo '</td>';
						}else{
							echo '<td>';
								echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_FILE_RETURNED').'" >';
									echo $this->lang->line('DASHBOARD_FILED_LABEL');
								echo '</span>';
							echo '</td>';
						}
						*/
						
					echo '</tr>';
				}
			}
		?>
	</tbody>
</table>

<?php /* ?>
<?php
if(count($vatdue_items) == 0)
{
	echo $this->lang->line('ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND');	
}else{
	$startMonth = true;
	$startDate = false;
	foreach($vatdue_items as $key=>$val)
	{
		$val = (object) $val;
		// pr( $val );
		$due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->SECOND)));
		//$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
		
		$date_difference =  strtotime($due_date) - strtotime(date('Y-m-d'));
		//$date_difference = str_replace('-','',$date_difference);
		$date_difference = trim($date_difference)/(60*60*24);
		if($date_difference <= 30)
		{
			$bg_color = "class='bg-color-red'";
		}elseif($date_difference > 30 && $date_difference <= 60){
			$bg_color = "class='bg-color-amber'";
		}elseif($date_difference > 60){
			$bg_color = "class='bg-color-green'";
		}
		
		if( !$startDate ){
			$startDate = strtotime($val->SECOND);
			$smDate = date('m', $startDate );
			$syDate = date('Y', $startDate );
		}
		
		if( $startMonth ){ ?>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			  <div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingOne">
				  <h4 class="panel-title">
					<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					  Collapsible Group Item #1
					</a>
				  </h4>
				</div>
				<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
				  <div class="panel-body">
					<table class="dashborad_table">
						<thead>
							<tr class="salary-table">
								<th>
									Client Name
								</th>
								<th>
									<a href="#" class="color">Company Name</a>
								</th>
								<th>
									<a href="#" class="color">Company Number</a>
								</th>
								<th>
									<a href="#" class="color">VAT Registration No.</a>
								</th>
								<th>
									<a href="#" class="color">VAT Due</a>
								</th>
							</tr>
						</thead>
						<tbody>
			<?php
				$startMonth = false;
			}
		
		
		
		if( ($smDate ==  date('m', strtotime($val->SECOND) ) && $syDate == date('Y', strtotime($val->SECOND) )) ){
			
		}else{ ?>
			
						</tbody>
					</table>
				  </div>
				</div>
			  </div>
			</div>
			
			<?php 	
			$startMonth = true;
			$startDate = false;
		}
	}
}
?>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Collapsible Group Item #1
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
      </div>
    </div>
  </div>
</div>



<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Collapsible Group Item #1
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Collapsible Group Item #2
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Collapsible Group Item #3
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
	  
      </div>
    </div>
  </div>
</div>

<?php */ ?>