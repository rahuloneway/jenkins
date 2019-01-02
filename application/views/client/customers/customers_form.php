<style type="text/css">
    /* Adjust feedback icon position */
    #movieForm .form-control-feedback {
        right: 15px;
    }
    #movieForm .selectContainer .form-control-feedback {
        right: 25px;
    }
</style>
<?php 
if (!empty($editcustomer)) {
    ?>

    <div class="form-group">
        <div class="row">
            
            <!--div class="col-xs-4">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_FIRST_NAME'); ?></label>
                <input type="text" class="form-control" name="firstname" id="firstname"  placeholder="Last name" value="<?php echo $editcustomer[0]['first_name']; ?>"/>
                <span id="fnameerror" style="display: none;color:red;">This field is required</span>
            </div>

            <div class="col-xs-4">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_LAST_NAME'); ?></label>
                <input type="text" class="form-control" name="lastname" id="lastname"  placeholder="Last name" value="<?php echo $editcustomer[0]['last_name']; ?>"/>
                <span id="lnameerror" style="display: none;color:red;">This field is required</span>
            </div-->

			<input type="hidden" class="form-control" name="cid" id="cid" value="<?php echo $editcustomer[0]['id']; ?>"/>
			
			<div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_COMPANY_NAME'); ?>*</label>
                <input type="text" class="form-control" name="companyname" id="company" placeholder="Company name" value="<?php echo $editcustomer[0]['companyname']; ?>"/>
                <span id="companyerror" style="display: none;color:red;">This field is required</span>
            </div>
			
            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_EMAIL'); ?></label>
                <input type="hidden" class="form-control" name="id" id="customerid" placeholder="Id" value="<?php echo $editcustomer[0]['id']; ?>"/>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $editcustomer[0]['email']; ?>"/>
                <!--span id="emailerror" style="display: none;color:red;">This field is required</span>
                <span id="emailerror2" style="display: none;color:red;">Invalid Email Address</span>
                <span id="emailerror3" style="display: none;color:red;">Email already exists</span-->
            </div>
					
			
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-6">
				<label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_MOBILE'); ?></label>
				<input type="text" class="form-control validNumber" name="mobile" placeholder="Mobile" id="mobile"  maxlength="10" value="<?php echo $editcustomer[0]['mobile']; ?>"/>
				<span id="mobileerror" style="display: none;color:red;">This field is required</span>
			</div>
			<div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_PAYMENT_TERMS'); ?></label>
                <input type="text" class="form-control" name="paymentterms" id="paymentterms" placeholder="Payment Terms" 
                value="<?php echo $editcustomer[0]['paymentterms']; ?>"/>                
			</div>			
        </div>		
    </div>
    
    <div class="form-group">
        <div class="row">
            <div class="col-xs-4">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_ADDRESS1'); ?></label>
                <textarea class="form-control" name="address1" id="address1" rows="1" placeholder="Address 1"><?php echo $editcustomer[0]['address1']; ?></textarea>
            </div>
            
            <div class="col-xs-4">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_ADDRESS2'); ?></label>
                <textarea class="form-control" name="address2" id="address2" rows="1" placeholder="Address 2"><?php echo $editcustomer[0]['address2']; ?></textarea>
            </div>
            
            <div class="col-xs-4">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_ADDRESS3'); ?></label>
                <textarea class="form-control" name="address3" id="address3" rows="1" placeholder="Address 2"><?php echo $editcustomer[0]['address3']; ?></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_CITY'); ?></label>
                <input type="text" class="form-control" name="city" id="city" placeholder="City/Town" value="<?php echo $editcustomer[0]['city']; ?>"/>
            </div>

            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_STATE'); ?></label>
                <input type="text" class="form-control" name="state" id="state"  placeholder="State/Province" value="<?php echo $editcustomer[0]['state']; ?>"/>
            </div>


        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_POSTCODE'); ?></label>
                <input type="text" class="form-control" name="postcode" id="postcode" placeholder="Postcode" value="<?php echo $editcustomer[0]['postcode']; ?>"/>
                <span id="posterror" style="display: none;color:red;">Invalid postal code.e.g AB22 9AB</span>
            </div>

            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_COUNTRY'); ?></label>
                <input type="text" class="form-control" name="country" id="country"  placeholder="Country" value="<?php echo $editcustomer[0]['country']; ?>"/>
            </div>


        </div>
    </div>
    <!--<div class="form-group">
        <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_ADDRESS2'); ?></label>
        <textarea class="form-control" name="address2" rows="address2"><?php echo $editcustomer[0]['address2']; ?></textarea>
    </div>-->

<?php } else {
    ?>

    <div class="col-xs-4 selectContainer" style="display:none;">
        <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_STATUS'); ?></label>
        <select class="form-control" name="status" id="status">
            <option value="1" >Active</option>
        </select>
        <div class="alert alert-danger error-field status" style="display: none;"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;This field is required</div>
    </div>
    <div class="form-group">
        <div class="row">
            <!--div class="col-xs-4">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_FIRST_NAME'); ?>*</label>
                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First name"/>
                <span id="fnameerror" style="display: none;color:red;">This field is required</span>
            </div>

            <div class="col-xs-4">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_LAST_NAME'); ?>*</label>
                <input type="text" class="form-control" name="lastname" id="lastname"  placeholder="Last name"/>
                <span id="lnameerror" style="display: none;color:red;">This field is required</span>
            </div-->
			
			<div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_COMPANY_NAME'); ?>*</label>
                <input type="text" class="form-control" name="companyname" id="company" placeholder="Company name"/>
                <span id="companyerror" style="display: none;color:red;">This field is required</span>
            </div>
			
            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_EMAIL'); ?></label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" />
                <!-- <div class="alert alert-danger error-field email-error" style="display: none;"><i class="glyphicon glyphicon-exclamation-sign"></i>Email already exists</div> -->
                <!--span id="emailerror" style="display: none;color:red;">This field is required</span>
                <span id="emailerror2" style="display: none;color:red;">Invalid Email Address</span>
                <span id="emailerror3" style="display: none;color:red;">Email already exists</span-->
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            
            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_MOBILE'); ?>*</label>
                <input type="text" class="form-control validNumber" name="mobile" placeholder="Mobile" id="mobile"  maxlength="10"/>
                <span id="mobileerror" style="display: none;color:red;">This field is required</span>
            </div>
            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_PAYMENT_TERMS'); ?></label>
                <input type="text" class="form-control" name="paymentterms" id="paymentterms" placeholder="Payment Terms" 
                value="<?php echo $editcustomer[0]['paymentterms']; ?>"/>                
            </div>
			
        </div>
    </div>
    
    <div class="form-group">
        <div class="row">
            <div class="col-xs-4">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_ADDRESS1'); ?></label>
                <textarea class="form-control" name="address1" id="address1" rows="1" placeholder="Address 1"></textarea>
            </div>
            <div class="col-xs-4">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_ADDRESS2'); ?></label>
                <textarea class="form-control" name="address2" id="address2" rows="1" placeholder="Address 2"></textarea>
            </div>
            <div class="col-xs-4">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_ADDRESS3'); ?></label>
                <textarea class="form-control" name="address3" id="address3" rows="1" placeholder="Address 3"></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_CITY'); ?></label>
                <input type="text" class="form-control" name="city" id="city" placeholder="City/Town"/>
            </div>

            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_STATE'); ?></label>
                <input type="text" class="form-control" name="state" id="state"  placeholder="State/Province"/>
            </div>


        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_POSTCODE'); ?></label>
                <input type="text" class="form-control" name="postcode" id="postcode" placeholder="Postcode"/>
                <span id="posterror" style="display: none;color:red;">Invalid postal code.e.g AB22 9AB</span>
            </div>

            <div class="col-xs-6">
                <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_COUNTRY'); ?></label>
                <input type="text" class="form-control" name="country" id="country"  placeholder="Country"/>
            </div>


        </div>
    </div>
    <!--<div class="form-group">
        <label class="control-label"><?php echo $this->lang->line('CUSTOMERS_ADD_ADDRESS2'); ?></label>
        <textarea class="form-control" name="address2" rows="address2"></textarea>
    </div>-->
<?php } ?>
<?php if(isset($page)){ ?>
<input type="hidden" name="cf_page" id="cf_page" value="bankstatment" />
<?php }else{ ?>
<input type="hidden" name="cf_page" id="cf_page" value="" />
<?php }?>