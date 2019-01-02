<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$access = clientAccess();
if (categoryName($accountant_detail['EmploymentLevel']) == 'Director') {
    $acc_access = 1;
} else {
    $acc_access = 0;
}
/* Check if accountant  is director */
if ($task == 'meeting'):
    $address = unserialize($item['Address']);
    ?>
    <table width="100%;">
        <thead>
            <tr>
                <th style="text-align:center;" height="60" colspan="2">
        <h1 color="#458ACE" style="font-size:24px"><?php echo ucFirst($CompanyName); ?></h1>
        <p>
            <?php
            if (!empty($Company_details['RegistrationNumber'])) {
                echo 'Resgistration No : ' . $Company_details['RegistrationNumber'] . '<br/>';
            }

            if (!empty($address['REG_AddressOne'])) {
                echo $address['REG_AddressOne'] . '<br/>';
            }

            if (!empty($address['REG_AddressTwo'])) {
                echo $address['REG_AddressTwo'] . '<br/>';
            }

            if (!empty($address['REG_AddressThree'])) {
                echo $address['REG_AddressThree'] . '<br/>';
            }

            if (!empty($address['REG_PostalCode'])) {
                echo $address['REG_PostalCode'] . '<br/>';
            }

            if (!empty($Company_details['REG_PhoneNo'])) {
                //echo '<img src="'.site_url().'assets/images/phone.png" height="10"width="10" style="margin-top:10px;"/>&nbsp;'.$Company_details['REG_PhoneNo'];
            }
            ?>
        </p>
        <br/><br/>
    </th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td style="text-align:center;" height="30" colspan="2">
                <?php echo $this->lang->line('DIVIDEND_PDF_TEXT_ONE'); ?><?php echo date('jS F Y', strtotime($item['PaidOn'])); ?>.
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p>
                <div style="float:left;margin-left:-50px !important"><b><?php echo $this->lang->line('DIVIDEND_PDF_TEXT_TWO'); ?></b></div>
                <?php
                foreach ($Directors as $key => $val) {
                    echo '<br/>' . $val;
                }
                ?>
                <br/><br/><br/>
                <b><?php echo $this->lang->line('DIVIDEND_PDF_TEXT_THREE'); ?></b><br/>				
                <?php echo $this->lang->line('DIVIDEND_PDF_TEXT_FOUR'); ?>
                <br/><br/><br/>
                <b><?php echo $this->lang->line('DIVIDEND_PDF_TEXT_FIVE'); ?></b><br/>				
                <?php echo sprintf($this->lang->line('DIVIDEND_PDF_TEXT_SIX'), date('jS F Y', strtotime($YearEndDate)), (($item['TotalShares'] == 0) ? 0 : number_format($item['NetAmount'] / $item['TotalShares'], 2, '.', ',')), date('jS F Y', strtotime($item['PaidOn']))); ?>
                </p>
                <br/>
                <br/>
                <br/>
                <br/>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td>
                <br/>
                <?php
                echo '(Director) &nbsp;.......................<br/><br/><br/><br/>';
                ?>

            </td>
            <td style="text-align: right;">
                <?php echo $this->lang->line('DIVIDEND_PDF_TEXT_SEVEN'); ?> <?php echo date('jS F Y', strtotime($item['PaidOn'])); ?>
            </td>
        </tr>
    </tfoot>
    </table>
