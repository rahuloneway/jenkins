<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="panel-group" id="accordion">
    <!-- First Panel -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#collapseOne1" href="#collapseOne1">
                    Next 30 Days
                </a>
            </h4>
        </div>
        <div id="collapseOne1" class="panel-collapse collapse in">
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
                                <a href="#" class="color">Company Number</a>
                            </th>
                            <th>
                                <a href="#" class="color">Year End Date</a>
                            </th>
                            <th>
                                <a href="#" class="color">Annual Return Due</a>
                            </th>
                            <th>
                                <a href="#" class="color">Action</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>



                        <?php
//pr($return_items);
                        if (count($return_items) == 0) {
                            echo '<tr>';
                            echo '<td colspan="6">';
                            echo '<div class="alert alert-info text-center">' . $this->lang->line('ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND') . '</div>';
                            echo '</td>';
                            echo '</tr>';
                        } else {
                            foreach ($return_items as $key => $val) {
                                $return_date = $val->ReturnDate;
                                $date_difference = strtotime($return_date) - strtotime(date('Y-m-d'));
                                //$date_difference = str_replace('-','',$date_difference);
                                $date_difference = $date_difference / (60 * 60 * 24);
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
                                    $return_date = cDate($val->ReturnDate);
                                    if (!empty($return_date)) {
                                        echo '<td ' . $bg_color . '>';
                                        echo cDate($val->ReturnDate);
                                        echo '</td>';
                                    } else {
                                        echo '<td>';
                                        echo '';
                                        echo '</td>';
                                    }

                                    echo '<td>';
                                    if ($return_date != "" && $return_date != "01-01-1970" && $return_date != "00-00-0000") {
                                        $filed = "";
                                        $href = $this->encrypt->encode($val->CID);
                                        $ID = $this->encrypt->encode($val->ID);
                                        if (empty($filed)) {
                                            echo '<a href="' . $href . '" data-client="' . $ID . '" class="btn btn-info btn-xs color markRFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
                                            echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
                                            echo '</a>';
                                        } else {
                                            echo 'aaaaaaaaa<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
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
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#collapseTwo1" href="#collapseTwo1">
                    Next 60 Days
                </a>
            </h4>
        </div>
        <div id="collapseTwo1" class="panel-collapse collapse">
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
                                <a href="#" class="color">Company Number</a>
                            </th>
                            <th>
                                <a href="#" class="color">Year End Date</a>
                            </th>
                            <th>
                                <a href="#" class="color">Annual Return Due</a>
                            </th>
                            <th>
                                <a href="#" class="color">Action</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>



                        <?php
//pr($return_items);
                        if (count($return_items) == 0) {
                            echo '<tr>';
                            echo '<td colspan="6">';
                            echo '<div class="alert alert-info text-center">' . $this->lang->line('ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND') . '</div>';
                            echo '</td>';
                            echo '</tr>';
                        } else {
                            foreach ($return_items as $key => $val) {
                                $return_date = $val->ReturnDate;
                                $date_difference = strtotime($return_date) - strtotime(date('Y-m-d'));
                                //$date_difference = str_replace('-','',$date_difference);
                                $date_difference = $date_difference / (60 * 60 * 24);
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
                                    $return_date = cDate($val->ReturnDate);
                                    if (!empty($return_date)) {
                                        echo '<td ' . $bg_color . '>';
                                        echo cDate($val->ReturnDate);
                                        echo '</td>';
                                    } else {
                                        echo '<td>';
                                        echo '';
                                        echo '</td>';
                                    }

                                    echo '<td>';
                                    if ($return_date != "" && $return_date != "01-01-1970" && $return_date != "00-00-0000") {
                                        $filed = "";
                                        $href = $this->encrypt->encode($val->CID);
                                        $ID = $this->encrypt->encode($val->ID);
                                        if (empty($filed)) {
                                            echo '<a href="' . $href . '" data-client="' . $ID . '" class="btn btn-info btn-xs color markRFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
                                            echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
                                            echo '</a>';
                                        } else {
                                            echo 'aaaaaaaaa<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURNED') . '" >';
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
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#collapseThree1" href="#collapseThree1">
                    60 Days Above
                </a>
            </h4>
        </div>
        <div id="collapseThree1" class="panel-collapse collapse">
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
                                <a href="#" class="color">Company Number</a>
                            </th>
                            <th>
                                <a href="#" class="color">Year End Date</a>
                            </th>
                            <th>
                                <a href="#" class="color">Annual Return Due</a>
                            </th>
                            <th>
                                <a href="#" class="color">Action</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>



                        <?php
//pr($return_items);
                        if (count($return_items) == 0) {
                            echo '<tr>';
                            echo '<td colspan="6">';
                            echo '<div class="alert alert-info text-center">' . $this->lang->line('ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND') . '</div>';
                            echo '</td>';
                            echo '</tr>';
                        } else {
                            foreach ($return_items as $key => $val) {
                                $return_date = $val->ReturnDate;
                                $date_difference = strtotime($return_date) - strtotime(date('Y-m-d'));
                                //$date_difference = str_replace('-','',$date_difference);
                                $date_difference = $date_difference / (60 * 60 * 24);
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
                                    $return_date = cDate($val->ReturnDate);
                                    if (!empty($return_date)) {
                                        echo '<td ' . $bg_color . '>';
                                        echo cDate($val->ReturnDate);
                                        echo '</td>';
                                    } else {
                                        echo '<td>';
                                        echo '';
                                        echo '</td>';
                                    }

                                    echo '<td>';
                                    if ($return_date != "" && $return_date != "01-01-1970" && $return_date != "00-00-0000") {
                                        $filed = "";
                                        $href = $this->encrypt->encode($val->CID);
                                        $ID = $this->encrypt->encode($val->ID);
                                        if (empty($filed)) {
                                            echo '<a href="' . $href . '" data-client="' . $ID . '" class="btn btn-info btn-xs color markRFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
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



