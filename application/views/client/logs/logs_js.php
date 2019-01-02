<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
    $(document).ready(function(){  
	
	
	jQuery(function($){
			$(".sDatepicker").mask("99-99-9999");
	
		});
	/*
			jQuery('body').on('dblclick','.sDatepicker',function($){	
			//alert('test');			 
			 jQuery(this).datepicker();
			 jQuery(this).datepicker("show")({
				 
				 
			//$(document).on('focus','.sDatepicker',function(){
            $(document).find('.modal-dialog').css({width:'90%',height:'auto','max-height':'100%'});
            //$(this).datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-5:+0"
            });
        });
				*/ 
			 
        $(document).on('dblclick','.sDatepicker',function(){
            $(document).find('.modal-dialog').css({width:'90%',height:'auto','max-height':'100%'});
			 jQuery(this).datepicker();
            $(this).datepicker("show")({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-5:+0"
            });
        });
    });
    
    $(document).on('click','.btn-log-search',function(){
        var data = $('#from-log-search').serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>clients/logs/searchLog",
            data:data,
            dataType: "json",
            cache:false,
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                hideSpinner();
            },
            success:function(data){
                hideSpinner();
                //console.log(data);
                $('#log-tbody').html(data);

            }
        });
    });

    /*
            Show log details from action logs
     */
    $(document).on('click','.showLogDetails',function(e){            
        e.preventDefault();
        var id = $(this).attr('id');
        var task = $(this).attr('data-type');
        var split =task.split('===');
       
        var title = "Log Details("+split[1]+")";
        task=split[0];
        openLogDetailsForm(task,id,title);
    });
 

    function openLogDetailsForm(task,id,title){
        
        $('.modal-title').html(title);
        $('.modal-view-logs').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>show_log_details",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"ID":id},
            beforeSend:function(){
                $('.modal-body').html('<img src="<?php echo site_url(); ?>assets/images/loading.gif"/>');
            },
            error:function(msg){                
                // alert(msg.responseText);
            },
            success:function(msg){				
                $('.modal-body').html(msg);
            }
        });
    }

</script>
