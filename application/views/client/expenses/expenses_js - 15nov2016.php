<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$user = $this->session->userdata('user');
$j_date = get_filed_year();
?>
<!-- Javascript code for adding invoice -->
<script>
    var prevMileage = 0;
    var expensetask = '';
    var car_cost = 0;
    function openExpenseForm(task, id, title)
    {
        car_cost = 0;
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>expense_form",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', "task": task, "ID": id},
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error: function (msg) {
                hideSpinner();
            },
            success: function (msg) {
                hideSpinner();
                $('.modal-title').html(title);
                $('.modal-expenses').modal('show');
                $('.modal-body').html(msg);
                checkButton('#expenseListItem', '.removeExpenseItem');
                checkButton('#expenseMileageItem', '.removeMileageItem');
                calExpenseItemAmount();
                calTotalMiles();
                /* Set year in datepicker */
                var year = $('.year').val();
                year = year + ":" + year;
                $('.exDatepicker').datepicker('destroy');
                $('.exDatepicker').datepicker({
                    dateFormat: 'dd-mm-yy',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: '0:+0',
                    minDate: '<?php echo $j_date; ?>'
                });
            }
        });
    }

    var dateToday = new Date();
    /* Apply date picker to all fields where required */
    $(document).on('focus', '#paidDate,.sdatepicker', function () {
        $("#paidDate,.sdatepicker").datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-50:+1",
            minDate: '<?php echo $j_date; ?>'
        });
    });

    function performAction(isAjax, href, pdate)
    {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>expense_action",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', "id": href, 'type': isAjax, 'PaidDate': pdate},
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error: function (msg) {
                hideSpinner();
            },
            success: function (msg) {
                hideSpinner();
                $('.modal-expenses').modal('hide');
                msg = JSON.parse(msg);
                //console.log = msg;return false;
                if (msg['error'] != 'error')
                {
                    $('#expense-listing').html('');
                    $('#expense-listing').html(msg['LIST']);
                    $('.ePagination').html(msg['PAGINATION']);
                    dialogBox('Message', msg['MSG']);
                } else {
                    window.location = '<?php echo site_url() . 'expenses' ?>';
                }
            }
        });
    }
    function validateExpenseForm(form, cnmsg, task)
    {
        var flag = 0;
        /* STEP - 1 Check required fields first */
        var text = 'This field is required!';
        if (!validateField('.required', text, 'div'))
        {
            flag = 1;
        }

        /* STEP - 2 Check if expense item data is valid or not */
        error = text;
        if (!validateExpenseItem('.expenseAmount', error))
        {
            flag = 1;
        }

        $('.TotalMiles').each(function () {
            if ($(this).val() != '')
            {
                if (!validateField($(this).closest('tr').find('.m-required'), text, 'td'))
                {
                    flag = 1;
                }
            }
        });

        var regex = <?php echo DATE_FORMAT_REGEX; ?>;
        var error = '<?php echo $this->lang->line('INVALID_DATE_FORMAT'); ?>';
        $('.exDatepicker').each(function () {
            if ($(this).val() != '')
            {
                if (!validateField($(this).closest('tr').find('.ExpenseCategory'), text, 'td') || !validateField($(this).closest('tr').find('.ExpenseMileage'), text, 'td') || !validateField($(this).closest('tr').find('.m-required'), text, 'td'))
                {
                    flag = 1;
                }
                if (!regex.test($(this).val()))
                {
                    if ($(this).closest('td').children('div.error-field'))
                    {
                        $(this).closest('td').children('div.error-field').remove();
                    }
                    $(this).closest('td').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' + error + '</div>');
                    flag = 1;
                }
            }
        });


        if (flag)
        {
            return false;
        }
        if (task == 'create')
        {
            var empty = 0;
            $('.exDatepicker').each(function (e, i) {
                if ($(this).val() == '')
                {
                    empty++;
                }
            });
            if ($('.exDatepicker').length == empty)
            {
                var text = '<?php echo $this->lang->line('EXPENSE_AT_LEAST_ONE_RECORD_ERROR'); ?>';
                dialogBox('Message', text);
                return false;
            }
        }

        var title = '<?php echo $this->lang->line('CLIENT_EXPENSE_ERROR_DIALOG_TITLE'); ?>';
        var text = cnmsg;

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
                    $('.modal').modal('hide');
                    $(form).trigger('submit');
                },
                No: function () {
                    $(this).dialog('close');
                }
            }
        });
    }

    function dialogBox(title, text, el)
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
                    if (el != null)
                    {
                        $(el).focus();
                    }
                    if (expensetask == 'task-completed')
                    {
                        window.location = "<?php echo site_url(); ?>expenses";
                    }
                }
            }
        });
    }

    function expenseItemIndex()
    {
        $("#expenseListItem tr").each(function (i, e) {
            $(this).children("td.sno").html(i + 1);
        });
        return true;
    }

    function expenseMileageIndex()
    {
        $("#expenseMileageItem tr").each(function (i, e) {
            $(this).children("td.sno").html(i + 1);
        });
        return true;
    }

    function checkButton(elem, rmClass)
    {
        //alert($(elem+' tr').length);
        if ($(elem + ' tr').length == 1)
        {
            $(elem).find(rmClass).addClass('hide');
        } else {
            $(elem).find(rmClass).removeClass('hide');
        }
        return true;
    }

    /* Function for getting number upto 2 decimal places */
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

    /* Function to calculate the total amount of expense items */
    function calExpenseItemAmount()
    {
        var total = 0;
        $('.expenseAmount').each(function () {
            total += Number($(this).val());
			var vatPrsnt = $(this).parent().parent().find('.vatPresentation').val();
			var vatAmt = $(this).val()*vatPrsnt/100;			
			if($(this).parent().parent().find('#isVatApplicable').val() == 1){
				$(this).parent().parent().find('.vatAmount').val(vatAmt);				
			}
        });
        $('.TotalItemAmount').val(total.toFixed(2));
        if ($('.MileageExpensed').length > 0)
        {
            total += Number($('.MileageExpensed').val());
        }

        if ($('.vatAmount').length > 0)
        {
            var vatTotal = 0;
            $('.vatAmount').each(function () {
                vatTotal += Number($(this).val());
            });
            $('.TotalVATAmount').val(vatTotal.toFixed(2));
            total += Number($('.TotalVATAmount').val());
        }
        $('.TotalExpenseAmount').val(total.toFixed(2));
        return true;
    }

    function get_prev_car_miles(mileageDate, miles)
    {
        var el = $('#eCustomer').val();
		if(el == '0'){			
			dialogBox('Select employee', 'Please select employee first');
			return false;
		}
        $.ajax({
            type: "POST",
            async: false,
            global: false,
            url: "<?php echo site_url(); ?>expense_get_car_cost",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', "ID": el, 'Year': mileageDate, 'miles': miles},
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
                car_cost = msg['cost'];
				//alert('Car Cost : '+car_cost);
                return car_cost;
                
            }
        });
        return car_cost;
    }

    /* Function to calculate the total mileage of the product */
    function calTotalMiles(vech)
    {      
		var total = 0;
        var car_miles = 0;
        var bike_miles = 0;
        var bicycle_miles = 0;
        var carCost = 0;
        var mileageDate = '';
		var mileageTotalAmt = 0;
        $('.TotalMiles').each(function (e, i) {
            if ($(this).closest('tr').find('.ExpenseMileage option:selected').text() == 'Bike')
            {
                bike_miles += Number($(this).val());
            } else if ($(this).closest('tr').find('.ExpenseMileage option:selected').val() == 32) {
                mileageDate = $(this).closest('tr').find('.mileage-date').val();
				mileageAmt = $(this).closest('tr').find('.TotalMiles').val();
				mileageTotalAmt += Number($(this).val()); 
				//alert(mileageAmt);
				if (vech != 'v' && mileageAmt != '')
                {
                    car_cost = get_prev_car_miles(mileageDate, mileageTotalAmt);
					carCost = car_cost;
                }				
                //carCost += car_cost;

                car_miles += Number($(this).val());
            } else if ($(this).closest('tr').find('.ExpenseMileage option:selected').text() == 'Bicycle') {
                bicycle_miles += Number($(this).val());
            }
            total += Number($(this).val());
        });

        var me = mileagedExpensed(bike_miles, bicycle_miles);
        me = Number(me) + Number(carCost);
		//me = Number(carCost);
        $('.totalMiles').val(total);
        $('.MileageExpensed').val(me.toFixed(2));
        me += Number($('.TotalItemAmount').val());

        if ($('.vatAmount').length > 0)
        {
            var vatTotal = 0;
            $('.vatAmount').each(function () {
                vatTotal += Number($(this).val());
            });
            $('.TotalVATAmount').val(vatTotal.toFixed(2));
            me += Number($('.TotalVATAmount').val());
        }
        $('.TotalExpenseAmount').val(me.toFixed(2));
        return true;
    }

    function mileagedExpensed(bike_miles, bicycle_miles)
    {
        //var car_cost 		= 0;
        var bike_cost = 0;
        var bicycle_cost = 0;
        var total_cost = 0;

        var MILEAGE_DISTANCE_LIMIT = Number('<?php echo MILEAGE_DISTANCE_LIMIT; ?>');
        var MILEAGE_EXCEED_COST = Number('<?php echo MILEAGE_EXCEED_COST; ?>');
        //var CAR_MILEAGE_COST 		= Number('<?php echo CAR_MILEAGE_COST; ?>');
        var BIKE_MILEAGE_COST = Number('<?php echo BIKE_MILEAGE_COST; ?>');
        var CYCLE_MILEAGE_COST = Number('<?php echo CYCLE_MILEAGE_COST; ?>');

        /* CASE - ONE : Car
         if(prevMileage > MILEAGE_DISTANCE_LIMIT)
         {
         car_cost = (car_miles * MILEAGE_EXCEED_COST)/100;
         }else{
         total_miles = prevMileage + car_miles;
         if(total_miles < MILEAGE_DISTANCE_LIMIT)
         {
         car_cost = (car_miles * CAR_MILEAGE_COST)/100;
         }else{
         car_cost_am = total_miles - MILEAGE_DISTANCE_LIMIT;
         car_cost_bm = car_miles - car_cost_am;
         car_cost = (car_cost_am * MILEAGE_EXCEED_COST)/100 + (car_cost_bm * CAR_MILEAGE_COST)/100;
         }
         }
         */
        var above_miles = 0;
        /* CASE - TWO : Bike */
        if (bike_miles > 0)
        {
            bike_cost = (bike_miles * BIKE_MILEAGE_COST) / 100;
        }
        var above_miles = 0;
        /* CASE - THREE : Bicycle */
        if (bicycle_miles > 0)
        {
            bicycle_cost = (bicycle_miles * CYCLE_MILEAGE_COST) / 100;
        }
        //total_cost = Number(car_cost) + Number(bike_cost) + Number(bicycle_cost);
        total_cost = Number(bike_cost) + Number(bicycle_cost);		
        return total_cost;
    }

    function validateField(id, error, elem)
    {
        var flag = 0;
        $(id).each(function (e, v) {
            id = v;
            if ($(id).val() == '' || $(id).val() == 0)
            {
                if ($(id).closest(elem).children('div.error-field'))
                {
                    $(id).closest(elem).children('div.error-field').remove();
                }
                $(id).closest(elem).append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' + error + '</div>');
                flag = 1;
            } else {
                if ($(id).closest(elem).children('div.error-field'))
                {
                    $(id).closest(elem).children('div.error-field').remove();
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

    function validateExpenseItem(id, error)
    {
        var flag = 0;
        $(id).each(function (e, v) {
            id = v;
            if ($(id).val() != '')
            {
                if ($(id).closest('tr').find('.exDatepicker').parent('td').children('.error-field').length > 0 ||
                        $(id).closest('tr').find('.ExpenseCategory').parent('td').children('.error-field').length > 0
                        )
                {
                    $(id).closest('tr').find('.exDatepicker').parent('td').children('.error-field').remove();
                    $(id).closest('tr').find('.ExpenseCategory').parent('td').children('.error-field').remove();
                }

                if ($(id).closest('tr').find('.exDatepicker').val() == '')
                {
                    $(id).closest('tr').find('.exDatepicker').parent('td').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' + error + '</div>');
                    flag = 1;
                } else {
                    $(id).closest('tr').find('.exDatepicker').parent('td').children('.error-field').remove();
                }

                if ($(id).closest('tr').find('.ExpenseCategory').val() == 0)
                {
                    $(id).closest('tr').find('.ExpenseCategory').parent('td').append('<div class="alert alert-danger error-field"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' + error + '</div>');
                    flag = 1;
                } else {
                    $(id).closest('tr').find('.ExpenseCategory').parent('td').children('.error-field').remove();
                }
            } else {
                if ($(id).closest('tr').find('.error-field').length > 0)
                {
                    $(id).closest('tr').find('.error-field').remove();
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


    $(document).ready(function () {
        $(document).off('change', '#eCustomer').on('change', '#eCustomer', function (e, i) {
            var el = $(this).val();
            var year = $('.year').val();

            if (el != 0 && year != 0)
            {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url(); ?>expense_get_car_miles",
                    data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', "ID": el, 'Year': year},
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
                        prevMileage = msg['miles'];
                        calTotalMiles();
                        $('#prevMiles').html(prevMileage);
                    }
                });
            } else {
                prevMileage = 0;
            }

        });

        /* Apply date picker to all fields where required */
        $(document).on('focus', '.exDatepicker', function () {
            $(this).datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "0:+3",
                minDate: '<?php echo $j_date; ?>'
            });
        });
        $(document).on('change', '.year', function (e, i) {
            var year = $(this).val();
            var ma = '01-' + '06-' + year;
            year = year + ":" + '+1';//year;
            $('.exDatepicker').datepicker('destroy');
            $('.exDatepicker').datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                yearRange: year,
                minDate: '<?php echo $j_date; ?>'
            });
        });

        $('.modal-expenses').on('show.bs.modal', function () {
            $.fn.modal.Constructor.prototype.enforceFocus = function () { };
        });
        /* Hover effect over the label of expense listing */
        $(document).on('mouseover', '.sort,.markPaid,.copyExpense', function (e) {
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

        /* Set the bootstrap modal size */
        $(document).find('.modal-dialog').css({width: '90%', height: 'auto', 'max-height': '100%'});
        $('.modal-expenses').on('hide.bs.modal', function () {
            $('.modal-body').html();
        });



        $(document).on('click', '.openExpenseForm', function (e) {
            e.preventDefault();
            var task = "<?php echo $this->encrypt->encode('addExpense'); ?>";
            var title = "<?php echo $this->lang->line('CLIENT_INVOICE_NEW_EXPENSE'); ?>";
            openExpenseForm(task, '', title);
        });

        $(document).on('click', '.creditCard', function (e) {
            e.preventDefault();
            //alert('here iame');
            var task = "<?php echo $this->encrypt->encode('addCreditCard'); ?>";
            var title = "<?php echo $this->lang->line('EXPENSE_NEW_CREDIT_CARD_STATEMENT_TITLE'); ?>";
            openExpenseForm(task, '', title);
        });

        /* This block will add the expense item */
        $(document).off('click', '.addExpenseItem').on('click', '.addExpenseItem', function (e) {
            e.preventDefault();
            var html = $('#expenseListItem tr:first-child').clone();			
            $('#expenseListItem').append(html);
            $('#expenseListItem tr:last-child input').each(function (i, e) {
				if( !$(this).hasClass('vatPresentation'))
					$(this).val('');
            });
            $('#expenseListItem tr:last-child .ExpenseCategory ').each(function (i, e) {
                $(this).val('0');

            });
            $('#expenseListItem tr:last-child .exDatepicker').each(function (i, e) {
                $(this).removeAttr('id').removeClass('hasDatepicker');
            });

            $('#expenseListItem tr:last-child .exDatepicker').each(function (i, e) {
                $(this).datepicker('destroy');
                $(this).datepicker({
                    dateFormat: 'dd-mm-yy',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-50:+3",
                    minDate: '<?php echo $j_date; ?>'
                });
            });

            /* Check if error field is present, if present then remove it */
            if ($('#expenseListItem tr:last-child').find('.error-field').length > 0)
            {
                $('#expenseListItem tr:last-child').find('.error-field').remove();
            }


            expenseItemIndex();
            checkButton('#expenseListItem', '.removeExpenseItem');
        });

        /* This block will remove the expense item */
        $(document).off('click', '.removeExpenseItem').on('click', '.removeExpenseItem', function (e) {
            e.preventDefault();
            if ($('#expenseListItem tr').length == 1)
            {
                $(this).find('.removeExpenseItem').addClass('hide');
            } else {
                var delitemId = $(this).attr('id');
                var val = $('#delexpItem').val();
                if (delitemId != null) {
                    if (val == '') {
                        $('#delexpItem').val(delitemId);
                    } else {
                        $('#delexpItem').val(val + ',' + delitemId);
                    }
                } else {
                    var val = $('#delexpItem').val();
                    if (delitemId != null) {
                        $('#delexpItem').val(val + ',' + delitemId);
                    }
                }
                $(this).find('.removeExpenseItem').removeClass('hide');
            }
            $(this).closest('tr').remove();
            expenseItemIndex();
            calExpenseItemAmount();
            checkButton('#expenseListItem', '.removeExpenseItem');
        });

        /* This block will add the expense item */
        $(document).off('click', '.addExpenseMileage').on('click', '.addExpenseMileage', function (e) {
            e.preventDefault();
            $('#expenseMileageItem tr:first-child').clone().appendTo('#expenseMileageItem');
            $('#expenseMileageItem tr:last-child input').each(function (i, e) {
                $(this).val('');
            });
            $('#expenseMileageItem tr:last-child .ExpenseMileage ').each(function (i, e) {
                $(this).val('0');
            });
            $('#expenseMileageItem tr:last-child .exDatepicker').removeClass('hasDatepicker');

            $('#expenseMileageItem tr:last-child .exDatepicker').each(function (i, e) {
                $(this).removeAttr('id').removeClass('hasDatepicker');
            });

            $('#expenseMileageItem tr:last-child .exDatepicker').each(function (i, e) {
                $(this).datepicker('destroy');
                $(this).datepicker({
                    dateFormat: 'dd-mm-yy',
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-50:+3",
                    minDate: '<?php echo $j_date; ?>'
                });
            });

            /* Check if error field is present, if present then remove it */
            if ($('#expenseMileageItem tr:last-child').find('.error-field').length > 0)
            {
                $('#expenseMileageItem tr:last-child').find('.error-field').remove();
            }
            expenseMileageIndex();
            checkButton('#expenseMileageItem', '.removeMileageItem');
        });

        /* This block will remove the mileage item*/
        $(document).off('click', '.removeMileageItem').on('click', '.removeMileageItem', function (e) {
            e.preventDefault();
            if ($('#expenseMileageItem tr').length == 1)
            {
                $(this).find('.removeMileageItem').addClass('hide');
            } else {
                var delitemId = $(this).attr('id');
                if ($('#delexpItem').val() == '') {
                    if (delitemId != null) {
                        $('#delexpItem').val(delitemId);
                    }
                } else {
                    var val = $('#delexpItem').val();
                    if (delitemId != null) {
                        $('#delexpItem').val(val + ',' + delitemId);
                    }
                }
                $(this).find('.removeMileageItem').removeClass('hide');
            }
            $(this).closest('tr').remove();
            expenseMileageIndex();
            calTotalMiles();
            checkButton('#expenseMileageItem', '.removeMileageItem');
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

        /* This block will calculate the amount of each expense item */
        $(document).on('change', '.expenseAmount,.vatAmount,.vatPresentation' , function () {
			calExpenseItemAmount();
        });

        /* This block will calculate the total miles of the expense */
        $(document).off('change', '.TotalMiles').on('change', '.TotalMiles', function () {
            /* STEP - 1 First check if any one method is selected */
            var elem = $(this).closest('tr').find('.ExpenseMileage');
            if (elem.val() == 0)
            {
                $(this).val('');
                var title = 'Message';
                var text = '<?php echo $this->lang->line('EXPENSE_SELECT_METHOD_ERROR'); ?>';
                dialogBox(title, text, elem);
            }
            calTotalMiles();
        });

        $(document).on('click', '#save-expense', function (e) {
            e.preventDefault();
            $('#task').val('<?php echo $this->encrypt->encode('save'); ?>');
            var msg = '<?php echo $this->lang->line('EXPENSE_SAVE_CONFIRMATION'); ?>';
            validateExpenseForm('#expenseForm', msg);
        });

        $(document).on('click', '#create-expense', function (e) {
            e.preventDefault();
            $('#task').val('<?php echo $this->encrypt->encode('create'); ?>');
            var msg = '<?php echo $this->lang->line('EXPENSE_CREATE_CONFIRMATION'); ?>';
            var task = 'create';
            validateExpenseForm('#expenseForm', msg, task);
            calExpenseItemAmount();

        });

        $(document).on('click', '.editExpense', function (e, i) {
            e.preventDefault();
            var href = $(this).attr('href');
            var task = "<?php echo $this->encrypt->encode('editExpense'); ?>";
            if ($(this).hasClass('creditcard'))
            {
                var title = "<?php echo $this->lang->line('EXPENSE_EDIT_CREDIT_CARD_POPUP_TITLE'); ?>";
            } else {
                var title = "<?php echo $this->lang->line('EXPENSE_EDIT_POPUP_TITLE'); ?>";
            }

            openExpenseForm(task, href, title);
        });

        $(document).on('click', '.copyExpense', function (e) {
            e.preventDefault();
            var task = '<?php echo $this->encrypt->encode('copyExpense'); ?>';
            var id = $(this).attr('href');
            var title = "<?php echo $this->lang->line('EXPENSE_COPY_FORM_TITLE'); ?>";
            openExpenseForm(task, id, title);
        });

        $(document).on('click', '.viewExpense', function (e, i) {
            e.preventDefault();
            var href = $(this).attr('href');
            var task = "<?php echo $this->encrypt->encode('viewExpense'); ?>";
            if ($(this).hasClass('creditcard'))
            {
                var title = "<?php echo $this->lang->line('EXPENSE_VIEW_CREDIT_CARD_POPUP_TITLE'); ?>";
            } else {
                var title = "<?php echo $this->lang->line('EXPENSE_VIEW_POPUP_TITLE'); ?>";
            }
            openExpenseForm(task, href, title);
        });

        $(document).on('click', '#update-expense', function (e) {
            e.preventDefault();
            $('#task').val('<?php echo $this->encrypt->encode('update'); ?>');
            var msg = '<?php echo $this->lang->line('EXPENSE_UPDATE_CONFIRMATION'); ?>';
            validateExpenseForm('#updateExpenseForm', msg);
        });

        $(document).on('click', '#createUpdateExpense', function (e) {
            e.preventDefault();
            $('#task').val('<?php echo $this->encrypt->encode('create'); ?>');
            var msg = '<?php echo $this->lang->line('EXPENSE_CREATE_CONFIRMATION'); ?>';
            var task = 'create';
            validateExpenseForm('#updateExpenseForm', msg, task);
        });

        /* Reset the search fields */
        $(document).on('click', '.reset', function (e) {
            e.preventDefault();
            $('#EmployeeID').val('0');
            $('#Month').val('0');
            $('#Year').val('0');

            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>expense_clean",
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
                    $('#expense-listing').html(msg['html']);
                    $('.ePagination').html(msg['pagination']);
                }
            });
        });

        /* Reset the search fields */
        $(document).on('click', '.resetreport', function (e) {
            e.preventDefault();
            $('#ExpensereportCategory').val('0');
            $('#Year').val('0');

            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>expense_report_clean",
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
                    $('#expense-listing').html(msg['html']);
                    $('.ePagination').html(msg['pagination']);
                    $('.res_total').html(msg['totalAmount']);
                }
            });
        });

        /* This block deletes the expense directly */
        $(document).on('click', '.deleteExpense', function (e) {
            e.preventDefault();
            ajaxcall = false;
            var href = $(this).attr('href');
            var title = '<?php echo $this->lang->line('CLIENT_EXPENSE_DELETE_CONFIRM_TITLE'); ?>';
            var text = '<?php echo $this->lang->line('CLIENT_EXPENSE_DELETE_CONFIRM'); ?>';
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
                        performAction(ajaxcall, href);
                    },
                    No: function () {
                        $(this).dialog('close');
                    }
                }
            });
        });

        /* This blOck change the expense status to paid */
        $(document).on('click', '.markPaid', function (e) {
            e.preventDefault();
            //alert('You clicked mee');
            ajaxcall = false;
            var href = $(this).attr('href');
            var datetext = "Please choose the paid date <input type='text' name='paidDate'id='paidDate' class='exDatepicker'/><br/><br/>";

            var title = '<?php echo $this->lang->line('CLIENT_EXPENSE_PAID_CONFIRM_TITLE'); ?>';
            var text = datetext + "<?php echo $this->lang->line('CLIENT_EXPENSE_PAID_CONFIRM'); ?>";
            usertask = 'mp';
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
                        pdate = $('#paidDate').val();
                        performAction(ajaxcall, href, pdate);
                    },
                    No: function () {
                        $(this).dialog('close');
                    }
                }
            });
        });

        $(document).off('change', '.ExpenseMileage').on('change', '.ExpenseMileage', function (e) {
            e.preventDefault();
            calTotalMiles();
        });
		$(document).off('change', '.mileage-date').on('change', '.mileage-date', function (e) {
			e.preventDefault();
            calTotalMiles();
        });

        /* This block performs the sorting operation */
        $(document).on('click', '.sort', function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var text = $(this).text();
            var se = $(this);
            var dir = '';

            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>expense_expense_sort",
                data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', order: href},
                beforeSend: function () {
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                error: function (msg) {
                    hideSpinner();
                },
                success: function (msg) {
                    hideSpinner();
                    if ($('.table-responsive th a').children('i').length > 0)
                    {
                        $('.table-responsive th a i').remove();
                    }
                    se.append('<i class="fa fa-sort-desc"></i>');
                    msg = JSON.parse(msg);
                    se.children('i').addClass(msg['dir']);
                    $('#expense-listing').html(msg['html']);
                }
            });
        });
    });
