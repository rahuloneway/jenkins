<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
$(document).ready(function(){
	$('.modal-dashboard').on('show.bs.modal',function (e) {
		$(this).find('.modal-dialog').css({width:'90%',height:'auto','max-height':'100%'});
		$('.modal-dashboard .modal-body').load(e.currentTarget.href);
	});

	$(document).on('hidden.bs.modal', '.modal', function(e) {
		$('.modal-body').html('');
	});
	
	$('.modal-dashboard').on('show.bs.modal', function () {
		$.fn.modal.Constructor.prototype.enforceFocus = function () { };
	});
	
	$(document).on('click','.open_form',function(e){
		e.preventDefault();
		var task = $(this).attr('id');
		open_form(task);	
	});
	
	/* commented BY Hitesh 
	$(document).on('change','#DashboardShareHoldersDetail',function(e){
		e.preventDefault();
		var id = $(this).val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url();?>client/get_shareholder_detail",
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','id':id},
			cache: false,
			beforeSend:function(){
				initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
				showSpinner();
			},
			error:function(msg){
				hideSpinner();
			},
			success:function(msg){
				hideSpinner();
				msg = JSON.parse(msg);
				
				if(msg['error'] == 'error')
				{
					location.reload(true);
				}else{
					$('.shareholders').html(msg['html']);
				}
			}
		});	
	});
	*/
	
	/* Added By hitesh : Dated 22 May 2015 */
	$(document).on('change','#DashboardShareHoldersDetail',function(e){
		e.preventDefault();
		var id = $(this).val();
		var year = $("#SHyear").val();
		getShareHoldersDetails(id,year);
	});
	$(document).on('change','#SHyear',function(e){
		e.preventDefault();
		var id = $("#DashboardShareHoldersDetail").val();
		var year = $(this).val();
		getShareHoldersDetails(id,year);
	});
	
	function getShareHoldersDetails(id,year){
		$.ajax({
			type: "POST",
			url: "<?php echo site_url();?>client/get_shareholder_detail",
			data: {
					'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
					'id':id,
					'year':year
				 },
			cache: false,
			beforeSend:function(){
				initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
				showSpinner();
			},
			error:function(msg){
				hideSpinner();
			},
			success:function(msg){
				hideSpinner();
				msg = JSON.parse(msg);
				
				if(msg['error'] == 'error')
				{
					location.reload(true);
				}else{
					$('.shareholders').html(msg['html']);
				}
			}
		});	
		
	}
	/* Added By hitesh : Dated 22 May 2015 */
	
	$(document).on('change','.tax_implication',function(e){
		e.preventDefault();
		var id = $('#DashboardShareHoldersDetail').val();
		var div_avail = $('#div_avail').val();
		var amount = $(this).val();
		var year = $('#SHyear').val();
		$.ajax({
			type: "POST",
			url: "<?php echo site_url();?>client/get_tax_implications",
			data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','id':id,'amount':amount,'div_avail':div_avail,'year':year},
			cache: false,
			beforeSend:function(){
				initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
				showSpinner();
			},
			error:function(msg){
				hideSpinner();
			},
			success:function(msg){
				hideSpinner();
				msg = JSON.parse(msg);
				var html = '';
				if(msg['error'] == 'error')
				{
					location.reload(true);
				}else{
					$('.implications').html(msg['amount']);
					$('.implications').css('color',msg['color']);
					if(msg['implication'] == 'G')
					{
						text = '<?php echo $this->lang->line('DASHBOARD_IMPLICATION_POPUP');?>';
						dialogBox('Message',text);
					}
				}
			}
		});	
	});
	////New Dividend ////
	$(document).on('change','.tax_implication_new',function(){
		//e.preventDefault();
		var extra_dividend=parseInt($(this).val());
		var gross_dividend = $('#gross_dividend_2').val();
		var gross_salary = $('#gross_salary_2').val();
		var gross_income = $('#gross_income_2').val();
		var annual_tax = $('#annual_tax_2').val();
		var totaltax = $('#totaltax_2').val();
		var net_dividend = parseInt($('#div_avail').val());
		var limit_d=parseInt("100000");
		if((extra_dividend < net_dividend )&& (extra_dividend < limit_d)){
			$.ajax({
				type: "POST",
				url: "<?php echo site_url();?>client/get_tax_implications_new",
				data: {'gross_dividend':gross_dividend,'gross_salary':gross_salary,'extra_dividend':extra_dividend,'gross_income':gross_income,'annual_tax':annual_tax,'totaltax':totaltax},
				cache: false,
				success:function(msg){
					$('.tax_implication_res').html(msg);	
				}
			});	
		}else{
			text = '<?php echo $this->lang->line('DASHBOARD_IMPLICATION_POPUP');?>';
			dialogBox('Message',text);
		}
	});
	
	$('.carousel').carousel({
	  interval: false
	});
	
	$(document).on('change','#AccountingYear',function(e){
		$('#accounting_year_data').trigger('submit');
	});
});
	function open_form(task)
	{
		
		$.ajax({
			type: "POST",
			url: "<?php echo site_url();?>client/getModalView",
			data: {view:'invoice','<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','task':task},
			cache: false,
			beforeSend:function(){
				if(task == 'expense')
				{
					$('.modal-title').html('Add Expense');
				}else if(task == 'uploadexpense'){
					$('.modal-title').html('Upload Expense');
				}else if(task == 'newDividend'){
					$('.modal-title').html('Add new Dividend Voucher');
				}else if(task == 'banks'){
					$('.modal-title').html('Upload bank Statement');
				}else{
					$('.modal-title').html('Add Invoice');
				}
				initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
				showSpinner();
			},
			error:function(msg){
				hideSpinner();
			},
			success:function(msg){
				hideSpinner();
				msg = JSON.parse(msg);
				var html = '';
				if(msg['file'] != '')
				{
					html = msg['file'];
				}
				html += msg['html'];
				$('.modal-body').html('');
				$('.modal-body').append(html);
				$('.modal-body').append(msg['script']);
				$('.modal-dashboard').modal('show');
			}
		});	
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
				}
			}
		});	
	}
</script>