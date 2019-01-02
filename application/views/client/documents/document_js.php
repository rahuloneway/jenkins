<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
	$(document).ready(function(){
		
		/* Set the bootstrap modal size */
		$(document).find('.modal-dialog').css({width:'35%',height:'auto','max-height':'100%'});
		
		$(document).on('click','#uploadDocument',function(e){
			e.preventDefault();
			var fname = $('#file').val().replace(/C:\\fakepath\\/i, '');
			//alert(fname);
			var cname = $('#documentsCategory').val();
			if(fname == '')
			{
				var title = 'Message';
				var text = '<?php echo $this->lang->line('DOCUMENTS_UPLOAD_CONFIRMATION_TEXT');?>';
				dialogBox(title,text);
				return false;
			}
			
			if(cname == '' || cname == 0)
			{
				var title = 'Message';
				var text = '<?php echo $this->lang->line('DOCUMENTS_UPLOAD_CATEGORY_SELECT_ERROR');?>';
				dialogBox(title,text,'#documentsCategory');
				return false;
			}
			
			/* Check if file already exists */
			$.ajax({
				type: "POST",
				url: '<?php echo site_url().'check_file';?>',
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','FileName':fname,'CatName':cname},
				beforeSend:function(){
					initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
					showSpinner();
				},
				error:function(msg){
					hideSpinner();
				},
				success:function(msg){
					
					hideSpinner();
					msg = JSON.parse(msg);//console.log(msg);return false;
					if(msg['error'] == 'error')
					{
						var title = 'Message';
						var text = '<?php echo $this->lang->line('DOCUMENT_FILE_ALREADY_EXISTS');?>';
						$('#dialog').html(text);
						$('#dialog').dialog({ 
							autoOpen : true,
							title: title,
							modal: true,
							minWidth: 500,
							draggable: false,
							buttons: {
								'Keep Both Files':function(){
									$(this).dialog('close');
									$('#operation').attr('value','keep');
									$('#mydocuments').trigger('submit');
								},
								Replace:function(){
									$(this).dialog('close');
									$('#operation').attr('value','replace');
									$('#mydocuments').trigger('submit');
								}
							}
						});
					}else{
						$('#operation').attr('value','');
						$('#mydocuments').trigger('submit');
					}
				}
			});
		});
		
		/* Hover effect over the label of expense listing */
		$(document).on('mouseover','.del-button',function(e){
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
		
		$(document).on('click','.del-button',function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			var title = 'Message';
			var text = '<?php echo $this->lang->line('DOCUMENT_DELETE_FILE_CONFIRM');?>';
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
						window.location = href;
					},
					No:	function(){
						$(this).dialog('close');
					}
				}
			});
		});
		
		$(document).on('click','.del-f-button',function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			var title = 'Message';
			var text = '<?php echo $this->lang->line('DOCUMENT_DELETE_FOLDER_CONFIRM');?>';
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
						window.location = href;
					},
					No:	function(){
						$(this).dialog('close');
					}
				}
			});
		});
		
		/* This block will open a modal to add sub-folders */
		$(document).on('click','#addFolder',function(e){
			$.ajax({
				type: "POST",
				url: '<?php echo site_url().'document_form';?>',
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
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
					$('.modal-body').html(msg['html']);
					$('#modal-folder').modal('show');
				}
			});
		});
		
		$(document).on('click','.createFolder',function(e){
			var f = $('#folder');
			var flag = 0;
			var error = 'This field is required';
			if(f.val() == '')
			{
				if($(f).closest('div').children('div.error-field'))
				{
					$(f).closest('div').children('div.error-field').remove();
				}
				$(f).closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
				flag = 1;
			}else{
				flag = 0;
				if($(f).closest('div').children('div.error-field'))
				{
					$(f).closest('div').children('div.error-field').remove();
				}
			}
			if(flag == 1)
			{
				return false;
			}
			$('#modal-folder').modal('hide');
			$('#create-folder').trigger('submit');
		});
	});
	
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
					if(el != null)
					{
						$(el).focus();
					}
					if(expensetask == 'task-completed')
					{
						window.location = "<?php echo site_url();?>documents";
					}
				}
			}
		});	
	}
</script>