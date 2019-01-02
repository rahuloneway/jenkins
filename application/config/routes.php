<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = 'errors';
$route['translate_uri_dashes'] = FALSE;

/*	For login page */
$home_page = $this->config->item('base_url').'home/';
$route[$this->config->item('base_url')] 	= $home_page;
$route['recovery'] 							= 'home/password_recovery';
$route["due_vat_mail_acction/(:any)"]		= "home/dueVatMailAcction/$1";


/*-------------------------------  ACCOUNTANT  ------------------------------*/

$route["dashboard"] 					= "accountant/dashboard/index";
$route["client_listing"] 				= "accountant/accountant/client";
$route["client_listing/(:num)"] 		= "accountant/accountant/client/$1";

$route["reset"] 						= "accountant/accountant/reset";
$route["reset_accountants"] 			= "accountant/accountants/reset";
$route["accountants"] 					= "accountant/accountants/index";
$route["accountant_update"] 			= "accountant/accountants/update";
$route["accountant_save"]				= "accountant/accountants/save";
$route["accountant_update_profile"] 	= "accountant/accountants/update_profile";

$route["checkEmail"] 					= "accountant/accountants/checkEmail";
$route["sorting"] 						= "accountant/accountants/sorting";
$route["checkEmail_accoutants"] 		= "accountant/accountant/checkEmail";
$route["deleteImage"] 					= "accountant/accountant/deleteImage";
$route["review"] 						= "accountant/accountant/review";
$route["client_sorting"] 				= "accountant/accountant/client_sort";
$route["configuration"] 				= "accountant/configuration/index";
$route["add_client"]					= "accountant/accountant/addClient";
$route["client_access/(:any)/(:any)"] 	= "accountant/accountant/clientAccess/$1/$2";
$route["resend_email/(:any)"] 			= "accountant/accountant/resendEmail/$1";
$route["update_client/(:any)/(:any)"] 	= "accountant/accountant/item/$1/$2";
$route["add_accountant"] 				= "accountant/accountants/forms";
$route["update_accountant/(:any)"] 		= "accountant/accountants/forms/$1";
$route["profile/(:any)"]				= "accountant/accountants/profile/$1";
$route["logout"]						= "accountant/accountant/logout";
$route["updateablecompanyid"] 			= "accountant/accountant/updateablecompanyid";
$route["addnewcompanysession"] 			= "accountant/accountant/addNewCompanySession";
$route["validateCompanyName"] 			= "accountant/accountant/validateCompanyName";

/*----------------------------------  CLIENT  ---------------------------------*/

$route["client_dashboard"]						= "client/index";
$route["edit-access"]						= "client/editClientAccess";

$route["bank_statements"]						= "clients/banks";
$route["bank_statements/(:num)"]				= "clients/banks/index/$1";
$route["bank_statements/before_upload"]			= "clients/banks/before_upload";
$route["bank_statements/add"]					= "clients/banks/addBankStatement";


$route["invoices"]								= "client/invoices";
$route["invoices/(:num)"]						= "client/invoices/$1";
$route["edit_invoice"]							= "client/editInvoice";
$route["update_invoice"]						= "client/updateInvoice";
$route["perform_action"]						= "client/action";
$route["save_invoice"]							= "client/saveInvoice";
$route["getParentCategory"]       			    = "client/getParentCategory";

$route["expenses"]								= "clients/expenses/index";
$route["expenses/(:num)"]						= "clients/expenses/index/$1";
$route["expenses/(:any)"]						= "clients/expenses/$1";

$route["expenses_save"]							= "clients/expenses/save";
$route["expense_upload_credit"]					= "clients/expenses/uploadCredit";
$route["expense_upload_expense"]				= "clients/expenses/uploadExpenses";
$route["update_expense"]						= "clients/expenses/update";

$route["expense_form"]							= "clients/expenses/expenseForm";
$route["expense_action"]						= "clients/expenses/action";
$route["expense_get_car_cost"]					= "clients/expenses/get_car_cost";
$route["expense_get_car_miles"]					= "clients/expenses/getCarMiles";
$route["getParentCategoryChild"]   				= "client/getParentCategoryChild";
$route["expense_clean"]							= "clients/expenses/clean/";
$route["expense_expense_sort"]					= "clients/expenses/expenseSort";
$route["nextexpenses"]							= "clients/expenses/nextexpenses";
$route["checkVatApplicable"]					= "clients/expenses/checkVatApplicable";

