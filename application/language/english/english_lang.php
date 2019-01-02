<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

		/**
		*		Common constants that will be used on every page
		*/
		$lang['BUTTON_NEW']                                                        = "New";
		$lang['BUTTON_CANCEL']                                                     = "Cancel";
		$lang['BUTTON_SAVE']                                                       = "Save";
		$lang['BUTTON_DELETE']                                                     = "Delete";
		$lang['BUTTON_EDIT']                                                       = "Edit";
		$lang['BUTTON_PUBLISH']                                                    = "Publish";
		$lang['BUTTON_UNPUBLISH']                                                  = "Unpublish";
		$lang['BUTTON_LOGOUT']                                                     = "Logout";
		$lang['BUTTON_MY_ACCOUNT']                                                 = "My Account";
		$lang['BUTTON_SEARCH']                                                     = "Search";
		$lang['BUTTON_RESET']                                                      = "Reset";
		$lang['BUTTON_CREATE']                                                     = "Create";
		$lang['BUTTON_UPDATE']                                                     = "Update";
		$lang['BUTTON_COPY']                                                       = "Copy";
		$lang['BUTTON_CLOSE']                                                      = "Close";
		$lang['BUTTON_SEND']                                                       = "Send";
		$lang['TABLE_COLUMN_ACTION']                                               = "Action";
		$lang['TABLE_COLUMN_ADDED_BY']                                             = "Added by";
		$lang['TABLE_COLUMN_ADDED_ON']                                             = "Added on";
		$lang['BUTTON_ADD_ITEM']                                                   = "Add Item";
		$lang['BUTTON_CREATE_AND_FINISH']                                          = "Create & Finish";
		$lang['BUTTON_SAVE_AND_FINISH']                                            = "Save & Finish";
		$lang['BUTTON_ADD_NEW_ROW']                                            = "Add new row";
		$lang['BUTTON_UPLOAD']                                                     = "Upload";
		$lang['BUTTON_DRAFT']                                                      = "Save as Draft";
		$lang['BUTTON_WARNING']                                                    = "Warning!";


		/***************************** Login Page Constants *****************************/
		$lang['LOGIN_FORM_TITLE']                                                  = "Welcome to Cashmann";
		$lang['LOGIN_FORM_LABEL_FORGET']                                           = "Forgot Password?";
		$lang['LOGIN_LABEL_USERNAME']                                              = "Username";
		$lang['LOGIN_LABEL_PASSWORD']                                              = "Password";
		//$lang['LOGIN_USER_NOT_EXISTS']                                             = "&nbsp; There is no account associated with %s. Please check your details!";
		$lang['LOGIN_USER_NOT_EXISTS']											= "User disable please contact with your admin for details";
		$lang['LOGIN_USER_PASSWORD_WRONG']                                         = "&nbsp; Your password is wrong. Please enter the correct password";
		$lang['LOGGED_IN']                                                         = "Logged In : ";
		$lang['UNAUTHORIZED_ACCES']                                                = "You are not authorized to view this page!";
		$lang['EMPTY_USERID_ERROR']                                                = "Please enter your login id!";
		$lang['EMPTY_PASSWORD_ERROR']                                              = "Please enter your password!";
		
		$lang['CHOOSE_COMPANYLOGIN']                                               = "Choose Company";
		$lang['CLIENT_PRIVILGE_SUCCESS']                                               = "Client privileges updated successfully.";
		$lang['HAPPENS_WRONG']                                               = "Something happens wrong. Please try again.";
		/***************************** Others Page Constants *****************************/
		$lang['RECOVERY_EMAIL_TITLE']                                              = "Reset password";
		$lang['RECOVERY_EMAIL_DESCRIPTION']                                        = "Enter your registered email address and we will send a recovery link to reset your password.";
		$lang['RECOVERY_EMAIL_VALID_ERROR']                                        = "Please, enter a valid email address!";
		$lang['RECOVERY_EMAIL_WRONG_ERROR']                                        = "Please, enter a valid email address!";
		$lang['RECOVERY_EMAIL_EMPTY_ERROR']                                        = "Email field cannot be left empty, please enter your email address.";
		$lang['RECOVERY_EMAIL_REGISTERED_LABEL']                                   = "Email";
		$lang['RECOVERY_EMAIL_SUBJECT']                                            = "Recover password";
		$lang['RECOVERY_EMAIL_MESSAGE']                                            = "Hello %s,<br/><br/>You have requested to reset password for WISe account %s.<br/><br/>To reset your password please click the following link:<br/><br/> %s <br/><br/>If you did not make this request, simply ignore this email.<br/><br/><br/>Best,<br/>WISe Team.";

		$lang['RESET_CLIENT_PASSWORD_TITLE']                                       =	"Set Password";
		$lang['SET_PASSWORD_SUCCESS']                                              =	"Your new password has been created. You can login now.";



		$lang['RECOVERY_EMAIL_SUCCESS']                                            = "Your request has been accepted and a recovery link has been sent to %s";
		$lang['RECOVERY_EMAIL_FAILURE']                                            = "There is no account associated with %s, please enter the registered email!";



		$lang['RESET_PASSWORD_LABEL_NEW']                                          = "New password";
		$lang['RESET_PASSWORD_LABEL_CONFIRM']                                      = "Confirm Password";
		$lang['RESET_PASSWORD_LABEL_QUESTION']                                     = "Choose Security question";
		$lang['RESET_PASSWORD_LABEL_ANSWER']                                       = "Answer";


		$lang['RESET_ERROR_EMPTY_NEW_PASSWORD']                                    = "Please, enter the new password.";
		$lang['RESET_ERROR_EMPTY_CONFIRM_PASSWORD']                                = "Please, confirm your password.";
		$lang['RESET_ERROR_WRONG_CONFIRM_PASSWORD']                                = "Your confirm password did not match with new password";
		$lang['RESET_ERROR_EMPTY_QUESTION']                                        = "Please, select your security question.";
		$lang['RESET_ERROR_EMPTY_ANSWER']                                          = "Please, enter the answer.";
		$lang['RESET_QUESTION_FAILURE']                                            = "Your Question/Answer did not match, please check your question/answer.";
		$lang['RESET_PASSWORD_SUCCESS']                                            = "Your password has been changed successfully. You can login now";



		/***************************** Administrator Dashboard Constants *****************************/

		/* New user Form Constants */
		$lang['MENU_NAME_DASHBOARD']                                               = "Back to Dashboard";
		$lang['MENU_NAME_USERS']                                                   = "Users";
		$lang['MENU_NAME_COMPANIES']                                               = "Companies";
		$lang['MENU_NAME_BANK_ACCOUNTS']                                           = "Bank Accounts";
		$lang['MENU_NAME_INVOICES']                                                = "Invoices";
		$lang['MENU_NAME_EXPENSES']                                                = "Expenses";
		$lang['MENU_NAME_REPORTS']                                                 = "Reports";

		/* New user Form Constants */
		$lang['LABEL_FIRST_NAME']                                                  = "First name";
		$lang['LABEL_LAST_NAME']                                                   = "Last name";
		$lang['LABEL_EMAIL']                                                       = "Email";
		$lang['LABEL_USERNAME']                                                    = "Username";
		$lang['LABEL_USERTYPE']                                                    = "Select user type";
		$lang['LABEL_SECURITY_QUESTION']                                           = "Select security question";
		$lang['LABEL_QUESTION_ANSWER']                                             = "Answer";
		$lang['LABEL_ADDRESS']                                                     = "Address";
		$lang['LABEL_CITY']                                                        = "City";
		$lang['LABEL_STATE']                                                       = "State";
		$lang['LABEL_COUNTRY']                                                     = "Country";
		$lang['LABEL_ZIPCODE']                                                     = "Zipcode";
		$lang['LABEL_CONTACT_NUMBER']                                              = "Contact No.";
		$lang['LABEL_STATUS']                                                      = "Status";

		/***************************** Client Dashboard Constants *****************************/
		$lang['CLIENT_DASHBOARD_MENU_DASHBOARD']                                   = "Dashboard";
		$lang['CLIENT_DASHBOARD_MENU_INVOICES']                                    = "Invoices";
		$lang['CLIENT_DASHBOARD_MENU_EXPENSES']                                    = "Expenses";
		$lang['CLIENT_DASHBOARD_MENU_DIVIDENTS']                                   = "Dividends";
		$lang['CLIENT_DASHBOARD_MENU_DOCUMENTS']                                   = "My Documents";
		$lang['CLIENT_DASHBOARD_MENU_CALCULATION']                                 = "Potential Tax Calculation";
		$lang['CLIENT_BUTTON_ADD_INVOICE']                                         = "Add Invoice";
		$lang['CLIENT_BUTTON_ADD_EXPENSE']                                         = "Add Expense";
		$lang['CLIENT_BUTTON_ADD_STATEMENT']                                       = "Upload Bank Statements";
		$lang['BANK_STATEMENT_UPLOADED']                                           = "A bank statement was uploaded";
                $lang['CLIENT_BUTTON_ADD_VOUCHER']                                         = "Create Dividend Voucher";
                $lang['BANK_TABLE_COLUMN_TOTAL']                                         = "Total";

                $lang['CLIENT_BUTTON_UPLOAD_EXPENSE']                                      = "Upload Expenses";
		$lang['CLIENT_BUTTON_PAID']                                                = "Mark as paid";
		$lang['CLIENT_CLOSE_BUTTON']                                               = "Close";
		$lang['CLIENT_NO_RECORD_FOUND']                                            = "No record found";
		$lang['CLIENT_CHANGE_STATUS']											= "Are you sure, you want to change the status of client";

		/***************************** Invoice Area Constants *****************************/
		$lang['CLIENT_INVOICE_NEW_INVOICE_TITLE']                                  = "New Invoice";
		$lang['CLIENT_INVOICE_CREATE_INVOICE_TITLE']                               = "Created Record";
		$lang['CLIENT_INVOICE_EDIT_INVOICE_TITLE']                                 = "Edit Invoice";
		$lang['CLIENT_INVOICE_PAID_INVOICE_TITLE']                                 = "Paid Invoice";

		$lang['CLIENT_INVOICE_ERROR_EMPTY_ITEM']                                   = "Blank record can not save/created, please add at least one invoice item!";
		$lang['CLIENT_INVOICE_ERROR_EMPTY_NAME']                                   = "Please enter the customer name!";
		$lang['CLIENT_INVOICE_ERROR_SELECT_NAME']                                  = "Please select the customer name!";
		$lang['CLIENT_INVOICE_ERROR_INVOICE_DATE']                                 = "Please select the date first!";
		$lang['CLIENT_INVOICE_EMPTY_ERROR']                                        = "Empty record can not be created, please enter some details!";
		$lang['CLIENT_INVOICE_DESCRIPTION_ERROR']                                  = "Please enter the description for the {s} item!";
		$lang['CLIENT_INVOICE_UNIT_ERROR']                                         = "Please enter the unit price for the {s} item!";
		$lang['CLIENT_INVOICE_UNIT_ERROR']                                         = "Please enter the unit price for the {s} item!";
		$lang['CLIENT_INVOICE_QUANTITY_ERROR']                                     = "Please enter the days/hours value for the {s} item!";
		$lang['CLIENT_INVOICE_UNITPRICE_ERROR']                                    = "Please enter the Daily/Hourly Rate value for the {s} item!";
		$lang['CLIENT_INVOICE_DELETE_ERROR']                                       = "Are you sure, you want to delete this record?";
		$lang['CLIENT_INVOICE_COPY_ERROR']                                         = "Are you sure, you want to copy this record?";
		$lang['CLIENT_INVOICE_PAID_ERROR']                                         = "Are you sure, you want to mark it as paid?";
		$lang['CLIENT_INVOICE_QUANTITY_UNIT_ERROR']                                = "Please enter the quantity and unit price value for the {s} item!";
		$lang['CLIENT_INVOICE_CREATE_SUCCESS']                                     = "The record %s has been created successfully.";
		$lang['CLIENT_INVOICE_SAVE_SUCCESS']                                       = "The record has been saved as draft successfully.";
		$lang['CLIENT_INVOICE_COPY_SUCCESS']                                       = "The record %s has been copied successfully.";
		$lang['CLIENT_INVOICE_DELETE_SUCCESS']                                     = "The record %s has been deleted successfully.";
		$lang['CLIENT_INVOICE_PAID_SUCCESS']                                       = "The record %s has been marked as paid successfully.";
		$lang['CLIENT_INVOICE_UPDATE_SUCCESS']                                     = "The record %s has been updated successfully.";


		$lang['CLIENT_INVOICE_FORM_LABEL_CUSTOMER']                                = "Customer";
		$lang['CLIENT_INVOICE_FORM_LABEL_ADD_CUSTOMER']                            = "Add Customer";
		$lang['CLIENT_INVOICE_FORM_LABEL_CANCEL_CUSTOMER']                         = "Cancel";

		$lang['CLIENT_INVOICE_FORM_LABEL_LISTING']                                 = "List of Invoices";
		$lang['CLIENT_INVOICE_FORM_LABEL_ID']                                      = "Invoice ID";
		$lang['CLIENT_INVOICE_FORM_LABEL_NAME']                                    = "Customer Name";
		$lang['CLIENT_INVOICE_FORM_LABEL_STATUS']                                  = "Status";
		$lang['CLIENT_INVOICE_FORM_LABEL_CDATE']                                   = "Created Date";
		$lang['CLIENT_INVOICE_FORM_LABEL_TO']                                      = "-to-";
		$lang['CLIENT_INVOICE_FORM_LABEL_DDATE']                                   = "Due Date";

		$lang['CLIENT_INVOICE_FORM_BUTTON_RESET']                                  = "Reset";


		$lang['CLIENT_INVOICE_MSG_BOX_DELETE_TITLE']                               = "Confirm Request";
		$lang['CLIENT_INVOICE_MSG_BOX_DELETE_TEXT']                                = "Are you sure, you want to delete this record?<br/><br/>Please click on Ok to continue.";

		$lang['CLIENT_INVOICE_MSG_BOX_SAVE_TITLE']                                 = "Confirm Request";
		$lang['CLIENT_INVOICE_MSG_BOX_SAVE_TEXT']                                  = "Are you sure, you want to save this record?";

		$lang['CLIENT_INVOICE_MSG_BOX_CREATE_TITLE']                               = "Confirm Request";
		$lang['CLIENT_INVOICE_MSG_BOX_CREATE_TEXT']                                = "Are you sure, you want to create this record?<br/><br/>Once created, it will not be editable!";

		$lang['CLIENT_INVOICE_MSG_BOX_COPY_TITLE']                                 = "Confirm Request";
		$lang['CLIENT_INVOICE_MSG_BOX_COPY_TEXT']                                  = "Are you sure, you want to make copy of this record?";

		$lang['CLIENT_INVOICE_MSG_BOX_UPDATE_TITLE']                               = "Confirm Request";
		$lang['CLIENT_INVOICE_MSG_BOX_UPDATE_TEXT']                                = "Are you sure, you want to update this record?";


		$lang['CLIENT_INVOICE_TABLE_LABEL_INVOICE']                                = "Invoice ID";
		$lang['CLIENT_INVOICE_TABLE_LABEL_NAME']                                   = "Customer Name";
		$lang['CLIENT_INVOICE_TABLE_LABEL_VAT']                                    = "VAT %";
		$lang['CLIENT_INVOICE_TABLE_LABEL_AMOUNT']                                 = "Amount";
		$lang['CLIENT_INVOICE_TABLE_LABEL_TOTAL_AMOUNT']                           = "Total";
		$lang['CLIENT_INVOICE_TABLE_LABEL_FLAT_RATE']                              = "Flat rate";
		$lang['CLIENT_INVOICE_TABLE_LABEL_SALES']                                  = "Sales";
		$lang['CLIENT_INVOICE_TABLE_LABEL_CDATE']                                  = "Invoice Date";
		$lang['CLIENT_INVOICE_TABLE_LABEL_DDATE']                                  = "Due Date";
		$lang['CLIENT_INVOICE_TABLE_LABEL_STATUS']                                 = "Status";
		$lang['CLIENT_INVOICE_TABLE_LABEL_ACTION']                                 = "Actions";

		$lang['INVOICE_PAGE_LABLE_VIEW_INVOICES']                                  = "View Invoices";
		$lang['INVOICE_PAGE_LABLE_DUE_DATE']                                       = "Due Date";
		$lang['INVOICE_PAGE_LABLE_SUPLIER_INVOICE_NO']                             = "Suplier's Invoice No";
		$lang['INVOICE_PAGE_LABLE_INVOICE_DATE']                                   = "Invoice Date";
		$lang['INVOICE_PAGE_LABLE_ADD_BANK_DETAIL']                                = "Add Bank Details";
		$lang['INVOICE_PAGE_LABLE_CUSTOMER_NAME']                                  = "Customer Name";
		$lang['INVOICE_PAGE_LABLE_CUSTOMER_ADDRESS']                               = "Customer Address";
		$lang['INVOICE_PAGE_LABLE_ITEM']                                           = "Item";
		$lang['INVOICE_PAGE_LABLE_DESCRIPTION']                                    = "Description";
		$lang['INVOICE_PAGE_LABLE_VAT_PERCENT']                                    = "VAT % : ";
		$lang['INVOICE_PAGE_LABLE_AMOUNT_GBP']                                     = "Amount GBP";
		$lang['INVOICE_PAGE_LABLE_SUBTOTAL']                                       = "Subtotal";
		$lang['INVOICE_PAGE_LABLE_NET']                                      	   = "Net";
		$lang['INVOICE_PAGE_LABLE_VAT']                                            = "VAT";
		$lang['INVOICE_PAGE_LABLE_TOTAL']                                          = "Total";

		$lang['INVOICE_PAGE_LABLE_INVOICES']                                       = "Invoice";
		$lang['INVOICE_PAGE_LABLE_VAT_SUMARY']                                     = "VAT Summary";
		$lang['INVOICE_PAGE_LABLE_PAID_DATE']                                      = "Paid Date";
		$lang['INVOICE_PAGE_LABLE_FLAT_RATE']                                      = "Flat Rate % : ";
		$lang['INVOICE_PAGE_LABLE_FERST_END_DATE']                                 = "First Year Discount End Date : ";

		$lang['INVOICE_TABLE_COLUMN_INV_NO']                                       = "Invoice Number";
		$lang['INVOICE_TABLE_COLUMN_CUSTOMER_NAME']                                = "Customer Name";
		$lang['INVOICE_TABLE_COLUMN_AMOUNT']                                       = "Amount";
		$lang['INVOICE_TABLE_COLUMN_DUE_DATE']                                     = "Due Date";

		$lang['INVOICE_PDF_TEXT_ONE']                                              = "Invoice No : ";
		$lang['INVOICE_PDF_TEXT_TWO']                                              = "Invoice Date : ";
		$lang['INVOICE_PDF_TEXT_THREE']                                            = "Due By Date : ";
		$lang['INVOICE_PDF_TEXT_FOUR']                                             = "INVOICE TO :";
		$lang['INVOICE_PDF_TEXT_FIVE']                                             = "S.No.";
		$lang['INVOICE_PDF_TEXT_SIX']                                              = "VAT NUMBER";
		$lang['INVOICE_PDF_TEXT_SEVEN']                                            = "BANK";
		$lang['INVOICE_PDF_TEXT_EIGHT']                                            = "SORT CODE";
		$lang['INVOICE_PDF_TEXT_NINE']                                             = "ACCOUNT";
		$lang['INVOICE_PDF_TEXT_TEN']                                              = "Company's Registration Number : ";
		$lang['INVOICE_PDF_TEXT_ELEVEN']                                           = "Registered in ";

		$lang['INVOICE_VAT_COLUMN_PERIODS']                                        = "VAT Periods";
		$lang['INVOICE_VAT_COLUMN_FROM']                                           = "From";
		$lang['INVOICE_VAT_COLUMN_TO']                                             = "To";
		$lang['INVOICE_VAT_COLUMN_TOTAL_SALES']                                    = "Total Value of Sales";
		$lang['INVOICE_VAT_COLUMN_DUE']                                            = "VAT Due";
		$lang['INVOICE_VAT_COLUMN_STATUS']                                         = "Status";
		$lang['INVOICE_VAT_REQUEST_STATUS']                                        = "Request Status";
		$lang['INVOICE_VAT_COLUMN_ACTION']                                         = "Action";
		$lang['INVOICE_VAT_NO_RECORD']                                             = "No record available";



		$lang['CLIENT_INVOICE_NEW_EXPENSE']                                        = "Add Expense";

		$lang['CLIENT_INVOICE_LABLE_INVOICE_NUMBER']                               = "Invoice Number";
		$lang['CLIENT_INVOICE_LABLE_BANK_DETAILS']                                 = "Bank Details";
		$lang['CLIENT_INVOICE_LABLE_SNO']                                          = "S.No.";
		$lang['CLIENT_INVOICE_LABLE_DESCRIPTION']                                  = "Description";
		$lang['CLIENT_INVOICE_LABLE_QUANTITY']                                     = "Days/Hours";
		$lang['CLIENT_INVOICE_LABLE_UNIT_PRICE']                                   = "Daily/Hourly Rate";
		$lang['CLIENT_INVOICE_LABLE_VAT']                                          = "VAT %";
		$lang['CLIENT_INVOICE_LABLE_CIS_PERCENTAGE']                               = "CIS %";
		$lang['CLIENT_INVOICE_LABLE_CIS']                                          = "CIS";
		$lang['CLIENT_INVOICE_LABLE_VAT_TWO']                                      = "VAT";
		$lang['CLIENT_INVOICE_LABLE_GBP']                                          = "Amount GBP";
		$lang['CLIENT_INVOICE_LABLE_SUB_TOTAL']                                    = "Subtotal";
		$lang['CLIENT_INVOICE_LABLE_TOTAL']                                        = "Total";
		$lang['INVOICE_ACTION_MARK_TO_PAID']                                       = "Are you sure, you want to mark this record as paid?";
		$lang['INVOICE_NEW_CREDIT_NOTE_TITLE']                                     = "New Credit Note";
		$lang['INVOICE_VAT_CREDENTIAL']                                         = "VAT Credentials";
		/***************************** Expense Area Constants *****************************/
		$lang['EXPENSE_FORM_LABEL_EMPLOYEE_NAME']                                  = "Employee Name:";
		$lang['EXPENSE_FORM_LABEL_EXPENSE_TYPE']                                   = "Expense Type:";
		$lang['EXPENSE_FORM_LABEL_MILEAGE']                                        = "Mileage";
		$lang['EXPENSE_FORM_LABEL_CATEGORY']                                       = "Category :";
		$lang['EXPENSE_FORM_LABEL_METHOD']                                         = "Method :";
		$lang['EXPENSE_FORM_LABEL_FROM']                                           = "From:";
		$lang['EXPENSE_FORM_LABEL_TO']                                             = "To:";
		$lang['EXPENSE_FORM_LABEL_MILES']                                          = "Miles:";
		$lang['EXPENSE_FORM_LABEL_DATE']                                           = "Date:";
		$lang['EXPENSE_FORM_LABEL_PURPOSE']                                        = "Purpose :";
		$lang['EXPENSE_FORM_LABEL_AMOUNT']                                         = "Amount:";
		$lang['EXPENSE_FORM_LABEL_VAT_AMOUNT']                                     = "VAT Paid";
		$lang['EXPENSE_FORM_LABEL_MARK_PAID']                                      = "Mark as Paid";
		$lang['EXPENSE_FORM_LABEL_PAID']                                           = "Paid";
		$lang['EXPENSE_SAVE_CONFIRMATION']                                         = "Are you sure you want to save this record?";
		$lang['EXPENSE_SELECT_MONTH_ERROR']                                        = "Please select the expense month?";
		$lang['EXPENSE_SELECT_YEAR_ERROR']                                         = "Please select the expense year?";
		$lang['EXPENSE_SELECT_METHOD_ERROR']                                       = "Please select the expense method first?";
		$lang['EXPENSE_EDIT_POPUP_TITLE']                                          = "Edit Expense";
		$lang['EXPENSE_EDIT_CREDIT_CARD_POPUP_TITLE']                              = "Edit Credit Card Statement";
		$lang['EXPENSE_VIEW_POPUP_TITLE']                                          = "View Expense";
		$lang['EXPENSE_VIEW_CREDIT_CARD_POPUP_TITLE']                              = "View Credit Card Statement";
		$lang['EXPENSE_UPDATE_CONFIRMATION']                                       = "Are you sure you want to update this record?";
		$lang['EXPENSE_UPDATE_SUCCESS']                                            = "Record has been updated successfully!";
		$lang['EXPENSE_COPY_FORM_TITLE']                                           = "Copy Record";
		$lang['EXPENSE_CREATE_CONFIRMATION']                                       = "Are you sure you want to create this record? Once created, you would not be able to make any changes.";
		$lang['EXPENSE_UPLOAD_FORM_TITLE']                                         = "Upload Expense Template";
		$lang['EXPENSE_AT_LEAST_ONE_RECORD_ERROR']                                 = "Empty record can not be created, please add at least one record";
		$lang['EXPENSE_NEW_CREDIT_CARD_STATEMENT_TITLE']                           = "Add Credit Card Statement";
		$lang['EXPENSE_UPLOAD_CREDIT_FORM_TITLE']                                  = "Upload Credit Card Statement";
		$lang['EXPENSE_UPLOAD_EMPTY_CREDIT_STATEMENT']                             = "The template you have uploaded has no record. Please add some record!";
		$lang['EXPENSE_PAGE_TITLE']                                                = "Expenses Listing";
		$lang['EXPENSE_PAGE_LABEL_EMP']                                            = "Employee";
		$lang['EXPENSE_PAGE_LABEL_MONTH']                                          = "Month";
		$lang['EXPENSE_PAGE_LABEL_FIN_YEAR']                                       = "Financial Year";
		$lang['EXPENSE_PAGE_LABEL_MONTHLY_EXP']                                    = "Monthly Expense";
		$lang['EXPENSE_PAGE_LABEL_CREDIT_STATEMENT']                               = "Credit Card Statement";
		$lang['EXPENSE_TABLE_COLUMN_EX_ID']                                        = "Expense ID";
		$lang['EXPENSE_TABLE_COLUMN_EMP_NAME']                                     = "Employee Name";
		$lang['EXPENSE_TABLE_COLUMN_MONTH']                                        = "Month";
		$lang['EXPENSE_TABLE_COLUMN_YEAR']                                         = "Year";
		$lang['EXPENSE_TABLE_COLUMN_MILEAGE']                                      = "Mileage";
		$lang['EXPENSE_TABLE_COLUMN_TOTAL_AMOUNT']                                 = "Total Amount";
		$lang['EXPENSE_TABLE_COLUMN_VAT_PAID']                                     = "VAT Paid";
		$lang['EXPENSE_TABLE_COLUMN_VAT_PRESENTAGE']                               = "VAT %";
		$lang['EXPENSE_TABLE_COLUMN_ADD_FROM']                                     = "Added From";
		$lang['EXPENSE_TABLE_COLUMN_STATUS']                                       = "Status";
		$lang['EXPENSE_TABLE_COLUMN_PAID_DATE']                                    = "Paid Date";
		$lang['EXPENSE_TABLE_COLUMN_ACTION']                                       = "Action";

		$lang['EXPENSE_TABLE_COLUMN_ITEM']                                         = "Item";
		$lang['EXPENSE_TABLE_COLUMN_DATE']                                         = "Date";
		$lang['EXPENSE_TABLE_COLUMN_CATEGORY']                                     = "Category";
		$lang['EXPENSE_TABLE_COLUMN_MAIN_CATEGORY']                                = "Main Category";
		$lang['EXPENSE_TABLE_COLUMN_AMOUNT']                                       = "Amount";
		$lang['EXPENSE_TABLE_COLUMN_NET_AMOUNT']                                   = "Net Amount";
		$lang['EXPENSE_TABLE_COLUMN_TOTAL']                                        = "Total";
		$lang['EXPENSE_TABLE_COLUMN_MILEAGE_DATE']                                 = "Mileage Date";
		$lang['EXPENSE_TABLE_COLUMN_FROM']                                         = "From";
		$lang['EXPENSE_TABLE_COLUMN_TO']                                           = "To";
		$lang['EXPENSE_TABLE_COLUMN_METHOD']                                       = "Method";
		$lang['EXPENSE_TABLE_COLUMN_PURPOSE']                                      = "Purpose";
		$lang['EXPENSE_TABLE_COLUMN_MILES']                                        = "Miles";
		$lang['EXPENSE_TABLE_COLUMN_MILES_LOGGED']                                 = "Miles Logged so far : %s miles";
		$lang['EXPENSE_TABLE_COLUMN_ADD_ITEM']                                     = "Add Item";

		$lang['EXPENSE_TABLE_COLUMN_TOTAL_MILES']                                  = "Total Miles:";
		$lang['EXPENSE_TABLE_COLUMN_MILEAGE_EXPENSED']                             = "Mileage Expensed:";
		$lang['EXPENSE_TABLE_COLUMN_TOTAL_EXPENSES']                               = "Total Expenses:";
		$lang['EXPENSE_PAGE_BUTTON_EXPENSE']                                       = "Expense template";
		$lang['EXPENSE_PAGE_BUTTON_CREDIT']                                        = "Credit Template";


		$lang['CLIENT_EXPENSE_DIALOG_EMPTY_TITLE']                                 = "Alert";
		$lang['CLIENT_EXPENSE_ERROR_EMPTY_EXPENSE']                                = "Empty record can not be created, please enter some record!";
		$lang['CLIENT_EXPENSE_ERROR_DIALOG_TITLE']                                 = "Message";
		$lang['CLIENT_EXPENSE_ERROR_NO_CUSTOMER']                                  = "Please, choose an Employee first!";
		$lang['CLIENT_EXPENSE_ERROR_NO_CATEGORY']                                  = "Please select the expense category!";
		$lang['CLIENT_EXPENSE_ERROR_EMPTY_DATE']                                   = "Please enter the date of the expense!";
		$lang['CLIENT_EXPENSE_ERROR_EMPTY_PURPOSE']                                = "Please enter the purpose of expense!";
		$lang['CLIENT_EXPENSE_ERROR_EMPTY_AMOUNT']                                 = "Please enter the amount of the expense!";
		$lang['CLIENT_EXPENSE_ERROR_EMPTY_VAT_AMOUNT']                             = "Please enter the total VAT amount paid!";
		$lang['CLIENT_EXPENSE_ERROR_EMPETY_METHOD']                                = "Please select the mode of transport!";
		$lang['CLIENT_EXPENSE_ERROR_EMPTY_FROM']                                   = "Please enter the departure place!";
		$lang['CLIENT_EXPENSE_ERROR_EMPTY_TO']                                     = "Please enter the destination place!";
		$lang['CLIENT_EXPENSE_ERROR_EMPTY_MILES']                                  = "Please enter the miles you have travelled!";
		$lang['CLIENT_EXPENSE_CREATE_CONFIRM']                                     = "Are you sure, you want to create the expense?";
		$lang['CLIENT_EXPENSE_CREATE_SUCCESS']                                     = "Expense have been created successfully.";

		$lang['CLIENT_EXPENSE_UPDATE_CONFIRM_TITLE']                               = "Message";
		$lang['CLIENT_EXPENSE_UPDATE_CONFIRM']                                     = "Are you sure, you want to update the expense!";

		$lang['CLIENT_EXPENSE_PAID_CONFIRM_TITLE']                                 = "Message";
		$lang['CLIENT_EXPENSE_PAID_CONFIRM']                                       = "Are you sure, you want to mark Expense as paid?";

		$lang['CLIENT_EXPENSE_COPY_CONFIRM_TITLE']                                 = "Message";
		$lang['CLIENT_EXPENSE_COPY_CONFIRM']                                       = "Are you sure, you want to copy the expense?";
		$lang['CLIENT_EXPENSE_COPY_SUCCESS']                                       = "Expense has been copied successfully.";

		$lang['CLIENT_EXPENSE_UPDATE_SUCCESS']                                     = "Expense has been updated successfully.";
		$lang['CLIENT_EXPENSE_ACTION_SUCCESS_PAID']                                = "Expense has been marked as paid successfully.";

		$lang['CLIENT_EXPENSE_DELETE_CONFIRM_TITLE']                               = "Message";
		$lang['CLIENT_EXPENSE_DELETE_CONFIRM']                                     = "Are you sure, you want to delete the expense?";
		$lang['CLIENT_EXPENSE_COPY_FORM_TITLE']                                    = "Copy Expense";

		$lang['CLIENT_EXPENSE_ACTION_SUCCESS_RECONCILED']                          = "Expense have been reconciled successfully.";
		$lang['CLIENT_EXPENSE_ACTION_SUCCESS_DELETE']                              = "Expense have been deleted successfully.";
		$lang['CLIENT_EXPENSE_EDIT_FORM_TITLE']                                    = "Edit Expense";

		$lang['CLIENT_UPLOAD_EXPENSE_DIALOG_TITLE']                                = "Message";
		$lang['CLIENT_UPLOAD_EXPENSE_DIALOG_TEXT']                                 = "Please select the file first!";
		$lang['CLIENT_SELECT_BANK_DIALOG_TEXT']                                 = "Please select a bank!";


		$lang['EXPENSE_UPLOAD_FILE_ERROR']                                         = "wrong file uploaded, please upload the correct file.";
		$lang['EXPENSE_UPLOAD_PATTERN_MATCH_ERROR']                                = "Pattern mismatch,please check the Expense template pattern.";
		$lang['EXPENSE_UPLOAD_FILE_WRONG_DATA_ERROR']                              = "Invalid value for %s at row %s";
		//$lang['EXPENSE_UPLOAD_FILE_WRONG_DATA_ERROR']                            = "Invalid values in expense templates, please check your template values";

		$lang['EXPENSE_UPLOAD_FILE_SAVE_DIALOG_TITLE']                             = "Message";
		$lang['EXPENSE_UPLOAD_FILE_SAVE_DIALOG_TEXT']                              = "Are you sure, you want to save the uploaded expense?";
		$lang['EXPENSE_UPLOAD_FILE_EMPTY_SAVE_DIALOG_TEXT']                        = "Empty expense can not be saved.Please add some record in expense template.";

		$lang['EXPENSE_UPLOAD_FILE_DATA_SUCCESS']                                  = "Expenses have been added successfully.";
		$lang['CLIENT_INVOICE_VIEW_EXPENSE']                                       = "View Expense";

		$lang['EXPENSE_STATUS_CHANGE_CONFIRM']                                     = "Message";
		$lang['EXPENSE_STATUS_RECONCILED']                                         = "Are you sure, you want to mark Expense as reconciled?";
		$lang['EXPENSE_UPLOAD_DATA_FEW_RECORDS']                                   = "No record added for Employee ID %s  as they does not exists in our database.";
		$lang['EXPENSE_UPLOAD_NO_RECORD']                                          = "You have uploaded empty expense file. Please add some record in expense file.";
		$lang['EXPENSE_SAVE_SUCCESS']                                              = "New record has been saved successfully!";
		$lang['EXPENSE_CREATE_SUCCESS']                                            = "Record %s has been created successfully!";




		$lang['FILE_UPLOAD_ERROR']                                                 = "An error occurred while uploading file to the server. Please contact your Server administrator.";
		$lang['FILE_DOES_NOT_EXISTS']                                              = "The file you are trying to upload does not exist on the server. Please check the file!";
		$lang['UPLOADED_FILE_DOES_NOT_EXISTS']                                     = "The uploaded file does not exists on the server. Please check the file!";
		$lang['UNEXPECTED_FILE_UPLOAD_ERROR']                                      = "Unexpected error occurred while saving the record. Please try again later or contact your server administrator.";


		/***************************** Accountant Constant Area ********************************/
		$lang['UNEXPECTED_ERROR_DURING_SAVING_CLIENT']                             = "Unexpected error occurred during saving client detail.";
		$lang['UNEXPECTED_ERROR_DURING_SAVING_COMPANY']                            = "Unexpected error occurred during saving company detail.";
		$lang['UNEXPECTED_ERROR_DURING_SAVING_VAT']                                = "Unexpected error occurred during saving VAT detail.";
		$lang['UNEXPECTED_ERROR_DURING_SAVING_SHARE']                              = "Unexpected error occurred during saving share holder detail.";
		$lang['UNEXPECTED_ERROR_DURING_SAVING_EMPLOYEE']                           = "Unexpected error occurred during saving employee detail.";
		$lang['UNEXPECTED_ERROR_DURING_SAVING_BANK']                               = "Unexpected error occurred during saving bank detail.";
		$lang['ACCOUNTANT_ADD_CLIENT_SUCCESS']                                     = "Client has been created successfully.";
		$lang['ACCOUNTANT_NO_CLIENT_RECORD']                                       = "No record found.";


		$lang['ACCOUNTANT_CLIENT_SAVE_TITLE']                                      = 	"Message";
		$lang['ACCOUNTANT_CLIENT_SAVE_CONFIRM']                                    = 	"Are you sure you want to update the details?";
		$lang['ACCOUNTANT_CLIENT_SAVE_CONFIRM_ADDNEWCOMPANY']                      = 	"Are you sure you want to update the details and add new company?";
		$lang['ACCOUNTANT_CLIENT_DETAIL_UPDATE_SUCCESS']                           = 	"Client record updated successfully.";
		$lang['ACCOUNTANT_CLIENT_DETAIL_UPDATE_FAILURE']                           = 	"Unexpected error occured, please check the error log file.";
		$lang['ACCOUNTANT_CLIENT_EMAIL_FAILURE']                                   = 	"Error occurred during sending mail to client.";
		$lang['ACCOUNTANT_CLIENT_PASSWORD_RESET_MESSAGE']                          = 	"Hello %s<br/><br/>Your account has been created on %s with %s email as your login ID.<br/><br/>Please follow the link below to set your password.<br/><br/>%s <br/><br/>Regards<br/>";
		$lang['ACCOUNTANT_CLIENT_EMAIL_EXIST']                                     =	"The client email you have entered is all ready registered, please enter any other email";
		$lang['ACCOUNTANT_CLIENT_ID_MISSING']                                      =	"The client id is missing, please try again.";
		$lang['ACCOUNTANT_CLIENT_PASSWORD_SET_SUBJECT']                            =	"New Account created";
		$lang['ACCOUNTANT_CLIENT_INVALID_EMAIL_FAILURE']                           =	"The client email is not valid email address, please check your record.";
		$lang['ACCOUNTANT_CLIENT_DISABLE_STATUS_SUCCESSFULL']                      =	"User has been disabled successfully";
		$lang['ACCOUNTANT_CLIENT_ENABLE_STATUS_SUCCESSFULL']                       =	"User has been disabled successfully";
		$lang['ACCOUNTANT_INVALID_UTR_NUMBER']                                     =	"Invalid UTR number. It should be 10-digit number";
		$lang['ACCOUNTANT_INVALID_NI_NUMBER']                                      =	"Invalid NI number. e.g AB 12 34 56 C or AB123456C";
		$lang['ACCOUNTANT_INVALID_EMAIL']                                          =	"Invalid email address";
		$lang['ACCOUNTANT_INVALID_PHONE_NUMBER']                                   =	"Invalid phone number";
		$lang['ACCOUNTANT_INVALID_BANK_SHORT_CODE']                                =	"Invalid short code. It should be 6-digit number";
		$lang['ACCOUNTANT_INVALID_BANK_ACCOUNT_NUMBER']                            =	"Invalid account number. It should be 8-digit number";
		$lang['ACCOUNTANT_INVALID_COMPANY_REGISTRATION_NUMBER']                    =	"Invalid Company Registration Number. The valid formats are 12345678 or AB123456";
		$lang['ACCOUNTANT_WRONG_TOTAL_SHARES']                                     =	"The sum of shares of all the shareholders should be equal to total number of shares";
		$lang['ACCOUNTANT_INVALID_VAT_REGISTRATION_NUMBER']                        =	"Invalid VAT registration number. It should be 9-digit number.";
		$lang['ACCOUNTANT_ENABLE_CLIENT_TEXT']                                     =	"Are you sure, you want to enable the client?";
		$lang['ACCOUNTANT_DISABLE_CLIENT_TEXT']                                    =	"Are you sure, you want to disable the client?";
		$lang['ACCOUNTANT_CLIENT_EMAIL_EXISTS']                                    =	"The {s} email is already registered. Please enter different one.";
		$lang['ACCOUNTANT_CLIENT_ALREADY_EMAIL_EXISTS']                                    =	"Email already registered.";
		$lang['ACCOUNTANT_UPLOAD_EXPENSE_CATEGORY_CHANGE']                         =	"Are you sure, you want to change the expense category from Mileage to another one?<br/><br/>Once changed all your data related to mileage will be lost.";
		$lang['ACCOUNTENT_RESEND_PASSWORD_INSTRUCTION_MAIL_CONFIRM']               =	"Are you sure, you want to send password setting instruction again?";
		$lang['ACCOUNTENT_RESEND_EMAIL_FAILURE']                                   =	"An error occurred while sending email. Please try gain later or contact you Server administrator.";
		$lang['ACCOUNTANT_INVALID_PAYEE_REFERENCE']                                =	"Invalid number.";
		$lang['ACCOUNTANT_INVALID_PAYEE_FORMATE']                                  =	"Invalid payee code e.g. 475/RA98286";
		$lang['ACCOUNTANT_INVALID_POSTAL_CODE']                                    =	"Invalid postal code.e.g AB22 9AB";
		$lang['ACCOUNTENT_RESEND_EMAIL_SUCCESS']                                   =	"Email has been successfully sent to %s";
		$lang['ACCOUNTANT_INVALID_DATE_FORMAT']                                    =	"Invalid date format. e.g. dd-mm-yy";
		$lang['ACCOUNTANT_POPUP_ADD_TITLE']                                        =	"Add Accountant";
		$lang['ACCOUNTANT_SAVE_CONFIRMATION']                                      =	"Are you sure you want to save the accountant detail?";
		$lang['ACCOUNTANT_CREATE_CONFIRMATION']                                    =	"Are you sure you want to create the accountant detail?";
		$lang['ACCOUNTENT_WRONG_FILE_UPLOADED']                                    =	"The signature image you have uploaded is not allowed. Only jpg,jpeg type of images are allowed";
		$lang['ACCOUNTENT_FILE_UPLOAD_UPLOADED']                                   =	"An error occurred while uploading the image file, please try again later or contact your server administrator";
		$lang['ACCOUNTENT_FILE_SIZE_ERROR']                                        =	"File size is too large, please check the file size!";
		$lang['ACCOUNTENT_CREATE_SUCCESS']                                         =	"The accountant account has been created successfully.";
		$lang['ACCOUNTENT_UPDATE_SUCCESS']                                         =	"Accountant details has been updated successfully";
		$lang['ACCOUNTANT_NEW_ACCOUNT_MESSAGE']                                    = 	"Hello %s<br/><br/>Your account has been created on %s with %s email as your login ID.<br/><br/>Please follow the link below to set your password.<br/><br/>%s <br/><br/>Regards<br/>";

		/***************************** Dividend view ********************************/
		$lang['DIVIDEND_NEW_FORM_TITLE']                                           =	"New Dividend Voucher";
		$lang['DIVIDEND_NEW_SAVE_CONFIRMATION']                                    =	"Are you sure, you want to create the dividend?";
		$lang['DIVIDEND_CREATED_SUCCESSFULLY']                                     =	"The dividend %s has been created successfully.";
		$lang['DIVIDEND_CREATION_ERROR']                                           =	"Unexpected error occurred during creating the dividend. Please try again later or contact your Server Administrator.";
		$lang['DIVIDEND_EDIT_FORM_TITLE']                                          =	"Edit Dividend";
		$lang['DIVIDEND_UPDATE_CONFIRMATION']                                      =	"Are you sure, you want to update the dividend voucher?";
		$lang['DIVIDEND_UNEXPECTED_ERROR']                                         =	"Unexpected error occurred please try again later or contact your server Administrator.";
		$lang['DIVIDENT_UPDATE_SUCCESSFUL']                                        =	"The dividend %s has been updated successfully.";
		$lang['DIVIDEND_DELETE_MESSAGE']                                           =	"The dividend {%s} has been deleted successfully.";
		$lang['DIVIDEND_PAID_MESSAGE']                                             =	"The dividend {%s} has been marked as paid successfully.";
		$lang['DIVIDEND_DELETE_CONFIRM_TEXT']                                      =	"Are you sure, you want to delete the dividend?";
		$lang['DIVIDEND_PAID_CONFIRM_TEXT']                                        =	"Are you sure, you want to mark the dividend as paid?";
		$lang['DIVIDEND_VIEW_FORM_TITLE']                                          =	"Dividend voucher %s";
		$lang['DIVIDEND_SHARES_ZERO_ERROR']                                        =	"The chosen shareholder has zero shares in the company, so a dividend can not be created!";

		$lang['DIVIDEND_PAGE_LABEL_TITLE']                                         =	"Dividend";
		$lang['DIVIDEND_PAGE_LABEL_SHARE_NAME']                                    =	"Shareholder Name";
		$lang['DIVIDEND_PAGE_LABEL_DIV_DATE']                                      =	"Dividend Date";
		$lang['DIVIDEND_PAGE_LABEL_NET_AMOUNT']                                    =	"Net Amount";
		$lang['DIVIDEND_PAGE_LABEL_DIV_NUMBER']                                    =	"Dividend Voucher No";
		$lang['DIVIDEND_PAGE_LABEL_TAX_CREDIT']                                    =	"Tax Credit";
		$lang['DIVIDEND_PAGE_LABEL_GROSS_DIV']                                     =	"Gross Dividend";
		$lang['DIVIDEND_PAGE_LABEL_DATE']                                          =	"Date";
		$lang['DIVIDEND_PAGE_LABEL_DIV_PER_SHARE']                                 =	"Dividend Per Share";
		$lang['DIVIDEND_PAGE_LABEL_DIV_STATUS']                                    =	"Status";
		$lang['DIVIDEND_PAGE_LABEL_PAID_DATE']                                     =	"Paid Date";
		$lang['DIVIDEND_PAGE_LABEL_ACTION']                                        =	"Actions";
		$lang['DIVIDEND_PAGE_LABEL_SIGNATORY']                                     =	"Signatory : ";
		$lang['DIVIDEND_PAGE_LABEL_TOTAL_SHARES']                                  =	"Total shares : ";
		$lang['DIVIDEND_PAGE_LABEL_SHAREHOLDER']                                   =	"Shareholder";
		$lang['DIVIDEND_PAGE_LABEL_DATE_OF_DIV']                                   =	"Date of Dividend";
		$lang['DIVIDEND_PAGE_LABEL_AMOUNT']                                        =	"Amount";
		$lang['DIVIDEND_PAGE_LABEL_NO_SHARES']                                     =	"Number of Shares";
		$lang['DIVIDEND_PAGE_LABEL_PAID_VIA_DIR']                                  =	"Paid via Director's Loan";
		$lang['DIVIDEND_FORM_LABEL_PAID']                                          =	"Paid?";

		$lang['DIVIDEND_FORM_BUTTON_VIEW_DIV']                                     =	"View Dividends";
		$lang['DIVIDEND_FORM_BUTTON_UPDATE_VOUCHER']                               =	"Update Voucher";
		$lang['DIVIDEND_FORM_BUTTON_CREATE_VOUCHER']                               =	"Create Voucher";
		$lang['DIVIDEND_PDF_TEXT_ONE']                                             =	"Minutes of the Meeting of Directors held at company’s premises on the ";
		$lang['DIVIDEND_PDF_TEXT_TWO']                                             =	"PRESENT";
		$lang['DIVIDEND_PDF_TEXT_THREE']                                           =	"MINUTES";
		$lang['DIVIDEND_PDF_TEXT_FOUR']                                            =	"The minutes of the previous meeting were read and approved as a current record, having previously been signed.";
		$lang['DIVIDEND_PDF_TEXT_FIVE']                                            =	"DIVIDENDS";
		$lang['DIVIDEND_PDF_TEXT_SIX']                                             =	"The payment of an interim dividend for the year ended %s of £ %s per share on %s was discussed and approved by the directors.";
		$lang['DIVIDEND_PDF_TEXT_SEVEN']                                           =	"Date:";
		$lang['DIVIDEND_PDF_TEXT_EIGHT']                                           =	"YEAR ENDED";
		$lang['DIVIDEND_PDF_TEXT_NINE']                                            =	"INTERIM DIVIDEND PAYABLE %s OF %s PER SHARE";
		$lang['DIVIDEND_PDF_TEXT_SHARE']                                           =	"Shares";
		$lang['DIVIDEND_PDF_TEXT_CREDIT']                                          =	"Tax Credit";
		$lang['DIVIDEND_PDF_TEXT_GROSS_DIV']                                       =	"Gross Dividend";
		$lang['DIVIDEND_PDF_TEXT_NET_DIV']                                         =	"Net Dividend";








		/***************************** Banks Statements **************************/
		$lang['BANK_UPLOAD_FILE_ERROR']                                            = "Wrong file uploaded.";
		$lang['BANK_UPLOAD_NO_RECORD']                                             = "Empty statements can not be uploaded!";
		$lang['BANK_UPLOAD_PATTERN_MATCH_ERROR']                                   = "Pattern mismatch,please check the Bank Statement template pattern!";
		$lang['BANK_SAVE_STATEMENT_CONFIRM']                                       = "Are you sure, you want to reconciled the matched statements?";
		$lang['BANK_RECONCILED_INVOICES_ERROR']                                    = "Error occurred while reconciling the invoices!";
		$lang['BANK_RECONCILED_EXPENSES_ERROR']                                    = "Error occurred while reconciling the expenses!";
		$lang['BANK_RECONCILED_DIVIDENDS_ERROR']                                   = "Error occurred while reconciling the dividends!";
		$lang['BANK_REONCILATION_SUCCESS']                                         = "%s records have been reconciled successfully.";
		$lang['BANK_STATEMENT_CANCEL_ERROR']                                       = "Some error occurred, please contact your server administrator";
		$lang['BANK_STATEMENT_NO_RECORDS']                                         = "No bank statements have been uploaded yet.";
		$lang['BANK_RECONCILED_FILES_ERROR']                                       = "Error occurred while reconciling expense files!";
		$lang['BANK_UPLOAD_STATEMENTS_TITLE']                                      = "Upload Bank Statements";
		$lang['BANK_STATEMENTS_ADDED_SUCCESSFULLY']                                = "Bank statements have been added successfully";
		$lang['BANKS_STATEMENT_UPLOAD_UPDATE_CONFIRM']                             = "Are you sure you want to save the statements?";
		$lang['BANK_STATEMENT_UPDATE_SUCCESS']                                     = "Bank statements has been updated successfully";
		$lang['BANK_UPLOAD_DUPPLIACATE_ENTRY']                                     = "The statement entry at rows %s already exists in our record, please delete/change them first!";
		$lang['BANK_UPLOAD_UPDATE_DUPPLIACATE_ENTRY']                              = "The statement entry at rows %s already exists in our record, please change them first!";
		$lang['BANK_STATEMENT_SELECT_CONFIRM']                                     = "Please first select the statement to delete!";
		$lang['BANK_STATEMENT_DELETE_CONFIRM']                                     = "Are you sure you want to delete the selected rows?";
		$lang['BANK_STATEMENT_DELETE_SUCCESS']                                     = "Statements have been deleted successfully";
		$lang['BANK_VIEW_DIVIDEND_POPUP_TITLE']                                    = "Previous Dividend entries";
		$lang['BANK_VIEW_INVOICES_POPUP_TITLE']                                    = "Previous Invoice entries";
		$lang['BANK_DIVIDEND_UPDATE_ERROR']                                        = "An error occurred while updating the dividend table";
		$lang['BANK_UPLOAD_PAGE_UNLOAD_MESSAGE']                                   = "Are you sure you want to leave this page, as there is some data to be saved which will be lost if you leave this page";
		$lang['BANK_PAGE_LABEL_TITLE']                                             = "Bank Accounts Summary";
		$lang['BANK_PAGE_LABEL_DESCRIPTION']                                       = "Description";
		$lang['BANK_PAGE_LABEL_DATE']                                              = "Date";
		$lang['BANK_PAGE_LABEL_CATEGORY']                                          = "Category";
		$lang['BANK_PAGE_LABEL_TYPE']                                          	   = "Type";
		$lang['BANK_PAGE_LABEL_FINANCIAL_YEAR']                                    = "Financial Year";
		$lang['BANK_UPLOAD_FILED_YEAR_ENTRIES']                                    = "You have uploaded filed year entries at rows %s , please check your entries!";
		$lang['BANK_UPLOAD_BUTTON']                                                = "Upload Bank Statement";
		$lang['BANKSTATEMENT_ADD_BUTTON']                                          = "Add Bank Statement";
		$lang['BANKSTATEMENT_ADD_FORM_TITLE']                                      = "Add Bank Statement";
		$lang['BANK_TABLE_COLUMN_SELECTBANK']                                      = "Bank Name";
		$lang['BANK_TABLE_COLUMN_BANK']                                            = "Bank";		
		$lang['BANK_TABLE_COLUMN_DATE']                                            = "Date";
		$lang['BANK_TABLE_COLUMN_TYPE']                                            = "Type";
		$lang['BANK_TABLE_COLUMN_DESCRIPTION']                                     = "Description";
		$lang['BANK_TABLE_COLUMN_MONEY_OUT']                                       = "Money Out";
		$lang['BANK_TABLE_COLUMN_MONEY_IN']                                        = "Money In";
		$lang['BANK_TABLE_COLUMN_BALANCE']                                         = "Balance";
		$lang['BANK_TABLE_COLUMN_CHECK']                                           = "Check";
		$lang['BANK_TABLE_COLUMN_CATEGORY']                                        = "Analysis Account";
		$lang['BANK_TABLE_COLUMN_MAIN_CATEGORY']                                   = "Analysis Ledger";
		$lang['BANK_TABLE_COLUMN_ACTIONS']                                         = "Action";
		$lang['BANK_UPLOAD_BUTTON_ONE']                                            = "Bank Statement &nbsp;+/-";
		$lang['BANK_UPLOAD_BUTTON_TWO']                                            = "Bank Statement &nbsp;IN/OUT";
		
		$lang['BANK_FILTER_EXCEL']                                            	   = "Match excel columns";

		/***************************** Bulk Upload Bank Statements **************************/
		$lang['SELECT_CATEGORY_ERROR']                                         	   = "Please select category first!";
		$lang['BULK_BANK_PAGE_LABEL_TITLE']                                        = "Bulk Bank Accounts Summary";
		$lang['BULK_UPLOAD_CLIENT_NAME']                                       	   = "Client Name";
		$lang['BULK_UPLOAD_COMPANY_NAME']                                          = "Company Name";
		$lang['BANK_STATMENT_LINK']                                         	   = "Bank Statment Linking";
		
		/***************************** Document sub folders **************************/
		$lang['DOCUMENT_PAGE_TITLE']                                               = "My Documents";
		$lang['DOCUMENT_LABEL_NAME']                                               = "Name";
		$lang['DOCUMENT_LABEL_KIND']                                               = "Kind";
		$lang['DOCUMENT_LABEL_SIZE']                                               = "Size";
		$lang['DOCUMENT_LABEL_FOLDER']                                             = "Folder";
		$lang['DOCUMENT_LABEL_FILE']                                               = "File";
		$lang['DOCUMENT_LABEL_FOLDER_NAME']                                        = "Folder Name";
		$lang['DOCUMENT_LABEL_CREATE_FOLDER']                                      = "Create Folder";
		$lang['DOCUMENT_BUTTON_UPLOAD']                                            = "Upload document";
		$lang['DOCUMENT_BUTTON_ADD_FOLDER']                                        = "Add new folder";
		$lang['DOCUMENTS_UPLOAD_CONFIRMATION_TEXT']                                = "Please select file first!";
		$lang['DOCUMENTS_UPLOAD_CATEGORY_SELECT_ERROR']                            = "Please select file category!";
		$lang['DOCUMENT_FILE_UPLOAD_ERROR']                                        = "An error occurred while save the file record in the database, please try again later or contact your Server Administrator!";
		$lang['DOCUMENT_FILE_UPLOAD_SUCCESS']                                      = "File has been uploaded successfully!";
		$lang['DOCUMENTS_DELETE_ERROR']                                            = "An error occurred while deleting the file from the folder, please try again later or contact your server administrator!";
		$lang['DOCUMENTS_DELETE_FILE_RECORD_ERROR']                                = "An error occurred while deleting the file record from the database, please try again later or contact your server administrator!";
		$lang['DOCUMENT_DELETE_FILE_SUCCESS']                                      = "File has been deleted successfully!";
		$lang['DOCUMENT_DELETE_FILE_CONFIRM']                                      = "Are you sure, you want to delete the file?";
		$lang['DOCUMENT_NEW_FOLDER_TITLE']                                         = "Add New Folder";
		$lang['DOCUMENT_FOLDER_CREATION_SUCCESS']                                  = "Folder has been created successfully!";
		$lang['DOCUMENT_DELETE_FOLDER_CONFIRM']                                    = "Are you sure you want to delete this folder and all of its contents?";
		$lang['DOCUMENT_FOLDER_DELETE_SUCCESS']                                    = "Folder has been deleted successfully!";
		$lang['DOCUMENT_FOLDER_EXISTS_ALREADY']                                    = "The folder with name %s exists already!";
		$lang['DOCUMENT_FILE_ALREADY_EXISTS']                                      = "The file you are uploading already exists. Do you want to replace the old file or keep the both files!";


		/***************************** Pay Statements **************************/
		$lang['SALARY_UPLOAD_FILE_ERROR']                                          = "Please select the pay statement first!";
		$lang['SALARY_FILE_UPLOAD_ERROR']                                          = "You have uploaded the wrong file type. Please choose the correct file!";
		$lang['SALARY_UPLOAD_FILE_FINANCIAL_YEAR_MISMATCH']                       = "Salary template pattern does not have financial year, please check the Salary template! Sheet 1 should be active!";
		$lang['SALARY_UPLOAD_FILE_PATTERN_MISMATCH']                               = "Salary template pattern does not match with the slangered template, please check the Salary template!";
		$lang['SALARY_FILE_INSERT_ERROR']                                          = "An error occurred while saving the file record, please try again later or contact your Server Administrator!";
		$lang['SALARY_FILE_RECORD_INSERT_ERROR']                                   = "An error occurred while saving the file entries, please try again later or contact your Server Administrator!";
		$lang['SALARY_ENTRIES_SAVE_SUCCESSFUL']                                    = "Salary statements saved successfully!";
		$lang['SALARY_AJAX_LISTING_ERROR']                                         = "Some error occurred, please try again later, or contact your server administrator!";
		$lang['SALARY_NO_RECORD_FOUND']                                            = "No record found";
		$lang['SALARY_DELETE_CONFIRMATION']                                        = "Are you sure, you want to delete the salary?";
		$lang['SALARY_DELETE_SUCCESS']                                             = "Salary statement has been deleted successfully!";
		$lang['SALARY_PAID_SUCCESS']                                               = "Salary has been marked as paid successfully!";
		$lang['SALARY_PAID_CONFIRMATION']                                          = "Are you sure, you want to mark salary as paid?";
		$lang['SALARY_INAPROPIATE_DATA']                                           = "The salary statement has insufficient data, please check the salary statement entries at row %s";
		$lang['SALARY_UPDATE_ENTRIES']                                             = "An error occurred while updating the salary entries!";
		$lang['SALARY_STATEMENT_UPDATE_SUCCESS']                                   = "Salary statements have been updated successfully!";
		$lang['SALARY_INVALID_RECORD_AT_ROW']                                      = " Also the entries at row %s are not added due to insufficient data";
		$lang['SALARY_WRONG_STATEMENT']                                            = "You have uploaded wrong salary statement file for this user. Please check the salary statement file!";



		$lang['PAYEE_SAVE_QUATER_CONFIRMATION']                                    = "Are you sure, you want to save the payee quarters information!";
		$lang['PAYEE_EMPTY_QUARTER_IBFO']                                          = "Empty quarter information can not be added. Please add some record!";
		$lang['PAYEE_NO_RECORD_FOUND']                                             = "No record found";
		$lang['PAYEE_NEW_DETAIL_TITLE']                                            = "New payee detail";
		$lang['PAYEE_DETAIL_SAVE_SUCCESS']                                         = "Payee detail has been saved successfully!";
		$lang['PAYEE_QUARTER_RECORD_ALREADY_ADDED']                                = "The record for selected financial year has been already added";
		$lang['PAYE_QUARTER_DELETE_CONFIRMATION']                                  = "Are you sure you want to delete the payee quarter?";
		$lang['PAYEE_QUARTER_DELETE_SUCCESS']                                      = "Payee quarter has been deleted successfully!";
		$lang['PAYEE_QUARTER_PAID_SUCCESS']                                        = "Payee quarter has been marked as paid successfully!";
		$lang['PAYE_QUARTER_PAID_CONFIRMATION']                                    = "Are you sure you want to mark the quarter as paid?";
		$lang['PAYEE_EDI_DETAIL_TITLE']                                            = "Edit Payee Detail";
		$lang['PAYEE_UPDATE_QUATER_CONFIRMATION']                                  = "Are you sure, you want to update the payee quarter detail?";
		$lang['PAYEE_QUARTER_UPDATE_SUCCESS']                                      = "Payee quarters has been updated successfully";
		$lang['PAYEE_SELECT_YEAR_ERROR']                                           = "Please select the financial year first!";

		/***************************** Contact Us ********************************/
		$lang['CONTACT_EMAIL_SUBJECT']                                             =	"Problem request";
		$lang['CONTACT_REQUEST_SUCCESS']                                           =	"Your request has been successfully send. We will get back to you within 24 hours.";
		$lang['CONTACT_REQUEST_FAILURE']                                           =	"Unexpected error occured, please try again later or contact your Server administrator.";

		/***************************** Tool-tip ********************************/

		$lang['TOOLTIP_IN_COPY']                                                   =	"Click to copy the invoice";
		$lang['TOOLTIP_IN_DELETE']                                                 =	"Click to delete the invoice";
		$lang['TOOLTIP_IN_PDF']                                                    =	"Click to download the PDF";
		$lang['TOOLTIP_IN_PAID']                                                   =	"Click to mark as paid";
		$lang['TOOLTIP_IN_INVOICE']                                                =	"Sort by Invoice ID";
		$lang['TOOLTIP_IN_NAME']                                                   =	"Sort by name";
		$lang['TOOLTIP_IN_AMOUNT']                                                 =	"Sort by amount";
		$lang['TOOLTIP_IN_TOTAL']                                                  =	"Sort by Total amount";
		$lang['TOOLTIP_IN_CDATE']                                                  =	"Sort by created date";
		$lang['TOOLTIP_IN_DDATE']                                                  =	"Sort by due date";


		$lang['TOOLTIP_EXPENSE']                                                   =	"Sort by Expense ID";
		$lang['TOOLTIP_NAME']                                                      =	"Sort by Employee name";
		$lang['TOOLTIP_MONTH']                                                     =	"Sort by Month";
		$lang['TOOLTIP_MILES']                                                     =	"Sort by miles";
		$lang['TOOLTIP_AMOUNT']                                                    =	"Sort by total amount";
		$lang['TOOLTIP_FILES']                                                     =	"Sort by Added from";
		$lang['TOOLTIP_STATUS']                                                    =	"Sort by Status";
		$lang['TOOLTIP_EX_COPY']                                                   =	"Click to copy the expense";
		$lang['TOOLTIP_EX_DELETE']                                                 =	"Click to delete the expense";
		$lang['TOOLTIP_EX_PAID']                                                   =	"Click to mark as paid";
		$lang['TOOLTIP_EX_RECONCILED']                                             =	"Click here to mark as reconciled";

		$lang['TOOLTIP_NAME']                                                      =	"Sort by name";
		$lang['TOOLTIP_CONTACTNO']                                                 =	"Sort by Contact Number";
		$lang['TOOLTIP_DATE']                                                      =	"Sort by Year End date";
		$lang['TOOLTIP_STATUS']                                                    =	"Sort by Status";


		$lang['TOOLTIP_DIVIDEND_VOUCHER']                                          =	"Sort by voucher number";
		$lang['TOOLTIP_DIVIDEND_DATE']                                             =	"Sort by dividend date";
		$lang['TOOLTIP_SHARERNAME']                                                =	"Sort by shareholder name";
		$lang['TOOLTIP_NET_AMOUNT']                                                =	"Sort by net amount";
		$lang['TOOLTIP_TAX_AMOUNT']                                                =	"Sort by tax amount";
		$lang['TOOLTIP_GROSS_AMOUNT']                                              =	"Sort by gross amount";
		$lang['TOOLTIP_DIVIDEND_DELETE']                                           =	"Click to delete the dividend";
		$lang['TOOLTIP_DIVIDEND_COPY']                                             =	"Click to copy the dividend";
		$lang['TOOLTIP_DIVIDEND_PAID']                                             =	"Click to mark dividend as paid";
		$lang['TOOLTIP_EDIT_CLIENT']                                               =	"Click to edit client";
		$lang['TOOLTIP_EDIT_CLIENT_PRIVILEGES']                                               =	"Click to edit client privileges";
		$lang['TOOLTIP_DIVIDEND_MINUTES']                                          =	"Click to download meeting pdf";
		$lang['TOOLTIP_DIVIDEND_WITH_CERTIFICATE']                                 =	"Click to download certificate pdf with signature";
		$lang['TOOLTIP_DIVIDEND_WITHOUT_CERTIFICATE']                              =	"Click to download certificate pdf without signature";
		$lang['TOOLTIP_PAYEE_DELETE']                                              =	"Click to delete the quarter";
		$lang['TOOLTIP_PAYEE_PAID']                                                =	"Click to mark as paid";
		$lang['TOOLTIP_SALARY_DELETE']                                             =	"Click to delete salary";
		$lang['TOOLTIP_SALARY_PAID']                                               =	"Click to mark as paid";
		$lang['TOOLTIP_DELETE_IMAGE']                                              =	"Click to delete the image";
		$lang['TOOLTIP_CATEGORY']                                                  =	"Sort by category";

		$lang['UNEXPECTED_ERROR_OCCURED']                                          = "Unexpected error occurred, please try again later or contact your server administrator";
		$lang['DIALOG_DELETE_CONFIRM_TITLE']                                       = "Delete-Confirm";


		$lang['LABEL_CONTACT_US']                                                  =	"Contact Us";
		$lang['LABEL_REASON']                                                      =	"Reason";
		$lang['LABEL_DESCRIPTION']                                                 =	"Description";
		$lang['POP_UP_DIALOG_TITLE']                                               =	"Message";

		/***************************** Invoices VAT Summary ********************************/

		$lang['TOOLTIP_VAT_MARK_AS_PAID']                                          =	"Click to mark as SUBMITTED.";
		$lang['TOOLTIP_VAT_SUBMITTED']                                             =	"VAT added till date.";
		$lang['CLIENT_INVOICE_VAT_SUBMITTED_LABEL']                                =	"NOT SUBMITTED";
		$lang['CLIENT_VAT_FINANCIAL_YEAR_LABEL']                                   =	"Financial Year";
		$lang['VAT_MARK_AS_PAID_QUES_LABEL']                                       =	"Are you sure you want to mark this Quarter as submitted?";
		$lang['CLIENT_VAT_MARK_AS_PAID_TITLE']                                     =	"Mark VAT as submitted for Quarter";
		$lang['VAT_MARK_AS_PAID_SUCCESS_MSG']                                      =	"VAT marked as submitted successfully.";
		$lang['ERROR_UPDATING_VAT_ID_IN_INVOICE']                                  =	"Can not update properly!";
		$lang['ERROR_UPDATING_VAT_ID_IN_EXPENSE']                                  =	"Can not update properly!";
		$lang['ERROR_ADDING_VAT_AS_PAID_REVERTED']                                 = 	"Error while marked as paid, reverted changes made so far!";
		$lang['ERROR_ADDING_VAT_AS_PAID']                                          =	"Can not mark as submitted, please try again later!";
		$lang['ERROR_ADDING_VAT_AS_PAID_SALES_ZERO']                               =	"Can not mark as submitted, no sales were found in this Quarter!";
		$lang['ERROR_ADDING_VAT_AS_PAID_NO_INVOICE']                               =	"No Invoices in this Quarter!";
		$lang['ERROR_ADDING_VAT_AS_PAID_NO_QUARTERS']                              =	"Can not get Quarter(s) to mark as submitted!";
		$lang['ERROR_ADDING_VAT_AS_PAID_NO_QUARTER_SELECTED']                      =	"No quarter selected to mark as submitted!";
		$lang['ERROR_ADDING_VAT_AS_PAID_NO_YEAR_SELECTED']                         =	"No VAT Year selected to mark as submitted!";
		$lang['ERROR_ADDING_VAT_AS_PAID_NO_PAID_DATE_CHOOSEN']                     =	"Date of submission was not selected!";
		$lang['TOOLTIP_VAT_ALREADY_PAID']                                          =	"VAT already submitted for this Quarter!";
		$lang['CLIENT_INVOICE_VAT_PAID_LABEL']                                     =	"SUBMITTED";
		$lang['ERROR_VAT_PAID_DATE_NOT_SELECTED']                                  =	"Submission date cannot be left blank!";
		$lang['ERROR_NOTHING_FOUND_IN_VAT_SUMARRY']                                =	"No VAT details found right now for this Year!";
		$lang['VAT_QUARTER_DETAILS_POPUP_TITLE']                                   =	"VAT Summary: Q";
		$lang['NO_INVOICES_IN_QUARTER_DETAILS']                                    =	"No invoices found in this quarter!";
		$lang['NO_EXPENSES_IN_QUARTER_DETAILS']                                    =	"No expenses found in this quarter!";
		$lang['INVALID_DATE_FORMAT']                                               =	"Invalid date format. e.g. dd-mm-yy";
		$lang['TOOLTIP_VAT_PDF_DOWNLOAD']                                          =	"click to download the PDF.";

		$lang['CASHMAN_DISABLED_ACCOUNT_MESSAGE']                                  = "The account you are trying to access is not yet activated, please activate the account first!";
		$lang['CASHMAN_COMPNAY_LOGO_REQUIREMENT']                                  = "The image size should not be greater than %sKB (Allowed extensions: jpg,png)";
		$lang['CASHMAN_SIGNATURE_IMAGE_REQUIREMENT']                               = "The image size should not be greater than 5KB (Allowed extensions: jpg,png)";




		$lang['CONFIGURATION_SAVE_SUCCESSFUL']                                     = "Settings have been saved successfully!";


		$lang['VAT_SUMMARY_POPUP_INV_DATE_COL_LABEL']                              = "Invoice Date";
		$lang['VAT_SUMMARY_POPUP_CLIENT_NAME_COL_LABEL']                           = "Client Name";
		$lang['VAT_SUMMARY_POPUP_INV_NO_COL_LABEL']                                = "Invoice ID";
		$lang['VAT_SUMMARY_POPUP_ANET_VALUE_COL_LABEL']                            = "Net Value (excl. VAT)";
		$lang['VAT_SUMMARY_POPUP_BVAT_VALUE_COL_LABEL']                            = "VAT Collected";
		$lang['VAT_SUMMARY_POPUP_FULL_AMOUNT_COL_LABEL']                           = "Full Amount";
		$lang['VAT_SUMMARY_POPUP_INV_VAT_COL_LABEL']                               = "VAT Payable";
		$lang['VAT_TOTAL_VALUE']                                                   = "Total";
		$lang['VAT_SUMMARY_POPUP_EXP_NO_COL_LABEL']                                = "Expense";
		$lang['VAT_SUMMARY_POPUP_CDATE_COL_LABEL']                                 = "Created Date";
		$lang['VAT_SUMMARY_POPUP_PDATE_COL_LABEL']                                 = "Paid Date";
		$lang['VAT_SUMMARY_POPUP_EXP_VAT_COL_LABEL']                               = "VAT";
		$lang['VAT_TOTAL_DUE']                                                     = "Total VAT due";

		/***************************** Trial Balance ********************************/

		$lang['TB_ROW_SRNO']                                                       = "Sr.No.";
		$lang['TB_ROW_TYPE']                                                       = "Type";
		$lang['TB_ROW_TOTAL_AMOUNT']                                               = "Total Amount";
		$lang['TB_ROW_TOTAL']                                                      = "Total";
		$lang['TB_PL_ACCOUNT']                                                     = "PROFIT AND LOSS ACCOUNT";
		$lang['TB_BS_ACCOUNT']                                                     = "BALANCE SHEET";
		$lang['NO_TB_RECORD_FOUND']                                                = "No record found!";
		$lang['NO_ACCESS_TO_TB']                                                   = "You do not have access to this Area!";
                $lang['TB_PL_ACCOUNT_INCOME']                                              ='Income';
                $lang['TB_PL_ACCOUNT_EXPENSES']                                              ='Less Expenses';
                $lang['TB_PL_ACCOUNT_PROFT_LOST']                                              ='Profit and loss';
                $lang['TB_PL_ACCOUNT_BALANCE']                                              ='Balance Sheet';
                
                

		$lang['ACCOUNTANT_DASHBOARD_NO_RECORD_FOUND']                              = "No record found";
		$lang['ACCOUNTANT_NOTES_NO_RECORD_FOUND']                                  = "No note has been added yet";
		$lang['NOTES_DESCRIPTION_EMPTY_ERROR']                                     = "Empty note can not be created, please enter some text!";
		$lang['NOTES_SAVE_SUCCESSFUL']                                             = "A note for the user has been added successfully";
		$lang['TOOLTIP_DELETE']                                                    = "Click to delete the note";
		$lang['NOTE_DELETE_CONFIRMATION']                                          = "Are you sure you want to delete the note?";
		$lang['NOTES_DELETE_SUCCESSFUL']                                           = "Note has been deleted successfully.";


		/**
		*
		*	Notes page constants
		*
		*/
		$lang['JOURNAL_LABEL_CR']                                                  = "CR";
		$lang['JOURNAL_LABEL_DB']                                                  = "DB";
		$lang['JOURNAL_LABEL_ADD']                                                 = "Add Journal";
		$lang['JOURNAL_COLUMN_ITEM']                                               = "Item";
		$lang['JOURNAL_COLUMN_TYPE']                                               = "Type";
		$lang['JOURNAL_COLUMN_CATEGORY']                                           = "Category";
		$lang['JOURNAL_COLUMN_NARRATION']                                           = "Narration";
		$lang['JOURNAL_COLUMN_SUB_CATEGORY']                                       = "Sub Category";
		$lang['JOURNAL_COLUMN_AMOUNT']                                             = "Amount";
		$lang['JOURNAL_COLUMN_GROUP']                                              = "Reference";
		$lang['JOURNAL_NEW_POPUP_TITLE']                                           = "New Journal Entry";
		$lang['JOURNAL_SAME_CATEGORY_ERROR']                                       = "Credit and debit can not have same category!";
		$lang['JOURNAL_EMPTY_ENTRY_ERROR']                                         = "Empty entry can not be made, please add some entry!";
		$lang['JOURNAL_SAVE_ENTRY_CONFIRMATION']                                   = "Are you sure, you want to save the entries?";
		$lang['JOURNAL_ADD_ENTRIES_SUCCESSFUL']                                    = "Journal entries have been saved successfully!";
		$lang['JOURNAL_BLANK_ENTRY_ERROR']                                         = "Blank journal entry can not be made, please enter some record";
		$lang['JOURNAL_WRONG_ENTRY']                                               = "Credit and debit amount should be equal, please check your entries";
		$lang['JOURNAL_NO_CR_ENTRY_ERROR']                                         = "You should have at-least one credit entry, please check your entries";
		$lang['JOURNAL_UPLOAD_PATTERN_MATCH_ERROR']                                = "Pattern mismatch,please check the Journal template pattern.";
                $lang['JOURNAL_BLANK_ENTRY_ERROR']                                         = "Blank journal entry can not be made, please enter some record";
		$lang['JOURNAL_NO_CR_ENTRY_ERROR']                                         = "You should have at-least one credit entry, please check your entries";
		$lang['JOURNAL_UPLOAD_PATTERN_MATCH_ERROR']                                = "Pattern mismatch,please check the Journal template pattern.";

		/**
		*	Notes page constants
		*/
		$lang['NOTES_LABEL_TITLE']                                                 = "Notes";
		$lang['NOTES_BUTTON_ADD']                                                  = "Add Note";
		$lang['NOTES_COLUMN_DESCRIPTION']                                          = "Description";



		/**
		*	Dashboard page constants
		*/
		// tooltips
		$lang['TOOLTIP_FILE_RETURN']                                               = "Click to mark as Filed!";
		$lang['TOOLTIP_ACCOUNT_FILED']                                             = "Return already marked as Filed!";
		$lang['TOOLTIP_RETURN_FILED']                                              = "Return already marked as Filed!";

		// labels
		$lang['DASHBOARD_UNFILED_LABEL']                                           = "Not filed";
		$lang['DASHBOARD_FILED_LABEL']                                             = "Filed";

		//error messages
		$lang['ERROR_NO_COMPANY_ACCOUNT']                                          = "No company selected to mark account year as filed!";
		$lang['ERROR_NO_COMPANY_RETURN']                                           = "No company selected to mark return year as filed!";
		$lang['ERROR_CANT_MARK_ACCOUNT_FILED']                                     = "Could not mark account year as filed, contact Administrator!";
		$lang['ERROR_CANT_MARK_RETURN_FILED']                                      = "Could not mark return year as filed, contact Administrator!";
		$lang['ERROR_MARK_NOT_REVERTED_ACCOUNT']                                   = "Could not revert back the changes, contact Administrator!";
		$lang['ERROR_MARK_NOT_REVERTED_RETURN']                                    = "Could not revert back the changes, contact Administrator!";
		$lang['ERROR_MARK_REVERTED_ACCOUNT']                                       = "Could not mark account year as filed, Successfully reverted the changes, please contact site Administrator!";
		$lang['ERROR_MARK_REVERTED_RETURN']                                        = "Could not mark return year as filed, Successfully reverted the changes, please contact site Administrator!";

		// success messages
		$lang['SUCCESS_MARK_FILED_ACCOUNT']                                        = "Successfully marked account year as filed!";
		$lang['SUCCESS_MARK_FILED_RETURN']                                         = "Successfully marked return year as filed!";


		$lang['CASHAMN_CLIENT_LOG_SIZE_ERROR']                                     = "The chosen file size exceeds the limit, please chose another image!";

		$lang['CASHMAN_MARK_ACCOUNT_FILED']                                        = "Are you sure you want to mark it as filed!";

		$lang['DASHBOARD_IMPLICATION_POPUP']                                       = "Amount needed exceeds available dividends";

		//Error Messages

		$lang['ERROR_FIELD_REQUIRED']                                              = "This field is required";
		$lang['CHART_INVOICE_LABLE_PAID']                                          = " Paid Invoices ";
		$lang['CHART_EXPENSE_LABLE_PAID']                                          = " Paid Expenses ";
		$lang['CHART_INVOICE_LABLE_UNPAID']                                        = " Unpaid Invoices ";
		$lang['CHART_EXPENSE_LABLE_UNPAID']                                        = " Unpaid Expenses ";

		$lang['COMPARITIVES_COLUMN_LABEL_COMPARITIVES']                            =	"Comparatives";
		$lang['COMPARITIVES_COLUMN_LABEL_CURRENT_YEAR']                            =	"Current Year";
		$lang['COMPARITIVES_COLUMN_LABEL_PREV_YEAR']                               =	"Previous Year";
		$lang['COMPARITIVES_COLUMN_LABEL_SALES']                                   =	"Sales (Incl Flat Rate Benefit)";
		$lang['COMPARITIVES_COLUMN_LABEL_ADMIN_COST']                              =	"Administration Costs";
		$lang['COMPARITIVES_COLUMN_LABEL_OPE_PROFIT']                              =	"Operating Profit";
		$lang['COMPARITIVES_COLUMN_LABEL_POT_CALCULATION']                         =	"Potential Corporation Tax Liability";
		$lang['COMPARITIVES_COLUMN_LABEL_PROFIT_AFTER_TAX']                        =	"Net Profit After Tax";
		$lang['COMPARITIVES_COLUMN_LABEL_ACC_PROFIT']                              =	"Accumulated Profits";
		$lang['COMPARITIVES_COLUMN_LABEL_DIV_TAKEN']                               =	"Total Dividend Taken";
		$lang['COMPARITIVES_COLUMN_LABEL_DIV_AVAIL']                               =	"Total Dividend Available";

		$lang['IMPORTANT_DATES_COLUMN_LABEL_DATE']                                 =	"Date";
		$lang['IMPORTANT_DATES_COLUMN_LABEL_EVENT_TYPE']                           =	" Event Type ";
		$lang['IMPORTANT_DATES_COLUMN_LABEL_DAYS_TO_GO']                           =	"Days to go";


		$lang['SHAREHOLDER_COLUMN_LABEL_HIGER_RATE']                               =	"Higher Rate Tax Threshold";
		$lang['SHAREHOLDER_COLUMN_LABEL_GROSS_SALARY']                             =	"Gross Salary";
		$lang['SHAREHOLDER_COLUMN_LABEL_GROSS_DIVIDEND']                           =	"Gross Dividends Taken to Date";
		$lang['SHAREHOLDER_COLUMN_LABEL_INCOME_DATE']                              =	"Total Gross Income to Date";
		$lang['SHAREHOLDER_COLUMN_LABEL_DIVIDEND_AVAIL']                           =	"Net Dividends Available (Before Div Tax)";
		$lang['SHAREHOLDER_COLUMN_LABEL_ABOVE_THERESHOLD']                         =	"Net Dividends above Threshold";
		$lang['SHAREHOLDER_COLUMN_LABEL_TAX_IMPLICATION']                          =	"High Dividend Tax Implications";
		$lang['SHAREHOLDER_COLUMN_LABEL_DIV_NEEDED']                               =	"Net Dividend Needed";
		$lang['SHAREHOLDER_COLUMN_LABEL_DIV_IMPLICATION']                          =	"Dividend Tax Implication";


		$lang['SERVER_MAIL_ERROR']                                                 =	"Could not instantiate the mail function, please check you server's mail settings!";


		// Ledger Balance
		$lang['ERROR_LOADING_LEDGER_POPUP_DETAILS']                                =	"Could not load Ledger details, Please contact site Administrator!";

		$lang['DIVIDEND_STATEMENT_ALREADY_EXISTS']                                 =	"There is an dividend already linked to this row in bank statement";
		$lang['STATEMENT_INVOICE_EXISTS_ALREADY']                                  =	"There is an invoice already linked to this row in bank statement";

		//Term And Conditions
		$lang['ACCOUNTANT_NO_TERM_CONDITIONS']                                     =       "&nbsp; There is no Terms And Conditions associated with. Please check your details!";
		$lang['ACCOUNTANT_TERM_CONDITION_VERSION']                                 =       "VERSION";
		$lang['ACCOUNTANT_TERM_CONDITION']                                         =          "Term And Conditions";
		$lang['ACCOUNTANT_TERM_CONDITION_ACTION']                                  =          "Action";
		$lang['TOOLTIP_EDIT_VERSION']                                              =          "Edit Terms And Conditions";
		$lang['ACCOUNTANT_TERM_CONDITION_ADDEDON']                                 =          "Added On";
		$lang['ACCOUNTANT_TERM_CONDITION_STATUS']                                  =          "Status";
		$lang['CASHAMN_ENABLED_TERM_CONDITIONS']                                   =          "Are you sure, you want to enabled setting instruction again?";
		$lang['TOOLTIP_ENABLED_VERSION']                                           = "Do You Want Enabled Version Click Here";

		//Action Logs

		$lang['TERM_CONDITION_LEFT_BLANK']			= "Terms And conditions can't left blank";
		$lang['TERM_CONDITION_SUCCESS']   			= "Terms and conditions has been added sucessfully";
		$lang['LOS_NO_LOGS_FOUND']        			= "No Records Found";
		$lang['USER_LOGIN']               			= "User logged into the portal";
		$lang['USER_LOGOUT']              			= "User logged out from the portal";
		$lang['LOG_LIST_SEARCH']          			= "Search Action Log";
		$lang['LOG_LIST_S_NO']            			= "S.NO";
		$lang['LOG_LIST_TYPE']            			= "Type";
		$lang['LOG_LIST_DATE']            			= "Date";
		$lang['LOG_LIST_DESCRIPTION']     			= "Description";
		$lang['TERMS_CONDITIONS']         			= " Terms And Conditions";
		$lang['TERM_CONDITIONS_ACCPET']   			= "Please accept Terms And Conditions";
		$lang['TERM_CONDITIONS_ACCPECTED']			= "Term and conditions accepted successfully";
		$lang['LOG_LIST_NAME']            			= "Name";
		$lang['LOG_LIST_ID']              			= "Log Id";

                $lang['USER_CREATED_INVOICE']     			= "An invoice was created";
                $lang['USER_SAVED_INVOICE_CRN_SAVED_AS_DRAFT']          = "An invoice was saved as CRN Draft";
                $lang['USER_SAVED_INVOICE_IN_DARFT']                    = "An invoice was saved as Draft";
                $lang['USER_SAVED_INVOICE']       			= "An invoice was saved as Draft";
                $lang['USER_DRAFT_INVOICE_CREATED']       		= "An invoice was created";
				$lang['USER_UPDATED_INVOICE']     			= "An invoice was updated";
				$lang['USER_MARKED_INVOICE_PAID'] 			= "The invoice was marked as paid";
				$lang['USER_DELETED_INVOICE']     			= "An invoice has been deleted";
				
				$lang['USER_CREATED_PURCHASE']     			= "An purchase invoice was created";
                $lang['USER_SAVED_PURCHASE_CRN_SAVED_AS_DRAFT']          = "An purchase item was saved as CRN Draft";
                $lang['USER_SAVED_PURCHASE_IN_DARFT']                    = "An purchase item was saved as Draft";
                $lang['USER_SAVED_PURCHASE']       			= "An purchase item was saved as Draft";
                $lang['USER_DRAFT_PURCHASE_CREATED']       	= "An purchase item was created";
				$lang['USER_UPDATED_PURCHASE']     			= "An purchase was updated";
				$lang['USER_MARKED_PURCHASE_PAID'] 			= "The purchase was marked as paid";
				$lang['USER_DELETED_PURCHASE']     			= "An purchase has been deleted";
				
				$lang['USER_CREATED_PAID_DIVIDEND']     	= "A dividend paid voucher was created";
				$lang['USER_CREATED_DIVIDEND']    			= "A dividend voucher was created";
				$lang['MARK_DIVIDEND_PAID']       			= "The dividend voucher  was marked as paid";
				$lang['DIVIDEND_DELETED']         			= "A dividend voucher  was deleted";
				$lang['DIVIDEND_UPDATED']         			= "A dividend voucher was updated";
				
				

		

		$lang['USER_CREATED_EXPENSES']    			= "An expense was created";
		$lang['USER_SAVED_EXPENSES']      			= "An expenses was saved as draft";
		$lang['USER_UPDATED_EXPENSES']    			= "An expenses was updated";
		$lang['USER_DELETED_EXPENSES']    			= "An expenses has been deleted";
		$lang['MARK_EXPENSES_PAID']       			= "The expenses was marked as paid";
        $lang['USER_EXPENSES_DRAFT_CREATE']       	= "The expenses Draft has been Created";

		

		$lang['SALARY_UPLOADED']          			= "A salary statement was uploaded";
		$lang['MARK_SALARY_PAID']         			= "A salary was marked as paid";
		$lang['SALARY_DELETED']           			= "A salary was deleted";
		$lang['SALARY_UPDATED']           			= "A salary was updated";
		$lang['PAYEE_UPDATED']            			= "A payee was updated";
		$lang['USER_ADDED_PAYEE_DETAILS'] 			= "A new payee details was added";
		$lang['MARK_PAYEE_PAID']          			= "The payee was marked as paid";
		$lang['DELETE_PAYEE']             			= "A payee was deleted";
		$lang['USER_CREATED_JOURNAL']     			= "A journal was created";
        $lang['USER_CREATED_NOTE']                   = "A note was created";
		$lang['USER_DELETED_NOTE']                   = "A note was deleted";
                $lang['LOG_ITEM_ID']              			= "Reference Id";
                $lang['ERROR_LOADING_LEDGER_POPUP_DELETE_DETAILS']         = "Data does not found!";
                $lang['ACTION_LOGS_PAGE_TITLE']         = "Search action log";


		/*
			Log popups
		 */

		$lang['ID']         						= "Id";

		$lang['NOTE_ID']          					= "Note Id";
		$lang['NOTE_CLIENT_ID']   					= "Client Id";
		$lang['NOTE_DESCRIPTION'] 					= "Note Description";
		$lang['NOTE_ADDED_BY']    					= "Added By";
		$lang['NOTE_ADDED_ON']    					= "Added On";
		$lang['NOTE_STATUS']      					= "Status";

		$lang['JOURNAL_ID']                    		= "Journal ID";
		$lang['JOURNAL_TITLE_NAME']            		= "Journal Entries";
                $lang['JOURNAL_PAGE_BUTTON_EXPENSE']        = "Journal template";
		$lang['JOURNAL_LABEL_ACCOUNTING_YEAR'] 		= "Company Accounting Year";
		$lang['JOURNAL_FINANCIAL_YEAR']        		= "Financial year";
		$lang['JOURNAL_GROUP_ID']              		= "Group Id";
		$lang['JOURNAL_ADDED_BY']              		= "Added By";
		$lang['JOURNAL_ADDED_ON']              		= "Added On";
		$lang['JOURNAL_MODIFIED_ON']           		= "Modified On";
		$lang['JOURNAL_STATUS']                		= "Status";
		$lang['JOURNAL_ACCOUNTANT_ACCESS']     		= "Accountant Access";

		$lang['SALARY_ID']          				= "Salary Id";
		$lang['SALARY_COMPANY_ID']          		= "Company ID";
		$lang['SALARY_YEAR']			            = "Financial year";
		$lang['SALARY_PAY_DATE']    				= "Pay Date";
		$lang['SALARY_NIC_EMPLOYEE']   				= "NIC Employee";
		$lang['SALARY_NIC_EMPLOYER']   				= "NIC Employer";
		$lang['SALARY_SMP']   						= "SMP";
		$lang['SALARY_INCOMETAX'] 					= "Income Tax";
		$lang['SALARY_NET_PAY']    					= "Net Pay";
		$lang['SALARY_GROSS']      					= "Gross Salary";
		$lang['SALARY_ADDED_BY']    				= "Added By";
		$lang['SALARY_ADDED_ON']    				= "Added On";
		$lang['SALARY_PAID_DATE']    				= "Paid Date";
		$lang['SALARY_ACCOUNTANT_ACCESS']      		= "Accountant Access";
		$lang['SALARY_STATUS']      				= "Status";
		$lang['SALARY_RECONCILED']   				= "Reconciled";
		$lang['SALARY_FILE_ID']    					= "FileID";

		$lang['PAYROLL_ID']          				= "Payroll Id";
		$lang['PAYROLL_COMPANY_ID']          		= "Company ID";
		$lang['PAYROLL_YEAR']			            = "Financial year";
		$lang['PAYROLL_QUARTER']			        = "Quarter";
		$lang['PAYROLL_INCOMETAX'] 					= "Income Tax";
		$lang['PAYROLL_NIC_EMPLOYEE']   			= "NIC Employee";
		$lang['PAYROLL_NIC_EMPLOYER']   			= "NIC Employer";
		$lang['PAYROLL_TOTAL']			            = "Total";
		$lang['PAYROLL_PAYEE_REFERENCE']			= "Payee Office Reference";
		$lang['PAYROLL_HRMC']			            = "HRMC Refunds";
		$lang['PAYROLL_START_DATE']			        = "Start Date";
		$lang['PAYROLL_END_DATE'] 					= "End Date";
		$lang['PAYROLL_PAID_DATE']    				= "Paid Date";
		$lang['PAYROLL_STATUS']      				= "Status";
		$lang['PAYROLL_ADDED_BY']    				= "Added By";
		$lang['PAYROLL_ADDED_ON']    				= "Added On";
		$lang['PAYROLL_ACCOUNTANT_ACCESS']      	= "Accountant Access";


		$lang['EXPENSE_NUMBER']          			= "Expense Number";
		$lang['EXPENSE_TYPE']			            = "Expense Type";
		$lang['EXPENSE_EMPLOYEE_ID']			    = "Employee Id";
		$lang['EXPENSE_MONTH'] 						= "Month";
		$lang['EXPENSE_YEAR']   					= "Expense Year";
		$lang['EXPENSE_TOTAL']			            = "Total Amount";
		$lang['EXPENSE_TOTAL_VAT']			        = "Total VAT Amount";
		$lang['EXPENSE_TOTAL_MILES']			    = "Total Miles";
		$lang['EXPENSE_ADDED_ON']    				= "Added On";
		$lang['EXPENSE_ADDED_BY']    				= "Added By";
		$lang['EXPENSE_EXPENSE_DATE']    			= "Expense Date";
		$lang['EXPENSE_MODIFIED']    				= "Modified On";
		$lang['EXPENSE_PAID_ON']    				= "Expense Paid On";
		$lang['EXPENSE_STATUS']      				= "Status";
		$lang['EXPENSE_RECONCILED']			        = "Reconciled";
		$lang['EXPENSE_FILE_ID']    				= "FileID";
		$lang['EXPENSE_ACCOUNTANT_ACCESS']      	= "Accountant Access";
		$lang['EXPENSE_VAT_ID']			        	= "Total VAT ID";
		$lang['EXPENSE_ID']          				= "Expense Id";
		$lang['EXPENSE_CATEGORY']   				= "Category";
		$lang['EXPENSE_ITEM_TYPE']					= "Item Type";
		$lang['EXPENSE_ITEM_DATE']					= "Item Date";
		$lang['EXPENSE_LOCATION_FROM']			    = "Location From";
		$lang['EXPENSE_LOCATION_TO']			    = "Location To";
		$lang['EXPENSE_PURPOSE'] 					= "Purpose";
		$lang['EXPENSE_AMOUNT']          			= "Amount";
		$lang['EXPENSE_VAT_AMOUNT']          		= "VAT Amount";
		$lang['EXPENSE_MILES']          			= "Miles";
		$lang['EXPENSE_TITLE']			            = "Title";
		$lang['EXPENSE_CATEGORY_TYPE']   			= "Category Type";
		$lang['EXPENSE_KEY']			        	= "Key";
                  $lang['EXPENSE_TABLE_COLUMN_DESCRIPTION']				= "Description";

		$lang['DIVIDEND_ID']          				= "Dividend Id";
		$lang['DIVIDEND_SHAREHOLDER_ID']          	= "Shareholder ID";
		$lang['DIVIDEND_COMPANY_ID']          		= "Company ID";
		$lang['DIVIDEND_VOUCHER_NUMBER']          	= "Voucher Number";
		$lang['DIVIDEND_DIVIDEND_DATE']    			= "Dividend Date";
		$lang['DIVIDEND_GROSS_AMOUNT']      		= "Gross Amount";
		$lang['DIVIDEND_TAX_AMOUNT'] 				= "Tax Amount";
		$lang['DIVIDEND_NET_AMOUNT'] 				= "Net Amount";
		$lang['DIVIDEND_PAIDBY_DIRECTOR'] 			= "Paid By Director Loan";
		$lang['DIVIDEND_ADDED_ON']    				= "Added On";
		$lang['DIVIDEND_ADDED_BY']    				= "Added By";
		$lang['DIVIDEND_PAID_ON']    				= "Paid On";
		$lang['DIVIDEND_RECONCILED']			    = "Reconciled";
		$lang['DIVIDEND_BANK_STATEMENT']			= "Bank Statement";
		$lang['DIVIDEND_STATUS']      				= "Status";
		$lang['DIVIDEND_ACCOUNTANT_ACCESS']      	= "Accountant Access";


		$lang['INVOICE_ID']          				= "Invoice Id";
		$lang['INVOICE_DESCRIPTION'] 				= "Description";
		$lang['INVOICE_TYPE']			            = "Type";
		$lang['INVOICE_UNIT_PRICE']			        = "Unit Price";
		$lang['INVOICE_QUANTITY']			        = "Quantity";
		$lang['INVOICE_TOTAL_AMOUNT']      			= "Total Amount";
		$lang['INVOICE_TAX'] 						= "Tax";
		$lang['INVOICE_TAX_NAME'] 					= "Tax Name";
		$lang['INVOICE_TAX_TYPE'] 					= "Tax Type";
		$lang['INVOICE_TAX_AMOUNT'] 				= "Tax Amount";
		$lang['INVOICE_ADDED_ON']    				= "Added On";
		$lang['INVOICE_ADDED_BY']    				= "Added By";
		$lang['INVOICE_MODIFIED_ON']    			= "Modified On";
		$lang['INVOICE_STATUS']      				= "Status";
		$lang['INVOICE_ACCESS']   				   	= "Accountant Access";
		$lang['INVOICE_NUMBER']   			       	= "Invoice Number";
		$lang['INVOICE_CUSTOMER_COMPANY_ID']		= "Customer Company ID";
		$lang['INVOICE_USER_ID']			    	= "User Id";
		$lang['INVOICE_SUB_TOTAL']			    	= "Sub Total";
		$lang['INVOICE_SHIPPING']			    	= "Is Shipping";
		$lang['INVOICE_TOTAL_SHIPPING']			    = "Total Shipping";
		$lang['INVOICE_TOTAL']			        	= "Total Invoice";
		$lang['INVOICE_STATUS']      				= "Status";
		$lang['INVOICE_DUE_DATE']			        = "Due Date";
		$lang['INVOICE_BANK_DETAIL']			    = "Bank Detail";
		$lang['INVOICE_PAID_ON']    				= "Paid On";
		$lang['INVOICE_RECONCILED']			    	= "Reconciled";
		$lang['INVOICE_BANK_STATEMENT']				= "Bank Statement";
		$lang['INVOICE_VAT_PAID_ID']				= "VAT Paid Id";
		$lang['INVOICE_DATE']						= "Invoice Date";


		$lang['ERROR_LOADING_LOG_POPUP_DETAILS']                                =	"Could not load Log details, Please contact site Administrator!";


		//Global Configuration
		$lang['GLOBAL_CONFIGURATION']                                              = "Global Configuration";
		$lang['PAGE_LISTING']                                                      = "Page Listing";
		$lang['GOLBAL_CONF_EMAIL']                                                 = "Email";
		$lang['GOLBAL_CONF_MILEAGE']                                               = "Mileage";
		$lang['GOLBAL_CONF_MILEAGE']                                               = "Mileage";
		$lang['GOLBAL_CONF_GOVT_TAXES']                                            = "Govt. Taxes";
		$lang['GOLBAL_CONF_EXPENSE_TEMPLATE']                                      = "Expense template";
		$lang['GOLBAL_CONF_IMAGE']                                                 = "Image";
		$lang['MAIL_CONFIGURATION']                                                = "Mail Settings";
		$lang['MAIL_CONFIGURATION_SETTINGS']                                       = "Mail Settings";
		$lang['MAIL_CONFIGURATION_SIGNATURE']                                      = "Signature";
		$lang['MAIL_CONFIGURATION_RESEND_PASSWORD']                                = "Email text for resend password";
		$lang['MAIL_CONFIGURATION_ACCOUNT_CREATED']                                = "Email text for created account";
		$lang['MAIL_CONFIGURATION_WARNING_MESSAGE']                                = "Please not remove %s sign in text field.Any problem Click Here And Save";
                $lang['ACCOUNTANT_CONFIGURATION_EMAIL_PAGINATION_LIMIT']                   = "Email pagination limit";
		//ACCOUNTANT ACCOUNTANTS
		$lang['ACCOUNTANT']                                                        = "Accountant";
		$lang['ACCOUNTANT_ACCOUNT_DUE']                                            = "Accounts Due";
		$lang['ACCOUNTANT_NAME']                                                   = "Name";
		$lang['ACCOUNTANT_EMAIL']                                                  = "Email";
		$lang['ACCOUNTANT_STATUS']                                                 = "Status";
		$lang['ACCOUNTANT_FULL_NAME']                                              = "Full Name";
		$lang['ACCOUNTANT_EMPLOYEE_LEVEL']                                         = "Employee Level";
		$lang['ACCOUNTANT_ACTIONS']                                                = "Actions";
		$lang['ACCOUNTANT_ADD_ACCOUNTANT']                                         = "Add Accountant";
		$lang['ACCOUNTANT_ACCOUNTANT_DETAIL']                                      = "Accountant Detail";
		$lang['ACCOUNTANT_TITLE']                                                  = "Title";
		$lang['ACCOUNTANT_DATE_OF_BIRTH']                                          = "Date Of Birth";
		$lang['ACCOUNTANT_EMPLOYMENT_LEVEL']                                       = "Employment level";
		$lang['ACCOUNTANT_ACTIVE']                                                 = "Active";


		//ACCOUNTANT CONFIGURATION
		$lang['ACCOUNTANT_CONFIGURATION_INVOICE_LISTING_PAGINATION_LIMIT']         = "Invoice Listing pagination limit";
		$lang['ACCOUNTANT_CONFIGURATION_EXPENSE_LISTING_PAGINATION_LIMIT']         = "Expense Listing pagination limit";
		$lang['ACCOUNTANT_CONFIGURATION_DIVIDEND_LISTING_PAGINATION_LIMIT']        = "Dividend Listing pagination limit";
		$lang['ACCOUNTANT_CONFIGURATION_BANK_STATEMENTS_LISTING_PAGINATION_LIMIT'] = "Bank Statements Listing pagination limit";
		$lang['ACCOUNTANT_CONFIGURATION_JOURNAL_LISTING_PAGINATION_LIMIT']         = "Journal Listing pagination limit";
		$lang['ACCOUNTANT_CONFIGURATION_CLIENT_LISTING_PAGINATION_LIMIT']          = "Client Listing pagination limit";
		$lang['ACCOUNTANT_CONFIGURATION_ACCOUNTANT_LISTING_PAGINATION_LIMIT']      = "Accountant Listing pagination limit";
		$lang['ACCOUNTANT_CONFIGURATION_SALARY_&_PAYEE_LISTING_PAGINATION_LIMIT']  = "Salary & Payee Listing pagination limit";
		$lang['ACCOUNTANT_CONFIGURATION_LEDGER_PAGINATION_LIMIT']                  = "Ledger pagination limit";
                $lang['ACCOUNTANT_CONFIGURATION_ACTION_LOG_PAGINATION_LIMIT']              = "Action Log pagination limit";
		$lang['ACCOUNTANT_CONFIGURATION_CONTACT_US_EMAIL_SETTINGS']                = "Contact us email settings";
		$lang['ACCOUNTANT_CONFIGURATION_TAX_RATES_PER_BUSSINESS_MILE']             = "Tax: rates per bussiness mile";
		$lang['ACCOUNTANT_CONFIGURATION_TYPES_OF_VEHICLE']                         = "Types of vehicle";
		$lang['ACCOUNTANT_CONFIGURATION_FIRST_10000_MILES']                        = "First 10,000 miles";
		$lang['ACCOUNTANT_CONFIGURATION_ABOVE_10000_MILES']                        = "Above 10,000 miles";
		$lang['ACCOUNTANT_CONFIGURATION_CARS_AND_VANS']                            = "Cars and vans";
		$lang['ACCOUNTANT_CONFIGURATION_MOTORCYCLES']                              = "Motorcycles";
		$lang['ACCOUNTANT_CONFIGURATION_BIKES']                                    = "Bikes";
		$lang['ACCOUNTANT_CONFIGURATION_MAXIMUM_DISTANCE']                         = "Maximum Distance";
		$lang['ACCOUNTANT_CONFIGURATION_VAT']                                      = "VAT %";
		$lang['ACCOUNTANT_CONFIGURATION_CORPORATION_TAX']                          = "Corporation tax";
		$lang['ACCOUNTANT_CONFIGURATION_INDIVIDUAL_TAXABLE_INCOME']                = "Individual Taxable Income";
		$lang['ACCOUNTANT_CONFIGURATION_YEAR']                                     = "Year";
		$lang['ACCOUNTANT_CONFIGURATION_EXPENSE_TEMPLATE_TEXT_ONE']                = "Text One";
		$lang['ACCOUNTANT_CONFIGURATION_EXPENSE_TEMPLATE_TEXT_TWO']                = "Text Two";
		$lang['ACCOUNTANT_CONFIGURATION_SIGNATURE_IMAGE_FILE_SIZE_LIMIT']          = "Signature image file size limit";
		$lang['ACCOUNTANT_CONFIGURATION_KB']                                       = "KB";
		$lang['ACCOUNTANT_CONFIGURATION_LOGO_IMAGE_FILE_SIZE_LIMIT']               = "Logo image file size limit";
		$lang['ACCOUNTANT_CONFIGURATION_ADD_TERM_AND_CONDITIONS']                  = "Add Term And Conditions";
                /*-----------------------------------------Email Setup---------------------------------*/

                $lang['EMAIL_PAGE_TITLE']                  = "Send Mail";
                $lang['EMAIL_SEARCH_VAT_TYPE']             = "Vat Type";
                $lang['EMAIL_SEARCH_VAT_DAYS']             = "Days";
                $lang['EMAIL_SEARCH_VAT_QUARTERS']         = "Quarters";
				$lang['BULK_EMAIL_TYPE']             = "Bulk Email Type";


                //Term And Conditions
		$lang['ACCOUNTANT_NO_TERM_CONDITIONS']                                     =       "&nbsp; There is no Terms And Conditions associated with. Please check your details!";
		$lang['ACCOUNTANT_TERM_CONDITION_VERSION']                                 =       "Version";
		$lang['ACCOUNTANT_TERM_CONDITION']                                         =          "Term And Conditions";
		$lang['ACCOUNTANT_TERM_CONDITION_ACTION']                                  =          "Action";
		$lang['TOOLTIP_EDIT_VERSION']                                              =          "Edit Terms And Conditions";
		$lang['ACCOUNTANT_TERM_CONDITION_ADDEDON']                                 =          "Added On";
		$lang['ACCOUNTANT_TERM_CONDITION_STATUS']                                  =          "Status";
		$lang['CASHAMN_ENABLED_TERM_CONDITIONS']                                   =          "Are you sure, you want to enabled setting instruction again?";
		$lang['TOOLTIP_ENABLED_VERSION']                                           = "Do You Want Enabled Version Click Here";
                $lang['CASHAMN_TERM_CLIENT_SELECT']                                           = "Please select client";
                $lang['ACCOUNTANT_TERM_CONDITION_COMPNAY_NAME']                                           = "Company name";
                $lang['ACCOUNTANT_TERM_CONDITION_CLIENT_NAME']                                           = "Client name";
                $lang['ACCOUNTANT_TERM_CONDITION_UPLOADED_BY']                                           = "Uploaded By";
                $lang['ACCOUNTANT_TERM_CONDITION_FILE_NAME']                                           = "File name";
                $lang['ACCOUNTANT_TERM_CONDITION_ACCEPTED_DATE']                                           = "Accepted date";
                $lang['ACCOUNTANT_TERM_CONDITION_ACCEPTED_STATUS']                                           = "Status";
                $lang['ACCOUNTANT_TERM_CONDITION_ACCEPTED_EMAIL']                                           = "Instruction Email ";
                $lang['TERMS_CONDITIONS_PAGE_TITLE']                                           = "Terms and Conditions ";
                $lang['CASHAMN_CLIENT_TERMPDF_PDF_ERROR']                                           = "Please upload pdf file";
                $lang['CASHAMN_CLIENT_TERMPDF_PDF_ERROR']                                           = "Please upload pdf file";
                $lang['SELECT_CLIENT']                                           = "Please select client";
                $lang['CASHAMN_TERM_STATUS_SELECT']                                           = "Please select status";
                $lang['MAIL_TEMPLATE']                                           = "Mail template";
                $lang['MAIL_CONFIGURATION_TERMS_CONDITION_TEMPLATE']                                     = "Terms and conditions mail template";
                $lang['TERMS_CONDITONS_SEND_MAIL_ERROR']                                           = "Could not send mail, Please contact site Administrator!";
                $lang['TERMS_CONDITIONS_EMAIL_FAILURE']                                           = "An error occurred while sending email. Please try gain later or contact you Server administrator";
                $lang['TERMS_CONDITONS_SEND_EMAIL_NOT_EXITS']                                           = "Could not send mail, client email does not exist!";
                $lang['TERMS_CONDITIONS_EMAIL_SUCCESS']                                           = "Your Email was successfully sent";
                $lang['TERMS_CONDITIONS_EMAIL_SUBJECT']                                           = "Terms and conditions";
                $lang['TERM_CONDITIONS_RECORD_FOUND']                                           = "No records found";

                /*-------------------------------------------EMAIL--------------------------------------------*/
                $lang['TEMPLATE_NAME_ERROR']                                           = "Please enter template name";
                $lang['TEMPLATE_TEXT_ERROR']                                           = "Please enter template text";
                $lang['TEMPLATE_TEXT_SUCCESS']                                           = "Email Template has been added successfully";
                $lang['ERROR_ADD_TEMPLATE']                                =	"Could not save template, Please contact site Administrator!";
                $lang['SEND_EMAIL']                                =	"Send";
                $lang['SELECT_TEMPLATE']                                =	"Select Email Template";
                $lang['SUBJECT']                                =	"Subject";
                $lang['TERM_EMAIL']                                =	"Email";
                $lang['TERMS_CONDITONS_SEND_MAIL_SUBJECT']                                =	"Please enter subject";
                $lang['TERMS_CONDITONS_SEND_MAIL_MESSAGE']                                =	"Please enter message";
                $lang['TEMPLATE_NAME']                                =	"Template Name";
                $lang['TEMPLATE_TEXT']                                =	"Template text";
                $lang['ERROR_ADD_TEMPLATE_CLIENT']                                =	"Could not Send template, Please contact site Administrator!";
                $lang['TEMPLATE_NAME']                                =	"Template name";
                $lang['TEMPLATE_MESSAGE']                                ="Template message";
                $lang['EMAIL_TEMPLATE']                                ="Email template";
                $lang['TEMPLATE_RECORD_FOUND']                                ="Email template not found";
                $lang['ADD_TEMPLATE']                                ="Add template";
                $lang['TEMPLATE_MESSAGE_ACTION']                                ="Action";
                $lang['TEMPLATE_TEXT_UPDATE_SUCCESS']                                ="Email template has been update successfully";


                /*----------------------------Bulk Email Logs-------------------------------------------*/
                $lang['BULK_EMAIL_LOGS_PAGE_TITLE']                                ="Bulk email logs";
                $lang['BULK_EMAIL_LOGS_COMPNAY_NAME']                              ="Company Name";
                $lang['BULK_EMAIL_LOGS_CLIENT_NAME']                               ="Client Name";
                $lang['BULK_TO']                                                   ="To";
                $lang['BULK_EMAI_SUBJECT']                                         ="Subject";
                $lang['BULK_EMAIL_DATE']                                           ="Date";             
                $lang['BULK_EMAIL_LOGS_ID']                                        ="Id#";
                $lang['BULK_EMAIL_LOGS_STATUS']                                    ="Status";
                $lang['BULK_EMAIL_LOGS_START_DATE']                                    ="Start date";
                $lang['BULK_EMAIL_LOGS_END_DATE']                                    ="End date";
                $lang['EMAIL_LOGS_PAGE_TITLE']                                    ="Search email log";
                $lang['UNEXPECTED_MAX_FILE_UPLOAD_ERROR']                          ="You can upload 5000 records at one time";
                /********** profit and loss----------------------------*/
                $lang['TB_ROW_TOTAL_PROFIT']                          ="Net Profit";
                $lang['TB_BS_ACCOUNT_ASSET']                          ="Assets";
                $lang['TB_BS_ACCOUNT_LIABILITY']                          ="Liabilities";
                $lang['TB_ROW_TOTAL_ASSETS']                          ="Total Assets";
                $lang['TB_ROW_TOTAL_LIABILITES']                          ="Total Liabilities and Equity";
                
                /**************************CUSTOMERS***********************************/
                
                $lang['CUSTOMERS_ID']                          ="Id#";
                $lang['CUSTOMERS_PAGE_TITLE']                          ="Customer";
                $lang['CUSTOMERS_NAME']                          ="Customer name";
                $lang['CUSTOMERS_ADDRESS']                          ="Customer address";
                $lang['CUSTOMERS_ACTION']                          ="Actions";
                $lang['CUSTOMERS_ADD']                              ="Add Customer";
                $lang['CUSTOMERS_ADD_COMPANY_NAME']                              ="Company name";
                $lang['CUSTOMERS_ADD_STATUS']                              ="Status";
                $lang['CUSTOMERS_ADD_FIRST_NAME']                              ="First Name";
                $lang['CUSTOMERS_ADD_LAST_NAME']                              ="Last Name";
                $lang['CUSTOMERS_ADD_EMAIL']                              ="Email";
                $lang['CUSTOMERS_ADD_PHONE']                              ="Phone";
                $lang['CUSTOMERS_ADD_ADDRESS1']                              ="Address 1";
                $lang['CUSTOMERS_ADD_ADDRESS2']                              ="Address 2";
                $lang['CUSTOMERS_ADD_ADDRESS3']                              ="Address 3";
                $lang['CUSTOMERS_ADD_MOBILE']                              ="Mobile"; 
                $lang['CUSTOMER_SUCCESSS']                              ="added successfully"; 
                $lang['CUSTOMERS_NAME']                              ="Name";
                $lang['CUSTOMERS_COMPANY']                              ="Company";
                $lang['CUSTOMERS_EMAIL']                              ="Email";
                $lang['CUSTOMERS_MOBILE']                              ="Mobile";
                $lang['CUSTOMERS_DATE']                              ="Added On";
                $lang['CUSTOMER_DELETE_SUCCESSS']                              ="deleted successfully";
                $lang['CUSTOMERS_EDIT']                              ="Edit Customer";
                $lang['CUSTOMER_UPDATE']                              ="Updated successfully";
                $lang['CLIENT_CUSTOMER_STATE_CHANGE_TITLE']                              ="Changed customer status";
                $lang['CLIENT_CUSTOMER_CHANGE_STATUS']                              ="Do you want to change customer status";
                $lang['CUSTOMER_STATUS_SUCCESSS']                              ="status changed successfully!";
                $lang['CUSTOMERS_ADD_CITY']                              ="City/Town";
                $lang['CUSTOMERS_ADD_STATE']                              ="State/Province";
                $lang['CUSTOMERS_ADD_POSTCODE']                              ="Postcode";
				$lang['CUSTOMERS_ADD_PAYMENT_TERMS']                            ="Payment Terms (In days)";
                $lang['CUSTOMERS_ADD_COUNTRY']                              ="Country";
                $lang['CUSTOMERS_ADD_VAT_REGISTRATION']                              ="VAT Registration No.";
                /****************************Suppliers****************************************/
                $lang['SUPPLIERS_ID']                          ="Id#";
                $lang['SUPPLIERS_PAGE_TITLE']                          ="Supplier";
                $lang['SUPPLIERS_NAME']                          ="Supplier";
                $lang['SUPPLIERS_ADDRESS']                          ="Supplier address";
                $lang['SUPPLIERS_ACTION']                          ="Actions";
                $lang['SUPPLIERS_ADD']                              ="Add Supplier";
                $lang['SUPPLIERS_ADD_COMPANY_NAME']                              ="Company name";
                $lang['SUPPLIERS_ADD_STATUS']                              ="Status";
                $lang['SUPPLIERS_ADD_FIRST_NAME']                              ="First Name";
                $lang['SUPPLIERS_ADD_LAST_NAME']                              ="Last Name";
                $lang['SUPPLIERS_ADD_EMAIL']                              ="Email";
                $lang['SUPPLIERS_ADD_PHONE']                              ="Phone";
                $lang['SUPPLIERS_ADD_ADDRESS1']                              ="Address 1";
                $lang['SUPPLIERS_ADD_ADDRESS2']                              ="Address 2";
               $lang['SUPPLIERS_ADD_ADDRESS3']                              ="Address 3";
                $lang['SUPPLIERS_ADD_MOBILE']                              ="Mobile"; 
                $lang['SUPPLIERS_SUCCESSS']                              ="added successfully"; 
                $lang['SUPPLIERS_NAME']                              ="Name";
                $lang['SUPPLIERS_COMPANY']                              ="Company";
                $lang['SUPPLIERS_EMAIL']                              ="Email";
                $lang['SUPPLIERS_MOBILE']                              ="Mobile";
                $lang['SUPPLIERS_DATE']                              ="Added On";
                $lang['SUPPLIERS_DELETE_SUCCESSS']                              ="deleted successfully";
                $lang['SUPPLIERS_EDIT']                              ="Edit Supplier";
                $lang['SUPPLIERS_UPDATE']                              ="Updated successfully";
                $lang['CLIENT_SUPPLIERS_STATE_CHANGE_TITLE']                              ="Changed supplier status";
                $lang['CLIENT_SUPPLIERS_CHANGE_STATUS']                              ="Do you want to change supplier status";
                $lang['SUPPLIERS_STATUS_SUCCESSS']                              ="status changed successfully";
                $lang['SUPPLIERS_ADD_CITY']                              ="City/Town";
                $lang['SUPPLIERS_ADD_STATE']                              ="State/Province";
                $lang['SUPPLIERS_ADD_POSTCODE']                              ="Postcode";
                $lang['SUPPLIERS_ADD_COUNTRY']                              ="Country";
                $lang['SUPPLIERS_ADD_VAT_REGISTRATION']                              ="VAT Registration No.";
                
				/* Upload client */
				$lang['CLIENT_UPLOAD_BUTTON']                                	  = "Upload Clients";
				$lang['BULK_CLIENT_UPLOAD_TITLE']                                 = "Upload Bulk Client";
				$lang['BULK_CLIENT_UPLOAD_DIALOG_TITLE']                          = "Message";
				$lang['BULK_CLIENT_UPLOAD_DIALOG_TEXT']                           = "Please select the file first!";
				$lang['BULK_CLIENT_UPLOAD_PATTERN_MATCH_ERROR']                   = "Pattern mismatch,please check the Bulk Client template pattern!";
				$lang['BULK_CLIENT_UPLOAD_DUPPLIACATE_ENTRY']                     = "The statement entry at rows %s already exists in our record, please delete/change them first!";
				$lang['BULK_CLIENT_UPLOAD_NO_RECORD']                             = "Empty Data can not be uploaded! Both sheet should have atleast one record";
				$lang['BULK_CLIENT_UPLOAD_UPDATE_DUPPLIACATE_ENTRY']              = "The statement entry at rows %s already exists in our record, please change them first!";
				$lang['BULK_CLIENT_UPLOAD_ERROR_CLIENT']                         = "Upload only 100 records in Client Detail Sheet.";
				$lang['BULK_CLIENT_UPLOAD_ERROR_Company']                        = "Upload only 100 records in Company Detail Sheet.";
				$lang['ACCOUNTANT_BULK_NI_NUMBER']                                         =	"NI number. e.g AB 12 34 56 C or AB123456C";
				$lang['ACCOUNTANT_BULK_UTR_NUMBER']                                   	   =	"UTR NUMBER should be 10-digit number";
				$lang['ACCOUNTANT_BULK_POSTAL_CODE']                                    =	"Postal Code.e.g AB22 9AB";
				$lang['ACCOUNTANT_BULK_VAT_REGISTRATION_NUMBER']                       	   =	"VAT registration number should be 9-digit number.";
				$lang['ACCOUNTANT_BULK_BANK_ACCOUNT_NUMBER']                           		 =	"Account Number should be 8-digit number";
				$lang['ACCOUNTANT_BULK_BANK_SHORT_CODE']                                =	"Sort code should be 6-digit number";
				$lang['ACCOUNTANT_BULK_COMPANY_REGISTRATION_NUMBER']                   	   =	"Company Reg Num 12345678 or AB123456";
                                
                              /* Purchase*/
                                
                                $lang['PURCHASE_PAGE_LABLE_PURCHASE']                                       = "Purchase";
                                $lang['CLIENT_PURCHASE_FORM_LABEL_ID']                                       = "Purchase ID";
                                $lang['CLIENT_PURCHASE_FORM_LABEL_NAME']                                       = "Supplier Name";
                                $lang['CLIENT_BUTTON_ADD_PURCHASE']                                       = "Add Purchase";
                                $lang['CLIENT_BUTTON_ADD_PURCHASE_DEBIT_NOTE']                                       = "Add Debit Note";
                                $lang['CLIENT_PURCHASE_TABLE_LABEL_PURCHASE']                                       = "Purchase ID";
                                $lang['CLIENT_PURCHASE_TABLE_LABEL_DDATE']                                       = "Purchase Date";
                                $lang['CLIENT_PURCHASE_NEW_PURCHASE_TITLE']                                       = "New Purchase";
                                $lang['CLIENT_PURCHASE_FORM_LABEL_SUPPLIER']                                       = "Supplier";
                                $lang['CLIENT_PURCHASE_FORM_LABEL_ADD_SUPPLIER']                                       = "Add Supplier";
                                $lang['CLIENT_PURCHASE_FORM_LABEL_ADD_SUPPLIER']                                       = "Add Supplier";
                                $lang['CLIENT_PURCHASE_LABLE_QUANTITY']                                       = "Quantity";
                                $lang['CLIENT_PURCHASE_LABLE_UNIT_PRICE']                                       = "Units";
                                $lang['PURCHASE_PAGE_LABLE_SUPPLIER_NAME']                                       = "Supplier Name";
                                $lang['PURCHASE_PAGE_LABLE_SUPPLIER_ADDRESS']                                       = "Supplier ";

                                $lang['CLIENT_PURCHASE_NEW_PURCHASE_TITLE']                                  = "New Purchase";
                                $lang['CLIENT_PURCHASE_CREATE_PURCHASE_TITLE']                               = "Created Record";
                                $lang['CLIENT_PURCHASE_EDIT_PURCHASE_TITLE']                                 = "Edit Invoice";
                                $lang['CLIENT_PURCHASEE_PAID_PURCHASE_TITLE']                                 = "Paid Invoice";

                                $lang['CLIENT_PURCHASE_ERROR_EMPTY_ITEM']                                   = "Blank record can not save/created, please add at least one invoice item!";
                                $lang['CLIENT_PURCHASE_ERROR_EMPTY_NAME']                                   = "Please enter the supplier name!";
                                $lang['CLIENT_PURCHASE_ERROR_SELECT_NAME']                                  = "Please select the supplier name!";
                                $lang['CLIENT_PURCHASE_ERROR_PURCHASE_DATE']                                 = "Please select the date first!";
                                $lang['CLIENT_PURCHASE_EMPTY_ERROR']                                        = "Empty record can not be created, please enter some details!";
                                $lang['CLIENT_PURCHASE_DESCRIPTION_ERROR']                                  = "Please enter the description for the {s} item!";
                                $lang['CLIENT_PURCHASE_UNIT_ERROR']                                         = "Please enter the unit price for the {s} item!";
                                $lang['CLIENT_PURCHASE_UNIT_ERROR']                                         = "Please enter the unit price for the {s} item!";
                                $lang['CLIENT_PURCHASE_QUANTITY_ERROR']                                     = "Please enter the Quantity value for the {s} item!";
                                $lang['CLIENT_PURCHASE_UNITPRICE_ERROR']                                    = "Please enter the units value for the {s} item!";
                                $lang['CLIENT_PURCHASE_DELETE_ERROR']                                       = "Are you sure, you want to delete this record?";
                                $lang['CLIENT_PURCHASE_COPY_ERROR']                                         = "Are you sure, you want to copy this record?";
                                $lang['CLIENT_PURCHASE_PAID_ERROR']                                         = "Are you sure, you want to mark it as paid?";
                                $lang['CLIENT_PURCHASE_QUANTITY_UNIT_ERROR']                                = "Please enter the quantity and unit price value for the {s} item!";
                                $lang['CLIENT_PURCHASE_CREATE_SUCCESS']                                     = "The record %s has been created successfully.";
                                $lang['CLIENT_PURCHASE_SAVE_SUCCESS']                                       = "The record has been saved as draft successfully.";
                                $lang['CLIENT_PURCHASE_COPY_SUCCESS']                                       = "The record %s has been copied successfully.";
                                $lang['CLIENT_PURCHASE_DELETE_SUCCESS']                                     = "The record %s has been deleted successfully.";
                                $lang['CLIENT_PURCHASE_PAID_SUCCESS']                                       = "The record %s has been marked as paid successfully.";
                                $lang['CLIENT_PURCHASE_UPDATE_SUCCESS']                                     = "The record %s has been updated successfully.";
                                $lang['CLIENT_PUCHASE_TABLE_LABEL_NAME']                                     = "Supplier Name";
                                
                            $lang['PURCHASE_PDF_TEXT_ONE']                                              = "Purchase No : ";
                            $lang['PURCHASE_PDF_TEXT_TWO']                                              = "Purchase Date : ";
                            $lang['PURCHASE_PDF_TEXT_THREE']                                            = "Due By Date : ";
                            $lang['PURCHASE_PDF_TEXT_FOUR']                                             = "PURCHASE TO :";
                            $lang['PURCHASE_PDF_TEXT_FIVE']                                             = "S.No.";
                            $lang['PURCHASE_PDF_TEXT_SIX']                                              = "VAT NUMBER";
                            $lang['PURCHASE_PDF_TEXT_SEVEN']                                            = "BANK";
                            $lang['PURCHASE_PDF_TEXT_EIGHT']                                            = "SORT CODE";
                            $lang['PURCHASE_PDF_TEXT_NINE']                                             = "ACCOUNT";
                            $lang['PURCHASE_PDF_TEXT_TEN']                                              = "Company's Registration Number : ";
                            $lang['PURCHASE_PDF_TEXT_ELEVEN']                                           = "Registered in ";

                            $lang['PURCHASE_VAT_COLUMN_PERIODS']                                        = "VAT Periods";
                            $lang['PURCHASE_VAT_COLUMN_FROM']                                           = "From";
                            $lang['PURCHASE_VAT_COLUMN_TO']                                             = "To";
                            $lang['PURCHASE_VAT_COLUMN_TOTAL_SALES']                                    = "Total Value of Sales";
                            $lang['PURCHASE_VAT_COLUMN_DUE']                                            = "VAT Due";
                            $lang['PURCHASE_VAT_COLUMN_STATUS']                                         = "Status";
                            $lang['PURCHASE_VAT_COLUMN_ACTION']                                         = "Action";
                            $lang['PURCHASE_VAT_NO_RECORD']                                             = "No record available";

$lang['CHOOSE_COMPANY']         			= "Choose company";