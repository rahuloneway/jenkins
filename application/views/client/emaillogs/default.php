<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
error_reporting(0);
$ip = $_SERVER['REMOTE_ADDR'];
$json = file_get_contents("http://api.easyjquery.com/ips/?ip=" . $ip . "&full=true");
$json = json_decode($json, true);
$timezone = $json['LocalTimeZone'];

$this->load->view('client/header', array('page' => $page, 'title' => $title));
$user = $this->session->userdata('user');
$sno = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
if (isset($user['UserID'])) {
    $access = 1;
} else {
    $access = 0;
}
?>
<section class="grey-body expenses_middle">
    <div class="container-fluid ">
        <div class="account_sum">
            <h4><?php echo $this->lang->line('EMAIL_LOGS_PAGE_TITLE'); ?></h4>
            <div class="clr"></div>
            <?php echo $this->session->flashdata('bulkEmaillogsError'); ?>
            <?php if (!empty($access)): ?>
                <?php echo form_open_multipart(site_url() . 'clients/emaillogs', array('id' => 'termsearch', 'name' => 'termsearch')); ?>
                <div class="panel panel-default panel_custom">
                    <div class="panel-body row">
                        <div class="top_btm_spc">
                            <div class="col-md-3 col-sm-3 col-xs-12 small_sel padding_field">
                                <div class="wid-40">
                                    <label><?php echo $this->lang->line('BULK_EMAIL_LOGS_START_DATE'); ?>:</label>
                                </div>
                                <div class="wid-40">
                                    <input type="text" value="<?php if(!empty($startdate)){echo $startdate;}else{echo '';}?>" style="float:left;" class="form-control sDatepicker input_100percent" name="StartDate" placeholder="Start Date" id="email-log-date-from">
                                </div>
                            </div>
                        </div>
                        <div class="top_btm_spc">
                            <div class="col-md-3 col-sm-3 col-xs-12  padding_field">
                                <div class="wid-40">
                                    <label><?php echo $this->lang->line('BULK_EMAIL_LOGS_END_DATE'); ?></label>
                                </div>
                                <div class="wid-40">
                                    <input type="text" value="<?php if(!empty($enddate)){echo $enddate;}else{echo '';}?>" style="float:left;" class="form-control sDatepicker input_100percent"  placeholder="End Date" name="EndDate" id="email-log-date-to">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12 text-right padding_field btn_float"> 
                            <button class="btn  btn_search btn-search" type="submit">
                                <span class="glyphicon glyphicon-search"></span>Search
                            </button> 
                            <a class="btn  btn_search reset"  href="">
                                <span class="glyphicon glyphicon-refresh"></span>Reset
                            </a>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>     
            <?php endif; ?>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-3 pull-left">
                    </div>
                    <div class="col-md-8 pull-right bPagination">
                        <?php
                        echo $pagination . '<br/>';
                        ?>
                    </div>
                </div>
            </div>
            <div class="listing-container"> 
                <div id="reset-position" class="table-responsive">  
                    <table id="example-advanced">
                        <thead>
                            <tr class="table-header">
                                <th width="200">
                                    <?php echo $this->lang->line('BULK_EMAIL_LOGS_ID'); ?>
                                </th>
                                <th width="200">
                                    <?php echo $this->lang->line('BULK_EMAIL_LOGS_CLIENT_NAME'); ?>
                                </th>
                                <th width="300">
                                    <?php echo $this->lang->line('BULK_EMAIL_LOGS_COMPNAY_NAME'); ?>  
                                </th>
                                <th width="300">
                                    <?php echo $this->lang->line('BULK_TO'); ?>  
                                </th>
                                <th width="200">
                                    <?php echo $this->lang->line('BULK_EMAI_SUBJECT'); ?>  
                                </th>
                                <th  width="250">
                                    <?php echo $this->lang->line('BULK_EMAIL_DATE'); ?>  
                                </th>


                            </tr>
                        </thead>
                        <tbody class="client-listing">
                            <?php
                            if (count($emailLogs) <= 0) {
                                echo '<tr>';
                                echo '<td colspan="11">';
                                echo '<div class="alert alert-info text-center">';
                                echo $this->lang->line('TERM_CONDITIONS_RECORD_FOUND');
                                echo '</div>';
                                echo '</td>';
                                echo '</tr>';
                            } else {
                                $link = '';
                                foreach ($emailLogs as $key => $value) {
                                    $link = $this->encrypt->encode($value->Id);
                                    echo'<tr>';
                                    echo '<td>';
                                    echo $sno;
                                    echo '</td>';
                                    echo '<td>';
                                    echo $value->FirstName . " " . $value->LastName;
                                    echo '</td>';
                                    echo '<td>';
                                    echo $value->Name;
                                    echo '</td>';
                                    echo '<td>';
                                    echo $value->Email;
                                    echo '</td>';
                                    echo '<td>';
                                    echo "<a href='#' id ='" . $link . "' class='showemailLogDetails' style=\"color:#2685e1;\">" . $value->Subject . "</a>";
                                    ;
                                    echo '</td>';
                                    echo '<td>';
                                    if (!empty($value->AddedOn) && $value->AddedOn != '0000-00-00') {
                                        echo date('d-m-Y h:i:s', strtotime($value->AddedOn));
                                    }
                                    echo '</td>';

                                    echo '</tr>';
                                    $sno++;
                                }
                            }
                            ?>
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>   
            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-3 pull-left">

                    </div>
                    <div class="col-md-8 pull-right bPagination">
                        <?php
                        echo $pagination . '<br/>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade modal-view-email-logs" id="modal-view-term-template-from" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 90%; height: auto; max-height: 100%;">
        <?php echo form_open_multipart(site_url() . 'accountant/Terms/sendMail', array('id' => 'sendemail', 'name' => 'sendemail')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <div class="pull-right">
                    <a href="#" class="btn btn-danger " data-dismiss="modal">
                        <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;Close</a>
                </div>
                <div class="pull-right">

                </div>
            </div>
        </div>
        <?php echo form_close(); ?>

    </div>

</div>
<?php $this->load->view('client/footer'); ?>

