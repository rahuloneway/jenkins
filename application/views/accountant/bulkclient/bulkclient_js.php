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
            var text = 'Are you sure you do not want to save clients ';
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
               // dateFormat: 'dd-mm-yy',
				dateFormat: '<?php echo CASHMAN_DATE_FORMATE;?>',
                changeMonth: true,
                changeYear: true,
               // yearRange: "-5:+0"
				yearRange: "-50:+10"
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
		//alert(response);
		if(!response){
			return false;
		} 		
			//$('.redalert-danger').remove();
			var title = 'Message';		
			var text = 'Are you sure you want to save the statements?';
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
					$('#updateBulkClients').submit();    
					 
					},
					No: function(){
						$(this).dialog('close');
					}
				}
			}); 
				
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
			var current = 0;
			var comp = 0;
			var vat = 0;
			var bk = 0;
			$('#tblOne > tbody  > tr').each(function() {
			current++;	
			var frstnm_empty = $(this).find('td .fstname'+current).val();
			var lastname_empty = $(this).find('td .lastname'+current).val();
			var email_empty = $(this).find('td .email'+current).val();
			var em = $(this).find('td .email'+current).val();					
				if(frstnm_empty == ''){	
					error = 'This field is required';
					$('.fstname'+current).attr('data-tooltip'+current, error);	
					$(this).find('td .fstname'+current).css("border", "1px solid red");
					errorFlag = 1;	
				}
				if(frstnm_empty != ''){	
					error = '';
					$('.fstname'+current).attr('data-tooltip'+current, error);	
					$(this).find('td .fstname'+current).css("border", "1px solid #ccc");					
				}
				if(lastname_empty == ''){
					error = 'This field is required';
					$('.lastname'+current).attr('data-tooltip'+current, error);	
					$(this).find('td .lastname'+current).css("border", "1px solid red");
					errorFlag = 1;
				}
				if(lastname_empty != ''){
					error = '';
					$('.lastname'+current).attr('data-tooltip'+current, error);	
					$(this).find('td .lastname'+current).css("border", "1px solid #ccc");					
				}
				if(email_empty == ''){	
					error = 'This field is required';
					$('.email'+current).attr('data-tooltip'+current, error);
					$(this).find('td .email'+current).css("border", "1px solid red");
					errorFlag = 1;					
				}
				
				if(email_empty != ''){
					error = '';
					$('.email'+current).attr('data-tooltip'+current, error);
					$(this).find('td .email'+current).css("border", "1px solid #ccc");						
				}
						
				if( !isValidEmailAddress( email_empty ) ) {				
					error = '<?php echo 'Please Enter Valid Email.'?>';	
					$(this).find('td .email'+current).css("border", "1px solid red");					
					$('.email'+current).attr('data-tooltip'+current, error);					
					errorFlag = 1;
					//return false;
					
				}
				
				if( isValidEmailAddress( email_empty )  ) {
					error = '';
					$(this).find('td .email'+current).css("border", "1px solid #ccc");
					$('.email'+current).attr('data-tooltip'+current, error);
					
				}
				
			
				var phn = $(this).find('td .phn'+current).val();
				if(phn != ''){					
					var regx = /[0-9]{10,11}/;
					error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_PHONE_NUMBER');?>';
					if(!regx.test($(this).find('td .phn'+current).val())){
						$(this).find('td .phn'+current).css("border", "1px solid red");
						$('.phn'+current).attr('data-tooltip'+current, error);						
						errorFlag = 1;
						//return false;
					}
					if(regx.test($(this).find('td .phn'+current).val())){
						error = '';
						$(this).find('td .phn'+current).css("border", "1px solid #ccc");
						$('.phn'+current).attr('data-tooltip'+current, error);
												
					}						
				}
			
				var niNumber = $(this).find('td .niNumber'+current).val();
				if(niNumber != ''){					
					error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_NI_NUMBER');?>';
					var regx = /^([a-zA-Z]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([a-zA-Z]){1}?$/;						
					if(!regx.test($(this).find('td .niNumber'+current).val())){	
						$(this).find('td .niNumber'+current).css("border", "1px solid red");
						$('.niNumber'+current).attr('data-tooltip'+current, error);						
						errorFlag = 1;					
						//return false;
					}
					if(regx.test($(this).find('td .niNumber'+current).val())){
						error = '';
						$(this).find('td .niNumber'+current).css("border", "1px solid #ccc");
						$('.niNumber'+current).attr('data-tooltip'+current, error);	
						
					}				
				}
							
				var utrnumber = $(this).find('td .utrnumber'+current).val();
				if(utrnumber != ''){
					var regx = /[0-9]{10}/;
					error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_UTR_NUMBER');?>';
					if(!regx.test($(this).find('td .utrnumber'+current).val())){
						$(this).find('td .lastname'+current).css("border", "1px solid red");
						$('.utrnumber'+current).attr('data-tooltip'+current, error);						
						errorFlag = 1;
						//return false;
					}
					if(regx.test($(this).find('td .utrnumber'+current).val())){
						error = '';
						$(this).find('td .utrnumber'+current).css("border", "1px solid #ccc");
						$('.utrnumber'+current).attr('data-tooltip'+current, error);
						
					}						
				}
				
				var postalcode = $(this).find('td .postalcode'+current).val();
				if(postalcode != ''){
					var regx = /^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/;
					error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_POSTAL_CODE');?>';
					if(!regx.test($(this).find('td .postalcode'+current).val())){
						$(this).find('td .postalcode'+current).css("border", "1px solid red");
						$('.postalcode'+current).attr('data-tooltip'+current, error);
						errorFlag = 1;
						//return false;
					}
					if(regx.test($(this).find('td .postalcode'+current).val())){
						error = '';
						$(this).find('td .postalcode'+current).css("border", "1px solid #ccc");
						$('.postalcode'+current).attr('data-tooltip'+current, error);
						
					}				
				}	
				ajaxEmailCheck(em);		
				if(email_error == false){					
					error = '<?php echo $this->lang->line('ACCOUNTANT_CLIENT_ALREADY_EMAIL_EXISTS');?>';
					$(this).find('td .email'+current).css("border", "1px solid red");					
					$('.email'+current).attr('data-tooltip'+current, error);					
					errorFlag = 1;	
					//return true;
				}
				
				
		});
		
		$('#tblComp > tbody  > tr').each(function() {
				comp++;
				var regnmbr = $(this).find('td .regnmbr'+comp).val();
				var cmpnm_empty = $(this).find('td .cmpnm'+comp).val();
				var yearnd = $(this).find('td .yearnd'+comp).val();
				if(regnmbr == ''){
				error = 'This field is required';
				$('.regnmbr'+comp).attr('data-tooltip'+comp, error);
				$(this).find('td .regnmbr'+comp).css("border", "1px solid red");
					errorFlag = 1;
				}
				if(cmpnm_empty == ''){
				error = 'This field is required';
				$('.cmpnm'+comp).attr('data-tooltip'+comp, error);
				$(this).find('td .cmpnm'+comp).css("border", "1px solid red");
					errorFlag = 1;
				}
				if(cmpnm_empty != ''){
				error = '';
				$('.cmpnm'+comp).attr('data-tooltip'+comp, error);
					$(this).find('td .cmpnm'+comp).css("border", "1px solid #ccc");					
				}
				if(yearnd == ''){
				error = 'This field is required';
				$('.yearnd'+comp).attr('data-tooltip'+comp, error);
				$(this).find('td .yearnd'+comp).css("border", "1px solid red");
					errorFlag = 1;
					
				}
				if(yearnd != ''){
				error = '';
				$('.yearnd'+comp).attr('data-tooltip'+comp, error);
					$(this).find('td .yearnd'+comp).css("border", "1px solid #ccc");					
				}
					
				if(regnmbr != ''){	
					$(this).find('td .regnmbr'+comp).css("border", "1px solid ccc");
					error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_COMPANY_REGISTRATION_NUMBER');?>';
					var regx = /[ A-Za-z0-9]{2}[0-9]{6}$/;						
					if(!regx.test($(this).find('td .regnmbr'+comp).val())){
						$(this).find('td .regnmbr'+comp).css("border", "1px solid red");
						$('.regnmbr'+comp).attr('data-tooltip'+comp, error);
						errorFlag = 1;
						//return false;
					}
					if(regx.test($(this).find('td .regnmbr'+comp).val())){
						error = '';
						$(this).find('td .regnmbr'+comp).css("border", "1px solid #ccc");
						$('.regnmbr'+comp).attr('data-tooltip'+comp, error);
						
					}
				}				
				
				var cpsc = $(this).find('td .cpsc'+comp).val();
					if(cpsc != ''){
					var regx = /^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/;
					error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_POSTAL_CODE');?>';
					if(!regx.test($(this).find('td .cpsc'+comp).val())){
						$(this).find('td .cpsc'+comp).css("border", "1px solid red");						
						$('.cpsc'+comp).attr('data-tooltip'+comp, error);
						errorFlag = 1;
						//return false;
					}
					if(regx.test($(this).find('td .cpsc'+comp).val())){
						error = '';
						$(this).find('td .cpsc'+comp).css("border", "1px solid #ccc");
						$('.cpsc'+comp).attr('data-tooltip'+comp, error);
						
					}					
				}
				
		});
		$('#tblVat > tbody  > tr').each(function() {
				vat++;
				var vatregno = $(this).find('td .vatregno'+vat).val(); 	
				if(vatregno != ''){
					error = '<?php echo 'Invalid, should be 9 digit number'?>';
					var regx = /^[0-9]{9}$/;						
					if(!regx.test($(this).find('td .vatregno'+vat).val())){
						$(this).find('td .vatregno'+vat).css("border", "1px solid red");
						$('.vatregno'+vat).attr('data-tooltip'+vat, error);
						errorFlag = 1;
						//return false;
					}
					if(regx.test($(this).find('td .vatregno'+vat).val())){
						error = '';
						$(this).find('td .vatregno'+vat).css("border", "1px solid #ccc");
						$('.vatregno'+vat).attr('data-tooltip'+vat, error);
						
					}						
				}
				
				var vatrate = $(this).find('td .vatrate'+vat).val(); 	
				if(vatrate != ''){
					error = '<?php echo 'Only Float or Number Value'?>';
					var intRegex =  /^\d{0,4}(\.\d{0,2})?$/;					
					if(!intRegex.test(vatrate)) {
						$(this).find('td .vatrate'+vat).css("border", "1px solid red");
						$('.vatrate'+vat).attr('data-tooltip'+vat, error);
						errorFlag = 1;						
					}
					
					if(intRegex.test(vatrate)) {
						error = '';
						$(this).find('td .vatrate'+vat).css("border", "1px solid #ccc");
						$('.vatrate'+vat).attr('data-tooltip'+vat, error);
						
					}						
				}
		});
		
		$('#tblBank > tbody  > tr').each(function() {
				bk++;
				var accnmbr = $(this).find('td .accnmbr'+bk).val(); 
				if(accnmbr != ''){				
					error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_BANK_ACCOUNT_NUMBER');?>';
					var regx = /[0-9]{8}/;						
					if(!regx.test($(this).find('td .accnmbr'+bk).val())){
						$(this).find('td .accnmbr'+vat).css("border", "1px solid red");
						$('.accnmbr'+bk).attr('data-tooltip'+bk, error);
						errorFlag = 1;	
						//return false;
					}
					if(regx.test($(this).find('td .accnmbr'+bk).val())){
						error = '';
						$(this).find('td .accnmbr'+vat).css("border", "1px solid #ccc");
						$('.accnmbr'+bk).attr('data-tooltip'+bk, error);
						
					}					
				}
				
				var shrtcd = $(this).find('td .shrtcd'+bk).val(); 	
				if(shrtcd != ''){
					error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_BANK_SHORT_CODE');?>';
					var regx = /[0-9]{6}/;						
					if(!regx.test($(this).find('td .shrtcd'+bk).val())){	
						$(this).find('td .shrtcd'+bk).css("border", "1px solid red");
						$('.shrtcd'+bk).attr('data-tooltip'+bk, error);
						errorFlag = 1;
					//	return false;
					}
					if(regx.test($(this).find('td .shrtcd'+bk).val())){
						error = '';
						$(this).find('td .shrtcd'+bk).css("border", "1px solid #ccc");
						$('.shrtcd'+bk).attr('data-tooltip'+bk, error);
						
					}					
				}
				
				// var openb = $(this).find('td .openb'+bk).val(); 	
				// if(openb != ''){
					// error = '<?php echo 'Numeric Value Required';?>';
					// var intRegex = /^\d+$/;
					// var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
					// var openbalance = $(this).find('td .openb'+bk).val();					
					// if(!intRegex.test(openbalance) || !floatRegex.test(openbalance)) {
						// if($(this).find('td .openb'+bk).nextAll('.redalert-danger').length == 0){
							// $(this).find('td .openb'+bk).after('<div class="redalert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
							
						// }
						// errorFlag = 1;
					// }
					// if(intRegex.test(openbalance) || floatRegex.test(openbalance)) {
						// $(this).find('td .openb'+bk).nextAll(".redalert-danger").remove();
					// }					
				// }
				
				
							
		});
				
		if(errorFlag == 1){			
			return false;
		}
		/* No error return true */
		return true;
	}
	function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
};
	function requiredFields(id){
		
		if($(id).val() == '' || $(id).val() == 0)
			{
				$(value).append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
				flag = 1;
			}
		if(flag == 1)
		{
			return false;
		}else{
			return true;
		}
		
	}


</script>

