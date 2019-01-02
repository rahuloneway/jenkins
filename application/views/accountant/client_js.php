<script type="text/javascript">
    var form_error = false;
    var email_error = false;
    function validateField(idd,error,regx)
    {
        var flag = 0;
        if(idd.length > 1)
        {
            $(idd).each(function(e,v){
                id = v;
                if($(id).closest('div').children('div.error-field'))
                {
                    $(id).closest('div').children('div.error-field').remove();
                }
                if($(id).val() != '' && typeof($(id).val()) !='undefined')
                {
                    if(!regx.test($(id).val()))
                    {
                        $(id).closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
                        flag = 1;
                    }
                }
            });
        }else{
            if($(idd).closest('div').children('div.error-field'))
            {
                $(idd).closest('div').children('div.error-field').remove();
            }
            if($(idd).val() != '' && typeof($(idd).val()) !='undefined')
            {

                if(!regx.test($(idd).val()))
                {
                    $(idd).closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
                    flag = 1;
                }
            }
        }
        if(flag == 1)
        {
            return false;
        }else{
            return true;
        }
    }

    function requiredFields(id)
    {
        var flag = 0;
        var error = 'This field is required';
        $(id).each(function(e,v){
            id = v;
            if($(id).closest('div').children('div.error-field'))
            {
                $(id).closest('div').children('div.error-field').remove();
            }
            //if($(id).val() == '' && typeof($(id).val()) !='undefined')
            if($(id).val() == '' || $(id).val() == 0)
            {
                $(id).closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
                flag = 1;
            }
        });
        if(flag == 1)
        {
            return false;
        }else{
            return true;
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function(){        
		$(".datepicker1").datepicker({
			dateFormat: '<?php echo CASHMAN_DATE_FORMATE; ?>',
			onSelect: function(dateStr) 
			{
				var d = $.datepicker.parseDate('<?php echo CASHMAN_DATE_FORMATE; ?>', dateStr);
				var years = 1;

				d.setFullYear(d.getFullYear() + years);
				$('#VATEndDate').datepicker('setDate', d);
				var daate = $('#VATEndDate').val();
				$('#VATEndDate').attr('readonly',true);
				$("#VATEndDate").datepicker("disable");
				$('#VATEndDate').val(daate).attr('disabled',false); 
			}
		});
	 
      
		$('.datepicker').datepicker({
            dateFormat: '<?php echo CASHMAN_DATE_FORMATE; ?>',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+3"
        });
		
		$(document).on('change','#IncorporationDate',function(){			
			$("#YearEndDate").datepicker( "option", "minDate", $('#IncorporationDate').val());            
        });
		$(document).on('change','#YearEndDate',function(){			
			$("#IncorporationDate").datepicker( "option", "maxDate", $('#YearEndDate').val());            
        });
		
        $(document).on('mouseover','.editClient,.delteImage',function(e){
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

        $(document).on('click','.save-detail',function(e){
            if(!inputFormValidation())
            {
                e.preventDefault();
            }
        });

		
		
		/* Check for user enable/disable client listing*/
		
       // $(document).ready(function () {
        $(document).on('click', '.changeClientstatus', function (e) {
            e.preventDefault();

            var title = "<?php echo $this->lang->line('CLIENT_SUPPLIERS_STATE_CHANGE_TITLE'); ?>";
            var text = "<?php echo $this->lang->line('CLIENT_CHANGE_STATUS'); ?>";
            var id = $(this).attr('id');
			//alert(id);
			
            $('#dialog').html(text);
            $('#dialog').dialog({
                autoOpen: true,
                title: title,
                modal: true,
                minWidth: 500,
                draggable: false,
                buttons: {
                    Yes: function () {
                        $(this).dialog('close');
                        $.ajax({
                            type: "POST",
                            
                            data: {
                                'id': id
                            },
							url: "<?php echo site_url(); ?>Client/changeclientstatus",
                            beforeSend: function () {

                            },
                            error: function (msg) {
                                alert(msg.responseText);
								//alert('error');
								//console.log(msg);
                            },
                            success: function (msg) {
								//alert('pass');
								//alert(msg);
                                location.reload();
                            }
                        });
                    },
                    No: function () {
                        $(this).dialog('close');
                    },
                }
            });
        });
    //});

		
		
        /* Check for user enable/disable*/
        $(document).on('click','.enable',function(e){
            var href = $(this).attr('href');
            var title = 'Message';
            var text = '<?php echo $this->lang->line('ACCOUNTANT_ENABLE_CLIENT_TEXT'); ?>';
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
                    No: function() {

                        $(this).dialog('close');
                    },
                }
            });
            e.preventDefault();
        })

        $(document).on('click','.disable',function(e){
            var title = 'Message';
            var href = $(this).attr('href');
            var text = '<?php echo $this->lang->line('ACCOUNTANT_DISABLE_CLIENT_TEXT'); ?>';
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
                    No: function() {

                        $(this).dialog('close');
                    },
                }
            });
            e.preventDefault();
        });

        $(document).on('change','#isVatRegistered',function(e){
            if($('#isVatRegistered').is(':checked'))
            {
                $('div.error-field').remove();
                $("#VATQuaters").addClass('required');
                //$('.spc_below').css('display','block');
            }else{

                $("#VATQuaters").removeClass('required');
                $("#VATStanderedRate").removeClass('required');
                $("#VATRatePercent").removeClass('required');
                $("#VATEndDate").removeClass('required');
                $("#VATRatePercentAfterYear").removeClass('required');
                $("#VATRegisteredType").removeClass('required');
            }
        });
		$(document).on('change','#isCISRegistered',function(e){ 
            if($('#isCISRegistered').is(':checked'))
            {
                $('div.error-field').remove();
				$(".isCISRegisteredDiv").show();
                $("#cis_percentage").addClass('required').val('');
            }else{
				$(".isCISRegisteredDiv").hide();
                $("#cis_percentage").removeClass('required').val('');
            }
        });
        if($('#isVatRegistered').is(':checked'))
        {
            $('.spc_below').css('display','block');
        }

        if($('#VATRegisteredType').val() == 'flat')
        {
			
		    $('.vat-multi').removeClass('hide');
            $("#VATStanderedRate").removeClass('required');
            $("#VATRatePercent").addClass('required');
			$("#SVATEffectiveDate").removeClass('required');			
			$("#VATEndDate").addClass('required');            
            $("#VATRatePercentAfterYear").addClass('required');
            $('.vat-multi').css('display','block');
        }else if($('#VATRegisteredType').val() == 'stand'){
			
            $('.standard-vat').removeClass('hide');
            $('.standard-vat').css('display','block');
            $("#VATStanderedRate").addClass('required');
            $("#VATRatePercent").removeClass('required');
			$("#FVATEffectiveDate").removeClass('required');			         
            $("#VATRatePercentAfterYear").removeClass('required');
        }

		$(document).on('change','#VATEffectiveDate',function(e){
			var effectiveDate = $(this).val();
			alert(effectiveDate);		
			var d = new Date(  );	
			alert(d);			
			alert(d.setMonth( Number(d.getMonth() + 12)));
			$("#VATEndDate").val('09-09-2017');
		});
		
        $('a[data-toggle="tab"]').on('show.bs.tab', function(e)
        {
            //alert(inputFormValidation());
            if(!inputFormValidation())
            {
                e.stopImmediatePropagation();
                return false;
            }
        });

        $(document).on('change','#VATRatePercent',function(e){
            var value = Number($(this).val() -1);
            $('#VATRatePercentAfterYear').val(value);
        });

        $(document).on('click','.revew_details',function(e){
            id = $('form').attr('id');
            reviewDetails(id);
        });



        $(document).on('click','.next-btn .nxt-btn',function(){
            var block = $('#myTab li.active').children('a').attr('href');
            if(block == '#revew_details')
            {
                id = $('form').attr('id');
                reviewDetails(id);
            }
        });

        $(document).on('change','#isVatRegistered',function(){
            if($('#isVatRegistered').is(":checked"))
            {
                $('.isRegisteredVAT').css('display','block');
                $('.standard-vat').css('display','block');
                $('.vat-multi').css('display','block');                
            }else{
                $('.isRegisteredVAT').css('display','none');
                $('.standard-vat').css('display','none');
                $('.vat-multi').css('display','none');                
            }
        });

        $('.sh-cont input').attr('disabled',false);

        $(document).on('keypress','.checkField',function(eve) {
            return eve.charCode >= 48 && eve.charCode <= 57 || eve.which == 8;
        });

        /* Reset the search fields */
        $(document).on('click','.reset',function(e){
            e.preventDefault();
            $('#Name').val('');
            $('#Email').val('');
            $('#YearEndDate').val('');
            $('#CompanyName').val('');
            $('#Status').val('');
            $('#Relation_with').val(0);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>reset",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
                beforeSend:function(){
                    //dialogBox('Trying...','Please wait...');
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error:function(msg){
                    dialogBox('Error',msg.responseText);
                },
                success:function(msg){
                    hideSpinner();
                    msg = JSON.parse(msg);
                    $('#client-listing').html(msg['items']);
                    $('.cPagination').html(msg['pagination']);
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
                url: "<?php echo site_url(); ?>client_sorting",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',order:href},
                beforeSend:function(){
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
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
                    $('#client-listing').html(msg[0]);
                    //$('.expenseListing').html(msg);
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

        $('.client_panel a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            $(".client_panel li").removeClass("prev");
            $(this).closest("li").prev().addClass("prev");
        });

        $(document).on('submit','#updateclientForm',function(e){ 
            e.preventDefault();
            var task = 'update';
            $('#task').val(task);
            var formData = new FormData($('#updateclientForm')[0]);
            $.ajax({
                type: "POST",
                url: '<?php echo site_url() . 'review'; ?>',
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend:function(){
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error:function(msg){
                    dialogBox('Error',msg.responseText);
                },
                success:function(msg){

                    hideSpinner();
                    msg = JSON.parse(msg);
                    window.location.href = msg['link'];
                }
            });
        });
		
        $(document).on('click','.UpdateFormAddNewCompany',function(e){
            e.preventDefault();
            var response = inputFormValidation();
            if(!response)
            {
                return false;
            }
            var title = '<?php echo $this->lang->line('ACCOUNTANT_CLIENT_SAVE_TITLE'); ?>';
            var text = '<?php echo $this->lang->line('ACCOUNTANT_CLIENT_SAVE_CONFIRM_ADDNEWCOMPANY'); ?>';
            $('#dialog').html(text);
            $('#dialog').dialog({
                title: title,
                modal: true,
                minWidth: 500,
                draggable: false,
                buttons: {
                    Ok: function() {						
						$.ajax({
							type: "POST",
							url: '<?php echo site_url('addnewcompanysession'); ?>',
							dataType:'json',
							data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','addCompany':'yes'},
							async: false,
							beforeSend:function(){
								initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
								showSpinner();
							},
							error:function(msg){
								dialogBox('Error',msg.responseText);
							},
							success:function(response){
								hideSpinner();
								if(response.success)
								{
									$('#updateclientForm').trigger('submit');
								}
							}
						});
                        $(this).dialog('close');
                    },
                    No: function(){
                        $(this).dialog('close');
                    }
                }
            });
        });
		$(document).on('click','.update',function(e){			
            e.preventDefault();
            var response = inputFormValidation();
            if(!response)
            {
                return false;
            }
            var title = '<?php echo $this->lang->line('ACCOUNTANT_CLIENT_SAVE_TITLE'); ?>';
            var text = '<?php echo $this->lang->line('ACCOUNTANT_CLIENT_SAVE_CONFIRM'); ?>';
            $('#dialog').html(text);
            $('#dialog').dialog({
                title: title,
                modal: true,
                minWidth: 500,
                draggable: false,
                buttons: {
                    Ok: function() {
						$.ajax({
							type: "POST",
							url: '<?php echo site_url('addnewcompanysession'); ?>',
							dataType:'json',
							data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','addCompany':'no'},
							async: false,
							beforeSend:function(){
								initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
								showSpinner();
							},
							error:function(msg){
								dialogBox('Error',msg.responseText);
							},
							success:function(response){
								hideSpinner();
								if(response.success)
								{
									$('#updateclientForm').trigger('submit');
								}
							}
						});
                        $(this).dialog('close');
                    },
                    No: function(){
                        $(this).dialog('close');
                    }
                }
            });
        });

        /* Send email to client */
        $(document).on('click','#createClient',function(e){
            e.preventDefault();
            $(this).append('<input type="hidden" name="sec" value="sec"/>');
            var id = $('form').attr('id');
            //alert(id);
            $('#'+id).trigger('submit');
        });

        $(document).on('change','.calShares',function(e){

            //$('#TotalShares').val(total);
        });

        $(document).on('click','.resendEmail',function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            var title = 'Message';
            var text = '<?php echo $this->lang->line('ACCOUNTENT_RESEND_PASSWORD_INSTRUCTION_MAIL_CONFIRM'); ?>';
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
                        window.location = href;
                    },
                    No: function(){
                        $(this).dialog('close');
                    }
                }
            });
        });

        $(document).on('click','.emp_details',function(e){
            fillEmployeeDetail();
        });

        $(document).on('click','.delteImage',function(e){
            e.preventDefault();
            var id = $(this).attr('href');
            $.ajax({
                type: "POST",
                url: '<?php echo site_url('deleteImage'); ?>',
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','ID':id},
                async: false,
                beforeSend:function(){
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error:function(msg){
                    dialogBox('Error',msg.responseText);
                    hideSpinner();
                },
                success:function(msg){
                    hideSpinner();
                    msg = JSON.parse(msg);
                    if(msg['error'] == 'error')
                    {
                        title = 'Message';
                        text = '<?php echo $this->lang->line('UNEXPECTED_ERROR_OCCURED'); ?>';
                        dialogBox(title,text);
                    }else{
                        $('.logo_image').remove();
                    }
                }
            });
        });

        /*$(document).on('change','#file',function(e){
            if(this.files[0].size > <?php echo LOGO_UPLOAD_FILE_SIZE; ?>)
            {
                this.value = null;
                $(".upload_logo").val("");
                dialogBox('Message','<?php echo $this->lang->line('CASHAMN_CLIENT_LOG_SIZE_ERROR'); ?>')
            }
        });
        $(document).on('change','#term_conditions',function(e){
            var sizet =1024*5;
            if(this.files[0].size > sizet)
            {
                this.value = null;
                dialogBox('Message','<?php echo $this->lang->line('CASHAMN_CLIENT_LOG_SIZE_ERROR'); ?>')
            }
        });*/
    });
    $(document).on('change','#term_conditions',function(e){
        var size =(this.files[0].size)/1024;
        var sizeInMb = this.files[0].size/1024;
        var sizeLimit= 1024*2; // if you want 5 MB
        if(sizeInMb > sizeLimit){
            this.value = null;
            this.value = '';
            $('#term_conditions').val('');
            dialogBox('Message','<?php echo $this->lang->line('CASHAMN_CLIENT_TERMPDF_SIZE_ERROR'); ?>');
        }
        if ($("#term_conditions").val().split(".")[1].toUpperCase() == "PDF"  || $("#term_conditions").val().split(".")[1].toUpperCase() == "pdf")
        {
            return true;
        }
        else{
            this.value = null;
            this.value = '';
            $('#term_conditions').val('');
            dialogBox('Message','<?php echo $this->lang->line('CASHAMN_CLIENT_TERMPDF_PDF_ERROR'); ?>');
        }
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
                }
            }
        });
    }

    function nextTab() {
        ///turnOnTabClick();
        var response = inputFormValidation();
		
        if(!response)
        {
            return false;
        }
        var current = $('#myTab li.active')
        .next().find('a[data-toggle="tab"]').attr('href');
		if(current == '#emp_details')
        {
            fillEmployeeDetail();
        }

        if(current == '#revew_details')
        {
            id = $('form').attr('id');
            reviewDetails(id);
        }

        $('#myTab li.active')
        .next()
        .find('a[data-toggle="tab"]').tab('show');
        turnOffTabClick();
        /* .click(); */
    }
    function prevTab() {
        turnOnTabClick();
        $('#myTab li.active')
        .prev()
        .find('a[data-toggle="tab"]').tab('show');
        turnOffTabClick();
        /* .click(); */
    }
    function showHideRate(frm){
        if($(frm).val()=='flat'){
            $(".vat-multi").removeClass("hide");
            $("#VATRatePercent").addClass('required');
			$("#VATEffectiveDate").addClass('required');
            $("#VATEndDate").addClass('required');
            $("#VATRatePercentAfterYear").addClass('required');
            $("#VATStanderedRate").removeClass('required');

            $(".standard-vat").addClass("hide");

        }else if($(frm).val()=='stand'){
            $(".vat-multi").addClass("hide");
            $("#VATStanderedRate").addClass('required');
            $("#VATRatePercent").removeClass('required');
			$("#VATEffectiveDate").removeClass('required');
            $("#VATEndDate").removeClass('required');
            $("#VATRatePercentAfterYear").removeClass('required');

            $(".standard-vat").removeClass("hide");
        }else{
            $(".vat-multi").addClass("hide");
            $(".standard-vat").addClass("hide");
        }
    }

    function inputFormValidation()
    { 
        var block = $('#myTab li.active').children('a').attr('href');
        /* Validate Required Fields of form data */
        var errorFlag = 0;
        //alert('1-'+errorFlag);
        id = block+' .niNumber';
        error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_NI_NUMBER'); ?>';
        var regx = /^([a-zA-Z]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([0-9]){2}( )?([a-zA-Z]){1}?$/;
        if(!validateField(id,error,regx))
        {
            errorFlag = 1;
        }


        //alert('2-'+errorFlag);
        id = block+' .utrnumber';
        var regx = /[0-9]{10}/;
        error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_UTR_NUMBER'); ?>';
        if(!validateField(id,error,regx))
        {
            errorFlag = 1;
        }

        id = block+' .email';
        var regx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_EMAIL'); ?>';
        if(!validateField(id,error,regx))
        {
            errorFlag = 1;
        }
        //alert('4-'+errorFlag);
        id = block+' .postalcode';
        var regx = /^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/;
        error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_POSTAL_CODE'); ?>';
        if(!validateField(id,error,regx))
        {
            errorFlag = 1;
        }
        //alert('5-'+errorFlag);
        id = block+' .phonenumber';
        var regx = /[0-9]{10,11}/;
        error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_PHONE_NUMBER'); ?>';
        if(!validateField(id,error,regx))
        {
            errorFlag = 1;
        }
        id = block+' .datepicker';
        error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_DATE_FORMAT'); ?>';
        var regx = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
        if(!validateField(id,error,regx))
        {
            errorFlag = 1;
        }
        //alert('7-'+errorFlag);


        id = block+' .PayeReference';
        var regx = /[0-9]{3}[\/][A-Z]{2}[0-9]{5}/;
        error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_PAYEE_FORMATE'); ?>';
        if(!validateField(id,error,regx))
        {
            errorFlag = 1;
        }

        id = block+' .payeref';
        var regx = /[A-Z0-9]{13}/;
        error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_PAYEE_REFERENCE'); ?>';
        if(!validateField(id,error,regx))
        {
            errorFlag = 1;
        }


        /* Check Company's Registration number */
        if($('#CompanyRegisteredNo').val() != '' && block == '#company_details')
        {
            error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_COMPANY_REGISTRATION_NUMBER'); ?>';
            var regx = /[ A-Za-z0-9]{2}[0-9]{6}$/;
            if(!validateField('#CompanyRegisteredNo',error,regx))
            {
                errorFlag = 1;
            }
        }
        //alert(errorFlag)

        if($('#isVatRegistered').is(':checked'))
        {
            $("#VATRegisteredNo").addClass('required');
            $("#VATRegisteredType").addClass('required');
        }else{
            $("#VATRegisteredNo").removeClass('required');
            $("#VATRegisteredType").removeClass('required');

            $("#VATStanderedRate").removeClass('required');
            $("#VATRatePercent").removeClass('required');
            $("#VATEndDate").removeClass('required');
            $("#VATRatePercentAfterYear").removeClass('required');
        }

        /* Check VAT Registration number */
        if($('#VATRegisteredNo').val() != '' && block == '#vat_details')
        {
            error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_VAT_REGISTRATION_NUMBER'); ?>';
            var regx = /^[0-9]{9}$/;
            if(!validateField('#VATRegisteredNo',error,regx))
            {
                errorFlag = 1;
            }
        }

        /* Check number of shares entered by the accountant*/
        if(block == '#sh_details')
        {
            var ts = $('[name^="ShareHolderShares"]');
            var ds = $('#DirectorShares').val();
            var total = 0;
            for(var i=0;i< ts.length;i++)
            {
				var ss = $(ts[i]).parents().siblings('.siblingsDiv').find(".IsShareholder");
				if($(ss).is(":checked")){
					total += Number(ts[i].value);
				}
            }
            total = Number(Number(total)+Number(ds));
			
            if($('#TotalShares').val() != total)
            {
                error = '<?php echo $this->lang->line('ACCOUNTANT_WRONG_TOTAL_SHARES'); ?>';
                if($('#TotalShares').closest('div').children('div.error-field'))
                {
                    $('#TotalShares').closest('div').children('div.error-field').remove();
                }
                $('#TotalShares').closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
                errorFlag = 1;
            }else{
                if($('#TotalShares').closest('div').children('div.error-field'))
                {
                    $('#TotalShares').closest('div').children('div.error-field').remove();
                }
            }
            total = 0;
        }

        /* Check Banks short code Registration number */
        if($('#ShortCode').val() != '' && block == '#bnk_details')
        {
            error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_BANK_SHORT_CODE'); ?>';
            var regx = /[0-9]{6}/;
            if(!validateField('#ShortCode',error,regx))
            {
                errorFlag = 1;
            }
        }

        /* Check Banks account number */
        if($('#AccountNumber').val() != '' && block == '#bnk_details')
        {
            error = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_BANK_ACCOUNT_NUMBER'); ?>';
            var regx = /[0-9]{8}/;
            if(!validateField('#AccountNumber',error,regx))
            {
                errorFlag = 1;
            }
        }


        id = block+' .calShares';
        error = 'Number of shares can not be zero';
        $(id).each(function(e,v){
            id = v;
            if($(id).closest('div').children('div.error-field'))
            {
                $(id).closest('div').children('div.error-field').remove();
            }
            //if($(id).val() == '' && typeof($(id).val()) !='undefined')
            if($(id).val() != '' && $(id).val() == 0)
            {
                $(id).closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
                errorFlag = 1;
            }
        });

        if(errorFlag)
        {
            return false;
        }

        id = block+' .required';
        if(!requiredFields(id))
        {
            errorFlag = 1;
        }
		//errorFlag = 0;
        if(errorFlag)
        {
            return false;
        }

        /**
         *	This if block will check if the entered email is already registered or not
         */
        if(block == '#client_details')
        {
            var email = $(block+' .email').val();
            if($(block+' .email').val() != '')
            {
                var cid = $('[name="client_id"]').val();
                ajaxEmailCheck(email,cid);

                if(email_error == false)
                {
                    error = '<?php echo $this->lang->line('ACCOUNTANT_CLIENT_EMAIL_EXISTS'); ?>';
                    error = error.replace('{s}',email);
                    if($(block+' .email').closest('div').children('div.error-field'))
                    {
                        $(block+' .email').closest('div').children('div.error-field').remove();
                    }
                    $(block+' .email').parent('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
                    errorFlag = 1;
                }
            }
        }
        if(errorFlag == 1)
        {
            return false;
        }
        /* No error return true */
        return true;
    }

    function ajaxEmailCheck(email,cid)
    {
        var flag = false;
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('checkEmail'); ?>',
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"email":email,'ID':cid},
            async: false,
            beforeSend:function(){
                //$('#review-detail').html('<img src="<?php echo site_url(); ?>assets/images/loading.gif"/>');
                //dialogBox('Message','Please wait.....');
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
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
</script>
<script>
	$(document).on('change','#CompanyList',function(){
		$.ajax({
            type: "POST",
            url: '<?php echo site_url('updateablecompanyid'); ?>',
			dataType:'json',
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"id":this.value},
            async: false,
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                dialogBox('Error',msg.responseText);
            },
            success:function(response){
                hideSpinner();
                if(response.success)
					location.reload(true);                
            }
        });
    });
	$(document).on('click','.IsShareholder',function(){
		if( $( this ).is(':checked'))
			$( this ).parents('.siblingsDiv').siblings().find('.calShares').removeAttr('readonly');
		else
			$( this ).parents('.siblingsDiv').siblings().find('.calShares').attr('readonly',true).val('');
    });
	
    $(document).on('click','#saa,.sh_details',function(){
        copyData("#client_details","#sh_details #dir1");
    });

    //copy data
	 $(document).on('click','#copyClientAdd',function(){		
        if($(this).is(":checked")){
            copyData("#client_address","#reg_address");
        }else{
            $("#reg_address input,#reg_address select").val("");
            $("#reg_address textarea").html("");
        }
    });
	
    $(document).on('click','#copyRegAdd',function(){		 
        if($(this).is(":checked")){
            copyData("#reg_address","#cont_address");
        }else{
            $("#cont_address input,#cont_address select").val("");
            $("#cont_address textarea").html("");
        }
    });
	

    function copyData( from , to ){ 		
		$(from+" input").each(function(i,v){
            if(from == '#client_details')
            {				
                $(to+" input").eq(i+4).val($(this).val());
            }else{				
                $(to+" input").eq(i).val($(this).val());
            }
        });
        $(from+" textarea").each(function(i,v){
            $(to+" textarea").eq(i).html($(this).html());
        });
        $(from+" select").each(function(i,v){
            $(to+" select").eq(i).val($(this).val());
        });
    }

    /** Add/Remove Multiple Rows in Flat rate **/
    var count=3;
    copyFullHTMLContent('sh-cont', 'sh-contnt', 'addBtn', 'removeBtn', 'count' , 'isEmployee', 'IsEmployee','isDirector','IsDirector','IsShareholder','IsShareholder');

    /** Add/Remove Multiple Rows in Shareholder **/
    var counter=2;
    copyFullHTMLContent('sh-cont-r', 'sh-contnt-r', 'addBtn-rate', 'removeBtn-rate', 'counter');

    /** Add/Remove Multiple Rows in Employee **/
    var counterEmp=2;
    copyFullHTMLContent('sh-cont-emp', 'sh-contnt-emp', 'addBtnEmp', 'removeBtnEmp', 'counterEmp');
	
	 /** Add/Remove Multiple Rows in Bank **/
    var counterBank = 2;
    copyFullHTMLContent('bank-cont', 'bank-contnt', 'addBtnBank', 'removeBtnBank', 'counterBank');
	

    /**
                Author: Hitesh Thakur
                Date: 31-Dec-2014
                Parameters:
				containerClass: Class of the Parent container outside the Div( Child Div) we will copy
				contentClass: Class of the Child container( Content Div ) we will copy
				addbtnClass: Class of the Add button
				rmbtnClass: Class of the Remove button
				counterStr: Pass the "Counter global variable" as String

                Return :void
     **/
    function copyFullHTMLContent(containerClass, contentClass, addbtnClass, rmbtnClass, counterStr, inputClass, inputName,directorClass, directorName,shareholderClass,shareholderName){
		
        $('.'+rmbtnClass).click( function() {
            var cointainer = $(this).closest('.'+containerClass);
            $(this).parent().remove();
            
            eval(counterStr+'--');
            //alert();
            if (eval(counterStr+' == 2')) {
                cointainer.find('.'+rmbtnClass).addClass("hide");
            }
            /*
                        cointainer.find('.label-num').text(function(idx){
                                if(rmbtnClass == 'removeBtn')
                                {
                                        return 2 + idx;
                                }else{
                                        return 1 + idx;
                                }

                        });*/

            $('.'+contentClass+' .label-num').each(function(ind,va){
                if(rmbtnClass == 'removeBtn')
                {
                    $(this).text(ind+2);
                }else{
                    $(this).text(ind+1);
                }

            });

            if(inputName != 'undefined')
            {
                indexifyInput( cointainer, inputClass , inputName ,directorClass, directorName);
            }

        });

        //add button
        $('.'+addbtnClass).click( function() {

            var cointainer = $(this).closest('.'+containerClass);
            var content = cointainer.find("."+contentClass+":first");

            // var content = cointainer.find(".sh-contnt-emp");
            var Cloned = content.clone(true,true);

            if(addbtnClass == 'addBtn')
            {
                Cloned.insertBefore($(this)).find('input').val('').end().find('.label-num').text(eval(counterStr)+1);
            }else{
                Cloned.insertBefore($(this)).find('input').val('').end().find('.label-num').text(eval(counterStr));
            }

            if(inputName != 'undefined')
            {
                indexifyInput( cointainer, inputClass , inputName ,directorClass, directorName,shareholderClass,shareholderName);
            }
			
            $(this).closest('.'+containerClass).find('.'+contentClass+":last").addClass('newElement');

            $('.newElement').find('.'+rmbtnClass).removeClass("hide");

            var checkbox = $(this).closest('.'+containerClass).find('.'+contentClass+":last");
            checkbox.find('.isEmployee').val('NEW');
            checkbox.find('.isEmployee').attr('checked',false);


            // Cloned.insertBefore($(this)).find('input').val('').end().find('.label-num').text(eval(counterStr));
            eval(counterStr+'++');
            // cointainer.find('.'+rmbtnClass).removeClass("hide");
            //cointainer.find('.'+rmbtnClass).eq(0).addClass("hide");

            //alert('.'+contentClass+' .newElement');
            $('.'+contentClass+' .label-num').each(function(ind,va){
                if(rmbtnClass == 'removeBtn')
                {
                    $(this).text(ind+2);
                }else{
                    $(this).text(ind+1);
                }
            });


            cointainer.find('.datepicker').each(function(i,v){
                $(this).datepicker("destroy");
                $(this).attr("id",counterStr+i).datepicker({
                    dateFormat: 'dd-mm-yy',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-100:+3"
                });
            });
        });
    }

    function indexifyInput( cointainer, inputClass , inputName ,directorClass, directorName,shareholderClass,shareholderName)
	{
       
		cointainer.find("."+inputClass).each(function(index, val){
            $(this).attr("name",inputName+"["+(index+2)+"]");
        });

        cointainer.find("."+directorClass).each(function(index, val){
            $(this).attr("name",directorName+"["+(index+2)+"]");
        });
		
		cointainer.find("."+shareholderClass).each(function(index, val){
            $(this).attr("name",shareholderName+"["+(index+2)+"]");
        });
    }

    $(document).ready(function() {
        $(document).on('click','.btn-search',function(e){
            e.preventDefault();
            var regex = <?php echo DATE_FORMAT_REGEX; ?>;
            var error = '<?php echo $this->lang->line('INVALID_DATE_FORMAT'); ?>';
            var flag = 0;
            $('.datepicker').each(function(e,v){

                if($(v).val() != '')
                {
                    if($(v).closest('div').children('div.error-field'))
                    {
                        $(v).closest('div').children('div.error-field').remove();
                    }
                    if(!regex.test($(v).val()))
                    {
                        $(v).closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
                        flag = 1;
                    }
                }
            });
            if(flag)
            {
                return false;
            }else{
                $('#client-search').trigger('submit');
            }
        });
    });

    function turnOffTabClick(){
        /*
                $("div.tabbable ul.nav").on('show.bs.tab', "li.disabled a", function(event) {
                        event.stopImmediatePropagation();
                        return false;
                });
                $("div.tabbable ul.nav").off('show.bs.tab', "li:not(.disabled) a");

         */
    }


    function turnOnTabClick(){
        $("div.tabbable ul.nav").off('show.bs.tab', "li.disabled a");
    }
</script>
<script>
    function reviewDetails(id)
    {

        $.ajax({
            type: "POST",
            url: '<?php echo site_url('review'); ?>',
            data: $('#'+id).serialize(),
            beforeSend:function(){
                //$('#review-detail').html('<img src="<?php echo site_url(); ?>assets/images/loading.gif"/>');
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                dialogBox('Error',msg.responseText);
            },
            success:function(msg){
                hideSpinner();
                msg = JSON.parse(msg);
                $('.actionButtons').css('display',msg['style']);
                $('#review-detail').html(msg['link']);
            }
        });
    }

    function fillEmployeeDetail()
    {
        var no_em = 0;

        /* First remove the dynamically added employee list */
        $('#emp_details .newElement').remove();

        var em = 1;
        $('#sh_details .sh-contnt input[type="checkbox"]').each(function(e,v){

            if($(v).is(":checked") && $(v).hasClass('isEmployee'))
            {
                //alert(v.name+' - '+v.value);
                if(v.value != 'EMP' && v.value != 'CHK')
                {
                    no_em++;
                    //$(v).val('CHK');
                }
            }
            em++;
        });
        //console.log('Number of Employee : '+no_em);

        var view = 0;
        if($('[name^="employee_id"]').length > 0)
        {
            view = 1;
        }else{
            view = 2;
            $('#emp_details .sh-contnt-emp '+" input").each(function(i,v){
                $(v).val('');
            });

        }
        //console.log('View : '+view);
        if(view == 2 && no_em > 1)
        {
            if($('#dir1').find('.isEmployee').is(':checked') || $('.sh-contnt:first-child').find('.isEmployee').is(':checked'))
            {
                no_em = Number(no_em - 1);
            }
            for(var x=0;x < no_em;x++)
            {
                copySharer('sh-cont-emp', 'sh-contnt-emp', 'addBtnEmp', 'removeBtnEmp', 'counterEmp');
            }
        }else if(view == 1){
            for(var x=0;x < no_em;x++)
            {
                copySharer('sh-cont-emp', 'sh-contnt-emp', 'addBtnEmp', 'removeBtnEmp', 'counterEmp');
            }
        }

        /* Check If updating the record */
        if($('[name^="employee_id"]').length > 0)
        {
            copySharerData("#sh_details .sh-contnt","#emp_details .newElement");
        }else{
            copySharerData("#sh_details .sh-contnt","#emp_details .sh-contnt-emp");
        }
        //copySharerData("#sh_details .sh-contnt","#emp_details .newElement");
    }

    function copySharerData( from , to ){
        var record = new Array;
        var x = 0;
        //console.log(from);
        $(from).each(function(e,vv){
            var current = $(this).find('input');
            var elem = $(this).find('[name^="IsEmployee"]');
            //console.log(current);
            if(elem.is(':checked') && elem.val() == 'NEW')
            {
                $(current).each(function(i,v){
                    if($(v).attr('type') != 'hidden' && $(v).attr('type') != 'checkbox')
                    {
                        if(!$(v).hasClass('calShares') && !$(v).hasClass('utrnumber'))
                        {
                            record[x] = v.value;x++;
                        }else if($(v).hasClass('utrnumber')){
                            record[x] = '';x++;
                        }
                    }
                });
            }
        });
        x =0 ;

        $.each(record,function(i,v){
            if($('.employee_id').length == 0)
            {
                $(to+" input").eq(x).val(v);x++;
            }else{
                $(to+" input").eq(x+1).val(v);x++;
            }
        });


        $(from+" textarea").each(function(i,v){
            $(to+" textarea").eq(i).html($(this).html());
        });

        $(from+" select").each(function(i,v){
            $(to+" select").eq(i).val($(this).val());
        });
    }

    function copySharer(containerClass, contentClass, addbtnClass, rmbtnClass, counterStr)
    {
        // console.log(eval(counterStr));
        var id = '#emp_details .addBtnEmp';
        var cointainer = $(id).closest('.'+containerClass);
        var content = cointainer.find("."+contentClass+":first");


        // var content = cointainer.find(".sh-contnt-emp");
        var Cloned = content.clone(true,true);
        if(addbtnClass == 'addBtn')
        {
            Cloned.insertBefore($(id)).find('input').val('').end().find('.label-num').text(eval(counterStr)+1);
        }else{
            Cloned.insertBefore($(id)).find('input').val('').end().find('.label-num').text(eval(counterStr));
        }

        $(id).closest('.'+containerClass).find('.'+contentClass+":last").addClass('newElement');
        var checkbox = $(id).closest('.'+containerClass).find('.'+contentClass+":last");
        checkbox.find('.isEmployee').val('NEW');
        checkbox.find('.isEmployee').attr('checked',false);

        cointainer.find('.label-num').text(function(idx){
            if(rmbtnClass == 'removeBtn')
            {
                return 2 + idx;
            }else{
                return 1 + idx;
            }

        });
        // Cloned.insertBefore($(this)).find('input').val('').end().find('.label-num').text(eval(counterStr));
        eval(counterStr+'++');
        $('.newElement').find('.'+rmbtnClass).removeClass("hide");
        cointainer.find('.datepicker').each(function(i,v){
            $(this).datepicker("destroy");
            $(this).attr("id",counterStr+i).datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+3"
            });
        });
    }
	$(document).on('click','.client-access',function(e){ 
		e.preventDefault();
		var client_id = $( this ).attr('data-val');
		alert(client_id);
		if(client_id == '')
			return false;
		$.ajax({
			type: "POST",
			url: "<?php echo site_url(); ?>setcompany",
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','client_id':client_id},
			beforeSend:function(){
				initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
				showSpinner();
			},
			error:function(msg){
				dialogBox('Error',msg.responseText);
			},
			success:function(msg){
				hideSpinner();
				msg = JSON.parse(msg);
				if(msg.success)
				{
					if(msg.url)
						window.location.href=msg.url;
					else{						
						var optHtml = '';
						optHtml += '<option value="">Select Company</option>';
						$.each(msg.allCompanies, function( index, value ) 
						{						
							optHtml += '<option value="'+value.CID+'">'+value.Name+'</option>';							
						});
						$('.chooseCompany').html(optHtml);
						$('.modal-choose-company').css({width: '100%', height: 'auto', 'max-height': '100%'});
						$('.modal-title').html('<?php echo $this->lang->line('CHOOSE_COMPANYLOGIN');?>');
						$('.modal-body123').modal('show');
						$('.modal-body123').append('<input type="hidden" id="clientIDD" value="'+client_id+'" />');
						$('.modal-choose-company').modal('show');
					}
				}
			}
		});
	});
	$(document).on('click','.client-access-close',function(e){ 
		$('.modal-choose-company').modal('hide');
		$(".modal-backdrop").remove();
	});
	$(document).on('change','#chooseCompanySelect',function(e){ 
		e.preventDefault();
		var company = $( this ).val();
		var client_id = $( '#clientIDD' ).val();
		if(company == '' && client_id == '' )
		{ 
			$( '#chooseCompanySelect' ).css('border','1px solid #ff0000');
			return false;
		}
	
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('choose-company'); ?>",
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','company':company,'client_id':client_id},
			beforeSend:function(){
				initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
				showSpinner();
			},
			error:function(msg){
				dialogBox('Error',msg.responseText);
			},
			success:function(msg){
				hideSpinner();
				msg = JSON.parse(msg);
				if(msg.success)
				{
					if(msg.url)
						window.location.href=msg.url;
					else{
						
						var optHtml = '';
						$.each(msg.allCompanies, function( index, value ) 
						{						
							optHtml += '<option value="'+value.CID+'">'+value.Name+'</option>';							
						});
						$('.chooseCompany').html(optHtml);
						$('.modal-choose-company').css({width: '100%', height: 'auto', 'max-height': '100%'});
						$('.modal-title').html('<?php echo $this->lang->line('CHOOSE_COMPANYLOGIN');?>');
						$('.modal-body123').modal('show');
						$('.modal-choose-company').modal('show');
					}
				}
			}
		});
	});
	$(document).on('click','.editAccess',function(e){ 
		var clientId = $( this ).attr('data-val');
		if(clientId == '')
			return false;
		
		$.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>edit-access",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', "clientId": clientId,'task':'view'},
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error: function (msg) {
                hideSpinner();
            },
            success: function (msg) { 
                hideSpinner();
                $('.modal-edit-access .modal-title').html('Manage Client Privilege');
				$('.modal-edit-access').modal('show');
				$('.modal-edit-access .modal-body').html(msg);              
            }
        });
		
	});
	$(document).on('click','#checkAllPriviliges',function(e){
		if(this.checked)
			$( '.privilgeChkBox' ).prop('checked',true);
		else
			$( '.privilgeChkBox' ).prop('checked',false);
	});
	$(document).on('click','.privilgeChkBox',function(e){
		if(!this.checked)
			$( '#checkAllPriviliges' ).prop('checked',false);		
	});
	$(document).on('click','#edit-access-submit',function(e){
		var clientId = $( '#hiddenClientID' ).val();
		//alert(clientId);
		if(clientId == '')
			return false;
		
		$.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>edit-access",
            data: $('#clientEditAccess').serialize(),
			dataType:'json',
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error: function (response) {
			//	alert(response);
			console.log(response);
                hideSpinner();
				
            },
            success: function (response) { 
			//alert('pass');
			console.log(response);
                hideSpinner();
				if(response.success)
				{
					
					$("#messgaeDiv").html( response.success_msg );
					$("#messgaeDiv").fadeIn();
					setTimeout( function(){
						$("#messgaeDiv").fadeOut("slow");
						$('.modal-edit-access').modal('hide');
					},3000 );
					
				}
				else
				{
					$("#messgaeDiv").html( response.error_msg );	
					$("#messgaeDiv").fadeIn();					
				}				
				setTimeout( function(){
					$("#messgaeDiv").fadeOut("slow");
				},3000 );				
            }
        });
	});	
	
		
	 $(document).on('blur','#CompanyName',function(e){
		  e.preventDefault();
            var CompanyName = $(this).val();
			if(CompanyName != ''){			
				$.ajax({
					type: "POST",
					url: "<?php echo site_url(); ?>validateCompanyName",
					data: {"CompanyName": CompanyName},
					dataType:'json',
					beforeSend: function () {
						initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
						showSpinner();
					},
					error: function (response) {
						hideSpinner();
					},
					success: function (response) { 
						hideSpinner();						
						if(response.success){		
							dialogBox('Error Message','Company Name Should Be Unique');
							$( "#CompanyName" ).val('');
							//$( "#CompanyName" ).focus();
						}
					}
				});	
			}
        });
</script>