</script>
<script>
    $(document).on('click', '.uploadExpenses', function (e) {
        e.preventDefault();
        $(this).unbind('click');
        var task = '<?php echo $this->encrypt->encode('uploadExpense'); ?>';
        var id = '';
        var title = "<?php echo $this->lang->line('EXPENSE_UPLOAD_FORM_TITLE'); ?>";
        openExpenseForm(task, id, title);
    });

    $(document).on('click', '.creditStatements', function (e) {
        e.preventDefault();
        $(this).unbind('click');
        var task = '<?php echo $this->encrypt->encode('uploadCredit'); ?>';
        var id = '';
        var title = "<?php echo $this->lang->line('EXPENSE_UPLOAD_CREDIT_FORM_TITLE'); ?>";
        openExpenseForm(task, id, title);
    });
<?php if (isset($page) && $page == 'expenses'): ?>
        $(document).on('click', '.uploadExpense', function (e) {
            e.preventDefault();
            var f = $('#file').val();
            if (f == '')
            {
                var title = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TITLE'); ?>';
                var text = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TEXT'); ?>';
                dialogBox(title, text);
                return false;
            } else {
                //$('.modal-expenses').modal('hide');
                uploadTemplate('');
            }
        });
<?php endif; ?>
    $(document).on('click', '.uploadCredit', function (e) {
        e.preventDefault();
        var f = $('#file').val();
        if (f == '')
        {
            var title = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TITLE'); ?>';
            var text = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TEXT'); ?>';
            dialogBox(title, text);
            return false;
        } else {
            //$('.modal-expenses').modal('hide');
            uploadTemplate('credit');
        }
    });
    $('.uploadExpense').click(function (e) {
        e.preventDefault();
        var f = $('#file').val();
        if (f == '')
        {
            var title = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TITLE'); ?>';
            var text = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TEXT'); ?>';
            dialogBox(title, text);
            return false;
        } else {
            $('.modal-expenses').modal('hide');
            uploadTemplate('');
        }
    });

    $(document).on('change', '.mileage_year', function (e) {
        var el = $('#eCustomer').val();
        var year = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>expense_get_car_miles",
            data: {'<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>', "ID": el, 'Year': year},
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
                prevMileage = msg['miles'];
                calTotalMiles();
                $('#prevMiles').html(prevMileage);
            }
        });
    });

    function uploadTemplate(tpl)
    {
        var fn = 'uploadExpenses';
        var tt = 'Expense Template Items';
        var frm = '#uExpense';
        if (tpl != '')
        {
            fn = 'uploadCredit';
            tt = 'Credit Statement Items';
            frm = '#Credit';
        }
        var task = "<?php echo $this->encrypt->encode('addExpense'); ?>";
        var id = '';
        var formUrl = "<?php echo site_url(); ?>expenses/" + fn;
        var formData = new FormData($(frm)[0]);

        $.ajax({
            url: formUrl,
            type: 'POST',
            data: formData,
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error: function (error) {
                hideSpinner();
            },
            success: function (msg) {
                hideSpinner();
                msg = JSON.parse(msg);
                if (msg['error'] != 'error')
                {
                    $('.modal-title').html(tt);
                    $('.modal-body').html(msg['html']);
                    $('.modal-expenses').modal('show');
                    checkButton('#expenseListItem', '.removeExpenseItem');
                    checkButton('#expenseMileageItem', '.removeMileageItem');
                    calExpenseItemAmount();
                    calTotalMiles();
                } else {
                    window.location = '<?php echo site_url() . 'expenses' ?>';
                }
            }
        });
        return false;
    }

    /*$(document).on('change', '#Searchtype', function (e) {
     if ($(this).val() == 1) {
     $('.category').css('display', 'none');
     } else {
     $('.category').css('display', 'block');
     }
     });*/
	/******** Start Script for Last field Press tab and Add New Field   ***********/

    $(document).on('keydown', '.tdtabamount:last', function (e) { 
        var code = e.keyCode || e.which;
        if (code == 9)
        {
			$( ".addExpenseItem" ).trigger( "click" );
		}
    });
	$(document).on('keydown', '.tdtabmiles:last', function (e) { 
        var code = e.keyCode || e.which;
        if (code == 9)
        {
			$( ".addExpenseMileage" ).trigger( "click" );
		}
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
	
	
	// On category change check vat aplicable or not
	$(document).on('change', '.category', function (e) {
		var id = $(this).val();		
		console.log($(this).parent().closest('tr'));
		console.log($(this).parent().closest('tr').find('td input#isVatApplicable'));
		var vatTd  = $(this).parent().closest('tr').find('td input#isVatApplicable'); 
		$.ajax({
			type: "POST",
			url: "<?php echo site_url(); ?>checkVatApplicable",
			data: {'id':id},
			dataType: "json",
			beforesend: function(){
				
			},
			success: function (msg){
				//alert(msg.vatApplicable);
				if(msg.vatApplicable == 1){
					//vatTd.removeAttr('disabled');
					vatTd.val('1'); 
				}
			},
		});
	});	
	
	/****************** Start Script for Last Category Press tab and Add New Field   *****************************/

    $(document).on('keydown', '.expensestab:last', function (e) { 
		var code = e.keyCode || e.which;		
        if (code == 9)
        {
			$( ".addExpenseItem" ).trigger( "click" );
        }
    });
	
	$(document).on('keydown', '.milestab:last', function (e) { 
		var code = e.keyCode || e.which;		
        if (code == 9)
        {
			$( ".addExpenseMileage" ).trigger( "click" );
        }
    });
	
	
</script>
