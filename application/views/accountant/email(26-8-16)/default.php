<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$this->load->view('accountant/header', array('page' => $page, 'title' => $title));
$Days_relation_with = $this->session->userdata('Days_relation_with');
$Status = $this->session->userdata('Status');
if (!empty($Days_relation_with) || !empty($Status)) {
    $Days_relation_with = $this->session->userdata('Days_relation_with');
    $Status = $this->session->userdata('Status');
} else {
    $Days_relation_with = $_POST['Days_relation_with'];
    $Status = $_POST['Status'];
}


?>
<section class="grey-body">
    <div class="container-fluid ">
        <div class="account_sum border_box_trial">
            <h4><?php echo $this->lang->line('EMAIL_PAGE_TITLE'); ?></h4>
             <div class="clr"></div>
            <?php echo $this->session->flashdata('templateDocumentError'); ?>
            <div class="panel panel-default panel_custom">
                <div class="panel-body row">
                    <?php echo form_open('email', array('name' => 'email-search', 'id' => 'email-search')); ?>
                    <div class="top_btm_spc">
                        <div class="col-md-3 col-sm-3 col-xs-12  padding_field">
                            <div class="wid-40">
                                <label><?php echo $this->lang->line('EMAIL_SEARCH_VAT_TYPE'); ?>:</label>
                            </div>
                            <div class="wid-60">
                                <select class="form-control" id="Status" name="Status">
                                    <option selected="selected" value="">--Select Vat Type--</option>
                                    <option value="ACCOUNT_DUE" <?php
                    if ($Status == 'ACCOUNT_DUE') {
                        echo 'selected="selected"';
                    } else {
                        echo '';
                    }
                    ?>>Account Due</option>
                                    <option value="RETURN_DUE" <?php
                                            if ($Status == 'RETURN_DUE') {
                                                echo 'selected="selected"';
                                            } else {
                                                echo '';
                                            }
                    ?>>Return Due</option>
                                    <option value="VAT_DUE" <?php
                                            if ($Status == 'VAT_DUE') {
                                                echo 'selected="selected"';
                                            } else {
                                                echo '';
                                            }
                    ?>>Vat Due</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="top_btm_spc">
                        <div class="col-md-3 col-sm-3 col-xs-12  padding_field">
                            <div class="wid-40">
                                <label><?php echo $this->lang->line('EMAIL_SEARCH_VAT_DAYS'); ?>:</label>
                            </div>
                            <div class="wid-60">
                                <select class="form-control" id="Days_relation_with" name="Days_relation_with">
                                    <option selected="selected" value="0">--Select Days--</option>
                                    <option value="30" <?php
                                            if ($Days_relation_with == '30') {
                                                echo 'selected="selected"';
                                            } else {
                                                echo '';
                                            }
                    ?>>Next 30 days</option>
                                    <option value="30-60" <?php
                                            if ($Days_relation_with == '30-60') {
                                                echo 'selected="selected"';
                                            } else {
                                                echo '';
                                            }
                    ?>>Next 30 TO 60 days</option>
                                    <option value="60" <?php
                                            if ($Days_relation_with == '60') {
                                                echo 'selected="selected"';
                                            } else {
                                                echo '';
                                            }
                    ?>>60 Days Above</option>
                                </select>
                            </div>
                        </div>
                    </div>
					<div class="top_btm_spc">
                        <div class="col-md-3 col-sm-3 col-xs-12  padding_field">
                            <div class="wid-40">
                                <label><?php echo $this->lang->line('EMAIL_SEARCH_VAT_QUARTERS'); ?>:</label>
                            </div>
                            <div class="wid-60">
                                <select class="form-control" id="Days_relation_with" name="Days_relation_with">
                                    <option selected="selected" value="0">--Select Quarters--</option>
                                    <option value="30">Q1</option>
                                    <option value="30-60">Q2</option>
                                    <option value="60">Q3</option>
                                    <option value="60">Q4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 text-right padding_field btn_float pull-right"> 
                        <button class="btn  btn_search btn-search" type="submit">
                            <span class="glyphicon glyphicon-search"></span>Search
                        </button> 
                        <a class="btn  btn_search reset" type="button" href="<?php echo site_url(); ?>email">
                            <span class="glyphicon glyphicon-refresh"></span>Reset
                        </a>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>

            <div class="listing-container">    
                <div class="panel panel-default panel_custom">
                    <div class="panel-body row ">
                        <div class="col-md-3 pull-left">
                            <a href="#" class="btn btn-inverse add-email-template">
                                <i class="glyphicon glyphicon-plus"></i>
                                Add Template
                            </a>
                        </div>
                        <div class="col-md-9 pull-right bPagination">
                            <?php
                            if ($Status == 'ACCOUNT_DUE' && $Status != 'RETURN_DUE' && $Status != 'VAT_DUE') {
                                echo $pagination . '<br/>';
                            } else if ($Status != 'ACCOUNT_DUE' && $Status == 'RETURN_DUE' && $Status != 'VAT_DUE') {
                                echo $pagination . '<br/>';
                            } else if ($Status != 'ACCOUNT_DUE' && $Status != 'RETURN_DUE' && $Status == 'VAT_DUE') {
                                echo $pagination . '<br/>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="table-responsive">
                    <!-------------ACCOUNT_DUE------------>
                    <?php
                    if ($Status == 'ACCOUNT_DUE') {
                        $sno = '';
                        $sno = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
                        ?>
                        <table class="dashborad_table">
                            <thead>
                                <tr class="salary-table">
                                    <th>
                                        <input type="checkbox" name="email_Statements" id="selectall"class="pull-left" value="0"/>
                                    </th>
                                    <th>
                                        #Id
                                    </th>
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
                                    
                                } else { 
                                    foreach ($annual_items as $key => $val) {
                                        $due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->EndDate)));
                                        //$annual_date = date('Y-m-d',strtotime($val->ReturnDate));

                                        $date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
                                        //$date_difference = str_replace('-','',$date_difference);
                                        $date_difference = trim($date_difference) / (60 * 60 * 24);

                                        if ($date_difference <= $no30) {
                                            $bg_color = "class='bg-color-red'";
                                        } elseif ($date_difference > $no30 && $date_difference <= $no60) {
                                            $bg_color = "class='bg-color-amber'";
                                        } elseif ($date_difference > $abvoe) {
                                            $bg_color = "class='bg-color-green'";
                                        }
                                        echo '<tr>';
                                        echo '<td>';
                                        echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb" value="'.$val->ID.'" data-value="'.$val->CID.'" /></td>';
                                        echo '</td>';
                                        echo '<td>';
                                        echo $sno;
                                        echo '</td>';
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
                                                echo '<a href="#" data-client="' . $ID . '" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
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

                                        $sno++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <?php
                        if ($Status != 'RETURN_DUE' && $Status != 'VAT_DUE') {
                            $sno = '';
                            $sno = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
                            ?>
                            <table class="dashborad_table">
                                <thead>
                                    <tr class="salary-table">
                                        <th>
                                            <input type="checkbox" name="email_Statements" id="selectall"class="pull-left" value="0" />
                                        </th>
                                        <th>
                                            #Id
                                        </th>
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
                                        
                                    } else {
                                        foreach ($annual_items as $key => $val) {
                                            $due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->EndDate)));
                                            //$annual_date = date('Y-m-d',strtotime($val->ReturnDate));

                                            $date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
                                            //$date_difference = str_replace('-','',$date_difference);
                                            $date_difference = trim($date_difference) / (60 * 60 * 24);
                                            if ($date_difference <= 30) {
                                                $bg_color = "class='bg-color-red'";
                                            } elseif ($date_difference > 30 && $date_difference <= 60) {
                                                $bg_color = "class='bg-color-amber'";
                                            } elseif ($date_difference > 60) {
                                                $bg_color = "class='bg-color-green'";
                                            }
                                            echo '<tr>';
                                            echo '<td>';
                                            echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb" value="'.$val->Id.'"/></td>';
                                            echo '</td>';
                                            echo '<td>';
                                            echo $sno;
                                            echo '</td>';
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
                                                    echo '<a href="#" data-client="' . $ID . '" class="btn btn-info btn-xs color markAFiled" data-toggle="tooltip" data-placement="right" title="' . $this->lang->line('TOOLTIP_FILE_RETURN') . '">';
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

                                            $sno++;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <?php
                        }
                    }
                    ?>
                    <!-------------RETURN_DUE------------>
                    <?php
                    if ($Status == 'RETURN_DUE') {
                        $sno = '';
                        $sno = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
                        ?>
                        <table class="dashborad_table">
                            <thead>
                                <tr class="salary-table">
                                    <th>
                                        <input type="checkbox" name="email_Statements" id="selectall"class="pull-left" value="0"/>
                                    </th>
                                    <th>#Id</th>
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
                                if (count($return_items) == 0) {
                                    
                                } else {
                                    foreach ($return_items as $key => $val) {
                                        $return_date = $val->ReturnDate;
                                        $date_difference = strtotime($return_date) - strtotime(date('Y-m-d'));
                                        //$date_difference = str_replace('-','',$date_difference);
                                        $date_difference = $date_difference / (60 * 60 * 24);
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
                                        echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb" value="'.$val->Id.'"/></td>';
                                        echo '</td>';
                                        echo '<td>';
                                        echo $sno;
                                        echo '</td>';
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

                                        $sno++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    <!-------------Vat_DUE------------>
                    <?php
                    if ($Status == 'VAT_DUE') {
                        $sno = '';
                        $sno = ($this->uri->segment(2) == '') ? 1 : $this->uri->segment(2) + 1;
                        ?>
                        <table class="dashborad_table">
                            <thead>
                                <tr class="salary-table">
                                    <th>
                                        <input type="checkbox" name="email_Statements" id="selectall"class="pull-left" value="0"/>
                                    </th>
                                    <td># Id</td>
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
                                    <th>
                                        <a href="#" class="color">VAT Due</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $com_date = date('Y-m-d', strtotime('-6 month', strtotime(date('Y-m-d'))));
                                if (count($vatdue_items) == 0) {
                                    
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
                                                $bg_color = "class='bg-color-red'";
                                            } elseif ($date_difference > 30 && $date_difference <= 60) {
                                                $bg_color = "class='bg-color-amber'";
                                            } elseif ($date_difference > 60) {
                                                $bg_color = "class='bg-color-green'";
                                            }
                                            echo '<tr>';

                                            $id = $this->encrypt->encode($val->ID);
                                            echo '<td>';
                                            echo ' <input type="checkbox" class="email-statement-check checkbox" name="cb" value="'.$val->Id.'"/></td>';
                                            echo '</td>';
                                            echo '<td>';
                                            echo $sno;
                                            echo '</td>';
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

                                            echo '</tr>';
                                            $sno++;
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                        <?php
                    }
                    ?>
                </div>
                <div class="clearfix"></div>
                <div class="panel panel-default panel_custom">
                    <div class="panel-body row ">
                        <div class="col-md-3 pull-left">
                            <div class="col-md-2 send-client-mail" style="display:none">
                                <button type="button" class="btn btn-danger btn-send-mail">Send Email</button>
                            </div>
                        </div>

                        <div class="col-md-8 pull-right bPagination">
                            <?php
                            if ($Status == 'ACCOUNT_DUE' && $Status != 'RETURN_DUE' && $Status != 'VAT_DUE') {
                                echo $pagination . '<br/>';
                            } else if ($Status != 'ACCOUNT_DUE' && $Status == 'RETURN_DUE' && $Status != 'VAT_DUE') {
                                echo $pagination . '<br/>';
                            } else if ($Status != 'ACCOUNT_DUE' && $Status != 'RETURN_DUE' && $Status == 'VAT_DUE') {
                                echo $pagination . '<br/>';
                            } else {
                                echo $pagination . '<br/>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div> 

        </div>
    </div>
</section>
<?php $this->load->view('accountant/footer'); ?>
<div class="modal fade modal select-email-template" id="select-email-template" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"data-backdrop="static">
    <div class="modal-dialog modal-lg" style="width: 90%; height: auto; max-height: 100%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php echo $this->lang->line('BUTTON_CLOSE'); ?></span>
                </button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body"></div>
         </div>
    </div>
</div>
