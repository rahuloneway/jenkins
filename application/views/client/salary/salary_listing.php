<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$user = $this->session->userdata('user');
$delete_access = accountant_role(clientAccess());

if (clientAccess()) {
    $access = 1;
} else {
    $access = 0;
}

$j_date = get_filed_year();

if (count($items) == 0) {
    echo '<tr>';
    echo '<td colspan="11">';
    echo '<div class="alert alert-info text-center">';
    echo $this->lang->line('SALARY_NO_RECORD_FOUND');
    echo '</div>';
    echo '</td>';
    echo '</tr>';
} else {
    $x = 0;
    $gross_salary = 0;
    $income_tax = 0;
    $nic_employee = 0;
    $nic_employer = 0;
    $smp = 0;
    $net_pay = 0;
    foreach ($items as $key => $val):

        $gross_salary += $val->GrossSalary;
        $income_tax += $val->IncomeTax;
        $nic_employee += $val->NIC_Employee;
        $nic_employer += $val->Employeer_NIC;
        $smp += $val->SMP;
        $net_pay += $val->NetPay;
        ?>
        <tr>
            <td data-title="#">
        <?php echo++$x; ?>
            </td>
            <td data-title="financial year">
        <?php echo $val->FinancialYear; ?>
            </td>
            <td data-title="Pay Date">
        <?php echo cDate($val->PayDate); ?>
            </td>
            <td data-title="Gross Salary" class="text-right">
        <?php echo numberFormat($val->GrossSalary); ?>
            </td>
            <td data-title="Income Tax" class="text-right">
        <?php echo numberFormat($val->IncomeTax); ?>
            </td>
            <td data-title=" NIC Employee" class="text-right">
        <?php echo numberFormat($val->NIC_Employee); ?>
            </td>
            <td data-title=" Employer NIC" class="text-right">
        <?php echo numberFormat($val->Employeer_NIC); ?>
            </td>
            <td data-title=" SMP" class="text-right">
        <?php echo numberFormat($val->SMP); ?>
            </td>
            <td data-title="  Net Pay " style="background-color: #DEDDDE;" class="text-right">
        <?php echo numberFormat($val->NetPay); ?>
            </td>
            <td id="sl<?php echo $val->ID; ?>">
        <?php echo cDate($val->PaidDate); ?>
            </td>
                <?php if ($access == 1): ?>
                <td data-title="Actions">
                <?php
                if ($val->Status == 0):
                    $href = $this->encrypt->encode('ACTION_PAID/' . $val->ID);
                    $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_SALARY_PAID') . '"';
                    echo '&nbsp;<a href= "' . $href . '" rel = "' . $val->ID . '" class="btn btn-primary btn-xs color paidSalary" ' . $tooltip . '>';
                    echo 'UNPAID';
                    echo '</a>';

                    $href = $this->encrypt->encode('ACTION_DELETE/' . $val->ID);
                    $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_SALARY_DELETE') . '"';
                    echo '<a href= "' . $href . '"class=" color deleteSalary" ' . $tooltip . '>';
                    echo '<i class="fa fa-times"></i>';
                    echo '</a>';
                    ?>
                    <?php else: ?>
                        <span class="btn btn-success btn-xs">PAID</span>
                        <?php
                        if ($delete_access) {
                            // if(strtotime($val->PaidDate) > strtotime($j_date))
                            if (strtotime($val->PaidDate) >= strtotime($j_date)) {
                                $href = $this->encrypt->encode('ACTION_DELETE/' . $val->ID);
                                $tooltip = 'data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_SALARY_DELETE') . '"';
                                echo '&nbsp;<a href= "' . $href . '"class=" color deleteSalary" ' . $tooltip . '>';
                                echo '<i class="fa fa-times"></i>';
                                echo '</a>';
                            }
                        }
                        ?>
                    <?php endif; ?>
                </td>
                <?php else: ?>
                    <?php
                    echo '<td>';
                    if ($val->Status == 0) {
                        echo '<span class="btn btn-danger btn-xs">UNPAID</span>';
                    } else {
                        echo '<span class="btn btn-success btn-xs">PAID</span>';
                    }
                    echo '</td>';
                    ?>
            <?php endif; ?>

        </tr>
            <?php
        endforeach;
    }
    ?>
<?php if (count($items) != 0): ?>
    <tr  class="salary-table">

        <td colspan="3">
            <span  class="pull-right">Total</span>
        </td>
        <td class="text-right">
    <?php echo numberFormat($gross_salary); ?>
        </td>
        <td class="text-right">
    <?php echo numberFormat($income_tax); ?>
        </td>
        <td class="text-right">
    <?php echo numberFormat($nic_employee); ?>
        </td>
        <td class="text-right">
    <?php echo numberFormat($nic_employer); ?>
        </td>
        <td class="text-right">
    <?php echo numberFormat($smp); ?>
        </td>
        <td class="text-right">
    <?php echo numberFormat($net_pay); ?>
        </td>
        <td colspan="2">

        </td>
    </tr>
<?php endif; ?>