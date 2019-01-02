<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('accountant/header', array('page' => $page, 'title' => $title)); ?>
<?php
//echo '<pre>';print_r($item);echo '</pre>';
?>
<script>
    $(document).ready(function(){
        var alt = false;
        var keys = {};

        $(document).keydown(function (e) {
			
            keys[e.which] = true;
			
            var block = $('#myTab li.active').children('a').attr('href');
            if(block != '#revew_details')
            {	
                if(keys[18] == true && keys[78] == true )
                {
                    keys[18] = false;
                    keys[78] = false;
                    nextTab();
                }
            }
			
            if(block != '#client_details')
            {
                if(keys[18] == true && keys[65] == true){
                    //console.log(keys);
                    keys[18] = false;
                    keys[65] = false;
                    prevTab();
                }
            }			
        });
    });
</script>
<section class="grey-body">
    <div class="container-fluid ">
        <?php echo form_open_multipart(site_url() . 'accountant/accountant/saveClient', 'id="clientForm" name="addClient"'); ?>
        <div class="account_sum tabbable">
            <?php echo $this->session->flashdata('addError'); ?>
            <div class="row">
                <h4 class="col-md-4 pull-left">Add Client</h4>
                <div class="col-md-3 pull-right">
                    <a href="<?php echo site_url() . 'client_listing' ?>" class="btn  btn_grey pull-right">
                        <i class="glyphicon glyphicon-chevron-left"></i>&nbsp;Back to listing
                    </a>
                </div>
            </div>
            <div class="client_panel">
                <ul class="nav" id="myTab" >
                    <li class="active disabled client_details">
                        <a data-toggle="tab" href="#client_details">Client Details</a>
                    </li>
                    <li class="disabled company_details">
                        <a data-toggle="tab" href="#company_details">Company Details</a>
                    </li>
                    <li class="disabled vat_details">
                        <a data-toggle="tab" href="#vat_details">VAT Details</a>
                    </li>
                    <li class="disabled sh_details">
                        <a data-toggle="tab" href="#sh_details">User Details</a>
                    </li>
                    <li class="disabled bnk_details">
                        <a data-toggle="tab" href="#bnk_details">Bank Details</a>
                    </li>
                    <li class="disabled revew_details">
                        <a data-toggle="tab" href="#revew_details">Review Details</a>
                    </li>
                </ul>
            </div>
            <div class="clr"></div>

            <div class="tab-content client_custom">

                <!-- #client_details start here  !-->

                <div id="client_details" class="tab-pane active client_detail">
                   
				   <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>Title</label>
                        </div>
                        <div class="wid-70">
                        <?php 	if(empty($item)) 
									echo salutationList('ClientSalutation');  
								else
									echo salutationList('ClientSalutation', $item['USER']->Params['Salutation']);
						?>
                        </div>
                    </div>
                    <div class="clr"></div>
					
					
					
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>First Name * :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" class="form-control required" placeholder="first name " name="FirstName" id="FirstName" value="<?php echo $item['USER']->FirstName; ?>" />
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>Last Name *  :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" class="form-control required" placeholder="last name" name="LastName" id="LastName" value="<?php echo $item['USER']->LastName; ?>" />
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>Date Of Birth:</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" id="DOB" name="DOB" class="form-control datepicker" placeholder="date of birth" value="<?php if(!empty($item)){ echo cDate($item['USER']->Params['DOB']); } ?>" />
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>NI Number :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="niNumber" id="niNumber" class="form-control niNumber" placeholder="national insurance number" value="<?php echo $item['USER']->Params['NI_NUMBER']; ?>" />
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>UTR :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="utr" id="utr" class="form-control utrnumber" placeholder="unique transaction reference number" maxlength="10" value="<?php echo $item['USER']->Params['UTR']; ?>" />
                        </div>
                    </div>
					<div class="col-md-5  col-md-offset-2  spc_below">
                        <div class="wid-30">
                            <label>Email * :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="email" id="email" class="form-control email required"  placeholder="email address" value="<?php echo $item['USER']->Email; ?>" />
                        </div>
                    </div>
					<div id="client_address">
						<div class="col-md-5 spc_below">
							<div class="wid-30">
								<label>Address line 1 :</label>
							</div>
							<div class="wid-70">
								<input type="text" name="addressOne" id="addressOne" class="form-control" placeholder="address line 1" value="<?php echo $item['USER']->Address; ?>" />
							</div>
						</div>						
						<div class="col-md-5 col-md-offset-2 spc_below">
							<div class="wid-30">
								<label>Address line 2 :</label>
							</div>
							<div class="wid-70">
								<input type="text" name="addressTwo" id="addressTwo" class="form-control" placeholder="address line 2" value="<?php echo $item['USER']->Params['AddressTwo']; ?>" />
							</div>
						</div>
						<div class="clr"></div>
						<div class="col-md-5  spc_below">
							<div class="wid-30">
								<label>Address line 3 :</label>
							</div>
							<div class="wid-70">
								<input type="text" name="addressThree" id="addressThree" class="form-control" placeholder="address line 3" value="<?php echo $item['USER']->Params['AddressThree']; ?>" />
							</div>
						</div>						
						<div class="col-md-5  col-md-offset-2 spc_below">
							<div class="wid-30">
								<label>Postal Code :</label>
							</div>
							<div class="wid-70">
								<input type="text" name="postalCode" id="postalCode" class="form-control postalcode" placeholder=" postal code" value="<?php echo $item['USER']->ZipCode; ?>" />
							</div>
						</div>
					
						<div class="clr"></div>
						<div class="col-md-5 spc_below">
							<div class="wid-30">
								<label>Country:</label>
							</div>
							<div class="wid-70">
								<?php  ?>
							<?php 	if( empty($item) )
										echo genericList($name = 'Country', countries(), '', 'Country');
									else
										echo genericList($name = 'Country', countries(), $item['USER']->Country, 'Country'); 
							?>
							</div>
						</div>
						<div class="col-md-5 col-md-offset-2 spc_below">
							<div class="wid-30">
								<label>Phone No * :</label>
							</div>
							<div class="wid-70">
								<input type="text" name="phoneNo" id="phoneNo" class="form-control phonenumber required" placeholder=" phone number" maxlength="11" value="<?php echo $item['USER']->ContactNo; ?>" />
							</div>
						</div>  
					</div>
                    
                    <!--<div class="clr"></div>
                    <div class="col-md-5  col-md-offset-2 spc_below" style="margin-left:-1px;">
                        <div class="wid-30">
                            <label>Terms and conditions:</label>
                        </div>
                        <div class="wid-70">
                            <div class="browse-file">
                                <input type="file" name="term_conditions" id="term_conditions"  class="filestyle term_conditions" data-buttonName="btn-primary" accept="application/pdf"/>
                                <?php
                                echo 'The Pdf  File should not be greater than 1 mb(Allowed extensions pdf)';
                                ?>
                            </div>
                        </div>
                    </div>-->
                    <div class="clr"></div>
                    <div class="col-md-12">
                        <div class="col-md-1 pull-right">
                            <button type="button" class="btn btn-success nxt-btn addBtn" onclick="javascript:nextTab();" >
                                Next<span class="glyphicon glyphicon-arrow-right"></span>
                            </button>
                        </div>
                        <div class="col-md-1 col-md-offset-5">
                            <button type="submit" class="btn btn-success btn-sm save-detail">
                                <i class="glyphicon glyphicon-floppy-disk"></i>Save
                            </button>
                        </div>
                    </div>

                </div>
                <!-- #client_details ends here  !-->

                <!-- #company_details start here  !-->
                <div class="clr"></div>
                <div id="company_details" class="tab-pane client_detail">
					<div class="sh-cont-company" >
						<div class="sh-contnt-company">
							<h3>Company <span class="label-num">1</span> Details:</h3>
							<div class="border_box">
								<div class="col-md-5 spc_below">
									<div class="wid-30">
										<label>Company Name * :</label>
									</div>
									<div class="wid-70">
										<input type="text" name="CompanyName" id="CompanyName" class="form-control required"    placeholder="company name"/>
									</div>
								</div>
								<div class="col-md-5 col-md-offset-2 spc_below">
									<div class="wid-30">
										<label>Trading name :</label>
									</div>
									<div class="wid-70">
										<input type="text"  name="TradingName" id="TradingName" class="form-control"    placeholder="trading name"/>
									</div>
								</div>
								<div class="clr"></div>
								<div class="col-md-5 spc_below">
									<div class="wid-30">
										<label>Expected Annual (£):</label>
									</div>
									<div class="wid-70">
										<input type="text" name="ExpectedAmount" id="ExpectedAmount" class="form-control validNumber"    placeholder="expected annual salary"/>
									</div>
								</div>
								<div class="col-md-5 col-md-offset-2 spc_below">
									<div class="wid-30">
										<label>Company Type:</label>
									</div>
									<div class="wid-70">
										<?php echo exCategories($type = 'COMP', $name = "CompanyType", ''); ?>
									</div>
								</div>
								<div class="clr"></div>
								<div class="col-md-5 spc_below">
									<div class="wid-30">
										<label>PAYE Reference:</label>
									</div>
									<div class="wid-70">
										<input type="text" name="PayeReference" id="PayeReference" class="form-control PayeReference"    placeholder="paye reference"/>
									</div>
								</div>
								<div class="col-md-5 col-md-offset-2 spc_below">
									<div class="wid-30">
										<label>PAYE Accounts Office Ref:</label>
									</div>
									<div class="wid-70">
										<input type="text" name="PayeAccountReference" id="PayeAccountReference" class="form-control payeref" placeholder="paye accounts office reference number"/ maxlength="13">
									</div>
								</div>
								<div class="clr"></div>
								<div class="col-md-5  spc_below">
									<div class="wid-30">
										<label>Company Reg. No *  :</label>
									</div>
									<div class="wid-70">
										<input type="text" name="CompanyRegisteredNo" id="CompanyRegisteredNo" class="form-control required" placeholder="company registration number " maxlength="8"/>
									</div>
								</div>
								<div class="col-md-5 col-md-offset-2 spc_below">
									<div class="wid-30">
										<label>Corporation Tax Reference :</label>
									</div>
									<div class="wid-70">
										<input type="text" name="TaxReference" id="TaxReference" class="form-control"    placeholder="corporation tax reference "/>
									</div>
								</div>
								<div class="clr"></div>
								<div class="col-md-5  spc_below">
									<div class="wid-30">
										<label>Date of incorporation * :</label>
									</div>
									<div class="wid-70">
										<input type="text" name="IncorporationDate" id="IncorporationDate" class="form-control  datepicker required" placeholder="date of incorporation "/>
									</div>
								</div>
								<div class="col-md-5 col-md-offset-2 spc_below">
									<div class="wid-30">
										<label>Return date :</label>
									</div>
									<div class="wid-70">
										<input type="text" name="ReturnDate" id="ReturnDate" class="form-control datepicker" placeholder="return date "/>
									</div>
								</div>
								<div class="clr"></div>
								<div class="col-md-5  spc_below">
									<div class="wid-30">
										<label>Year-end date * :</label>
									</div>
									<div class="wid-70">
										<input type="text" name="YearEndDate" id="YearEndDate" class="form-control datepicker required"placeholder="year-end date "/>
									</div>
								</div>
								<div class="col-md-5 col-md-offset-2 spc_below">
									<div class="wid-30">
										<label>Fax No :</label>
									</div>
									<div class="wid-70">
										<input type="text" name="FaxNo" id="FaxNo" class="form-control "    placeholder="fax number "/>
									</div>
								</div>
								<div class="clr"></div>
								<div class="col-md-5  spc_below">
									<div class="wid-30">
										<label>Email :</label>
									</div>
									<div class="wid-70">
										<input type="text" name="CompanyEmail" id="CompanyEmail" class="form-control email"    placeholder="email address "/>
									</div>
								</div>
								<div class="col-md-5 col-md-offset-2 spc_below">
									<div class="wid-30">
										<label>Website :</label>
									</div>
									<div class="wid-70">
										<input type="text" name="CompanyWebsite" id="CompanyWebsite" class="form-control "    placeholder="company name"/>
									</div>
								</div>
								<div class="clr"></div>
								<div class="col-md-5  spc_below">
									<div class="wid-30">
										<label>Describe Nature of the Business :</label>
									</div>
									<div class="wid-70">
									<textarea class="form-control" rows="0" name="BussinessDescription" id="BussinessDescription" placeholder="describe nature of the business"></textarea>
									</div>
								</div>
								<div class="col-md-5  col-md-offset-2 spc_below">
									<div class="wid-30">
										<label>Company Logo :</label>
									</div>
									<div class="wid-70">
										<div class="browse-file">
											<input type="file" name="file" id="file" class="filestyle" data-buttonName="btn-primary"accept="image/jpg,image/png,image/jpeg"/>
											<input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
											<?php
											echo sprintf($this->lang->line('CASHMAN_COMPNAY_LOGO_REQUIREMENT'), (LOGO_UPLOAD_FILE_SIZE / 1024));
											?>
										</div>
									</div>
								</div>
								<div class="clr"></div>
								<h3>Registered Address:</h3>
								<div class="col-md-5  spc_below">
									<label>Same as Client Address &nbsp;</label>
									<input type="checkbox" id="copyClientAdd" />
								</div>
								<div class="clr" ></div>
								<div id="reg_address" >
									<div class="col-md-5 spc_below">
										<div class="wid-30">
											<label>Address Line 1 :</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control"  name="CompanyAddOne" id="CompanyAddOne"  placeholder="address"/>
										</div>
									</div>
									<div class="col-md-5 col-md-offset-2 spc_below">
										<div class="wid-30">
											<label>Address Line 2 :</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control"  name="CompanyAddTwo" id="CompanyAddTwo"  placeholder="address"/>
										</div>
									</div>
									<div class="col-md-5 spc_below">
										<div class="wid-30">
											<label>Address Line 3 :</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control" name="CompanyAddThree" id="CompanyAddThree"   placeholder="address"/>
										</div>
									</div>
									<div class="col-md-5 col-md-offset-2 spc_below">
										<div class="wid-30">
											<label>Postal Code :</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control postalcode"  name="CompanyPostalCode" id="CompanyPostalCode"  placeholder="postal code"/>
										</div>
									</div>
									<div class="col-md-5 spc_below">
										<div class="wid-30">
											<label>Country:</label>
										</div>
										<div class="wid-70">
											<?php echo genericList($name = 'CompanyCountry', countries(), '', 'CompanyCountry'); ?>
										</div>
									</div>
									<div class="col-md-5 col-md-offset-2 spc_below">
										<div class="wid-30">
											<label>Phone No :</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control phonenumber"  name="CompanyPhoneNo" id="CompanyPhoneNo"  placeholder="phone number "maxlength="11"/>
										</div>
									</div>
								</div>
								<div class="clr"></div>
								<h3>Contact Address: </h3>
								<div class="col-md-5  spc_below">
									<label>Same as Registered Address &nbsp;</label>
									<input type="checkbox" id="copyRegAdd" />
								</div>
								<div class="clr" ></div>
								<div id="cont_address" >
									<div class="col-md-5 spc_below">
										<div class="wid-30">
											<label>Address Line 1 :</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control"  name="CCAddressOne" id="CCAddressOne"  placeholder="address"/>
										</div>
									</div>
									<div class="col-md-5 col-md-offset-2 spc_below">
										<div class="wid-30">
											<label>Address Line 2 :</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control" name="CCAddressTwo" id="CCAddressTwo"   placeholder="address"/>
										</div>
									</div>
									<div class="col-md-5 spc_below">
										<div class="wid-30">
											<label>Address Line 3 :</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control" name="CCAddressThree" id="CCAddressThree"   placeholder=" address"/>
										</div>
									</div>
									<div class="col-md-5 col-md-offset-2 spc_below">
										<div class="wid-30">
											<label>Postal Code :</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control postalcode"  name="CCpostalcode" id="CCpostalcode"  placeholder="postal code "/>
										</div>
									</div>
									<div class="col-md-5 spc_below">
										<div class="wid-30">
											<label>Country:</label>
										</div>
										<div class="wid-70">
											<?php echo genericList($name = 'CCompanyCountry', countries(), '', 'CCompanyCountry'); ?>
										</div>
									</div>
									<div class="col-md-5 col-md-offset-2 spc_below">
										<div class="wid-30">
											<label>Phone No :</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control phonenumber" name="CCPhoneNo" id="CCPhoneNo" placeholder="phone number "maxlength="11"/>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							<br>
						</div>				
					</div>
					<div class="clr"></div>
                    <div class="col-md-12">						
                        <div class="col-md-1 pull-left">
                            <button type="button" class="btn btn-primary nxt-btn" onclick="javascript:prevTab();" ><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Back</button>
                        </div>
                        <div class="col-md-1 col-md-offset-5 ">
                            <button type="submit" class="btn btn-success nxt-btn save-detail">
                                <i class="glyphicon glyphicon-floppy-disk"></i>Save
                            </button>
                        </div>
                        <div class="col-md-1 pull-right">
                            <button type="button" class="btn btn-success nxt-btn" onclick="javascript:nextTab();" >Next<span class="glyphicon glyphicon-arrow-right"></span></button>
                        </div>
                    </div>
                    <div class="clr"></div>
				</div>
                <div class="clr"></div>
                <!-- #company_details ends here  !-->

                <!-- #vat_details start here  !-->
                <div id="vat_details" class="tab-pane client_detail">
                    <div class="col-md-4 spc_below">
                        <div class="wid-30">
                            <label>Vat Registered :</label>
                        </div>
                        <div class="wid-70">
                            <input id="isVatRegistered" type="checkbox" aria-label="..." value="option1" name="VATRegistred">
                        </div>
                    </div>
					<div class="clr"></div>
                    <div class="col-md-4 spc_below isRegisteredVAT">
                        <div class="wid-30">
                            <label>VAT Quarters * </label>
                        </div>
                        <div class="wid-70">
                            <?php echo vatQuatersDropdown('', 'class="" id="VATQuaters"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 spc_below isRegisteredVAT">
                        <div class="wid-30">
                            <label>Vat Reg. No * :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" class="form-control required" name="VATRegisteredNo" id="VATRegisteredNo" placeholder="vat registration number" maxlength="9"/>
                        </div>
                    </div>                   
                    <div class="col-md-4 spc_below isRegisteredVAT">
                        <div class="wid-30">
                            <label>Vat Reg. Type:</label>
                        </div>
                        <div class="wid-70">
                            <select onchange="javascript:showHideRate(this);" name="VATRegisteredType" id="VATRegisteredType" class="required">
                                <option value="" >Select Rate</option>
                                <option value="flat" >Flat Rate</option>
                                <option value="stand">Standard</option>
                            </select>
                        </div>
                    </div>	
					<div class="clr"></div>
                    <div class="col-md-3 spc_below isRegisteredVAT">
						<div class="wid-40">
							<label>Vat Effective Date *</label>
						</div>
						<div class="wid-60">
							<input type="text"name="VATEffectiveDate" id="VATEffectiveDate" class="form-control datepicker1 required"placeholder="Vat effective date"/>
						</div>
					</div>
					<div class="clr"></div>
                    <div  class="vat-multi hide" >
                        <h3>Flat rate:</h3>
                        <div class="sh-cont-r vat-content1" >
                            <div class="sh-contnt-r">
                                <input type="hidden" name="flat_id" value=""/>
                                <div class="col-md-3 spc_below">
                                    <div class="wid-40">
                                        <label>VAT % *</label>
                                    </div>
                                    <div class="wid-60">
                                        <input type="text" name="VATRatePercent" id="VATRatePercent"class="form-control validNumber required" placeholder=""maxlength="5" />
                                    </div>
                                </div>
                                
								<!--div class="col-md-3 spc_below">
									<div class="wid-40">
										<label>Vat Effective Date *</label>
									</div>
									<div class="wid-60">
										<input type="text"name="VATEffectiveDate" id="VATEffectiveDate"class="form-control datepicker1 required"placeholder="Vat effective date"/>
									</div>
								</div-->
								<div class="col-md-3 spc_below">
									<div class="wid-40">
										<label>First year Discount end date *</label>
									</div>
									<div class="wid-60">
										<input type="text"name="VATEndDate" id="VATEndDate"class="form-control datepicker required"placeholder="first year discount end date"/>
									</div>
								</div>
																
                                <div class="col-md-3 spc_below">
                                    <div class="wid-40">
                                        <label>First year Discounted rate *</label>
                                    </div>
                                    <div class="wid-60">
                                        <input type="text" name="VATRatePercentAfterYear" id="VATRatePercentAfterYear"class="form-control" placeholder="first year discount rate" maxlength="5" readonly />
                                    </div>
                                </div>
                                <div class="clr"></div>
                                <br/>
                            </div>	
                            <div class="clr"></div>
                        </div>
                        <br/>
                        <div class="clr"></div>
                    </div>
                    <div  class="standard-vat hide" >
                        <h3>Standard rate:</h3>
                        <div class="col-md-4 spc_below">
                            <input type="hidden" name="stand_id" value=""/>
                            <div class="wid-30">
                                <label>Rate in percent * </label>
                            </div>
                            <div class="wid-70">
                                <input type="text" name="VATStanderedRate" id="VATStanderedRate" class="form-control validateNumber required"placeholder="rate in percent" maxlength="5" value="20" readonly/>
                            </div>
                        </div>
						<!--div class="col-md-3 spc_below">
							<div class="wid-40">
								<label>Vat Effective Date *</label>
							</div>
							<div class="wid-60">
								<input type="text"name="VATEffectiveDate" id="VATEffectiveDate" class="form-control datepicker1 required"placeholder="Vat effective date"/>
							</div>
						</div-->
                    </div>
                    <div class="clr"></div>
					<div class="col-md-4 spc_below">
                        <div class="wid-30">
                            <label>CIS Registered :</label>
                        </div>
                        <div class="wid-70">
                            <input id="isCISRegistered" type="checkbox" value="yes" name="isCISRegistered" >
                        </div>
                    </div>
					<div class="col-md-4 isCISRegisteredDiv" style="display:none">
                        <div class="wid-40">
                            <label>CIS % </label>
                        </div>
                        <div class="wid-60">
                            <input type="text" class="form-control" name="cis_percentage" id="cis_percentage" placeholder="" value="" maxlength="3"/>
                        </div>
                    </div>					
					 <div class="clr"></div>
                    <div class="col-md-1 pull-left">
                        <button type="button" class="btn btn-primary nxt-btn" onclick="javascript:prevTab();" ><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Back</button>
                    </div>
                    <div class="col-md-1 col-md-offset-5 ">
                        <button type="submit" class="btn btn-success nxt-btn save-detail">
                            <i class="glyphicon glyphicon-floppy-disk"></i>Save
                        </button>
                    </div>
                    <div class="col-md-1 pull-right">
                        <button type="button" id="saa" class="btn btn-success nxt-btn" onclick="javascript:nextTab(); " >Next<span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>

                <!-- #vat_details ends here  !-->

                <!-- #sh_details start here  !-->
                <div id="sh_details" class="tab-pane client_detail">
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>Total Shares :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" class="form-control checkField" class="TotalShares" id="TotalShares" placeholder="total shares" name="TotalShares"/>
                        </div>
                    </div>
                    <div class="clr" ></div>
                    <div id="dir1" class="sh-contnt">
                        <h3>User - 1 (Director Details)</h3>
                        <div class="border_box spc_below">
							<div class="col-md-2 spc_below">
								<div class="wid-60">
									<label>Director?</label>
								</div>
								<div class="wid-10">
									<!-- input type="hidden" name="IsDirector[]" value="0" -->
									<input type="checkbox" name="IsDirector[1]" value="<?php echo random_string('alnum', 3); ?>" class="isDirector" checked>
								</div>
							</div>
							<div class="col-md-4 spc_below siblingsDiv">
								<div class="wid-30">
									<label>Employee?</label>
								</div>
								<div class="wid-30">
									<input type="checkbox" name="IsEmployee[1]" class="isEmployee" value="NEW">
								</div>
								<div class="wid-30">
									<label>Shareholder?</label>
								</div>
								<div class="wid-10">
									<input type="checkbox" name="IsShareholder[1]" class="IsShareholder" value="NEW">
								</div>
							</div>
                            <div class="clr"></div>
                            <div class="col-md-5 spc_below">
                                <div class="wid-30">
                                    <label>Title</label>
                                </div>
                                <div class="wid-70">
                                    <?php echo salutationList('directorSalutation'); ?>
                                </div>
                            </div>
                            <div class="col-md-5 spc_below col-md-offset-2">
                                <div class="wid-30 ">
                                    <label>No. of Shares</label>
                                </div>
                                <div class="wid-70 ">
                                    <input type="text" name="DirectorShares" id="DirectorShares" class="form-control checkField calShares" placeholder="number of shares"/>
                                </div>
                            </div>
                            <div class="clr"></div>
                            <div class="col-md-5 spc_below">
                                <div class="wid-30">
                                    <label>First Name :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DFirstName" id="DFirstName" readonly class="form-control"    placeholder="first name"/>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-2 spc_below">
                                <div class="wid-30">
                                    <label>Last Name :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DLastName" id="DLastName" readonly class="form-control"    placeholder="last name"/>
                                </div>
                            </div>
                            <div class="col-md-5 spc_below">
                                <div class="wid-30">
                                    <label>Date Of Birth:</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="Ddob" id="Ddob"class="form-control datepicker"placeholder="date of birth"/>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-2 spc_below">
                                <div class="wid-30">
                                    <label>NI Number :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DNINumber" id="DNINumber" readonly class="form-control niNumber"placeholder="national insurance number"/>
                                </div>
                            </div>
                            <div class="clr"></div>
                            <div class="col-md-5 spc_below">
                                <div class="wid-30">
                                    <label>UTR :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DUTR" id="DUTR" readonly class="form-control utrnumber" placeholder="unique transaction reference number "maxlength="10"/>
                                </div>
                            </div>
							<div class="col-md-5 col-md-offset-2 spc_below">
                                <div class="wid-30">
                                    <label>Email :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DEmail" id="DEmail" readonly class="form-control"    placeholder="email"/>
                                </div>
                            </div>
							<div class="clr"></div>
                            <div class="col-md-5  spc_below">
                                <div class="wid-30">
                                    <label>Address line 1 :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DAddressOne" id="DAddressOne" readonly class="form-control"    placeholder="address"/>
                                </div>
                            </div>
                            <div class="col-md-5  col-md-offset-2 spc_below">
                                <div class="wid-30">
                                    <label>Address line 2 :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DAddressTwo" id="DAddressTwo" readonly class="form-control"    placeholder="address"/>
                                </div>
                            </div>
							<div class="clr"></div>
                            <div class="col-md-5  spc_below">
                                <div class="wid-30">
                                    <label>Address line 3 :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DAddressThree" id="DAddressThree" readonly class="form-control"    placeholder=" address"/>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-2 spc_below">
                                <div class="wid-30">
                                    <label>Postal Code :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DPostalCode" id="DPostalCode" readonly class="form-control postalcode"placeholder="postal code"/>
                                </div>
                            </div>
							<div class="clr"></div>
							<div class="col-md-5  spc_below">
                                <div class="wid-30">
                                    <label>Country:</label>
                                </div>
                                <div class="wid-70">
                                    <?php echo genericList($name = 'DCountry', countries(), '', 'DCountry readonly'); ?>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-2 spc_below">
                                <div class="wid-30">
                                    <label>Phone No:</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DPhoneNo" id="DPhoneNo" readonly class="form-control phonenumber"placeholder="phone number" maxlength="11"/>
                                </div>
                            </div>
                            <div class="clr"></div>
                        </div>

                    </div>
                    <div class="sh-cont" >
                        <div class="sh-contnt">
                            <br/><br/>
                            <h3>User <span class="label-num">2</span> Details:</h3>
                            <div class="border_box">
                                <div class="col-md-2 spc_below">
                                    <div class="wid-60">
                                        <label>Director?</label>
                                    </div>
                                    <div class="wid-10">
                                        <!-- input type="hidden" name="IsDirector[]" value="0" -->
                                        <input type="checkbox" name="IsDirector[2]" value="<?php echo random_string('alnum', 3); ?>" class="isDirector">
                                    </div>
                                </div>
                                <div class="col-md-4 spc_below siblingsDiv">
                                    <div class="wid-30">
                                        <label>Employee?</label>
                                    </div>
                                    <div class="wid-30">
                                        <input type="checkbox" name="IsEmployee[2]" class="isEmployee" value="NEW">
                                    </div>
                                    <div class="wid-30">
                                        <label>Shareholder?</label>
                                    </div>
                                    <div class="wid-10">
                                        <input type="checkbox" name="IsShareholder[2]" class="IsShareholder" value="NEW">
                                    </div>
                                </div>
                                <div class="clr"></div>
                                <div class="col-md-5 spc_below">
                                    <div class="wid-30">
                                        <label>Title</label>
                                    </div>
                                    <div class="wid-70">
                                        <?php echo salutationList('salutation[]'); ?>
                                    </div>
                                </div>
                                <div class="col-md-5 col-md-offset-2 spc_below">
                                    <div class="wid-30">
                                        <label>No. of Shares</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="ShareHolderShares[]" class="form-control checkField calShares"placeholder="number of shares" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="clr"></div>
                                <div class="col-md-5 spc_below">
                                    <input type="hidden"name="share_holder_id[]" value=""/>
                                    <div class="wid-30">
                                        <label>First Name :</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="SFirstName[]" disabled class="form-control"    placeholder="first name"/>
                                    </div>
                                </div>
                                <div class="col-md-5 col-md-offset-2 spc_below">
                                    <div class="wid-30">
                                        <label>Last Name :</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="SLastName[]" disabled class="form-control"    placeholder="last name"/>
                                    </div>
                                </div>
                                <div class="col-md-5 spc_below">
                                    <div class="wid-30">
                                        <label>Date Of Birth:</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="SDOB[]"disabled class="form-control datepicker"placeholder="date of birth"/>
                                    </div>
                                </div>
                                <div class="col-md-5 col-md-offset-2 spc_below">
                                    <div class="wid-30">
                                        <label>NI Number :</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="SNINumber[]"disabled class="form-control niNumber" placeholder="national insurance number"/>
                                    </div>
                                </div>
                                <div class="clr"></div>
                                <div class="col-md-5 spc_below">
                                    <div class="wid-30">
                                        <label>UTR :</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="SUTR[]" disabled class="form-control utrnumber" placeholder="unique transaction reference number"maxlength="10"/>
                                    </div>
                                </div>
								<div class="col-md-5 col-md-offset-2 spc_below">
                                    <div class="wid-30">
                                        <label>Email :</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="SEmail[]"disabled class="form-control email"    placeholder="email"/>
                                    </div>
                                </div>
								<div class="clr"></div>
                                <div class="col-md-5 spc_below">
                                    <div class="wid-30">
                                        <label>Address line 1 :</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="SAddressOne[]" disabled class="form-control"    placeholder="address"/>
                                    </div>
                                </div>                                
                                <div class="col-md-5 col-md-offset-2 spc_below">
                                    <div class="wid-30">
                                        <label>Address line 2 :</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="SAddressTwo[]" disabled class="form-control"    placeholder="address"/>
                                    </div>
                                </div>
								<div class="clr"></div>
                                <div class="col-md-5  spc_below">
                                    <div class="wid-30">
                                        <label>Address line 3 :</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="SAddressThree[]" disabled class="form-control"    placeholder="address "/>
                                    </div>
                                </div>
                                <div class="col-md-5 col-md-offset-2 spc_below">
                                    <div class="wid-30">
                                        <label>Postal Code :</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="SPostalCode[]" disabled class="form-control postalcode"    placeholder="postal code"/>
                                    </div>
                                </div>
								<div class="clr"></div>
								<div class="col-md-5  spc_below">
                                    <div class="wid-30">
                                        <label>Country:</label>
                                    </div>
                                    <div class="wid-70">
                                        <?php echo genericList($name = 'SCountry[]', countries(), '', ' readonly'); ?>
                                    </div>
                                </div>
                                <div class="col-md-5 col-md-offset-2 spc_below">
                                    <div class="wid-30">
                                        <label>Phone No:</label>
                                    </div>
                                    <div class="wid-70">
                                        <input type="text" name="SPhoneNumber[]" disabled class="form-control phonenumber"placeholder="phone number" maxlength="11"/>
                                    </div>
                                </div>                                
                               
                                <div class="clr"></div>

                            </div>
                            <div class="clr"></div><br/>
                            <button type="button" class="pull-right btn btn-danger removeBtn hide">Remove</button>
                        </div>
                        <button type="button" class="pull-left btn btn-success addBtn">Add New User</button>	
                        <div class="clr"></div>
                    </div>
                    <br/>
                    <div class="clr"></div>
                    <div class="col-md-1 pull-left">
                        <button type="button" class="btn btn-primary nxt-btn" onclick="javascript:prevTab();" ><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Back</button>
                    </div>
                    <div class="col-md-1 col-md-offset-5">
                        <button type="submit" class="btn btn-success nxt-btn save-detail">
                            <i class="glyphicon glyphicon-floppy-disk"></i>Save
                        </button>
                    </div>
                    <div class="col-md-1 pull-right">
                        <button type="button" class="btn btn-success nxt-btn" onclick="javascript:nextTab();" >Next<span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>

                <!-- #sh_details ends here  !-->

                <!-- #bnk_details start here  !-->

                <div id="bnk_details" class="tab-pane client_detail">
					<div class="bank-contnt">
						<h3>Bank <span class="label-num">1</span> Details:</h3>
						<div class="border_box">
							<div class="col-md-5 spc_below">
							<input type="hidden" name="bank_id[]" value=""/>
								<div class="wid-30">
									<label>Bank Name:</label>
								</div>
								<div class="wid-70">
									<input type="text" name="BankName[]" id="BankName" class="form-control"  placeholder="bank name"/>
								</div>
							</div>
							<div class="col-md-5 col-md-offset-2 spc_below">
								<div class="wid-30">
									<label>Sort Code :</label>
								</div>
								<div class="wid-70">
									<input type="text" class="form-control" name="ShortCode[]" id="ShortCode" placeholder="sort code" maxlength="6"/>
								</div>
							</div>
							<div class="col-md-5 spc_below">
								<div class="wid-30">
									<label>Account Number:</label>
								</div>
								<div class="wid-70">
									<input type="text" class="form-control"  name="AccountNumber[]" id="AccountNumber" placeholder="account number" maxlength="8"/>
								</div>
							</div>
							<div class="clr"></div>
						</div>	
					</div>					
                    <div class="clr"></div>
					<div class="bank-cont" >
						<div class="bank-contnt">
							<h3>Bank <span class="label-num">2</span> Details:</h3>
							<div class="border_box">
									<div class="col-md-5 spc_below">
									<input type="hidden" name="bank_id[]" value=""/>
										<div class="wid-30">
											<label>Bank Name:</label>
										</div>
										<div class="wid-70">
											<input type="text" name="BankName[]" id="BankName" class="form-control"  placeholder="bank name"/>
										</div>
									</div>
									<div class="col-md-5 col-md-offset-2 spc_below">
										<div class="wid-30">
											<label>Sort Code :</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control" name="ShortCode[]" id="ShortCode" placeholder="sort code" maxlength="6"/>
										</div>
									</div>
									<div class="col-md-5 spc_below">
										<div class="wid-30">
											<label>Account Number:</label>
										</div>
										<div class="wid-70">
											<input type="text" class="form-control"  name="AccountNumber[]" id="AccountNumber" placeholder="account number" maxlength="8"/>
										</div>
									</div>								
									<div class="clr"></div>								
							</div><br><button type="button" class="pull-right btn btn-danger removeBtnBank hide">Remove</button>
						</div>
						<button type="button" class="pull-left btn btn-success addBtnBank">Add New Bank</button>	
						<div class="clr"></div>
					</div>
                    <div class="col-md-1 pull-left">
                        <button type="button" class="btn btn-primary nxt-btn" onclick="javascript:prevTab();" ><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Back</button>
                    </div>
                    <div class="col-md-1 col-md-offset-5">
                        <button type="submit" class="btn btn-success nxt-btn save-detail">
                            <i class="glyphicon glyphicon-floppy-disk"></i>Save
                        </button>
                    </div>
                    <div class="col-md-1 pull-right">
                        <button type="button" class="btn btn-success nxt-btn" onclick="javascript:nextTab();" >Next<span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                    <div class="clr"></div>
                </div>

                <!-- #bnk_details ends here  !-->

                <!-- #revew_details start here  !-->
                <div id="revew_details" class="tab-pane client_detail">
                    <div id="review-detail"></div>
                    <div class="clr"></div><br/><br/>
                    <div class="col-md-4 spc_below">
                        <div class="wid-70">
                            <label>Monthly Fee (Including VAT):</label>
                        </div>
                        <div class="wid-30">
                            <input type="text" class="form-control" name="CompanyMonthlyFee" placeholder=""/>
                        </div>
                    </div>
                    <div class="col-md-5 spc_below">
                        <div class="col-md-6">
                            <label>Relationship Manager</label>
                        </div>
                        <div class="col-md-6">
                            <?php echo form_dropdown('relationship_manager', accountant_list(), 0, 'form-control'); ?>
                        </div>
                    </div>
                    <div class="col-md-3 spc_below">
                        <div class="wid-70">
                            <label>SI Start Date:</label>
                        </div>
                        <div class="wid-30">
                            <input type="text" class="form-control datepicker" name="CompanySIDate" id="sdi"placeholder="start date"/>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <br/><br/><br/>
                    <div class="col-md-12 pull-right">
                        <div class="col-md-3  pull-left">
                            <button type="button" class="btn btn-primary" onclick="javascript:prevTab();" >
                                <span class="glyphicon glyphicon-arrow-left"></span>
                                &nbsp;Back
                            </button>
                        </div>
                        <div class="col-md-1  pull-right">
                            <a href="<?php echo site_url();
                            'client_listing'
                            ?>" class="btn btn-danger">
                                Cancel 
                            </a>				
                        </div>
                        <div class="col-md-4  pull-right actionButtons">
                            <button type="submit" class="btn btn-success addnewCompany" name="addnewcompany" style="margin-right:8%">
                                Create & Add new company
                            </button>
															
							<button type="submit" class="btn btn-success" name="createandfinish">
								<?php if( $this->session->userdata('lastAddedClientId') != '' && $this->session->userdata('lastAddedClientId') > 0 ){ ?>Finish<?php } else {?>Create & Finish<?php } ?>
                            </button>							
                        </div>
                        <!--div class="col-md-1  pull-right actionButtons">
                                <a class="btn btn-info" href="#" id="createClient">
                                        &nbsp;Create 
                                </a>
                        </div-->
                    </div>
                    <div class="clr"></div>
                </div>

                <!-- #revew_details ends here  !-->
            </div>

        </div>
        <input type="hidden"name="director_id" value=""/>
        <input type="hidden" name="bank_id" value=""/>
        <input type="hidden" name="client_id" value=""/>
        <input type="hidden" name="company_id" value=""/>
<?php echo form_close(); ?>
    </div>
</section>
<div id="dialog"></div>
<?php $this->load->view('accountant/client_js'); ?>
<?php $this->load->view('accountant/footer'); ?>