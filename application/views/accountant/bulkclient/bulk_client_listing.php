<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
//echo '<pre>';print_r($item);echo '</pre>';
//die;
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

     
       
		<?php 
		$x = 1;
		foreach($item->ClientDetails as $clientdetail){	?>
           <tr>
				<td class="sno">
					<?php echo ($x); ?>
				</td>
				<td>
                    <?php echo salutationList('ClientSalutation', $clientdetail->Title); ?>
                </td>
				<td>
					<input type="text" class="form-control required"placeholder="" name="FirstName" id="FirstName" value="<?php echo $clientdetail->First_name; ?>"/>
				</td>
				<td>
					<input type="text" class="form-control required"placeholder="" name="LastName" id="LastName" value="<?php echo $clientdetail->Last_Name; ?>"/>
				</td>
				<td>
					<input type="text" name="email" id="email" class="form-control email required"placeholder="" value="<?php echo $clientdetail->Email; ?>"/>					
				</td>
				<td>
				<input type="text" name="phoneNo" id="phoneNo" class="form-control required phonenumber" placeholder="" value="<?php echo $clientdetail->Phone; ?>" maxlength="11"/>					
				</td>
				<td>   
					<input type="text" name="niNumber" id="niNumber" class="form-control niNumber"placeholder=""value="<?php echo $clientdetail->NI_Number; ?>"/>
					
				</td>
				<td>  
					<input type="text" name="utr" id="utr" class="form-control utrnumber"placeholder=""value="<?php echo $clientdetail->UTR; ?>" maxlength="10"/>					
				</td>
				<td>    
					<input type="text" name="addressOne" id="addressOne" class="form-control" placeholder=""value="<?php echo $clientdetail->Address1;?>"/>					
				</td>
				<td>
					<input type="text" name="addressTwo" id="addressTwo" class="form-control"placeholder="" value="<?php echo $clientdetail->Address2; ?>"/>					
				</td>
				<td>        
					<input type="text" name="addressThree" id="addressThree" class="form-control" placeholder=" " value="<?php echo $clientdetail->Address3; ?>"/>
				</td>
				<td>       
					<input type="text" name="postalCode" id="postalCode" class="form-control postalcode"placeholder=" "  value="<?php echo $clientdetail->Post_code; ?>"/>
				</td>				
				<td>   
            </tr>
	<?php 
	$x++;
	} ?>
				
                <!-- #revew_details ends here  !-->
<?php //$this->load->view('accountant/client_js'); ?>
