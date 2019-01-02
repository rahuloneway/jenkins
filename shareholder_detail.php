<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
$config = settings();
$tax_on_dividend = getTaxondividend();

$config['Tax_able_income'] = explode(',', $config['Tax_able_income']);
$fy_date = date('Y') . '-04-30';
$c_date = date('Y-m-d');
if ($c_date > $fy_date) {
    $current_year = date('Y') . ' / ' . (date('Y') + 1);
} else {
    $current_year = (date('Y') - 1) . ' / ' . date('Y');
}
$config['Financial_year'] = explode(',', $config['Financial_year']);

$financial_year = explode('/', $financial_year);
$financial_year = trim($financial_year[0]);
$financial_year = ($financial_year) . " / " . ($financial_year + 1);
$annual_tax = $config['Tax_able_income'][array_search($financial_year, $config['Financial_year'])];
$gross_income = $statistics['gross_salary'] + $statistics['gross_dividend'];
$expFYear = explode('/', $financial_year);
$firstFYeardate = trim($expFYear[0]) . "-04-06";
$secondFYeardate = $expFYear[1] . "-04-06";
if ($c_date >= $firstFYeardate && $c_date >= $secondFYeardate) {
    $layout = 1;
} else {
    $layout = 2;
}
?>
<?php if ($layout == 1) { ?>
    <tr>
        <td data-title="Shareholder Information ">
            <?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_HIGER_RATE'); ?>
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php echo numberFormat($annual_tax); ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            <?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_GROSS_SALARY'); ?>
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php echo numberFormat($statistics['gross_salary']); ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            <?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_GROSS_DIVIDEND'); ?>
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php echo numberFormat($statistics['gross_dividend']); ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            <b class="text-bold-color"><?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_INCOME_DATE'); ?></b>
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php echo numberFormat($gross_income); ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            <b class="text-bold-color"><?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_DIVIDEND_AVAIL'); ?></b>
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php
            // echo 'Annual TAX : '.$annual_tax.'<br/>';
            // echo 'Gross Income : '.$gross_income.'<br/>';
            $tax = (($annual_tax - $gross_income) * 0.9);
            if ($gross_income > 0) {
                if ($tax < 0) {
                    echo '&pound; 0';
                } else {
                    echo '&pound; ' . number_format($tax, 2, '.', ',');
                }
            } else {
                echo '&pound; 0';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information" colspan="2">
            <b class="text-bold-color">
                <?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_ABOVE_THERESHOLD'); ?>
            </b>
        </td>
        <?php
        //$tax = ($annual_tax - $tax);
        //$tax = $tax - $annual_tax;
        $div_above_threshhold = $tax;
        if ($div_above_threshhold < 0) {
            $style = "style='background-color: red; color:#fff;padding: 10px 0px 10px 10px;'";
        } else {
            $style = "";
            $div_above_threshhold = 0;
        }
        ?>
        <td data-title="Current Years"  class="text-right">
            <span <?php echo $style; ?>>
                <?php
                //echo $annual_tax.' - '.$gross_income;
                //echo '&pound; '.negativeNumber(number_format($div_above_threshhold,2,'.',','));
                echo '&pound; ' . number_format(-$div_above_threshhold, 2);
                ?>
            </span>
        </td>
    </tr>
    <tr class="light-grey-bg">
        <td data-title="Shareholder Information ">
            <?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_TAX_IMPLICATION'); ?>
        </td>
        <td colspan="2" class="text-right">
            <?php
            if ($tax <= 0) {
                echo numberFormat($tax * 25 / 100);
            } else {
                echo '&pound; 0';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            <?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_DIV_NEEDED'); ?>
        </td>
        <td>
        </td>
        <td data-title="Current Years">
            <input type="text" name="tax_implication"class="form-control input-type pull-right tax_implication" placeholder=" ">
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            <b style="color:#585656"><?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_DIV_IMPLICATION'); ?></b>
        </td>
        <td data-title="Current Years" colspan="2" style="color:#585656" class="text-right">
            <span class="implications"></span>
        </td>
    </tr>
<?php } else { ?>
    <tr>
        <td data-title="Shareholder Information ">
            <?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_HIGER_RATE'); ?>
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php echo numberFormat($annual_tax); ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            <b class="text-bold-color">
                Tax Free Dividend Allowance 
            </b>
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php echo numberFormat($config['tax_free_dividend_allow']); ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            <?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_GROSS_SALARY'); ?>
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php echo numberFormat($statistics['gross_salary']); ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            <?php echo $this->lang->line('SHAREHOLDER_COLUMN_LABEL_GROSS_DIVIDEND'); ?>
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php echo numberFormat($statistics['gross_dividend']); ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            Total Gross Income to Date
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php echo numberFormat($gross_income); ?>
        </td>
    </tr>

    <tr>
        <td data-title="Shareholder Information" colspan="2">
            <b class="text-bold-color">
                Basic Dividend Tax at 7.5%
            </b>
        </td>

        <td data-title="Current Years" colspan="2" class="text-right">
            <?php
            $grossdividend = $statistics['gross_dividend'];
            if ($grossdividend <= $tax_on_dividend['ZRB']) {
                $basicDividendtax = $grossdividend * ZRB_PERCENTAGE;
            } else if ($grossdividend >= $tax_on_dividend['ZRB'] && $grossdividend <= $tax_on_dividend['BRB']) {
                $BRBgrossdividend = $grossdividend - $tax_on_dividend['ZRB'];
                $BRBgrossdividend = ($BRBgrossdividend * BRB_PERCENTAGE);
                $basicDividendtax = $BRBgrossdividend;
            }else if ($grossdividend >= $tax_on_dividend['BRB'] && $grossdividend <= $tax_on_dividend['HRB']) {
                $HRBgrossdividend = $grossdividend - $tax_on_dividend['BRB'];
                $HRBgrossdividend1 = ($tax_on_dividend['BRB'] * BRB_PERCENTAGE);
                $basicDividendtax=$HRBgrossdividend1;
    
            } else {
                $total_dividend_tax_3 = 0;
                echo numberFormat($total_dividend_tax_3);
            };
            echo numberFormat($basicDividendtax);
            ?>
        </td>
    </tr>
    <tr>
        <td >
            Dividends Available (Before higher Dividend Tax of 32.5%)
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php
             $available =$annual_tax-$gross_income;
             echo numberFormat($available);
             $availablebalance= $available;
            ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            Dividends above Threshold
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
             <?php
            $grossdividend = $statistics['gross_dividend'];
            if ($grossdividend <= $tax_on_dividend['ZRB']) {
                $basicDividendtax = $grossdividend * ZRB_PERCENTAGE;
            } else if ($grossdividend >= $tax_on_dividend['ZRB'] && $grossdividend <= $tax_on_dividend['BRB']) {
                $BRBgrossdividend = $grossdividend - $tax_on_dividend['ZRB'];
                $BRBgrossdividend = ($BRBgrossdividend * BRB_PERCENTAGE);
                $basicDividendtax = $BRBgrossdividend;
            }else if ($grossdividend >= $tax_on_dividend['BRB'] && $grossdividend <= $tax_on_dividend['HRB']) {
                $HRBgrossdividend = $grossdividend - $tax_on_dividend['BRB'];

                $HRBgrossdividend1 = ($tax_on_dividend['BRB'] * BRB_PERCENTAGE);

                $HRBgrossdividend2 = $grossdividend - $tax_on_dividend['BRB'];
                $HRBgrossdividend2 = $HRBgrossdividend2 - $tax_on_dividend['ZRB'];
              
    
            } else {
                $basicDividendtax='0';
            };
            echo numberFormat($HRBgrossdividend2);
            ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            High Dividend Tax Implications at 32.5%
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
             <?php
            $grossdividend = $statistics['gross_dividend'];
            if ($grossdividend <= $tax_on_dividend['ZRB']) {
                $basicDividendtax = $grossdividend * ZRB_PERCENTAGE;
            } else if ($grossdividend >= $tax_on_dividend['ZRB'] && $grossdividend <= $tax_on_dividend['BRB']) {
                $BRBgrossdividend = $grossdividend - $tax_on_dividend['ZRB'];
                $BRBgrossdividend = ($BRBgrossdividend * BRB_PERCENTAGE);
                $basicDividendtax = $BRBgrossdividend;
            }else if ($grossdividend >= $tax_on_dividend['BRB'] && $grossdividend <= $tax_on_dividend['HRB']) {
                $HRBgrossdividend = $grossdividend - $tax_on_dividend['BRB'];

                $HRBgrossdividend1 = ($tax_on_dividend['BRB'] * BRB_PERCENTAGE);

                $HRBgrossdividend2 = $grossdividend - $tax_on_dividend['BRB'];
                $HRBgrossdividend2 = $HRBgrossdividend2 - $tax_on_dividend['ZRB'];
                $HRBgrossdividend2 = ($HRBgrossdividend2 * HRB_PERCENTAGE);
                $basicDividendtax =  $HRBgrossdividend2;
    
            } else {
                $basicDividendtax='0';
            };
            echo numberFormat($basicDividendtax);
            ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            <b class="text-bold-color">
                Total Dividend Tax
            </b>
        </td>
        <td data-title="Current Years" colspan="2" class="text-right">
            <?php
            $grossdividend = $statistics['gross_dividend'];
            if ($grossdividend <= $tax_on_dividend['ZRB']) {
                $total_dividend_tax_1 = $ZRBgrossdividend;
                echo numberFormat($total_dividend_tax_1);
            } else if ($grossdividend >= $tax_on_dividend['ZRB'] && $grossdividend <= $tax_on_dividend['BRB']) {
                $BRBgrossdividend = $grossdividend - $tax_on_dividend['ZRB'];
                $BRBgrossdividend = ($BRBgrossdividend * BRB_PERCENTAGE);
                $total_dividend_tax_2 = $BRBgrossdividend;
                echo numberFormat($total_dividend_tax_2);
            } else if ($grossdividend >= $tax_on_dividend['BRB'] && $grossdividend <= $tax_on_dividend['HRB']) {

                $HRBgrossdividend = $grossdividend - $tax_on_dividend['BRB'];

                $HRBgrossdividend1 = ($tax_on_dividend['BRB'] * BRB_PERCENTAGE);

                $HRBgrossdividend2 = $grossdividend - $tax_on_dividend['BRB'];
                $HRBgrossdividend2 = $HRBgrossdividend2 - $tax_on_dividend['ZRB'];
                $HRBgrossdividend2 = ($HRBgrossdividend2 * HRB_PERCENTAGE);
                $total_dividend_tax_3 = $HRBgrossdividend1 + $HRBgrossdividend2;
                echo numberFormat($total_dividend_tax_3);
            } else {
                $total_dividend_tax_3 = 0;
                echo numberFormat($total_dividend_tax_3);
            }
            ?>
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
         Net Dividend Needed
        </td>
       <td data-title="Current Years" colspan="2" class="text-right">
            <input type="text" placeholder=" " class="form-control input-type pull-right tax_implication" name="tax_implication">
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">
            Dividend Tax Implication 
        </td>
          <td data-title="Current Years" colspan="2" class="text-right">
            
        </td>
    </tr>
    <tr>
        <td data-title="Shareholder Information ">

        </td>
        <td data-title="Current Years" colspan="2" style="color:#585656" class="text-right">
            <span class="implications"></span>
        </td>


    <?php } ?>