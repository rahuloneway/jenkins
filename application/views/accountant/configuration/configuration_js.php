<script type="text/javascript">	
    $(document).ready(function(e){
        $('.datepicker').datepicker({
            dateFormat: '<?php echo CASHMAN_DATE_FORMATE; ?>',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+3"
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
        $(document).on('change','.bike_one',function(e){
            $('.bike_two').val($(this).val());
        });
	
        $(document).on('change','.cycle_one',function(e){
            $('.cycle_two').val($(this).val());
        });
	
        $(document).on('click','.add-item',function(e){
            e.preventDefault();
            var html = $('.taxable-income:first-child').clone();
            $('.tax-income').append(html);
            $('.taxable-income:last-child input').each(function(i,e){
                $(this).val('');
            });
            $('.taxable-income').each(function(i,e){
                $(this).find('.remove-item').closest('div.hide').removeClass('hide');
            });
        });
	
        $(document).on('click','.remove-item',function(e){
            e.preventDefault();
            $(this).closest('.taxable-income').remove();
            checkRemoveButton();
        });
    });
    function checkRemoveButton()
    {
        var len = $('.taxable-income').length;
        if(len == 1)
        {
            $('.taxable-income').find('.remove-item').closest('div').addClass('hide');
        }
    }


    $(document).on('click', '.config-add-email-template', function(e) {
        e.preventDefault();
        $('.templateName').css('display','none');
        $('.templateText').css('display','none');
        $('.modal-title').html('Add Template');
        $('.select-email-template').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>accountant/configuration/addemailTemplate",
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
    $(document).on('click', '.config-edit-email-template', function(e) {
        e.preventDefault();
        $('.templateName').css('display','none');
        $('.templateText').css('display','none');
        $('.modal-title').html('Edit Template');
        $('.select-email-template').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>accountant/configuration/editemailTemplate",
            data: {
                'id':$(this).attr('id')
            },
            beforeSend: function() {
                $('.modal-body').html('<img src="<?php echo site_url(); ?>assets/images/loading.gif"/>');
            },
            error: function(msg) {
                // alert(msg.responseText);
            },
            success: function(msg) { 
                var obj = jQuery.parseJSON( msg);
                $('.select-email-template .modal-body ').html(obj);
            }
        });
    });
  
    $(document).on('click', '.addemailTemplate,.edit-template', function(e) {
        e.preventDefault();
        
        if($('#templateName').val()==''){
            $('.templateName').css('display','block');
            return false;
        }else if(tinyMCE.get('templateText').getContent()==''){
            $('.templateText').css('display','block');
            return false;
        }else{
            $('#fromEmailtemplate').submit();
        }
         
    });  
</script>