$route["dividends"]								= "clients/dividend";
$route["dividend/(:num)"]						= "clients/dividend/index/$1";
$route["new_dividend"]							= "clients/dividend/newDividend";
$route["dividendcorn"]							= "clients/dividend/dividendcorn";
$route["updateshareholderAddress"]              = "clients/dividend/updateshareholderAddress";

$route["salary"]								= "clients/salary";
$route["salary/(:num)"]							= "clients/salary/index/$1";
$route["salary_action"]							= "clients/salary/action";
$route["salary_payeeform"]						= "clients/salary/payeeform";
$route["salaryactions"]							= "clients/salary/salaryAction";
$route["payeelist"]								= "clients/salary/payee_list/";
$route["salary_ajax_listing"]					= "clients/salary/ajax_listing/";
$route["salary_ajax_payee_listing"]				= "clients/salary/ajax_payee_listing";
$route["update_payee"]							= "clients/salary/updatePayee";
$route["payee_edit_form"]						= "clients/salary/payee_edit_form";


$route["documents"]								= "clients/documents";
$route["check_file"]							= "clients/documents/checkFile";
$route["document_form"]							= "clients/documents/form";


$route["journals"]                                                       = "clients/journals";
$route["journals/(:num)"]                                                = "clients/journals/index/$1";
$route["journals_form"]                                                  = "clients/journals/forms";
$route["journal_search"]                                                 = "clients/journals/search";
$route["journal_clean"]                                                  = "clients/journals/clean/";
$route["journals_uploadform"]                                            = "clients/journals/journaluploadForm";
$route["journals_uploadsave"]                                            = "clients/journals/journaluploadSheet";

$route["trial_balances"]						= "clients/trial_balances";
$route["ledger_accounts"]						= "clients/trial_balances/ledger_accounts";
$route["ledger_account/(:any)"]					= "clients/trial_balances/ledger_accounts/$1";
$route["ledger_accounts/(:any)/(:num)"]			= "clients/trial_balances/ledger_accounts/$1/$2";
$route["clean"]									= "clients/trial_balances/clean";
$route["get_trialbalance_to_file"]				= "clients/trial_balances/getTBToFile";
$route["show_ledger_details"]					= "clients/trial_balances/showLedgerDetails";

$route["contactus"]								= "clients/contactus";
$route["save_form_contactus"]					= "clients/contactus/save";

$route["notes"]									= "clients/notes";

$route["logout"]								= "client/logout";

$route["accountant_view"]						= "client/accessAccountant";

$route['signout']								= "accountant/accountant/logout";
$route["configuration/(:any)"] 					= "accountant/Configuration/resetemailconfig";

// Bulk Upload
$route["bulkupload"] 					= "accountant/bulkupload/index";
$route["bulkupload/(:num)"] 			= "accountant/bulkupload/index/$1";
$route["bulkupload/before_bulk_upload"]	= "accountant/bulkupload/before_bulk_upload";
/*-----------------------------------Terms And Conditions---------------------------------*/

$route["terms_conditions"]						= "accountant/terms/index";
$route["terms_conditions/(:any)"]				 = "accountant/terms/index/$1";
$route["termCondition"]						= "client/termCondition";
$route["sendMail/(:any)"]						= "accountant/terms/sendMail/$1";
/*----------------------------------  Backup  ---------------------------------*/

$route["backup"] 								= "cron/backup/backup_cron";
$route["configuration/(:any)"] 					= "accountant/Configuration/resetemailconfig";
/*-----------------------------------Email------------------------------------*/
$route["email"] 								= "accountant/email";
$route["emailTemplate"] 						= "accountant/email/selemailTemplate";
$route["email/(:any)"]						    = "accountant/email/index/$1";
$route["add_email_template"] 					= "accountant/email/addemailTemplate";
$route["sendMail"] 								= "accountant/email/sendMail";
$route["send_mail_template"] 					= "accountant/email/sendMailTemplate";
//$route["due_vat_mail_acction/(:any)"]			= "accountant/email/dueVatMailAcction/$1";


/*-----------------------------------Expense Report-------------------------------*/
$route["expense_report"]							= "clients/expenses/expense_report";
$route["expense_report/(:num)"]						= "clients/expenses/expense_report/$1";
$route["expense_report/(:any)"]						= "clients/expenses/$1";
$route["expense_report_clean"]						= "clients/expenses/cleanreport/";
$route["expense_report_sheet"]						= "clients/expenses/getreportsheet";

