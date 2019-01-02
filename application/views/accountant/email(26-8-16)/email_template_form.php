<section>
    <div class="row data_opn">
        <?php
        echo form_open(site_url() . 'accountant/Email/save', array('name' => 'fromEmailtemplate', 'id' => 'fromEmailtemplate'));
        ?>
        <div class="col-md-12">
            <div class="col-md-9">  
                <div class="col-md-12">
                    <label><?php echo $this->lang->line('TEMPLATE_NAME'); ?>  :</label>
                    <input type="text"  class="form-control" value="" name="templateName" id="templateName"/>
                </div>  
                <div class="clearfix"></div><br/>
                <div class="col-md-12">
                    <label><?php echo $this->lang->line('TEMPLATE_TEXT'); ?>:</label>
                    <center>
                        <div class="clearfix"></div>
                        <textarea class="form-control description" name="templateText" id="templateText">
                        </textarea>
                    </center>

                </div>
            </div>
            <div class="col-md-3">
                <div class="article_series-box">
                    <div class="panel panel-default panel_custom">
                        <center>    
                            <h4>
                                <a href="#" class="series-3793" title="Template hint">
                                    Hint
                                </a>
                            </h4>

                        </center>   
                    </div>
                    <ul style="float:left;list-style:none;">
                        <li>First Name <br/><input type="text" value="FIRSTNAME" readonly="readonly" class="form-control"/></li>
                        <li>Last Name <br/><input type="text" value="LASTNAME" readonly="readonly" class="form-control"/></li>
                        <li>Company Name<br/><input type="text" value="COMPANY" readonly="readonly" class="form-control"/></li>
                        <li>Email<br/><input type="text" value="EMAIL" readonly="readonly" class="form-control"/></li>
                        <li>Signature<br/><input type="text" value="SIGNATURE" readonly="readonly" class="form-control"/></li>

                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
            <br/> <br/>
            <div class="modal-footer">
                <div class="pull-right">
                   
                    <a data-dismiss="modal" class="btn btn-danger" href="#">
                        <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;Cancel</a>
                </div>
                <div class="pull-right">
                     <button type="submit" class="btn btn-primary btn-sm spacer" id="uploadTerm">
                        <i class="glyphicon glyphicon-floppy-disk"></i> Save </button>>
                </div>
            </div>
        </div>
        <?php
        echo form_close();
        ?>

</section>
<?php echo '<script type="text/javascript" src="' . site_url() . 'assets/js/tinymce/tinymce.min.js"></script>' ?>
<script>
    tinymce.init({selector: 'textarea'});
</script>