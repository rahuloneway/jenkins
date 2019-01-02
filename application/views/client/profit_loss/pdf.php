<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$TBYears = getTBYear();

$TBYear = $TBYears[0]["title"];
$TBPrevYear = $TBYears[1]["title"];
$totalCurrYear = 0;
$totalPrevYear = 0;
$user = $this->session->userdata('user');
$end_date = $user['CompanyEndDate'];
$financial_date = company_year($TBYear);
$financial_date = $financial_date['end_date'];
$exp = explode('-', $end_date);
if ($exp[1] == 12 && $exp[2] == 31) {
    if ($count == 1) {
        $Ty = $TBYear - 1;
        $Tpy = $TBPrevYear - 1;
    } else {
        $Ty = $TBYear;
        $Tpy = $TBPrevYear;
    }
} else {
    $Ty = $TBYear;
    $Tpy = $TBPrevYear;
}
$user = $this->session->userdata('user');
?>
<table>
    <tr>
        <td colspan="7" align="center" valign="middle" height="100">
            <p style="color:#458ACE;font-weight:bold;font-size:14px;">Trading and Profit and Loss Account</p>
            <p>for the year ending <?php echo date('jS M', strtotime($financial_date)); ?></p> 
        </td>
    </tr>
</table>
<table style="width:100%;" cellspacing="0" cellpadding="0" border="0">
    <thead>
        <tr  bgcolor="#458ACE" color="#FFF" style="line-height:40px;font-weight:bold;">
            <th width="10%" align="center"><?php echo $this->lang->line("TB_ROW_SRNO"); ?></th>
            <th width="20%"></th>
            <th width="30%"></th>
            <!--<th><?php echo $this->lang->line("TB_ROW_TYPE"); ?></th>-->
            <?php
            if (!empty($pfyr) && !empty($pfcwith) && !empty($pfpwith)) {
                $pfy = $pfyr;
                if ($pfcwith == 'Previous_Year') {
                    for ($i = 1; $i <= $pfpwith + 1; $i++) {
                        echo ' <th  >
                              ' . $pfy . '
                            </th>';
                        $pfy = $pfyr - $i;
                    }
                }
            } else {
                ?>
                <th width="20%" align="right">
                    <?php echo $TBYear; ?>
                </th>
                <th width="15%" align="right">
                    <?php echo $TBPrevYear; ?>
                </th>
            <?php } ?>
            <th width="5%"></th>

        </tr>

    </thead>
    <tbody>
        <?php
        if (!empty($TBData)) {
            if (!empty($pfyr) && !empty($pfcwith) && !empty($pfpwith)) {
                ?>
                <tr>
                    <td></td>
                    <td style="color:#458ACE;"><?php echo $this->lang->line("TB_PL_ACCOUNT_INCOME"); ?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <?php
                    $pfy = $pfyr;
                    if ($pfcwith == 'Previous_Year') {
                        for ($i = 1; $i <= $pfpwith + 1; $i++) {
                            echo '<td ></td>';
                        }
                    }
                    ?>
                </tr>
                <?php
                foreach ($TBCats as $TBid => $TBCat) {
                    ?>
                    <tr>	
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <?php
                        $pfy = $pfyr;
                        if ($pfcwith == 'Previous_Year') {
                            for ($i = 1; $i <= $pfpwith + 1; $i++) {
                                $pfy = $pfyr - $i;
                                echo '<td >' . $pfy . '</td>';
                            }
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td  style="color:#458ACE;font-weight:bold;"><?php echo $this->lang->line("TB_ROW_TOTAL"); ?></td>
                    <?php
                    $pfy = $pfyr;
                    if ($pfcwith == 'Previous_Year') {
                        for ($i = 1; $i <= $pfpwith + 1; $i++) {
                            echo '<td >20000.00</td>';
                        }
                    }
                    ?>
                </tr>
            <?php } else {
                ?>
                <?php
                $PL = $BS = 0;
                $count = 0;
                foreach ($TBCats as $TBid => $TBCat) {
                    if ($TBCat["cat_type"] == 2) {
                        $parent = true;
                        if ($TBCat["type"] != 'B/S') {
                            if ($PL == 0 && $TBCat["type"] == "P/L") {
                                ?>

                                <tr>
                                    <td width="10%"></td>
                                    <td width="20%" align="left" style="color:#458ACE;font-size:12px;"><?php echo $this->lang->line("TB_PL_ACCOUNT_INCOME"); ?></td>
                                    <td width="30%">&nbsp;</td>

                                    <td width="20%" align="right">&pound;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td width="15%" align="right">&pound;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="5%"></td>
                                </tr>
                                <?php
                                $PL = 1;
                            }
                            if (!empty($TBCat["childrens"]) && count($TBCat["childrens"]) > 0) {
                                $catHasData = false;
                                foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {
                                    if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                                        $catHasData = true;
                                        $count++;
                                        break;
                                    }
                                }
                                if ($catHasData) {
                                    ?>
                                    <tr>
                                        <td align="center"><?php echo $count; ?></td>
                                        <td align="left"><?php echo $TBCat["title"]; ?></td>
                                        <?php
                                    }
                                    foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {

                                        if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                                            if (!$parent) {
                                                ?>
                                            <tr>
                                                <!--<td>&nbsp;</td>-->
                                                <td>&nbsp;</td>
                                                <?php
                                            } else {
                                                $parent = false;
                                            }
                                            ?>
                                            <td align="left"><?php echo $tbChild["title"]; ?></td>
                                            <!--<td><?php echo $tbChild["type"]; ?></td>-->
                                            <td align="right">
                                                <?php
                                                if (isset($TBData[$TBYear][$tbChildId])) {
                                                    if ($TBCat["type"] == "P/L") {
                                                        $totalCurrYear = $totalCurrYear + $TBData[$TBYear][$tbChildId]["amount"];
                                                    }

                                                    if ($TBData[$TBYear][$tbChildId]["amount"] > 0) {
                                                        echo numberFormatPLNEG($TBData[$TBYear][$tbChildId]["amount"]);
                                                    } else {
                                                        echo numberFormatPL($TBData[$TBYear][$tbChildId]["amount"]);
                                                    }
                                                }
                                                ?>

                                            </td>
                                            <td align="right">
                                                <?php
                                                if (isset($TBData[$TBPrevYear][$tbChildId])) {
                                                    if ($TBCat["type"] == "P/L") {
                                                        $totalPrevYear = $totalPrevYear + $TBData[$TBPrevYear][$tbChildId]["amount"];
                                                    }
                                                    if ($TBData[$TBPrevYear][$tbChildId]["amount"] > 0) {
                                                        echo numberFormatPLNEG($TBData[$TBPrevYear][$tbChildId]["amount"]);
                                                    } else {
                                                        echo numberFormatPL($TBData[$TBPrevYear][$tbChildId]["amount"]);
                                                    }
                                                }
                                                ?>
                                            </td><td width="5%"></td>
                                        </tr>	
                                        <?php
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
                <tr>
                    <!--<td></td>-->
                    <td></td>
                    <td></td>
                    <td align="left" style="color:#458ACE "><?php echo $this->lang->line("TB_ROW_TOTAL"); ?></td>
                    <td style="color:#458ACE">
                        <table> 
                            <tbody>
                                <tr> <?php
        if (strlen($totalCurrYear) < 10) {
            echo "<td></td>";
        } else {
            echo "";
        }
                ?>
                                    <td align="right" style="border-top:1px #000 solid;">
                                        <?php
                                        echo ( $totalCurrYear != 0 ) ? numberFormatPL($totalCurrYear) : numberFormatPL("0.00");
                                        $totalCurrYear1 = $totalCurrYear;
                                        ?>
                                    </td>
                                </tr>    
                            </tbody>     
                        </table>    
                    </td>
                    <td  style="color:#458ACE">
                        <table> 
                            <tbody>
                                <tr> <?php
                                if (strlen($totalPrevYear) < 10) {
                                    echo '<td style="width:30%"></td>';
                                } else {
                                    echo "";
                                }
                                        ?>
                                    <td  align="right" style="border-top:1px #000 solid;width:70%">
                                        <?php
                                        echo ( $totalPrevYear != 0 ) ? numberFormatPL($totalPrevYear) : numberFormatPL("0.00");
                                        $totalPrevYear1 = $totalPrevYear;
                                        ?>
                                    </td>
                                </tr>    
                            </tbody>     
                        </table>    


                    </td><td width="5%"></td>
                </tr>  

                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="6" align="center">
                    <div class="alert alert-info ">
                        <?php echo $this->lang->line('NO_TB_RECORD_FOUND'); ?>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>



<div class="clearfix"><br/><br/>
    <table  >
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <!--<th></th>-->
                <?php
                if (!empty($pfyr) && !empty($pfcwith) && !empty($pfpwith)) {
                    $pfy = $pfyr;
                    if ($pfcwith == 'Previous_Year') {
                        for ($i = 1; $i <= $pfpwith + 1; $i++) {
                            echo ' <th align="left">
                              
                            </th>';
                            $pfy = $pfyr - $i;
                        }
                    }
                } else {
                    ?>

                    <th>

                    </th>
                    <th>

                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($TBData)) {
                if (!empty($pfyr) && !empty($pfcwith) && !empty($pfpwith)) {
                    ?>
                    <tr>
                        <td data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">
                        </td>
                        <td style="color:#458ACE;font-weight:bold;">
                            <?php echo $this->lang->line("TB_PL_ACCOUNT_EXPENSES"); ?>
                        </td>
                        <td>&nbsp;</td>
                        <!--<td>&nbsp;</td>-->
                        <?php
                        $pfy = $pfyr;
                        if ($pfcwith == 'Previous_Year') {
                            for ($i = 1; $i <= $pfpwith + 1; $i++) {
                                echo '<td ></td>';
                            }
                        }
                        ?>
                    </tr>
                    <?php
                    foreach ($TBCats as $TBid => $TBCat) {
                        ?>
                        <tr align="center">	
                            <td></td>
                            <td></td>
                            <td></td>
                            <!--<td></td>-->
                            <?php
                            $pfy = $pfyr;
                            if ($pfcwith == 'Previous_Year') {
                                for ($i = 1; $i <= $pfpwith + 1; $i++) {
                                    $pfy = $pfyr - $i;
                                    echo '<td >' . $pfy . '</td>';
                                }
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td align="center"><?php echo $this->lang->line("TB_ROW_TOTAL"); ?></td>
                        <?php
                        $pfy = $pfyr;
                        if ($pfcwith == 'Previous_Year') {
                            for ($i = 1; $i <= $pfpwith + 1; $i++) {
                                echo '<td>20000.00</td>';
                            }
                        }
                        ?>
                    </tr>
                <?php } else {
                    ?>
                    <?php
                    $PL = $BS = 0;
                    $count = 0;
                    foreach ($TBCats as $TBid => $TBCat) {
                        if ($TBCat["cat_type"] == 1) {
                            $parent = true;
                            if ($TBCat["type"] != 'B/S') {
                                if ($PL == 0 && $TBCat["type"] == "P/L") {
                                    ?>
                                    <tr>
                                        <td width="10%"></td>
                                        <td width="30%" height="10px" style="color:#458ACE;font-size:12px;"><?php echo $this->lang->line("TB_PL_ACCOUNT_EXPENSES"); ?></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <!--<td>&nbsp;</td>-->
                                        <td>&nbsp;</td>
                                    </tr>
                                    <?php
                                    $PL = 1;
                                }
                                if (!empty($TBCat["childrens"]) && count($TBCat["childrens"]) > 0) {
                                    $catHasData = false;
                                    foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {

                                        if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                                            $catHasData = true;
                                            $count++;
                                            break;
                                        }
                                    }
                                    if ($catHasData) {
                                        ?>
                                        <tr>
                                            <td align="center" width="10%"><?php echo $count; ?></td>
                                            <td align="left" width="20%"> <?php
                            if ($tbChild["title"] == 'Employee costs-Directors salaries') {
                                echo 'Directors salaries';
                            } else {
                                echo $tbChild["title"];
                            }
                                        ?></td>
                                            <?php
                                        }
                                        foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {

                                            if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                                                if (!$parent) {
                                                    ?>
                                                <tr>
                                                    <td align="center">&nbsp;</td>
                                                    <td align="center">&nbsp;</td>
                                                    <?php
                                                } else {
                                                    $parent = false;
                                                }
                                                ?>
                                                <td height="20px" width="30%" align="left"><?php echo $tbChild["title"]; ?></td>
                                                <!--<td align="right"><?php echo $tbChild["type"]; ?>
                                                </td>-->
                                                <td width="20%" align="right">

                                                    <?php
                                                    if (isset($TBData[$TBYear][$tbChildId])) {
                                                        if ($TBCat["type"] == "P/L") {
                                                            $totalCurrYear12 = $totalCurrYear12 + $TBData[$TBYear][$tbChildId]["amount"];
                                                        }
                                                        echo numberFormatSignedprofit($TBData[$TBYear][$tbChildId]["amount"]);
                                                    }
                                                    ?>

                                                </td>
                                                <td width="15%" align="right">
                                                    <?php
                                                    if (isset($TBData[$TBPrevYear][$tbChildId])) {
                                                        if ($TBCat["type"] == "P/L") {
                                                            $totalPrevYear12 = $totalPrevYear12 + $TBData[$TBPrevYear][$tbChildId]["amount"];
                                                        }
                                                        echo numberFormatSignedprofit($TBData[$TBPrevYear][$tbChildId]["amount"]);
                                                    }
                                                    ?>

                                                </td><td width="5%"></td>
                                            </tr>	
                                            <?php
                                        }
                                    }
                                }
                            }
                        }
                    }
                    ?>
                    <tr>
                        <!--<td></td>-->
                        <td width="10%"></td>
                        <td width="20%"></td>
                        <td width="30%" align="left" style="color:#458ACE;"><?php echo $this->lang->line("TB_ROW_TOTAL"); ?></td>
                        <td width="20%" align="right" style="color:#458ACE;">
                            <table> 
                                <tbody>
                                    <tr> <?php
            if (strlen($totalCurrYear) < 10) {
                echo "<td></td>";
            } else {
                echo "";
            }
                    ?>
                                        <td align="right" style="border-top:1px #000 solid;">
                                            <?php
                                            $totalCurrY = $totalCurrY + $totalCurrYear12;
                                            echo ( $totalCurrY != 0 ) ? ("(" . str_replace('-', '', numberFormatPL($totalCurrY)) . ")") : numberFormatSignedprofit("0.00");
                                            $totalCurrYear2 = $totalCurrY;
                                            ?>
                                        </td>
                                    </tr>    
                                </tbody>                                 
                            </table>   
                        </td>
                        <td width="16%" align="right" style="color:#458ACE;">
                            <table> 
                                <tbody>
                                    <tr> <?php
                                    if (strlen($totalPrevYear) < 10) {
                                        echo '<td style="width:40%"></td>';
                                    } else {
                                        echo "";
                                    }
                                            ?>
                                        <td align="right" style="border-top:1px #000 solid;width:60%;">
                                            <?php
                                            $totalPrevY = $totalPrevY + $totalPrevYear12;
                                            echo ( $totalPrevY != 0 ) ? ("(" . str_replace('-', '', numberFormatPL($totalPrevY)) . ")") : numberFormatPL("0.00");
                                            $totalPrevYear2 = $totalPrevY;
                                            ?>
                                        </td>
                                    </tr>    
                                </tbody>                                 
                            </table>

                        </td>
                    </tr>  
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="6">
                        <div class="alert alert-info ">
                            <?php echo $this->lang->line('NO_TB_RECORD_FOUND'); ?>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="clearfix"><br/><br/>
        <table>
            <tbody>    
                <tr  bgcolor="#458ACE" color="#fff" rowspan="2" font-size="12px" color="#FFF" height="40px;" style="line-height:45px;">
                    <!--<td></td>-->
                    <td width="10%"></td>
                    <td width="20%"></td>
                    <td width="30%" align="left" style="color:#FFF;font-size:11px"><?php echo $this->lang->line("TB_ROW_TOTAL_PROFIT"); ?></td>
                    <td width="20%" align="right" style="color:#FFF;font-size:11px">
                        <?php
                        $nettotalCurrYear = str_replace('-', '', $totalCurrYear1) - str_replace('-', '', $totalCurrYear2);
                        if ($nettotalCurrYear != 0) {
                            $fcy = str_replace('-', '', @$totalCurrYear1);
                            if ($totalCurrYear2 > $fcy) {
                                echo "(" . numberFormatPL($nettotalCurrYear) . ")";
                            } else {
                                echo numberFormatPL($nettotalCurrYear);
                            }
                        } else {
                            echo numberFormatPL("0.00");
                        }
                        ?>
                    </td>
                    <td  width="15%" align="right" style="color:#FFF;font-size:11px">
                        <?php
                        $nettotalPrevYear = str_replace('-', '', $totalPrevYear1) - str_replace('-', '', $totalPrevYear2);
                        if ($nettotalPrevYear != 0) {
                            $fpy = str_replace('-', '', @$totalPrevYear1);
                            if ($totalPrevYear2 > $fpy) {
                                echo "(" . numberFormatPL($nettotalPrevYear) . ")";
                            } else {
                                echo numberFormatPL($nettotalPrevYear);
                            }
                        } else {
                            echo numberFormatPL("0.00");
                        }
                        ?>
                    </td>
                    <td width="5%" align="right" style="color:#FFF;"></td>
                </tr>  
            </tbody>
            <tfoot>
                <tr >
                    <td colspan="7" align="center" valign="middle" height="100">
                        <br/><br/><br/>
                        <p><?php echo $this->lang->line('INVOICE_PDF_TEXT_TEN'); ?><?php echo $user['CompanyRegNo']; ?></p>
                        <p>
                            <?php echo $this->lang->line('INVOICE_PDF_TEXT_ELEVEN'); ?><?php echo 'UK-England'; ?>
                        </p>
                    </td>
                </tr>

            </tfoot>
        </table>