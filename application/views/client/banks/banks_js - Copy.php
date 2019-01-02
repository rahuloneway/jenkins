<script>
    /****** getting excel filter arranged order ****/
    function getOrder()
    {
        $('.dropzone > div >div').map(function () {
            alert($(this).attr('data-value'));
        });
    }

    function openStatementForm(task, id, title, ajax_add, amount, pdate, clickedTrIndex)
    {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>clients/banks/form/",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', "task": task, "ID": id, "Others": ajax_add, "Amount": amount, "PDate": pdate,"clickedrow":clickedTrIndex},
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                $(document).find('#script').html('');
                showSpinner();
            },
            error: function (msg) {
                hideSpinner();
            },
            success: function (msg) {				
                hideSpinner();
                msg = JSON.parse(msg);
                var html = '';
                $('.modal-statements .modal-title').html(title);
                $('.modal-statements').modal('show');
                if (msg['file'] != '')
                {
                    html = msg['file'];
                }
                html += msg['html'];
                $(document).find('#script').html(msg['script']);
                //$('.modal-statements .modal-body').html('<script>'+msg['script']+'<\/script>');
                $('.modal-statements .modal-body').html(html);

                var t = '<?php echo $this->encrypt->encode('createInvoice'); ?>';
                //calBalance();
                window.onbeforeunload = false;
            }
        });
    }



    function createItems(task, id, title, ajax_add, amount, pdate, clickedTrIndex)
    {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>clients/banks/form/",
            data: $('#updateStatements').serialize() + "&task=" + task + "&ID=" + id + "&Others=" + ajax_add + "&Amount=" + amount + "&PDate=" + pdate + "&clickedrow="+clickedTrIndex,
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                $(document).find('#script').html('');
                showSpinner();
            },
            error: function (msg) {
                hideSpinner();
            },
            success: function (msg) {
                hideSpinner();
                msg = JSON.parse(msg);
                var html = '';
                $('.modal-statements .modal-title').html(title);
                $('.modal-statements').modal('show');
                if (msg['file'] != '')
                {
                    html = msg['file'];
                }
                html += msg['html'];
                $(document).find('#script').html(msg['script']);
                $('.modal-statements .modal-body').html(html);
                var t = '<?php echo $this->encrypt->encode('createInvoice'); ?>';
                window.onbeforeunload = false;
            }
        });
    }

    $(document).ready(function (e) {
		$(document).on('click', '#savebanksubmodel', function (e) {           
			$('#modal-bank-upload').modal("hide");  
        });
  
		$(document).on('click', '#uploadstatementsmodel', function (e) {           
			$('#modal-bank-upload').modal("hide");  
        });
		$(document).on('click', '#uploadAddBank', function (e) {
			e.preventDefault();	
			$('#savebankform').trigger("reset");
			$('#modal-bank-upload').modal("show");  
        });
        $(document).on('click', '.showmodal', function (e) {
            e.preventDefault();
			$('#savebankform').trigger("reset");
            $('.modal-bank').modal('show');
        });

        $(document).on('click', '#savebank', function (e) { 
            e.preventDefault();
            window.onbeforeunload = false;
			var isError = false;
            $("#savebankform input[type = 'text']").each(function () {
                if ($(this).val() == '') {
					isError = true;
                    $(this).addClass('error');
                    $(this).css('border', '1.8px solid #b94a48');
                    $(this).attr('placeholder', 'This Field is Required');
                }
				else{
					$(this).removeClass('error');
					$(this).css('border', '1px solid #ccc');
                    $(this).attr('placeholder', '');
				}
            });
            if (isError) {
                return false;
            } else {
				var page =  $('#uploadstatment').val();
                $.ajax({
                    type: "POST",
					dataType:"JSON",
                    url: "<?php echo site_url(); ?>clients/banks/saveBank",
                    data: {
                        'BankName': $('#BankName').val(),
                        'ShortCode': $('#ShortCode').val(),
                        'AccountNumber': $('#AccountNumber').val(),
						'page' : page
                    },
                    beforeSend: function () {
                    },
                    success: function (msg) {									
						if(msg.success && msg.page == "addstatment"){					
                       		var bnkName = $('#BankName').val();		
							$('#bankId').append('<option value="'+msg.savedata+'" selected="selected">'+bnkName+'</option>');
							$('#modal-bank').modal('hide');
						}else{					
							var bnkName = $('#BankName').val();		
							$('#bankId').append('<option value="'+msg.savedata+'" selected="selected">'+bnkName+'</option>');
							$('#modal-bank-upload').modal('hide');
						}
                    }
                });
            }
        });

        $(document).find('.modal-dialog').css({width: '90%', height: 'auto', 'max-height': '100%'});
        $('.modal-statements-edit').on('hide.bs.modal', function (e) {
            e.preventDefault();
        });
        $(document).on('hide.bs.modal', '.modal', function (e) {
            //location.reload(true);
        });

        $(document).on('mouseover', '.sort', function (e) {
            $('[data-toggle="tooltip"]').tooltip({
                show: null,
                position: {
                    my: "left top",
                    at: "left bottom"
                },
                open: function (event, ui) {
                    ui.tooltip.animate({top: ui.tooltip.position().top + 10}, "fast");
                }
            });
        });


        $(document).on('click', '.sort', function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var text = $(this).text();
            var se = $(this);
            var dir = '';

            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>clients/banks/bank_sort/",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', order: href},
                beforeSend: function () {

                },
                error: function (msg) {
                    dialogBox('Error', msg);
                },
                success: function (msg) {
                    //dialogBox('Message',msg);
                    if ($('.table-responsive th a').children('i').length > 0)
                    {
                        $('.table-responsive th a i').remove();
                    }
                    se.append('<i class="fa fa-sort-desc"></i>');
                    msg = JSON.parse(msg);
                    se.children('i').addClass(msg['dir']);
                    $('#bank-listing').html(msg['html']);
                    //$('.expenseListing').html(msg);
                }
            });
        });


        $(document).on('click', '.cancel-manaualuoloadstatements', function (e) {
            e.preventDefault();
            window.onbeforeunload = false;
            var title = 'Message';
            var text = 'Are you sure you do not want to store these statements ';
            $('#dialog').html(text);
            $('#dialog').dialog({
                autoOpen: true,
                title: title,
                modal: true,
                minWidth: 500,
                draggable: false,
                buttons: {
                    Yes: function () {
                        //$('.modal-statements-edit').modal('hide');
                        $(this).dialog('close');
                        window.location = '<?php echo site_url() . 'clients/banks/cancelAddManualStatement' ?>';
                    },
                    No: function () {
                        $(this).dialog('close');
                    }
                }
            });
        });
        $(document).on('click', '.modal-statements-edit .close,.cancel-upload', function (e) {
            e.preventDefault();
            window.onbeforeunload = false;
            var title = 'Message';
            var text = 'Are you sure you do not want to store these statements ';
            $('#dialog').html(text);
            $('#dialog').dialog({
                autoOpen: true,
                title: title,
                modal: true,
                minWidth: 500,
                draggable: false,
                buttons: {
                    Yes: function () {
                        //$('.modal-statements-edit').modal('hide');
                        $(this).dialog('close');
                        window.location = '<?php echo site_url() . 'clients/banks/cancel' ?>';
                    },
                    No: function () {
                        $(this).dialog('close');
                    }
                }
            });
        });

        /* Apply date picker to all fields where required */
        $(document).on('focus', '.sDatepicker', function () {
            $(this).datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-5:+0"
            });
        });

        $(document).on('click', '.uploadStatements', function (e) {
            e.preventDefault();
            var task = '<?php echo $this->encrypt->encode('uploadStatement'); ?>';
            var id = '';
            var title = '<?php echo $this->lang->line('BANK_UPLOAD_STATEMENTS_TITLE'); ?>';
            openStatementForm(task, id, title);
        });

        $(document).on('click', '.uploadStatement', function (e) {
            e.preventDefault();
            var f = $('#fileTest').val();
            if (f == '' || f == undefined)
            {
                var title = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TITLE'); ?>';
                var text = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TEXT'); ?>';
                dialogBox(title, text);
                return false;
            }
            getExcelFilterPage();
            return false;
        });

        $(document).on('click', '.createBankInvoice', function (e) {
            e.preventDefault();
            var task = '<?php echo $this->encrypt->encode('createInvoice'); ?>';
            var id = $(this).attr('href');
            var title = '<?php echo $this->lang->line('CLIENT_INVOICE_NEW_INVOICE_TITLE'); ?>';
			var clickedTrIndex     = $(this).parent().parent().index();

            /* Check if clicked from the listing or from intermediate screen */
            if ($(this).closest('tr').find('.MoneyOut').length > 0)
            {
                var ajax_add = 'ajax_add';
                var pdate = $(this).closest('tr').find('.sDatepicker').val();
                //var amount = $(this).closest('tr').find('.amount').val();
            } else {
                var ajax_add = 'bank_ajax_add';
                var pdate = '';

            }
            var money_out = $(this).closest('tr').find('.MoneyOut').val();
            var money_in = $(this).closest('tr').find('.MoneyIn').val();
            if (money_out != '' && typeof (money_out) != 'undefined')
            {
                var amount = money_out;
            } else if (money_in != '' && typeof (money_in) != 'undefined') {
                var amount = money_in;
            } else {
                var amount = '';
            }

            if (ajax_add == 'bank_ajax_add')
            {
                openStatementForm(task, id, title, ajax_add, amount, pdate, clickedTrIndex);
            } else {
                createItems(task, id, title, ajax_add, amount, pdate, clickedTrIndex);
            }

        });

        $(document).on('click', '.createBankDividend', function (e) {
            e.preventDefault();
            var task = '<?php echo $this->encrypt->encode('createDividend'); ?>';
            var id = $(this).attr('href');

            var title = '<?php echo $this->lang->line('DIVIDEND_NEW_FORM_TITLE'); ?>';

            /* Check if clicked from the listing or from intermediate screen */

            if ($(this).closest('tr').find('.MoneyOut').length > 0)
            {
                var ajax_add = 'ajax_add';
                var pdate = $(this).closest('tr').find('.sDatepicker').val();
                //var amount = $(this).closest('tr').find('.amount').val();
            } else {
                var ajax_add = 'bank_ajax_add';
                var pdate = '';
                //var amount = '';
            }

            var money_out = $(this).closest('tr').find('.MoneyOut').val();
            var money_in = $(this).closest('tr').find('.MoneyIn').val();
            if (money_out != '' && typeof (money_out) != 'undefined')
            {
                var amount = money_out;
            } else if (money_in != '' && typeof (money_in) != 'undefined') {
                var amount = money_in;
            } else {
                var amount = '';
            }
            if (ajax_add == 'bank_ajax_add')
            {
                openStatementForm(task, id, title, ajax_add, amount, pdate,'');
            } else {
                createItems(task, id, title, ajax_add, amount, pdate ,'');
            }
        });

        $(document).on('click', '.finish', function (e) {
			e.preventDefault();
            window.onbeforeunload = false;
            var title = 'Message';
            if ($('#bankId').val() == "")
            {
                $('#bankiderror').css('display', 'block');
                return false;
            } else {
                $('#bankiderror').css('display', 'none');
            }
            if (!validateField('.category', 'This field is required'))
            {
                return false;
            }

            if (!validateField('.required', 'This field is required'))
            {
                return false;
            }

            var text = '<?php echo $this->lang->line('BANKS_STATEMENT_UPLOAD_UPDATE_CONFIRM'); ?>';
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
                        //window.location = '<?php echo site_url() . 'clients/banks/save_statements' ?>';
                        $('#updateStatements').trigger('submit');
                    },
                    No: function () {
                        $(this).dialog('close');
                    }
                }
            });
            //window.location = '<?php echo site_url() . 'clients/banks/'; ?>';
        });

        $(document).on('change', '.category', function (e) {
			var clickedTrIndex = 0;
			clickedTrIndex     = $(this).parent().parent().index();
			var analysisInput  = $(this).parent().next('td').find('input'); 
			
            var st_key = $(this).closest('tr').find('.statement_key').val();
            var bankId = $('#bankId').val();
            var text = $(this).find('option:selected').text();
            var actionCheck = $(this).closest('tr').find('.action');
            actionCheck.html('');
            var el = $(this).val();
			
            if (text == 'Dividend')
            {
                $(this).closest('tr').find('.createBankDividend').removeClass('hide');
                $(this).closest('tr').find('.createBankInvoice').addClass('hide');
            } else if (text == "Sales") {
                $(this).closest('tr').find('.createBankDividend').addClass('hide');
                $(this).closest('tr').find('.createBankInvoice').removeClass('hide');
            } else {
                $(this).closest('tr').find('.createBankDividend').addClass('hide');
                $(this).closest('tr').find('.createBankInvoice').addClass('hide');
            }
            $.ajax({
                type: "POST",
				dataType:"json",
                url: "<?php echo site_url(); ?>clients/banks/change_category/",
                data: $('#updateStatements').serialize() + '&CatID=' + el + '&StatementKey=' + st_key,
                beforeSend: function () {
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error: function (msg) {
                    hideSpinner();
                },
                success: function (msg) {	
					analysisInput.val(msg[clickedTrIndex].CategoryParent);
                    hideSpinner();
                    //	window.onbeforeunload = false;
                    //window.location = '<?php echo site_url() . 'clients/banks/before_upload' ?>';
                    //	location.reload(true);
                }
            });
        });
        
        $(document).on('keypress', '.validNumber', function (evt) {
            var el = this;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            var number = el.value.split('.');
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            //just one dot
            if (number.length > 1 && charCode == 46) {
                return false;
            }
            //get the carat position
            var caratPos = getSelectionStart(el);
            var dotPos = el.value.indexOf(".");
            if (caratPos > dotPos && dotPos > -1 && (number[1].length > 1)) {
                return false;
            }
            return true;
        });


        /* Reset the search fields */
        $(document).on('click', '.reset', function (e) {
            e.preventDefault();
            $('#Description').val('');
            $('#Category').val('0');
            $('#TBYear').val('0');
            $('.sDatepicker').val('');

            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>clients/banks/clean/",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
                beforeSend: function () {
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error: function (msg) {
                    hideSpinner();
                },
                success: function (msg) {
                    hideSpinner();
                    msg = JSON.parse(msg);
                    $('#bank-listing').html(msg['html']);
                    $('.bPagination').html(msg['pagination']);
                }
            });
        });

        $(document).on('focusout', '.sDatepicker', function (e) {
            var regex = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
            var elem = $(this).val();
            var close_elment = '';
            if ($(this).closest('td').length > 0)
            {
                close_element = 'td';
            } else {
                close_element = 'div';
            }
            if ($(this).closest(close_element).children('div.error-field'))
            {
                $(this).closest(close_element).children('div.error-field').remove();
            }
            if (elem != '')
            {
                if (!regex.test(elem))
                {
                    var error = 'Invalid date format';

                    $(this).closest(close_element).append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' + error + '</div>');
                    return false;
                }
            }
        });

        $('#Statements').click(function () {
            if ($(this).is(':checked'))
            {
                $('.delete-statement').attr('disabled', false);
            } else {
                $('.delete-statement').attr('disabled', true);
            }
            $('.statement-check').prop('checked', $(this).is(':checked'));
        });
        $('.statement-check').click(function () {
            if ($('.statement-check:checked').length != 0)
            {
                $('.delete-statement').attr('disabled', false);
            } else {
                $('.delete-statement').attr('disabled', true);
            }
            if ($('.statement-check:checked').length == $('.statement-check').length) {
                $('#Statements').prop('checked', true);
            } else {
                $('#Statements').prop('checked', false);
            }
        });

        $(document).on('click', '.delete-statement', function (e) {
            e.preventDefault();
            if ($('.statement-check:checked').length == 0)
            {
                var title = 'Message';
                var text = '<?php echo $this->lang->line('BANK_STATEMENT_SELECT_CONFIRM'); ?>';
                dialogBox(title, text);
                return false;
            } else {
                var title = 'Message';
                var text = '<?php echo $this->lang->line('BANK_STATEMENT_DELETE_CONFIRM'); ?>';
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
                            $('#statementDelete').trigger('submit');
                        },
                        No: function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }
        });

        $(document).on('click', '.view_preview_dividend', function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>clients/banks/viewDividends/",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
                beforeSend: function () {
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error: function (msg) {
                    hideSpinner();
                },
                success: function (msg) {
                    hideSpinner();
                    msg = JSON.parse(msg);
                    $('#modal-view-items .modal-title').html('<?php echo $this->lang->line('BANK_VIEW_DIVIDEND_POPUP_TITLE'); ?>');
                    $('#modal-view-items .modal-body').html(msg['html']);
                    $('#modal-view-items').modal('show');
                }
            });
        });
        $(document).on('click', '.preview_invoices', function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>clients/banks/viewInvoices/",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'},
                beforeSend: function () {
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error: function (msg) {
                    hideSpinner();
                },
                success: function (msg) {
                    hideSpinner();
                    msg = JSON.parse(msg);
                    $('#modal-view-items .modal-title').html('<?php echo $this->lang->line('BANK_VIEW_INVOICES_POPUP_TITLE'); ?>');
                    $('#modal-view-items .modal-body').html(msg['html']);
                    $('#modal-view-items').modal('show');
                }
            });
        });

        $(document).on('change', '.MoneyOut,.MoneyIn,.balance', function (e) {
            //calBalance();
        });
    });
    function dialogBox(title, text)
    {
        $('#dialog').html(text);
        $('#dialog').dialog({
            autoOpen: true,
            title: title,
            modal: true,
            minWidth: 500,
            draggable: false,
            buttons: {
                Ok: function () {
                    $(this).dialog('close');
                }
            }
        });
    }


    function calBalance()
    {
        var balance = new Array;
        var MoneyOut = new Array;
        var MoneyIn = new Array;
        var amount = 0;
        $('.MoneyOut').each(function (e, i) {
            MoneyOut.push($(i).val());
        });

        $('.MoneyIn').each(function (e, i) {
            MoneyIn.push($(i).val());
        });

        var temp_amount = 0;
        $('.balance').each(function (e, i) {
            balance.push($(i).val());
        });
        $('.balance').each(function (e, i) {
            if (e == 0)
            {
                temp_amount = Number($('.balance').eq(e).val());

            } else {
                temp_amount = Number($('.balance').eq(e - 1).val()) - Number(MoneyOut[e]) + Number(MoneyIn[e]);
            }
            $(i).val(temp_amount);
            temp_amount = 0;
        });
    }

    function getSelectionStart(o)
    {
        if (o.createTextRange)
        {
            var r = document.selection.createRange().duplicate()
            r.moveEnd('character', o.value.length)
            if (r.text == '')
                return o.value.length
            return o.value.lastIndexOf(r.text)
        } else
            return o.selectionStart
    }
    function validateField(id, error)
    {
        var flag = 0;
        $(id).each(function (e, v) {
            id = v;
            if ($(id).val() == '' || $(id).val() == 0)
            {
                if ($(id).closest('td').children('div.error-field'))
                {
                    $(id).closest('td').children('div.error-field').remove();
                }
                $(id).closest('td').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' + error + '</div>');
                flag = 1;
            } else {
                if ($(id).closest('td').children('div.error-field'))
                {
                    $(id).closest('td').children('div.error-field').remove();
                }
            }
        });
        if (flag == 1)
        {
            return false;
        } else {
            return true;
        }
    }
    /************ show excel filter form 11-04-2016 ****/
    function getExcelFilterPage()
    {
        var posturl = '<?php echo site_url(); ?>clients/banks/excelFileFilter/';
        $("#bankStatements").ajaxForm({
            url: posturl,
            type: 'post',
            dataType: 'json',
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                $(document).find('#script').html('');
                showSpinner();
            },
            error: function (msg) {
                hideSpinner();
            },
            success: function (data) {
                hideSpinner();
                if (data.success == true)
                {
                    $('#modal-filter-excel .modal-title').html('<?php echo $this->lang->line('BANK_FILTER_EXCEL'); ?>');
                    $('#modal-filter-excel .modal-body').html(data.html);
                    $('#modal-filter-excel').modal('show');
                } else {
                    if (data.url != '')
                        window.location.href = data.url;
                }
            }
        }).submit();

        return false;


        //$('#bankStatements').trigger('submit');
    }
    $(document).off('click').on('click', '#filterExcel', function (e) {
        e.preventDefault();
        var col = [];
        $(".matchDropdown option:selected").each(function (i) {
            col[i] = $(this).val();
        });
        var template = col.length;
        var error = '';

        if ($.inArray("Date", col) == -1)
        {
            error += 'Please match date column.<br>';
        }
        if ($.inArray("Description", col) == -1)
        {
            error += 'Please match description column.<br>';
        }
        if ($.inArray("Money In", col) == -1 && $.inArray("Money Out", col) == -1 && $.inArray("Value", col) == -1)
        {
            error += 'Please match money out and money in or value column.<br>';
        } else if ($.inArray("Money Out", col) == -1 && $.inArray("Value", col) == -1)
        {
            error += 'Please match Money Out column.<br>';
        } else if ($.inArray("Money In", col) == -1 && $.inArray("Value", col) == -1)
        {
            error += 'Please match Money In column.<br>';
        }

        if ($.inArray("Balance", col) == -1)
        {
            error += 'Please match Balance column.<br>';
        }
        if (error != '')
        {
            $('#errorDiv').fadeIn('slow');
            $('#errorDiv').html(error);
            setTimeout(function () {
                $('#errorDiv').fadeOut('slow');
            }, 5000);
            return false;
        }

        var posturl = '<?php echo site_url(); ?>clients/banks/saveExcelColumnMatch/';
        $("#excelColMatchForm").ajaxForm({
            url: posturl,
            type: 'post',
            dataType: 'json',
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                $(document).find('#script').html('');
                showSpinner();
            },
            error: function (msg) {
                hideSpinner();
            },
            success: function (data) {
                hideSpinner();
                if (data.success == true)
                {
                    $("#bankStatements").ajaxForm({
                        url: '<?php echo site_url(); ?>clients/banks/upload/',
                        type: 'post',
                        dataType: 'json',
                        success: function (data1) {
                            if (data1.url != '')
                                window.location.href = data1.url;
                        }
                    }).submit();
                } else {
                    alert(data.error_msg);
                }
            }
        }).submit();


    });
    /* Apply date picker to all fields where required */
    $(document).on('focus', '#statementDate', function () {
        $("#statementDate").datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-50:+1"
        });
    });
    
    $(document).off('click', '.addNewRow').on('click', '.addNewRow', function (e) {
		var rowcounter = $('#statementsListingTable tr').length;
       // var rowHtml = $('#categoriesDropDown').html();
		var rowHtml = $('.exParentCattd').html();
		var href = '<?php echo $this->encrypt->encode('+rowcounter+')?>';
		
        var html = '<tr>';
        html += '<td><input type="hidden" name="statement_key[]" class="statement_key" value="'+href+'"/>';
        html += rowcounter;
        html += '</td>';

        html += '<td>';
        html += '<input type="text" name="Date[]" class="form-control sDatepicker required sm-width-box" value="" />';
        html += '</td>';

        html += '<td>';
        html += '<select name="Type[]" class="form-control statementtype selectCls" style="width:100px !improtant;"> <option value="">--select--</option> <option value="Payment">Payment</option> <option value="Receipts">Receipts </option> <option value="Chq">Chq</option><option value="Deposit">Deposit</option><option value="Other">Other</option></select>';
        html += '</td>';

        html += '<td class="lm-width-box">';
        html += '<input type="text" name="Description[]" class="form-control" value="" />';
        html += '</td>';

        html += '<td class="xs-width-box">';
        html += '<input type="text" name="MoneyOut[]" class="form-control MoneyOut validNumber" value="" />';
        html += '</td>';

        html += '<td class="xs-width-box">';
        html += '<input type="text" name="MoneyIn[]" class="form-control MoneyIn validNumber" value="" />';
        html += '</td>';

        html += '<td class="xs-width-box">';
        html += '<input type="text" name="Balance[]" class="form-control balance validNumber" value="" />';
        html += '</td>';

        html += '<td class="xs-width-box">';
        html += rowHtml;
        html += '</td>';
		
		html += '<td class="xs-width-box exChildCattd">';
        html += '<select id="Category[]" class="form-control category sm-width-box tdtab" name="Category[]">';
		html += '<option selected="selected" value="0">--Select Category--</option>';
		html += '</select>';
        html += '</td>';
									
        html += '<td><a class="btn btn-primary btn-xs color createBankInvoice hide" href="' + href + '">CREATE INVOICE</a><a class="btn btn-success btn-xs color createBankDividend hide" href="' + href + '">CREATE DIVIDEND</a>';
        html += '<a class="btn removeStatementItem"><i class="fa fa-times"></i></a>';
        html += '</td>';

        html += '</tr>';
        rowcounter++;

        $('#statement-listing').append(html);
		$('#statement-listing').find('tr:last').find('select.newCategoryDrop').attr('name','Category[]').attr('id','Category[]').addClass('category');

        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>clients/banks/updateStatementsSession/",
            data: $('#updateStatements').serialize(),
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error: function (msg) {
                hideSpinner();
            },
            success: function (msg) { console.log(msg);
                hideSpinner();
                window.onbeforeunload = false;
            }
        });

    });

    /****************** Start Script for Last Category Press tab and Add New Field   *****************************/

    $(document).on('keydown', '.tdtab:last', function (e) {
        var code = e.keyCode || e.which;
        if (code == 9)
        {
			$( ".addNewRow" ).trigger( "click" );
        }
    });

    /****************** Start Script for Last Category Press tab and Add New Field       *****************************/


    $(document).off('click', '.removeStatementItem').on('click', '.removeStatementItem', function () {        
        $(this).closest('tr').remove();
        $("#statement-listing tr").each(function (index, e) {
            $(this).children("td:first").html(index + 1);
        });
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>clients/banks/updateStatementsSession/",
            data: $('#updateStatements').serialize(),
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error: function (msg) {
                hideSpinner();
            },
            success: function (msg) {
                hideSpinner();
                window.onbeforeunload = false;
            }
        });

    });

    /*******************************  ***********************/


    $(document).on('keyup change', '.statementtype', function (e) {
        var Character = $(this).val();

        var a = ["Receipts", "Chq", "Payment", "Deposit", "Other"];

        for (i = 0; i < a.length; i++) {
            if (a[i].indexOf(Character) != -1) {
                var value = a[i];
            }

        }
        $(this).val(value);

        if ($(this).val() == 'Payment' || $(this).val() == 'Chq'){ 			        	
		    $(this).parent('td').next('td').next('td').children().prop('readonly', false);
            $(this).parent('td').next('td').next('td').find('input:text').attr( 'tabIndex', '' );
            $(this).parent('td').next('td').next('td').next('td').find('input:text').val('');
			$(this).parent('td').next('td').next('td').next('td').find('input:text').attr( 'tabIndex', -1 ); 
        } else if ($(this).val() == 'Receipts' || $(this).val() == 'Deposit'){			
            $(this).parent('td').next('td').next('td').children().prop('readonly', true);
            $(this).parent('td').next('td').next('td').find('input:text').val('');
			$(this).parent('td').next('td').next('td').find('input:text').attr( 'tabIndex', -1 ); 
            $(this).parent('td').next('td').next('td').next('td').children().prop('readonly', false);
			$(this).parent('td').next('td').next('td').next('td').find('input:text').attr( 'tabIndex', '' );  
        } else{
            $(this).parent('td').next('td').next('td').children().prop('readonly', false);
			$(this).parent('td').next('td').next('td').find('input:text').attr( 'tabIndex', '' );
            $(this).parent('td').next('td').next('td').next('td').children().prop('readonly', false);
			$(this).parent('td').next('td').next('td').next('td').find('input:text').attr( 'tabIndex', '' );
        }

    });

    $(document).on('change', '#clientbankid', function (e) {
        var bankId = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>clients/banks/getStatementById/",
            data: {
                'bankId': bankId,
            },
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error: function (msg) {
                hideSpinner();
            },
            success: function (msg) {
                hideSpinner();
                window.onbeforeunload = false;
                $('#bank-listing').html(msg);
            }
        });
    });

	$(document).on('change', '.exParentCat', function (e) { 
		var parentid = $(this).val();  
		var td 		 = $(this).parent().next('td'); 
		
		$.ajax({
			type: "POST",
			url: "<?php echo site_url(); ?>getParentCategoryChild",
			data: {'parentid': parentid},
			beforeSend: function () {                
			},
			success: function (msg) { 
				$(td).html(msg);
			}
		});
	});
</script>
