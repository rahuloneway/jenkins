<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$this->load->view('client/header', array('page' => $page, 'title' => $title));
$search = $this->session->userdata('journalSearch');
if ($search == '') {
    $search = array(
        'FinancialYear' => ''       
    );
}
$TBYears = getTBYear();


// prd( $TBYears );
$TBYear = $TBYears[0]["value"];
$TBPrevYear = $TBYears[1]["value"];
$financial_date = company_year($TBYear);
$financial_date = $financial_date['end_date'];
$fin_yearexcel = $this->session->userdata('fin_yearexcel');
//$jSearchYear = $this->session->userdata('jSearchYear');
$search = $this->session->userdata('journalSearch');
//echo $search['FinancialYear'];

	if($search == '')
	{
            $search = array(
            'FinancialYear' => $TBYear
            );
	}
 //echo "<pre>"; print_r($search); echo "</pre>";     
?>
<section class="grey-body">
    <div class="container-fluid">
        <div class="account_sum container">
            <h4><?php echo $this->lang->line('JOURNAL_TITLE_NAME'); ?></h4>
            <?php echo $this->session->flashdata('journalError'); ?>
            <div class="panel panel-default panel_custom ">
                <div class="panel-body row">
                    <div class="col-md-3 col-sm-6 col-xs-12 padding_field">
                        <label>
                            <?php echo $this->lang->line('JOURNAL_LABEL_ACCOUNTING_YEAR'); ?>
                            <span class="financial_date"><?php echo date('jS M', strtotime($financial_date)); ?></span>
                        </label>
                    </div>
                    <div class="col-md-2">
                        <?php
                        echo form_open(site_url() . 'journal_search/', array('name' => 'journal-search','id' => 'journal-search'));
                        $TBDDYears = TBDropDownYears();
                        for ($i = $TBDDYears["end"]; $i >= $TBDDYears["start"]; $i--) {
                            $arrYear = TBListYearsDD($i);
                            $arrYears[$arrYear["value"]] = $arrYear["title"];
                            unset($arrYear);
                        }
                        if (!empty($fin_yearexcel)) {  
                            //echo genericList("financialyear", $arrYears, $fin_yearexcel, "financialyear");                                                   
                        } 
                        else if( !empty($search)){
                            //echo genericList("financialyear", $arrYears,  $search['FinancialYear'], "financialyear");
                        }
                        else { 
                            //echo genericList("financialyear", $arrYears, $TBYear, "financialyear");    
                        }
						if(date('d',strtotime($financial_date)) == 31 && date('m',strtotime($financial_date)) == 12){
							$keys = array_keys($arrYears);
							$key = $keys[1];
							$selected = $key;
						}						
						echo genericList("TBYear", $arrYears, $selected, "financialyear");	
                        echo form_close();
                        ?>
                    </div>
                   
                    <a href="#" type="button" class="btn btn_search journalreset">
                            <span class="glyphicon glyphicon-refresh"></span> <?php echo $this->lang->line('BUTTON_RESET');?> 
                    </a>
						
                </div> 
            </div>
            <div class="clr"> </div>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-2  col-sm-3 col-xs-12 btn_centre_bot"> 
                        <a class="btn btn-inverse add-journal" type="button" href="#">
                            <span class="glyphicon glyphicon-plus"></span>
                            <?php echo $this->lang->line('JOURNAL_LABEL_ADD'); ?>
                        </a> 
                    </div>					
                    <div class="col-md-8 col-sm-9 col-xs-12 tdd">                       
                        <div class="pagins">
                        <?php 
                        if($TBYear != $search['FinancialYear'] && $search['totalCount']>=JOURNAL_LISTING_PAGINATION_LIMIT){
                            echo $pagination . '<br/>';
                        }
                        ?>
                        </div>
                       <?php //if($TBYear == $search['FinancialYear'] ){
                        ?>
                        <div class="gges" style="<?php if($TBYear == $search['FinancialYear'] ){ echo "display:block"; }else{echo "display:none;";}?>">                       
                            <?php
                             echo $pagination . '<br/>';
                            ?>
                        </div>
                        <?php //} ?>
                    </div>
                </div>
            </div>
            <div class="tabl_journal">
                <div class="clr"></div>
                <table class="tbl-editable">
                    <thead class="tbody_bg">
                        <tr>
                            <th><?php echo $this->lang->line('JOURNAL_COLUMN_ITEM'); ?></th>
                            <th><?php echo $this->lang->line('JOURNAL_COLUMN_TYPE'); ?></th>
                            <th><?php echo $this->lang->line('JOURNAL_COLUMN_CATEGORY'); ?></th>
                            <th><?php echo $this->lang->line('JOURNAL_COLUMN_NARRATION'); ?></th>
                            <th><?php echo $this->lang->line('JOURNAL_COLUMN_GROUP'); ?></th>
                            <th class="text-right"><?php echo $this->lang->line('JOURNAL_COLUMN_AMOUNT'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="journal-listing">
                        <?php $this->load->view('client/journals/journal_listing'); ?>
                    </tbody>
                </table>
                <div class="clr"></div>
            </div>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row ">
                    <div class="col-md-2  col-sm-3 col-xs-12 btn_centre_bot"> 
                        <a class="btn btn-inverse add-journal" type="button" href="#">
                            <span class="glyphicon glyphicon-plus"> </span><?php echo $this->lang->line('JOURNAL_LABEL_ADD'); ?>
                        </a> 
                    </div>
                    <div class="col-md-8 col-sm-9 col-xs-12 tdd">                       
                        <div class="pagins">
                        <?php 
                        if($TBYear != $search['FinancialYear'] && $search['totalCount']>=JOURNAL_LISTING_PAGINATION_LIMIT){
                            echo $pagination . '<br/>';
                        }
                        ?>
                        </div>
                       <?php //if($TBYear == $search['FinancialYear'] ){
                        ?>
                        <div class="gges" style="<?php if($TBYear == $search['FinancialYear'] ){ echo "display:block"; }else{echo "display:none;";}?>">                       
                            <?php
                             echo $pagination . '<br/>';
                            ?>
                        </div>
                        <?php //} ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade modal-journal" id="modal-journal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('JOURNAL_NEW_POPUP_TITLE'); ?></h4>
            </div>
            <div class="modal-body"></div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div id="dialog"></div>	
<style>
    .col-md-8.col-sm-9.col-xs-12.tdd {
    float: right;
}
</style>
<?php $this->load->view('client/footer'); ?>