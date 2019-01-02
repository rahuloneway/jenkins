<script>tinymce.init({selector:'textarea'});</script>
<script>
$(document).ready(function(){
	
	$(document).on('mouseover','.sort,.delete-note',function(e){
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
		
		
	$(document).off('click').on('click','.save-note',function(e){
		e.preventDefault();
		tinymce.triggerSave();
		if($('.description').val() == '')
		{
			var title = 'Message';
			var text = '<?php echo $this->lang->line('NOTES_DESCRIPTION_EMPTY_ERROR');?>';
			dialogBox(title,text,'.description');
		}else{
			$('#notes').trigger('submit');
		}
	});
	
	
	$(document).off('submit','#notes').on('submit','#notes',function(e){
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: $('#notes').attr('action'),
			data: $(this).serialize(),
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
					$('#notes-listing').html(msg['html']);
					var text = '<?php echo $this->lang->line('NOTES_SAVE_SUCCESSFUL');?>';
					dialogBox('Message',text);
				}
			}
		});
		return false;
	});
	
	$(document).on('click','.delete-note',function(e){
		e.preventDefault();
		var href = $(this).attr('href');
		var title = 'Message';
		var url = "<?php echo site_url().'clients/notes/delete';?>";
		var text = '<?php echo $this->lang->line('NOTE_DELETE_CONFIRMATION');?>';
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
					performAction(href,url);
				},
				No: function() {
					$(this).dialog('close');
				}
			}
		});	
	});
});
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
			}
		}
	});	
}

function performAction(href,url,pDate)
{
	$.ajax({
		type: "POST",
		url: url,
		data: {'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',"task":href},
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
			if(msg['error'] != 'error')
			{
				$('#notes-listing').html(msg['html']);
				var text = '<?php echo $this->lang->line('NOTES_DELETE_SUCCESSFUL');?>';
				dialogBox('Message',text);
			}else{
				location.reload(true);
			}
		}
	});
}
</script>