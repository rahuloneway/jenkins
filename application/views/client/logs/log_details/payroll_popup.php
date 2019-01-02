<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
echo '<pre>';print_r($payee);echo '</pre>';
?>
<?php echo form_open(site_url() . 'clients/salary/save_payee', array('id' => 'payeUpdateForm', 'name' => 'payeUpdateForm')); ?>
<div class="table-responsive">
    <table>
        <thead>
            <tr class="salary-table">
                <th>
                    #
                </th>
                <th>
                    <a href="#">Quarter </a>
                </th>
                <th>
                    <a href="#"> Income Tax </a>
                </th>
                <th>
                    <a href="#">NIC Employee</a>
                </th>
                <th>
                    <a href="#">NIC Employer</a>
                </th>
                <th>
                    <a href="#">Total £ <a>
                            </th>
                            <th>
                                <a href="#">Payment Reference</a>
                            </th>
                            <th>
                                <a href="#">HMRC refunds</a>
                            </th>
                            <th>
                                <a href="#">Pay Between </a>
                            </th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($payee as $key => $val) {
                                    if ($val->Status == 1) {
                                        ?>
                                        <tr>
                                            <td data-title="#">
                                                <?php echo $key + 1; ?>
                                            </td>
                                            <td data-title="Quarter">
                                                <?php echo $quarters[$val->Quarter]; ?>
                                            </td>
                                            <td data-title="Income Tax ">
                                                <?php echo $val->IncomeTax; ?>
                                            </td>
                                            <td data-title="NIC Employee">
                                                <?php echo $val->NIC_Employee; ?>
                                            </td>
                                            <td data-title="NIC Employer">
                                                <?php echo $val->NIC_Employer; ?>
                                            </td>
                                            <td data-title="Total £">
                                                <?php echo $val->Total; ?>
                                            </td>
                                            <td data-title="PAYE Office Ref">
                                                <?php echo $val->PayeeOfficeReference; ?>
                                            </td>
                                            <td data-title="HMRC refunds">
                                                <?php echo $val->HMRC_Refunds; ?>
                                            </td>
                                            <td data-title="Pay Between">
                                                <?php echo cDate($val->StartDate); ?>
                                                -to-
                                                <?php echo cDate($val->EndDate); ?>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        ?>
                                        <tr>
                                            <td data-title="#">
                                                <?php echo $key + 1; ?>
                                                <input type="hidden" name="ids[]" value="<?php echo $this->encrypt->encode($val->ID); ?>"/>
                                            </td>
                                            <td data-title="Quarter">
                                                <?php echo $quarters[$val->Quarter]; ?>
                                                <input type="hidden" name="quarters[]" value="<?php echo $val->Quarter; ?>"/>
                                            </td>
                                            <td data-title="Income Tax ">
                                                <?php echo $val->IncomeTax; ?>
                                            </td>
                                            <td data-title="NIC Employee">
                                                <?php echo $val->NIC_Employee; ?>
                                            </td>
                                            <td data-title="NIC Employer">
                                                <?php echo $val->NIC_Employer; ?>
                                            </td>
                                            <td data-title="Total £">
                                                <?php echo $val->Total; ?>
                                            </td>
                                            <td data-title="PAYE Office Ref">
                                                <?php echo $val->PayeeOfficeReference; ?>
                                            </td>
                                            <td data-title="HMRC refunds">
                                                <?php echo $val->HMRC_Refunds; ?>
                                            </td>
                                            <td data-title="Pay Between">
                                                <?php echo cDate($val->StartDate); ?>
                                                <span style="float:left; padding:4px 5px;" class="mid-lbl">-to-</span>
                                                <?php echo cDate($val->EndDate); ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                            </table>
                            </div>
                            <?php echo form_close(); ?>