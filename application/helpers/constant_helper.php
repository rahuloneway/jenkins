<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php

/* Get the application settings from the database */
$config = settings();
/* Cashman Constant variables */
define('CASHMAN_DATE_FORMATE','dd-mm-yy');
if(count($config) > 0)
{
	define('INVOICE_PAGINATION_LIMIT',$config['Invoice_listing']);
	define('EXPENSE_PAGINATION_LIMIT',$config['Expense_listing']);
	define('EXPENSE_REPORT_PAGINATION_LIMIT',$config['Expense_report']);
	define('DIVIDEND_PAGINATION_LIMIT',$config['Dividend_listing']);
	define('CLIENT_LISTING_PAGINATION_LIMIT',$config['Client_listing']);

    define('EMAIL_PAGINATION_LIMIT',$config['Email_listing']);
	define('ACTION_LOG_PAGINATION_LIMIT', $config['Action_log_listing']);
	define('BANK_PAGINATION_LIMIT',$config['Bank_listing']);
	define('SALARY_PAGINATION_LIMIT',$config['Bank_listing']);
	define('ACCOUNTANT_LISTING_PAGINATION_LIMIT',$config['Bank_listing']);
	define('JOURNAL_LISTING_PAGINATION_LIMIT',$config['Journal_listing']);
	
	define('TB_LEDGER_LISTING_PAGINATION_LIMIT',$config['Ledger_listing']);
        define('TERM_CONDITIONS_PAGINATION_LIMIT',$config['Term_listing']);

	define('CAR_MILEAGE_COST',$config['Car_mileage_cost']);
	define('BIKE_MILEAGE_COST',$config['Bike_mileage_cost']);
	define('CYCLE_MILEAGE_COST',$config['Bicycle_milege_cost']);
	define('MILEAGE_EXCEED_COST',$config['Car_mileage_overdue_cost']);
	define('MILEAGE_DISTANCE_LIMIT',$config['Travelling_distance']);

	define('CONTACTUS_EMAIL',$config['Contact_email']);
	define('EXPENSE_TEMPLATE_FIRST_TEXT',$config['Expense_template_text_one']);
	define('EXPENSE_TEMPLATE_SECOND_TEXT',$config['Expense_template_text_two']);
	define('UPLOAD_FILE_SIZE',$config['Signature_image_limit']);
	define('LOGO_UPLOAD_FILE_SIZE',($config['Logo_image_limit']*1024));
	define('CASHMAN_FROM_EMAIL_ADDRESS',$config['Contact_email']);
    define('TAX_FREE_DIVIDEND_ALLOW',$config['tax_free_dividend_allow']);
    define('BASIC_DIVIDEND_TAX',$config['basic_dividend_tax']);
}
define('VAT_QUATER_ONE_FROM','01-01-2015');
define('VAT_QUATER_ONE_TO','31-01-2015');
define('VAT_PERCENT_ONE','13.5');
define('VAT_QUATER_TWO_FROM','02-02-2015');
define('VAT_QUATER_TWO_TO','28-01-2015');
define('VAT_PERCENT_TWO','15');
define('VAT_QUATER_THREE_FROM','03-01-2015');
define('VAT_QUATER_THREE_TO','31-01-2015');
define('VAT_PERCENT_THREE','13.5');
define('VAT_QUATER_FOUR_FROM','01-01-2015');
define('VAT_QUATER_FOUR_TO','30-01-2015');
define('VAT_PERCENT_FOUR','20');
define('DIVIDEND_TAX_PERCENT','0.09');
define('DATE_FORMAT_REGEX','/^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/');
define('APP_START_YEAR','2014');



define('PA_PERCENTAGE','0.00');
define('ZRB_PERCENTAGE','0.00');
define('BRB_PERCENTAGE','0.075');
define('HRB_PERCENTAGE','0.325');
define('ATRB_PERCENTAGE','0.381');