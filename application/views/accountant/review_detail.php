<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php //echo '<pre>';print_r($record);echo '</pre>';die;?>
<h3 class="text-center spc_below" style="font-size:25px;">Client Details</h3>
<div class="border_box">
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>First Name :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['USER']['FirstName'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Last Name :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['USER']['LastName'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>NI Number:</label>
		</div>
		<div class="wid-50">
			<?php echo $record['USER']['Params']['NI_NUMBER'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>UTR :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['USER']['Params']['UTR'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Address:</label>
		</div>
		<div class="wid-50">
			<?php 
				echo $record['USER']['Address'].'<br/>';
				echo $record['USER']['Params']['AddressTwo'].'<br/>';
				echo $record['USER']['Params']['AddressThree'].'<br/>';
			?>		
		</div>
	</div>

	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Postal Code :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['USER']['ZipCode'];?>
		</div>
	</div>
	<div class="clr"></div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Phone No:</label>
		</div>
		<div class="wid-50">
			<?php echo $record['USER']['ContactNo'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Email :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['USER']['Email'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Country:</label>
		</div>
		<div class="wid-50">
			<?php echo countryName($record['USER']['Country']);?>
		</div>
	</div>
	<div class="clr"></div>
</div>
<h3 class="text-center spc_below" style="font-size:25px;">Company Details</h3>
<div class="border_box">
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Company Name:</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['Name'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Trading name :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['TradingName'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Expected Annual (£):</label>
		</div>
		<div class="wid-50">
			<?php echo ($record['COMPANY']['AnnualAmount'] == 0)?'':'&#163; '.$record['COMPANY']['AnnualAmount'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Company Type:</label>
		</div>
		<div class="wid-50">
			<?php echo categoryName($record['COMPANY']['CompanyType']);?>
		</div>
	</div>

	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>PAYE Reference:</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['PayeReference'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>PAYE Accounts Office Ref:</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['PayeAcountReference'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Company Reg. No :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['RegistrationNo'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Corporation Tax Reference :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['TaxReference'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Date of incorporation :</label>
		</div>
		<div class="wid-50">
			<?php echo cDate($record['COMPANY']['IncorporationDate']);?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Return date :</label>
		</div>
		<div class="wid-50">
			<?php echo cDate($record['COMPANY']['ReturnDate']);?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Year-end date:</label>
		</div>
		<div class="wid-50">
			<?php echo cDate($record['COMPANY']['EndDate']);?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Fax No : </label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['FaxNumber'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Email:</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['Email'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Website  :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['Website'];?>
		</div>
	</div>

	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Describe Nature of the Business ::</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['Description'];?>
		</div>
	</div>
	<div class="clr"></div>
</div>
<?php if(isset($record['VAT'])):?>
<h3 class="text-center spc_below" style="font-size:25px;">Vat Details</h3>
<div class="border_box">
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Vat Reg. No :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['Params']['VATRegistrationNo'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Vat Reg. Type:</label>
		</div>
		<div class="wid-50">
			<?php 
				if( $record['VAT']['Type'] == 'flat') 
					echo 'FLAT RATE';
				else if( $record['VAT']['Type'] == 'stand') 
					echo 'STANDARD RATE';
				else echo "";	?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>VAT %:</label>
		</div>
		<div class="wid-50">
			<?php echo $record['VAT']['PercentRate'];?>
		</div>
	</div>
	<?php if($record['VAT']['Type'] == 'flat'):?>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>First year discounted rate</label>
		</div>
		<div class="wid-50">
			<?php echo $record['VAT']['PercentRateAfterEndDate'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>First year end date:</label>
		</div>
		<div class="wid-50">
			<?php echo cDate($record['VAT']['EndDate']);?>
		</div>
	</div>
	<?php endif;?>
	<div class="clr"></div>
</div>
<?php endif;?>
<?php if(isset($record['COMPANY']['Params']['isCISRegistered'])):?>
<h3 class="text-center spc_below" style="font-size:25px;">CIS Details</h3>
<div class="border_box">
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>CIS %:</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['Params']['cis_percentage'];?>
		</div>
	</div>
	<div class="clr"></div>
</div>
<?php endif;?>
<h3 class="text-center spc_below" style="font-size:25px;">User Details</h3>
<div class="border_box">
	
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Total Shares :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['COMPANY']['Params']['CompanyShares'];?>
		</div>
	</div>
	<div class="clr"></div>

	<h5 class="spc_below" style="font-size:20px;">User 1 Details</h5>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Number of Shares :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['DIRECTOR']['TotalShares'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>First Name :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['DIRECTOR']['FirstName'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Last Name :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['DIRECTOR']['LastName'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Date Of Birth:</label>
		</div>
		<div class="wid-50">
			<?php echo cDate($record['DIRECTOR']['Params']['DOB']);?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>NI Number:</label>
		</div>
		<div class="wid-50">
			<?php echo $record['DIRECTOR']['Params']['NI_Number'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>UTR :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['DIRECTOR']['Params']['UTR'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Address:</label>
		</div>
		<div class="wid-50">
			<?php 
				echo $record['DIRECTOR']['Params']['AddressOne'].'<br/>';
				echo $record['DIRECTOR']['Params']['AddressTwo'].'<br/>';
				echo $record['DIRECTOR']['Params']['AddressThree'].'<br/>';
			?>
		</div>
	</div>

	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Postal Code :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['DIRECTOR']['Params']['PostalCode'];?>
		</div>
	</div>

	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Phone No:</label>
		</div>
		<div class="wid-50">
			<?php echo $record['DIRECTOR']['Params']['ContactNumber'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Email :</label>
		</div>
		<div class="wid-50">
			<?php echo $record['DIRECTOR']['Email'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Country:</label>
		</div>
		<div class="wid-50">
			<?php echo countryName($record['DIRECTOR']['Params']['Country']);?>
		</div>
	</div>
	<div class="clr"></div>
	<?php if(count($record['SHARES']) > 0):?>
	<?php $x = 1;foreach($record['SHARES'] as $key=>$val):?>
	<h5 class="spc_below" style="font-size:20px;">User <?php echo $x+1;$x++;?>:</h5>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Number of Shares :</label>
		</div>
		<div class="wid-50">
			<?php echo $val['TotalShares'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>First Name :</label>
		</div>
		<div class="wid-50">
			<?php echo $val['FirstName'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Last Name :</label>
		</div>
		<div class="wid-50">
			<?php echo $val['LastName'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Date Of Birth:</label>
		</div>
		<div class="wid-50">
			<?php echo cDate($val['Params']['DOB']);?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>NI Number:</label>
		</div>
		<div class="wid-50">
			<?php echo $val['Params']['NI_Number'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>UTR :</label>
		</div>
		<div class="wid-50">
			<?php echo $val['Params']['UTR'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Address:</label>
		</div>
		<div class="wid-50">
			<?php 
				echo $val['Params']['AddressOne'].'<br/>';
				echo $val['Params']['AddressTwo'].'<br/>';
				echo $val['Params']['AddressThree'].'<br/>';
			?>	
		</div>
	</div>

	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Postal Code :</label>
		</div>
		<div class="wid-50">
			<?php echo $val['Params']['PostalCode'];?>
		</div>
	</div>

	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Phone No:</label>
		</div>
		<div class="wid-50">
			<?php echo $val['Params']['ContactNumber'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Email :</label>
		</div>
		<div class="wid-50">
			<?php echo $val['Email'];?>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4 spc_below">
		<div class="wid-50">
			<label>Country:</label>
		</div>
		<div class="wid-50">
			<?php echo countryName($val['Params']['Country']);?>
		</div>
	</div>
	<div class="clr"></div>
	<?php endforeach;?>
	<?php endif;?>
</div>

<?php if(count($record['BANKS']) > 0):?>
	<?php $x = 1;foreach($record['BANKS'] as $key=>$val):?>
	<h5 class="spc_below" style="font-size:20px;">Bank <?php echo $x;$x++;?> Details :</h5>
<!--h3 class="text-center spc_below" style="font-size:25px;">Bank Details</h3-->
	<div class="border_box">
		<div class="col-md-6 col-md-offset-4 spc_below">
			<div class="wid-50">
				<label>Bank Name :</label>
			</div>
			<div class="wid-50">
				<?php echo $val['Name'];?>
			</div>
		</div>
		<div class="col-md-6 col-md-offset-4 spc_below">
			<div class="wid-50">
				<label>Sort Code :</label>
			</div>
			<div class="wid-50">
				<?php echo $val['ShortCode'];?>
			</div>
		</div>
		<div class="col-md-6 col-md-offset-4 spc_below">
			<div class="wid-50">
				<label>Account Number:</label>
			</div>
			<div class="wid-50">
				<?php echo $val['AccountNumber'];?>
			</div>
		</div>
		<div class="clr"></div>
	</div>
<?php endforeach;?>
	<?php endif;?>