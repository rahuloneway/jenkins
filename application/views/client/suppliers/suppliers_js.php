<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.changestatus', function (e) {
            e.preventDefault();

            var title = "<?php echo $this->lang->line('CLIENT_SUPPLIERS_STATE_CHANGE_TITLE'); ?>";
            var text = "<?php echo $this->lang->line('CLIENT_SUPPLIERS_CHANGE_STATUS'); ?>";
            var id = $(this).attr('id');
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
                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>clients/suppliers/changesupplierstatus",
                            data: {
                                'id': id
                            },
                            beforeSend: function () {

                            },
                            error: function (msg) {
                                alert(msg.responseText);
                            },
                            success: function (msg) {
                                location.reload();
                            }
                        });
                    },
                    No: function () {
                        $(this).dialog('close');
                    },
                }
            });
        });
    });
    $(document).ready(function () {
        $(document).on('click', '.deletecustomer', function (e) {
            e.preventDefault();

            var title = "<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_DELETE_TITLE'); ?>";
            var text = "<?php echo $this->lang->line('CLIENT_INVOICE_MSG_BOX_DELETE_TEXT'); ?>";
            var id = $(this).attr('id');
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
                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>clients/suppliers/deletesupplier",
                            data: {
                                'id': id
                            },
                            beforeSend: function () {

                            },
                            error: function (msg) {
                                alert(msg.responseText);
                            },
                            success: function (msg) {
                                location.reload();
                            }
                        });
                    },
                    No: function () {
                        $(this).dialog('close');
                    },
                }
            });
        });
    });
    $(document).ready(function () {
        $(document).on('change', '#suppplier', function (e) {
            e.preventDefault();

            $('#supplier-search').submit();
        });
    });
    $(document).ready(function () {
        $(document).on('change', '.form-control', function (e) {
            $(this).css('border', '');
        });
    });
    $(document).ready(function () {
        $(document).on('click', '.add-supplier', function (e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $('.modal-supplier-form .modal-title').html('<?php echo $this->lang->line('SUPPLIERS_ADD'); ?>');
            $('.modal-supplier-form').modal('show');
            $('.modal-supplier-form').css({width: '100%', height: 'auto', 'max-height': '100%'});
            $('#create-supplier').html('<i class="fa fa-file-text"></i>Create');
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>clients/suppliers/supplierForm",
                data: {'id': id},
                beforeSend: function () {
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                success: function (msg) {
                    hideSpinner();
                    $('.modal-supplier-form .modal-body').html(msg);
                }
            });
        });
    });

    $(document).ready(function () {
        $(document).on('click', '.editcustomer', function (e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $('.modal-supplier-form .modal-title').html('<?php echo $this->lang->line('SUPPLIERS_EDIT'); ?>');
            $('.modal-supplier-form').modal('show');
            $('.modal-supplier-form').css({width: '100%', height: 'auto', 'max-height': '100%'});
            $('#create-supplier').html('<i class="fa fa-file-text"></i>Update');
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>clients/suppliers/supplierForm",
                data: {'id': id},
                beforeSend: function () {
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                success: function (msg) {
                    hideSpinner();
                    $('.modal-supplier-form .modal-body').html(msg);
                }
            });
        });
    });

    $(document).ready(function () {
        $(document).on('click', '#create-supplier', function (e) {
            e.preventDefault();
            if ($('#firstname').val() == '') {
                $('#firstname').css('border', '1px solid #b92c28');
                $('#firstname').focus();
                $("#fnameerror").css("display", "block");
                return false;
            } else
                ($('#firstname').val() != '')
            {
                $("#fnameerror").css("display", "none");
            }
            if ($('#lastname').val() == '') {
                $('#lastname').css('border', '1px solid #b92c28');
                $('#lastname').focus();
                $("#lnameerror").css("display", "block");
                return false;
            } else
                ($('#firstname').val() != '')
            {
                $("#lnameerror").css("display", "none");
            }
            if ($('#email').val() == '') {
                $('#email').css('border', '1px solid #b92c28');
                $('#email').focus();
                $("#emailerror").css("display", "block");
                $("#emailerror3").css("display", "none");
                $("#emailerror2").css("display", "none");
                return false;
            } else
                ($('#email').val() != '')
            {
                $("#emailerror").css("display", "none");
            }

            if ($('#phone').val() == '') {
                $('#phone').css('border', '1px solid #b92c28');
                $('#phone').focus();
                $("#phoneerror").css("display", "block");
                return false;
            } else
                ($('#phone').val() != '')
            {
                $("#phoneerror").css("display", "none");
            }
            if ($('#mobile').val() == '') {
                $('#mobile').css('border', '1px solid #b92c28');
                $('#mobile').focus();
                $("#mobileerror").css("display", "block");
                return false;
            } else
                ($('#mobile').val() != '')
            {
                $("#mobileerror").css("display", "none");
            }
            if ($('#vat_registration_no').val() == '') {
                $('#vat_registration_no').css('border', '1px solid #b92c28');
                $('#vat_registration_no').focus();
                $("#vaterror").css("display", "block");
                return false;
            } else
                ($('#vat_registration_no').val() != '')
            {
                $("#vaterror").css("display", "none");
            }
            if ($('#vat_registration_no').val().length < 10) {
                $('#vat_registration_no').css('border', '1px solid #b92c28');
                $('#vat_registration_no').focus();
                $("#vaterror2").css("display", "block");
                return false;
            } else
                ($('#vat_registration_no').val() != '')
            {
                $("#vaterror2").css("display", "none");
            }
            if ($('#company').val() == '') {
                $('#company').css('border', '1px solid #b92c28');
                $('#company').focus();
                $("#companyerror").css("display", "block");
                return false;
            } else
                ($('#company').val() != '')
            {
                $("#companyerror").css("display", "none");
            }

            var frmdata = $('#frm-supplier').serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>clients/suppliers/save",
                data: frmdata,
                beforeSend: function () {
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                success: function (msg) {
                    hideSpinner();
                    location.reload();
                }
            });


        });
    });


    $(document).ready(function () {
        $(document).on('change', '#postcode', function (e) {
            e.preventDefault();

            var regxss = /^([A-PR-UWYZ0-9][A-HK-Y0-9][AEHMNPRTVXY0-9]?[ABEHMNPRVWXY0-9]? {1,2}[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA)$/;
            if (!regxss.test($('#postcode').val())) {
                $('#postcode').css('border', '1px solid #b92c28');
                $('#postcode').focus();
                $("#posterror").css("display", "block");
                return false;
            } else
            {
                $("#posterror").css("display", "none");
            }

        });
    });

    $(document).ready(function () {
        $(document).on('change', '#email', function (e) {
            e.preventDefault();
            var regx = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!regx.test($('#email').val()))
            {
                $('#emailerror2').css('display', 'block');
                $('#email').css('border', '1px solid #b92c28');
                $('#email').val('');
                $('#email').focus();
                $('#emailerror').css('display', 'none');
                $('#emailerror3').css('display', 'none');
                return false;
            } else {
                $("#emailerror2").css("display", "none");
            }
            $.ajax({
                type: "POST",
                url: "<?php echo site_url(); ?>clients/suppliers/checkSupplieremail",
                data: {
                    'email': $(this).val(),
                    'id': $('#customerid').val()
                },
                beforeSend: function () {
                    initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                    showSpinner();
                },
                success: function (msg) {
                    hideSpinner();
                    if (msg == 1) {
                        $("#emailerror3").css("display", "block");
                        $('#email').css('border', '1px solid #b92c28');
                        $('#email').val('');
                        $('#email').focus();
                        return false
                    } else {
                        $("#emailerror3").css("display", "none");
                    }

                }
            });


        });
    });

    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(emailAddress);
    }
    ;


    function validatecustomerData(title, msg)
    {
        BootstrapDialog.show({
            message: msg,
            title: title,
            closable: false,
            buttons: [{
                    label: 'ok',
                    action: function (dialogRef) {
                        dialogRef.close();
                    }
                }]
        });
    }

    $(document).on('keypress', '.validNumber', function (eve) {
        if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57)) {
            if (eve.which != 8)
            {
                eve.preventDefault();
            }
        }

        $('.validNumber').keyup(function (eve) {
            if ($(this).val().indexOf('.') == 0) {
                $(this).val($(this).val().substring(1));
            }
        });
    });
</script>
