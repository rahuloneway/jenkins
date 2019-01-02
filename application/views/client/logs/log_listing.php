<?php
error_reporting(0);
$sn = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
$ip = $_SERVER['REMOTE_ADDR'];
$json = file_get_contents("http://api.easyjquery.com/ips/?ip=".$ip."&full=true");
$json = json_decode($json,true);
$timezone = $json['LocalTimeZone'];
if ($actionlogs[0] == 'No Employees' || empty($actionlogs) || count($actionlogs) <= 0) {
    echo '<tr>';
    echo '<td colspan="13">';
    echo '<div class="alert alert-info text-center">';
    echo $this->lang->line('LOS_NO_LOGS_FOUND');
    echo '</div>';
    echo '</td>';
    echo '</tr>';
} else {
    foreach ($actionlogs as $key => $val) {
		
		//echo "<pre>";print_r($val); die;
		
        if ($val['Source'] == 'INVOICE' || 'BANK' || 'DIVIDEND' || 'EXPENSE' || 'PAYROLL' || 'SALARY' || 'JOURNAL' || 'NOTES') {
            $date = date('d-M-Y h:i', strtotime($val['addedOn']));
        } else if ($val['Source'] == 'LOGIN/LOGOUT') {
            $date = date('d-M-Y h:i', strtotime($val['addedOn']));
        } else {
            $date = '--';
        }

        $link = $this->encrypt->encode($val['Id']);
        $source = $this->encrypt->encode($val['Source']);
        $linkAttrs = "<span id ='" . $link . "' class='showLogDetails' data-type='" . $source . "===" . $val['Source'] . "'>" . $this->lang->line($val['Type']) . "</span>";
		
        if ($val['Source'] != 'BANK' && $val['Source'] != 'NOTES' && $val['Source'] != 'PAYROLL' && $val['Source'] != 'SALARY' && $val['Source'] != 'JOURNAL' && $val['Source'] != 'LOGIN/LOGOUT') {
            if ($val['Source'] == 'INVOICE') { 
                $INVOICE = getInvoicenumber($val['ItemId']);
                $linkAttrs1 = "<a href='#' id ='" . $link . "' class='showLogDetails' data-type='" . $source . "===" . $val['Source'] . "'>" . $INVOICE . "</a>";
            } else if ($val['Source'] == 'EXPENSE') { 
                $EXPENSE = getExpensenumber($val['ItemId']);
                $linkAttrs1 = "<a href='#' id ='" . $link . "' class='showLogDetails' data-type='" . $source . "===" . $val['Source'] . "'>" . $EXPENSE . "</a>";
            } else if ($val['Source'] == 'PURCHASE') { 
                $PURCHASE = getPurchasenumber($val['ItemId']);
                $linkAttrs1 = "<a href='#' id ='" . $link . "' class='showLogDetails' data-type='" . $source . "===" . $val['Source'] . "'>" . $PURCHASE . "</a>";
            } else { 
                $DIVIDEND = getDividendnumber($val['ItemId']);
                $linkAttrs1 = "<a href='#' id ='" . $link . "' class='showLogDetails' data-type='" . $source . "===" . $val['Source'] . "'>" . $DIVIDEND . "</a>";
            }
        } else {
            $linkAttrs1 = "<a href='#' id ='" . $link . "' class='showLogDetails' data-type='" . $source . "===" . $val['Source'] . "'>" . $val['Source'] . "</a>";
        }
        echo '<tr>';
        echo '<td><center>' . $sn . '</center></td>';
        echo '<td><center>' . $date . '</center></td>';
        echo '<td><center>' . $linkAttrs . '</center></td>';
        echo '<td><center>' . $val['Source'] . '</strong></td>';
        echo '<td><center>' . $linkAttrs1 . '</center></td>';
        echo '<td><center>' . getlogUserName($val['UserId'], $val['AccessAccount']) . '</center></td>';
        echo '</tr>';
        $sn++;
    }
}
?>
