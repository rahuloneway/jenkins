<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$this->load->view('accountant/header', array('title' => $title));
//echo "<pre>"; print_r($item->ClientDetails); die; ?>

<section class="grey-body">
    <div class="container-fluid">
        <div class="account_sum">
            <?php echo $this->session->flashdata('clientError'); ?>	
			<div class="client_panel">
                <ul class="nav tabs-menu" id="myTab" >
                    <li class="client_details active">
                        <a href="#client_det">Client Details</a>
                    </li>
                    <li class="company_details">
                        <a href="#company_details">Company Details</a>
                    </li>
                    <li class="vat_details">
                        <a href="#vat_details">VAT Details</a>
                    </li>                   
                    <li class="bnk_details">
                        <a href="#bnk_details">Bank Details</a>
                    </li>                   
                </ul>
            </div>
			    
			
            <?php echo form_open_multipart(site_url() . 'accountant/bulkclient/save_statements', array('id' => 'updateBulkClients')); ?>
            <div class="tab tab-content">
			<div id="client_det" class="client_detail">	
				<div class="table-responsive">				
					<table id="tblOne">
						<thead>					
							<tr class="clientdetails-table">
								<th>
									#
								</th>
								<th>
								   Title
								</th>
								<th>
								   First Name
								</th>
								<th>
								   Last Name
								</th>
								<th>
									Email
								</th>
								<th>
									Phone No.
								</th>
								<th>
									NI Number
								</th>
								<th>
									UTR
								</th>
								<th>
									Address 1
								</th>
								<th>
									Address 2
								</th>
								<th>
									Address 3
								</th>
								<th>
									Postal Code
								</th>
								<th>
									Is Director
								</th>
								<th>
									Is Shareholder
								</th>
								<th>
									Is Employee
								</th>							
							</tr>
						</thead>
						<tbody id="clientdetail-listing">
						<?php 
						$x = 1;
						$h = 0;
						foreach($item->ClientDetails as $clientdetail){	
						$clname[] = $clientdetail->First_name.' '.$clientdetail->Last_Name;
						?>
						<tr>
						<td class="sno">
							<?php echo ($x); ?>
						</td>
						<td>
							<?php echo salutationList('ClientSalutation[]', $clientdetail->Title); ?>
						</td>
						<td>
							<input type="text" class="form-control required fstname<?php echo $x; ?>" id="fstname<?php echo $x; ?>" placeholder="" name="FirstName[]" value="<?php echo $clientdetail->First_name; ?>" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>
						</td>
						<td>
							<input type="text" class="form-control required lastname<?php echo $x; ?>" id="lastname<?php echo $x; ?>" placeholder="" name="LastName[]"  value="<?php echo $clientdetail->Last_Name; ?>" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>
						</td>
						<td>
							<input type="text" name="email[]" id="email<?php echo $x; ?>" class="form-control email<?php echo $x; ?> required" placeholder="" value="<?php echo $clientdetail->Email; ?>" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>					
						</td>
						<td>
						<input type="text" name="phoneNo[]" id="phn<?php echo $x; ?>" class="form-control phonenumber phn<?php echo $x; ?>" placeholder="" value="<?php echo $clientdetail->Phone; ?>" maxlength="11" data-tooltip<?php echo $x; ?> onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>
						</td>
						<td>   
							<input type="text" name="niNumber[]" id="niNumber<?php echo $x; ?>" class="form-control niNumber<?php echo $x; ?>" placeholder=""value="<?php echo $clientdetail->NI_Number; ?>" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>
							
						</td>
						<td>  
							<input type="text" name="utr[]" class="form-control utrnumber<?php echo $x; ?>" id="utrnumber<?php echo $x; ?>" placeholder=""value="<?php echo $clientdetail->UTR; ?>" maxlength="10" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>					
						</td>
						<td>    
							<input type="text" name="addressOne[]" class="form-control" placeholder=""value="<?php echo $clientdetail->Address1;?>"/>					
						</td>
						<td>
							<input type="text" name="addressTwo[]" class="form-control"placeholder="" value="<?php echo $clientdetail->Address2; ?>"/>					
						</td>
						<td>        
							<input type="text" name="addressThree[]" class="form-control" placeholder=" " value="<?php echo $clientdetail->Address3; ?>"/>
						</td>
						<td>       
							<input type="text" name="postalCode[]" class="form-control postalcode<?php echo $x; ?>" id="postalcode<?php echo $x; ?>" placeholder="" value="<?php echo $clientdetail->Post_code; ?>" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>
						</td>				
						<td>
						<?php
						if ($clientdetail->is_director == 1) {
							$is_director = "checked='checked'";	
							$is_directorvalue = 1;
						}else {							
							$is_director = "";	
							$is_directorvalue = 0;
						}
						if ($clientdetail->is_shareholder == 1) {
							$is_shareholder = "checked='checked'";	
							$is_shareholdervalue = 1;	
						}else {							
							$is_shareholder = "";	
							$is_shareholdervalue = 0;
						}
						if ($clientdetail->is_employee == 1) {
							$is_employee = "checked='checked'";		
							$is_employeevalue = 1;
						}else{						
							$is_employee = "";	
							$is_employeevalue = 0;
						}
						?>
							<input type="checkbox" class="form-control Isdirector" name="Isdirector[<?php echo $h; ?>]" id="is_dir_<?php echo $x; ?>" placeholder="" onClick="onchangeDirector(<?php echo $x; ?>)"  value="<?php echo $is_directorvalue; ?><?php //echo $clientdetail->is_director; ?>" <?php echo $is_director; ?>/>							
							</td>
							<td>
							<input type="checkbox" class="form-control Isshareholder" name="Isshareholder[<?php echo $h; ?>]" id="is_share_<?php echo $x; ?>" placeholder=""onClick="onchangeShare(<?php echo $x; ?>)" value="<?php echo $is_shareholdervalue; ?><?php //echo $clientdetail->is_shareholder; ?>" <?php echo $is_shareholder; ?>/>					
							</td>
							<td>
							<input type="checkbox" class="form-control Isemployee" name="Isemployee[<?php echo $h; ?>]" id="is_emp_<?php echo $x; ?>"  placeholder="" onClick="onchangeEmployee(<?php echo $x; ?>)" value="<?php echo $is_employeevalue;?><?php //echo $clientdetail->is_employee; ?>" <?php echo $is_employee; ?>/>			</td>  
						</tr>
						<?php 
						$x++;
						$h++;
						} ?>
						</tbody>
					</table>
				</div>
            </div>
			<div id="company_details" class="client_detail">
			<div class="table-responsive">
                <table id="tblComp">
                    <thead>					
                        <tr class="companydetails-table">
                            <th>
                               Client Name
                            </th>
							<th>
                               Company Name
                            </th>
							<th>
                               Registration No.
                            </th>
							<th>
                               Incorporation Date
                            </th>
							<th>
                               Year End
                            </th>
							<th>
                               Return Date
                            </th>
							<th>
                               Address 1
                            </th>
                            <th>
                               Address 2
                            </th>
                            <th>
                                Address 3
                            </th> 
							<th>
                               Postal Code
                            </th>							
                        </tr>
                    </thead>
                    <tbody id="companydetails-listing">
                        <?php 
					$x = 1;
					//echo "<pre>"; print_r($clname);
					foreach($item->CompanyDetails as $key=>$companyDetail){	?>
					   <tr>
							<td>
							<input type="text" name="Clientname" class="form-control required" placeholder="" value="<?php echo $clname[$key];?>" readonly />
								
							</td>
							<td>
							<input type="text" name="CompanyName[]" id="cmpnm<?php echo $x; ?>" class="form-control required cmpnm<?php echo $x; ?>" placeholder="" value="<?php echo $companyDetail->Company_name; ?>" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>
							</td>
							<td>
							 <input type="text" name="CompanyRegisteredNo[]" id="regnmbr<?php echo $x; ?>" class="form-control required check_value regnmbr<?php echo $x; ?>" id="regnmbr<?php echo $x; ?>" placeholder=" " value="<?php echo $companyDetail->Company_reg_num; ?>" maxlength="8" data-tooltip <?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>					
							</td>
							<td>
							<input type="text" name="IncorporationDate[]" class="form-control sDatepicker" placeholder=" "value="<?php echo cDate($companyDetail->date_of_incop); ?>"/>				
							</td>
							<td>
							 <input type="text" name="YearEndDate[]" id="yearnd<?php echo $x; ?>" class="form-control sDatepicker required yearnd<?php echo $x; ?>" placeholder=" "value="<?php echo cDate($companyDetail->year_date_end); ?>" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>										
							</td>
							<td>
							 <input type="text" name="ReturnDate[]" class="form-control sDatepicker" placeholder=" "value="<?php echo cDate($companyDetail->returndate); ?>"/>								
							</td>	
							<td> 
							<input type="text" class="form-control" name="CompanyAddOne[]" placeholder="" value="<?php echo $companyDetail->line1; ?>"/>						
							</td>
							<td>  
							<input type="text" class="form-control"  name="CompanyAddTwo[]" placeholder="" value="<?php echo $companyDetail->line2; ?>"/>										
							</td>
							<td> 
							<input type="text" class="form-control" name="CompanyAddThree[]" placeholder="" value="<?php echo $companyDetail->line3; ?>"/>						
							</td>
							<td>
							<input type="text" class="form-control cpostalcode cpsc<?php echo $x; ?>" id="cpsc<?php echo $x; ?>"  name="CompanyPostalCode[]" placeholder=" "value="<?php echo $companyDetail->post_codes; ?>" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>								
							</td>							
							</tr>
					<?php $x++; } ?>
                    </tbody>
                </table>
            </div>
			</div>
			
			
			<div id="vat_details" class="client_detail">
			<div class="table-responsive">
                <table id="tblVat">
                    <thead>					
                        <tr class="companydetails-table">                 
                           <th>
                                Client Name
                            </th>
							 <th>
                                Vat Type
                            </th>
                            <th>
                                Quarters
                            </th>
							<th>
                                Registration Number
                            </th>
							<th>
                                Percentage
                            </th>
							<th>
                                First Year Discount End Date
                            </th>						
														
                        </tr>
                    </thead>
                    <tbody id="companydetails-listing">
                        <?php 
					$x = 1;
					foreach($item->CompanyDetails as $key=>$companyDetail){	?>	
						<tr>
						<td>
						<input type="text" name="Clientname" class="form-control required" placeholder="" value="<?php echo $clname[$key];?>" readonly />
						</td>
							<td>
						<?php
						if ($companyDetail->vat_type == '1') {
							$flat = "selected='selected'";
							$stand = '';
							$select = '';
						} elseif ($companyDetail->vat_type == '2') {
							$stand = "selected='selected'";
							$flat = '';
							$select = '';
						} else {
							$stand = '';
							$flat = '';
							$select = "selected='selected'";
						}
						?>
						<select name="VATRegisteredType[]" class="required">
							<option value="" <?php echo $select; ?>>Select Rate</option>
							<option value="flat" <?php echo $flat; ?>>Flat Rate</option>
							<option value="stand"<?php echo $stand; ?>>Standard</option>
						</select>
						</td>
						<td>       
							 <?php echo vatQuatersDropdownmulti($companyDetail->quarter, 'class="" id="VATQuaters"'); ?>						
						</td>
						<td>       
							 <input type="text" class="form-control required vatregno<?php echo $x; ?>" id="vatregno<?php echo $x; ?>" name="VATRegisteredNo[]" placeholder="" value="<?php echo $companyDetail->reg_number; ?>" maxlength="9" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)" /> 
						</td>
						<td>       
							 <input type="text" name="VATRatePercent[]" class="form-control validNumber vatrate<?php echo $x; ?>" id="vatrate<?php echo $x;?>" placeholder=""maxlength="5"value="<?php echo $companyDetail->percentage; ?>" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/> 
						</td> 
						<td>       
							 <input type="text"name="VATEndDate[]" class="form-control sDatepicker required" placeholder="End date"value="<?php echo cDate($companyDetail->first_year_dis); ?>"/>
						</td>
						</tr>
					<?php 
					$x++;
					} ?>
                    </tbody>
                </table>
            </div>
			</div>
			
			<div id="bnk_details" class="client_detail">
			<div class="table-responsive">
                <table id="tblBank">
                    <thead>					
                        <tr class="companydetails-table"> 
						<th>
						Client Name
						</th>
                         <th>
                                Name
                            </th>
							<th>
                                Account No.
                            </th>
							<th>
                                Sort Order
                            </th>
							<th>
                                Opening Balance
                            </th>							
                        </tr>
                    </thead>
                    <tbody id="companydetails-listing">
                        <?php 
					$x = 1;
					foreach($item->CompanyDetails as $key=>$companyDetail){	?>	
					<tr>
					<td>
						<input type="text" name="Clientname" class="form-control required" placeholder="" value="<?php echo $clname[$key];?>" readonly />
					</td>
					<td>       
					 <input type="text" name="BankName[]" class="form-control" placeholder="" value="<?php echo $companyDetail->name ?>"/>
					</td> 
					<td>       
						 <input type="text" class="form-control accnmbr<?php echo $x; ?>" id="accnmbr<?php echo $x; ?>" name="AccountNumber[]" placeholder=""value="<?php echo $companyDetail->account_number; ?>" maxlength="8" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>
					</td> 
					<td>       
						<input type="text" class="form-control shrtcd<?php echo $x; ?>" id="shrtcd<?php echo $x; ?>" name="ShortCode[]" placeholder="" value="<?php echo $companyDetail->sort_code; ?>"maxlength="6" data-tooltip<?php echo $x; ?>  onmouseover="onhover(this,<?php echo $x; ?>);" onmouseout="onhoverdelete(this)"/>
					</td>
					<td>       
						<input type="text" class="form-control validNumber openb<?php echo $x; ?>" id="openb<?php echo $x; ?>" name="OpeningBalance[]" placeholder="" value="<?php 
						$openbalance =  $companyDetail->open_balance;
						if (!is_float($openbalance)){
							echo number_format($openbalance, 2);
						}else{
							echo $openbalance;
						}
							
	
						?>"maxlength="6"/>
					</td>
					</tr>
					<?php 
					$x++;
					} ?>
                    </tbody>
                </table>
            </div>
			</div>
			
            <br/><br/>
			 </div>
            <div class="col-md-12">
			
                <div class="pull-right">
                    <button type="button" class="btn btn-success btn-primary btn-sm spacer bulk_client_finish" id="uploadTerm">
                        <i class="glyphicon glyphicon-floppy-disk"></i>&nbsp;<?php echo $this->lang->line('BUTTON_SAVE_AND_FINISH'); ?>
                    </button>
                    <a data-dismiss="modal" class="btn btn-danger btn-sm spacer bulkcancel_clientupload" href="#">
                        <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CANCEL'); ?>
                    </a>
                </div>
            </div>
           
			<?php echo form_close(); ?>
            <br/>
            <div class="clr"></div>
        </div>
    </div>