<?php elseif ($task == 'certificate'): ?>
    <table width="100%" cellpadding="10">
        <thead>
            <tr>
                <th colspan="4" style="text-align:center;">
        <h1 color="#458ACE"><?php echo ucfirst($CompanyName); ?></h1>
        <p>
            <?php
            $address = unserialize($item['Address']);
            if (!empty($address['REG_AddressOne'])) {
                echo $address['REG_AddressOne'] . '<br/>';
            }

            if (!empty($address['REG_AddressTwo'])) {
                echo $address['REG_AddressTwo'] . '<br/>';
            }

            if (!empty($address['REG_AddressThree'])) {
                echo $address['REG_AddressThree'] . '<br/>';
            }

            if (!empty($address['REG_PostalCode'])) {
                echo $address['REG_PostalCode'] . '<br/>';
            }
            ?>
            <?php
            if (!empty($Company_details['REG_PhoneNo'])) {
                //echo '<img src="'.site_url().'assets/images/phone.png" height="10"width="10" style="margin-top:10px;"/>&nbsp;'.$Company_details['REG_PhoneNo'];
            }
            ?>
        </p>
        <br/><br/>
        <?php echo $this->lang->line('DIVIDEND_PDF_TEXT_EIGHT'); ?> <?php echo (empty($YearEndDate)) ? '' : date('jS F Y', strtotime($YearEndDate)); ?>
        <br/>
        <?php echo sprintf($this->lang->line('DIVIDEND_PDF_TEXT_NINE'), date('jS F Y', strtotime($item['PaidOn'])), (($item['TotalShares'] == 0) ? 0 : number_format($item['NetAmount'] / $item['TotalShares'], 2, '.', ','))); ?>
        <br/><br/><br/><br/>			
    </th>
    </tr>
    </thead>
    <tbody>
        <tr bgcolor="#458ACE" color="#fff">
            <td  style="text-align:center;">
                <b><?php echo $this->lang->line('DIVIDEND_PDF_TEXT_SHARE'); ?></b>
            </td>
            <?php if(!empty($item['TaxAmount'])){?>
            <td  style="text-align:center;">
                <b><?php echo $this->lang->line('DIVIDEND_PDF_TEXT_CREDIT'); ?></b>
            </td>
            <?php }else{?>
            <td  style="text-align:center;"></td>
            <?php }?>
            <td  style="text-align:center;">
                <b><?php echo $this->lang->line('DIVIDEND_PDF_TEXT_GROSS_DIV'); ?></b>
            </td>
            <td  style="text-align:center;">
                <b><?php echo $this->lang->line('DIVIDEND_PDF_TEXT_NET_DIV'); ?></b>
            </td>
        </tr>
        <tr bgcolor="#E1E1E1">
            <td style="text-align:center;">
                <?php echo $item['TotalShares']; ?>
            </td>
            <?php if(!empty($item['TaxAmount'])){?>
            <td style="text-align:center;">
                £ <?php echo number_format($item['TaxAmount'], 2, '.', ','); ?>
            </td>
            <?php }else{?>
            <td style="text-align:center;">
                
            </td>
            <?php }?>
            <td style="text-align:center;">
                £ <?php echo number_format($item['GrossAmount'], 2, '.', ','); ?>
            </td>
            <td style="text-align:center;">
                £ <?php echo number_format($item['NetAmount'], 2, '.', ','); ?>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4"  style="text-align:center;">
                <br/><br/><br/>
                <?php echo '<br/><br/>' . $item['SharerName'] . '<br/>'; ?>				
                <?php
            /* if (!empty($item['Params']['AddressOne'])) {
                  echo $item['Params']['AddressOne'] . "<br/>";
                  }
                  if (!empty($item['Params']['AddressTwo'])) {
                  echo $item['Params']['AddressTwo'] . "<br/>";
                  }
                  if (!empty($item['Params']['AddressThree'])) {
                  echo $item['Params']['AddressThree'] . "<br/>";
                  }
                  if (!empty($item['Params']['PostalCode'])) {
                  echo $item['Params']['PostalCode'] . "<br/>";
                  }
             */
                
                $shareholder_address = unserialize($item['shareholder_address']);
                 if (!empty($shareholder_address['AddressOne'])) {
                    echo $shareholder_address['AddressOne'] . '<br/>';
                }

                if (!empty($shareholder_address['AddressTwo'])) {
                    echo $shareholder_address['AddressTwo'] . '<br/>';
                }

                if (!empty($shareholder_address['AddressThree'])) {
                    echo $shareholder_address['AddressThree'] . '<br/>';
                }

                if (!empty($shareholder_address['PostalCode'])) {
                    echo $shareholder_address['PostalCode'] . '<br/>';
                }
                ?>
                <br/><br/><br/>
            </td>
        </tr>
        <tr>
            <td width="215px"></td>
            <td colspan="2">
                <?php
                echo '<br/><br/>(Director) &nbsp;&nbsp;...........................<br/><br/><br/><br/>';
                if ($access && $acc_access) {
                    echo '<b style="color:#760E83;">CERTIFIED TRUE COPY</b><br/><br/>';
                    if ($include_signature == 'YES_SIG') {
                        if (file_exists($accountant_detail['ImageLink'])) {
                            echo '<img src="' . site_url() . $accountant_detail['ImageLink'] . '" width="100px" height="40px"/>';
                        }
                        echo '<b style="color:#760E83;line-height:10px;">' . $accountant_detail['DigitalSignature'] . '</b>';
                    } else {
                        echo '<b style="color:#760E83;line-height:10px;">' . $accountant_detail['DigitalSignature'] . '</b>';
                    }
                }
                ?>
            </td>
            <td></td>
        </tr>
    </tfoot>
    </table>
<?php endif; ?>