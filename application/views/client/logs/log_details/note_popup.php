<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!$stuff || empty($stuff) || count($stuff) <= 0) {
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
                        <a class="color" href="#"><?php echo $this->lang->line('NOTE_ID'); ?></a>
                    </th>
                    <!--<th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('NOTE_CLIENT_ID'); ?></a>
                    </th>-->
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('NOTE_DESCRIPTION'); ?></a>
                    </th>
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('NOTE_ADDED_BY'); ?></a>
                    </th> 
                    <th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('NOTE_ADDED_ON'); ?></a>
                    </th>
                    <!--<th class="text-center">
                        <a class="color" href="#"><?php echo $this->lang->line('NOTE_STATUS'); ?></a>
                    </th>-->
                </tr>
            </thead>
            <tbody id="log-tbody">
                <?php
                $x = 0;
                foreach ($stuff as $key => $val) {
                    ?>
                    <tr>
                        <td data-title= <?php echo $this->lang->line('NOTE_ID'); ?> class="text-center">
                            <?php echo $val['ID']; ?>
                        </td>
                        <!--<td data-title= <?php echo $this->lang->line('NOTE_CLIENT_ID'); ?> class="text-center">
                            <?php echo $val['ClientID']; ?>
                        </td>-->
                        <td data-title= <?php echo $this->lang->line('NOTE_DESCRIPTION'); ?> class="text-center">
                            <p><?php echo $val['Description']; ?></p>
                        </td>
                        <td data-title= <?php echo $this->lang->line('NOTE_ADDED_BY'); ?> class="text-center">
                            <?php echo getUserName($val['AddedBy']); ?>
                        </td>
                        <td data-title= <?php echo $this->lang->line('NOTE_ADDED_ON'); ?> class="text-center">
                            <?php echo $val['AddedOn']; ?>
                        </td>
                        <!--<td data-title= <?php echo $this->lang->line('NOTE_STATUS'); ?> class="text-center">
                            <?php echo $val['Status']; ?>
                        </td>-->
                    </tr>	
                <?php } ?>
            </tbody>
        </table>
    </div>
    </div>				      	
<?php } ?>
