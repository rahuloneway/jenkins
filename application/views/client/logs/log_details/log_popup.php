<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!$details || empty($details) || count($details) <= 0) {
    echo "<div class='alert alert-danger text-center'><i class='glyphicon glyphicon-exclamation-sign'></i>&nbsp;";
    echo $this->lang->line("ERROR_LOADING_LOG_POPUP_DETAILS");
    echo "</div>";
} else {
    ?>
    <div class="clearfix"></div>
    <div id="TBListing" class="table-responsive" style="overflow-y:none;">
        <table>
            <thead>
                <tr class="salary-table" >
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('LOG_LIST_DATE'); ?></a>
                    </th>
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('LOG_LIST_NAME'); ?></a>
                    </th>
                    <!--<th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('LOG_LIST_ID'); ?></a>
                    </th> -->
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('LOG_LIST_TYPE'); ?></a>
                    </th>
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('LOG_LIST_DESCRIPTION'); ?></a>
                    </th>
                </tr>
            </thead>
            <tbody id="log-tbody">
                <?php
              
                $x = 0;
                foreach ($details as $key => $val) {
                    ?>
                    <tr>
                        <td data-title= <?php echo $this->lang->line('LOG_LIST_DATE'); ?> class="text-center">
                            <?php echo date('d-M-Y h:i', strtotime($val['addedOn'])); ?>
                        </td>
                        <td data-title= <?php echo $this->lang->line('LOG_LIST_NAME'); ?> class="text-center">
                            <?php echo getlogUserName($val['UserId'], $val['AccessAccount']); ?>
                        </td>
                        <!--<td data-title= <?php echo $this->lang->line('LOG_LIST_ID'); ?> class="text-center">
                        <?php echo $val['Id']; ?>
                        </td>-->
                        <td data-title= <?php echo $this->lang->line('LOG_LIST_TYPE'); ?> class="text-center">
                            <?php echo $val['Source']; ?>
                        </td>
                        <td data-title= <?php echo $this->lang->line('LOG_LIST_DESCRIPTION'); ?> class="text-center">
                            <?php echo $this->lang->line($val['Type']); ?>
                        </td>
                    </tr>	
                <?php } ?>
            </tbody>
        </table>
    </div>
    </div>				      	
<?php } ?>
