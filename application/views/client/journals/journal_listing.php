<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$sn = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
$row_one = 0;
$row_two = 0;
$class = "class='datacellone'";
//echo "<pre>"; print_r($items); echo "</pre>";
//echo "<pre>"; print_r($items_amount); echo "</pre>";
if($items_amount){
	foreach($items_amount as $totalamount){
		$debit_amount = $totalamount->debit_amount;
		$credit_amount = $totalamount->credit_amount;
	}
}
if (count($items) <= 0) {
    echo '<tr>';
    echo '<td colspan="6">';
    echo '<div class="alert alert-info text-center">';
    echo $this->lang->line('CLIENT_NO_RECORD_FOUND');
    echo '</div>';
    echo '</td>';
    echo '</tr>';
} else {
    $cr_total = 0;
    $db_total = 0;
    foreach ($items as $key => $val) {
        if ($key % 2 == 0) {
            $class = "class='datacelltwo'";
        }

        if ($val->JournalType == 'CR') {
            $cr_total += $val->Amount;
        } else {
            $db_total += $val->Amount;
        }

        echo '<tr ' . $class . '>';
        echo '<td>';
        echo $sn;
        $sn++;
        echo '</td>';
        echo '<td>';
        echo $val->JournalType;
        echo '</td>';
        echo '<td>';
        echo journal_cat_name($val->Category);
        echo '</td>';
		echo '<td>';
        echo $val->Narration;
        echo '</td>';
        echo '<td>';
        echo $val->GroupID;
        echo '</td>';
        echo '<td class="text-right">';
        echo numberFormat($val->Amount);
        echo '</td>';
        echo '</tr>';
        if ($row_one == 0) {
            $class = "class='datacellone'";
            $row_two = 0;
        }
    }
    echo '<tr class="datacelltwo">';
    echo '<td colspan="5"></td>';
    echo '<td class="text-right">';
    echo '<b>Total Credit Amount:</b> &nbsp;&nbsp;' . numberFormat($credit_amount);
    echo '<br/><b>Total Debit Amount:</b> &nbsp;&nbsp;' . numberFormat($debit_amount);
    echo '</td>';
    echo '</tr>';
}
?>

<!--div class="modal-footer d">
    <div class="col-md-12">
        <a type="button" class="btn btn-danger"data-dismiss="modal">
<?php echo $this->lang->line('BUTTON_CANCEL'); ?>
        </a>
    </div>
</div-->