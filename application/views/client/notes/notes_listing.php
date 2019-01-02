<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	$delete_access = accountant_role(clientAccess());
	if(count($items) == 0)
	{
		echo '<tr>';
			echo '<td colspan="5">';
				echo '<div class="alert alert-info text-center">';
					echo $this->lang->line('ACCOUNTANT_NOTES_NO_RECORD_FOUND');
				echo '</div>';
			echo '</td>';
		echo '</tr>';
	}else{
		$s = 1;
		foreach($items as $key=>$val)
		{
			echo '<tr>';
				echo '<td>';
					echo $s++;
				echo '</td>';
				echo '<td>';
					echo $val->Description;
				echo '</td>';
				echo '<td>';
					echo getUserName($val->AddedBy);
				echo '</td>';
				echo '<td>';
					echo cDate($val->AddedOn);
				echo '</td>';
				if($delete_access)
				{
					echo '<td>';
						$href = $this->encrypt->encode($val->ID);
						$tooltip = 'data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_DELETE').'"';
						echo '<a href= "'.$href.'"class=" color delete-note" '.$tooltip.'>';
							echo '<i class="fa fa-times"></i>';
						echo '</a>';
					echo '</td>';
				}
			echo '</tr>';
		}
	}