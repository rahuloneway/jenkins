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
?>

<?php
$PL = $BS = 0;
$count = 0;
foreach ($TBCats as $TBid => $TBCat) {
    if ($TBCat["cat_type"] == 2) {
        $parent = true;
        if ($TBCat["type"] != 'B/S') {
            if (!empty($TBCat["childrens"]) && count($TBCat["childrens"]) > 0) {
                $catHasData = false;
                foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {
                    if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                        $catHasData = true;
                        $count++;
                        break;
                    }
                }
                foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {
                    if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                        if (isset($TBData[$TBYear][$tbChildId])) {
                            if ($TBCat["type"] == "P/L") {
                                $totalCurrYearplin = $totalCurrYearplin + $TBData[$TBYear][$tbChildId]["amount"];
                            }
                        }
                        if (isset($TBData[$TBPrevYear][$tbChildId])) {
                            if ($TBCat["type"] == "P/L") {
                                $totalPrevYearplin = $totalPrevYearplin + $TBData[$TBPrevYear][$tbChildId]["amount"];
                            }
                        }
                    }
                }
            }
        }
    }
}
foreach ($TBCats as $TBid => $TBCat) {
    if ($TBCat["cat_type"] == 1) {
        $parent = true;
        if ($TBCat["type"] != 'B/S') {
            if (!empty($TBCat["childrens"]) && count($TBCat["childrens"]) > 0) {
                $catHasData = false;
                foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {
                    if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                        $catHasData = true;
                        $count++;
                        break;
                    }
                }
                foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {
                    if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                        if (isset($TBData[$TBYear][$tbChildId])) {
                            if ($TBCat["type"] == "P/L") {
                                $totalCurrYearplinexp = $totalCurrYearplinexp + $TBData[$TBYear][$tbChildId]["amount"];
                            }
                        }
                        if (isset($TBData[$TBPrevYear][$tbChildId])) {
                            if ($TBCat["type"] == "P/L") {
                                $totalPrevYearplinexp = $totalPrevYearplinexp + $TBData[$TBPrevYear][$tbChildId]["amount"];
                            }
                        }
                    }
                }
            }
        }
    }
}
$pfttotalCurrYear = $totalCurrYearplin + $totalCurrYearplinexp;
$pfttotalPrevYear = $totalPrevYearplin + $totalPrevYearplinexp;
?>
<table>
    <tr>
        <td colspan="7" align="center" valign="middle" height="100">
            <p style="color:#458ACE;font-weight:bold;font-size:14px;">Balance Sheet</p>
            <p>As on <?php echo date('jS M', strtotime($financial_date)); ?></p> 
        </td>
    </tr>
</table>

