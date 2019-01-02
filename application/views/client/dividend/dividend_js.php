<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$user = $this->session->userdata('user');
$j_date = get_filed_year();
?>
<script>
    var dividendtask = '';
    var usertask = '';
    var number_shares = 0;
    var return_url = '';
    function openExpenseForm(task,id,title)
    {
        $('.modal-title').html(title);
        $('.modal-dividend').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>clients/dividend/newDividend/",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"ID":id},
            beforeSend:function(){
                $('.modal-body').html('<img src="<?php echo site_url(); ?>assets/images/loading.gif"/>');
            },
            error:function(msg){
                //alert(msg.responseText);
            },
            success:function(msg){
                $('.modal-body').html(msg);
                //alert(usertask);
                if(usertask != 'vd')
                {
                    if($('#ShareHolders').val() == 0)
                    {
                        $('#payViaDirector').css('display','none');
                        $('[name^="directorLoan"]').attr('disabled',true);
                    }else{
                        //alert($('#ShareHolders').val());
                        $('[name^="directorLoan"]').attr('disabled',false);
                        $('#payViaDirector').css('display','block');
                        url = "<?php echo site_url(); ?>clients/dividend/checkShareHolderType/";
                        action($('#ShareHolders').val(),'chk',url);
                    }
                }
                if($('#IsPaid').is(':checked'))
                {
                    $('.paidDate').css('display','block');
                }
            }
        });
    }
    $(document).ready(function(){
        /* Datepicker setting for dividend view */
        $(document).on('focus','.dDatepicker',function(){
            $(".dDatepicker").datepicker({ 
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-50:+0",
                minDate: '<?php echo $j_date; ?>'
            });			
        });
		
        /* Hover effect over the label of expense listing */
        $(document).on('mouseover','.sort,.markPaid,.copyDividend',function(e){
            $('[data-toggle="tooltip"]').tooltip({
                show: null,
                position: {
                    my: "left top",
                    at: "left bottom"
                },
                open: function( event, ui ){
                    ui.tooltip.animate({ top: ui.tooltip.position().top + 10 }, "fast" );
                }
            });
        });
		
        /* Set the bootstrap modal size */
        $(document).find('.modal-dialog').css({width:'80%',height:'auto','max-height':'100%'});
        $('.modal-expenses').on('hide.bs.modal',function () {
        });
		
        $('.modal-expenses').on('show.bs.modal', function () {
            $.fn.modal.Constructor.prototype.enforceFocus = function () { };
        });
        /* Open the form to create the dividend */
        $(document).on('click','.openDividendForm',function(e){
            e.preventDefault();
            var task = "<?php echo $this->encrypt->encode('addDividend'); ?>";
            var title = "<?php echo $this->lang->line('DIVIDEND_NEW_FORM_TITLE'); ?>";
            openExpenseForm(task,'',title);
        });
		
        /* Validate the amount and miles input fields */
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
		
        /* Insert dividend values in database */
        $(document).off('click','.createDividend').on('click','.createDividend',function(e){
            e.preventDefault();
            dividendtask = '<?php echo $this->encrypt->encode('newdividend'); ?>';
            var text = '<?php echo $this->lang->line('DIVIDEND_NEW_SAVE_CONFIRMATION'); ?>';
            validateFormFields(text);
			
        });
		
        $(document).on('click','.updateDividend',function(e){
            e.preventDefault();
            usertask = 'u';
            dividendtask = '<?php echo $this->encrypt->encode('updatedividend'); ?>';
            var text = '<?php echo $this->lang->line('DIVIDEND_UPDATE_CONFIRMATION'); ?>';
            validateFormFields(text);
        });
		
        $(document).on('click','.markPaid',function(e){
            e.preventDefault();
            var task = $(this).attr('href');
            var title = 'Message';
            var text = '<?php echo $this->lang->line('DIVIDEND_PAID_CONFIRM_TEXT'); ?>';
            var other = $(this).closest('tr td').siblings(':nth-child(2)').text();
            var delmsg = '<?php echo $this->lang->line('DIVIDEND_PAID_MESSAGE'); ?>';
            other = delmsg.replace('{%s}',other);
            var datetext = "Please choose the paid date <input type='text' name='paidDate'id='paidDate' class='dDatepicker' readonly/><br/><br/>";
			
            text = datetext+text;
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
                        var url = "<?php echo site_url(); ?>clients/dividend/action/";
                        var act = 'ajax';
                        var paid = $('#paidDate').val();
                        action(task,act,url,other,paid)
                    },
                    No:	function(){
                        $(this).dialog('close');
                    }
                }
            });
        });
		
        $(document).off('submit','#dividendForm').on('submit','#dividendForm',function(e){
            e.preventDefault();
            $('.modal').modal('hide');
            if(usertask == 'u')
            {
                var url = '<?php echo site_url() . 'clients/dividend/update'; ?>';
            }else{
                var url = '<?php echo site_url() . 'clients/dividend/save'; ?>';
            }
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+"&task="+dividendtask,
                beforeSend:function(){
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error:function(msg){
                    hideSpinner();
                },
                success:function(msg){
                    //$('.modal-dividend').modal('hide');
                    hideSpinner();
                    msg = JSON.parse(msg);
                    dividendtask = 'task-completed';
                    return_url = msg['link'];
                    if(msg['ajax_add'] != 'ajax_add' && msg['ajax_add'] != 'bank_ajax_add')
                    {
                        dialogBox('Message',msg['msg']);
                        $('.modal-statements').modal('hide');
                        $('.modal-dashboard').modal('hide');
                        location.reload(true);
                    }else if(msg['ajax_add'] == 'bank_ajax_add'){
                        $('.modal-statements').modal('hide');
                        //window.location = '<?php echo site_url() . 'clients/banks/'; ?>';
                        location.reload(true);
                    }else{
                        $('.modal-statements').modal('hide');
                        $('#statement-listing').html(msg['html']);
                        window.location = '<?php echo site_url() . 'clients/banks/before_upload/'; ?>';
                    }
                }
            });
        });
		
        /* Check if the choosen Shareholder is director or not */
        $(document).off('change','select#ShareHolders').on('change','select#ShareHolders',function(e){
            e.stopPropagation();
            var task = $(this).val();
            var url = "<?php echo site_url(); ?>clients/dividend/checkShareHolderType/";
            if(task == 0)
            {
                $('#payViaDirector').css('display','none');
                $('.showShareHolderFields').css('display','none');
                $('[name^="directorLoan"]').attr('disabled',true);
            }else{
                $('[name^="directorLoan"]').attr('disabled',false);
                action(task,'chk',url);
            }
            //$(document).find('#ShareHolders').unbind('change');
        });
		
		
		
        /* Open form to edit the dividend */
        $(document).on('click','.editDividend',function(e){
            e.preventDefault();
            var id = $(this).attr('href');
            var task = "<?php echo $this->encrypt->encode('editDividend'); ?>";
            var title = "<?php echo $this->lang->line('DIVIDEND_EDIT_FORM_TITLE'); ?>";
            usertask = '';
            openExpenseForm(task,id,title);
        });
		
        /* View the paid dividend voucher */
        $(document).on('click','.viewDividend',function(e){
            e.preventDefault();
            var id = $(this).attr('href');
            var task = "<?php echo $this->encrypt->encode('viewDividend'); ?>";
            var title = "<?php echo $this->lang->line('DIVIDEND_VIEW_FORM_TITLE'); ?>";
            var vn = $(this).text();
            title = title.replace('%s',vn);
            usertask = 'vd';
            openExpenseForm(task,id,title);
        });
		
        /* Copy the dividend voucher */
        $(document).on('click','.copyDividend',function(e){
            e.preventDefault();
            var id = $(this).attr('href');
            var task = "<?php echo $this->encrypt->encode('copyDividend'); ?>";
            var title = "<?php echo $this->lang->line('DIVIDEND_EDIT_FORM_TITLE'); ?>";
            openExpenseForm(task,id,title);
        });
        /* Reset the search fields */
        $(document).on('click','.reset',function(e){
            e.preventDefault();
            $('#SharerName').val(0);
            $('#NetAmount').val('');
            $('#VoucherNumber').val('');
            $('#GrossAmount').val('');
            $('#dStartDate').val('');
            $('#dEndDate').val('');
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>clients/dividend/clean/",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
                beforeSend:function(){
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error:function(msg){
                    alert(msg.responseText);
                },
                success:function(msg){
                    hideSpinner();
                    msg = JSON.parse(msg);
                    $('#dividend-listing').html(msg['items']);
                    $('.dPagination').html(msg['pagination']);
                }
            });
        });
		
        /* This block performs the sorting operation */
        $(document).on('click','.sort',function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            var text = $(this).text();
            var se = $(this);
            var dir = '';
			
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>clients/dividend/dividendSort/",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',order:href},
                beforeSend:function(){
				
                },
                error:function(msg){
                    dialogBox('Error',msg);
                },
                success:function(msg){
                    //dialogBox('Message',msg);
                    if($('.table-responsive th a').children('i').length > 0)
                    {
                        $('.table-responsive th a i').remove();
                    }
                    se.append('<i class="fa fa-sort-desc"></i>');
                    msg = JSON.parse(msg);
                    se.children('i').addClass(msg[1]);
                    $('#dividend-listing').html(msg[0]);
                    //$('.expenseListing').html(msg);
                }
            });
        });
		
        $(document).on('click','.deleteDividend',function(e){
            e.preventDefault();
            var task = $(this).attr('href');
            var title = '<?php echo $this->lang->line('DIALOG_DELETE_CONFIRM_TITLE'); ?>';
            var text = '<?php echo $this->lang->line('DIVIDEND_DELETE_CONFIRM_TEXT'); ?>';
            var other = $(this).closest('tr td').siblings(':nth-child(2)').text();
            var delmsg = '<?php echo $this->lang->line('DIVIDEND_DELETE_MESSAGE'); ?>';
            other = delmsg.replace('{%s}',other);
			
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
                        var url = "<?php echo site_url(); ?>clients/dividend/action/";
                        var act = 'ajax';
                        action(task,act,url,other)
                    },
                    No:	function(){
                        $(this).dialog('close');
                    }
                }
            });	
        });
		
        $(document).on('change','#dividendAmount',function(e){
            if(number_shares != 0)
            {
                var share = Number($(this).val()/number_shares);
                $('#dividendPerShare').text(share.toFixed(2));
            }
        });
		
        $(document).on('change','#IsPaid',function(e){;
            if($(this).is(':checked'))
            {
                $('.paidDate').css('display','block');
            }else{
                $('.paidDate').css('display','none');
            }
        });
		
        $(document).on('click','.btn-search',function(e){
            e.preventDefault();
            var regex = <?php echo DATE_FORMAT_REGEX; ?>;
            var error = '<?php echo $this->lang->line('INVALID_DATE_FORMAT'); ?>'
            var flag = 0;
            $('.dDatepicker').each(function(e,v){
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
                $('#dividendSearch').trigger('submit');
            }
        });
    });
	
    function action(task,act,url,other,paid)
    {
        $.ajax({
            type: "POST",
            //url: "<?php echo site_url(); ?>clients/dividend/action/",
            url: url,
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"PaidDate":paid},
            cache:false,
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                alert(msg.responseText);
            },
            success:function(msg){
                hideSpinner();
                //dialogBox('Message',msg);return false;
                if(act == 'chk')
                {
                    if(msg == 'link')
                    {
                        //window.location = '<?php echo site_url() . 'clients/dividend' ?>';
                    }else{
                        msg = JSON.parse(msg);
                        //console.log(msg);
                        number_shares = Number(msg['shares']);
						
                        /* Check if number of shares is less then zero */
                        if(number_shares <= 0)
                        {
                            var title = 'Message';
                            var text = '<?php echo $this->lang->line('DIVIDEND_SHARES_ZERO_ERROR'); ?>'
                            dialogBox(title,text);
                            $('.modal').modal('hide');
                        }
						
                        if($('#dividendAmount').val() != '')
                        {
                            var amount = Number($('#dividendAmount').val())/number_shares;
                            $('#dividendPerShare').html(amount.toFixed(2));
                        }
                        $('#shareHoldersShares').text(number_shares);
                        $('.showShareHolderFields').css('display','block');
                        $('#payViaDirector').css('display',msg['style']);
                        $('#addressParams').val(msg['addressParams']);
                        $('#shareholderaddress').val(msg['shareHolderaddressParams']);
                        if(msg['style'] == 'block')
                        {
                            $('[name^="directorLoan"]').attr('disabled',false);
                        }else{
                            $('[name^="directorLoan"]').attr('disabled',true);
                        }
                    }
                }else if(act == 'ajax'){
                    if(msg == 'error')
                    {
                        dialogBox('Message',"<?php echo $this->lang->line('DIVIDENT_UNEXPECTED_ERROR'); ?>");
                    }else{
						
                        dialogBox('Message',other);
                        msg = JSON.parse(msg);
                        $('#dividend-listing').html(msg['items']);
                        $('.dPagination').html(msg['pagination']);
                    }
                }
            }
        });
        return true;
    }
	
    function validateFormFields(text)
    {
        error = 'This field is required';
        if(!validateField('.required',error))
        {
            return false;
        }
        var regex = <?php echo DATE_FORMAT_REGEX; ?>;
        var error = '<?php echo $this->lang->line('INVALID_DATE_FORMAT'); ?>';
        if($('#dividendDate').val() != '')
        {
            if(!regex.test($('#dividendDate').val()))
            {
                if($('#dividendDate').closest('div').children('div.error-field'))
                {
                    $('#dividendDate').closest('div').children('div.error-field').remove();
                }
                $('#dividendDate').closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
                return false;
            }
        }
		
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
                    $('#dividendForm').trigger('submit');
                },
                No:	function(){
                    $(this).dialog('close');
                }
            }
        });	
    }
	
    function validateField(id,error)
    {
        var flag = 0;
        $(id).each(function(e,v){
            id = v;
            if($(id).val() == '' || $(id).val() == 0)
            {
                if($(id).closest('div').children('div.error-field'))
                {
                    $(id).closest('div').children('div.error-field').remove();
                }
                $(id).closest('div').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>');
                flag = 1;
            }else{
                if($(id).closest('div').children('div.error-field'))
                {
                    $(id).closest('div').children('div.error-field').remove();
                }
            }
        });
        if(flag == 1)
        {
            return false;
        }else{
            return true;
        }
    }
	
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
                    if(dividendtask == 'task-completed')
                    {
                        window.location = return_url;
                    }else{
                        location.reload(true);
                    }
					
                }
            }
        });	
    }
	
</script>