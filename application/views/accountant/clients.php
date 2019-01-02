<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php $this->load->view('accountant/header',array('page'=>$page,'title'=>$title));?>
<?php
	$user = $this->session->userdata('user');
	//pr($user);
	
	$search = $this->session->userdata('accountantSearch');
	if($search == '')
	{
		$search = array(
			'Name' 			=> '',
			'Email' 		=> '',
			'EndDate' 		=> '',
			'CompanyName' 	=> '',
			'Status' 		=> '',
			'Relation_with' => 0
		);
	}
	
	$asc_order_value = array(
		'SORT_BY_NAME'		=>	'CONCAT(u.FirstName," ",u.LastName) ASC',
		'SORT_BY_CONTACTNO'	=>	'u.ContactNo ASC',
		'SORT_BY_DATE'		=>	'c.EndDate ASC',
		'SORT_BY_STATUS'	=>	'u.Status ASC'
	);
	$order = $this->session->userdata('accountantSortingOrder');
?>
<section class="grey-body">
	<div class="container-fluid">
		<div class="account_sum">
			<?php echo $this->session->flashdata('clientError');?>
			<h4>Clients</h4>
			<div class="panel panel-default panel_custom">
				<div class="panel-body row">
					<?php echo form_open(site_url().'accountant/accountant/search',array('id'=>'client-search'));?>
					<div class="col-md-4 col-sm-3 col-xs-12 small_sel padding_field">
						<div class="wid-40">
							<label>Name:</label>
						</div>
						<div class="wid-60">
							<input type="text" class="form-control" name="Name" id="Name" placeholder="name" value="<?php echo $search['Name'];?>">
						</div>
					</div>
					<div class="col-md-4 col-sm-3 col-xs-12 small_sel padding_field">
						<div class="wid-40">
							<label>Email:</label>
						</div>
						<div class="wid-60">
							<input type="text" class="form-control" name="Email" id="Email" placeholder="email" value="<?php echo $search['Email'];?>">
						</div>
					</div>
					<div class="col-md-4 col-sm-3 col-xs-12 small_sel padding_field">
						<div class="wid-40">
							<label>Year End Date:</label>
						</div>
						<div class="wid-60">
							<input type="text" class="form-control datepicker" name="YearEndDate" id="YearEndDate" placeholder="year end date"value="<?php echo cDate($search['EndDate']);?>"/>
						</div>
					</div>
					<div class="clr"></div>
					<div class="top_btm_spc">
						<div class="col-md-4 col-sm-3 col-xs-12 small_sel padding_field">
							<div class="wid-40">
								<label>Company Name:</label>
							</div>
							<div class="wid-60">
								<input name="CompanyName" id="CompanyName"type="text" class="form-control" id="exampleInputEmail1" placeholder="company name" value="<?php echo $search['CompanyName'];?>">
							</div>
						</div>
					</div>
					<div class="top_btm_spc">
						<div class="col-md-4 col-sm-3 col-xs-12  padding_field">
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
					</div>
					<div class="top_btm_spc">
						<div class="col-md-4 col-sm-3 col-xs-12  padding_field">
							<div class="wid-40">
								<label>Rel. Manager</label>
							</div>
							<div class="wid-60">
								<?php
									$options = accountant_list();
									echo genericList('Relation_with',$options,$search['Relation_with'],'Relation_with');
								?>
							</div>
						</div>
					</div>
					<div class="clr"></div>
					<br/>
					<div class="col-md-3 col-sm-3 col-xs-12 text-right padding_field btn_float pull-right"> 
						<button type="submit" class="btn  btn_search btn-search">
							<span class="glyphicon glyphicon-search"></span>Search
						</button> 
						<a href="#" type="button" class="btn  btn_search reset">
							<span class="glyphicon glyphicon-refresh"></span>Reset
						</a>
					</div>
					<?php echo form_close();?>
				</div>
			</div>
			<div class="panel panel-default panel_custom">
				<div class="panel-body row ">
					<div class="col-md-4 col-sm-4 col-xs-12 btn_centre"> 
						<a href="<?php echo site_url();?>add_client" type="button" class="btn btn-inverse">
						<span class="glyphicon glyphicon-plus"> </span> Add Client
						</a> 
						<a href="#" class="btn btn-inverse upload_bulk_client">
							<i class="fa fa-upload"></i>
							<?php echo $this->lang->line('CLIENT_UPLOAD_BUTTON'); ?>
                        </a>
					</div>
					<div class="col-md-10 col-xs-12 cPagination">
						<?php echo $pagination;?>
					</div>
				</div>
			</div>
			<div class="table-responsive" id="reset-position">
				<table>
					<thead>
						<tr class="table-header">
							<th>#</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_NAME');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_NAME');?>" class="sort color">
									Company Name
								</a>
							</th>
							<th>
								
									Full Name
									<?php
										getSortDirection($order,'SORT_BY_NAME',$asc_order_value);
									?>
								</a>
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
								Email
							</th>							
							<th>
								Rel. Manager
							</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_DATE');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_DATE');?>" class="sort color">
									Year Date End
									<?php
										getSortDirection($order,'SORT_BY_DATE',$asc_order_value);
									?>
								</a>
							</th>
							<th>
								<a href="<?php echo $this->encrypt->encode('SORT_BY_STATUS');?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_STATUS');?>" class="sort color">
									Status
									<?php
										getSortDirection($order,'SORT_BY_STATUS',$asc_order_value);
									?>
								</a>
							</th>
							<th>
								Instruction Email
							</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody id="client-listing">
							<?php $this->load->view('accountant/client_listing',$items);?>
					</tbody>
				</table>
			</div>	
		
    <div class="panel panel-default panel_custom">
      <div class="panel-body row ">
        <div class="col-md-2  col-sm-4 col-xs-12 btn_centre"> 
			<a href="<?php echo site_url();?>add_client" type="button" class="btn btn-inverse">
				<span class="glyphicon glyphicon-plus"> </span> Add Client
			</a> 
		</div>
        <div class="col-md-10 cPagination">
          <?php echo $pagination;?>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal fade upload_more_clients" id="modal_bulk_clients" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php //echo $this->lang->line('CLIENT_INVOICE_NEW_EXPENSE'); ?></h4>
            </div>
            <div class="modal-body"></div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="modal fade modal-edit-access" id="modal-edit-access" tabindex="-1" role="modal" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('CLIENT_INVOICE_NEW_EXPENSE'); ?></h4>
            </div>
            <div class="modal-body"></div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="dialog"></div>
<?php $this->load->view('accountant/footer');?>