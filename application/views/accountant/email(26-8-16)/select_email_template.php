<?php echo form_open_multipart(site_url() . 'accountant/email/sendMail', array('id' => 'frmbulkemail', 'name' => 'frmbulkemail')); ?>
<section>
    <div class="row data_opn">
        <div class="col-md-12">
            <div class="loader"></div>
            <label><?php echo $this->lang->line('SELECT_TEMPLATE'); ?>  :</label>

            <select class="form-control" id="template_type" name="template_type">
                <option selected="selected" value="0">--Select Template--</option>
                <?php
                foreach ($templatename as $key => $value) {
                    echo '<option value="' . $value['Id'] . '">' . $value['Template_Name'] . '</option>';
                }
                ?>
            </select>
            <div class="clearfix"></div>
            <label><?php echo $this->lang->line('SUBJECT'); ?>  :</label>
            <div class="clearfix"></div>
            <input type="text" value="" class="form-control" name="subject" subject/>

            <div class="clearfix"></div>
            <br/>
            <input type="hidden" id="clientId" name="clientId" value=""/>
            <input type="hidden" id="companyId" name="companyId" value=""/>
            <textarea class="form-control description" name="email_text" id="email_text">
            </textarea>
        </div>
        <div class="clearfix"></div>
        <br/>
        <div class="modal-footer">
            <div class="pull-right">
                <a data-dismiss="modal" class="btn btn-danger" href="#">
                    <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;Cancel</a>
            </div>
            <div class="pull-right">
                <button type="submit" class="btn btn-primary  btn-sm spacer" id="bulk-email">
                    <i class="fa fa-upload"></i> Send </button>
            </div>
        </div>
    </div>
</section>
<?php echo form_close(); ?>
<?php echo '<script type="text/javascript" src="' . site_url() . 'assets/js/tinymce/tinymce.min.js"></script>' ?>
<script>
    tinymce.init({selector: 'textarea'});
</script>