</section>

<div id="dialog"></div>	
 <script type="text/javascript" src="<?php echo site_url().'assets/js/jquery-ui.min.js' ?>"></script>
<?php $this->load->view('accountant/bulkclient/bulkclient_js'); ?>
<?php $this->load->view('accountant/footer'); ?>

<script>
$(document).ready(function() {
	$('.client_detail').hide();
	$('#client_det').show();
    $(".tabs-menu a").click(function(event) {
        event.preventDefault();
        $(this).parent().addClass("active");
        $(this).parent().siblings().removeClass("active");
        var tab = $(this).attr("href");
        $(".client_detail").not(tab).css("display", "none");
        $(tab).fadeIn();
    });	
});
function onchangeEmployee(x){
	 var value =$('#is_emp_'+x).val();
	if(value == 1){
		$('#is_emp_'+x).val(0);
	}
	if(value == 0){
		$('#is_emp_'+x).val(1);
	}
}
function onchangeDirector(x){
	 var value =$('#is_dir_'+x).val();
	if(value == 1){
		$('#is_dir_'+x).val(0);
	}
	if(value == 0){
		$('#is_dir_'+x).val(1);
	}
}
function onchangeShare(x){
	 var value =$('#is_share_'+x).val();
	if(value == 1){
		$('#is_share_'+x).val(0);
	}
	if(value == 0){
		$('#is_share_'+x).val(1);
	}
}

