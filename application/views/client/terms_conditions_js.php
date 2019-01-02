<script>    
    function view_term(){
        $('.term-condition-view-details').modal('show');
        $('.modal-title').html('Terms and conditons');
        if($("#tcid").val()!=''){
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>client/viewTermandconditons", 
                data: {
                    'id':$("#tcid").val()
                },
                dataType: "text",  
                cache:false,
                beforeSend:function(){
                    $('.modal-body-term').html('<img src="<?php echo site_url(); ?>assets/images/loading.gif"/>');
                },
                error:function(msg){
                    //alert(msg.responseText);
                },
                success: 
                    function(data){
                    //$('.modal-body-term').css('padding','2px 5px 2px 10px');
                    $('.modal-body-term').html(data);
               
                            
                }
            });
        }else{
            $('.modal-body-term').html('<center><strong>Terms and conditions not found</strong></center>');
        }
    }
</script>                