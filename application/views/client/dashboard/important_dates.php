<div class="tbody">
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
		 <div class="carousel-inner" role="listbox">
			 
			 <?php
					if(count($important_dates) == 0)
					{
						echo '<ul>';
							echo '<li colspan="3" style="padding: 10px">No Data Available</li>';
						echo '</ul>';
					}else{
						$counter = 1;
						$class = 'active';
						echo '<div class="item '.$class.'">';
						end($important_dates);
						$last = key($important_dates);
						echo '<ul><li>';
						echo '<table class="table">';
						echo '<tr class="thead">
								<th>
									<a href="#">'.$this->lang->line('IMPORTANT_DATES_COLUMN_LABEL_DATE').'</a></th>
									<th><a href="#">'.$this->lang->line('IMPORTANT_DATES_COLUMN_LABEL_EVENT_TYPE').'</a></th>
									<th><a href="#">'.$this->lang->line('IMPORTANT_DATES_COLUMN_LABEL_DAYS_TO_GO').'</a></th>
								</tr>';
						foreach($important_dates as $key=>$val)
						{
							$date = cDate($val['Date']);
							if(!empty($date))
							{
								echo '<tr>';
								echo '<td>';
									echo cDate($val['Date']);
								echo '</td>';
								echo '<td>';
									echo $val['Event'].'<br/><br/>';
								echo '</td>';
								if($val['DaysLeft'] > 0)
								{
									$style = "style='color: green;'";
								}else{
									$style = "style='color: red;'";
								}
								echo '<td '.$style.'>';
									//echo negativeNumber($val['DaysLeft']);
									echo str_replace(array('-','+'),array('',''),$val['DaysLeft']);
								echo '</td>';
								echo '</tr>';
								if(($counter%11) == 0 && $last != $key)
								{
									echo '</table>';
									echo '</li></ul>';
									echo '</div>';
									echo '<div class="item">';
									echo '<ul><li>';
									echo '<table class="table">';
									echo '<tr class="thead">
											<th>
												<a href="#">'.$this->lang->line('IMPORTANT_DATES_COLUMN_LABEL_DATE').'</a></th>
												<th><a href="#">'.$this->lang->line('IMPORTANT_DATES_COLUMN_LABEL_EVENT_TYPE').'</a></th>
												<th><a href="#">'.$this->lang->line('IMPORTANT_DATES_COLUMN_LABEL_DAYS_TO_GO').'</a></th>
											</tr>';
									$counter = 0;
								}
								$counter++;
							}
						}
						echo '</table>';
									echo '</li></ul>';
						echo '</div>';
					}
				?>
				
			</div>
			<div class="clr"></div>
			 <div class=" col-md-4 pull-right text-right arrow_disp">
				<?php
					if(count($important_dates) > 12):
				?>
				<ul>
					<li>
						<a class="left " href="#myCarousel" role="button" data-slide="prev">
							<i class="fa fa-angle-left"></i>
						</a>
					</li>
					<li>
						<a class="right " href="#myCarousel" role="button" data-slide="next">
							<i class="fa fa-angle-right"></i>
						</a>
					</li>
				</ul>
				<?php
					endif;
				?>
			</div>
		</div>
</div>
