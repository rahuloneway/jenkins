<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="panel-group" id="accordion">
    <!-- First Panel -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#collapseOne" href="#collapseOne">
                    Next 30 Days
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
                <table class="dashborad_table">
                    <thead>
                        <tr class="salary-table">
                            <th>
                                Client Name
                            </th>
                            <th>
                                <a href="#" class="color">Company Name</a>
                            </th>
                            <th>
                                <a href="#" class="color">Company Registration Number</a>
                            </th>
                            <th>
                                <a href="#" class="color">Year end Date </a>
                            </th>
                            <th>
                                <a href="#" class="color">CH Account Due </a>
                            </th>
                            <th>
                                <a href="#" class="color">Action</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if (count($annual_items) == 0) {
                            echo '<tr>';
                            echo '<td colspan="6">';
                            echo '<div class="alert alert-info text-center">' . $this->lang->line('ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND') . '</div>';
                            echo '</td>';
                            echo '</tr>';
                        } else {
                            foreach ($annual_items as $key => $val) {
                                $due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->EndDate)));
                                //$annual_date = date('Y-m-d',strtotime($val->ReturnDate));

                                $date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
                                //$date_difference = str_replace('-','',$date_difference);
                                $date_difference = trim($date_difference) / (60 * 60 * 24);
                                if ($date_difference <= 30) {
                                    if ($date_difference <= 30) {
                                        $bg_color = "class='bg-color-red'";
                                    } elseif ($date_difference > 30 && $date_difference <= 60) {
                                        $bg_color = "class='bg-color-amber'";
                                    } elseif ($date_difference > 60) {
                                        $bg_color = "class='bg-color-green'";
                                    }
                                    echo '<tr>';
                                    $id = $this->encrypt->encode($val->ID);
                                    echo '<td>';
                                    echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo $val->CompanyName;
                                    echo '</td>';
                                    echo '<td>';
                                    echo $val->RegistrationNo;
                                    echo '</td>';
                                    echo '<td>';
                                    echo cDate($val->EndDate);
                                    echo '</td>';
                                    $end_date = cDate($val->EndDate);
                                    if (!empty($end_date)) {
                                        echo '<td ' . $bg_color . '>';
                                        echo cDate($due_date);
                                        echo '</td>';
                                    } else {
                                        echo '<td>';
                                        echo '';
                                        echo '</td>';
                                    }

                                    echo '<td>';
                                    if ($end_date != "" && $end_date != "01-01-1970" && $end_date != "00-00-0000") {
                                        $filed = "";
                                        $href = $this->encrypt->encode($val->CID);
                                        $ID = $this->encrypt->encode($val->ID);
                                        if (empty($filed)) {
                                            echo '<a href="' . $href . '" data-client="' . $ID . '" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
                                            echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
                                            echo '</a>';
                                        } else {
                                            echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
                                            echo $this->lang->line('DASHBOARD_FILED_LABEL');
                                            echo '</span>';
                                        }
                                    } else {
                                        echo "&nbsp;";
                                    }
                                    echo '</td>';

                                    echo '</tr>';
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <br/>
    <!-- Second Panel -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#collapseTwo" href="#collapseTwo">
                    Next 60 Days
                </a>
            </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
            <div class="panel-body">
                <table class="dashborad_table">
                    <thead>
                        <tr class="salary-table">
                            <th>
                                Client Name
                            </th>
                            <th>
                                <a href="#" class="color">Company Name</a>
                            </th>
                            <th>
                                <a href="#" class="color">Company Registration Number</a>
                            </th>
                            <th>
                                <a href="#" class="color">Year end Date </a>
                            </th>
                            <th>
                                <a href="#" class="color">CH Account Due </a>
                            </th>
                            <th>
                                <a href="#" class="color">Action</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if (count($annual_items) == 0) {
                            echo '<tr>';
                            echo '<td colspan="6">';
                            echo '<div class="alert alert-info text-center">' . $this->lang->line('ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND') . '</div>';
                            echo '</td>';
                            echo '</tr>';
                        } else {
                            foreach ($annual_items as $key => $val) {
                                $due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->EndDate)));
                                //$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
                                $date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
                                //$date_difference = str_replace('-','',$date_difference);
                                $date_difference = trim($date_difference) / (60 * 60 * 24);
                                if ($date_difference > 30 && $date_difference <= 60) {
                                    if ($date_difference <= 30) {
                                        $bg_color = "class='bg-color-red'";
                                    } elseif ($date_difference > 30 && $date_difference <= 60) {
                                        $bg_color = "class='bg-color-amber'";
                                    } elseif ($date_difference > 60) {
                                        $bg_color = "class='bg-color-green'";
                                    }
                                    echo '<tr>';
                                    $id = $this->encrypt->encode($val->ID);
                                    echo '<td>';
                                    echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo $val->CompanyName;
                                    echo '</td>';
                                    echo '<td>';
                                    echo $val->RegistrationNo;
                                    echo '</td>';
                                    echo '<td>';
                                    echo cDate($val->EndDate);
                                    echo '</td>';
                                    $end_date = cDate($val->EndDate);
                                    if (!empty($end_date)) {
                                        echo '<td ' . $bg_color . '>';
                                        echo cDate($due_date);
                                        echo '</td>';
                                    } else {
                                        echo '<td>';
                                        echo '';
                                        echo '</td>';
                                    }

                                    echo '<td>';
                                    if ($end_date != "" && $end_date != "01-01-1970" && $end_date != "00-00-0000") {
                                        $filed = "";
                                        $href = $this->encrypt->encode($val->CID);
                                        $ID = $this->encrypt->encode($val->ID);
                                        if (empty($filed)) {
                                            echo '<a href="' . $href . '" data-client="' . $ID . '" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
                                            echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
                                            echo '</a>';
                                        } else {
                                            echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
                                            echo $this->lang->line('DASHBOARD_FILED_LABEL');
                                            echo '</span>';
                                        }
                                    } else {
                                        echo "&nbsp;";
                                    }
                                    echo '</td>';

                                    echo '</tr>';
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <br/>
    <!-- Third Panel -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#collapseThree" href="#collapseThree">
                    60 Days Above
                </a>
            </h4>
        </div>
        <div id="collapseThree" class="panel-collapse collapse">
            <div class="panel-body">
                <table class="dashborad_table">
                    <thead>
                        <tr class="salary-table">
                            <th>
                                Client Name
                            </th>
                            <th>
                                <a href="#" class="color">Company Name</a>
                            </th>
                            <th>
                                <a href="#" class="color">Company Registration Number</a>
                            </th>
                            <th>
                                <a href="#" class="color">Year end Date </a>
                            </th>
                            <th>
                                <a href="#" class="color">CH Account Due </a>
                            </th>
                            <th>
                                <a href="#" class="color">Action</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($annual_items) == 0) {
                            echo '<tr>';
                            echo '<td colspan="6">';
                            echo '<div class="alert alert-info text-center">' . $this->lang->line('ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND') . '</div>';
                            echo '</td>';
                            echo '</tr>';
                        } else {
                            foreach ($annual_items as $key => $val) {
                                $due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->EndDate)));
                                //$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
                                $date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
                                //$date_difference = str_replace('-','',$date_difference);
                                $date_difference = trim($date_difference) / (60 * 60 * 24);
                                if ($date_difference > 60) {
                                    if ($date_difference <= 30) {
                                        $bg_color = "class='bg-color-red'";
                                    } elseif ($date_difference > 30 && $date_difference <= 60) {
                                        $bg_color = "class='bg-color-amber'";
                                    } elseif ($date_difference > 60) {
                                        $bg_color = "class='bg-color-green'";
                                    }
                                    echo '<tr>';
                                    $id = $this->encrypt->encode($val->ID);
                                    echo '<td>';
                                    echo '<a href="' . site_url() . 'client_access/' . $id . '">' . $val->Name . '</a>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo $val->CompanyName;
                                    echo '</td>';
                                    echo '<td>';
                                    echo $val->RegistrationNo;
                                    echo '</td>';
                                    echo '<td>';
                                    echo cDate($val->EndDate);
                                    echo '</td>';
                                    $end_date = cDate($val->EndDate);
                                    if (!empty($end_date)) {
                                        echo '<td ' . $bg_color . '>';
                                        echo cDate($due_date);
                                        echo '</td>';
                                    } else {
                                        echo '<td>';
                                        echo '';
                                        echo '</td>';
                                    }

                                    echo '<td>';
                                    if ($end_date != "" && $end_date != "01-01-1970" && $end_date != "00-00-0000") {
                                        $filed = "";
                                        $href = $this->encrypt->encode($val->CID);
                                        $ID = $this->encrypt->encode($val->ID);
                                        if (empty($filed)) {
                                            echo '<a href="' . $href . '" data-client="' . $ID . '" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
                                            echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
                                            echo '</a>';
                                        } else {
                                            echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
                                            echo $this->lang->line('DASHBOARD_FILED_LABEL');
                                            echo '</span>';
                                        }
                                    } else {
                                        echo "&nbsp;";
                                    }
                                    echo '</td>';

                                    echo '</tr>';
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

