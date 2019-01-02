<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//echo '<pre>';print_r($item->CompanyDetails);echo '</pre>';
//die;
 ?>
     
       
		<?php 
		$x = 1;
		foreach($item->CompanyDetails as $companyDetail){	?>
           <tr>
				<td class="sno">
					<?php echo ($x); ?>
				</td>
				<td>
                    <input type="text" name="CompanyName" id="CompanyName" class="form-control required" placeholder="" value="<?php echo $companyDetail->Company_name; ?>"/>
                </td>
				<td>
				 <input type="text" name="CompanyRegisteredNo" id="CompanyRegisteredNo" class="form-control required check_value" placeholder=" " value="<?php echo $companyDetail->Company_reg_num; ?>" maxlength="8"/>					
				</td>
				<td>
				<input type="text" name="IncorporationDate" id="IncorporationDate" class="form-control datepicker"placeholder=" "value="<?php echo cDate($companyDetail->date_of_incop); ?>"/>				
				</td>
				<td>
				 <input type="text" name="YearEndDate" id="YearEndDate" class="form-control datepicker required"    placeholder=" "value="<?php echo cDate($companyDetail->year_date_end); ?>"/>										
				</td>
				<td>
				 <input type="text" name="ReturnDate" id="ReturnDate" class="form-control datepicker" placeholder=" "value="<?php echo cDate($companyDetail->returndate); ?>"/>								
				</td>				
				<td> 
				<input type="text" class="form-control"  name="CompanyAddOne" id="CompanyAddOne"  placeholder="" value="<?php echo $companyDetail->line1; ?>"/>						
				</td>
				<td>  
				<input type="text" class="form-control"  name="CompanyAddTwo" id="CompanyAddTwo"  placeholder="" value="<?php echo $companyDetail->line2; ?>"/>										
				</td>
				<td> 
				<input type="text" class="form-control" name="CompanyAddThree" id="CompanyAddThree" placeholder="" value="<?php echo $companyDetail->line3; ?>"/>						
				</td>
				<td>
				<input type="text" class="form-control postalcode"  name="CompanyPostalCode" id="CompanyPostalCode"  placeholder=" "value="<?php echo $companyDetail->post_codes; ?>"/>								
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
                            <select name="VATRegisteredType" id="VATRegisteredType"class="required">
                                <option value="" <?php echo $select; ?>>Select Rate</option>
                                <option value="flat" <?php echo $flat; ?>>Flat Rate</option>
                                <option value="stand"<?php echo $stand; ?>>Standard</option>
                            </select>
				</td>
				<td>       
					 <?php echo vatQuatersDropdown($companyDetail->quarter, 'class="" id="VATQuaters"'); ?>
				</td>
				<td>       
					 <input type="text" class="form-control required" name="VATRegisteredNo" id="VATRegisteredNo" placeholder="" value="<?php echo $companyDetail->reg_number; ?>" maxlength="9"/> 
				</td>
				<td>       
					 <input type="text" name="VATRatePercent" id="VATRatePercent"class="form-control" placeholder=""maxlength="5"value="<?php echo $companyDetail->percentage; ?>" /> 
				</td> 
				<td>       
					 <input type="text"name="VATEndDate" id="VATEndDate"class="form-control datepicker required"placeholder="End date"value="<?php echo cDate($companyDetail->first_year_dis); ?>"/>
				</td>
				<td>       
					 <input type="text" name="BankName" id="BankName" class="form-control" placeholder="" value="<?php 
					 echo $companyDetail->name ?>"/>
				</td> 
				<td>       
					 <input type="text" class="form-control"  name="AccountNumber" id="AccountNumber" placeholder=""value="<?php echo $companyDetail->account_number; ?>" maxlength="8"/>
				</td> 
				<td>       
					<input type="text" class="form-control" name="ShortCode" id="ShortCode" placeholder="" value="<?php echo $companyDetail->sort_code; ?>"maxlength="6"/>
				</td>
			
				
            </tr>
	<?php 
	$x++;
	} ?>

<?php $this->load->view('accountant/client_js'); ?>
