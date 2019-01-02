<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php 
$user = $this->session->userdata('user');
$j_date = get_filed_year();

?>
<script>
	$(document).ready(function(){
		
		/* Set the bootstrap modal size */
		$(document).find('.modal-dialog').css({width:'95%',height:'auto','max-height':'100%'});
		$('.modal-payee').on('hide.bs.modal',function () {
		});
		
		$('.modal-payee').on('show.bs.modal', function () {
			$.fn.modal.Constructor.prototype.enforceFocus = function () { };
		});
		
		$(document).on('mouseover','.sort,.deletePayee,.deleteSalary,.paidSalary',function(e){
			$('[data-toggle="tooltip"]').tooltip({
				show: null,
				  position: {
					my: "left top",
					at: "left bottom"
				  },
				  open: function( event, ui ) {
					ui.tooltip.animate({ top: ui.tooltip.position().top + 10 }, "fast" );
				  }
			});
		});
		
		$(document).on('click','.uploadPay',function(e){
			e.preventDefault();
			var f = $('#file').val();
			if(f == '')
			{	
				var title = 'Message';
				var text = '<?php echo $this->lang->line('SALARY_UPLOAD_FILE_ERROR');?>';
				dialogBox(title,text);
				return false;
			}
			$('#payStatements').trigger('submit');
		});
		
		$(document).on('click','#saveStatements',function(e){
			e.preventDefault();
			var title = "Message";
			var text = "<?php echo $this->lang->line('BANK_SAVE_STATEMENT_CONFIRM');?>";
			
			$('#dialog').html(text);
			$('#dialog').dialog({ 
				autoOpen : true,
				title: title,
				modal: true,
				minWidth: 500,
				draggable: false,
				buttons: {
					Yes: function() {
						$(this).dialog('close');
						$('#saveBankStatements').trigger('submit');
					},
					No: function() {
						$(this).dialog('close');
					}
				}
			});
		});
		
		$(document).on('change','#employees,#financialyear',function(e){
			var emp = $('#employees').val();
			var fy = $('#financialyear').val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url();?>salary_ajax_listing",
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"emp":emp,'fy':fy},
				beforeSend:function(){
					initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
					showSpinner();
				},
				error:function(msg){
					hideSpinner();
				},
				success:function(msg){
					hideSpinner();
					msg = JSON.parse(msg);
					$('#salary-listing').html(msg['items']);
					/*$('.pagination').html(msg['pagination']);*/
				}
			});
		});
		
		$(document).on('change','#payeefinancialyear',function(e){
			var year = $(this).val();
			var currentyear = $('#payeefinancialyear :selected').val();			
			$("#payeefinancialyear option[value='" + currentyear + "']").attr("selected",true).siblings().removeAttr('selected');
			$.ajax({
				type: "POST",
				url: "<?php echo site_url();?>payeelist",
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"year":year},
				beforeSend:function(){
					initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
					showSpinner();
				},
				error:function(msg){
					hideSpinner();
				},
				success:function(msg){
					hideSpinner();
					msg = JSON.parse(msg);
					if(msg['count'] == 0)
					{
						$('.editPayee').addClass('hide');
					}else{
						$('.editPayee').removeClass('hide');
					}
					$('#payeeListing').html(msg['html']);
					/*$('.pagination').html(msg['pagination']);*/
				}
			});
		});
		
		$(document).on('click','.addPayee',function(e){
			e.preventDefault();
			var title = '<?php echo $this->lang->line('PAYEE_NEW_DETAIL_TITLE');?>';
			var task = '<?php echo $this->encrypt->encode('addPayee');?>';
			payeeForm(task,title);
		});
		
		$(document).on('click','.editPayee',function(e){
			e.preventDefault();
			var title = '<?php echo $this->lang->line('PAYEE_EDI_DETAIL_TITLE');?>';
			$('.modal-title').html(title);
			var payeeID = $('[name^="payeeEdit"]');
			var year = $('#payeefinancialyear').val();
			var payeeIDS = payeeID;
			var ids = new Array;
			$.each(payeeID,function(e,i){
				ids[e] = payeeIDS[e].value;
			});
			$.ajax({
				type: "POST",
				url: "<?php echo site_url();?>payee_edit_form",
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','ID':ids,'year':year},
				beforeSend:function(){
					initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
					showSpinner();
				},
				error:function(msg){
					hideSpinner();
				},
				success:function(msg){
					hideSpinner();
					msg = JSON.parse(msg);
					if(msg['error'] == 'error')
					{
						dialogBox('Message',msg['html']);
					}else{
						$('.modal-payee').modal('show');
						$('.modal-body').html(msg['html']);
					}
					if($('[name^="IncomeTax"]').length == 0)
					{
						$('.updateQuarters').addClass('hide');
					}else{
						$('.updateQuarters').removeClass('hide');
					}
				}
			});
		});
		
		$(document).on('click','.saveQuarters',function(e){
			e.preventDefault();
			var title = "Message";
			var text = "<?php echo $this->lang->line('PAYEE_SAVE_QUATER_CONFIRMATION');?>";
			var f = true;
			f = validateForm();
			if(!f)
			{
				return false;
			}
			$('#dialog').html(text);
			$('#dialog').dialog({ 
				autoOpen : true,
				title: title,
				modal: true,
				minWidth: 500,
				draggable: false,
				buttons: {
					Yes: function() {
						$('.modal-payee').modal('hide');
						$('#payeForm').trigger('submit');
						$(this).dialog('close');
					},
					No: function() {
						$(this).dialog('close');
					}
				}
			});	
		});
		
		$(document).on('submit','#payeUpdateForm',function(e){
			e.preventDefault();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url().'update_payee';?>',
				data: $(this).serialize(),
				beforeSend:function(){
					initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
					showSpinner();
				},
				error:function(msg){
					hideSpinner();
				},
				success:function(msg){
					hideSpinner();
					msg = JSON.parse(msg);
					if(msg['error'] == 'error')
					{
						dialogBox('Message',msg['html']);
					}else{
						window.location = '<?php echo site_url().'salary'?>';
					}
				}
			});
		});
		$(document).on('click','.updateQuarters',function(e){
			e.preventDefault();
			var title = "Message";
			var text = "<?php echo $this->lang->line('PAYEE_UPDATE_QUATER_CONFIRMATION');?>";
			var f = true;
			f = validateForm();
			if(!f)
			{
				return false;
			}
			$('#dialog').html(text);
			$('#dialog').dialog({ 
				autoOpen : true,
				title: title,
				modal: true,
				minWidth: 500,
				draggable: false,
				buttons: {
					Yes: function() {
						$('.modal-payee').modal('hide');
						$('#payeUpdateForm').trigger('submit');
						$(this).dialog('close');
					},
					No: function() {
						$(this).dialog('close');
					}
				}
			});
		});
		$(document).on('focus','.pDatepicker',function(){
			$(this).datepicker({ 
				dateFormat: 'dd-mm-yy',
				changeMonth: true,
				changeYear: true,
				yearRange: "-50:+0",
				minDate: '<?php echo $j_date;?>'
			});			
		});
		
		/* Prevent user to enter only valid values in unit-price and vat fields field i.e only integer numbers */
		$(document).on('keypress','.validNumber',function(evt) {
			var el = this;
			var charCode = (evt.which) ? evt.which : event.keyCode;
			var number = el.value.split('.');
			if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			//just one dot
			if(number.length>1 && charCode == 46){
				 return false;
			}
			//get the carat position
			var caratPos = getSelectionStart(el);
			var dotPos = el.value.indexOf(".");
			if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
				return false;
			}
			return true;
		});
		
		$(document).on('change','#newpayeefinancialyear',function(e){
			e.preventDefault();
			//alert('hello');
			var year = $(this).val();
			$.ajax({
				type: "POST",
				url: "<?php echo site_url();?>salary_ajax_payee_listing",
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"year":year},
				beforeSend:function(){
					initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
					showSpinner();
				},
				error:function(msg){
					hideSpinner();
				},
				success:function(msg){
					hideSpinner();
					msg = JSON.parse(msg);
					
					if(msg['error'] == 'error')
					{
						window.location = '<?php echo site_url().'salary';?>';
					}else{
						$('#newpayeeListing').html(msg['html']);
						if($('[name^="IncomeTax"]').length == 0)
						{
							$('.saveQuarters').addClass('hide');
						}else{
							$('.saveQuarters').removeClass('hide');
						}
					}
				}
			});
		});
		
		$(document).on('click','.deletePayee',function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			var title = 'Message';
			var currentyear = $('#payeefinancialyear :selected').val();	
			var text = '<?php echo $this->lang->line('PAYE_QUARTER_DELETE_CONFIRMATION');?>';
			$('#dialog').html(text);
			$('#dialog').dialog({ 
				autoOpen : true,
				title: title,
				modal: true,
				minWidth: 500,
				draggable: false,
				buttons: {
					Yes: function() {
						$(this).dialog('close');
						performAction(href,'',currentyear);
						//$(this).closest("tr").remove();	
						 // $(this).closest('tr').find('td').fadeOut(1000, 
                                // function(){                                 
                                    // $(this).parents('tr:first').remove();                    
                                // }); 
					},
					No: function() {
						$(this).dialog('close');
					}
				}
			});	
		});
		
		$(document).on('click','.paidPayee',function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			var rel = $(this).attr('rel');
			var MarkButton = $(this);
			var title = 'Message';
			var datetext = "Please choose the paid date <input type='text' name='paidDate'id='paidDate' class='pDatepicker' readonly/><br/><br/>";
			var text = datetext+'<?php echo $this->lang->line('PAYE_QUARTER_PAID_CONFIRMATION');?>';
			$('#dialog').html(text);
			$('#dialog').dialog({ 
				autoOpen : true,
				title: title,
				modal: true,
				minWidth: 500,
				draggable: false,
				buttons: {
					Yes: function() {
						$(this).dialog('close');
						pdate = $('#paidDate').val();
						$('td#'+rel).html(pdate);
						performAction(href,'',pdate,MarkButton);
					},
					No: function() {
						$(this).dialog('close');
					}
				}
			});	
		});
		
		$(document).on('click','.deleteSalary',function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			var title = 'Message';
			var url = "<?php echo site_url().'salaryactions';?>";
			var text = '<?php echo $this->lang->line('SALARY_DELETE_CONFIRMATION');?>';
			$('#dialog').html(text);
			$('#dialog').dialog({ 
				autoOpen : true,
				title: title,
				modal: true,
				minWidth: 500,
				draggable: false,
				buttons: {
					Yes: function() {
						$(this).dialog('close');
						performAction(href,url);
						
					},
					No: function() {
						$(this).dialog('close');
					}
				}
			});	
		});
		
		$(document).on('click','.paidSalary',function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			var rel = $(this).attr('rel');
			var title = 'Message';
			var MarkButton = $(this);
			var datetext = "Please choose the paid date <input type='text' name='paidDate'id='paidDate' class='pDatepicker' readonly/><br/><br/>";
			var url = "<?php echo site_url().'salaryactions';?>";
			var text = datetext+'<?php echo $this->lang->line('SALARY_PAID_CONFIRMATION');?>';
			$('#dialog').html(text);
			$('#dialog').dialog({ 
				autoOpen : true,
				title: title,
				modal: true,
				minWidth: 500,
				draggable: false,
				buttons: {
					Yes: function() {
						$(this).dialog('close');
						pdate = $('#paidDate').val();						
						$('td#sl'+rel).html(pdate);
						performAction(href,url,pdate,MarkButton);
					},
					No: function() {
						$(this).dialog('close');
					}
				}
			});	
		});
		
		$(document).on('click','.sort',function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			var text = $(this).text();
			var se = $(this);
			var dir = '';
			
			$.ajax({
				type: "POST",
				url: "<?php echo site_url();?>clients/salary/salary_sorting/",
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',order:href},
				beforeSend:function(){
				
				},
				error:function(msg){
					dialogBox('Error',msg);
				},
				success:function(msg){
					//dialogBox('Message',msg);
					if($('.table-responsive th a').children('i').length > 0)
					{
						$('.table-responsive th a i').remove();
					}
					se.append('<i class="fa fa-sort-desc"></i>');
					msg = JSON.parse(msg);
					se.children('i').addClass(msg[1]);
					$('#salary-listing').html(msg[0]);
					//$('.expenseListing').html(msg);
				}
			});
		});
		
		
	});
	
	function dialogBox(title,text)
	{
		$('#dialog').html(text);
		$('#dialog').dialog({ 
			autoOpen : true,
			title: title,
			modal: true,
			minWidth: 500,
			draggable: false,
			buttons: {
				Ok: function() {
					$(this).dialog('close');
				}
			}
		});	
	}
	
	function payeeForm(href,title)
	{
		$('.modal-title').html(title);
		$('.modal-payee').modal('show');
		var financialyears = '<?php echo currentFinancialYear(); ?>';
		$.ajax({
			type: "POST",
			url: "<?php echo site_url();?>salary_payeeform/",
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":href,"fsyear": financialyears,},
			beforeSend:function(){
				$('.modal-body').html('<img src="<?php echo site_url();?>assets/images/loading.gif"/>');
			},
			error:function(msg){
				alert(msg.responseText);
			},
			success:function(msg){
				msg = JSON.parse(msg);
				
				if(msg['error'] == 'error')
				{
					window.location = '<?php echo site_url().'salary';?>';
				}else{
					$('.modal-body').html(msg['html']);
				}
				
				if($('[name^="IncomeTax"]').length == 0)
				{
					$('.saveQuarters').addClass('hide');
				}else{
					$('.saveQuarters').removeClass('hide');
				}
			}
		});
	}
	
	function performAction(href,url,pDate,MarkButton)
	{
		var params = '';
		if(url == '')
		{
			url = "<?php echo site_url();?>salary_action";
			params = '/index/1';
		}
		$.ajax({
			type: "POST",
			url: url,
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":href,"PaidDate":pDate},
			beforeSend:function(){
				initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
				showSpinner();
			},
			error:function(msg){
				hideSpinner();
			},
			success:function(msg){				
				hideSpinner();
				msg = JSON.parse(msg);				
				if(msg['suc'] == 'paid'){
					 MarkButton.replaceWith('<span class="btn btn-danger btn-xs color" id="'+(msg['id'])+'">PAID</span>');	
				}
				if(msg['suc'] == 'del'){
					$('#payeeListing').html(msg['payee_filtervew']);
					if(msg['count'] == 0){
						$('.editnoshow').hide();
					}
					//var $item = $('#payeeListing').closest("tr").find(".norecs").hide(); 
				//$("#"+msg['id']).closest("tr").remove();			  
				}
				if(msg['salsuc'] == 'salpaid'){
					 MarkButton.replaceWith('<span class="btn btn-success btn-xs">PAID</span>');	
				}
				 if(msg['sucdel'] == 'saldel'){
					$('#salary-listing').html(msg['filtervew']);
				// $("#sl"+msg['id']).closest("tr").remove();			  
				 }
				if(msg['error'] == 'error')
				{
					var title = 'Message';
					var text = msg['html'];
					
					dialogBox(title,text);
				}else{
					// if(params != "")
					// {
						// window.location = '<?php echo site_url()."salary"?>';
					// }
					// else
					// {
						// window.location = '<?php echo site_url().'salary';?>';
					// }
					
				}
			}
		});
	}
	
	function validateForm()
	{
		var id = $('.PayeReference');
		var regx = /[A-Z0-9]{17}/;
		var errorOccured = 0;
		error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_PAYEE_REFERENCE');?>';
		var flag = validateField(id,error,regx);
		if(!flag)
		{
			errorOccured = 1;
		}
		
		if($('#newpayeefinancialyear').val() == '' || $('#newpayeefinancialyear').val() == 0)
		{
			var title = 'Message';
			var text = '<?php echo $this->lang->line('PAYEE_SELECT_YEAR_ERROR');?>';
			dialogBox(title,text)
			errorOccured = 1;
		}
		
		var regex = <?php echo DATE_FORMAT_REGEX;?>;
		var error = '<?php echo $this->lang->line('INVALID_DATE_FORMAT');?>';
		$('.pDatepicker').each(function(){
			if($(this).val() != '')
			{
				if($(this).closest('td').children('div.error-field'))
				{
					$(this).closest('td').children('div.error-field').remove();
				}
				if(!regex.test($(this).val()))
				{
					$(this).closest('td').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
					errorOccured = 1;
				}
			}
		});
		if(errorOccured)
		{
			errorOccured = 0;
			return false;
		}else{
			return true;
		}
	}
	
	function validateField(id,error,regx)
	{
		var flag = 0;
		
		$(id).each(function(e,v){
			id = v;
			if($(id).closest('td').children('div.error-field'))
			{
				$(id).closest('td').children('div.error-field').remove();
			}
			if($(id).val() != '' && typeof($(id).val()) !='undefined')
			{
				
				if(!regx.test($(id).val()))
				{
					
					$(id).closest('td').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
					flag = 1;
				}
			}
		});
		if(flag == 1)
		{
			return false;
		}else{
			return true;
		}
	}
	
	function getSelectionStart(o) {
		if (o.createTextRange) {
			var r = document.selection.createRange().duplicate()
			r.moveEnd('character', o.value.length)
			if (r.text == '') return o.value.length
			return o.value.lastIndexOf(r.text)
		} else return o.selectionStart
	}
	
	
</script>