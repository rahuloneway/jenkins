<?php
$acc_id = clientAccess();
$delete_access = accountant_role($acc_id);
if ($acc_id != 0) {
    $access = 1;
} else {
    $access = 0;
}

$user = $this->session->userdata('user');
$j_date = get_filed_year();

$sn = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
$flateRate = 0;
//pr($items);
if (count($items) <= 0) {
    echo '<tr>';
    echo '<td colspan="13">';
    echo '<div class="alert alert-info text-center">';
    echo $this->lang->line('CLIENT_NO_RECORD_FOUND');
    echo '</div>';
    echo '</td>';
    echo '</tr>';
} else {
    foreach ($items as $key => $val) {
        echo '<tr>';
        echo '<td>';
        echo date("M", mktime(0, 0, 0, $val->Month, 10)); /*echo '&#39 ' */ echo ', '.$val->Year;
        echo '</td>';
        echo '<td>';
        echo  $val->ExpenseNumber ;
        echo '</td>';
        echo '<td>';
        echo $val->Title;
        echo '</td>';
        echo '<td>';
        echo $val->Description;
        echo '</td>';
        echo '<td>';
        echo $val->Miles;
        echo '</td>';
		echo '<td>';
        echo numberFormat($val->Amount);
        echo '</td>';
         echo '</tr>';
        $sn++;
    }
}
/**
 *	Status : 1 - DRAFT
 *	Status : 2 - CREATED
 *	Status : 3 - PAID
 */