<script>

function openJournalUploadForm(task,id,title){
        car_cost = 0;
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>journals_uploadform",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"ID":id},
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                hideSpinner();
            },
            success:function(msg){
                hideSpinner();
                $('.modal-title').html(title);
                $('.modal-journal').modal('show');
                $('.modal-body').html(msg);             
            }
        });
    }
	function uploadTemplate(){
        var fn = 'journaluploadSheet';
        var tt = 'Journal Template Items';
        var frm = '#journalupform';       
		var task 	= 	"<?php echo $this->encrypt->encode('uploadJournles');?>";
		var id = '';
		var formUrl = "<?php echo site_url(); ?>clients/journals/journaluploadSheet";
        var formData = new FormData($(frm)[0]);		
        $.ajax({
            url: formUrl,
            type: 'POST',
            data: formData,
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error: function(error){
                hideSpinner();
            },
            success: function(msg){
			setTimeout(function(){ 
                hideSpinner(); 				
                msg = JSON.parse(msg);				
					if(msg['error'] != 'error')
					{
						$('.modal-title').html(tt);
						$('.modal-body').html(msg['html']);
						$('.modal-journal').modal('show');	
						calculate_total_amount();	
					}else{
						window.location = '<?php echo site_url().'journals/'?>';
					}
				}, 1000);
            }
        });
        return false;	
    }

	function open_form(task,id,title,ajax_add,amount,pdate)
	{
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "<?php echo site_url();?>journals_form",
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"ID":id,"Others":ajax_add,"Amount":amount,"PDate":pdate},
			beforeSend:function(){
				initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
				$(document).find('#script').html('');
				showSpinner();
			},
			error:function(msg){
				hideSpinner();
			},
			success:function(msg){
				hideSpinner();
				//msg = JSON.parse(msg);
				$('.modal-title').html(title);
				$('.modal-body').html(msg['html']);
				$('.modal-journal').modal('show');
			}
		});
	}
	
	$(document).ready(function(e){
		$(document).find('.modal-dialog').css({width:'90%',height:'auto','max-height':'100%'});
		$('.modal-statements-edit').on('hide.bs.modal',function (e){
			//e.preventDefault();
		});
		
		$(document).on('click','.uploadJournles',function(e){
		e.preventDefault();
		$(this).unbind('click');
		var task = '<?php echo $this->encrypt->encode('uploadJournles');?>';
		var id = '';
		var title = "<?php echo $this->lang->line('JOURNAL_NEW_POPUP_TITLE');?>";
		openJournalUploadForm(task,id,title);
		});
		
		
		$(document).on('click','.uploadJournal',function(e){
		e.preventDefault();
		var f = $('#file').val();
		if(f == '')
		{	
			var title = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TITLE');?>';
			var text = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TEXT');?>';
			dialogBox(title,text);
			return false;
		}else{
			$('.modal-journal').modal('hide');
			uploadTemplate();
		}
	});
	

		
		
		
		
		$(document).on('click','.add-journal',function(e){
			e.preventDefault();
			var task = '<?php echo $this->encrypt->encode('add_journal_entry');?>';
			var id = '';
			var title = '<?php echo $this->lang->line('JOURNAL_NEW_POPUP_TITLE');?>';
			open_form(task,id,title);
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
		
		
		/* Reset the search fields */
		$(document).on('click','.reset',function(e){
			e.preventDefault();
			$('#Description').val('');
			$('#Category').val('0');
			$('.sDatepicker').val('');
			
			$.ajax({
				type: "POST",
				url: "<?php echo site_url();?>clients/banks/clean/",
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
					$('#bank-listing').html(msg['html']);
					$('.bPagination').html(msg['pagination']);
				}
			});
		});
		
		/* This block will add the journal item */
		$(document).on('click','.add-entry',function(e){
			e.preventDefault();
			var html = $('#jurnal-items tr:first-child').clone();
			$('#jurnal-items').append(html);
			$('#jurnal-items tr:last-child input').each(function(i,e){
				if($(this).attr('type') != 'hidden')
				{
					$(this).val('');
				}
			});
			
			journalItemIndex();
			
			checkButton('#jurnal-items','.remove-item');
		});
		
		/* This block will remove the journal item */
		$(document).on('click','.remove-item',function(e){
			e.preventDefault();
			if($('#jurnal-items tr').length == 1)
			{
				$(this).find('.remove-item').addClass('hide');
			}else{
				$(this).find('.remove-item').removeClass('hide');
			}
			$(this).closest('tr').remove();
			journalItemIndex();
			checkButton('#jurnal-items','.remove-item');
		});
		
		$(document).on('change','.db_amount',function(e){
			$(this).closest('tr').next('tr').find('.cr_amount').val($(this).val());
		});
		
		$(document).on('click','.save-entry',function(e){
			e.preventDefault();
			if(!checkCategories())
			{
				alert('dfsdf');
				return false;
			}else{
				var title = 'Message';
				var text = '<?php echo $this->lang->line('JOURNAL_SAVE_ENTRY_CONFIRMATION');?>';
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
							$('.modal-journal').modal('hide');
							$('#journalEntry').trigger('submit');
                                                        // var test = '<?php 
                                                         //$this->session->set_userdata('journalSearch', ''); ?>';
						},
						No: function() {
							$(this).dialog('close');
						}
					}
				});	
				
			}
		});
		
		$(document).on('change','#financialyear',function(e){                 
			var elem = $( "#financialyear option:selected" ).val();                         
                         var ts = '<?php 
                               $TBYears = getTBYear();
                               echo $TBYear = $TBYears[0]["value"];
                               ?>';                       
                            if(elem == ts){                              
                               // $('.gges').hide();
                            }
                        $('#journal-search').submit();
			/*$.ajax({
				type: "POST",
				url: "<?php echo site_url();?>journal_search",
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"Year":elem},
				beforeSend:function(){
					initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
					$(document).find('#script').html('');
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
						$('#journal-listing').html(msg['html']);
					}else{
						location.reload(true);
					}
					
				}
			});*/
		});
		
		$(document).on('change','.j_amount,.journal_type',function(e){
			calculate_total_amount();
		});
                
                /* Reset the search fields */
		$(document).on('click','.journalreset',function(e){
			e.preventDefault();
                        var currentyear = '<?php
                        $TBYears = getTBYear();
                        echo $TBYear = $TBYears[0]["value"];
                        ?>';
			$('#financialyear').val(currentyear);
			$.ajax({
				type: "POST",
				url: "<?php echo site_url();?>journal_clean",
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
					$('#journal-listing').html(msg['html']);
					$('.pagins').html(msg['pagination']);
                                        $('.gges').hide();
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
	
	function validateField(id,error,e)
	{
		var flag = 0;
		if($(id).val() == '' || $(id).val() == 0)
		{
			if($(id).closest('td').children('div.error-field'))
			{
				$(id).closest('td').children('div.error-field').remove();
			}
			$(id).closest('td').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
			flag = 1;
		}else{
			if($(id).closest('td').children('div.error-field'))
			{
				$(id).closest('td').children('div.error-field').remove();
			}
		}
		if(flag == 1)
		{
			return false;
		}else{
			return true;
		}
	}
	
	function journalItemIndex()
	{
		var j = 1;
		$("#jurnal-items tr").each(function(i,e){		
			if($(this).children("td.sno").length > 0)
			{
				$(this).children("td.sno").html(j);j++;
			}
			
		});
		return true;
	}
	
	function checkButton(elem,rmClass)
	{
		if($(elem+' tr').length == 1)
		{
			$(elem).find(rmClass).addClass('hide');
		}else{
			$(elem).find(rmClass).removeClass('hide');
		}
		return true;
	}
	
	/* Function for getting number upto 2 decimal places */
	function getSelectionStart(o) 
	{
		if (o.createTextRange) 
		{
			var r = document.selection.createRange().duplicate()
			r.moveEnd('character', o.value.length)
			if (r.text == '') return o.value.length
			return o.value.lastIndexOf(r.text)
		} else return o.selectionStart
	}
	
	function checkCategories()
	{
		var journal_type = new Array;
		var amount = new Array;
		var cr_count = 0;
		var cr_amount = 0;
		var db_amount = 0;
		var errorFlag = 0;
		
		var error = 'This field is required';
		$('.j_amount').each(function(e,i){
			if($(this).val() != '')
			{
				if(!validateField($(this).closest('tr').find('.JournalCategories'),error,e))
				{
					errorFlag = 1;
				}
			}
			amount.push($(this).val());
		});
		
		if(errorFlag)
		{
			return false;
		}
		
		$('.journal_type').each(function(e,i){
			if($(this).val() == 'CR')
			{
				cr_count++;
				cr_amount += Number(amount[e]);
			}else if($(this).val() == 'DB'){
				db_amount += Number(amount[e]);
			}
		});
		
		if(amount[0] == '' || amount[0] == 0)
		{
			var title 	= 	'Message';
			var text 	= 	'<?php echo $this->lang->line('JOURNAL_BLANK_ENTRY_ERROR');?>';
			dialogBox(title,text);
			return false;
		}
		
		if(cr_count == 0)
		{
			var title 	= 	'Message';
			var text 	= 	'<?php echo $this->lang->line('JOURNAL_NO_CR_ENTRY_ERROR');?>';
			dialogBox(title,text);
			return false;
		}
		
		//alert(cr_amount+ ' ' +db_amount);
		cr_amount = cr_amount.toFixed(2);
		db_amount = db_amount.toFixed(2);
		if(cr_amount != db_amount)
		{
			var title 	= 	'Message';
			var text 	= 	'<?php echo $this->lang->line('JOURNAL_WRONG_ENTRY');?>';
			dialogBox(title,text);
			return false;
		}else{
			return true;
		}
	}
	
	function appenErrorHTML(el,error)
	{
		if(el.children('div.error-field'))
		{
			el.children('div.error-field').remove();
		}
		el.append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
	}
	
	function calculate_total_amount()
	{
		var cr_amount = 0;
		var db_amount = 0;
		$('.j_amount').each(function(e,i){
			if($(this).closest('tr').find('.journal_type').val() == 'CR')
			{
				cr_amount += Number($(this).val());
			}else if($(this).closest('tr').find('.journal_type').val() == 'DB'){
				db_amount += Number($(this).val());
			}
		});
		
		$('.total_credit_amount').html(cr_amount.toFixed(2));
		$('.total_debit_amount').html(db_amount.toFixed(2));
	}
	$(document).on('change', '.jnParentCat', function (e) { 
		var parentid = $(this).val();  
		var td 		 = $(this).parent().next('td'); 
		$.ajax({
			type: "POST",
			url: "<?php echo site_url(); ?>getParentCategoryChild",
			data: {'parentid': parentid},
			beforeSend: function () {                
			},
			success: function (msg) {                        
				//$('.jnChildCatTd').html(msg);
				td.html(msg);
			}
		});
	});
</script>
