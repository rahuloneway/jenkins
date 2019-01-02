<script type="text/javascript">
       
    $(document).ready(function(){  
        $(document).on('focus','.sDatepicker',function(){
            $(document).find('.modal-dialog').css({width:'90%',height:'auto','max-height':'100%'});
            $(this).datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-5:+0"
            });
        });
    });
    $(document).ready(function() { 
        $(document).on('click','.showemailLogDetails',function(e){
            e.preventDefault();
            var id = $(this).attr('id');
            $('.modal-view-email-logs .modal-title').html('View Email');
            $('.modal-view-email-logs').modal('show');
            $('.modal-view-email-logs').css({width: '100%', height: 'auto', 'max-height': '100%'});
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>accountant/Email/viewEmail",
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
                    $('.modal-view-email-logs .modal-body').html(msg);
                }
            });
        });
    });
    
 
</script>