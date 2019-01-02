<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('accountant/header',array('page'=>$page,'title'=>$title));?>
<?php
	$user = $this->session->userdata('user');
	//echo '<pre>';print_r($this->session->userdata);echo '</pre>';
?>
<?php
	$search = $this->session->userdata('accountant_search');
	if($search == '')
	{
		$search = array(
			'Name' 			=> '',
			'Email' 	=> '',
			'Status' 		=> ''
		);
	}
	
	$asc_order_value = array(
		'SORT_BY_NAME'		=>	'CONCAT(u.FirstName," ",u.LastName) ASC',
		'SORT_BY_CONTACTNO'	=>	'u.ContactNo ASC',
		'SORT_BY_DATE'		=>	'c.EndDate ASC',
		'SORT_BY_STATUS'	=>	'u.Status ASC'
	);
	$order = $this->session->userdata('accountant_sorting');
?>
<section class="grey-body">
	<div class="container-fluid ">
		<div class="account_sum">
			<h4>Accountants</h4>
			<?php echo $this->session->flashdata('accountantsError');?>
			<?php echo form_open(site_url().'accountant/accountants/search');?>
			<div class="panel panel-default panel_custom">
				<div class="panel-body row">
					<div class="col-md-3 col-sm-3 col-xs-12 small_sel padding_field">
						<div class="wid-40">
							<label>Name:</label>
						</div>
						<div class="wid-60">
							<input type="text" class="form-control"name="Name" id="Name"placeholder="Name" value="<?php echo $search['Name'];?>"/>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12 small_sel padding_field">
						<div class="wid-40">
							<label>Email:</label>
						</div>
						<div class="wid-60">
							<input type="text" class="form-control"id="Email"name="Email"placeholder="Email"value="<?php echo $search['Email'];?>"/>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12  padding_field">
						<div class="wid-40">
							<label>Status:</label>
						</div>
						<div class="wid-60">
							<?php
								$options = array(
									'' 	=> 'Select Status',
									'1'	=>	'ENABLED',
									'2'	=>'DISABLED'
								);
								echo genericList('Status',$options,$search['Status'],'Status');
							?>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12 text-right padding_field btn_float pull-right"> 
						<button type="submit" class="btn  btn_search">
							<span class="glyphicon glyphicon-search"></span>Search
						</button>
						<a href="#" type="button" class="btn  btn_search reset">
							<span class="glyphicon glyphicon-refresh"></span>Reset
						</a>
					</div>
				</div>
			</div>
			<?php echo form_close();?>
			<div class="panel panel-default panel_custom">
				<div class="panel-body row ">
					<div class="col-md-2  col-sm-4 col-xs-12 btn_centre"> 
						<a href="<?php echo site_url().'add_accountant'?>" type="button" class="btn btn-inverse">
							<span class="glyphicon glyphicon-plus"> </span> 
							Add Accountant
						</a> 
					</div>
					<div class="col-md-10 col-xs-12 ac-pagination">
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table>
					<thead>
						<tr class="salary-table">
							<th>#</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_NAME');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_NAME');?>" class="sort color">
									Full Name
									<?php
										getSortDirection($order,'SORT_BY_NAME',$asc_order_value);
									?>
								</a>
							</th>
							<th>
								Email
							</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_CONTACTNO');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_CONTACTNO');?>" class="sort color">
									Contact No
									<?php
										getSortDirection($order,'SORT_BY_CONTACTNO',$asc_order_value);
									?>
								</a>
							</th>
							<th>
								Employee Level
							</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_STATUS');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_STATUS');?>" class="sort">
									Status
									<?php
										getSortDirection($order,'SORT_BY_STATUS',$asc_order_value);
									?>
								</a>
							</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody id="accountant-listing">
						<?php $this->load->view('accountant/accountants/accountant_listing');?>
					</tbody>
				</table>
			</div>
			<div class="panel panel-default panel_custom">
				<div class="panel-body row ">
					<div class="col-md-2  col-sm-4 col-xs-12 btn_centre"> 
						<a href="<?php echo site_url().'add_accountant'?>" type="button" class="btn btn-inverse">
							<span class="glyphicon glyphicon-plus"> </span> 
							Add Accountant
						</a> 
					</div>
					<div class="col-md-10 col-xs-12 ac-pagination">
					
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="modal fade modal-accountant" id="modal-accountant"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE');?></span>
				</button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<div class="modal-body"></div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>



<?php $this->load->view('accountant/footer');?>