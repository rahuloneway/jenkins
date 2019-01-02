<script type="text/javascript">	
  	
    function openBulkClientForm(task,id,title){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>accountant/bulkclient/form",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"ID":id},
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
               // $(document).find('#script').html('');
                showSpinner();
            },
            error:function(msg){
                hideSpinner();
            },
            success:function(msg){
				
                hideSpinner();
                msg = JSON.parse(msg);
                var html = '';
                $('#modal_bulk_clients').modal('show');
                $('#modal_bulk_clients .modal-title').html(title);               
                if(msg['file'] != ''){
                    html = msg['file'];
                }
                html += msg['html'];
              //  $(document).find('#script').html(msg['script']);               
                $('.upload_more_clients .modal-body').html(html);
                window.onbeforeunload = false;
            }
        });
    }
	
	function dialogBox(title,text){
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
	function ajaxEmailCheck(email,cid)	{
		var flag = false;
		$.ajax({
			type: "POST",
			url: '<?php echo site_url('checkEmail');?>',
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"email":email,'ID':cid},
			async: false,
			beforeSend:function(){
				//$('#review-detail').html('<img src="<?php echo site_url();?>assets/images/loading.gif"/>');
				//dialogBox('Message','Please wait.....');
				initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
				showSpinner();
			},
			error:function(msg){
				dialogBox('Error',msg.responseText);
			},
			success:function(msg){
				hideSpinner();
				if(msg == 'wrong')
				{
					email_error = false;
				}else{
					email_error = true;
				}
			}
		});
	}

	
    $(document).ready(function(e){	
		
		
        $(document).on('click','.upload_bulk_client',function(e){			
            e.preventDefault();
            var task = '<?php echo $this->encrypt->encode('uploadBulkClient'); ?>';
            var id = '';
            var title = '<?php echo $this->lang->line('BULK_CLIENT_UPLOAD_TITLE'); ?>';
            openBulkClientForm(task,id,title);			
            
        });
		
	$(document).on('click','#uploadBclient',function(e){
		e.preventDefault();
		var f = $('#file').val();
		if(f == '')
		{	
			$('.needthis').show().fadeOut(3000);
			return false;
		}else{
			$('.upload_more_clients').modal('hide');
            $('#bulkuploadClients').trigger('submit');			
		}
	});
		
    $(document).on('click','.bulkcancel_clientupload',function (e) {
            e.preventDefault();
            window.onbeforeunload = false;
            var title = 'Message';
            var text = 'Are you sure you do not want to store these statements ';
            $('#dialog').html(text);
            $('#dialog').dialog({ 
                autoOpen : true,
                title: title,
                modal: true,
                minWidth: 500,
                draggable: false,
                buttons: {
                    Yes: function() {
                        //$('.modal-statements-edit').modal('hide');
                        $(this).dialog('close');
                        window.location = '<?php echo site_url(); ?>accountant/bulkclient/cancel';
                    },
                    No: function(){
                        $(this).dialog('close');
                    }
                }
            });
        });
		
       
        /* Apply date picker to all fields where required */
        $(document).on('focus','.sDatepicker',function(){
            $(this).datepicker({ 
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-5:+0"
            });			
        });
		
        $(document).on('focusout','.sDatepicker',function(e){
            var regex = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
            var elem = $(this).val();
            var close_elment = '';
            if($(this).closest('td').length > 0)
            {
                close_element = 'td';
            }else{
                close_element = 'div';
            }
            if($(this).closest(close_element).children('div.error-field'))
            {
                $(this).closest(close_element).children('div.error-field').remove();
            }
            if(elem != '')
            {
                if(!regex.test(elem))
                {
                    var error = 'Invalid date format';
					
                    $(this).closest(close_element).append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
                    return false;
                }
            }
        });
		
	$(document).on('click','.bulk_client_finish',function(e){
		var response = bulkupload_validate();
		if(!response)
		{
			return false;
		}
	    var title = 'Message';
		$('#updateBulkClients').submit();
      /*  var text = 'Are you sure you want to save the statements?';
        $('#dialog').html(text);
        $('#dialog').dialog({ 
            autoOpen : true,
            title: title,
            modal: true,
            minWidth: 500,
            draggable: false,
            buttons: {
                Yes: function(){
                    $(this).dialog('close');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url(); ?>accountant/bulkupload/save_statements",
                        data: $('#updateBulkStatements').serialize(),
                        error: function(msg) {
                            // alert(msg.responseText);
                        },
                        beforeSend:function(){
                            initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                            showSpinner();
                        },
                        success: function(msg) {
                            hideSpinner();
                            window.location.href= "<?php echo site_url() . "bulkupload"; ?>";
                        }
                    });
                 
                 
                },
                No: function(){
                    $(this).dialog('close');
                }
            }
        }); */
            
    });
	
	
	$(document).on('keypress','.validNumber',function(eve) {
			if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57)) {
				if(eve.which != 8)
				{
					eve.preventDefault();
				}
			}
		 
			$('.validNumber').keyup(function(eve) {
				if($(this).val().indexOf('.') == 0) {    
					$(this).val($(this).val().substring(1));
				}
			});
		});
	
});
		
