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
            <th width="5%">
                <a href="#" class="color"><?php echo $this->lang->line("TB_ROW_SRNO"); ?></a>
            </th>
            <th>
                <a href="#" class="color"></a>
            </th>
            <th>
                <a href="#" class="color"></a>
            </th>
            <th class="text-center">
                <a href="#" class="color"><?php echo $this->lang->line("TB_ROW_TYPE"); ?></a>
            </th>
            <th class="text-center" >
                <a href="#" class="color"><?php echo $TBYear; ?></a>
            </th>
            <th class="text-center" >
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
                $parent = true;
                if ($PL == 0 && $TBCat["type"] == "P/L") {
                    ?>
                    <tr>
                        <td data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">

                        </td>
                        <td data-title="">
                            <strong><?php echo $this->lang->line("TB_PL_ACCOUNT"); ?></strong>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php
                    $PL = 1;
                } else if ($BS == 0 && $TBCat["type"] == "B/S") {
                    for ($k = 0; $k <= 1; $k++) {
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td data-title="<?php echo $this->lang->line("TB_ROW_SRNO"); ?>">

                        </td>
                        <td data-title="">
                            <strong><?php echo $this->lang->line("TB_BS_ACCOUNT"); ?></strong>
                        </td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
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
                                // pr($tbChild);
                                // prd($TBData[$TBYear][$tbChildId]);
								//echo "<pre>";print_r($tbChild); echo "</prE>";
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
									<?php if($tbChild['CategoryType'] == 'CUSTOMERS') { 
										echo getCustomerNameTBListing($tbChild["title"]); 
									} else if($tbChild['CategoryType'] == 'SUPPLIERS'){ 
										echo getSupplierNameTBListing($tbChild["title"]);  
									}else if($tbChild['CategoryType'] == 'SHAREHOLDERS'){ 
										echo getShareholderNameTBListing($tbChild["title"]); 
									}else if($tbChild['CategoryType'] == 'EMPLOYEES'){ 
										echo getEmployeeNameTBListing($tbChild["title"]); 
									}else if($tbChild['CategoryType'] == 'BANK'){ 
										echo getBankNameTBListing($tbChild["title"]); 
									}else{
										echo $tbChild["title"]; 
									}?>
                                </td>
                                <td class="text-center" data-title="<?php echo $this->lang->line("TB_ROW_TYPE"); ?>">
                                    <?php echo $tbChild["type"]; ?>
                                </td>
                                <td class="text-right" data-title="<?php echo $this->lang->line("TB_ROW_TOTAL_AMOUNT"); ?>">
                                    <a href="<?php echo site_url() . 'ledger_account/' . $this->encrypt->encode($tbChildId); ?>" title="Click to view <?php echo $tbChild["title"]; ?> Ledger Accounts"  >
                                        <?php
                                        // pr( $tbChild );
                                        if (isset($TBData[$TBYear][$tbChildId])) {
                                            if ($TBCat["type"] == "P/L") {
                                                $totalCurrYear = $totalCurrYear + $TBData[$TBYear][$tbChildId]["amount"];
                                            } else if ($TBCat["type"] == "B/S") {
                                                $totalCurrYear = $totalCurrYear + $TBData[$TBYear][$tbChildId]["amount"];
                                            }
                                            echo numberFormatSigned($TBData[$TBYear][$tbChildId]["amount"]);
                                        }
                                        ?>
                                    </a>
                                </td>
                                <td data-title="<?php echo $this->lang->line("TB_ROW_TOTAL_AMOUNT"); ?>"  class="text-right"  >
                                    <?php
                                    if (isset($TBData[$TBPrevYear][$tbChildId])) {
                                        if ($TBCat["type"] == "P/L") {
                                            $totalPrevYear = $totalPrevYear + $TBData[$TBPrevYear][$tbChildId]["amount"];
                                        } else if ($TBCat["type"] == "B/S") {
                                            $totalPrevYear = $totalPrevYear + $TBData[$TBPrevYear][$tbChildId]["amount"];
                                        }
                                        echo numberFormatSigned($TBData[$TBPrevYear][$tbChildId]["amount"]);
                                    }
                                    ?>
                                </td>
                            </tr>	
                            <?php
                        }
                    }
                }
            }
            // if( $totalCurrYear >0 || $totalPrevYear > 0){ 
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td  class="text-center"><strong><?php echo $this->lang->line("TB_ROW_TOTAL"); ?></strong></td>
                <td data-title="<?php echo $TBYear; ?>"  class="text-right"  >
                    <strong>
                        <?php echo ( $totalCurrYear != 0 ) ? numberFormatSigned($totalCurrYear) : numberFormatSigned("0.00"); ?>
                    </strong>
                </td>
                <td data-title="<?php echo $TBPrevYear; ?>"  class="text-right"  >
                    <strong>
                        <?php echo ( $totalPrevYear != 0 ) ? numberFormatSigned($totalPrevYear) : numberFormatSigned("0.00"); ?>
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
