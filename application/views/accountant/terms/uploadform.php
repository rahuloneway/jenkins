<?php
echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
?>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<section>
    <div class="row data_opn">
        <div class="alert alert-danger fade in" style="display:none;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            </div>
        <center>
            <div class="col-md-1 col-sm-1 col-xs-12 padding_field "></div>
            <div class="col-md-3 col-sm-3 col-xs-12 padding_field ">
                <select class="form-control client-dropdown required" id="company" name="company">
                    <option selected="selected" value="">Select Company</option>
                    <?php
                    if (!empty($company)) {
                        foreach ($company as $key => $value) {
                            if (!empty($value->Name)) {
                                echo '<option value="' . $value->CID . '">' . $value->Name . '</option>';
                            }
                        }
                    }
                    ?>
                </select>

            </div>
            <div class="col-md-3 col-sm-3 col-xs-12 padding_field ">
                <select class="form-control company-dropdown required" id="client" name="client">
                    <option selected="selected" value="">Select Client</option>
                </select>

            </div>
            <div class="col-md-4 ">
                <div class="browse-file">
                    <input type="file" name="file" id="file" class="filestyle" data-buttonName="btn-primary" accept="application/pdf,application/vnd.ms-excel" value=""/>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000000" />
                </div>
            </div>
        </center>
    </div>      

</section>
