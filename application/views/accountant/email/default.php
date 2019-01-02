<?php //echo "<prE>";print_r($_POST); echo "</pre>";
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$this->load->view('accountant/header', array(
    'page' => $page,
    'title' => $title
));
$Days_relation_with    		= $this->session->userdata('Days_relation_with');
$Quarters_relation_with 	= $this->session->userdata('Quarters_relation_with');
$Status                 	= $this->session->userdata('Status');
$filterby                   = $this->session->userdata('filterby');
$StartDate                  = $this->session->userdata('StartDate');

if (!empty($Days_relation_with) || !empty($Status)) {
    $Days_relation_with     = $this->session->userdata('Days_relation_with');
    $Quarters_relation_with = $this->session->userdata('Quarters_relation_with');
    $Status                 = $this->session->userdata('Status');
	$filterby               = $this->session->userdata('filterby');
	$StartDate              = $this->session->userdata('StartDate');
} else {
    $Days_relation_with     = @$_POST['Days_relation_with'];
    $Quarters_relation_with = @$_POST['Quarters_relation_with'];
    $Status                 = @$_POST['Status'];
	$filterby               = @$_POST['filterby'];
	$StartDate              = @$_POST['StartDate'];
}

if($Status == ''){
	$Status = "BANK_STATMENT_DUE";
}

