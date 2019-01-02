<section>
    <div class="row data_opn">
            <div class="col-md-12">
                <label><?php echo $this->lang->line('SELECT_TEMPLATE'); ?>  :</label>
                 <div class="clearfix"></div>
                    <select class="form-control template_type required" id="template_type" name="template_type">
                        <option selected="selected" value="0">--Select Template--</option>
                         <?php 
                           foreach ($templatename as $key => $value) {
                               echo '<option value="'.$value['Id'].'">'.$value['Template_Name'].'</option>';
                           }
                         ?>
                    </select>
                 
                   <input type="hidden" id="clientId" name="clientId" value="<?php echo $clientId;?>"/>
                    <div class="clearfix"></div>
                    <label><?php echo $this->lang->line('TERM_EMAIL'); ?>  :</label>
                    <div class="clearfix"></div>
                    <input type="text" value="<?php echo $email;?>" class="form-control" readonly=""/>
                    <br/>
                    <label><?php echo $this->lang->line('SUBJECT'); ?>  :</label>
                   <div class="clearfix"></div>
                     <input type="text" value=""  id="subject" class="form-control subject required" name="subject" subject />
                      <div class="alert alert-danger subject error-field" style="display:none;width:200px;"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;This field is required!</div>
                    <br/>
                    <textarea class="form-control description" name="email_text" id="email_text">
		    </textarea>
                    <div class="alert alert-danger email_text error-field" style="display:none;width:200px;"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;This field is required!</div>

            </div>

    
        <div class="clearfix"></div>
    </div>
</section>
<?php echo '<script type="text/javascript" src="' . site_url() . 'assets/js/tinymce/tinymce.min.js"></script>'?>
<script>
    tinymce.init({selector: 'textarea'});
    
</script>