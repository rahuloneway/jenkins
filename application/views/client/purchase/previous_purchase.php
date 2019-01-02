<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>
                    <?php echo $this->lang->line('INVOICE_TABLE_COLUMN_INV_NO'); ?>
                </th>
                <th>
                    <?php echo $this->lang->line('INVOICE_TABLE_COLUMN_CUSTOMER_NAME'); ?>
                </th>
                <th>
                    <?php echo $this->lang->line('INVOICE_TABLE_COLUMN_AMOUNT'); ?>
                </th>
                <th>
                    <?php echo $this->lang->line('INVOICE_TABLE_COLUMN_DUE_DATE'); ?>
                </th>
            </tr>
        </thead>
        <tbody id="dividend-listing">
            <?php
            //echo '<pre>';print_r($items);echo '</pre>';
            $sn = 1;
            if (count($prev_invoices) <= 0) {
                echo '<tr>';
                echo '<td colspan="11">';
                echo '<div class="alert alert-info text-center">';
                echo $this->lang->line('CLIENT_NO_RECORD_FOUND');
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            } else {
                foreach ($prev_invoices as $key => $val) {
                    echo '<tr>';
                    echo '<td>';
                    echo $sn;
                    echo '</td>';
                    echo '<td>';
                    echo $val->InvoiceNumber;
                    echo '</td>';
                    echo '<td>';
                    echo $val->Name;
                    echo '</td>';
                    echo '<td>';
                    echo numberFormat($val->InvoiceTotal);
                    echo '</td>';
                    echo '<td>';
                    echo cDate($val->DueDate);
                    echo '</td>';
                    echo '</tr>';
                    $sn++;
                }
            }
            ?>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <div class="pull-right col-md-6">
        <a data-dismiss="modal" class="btn btn-danger btn-sm spacer" href="#">
            <i class="glyphicon glyphicon-remove-sign"></i>&nbsp;<?php echo $this->lang->line('BUTTON_CLOSE'); ?>
        </a>
    </div>
</div>