<style>
input[type="checkbox"]{
	margin:4px 0 0;
}
</style>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php echo form_open('edit-access', array('id' => 'clientEditAccess')); ?>
<div id="messgaeDiv"></div>

<div class="col-sm-3">
	<div class="checkbox">
		<label>
		<input id="checkAllPriviliges" name="checkAll" type="checkbox" <?php if( $privilgedMenusIds == 'all' ){?> checked="checked" <?php } ?> value="all" /> Select All
		</label>
	</div>
</div>
<section>
    <div class="panel panel-default panel_custom">
        <div class="panel-body row" style="padding-left:3%;padding-right:3%;">
            <div class="form-horizontal col-md-12  col-sm-12 col-xs-12 padding_field">
				<div class="form-group">
				<?php 
					if( $privilgedMenusIds != 'all' )
						$privilgedMenusIds = explode(',',$privilgedMenusIds);
					if(!empty($allMenus)){
					foreach($allMenus as $Menu){ 
					if( empty($Menu['subMenus'])){
				?>
					  <div class="col-sm-3">
						<div class="checkbox">
						  <label>
							  <input class="privilgeChkBox" name="menus[]" type="checkbox" <?php if( $privilgedMenusIds != 'all' ) { if( in_array( $Menu['id'],$privilgedMenusIds )){?> checked="checked" <?php } } else { ?>checked="checked"  <?php } ?> value="<?php echo $Menu['id'];?>" /> <?php echo ucfirst($Menu['title']);?>
						  </label>
						</div>
					  </div>
					<?php } ?>
					  <?php 
						if(!empty($Menu['subMenus'])){ 
						foreach($Menu['subMenus'] as $subMenu){ ?>
							<div class="col-sm-3">
								<div class="checkbox">
								  <label>
									  <input class="privilgeChkBox" name="menus[]" type="checkbox" <?php if( $privilgedMenusIds != 'all' ) { if( in_array( $subMenu['id'],$privilgedMenusIds )){?> checked="checked" <?php } } else {?> checked="checked"  <?php } ?> value="<?php echo $subMenu['id'];?>" /> <?php echo ucfirst($subMenu['title']);?>
								  </label>
								</div>
							</div>
						<?php } }
					} } ?>					
				</div>
				
			</div>
        </div>
    </div>
</section>
<div class="modal-footer">
    <div class="pull-right col-md-6">
		<a id="edit-access-submit" class="btn btn-success btn-sm spacer" href="javascript:;">
			<i class="fa fa-file-text"></i>&nbsp;<?php echo $this->lang->line('BUTTON_UPDATE'); ?>
		</a>
        <a data-dismiss="modal" class="btn btn-danger btn-sm spacer" href="#">
            <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CANCEL'); ?>
        </a>
    </div>
</div>
<input type="hidden" name="clientId" id="hiddenClientID" value="<?php echo $this->encrypt->encode($clientId); ?>"/>
<input type="hidden" name="task" id="hiddenTaskId" value="update"/>
<?php echo form_close(); ?>