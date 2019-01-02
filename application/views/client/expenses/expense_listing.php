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
        echo $sn;
        echo '</td>';
        echo '<td>';
        if ($val->Status == 1) {
            if ($val->ExpenseType == 'CREDITCARD') {
                $class = "editExpense creditcard";
            } else {
                $class = "editExpense";
            }
        } else {
            if ($val->ExpenseType == 'CREDITCARD') {
                $class = "viewExpense creditcard";
            } else {
                $class = "viewExpense";
            }
        }
        $href = $this->encrypt->encode($val->ID);
        echo '<a href="' . $href . '" class="' . $class . '">' . $val->ExpenseNumber . '</a>';
        echo '</td>';
        echo '<td>';
        echo $val->EmployeeName;
        echo '</td>';
        echo '<td>';
        echo date("M", mktime(0, 0, 0, $val->Month, 1, 0)) . ' \'' . substr($val->Year, -2);
        ;
        echo '</td>';
        echo '<td>';
        echo $val->TotalMiles;
        echo '</td>';
        echo '<td class="text-right">';
        echo numberFormat($val->TotalAmount);
        echo '</td>';
        if ($user['VAT_TYPE'] == 'stand') {
            echo '<td class="text-right">';
            echo numberFormat($val->TotalVATAmount);
            echo '</td>';
        }
        echo '<td>';
        if ($val->FileID == 0) {
            echo '<span class="label label-danger">Added manually</span>';
        } else {
            echo getFileName($val->FileID);
        }
        echo '</td>';
        echo '<td>';
        if ($val->Status == 1) {
            echo '<span class="btn btn-primary btn-xs">DRAFT</span>';
        }elseif ($val->Status == 2 && cDate($val->PaidOn) == '') {
            $href = $this->encrypt->encode('ACTION_PAID/' . $val->ID);
            if ($access) {
                $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_EX_PAID') . '"';
                echo '<a href= "' . $href . '"class="btn btn-info btn-xs color markPaid" ' . $tooltip . '>';
                echo 'CREATED';
                echo '</a>';
            } else {
                echo '<span class="btn btn-info btn-xs color">';
                echo 'CREATED';
                echo '</span>';
            }
        }elseif (cDate($val->PaidOn) != '') {
            echo '<span class="btn btn-xs btn-success pointer">PAID</span>';
        }
        echo '</td>';
        echo '<td>';
        echo cDate($val->PaidOn);
        echo '</td>';
        echo '<td>';
        $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_EX_COPY') . '"';
        $href = $this->encrypt->encode($val->ID);
        echo '<a href="' . $href . '" ' . $tooltip . ' class="copyExpense"><i class="fa fa-files-o"></i></a>&nbsp;';
        if (cDate($val->PaidOn) == '' || $delete_access) {

            if (strtotime($val->PaidOn) > strtotime($j_date) && $delete_access) {
                $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_EX_DELETE') . '"';
                $href = $this->encrypt->encode('ACTION_DELETE/' . $val->ID);
                echo '<a href="' . $href . '" ' . $tooltip . ' class="deleteExpense"><i class="fa fa-times"></i></a>';
            } elseif (cDate($val->PaidOn) == '' && $val->Status == 1 || $val->Status == 2) {
                $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_EX_DELETE') . '"';
                $href = $this->encrypt->encode('ACTION_DELETE/' . $val->ID);
                echo '<a href="' . $href . '" ' . $tooltip . ' class="deleteExpense"><i class="fa fa-times"></i></a>';
            }
        }

        if ($val->ExpenseType == 'CREDITCARD') {
            $class = "class='editExpense creditcard btn btn-primary btn-xs color pointer'";
        } else {
            $class = "class='editExpense btn btn-primary btn-xs color pointer'";
        }
		if($user['AccountantAccess'] != ''){
			$link = $this->encrypt->encode($val->ID);
			echo '&nbsp;<a href="' . $link . '" ' . $class . '>Edit</a>';
		}
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