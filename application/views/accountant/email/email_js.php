<script>
    tinymce.init({selector: 'textarea'});
    // add multiple select / deselect functionality
    $(document).ready(function() {
        $('#selectall').on('click', function() {
            if (this.checked) {
                $('.checkbox').each(function() {
                    this.checked = true;
                });
                $('.send-client-mail').css('display', 'block');
            } else {
                $('.checkbox').each(function() {
                    this.checked = false;

                });

                $('.send-client-mail').css('display', 'none');

            }
        });
		 $('.checkbox').on('click', function() {
            var len = $('.checkbox:checked').length;

            if ($('.checkbox:checked').length == $('.checkbox').length) {
                $('#selectall').prop('checked', true);
                if ($('.checkbox:checked').length > 0) {
                    $('.send-client-mail').css('display', 'block');
                }
            } else {
                $('#selectall').prop('checked', false);
                if ($('.checkbox:checked').length > 0) {
                    $('.send-client-mail').css('display', 'block');
                } else {
                    $('.send-client-mail').css('display', 'none');
                }
            }
        });
		
		$('#selectall2').on('click', function() {			
            if (this.checked) {
                $('.checkbox2').each(function() {
                    this.checked = true;
                });
                $('.send-client-mail').css('display', 'block');
            } else {
                $('.checkbox2').each(function() {
                    this.checked = false;
                });
                $('.send-client-mail').css('display', 'none');
            }
        });

        $('.checkbox2').on('click', function() {
            var len = $('.checkbox2:checked').length;

            if ($('.checkbox2:checked').length == $('.checkbox2').length) {
                $('#selectall2').prop('checked', true);
                if ($('.checkbox2:checked').length > 0) {
                    $('.send-client-mail').css('display', 'block');
                }
            } else {
                $('#selectall2').prop('checked', false);
                if ($('.checkbox2:checked').length > 0) {
                    $('.send-client-mail').css('display', 'block');
                } else {
                    $('.send-client-mail').css('display', 'none');
                }
            }
        });
    });

    // if all checkbox are selected, check the selectall checkbox
    // and Click send mail open modal
    $(document).on('click', '.btn-send-mail', function(e) {
        e.preventDefault();
        $('.modal-title').html('Email Template');
        $('.select-email-template').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>accountant/email/selemailTemplate",
            data: '',
            beforeSend: function() {
                $('.modal-body').html('<img src="<?php echo site_url(); ?>assets/images/loading.gif"/>');
            },
            error: function(msg) {
                // alert(msg.responseText);
            },
            success: function(msg) {
                $('.modal-body').html(msg);
            }
        });
    });

    $(document).on('change', '#template_type', function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>accountant/email/selemailtemplateType",
            data:{
                'Id':$(this).val()
            },
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                hideSpinner();
            },
            success: function(msg) {
                hideSpinner();
                tinyMCE.activeEditor.setContent(msg);

            }
        });
    });

    $(document).on('click', '.add-email-template', function(e) {
        e.preventDefault();
        $('.modal-title').html('Add Template');
        $('.select-email-template').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>accountant/email/addemailTemplate",
            data: '',
            beforeSend: function() {
                $('.modal-body').html('<img src="<?php echo site_url(); ?>assets/images/loading.gif"/>');
            },
            error: function(msg) {
                // alert(msg.responseText);
            },
            success: function(msg) {
                $('.select-email-template .modal-body ').html(msg);
            }
        });
    });
   
    $(document).on('click', '#bulk-email', function(e) {
        e.preventDefault();
      var matches = [];
      var companyIds = [];
        $("input:checked").each(function() {
          matches.push($(this).val());
          companyIds.push($(this).attr('data-value'));
        });
        $('#clientId').val(matches);
        $('#companyId').val(companyIds);
        if($('#clientId').val()==''){
            dialogBox('Message','<?php echo $this->lang->line('ERROR_ADD_TEMPLATE_CLIENT'); ?>');
             
        }else{
            $('#frmbulkemail').submit();
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
	
	$(document).on('focus', '.sDatepicker', function () {
		$(this).datepicker({
			dateFormat: 'dd-mm-yy',
			changeMonth: true,
			changeYear: true,
			yearRange: "-5:+0"
		});
    });
	$(document).on('focus', '.eDatepicker', function () {
		$(this).datepicker({
			dateFormat: 'dd-mm-yy',
			changeMonth: true,
			changeYear: true,
			yearRange: "-5:+0"
		});
    });
	$(document).on('focus', '.yDatepicker', function () {
		$(this).datepicker({
		   dateFormat: 'dd-mm-yy',
			changeMonth: true,
			changeYear: true,
			yearRange: "-5:+0"
		});
    });

	
	
	$(document).on('change','#Status', function(e){
		if(this.value == 'BANK_STATMENT_DUE'){
			$("#filterby").val("0");
			$("#Days_relation_with").val("0");	
			$("#Quarters_relation_with").val("0");
			$("#fieldsection1").hide();
			$("#fieldsection2").show();
			$("#mail_type").val("bank");			
		}else{			 
			$(".sDatepicker").val('');			
			$("#filterby").val("0");
			$("#filterbydays").hide();
			$("#filterbyquarters").hide();
			$("#fieldsection2").hide();
			$("#fieldsection1").show();
			$("#mail_type").val("vat");
		}
	});
	$(document).on('change','#filterby', function(e){			
		if(this.value == 'Days'){				
			$("#Quarters_relation_with").val("0");
			$("#filterbyquarters").hide();
			$("#filterbydays").val(0);
			$("#filterbydays").show();
		}else{
			$("#Days_relation_with").val("0");				
			$("#filterbydays").hide();
			$("#filterbyquarters").val(0);
			$("#filterbyquarters").show();
		}
	});	
	
</script>   