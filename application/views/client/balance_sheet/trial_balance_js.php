<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script>
    $(document).ready(function(){
        $(document).on('change','#TBYear',function(){
            var TBYear = $( this ).val();
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>balance_sheet",
                dataType: 'html',
                data: {
                    '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                    'TBYear':TBYear,
                    'ajax':'true'
                },
                beforeSend:function(){
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error:function(msg){
                    dialogBox('Error',msg);
                },
                success:function(msg){
                    var response = JSON.parse(msg);
                    hideSpinner();
                    $('.financial_date').html(response.FIN_DATE);
                    if(response.hasOwnProperty('HTML')){
                        $('#TBListing').html( response.HTML );
                        $('[data-toggle="tooltip"]').tooltip({placement: 'right'});
                    }else{
                        $('#TBListing').html( '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+
                            '<?php echo $this->lang->line("NO_TB_RECORD_FOUND"); ?>'
                            +'</div>' );
                    }
					
                }
            });
        });
    });
</script>