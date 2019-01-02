<?php
if (count($termandcondition) <= 0) {
    echo '<tr>';
    echo '<td colspan="13">';
    echo '<div class="alert alert-info text-center">';
    echo $this->lang->line('ACCOUNTANT_NO_TERM_CONDITIONS');
    echo '</div>';
    echo '</td>';
    echo '</tr>';
} else {
    foreach ($termandcondition as $key => $val) {
        $str = base64_decode($val['TermAndConditions']);
        
        echo '<tr>';
        //echo '<td>' . ($sn + 1) . '</td>';
        echo '<td>' . $val['Version'] . '</td>';
        echo '<td>' . $str . '</td>';
        echo '<td>' . date('d-m-Y', strtotime($val['AddedOn'])) . '</td>';
       /* echo '<td>';
        $tooltipen = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_ENABLED_VERSION') . '"';
        if ($val['Status'] == 1) {
            echo '<a href="#" id="' . $val['Id'] . '" ' . $tooltipen . ' class="term-enabled"><span class="label label-success pointer">ENABLED</span></a>';
        } else {
            echo '<a href="#" id="' . $val['Id'] . '" ' . $tooltipen . ' class="term-enabled"><span class="label label-danger pointer">DISABLED</span></a>';
        }
        echo '</td>';
        echo '<td>';
        $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_EDIT_VERSION') . '"';
        echo '<a href="#" id="' . $val['Id'] . '" class="btn btn-primary btn-xs color editTerm" ' . $tooltip . '>';
        echo '<i class="glyphicon glyphicon-pencil"></i>';
        echo '<a/>';
        echo '</td>';*/
        echo '</tr>';
        $sn++;
    }
}
?>
<div class="modal fade term-condition-form-edit" id="term-condition-form-edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php
            echo form_open('', array('name' => 'term-conditions-form', 'id' => 'term-conditions-form'));
            ?>
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('ACCOUNTANT_TERM_CONDITION'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="tern-id" name="term-id"/>
                    <textarea class="form-control description" name="tc" id="tc"></textarea>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <a data-dismiss="modal" class="btn btn-danger btn-sm spacer" href="#">
                    <i class="glyphicon glyphicon-remove-sign"></i><?php echo $this->lang->line('BUTTON_CANCEL'); ?>
                </a>
                <a id="create-term" class="btn btn-success btn-sm spacer btn-term-update" href="#">
                    <i class="fa fa-file-text"></i><?php echo $this->lang->line('BUTTON_UPDATE'); ?></a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.editTerm').on('click',function(){
            var id = $(this).attr('id');
            if(id==''){
                return false;
            }else{
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>accountant/Configuration/editTermAndConditions", 
                    data:{
                        'id':id
                    },
                    beforeSend:function(){
                        initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                        showSpinner();
                    },
                    error:function(msg){
                        hideSpinner();
                    },
                    success:function(msg){
                        hideSpinner();
                        var split =msg.split('==='); 
                        $("#tern-id").val(split[0]);
                        $('#tc').html(split[1]);
                        $(tinymce.get('tc').getBody()).html(split[1]);
                        $('.term-condition-form-edit').modal('show');
                       
              
                    }
                });
                return false;
            }
        });                                                                                                                                                       
    });
    
    $('#create-term').on('click',function(e){
        e.preventDefault();
        var tc = tinyMCE.get('tc').getContent();
        var id = $('#tern-id').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>accountant/Configuration/updateTermAndConditions", 
            data:{
                'term':tc,
                'id':id
            },
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                hideSpinner();
            },
            success:function(msg){
                hideSpinner();
                window.location.reload();
              
            }
        });
        return false;
    });
    $('.term-enabled').on('click',function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>accountant/Configuration/enabledTermAndConditions", 
            data:{
                'id':id
            },
            beforeSend:function(){
                initSpinnerFunction("<?php echo base_url(); ?>assets/loading.gif");
                showSpinner();
            },
            error:function(msg){
                hideSpinner();
            },
            success:function(msg){
                hideSpinner();
                //console.log(msg);
                window.location.reload();
              
            }
        });
        return false;
    });
    
</script>   
