
<?php

$acc_id = clientAccess();
$delete_access = accountant_role($acc_id);
if ($acc_id != 0) {
    $access = 1;
} else {
    $access = 0;
}

$user = $this->session->userdata('user');
//pr($user);
$j_date = get_filed_year();
$sn = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
$page = ($this->uri->segment(2) == '') ? 0 : $this->uri->segment(2) + 1;
$flateRate = 0;
if (empty($vat_listing->Type)) {
    $td = 0;
} else {
    $td = 1;
}

if (count($Invoices) <= 0) {
    echo '<tr>';
    echo '<td colspan="13">';
    echo '<div class="alert alert-info text-center">';
    echo $this->lang->line('CLIENT_NO_RECORD_FOUND');
    echo '</div>';
    echo '</td>';
    echo '</tr>';
} else {
    foreach ($Invoices as $key => $val) {
        if ($val->InvoiceTotal < 0) {
            $class = "class='less-amount'";
        } else {
            $class = "";
        }
        echo '<tr ' . $class . '>';
        echo '<td ' . $class . '>';
        echo $sn;
        echo '</td>';
        echo '<td class="item-id">';
        $link = $this->encrypt->encode($val->InvoiceID);

        if ($val->Status == 1) {
            $class = "class='createInvoice'";
        } elseif ($val->Status == 2) {
            $class = "class='markPaid'";
        } elseif ($val->Status == 3) {
            $class = " class='showPaid' ";
        }
        echo '<a href="' . $link . '" ' . $class . '>';
        echo $val->InvoiceNumber;
        echo '</a>';
        echo '</td>';
        echo '<td>';
        echo $val->Name;
        echo '</td>';
        echo '<td class="text-right">';
        if ($val->InvoiceTotal == 0) {
            echo numberFormat($val->InvoiceTotal);
            $InvoiceTotal += $val->InvoiceTotal;
        } elseif ($val->InvoiceTotal < 0) {
            echo numberFormat(($val->InvoiceTotal + $val->Tax));
            $InvoiceTotal += ($val->InvoiceTotal + $val->Tax);
        } else {
            echo numberFormat(($val->InvoiceTotal - $val->Tax));
            $InvoiceTotal += ($val->InvoiceTotal - $val->Tax);
        }
        //echo $val->SubTotal;
        echo '</td>';
        if (empty($vat_listing->Type) && empty($vat_listing->PercentRateAfterEndDate) && empty($vat_listing->PercentRate)) {
            
        } else {
            echo '<td class="text-right">';
            if ($val->InvoiceTotal != 0) {
                echo numberFormat($val->Tax);
                $InvoiceTax += $val->Tax;
            } else {
                echo numberFormat('0');
                $InvoiceTax += numberFormat('0');
            }
            echo '</td>';
        }
        echo '<td class="text-right">';
        echo numberFormat($val->InvoiceTotal);
        $InvoiceTotalAm += $val->InvoiceTotal;
        echo '</td>';
        
        echo '<td>';
        if (strtotime($val->InvoiceDate) != '') {
            echo cDate($val->InvoiceDate);
        } else {
            echo '';
        }
        //echo cDate($val->InvoiceDate);
        echo '</td>';
        echo '<td>';
        if (strtotime($val->DueDate) != '') {
            echo cDate($val->DueDate);
        } else {
            echo '';
        }
        echo '</td>';
        echo '<td>';
        if (strtotime($val->PaidOn) != '') {
            echo cDate($val->PaidOn);
        } else {
            echo '';
        }
        echo '</td>';
        echo '<td>';
        if ($val->Status == 1) {
            $link = '<span class="btn btn-xs btn-primary">DRAFT</span>';
        } elseif ($val->Status == 2) {
            if ($access) {
                $link = $this->encrypt->encode('ACTION_PAID/' . $val->InvoiceID . '/' . $page);
                $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_IN_PAID') . '"';
                $link = '<a class="btn btn-xs btn-info color changeToPaid" href="' . $link . '" ' . $tooltip . '>CREATED</a>';
            } else {
                $link = '<span class="btn btn-xs btn-info color">CREATED</span>';
            }
        } elseif ($val->Status == 3) {
            $link = '<span class="btn btn-xs btn-success pointer">PAID</span>';
        } else {
            $link = '';
        }
        echo $link;
        echo '</td>';
        echo '<td>';
        $link = $this->encrypt->encode($val->InvoiceID);
        $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_IN_COPY') . '"';
        echo '<span>';
        echo '<a href="' . $link . '" class="copyInvoice" ' . $tooltip . '><i class="fa fa-files-o"></i></a>';
        echo '</span>&nbsp;&nbsp;';
        if ($val->Status == 3) {
            if ($delete_access) {
                if (strtotime($val->PaidOn) > strtotime($j_date)) {
                    $link = $this->encrypt->encode('ACTION_DELETE/' . $val->InvoiceID . '/' . $page);
                    $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_IN_DELETE') . '"';
                    echo '<span><a href="' . $link . '" class="deleteInvoice"><i class="fa fa-times" ' . $tooltip . '></i></a></span>&nbsp;&nbsp;';
                }
            }
        }
        if ($val->Status == 1) {
            $link = $this->encrypt->encode('ACTION_DELETE/' . $val->InvoiceID . '/' . $page);
            $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_IN_DELETE') . '"';
            echo '<span><a href="' . $link . '" class="deleteInvoice"><i class="fa fa-times" ' . $tooltip . '></i></a></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        } elseif ($val->Status == 2) {

            $link = $this->encrypt->encode('ACTION_DELETE/' . $val->InvoiceID . '/' . $page);
            $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_IN_DELETE') . '"';
            echo '<span><a href="' . $link . '" class="deleteInvoice"><i class="fa fa-times" ' . $tooltip . '></i></a></span>';

            $link = $this->encrypt->encode($val->InvoiceID);
            $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_IN_PDF') . '"';
            echo '&nbsp;&nbsp;<span><a href="' . site_url() . 'clients/purchase/getPDF/' . $link . '" ' . $tooltip . ' class="ggeneratePDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a></span>';
        } else {
            $link = $this->encrypt->encode($val->InvoiceID);
            $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_IN_PDF') . '"';
            echo '<span><a href="' . site_url() . 'clients/purchase/getPDF/' . $link . '" ' . $tooltip . ' class="ggeneratePDF" target="_blank"><i class="fa fa-file-pdf-o"></i></a></span>';
        }

        if($user['AccountantAccess'] != ''){
			$link = $this->encrypt->encode($val->InvoiceID);
			$class = "class='createInvoice editcustomer btn btn-primary btn-xs color pointer'";
			echo '&nbsp;<a href="' . $link . '" ' . $class . '>Edit</a>';
        }

        echo '</td>';
        echo '</tr>';
        $sn++;
    }
}
if (count($Invoices) != 0):
    ?>
    <tr  class="exp_detail_table">
        <td colspan="3">
            <span  class="pull-right text-right">Total</span>
        </td>
        <td class="text-right">
            <?php echo numberFormat($InvoiceTotal); ?>
        </td>
        <td class="text-right">
            <?php echo numberFormat($InvoiceTax); ?>
        </td>
        <td class="text-right">
            <?php echo numberFormat($InvoiceTotalAm); ?>
        </td>	
        <td colspan="7"></td>		

    </tr>
    <?php
 endif;
/**
 *	Status : 1 - DRAFT
 *	Status : 2 - CREATED
 *	Status : 3 - PAID
 */