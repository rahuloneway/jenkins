<?php

//pr($items);
$access = clientAccess();
$delete_access = accountant_role($access);
$sn = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
$user = $this->session->userdata('user');

$j_date = get_filed_year();

if (count($items) <= 0) {
    echo '<tr>';
    echo '<td colspan="11">';
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
            $class = "editDividend";
        } elseif ($val->Status == 2) {
            $class = "viewDividend";
        }
        echo '<a href="' . $this->encrypt->encode($val->DID) . '" class="' . $class . '">';
        echo $val->VoucherNumber;
        echo '<a/>';
        echo '</td>';
        echo '<td>';
        echo cDate($val->DividendDate);
        echo '</td>';
        echo '<td>';
        echo $val->ShareholderName;
        echo '</td>';
        echo '<td class="text-right">';
        echo '&pound; ' . number_format($val->NetAmount, 2, '.', ',');
        echo '</td>';
        echo '<td class="text-right">';
        if ($val->TotalShares == 0) {
            echo '0';
        } else {
            echo '&pound; ' . number_format($val->NetAmount / $val->TotalShares, 2, '.', ',');
        }

        echo '</td>';
        echo '<td class="text-right">';
        echo '&pound; ' . number_format($val->TaxAmount, 2, '.', ',');
        echo '</td>';
        echo '<td class="text-right">';
        echo '&pound; ' . number_format($val->GrossAmount, 2, '.', ',');
        echo '</td>';
        echo '<td>';
        if ($val->Status == 1) {
            if ($access) {
                $href = $this->encrypt->encode('ACTION_PAID/' . $val->DID);
                $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_DIVIDEND_PAID') . '"';
                echo '<a href= "' . $href . '"class="btn btn-primary btn-xs color markPaid" ' . $tooltip . '>';
                echo 'CREATED';
                echo '</a>';
            } else {
                echo '<span class="btn btn-primary btn-xs color">CREATED</span>';
            }
        } elseif ($val->Status == 2) {
            echo '<span class="label label-success pointer">PAID</span>';
        }
        echo '</td>';
        echo '<td>';
        echo cDate($val->PaidOn);
        echo '</td>';
        echo '<td>';
        $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_DIVIDEND_COPY') . '"';
        $href = $this->encrypt->encode($val->DID);
        echo '<a href="' . $href . '" ' . $tooltip . ' class="copyDividend"><i class="fa fa-files-o"></i></a>&nbsp;';
        if ($val->Status != '2' || $delete_access) {
            $d = mDate($val->PaidOn);
            if (!empty($d)) {
                if (strtotime($val->PaidOn) > strtotime($j_date)) {
                    $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_DIVIDEND_DELETE') . '"';
                    $href = $this->encrypt->encode('ACTION_DELETE/' . $val->DID);
                    echo '<a href="' . $href . '" ' . $tooltip . ' class="deleteDividend"><i class="fa fa-times"></i></a>';
                }
            } else {
                $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_DIVIDEND_DELETE') . '"';
                $href = $this->encrypt->encode('ACTION_DELETE/' . $val->DID);
                echo '<a href="' . $href . '" ' . $tooltip . ' class="deleteDividend"><i class="fa fa-times"></i></a>';
            }
        }
        if ($val->Status == 2) {
            $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_DIVIDEND_MINUTES') . '"';
            $id = site_url() . 'clients/dividend/pdf/' . $this->encrypt->encode('PDF_MINUTES/' . $val->DID . '/YES_SIG');
            $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_DIVIDEND_WITH_CERTIFICATE') . '"';
            echo '&nbsp<a ' . $tooltip . ' href="' . $id . '" style="color:red;">';
            echo '<i class="fa fa-file-pdf-o"></i>';
            echo '</a>';
            $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_DIVIDEND_WITHOUT_CERTIFICATE') . '"';
            if ($access) {
                $id = site_url() . 'clients/dividend/pdf/' . $this->encrypt->encode('PDF_MINUTES/' . $val->DID . '/NO_SIG');
                echo '&nbsp<a ' . $tooltip . ' href="' . $id . '">';
                echo '<i class="fa fa-file-pdf-o"></i>';
                echo '</a>';
            }
        }
        if($user['AccountantAccess'] != ''){
			$link  = $this->encrypt->encode($val->DID);
			$class = "class='createInvoice editDividend btn btn-primary btn-xs color pointer'";
			echo '&nbsp;<a href="' . $link . '" ' . $class . '>Edit</a>';
        }
        echo '</td>';
        echo '</tr>';
        $sn++;
    }
}