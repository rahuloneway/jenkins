<script type="text/javascript">	
    $(document).ready(function(){
        $(document).on('click','.markAFiled',function(e){
            e.preventDefault();
            var el = $( this );
            var text = '<?php echo $this->lang->line('CASHMAN_MARK_ACCOUNT_FILED'); ?>';
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
                        var task = "<?php echo $this->encrypt->encode("markAccountsFiled"); ?>";
                        var cid = el.attr("href");
                        var MarkButton = el;
                        var clientId = el.attr("data-client");
                        var replaceButton = '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('TOOLTIP_ACCOUNT_FILED'); ?>" >' + 	'<?php echo $this->lang->line('DASHBOARD_FILED_LABEL'); ?>' + '</span>';
                        markFiled( task, cid ,  clientId , MarkButton, replaceButton);
                    },
                    No: function(){
                        $(this).dialog('close');
                    }
                }
            });			
        });
		
        $(document).on('click','.markRFiled',function(e){
            e.preventDefault();
            var el = $( this );
            var text = '<?php echo $this->lang->line('CASHMAN_MARK_ACCOUNT_FILED'); ?>';
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
                        var task = "<?php echo $this->encrypt->encode("markReturnsFiled"); ?>";
                        var cid = el.attr("href");
                        var MarkButton = el;
                        var clientId = el.attr("data-client");
                        var replaceButton = '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('TOOLTIP_RETURN_FILED'); ?>" >' + 	'<?php echo $this->lang->line('DASHBOARD_FILED_LABEL'); ?>' + '</span>';
                        markFiled( task, cid , clientId , MarkButton , replaceButton);
                    },
                    No: function(){
                        $(this).dialog('close');
                    }
                }
            });
        });
		
    });
	
    function markFiled( task, cid , clientId , MarkButton , replaceButton)
    {
        var flag = false;
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('accountant/dashboard/executeFxn'); ?>',
            data: {
                "<?php echo $this->security->get_csrf_token_name(); ?>":"<?php echo $this->security->get_csrf_hash(); ?>",
                "task":task,
                "Identifier":cid,
                "person":clientId
            },
            async: false,
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                dialogBox('Error',msg.responseText);
            },
            success:function(msg){
                hideSpinner();
                var response = JSON.parse(msg);
                if(response.hasOwnProperty('success')){
                    MarkButton.replaceWith( replaceButton );
                    $(".dashboardErrors").html( response.success );
                    $('span[data-toggle="tooltip"]').tooltip({placement: 'right'});
                }else if( response.hasOwnProperty('error')){
                    $(".dashboardErrors").html( response.error );
                }
                $(".dashboardErrors").fadeIn();
                setTimeout( function(){
                    $(".dashboardErrors").fadeOut("slow");
                },10000 );
            }
        });
    }
	
    //Upload Bank Statement on Account Dashboard 16-05-2015
	
	
    function openStatementForm(task,id,title,ajax_add,amount,pdate){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>accountant/bulkupload/form",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"ID":id,"Others":ajax_add,"Amount":amount,"PDate":pdate},
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                $(document).find('#script').html('');
                showSpinner();
            },
            error:function(msg){
                hideSpinner();
            },
            success:function(msg){
                hideSpinner();
                msg = JSON.parse(msg);
                var html = '';
                $('#modal-acc-statements').modal('show');
                $('#modal-acc-statements .modal-title').html(title);               
                if(msg['file'] != ''){
                    html = msg['file'];
                }
                html += msg['html'];
                $(document).find('#script').html(msg['script']);               
                $('.modal-statements .modal-body').html(html);
                window.onbeforeunload = false;
            }
        });
    }
	

	
    $(document).ready(function(e){		
        $(document).on('click','.upload-acc-statement',function(e){
            e.preventDefault();
            var task = '<?php echo $this->encrypt->encode('uploadAccStatement'); ?>';
            var id = '';
            var title = '<?php echo $this->lang->line('BANK_UPLOAD_STATEMENTS_TITLE'); ?>';
            openStatementForm(task,id,title);			
            
        });
		
        $(document).on('click','.uploadAccStatement',function(e){
            e.preventDefault();
            var f = $('#file').val();
            if(f == '')
            {	
                var title = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TITLE'); ?>';
                var text = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TEXT'); ?>';
                dialogBox(title,text);
                return false;
            }
            $('.modal-statements').modal('hide');
            $('#bankAccStatements').trigger('submit');
        });
		
        /* $(document).on('click','.bulk_finish',function(e){
            e.preventDefault();
            window.onbeforeunload = false;
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
                     
                        $('#updateBulkStatements').trigger('submit');
                    },
                    No: function(){
                        $(this).dialog('close');
                    }
                }
            });
           
        });*/
		
        $(document).on('click','.bulk-cancel-upload',function (e) {
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
                        window.location = '<?php echo site_url(); ?>accountant/bulkupload/cancel';
                    },
                    No: function(){
                        $(this).dialog('close');
                    }
                }
            });
        });
		
        /* Reset the search fields */
        $(document).on('click','.bulk_reset',function(e){
            e.preventDefault();
            $('#client_name').val('');
            $('#companyname').val('');
            $('#TBYear').val('0');
            $('.sDatepicker').val('');
			
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>accountant/bulkupload/clean/",
                data: {'ci_csrf_token':''},
                beforeSend:function(){
                    initSpinnerFunction("<?php echo site_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error:function(msg){
                    hideSpinner();
                },
                success:function(msg){
                    hideSpinner();
                    $('.bulkbtn_trigger').trigger('click');
                    msg = JSON.parse(msg);
                    $('#bulk-bank-listing').html(msg['html']);
                    // $('.bPagination').html(msg['pagination']);
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
		
		
        $('#BulkStatements').click(function(){
            if($(this).is(':checked'))
            {
                $('.bulkdelete-statement').attr('disabled',false);
            }else{
                $('.bulkdelete-statement').attr('disabled',true);
            }
            $('.statement-check').prop('checked', $(this).is(':checked'));
        });
        $('.statement-check').click(function () {
            if($('.statement-check:checked').length != 0)
            {
                $('.bulkdelete-statement').attr('disabled',false);
            }else{
                $('.bulkdelete-statement').attr('disabled',true);
            }
            if ($('.statement-check:checked').length == $('.statement-check').length) {
                $('#BulkStatements').prop('checked', true);
            }else {
                $('#BulkStatements').prop('checked', false);
            }
        });
		
        $(document).on('click','.bulkdelete-statement',function(e){
            e.preventDefault();
            if($('.statement-check:checked').length == 0)
            {
                var title = 'Message';
                var text = 'Please first select the statement to delete!';
                dialogBox(title,text);
                return false;
            }else{
                var title = 'Message';
                var text = 'Are you sure you want to delete the selected rows?';
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
                            $('#bulkstatementDelete').trigger('submit');
                        },
                        No: function(){
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }
        });
    });
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
    $(document).on('click','.bulk_finish',function(e){
        e.preventDefault();
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
        });  
            
    });

		
</script>