function onhover(obj,x){
	var inputid = obj.id;
	var toolTiptext = $('#'+inputid).attr('data-tooltip'+x);
	if(toolTiptext != ''){
		if($('#'+inputid).nextAll('.tooltipee').length == 0){
			$('#'+inputid).after("<div class='tooltipee'>"+toolTiptext +"</div>");
		}		
	}	
	
}
function onhoverdelete(obj){
	var inputid = obj.id;
	$('#'+inputid).nextAll('.tooltipee').remove();	
}


</script>

<style>
form#updateBulkClients {
    margin-top: 10px;
}
.tooltipee{
font-size:12px;
  position:absolute;
   background-color:#333;
  color:#fff;
  padding:5px;
  border-radius:5px;
  margin-left: -21px;
    margin-top: -63px;
}
.tooltipee {
	
	background: #88b7d5;
	border: 1px solid #c2e1f5;
}
.tooltipee:after, .tooltipee:before {
	top: 100%;
	left: 50%;
	border: solid transparent;
	content: " ";
	height: 0;
	width: 0;
	position: absolute;
	pointer-events: none;
}

.tooltipee:after {
	border-color: rgba(136, 183, 213, 0);
	border-top-color: #88b7d5;
	border-width: 15px;
	margin-left: -15px;
}
.tooltipee:before {
	border-color: rgba(194, 225, 245, 0);
	border-top-color: #c2e1f5;
	border-width: 19px;
	margin-left: -19px;
}
</style>
