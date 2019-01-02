<script type="text/javascript">	
	var email_error = true;
	$(document).ready(function(){
		$(document).find('.modal-dialog').css({width:'80%',height:'auto','max-height':'100%'});
		
		$(document).on('focus','.datepicker',function(){
			$(".datepicker").datepicker({ 
				dateFormat: 'dd-mm-yy',
				changeMonth: true,
				changeYear: true,
				yearRange: "-50:+0"
			});			
		});
		
		$(document).on('click','.saveAccountant',function(e){
			e.preventDefault();
			$('#task').val('<?php echo $this->encrypt->encode('save_accountant');?>');
			var text = "<?php echo $this->lang->line('ACCOUNTANT_SAVE_CONFIRMATION');?>";
			validateForm(text,'#addAccountant');
		});
		
		$(document).on('click','.createAccountant',function(e){
			e.preventDefault();
			$('#task').val('<?php echo $this->encrypt->encode('create_accountant');?>');
			var text = "<?php echo $this->lang->line('ACCOUNTANT_CREATE_CONFIRMATION');?>";
			validateForm(text,'#addAccountant');
		});
		
		$(document).on('click','.updateAccountant',function(e){
			e.preventDefault();
			$('#task').val('<?php echo $this->encrypt->encode('update_accountant');?>');
			var text = "<?php echo $this->lang->line('ACCOUNTANT_SAVE_CONFIRMATION');?>";
			validateForm(text,'#update_accountant');
		});
		
		$(document).on('click','.upcreateAccountant',function(e){
			e.preventDefault();
			$('#task').val('<?php echo $this->encrypt->encode('update_status_accountant');?>');
			var text = "<?php echo $this->lang->line('ACCOUNTANT_CREATE_CONFIRMATION');?>";
			validateForm(text,'#update_accountant');
		});
		
		$(document).on('click','.reset',function(e){
			e.preventDefault();
			$('#Name').val('');
			$('#Email').val('');
			$('#Status').val('');
			$.ajax({
				type: "POST",
				url: "<?php echo site_url();?>reset_accountants",
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
				beforeSend:function(){
					//dialogBox('Trying...','Please wait...');
					initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
					showSpinner();
				},
				error:function(msg){
					hideSpinner();
				},
				success:function(msg){
					hideSpinner();
					msg = JSON.parse(msg);
					$('#accountant-listing').html(msg['items']);
					$('.ac-pagination').html(msg['pagination']);
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
				url: "<?php echo site_url();?>sorting",
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',order:href},
				beforeSend:function(){
					initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
					showSpinner();
				},
				error:function(msg){
					hideSpinner();
				},
				success:function(msg){
					hideSpinner();
					//dialogBox('Message',msg);
					if($('.table-responsive th a').children('i').length > 0)
					{
						$('.table-responsive th a i').remove();
					}
					se.append('<i class="fa fa-sort-desc"></i>');
					msg = JSON.parse(msg);
					se.children('i').addClass(msg[1]);
					$('#accountant-listing').html(msg[0]);
				}
			});
		});
		
		$(document).on('change','#EmploymentLevel',function(){
			if($('#EmploymentLevel option:selected').text() == 'Director')
			{
				$('.signature').removeClass('hide');
			}else{
				$('.signature').addClass('hide');
			}
		});
		
		$(document).on('change','#file',function(e){
			if(Math.round((this.files[0].size)/1024) > <?php echo LOGO_UPLOAD_FILE_SIZE;?>)
			{
				this.value = null;
				dialogBox('Message','<?php echo $this->lang->line('CASHAMN_CLIENT_LOG_SIZE_ERROR');?>')
			}else{
				showImage(this);
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
	
	$(document).on('click','.open_form',function(e){
		e.preventDefault();
		var title 	= '<?php echo $this->lang->line('ACCOUNTANT_POPUP_ADD_TITLE');?>';
		var task 	= '<?php echo $this->encrypt->encode('addAccountant');?>';
		open_form(task,'',title);
	});
	
	/*
	function open_form(task,id,title)
	{
		var html = '';
		$.ajax({
			type: "POST",
			url: "<?php echo site_url();?>accountant/accountant/forms/",
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"ID":id},
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
				if(msg['error'] != 'error')
				{
					$('.modal-title').html(title);
					if(msg['file'] != '')
					{
						html = msg['file'];
					}
					html += msg['html'];
					$('.modal-body').html(html);
					$('.modal-accountant').modal('show');
				}else{
					window.location = '<?php echo site_url().'accountant/accountant/accountants';?>';
				}
				
			}
		});
	}
	*/
	function showImage(input) {
		if (input.files && input.files[0]) {
			$('.showImage').show();
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#imgPath').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	function validateForm(text,form)
	{
		var error = 'This field is required';
		var errorFlag = 0;
		if(!validateField('.required',error))
		{
			errorFlag = 1;
		}
		var id = $('#id').val();
		var regex = <?php echo DATE_FORMAT_REGEX;?>;
		error = '<?php echo $this->lang->line('INVALID_DATE_FORMAT');?>';
		if($('#DOB').val() != '')
		{
			if($('#DOB').closest('div').children('div.error-field'))
			{
				$('#DOB').closest('div').children('div.error-field').remove();
			}
			
			if(!regex.test($('#DOB').val()))
			{
				$('#DOB').closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
				errorFlag = 1;
			}
		}

		var regx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_EMAIL');?>';
		if(!regx.test($('.email').val()))
		{
			var error = 'Invalid email format';
			if($('.email').closest('div').children('div.error-field'))
			{
				$('.email').closest('div').children('div.error-field').remove();
			}
			$('.email').closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
			errorFlag = 1;
		}
		
		if($('.email').val() != '')
		{
			var res = ajaxEmailCheck($('.email').val(),id);
			if(!res)
			{
				error = '<?php echo $this->lang->line('ACCOUNTANT_CLIENT_EMAIL_EXISTS');?>';
				error = error.replace('{s}',$('.email').val());
				if($('.email').closest('div').children('div.error-field'))
				{
					$('.email').closest('div').children('div.error-field').remove();
				}
				$('.email').closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
				errorFlag = 1;
			}
		}
		
		
		var regx = /^[0-9]{10,11}$/;
		error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_PHONE_NUMBER');?>';
	
		if($('.ContactNumber').val() != '')
		{
			if($('.ContactNumber').closest('div').children('div.error-field'))
			{
				$('.ContactNumber').closest('div').children('div.error-field').remove();
			}
			
			if(!regx.test($('.ContactNumber').val()))
			{
				$('.ContactNumber').closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
				errorFlag = 1;
			}
		}
		
		if(errorFlag)
		{
			return false;
		}
		
		var title = 'Message';
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
					$(form).trigger('submit');
				},
				No:	function(){
					$(this).dialog('close');
				}
			}
		});	
	}
	function validateField(id,error)
	{
		var flag = 0;
		$(id).each(function(e,v){
			id = v;
			if($(id).val() == '' || $(id).val() == 0)
			{
				if($(id).closest('div').children('div.error-field'))
				{
					$(id).closest('div').children('div.error-field').remove();
				}
				$(id).closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
				flag = 1;
			}else{
				if($(id).closest('div').children('div.error-field'))
				{
					$(id).closest('div').children('div.error-field').remove();
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
	
	function ajaxEmailCheck(email,cid)
	{
		var flag = false;
		$.ajax({
			type: "POST",
			url: '<?php echo site_url('checkEmail_accoutants');?>',
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
		return email_error;
	}
	function dialogBox(title,text,el)
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
</script>
<script>tinymce.init({selector:'textarea'});</script>