</script>
<script type="text/javascript">	
	function bulkupload_validate(){
		var errorFlag;
			$('#tblOne > tbody  > tr').each(function() {			
			var email = $(this).find('td .email').val(); 
			ajaxEmailCheck(email); 			
			if(email_error == false){					
					error = '<?php echo $this->lang->line('ACCOUNTANT_CLIENT_EMAIL_EXISTS');?>';					
					error = error.replace('{s}',email);					
					$(this).find('td .email').after('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
					errorFlag = 1;
					
			}
			if($(this).find('td .email').children('div.error-field')){
					$(this).find('td .email').children('div.error-field').remove();
			}
				var niNumber = $(this).find('td .niNumber').val(); 
				error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_NI_NUMBER');?>';
				var regx = /^([a-zA-Z]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([a-zA-Z]){1}?$/;						
				if(!regx.test($(this).find('td .niNumber').val())){
					$(this).find('td .niNumber').after('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
					errorFlag = 1;
				}
		
		
		//alert('2-'+errorFlag);
				var utrnumber = $(this).find('td .utrnumber').val();				
				var regx = /[0-9]{10}/;
				error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_UTR_NUMBER');?>';
				if(!regx.test($(this).find('td .utrnumber').val())){
					$(this).find('td .utrnumber').after('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
					errorFlag = 1;
				}		
		
		// var postalcode = $(this).find('td .postalcode').val();
		// var regx = /^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/;
		// error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_POSTAL_CODE');?>';
		// if(!regx.test($(this).find('td .postalcode').val())){
			// $(this).find('td .postalcode').after('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
			// errorFlag = 1;
		// }else{
			// $(this).find('td .postalcode').after('');
		// }
		//alert('5-'+errorFlag);
			var phonenumber = $(this).find('td .phonenumber').val();
			var regx = /[0-9]{10,11}/;
			error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_PHONE_NUMBER');?>';
			if(!regx.test($(this).find('td .phonenumber').val())){
				$(this).find('td .phonenumber').after('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
				errorFlag = 1;
			}					
		});
		
		$('#tblComp > tbody  > tr').each(function() {				
				var regnmbr = $(this).find('td .regnmbr').val(); 			
				error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_COMPANY_REGISTRATION_NUMBER');?>';
				var regx = /[ A-Za-z0-9]{2}[0-9]{6}$/;						
				if(!regx.test($(this).find('td .regnmbr').val())){
					$(this).find('td .regnmbr').after('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
					errorFlag = 1;
				}					
		});
		$('#tblVat > tbody  > tr').each(function() {				
				var vatregno = $(this).find('td .vatregno').val(); 	
				error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_VAT_REGISTRATION_NUMBER');?>';
				var regx = /^[0-9]{9}$/;						
				if(!regx.test($(this).find('td .vatregno').val())){
					$(this).find('td .vatregno').after('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
					errorFlag = 1;
				}					
		});
		
		$('#tblBank > tbody  > tr').each(function() {
				var accnmbr = $(this).find('td .accnmbr').val(); 	
				error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_BANK_ACCOUNT_NUMBER');?>';
				var regx = /[0-9]{8}/;						
				if(!regx.test($(this).find('td .accnmbr').val())){
					$(this).find('td .accnmbr').after('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
					errorFlag = 1;
				}	
				
				var shrtcd = $(this).find('td .shrtcd').val(); 	
				error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_BANK_SHORT_CODE');?>';
				var regx = /[0-9]{6}/;						
				if(!regx.test($(this).find('td .shrtcd').val())){
					$(this).find('td .shrtcd').after('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
					errorFlag = 1;
				}
					
				
				
		});
		
		if(errorFlag == 1)
		{
			return false;
		}
		/* No error return true */
		return true;
	}
	
	
	function inputFormValidation()
	{
		var block = $('#myTab li.active').children('a').attr('href');
		/* Validate Required Fields of form data */
		var errorFlag = 0;
		//alert('1-'+errorFlag);
		id = '.niNumber';
		error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_NI_NUMBER');?>';
		var regx = /^([a-zA-Z]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([a-zA-Z]){1}?$/;
		if(!validateField(id,error,regx))
		{
			errorFlag = 1;
		}
		
		
	
		
		/* Check Company's Registration number */
		if($('#CompanyRegisteredNo').val() != '' && block == '#company_details')
		{	
			error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_COMPANY_REGISTRATION_NUMBER');?>';
			var regx = /[ A-Za-z0-9]{2}[0-9]{6}$/;
			if(!validateField('#CompanyRegisteredNo',error,regx))
			{
				errorFlag = 1;
			}
		}
		//alert(errorFlag)
		
		
		/* Check VAT Registration number */
		if($('#VATRegisteredNo').val() != '' && block == '#vat_details')
		{	
			error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_VAT_REGISTRATION_NUMBER');?>';
			var regx = /^[0-9]{9}$/;
			if(!validateField('#VATRegisteredNo',error,regx))
			{
				errorFlag = 1;
			}
		} 
		
		
		/* Check Banks short code Registration number */
		if($('#ShortCode').val() != '' && block == '#bnk_details')
		{	
			error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_BANK_SHORT_CODE');?>';
			var regx = /[0-9]{6}/;
			if(!validateField('#ShortCode',error,regx))
			{
				errorFlag = 1;
			}
		}
		
		/* Check Banks account number */
		if($('#AccountNumber').val() != '' && block == '#bnk_details')
		{	
			error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_BANK_ACCOUNT_NUMBER');?>';
			var regx = /[0-9]{8}/;
			if(!validateField('#AccountNumber',error,regx))
			{
				errorFlag = 1;
			}
		}
		
		
		
		
		if(errorFlag)
		{
			return false;
		}

		/**
		 *	This if block will check if the entered email is already registered or not
		 */
		
		
		if(errorFlag == 1)
		{
			return false;
		}
		/* No error return true */
		return true;
	}
	

</script>