?>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum border_box_trial">
            <h4><?php echo $this->lang->line('EMAIL_PAGE_TITLE');?></h4>
             <div class="clr"></div>
            <?php echo $this->session->flashdata('templateDocumentError');?>
			</br>
            <!--<ul class="nav nav-tabs">
				<li class="tab-bg <?php if($this->session->userdata('MailBankStatmentFrom') == ''){ echo "active"; } ?> ">
					<a data-toggle="tab" href="#vat_tab">
						Vat                    
					</a>
				</li>
				<li class="tab-bg <?php if($this->session->userdata('MailBankStatmentFrom') != ''){ echo "active"; } ?>">
					<a data-toggle="tab" href="#bank_tab">
						Bank Statment                        
					</a>
				</li>
			</ul>-->
			<div class="tab-content">
                <div id="vat_tab" class="tab-pane fade in <?php if(empty($_POST) || $_POST['search_vat'] == 'yes'){ echo "active";} ?> ">
					<div class="panel panel-default panel_custom">
						<div class="panel-body row">
						<?php echo form_open('email', array(
								'name' => 'email-search',
								'id' => 'email-search'
							));?>
							<input type="hidden" name="search_vat" value="yes">							
							<div class="top_btm_spc">
								<div class="col-md-3 col-sm-3 col-xs-12  padding_field">
									<div class="wid-50">
										<label><?php echo $this->lang->line('BULK_EMAIL_TYPE');?>:</label>
									</div>
									<div class="wid-50">
										<select class="form-control" id="Status" name="Status">
											<!-- <option selected="selected" value="">--Select Vat Type--</option> -->
											<!--<option value="ACCOUNT_DUE" <?php
												if ($Status == 'ACCOUNT_DUE') {
													echo 'selected="selected"';
												} else {
													echo '';
												}
											?>>Account Due</option>-->
											<!--<option value="RETURN_DUE" <?php
												if ($Status == 'RETURN_DUE') {
													echo 'selected="selected"';
												} else {
													echo '';
												}
											?>>Return Due</option>-->
											<option value="VAT_DUE" <?php
												if ($Status == 'VAT_DUE') {
													echo 'selected="selected"';
												} else {
													echo '';
												}
											?>>Vat Due</option>
											<option value="BANK_STATMENT_DUE" <?php
												if ($Status == 'BANK_STATMENT_DUE') {
													echo 'selected="selected"';
												} else {
													echo '';
												}
											?>>Bank Statment Due</option>
										</select>
									</div>
								</div>
							</div>
							<div id="fieldsection1" <?php if (empty($_POST) || $filterby == "0") { echo 'style="display:none;"'; }?>>	
								<div class="col-md-3 col-sm-3 col-xs-12  padding_field">
									<div class="wid-40">
										<label><?php echo $this->lang->line('EMAIL_SEARCH_VAT_DAYS');?>:</label>
									</div>
									<div class="wid-60">
										<div id="daysorquarters"> 
											<select name="filterby" id="filterby" class="form-control">
												<option value="0" >--Filter by--</option>
												<option value="Days"<?php
												if ($filterby == 'Days') {
													echo 'selected="selected"';
												} else {
													echo '';
												}
											?>>Days</option>
												<option value="Quarters"<?php
												if ($filterby == 'Quarters') {
													echo 'selected="selected"';
												} else {
													echo '';
												}
											?>>Quarters</option>										
											</select>
										</div>
									</div>
								</div>								
								<div class="top_btm_spc" id="filterbydays" <?php if (empty($_POST) || $Days_relation_with == "0") { echo 'style="display:none;"'; }?>>
									<div class="col-md-3 col-sm-3 col-xs-12  padding_field">
										<div class="wid-40">
											<label><?php echo $this->lang->line('EMAIL_SEARCH_VAT_DAYS');?>:</label>
										</div>
										<div class="wid-60">
											<select class="form-control" id="Days_relation_with" name="Days_relation_with">
												<option value="0">--Select Days--</option> 
												<option value="30" <?php
													if ($Days_relation_with == '30') {
														echo 'selected="selected"';
													} else {
														echo '';
													}
												?>>Next 30 days</option>
												<option value="31" <?php
													if ($Days_relation_with == '31') {
														echo 'selected="selected"';
													} else {
														echo '';
													}
												?>>Next 30 TO 60 days</option>
												<option value="60" <?php
													if ($Days_relation_with == '60') {
														echo 'selected="selected"';
													} else {
														echo '';
													}
												?>>60 Days Above</option>
											</select>
										</div>
									</div>
								</div>
								<div class="top_btm_spc" id="filterbyquarters"  <?php if (empty($_POST) || $Quarters_relation_with == "0") { echo 'style="display:none;"'; }?>>
									<div class="col-md-3 col-sm-3 col-xs-12  padding_field">
										<div class="wid-40">
											<label><?php echo $this->lang->line('EMAIL_SEARCH_VAT_QUARTERS');?>:</label>
										</div>
										<div class="wid-60">
											<select class="form-control" id="Quarters_relation_with" name="Quarters_relation_with">
												<option value="0">--Select Quarters--</option>
												<option value="1" <?php
													if ($Quarters_relation_with == '1') {
														echo 'selected="selected"';
													} else {
														echo '';
													}
												?>>Q1</option>
												<option value="2" <?php
													if ($Quarters_relation_with == '2') {
														echo 'selected="selected"';
													} else {
														echo '';
													}
												?>>Q2</option>
												<option value="3" <?php
													if ($Quarters_relation_with == '3') {
														echo 'selected="selected"';
													} else {
														echo '';
													}
												?>>Q3</option>
												<option value="4" <?php
													if ($Quarters_relation_with == '4') {
														echo 'selected="selected"';
													} else {
														echo '';
													}
												?>>Q4</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div id="fieldsection2" <?php if($Status != 'BANK_STATMENT_DUE'){ echo 'style="display:none;"'; }?>>
								<div class="top_btm_spc">
									<div class="col-md-4 col-sm-4 col-xs-12  padding_field">
										<div class="wid-40">
											<label>Last Upload Date:</label>
										</div>
											<?php //echo date('d-m-Y',strtotime('f day of last month')); ?>
											<?php 
												/*if($this->session->userdata('MailBankStatmentFrom')){
													$Start_date = $this->session->userdata('MailBankStatmentFrom'); 
												}else{
													$Start_date ="";
												}*/
											?>
										<div class="wid-60">
											<input type="text" placeholder="" name="StartDate" class="form-control sDatepicker" value="<?php echo $StartDate; ?>" id="">
										</div>							
									</div>																							
								</div>
							</div>							
							
							<div class="col-md-3 col-sm-3 col-xs-12 text-right padding_field btn_float pull-right"> 
								<button class="btn  btn_search btn-search" type="submit">
									<span class="glyphicon glyphicon-search"></span>Search
								</button> 
								<a class="btn  btn_search reset" type="button" href="<?php echo site_url();?>email">
									<span class="glyphicon glyphicon-refresh"></span>Reset
								</a>
							</div>
						<?php echo form_close();?>
						</div>
            </div>
			<div class="listing-container">    
				<div class="panel panel-default panel_custom">
                    <div class="panel-body row ">
                        <div class="col-md-3 pull-left">
                            <a href="<?php echo site_url('add_email_template');?>" class="btn btn-inverse add-email-template123">
                                <i class="glyphicon glyphicon-plus"></i>
                                Add Template
                            </a>
                        </div>
                        <div class="col-md-9 pull-right bPagination">
                            <?php
							if ($Status == 'ACCOUNT_DUE' && $Status != 'RETURN_DUE' && $Status != 'VAT_DUE') {
								echo $pagination . '<br/>';
							} else if ($Status != 'ACCOUNT_DUE' && $Status == 'RETURN_DUE' && $Status != 'VAT_DUE') {
								echo $pagination . '<br/>';
							} else if ($Status != 'ACCOUNT_DUE' && $Status != 'RETURN_DUE' && $Status == 'VAT_DUE') {
								echo $pagination . '<br/>';
							}?>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
				<?php
					echo form_open('send_mail_template', array(
					'name' => 'sendMail',
					'id' => 'sendMail'
					));
				?>
				<?php if($Status == 'VAT_DUE'){ ?>
					<input type="hidden" name="mail_type" id="mail_type" value="vat">
				<?php }else{ ?>
					<input type="hidden" name="mail_type" id="mail_type" value="bank">
				<?php }?>
				<div class="table-responsive">					
                <!-------------ACCOUNT_DUE------------>
                <?php
				if ($Status == 'ACCOUNT_DUE' && $Status == '') {
					$sno = '';
					$sno = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;?>
                        <table class="dashborad_table">
                            <thead>
                                <tr class="salary-table">
                                    <th>
                                        <input type="checkbox" name="email_Statements" id="selectall"class="pull-left" value="0"/>
                                    </th>
                                    <th>
                                        #Id
                                    </th>
                                    <th>
                                        Client Name
                                    </th>
                                    <th>
                                        <a href="#" class="color">Company Name</a>
                                    </th>
                                    <th>
                                        <a href="#" class="color">Company Registration Number</a>
                                    </th>
                                    <th>
                                        <a href="#" class="color">Year end Date </a>
                                    </th>
                                    <th>
                                        <a href="#" class="color">CH Account Due </a>
                                    </th>
                                    <th>
                                        <a href="#" class="color">Action</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
								if (count($annual_items) == 0) {
									
								} else {
									foreach ($annual_items as $key => $val) {
										$val      = (object) $val;
										$due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->EndDate)));
										//$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
										$date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
										//$date_difference = str_replace('-','',$date_difference);
										$date_difference = trim($date_difference) / (60 * 60 * 24);
										
										if ($date_difference <= $no30) {
											$bg_color = "class='bg-color-red'";
										} elseif ($date_difference > $no30 && $date_difference <= $no60) {
											$bg_color = "class='bg-color-amber'";
										} elseif ($date_difference > $abvoe) {
											$bg_color = "class='bg-color-green'";
										}
										
										if($Days_relation_with == 30 && strtotime(cDate($due_date)) <  date('Y-m-d', strtotime("+30 days"))){
											$userDetails = $this->encrypt->encode($val->ID."/".$val->CID."/".$val->FIRST."/".$val->SECOND);
											echo '<tr>';
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $userDetails . '" data-value="' . $val->CID . '" /></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											$id = $this->encrypt->encode($val->ID);
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
											echo '</td>';
											echo '<td>';
											echo $val->CompanyName;
											echo '</td>';
											echo '<td>';
											echo $val->RegistrationNo;
											echo '</td>';
											echo '<td>';
											echo cDate($val->EndDate);
											echo '</td>';
											$end_date = cDate($val->EndDate);
											if (!empty($end_date)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($due_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											echo '<td>';
											if ($end_date != "" && $end_date != "01-01-1970" && $end_date != "00-00-0000") {
												$filed = "";
												$href  = $this->encrypt->encode($val->CID);
												$ID    = $this->encrypt->encode($val->ID);
												if (empty($filed)) {
													echo '<a href="#" data-client="' . $ID . '" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
													echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
													echo '</a>';
												} else {
													echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
													echo $this->lang->line('DASHBOARD_FILED_LABEL');
													echo '</span>';
												}
											} else {
												echo "&nbsp;";
											}
											echo '</td>';
											
											echo '</tr>';
										}
										if($Days_relation_with == 31 && strtotime(cDate($due_date)) > date('Y-m-d', strtotime("+30 days")) && strtotime(cDate($due_date)) < date('Y-m-d', strtotime("+60 days"))){
											echo '<tr>';
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $userDetails . '" data-value="' . $val->CID . '" /></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											$id = $this->encrypt->encode($val->ID);
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
											echo '</td>';
											echo '<td>';
											echo $val->CompanyName;
											echo '</td>';
											echo '<td>';
											echo $val->RegistrationNo;
											echo '</td>';
											echo '<td>';
											echo cDate($val->EndDate);
											echo '</td>';
											$end_date = cDate($val->EndDate);
											if (!empty($end_date)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($due_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											echo '<td>';
											if ($end_date != "" && $end_date != "01-01-1970" && $end_date != "00-00-0000") {
												$filed = "";
												$href  = $this->encrypt->encode($val->CID);
												$ID    = $this->encrypt->encode($val->ID);
												if (empty($filed)) {
													echo '<a href="#" data-client="' . $ID . '" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
													echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
													echo '</a>';
												} else {
													echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
													echo $this->lang->line('DASHBOARD_FILED_LABEL');
													echo '</span>';
												}
											} else {
												echo "&nbsp;";
											}
											echo '</td>';
											
											echo '</tr>';
										}
										if($Days_relation_with == 60 && strtotime(cDate($due_date)) > date('Y-m-d', strtotime("+60 days")) ){
											$userDetails = $this->encrypt->encode($val->ID."/".$val->CID."/".$val->FIRST."/".$val->SECOND);
											echo '<tr>';
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $userDetails . '" data-value="' . $val->CID . '" /></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											$id = $this->encrypt->encode($val->ID);
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
											echo '</td>';
											echo '<td>';
											echo $val->CompanyName;
											echo '</td>';
											echo '<td>';
											echo $val->RegistrationNo;
											echo '</td>';
											echo '<td>';
											echo cDate($val->EndDate);
											echo '</td>';
											$end_date = cDate($val->EndDate);
											if (!empty($end_date)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($due_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											echo '<td>';
											if ($end_date != "" && $end_date != "01-01-1970" && $end_date != "00-00-0000") {
												$filed = "";
												$href  = $this->encrypt->encode($val->CID);
												$ID    = $this->encrypt->encode($val->ID);
												if (empty($filed)) {
													echo '<a href="#" data-client="' . $ID . '" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
													echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
													echo '</a>';
												} else {
													echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
													echo $this->lang->line('DASHBOARD_FILED_LABEL');
													echo '</span>';
												}
											} else {
												echo "&nbsp;";
											}
											echo '</td>';
											
											echo '</tr>';
										}										
										$sno++;
									}
								}?>
                            </tbody>
                        </table>
                    <?php
				} else {?>
                        <?php
						if ($Status != 'RETURN_DUE' && $Status != 'VAT_DUE' && $Status != 'BANK_STATMENT_DUE') {
							$sno = '';
							$sno = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;?>
                            <table class="dashborad_table">
                                <thead>
                                    <tr class="salary-table">
                                        <th>
                                            <input type="checkbox" name="email_Statements" id="selectall"class="pull-left" value="0" />
                                        </th>
                                        <th>
                                            #Id
                                        </th>
                                        <th>
                                            Client Name
                                        </th>
                                        <th>
                                            <a href="#" class="color">Company Name</a>
                                        </th>
                                        <th>
                                            <a href="#" class="color">Company Registration Number</a>
                                        </th>
                                        <th>
                                            <a href="#" class="color">Year end Date </a>
                                        </th>
                                        <th>
                                            <a href="#" class="color">CH Account Due </a>
                                        </th>
                                        <th>
                                            <a href="#" class="color">Action</a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									if (count($annual_items) == 0) {
										
									} else {
										foreach ($annual_items as $key => $val) {
											$val      = (object) $val;
											$due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->EndDate)));
											//$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
											
											$date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
											//$date_difference = str_replace('-','',$date_difference);
											$date_difference = trim($date_difference) / (60 * 60 * 24);
											if ($date_difference <= 30) {
												$bg_color = "class='bg-color-red'";
											} elseif ($date_difference > 30 && $date_difference <= 60) {
												$bg_color = "class='bg-color-amber'";
											} elseif ($date_difference > 60) {
												$bg_color = "class='bg-color-green'";
											}
											echo '<tr>';
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $val->ID . '"/></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											$id = $this->encrypt->encode($val->ID);
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
											echo '</td>';
											echo '<td>';
											echo $val->CompanyName;
											echo '</td>';
											echo '<td>';
											echo $val->RegistrationNo;
											echo '</td>';
											echo '<td>';
											echo cDate($val->EndDate);
											echo '</td>';
											$end_date = cDate($val->EndDate);
											if (!empty($end_date)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($due_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											echo '<td>';
											if ($end_date != "" && $end_date != "01-01-1970" && $end_date != "00-00-0000") {
												$filed = "";
												$href  = $this->encrypt->encode($val->CID);
												$ID    = $this->encrypt->encode($val->ID);
												if (empty($filed)) {
													echo '<a href="#" data-client="' . $ID . '" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
													echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
													echo '</a>';
												} else {
													echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
													echo $this->lang->line('DASHBOARD_FILED_LABEL');
													echo '</span>';
												}
											} else {
												echo "&nbsp;";
											}
											echo '</td>';
											
											echo '</tr>';
											
											$sno++;
										}
									}?>
								</tbody>
							</table>
						<?php
						}
					}?>
                    <!-------------RETURN_DUE------------>
                    <?php
					if ($Status == 'RETURN_DUE' && $status == '') {
						$sno = '';
						$sno = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;?>
                        <table class="dashborad_table">
                            <thead>
                                <tr class="salary-table">
                                    <th>
                                        <input type="checkbox" name="email_Statements" id="selectall"class="pull-left" value="0"/>
                                    </th>
                                    <th>#Id</th>
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
                                        <a href="#" class="color">Year End Date</a>
                                    </th>
                                    <th>
                                        <a href="#" class="color">Annual Return Due</a>
                                    </th>
                                    <th>
                                        <a href="#" class="color">Action</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
								
									if (count($return_items) == 0) {
										
									} else {
										foreach ($return_items as $key => $val) {
											$val             = (object) $val;
											$return_date     = $val->ReturnDate;
											$date_difference = strtotime($return_date) - strtotime(date('Y-m-d'));
											//$date_difference = str_replace('-','',$date_difference);
											$date_difference = $date_difference / (60 * 60 * 24);
											if ($date_difference <= 30) {
												$bg_color = "class='bg-color-red'";
											} elseif ($date_difference > 30 && $date_difference <= 60) {
												$bg_color = "class='bg-color-amber'";
											} elseif ($date_difference > 60) {
												$bg_color = "class='bg-color-green'";
											}
											echo '<tr>';
											$id = $this->encrypt->encode($val->ID);
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $val->ID . '"/></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
											echo '</td>';
											echo '<td>';
											echo $val->CompanyName;
											echo '</td>';
											echo '<td>';
											echo $val->RegistrationNo;
											echo '</td>';
											echo '<td>';
											echo cDate($val->EndDate);
											echo '</td>';
											$return_date = cDate($val->ReturnDate);
											if (!empty($return_date)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($val->ReturnDate);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											echo '<td>';
											if ($return_date != "" && $return_date != "01-01-1970" && $return_date != "00-00-0000") {
												$filed = "";
												$href  = $this->encrypt->encode($val->CID);
												$ID    = $this->encrypt->encode($val->ID);
												if (empty($filed)) {
													echo '<a href="' . $href . '" data-client="' . $ID . '" class="btn btn-info btn-xs color markRFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
													echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
													echo '</a>';
												} else {
													echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
													echo $this->lang->line('DASHBOARD_FILED_LABEL');
													echo '</span>';
												}
											} else {
												echo "&nbsp;";
											}
											echo '</td>';
											
											echo '</tr>';
											
											$sno++;
										}
									}
									
								?>
                            </tbody>
                        </table>
                    <?php
					}?>
                    <!-------------Vat_DUE------------>
					
					<?php
					if ($Status == 'VAT_DUE') { 
						$sno = '';
						$sno = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
						echo @$ifCondition;
					?>
                        <table class="dashborad_table">
                            <thead>
                                <tr class="salary-table">
                                    <th>
                                        <input type="checkbox" name="email_Statements" id="selectall"class="pull-left" value="0"/>
                                    </th>
                                    <td># Id</td>
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
                                        <a href="#" class="color">Quarter End Date</a>
                                    </th>
                                    <th>
                                        <a href="#" class="color">VAT Due</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
								
                                <?php
								$com_date = date('Y-m-d', strtotime('-6 month', strtotime(date('Y-m-d'))));
								//$com_date = date('Y-m-d', strtotime('0 month', strtotime(date('Y-m-d'))));
								//echo $vatdue_items;																
							    if (count($vatdue_items) == 0) {									
								} else 
								{
								//echo "<pre>";  print_r($vatdue_items); echo "</pre>"; //die;
								foreach ($vatdue_items as $key => $val) {  
									//echo "<pre>"; //print_r($key); 
									//echo "<pre>"; print_r($val); echo "</pre>";								
									
									//}	die;								
									//echo "<pre>"; print_r($val); echo "</pre>"; die;
									$val      = (object) $val;
									//$due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->SECOND))); //die;
									$due_date = date('Y-m-d', strtotime("+7 day", strtotime($val->SECOND)));
									$next_date = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
									//die('772 default');
									//$annual_date = date('Y-m-d',strtotime($val->ReturnDate));									
									if($Days_relation_with == '30'){										
										//$next_date = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
										$next_date = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
										//die('view dafult 769');
										if ($due_date >= $com_date && $due_date <= $next_date) {
											//die('view dafult 769');
											$date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
											//$date_difference = str_replace('-','',$date_difference);
											$date_difference = trim($date_difference) / (60 * 60 * 24);
											if ($date_difference <= 30) {
												$bg_color = "class='bg-color-red'";
											} elseif ($date_difference > 30 && $date_difference <= 60) {
												$bg_color = "class='bg-color-amber'";
											} elseif ($date_difference > 60) {
												$bg_color = "class='bg-color-green'";
											}								
											
											echo '<tr>';										
											$userDetails = $this->encrypt->encode($val->ID."/".$val->CID."/".$val->FIRST."/".$val->SECOND);
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $userDetails . '"/></td>';
											//echo ' <input type="hidden"  name="userDetails[]" value="' . $userDetails. '"/></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
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
											//$end_date = cDate($val->SECOND);
											//$end_date = $val->SECOND;
											//$end_date = cDate($due_date);
											echo '<td>';											
											echo cDate($val->SECOND);
											echo '</td>';
												//$vatDueDate = date("Y-m-d", strtotime("+1 month", strtotime($end_date)));
												//$vatDueDate = date("Y-m-d", strtotime("+7 days", strtotime($end_date)));
												$vatDueDate = date("Y-m-d", strtotime("+0 days", strtotime($next_date)));
											
											if (!empty($vatDueDate)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($due_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											echo '</tr>';
											$sno++;
										}
									} 		

									
									// show data for quarter 1
									//elseif($Quarters_relation_with == '1') {	
									elseif($Quarters_relation_with == '1' && $val->quarter == '1') {
									//echo "<pre>"; print_r($val); echo "</pre>";										
									//echo "first quarter";
																									
									//$quarter_date = date('Y-m-d', strtotime('+3 month', strtotime(date('Y-m-d'))));
									$quarter_date = $val->FIRST. '-' .$val->SECOND;
										   //die('default 843');
										  //$quarter_date = $val->FIRST. '-' .$val->SECOND;
										 // echo $quarter_date;
										//die('view dafult 769');									
										
									//	if ($quarter_date == '2015-11-01-2016-01-31' || $quarter_date == '2017-11-01-2018-01-31') {
								//if ($quarter_date == $val->FIRST. '-' .$val->SECOND) {
								$due_date_month_date = date('Y-m-d', strtotime("+1 month", strtotime($val->SECOND)));
								if ($due_date >= $com_date && $due_date <= $due_date_month_date) {
								
											//die('default 849');
											$date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
											//$date_difference = str_replace('-','',$date_difference);
											$date_difference = trim($date_difference) / (60 * 60 * 24);
											 if ($date_difference <= 90) {
												$bg_color = "class='bg-color-red'";
											}
										elseif ($date_difference > 30 && $date_difference <= 60) {
												$bg_color = "class='bg-color-amber'";
											} elseif ($date_difference > 60) {
												$bg_color = "class='bg-color-green'";
											}						
											
											echo '<tr>';										
											$userDetails = $this->encrypt->encode($val->ID."/".$val->CID."/".$val->FIRST."/".$val->SECOND);
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $userDetails . '"/></td>';
											//echo ' <input type="hidden"  name="userDetails[]" value="' . $userDetails. '"/></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
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
											//$end_date = cDate($val->SECOND);
											//$end_date = cDate($due_date);
											$end_date = $val->SECOND;
											echo '<td>';											
											echo cDate($end_date);
											echo '</td>';
												//$vatDueDate = date("Y-m-d", strtotime("+1 month", strtotime($end_date)));
												
												
												//$vatDueDate = date("Y-m-d", strtotime("+7 days", strtotime($end_date)));
										//$vat_quarter_DueDate = date("Y-m-d", strtotime("+0 days", strtotime($quarter_date)));
												
												 $vat_quarter_DueDate = $quarter_date;
											
											if (!empty($due_date)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($due_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											
											
											echo '</tr>';
											$sno++;
										}
									} 		




									//die('default 920');




									
									if($Days_relation_with == '31'){										
										 $next_date = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
										 $next_date2 = date('Y-m-d', strtotime('+2 month', strtotime(date('Y-m-d'))));										
										if ($due_date >= $next_date && $due_date <= $next_date2) {
											$date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
											//$date_difference = str_replace('-','',$date_difference);
											$date_difference = trim($date_difference) / (60 * 60 * 24);
											if ($date_difference <= 30) {
												$bg_color = "class='bg-color-red'";
											} elseif ($date_difference > 30 && $date_difference <= 60) {
												$bg_color = "class='bg-color-amber'";
											} elseif ($date_difference > 60) {
												$bg_color = "class='bg-color-green'";
											}
											
											
											echo '<tr>';										
											$id = $this->encrypt->encode($val->ID);
											$userDetails = $this->encrypt->encode($val->ID."/".$val->CID."/".$val->FIRST."/".$val->SECOND);
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $userDetails. '"/></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
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
											// $end_date = cDate($val->SECOND);
											
											// add value on quarter End Date
											
											//$end_date = cDate($due_date);
											//echo '<td>';											
											//echo ($end_date);
											//echo '</td>';
											
										
											//$end_date = cDate($due_date);
											$end_date = $val->SECOND;
											if (!empty($end_date)) {
												echo '<td>';
												//echo '<td ' . $bg_color . '>';
												echo cDate($end_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											
											$vatDueDate1 = date("Y-m-d", strtotime("+0 days", strtotime($next_date2)));
											
											
											if (!empty($due_date)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($due_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											
											
											
											
											echo '</tr>';
											$sno++;
										}
									}	

									// show data for quarter 2
									elseif($Quarters_relation_with == '2' && $val->quarter == '2'){	
									//echo 'quarter2';
										//die('992');								
										//$next_date = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
										  //$quarter_date = date('Y-m-d', strtotime('+3 month', strtotime(date('Y-m-d'))));
										   $quarter_date2 = $val->FIRST. '-' .$val->SECOND;
										//die('view dafult 769');
							//if($quarter_date2 == '2016-02-01-2016-04-30' || $quarter_date2 == '2017-02-01-2017-04-30' || $quarter_date2 == '2018-02-01-2018-04-30'){
								$due_date_month_date = date('Y-m-d', strtotime("+1 month", strtotime($val->SECOND)));
								if ($due_date >= $com_date && $due_date <= $due_date_month_date) {
										//if ($due_date >= $com_date && $due_date <= $quarter_date) {
			
											$date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
											//$date_difference = str_replace('-','',$date_difference);
											$date_difference = trim($date_difference) / (60 * 60 * 24);
											 if ($date_difference <= 90) {
												$bg_color = "class='bg-color-red'";
											}
										elseif ($date_difference > 30 && $date_difference <= 60) {
												$bg_color = "class='bg-color-amber'";
											} elseif ($date_difference > 60) {
												$bg_color = "class='bg-color-green'";
											}								
											
											echo '<tr>';										
											$userDetails = $this->encrypt->encode($val->ID."/".$val->CID."/".$val->FIRST."/".$val->SECOND);
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $userDetails . '"/></td>';
											//echo ' <input type="hidden"  name="userDetails[]" value="' . $userDetails. '"/></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
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
											//$end_date = cDate($val->SECOND);
											//$end_date = cDate($due_date);
											$end_date = $val->SECOND;
											echo '<td>';											
											echo cDate($end_date);
											echo '</td>';
												//$vatDueDate = date("Y-m-d", strtotime("+1 month", strtotime($end_date)));
												
												
												//$vatDueDate = date("Y-m-d", strtotime("+7 days", strtotime($end_date)));
										//$vat_quarter_DueDate2 = date("Y-m-d", strtotime("+0 days", strtotime($quarter_date2)));
										$vat_quarter_DueDate2 = $quarter_date2;
											
											if (!empty($due_date)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($due_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											echo '</tr>';
											$sno++;
										}
									} 		




									
									if($Days_relation_with == '60'){	
										//echo $next_date = date('Y-m-d', strtotime('+2 month', strtotime(date('Y-m-d'))));
										  $next_date3 = date('Y-m-d', strtotime('+2 month', strtotime(date('Y-m-d'))));
										if ($due_date >= $next_date3) {
											$date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
											//$date_difference = str_replace('-','',$date_difference);
											$date_difference = trim($date_difference) / (60 * 60 * 24);
											if ($date_difference <= 30) {
												$bg_color = "class='bg-color-red'";
											} elseif ($date_difference > 30 && $date_difference <= 60) {
												$bg_color = "class='bg-color-amber'";
											} elseif ($date_difference > 60) {
												$bg_color = "class='bg-color-green'";
											}
											
											
											echo '<tr>';										
											$id = $this->encrypt->encode($val->ID);
											$userDetails = $this->encrypt->encode($val->ID."/".$val->CID."/".$val->FIRST."/".$val->SECOND);
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . userDetails . '"/></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
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
											
											// add value on quarter End Date
											
											//$end_date = cDate($due_date);
											$end_date = $val->SECOND;
											echo '<td>';											
											echo cDate($end_date);
											echo '</td>';
											
											// $end_date = cDate($val->SECOND);
											
											/*
											$end_date = cDate($due_date);
											if (!empty($end_date)) {
												echo '<td ' . $bg_color . '>';
												echo ($end_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}*/
											
											$vatDueDate2 = date("Y-m-d", strtotime("+0 days", strtotime($next_date3)));
											
											if (!empty($due_date)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($due_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											
											echo '</tr>';
											$sno++;
										}
									}
									
									
									// show data for quarter 3
									elseif($Quarters_relation_with == '3' && $val->quarter == '3'){	
									//echo 'third quarter';
										//die('992');								
										//$next_date = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
										//echo  $quarter_date = date('Y-m-d', strtotime('+3 month', strtotime(date('Y-m-d'))));
										 $quarter_date3 = $val->FIRST. '-' .$val->SECOND;
										//$quarter_date3 = $val;
										//die('view dafult 769');
										//if ($due_date >= $com_date && $due_date <= $quarter_date) {
										//if($quarter_date3 == '2016-05-01-2016-07-31' || $quarter_date3 == '2017-05-01-2017-07-31' || $quarter_date3 == '2018-05-01-2018-07-31'){
										$due_date_month_date = date('Y-m-d', strtotime("+1 month", strtotime($val->SECOND)));
										if ($due_date >= $com_date && $due_date <= $due_date_month_date) {
											
											$date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
											//$date_difference = str_replace('-','',$date_difference);
											$date_difference = trim($date_difference) / (60 * 60 * 24);
											 if ($date_difference <= 90) {
												$bg_color = "class='bg-color-red'";
											}
											elseif ($date_difference > 30 && $date_difference <= 60) {
												$bg_color = "class='bg-color-amber'";
											} elseif ($date_difference > 60) {
												$bg_color = "class='bg-color-green'";
											}								
											
											echo '<tr>';										
											$userDetails = $this->encrypt->encode($val->ID."/".$val->CID."/".$val->FIRST."/".$val->SECOND);
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $userDetails . '"/></td>';
											//echo ' <input type="hidden"  name="userDetails[]" value="' . $userDetails. '"/></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
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
											//$end_date = cDate($val->SECOND);
											//$end_date = cDate($due_date);
											$end_date = $val->SECOND;
											echo '<td>';											
											echo cDate($end_date);
											echo '</td>';
												//$vatDueDate = date("Y-m-d", strtotime("+1 month", strtotime($end_date)));
												
												
												//$vatDueDate = date("Y-m-d", strtotime("+7 days", strtotime($end_date)));
										//$vat_quarter_DueDate = date("Y-m-d", strtotime("+0 days", strtotime($quarter_date3)));
										$vat_quarter_DueDate3 = $quarter_date3;
											
											if (!empty($due_date)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($due_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}
											
											echo '</tr>';
											$sno++;
										}
									} 	

									// show data for quarter 4
									
									elseif($Quarters_relation_with == '4' && $val->quarter == '4'){	
									
										//die('992');								
										
										 $quarter_date4 = $val->FIRST. '-' .$val->SECOND;
										//$quarter_date3 = $val;
										//die('view dafult 769');
										//if($quarter_date4 == '2016-08-01-2016-10-31' || $quarter_date4 == '2017-08-01-2017-10-31' || $quarter_date4 == '2018-08-01-2018-10-31'){
									//if($quarter_date4 == $val->FIRST. '-' .$val->SECOND ){
									$due_date_month_date = date('Y-m-d', strtotime("+1 month", strtotime($val->SECOND)));
									if ($due_date >= $com_date && $due_date <= $due_date_month_date) {	
										
											$date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
											//$date_difference = str_replace('-','',$date_difference);
											$date_difference = trim($date_difference) / (60 * 60 * 24);
											 if ($date_difference <= 90) {
												$bg_color = "class='bg-color-red'";
											}					
												
											echo '<tr>';										
											$userDetails = $this->encrypt->encode($val->ID."/".$val->CID."/".$val->FIRST."/".$val->SECOND);
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $userDetails . '"/></td>';
											//echo ' <input type="hidden"  name="userDetails[]" value="' . $userDetails. '"/></td>';
											echo '</td>';
											echo '<td>';
											echo $sno;
											echo '</td>';
											echo '<td>';
											echo '<a href="' . site_url() . 'client_access/' . @$id . '">' . $val->Name . '</a>';
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
											//$end_date = cDate($val->SECOND);
											//$end_date = cDate($due_date);
											$end_date = $val->SECOND;
											echo '<td>';											
											echo cDate($end_date);
											echo '</td>';
												
										//$vat_quarter_DueDate = date("Y-m-d", strtotime("+0 days", strtotime($quarter_date4)));
										
										$vat_quarter_DueDate4 = $quarter_date4;
											
											if (!empty($due_date)) {
												echo '<td ' . $bg_color . '>';
												echo cDate($due_date);
												echo '</td>';
											} else {
												echo '<td>';
												echo '';
												echo '</td>';
											}											
											echo '</tr>';
											$sno++;
										}
									} 		
									
									
								}
							}
							
							
							
							
							
							
							
							?>
                            </tbody>
                        </table>

                    <?php
					}?>
					
					
					<!-------------BANK STATMENT DUE------------>
					<?php
					if ($Status == 'BANK_STATMENT_DUE') { 
						$sno = '';
						$sno = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
					?>		
                    
					<table class="dashborad_table">
							<thead>
								<tr class="salary-table">
									<th>
										<input type="checkbox" name="email_Statements"  id="selectall2"class="pull-left" value="0" />
									</th>
									<th>
										#Id
									</th>
									<th>
										Client Name
									</th>
									<th>
										<a href="#" class="color">Company Name</a>
									</th>
									<th>
										<a href="#" class="color">Company Registration Number</a>
									</th>
									<th>
										<a href="#" class="color">Year end Date </a>
									</th>
									<th>
										<a href="#" class="color">Last Upload Date </a>
									</th>
									<th>
										<a href="#" class="color">Action</a>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php								
									foreach ($bankstatment_items as $key => $val) {
										//echo "<pre>";print_r($val); die;										
										$val      = (object) $val;
										$due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->EndDate)));
										//$annual_date = date('Y-m-d',strtotime($val->ReturnDate));												
										$date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
										//$date_difference = str_replace('-','',$date_difference);
										$date_difference = trim($date_difference) / (60 * 60 * 24);
										if ($date_difference <= 30) {
											$bg_color = "class='bg-color-red'";
										} elseif ($date_difference > 30 && $date_difference <= 60) {
											$bg_color = "class='bg-color-amber'";
										} elseif ($date_difference > 60) {
											$bg_color = "class='bg-color-green'";
										}
										if($this->session->userdata('MailBankStatmentFrom') != ''){											
											if( strtotime(cDate($val->last_uplode_date)) < strtotime($this->session->userdata('MailBankStatmentFrom')) ){
												$userDetails = $this->encrypt->encode($val->ID."/".$val->CID);
												echo '<tr>';
												echo '<td>';
												echo ' <input type="checkbox" class="email-statement-check checkbox2" name="cb[]" value="' . $userDetails . '"/></td>';
												echo '</td>';
												echo '<td>';
												echo $sno;
												echo '</td>';
												$id = $this->encrypt->encode($val->ID);
												echo '<td>';
												echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
												echo '</td>';
												echo '<td>';
												echo $val->CompanyName;
												echo '</td>';
												echo '<td>';
												echo $val->RegistrationNo;
												echo '</td>';
												echo '<td>';
												echo cDate($val->EndDate);
												echo '</td>';																				
												echo '<td>';
												if($val->ID != '' && $val->CID != '')
													echo cDate(getBankStatmentLastUploadDate($val->ID,$val->CID));
												echo '</td>';												
												
												echo '<td>';
												if ($end_date != "" && $end_date != "01-01-1970" && $end_date != "00-00-0000") {
													$filed = "";
													$href  = $this->encrypt->encode($val->CID);
													$ID    = $this->encrypt->encode($val->ID);
													if (empty($filed)) {
														echo '<a href="#" data-client="' . $ID . '" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
														echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
														echo '</a>';
													} else {
														echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
														echo $this->lang->line('DASHBOARD_FILED_LABEL');
														echo '</span>';
													}
												} else {
													echo "&nbsp;";
												}
												echo '</td>';
												
												echo '</tr>';
												
												$sno++;
											}
										}else{											
											if( strtotime(cDate(@$val->last_uplode_date)) <  strtotime(date("d-m-Y")) ){
												//print_r($val); die;										
												$val      = (object) $val;
												$due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->EndDate)));
												//$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
												
												$date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
												//$date_difference = str_replace('-','',$date_difference);
												$date_difference = trim($date_difference) / (60 * 60 * 24);
												if ($date_difference <= 30) {
													$bg_color = "class='bg-color-red'";
												} elseif ($date_difference > 30 && $date_difference <= 60) {
													$bg_color = "class='bg-color-amber'";
												} elseif ($date_difference > 60) {
													$bg_color = "class='bg-color-green'";
												}
												$userDetails = $this->encrypt->encode($val->ID."/".$val->CID);
												echo '<tr>';
												echo '<td>';
												echo ' <input type="checkbox" class="email-statement-check checkbox2" name="cb[]" value="' . $userDetails.'"/></td>';
												echo '</td>';
												echo '<td>';
												echo $sno;
												echo '</td>';
												$id = $this->encrypt->encode($val->ID);
												echo '<td>';
												echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
												echo '</td>';
												echo '<td>';
												echo $val->CompanyName;
												echo '</td>';
												echo '<td>';
												echo $val->RegistrationNo;
												echo '</td>';
												echo '<td>';
												echo cDate($val->EndDate);
												echo '</td>';																				
												echo '<td>';												
												if($val->ID != '' && $val->CID != '')
													echo cDate(getBankStatmentLastUploadDate($val->ID,$val->CID));
												echo '</td>';
											
											
												echo '<td>';
												if (@$end_date != "" && @$end_date != "01-01-1970" && @$end_date != "00-00-0000") {
													$filed = "";
													$href  = $this->encrypt->encode($val->CID);
													$ID    = $this->encrypt->encode($val->ID);
													if (empty($filed)) {
														echo '<a href="#" data-client="' . $ID . '" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
														echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
														echo '</a>';
													} else {
														echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
														echo $this->lang->line('DASHBOARD_FILED_LABEL');
														echo '</span>';
													}
												} else {
													echo "&nbsp;";
												}
												echo '</td>';
												
												echo '</tr>';
												
												$sno++;
											}
										}
									}
								?>
							</tbody>
						</table>					
						<?php
						}?>				
					</div> 
					<div class="clearfix"></div>
						<div class="panel panel-default panel_custom">
							<div class="panel-body row ">
								<div class="col-md-3 pull-left">
									<div class="col-md-2 send-client-mail" style="display:none">
										<button type="submit" class="btn btn-danger btn-send-mailasdf">Send Email</button>
									</div>
								</div>

								<div class="col-md-8 pull-right bPagination">
									<?php
									if ($Status == 'ACCOUNT_DUE' && $Status != 'RETURN_DUE' && $Status != 'VAT_DUE') {
										echo $pagination . '<br/>';
									} else if ($Status != 'ACCOUNT_DUE' && $Status == 'RETURN_DUE' && $Status != 'VAT_DUE') {
										echo $pagination . '<br/>';
									} else if ($Status != 'ACCOUNT_DUE' && $Status != 'RETURN_DUE' && $Status == 'VAT_DUE') {
										echo $pagination . '<br/>';
									} else {
										echo $pagination . '<br/>';
									}
									?>
								</div>
							</div>
						</div>
			<?php
				echo form_close();
				?>
            </div> 
			</div>			
				
			</div>	         
        </div>
    </div>
</section>
<?php 
$this->load->view('accountant/footer');
?>
<div class="modal fade modal select-email-template" id="select-email-template" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 90%; height: auto; max-height: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE');?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body"></div>
         </div>
    </div>
</div>