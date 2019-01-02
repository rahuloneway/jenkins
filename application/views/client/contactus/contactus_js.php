<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<script>
	$(document).ready(function(){
		$(document).on('click','.addMessage',function(e){
			e.preventDefault();
			var error = '<?php echo $this->lang->line('ERROR_FIELD_REQUIRED');?>';
			if(!validateField('.required',error))
			{
				return false;
			}else{
				$('#contactUs').trigger('submit');
			}
		});
	});
	
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
</script>