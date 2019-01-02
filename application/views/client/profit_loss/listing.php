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
<table>
    <thead>
        <tr class="salary-table">
            <th width="10%">
                <a href="#" class="color"><?php echo $this->lang->line("TB_ROW_SRNO"); ?></a>
            </th>
            <th width="30%">
                <a href="#" class="color"></a>
            </th>
            <th width="20%">
                <a href="#" class="color"></a>
            </th>
            <!--<th class="text-center">
                <a href="#" class="color"><?php echo $this->lang->line("TB_ROW_TYPE"); ?></a>
            </th>-->
            <?php
            if (!empty($pfyr) && !empty($pfcwith) && !empty($pfpwith)) {
                $pfy = $pfyr;
                if ($pfcwith == 'Previous_Year') {
                    for ($i = 1; $i <= $pfpwith + 1; $i++) {
                        echo ' <th class="text-center" >
                               <a href="#" class="color">' . $pfy . '</a>
                            </th>';
                        $pfy = $pfyr - $i;
                    }
                }
            } else { 
                ?>

                <th  width="20%" class="text-center" >
                    <a href="#" class="color"><?php echo $TBYear; ?></a>
                </th>
                <th  width="20%" class="text-center" >
                    <a href="#" class="color"><?php echo $TBPrevYear; ?></a>
                </th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php 
        if (!empty($TBData)) { 
            if (!empty($pfyr) && !empty($pfcwith) && !empty($pfpwith)) 
			{ 
                ?>
                <tr>
                    <td data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">
                    </td>
                    <td style="width:20%;">
                        <strong><?php echo $this->lang->line("TB_PL_ACCOUNT_INCOME"); ?></strong>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <?php 
                    $pfy = $pfyr;
                    if ($pfcwith == 'Previous_Year') {
                        for ($i = 1; $i <= $pfpwith + 1; $i++) {
                            echo '<td class="text-center"></td>';
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
                                echo '<td class="text-center">' . $pfy . '</td>';
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
                    <td  class="text-center"><strong><?php echo $this->lang->line("TB_ROW_TOTAL"); ?></strong></td>
                    <?php
                    $pfy = $pfyr;
                    if ($pfcwith == 'Previous_Year') {
                        for ($i = 1; $i <= $pfpwith + 1; $i++) {
                            echo '<td class="text-center"><strong>20000.00</strong></td>';
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
                    if ($TBCat["cat_type"] == 2) 
					{ 
                        $parent = true;
                        if ($TBCat["type"] != 'B/S') { 
                            if ($PL == 0 && $TBCat["type"] == "P/L") {
                                ?>
                                <tr>
                                    <td data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">

                                    </td>
                                    <td data-title="" style="width:20%;">
                                        <strong><?php echo $this->lang->line("TB_PL_ACCOUNT_INCOME"); ?></strong>
                                    </td>
                                    <td style="width:20%;">&nbsp;</td>
                                    <td><center>&pound;</center></td>
                        <td><center>&pound;</td>
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
                                    <td data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">
                                        <?php echo $count; ?>
                                    </td>
                                    <td data-title="">
                                        <b><?php echo $TBCat["title"]; ?></b>
                                    </td>
                                    <?php
                                }
                                foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {
                                    if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                                        if (!$parent) {
                                            ?>
                                        <tr>
                                            <td data-title="">&nbsp;</td>
                                            <td data-title="">&nbsp;</td>
                                            <?php
                                        } else {
                                            $parent = false;
                                        }
                                        ?>
                                        <td data-title="">
                                            <?php echo $tbChild["title"]; ?>
                                        </td>
                                        <!--<td class="text-center" data-title="<?php echo $this->lang->line("TB_ROW_TYPE"); ?>">
                                        <?php echo $tbChild["type"]; ?>
                                        </td>-->
                                        <td class="text-right" data-title="<?php echo $this->lang->line("TB_ROW_TOTAL_AMOUNT"); ?>" style="width:20%;">
                                            <a href="<?php echo site_url() . 'ledger_account_profit/' . $this->encrypt->encode($tbChildId); ?>" title="Click to view <?php echo $tbChild["title"]; ?> Ledger Accounts" >
                                                <?php
                                                // pr( $tbChild );
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
                                            </a>
                                        </td>
                                        <td data-title="<?php echo $this->lang->line("TB_ROW_TOTAL_AMOUNT"); ?>"  class="text-right"  style="width:20%;">
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
                                        </td>
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
                <td></td>
                <td></td>
                <td  class="text-center"><strong><?php echo $this->lang->line("TB_ROW_TOTAL"); ?></strong></td>

                <td data-title="<?php echo $TBYear; ?>"  class="text-right">
                    <strong>
                        <?php
                        echo ( $totalCurrYear != 0 ) ? numberFormatPL($totalCurrYear) : numberFormatSignedprofit("0.00");
                        $totalCurrYear1 = $totalCurrYear;
                        ?>
                    </strong>
                </td>

                <td data-title="<?php echo $TBPrevYear; ?>"  class="text-right">
                    <strong>
                        <?php
                        echo ( $totalPrevYear != 0 ) ? numberFormatPL($totalPrevYear) : numberFormatSignedprofit("0.00");
                        $totalPrevYear1 = $totalPrevYear;
                        ?>
                    </strong>
                </td>
            </tr>  
            <?php
        }
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



<!----------------------------------------Expenses------------------>
<div class="clearfix"></div><br/><br/>
<table>
    <thead>
        <tr>
            <th width="10%">
                <a href="#" class="color"></a>
            </th>
            <th width="30%">
                <a href="#" class="color"></a>
            </th>
            <th width="20%">
                <a href="#" class="color"></a>
            </th>
            <?php
            if (!empty($pfyr) && !empty($pfcwith) && !empty($pfpwith)) {
                $pfy = $pfyr;
                if ($pfcwith == 'Previous_Year') {
                    for ($i = 1; $i <= $pfpwith + 1; $i++) {
                        echo ' <th class="text-center" >
                               <a href="#" class="color"></a>
                            </th>';
                        $pfy = $pfyr - $i;
                    }
                }
            } else {
                ?>

                <th  width="20%" class="text-center" >
                    <a href="#" class="color"></a>
                </th>
                <th  width="20%" class="text-center" >
                    <a href="#" class="color"></a>
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
                    <td style="width:20%;">
                        <strong><?php echo $this->lang->line("TB_PL_ACCOUNT_EXPENSES"); ?></strong>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <?php
                    $pfy = $pfyr;
                    if ($pfcwith == 'Previous_Year') {
                        for ($i = 1; $i <= $pfpwith + 1; $i++) {
                            echo '<td class="text-center"></td>';
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
                        <?php
                        $pfy = $pfyr;
                        if ($pfcwith == 'Previous_Year') {
                            for ($i = 1; $i <= $pfpwith + 1; $i++) {
                                $pfy = $pfyr - $i;
                                echo '<td class="text-center">' . $pfy . '</td>';
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
                    <td  class="text-center"><strong><?php echo $this->lang->line("TB_ROW_TOTAL"); ?></strong></td>
                    <?php
                    $pfy = $pfyr;
                    if ($pfcwith == 'Previous_Year') {
                        for ($i = 1; $i <= $pfpwith + 1; $i++) {
                            echo '<td class="text-center"><strong>20000.00</strong></td>';
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
                                    <td data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">
                                    </td>
                                    <td style="width:20%;">
                                        <strong><?php echo $this->lang->line("TB_PL_ACCOUNT_EXPENSES"); ?></strong>
                                    </td>
                                    <td style="width:20%;">&nbsp;</td>
                                    <td>&nbsp;</td>
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
                                        <td data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">
                                            <?php echo $count; ?>
                                        </td>
                                        <td data-title="">
                                            <b><?php echo $TBCat["title"]; ?></b>
                                        </td>
                                        <?php
                                    }
                                    foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {

                                        if (array_key_exists($tbChildId, $TBData[$TBYear]) || array_key_exists($tbChildId, $TBData[$TBPrevYear])) {
                                            if (!$parent) {
                                                ?>
                                            <tr>
                                                <td data-title="">&nbsp;</td>
                                                <td data-title="">&nbsp;</td>
                                                <?php
                                            } else {
                                                $parent = false;
                                            }
                                            ?>
                                            <td data-title="">
                                                <?php
                                                if ($tbChild["title"] == 'Employee costs-Directors salaries') {
                                                    echo 'Directors salaries';
                                                } else {
                                                    echo $tbChild["title"];
                                                }
                                                ?>
                                            </td>
                                            <!--<td class="text-center" data-title="<?php echo $this->lang->line("TB_ROW_TYPE"); ?>">
                                            <?php echo $tbChild["type"]; ?>
                                            </td>-->
                                            <td class="text-right" data-title="<?php echo $this->lang->line("TB_ROW_TOTAL_AMOUNT"); ?>" style="width:20%;">
                                                <a href="<?php echo site_url() . 'ledger_account_profit/' . $this->encrypt->encode($tbChildId); ?>" title="Click to view <?php echo $tbChild["title"]; ?> Ledger Accounts"  >
                                                    <?php
                                                    if (isset($TBData[$TBYear][$tbChildId])) {
                                                        if ($TBCat["type"] == "P/L") {
                                                            $totalCurrYear12 = $totalCurrYear12 + $TBData[$TBYear][$tbChildId]["amount"];
                                                        }
                                                        echo numberFormatSignedprofit($TBData[$TBYear][$tbChildId]["amount"]);
                                                    }
                                                    ?>
                                                </a>
                                            </td>
                                            <td data-title="<?php echo $this->lang->line("TB_ROW_TOTAL_AMOUNT"); ?>"  class="text-right"  style="width:20%;" >
                                                <?php
                                                if (isset($TBData[$TBPrevYear][$tbChildId])) {
                                                    if ($TBCat["type"] == "P/L") {
                                                        $totalPrevYear12 = $totalPrevYear12 + $TBData[$TBPrevYear][$tbChildId]["amount"];
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
                ?>
                <tr rowcols="">
                    <td></td>
                    <td></td>
                    <td  class="text-center"><strong><?php echo $this->lang->line("TB_ROW_TOTAL"); ?></strong></td>
                    <td data-title="<?php echo $TBYear; ?>"  class="text-right"  style="text-align:right;" >
                        <strong>
                            <?php
                            $totalCurrY = $totalCurrY + $totalCurrYear12;
                            echo ( $totalCurrY != 0 ) ? ("(" . numberFormatPL($totalCurrY) . ")") : numberFormatPL("0.00");
                            $totalCurrYear2 = $totalCurrY;
                            ?>
                        </strong>
                    </td>
                    <td data-title="<?php echo $TBPrevYear; ?>"  class="text-right" style="text-align:right;">
                        <strong>
                            <?php
                            $totalPrevY = $totalPrevY + $totalPrevYear12;
                            echo ( $totalPrevY != 0 ) ? ("(" . numberFormatPL($totalPrevY) . ")") : numberFormatPL("0.00");
                            $totalPrevYear2 = $totalPrevY;
                            ?>
                        </strong>
                    </td>
                </tr>  
                <?php
            }
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
    <tfoot>
        <tr>
            <td class="text-center" style="border:none;"></td>
            <td class="text-center" style="border:none;"></td>
            <td  class="text-center" style="border:none;"><strong><?php echo $this->lang->line("TB_ROW_TOTAL_PROFIT"); ?></strong></td>
            <td data-title="<?php echo $TBYear; ?>"  class="text-right"  style="border:none;width:13%;">
                <strong>
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
                </strong>
            </td>
            <td data-title="<?php echo $TBPrevYear; ?>"  class="text-right" style="border:none;width:20%;" >
                <strong>
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
                </strong>


            </td>
        </tr>  
    </tfoot>
</table>

