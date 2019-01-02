<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php 
	//$this->load->view('client/header',array('page'=>$page,'title'=>$title));
	$access = clientAccess();
	
	$user=$this->session->userdata('user');
	$search = $this->session->userdata('BankSearch');
	$aaccess= $user['AccountantAccess'];
	
?>
<style type="text/css" media="screen">
	.droppable {
		border: 1px solid #000;
		height: 38px;
		margin: 20px;
		width: 113px;
		cursor:pointer;
    }

    .draggable {
     background: #ccc none repeat scroll 0 0;
    height: 100%;
    line-height: 25px;
    text-align: center;
    width: 100%;
	cursor:pointer;
    }
    .ui-draggable-dragging {
      background: #ccf;
	  cursor:pointer;
    }

    .hoverClass {
      border: 2px solid red;
	  cursor:pointer;
    }
	.existCols li{
		background: #ccc;
		border: 1px solid;
		float: left;
		list-style-type: none;
		margin: 10px;
		padding: 10px;
		text-align: center;
		width: 10%;;
	}
	.newCols{
		padding:0px;
		margin-top:20px;
	}
	.newCols li{
		background: #ccc;
		border: 1px solid;
		list-style-type: none;
		margin: 10px 10px 20px;
		padding: 5px;
		text-align: center;
		width: auto;
	}
	.newCols li>select{
		height:26px;
	}
	
</style>
<section class="grey-body">
	<div class="container-fluid ">
		<div class="account_sum">
			<?php echo $this->session->flashdata('bankError');?>
            <?php $_SESSION['bankID'] = $_POST['bankId']; ?>
			<h4>Your excel sheet has following column's. Please match columns to upload file.</h4>
			<div id="errorDiv" style="margin-top:10px;display:none" class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i></div>
			<div class="row " style="margin-bottom:20px;margin-top:20px">
				<?php echo form_open(site_url().'clients/banks/saveExcelColumnMatch',array('id'=>'excelColMatchForm','name'=>'excelColMatchForm'));?>
					<div class="col-md-12 col-sm-3 col-xs-12 padding_field">
						
						<div class="dropzone" style="float:left">
						<?php $template = count($columns['0']);
							foreach($columns['0'] as $col){ ?>	
								<div class='droppable'>
									<div class="draggable" data-value="<?php echo $col?>"><?php echo $col?></div>
								</div>
						<?php } ?>							  
						</div>  
						<?php  
							if($template == 5)
							{
								$optionVals = '<option value="1">Choose One</option><option value="Date">Date</option><option value="Type">Type</option><option value="Description">Description</option><option value="Value">Value (Money Out/Money In)</option><option value="Balance">Balance</option><option value="Category">Category</option>';
							}
							else if($template == 6)
							{
								$optionVals = '<option value="1">Choose One</option><option value="Date">Date</option><option value="Type">Type</option><option value="Description">Description</option><option value="Value">Value (Money Out/Money In)</option><option value="Money Out">Money Out</option><option value="Money In">Money In</option><option value="Balance">Balance</option><option value="Category">Category</option>';
							}else{
								$optionVals = '<option value="1">Choose One</option><option value="Date">Date</option><option value="Type">Type</option><option value="Description">Description</option><option value="Value">Value (Money Out/Money In)</option><option value="Money Out">Money Out</option><option value="Money In">Money In</option><option value="Balance">Balance</option><option value="Category">Category</option>';
							}
						?>
						<div class="dropzone" style="float:left">
							<ul class="newCols">
							<?php
								for($i = 1; $i <= $template; $i++){ ?>
								<li >
									<select name="col<?php echo $i;?>" id="dropDown<?php echo $i;?>" class="matchDropdown">
										<?php echo $optionVals;?>
									</select>
								</li>
								
							<?php } ?>
							</ul>							
						</div>  
						
					</div>
				<?php echo form_close();?>
				
			</div>
			
	
		</div>
	</div>
</section>


<script type="text/javascript" src="<?php echo site_url();?>assets/js/jquery-ui-1.9.min.js"></script>

<script>
var myId;

(function () {
    var previous;
    $(".matchDropdown").focus(function () {
        previous = this.value;		
    }).change(function() {
        $this = $(this);
		myId = $this.attr('id');
		myVal = $this.val();
		
		if(myVal == 'Money Out')
		{
			$('.matchDropdown option:contains(Value)').attr("disabled","disabled");			
		}
		else if(myVal == 'Money In')
		{
			$('.matchDropdown option:contains(Value)').attr("disabled","disabled");
		}
		else if(myVal == 'Value')
		{
			$('.matchDropdown option:contains(Value)').attr("disabled","disabled");
			$('.matchDropdown option:contains(Money In)').attr("disabled","disabled");
			$('.matchDropdown option:contains(Money Out)').attr("disabled","disabled");
		}
		$('.matchDropdown option:contains('+myVal+')').attr("disabled","disabled");
		$('#'+myId+' option:contains('+myVal+')').attr("disabled",false);
		
		if(previous == 'Value')
		{
			$('.matchDropdown option:contains(Value)').attr("disabled",false);
			$('.matchDropdown option:contains(Money In)').attr("disabled",false);
			$('.matchDropdown option:contains(Money Out)').attr("disabled",false);
		}
		if(previous == 'Money Out')
		{
			$('.matchDropdown option:contains(Value)').attr("disabled",false);
			$('.matchDropdown option:contains(Money Out)').attr("disabled",false);
		}
		if( previous == 'Money In')
		{
			$('.matchDropdown option:contains(Value)').attr("disabled",false);
			$('.matchDropdown option:contains(Money In)').attr("disabled",false);
		}
		
        $('.matchDropdown option:contains('+previous+')').attr("disabled",false);
		previous = myVal;		
		var selectedVal = '';
		$( ".matchDropdown option:selected" ).each(function(i) {
				selectedVal = $( this ).val();
				if(selectedVal == 'Money Out' || selectedVal == 'Money Out')
					$('.matchDropdown option:contains(Value)').attr("disabled","disabled");	
        });
    });
})();
</script>
	