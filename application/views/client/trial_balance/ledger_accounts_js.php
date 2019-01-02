<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
	$(document).ready(function(){
		$(document).find('.modal-dialog').css({width:'90%',height:'auto','max-height':'100%'});
		$('.datepicker').datepicker({
			dateFormat: '<?php echo CASHMAN_DATE_FORMATE;?>',
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+3"
		});
		$(document).on('change','#JournalCategories',function(){
			submitLedgerFilters();
		});
		/*
		$(document).on('change','#TBYear',function(){
			submitLedgerFilters();
		});
		$(document).on('change','#source',function(){
			submitLedgerFilters();
		});
		*/
		
		/* Bind Invoice Details Popup */
		$(document).on('click','.showPaid',function(e){
			e.preventDefault();
			var task = '<?php echo $this->encrypt->encode('displayInvoice');?>';
			userTask = 'sp';
			var id = $(this).attr('href');
			var title = "<?php echo $this->lang->line('CLIENT_INVOICE_PAID_INVOICE_TITLE');?>";
			openInvoiceForm(task,id,title);
		});
		
		/* Bind Expense Details Popup */
		$(document).on('click','.viewExpense',function(e,i){
			e.preventDefault();
			var href 	= 	$(this).attr('href');
			var task 	= 	"<?php echo $this->encrypt->encode('viewExpense');?>";
			if($(this).hasClass('creditcard'))
			{
				var title 	= 	"<?php echo $this->lang->line('EXPENSE_VIEW_CREDIT_CARD_POPUP_TITLE');?>";
			}else{
				var title 	= 	"<?php echo $this->lang->line('EXPENSE_VIEW_POPUP_TITLE');?>";
			}
			openExpenseForm(task,href,title);
		});
		
		/* View the paid dividend voucher */
		$(document).on('click','.viewDividend',function(e){
			e.preventDefault();
			var id = $(this).attr('href');
			var task = "<?php echo $this->encrypt->encode('viewDividend');?>";
			var title = "<?php echo $this->lang->line('DIVIDEND_VIEW_FORM_TITLE');?>";
			title = title.replace('%s',"");
			openDividendForm(task,id,title);
		});
		
		/* View the paid dividend voucher */
		$(document).on('click','.showLedgerDetails',function(e){
			e.preventDefault();
			var id = $(this).attr('href');
			var task = $(this).attr('data-type');
			var title = "Ledger Details";
			openLedgerDetailsForm(task,id,title);
		});
	});
	
	function submitLedgerFilters(){
		initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
		showSpinner();
		jQuery('#ledgerFilters').submit();
	}
	
	function openInvoiceForm(task,id,title,type)
	{
		$('.modal-title').html(title);
		
		$.ajax({
			type: "POST",
			url: "<?php echo site_url();?>client/editInvoice/",
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"InvoiceID":id,"task":task,'type':type},
			beforeSend:function(){
				initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
				showSpinner();
			},
			error:function(msg){
				hideSpinner();
			},
			success:function(msg){
				hideSpinner();
				$('.modal-body').html(msg);
				$('.modal-ledger').modal('show');
			}
		});
	}
	
	function openExpenseForm(task,id,title)
	{
		$.ajax({
			type: "POST",
			url: "<?php echo site_url();?>expense_form",
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"ID":id},
			beforeSend:function(){
				initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
				showSpinner();
			},
			error:function(msg){
				hideSpinner();
			},
			success:function(msg){
				hideSpinner();
				$('.modal-title').html(title);
				$('.modal-ledger').modal('show');
				$('.modal-body').html(msg);
			}
		});
	}
	
	function openDividendForm(task,id,title)
	{
		$('.modal-title').html(title);
		$('.modal-ledger').modal('show');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url();?>new_dividend",
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"ID":id},
			beforeSend:function(){
				$('.modal-body').html('<img src="<?php echo site_url();?>assets/images/loading.gif"/>');
			},
			error:function(msg){
				//alert(msg.responseText);
			},
			success:function(msg){
				$('.modal-body').html(msg);
			}
		});
	}
	
	function openLedgerDetailsForm(task,id,title)
	{
		$('.modal-title').html(title);
		$('.modal-ledger').modal('show');
		$.ajax({
			type: "POST",
			url: "<?php echo site_url();?>show_ledger_details",
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":task,"ID":id},
			beforeSend:function(){
				$('.modal-body').html('<img src="<?php echo site_url();?>assets/images/loading.gif"/>');
			},
			error:function(msg){
				//alert(msg.responseText);
			},
			success:function(msg){
				$('.modal-body').html(msg);
			}
		});
	}
</script>