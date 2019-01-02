<?php 
	if(count($items) <= 0)
	{
		echo '<tr>';
			echo '<td colspan="13">';
				echo '<div class="alert alert-info text-center">';
					echo $this->lang->line('ACCOUNTANT_NO_CLIENT_RECORD');
				echo '</div>';
			echo '</td>';
		echo '</tr>';
	}else{
		$sn = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		foreach($items as $key=>$val)
		{
			$val->Params = unserialize($val->Params);
			$id = $this->encrypt->encode($val->ID);
			echo '<tr>';
				echo '<td>'.($sn+1).'</td>';
				echo '<td><a href="'.site_url().'update_accountant/'.$id.'">'.$val->Name.'</a></td>';
				
				echo '<td>'.$val->Email.'</td>';
				echo '<td>'.emptyNumber($val->ContactNo).'</td>';
				echo '<td>'.categoryName($val->Params['EmploymentLevel']).'</td>';
				echo '<td>';
					if($val->Status == 1)
					{
						echo '<span class="label label-success pointer">ENABLED</span>';
					}else{
						echo '<span class="label label-danger pointer">DISABLED</span>';
					}
				echo '</td>';
				echo '<td>';
					if($val->State == 1)
					{
						echo '<span class="label label-success btn-xs pointer">Email Sent</span>';
					}else{
						echo '<a href ="'.site_url().'accountant/accountants/resendEmail/'.$id.'"class="btn btn-danger btn-xs color resendEmail">Resend Email</a>';
					}
				echo '</td>';
			echo '</tr>';
			$sn++;
		}
	}
?>