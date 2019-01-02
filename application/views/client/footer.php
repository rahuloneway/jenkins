<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$user = $this->session->userdata('user');
?>
<script type="text/javascript">
$(document).ready(function(){
	$('.salcls').click(function(){
		var test = '<?php 
		$val = $this->session->set_userdata('AddPayese','');		
		?>';		
	})
});
</script>
<footer class="copyright">
    <div class="container-fluid">
        <p>&copy; 2014 CASHMANN All Rights Reserved.</p>
        <ul class=" pull-right list">
            <li><a href="#">Privacy Policy </a></li>
            <li><a href="#"  class="terms-condition-view"  onclick="view_term()">Terms and Conditions</a></li>
        </ul>
    </div>
</footer>
</body>
</html>

<?php
if (!empty($user)) {
    $checkTC = checkTermAndConditionVersion($user['UserID'], $user['AddedBy']);
	if( !empty($checkTC))
	{
		$TC = $checkTC[0]->FName;
		$TCID = $checkTC[0]->Id;
		$TC_Version = '';
		if ($user['UserType'] == 'TYPE_CLI' && $user['AcType'] == 'TYPE_CLI') {
			if ($checkTC[0]->Version > $user['T_AND_C_Version']) {
				?>
				<script>
					$(document).ready(function() { 
						$('.modal-view-term').css({width: '100%', height: 'auto', 'max-height': '100%'});
						$('.modal-title').html('Terms and conditons');
						$('.modal-view-term').modal('show');

					});
				</script>
				<div class="modal fade modal-view-term" id="modal-view-term" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
					<div class="modal-dialog modal-lg" style="width: 90%; height: auto; max-height: 100%;">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close term-close" style="display:none;" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
								<h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('TERMS_CONDITIONS'); ?></h4>
							</div>
							<div class="modal-body">
								<?php
								if (!empty($TC)) {
									$fileurl = base_url() . "assets/uploads/terms/" . $TC;
									echo '<iframe width="100%" scrolling="no" height="400" frameborder="0" style="width:100%; height:400px;" src="' . $fileurl . '" /></iframe>';
								}
								?>
							</div>
							<div class="modal-footer">
								<?php
								echo form_open('', array('name' => 'term-conditions', 'id' => 'term-conditions'));
								?>
								<input type="hidden" id="tc_version" name="tc_version" value="<?php echo $checkTC[0]->Version; ?>"/>
								<button type="button" class="btn btn-success btn-term-condition">Accept</button>
								<?php echo form_close(); ?>
							</div>
						</div>
					</div>
				</div>
				<script>
					$(".btn-term-condition").click(function() {
						$.ajax({
							type: "POST",
							url: "<?php echo base_url(); ?>/termCondition",
							data: {
								'term_version': $("#tc_version").val()
							},
							dataType: "text",
							cache: false,
							beforeSend: function() {
								initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
								showSpinner();
							},
							error: function(msg) {
								hideSpinner();
							},
							success:
								function(data) {
								hideSpinner();
								$(".term-condition-success").css('display', 'block');
								window.location.reload();
							}
						});
					});

				</script>
				<?php
			}
		}
	}
}
?>
</body>
</html>
<!--Term and Conditions -->

<div class="modal fade term-condition-view-details" id="term-condition-view-details" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 90%; height: auto; max-height: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <input type="hidden" value="<?php echo $TCID; ?>" id="tcid" class="tcid"/>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('TERMS_CONDITIONS'); ?></h4>
            </div>
            <div class="modal-body-term">

            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger btn-sm spacer" data-dismiss="modal">
                    <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;Close		
                </a>
            </div>
        </div>
    </div>
</div>