<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	$this->load->view('client/header',array('page'=>'dividends','title'=>$title));
	$search = $this->session->userdata('DividendSearch');
	if($search == '')
	{
		$search = array(
			'SharerName'	=>	'',
			'dStartDate'	=>	'',
			'dEndDate'		=>	'',
			'NetAmount'		=>	'',
			'GrossAmount'	=>	'',
			'VoucherNumber'	=>	''
		);
	}
	$asc_order_value = array(
		'SORT_BY_SHARERNAME'		=>	's.CONCAT(s.FirstName," ",s.LastName) ASC',
		'SORT_BY_DIVIDEND_VOUCHER'	=>	'd.VoucherNumber ASC',
		'SORT_BY_DATE'				=>	'd.DividendDate ASC',
		'SORT_BY_NET_AMOUNT'		=>	'd.NetAmount ASC',
		'SORT_BY_TAX_AMOUNT'		=>	'd.TaxAmount ASC',
		'SORT_BY_GROSS_AMOUNT'		=>	'd.GrossAmount ASC'
	);
	$order = $this->session->userdata('DividendSortingOrder');
	
	//echo '<pre>';print_r($user);echo '</pre>';
?>
<section class="grey-body">
	<div class="container-fluid ">
		<div class="account_sum">
			<h4><?php echo $this->lang->line('DIVIDEND_PAGE_TITLE');?></h4>
			<?php echo $this->session->flashdata('dividendMessage');?>
			<div class="panel panel-default panel_custom">
				<?php echo form_open(site_url().'clients/dividend/search',array('name'=>'dividendSearch','id'=>'dividendSearch'));?>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-4 col-sm-3 col-xs-12 padding_field">
							<div class="wid-50">
								<label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_SHARE_NAME');?></label>
							</div>
							<div class="wid-50">
								<?php 
									echo form_dropdown('SharerName',$share_holders,$search['SharerName'],'id="SharerName". class="input_100percent"');
								?>
							</div>
						</div>
						<div class="col-md-4 col-sm-12 col-xs-12 invoice_field">
							<div class="wid-40">
								<label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DIV_DATE');?></label>
							</div>
							<div class="wid-60 date_input41">
								<input type="text" style="float:left;" class="form-control dDatepicker" placeholder="Start"name="dStartDate" id="dStartDate"value="<?php echo $search['dStartDate'];?>" >
								<span class="mid-lbl" style="float:left; padding:6px 9px;">-to-</span>
								<input type="text" style="float:left;" class="form-control dDatepicker" placeholder="End"name="dEndDate" id="dEndDate" value="<?php echo $search['dEndDate'];?>">
								<br/><br/>
							</div>
						</div>
						<div class="col-md-4 col-sm-3 col-xs-12 padding_field">
							<div class="wid-40">
								<label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_NET_AMOUNT');?></label>
							</div>
							<div class="wid-60">
								<input type="text" name="NetAmount"id="NetAmount"class="form-control validNumber" placeholder="net amount" value="<?php echo $search['NetAmount'];?>">
							</div>
						</div>
					</div>
					<div class="row" style="margin-top:10px;">
						<div class="col-md-4 col-sm-3 col-xs-12 padding_field">
							<div class="wid-50">
								<label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DIV_NUMBER');?></label>
							</div>
							<div class="wid-50">
								<input type="text" class="form-control" name="VoucherNumber" id="VoucherNumber"placeholder="voucher number" value="<?php echo $search['VoucherNumber'];?>"/>
							</div>
						</div>
						<div class="col-md-4 col-sm-3 col-xs-12 padding_field">
							<div class="wid-40">
								<label><?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_GROSS_DIV');?></label>
							</div>
							<div class="wid-60">
								<input type="text" class="form-control validNumber" name="GrossAmount" placeholder="gross amount" id="GrossAmount"value="<?php echo $search['GrossAmount'];?>"/>
							</div>
						</div>
						<div class="col-md-4 col-sm-3 col-xs-12 text-right padding_field btn_float pull-right"> 
							<button type="submit" class="btn  btn_search btn-search">
								<i class="glyphicon glyphicon-search"></i><?php echo $this->lang->line('BUTTON_SEARCH');?>
							</button> 
							<a class="btn  btn_search reset" href="#">
								<span class="glyphicon glyphicon-refresh"></span><?php echo $this->lang->line('BUTTON_RESET');?>
							</a>
						</div>
					</div>
				</div>
				<?php echo form_close();?>
			</div>
			<div class="panel panel-default panel_custom">
				<div class="panel-body row ">
					<div class="col-md-2  col-sm-4 col-xs-12 btn_centre">
						<a href="#" type="button" class="btn btn-inverse openDividendForm">
							<span class="glyphicon glyphicon-plus"></span>
							<?php echo $this->lang->line('CLIENT_BUTTON_ADD_VOUCHER');?>
						</a> 
					</div>
					<div class="col-md-10 col-xs-12 dPagination">
						<?php echo $pagination;?>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table>
					<thead>
						<tr class="salary-table">
							<th>#</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_DIVIDEND_VOUCHER');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_DIVIDEND_VOUCHER');?>" class="sort color">
									<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DIV_NUMBER');?>
									<?php
										getSortDirection($order,'SORT_BY_DIVIDEND_VOUCHER',$asc_order_value);
									?>
								</a>
							</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_DATE');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_DIVIDEND_DATE');?>" class="sort color">
									<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DATE');?>
									<?php
										getSortDirection($order,'SORT_BY_DATE',$asc_order_value);
									?>
								</a>
							</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_SHARERNAME');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_SHARERNAME');?>" class="sort color">
									<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_SHARE_NAME');?>
									<?php
										getSortDirection($order,'SORT_BY_SHARERNAME',$asc_order_value);
									?>
								</a>
							</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_NET_AMOUNT');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_NET_AMOUNT');?>" class="sort color">
									<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_NET_AMOUNT');?>
								</a>
								<?php
									getSortDirection($order,'SORT_BY_NET_AMOUNT',$asc_order_value);
								?>
							</th>
							<th>
								<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DIV_PER_SHARE');?>
							</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_TAX_AMOUNT');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_TAX_AMOUNT');?>" class="sort color">
									<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_TAX_CREDIT');?>
								</a>
								<?php
									getSortDirection($order,'SORT_BY_TAX_AMOUNT',$asc_order_value);
								?>
							</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_GROSS_AMOUNT');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_GROSS_AMOUNT');?>" class="sort color">
									<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_GROSS_DIV');?>
									<?php
										getSortDirection($order,'SORT_BY_GROSS_AMOUNT',$asc_order_value);
									?>
								</a>
							</th>
							<th>
								<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_DIV_STATUS');?>
							</th>
							<th>
								<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_PAID_DATE');?>
							</th>
							<th>
								<?php echo $this->lang->line('DIVIDEND_PAGE_LABEL_ACTION');?>
							</th>
						</tr>
					</thead>
					<tbody id="dividend-listing">
						<?php $this->load->view('client/dividend/dividend_listing',$items);?>
					</tbody>
				</table>
			</div>
			<div class="panel panel-default panel_custom">
				<div class="panel-body row ">
					<div class="col-md-2  col-sm-4 col-xs-12 btn_centre"> 
						<a href="#" type="button" class="btn btn-inverse openDividendForm">
							<span class="glyphicon glyphicon-plus"></span>
							<?php echo $this->lang->line('CLIENT_BUTTON_ADD_VOUCHER');?>
						</a> 
					</div>
					<div class="col-md-10 dPagination">
						<?php echo $pagination;?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="modal fade modal-dividend" id="modal-dividend"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE');?></span>
				</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('CLIENT_INVOICE_NEW_EXPENSE');?></h4>
			</div>
			<div class="modal-body"></div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<div id="dialog"></div>	
<?php $this->load->view('client/footer');?>