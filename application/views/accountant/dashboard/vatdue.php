<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="panel-group" id="accordion">
    <!-- First Panel -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#collapseOne2" href="#collapseOne2">
                    Next 30 Days
                </a>
            </h4>
        </div>
        <div id="collapseOne2" class="panel-collapse collapse in">
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
                                <a href="#" class="color">VAT Registration No.</a>
                            </th>

                            <!--  testing purpose >
                            <th>
                                    <a href="#" class="color">Year</a>
                            </th>
                            <th>
                                    <a href="#" class="color">Quarter</a>
                            </th>
                            <  testing purpose -->

                            <th>
                                <a href="#" class="color">VAT Due</a>
                            </th>
                            <!-- th>
                                    <a href="#" class="color">Action</a>
                            </th -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $com_date = date('Y-m-d', strtotime('-6 month', strtotime(date('Y-m-d'))));
                        if (count($vatdue_items) == 0) {
                            echo '<tr>';
                            echo '<td colspan="5">';
                            echo '<div class="alert alert-info text-center">' . $this->lang->line('ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND') . '</div>';
                            echo '</td>';
                            echo '</tr>';
                        } else {
                            foreach ($vatdue_items as $key => $val) {
                                $val = (object) $val;
                                $due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->SECOND)));
                                //$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
                                if ($due_date >= $com_date) {
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
                                        echo $val->VATRegistrationNo;
                                        echo '</td>';

                                        /*
                                          echo '<td>';
                                          echo cDate($val->dEndDate);
                                          echo '</td>';

                                          // testing purpose
                                          echo '<td>';
                                          echo $val->year;
                                          echo '</td>';

                                          echo '<td>';
                                          echo $val->quarter;
                                          echo '</td>';
                                          // testing purpose */

                                        // $end_date = cDate($val->SECOND);
                                        $end_date = cDate($due_date);
                                        if (!empty($end_date)) {
                                            echo '<td ' . $bg_color . '>';
                                            echo ($end_date);
                                            echo '</td>';
                                        } else {
                                            echo '<td>';
                                            echo '';
                                            echo '</td>';
                                        }

                                        /*
                                          $filed="";
                                          $href=$this->encrypt->encode($val->CID);
                                          $ID=$this->encrypt->encode($val->ID);
                                          if(empty($filed))
                                          {
                                          echo '<td>';
                                          echo '<a href="'.$href.'" data-client="'.$ID.'" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_FILE_RETURN').'">';
                                          echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
                                          echo '</a>';
                                          echo '</td>';
                                          }else{
                                          echo '<td>';
                                          echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_FILE_RETURNED').'" >';
                                          echo $this->lang->line('DASHBOARD_FILED_LABEL');
                                          echo '</span>';
                                          echo '</td>';
                                          }
                                         */

                                        echo '</tr>';
                                    }
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
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#collapseTwo2" href="#collapseTwo2">
                    Next 60 Days
                </a>
            </h4>
        </div>
        <div id="collapseTwo2" class="panel-collapse collapse">
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
                                <a href="#" class="color">VAT Registration No.</a>
                            </th>

                            <!--  testing purpose >
                            <th>
                                    <a href="#" class="color">Year</a>
                            </th>
                            <th>
                                    <a href="#" class="color">Quarter</a>
                            </th>
                            <  testing purpose -->

                            <th>
                                <a href="#" class="color">VAT Due</a>
                            </th>
                            <!-- th>
                                    <a href="#" class="color">Action</a>
                            </th -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $com_date = date('Y-m-d', strtotime('-6 month', strtotime(date('Y-m-d'))));
                        if (count($vatdue_items) == 0) {
                            echo '<tr>';
                            echo '<td colspan="5">';
                            echo '<div class="alert alert-info text-center">' . $this->lang->line('ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND') . '</div>';
                            echo '</td>';
                            echo '</tr>';
                        } else {
                            foreach ($vatdue_items as $key => $val) {
                                $val = (object) $val;
                                $due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->SECOND)));
                                //$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
                                if ($due_date >= $com_date) {
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
                                        echo $val->VATRegistrationNo;
                                        echo '</td>';

                                        /*
                                          echo '<td>';
                                          echo cDate($val->dEndDate);
                                          echo '</td>';

                                          // testing purpose
                                          echo '<td>';
                                          echo $val->year;
                                          echo '</td>';

                                          echo '<td>';
                                          echo $val->quarter;
                                          echo '</td>';
                                          // testing purpose */

                                        // $end_date = cDate($val->SECOND);
                                        $end_date = cDate($due_date);
                                        if (!empty($end_date)) {
                                            echo '<td ' . $bg_color . '>';
                                            echo ($end_date);
                                            echo '</td>';
                                        } else {
                                            echo '<td>';
                                            echo '';
                                            echo '</td>';
                                        }

                                        /*
                                          $filed="";
                                          $href=$this->encrypt->encode($val->CID);
                                          $ID=$this->encrypt->encode($val->ID);
                                          if(empty($filed))
                                          {
                                          echo '<td>';
                                          echo '<a href="'.$href.'" data-client="'.$ID.'" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_FILE_RETURN').'">';
                                          echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
                                          echo '</a>';
                                          echo '</td>';
                                          }else{
                                          echo '<td>';
                                          echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_FILE_RETURNED').'" >';
                                          echo $this->lang->line('DASHBOARD_FILED_LABEL');
                                          echo '</span>';
                                          echo '</td>';
                                          }
                                         */

                                        echo '</tr>';
                                    }
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
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#collapseThree2" href="#collapseThree2">
                    60 Days Above
                </a>
            </h4>
        </div>
        <div id="collapseThree2" class="panel-collapse collapse">
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
                                <a href="#" class="color">VAT Registration No.</a>
                            </th>

                            <!--  testing purpose >
                            <th>
                                    <a href="#" class="color">Year</a>
                            </th>
                            <th>
                                    <a href="#" class="color">Quarter</a>
                            </th>
                            <  testing purpose -->

                            <th>
                                <a href="#" class="color">VAT Due</a>
                            </th>
                            <!-- th>
                                    <a href="#" class="color">Action</a>
                            </th -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $com_date = date('Y-m-d', strtotime('-6 month', strtotime(date('Y-m-d'))));
                        if (count($vatdue_items) == 0) {
                            echo '<tr>';
                            echo '<td colspan="5">';
                            echo '<div class="alert alert-info text-center">' . $this->lang->line('ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND') . '</div>';
                            echo '</td>';
                            echo '</tr>';
                        } else {
                            foreach ($vatdue_items as $key => $val) {
                                $val = (object) $val;
                                $due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->SECOND)));
                                //$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
                                if ($due_date >= $com_date) {
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
                                        echo $val->VATRegistrationNo;
                                        echo '</td>';

                                        /*
                                          echo '<td>';
                                          echo cDate($val->dEndDate);
                                          echo '</td>';

                                          // testing purpose
                                          echo '<td>';
                                          echo $val->year;
                                          echo '</td>';

                                          echo '<td>';
                                          echo $val->quarter;
                                          echo '</td>';
                                          // testing purpose */

                                        // $end_date = cDate($val->SECOND);
                                        $end_date = cDate($due_date);
                                        if (!empty($end_date)) {
                                            echo '<td ' . $bg_color . '>';
                                            echo ($end_date);
                                            echo '</td>';
                                        } else {
                                            echo '<td>';
                                            echo '';
                                            echo '</td>';
                                        }

                                        /*
                                          $filed="";
                                          $href=$this->encrypt->encode($val->CID);
                                          $ID=$this->encrypt->encode($val->ID);
                                          if(empty($filed))
                                          {
                                          echo '<td>';
                                          echo '<a href="'.$href.'" data-client="'.$ID.'" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_FILE_RETURN').'">';
                                          echo $this->lang->line('DASHBOARD_UNFILED_LABEL');
                                          echo '</a>';
                                          echo '</td>';
                                          }else{
                                          echo '<td>';
                                          echo '<span class="btn btn-success btn-xs color" data-toggle="tooltip" data-placement="right" title="'.$this->lang->line('TOOLTIP_FILE_RETURNED').'" >';
                                          echo $this->lang->line('DASHBOARD_FILED_LABEL');
                                          echo '</span>';
                                          echo '</td>';
                                          }
                                         */

                                        echo '</tr>';
                                    }
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


