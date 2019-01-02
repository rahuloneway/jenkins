<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view('accountant/header', array('page' => $page, 'title' => $title)); ?>
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
        <?php echo form_open_multipart(site_url() . 'accountant/accountant/review', 'id="updateclientForm"'); ?>
        <div class="account_sum tabbable">
            <?php echo $this->session->flashdata('addError'); ?>
            <div class="row">
                <h4 class="col-md-4 pull-left">Update Client</h4>
                <div class="col-md-4 pull-right">
                    <?php if ($item['USER']->Activation == 1 && $item['USER']->Status == 1): ?>
                        <a href="<?php echo site_url() . 'accountant/accountant/changeStatus/' . $this->encrypt->encode('ACTION_DISABLE/' . $item['USER']->ID); ?>" class="btn btn-primary btn-sm  pull-right disable">
                            <i class="fa fa-minus-square"></i>Disable
                        </a>
                    <?php elseif ($item['USER']->Activation == 1 && $item['USER']->Status == 0): ?>
                        <a href="<?php echo site_url() . 'accountant/accountant/changeStatus/' . $this->encrypt->encode('ACTION_ENABLE/' . $item['USER']->ID); ?>" class="btn btn-success btn-sm pull-right enable">
                            <i class="fa fa-plus-square"></i>Enable
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo site_url() . 'client_listing' ?>" class="btn btn_grey btn-sm pull-right" style="margin-right: 5px;">
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
                            <?php echo salutationList('ClientSalutation', $item['USER']->Params['Salutation']); ?>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>First Name :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" class="form-control required"placeholder="" name="FirstName" id="FirstName" value="<?php echo $item['USER']->FirstName; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>Last Name :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" class="form-control required"placeholder="" name="LastName" id="LastName" value="<?php echo $item['USER']->LastName; ?>" />
                        </div>
                    </div>
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>Date Of Birth:</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" id="DOB" name="DOB" class="form-control datepicker" placeholder="" value="<?php echo cDate($item['USER']->Params['DOB']); ?>" />
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>NI Number :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="niNumber" id="niNumber" class="form-control niNumber" placeholder="" value="<?php echo $item['USER']->Params['NI_NUMBER']; ?>" />
                        </div>
                    </div>
                    <div class="clr"></div><br/>
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>UTR :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="utr" id="utr" class="form-control utrnumber" placeholder="" value="<?php echo $item['USER']->Params['UTR']; ?>" maxlength="10"/>
                        </div>
                    </div>
					<div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>Email :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="email" id="email" class="form-control email required" placeholder="" value="<?php echo $item['USER']->Email; ?>" />
                        </div>
                    </div>
					<div id="client_address">
						<div class="col-md-5  spc_below">
							<div class="wid-30">
								<label>Address line 1 :</label>
							</div>
							<div class="wid-70">
								<input type="text" name="addressOne" id="addressOne" class="form-control" placeholder="" value="<?php echo $item['USER']->Address; ?>"/>
							</div>
						</div>						
						<div class="col-md-5  col-md-offset-2 spc_below">
							<div class="wid-30">
								<label>Address line 2 :</label>
							</div>
							<div class="wid-70">
								<input type="text" name="addressTwo" id="addressTwo" class="form-control" placeholder="" value="<?php echo $item['USER']->Params['AddressTwo']; ?>"/>
							</div>
						</div>
						<div class="clr"></div>
						<div class="col-md-5  spc_below">
							<div class="wid-30">
								<label>Address line 3 :</label>
							</div>
							<div class="wid-70">
								<input type="text" name="addressThree" id="addressThree" class="form-control" placeholder="" value="<?php echo $item['USER']->Params['AddressThree']; ?>" />
							</div>
						</div>
						<div class="col-md-5 col-md-offset-2 spc_below">
							<div class="wid-30">
								<label>Postal Code :</label>

							</div>
							<div class="wid-70">
								<input type="text" name="postalCode" id="postalCode" class="form-control postalcode" placeholder=" "  value="<?php echo $item['USER']->ZipCode; ?>"/>
							</div>
						</div>
						<div class="clr"></div>
						<div class="col-md-5 spc_below">
							<div class="wid-30">
								<label>Country:</label>
							</div>
							<div class="wid-70">
								<?php echo genericList($name = 'Country', countries(), $item['USER']->Country, 'Country'); ?>
							</div>
						</div>
						<div class="col-md-5 col-md-offset-2 spc_below">
							<div class="wid-30">
								<label>Phone No:</label>
							</div>
							<div class="wid-70">
								<input type="text" name="phoneNo" id="phoneNo" class="form-control required phonenumber" placeholder="" value="<?php echo $item['USER']->ContactNo; ?>" maxlength="11"/>
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
                    <div class="col-md-4 col-md-offset-5 actionButtons">
                        <a href="#"class="btn btn-success update nxt-btn">
                            <i class="glyphicon glyphicon-floppy-disk"></i>
                            <?php if ($item['USER']->Activation == 1): ?>
                                Update
                            <?php else: ?>
                                Save
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="col-md-1 pull-right">
                        <button type="button" class="btn btn-success next-btn nxt-btn" onclick="javascript:nextTab();" >Next<span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                </div>
                <!-- #client_details ends here  !-->

                <!-- #company_details start here  !-->
                <div class="clr"></div>
                <div id="company_details" class="tab-pane client_detail">
				
					<div class="col-md-12 spc_below" style="border-bottom: 1px solid #ccc;margin-bottom:20px">
                        <div class="wid-10">
                            <label>Select Company : </label>
                        </div>
                        <div class="wid-30">
                           <?php echo form_dropdown('company', $allCompanies, $item['COMPANY']->CID, 'id="CompanyList" class=form-control'); ?>
                        </div>
                    </div>
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>Company Name:</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="CompanyName" id="CompanyName" class="form-control required" placeholder="" value="<?php echo $item['COMPANY']->Name; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>Trading name :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="TradingName" id="TradingName" class="form-control" placeholder="" value="<?php echo $item['COMPANY']->TradingName; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>Expected Annual (£):</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="ExpectedAmount" id="ExpectedAmount" class="form-control validNumber"placeholder=""value="<?php echo $item['COMPANY']->AnnualAmount; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>Company Type:</label>
                        </div>
                        <div class="wid-70">
                            <?php echo exCategories($type = 'COMP', $name = "CompanyType", $item['COMPANY']->CompanyType); ?>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-md-5 spc_below">
                        <div class="wid-30">
                            <label>PAYE Reference:</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="PayeReference" id="PayeReference" class="form-control PayeReference" placeholder="" value="<?php echo $item['COMPANY']->PayeReference; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>PAYE Accounts Office Ref:</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="PayeAccountReference" id="PayeAccountReference" class="form-control payeref" placeholder="" value="<?php echo $item['COMPANY']->PayeAcountReference; ?>" maxlength="13"/>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-md-5  spc_below">
                        <div class="wid-30">
                            <label>Company Reg. No :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="CompanyRegisteredNo" id="CompanyRegisteredNo" class="form-control required check_value" placeholder=" " value="<?php echo $item['COMPANY']->RegistrationNo; ?>" maxlength="8"/>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>Corporation Tax Reference :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="TaxReference" id="TaxReference" class="form-control" placeholder=" " value="<?php echo $item['COMPANY']->TaxReference; ?>"/>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-md-5  spc_below">
                        <div class="wid-30">
                            <label>Date of incorporation :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="IncorporationDate" id="IncorporationDate" class="form-control datepicker"placeholder=" "value="<?php echo cDate($item['COMPANY']->IncorporationDate); ?>"/>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>Return date :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="ReturnDate" id="ReturnDate" class="form-control datepicker" placeholder=" "value="<?php echo cDate($item['COMPANY']->ReturnDate); ?>"/>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-md-5  spc_below">
                        <div class="wid-30">
                            <label>Year-end date:</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="YearEndDate" id="YearEndDate" class="form-control datepicker required"    placeholder=" "value="<?php echo cDate($item['COMPANY']->EndDate); ?>"/>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>Fax No :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="FaxNo" id="FaxNo" class="form-control " placeholder=" "value="<?php echo $item['COMPANY']->FaxNumber; ?>"/>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-md-5  spc_below">
                        <div class="wid-30">
                            <label>Email:</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="CompanyEmail" id="CompanyEmail" class="form-control email" placeholder=" "value="<?php echo $item['COMPANY']->Email; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-5 col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>Website :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" name="CompanyWebsite" id="CompanyWebsite" class="form-control " placeholder=" " value="<?php echo $item['COMPANY']->Website; ?>"/>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-md-5  spc_below">
                        <div class="wid-30">
                            <label>Describe Nature of the Business :</label>
                        </div>
                        <div class="wid-70">
                            <textarea class="form-control" rows="0" name="BussinessDescription" id="BussinessDescription"><?php echo $item['COMPANY']->Description; ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-5  col-md-offset-2 spc_below">
                        <div class="wid-30">
                            <label>Company Logo : </label>
                        </div>
                        <div class="wid-70">
                            <div class="browse-file">
                                <input type="file" name="file" id="file" class="filestyle" data-buttonName="btn-primary"accept="image/jpg,image/png,image/jpeg"/>
                                <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
                            </div>
                            <br/>
                            <input type="hidden" name="image_link" value="<?php echo $item['COMPANY']->Params['LogoLink']; ?>"/>
                            <?php
                            echo sprintf($this->lang->line('CASHMAN_COMPNAY_LOGO_REQUIREMENT'), (LOGO_UPLOAD_FILE_SIZE / 1024));
                            ?>
                        </div>
                        <div class="clr"></div><br/>
                        <?php if (isset($item['COMPANY']->Params['LogoLink']) && !empty($item['COMPANY']->Params['LogoLink'])): ?>
                            <div class="logo_image">
                                <img src="<?php echo site_url() . $item['COMPANY']->Params['LogoLink']; ?>" width="120px" height="60px"/>
                                <a class="btn btn-danger btn-xs pull-right delteImage" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_DELETE_IMAGE'); ?>" href="<?php echo $this->encrypt->encode($item['USER']->ID); ?>">
                                    <i class="fa fa-close"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="clr"></div>
                    <h3>Registered Address:</h3>
					<div class="col-md-5  spc_below">
                        <label>Same as Client Address &nbsp;</label>
                        <input type="checkbox" id="copyClientAdd" />
                    </div>
					 <div class="clr" ></div>
                    <div id="reg_address">
                        <div class="col-md-5 spc_below">
                            <div class="wid-30">
                                <label>Address Line 1 :</label>
                            </div>
                            <div class="wid-70">
                                <input type="text" class="form-control"  name="CompanyAddOne" id="CompanyAddOne"  placeholder="" value="<?php echo $item['COMPANY']->Params['REG_AddressOne']; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-5 col-md-offset-2 spc_below">
                            <div class="wid-30">
                                <label>Address Line 2 :</label>
                            </div>
                            <div class="wid-70">
                                <input type="text" class="form-control"  name="CompanyAddTwo" id="CompanyAddTwo"  placeholder=""value="<?php echo $item['COMPANY']->Params['REG_AddressTwo']; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-5 spc_below">
                            <div class="wid-30">
                                <label>Address Line 3 :</label>
                            </div>
                            <div class="wid-70">
                                <input type="text" class="form-control" name="CompanyAddThree" id="CompanyAddThree"   placeholder=" "value="<?php echo $item['COMPANY']->Params['REG_AddressThree']; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-5 col-md-offset-2 spc_below">
                            <div class="wid-30">
                                <label>Postal Code :</label>
                            </div>
                            <div class="wid-70">
                                <input type="text" class="form-control postalcode"  name="CompanyPostalCode" id="CompanyPostalCode"  placeholder=" "value="<?php echo $item['COMPANY']->Params['REG_PostalCode']; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-5 spc_below">
                            <div class="wid-30">
                                <label>Country:</label>
                            </div>
                            <div class="wid-70">
                                <?php echo genericList($name = 'CompanyCountry', countries(), $item['COMPANY']->Params['REG_Country'], 'CompanyCountry'); ?>
                            </div>
                        </div>
                        <div class="col-md-5 col-md-offset-2 spc_below">
                            <div class="wid-30">
                                <label>Phone No :</label>
                            </div>
                            <div class="wid-70">
                                <input type="text" class="form-control phonenumber"  name="CompanyPhoneNo" id="CompanyPhoneNo"  placeholder=" "value="<?php echo $item['COMPANY']->Params['REG_PhoneNo']; ?>"maxlength="11"/>
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
                                <input type="text" class="form-control"  name="CCAddressOne" id="CCAddressOne"  placeholder=""value="<?php echo $item['COMPANY']->Params['CON_AddressOne']; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-5 col-md-offset-2 spc_below">
                            <div class="wid-30">
                                <label>Address Line 2 :</label>
                            </div>
                            <div class="wid-70">
                                <input type="text" class="form-control" name="CCAddressTwo" id="CCAddressTwo"   placeholder="" value="<?php echo $item['COMPANY']->Params['CON_AddressTwo']; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-5 spc_below">
                            <div class="wid-30">
                                <label>Address Line 3 :</label>
                            </div>
                            <div class="wid-70">
                                <input type="text" class="form-control" name="CCAddressThree" id="CCAddressThree"   placeholder=" "value="<?php echo $item['COMPANY']->Params['CON_AddressThree']; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-5 col-md-offset-2 spc_below">
                            <div class="wid-30">
                                <label>Postal Code :</label>
                            </div>
                            <div class="wid-70">
                                <input type="text" class="form-control postalcode"  name="CCpostalcode" id="CCpostalcode"  placeholder=" "value="<?php echo $item['COMPANY']->Params['CON_PostalCode']; ?>"/>
                            </div>
                        </div>
                        <div class="col-md-5 spc_below">
                            <div class="wid-30">
                                <label>Country:</label>
                            </div>
                            <div class="wid-70">
                                <?php echo genericList($name = 'CCompanyCountry', countries(), $item['COMPANY']->Params['CON_Country'], 'CCompanyCountry'); ?>
                            </div>
                        </div>
                        <div class="col-md-5 col-md-offset-2 spc_below">
                            <div class="wid-30">
                                <label>Phone No :</label>
                            </div>
                            <div class="wid-70">
                                <input type="text" class="form-control phonenumber" name="CCPhoneNo" id="CCPhoneNo" placeholder="" value="<?php echo $item['COMPANY']->Params['CON_PhoneNo']; ?>"maxlength="11"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-1 pull-left">
                            <button type="button" class="btn btn-primary nxt-btn" onclick="javascript:prevTab();" ><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Back</button>
                        </div>
                        <div class="col-md-4 col-md-offset-5 actionButtons">
                            <a href="#"class="btn btn-success update nxt-btn">
                                <i class="glyphicon glyphicon-floppy-disk"></i>
                                <?php if ($item['USER']->Activation == 1): ?>
                                    Update
                                <?php else: ?>
                                    Save
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="col-md-1 pull-right">
                            <button type="button" class="btn btn-success next-btn nxt-btn" onclick="javascript:nextTab();" >Next<span class="glyphicon glyphicon-arrow-right"></span></button>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>


                <!-- #company_details ends here  !-->

                <!-- #vat_details start here  !-->
                <div id="vat_details" class="tab-pane client_detail">
                    <div class="col-md-4 ">
                        <div class="wid-30">
                            <label>Vat Registered :</label>
                        </div>
                        <div class="wid-70">
                            <?php
                            if (!empty($item['COMPANY']->Params['VATRegistrationNo'])) {
                                $checked = 'checked="true"';
                            } else {
                                $checked = "";
                            }
                            ?>
                            <input id="isVatRegistered" type="checkbox"value="option1" name="VATRegistred" <?php echo $checked; ?>>
                        </div>
                    </div>
                    <div class="col-md-4 spc_below isRegisteredVAT">
                        <div class="wid-30">
                            <label>VAT Quarters</label>
                        </div>
                        <div class="wid-70">
                            <?php echo vatQuatersDropdown($item['COMPANY']->Params['VATQuaters'], 'class="" id="VATQuaters"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 spc_below isRegisteredVAT">
                        <div class="wid-30">
                            <label>Vat Reg. No :</label>
                        </div>
                        <div class="wid-70">
                            <input type="text" class="form-control required" name="VATRegisteredNo" id="VATRegisteredNo" placeholder="" value="<?php echo $item['COMPANY']->Params['VATRegistrationNo']; ?>" maxlength="9"/>
                        </div>
                    </div>
                    <div class="col-md-4 spc_below isRegisteredVAT">
                        <div class="wid-30">
                            <label>Vat Reg. Type:</label>
                        </div>
                        <div class="wid-70">
                            <?php
                            if ($item['VAT']->Type == 'flat') {
                                $flat = "selected='selected'";
                                $stand = '';
                                $select = '';
                            } elseif ($item['VAT']->Type == 'stand') {
                                $stand = "selected='selected'";
                                $flat = '';
                                $select = '';
                            } else {
                                $stand = '';
                                $flat = '';
                                $select = "selected='selected'";
                            }
                            ?>
                            <select onchange="javascript:showHideRate(this);" name="VATRegisteredType" id="VATRegisteredType"class="required">
                                <option value="" <?php echo $select; ?>>Select Rate</option>
                                <option value="flat" <?php echo $flat; ?>>Flat Rate</option>
                                <option value="stand"<?php echo $stand; ?>>Standard</option>
                            </select>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div  class="vat-multi hide" >
                        <h3>Flat rate:</h3>
                        <div class="sh-cont-r vat-content1" >	
                            <div class="sh-contnt-r">
                                <input type="hidden" name="flat_id" value="<?php echo $this->encrypt->encode($item['VAT']->VID); ?>"/>
                                <div class="col-md-3 spc_below">
                                    <div class="wid-40">
                                        <label>VAT %</label>
                                    </div>
                                    <div class="wid-60">
                                        <input type="text" name="VATRatePercent" id="VATRatePercent"class="form-control" placeholder=""maxlength="5"value="<?php echo $item['VAT']->PercentRate; ?>" />
                                    </div>
                                </div>
								<div class="col-md-3 spc_below">
									<div class="wid-40">
										<label>Vat Effective Date</label>
									</div>
									<div class="wid-60">
										<input type="text"name="VATEffectiveDate" id="VATEffectiveDate"class="form-control datepicker1"placeholder="End date" value="<?php echo cDate($item['VAT']->StartDate); ?>"/>
									</div>
								</div>
                                <div class="col-md-3 spc_below">
                                    <div class="wid-40">
                                        <label>First year Discount end date</label>
                                    </div>
                                    <div class="wid-60">
                                        <input type="text"name="VATEndDate" id="VATEndDate"class="form-control datepicker"placeholder="End date"value="<?php echo cDate($item['VAT']->EndDate); ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-3 spc_below">
                                    <div class="wid-40">
                                        <label>First year Discounted rate</label>
                                    </div>
                                    <div class="wid-60">
                                        <input type="text" name="VATRatePercentAfterYear" id="VATRatePercentAfterYear"class="form-control validNumber required" placeholder=""maxlength="2" value="<?php echo $item['VAT']->PercentRateAfterEndDate; ?>" readonly />
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
                            <input type="hidden" name="stand_id" value="<?php echo $this->encrypt->encode($item['VAT']->VID); ?>"/>
                            <div class="wid-30">
                                <input type="hidden" name="stand_id" value="<?php echo $this->encrypt->encode($item['VAT']->VID); ?>"/>
                                <label>Rate in percent</label>
                            </div>
                            <div class="wid-70">
                                <input type="text" name="VATStanderedRate" id="VATStanderedRate" class="form-control validateNumber required" placeholder="" maxlength="5"value="20" readonly/>
                            </div>
                        </div>
						<div class="col-md-3 spc_below">
							<div class="wid-40">
								<label>Vat Effective Date *</label>
							</div>
							<div class="wid-60">
								<input type="text"name="VATEffectiveDate" id="VATEffectiveDate123"class="form-control datepicker1"placeholder="End date" value="<?php echo cDate($item['VAT']->StartDate); ?>"/>
							</div>
						</div>
                    </div>
                    <div class="clr"></div>
					
					<div class="col-md-4 spc_below">
                        <div class="wid-30">
                            <label>CIS Registered :</label>
                        </div>
                        <div class="wid-70">
                            <?php
                            if (!empty($item['COMPANY']->Params['isCISRegistered'])) {
                                $checked = 'checked="true"';
                            } else {
                                $checked = "";
                            }
                            ?>
                            <input id="isCISRegistered" type="checkbox" value="yes" name="isCISRegistered" <?php echo $checked; ?>>
                        </div>
                    </div>
					<div class="col-md-4 isCISRegisteredDiv" <?php if( $item['COMPANY']->Params['isCISRegistered'] == 'yes') { ?>style="display:block"<?php } else {?> style="display:none"<?php } ?>>
                        <div class="wid-40">
                            <label>CIS % </label>
                        </div>
                        <div class="wid-60">
                            <input type="text" class="form-control" name="cis_percentage" id="cis_percentage" placeholder="" value="<?php echo $item['COMPANY']->Params['cis_percentage']; ?>" maxlength="3"/>
                        </div>
                    </div>					
					 <div class="clr"></div>
                    <div class="col-md-1 pull-left">
                        <button type="button" class="btn btn-primary nxt-btn" onclick="javascript:prevTab();" ><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Back</button>
                    </div>
                    <div class="col-md-4 col-md-offset-5 actionButtons">
                        <a href="#"class="btn btn-success update nxt-btn">
                            <i class="glyphicon glyphicon-floppy-disk"></i>
<?php if ($item['USER']->Activation == 1): ?>
                                Update
                            <?php else: ?>
                                Save
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="col-md-1 pull-right">
                        <button type="button" id="saa" class="btn btn-success next-btn nxt-btn" onclick="javascript:nextTab(); " >Next<span class="glyphicon glyphicon-arrow-right"></span></button>
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
                            <input type="text" class="form-control checkField" class="TotalShares" id="TotalShares" placeholder="" name="TotalShares"value="<?php echo $item['COMPANY']->Params['CompanyShares']; ?>"/>
                        </div>
                    </div>
                    <div class="clr" ></div>
                    <div id="dir1" class="sh-contnt">
                        <h3>User - 1 (Director Details)</h3>
                        <div class="border_box">
							<?php
							$emp = 1;
							if ($item['SHARES'][0]->IS_Employee == 1) {
								$schecked = 'checked="checked"';
								$svalue = 'EMP';
							} else {
								$schecked = '';
								$svalue = 'NEW';
							}
							if ($item['SHARES'][0]->IS_Director == 1) {
								$dchecked = 'checked="checked"';
								$dvalue = 'EMP';
							} else {
								$dchecked = '';
								$dvalue = 'NEW';
							}
							if ($item['SHARES'][0]->IS_ShareHolder == 1) {
								$shchecked = 'checked="checked"';
								$shvalue = 'EMP';
							} else {
								$shchecked = '';
								$shvalue = 'NEW';
							}
							?>							
							<div class="col-md-2 spc_below">
								<div class="wid-60">
									<label>Director?</label>
								</div>
								<div class="wid-10">
									<!--input type="hidden" name="IsDirector[]" value="0" -->
									<input name="IsDirector[1]" type="checkbox" value="<?php echo $svalue; ?>" <?php echo $dchecked; ?> class="isDirector">
								</div>
							</div>                                  
							<div class="col-md-4  spc_below siblingsDiv">
								<div class="wid-30">
									<label>Employee?</label>
								</div>
								<div class="wid-30">
									<input type="checkbox" name="IsEmployee[1]" class="isEmployee" value="<?php echo $svalue; ?>" <?php echo $schecked; ?>>
								</div>
								<div class="wid-30">
									<label>Shareholder?</label>
								</div>
								<div class="wid-10">
									<input type="checkbox" name="IsShareholder[1]" class="IsShareholder" value="<?php echo $shvalue; ?>" <?php echo $shchecked; ?>>
								</div>
							</div>
                            <div class="clr"></div>
                            <div class="col-md-5  spc_below">
                                <div class="wid-30">
                                    <label>Title</label>
                                </div>
                                <div class="wid-70">
								<?php
								echo salutationList('directorSalutation', $item['SHARES'][0]->Params['Salutation']);
								?>
                                </div>
                            </div>

                            <div class="col-md-5 spc_below col-md-offset-2">
                                <div class="wid-30">
                                    <label>No. of Shares</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" id="DirectorShares"name="DirectorShares" class="form-control checkField calShares" placeholder="" value="<?php if($item['SHARES'][0]->TotalShares!= 0) echo $item['SHARES'][0]->TotalShares; ?>"/>
                                </div>
                            </div>
                            <div class="clr"></div>
                            <div class="col-md-5 spc_below">
                                <div class="wid-30">
                                    <label>First Name :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DFirstName" id="DFirstName" readonly class="form-control"    placeholder="" value="<?php echo $item['SHARES'][0]->FirstName; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-2 spc_below">
                                <div class="wid-30">
                                    <label>Last Name :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DLastName" id="DLastName" readonly class="form-control"    placeholder=""value="<?php echo $item['SHARES'][0]->LastName; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-5 spc_below">
                                <div class="wid-30">
                                    <label>Date Of Birth:</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="Ddob" id="Ddob"class="form-control datepicker"placeholder=""value="<?php echo cDate($item['SHARES'][0]->Params['DOB']); ?>"/>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-2 spc_below">
                                <div class="wid-30">
                                    <label>NI Number :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DNINumber" id="DNINumber" readonly class="form-control niNumber"placeholder="" value="<?php echo $item['SHARES'][0]->Params['NI_Number']; ?>"/>
                                </div>
                            </div>
                            <div class="clr"></div>
                            <div class="col-md-5 spc_below">
                                <div class="wid-30">
                                    <label>UTR :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DUTR" id="DUTR" readonly class="form-control utrnumber"placeholder="" maxlength="10"value="<?php echo $item['SHARES'][0]->Params['UTR']; ?>"/>
                                </div>
                            </div>
							<div class="col-md-5 col-md-offset-2 spc_below">
                                <div class="wid-30">
                                    <label>Email :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DEmail" id="DEmail" readonly class="form-control"    placeholder=""value="<?php echo $item['SHARES'][0]->Email; ?>"/>
                                </div>
                            </div>
							<div class="clr"></div>
                            <div class="col-md-5  spc_below">
                                <div class="wid-30">
                                    <label>Address line 1 :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DAddressOne" id="DAddressOne" readonly class="form-control"    placeholder=""value="<?php echo $item['SHARES'][0]->Params['AddressOne']; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-2  spc_below">
                                <div class="wid-30">
                                    <label>Address line 2 :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DAddressTwo" id="DAddressTwo" readonly class="form-control"    placeholder=""value="<?php echo $item['SHARES'][0]->Params['AddressTwo']; ?>"/>
                                </div>
                            </div>
							<div class="clr"></div>
                            <div class="col-md-5  spc_below">
                                <div class="wid-30">
                                    <label>Address line 3 :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DAddressThree" id="DAddressThree" readonly class="form-control"placeholder=""value="<?php echo $item['SHARES'][0]->Params['AddressThree']; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-5  col-md-offset-2 spc_below">
                                <div class="wid-30">
                                    <label>Postal Code :</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DPostalCode" id="DPostalCode" readonly class="form-control postalcode"placeholder=""value="<?php echo $item['SHARES'][0]->Params['PostalCode']; ?>"/>
                                </div>
                            </div>
							<div class="clr"></div>
							<div class="col-md-5 spc_below">
                                <div class="wid-30">
                                    <label>Country:</label>
                                </div>
                                <div class="wid-70">
								<?php echo genericList($name = 'DCountry', countries(), $item['SHARES'][0]->Params['Country'], 'DCountry readonly'); ?>
                                </div>
                            </div>
                            <div class="col-md-5 col-md-offset-2 spc_below">
                                <div class="wid-30">
                                    <label>Phone No:</label>
                                </div>
                                <div class="wid-70">
                                    <input type="text" name="DPhoneNo" id="DPhoneNo" readonly class="form-control phonenumber checkField" maxlength="11" placeholder=""value="<?php echo $item['SHARES'][0]->Params['ContactNumber']; ?>"/>
                                </div>
                            </div>                            
                            
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div class="sh-cont" >
						<?php $x = 1; $countShareholders = count($item['SHARES']);
						if( !empty($item['SHARES']))
						{
						foreach ($item['SHARES'] as $key => $val): ?>
							<?php
							if ($key == 0 && $countShareholders > 1) {
								continue;
							}
							?>

                            <div class="sh-contnt">
                            <?php
                            if ($val->DesignationType == 'D') {
                                $checked = 'checked="checked"';
                            } else {
                                $checked = '';
                            }

                            if ($val->IS_Employee == 1) {
                                $schecked = 'checked="checked"';
                                $svalue = 'EMP';
                            } else {
                                $schecked = '';
                                $svalue = 'NEW';
                            }

                            if ($val->IS_Director == 1) {
                                $dchecked = 'checked="checked"';
                                $dvalue = 'EMP';
                            } else {
                                $dchecked = '';
                                $dvalue = 'NEW';
                            }
							if ($val->IS_ShareHolder == 1) {
                                $shchecked = 'checked="checked"';
                                $shvalue = 'EMP';
                            } else {
                                $shchecked = '';
                                $shvalue = 'NEW';
                            }
                            ?>
                                <input type="hidden"name="share_holder_id[]" value="<?php echo $this->encrypt->encode($val->ID); ?>"/>
                                <br/>
                                <h3>User <span class="label-num"><?php $x++;echo $x; ?></span> Details:</h3>
                                <div class="border_box">
                                    <div class="col-md-2 spc_below">
                                        <div class="wid-60">
                                            <label>Director?</label>
                                        </div>
                                        <div class="wid-10">
                                            <!--input type="hidden" name="IsDirector[]" value="0" -->
                                            <input name="IsDirector[<?php echo $x; ?>]" type="checkbox" value="<?php echo $svalue; ?>" <?php echo $dchecked; ?> class="isDirector">
                                        </div>
                                    </div>                                  
									<div class="col-md-4 spc_below siblingsDiv">
										<div class="wid-30">
											<label>Employee?</label>
										</div>
										<div class="wid-30">
											<input type="checkbox" name="IsEmployee[<?php echo $x; ?>]" class="isEmployee" value="<?php echo $svalue; ?>" <?php echo $schecked; ?>>
										</div>
										<div class="wid-30">
											<label>Shareholder?</label>
										</div>
										<div class="wid-10">
											<input type="checkbox" name="IsShareholder[<?php echo $x; ?>]" class="IsShareholder" value="<?php echo $shvalue; ?>" <?php echo $shchecked; ?>>
										</div>
									</div>
                                    <div class="clr"></div>
                                    <div class="col-md-5  spc_below">
                                        <div class="wid-30">
                                            <label>Title</label>
                                        </div>
                                        <div class="wid-70">
											<?php echo salutationList('salutation[]', $val->Params['Salutation']); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-5 spc_below col-md-offset-2">
                                        <div class="wid-30">
                                            <label>No. of Shares</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="ShareHolderShares[]" class="form-control checkField calShares"placeholder="" value="<?php if($shchecked == 'checked="checked"') echo $val->TotalShares; ?>"/>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                    <div class="col-md-5 spc_below">
                                        <div class="wid-30">
                                            <label>First Name :</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="SFirstName[]" disabled class="form-control" placeholder=""value="<?php echo $val->FirstName; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-md-offset-2 spc_below">
                                        <div class="wid-30">
                                            <label>Last Name :</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="SLastName[]" disabled class="form-control" placeholder="" value="<?php echo $val->LastName; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-5 spc_below">
                                        <div class="wid-30">
                                            <label>Date Of Birth:</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="SDOB[]"disabled class="form-control datepicker" placeholder="" value="<?php echo cDate($val->Params['DOB']); ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-md-offset-2 spc_below">
                                        <div class="wid-30">
                                            <label>NI Number :</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="SNINumber[]"disabled class="form-control niNumber" placeholder="" value="<?php echo $val->Params['NI_Number']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                    <div class="col-md-5 spc_below">
                                        <div class="wid-30">
                                            <label>UTR :</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="SUTR[]" disabled class="form-control utrnumber" placeholder="" value="<?php echo $val->Params['UTR']; ?>" maxlength="10"/>
                                        </div>
                                    </div>
									<div class="col-md-5 col-md-offset-2 spc_below">
                                        <div class="wid-30">
                                            <label>Email :</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="SEmail[]"disabled class="form-control email" placeholder="" value="<?php echo $val->Email; ?>"/>
                                        </div>
                                    </div>
									<div class="clr"></div>
                                    <div class="col-md-5   spc_below">
                                        <div class="wid-30">
                                            <label>Address line 1 :</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="SAddressOne[]" disabled class="form-control" placeholder="" value="<?php echo $val->Params['AddressOne']; ?>"/>
                                        </div>
                                    </div>                                    
                                    <div class="col-md-5  col-md-offset-2  spc_below">
                                        <div class="wid-30">
                                            <label>Address line 2 :</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="SAddressTwo[]" disabled class="form-control" placeholder="" value="<?php echo $val->Params['AddressTwo']; ?>"/>
                                        </div>
                                    </div>
									<div class="clr"></div>
                                    <div class="col-md-5  spc_below">
                                        <div class="wid-30">
                                            <label>Address line 3 :</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="SAddressThree[]" disabled class="form-control" placeholder=" " value="<?php echo $val->Params['AddressThree']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-md-5  col-md-offset-2  spc_below">
                                        <div class="wid-30">
                                            <label>Postal Code :</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="SPostalCode[]" disabled class="form-control postalcode" placeholder=" " value="<?php echo $val->Params['PostalCode']; ?>"/>
                                        </div>
                                    </div>
									<div class="clr"></div>
									<div class="col-md-5 spc_below">
                                        <div class="wid-30">
                                            <label>Country:</label>
                                        </div>
                                        <div class="wid-70">
											<?php echo genericList($name = 'SCountry[]', countries(), $val->Params['Country'], ' readonly'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-md-offset-2 spc_below">
                                        <div class="wid-30">
                                            <label>Phone No:</label>
                                        </div>
                                        <div class="wid-70">
                                            <input type="text" name="SPhoneNumber[]" disabled class="form-control phonenumber" placeholder="" value="<?php echo $val->Params['ContactNumber']; ?>"maxlength="11"/>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                    
                                    

                                    <div class="clr"></div>
                                </div>	
                                <br/>
                                <button type="button" class="pull-right btn btn-danger removeBtn hide">Remove</button>
                            </div>
						<?php endforeach; } ?>
                        <button type="button" class="pull-left btn btn-success addBtn">Add New User</button>	
                        <div class="clr"></div>
                    </div>
                    <br/>
                    <div class="clr"></div>
                    <div class="col-md-1 pull-left">
                        <button type="button" class="btn btn-primary nxt-btn" onclick="javascript:prevTab();" ><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Back</button>
                    </div>
                    <div class="col-md-4 col-md-offset-5 actionButtons">
                        <a href="#"class="btn btn-success update nxt-btn">
                            <i class="glyphicon glyphicon-floppy-disk"></i>
							<?php if ($item['USER']->Activation == 1): ?>
                                Update
							<?php else: ?>
                                Save
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="col-md-1 pull-right">
                        <button type="button" class="btn btn-success next-btn nxt-btn" onclick="javascript:nextTab();" >Next<span class="glyphicon glyphicon-arrow-right"></span></button>
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
							<input type="hidden"name="bank_id[]" value="<?php echo $this->encrypt->encode($item['BANKS'][0]->BID); ?>"/>
								<div class="wid-30">
									<label>Bank Name:</label>
								</div>
								<div class="wid-70">
									<input type="text" name="BankName[]" id="BankName" class="form-control" placeholder="" value="<?php echo $item['BANKS'][0]->Name; ?>"/>
								</div>
							</div>
							<div class="col-md-5 col-md-offset-2 spc_below">
								<div class="wid-30">
									<label>Sort Code :</label>
								</div>
								<div class="wid-70">
									<input type="text" class="form-control" name="ShortCode[]" id="ShortCode" placeholder="" value="<?php echo $item['BANKS'][0]->ShortCode; ?>"maxlength="6"/>
								</div>
							</div>
							<div class="col-md-5 spc_below">
								<div class="wid-30">
									<label>Account Number:</label>
								</div>
								<div class="wid-70">
									<input type="text" class="form-control"  name="AccountNumber[]" id="AccountNumber" placeholder=""value="<?php echo $item['BANKS'][0]->AccountNumber; ?>" maxlength="8"/>
								</div>
							</div>
							<div class="clr"></div>
						</div>	
					</div>	
				   <div class="clr"></div>
					<div class="bank-cont" >
						<?php $x = 1; $countBanks = count($item['BANKS']);
							if($countBanks <= 1)
							{ ?>
									<div class="bank-contnt">
									<h3>Bank <span class="label-num">2</span> Details:</h3>
									<div class="border_box">
										<div class="col-md-5 spc_below">
										<input type="hidden"name="bank_id[]" value=""/>
											<div class="wid-30">
												<label>Bank Name:</label>
											</div>
											<div class="wid-70">
												<input type="text" name="BankName[]" id="BankName" class="form-control" placeholder="" value=""/>
											</div>
										</div>
										<div class="col-md-5 col-md-offset-2 spc_below">
											<div class="wid-30">
												<label>Sort Code :</label>
											</div>
											<div class="wid-70">
												<input type="text" class="form-control" name="ShortCode[]" id="ShortCode" placeholder="" value="" maxlength="6"/>
											</div>
										</div>
										<div class="col-md-5 spc_below">
											<div class="wid-30">
												<label>Account Number:</label>
											</div>
											<div class="wid-70">
												<input type="text" class="form-control"  name="AccountNumber[]" id="AccountNumber" placeholder="" value="" maxlength="8"/>
											</div>
										</div>
										<div class="clr"></div>
										
									</div><br><button type="button" class="pull-right btn btn-danger removeBtnBank hide">Remove</button>
								</div>
							<?php }
							else if( !empty($item['BANKS']))
							{
								foreach ($item['BANKS'] as $key => $val): ?>
								<?php
								if ($key == 0 ) {
									continue;
								}
								?>
								<div class="bank-contnt">
									<h3>Bank <span class="label-num"><?php $x++;echo $x; ?></span> Details:</h3>
									<div class="border_box">
										<div class="col-md-5 spc_below">
										<input type="hidden"name="bank_id[]" value="<?php echo $this->encrypt->encode($val->BID); ?>"/>
											<div class="wid-30">
												<label>Bank Name:</label>
											</div>
											<div class="wid-70">
												<input type="text" name="BankName[]" id="BankName" class="form-control" placeholder="" value="<?php echo $val->Name; ?>"/>
											</div>
										</div>
										<div class="col-md-5 col-md-offset-2 spc_below">
											<div class="wid-30">
												<label>Sort Code :</label>
											</div>
											<div class="wid-70">
												<input type="text" class="form-control" name="ShortCode[]" id="ShortCode" placeholder="" value="<?php echo $val->ShortCode; ?>" maxlength="6"/>
											</div>
										</div>
										<div class="col-md-5 spc_below">
											<div class="wid-30">
												<label>Account Number:</label>
											</div>
											<div class="wid-70">
												<input type="text" class="form-control"  name="AccountNumber[]" id="AccountNumber" placeholder=""value="<?php echo $val->AccountNumber; ?>" maxlength="8"/>
											</div>
										</div>
										<div class="clr"></div>
										
									</div><br><button type="button" class="pull-right btn btn-danger removeBtnBank hide">Remove</button>
								</div>
							<?php endforeach; } ?>
						<button type="button" class="pull-left btn btn-success addBtnBank">Add New Bank</button>	
						<div class="clr"></div>
					</div>				
                    <div class="col-md-1 pull-left">
                        <button type="button" class="btn btn-primary nxt-btn" onclick="javascript:prevTab();" ><span class="glyphicon glyphicon-arrow-left"></span>&nbsp;Back</button>
                    </div>
                    <div class="col-md-4 col-md-offset-5 actionButtons">
                        <a href="#"class="btn btn-success update nxt-btn">
                            <i class="glyphicon glyphicon-floppy-disk"></i>
<?php if ($item['USER']->Activation == 1): ?>
                                Update
                            <?php else: ?>
                                Save
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="col-md-1 pull-right">
                        <button type="button" class="btn btn-success next-btn nxt-btn" onclick="javascript:nextTab();" >Next<span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
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
                            <input type="text" class="form-control" name="CompanyMonthlyFee" placeholder="" value="<?php echo $item['COMPANY']->Params['CompanyMonthlyFee']; ?>"/>
                        </div>
                    </div>
                    <div class="col-md-5 spc_below">
                        <div class="col-md-6">
                            <label>Relationship Manager</label>
                        </div>
                        <div class="col-md-6">
<?php echo form_dropdown('relationship_manager', accountant_list(), $item['USER']->Relation_with, 'form-control'); ?>
                        </div>
                    </div>
                    <div class="col-md-3 spc_below">
                        <div class="wid-70">
                            <label>SI Start Date:</label>
                        </div>
                        <div class="wid-30">
                            <input type="text" class="form-control datepicker" name="CompanySIDate" id="sdi"placeholder="" value="<?php echo $item['COMPANY']->Params['CompanySIDate']; ?>"/>
                        </div>
                    </div>
                    <div class="clr"></div><br/><br/><br/>
                    <div class="col-md-12">
                        <div class="col-md-1 pull-left">
                            <button type="button" class="btn btn-primary nxt-btn" onclick="javascript:prevTab();" >
                                <span class="glyphicon glyphicon-arrow-left"></span>
                                &nbsp;Back
                            </button>
                        </div>
                        <div class="pull-right col-md-10">
<?php if ($item['USER']->Activation != 1): ?>
                                <!--div class="col-md-4 actionButtons ">
                                        <a class="btn btn-info nxt-btn" href="#" id="createClient">
                                                <i class="glyphicon glyphicon-file"></i>Create 
                                        </a>
                                </div-->
<?php endif; ?>
                            <div class="col-md-2 pull-right">
                                <a href="<?php echo site_url() . 'client_listing'; ?>" class="btn btn-danger nxt-btn pull-right">
                                    <i class="fa fa-close"></i>Cancel 
                                </a>				
                            </div>
							<div class="col-md-5  pull-right actionButtons">
								<a href="javascript:;" class="btn btn-success UpdateFormAddNewCompany" style="margin-right:5%;margin-top:2.5em;">
									Update & Add new company
								</a>
								<a href="javascript:;" class="btn btn-success update nxt-btn pull-right">
                                    <i class="glyphicon glyphicon-floppy-disk"></i>Update
                                </a>							
							</div>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>

                <!-- #revew_details ends here  !-->
            </div>

        </div>
        <input type="hidden"name="director_id" value="<?php echo $this->encrypt->encode($item['SHARES'][0]->ID); ?>"/>
        <!--input type="hidden" name="bank_id" value="<?php echo $this->encrypt->encode($item['BANKS']->BID); ?>"/-->
        <input type="hidden" name="client_id" value="<?php echo $this->encrypt->encode($item['USER']->ID); ?>"/>
        <input type="hidden" name="company_id" value="<?php echo $this->encrypt->encode($item['COMPANY']->CID); ?>"/>
        <input type="hidden" name="task" value="" id="task"/>
<?php echo form_close(); ?>
    </div>
</section>
<div id="dialog"></div>
<?php $this->load->view('accountant/client_js'); ?>
<?php $this->load->view('accountant/footer'); ?>