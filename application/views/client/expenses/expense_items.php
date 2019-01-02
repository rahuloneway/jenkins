<?php
$user = $this->session->userdata('user');
//echo "<pre>";print_r($user); echo "</pre>";
if (count($item) <= 0 || count($item['ExpenseItems']) <= 0):
    ?>
    <tr>
        <td class="sno">
            1
        </td>
        <td width="200">
            <input type="text" class="form-control exDatepicker expense-Date" name="ExpenseItemDate[]" readonly>
        </td>
		<td style="width:17%;" class="exParentCattd">
			<?php $parentcat = getCategoryParent('GEN'); ?>
			<select id="ExpenseCategory[]" class="form-control ExpenseCategory exParentCat" name="ExpenseParentCategory[]">
				<option selected="selected" value="0">--Select Category--</option>
				<?php foreach($parentcat as $parentcat){ ?>             
				<option value="<?php echo $parentcat->id;?>"><?php  echo $parentcat->title;?></option>
				<?php } ?>
			</select>
		</td>
		<td class="exChildCattd sm-width-box">
			<select id="Category[]" class="form-control category sm-width-box tdtab" name="Category[]">
				<option selected="selected" value="0">--Select Category--</option>
			</select>
		</td>
        <td class="form-group has-feedback">
            <input type="text" class="form-control" name="Description[]">
        </td>
        <td width="150px"<?php //if ($user['VAT_TYPE'] != 'stand'){ echo 'class="expensestab"';  } ?>  >
            <input type="text" class="sm-width validNumber expenseAmount form-control input_100percent" name="ExpenseItemAmount[]" value="">
        </td>
        <?php //if ($user['VAT_TYPE'] == 'stand'): ?>
            <!--td class="expensestab"-->
			<td width="100px">
				<input type="text" class=" validNumber vatPresentation form-control input_100percent" name="vatPresentation[]" value="20">
            </td>
			<td width="100px">
				<input type="hidden" name="isVatApplicable" id="isVatApplicable" value="0">
                <input type="text" class="sm-width validNumber vatAmount form-control input_100percent" name="VatAmount[]" readonly="readonly">
            </td>
        <?php //endif; ?>
		 <!--td width="100px"<?php //if ($user['VAT_TYPE'] != 'stand'){ echo 'class="expensestab"';  } ?>  >
            <input type="text" class="sm-width validNumber  form-control input_100percent" name="ExpenseItemAmount[]">
        </td-->
        <td width="50px" class="text-right">
            <a class='btn removeExpenseItem hide'>
                <i class='fa fa-times'></i>
            </a>
        </td>
    </tr>
<?php else: ?>
    <?php foreach ($item['ExpenseItems'] as $key => $val): ?>
	<?php //echo "<pre>"; print_r($val);echo "<pre>"; ?>
        <tr>
            <td class="sno">
                <?php echo ($key + 1); ?>
            </td>
            <td width="100px">
                <input type="text" class="form-control exDatepicker input_50percent" name="ExpenseItemDate[]" value="<?php echo cDate($val->ItemDate); ?>">
                <input type="hidden" name="expense_item_id[]" value="<?php echo $this->encrypt->encode($val->ID); ?>"/>
            </td>
            <td style="width:17%;" class="exParentCattd">
				<?php $parentcat = getCategoryParent('GEN'); ?>
				<select id="ExpenseCategory[]" class="form-control ExpenseCategory exParentCat" name="ExpenseParentCategory[]">
					<option selected="selected" value="0">--Select Category--</option>
					<?php foreach($parentcat as $parentcat){ ?>             
					<option value="<?php echo $parentcat->id;?>"><?php  echo $parentcat->title;?></option>
					<?php } ?>
				</select>
			</td>
			<td class="exChildCattd sm-width-box">
				<select id="Category[]" class="form-control category sm-width-box tdtab" name="Category[]">
					<option selected="selected" value="<?php //echo $val->Category ?>"><?php echo getCategoryName($val->Category) ?></option>
				</select>
			</td>
            <td class="form-group has-feedback">
                <input type="text" class="form-control" name="Description[]" value="<?php echo $val->Description; ?>">
            </td>
            <td width="100px"  <?php //if ($user['VAT_TYPE'] != 'stand'){ echo 'class="expensestab"';} ?>>
                <input type="text" class="sm-width validNumber expenseAmount form-control input_100percent" name="ExpenseItemAmount[]" value="<?php echo $val->Amount; ?>">
            </td>			
            <?php //if ($user['VAT_TYPE'] == 'stand'): ?>
				<!--td class="expensestab"-->
				<td width="100px">
					<input type="text" class=" validNumber vatPresentation form-control input_100percent" name="vatPresentation[]" value="20">
				</td>
				<td width="100px">
					<input type="hidden" name="isVatApplicable" id="isVatApplicable" value="0">
                    <input type="text" class="sm-width validNumber vatAmount form-control input_100percent" name="VatAmount[]" value="<?php echo $val->VATAmount; ?>">
                </td>
            <?php //endif; ?>
			<!--td width="100px" <?php //if ($user['VAT_TYPE'] != 'stand'){ echo 'class="expensestab"';} ?>>
                <input type="text" class="sm-width validNumber expenseAmount form-control input_100percent" name="ExpenseItemAmount[]" value="<?php echo $val->Amount; ?>">
            </td-->
            <td width="100px">
                <a class='btn removeExpenseItem' id="<?php echo $val->ID ?>">
                    <i class='fa fa-times'></i>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
<input type="hidden"  id="delexpItem" name="delexpItem" class="delexpItem"/>