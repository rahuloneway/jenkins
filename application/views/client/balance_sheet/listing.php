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
$this->session->set_userdata("pfttotalCurrYear", $pfttotalCurrYear);
$this->session->set_userdata("pfttotalPrevYear", $pfttotalPrevYear);
?>

<table>
    <thead>
        <tr class="salary-table">
            <th style="width:10%">
                <a href="#" class="color"><?php echo $this->lang->line("TB_ROW_SRNO"); ?></a>
            </th>
            <th style="width:20%;">
            </th>
            <th style="width:20%;">
                <a href="#" class="color"></a>
            </th>
            <th class="text-center" style="width:20%;" >
                <a href="#" class="color"><?php echo $TBYear; ?></a>
            </th>
            <th class="text-center"  style="width:20%;">
                <a href="#" class="color"><?php echo $TBPrevYear; ?></a>
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
                            <tr style="width:10%;">
                                <td data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">

                                </td>
                                <td data-title=" " style="width:20%;">
                                    <strong><?php echo $this->lang->line("TB_BS_ACCOUNT_ASSET"); ?></strong>
                                </td>
                                <td style="width:30%;">&nbsp;</td>
                                <td style="width:20%;"><center>&pound;</center></td>
                    <td style="width:20%;"><center>&pound;</center></td>
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
                            <td style="width:10%;" data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">
                                <?php echo $count; ?>
                            </td>
                            <td data-title="" style="width:10%">
                                <b><?php echo $TBCat["title"]; ?></b>
                            </td>
                            <?php
                        }
                        foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {
                            if ($tbChild['cat_type'] == 3) {
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
                                    <td data-title="" style="width:30%;"> 
                                        <?php echo $tbChild["title"]; ?>
                                    </td>
                                    <td style="width:20%;" class="text-right" data-title="<?php echo $this->lang->line("TB_ROW_TOTAL_AMOUNT"); ?>">
                                        <a href="<?php echo site_url() . 'ledger_account_balance_sheet/' . $this->encrypt->encode($tbChildId); ?>" title="Click to view <?php echo $tbChild["title"]; ?> Ledger Accounts"  >
                                            <?php
                                            // pr( $tbChild );
                                            if (isset($TBData[$TBYear][$tbChildId])) {
                                                if ($TBCat["type"] == "B/S") {
                                                    $totalCurrYear = $totalCurrYear + $TBData[$TBYear][$tbChildId]["amount"];
                                                }
                                                echo numberFormatSignedprofit($TBData[$TBYear][$tbChildId]["amount"]);
                                            }
                                            ?>
                                        </a>
                                    </td>
                                    <td style="width:20%;" data-title="<?php echo $this->lang->line("TB_ROW_TOTAL_AMOUNT"); ?>"  class="text-right"  >
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
    // if( $totalCurrYear >0 || $totalPrevYear > 0){ 
    ?>
    <tr>
        <td></td>
        <td></td>
        <td  class="text-center"><strong><?php echo $this->lang->line("TB_ROW_TOTAL_ASSETS"); ?></strong></td>
        <td data-title="<?php echo $TBYear; ?>"  class="text-right">
            <strong>
                <?php echo ( $totalCurrYear != 0 ) ? numberFormatPL($totalCurrYear) : numberFormatPL("0.00"); ?>
            </strong>
        </td>
        <td data-title="<?php echo $TBPrevYear; ?>"  class="text-right"  >
            <strong>
                <?php echo ( $totalPrevYear != 0 ) ? numberFormatPL($totalPrevYear) : numberFormatPL("0.00"); ?>
            </strong>
        </td>
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
<table>
    <thead>
        <tr style="width:10%;">
            <td data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">

            </td>
            <td data-title=" " style="width:20%;">
                <strong><?php echo $this->lang->line("TB_BS_ACCOUNT_LIABILITY"); ?></strong>
            </td>
            <td style="width:30%;">&nbsp;</td>
            <td style="width:20%;"></td>
            <td style="width:20%;"></td>
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
                                    <td style="width:10%;" data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">
                                        <?php echo $count; ?>
                                    </td>
                                    <td data-title="" style="width:10%">
                                        <b><?php echo $TBCat["title"]; ?></b>
                                    </td>
                                    <?php
                                }

                                foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {
                                    if ($tbChild['cat_type'] == 4) {
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
                                            <td data-title="" style="width:30%;"> 
                                                <?php echo $tbChild["title"]; ?>
                                            </td>
                                            <td style="width:20%;" class="text-right" data-title="<?php echo $this->lang->line("TB_ROW_TOTAL_AMOUNT"); ?>">
                                                <a href="<?php echo site_url() . 'ledger_account_balance_sheet/' . $this->encrypt->encode($tbChildId); ?>" title="Click to view <?php echo $tbChild["title"]; ?> Ledger Accounts"  >
                                                    <?php
                                                    // pr( $tbChild );
                                                    if (isset($TBData[$TBYear][$tbChildId])) {
                                                        if ($TBCat["type"] == "B/S") {
                                                            $totalCurrYear1 = $totalCurrYear1 + $TBData[$TBYear][$tbChildId]["amount"];
                                                        }

                                                        echo numberFormatPL($TBData[$TBYear][$tbChildId]["amount"]);
                                                    }
                                                    ?>
                                                </a>
                                            </td>
                                            <td style="width:20%;" data-title="<?php echo $this->lang->line("TB_ROW_TOTAL_AMOUNT"); ?>"  class="text-right"  >
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
            // if( $totalCurrYear >0 || $totalPrevYear > 0){ 
            ?>
            <tr>
                <td><?php echo $count + 1; ?></td>
                <td><b>Net Profit</b></td>
                <td  class="text-left">Profit for the year</td>
                <td   class="text-right">
                    <strong>
                        <?php
                      
                        if ($pfttotalCurrYear > 0) {

                            echo "(" . numberFormatPL($pfttotalCurrYear) . ")";
                        } else {
                            echo numberFormatPL($pfttotalCurrYear);
                        }
                        ?>
                    </strong>
                </td>
                <td  class="text-right"  >
                    <strong>
                        <?php
                        if ($pfttotalPrevYear > 0) {
                            echo "(" . numberFormatPL($pfttotalPrevYear) . ")";
                        } else {
                            echo numberFormatPL($pfttotalPrevYear);
                        }
                        ?>
                    </strong>
                </td>
            </tr>                          

            <tr>
                <td></td>
                <td></td>
                <td  class="text-center"><strong><?php echo $this->lang->line("TB_ROW_TOTAL_LIABILITES"); ?></strong></td>
                <td data-title="<?php echo $TBYear; ?>"  class="text-right"  >
                    <strong>
                        <?php
                        $liabilitycurrYear = $pfttotalCurrYear + $totalCurrYear1;
                        if ($liabilitycurrYear != 0) {
                            echo numberFormatPL($liabilitycurrYear);
                        } else {
                            echo numberFormatPL("0.00");
                        }
                        ?>
                    </strong>
                </td>
                <td data-title="<?php echo $TBPrevYear; ?>"  class="text-right"  >
                    <strong>
                        <?php
                        $liabilityPrevYear = $pfttotalPrevYear + $totalPrevYear1;
                        if ($liabilityPrevYear != 0) {
                            echo numberFormatPL($liabilityPrevYear);
                        } else {
                            echo numberFormatPL("0.00");
                        }
                        ?>
                    </strong>
                </td>
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