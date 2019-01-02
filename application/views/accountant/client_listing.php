<?php 
	//echo '<pre>';print_r($items);echo '</pre>';die;
	if(count($items) <= 0)
	{
		echo '<tr>';
			echo '<td colspan="14">';
				echo '<div class="alert alert-info text-center">';
					echo $this->lang->line('ACCOUNTANT_NO_CLIENT_RECORD');
				echo '</div>';
			echo '</td>';
		echo '</tr>';
	}else{
		$sn = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		foreach($items as $key=>$val)
		{
			//echo '<pre>'; print_r($val); echo '</pre>';
			//$link = $this->encrypt->encode($val->ID);
			//$link = $this->encrypt->encode($val->ID);
			$link = $val->ID;
			//echo '<pre>'; print_r($link); echo '</pre>';
			echo '<tr>';
				echo '<td>'.($sn+1).'</td>';
				$id = $this->encrypt->encode($val->ID);
				$cid = $this->encrypt->encode($val->CID);				
				echo '<td>';
				echo '<a href="'.site_url().'client_access/'.$id.'/'.$cid.'">'.$val->CompanyName.'</a>';	
				echo '</td>';
				echo '<td>'.$val->Name.'</td>';			
				echo '<td>'.$val->ContactNo.'</td>';
				echo '<td>'.$val->Email.'</td>';
				
				echo '<td>';
					$rel_acc = accountant_list();
					
					$relation_with = $val->Relation_with;
					if($val->Relation_with != 0)
					{
						if(array_key_exists($relation_with,$rel_acc))
						{
							echo $rel_acc[$relation_with];
						}
					}
				echo '</td>';
				echo '<td>'.cDate($val->EndDate).'</td>';
				
				echo '<td>';
					if($val->Status == 1)
					{
						echo '<span class = "changeClientstatus btn btn-success btn-xs pointer" id=' . $link . ' style="width:85px;" title="Click to enabled the Client" data-toggle="tooltip">ENABLED</span>';
					}else{
						echo '<span class="changeClientstatus btn btn-danger btn-xs pointer" id=' . $link . ' style="width:85px;" title="Click to disable the Client" data-toggle="tooltip">DISABLED</span>';
					}
				echo '</td>';
				echo '<td style="width:7%">';
					if($val->State == 1)
					{
						echo '<a href ="'.site_url().'resend_email/'.$id.'"class="btn btn-danger btn-xs color resendEmail">Resend Email</a>';
					}else{
						echo '<a href ="'.site_url().'resend_email/'.$id.'"class="btn btn-success btn-xs color resendEmail">Send Email</a>';
					}
				echo '</td>';
				echo '<td style="width:8%">';
					$tooltip = 'data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_EDIT_CLIENT').'"';
					$tooltip1 = 'data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_EDIT_CLIENT_PRIVILEGES').'"';
					echo '<a href="'.site_url().'update_client/'.$id.'/'.$cid.'" class="btn btn-primary btn-xs color editClient" '.$tooltip.'>';
						echo '<i class="glyphicon glyphicon-pencil"></i>';
					echo '<a/>';
					echo '<a data-val="'.$id.'" style="margin-left:8px;" href="javascript:;" class="btn btn-primary btn-xs color editAccess" '.$tooltip1.'>';
						echo '<i class="glyphicon glyphicon-eye-close"></i>';
					echo '<a/>';
				echo '</td>';
			echo '</tr>';
			$sn++;
		} ?>
		<div class="modal fade modal-choose-company" id="modal-choose-company" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
				<div class="modal-dialog modal-mm" style="">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close client-access-close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
							<h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('CHOOSE_COMPANYLOGIN'); ?></h4>
						</div>
						<div class="modal-body123">	
								<div class="row">
									 <div class="col-sm-11 col-md-11 login_with-us">
										<?php echo form_open('',array('name'=>'chooseCompanyform','id'=>'chooseCompanyform')); ?>
										<select name="company" id="chooseCompanySelect" class="chooseCompany form-control"></select>
										<br/>
										<?php echo form_close();?>
									</div>
								</div>
						</div>
						<div class="modal-footer">
						</div>
					</div>
				</div>
			</div>
			<style>
			.modal-body123{
				margin:10px;
			}
			</style>
<?php	}
?>