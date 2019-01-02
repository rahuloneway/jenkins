<script type="text/javascript">
    tinymce.init({selector: 'textarea'});
    /*$(document).on('change','#file',function(e){
        var size =(this.files[0].size)/1024;
        var sizeInMb = this.files[0].size/1024;
        var sizeLimit= 1024*2; // if you want 5 MB
        if(sizeInMb > sizeLimit){ 
            this.value = null;
            this.value = '';
            $('#file').val('');
            dialogBox('Message','<?php echo $this->lang->line('CASHAMN_CLIENT_TERMPDF_SIZE_ERROR'); ?>');
        }
        if ($("#file").val().split(".")[1].toUpperCase() == "PDF"  || $("#file").val().split(".")[1].toUpperCase() == "pdf") 
        {
            return true;
        }
        else{
            this.value = null;
            this.value = '';
            $('#file').val('');
            dialogBox('Message','<?php echo $this->lang->line('CASHAMN_CLIENT_TERMPDF_PDF_ERROR'); ?>');
        }
    });	
     */  
    $(document).on('change','.clientNmae',function(e){
        $('#CompanyName').val('');
        if($(this).val()==''){
            dialogBox('Message','<?php echo $this->lang->line('CASHAMN_TERM_CLIENT_SELECT'); ?>');
        }else{
            $('#clientId').val($(this).val());
            $('#termsearch').submit();
        }
    });	
    $(document).on('change','.Status',function(e){
        $('#CompanyName').val('');
        if($(this).val()==''){
            dialogBox('Message','<?php echo $this->lang->line('CASHAMN_TERM_STATUS_SELECT'); ?>');
        }else{
            $('#termsearch').submit();
        }
    });	
    $(document).on('click','.uploadtermStatements',function(e){
        e.preventDefault();
        $('.modal-view-term-from .modal-title').html('Upload terms and conditions');
        $('.modal-view-term-from').modal('show');
        $('.modal-view-term-from').css({width: '100%', height: 'auto', 'max-height': '100%'});
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>accountant/Terms/uploadFrom",
            data: {'Upload':'upload'},
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();		
            },
            error:function(msg){
                dialogBox('Error',msg);
                hideSpinner();
            },
            success:function(msg){
                hideSpinner();
                $('.modal-view-term-from .modal-body').html(msg);
            }
        });
    });
    $(document).ready(function() { 
        $(document).on('click','.terms-conditions-from-select',function(e){
            e.preventDefault();
            
            var id = $(this).attr('id');
            $('.modal-view-term-template-from .modal-title').html('Send email');
            $('.modal-view-term-template-from').modal('show');
            $('.modal-view-term-template-from').css({width: '100%', height: 'auto', 'max-height': '100%'});
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>accountant/Terms/templateForm",
                data: {'id':id},
                beforeSend:function(){
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();		
                },
                error:function(msg){
                    dialogBox('Error',msg);
                    hideSpinner();
                },
                success:function(msg){
                    
                    hideSpinner();
                    $('.modal-view-term-template-from .modal-body').html(msg);
                }
            });
        });
    });
    $(document).ready(function() {
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
    });
    
    $(document).ready(function() {
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
    });
    
    $(document).ready(function() {
        $(document).on('click', '.term-send-email', function(e) {
            var subject =$('.subject').val();
            if(subject==''){
                $('.subject').css('display','block');
            }else if(tinyMCE.get('email_text').getContent()==''){
                $('.email_text').css('display','block');
                return false;
            }
            else{
                $('.alert-danger').css('display','none');
                $('#sendemail').submit();
            }
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
                }
            }
        });	
    }
    $(document).ready(function() {
        $(document).on('change', '#company', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>accountant/Terms/selectClient",
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
                    console.log(msg);
                    $('.company-dropdown').html(msg);
                }
            });
        });
    });
</script>