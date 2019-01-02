<?php

$sno = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
if (empty($customers)) {
    echo '<tr><td colspan="8"><div class="alert alert-info text-center">No record found</div></td></tr>';
} else {
	$page = 0;
	$year = 0;
    foreach ($customers as $key => $value) {
        $link = $this->encrypt->encode($value['id'])."/".$this->encrypt->encode($page)."/".$this->encrypt->encode($year);
        echo '<tr>';
        echo '<td >';
        echo $sno++;
        echo '</td>';
        //echo '<td align="center">';
        //echo $value['first_name'] . " " . $value['last_name'];
        //echo '</td>';
        echo '<td align="center">';
        echo "<a href='".site_url()."customer_invoices/".$link."'>".$value['companyname']."</a>";
        echo '</td>';
        echo '<td align="center">';
        echo $value['email'];
        echo '</td>';
        echo '<td align="center">';
        echo $value['mobile'];
        echo '</td>';
        echo '<td align="center">';
        echo date('d-m-Y', strtotime($value['create_date']));
        echo '</td>';
        echo '<td align="center">';
        if ($value['status'] == 1) {
            echo '<a class="changestatus btn btn-success btn-xs pointer color" id=' . $link . ' style="" href="#" title="Click to  enabled the customer" data-toggle="tooltip" >Enabled</a>';
        } else {
            echo '<a class="changestatus btn btn-danger btn-xs pointer color" id=' . $link . ' style="color:#FFF;" href="#" title="Click to disabled the customer" data-toggle="tooltip" >Disabled</a>';
        }
        echo '</td>';
        echo '<td align="center">';
        echo '<a class="editcustomer btn btn-primary btn-xs color pointer" id=' . $link . ' style="" href="#"><i title="Click to edit the customer" data-placement="right" data-toggle="tooltip" class="glyphicon glyphicon-pencil"></i></a>';
        echo '&nbsp;&nbsp;';
      //  echo '<a class="deletecustomer" id =' . $link . ' href="#"><i title="Click to delete the customer" data-placement="right" data-toggle="tooltip" class="fa fa-times"></i></a>';

        echo '</td>';
        echo '</tr>';
    }
}
?>
