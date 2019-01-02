<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$this->load->view('client/header', array('page' => $page, 'title' => $title));
$user = $this->session->userdata('user');
if (isset($user['AccountantAccess'])) {
    $access = 1;
} else {
    $access = 0;
}

$TBYears = getTBYear();
$TBYear = $TBYears[0]["value"];
$TBPrevYear = $TBYears[1]["value"];
$financial_date = company_year($TBYear);
$financial_date = $financial_date['end_date'];
?>
<section class="grey-body">
    <div class="container-fluid ">
        <?php // echo $this->session->flashdata('someerror'); ?>
        <div class="account_sum border_box_trial">

            <div class="panel panel-default panel_custom panel-inv">
                <div class="panel-body row">
                    <div class="col-md-8 col-sm-12 col-xs-12  invoice_field">
                        <div class="col-md-5"><label>Company Accounting Year: Year Ending <span class="financial_date"><?php echo date('jS M', strtotime($financial_date)); ?></span></label></div>
                        <div class="wid-30">
                            <?php
                            /*
                              $TBDDYears = TBDropDownYears();
                              for( $i=$TBDDYears["end"];$i >= $TBDDYears["start"]; $i-- ){
                              $tempcheckDate = $i."-".date("m")."-01";
                              $arrYear = TBListYears( $tempcheckDate );
                              $arrYears[$arrYear["value"]] = $arrYear["title"];
                              unset($tempcheckDat);
                              unset($arrYear);
                              }
                              echo genericList("TBYear", $arrYears, $TBYear , "TBYear");
                             */
                            $TBDDYears = TBDropDownYears();
							
                            for ($i = $TBDDYears["end"]; $i >= $TBDDYears["start"]; $i--) {
                                $arrYear = TBListYearsDD($i);
                                $arrYears[$arrYear["value"]] = $arrYear["title"];
                                unset($arrYear);
                            }
							if(date('d',strtotime($financial_date)) == 31 && date('m',strtotime($financial_date)) == 12){
								$keys = array_keys($arrYears);
								$key = $keys[1];
								$selected = $key;
							}	
							//echo "<pre>"; print_r($arrYears); echo "</pre>";
                            echo genericList("TBYear", $arrYears, $TBYear, "TBYear");
                            //echo genericList("TBYear", $arrYears, $selected, "TBYear");
                            ?>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="browse-file col-md-2 pull-right">
                        <a id="uploadDocument" class="btn btn-primary" href="<?php echo site_url() . 'get_trialbalance_to_file'; ?>" target="_blank" >
                            <span class="glyphicon glyphicon-download-alt"></span> Download
                        </a>
                    </div>
                </div>
            </div>
            <div class="table-responsive" id="TBListing" >
                <?php $this->load->view('client/trial_balance/listing'); ?> 
            </div>
        </div>
    </div>
</section>
<div id="dialog"></div>	
<?php $this->load->view('client/footer'); ?>