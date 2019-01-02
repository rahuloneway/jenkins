<?php

//echo "<prE>";print_r($_POST); echo "</pre>";
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
    $Days_relation_with     = $_POST['Days_relation_with'];
    $Quarters_relation_with = $_POST['Quarters_relation_with'];
    $Status                 = $_POST['Status'];
	$filterby               = $_POST['filterby'];
	$StartDate              = $_POST['StartDate'];
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
											echo '<tr>';
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $val->ID . '" data-value="' . $val->CID . '" /></td>';
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
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $val->ID . '" data-value="' . $val->CID . '" /></td>';
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
											echo '<tr>';
											echo '<td>';
											echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb[]" value="' . $val->ID . '" data-value="' . $val->CID . '" /></td>';
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
						echo $ifCondition;
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
																								
							    if (count($vatdue_items) == 0) {
									
								} else {
        
								foreach ($vatdue_items as $key => $val) {  
									//echo "<pre>"; print_r($key); print_r($val);echo "</pre>"; die;
									//}	die;								
									//echo "<pre>"; print_r($val); echo "</pre>"; die;
									$val      = (object) $val;
									//$due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->SECOND))); //die;
									$due_date = date('Y-m-d', strtotime($val->SECOND)); //die;
									//$annual_date = date('Y-m-d',strtotime($val->ReturnDate));									
									if($Days_relation_with == '30'){										
										$next_date = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
										if ($due_date >= $com_date && $due_date <= $next_date) {
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
											$end_date = cDate($due_date);
											echo '<td>';											
											echo ($end_date);
											echo '</td>';
												//$vatDueDate = date("Y-m-d", strtotime("+1 month", strtotime($end_date)));
												$vatDueDate = date("Y-m-d", strtotime("+7 days", strtotime($end_date)));
											
											if (!empty($vatDueDate)) {
												echo '<td ' . $bg_color . '>';
												echo ($vatDueDate);
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
											echo $val->VATRegistrationNo;
											echo '</td>';
											// $end_date = cDate($val->SECOND);
											$end_date = cDate($due_date);
											if (!empty($end_date)) {
												echo '<td ' . $bg_color . '>';
												echo ($end_date);
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
										$next_date = date('Y-m-d', strtotime('+2 month', strtotime(date('Y-m-d'))));
										if ($due_date >= $next_date) {
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
											echo $val->VATRegistrationNo;
											echo '</td>';
											// $end_date = cDate($val->SECOND);
											$end_date = cDate($due_date);
											if (!empty($end_date)) {
												echo '<td ' . $bg_color . '>';
												echo ($end_date);
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
							}?>
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
											if( strtotime(cDate($val->last_uplode_date)) <  strtotime(date("d-m-Y")) ){
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
