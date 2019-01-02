<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	$this->load->view('client/header',array('page'=>$page,'title'=>$title));
	$access = clientAccess();
	$delete_access = accountant_role($access);
?>
<section class="grey-body">
	<div class="container-fluid ">
		<div class="account_sum">
			<h4>
				<img src="<?php echo site_url();?>assets/images/notes.png"/>&nbsp;&nbsp;<?php echo $this->lang->line('NOTES_LABEL_TITLE');?>
			</h4>
			<hr/>
			<?php echo $this->session->flashdata('notesMessage');?>
			<div class="col-md-4">
			<?php echo form_open(site_url().'clients/notes/save',array('name'=>'notes','id'=>'notes'));?>
				<textarea class="form-control description" name="Description"></textarea>
				<br/>
				<a class="btn btn-primary pull-right save-note" href="#">
					<?php echo $this->lang->line('NOTES_BUTTON_ADD');?>
				</a>
			<?php echo form_close();?> 
			</div>
			<div class="col-md-8">
				<div class="table-responsive">
					<table>
						<thead>
							<tr class="salary-table">
								<th>
									#
								</th>
								<th>
									<?php echo $this->lang->line('NOTES_COLUMN_DESCRIPTION');?>
								</th>
								<th>
									<?php echo $this->lang->line('TABLE_COLUMN_ADDED_BY');?>
								</th>
								<th>
									<?php echo $this->lang->line('TABLE_COLUMN_ADDED_ON');?>
								</th>
								<?php if($delete_access):?>
								<th>
									<?php echo $this->lang->line('TABLE_COLUMN_ACTION');?>
								</th>
								<?php endif;?>
							</tr>
						</thead>
						<tbody id="notes-listing">
							<?php $this->load->view('client/notes/notes_listing');?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="clr"></div>
		</div>
	</div>
</section>
<div id="dialog"></div>	
<?php $this->load->view('client/footer');?>