<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$config = settings();
$vat_listing = checkvatifExist();
$vat_percent = '';
if (empty($vat_listing->Type)) {
    $vat_percent = 0;
} else {
    $vat_percent = $config['VAT_percentage'];
}

$user = $this->session->userdata('user');
$isCISRegistered = $user['Params']['isCISRegistered'];
if( $isCISRegistered == 'yes')
	$cis_percentage  = $user['Params']['cis_percentage'];
else
	$cis_percentage  = 0;
$j_date = get_filed_year();
?>
<script>
    var  isCISRegistered = '<?php echo $isCISRegistered;?>';
	var  cis_percentage  = '<?php echo $cis_percentage;?>';
			
    var vat_percent 	= Number("<?php echo $vat_percent; ?>");
    var counter = 2;
    var g = 0;// For grand total
    var vat = 0;
    var boxResult = '';
    var subTotal = new Array;
    var invoicetask = '';
    var userTask = '';
    var ajaxcall = false;
    function openInvoiceForm(task,id,title,type)
    {
        $('.modal-title').html(title);		
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>edit_invoice",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"InvoiceID":id,"task":task,'type':type},
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                hideSpinner();
            },
            success:function(msg){
				console.log(msg);
                hideSpinner();
                $('.modal-body').html(msg);
                $('.modal-invoice').modal('show');
                afterAJAXCall();
            }
        });
    }
	
    function afterAJAXCall()
    {
        if(userTask == 'c')
        {
            $('#addCustomerDetail').css('display','block');
            $('#addcustomer').addClass('disabled');
            $('#customerName').attr('readonly','readonly');
            $('#customerAddress').attr('readonly','readonly');
        }
        calculations();
    }
	
    function calculations()
    { 
        var d = $('[name^="description"]');
        var q = $('[name^="quantity"]');
        var u = $('[name^="unitprice"]');
        var v = $('[name^="vat["]');
		if( isCISRegistered == 'yes')
			var c = $('[name^="cis_percentage["]'); // cis percentage
		
        var Sub = 0;
        var Vat = 0;
        var Cis = 0;
        var gbp = 0;
        var totalInvoice = 0;
		
        for(i=0;i<q.length;i++)
        {
			if( Number(q[i].value) == 0 && Number(u[i].value) == 0)
				continue;			
            gbp  = Number(q[i].value)*Number(u[i].value);
            Sub += Number(q[i].value)*Number(u[i].value);
            gbp  = (Number(q[i].value)*Number(u[i].value)*Number(v[i].value)/100)+Number(gbp);
			if( isCISRegistered == 'yes')
				Cis += (Number(q[i].value)*Number(u[i].value)*Number(c[i].value)/100);
			if( isCISRegistered == 'yes'){
				//totalInvoice += gbp - (Number(c[i].value));
				totalInvoice += gbp - Cis;
			}else{
				totalInvoice += gbp ;
			}
			Vat += (Number(q[i].value)*Number(u[i].value)*Number(v[i].value)/100);
            $('#gbp'+Number(i+1)).text((Number(q[i].value)*Number(u[i].value)).toFixed(2));
        }

        $('#subtotal').text(Sub.toFixed(2));
        $('#totalvat').text(Vat.toFixed(2));
        $('#totalCis').text(Cis.toFixed(2));
        $('#grandTotal').text(totalInvoice.toFixed(2));
        return true;
    }
    $(document).ready(function(){		
        $(document).on('mouseover','.copyInvoice,.ggeneratePDF,.deleteInvoice,.changeToPaid,.sort',function(e){
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
		
        /*
                $('#vatSummary').on('shown.bs.collapse', function () {
                   $(".showSummary").removeClass("fa-plus").addClass("fa-minus");
                });

                $('#vatSummary').on('hidden.bs.collapse', function () {
                   $(".showSummary").removeClass("fa-minus").addClass("fa-plus");
                });
         */
		
        /* Set the bootstrap modal size */
        $(document).find('.modal-dialog').css({width:'90%',height:'auto','max-height':'100%'});
        $('.modal-invoice').on('hide.bs.modal',function () {
        });
        $('.modal-invoice').on('show.bs.modal', function () {
            $.fn.modal.Constructor.prototype.enforceFocus = function () { };
        });
		
        /* Open model to add invoice */
        $(document).on('click','.addInvoice',function(e){
            e.preventDefault();
            usertask = 'a';
            var task = "<?php echo $this->encrypt->encode('addinvoice'); ?>";
            var title = "<?php echo $this->lang->line('CLIENT_INVOICE_NEW_INVOICE_TITLE'); ?>";
            openInvoiceForm(task,'',title);
        });
        $(document).on('click','.addCrediteNote',function(e){
            e.preventDefault();
            usertask = 'a';
            var task = "<?php echo $this->encrypt->encode('addCreditnote'); ?>";
            var title = "<?php echo $this->lang->line('INVOICE_NEW_CREDIT_NOTE_TITLE'); ?>";
            openInvoiceForm(task,'',title,'CRN');
        });
		
        /* Open model to edit the Invoice */
        $(document).on('click','.createInvoice',function(e){
            e.preventDefault();
            userTask = 'c';
            var task = "<?php echo $this->encrypt->encode('create'); ?>";
            var id = $(this).attr('href');
            var title = "<?php echo $this->lang->line('CLIENT_INVOICE_CREATE_INVOICE_TITLE'); ?>";
            openInvoiceForm(task,id,title);			
        });
		
        /* Delete the invoice permanently */
        $(document).on('click','#delete-invoice',function(e){
            e.preventDefault();
            var title 	= "<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_DELETE_TITLE'); ?>";
            var text 	= "<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_DELETE_TEXT'); ?>";
            invoicetask = "<?php echo $this->encrypt->encode('deleteInvoice'); ?>";
            userTask = 'd';
            var task = $(this).attr('href');
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
                        $.ajax({
                            type: "POST",
							dataType: 'JSON',
                            url: "<?php echo site_url(); ?>perform_action",
                            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"call":"ajaxcall"},
                            beforeSend:function(){
                                $('.modal-body').html('please wait.......');
                            },
                            error:function(msg){
                                alert(msg.responseText);
                            },
                            success:function(msg){								
                                $('.modal-invoice').modal('hide');
                                invoicetask = 'task-completed';
                                dialogBox('Message',msg.msg);
                            }
                        });
                    },
                    No: function() {
                        $(this).dialog('close');
                    },
                }
            });
        });
		
        $(document).off('change','#customer').on('change','#customer',function(){
            if($(this).val() == 0)
            {
                $('#addcustomer').removeClass('disabled');
                $('#addCustomerDetail').css('display','none');
                $('#customerName').removeAttr("readonly");
                $('#customerAddress').removeAttr("readonly");
                $('#customerName').val('');
                $('#customerAddress').val('');
            }else{
                var id = $(this).val();
                if($('#addcustomer').children('i').hasClass('fa-close'))
                {
                    $('#addcustomer').html('<i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo $this->lang->line('CLIENT_INVOICE_FORM_LABEL_ADD_CUSTOMER'); ?>');
                    $('#addCustomerDetail').css('display','block');
                }
                if($('#addCustomerDetail').css('display') == 'block')
                {
                    $('#addCustomerDetail').css('display','none')
                }
                $('#addcustomer').addClass('disabled');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url(); ?>client/getInvoiceUserDetail/",
                    //dataType: 'JSON',
                    data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',ID:id},
                    beforeSend:function(){
                        initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                        showSpinner();
                    },
                    error:function(msg){
                        hideSpinner();
                    },
                    success:function(msg){					
                        hideSpinner();
                        $('.progress').css('display','none');
                        $('.progress').html('<img src="<?php echo site_url(); ?>/assets/images/progress.gif"/>');
                        msg = $.parseJSON(msg);
                        $('#addCustomerDetail').css('display','block');
                        $('#customerName').val(msg[0].Name);
                        $('#customerName').attr("readonly","readonly");
                        $('#customerAddress').attr("readonly","readonly");
                        $('#customerAddress').val(msg[0].Address);
						$('#invoiceDate').val(msg[0].paymentterms);
                    }
                });
            }
        });
		
        /* This will add two input fields name and address in new invoice form */
        $(document).off('click','#addcustomer').on('click','#addcustomer',function(e){
            e.preventDefault();
            if($(this).children('i').hasClass('fa-plus'))
            {
                $('#addcustomer').html('<i class="fa fa-close"></i>&nbsp;<?php echo $this->lang->line('CLIENT_INVOICE_FORM_LABEL_CANCEL_CUSTOMER'); ?>');
                $('#addCustomerDetail').css('display','block');
                $('#customerName').val('');
                $('#customerAddress').val('');
                return false;
            }
            if($(this).children('i').hasClass('fa-close'))
            {
                $('#addcustomer').html('<i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo $this->lang->line('CLIENT_INVOICE_FORM_LABEL_ADD_CUSTOMER'); ?>');
                $('#addCustomerDetail').css('display','none');
            }
        });
        /* Prevent user to enter only valid values in unit-price and vat fields field i.e only integer numbers */
        $(document).on('keypress','.validNumber',function(evt) {
            var el = this;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            var number = el.value.split('.');
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            //just one dot
            if(number.length>1 && charCode == 46){
                return false;
            }
            //get the carat position
            var caratPos = getSelectionStart(el);
            var dotPos = el.value.indexOf(".");
            if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
                return false;
            }
            return true;
        });
		
        $(document).on('keypress','.checkField',function(eve) {
            return eve.charCode >= 48 && eve.charCode <= 57 || eve.which == 8 || eve.which == 0;
        });
		
        $(document).on('change','.checkField,.validNumber',function(eve) {
            calculations();
        });
		
        $('#myTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show')
        });
        
        var dateToday = new Date();
        /* Apply date picker to all fields where required */
        $(document).on('focus','#invoiceDate,#CurrentDate,#creDate,#dueDate,#expensedatepicker,#paidDate,.sdatepicker',function(){
            $("#invoiceDate,#creDate,#CurrentDate,#paidDate,#dueDate,.sdatepicker").datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-50:+1",
                minDate: '<?php echo $j_date; ?>'
            });
        });

		$(document).on('change','#CurrentDate',function(){			
			$("#invoiceDate").datepicker( "option", "minDate", $('#CurrentDate').val() );            
        });
		$(document).on('change','#sDueStart',function(){			
			$("#sDueEnd").datepicker( "option", "minDate", $('#sDueStart').val() );            
        });
		$(document).on('change','#sCreatedStart',function(){			
			$("#sCreatedEnd").datepicker( "option", "minDate", $('#sCreatedStart').val() );            
        });
		
        /* This will append invoice items in the new invoice form */
        $(document).off('click','#add-invoice-item').on('click','#add-invoice-item',function(e){
            e.preventDefault();
            var html = '<tr id="r'+counter+'">';
            html += '<td class="sno">';
            html += counter;
            html += '</td>';
			
            html += '<td class="form-group has-feedback">';
            html += '<input type="text" name="description[]" class="form-control description_error "/>';
            html += '</td>';
			
            html += '<td class="form-group has-feedback">';
            html += '<input type="text" name="quantity[]"class="form-control validNumber quantity_error working_hours"  min="1" maxlength="5"/>';
            html += '</td>';
			
            html += '<td>';
            html += '<input type="text" name="unitprice[]" class="input-sm validNumber form-control hourly_rate" maxlength="10"/>';
            html += '</td>';
			
            html += '<td>';
            if(vat_percent==0){
                html += '<input type="hidden" name="vat[]"class="sm-width validNumber form-control" value="'+vat_percent+'"maxlength="5"/>';
            }else{
                html += '<input type="text" name="vat[]"class="sm-width validNumber form-control" value="'+vat_percent+'"maxlength="5"/>';
            }
            html += '</td>';
			if( isCISRegistered == 'yes' )
			{
				html += '<td>';
				html += '<input type="text" name="cis_percentage[]" class="input-sm validNumber form-control cis_percentage" value="'+cis_percentage+'" maxlength="3"/>';			
				html += '</td>';
			}
            html += '<td class="text-right gbp" id="gbp'+counter+'" style="width:80px">';
            html += '0.0';
            html += '</td><td>'
            html += '<a class="btn removeInvoiceItem"><i class="fa fa-times"></i></a>';
            html += '</td>';
            html += '</tr>';
            counter++;
            $('#addItems').append(html);
        });	

        $(document).off('click','.removeInvoiceItem').on('click','.removeInvoiceItem',function(){
            counter--;
            
            $(this).closest('tr').remove();
            $("#invoiceTable tr").each(function(index,e){
                $(this).children("td.sno").html(index);
                $(this).children("td.gbp").attr('id','gbp'.index);
                $(this).attr('id','r'+index);
            });
            var delitemId = $(this).attr('id');
            if ($('#delinvoiceId').val() == '') {
                if(delitemId!=null){ 
                    $('#delinvoiceId').val(delitemId);
                }
            } else {
                var val = $('#delinvoiceId').val();
                if(delitemId!=null){ 
                    $('#delinvoiceId').val(val + ',' + delitemId);
                }
            }
            g = 0;
            vat = 0;
            calculations();
        });
		
        /* Save the invoice as draft in database */
        $(document).off('click','#save-invoice').on('click','#save-invoice',function(e){
            e.preventDefault();
            var title 	= '<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_SAVE_TITLE'); ?>';
            var text 	= '<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_SAVE_TEXT'); ?>';
            invoicetask = '<?php echo $this->encrypt->encode('save'); ?>';
            userTask = 's';
            validateInvoiceData(title,text);
        });
		
        $(document).on('click','#update-invoice',function(e){
            e.preventDefault();
            var title 	= '<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_UPDATE_TITLE'); ?>';
            var text 	= '<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_UPDATE_TEXT'); ?>';
            invoicetask = '<?php echo $this->encrypt->encode('update'); ?>';
            userTask = 'u';
            validateInvoiceData(title,text);	
        });
		
        $(document).off('click','#create-invoice').on('click','#create-invoice',function(e){
            e.preventDefault();
            var title = '<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_CREATE_TITLE'); ?>';
            var text = '<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_CREATE_TEXT'); ?>';
            invoicetask = '<?php echo $this->encrypt->encode('create'); ?>';
            userTask = 'c';
            validateInvoiceData(title,text);
        });
		
        $(document).on('click','#eCreateInvoice',function(e){
            e.preventDefault();
            var title = '<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_CREATE_TITLE'); ?>';
            var text = '<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_CREATE_TEXT'); ?>';
            invoicetask = '<?php echo $this->encrypt->encode('createInvoice'); ?>';
            userTask = 'ec';
            validateInvoiceData(title,text);
        });
		
        $(document).on('click','#uCreateInvoice',function(e){
            e.preventDefault();
            var title = '<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_CREATE_TITLE'); ?>';
            var text = '<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_CREATE_TEXT'); ?>';
            invoicetask = '<?php echo $this->encrypt->encode('uCreateInvoice'); ?>';
            userTask = 'ec';
            validateInvoiceData(title,text);
        });
		
        /* Invoice form */
        $(document).off('submit','#invoiceForm').on('submit','#invoiceForm',function(e){
            e.preventDefault();
            $('.modal').modal('hide');
            var call = 'none';
            if(userTask == 'ec' || userTask == 'u')
            {
                var url = '<?php echo site_url(); ?>update_invoice';
            }else if(userTask == 'ci'){
                var url = '<?php echo site_url(); ?>perform_action';
                call = 'ajaxcall';
            }else{
                var url = "<?php echo site_url(); ?>save_invoice";
            }
			
            /* If Yes, then we will perform the specified task */
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+"&task="+invoicetask+"&call="+call,
                beforeSend:function(){
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error:function(msg){
                    hideSpinner();
                },
                success:function(msg){
                    hideSpinner();
                    msg = JSON.parse(msg);
                    if($('.modal-statements').length > 0)
                    {
                        $('.modal-statements').modal('hide');
                    }
                    if(msg['ajax_add'] == 'ajax_add')
                    {
                        $('#script').html();
                        $('#statement-listing').html(msg['html']);
                        //window.location = '<?php echo site_url() . 'bank_statements/before_upload'; ?>';
                        //location.reload(true);
                    }else if(msg['ajax_add'] == 'bank_ajax_add'){
                        //$('.modal-statements').modal('hide');
                        //window.location = '<?php echo site_url() . 'bank_statements'; ?>';
                        location.reload(true);
                    }else{
                        $('.modal-dashboard').modal('hide');
                        $('.modal-invoice').modal('hide');
                        invoicetask = 'task-completed';
                        $('.progress').css('display','none');
                        dialogBox('Message',msg['msg']);
                    }
                }
            });
            return false;
        });
		
        $(document).on('click','.showPaid',function(e){
            e.preventDefault();
            var task = '<?php echo $this->encrypt->encode('displayInvoice'); ?>';
            userTask = 'sp';
            var id = $(this).attr('href');
            var pop_up_title = $(this).closest('tr').find('.item-id').text()+' : PAID';
            //var title = "<?php echo $this->lang->line('CLIENT_INVOICE_PAID_INVOICE_TITLE'); ?>";
            var title = pop_up_title;
            openInvoiceForm(task,id,title);
        });
		
		
        $(document).on('click','.markPaid',function(e){
            e.preventDefault();
            var task = '<?php echo $this->encrypt->encode('changeInvoiceStatus'); ?>';
            userTask = 'mp';
            var id = $(this).attr('href');
            var title = "<?php echo $this->lang->line('CLIENT_INVOICE_CREATE_INVOICE_TITLE'); ?>";
            openInvoiceForm(task,id,title);
        });
		
        $(document).on('click','#markInvoice',function(e){
            e.preventDefault();
            var link = $(this).attr('href');
            var msg = "<?php echo '<i class=\"fa fa-check\"></i>' . $this->lang->line('CLIENT_INVOICE_CREATE_SUCCESS'); ?>";
            ajaxcall = true;
            var title = 'Message';
            var datetext = "Please choose the paid date <input type='text' name='inpaidDate'id='inpaidDate' class='sdatepicker' readonly/><br/><br/>";
            var text = datetext+'<?php echo $this->lang->line('INVOICE_ACTION_MARK_TO_PAID'); ?>';
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
                        pdate = $('#inpaidDate').val();
                        invoiceAction(link,msg,pdate);
                    },
                    No: function(){
                        $(this).dialog('close');
                    }
                }
            });
			
        });
		
        $(document).on('click','.changeStatus',function(e){
            e.preventDefault();
            var link = $(this).attr('href');
            var no = $(this).closest('tr td').siblings(':nth-child(2)').text();
            var msg = "<?php echo '<i class=\"fa fa-check\"></i>' . $this->lang->line('CLIENT_INVOICE_CREATE_SUCCESS'); ?>";
            msg = msg.replace('%s',no);
            invoiceAction(link,msg);
        });
		
        $(document).on('click','.copyInvoice',function(e){
            e.preventDefault();
            var link = $(this).attr('href');
            var no = $(this).closest('tr td').siblings(':nth-child(2)').text();
            var title = 'Create copy of '+no+' invoice';
            userTask = 'c';
            var task = '<?php echo $this->encrypt->encode('copyInvoice'); ?>';
            openInvoiceForm(task,link,title);
        });
		
        $(document).on('click','#copy-invoice',function(e){
            e.preventDefault();
            var link = $(this).attr('href');
            var title	= "<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_COPY_TITLE'); ?>";
            var text 	= "<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_COPY_TEXT'); ?>";
            invoicetask = link;
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
                        userTask = 'ci';
                        $('#invoiceForm').trigger('submit');
                    },
                    No: function() {
                        $(this).dialog('close');
                    }
                }
            });
        });
		
        /* Directly delete the invoice from dashboard */
        $(document).on('click','.deleteInvoice',function(e){
            e.preventDefault();
            var link =$(this).attr('href');
            var no = $(this).closest('tr td').siblings(':nth-child(2)').text();
            var msg = "<?php echo '<i class=\"fa fa-check\"></i>' . $this->lang->line('CLIENT_INVOICE_DELETE_SUCCESS'); ?>";
            msg = msg.replace('%s',no);
            $('#dialog').html('<?php echo $this->lang->line('CLIENT_INVOICE_DELETE_ERROR'); ?>');
            $('#dialog').dialog({ 
                autoOpen : true,
                title: 'Delete : '+no,
                modal: true,
                minWidth: 500,
                draggable: false,
                buttons: {
                    Yes: function() {
                        $(this).dialog('close');
                        userTask = 'nad';
                        invoiceAction(link,msg);
                    },
                    No: function() {
                        $(this).dialog('close');
                    }
                }
            });
        });
		
        /* Directly mark as paid status of the invoice from dashboard */
        $(document).on('click','.changeToPaid',function(e){
            e.preventDefault();
            var link = $(this).attr('href');
            var no = $(this).closest('tr td').siblings(':nth-child(2)').text();
            var msg = "<?php echo '<i class=\"fa fa-check\"></i>' . $this->lang->line('CLIENT_INVOICE_PAID_SUCCESS'); ?>";
            var datetext = "Please choose the paid date <input type='text' name='inpaidDate'id='inpaidDate' class='sdatepicker' readonly/><br/><br/>";
            var text = datetext+'<?php echo $this->lang->line('CLIENT_INVOICE_PAID_ERROR'); ?>';
			
            msg = msg.replace('%s',no);
            $('#dialog').html(text);
            $('#dialog').dialog({
                autoOpen : true,
                title: 'Mark to paid : '+no,
                modal: true,
                minWidth: 500,
                draggable: false,
                buttons: {
                    Yes: function() {
                        $(this).dialog('close');
                        userTask = 'nad';
                        var paidDate = $('#inpaidDate').val();
                        invoiceAction(link,msg,paidDate);
                    },
                    No: function() {
                        $(this).dialog('close');
                    }
                }
            });
        });
		
        $(document).on('click','.generatePDF',function(e){
            e.preventDefault();
            dialogBox('Message','Not available right now');
        });
		
        $(document).on('click','.sort',function(e){
            e.preventDefault();
            var href = $(this).attr('href');
            var text = $(this).text();
            var se = $(this);
            var dir = '';
			
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>client/invoiceSort/",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',order:href},
                beforeSend:function(){
				
                },
                error:function(msg){
                    dialogBox('Error',msg);
                },
                success:function(msg){
                    if($('.table-responsive th a').children('i').length > '0')
                    {
                        $('.table-responsive th a i').remove();
                    }
                    se.append('<i class="fa fa-sort-desc"></i>');
                    msg = JSON.parse(msg);
                    se.children('i').addClass(msg[1]);
                    $('.invoiceListing').html(msg[0]);
                }
            });
        });
		
		
		
        /* This block will rest the search fields */
        $(document).on('click','.reset',function(e){
            e.preventDefault();
            $('#sInvoiceNumber').val('');
            $('#sCustomerName').val('');
            $('#sInvoiceStatus').val('0');
            $('#sCreatedStart').val('');
            $('#sCreatedEnd').val('');
            $('#sDueStart').val('');
            $('#sDueEnd').val('');
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>client/clean/",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
                beforeSend:function(){
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error:function(msg){
                    hideSpinner();
                },
                success:function(msg){
                    hideSpinner();
                    $('.invoiceListing').html(msg);
                }
            });
        });
		
		
        $(document).on('click','.markVATPaid',function(e){ 		
            e.preventDefault();			
            var task = '<?php echo $this->encrypt->encode('markVATPaid'); ?>';
            var MarkButton = $(this);
            var quarter = $(this).attr('href');
            var VATYear = $("#vatYear").val();		

			alert(quarter);
			alert(VATYear);
			alert('******');
			
			return false;

			
            var msg = "<?php echo '<i class=\"fa fa-check\"></i>' . $this->lang->line('CLIENT_INVOICE_PAID_SUCCESS'); ?>";
            var datetext = "Please choose the VAT paid date <input type='text' name='VATpaidDate'id='VATpaidDate' class='sdatepicker' readonly/><br/><br/>";
            var text = datetext+'<?php echo $this->lang->line('VAT_MARK_AS_PAID_QUES_LABEL'); ?>';
			
            $('#dialog').html(text);
            $('#dialog').dialog({ 
                autoOpen : true,
                title: 'Mark VAT as paid',
                modal: true,
                minWidth: 500,
                draggable: false,
                buttons: {
                    Yes: function() {
                        var Eid = "VATpaidDateError";
                        var paidDate = $('#VATpaidDate').val();
                        if( paidDate != "" ){
                            if( $('#'+Eid).length > 0 ){
                                $('#'+Eid).remove();
                            }
                            $(this).dialog('close');
                            markVATPaid(quarter,VATYear,paidDate, MarkButton);
                        }else{
                            var notSelectedError="<?php echo $this->lang->line("ERROR_VAT_PAID_DATE_NOT_SELECTED"); ?>";
                            if( $('#'+Eid).length <= 0 ){
                                var errorLabel = createHTMLElement("label",notSelectedError, "dialogError", Eid );
                                $('#VATpaidDate').after( errorLabel );
                            }else{
                                $('#'+Eid).html( notSelectedError );
                            }
                            return;
                        }
                    },
                    No: function() {
                        $(this).dialog('close');
                    }
                },
                open: function( event, ui ) {
                    $('#VATpaidDate').on('change',function(){
                        var Eid = "VATpaidDateError";
                        var paidDate = $(this).val();
                        if( paidDate != "" ){
                            if( $('#'+Eid).length > 0 ){
                                $('#'+Eid).remove();
                            }
                        }else{
                            var notSelectedError="<?php echo $this->lang->line("ERROR_VAT_PAID_DATE_NOT_SELECTED"); ?>";
                            if( $('#'+Eid).length <= 0 ){
                                var errorLabel = createHTMLElement("label",notSelectedError, "dialogError", Eid );
                                $( this ).after( errorLabel );
                            }else{
                                $('#'+Eid).html(notSelectedError);
                            }
                            return;
                        }
                    });
                }
            });
        });
		
        $(document).on('change','#vatYear',function(){
            loadVatDetails();
        });
		
        $(document).on('click','.vatDetailsLink',function(e){ 
            e.preventDefault();
            var q = $(this).attr("href");
            var quarter = $(this).attr("data-quarter");
            var qStartDate= $("#from_date_"+quarter).html();
            var qEndDate= $("#to_date_"+quarter).html();
           
			<?php $user = $this->session->userdata('user'); ?>
            var title ="<?php echo companyName($user["CompanyID"]); ?><br/>";
            title +="<?php echo $this->lang->line("VAT_QUARTER_DETAILS_POPUP_TITLE"); ?>"+quarter+"( "+ qStartDate +" : "+qEndDate+" )";
            openQuarterDetails( q, title );
        });
		
        $(document).on('click','.btn-search',function(e){
            e.preventDefault();
            var regex = <?php echo DATE_FORMAT_REGEX; ?>;
            var error = '<?php echo $this->lang->line('INVALID_DATE_FORMAT'); ?>';
            var flag = 0;
            $('.sdatepicker').each(function(e,v){
				
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
		
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href") // activated tab
            if ((target == '#vat_summary_tab')) {
                loadVatDetails();
            }
        });
		
		
        //Vat Summary Extended sheet in Vat Summary Portion 30-10-2015 (Ravinder addition)
		
        $(document).on('keyup','.vatcnb,.validNumber',function(eve) {
            vat_extended_calculations();
        });
		
        $(document).on('click','.submit_all_cal',function(e){ 
            e.preventDefault();			
            var task 		 = '<?php echo $this->encrypt->encode('markVATPaid'); ?>';
            var MarkButton   = $(this);
            
			var Vat_userid   = $('#Vat_userid').val();
			var Vat_pass     = $('#Vat_pass').val();
			var Vat_capacity = $('#Vat_capacity').val();
			var vat_number   = $('#vat_number').val();
            var quarter = $('.quarter_val_sm').val();
			
			var qStartDate = $('#hmrcquarterfrom_date').val();
			var qEndDate   = $('#hmrcquarterto_date').val();
			
			
            var vat_due_ac_ec 				= $('#vat_due_ac_ec').val();
            var total_val_due_period_ec_mem = $('#total_val_due_period_ec_mem').val();
            var total_val_supp_ex_vat 		= $('#total_val_supp_ex_vat').val();
            var ex_vat_gds 					= $('#ex_vat_gds').val();
            var VATYear 					= $("#vatYear").val();
            var paidDate 					= '<?php echo date('Y-m-d') ?>';
            var text 						= '<?php echo $this->lang->line('VAT_MARK_AS_PAID_QUES_LABEL'); ?>';			
            
			$('#dialog').html(text);
            $('#dialog').dialog({ 
                autoOpen : true,
                title	 : 'Mark VAT as paid',
                modal	 : true,
                minWidth : 500,
                draggable: false,
                buttons: {
                    Yes: function() {
                        $(this).dialog('close');
                        VatSumm_markVATPaid(quarter,VATYear,paidDate,MarkButton,vat_due_ac_ec,total_val_due_period_ec_mem,total_val_supp_ex_vat,ex_vat_gds,Vat_userid,Vat_pass,Vat_capacity,vat_number,qStartDate,qEndDate);
                        //window.location = "<?php echo site_url(); ?>invoices";					
                    },
                    No: function() {
                        $(this).dialog('close');
                    }
                }               
            });			
        });
		// Before submit online
		$(document).on('click','.before_submit',function(e){ 
			$.ajax({
				type: "POST",
				dataType:"json",
				url: "<?php echo site_url(); ?>client/getVatCredential/",
				data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'},
				beforeSend:function(){
					initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
					showSpinner();
				},
				error:function(msg){
					hideSpinner();
				},
				success:function(msg){
					hideSpinner();
					$('#modal-vat-details').modal('show');   
					$(document).find('#modal-vat-details').find('.modal-dialog').css({width:'50%',height:'auto','max-height':'100%'});					
					$('#Vat_userid').val(msg.Vat_userid);
					$('#hmrcId').val(msg.Vat_userid);
					$('#Vat_pass').val(msg.Vat_pass);
					$('#hmrcPass').val(msg.Vat_pass);
					$('#Vat_capacity').val(msg.Vat_capacity);
					$('#vat_number').val(msg.vat_number);
					var quarter      = $('.vatDetailsLink').attr("data-quarter");
					var qStartDate   = $("#from_date_"+quarter).html();
					var qEndDate     = $("#to_date_"+quarter).html();
					$('#hmrcquarter').val(quarter);
					$('#hmrcquarterfrom_date').val(qStartDate);
					$('#hmrcquarterto_date').val(qEndDate);
				}
			});
		}); 
		// trigger submit vat online
		$(document).on('click','.submitVat',function(e){
			var Vat_userid   = $('#Vat_userid').val();
			var Vat_pass     = $('#Vat_pass').val();
			var Vat_capacity = $('#Vat_capacity').val();	
			var vat_number   = $('#vat_number').val();	
			
			var error        = false;	
			if( Vat_userid == '' )
			{
				$('#Vat_userid').css('border','1px solid #ff0000');
				$('#Vat_userid').attr('placeholder','Please enter user id.');
				error = true;
			}
			if( Vat_pass == '' )
			{
				$('#Vat_pass').css('border','1px solid #ff0000');
				$('#Vat_pass').attr('placeholder','Please enter password.');
				error = true;
			}
			if( Vat_capacity == '' )
			{
				$('#Vat_capacity').css('border','1px solid #ff0000');
				$('#Vat_capacity').attr('placeholder','Please select submit as.');
				error = true;
			}
			var vatNmbrLen = vat_number.length;
			if( vat_number == '' || vat_number <= 0 || vatNmbrLen < 9 || vatNmbrLen > 9)
			{
				$('#vat_number').css('border','1px solid #ff0000');
				$('#vat_number').attr('placeholder','Please enter valid VAT number.');
				error = true;
			}
			if( error )
				return false
			else
			{
				$('#modal-vat-details').modal('hide');
				$('.submit_all_cal').trigger('click');
			}
		});
		// change vat credential
		$(document).on('change','#Vat_capacity',function(e){
			var capacity = $(this).val();
			if( capacity == 'agent'){ 
				var hmrcId   = $('#hmrcId').val();
				var hmrcPass = $('#hmrcPass').val();
				$("#Vat_userid").attr("readonly", true).attr("disabled", true).val(hmrcId); 
				$("#Vat_pass").attr("readonly", true).attr("disabled", true).val(hmrcPass); 
			}
			else{
				$("#Vat_userid").attr("readonly", false).attr("disabled", false); 
				$("#Vat_pass").attr("readonly", false).attr("disabled", false);				
			}
		});		
    });
	
    //Vat summary Related
    function vat_extended_calculations(){
        var vat_sales_outputs = $('#vat_sales_outputs').val();
        var vat_due_ac_ec = $('#vat_due_ac_ec').val();
        if(vat_sales_outputs != "" || vat_due_ac_ec != ""){
            var addtion_sales_due_ac = parseFloat(vat_sales_outputs) + parseFloat(vat_due_ac_ec);
            var adds = addtion_sales_due_ac.toFixed(2);
            //console.log('Not empty');
        } 
        if(vat_sales_outputs != "" && vat_due_ac_ec == ""){
            var addtion_sales_due_ac = parseFloat(vat_sales_outputs);
            var adds = addtion_sales_due_ac.toFixed(2);
            //console.log('first notempt');
        }
        if( vat_sales_outputs == "" && vat_due_ac_ec != ""){
            var addtion_sales_due_ac = parseFloat(vat_due_ac_ec);
            var adds = addtion_sales_due_ac.toFixed(2);
            //console.log('sec notempt');
        }		
        var third_variable = $('#total_val_due_period_ec_mem').val(adds);	
        var vat_reclaimed = $('#vat_reclaimed').val();		
        var fifth_variable = adds - parseFloat(vat_reclaimed);
        $('#net_vat_reclaimed').val(fifth_variable.toFixed(2));
        return true;
    }
	
	
    function createHTMLElement(Etype, text, classes, Eid){
		
        var ele = document.createElement( Etype );        // Create a <button> element
        var txt = document.createTextNode( text );       // Create a text node
        ele.setAttribute("id", Eid); 
        ele.setAttribute("class", classes);
        ele.appendChild(txt);
        return ele;
	
    }
    /* Function to validate the invoice Fields */
    function validateInvoiceData(title,text)
    {
		
        var tit = '<?php echo $this->lang->line('POP_UP_DIALOG_TITLE'); ?>';
        var d = $('[name^="description"]');
        var q = $('[name^="quantity"]');
        var u = $('[name^="unitprice"]');
        var v = $('[name^="vat["]');
		
        if($("#addcustomer").children('i').hasClass('fa-close') && !$("#addcustomer").hasClass('disabled'))
        {
            if($('#customerName').val() == '')
            {
                var txt = '<?php echo $this->lang->line('CLIENT_INVOICE_ERROR_EMPTY_NAME'); ?>';
                dialogBox(tit,txt,'#customerName');	
                $('#customerName').focus();
                return false;
            }
        }else{
            if($('#customer').val() == 0)
            {
                var txt = '<?php echo $this->lang->line('CLIENT_INVOICE_ERROR_SELECT_NAME'); ?>';
                dialogBox(tit,txt,'#customer');	
                return false;
            }
        }
		
		
        if(d.length == 0)
        {
            var txt = '<?php echo $this->lang->line('CLIENT_INVOICE_ERROR_EMPTY_ITEM'); ?>';
            dialogBox(tit,txt);	
            return false;
        }
        var regx = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
		
        if(userTask != 's' && userTask != 'u')
        {
            /* Check for invoice date */
            if($.trim($('#invoiceDate').val()) == '')
            {
                var txt = '<?php echo $this->lang->line('CLIENT_INVOICE_ERROR_INVOICE_DATE'); ?>';
                dialogBox(tit,txt,'#invoiceDate');	
                return false;
            }else if(!regx.test($('#invoiceDate').val())){
                var txt = '<?php echo $this->lang->line('ACCOUNTANT_INVALID_DATE_FORMAT'); ?>';
                dialogBox(tit,txt,'#invoiceDate');	
                return false;
            }
			
            var flag = 1;
            /* Check if there is any row of invoice item */
            var totalRows = $('#addItems tr').length;//alert('totalRows : '+totalRows);
            if(totalRows == 0)
            {
                var txt = '<?php echo $this->lang->line('CLIENT_INVOICE_EMPTY_ERROR'); ?>';
                dialogBox('Error',txt);	
                return false;
            }
            var counter = 0;
            var error_occured = 0;
            //$(document).find('.error-border').removeClass('.error-border');
            $(document).find('.icon-color').remove();
            $(document).find('.description_error').removeClass('error-border');
            $(document).find('.quantity_error').removeClass('error-border');
            for(var x = 0;x < d.length;x++)
            {	
				
                /* Check if only one row and is empty */
                if(d[x].value == '' && q[x].value == '')
                {
                    counter++;
					
                    /*
                                        var txt = '<?php echo $this->lang->line('CLIENT_INVOICE_EMPTY_ERROR'); ?>';
                                        dialogBox('Error',txt);	
                                        return false;
                     */
                }
                if(flag)
                {
                    /*
							if(d[x].value != '')
							{
									if(q[x].value == '')
									{
											var txt = "<?php echo $this->lang->line('CLIENT_INVOICE_QUANTITY_ERROR'); ?>";
											txt = txt.replace('{s}',x+1);
											dialogBox('Alert',txt);	
											return false;
									}
							}else if(q[x].value != ''){
									if(d[x].value == '')
									{
											var txt = "<?php echo $this->lang->line('CLIENT_INVOICE_DESCRIPTION_ERROR'); ?>";
											txt = txt.replace('{s}',x+1);
											dialogBox('Alert',txt);	
											return false;
									}
							}else if(u[x].value != ''){
									if(d[x].value == '')
									{
											var txt = "<?php echo $this->lang->line('CLIENT_INVOICE_DESCRIPTION_ERROR'); ?>";
											txt = txt.replace('{s}',x+1);
											dialogBox('Alert',txt);	
											return false;
									}else if(q[x].value == ''){
											var txt = "<?php echo $this->lang->line('CLIENT_INVOICE_QUANTITY_ERROR'); ?>";
											txt = txt.replace('{s}',x+1);
											dialogBox('Alert',txt);	
											return false;
									}
							}
                     */
					
                    if(d[x].value == '' && q[x].value != '')
                    {
                        var txt = "<?php echo $this->lang->line('CLIENT_INVOICE_DESCRIPTION_ERROR'); ?>";
                        txt = txt.replace('{s}',x+1);
                        //alert($('.quantity_error').eq(x).val());
                        //$('.description_error').eq(x).closest('td').append('<div class="error_field"><i class="fa fa-exclamation"></i>&nbsp;'+txt+'</div>');
                        $('.description_error').eq(x).addClass('error-border');
                        //$('.description_error').attr('placeholder',txt);
                        $('.description_error').eq(x).closest('td').append('<i class="fa fa-close form-control-feedback icon-color" aria-hidden="true"></i>');
                        error_occured = 1;
                    }else if(q[x].value == '' && d[x].value != ''){
                        var txt = "<?php echo $this->lang->line('CLIENT_INVOICE_QUANTITY_ERROR'); ?>";
                        txt = txt.replace('{s}',x+1);
                        $('.quantity_error').eq(x).addClass('error-border');
                        $('.quantity_error').eq(x).closest('td').append('<i class="fa fa-close form-control-feedback icon-color" aria-hidden="true"></i>');
                        //dialogBox('Alert',txt);	
                        error_occured = 1;
                    }
                }
            }
            //alert(counter+' = '+totalRows);
            if(counter == totalRows)
            {
                var txt = '<?php echo $this->lang->line('CLIENT_INVOICE_EMPTY_ERROR'); ?>';
                dialogBox('Error',txt);	
                return false;
            }
			
            if(error_occured)
            {
                return false;
            }
			
        }
        // After validation perform the specific task
		
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
					
                    $('#invoiceForm').trigger('submit');
                },
                No: function() {
                    $(this).dialog('close');
                }
            }
        });
        return false;
    }
    function invoiceAction(link,invoiceMessage,paidDate)
    {
        if(userTask == 'nad')
        {
            aj = 'noajaxcall';
        }else{
            aj = 'ajaxcall';
        }
        $.ajax({
            type: "POST",
            url: "<?php echo site_url() . 'perform_action'; ?>",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":link,"call":aj,'PaidDate':paidDate},
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                hideSpinner();
            },
            success:function(msg){
                hideSpinner();
                if(msg == 'ERROR')
                {
                    $('.clientErrors').css('display','block');
                    if($('.clientErrors').hasClass('alert-success'))
                    {
                        $('.clientErrors').removeClass('alert-success')
                    }
                    $('.clientErrors').addClass('alert-danger');
                    $('.clientErrors').html("<?php echo '<i class=\"fa fa-close\"> ' . $this->lang->line('UNEXPECTED_ERROR_OCCURED') . '</i>'; ?>");
                }else if(ajaxcall){
                    ajaxcall = false;
                    invoicetask = 'task-completed';
                    msg = JSON.parse(msg);
                    dialogBox('Message',msg['msg']);
                }else{
                    if(userTask == 'nad')
                    {
                        msg = JSON.parse(msg);
                        $('#vatSummary').html('');
                        $('.invoiceListing').html('');
                        $('#vatSummary').html(msg['vat']);
                        $('.invoiceListing').html(msg['invoice']);
                        dialogBox('Message',invoiceMessage);
                    }else{
                        msg = JSON.parse(msg);
                        $('.clientErrors').css('display','block');
                        if($('.clientErrors').hasClass('alert-danger'))
                        {
                            $('.clientErrors').removeClass('alert-danger')
                        }
                        $('.clientErrors').addClass('alert-success');
                        $('.clientErrors').html(invoiceMessage);
                        $('.invoiceListing').html(msg['msg']);
                    }
                }
            }
        });
    }
	
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
                    if(el != null)
                    {
                        $(el).focus();
                    }
                    if(invoicetask == 'task-completed')
                    {
                        //window.location = "<?php echo site_url(); ?>invoices";
                        location.reload(true);
                    }
                }
            }
        });	
    }
	
    function markVATPaid(quarter,VATYear,paidDate, MarkButton)
    {
        alert('fdgfdgf'); return false;
		$.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>client/markVATPaid",
            data: {
                "<?php echo $this->security->get_csrf_token_name(); ?>":"<?php echo $this->security->get_csrf_hash(); ?>",
                "quarter":quarter,
                "VATYear":VATYear,
                "paidDate":paidDate
            },
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                dialogBox(msg.responseText);
            },
            success:function(msg){				
                hideSpinner();
                var response = JSON.parse(msg);
                if(response.hasOwnProperty('success')){
                    MarkButton.replaceWith('<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('TOOLTIP_VAT_ALREADY_PAID'); ?>" >' + 	'<?php echo $this->lang->line('CLIENT_INVOICE_VAT_PAID_LABEL'); ?>' + '</span>');
                    $(".clientErrors").html( response.success );
                    $('span[data-toggle="tooltip"]').tooltip({placement: 'right'});
                }else if( response.hasOwnProperty('error')){
                    $(".clientErrors").html( response.error );
                }
                $(".clientErrors").fadeIn();
                setTimeout( function(){
                    $(".clientErrors").fadeOut("slow");
                },10000 );
            }
        });
    }
	
	
    //Vat Summary 02-11-2015 Vat Summary Inner TAb
	
    function VatSumm_markVATPaid(quarter,VATYear,paidDate,MarkButton,vat_due_ac_ec,total_val_due_period_ec_mem,total_val_supp_ex_vat,ex_vat_gds,Vat_userid,Vat_pass,Vat_capacity,vat_number,qStartDate,qEndDate)
	{
        /************************* dynamic vat submission start here **************/
		var box1 = $('#vat_sales_outputs').val();
		var box2 = $('#vat_due_ac_ec').val();
		var box4 = $('#vat_reclaimed').val();
		var box6 = $('#total_val_sales_ex_vat').val();
		var box7 = $('#total_value_purchase_ex_vat').val();
		var box8 = $('#total_val_supp_ex_vat').val();
		var box9 = $('#ex_vat_gds').val();
		
		 $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo site_url(); ?>client/onlineVatSubmission",
            data: {
                "<?php echo $this->security->get_csrf_token_name(); ?>":"<?php echo $this->security->get_csrf_hash(); ?>",
                "box1":box1,
                "box2":box2,
                "box4":box4,
                "box6":box6,
                "box7":box7,
                "box8":box8,
                "box9":box9,
				"Vat_userid"  : Vat_userid,
				"Vat_pass"    : Vat_pass,
				"Vat_capacity": Vat_capacity,
				"vat_number"  : vat_number,
				"quarter"	  : quarter,
				"qStartDate"  : qStartDate,
				"qEndDate"    : qEndDate,
            },
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                dialogBox(msg.responseText);
            },
            success:function(response){
				hideSpinner();
				if(response.success)
				{ 
					$.ajax({
						type: "POST",
						url: "<?php echo site_url(); ?>client/VatSum_markVATPaid",
						data: {
							"<?php echo $this->security->get_csrf_token_name(); ?>":"<?php echo $this->security->get_csrf_hash(); ?>",
							"quarter":quarter,
							"VATYear":VATYear,
							"paidDate":paidDate,
							"vatDueAc":vat_due_ac_ec,
							"TDue":total_val_due_period_ec_mem,
							"TgEC":total_val_supp_ex_vat,
							"TaEC":ex_vat_gds,
							"pollResponseData":response.pollResponseData
						},
						beforeSend:function(){
							initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
							showSpinner();
						},
						error:function(msg){
							dialogBox(msg.responseText);
						},
						success:function(msg){
							hideSpinner();
							var response = JSON.parse(msg);
							if(response.hasOwnProperty('success')){
								$('.before_submit').hide();
								MarkButton.replaceWith('<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="<?php echo $this->lang->line('TOOLTIP_VAT_ALREADY_PAID'); ?>" >' + 	'<?php echo $this->lang->line('CLIENT_INVOICE_VAT_PAID_LABEL'); ?>' + '</span>');
								$("#messgaeDiv").html( response.success );
								$('span[data-toggle="tooltip"]').tooltip({placement: 'right'});
							}else if( response.hasOwnProperty('error')){
								$("#messgaeDiv").html( response.error );
							}
							$("#messgaeDiv").fadeIn();
							setTimeout( function(){
								$("#messgaeDiv").fadeOut("slow");
							},10000 );
						}
					});
				}
				else
				{
					var error = response.error_msg.label + response.error_msg.message;
					var errorHtml = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+error+'</div>';
					$("#messgaeDiv").html( errorHtml );
					$("#messgaeDiv").fadeIn();
					setTimeout( function(){
						$("#messgaeDiv").fadeOut("slow");
					},10000 );
					return false;
				}               
            }
        });
		/************************* dynamic vat submission end here **************/
    }
	
    function openQuarterDetails(q,title)
    {
        var VATYear = $("#vatYear").val();
        $('.modal-title').html(title);
        $('.modal-invoice').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>client/loadQuarterDetails/",
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                "quarter":q,
                "VATYear":VATYear
            },
            beforeSend:function(){
                $('.modal-body').html('<img src="<?php echo site_url(); ?>assets/images/loading.gif"/>');
            },
            error:function(msg){
                //alert(msg.responseText);
            },
            success:function(msg){				
                $('.modal-body').html(msg);				
            }
        });
    }
	
    function loadVatDetails(){
        var vatYear = $( '#vatYear' ).val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>client/loadVatDetails/",
            dataType: 'html',
            data: {
                '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                'vatYear':vatYear
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
                if(response.hasOwnProperty('HTML')){
                    $('#vatSummary').html( response.HTML );
                    $('[data-toggle="tooltip"]').tooltip({placement: 'right'});
                }else{
                    $('#vatSummary').html( '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'+
                        '<?php echo $this->lang->line("ERROR_NOTHING_FOUND_IN_VAT_SUMARRY"); ?>'
                        +'</div>' );
                }
					
            }
        });
    }

    /* Function for getting number upto 2 decimal places */
    function getSelectionStart(o) 
    {
        if (o.createTextRange) 
        {
            var r = document.selection.createRange().duplicate()
            r.moveEnd('character', o.value.length)
            if (r.text == '') return o.value.length
            return o.value.lastIndexOf(r.text)
        } else return o.selectionStart
    }
</script>