/*------------------------------- Bulk Client Routes 20-11-2015 ------------------------------*/

$route['accountant/bulkclient/showClientform']      = 'accountant/ShowClient_form';
$route["accountant/bulkclients"]                    = "accountant/bulkclient/before_bulkclient";

/*------------------------------- customers ------------------------------*/

$route["customers"]                                                      = "clients/customers";
$route["customers/(:any)"]                                               = "clients/customers/index/$1";
$route["customer_invoices/(:any)/(:any)/(:any)"]                         = "clients/customers/invoices/$1/$2/$3";
//$route["customerInvoiceSearch/(:any)/(:any)/(:any)"]                     = "clients/customers/customerInvoiceSearch";

/*------------------------------- suppliers ------------------------------*/

$route["suppliers"]                                                      = "clients/suppliers";
$route["suppliers/(:any)"]                                               = "clients/suppliers/index/$1";

/*--------------------------------------purchase-------------------------------------*/
$route["purchases"]                                                      = "clients/purchase/index";
$route["edit_purchase"]                                                  = "clients/purchase/editPurchase";
$route["getsupplierUserDetail"]                                          = "clients/purchase/getSupplierUserDetail";

//$route["edit_invoice"]                                                 = "clients/purchase/editInvoice";
$route["update_purchase"]                                                = "clients/purchase/updateInvoice";
$route["perform_action_purchase"]                                        = "clients/purchase/action";
$route["save_purchase"]                                                  = "clients/purchase/saveInvoice";
$route["purchaseSort"]                                                   = "clients/purchase/invoiceSort";
$route["purchaseclean"]                                                  = "clients/purchase/clean";

/*--------------------------------------profit-------------------------------------*/
$route["profit"]                                                         = "clients/profitloss";
$route["ledger_account_profit"]                                          = "clients/profitloss/ledger_accounts";
$route["ledger_account_profit/(:any)"]                                   = "clients/profitloss/ledger_accounts/$1";
$route["get_trialbalance_to_file_profit_xlx"]                            = "clients/profitloss/getTBToFile";
$route["get_trialbalance_to_file_profit_pdf"]                            = "clients/profitloss/pdf/$1";

/*--------------------------------------Balance sheet-------------------------------------*/

$route["balance_sheet"]                                                  = "clients/Balancesheet";
$route["ledger_account_balance_sheet"]                                   = "clients/Balancesheet/ledger_accounts";
$route["ledger_account_balance_sheet/(:any)"]                            = "clients/Balancesheet/ledger_accounts/$1";
$route["get_trialbalance_to_balance_sheet_xlx"]                          = "clients/Balancesheet/getTBToFile";
$route["get_trialbalance_to_balance_sheet_pdf"]                          = "clients/Balancesheet/pdf/$1";

/*--------------------------------------Logs-------------------------------------*/

$route["logs"]                                                           = "clients/logs";
$route["logs/(:any)"]                                                    = "clients/logs/index/$1";
$route["show_log_details"]                                               = "clients/logs/showLogDetails";

/*------------------------------- Email Logs ------------------------------*/

$route["emaillogs"]                                                      = "clients/emaillogs";
$route["emaillogs/(:any)"]                                               = "clients/emaillogs/index/$1";

/*------------------------------- Bank Statements ------------------------------*/
$route["saveeditdbankstatment"]             = "clients/banks/saveEditdBankStatment";
$route["searchStatmentLinkItem"]            = "clients/banks/searchStatmentLinkItem";
$route["getStatmentLinkPage"]               = "clients/banks/getStatmentLinkPage";
$route["updateStatementsAssociate"]         = "clients/banks/updateStatementsAssociate";
$route["getAllStatmentForLink"]             = "clients/banks/getAllStatmentForLink";

$route["setcompany"] 						= "home/setCompanySession";
$route["choose-company"] 					= "home/chooseCompany";



/*------------------------------- API ------------------------------*/

// $route['api/Clientapi/vatsummary/']                                   = 'api/Clientapi/vatsummary/';

