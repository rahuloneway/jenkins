<?php $this->load->view('client/header',array('page'=>'dashboard','title'=>$title));?>
<?php

	$user = $this->session->userdata('user');
	$config = settings();
	$TBYears = getTBYear();

	$TBYear = $TBYears[0]["value"];
	$TBPrevYear = $TBYears[1]["value"];
	$s_data = $this->session->userdata('search_accounting_year');
	if(empty($s_data))
	{
		$search['AccountingYear'] = $TBYear;
	}else{
		$search['AccountingYear'] = $s_data;
	}

	$chart_year = explode('/',$search['AccountingYear']);
	$chart_year = $chart_year[1];
	$financial_date = company_year($search['AccountingYear']);
	$financial_date = $financial_date['end_date'];
?>

<section class="database_btn">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-3 col-sm-4  col-xs-12 text-center">
				<a href="#invoice" class="btn btn-inverse open_form " id="invoice">
					<i class="glyphicon glyphicon-plus"></i>
					<?php echo $this->lang->line('CLIENT_BUTTON_ADD_INVOICE');?>
				</a>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 text-center">
				<a href="#expense" class="btn btn-inverse open_form" id="expense">
					<i class="glyphicon glyphicon-plus"></i>
					<?php echo $this->lang->line('CLIENT_BUTTON_ADD_EXPENSE');?>
				</a>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 text-center">
				<a href="#uploadexpense" class="btn btn-inverse open_form" id="uploadexpense">
					<i class="glyphicon glyphicon-plus"></i>
					<?php echo $this->lang->line('CLIENT_BUTTON_UPLOAD_EXPENSE');?>
				</a>
			</div>
			<!--div  class="col-md-3 col-sm-6  col-xs-12 padding_btn" >
				<a href="#banks" class="btn btn-inverse open_form" id="banks">
					<i class="glyphicon glyphicon-plus"></i>
					<?php //echo $this->lang->line('CLIENT_BUTTON_ADD_STATEMENT');?>
				</a>
			</div-->
			<div class="col-md-3 col-sm-6 col-xs-12 text-center">
				<a href="#dividend" class="btn btn-inverse open_form" id="newDividend">
					<i class="glyphicon glyphicon-plus"></i>
					<?php echo $this->lang->line('CLIENT_BUTTON_ADD_VOUCHER');?>
				</a>
			</div>
		</div>
	</div>
</section>
<section>
<div class="gradient-grey">
	<div class="container-fluid padding-bottom">
		<div class="col-md-7 col-md-sm-12">
			<h4>
				<?php
					echo companyName($user['CompanyID']);
				?>
				<a href="#"> Management Dashboard </a>
					<span>As at Date <?php echo cDate($statistics['balance_date']);?></span>
			</h4>
		</div>
		<div class="col-md-5  col-sm-12 col-xs-12  invoice_field dashbord-filed">
			<div class="wid-60">
				<label>
					Company Accounting Year Ending <?php echo date('jS M',strtotime($financial_date));?>
				</label>
			</div>
			<div class="wid-20">
				<?php
					echo form_open(site_url().'client/get_accounting_year_data',array('name'=>'accounting_year_data','id'=>'accounting_year_data'));
					$TBDDYears = TBDropDownYears();
					for( $i=$TBDDYears["end"];$i >= $TBDDYears["start"]; $i-- ){
						$arrYear = TBListYearsDD( $i );
						$arrYears[$arrYear["value"]] = $arrYear["title"];
						unset($arrYear);
					}
					echo genericList("AccountingYear", $arrYears, $search['AccountingYear'] , "AccountingYear");
					echo form_close();
				?>
			</div>
			<div class="clr"></div>
		</div>
		<div class="clr"></div>
	</div>
</div>
	<div class="dark-grey">
	<div class="container-fluid">
	<div class="row">
	<div class="wid-30 padding-me" >

			<div id="revenueChart" class="thumbnail">
				<b>Invoices for <?php echo $chart_year;?></b>
				<canvas id="myChart" width="380" height="325"></canvas>
				<div id="invoice_legendDiv"></div>
			</div>

		<div class="div-chart-space"  class="thumbnail">
			<div id="revenueChart">
				<b>Expenses for <?php echo $chart_year;?></b>
				<canvas id="chart_expenses" width="380" height="353"></canvas>
				<div id="expense_legendDiv"></div>
			</div>
		</div>

	</div>
	<div class="wid-40">
		<div class="border-box thumbnail">
			<?php $this->load->view('client/dashboard/comparitives',array('acc_year'=>$search['AccountingYear']));?>
		</div>
		<div class="border-box div-space thumbnail">
			<table class="dashbord_table">
				<thead class="light-grrey-bg">
					<tr class="folow_me">
						<th>
							<a href="#">Shareholder Information </a>


						</th>


						<th colspan="2">
							<?php
								$share_holders = (isset($share_holders) && !empty($share_holders)) ? $share_holders : array("0" => "No Shareholders");
								echo form_dropdown('DashboardShareHoldersDetail',$share_holders,'','class="form-control pull-left" id="DashboardShareHoldersDetail"');
							?>



						<?php
								for($y = APP_START_YEAR; $y<=date("Y")+1; $y++){
									$label = ($y);
									$value = ($y-1)." / ".($y-1);
									$shYears[$value] = 'April '.$label;
								}

								(date('m') > 3) ?  ( (date('m') == 4) ? (date('d') > 5 ? $check = 0 : $check = 1) : $check = 0 )  : $check = 1 ;

								if($check)
								{
									$SHselected = (date("Y")-1)." / ".(date("Y")-1);
								}
								else
								{
									$SHselected = date("Y")." / ".(date("Y"));
								}

								echo form_dropdown("SHyear", $shYears, $SHselected,' id="SHyear" class="pull-right"');
								$d['financial_year'] =  $SHselected;
							?>

						</th>

					</tr>
				</thead>
				<tbody class="shareholders">
					<?php $this->load->view('client/dashboard/shareholder_detail',$d);?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="wid-30 padding-me">
		<div class="border-box_blue">
			<div class="grey-td-head">
				 Balances Section
			</div>
			<div class="arrow"></div>
			<table class="dashbord_table-blue ">
				<thead>
					<tr>
						<th colspan="2">
							<a href="#">As at Date: &nbsp;<?php echo cDate($statistics['balance_date']);?></a>
						</th>
						<th>
							<a href="#"></a>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td data-title="Date ">
							 Current Account Balance
						</td>
						<td data-title="Current Years" class="text-right">
							<?php echo '&pound;&nbsp;'.number_format($balances['CurrentAmount'],2,'.',',');?>
						</td>
					</tr>
					<tr>
						<td data-title="Date ">
							 Saving Account Balance
						</td>
						<td data-title="Current Years" class="text-right">
							<?php echo '&pound;&nbsp;'.number_format($balances['SavingAmount'],2,'.',',');?>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="grey-td-head">
				 Important Dates
			</div>
			<div class="arrow_white" style="top:1px;position:relative;z-index:4;">
			</div>
			<div class="border-box ">
				<?php $this->load->view('client/dashboard/important_dates');?>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>
</div>
</section>
<div class="modal fade modal-dashboard" id="modal-dashboard"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE');?></span>
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('BUTTON_NEW');?></h4>
			</div>
			<div class="modal-body"></div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo site_url()?>assets/js/chart.min.js"></script>
<?php
	$this->load->view('client/dashboard/charts_js');
?>
<div id="dialog" style="display:none;"></div>
<?php $this->load->view('client/footer');?>
