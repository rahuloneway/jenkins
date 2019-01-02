<?php
//echo "<pre>"; print_r($items); echo "</pre>";
?>  
  <div class="table_b_updte table-responsive">

                <table class="table-striped tble_colr_txt">
                    <thead>
                        <tr class="salary-table">
                            <?php if ($access): ?>
                                <th>

                                </th>
                            <?php endif; ?>
                            <th>
                                #
                            </th>
                            <th width="13%">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_DATE'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_TYPE'); ?>
                            </th>
                            <th width="250">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_DESCRIPTION'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_OUT'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_MONEY_IN'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_BALANCE'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_CHECK'); ?>
                            </th>
                            <!--th width="100">
                                <a href="<?php echo $this->encrypt->encode('SORT_BY_CATEGORY'); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $this->lang->line('TOOLTIP_CATEGORY'); ?>" class="sort color">
                                    <?php echo $this->lang->line('BANK_TABLE_COLUMN_CATEGORY'); ?>
                                    <?php
                                    getSortDirection($order, 'SORT_BY_CATEGORY', $asc_order_value);
                                    ?>
                                </a>
                            </th>
                            <th width="100">
                                <?php echo $this->lang->line('BANK_TABLE_COLUMN_ACTIONS'); ?>
                            </th-->
                        </tr>
                    </thead>
                    <tbody id="bank-listing">
                        <?php $this->load->view('accountant/bulkupload/bulk_bank_listing', $items); ?>
                    </tbody>
                </table>
            </div>