<table  style="width:100%;" cellspacing="0" cellpadding="0" border="0">
    <thead>
        <tr  bgcolor="#458ACE" color="#FFF" style="line-height:40px;font-weight:bold;">
            <th width="10%" align="center"><?php echo $this->lang->line("TB_ROW_SRNO"); ?></th>
            <th width="25%"></th>
            <th width="35%"></th>
            <th width="15%" align="right">
                <?php echo $TBYear; ?>
            </th>
            <th width="15%" align="right">
                <?php echo $TBPrevYear; ?>
            </th>

        </tr>
    </thead>
    <tbody>
        <?php
        if ($TBData) {
            $PL = $BS = 0;
            $count = 0;
            foreach ($TBCats as $TBid => $TBCat) {
                if ($TBCat["cat_type"] == 3) {
                    if ($TBCat["type"] != "P/L") {
                        $parent = true;
                        if ($PL == 0 && $TBCat["type"] != "P/L") {
                            ?>
                            <?php
                            $PL = 1;
                        } else if ($BS == 0 && $TBCat["type"] == "B/S") {
                            for ($k = 0; $k <= 1; $k++) {
                                ?>
                            <?php } ?>

                            <tr>
                                <td width="10%"></td>
                                <td width="25%" align="left" style="color:#458ACE;font-size:12px;"><?php echo $this->lang->line("TB_BS_ACCOUNT_ASSET"); ?></td>
                                <td width="35%">&nbsp;</td>

                                <td width="15%" align="right">&pound;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td width="15%" align="right">&pound;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="5%"></td>
                            </tr>
                            <?php
                            $BS = 1;
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
                                    <td align="center"  style="width:10%;"><?php echo $count; ?></td>
                                    <td align="left" style="width:25%;"><?php echo $TBCat["title"]; ?></td>
                                    <?php
                                }
                                foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {
                                    if ($tbChild['cat_type'] == 3) {
                                        if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                                            if (!$parent) {
                                                ?>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <?php
                                            } else {
                                                $parent = false;
                                            }
                                            ?>
                                            <td align="left" style="width:35%;"> 
                                                <?php echo $tbChild["title"]; ?>
                                            </td>
                                            <td align="right" style="width:15%">
                                                <?php
                                                // pr( $tbChild );
                                                if (isset($TBData[$TBYear][$tbChildId])) {
                                                    if ($TBCat["type"] == "B/S") {
                                                        $totalCurrYear = $totalCurrYear + $TBData[$TBYear][$tbChildId]["amount"];
                                                    }
                                                    echo numberFormatSignedprofit($TBData[$TBYear][$tbChildId]["amount"]);
                                                }
                                                ?>
                                            </td>
                                            <td align="right" style="width:15%">
                                                <?php
                                                if (isset($TBData[$TBPrevYear][$tbChildId])) {
                                                    if ($TBCat["type"] == "B/S") {
                                                        $totalPrevYear = $totalPrevYear + $TBData[$TBPrevYear][$tbChildId]["amount"];
                                                    }
                                                    echo numberFormatSignedprofit($TBData[$TBPrevYear][$tbChildId]["amount"]);
                                                }
                                                ?>
                                            </td>
                                        </tr>	
                                        <?php
                                    }
                                }
                            }
                        }
                    }
                }
            }
            ?>
            <tr style="color:#458ACE;">
                <td></td>
                <td></td>
                <td align="left" style="color:#458ACE ">&nbsp;&nbsp;<?php echo $this->lang->line("TB_ROW_TOTAL_ASSETS"); ?></td>
                <td>
                    <table> 
                        <tbody>
                            <tr> <?php
        if (strlen($totalCurrYear) < 10) {
            echo '<td style="width:30%"></td>';
        } else {
            echo "";
        }
            ?>
                                <td align="right" style="border-top:1px #000 solid;width:70%">
                                    <?php echo ( $totalCurrYear != 0 ) ? numberFormatPL($totalCurrYear) : numberFormatPL("0.00"); ?>
                                </td>
                            </tr>    
                        </tbody>     
                    </table>    
                </td>
                <td>
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
                                    <?php echo ( $totalPrevYear != 0 ) ? numberFormatPL($totalPrevYear) : numberFormatPL("0.00"); ?>
                                </td>
                            </tr>    
                        </tbody>     
                    </table>    
                </td>
                <td width="5%"></td>
            </tr>
            <?php
            // } 		
        } else {
            ?>
            <tr>
                <td colspan="6" >
                    <div class="alert alert-info text-center">
                        <?php echo $this->lang->line('NO_TB_RECORD_FOUND'); ?>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div class="clearfix"></div><br/><br/>
<table style="width:100%;" cellspacing="0" cellpadding="0" border="0">
    <thead>
        <tr>
            <td width="10%"></td>
            <td width="25%" align="left" style="color:#458ACE;font-size:12px;"><?php echo $this->lang->line("TB_BS_ACCOUNT_LIABILITY"); ?></td>
            <td width="35%">&nbsp;</td>

            <td width="15%" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td width="15%" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="5%"></td>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($TBData) {
            $PL = $BS = 0;
            $count = 0;
            foreach ($TBCats as $TBid => $TBCat) {
                if ($TBCat["cat_type"] == 4) {
                    if ($TBCat["type"] != "P/L") {
                        $parent = true;
                        if ($PL == 0 && $TBCat["type"] != "P/L") {
                            ?>
                            <?php
                            $PL = 1;
                        } else if ($BS == 0 && $TBCat["type"] == "B/S") {
                            for ($k = 0; $k <= 1; $k++) {
                                ?>
                            <?php } ?>

                            <?php
                            $BS = 1;
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
                                    <td align="center"  style="width:10%;"><?php echo $count; ?></td>
                                    <td align="left" style="width:25%;"><?php echo $TBCat["title"]; ?></td>
                                    <?php
                                }
                                foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {
                                    if ($tbChild['cat_type'] == 4) {
                                        if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                                            if (!$parent) {
                                                ?>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                                <?php
                                            } else {
                                                $parent = false;
                                            }
                                            ?>
                                            <td align="left" style="width:35%;"> 
                                                <?php echo $tbChild["title"]; ?>
                                            </td>
                                            <td align="right" style="width:15%">
                                                <?php
                                                if (isset($TBData[$TBYear][$tbChildId])) {
                                                    if ($TBCat["type"] == "B/S") {
                                                        $totalCurrYear1 = $totalCurrYear1 + $TBData[$TBYear][$tbChildId]["amount"];
                                                    }
                                                    echo numberFormatPL($TBData[$TBYear][$tbChildId]["amount"]);
                                                }
                                                ?>
                                            </td>
                                            <td align="right" style="width:15%">
                                                <?php
                                                if (isset($TBData[$TBPrevYear][$tbChildId])) {
                                                    if ($TBCat["type"] == "B/S") {
                                                        $totalPrevYear1 = $totalPrevYear1 + $TBData[$TBPrevYear][$tbChildId]["amount"];
                                                    }
                                                    echo numberFormatPL($TBData[$TBPrevYear][$tbChildId]["amount"]);
                                                }
                                                ?>
                                            </td>
                                        </tr>	
                                        <?php
                                    }
                                }
                            }
                        }
                    }
                }
            }
            ?>
            <tr>
                <td width="10%" align="center"><?php echo $count + 1; ?></td>
                <td align="left" style="width:25%;">Net Profit</td>
                <td width="35%" align="left">&nbsp; Profit for the year</td>
                <td width="15%" align="right">
                    <?php
                    if ($pfttotalCurrYear > 0) {

                        echo "(" . numberFormatPL($pfttotalCurrYear) . ")";
                    } else {
                        echo numberFormatPL($pfttotalCurrYear);
                    }
                    ?>

                </td>
                <td width="15%" align="right">
                    <?php
                    if ($pfttotalPrevYear > 0) {
                        echo "(" . numberFormatPL($pfttotalPrevYear) . ")";
                    } else {
                        echo numberFormatPL($pfttotalPrevYear);
                    }
                    ?>
                </td>

            </tr>                          

            <tr style="color:#458ACE;">
                <td></td>
                <td></td>
                <td align="left" style="color:#458ACE ">&nbsp;&nbsp;<?php echo $this->lang->line("TB_ROW_TOTAL_LIABILITES"); ?></td>
                <td>
                    <table> 
                        <tbody>
                            <tr> <?php
                if (strlen($totalCurrYear) < 10) {
                    echo '<td style="width:30%"></td>';
                } else {
                    echo "";
                }
                    ?>
                                <td align="right" style="border-top:1px #000 solid;width:70%">
                                    <?php
                                    $liabilitycurrYear = $pfttotalCurrYear + $totalCurrYear1;
                                    if ($liabilitycurrYear != 0) {
                                        echo numberFormatPL($liabilitycurrYear);
                                    } else {
                                        echo numberFormatPL("0.00");
                                    }
                                    ?>

                                </td>
                            </tr>    
                        </tbody>     
                    </table>    
                </td>
                <td>
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
                                    $liabilityPrevYear = $pfttotalPrevYear + $totalPrevYear1;
                                    if ($liabilityPrevYear != 0) {
                                        echo numberFormatPL($liabilityPrevYear);
                                    } else {
                                        echo numberFormatPL("0.00");
                                    }
                                    ?>

                                </td>
                            </tr>    
                        </tbody>     
                    </table>    
                </td>
                <td width="5%"></td>
            </tr>
            <?php
            // } 		
        } else {
            ?>
            <tr>
                <td colspan="6" >
                    <div class="alert alert-info text-center">
                        <?php echo $this->lang->line('NO_TB_RECORD_FOUND'); ?>
                    </div>
                </td>
            </tr>
        <?php } ?>
        <tr height="20px;">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr  bgcolor="#458ACE" color="#fff" rowspan="2" font-size="12px" color="#FFF" height="40px;" style="line-height:45px;">
            <td width="10%"></td>
            <td width="25%"></td>
            <td width="35%" align="left" style="color:#FFF;font-size:11px">&nbsp;&nbsp;<?php echo $this->lang->line("TB_ROW_TOTAL_ASSET_LIABLILITY"); ?></td>
            <td width="15%" align="right" style="color:#FFF;font-size:11px">
                <?php
                /* $liabilitycurrYear = $pfttotalCurrYear + $totalCurrYear1;
                  if ($liabilitycurrYear != 0) {
                  echo numberFormatPL($liabilitycurrYear);
                  } else {
                  echo numberFormatPL("0.00");
                  } */
                ?>
            </td>
            <td  width="15%" align="right" style="color:#FFF;font-size:11px">
                <?php
                /* $liabilityPrevYear = $pfttotalPrevYear + $totalPrevYear1;
                  if ($liabilityPrevYear != 0) {
                  echo numberFormatPL($liabilityPrevYear);
                  } else {
                  echo numberFormatPL("0.00");
                  } */
                ?>
            </td>

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