$route['api/Clientapi/login/(:any)/(:any)/(:any)']                    	 = 'api/Clientapi/login/uname/$1/password/$2/deviceId/$3';
// $route['api/Clientapi/login/(:any)/(:any)']                              = 'api/Clientapi/login/uname/$1/password/$2';
$route['api/Clientapi/logout/(:any)']                                    = 'api/Clientapi/logout/API-KEY/$1';
$route['api/Clientapi/dashboard/(:any)/(:any)/(:any)']                   = 'api/Clientapi/dashboard/companyId/$1/deviceId/$2/API-KEY/$3';

$route['api/Clientapi/payroll/(:num)/(:any)']				             = 'api/Clientapi/payroll/page/$1/API-KEY/$2';

$route['api/Clientapi/invoices/(:num)/(:any)']                           = 'api/Clientapi/invoices/page/$1/API-KEY/$2';
$route['api/Clientapi/invoice/(:num)/(:any)']                            = 'api/Clientapi/invoice/itemId/$1/API-KEY/$2';
$route['api/Clientapi/invoice/(:num)/(:any)']                            = 'api/Clientapi/invoice/invoiceId/$1/API-KEY/$2';
$route['api/Clientapi/invoicePdf/(:num)/(:any)']                         = 'api/Clientapi/invoicePdf/itemId/$1/API-KEY/$2';
$route['api/Clientapi/invoiceUserList/(:any)']                           = 'api/Clientapi/invoiceUserList/API-KEY/$1';
$route['api/Clientapi/invoiceUserDetail/(:num)/(:any)']                  = 'api/Clientapi/invoiceUserDetail/userId/$1/API-KEY/$2';
$route['api/Clientapi/expenses/(:num)/(:any)']                           = 'api/Clientapi/expenses/page/$1/API-KEY/$2';
$route['api/Clientapi/expense/(:num)/(:any)']                            = 'api/Clientapi/expense/itemId/$1/API-KEY/$2';
//$route['api/Clientapi/expenseCalMileage/(:any)']                       = 'api/Clientapi/expenseCalMileage/API-KEY/$1';
$route['api/Clientapi/expenseCalMileage/(:num)/(:num)/(:num)/(:any)'] 	 = 'api/Clientapi/expenseCalMileage/vehicleType/$1/miles/$2/previousMiles/$3/API-KEY/$4';
$route['api/Clientapi/expenseEmployeeList/(:any)']                       = 'api/Clientapi/expenseEmployeeList/API-KEY/$1';
$route['api/Clientapi/expenseCategoryList/(:any)']                       = 'api/Clientapi/expenseCategoryList/API-KEY/$1';
$route['api/Clientapi/expenseVehicleList/(:any)']                        = 'api/Clientapi/expenseVehicleList/API-KEY/$1';
$route['api/Clientapi/expenseVatType/(:any)']                            = 'api/Clientapi/expenseVatType/API-KEY/$1';
$route['api/Clientapi/expenseMiles/(:num)/(:any)/(:any)']                = 'api/Clientapi/expenseMiles/userId/$1/date/$2/API-KEY/$3';
$route['api/Clientapi/dividends/(:num)/(:any)']                          = 'api/Clientapi/dividends/page/$1/API-KEY/$2';
$route['api/Clientapi/dividend/(:num)/(:any)']                           = 'api/Clientapi/dividend/itemId/$1/API-KEY/$2';
$route['api/Clientapi/dividendPdf/(:num)/(:any)']                        = 'api/Clientapi/dividendPdf/itemId/$1/API-KEY/$2';
$route['api/Clientapi/dividendShareHolderList/(:any)']                   = 'api/Clientapi/dividendShareHolderList/API-KEY/$1';
$route['api/Clientapi/dividendDirectorsList/(:any)']                     = 'api/Clientapi/dividendDirectorsList/API-KEY/$1';
$route['api/Clientapi/dividendTotalShare/(:any)']                        = 'api/Clientapi/dividendTotalShare/API-KEY/$1';
$route['api/Clientapi/dividendUserShare/(:num)/(:any)']                  = 'api/Clientapi/dividendUserShare/userId/$1/API-KEY/$2';
$route['api/Clientapi/contactCategories/(:any)']                         = 'api/Clientapi/contactCategories/API-KEY/$1';
$route['api/Clientapi/userCompanies/(:any)']                         	 = 'api/Clientapi/userCompanies/$1/API-KEY';

$route['Lioptws/TryLogin/(:any)/(:any)'] 								 = 'Lioptws/TryLogin/$1/$2';