<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
	$(document).on('click','.uploadExpenses',function(e){
		e.preventDefault();
		$(this).unbind('click');
		var task = '<?php echo $this->encrypt->encode('uploadExpense');?>';
		var id = '';
		var title = "<?php echo $this->lang->line('EXPENSE_UPLOAD_FORM_TITLE');?>";
		openExpenseForm(task,id,title);
	});
	$(document).on('click','.uploadExpense',function(e){
		e.preventDefault();
		var f = $('#file').val();
		if(f == '')
		{	
			var title = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TITLE');?>';
			var text = '<?php echo $this->lang->line('CLIENT_UPLOAD_EXPENSE_DIALOG_TEXT');?>';
			dialogBox(title,text);
			return false;
		}else{
			$('.modal-expenses').modal('hide');
			uploadTemplate();
		}
	});
	
	function uploadTemplate()
	{

		$('.uploadExpense').unbind('click');
		var task 	= 	"<?php echo $this->encrypt->encode('addExpense');?>";
		var id = '';
		var formUrl = "<?php echo site_url();?>clients/expenses/uploadExpenses/";
        var formData = new FormData($('#uExpense')[0]);
        $.ajax({
			url: formUrl,
			type: 'POST',
			data: formData,
			mimeType: "multipart/form-data",
			contentType: false,
			cache: false,
			processData: false,
			beforeSend:function(){
				initSpinnerFunction("<?php echo base_url();?>assets/loading.gif");
				showSpinner();
			},
			error: function(error){
				hideSpinner();
			},
			success: function(msg){
				hideSpinner(); 
				msg = JSON.parse(msg);
				if(msg['error'] != 'error')
				{
					$('.modal-title').html('Expense Template Items');
					$('.modal-body').html(msg['html']);
					$('.modal-expenses').modal('show');
					checkButton('#expenseListItem','.removeExpenseItem');
					checkButton('#expenseMileageItem','.removeMileageItem');
					calExpenseItemAmount();
					calTotalMiles();
				}else{
					window.location = '<?php echo site_url().'clients/expenses/'?>';
				}
			}
        });
		return false;	}
</script>
