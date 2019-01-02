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

// prd( $TBYears );
$TBYear = $TBYears[0]["value"];
$TBPrevYear = $TBYears[1]["value"];
$financial_date = company_year($TBYear);
$financial_date = $financial_date['end_date'];

?>
<section class="grey-body">
    <div class="container-fluid ">
        <?php echo $this->session->flashdata('someerror'); ?>
        <div class="account_sum border_box_trial">
            <div class="panel panel-default panel_custom panel-inv">
                <div class="panel-body row" >
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class="col-md-5"> 
                            <div class="col-md-8">
                                <label>Company Accounting Year: Year Ending <span class="financial_date"><?php echo date('jS M',strtotime($financial_date));?></span></label>
                          
                            </div>
                            <div class="wid-20">
                                <?php
                                $TBDDYears = TBDropDownYears();
                                for ($i = $TBDDYears["end"]; $i >= $TBDDYears["start"]; $i--) {
                                    $arrYear = TBListYearsDD($i);
                                    $arrYears[$arrYear["value"]] = $arrYear["title"];
                                    unset($arrYear);
                                }
                                echo genericList("TBYear", $arrYears, $TBYear, "TBYear");
                                ?>
                            </div>
                        </div>
                        <div class="col-md-3" style="font-size:16px;">
                            <label><b><?php echo $this->lang->line("TB_PL_ACCOUNT_BALANCE"); ?></b></label>
                        </div>
                        <!--<div class="col-md-3"> 
                            <div class="col-md-6"><label>Compare with:</label></div>
                            <div class="wid-50">
                                <select name="compareyear" id="compareyear" class="form-control">
                                    <option value="">--Select--</option>
                                    <option value="Previous_Year">Previous Year</option>
                                   </select>
                            </div>
                        </div>
                        <div class="col-md-4"> 
                            <div class="col-md-5"><label>Compare Periods:</label></div>
                            <div class="wid-50">
                                <select name="compareperiods" id="compareperiods" class="form-control">
                                    <option value="">--Select--</option>
                                    <option value="1">Periods One</option>
                                    <option value="2">Periods Two</option>
                                    <option value="3">Periods Three</option>
                                    <option value="4">Periods Four</option>
                                    <option value="5">Periods Five</option>
                                </select>
                            </div>
                        </div>-->
                        <div class="col-md-2"> 
                        </div>
                        <div class="col-md-1 col-sm-3 col-xs-12 text-right padding_field btn_float"> 
                           <!--1 <button class="btn  btn_search btn-tril-balance-search" type="button">
                                <i class="glyphicon glyphicon-search"></i>Search  
                            </button> 
                            <a href="" class="btn  btn_search reset">
                                <span class="glyphicon glyphicon-refresh"></span>Reset
                            </a>-->
                           <a class="btn btn-primary" href="#" type="" data-toggle="dropdown">&nbsp;&nbsp; <span class="glyphicon glyphicon-download-alt"></span>&nbsp; Download &nbsp;&nbsp; 
                              
                           </a>
                           <ul class="dropdown-menu" style="min-width:138px !important;margin-left:14px !important;border-radius:none !important;border:none; ">
                                <li>
                                    <a id="uploadDocument" class="" href="<?php echo site_url() . 'get_trialbalance_to_balance_sheet_xlx'; ?>">
                                        <span class="glyphicon glyphicon-download-alt"></span>Excel 
                                    </a>
                                </li>
                                <li>
                                    <a id="uploadDocument" class="get_trialbalance_to_file_profit_pdf" href="<?php echo site_url() . 'get_trialbalance_to_balance_sheet_pdf'; ?>">
                                        <span class="glyphicon glyphicon-download-alt"></span>PDF
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive" id="TBListing" >
                <?php $this->load->view('client/balance_sheet/listing'); ?> 
            </div>
        </div>
    </div>
</section>
<div id="dialog"></div>	
<?php $this->load->view('client/footer'); ?>