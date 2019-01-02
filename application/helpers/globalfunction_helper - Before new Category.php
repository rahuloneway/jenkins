<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* This function checks the Type of User */
if (!function_exists('userDasboard')) {

    function userDasboard($type = NULL) {
        switch ($type) {
            case 'TYPE_ACC':
                setRedirect(site_url() . 'client_listing');
                break;
            case 'TYPE_CLI':
                $ci = & get_instance();
                if ($ci->session->userdata('chooseCompanyRequired') == 'no')
                    setRedirect(site_url() . 'client');
                else
                    setRedirect(site_url() . 'setcompany');
                break;
            default:
                setRedirect(site_url());
                break;
        }
    }

    /*
     * 	This function will check the access level of the user on dashboard.
     * 	Return Value : 404 error if not corresponding access level.
    */

    function checkUserAccess($level = NULL) {
        $ci = & get_instance();
        $user = $ci->session->userdata('user');
        if ($user == false) {
            setRedirect(site_url());
        } elseif (!in_array($user['UserType'], $level)) {
            setRedirect(site_url());
        }
    }

    /*
     * 	This function returns the list of user types
    */

    function userTypeList() {
        $list = array(
            '0' => '-- Select user --',
            'TYPE_SUP' => 'Super User',
            'TYPE_IT' => 'Assistant',
            'TYPE_ACC' => 'Accountant'
        );
        return $list;
    }

    /*
     * 		This function will add the javascript files corresponding to the page accessed by the user
     *
     */

    function addScriptsFiles($page) {
        $ci = & get_instance();
        switch ($page) {
            case 'dashboard':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                $ci->load->view('client/dashboard_js');
                $ci->load->view('client/terms_conditions_js');

                break;
            case 'invoices':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/invoices/invoice_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'expenses':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/expenses/expenses_js');
                $ci->load->view('client/terms_conditions_js');
                //$ci->load->view('client/expenses/upload_js');
                break;
            case 'upload_expense':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                //$ci->load->view('client/expenses/upload_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'client_listing':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                $ci->load->view('accountant/client_js');
                $ci->load->view('accountant/bulkclient/bulkclient_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'add_client':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'client_update':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                break;
            case 'dividends':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/dividend/dividend_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'contactus':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                $ci->load->view('client/contactus/contactus_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'banks':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';

                $ci->load->view('client/banks/banks_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'bank_statements':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/banks/bank_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'documents':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/tree/screen.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/tree/jquery.treetable.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/tree/jquery.treetable.theme.default.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/tree/jquery.treetable.js"></script>';
                $ci->load->view('client/documents/document_js');
                $ci->load->view('client/terms_conditions_js');
                break;

            case 'terms_conditions':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/tinymce/tinymce.min.js"></script>';
                $ci->load->view('accountant/terms/term_js');

                break;
            case 'salary':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/salary/salary_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'accountants':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/tinymce/tinymce.min.js"></script>';
                $ci->load->view('accountant/accountants/accountant_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'journals':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/journals/journal_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'trial_balances':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/trial_balance/trial_balance_js');
                $ci->load->view('client/terms_conditions_js');
                break;

            case 'profitloss':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/profit_loss/trial_balance_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'balance_sheet':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client//balance_sheet/trial_balance_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'logs':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/logs/logs_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'customers':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/terms_conditions_js');
                $ci->load->view('client/customers/customers_js');
                break;
            case 'suppliers':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/terms_conditions_js');
                $ci->load->view('client/suppliers/suppliers_js');
                break;
            case 'purchases':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/terms_conditions_js');
                $ci->load->view('client/purchase/purchase_js');
                break;

            case 'ledger_accounts':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                $ci->load->view('client/trial_balance/ledger_accounts_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'configuration':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/tinymce/tinymce.min.js"></script>';
                $ci->load->view('accountant/configuration/configuration_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'accountant_dashboard':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('accountant/dashboard/dashboard_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'BulkUpload':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('accountant/dashboard/dashboard_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'notes':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/tinymce/tinymce.min.js"></script>';
                $ci->load->view('client/notes/notes_js');
                $ci->load->view('client/terms_conditions_js');
                break;
            case 'email':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/tinymce/tinymce.min.js"></script>';
                $ci->load->view('accountant/email/email_js');
                break;
            case 'emaillogs':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/tinymce/tinymce.min.js"></script>';
                $ci->load->view('client/emaillogs/emailogs_js');
                break;
            case 'expense_report':
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/cashman.css"/>';
                echo '<link rel="stylesheet" type="text/css" href="' . site_url() . 'assets/css/style_table.css"/>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jquery-ui.min.js"></script>';
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/jqueryAjax.js"></script>';
                $ci->load->view('client/expenses/expenses_js');
                $ci->load->view('client/terms_conditions_js');
                //$ci->load->view('client/expenses/upload_js');
                break;
            default:
                break;
        }
    }

    function getUserName($id) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $query = $ci->db->query('SELECT FirstName FROM ' . $prefix . 'users WHERE ID=' . $id);
        if ($query->num_rows() > 0) {
            $record = $query->result();
            $record = $record[0]->FirstName;
            return $record;
        } else {
            return FALSE;
        }
    }

    function getCutomername($id) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $query = $ci->db->query('SELECT first_name FROM ' . $prefix . 'customers WHERE ID=' . $id);
		//echo $ci->db->last_query();
        if ($query->num_rows() > 0) {
            $record = $query->result();
            $record = $record[0]->first_name;
            return $record;
        } else {
            return FALSE;
        }
    }
	function getSuppliername($id) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $query = $ci->db->query('SELECT first_name FROM ' . $prefix . 'suppliers WHERE id=' . $id);
		//echo $ci->db->last_query();
        if ($query->num_rows() > 0) {
            $record = $query->result();
            $record = $record[0]->first_name;
            return $record;
        } else {
            return FALSE;
        }
    }

    function getUserNameterm($id) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $query = $ci->db->query('SELECT FirstName,LastName FROM ' . $prefix . 'users WHERE ID=' . $id);
        if ($query->num_rows() > 0) {
            $record = $query->result();
            $record = $record[0]->FirstName . " " . $record[0]->LastName;
            return $record;
        } else {
            return FALSE;
        }
    }

    function getlogUserName($userId, $accountantId) {
        if ($accountantId == 0) {
            $accountantId = $userId;
        }
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $query = $ci->db->query("SELECT FirstName,LastName FROM cashman_users WHERE ID=$accountantId");
        if ($query->num_rows() > 0) {
            $record = $query->result();
            $record = $record[0]->FirstName . " " . $record[0]->LastName;
            return $record;
        } else {
            return FALSE;
        }
    }

    function safe($string) {
        $ci = & get_instance();
        //$string = $ci->db->escape_str($string);
        return xss_clean(trim($string));
    }

    function clean($string) {
        $ci = & get_instance();
        //$string = str_replace("'","",$string);
        $string = $ci->db->escape_str($string);
        return xss_clean(trim($string));
    }

    /* Clean space and special characters */

    function cleanString($text) {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => ' ', // Literally a single quote
            '/[“”«»„]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

    function clean_string_friendly($string) {
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/'), '', $string);
        return strtolower(trim($string, '-'));
    }

    function bulk_clean_desp($string) {
        $newstg = trim($string, '"');
        return $newstg;
        //$string = str_replace(' ', "  ", $string); // Replaces all spaces with hyphens.
        //$string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
        //return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
        // return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    function genericList($name = 'genericlist', $option = NULL, $selected = 0, $id = NULL) {
        if ($option == NULL) {
            $option = array(
                '0' => 'Select status',
                '1' => 'Draft',
                '2' => 'Created',
                '3' => 'Paid'
            );
        }
        $id = (empty($id)) ? '' : 'id=' . $id;
        $id = $id . ' class="form-control"';
        $options = array_filter($option);
        return form_dropdown($name, $options, $selected, $id);
    }

    /*
     * 		Function to generate the invoice/expenses pdf
     */

    function pdf($html = NULL, $name = null, $path = NULL, $action = 'D') {
        require_once(APPPATH . 'third_party/tcpdf/tcpdf.php');
        ob_start();
        ob_clean();
        ini_set('memory_limit', '-1');
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetPrintHeader(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetFont('dejavusans', '', 10);

        // add a page
        $pdf->AddPage();
        $pdf->Line(5, 5, $pdf->getPageWidth() - 5, 5);
        $pdf->Line($pdf->getPageWidth() - 5, 5, $pdf->getPageWidth() - 5, $pdf->getPageHeight() - 5);
        $pdf->Line(5, $pdf->getPageHeight() - 5, $pdf->getPageWidth() - 5, $pdf->getPageHeight() - 5);
        $pdf->Line(5, 5, 5, $pdf->getPageHeight() - 5);
        //$pdf->Image(site_url().'assets/images/logo.png');
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Output($name . '.pdf', $action);
    }

    function exCategories($type = 'GEN', $name = "ExpenseCategory", $selected = '0', $attribute = 'class="form-control"') {
        $ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->where(array('CategoryType' => $type, 'Status' => 1));
        $query = $ci->db->get('expenses_category');

        if ($query->num_rows() > 0) {
            $result = $query->result();

            foreach ($result as $key => $val) {
                $categories[$val->ID] = $val->Title;
            }
            if ($type != 'COMP') {
                $categories[0] = '--Select Category--';
            }
            asort($categories);
            return form_dropdown($name, $categories, $selected, 'id=' . $name . ' ' . $attribute);
        } else {
            return FALSE;
        }
    }
	
	function getExpenceCategoryName($id){  
		$ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('Title');
        $query = $ci->db->get_where('cashman_expenses_category', array('ID' => $id));
		$result = $query->result();
		return $result[0]->Title;
	}
	
	function getCategoryParent($catType=NULL){  
		$ci = &get_instance();
		$prefix = $ci->db->dbprefix;
		$ci->db->select('parent');		
		$ci->db->where("`catKey` IN (SELECT `key` FROM `cashman_expenses_category` where `CategoryType` = '$catType' AND `Status` != 0 AND `CategoryType` != '')", NULL, FALSE);
		$ci->db->group_by('parent'); 
		$query = $ci->db->get('cashman_trial_balance_categories');		
		//echo $ci->db->last_query(); //die;
		$result1 = $query->result();		
		$parent = array();
		foreach($result1 as $row){			
			$ci->db->select('id,title');		
			$ci->db->where('id', $row->parent);
			$query = $ci->db->get('trial_balance_categories');
			$result2 = $query->result();
			//echo $ci->db->last_query(); //die;
			if(!empty($result2[0])){
				$parent[] = $result2[0];
			}
			
		} 		
		if(!empty($parent)){
			return $parent;
		} else {
			return '';
		} 		
	}
	function getCategoryParentName($id){  
		$ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('key');
        $query = $ci->db->get_where('cashman_expenses_category', array('ID' => $id));
		$result = $query->result();
				
		if($result[0]->key != ''){
			$ci->db->select('parent');
			$query2 = $ci->db->get_where('cashman_trial_balance_categories', array('catKey' => $result[0]->key));
			$result2 = $query2->result();						
			if($result2[0]->parent != ''){
				$ci->db->select('title');
				$query3 = $ci->db->get_where('cashman_trial_balance_categories', array('id' => $result2[0]->parent));
				$result3 = $query3->result();
				return $result3[0]->title;
			}else{
				return "";
			}				
		}else{
			return "";
		}					
	}
	# Get Expense Category Key By Category Id 	
	function getCategoryKeyById($id){  
		$ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('key');
        $query = $ci->db->get_where('cashman_expenses_category', array('ID' => $id));
		$result = $query->result();
		return $result[0]->key;					
	}
	#Get Trial Balance Category 
	function getCategoryTbKeyId($key){  
		$ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('id');
		$query = $ci->db->get_where('cashman_trial_balance_categories', array('catKey' => $key, 'parent !=' => 0));
		$result = $query->result();
		return $result[0]->id;					
	}
		
	function getCategoryParentChild($parentId){  
		$ci = &get_instance();
		$prefix = $ci->db->dbprefix;
		$ci->db->select('id,title');
		$ci->db->where(array('CategoryType' => 'BANK'));
		$ci->db->where("`key` IN (SELECT catKey FROM cashman_trial_balance_categories where parent = '$parentId' AND catKey != '')",NULL,FALSE);
		$query = $ci->db->get('cashman_expenses_category');
		//echo $ci->db->last_query(); //die;
		if ($query->num_rows() > 0) {
			$result = $query->result();
		} else {
			$result = '';
		} 
		return $result;
		
		/*$ci = &get_instance();
		$prefix = $ci->db->dbprefix;
		$ci->db->select('expenses_category.id,expenses_category.title');
		$ci->db->from('trial_balance_categories');
		$ci->db->join('expenses_category', 'expenses_category.key = trial_balance_categories.catKey', 'INNER');
		$ci->db->where(array('trial_balance_categories.parent' => $parentId));
		$query = $ci->db->get();
		echo $ci->db->last_query(); //die;
		if ($query->num_rows() > 0) {
			$result = $query->result();
		} else {
			$result = '';
		} 
		return $result;	*/
		
	}
	function exCategoriesParents(){		
		$ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('id,title,catKey');
		$ci->db->where('`id` IN (SELECT parent FROM cashman_trial_balance_categories where parent > 0)', NULL, FALSE);
        $query = $ci->db->get('cashman_trial_balance_categories');
		
		$result = $query->result();
		return $result;		
	}
	function exCategoriesParentName($id){		
		/*$ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('id,title,catKey');
		$ci->db->where('catKey = ("SELECT key FROM cashman_expenses_category where ID = '.$id.'")', NULL, FALSE);
        $query = $ci->db->get('cashman_trial_balance_categories');
		echo $ci->db->last_query(); die;
		$result = $query->result();
		return $result;	*/
		
		$ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('key');
        $query = $ci->db->get_where('cashman_expenses_category', array('ID' => $id));
		$result = $query->result();
				
		if($result[0]->key != ''){
			$ci->db->select('parent');
			$query2 = $ci->db->get_where('cashman_trial_balance_categories', array('catKey' => $result[0]->key));
			$result2 = $query2->result();						
			if($result2[0]->parent != ''){
				$ci->db->select('title');
				$query3 = $ci->db->get_where('cashman_trial_balance_categories', array('id' => $result2[0]->parent));
				$result3 = $query3->result();
				return $result3[0]->title;
			}else{
				return "";
			}				
		}else{
			return "";
		}				
	}
	

    function getSortDirection($order = NULL, $index = NULL, $asc_order_value = array()) {
        /*
          $asc_order_value = array(
          'SORT_BY_ID'		=>	'i.InvoiceNumber ASC',
          'SORT_BY_NAME'		=>	'CONCAT(u.FirstName," ",u.LastName) ASC',
          'SORT_BY_AMOUNT'	=>	'i.InvoiceTotal ASC',
          'SORT_BY_CDATE'		=>	'i.AddedOn ASC',
          'SORT_BY_DDATE'		=>	'i.DueDate ASC'
          );
         */
        if (!empty($order)) {
            $first_match = explode(' ', $order);
            $second_match = explode(' ', $asc_order_value[$index]);
            if ($first_match[0] == $second_match[0]) {
                if ($order == $asc_order_value[$index]) {
                    echo '<i class="fa fa-sort-up"></i>';
                } else {
                    echo '<i class="fa fa-sort-desc"></i>';
                }
            }
        } else {
            echo '';
        }
    }

    function getSecurityQuestions() {
        $ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('QID,Question');
        $ci->db->where('Status', '1');
        $query = $ci->db->get('security_questions');
        if ($query->num_rows() > 0) {
            $question = $query->result();
            $questions = array('0' => 'Select question');
            foreach ($question as $key => $val) {
                $questions[$val->QID] = $val->Question;
            }
            return genericList('SecurityQuestions', $questions, 0, 'SecurityQuestions');
        } else {
            return array('0' => 'No question added yet');
        }
    }

    function getEmployeeInfo($id) {
        if (empty($id)) {
            return FALSE;
        }
        $ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('CONCAT(FirstName," ' . '",LastName) AS Name', false);
        $query = $ci->db->get_where('company_customers', array('ID' => $id));
        if ($query->num_rows() > 0) {
            $response = $query->result();
            $result = array('0' => 'Select Employee');
            foreach ($response as $key => $val) {
                $result['Name'] = $val->Name;
            }
            return $result['Name'];
        } else {
            return FALSE;
        }
    }

    function categoryName($id) {
        if (empty($id)) {
            return FALSE;
        }
        $ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('Title');
        $query = $ci->db->get_where('expenses_category', array('ID' => $id));
        if ($query->num_rows() > 0) {
            $response = $query->result();
            $result = array('0' => 'Select question');
            foreach ($response as $key => $val) {
                $result['Name'] = $val->Title;
            }
            return $result['Name'];
        } else {
            return FALSE;
        }
    }
	
	function categoryNameTrialCat($id) {		
        if (empty($id)) {
            return FALSE;
        }
        $ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('Title');
        $query = $ci->db->get_where('expenses_category', array('id' => $id));
		//echo $ci->db->last_query();die;
	    if ($query->num_rows() > 0) {
			
            $response = $query->result();
            $result = array('0' => 'Select question');
            foreach ($response as $key => $val) {
                $result['Name'] = $val->Title;
            }
            return $result['Name'];
        } else {
            return FALSE;
        }
    }

    function countries() {
        $ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $query = $ci->db->get('countries_list');
        //die('total : '.$query->num_rows());
        if ($query->num_rows() <= 0) {
            $default = array('1' => 'United Kingdom');
            return $default;
        } else {

            foreach ($query->result() as $key => $val) {
                $record[$val->ID] = $val->Name;
            }
            return $record;
        }
    }

    function companyName($id = null) {
        $ci = &get_instance();
        $prefix = $ci->db->dbprefix;
// 		$ci->db->select('Name');
// 		$ci->db->where('CID',$id);
// 		$query = $ci->db->get($prefix.'company');
        $query = $ci->db->query("SELECT `Name` FROM (`" . $prefix . "company`) WHERE `CID` = '" . $id . "'");
        //die('total : '.$query->num_rows());
        if ($query->num_rows() <= 0) {
            $default = array('1' => 'No Company');
            return $default;
        } else {
            $default = $query->result();
            return $default[0]->Name;
        }
    }

    function setRedirect($url) {
        redirect($url);
        die;
    }

    function countryName($id) {
        $ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('Name');
        $query = $ci->db->get_where('countries_list', array('ID' => $id));
        //die('total : '.$query->num_rows());
        if ($query->num_rows() <= 0) {
            $default = 'United Kingdom';
            return $default;
        } else {
            $default = $query->result();
            return $default[0]->Name;
        }
    }

    function tableColumns($table = NULL) {
        if (empty($table)) {
            return array();
        }

        $ci = &get_instance();
        $query = $ci->db->query('SHOW COLUMNS FROM ' . $table);
        //$result = array();
        $result = new stdClass;
        foreach ($query->result() as $key => $val) {
            $result->{$val->Field} = '';
        }
        return $result;
    }

    function salutationList($name = 'salutation', $selected = '1') {
        $options = array(
            '1' => 'Mr.',
            '2' => 'Mrs.',
            '3' => 'Miss',
            '4' => 'Dr.',
            '5' => 'Ms.',
            '6' => 'Prof.',
            '7' => 'Sir'
        );
        return genericList($name, $options, $selected, '');
    }

    function defaultVatQuatersArr() {
        $options = array(
            '0' => 'Select VAT quarters',
            '1' => 'Jan/April/July/Oct',
            '2' => 'Feb/May/Aug/Nov',
            '3' => 'Mar/Jun/Sep/Dec'
        );
        return $options;
    }

    function vatQuatersDropdown($selected = '', $attributes = '') {
        $options = defaultVatQuatersArr();
        return form_dropdown('VATQuaters', $options, $selected, $attributes);
    }

    function vatQuatersDropdownmulti($selected = '', $attributes = '') {
        $options = defaultVatQuatersArr();
        return form_dropdown('VATQuaters[]', $options, $selected, $attributes);
    }

    function vatQuaters($id, $y = "") {
        $options = defaultVatQuatersArr();
        if ($y == "")
            $y = date('Y');
        /*
          for($x = 1;$x <= 4;$x++)
          {
          $quarters[$x] = array(
          'FIRST'		=>	'01-'.$m.'-'.$m,
          'SECOND'	=>	'31-03-'.$m,
          );
          } */
        if ($id == 1) {

            $quaters = array(
                '1' => array(
                    'FIRST' => '01-11-' . $y,
                    'SECOND' => '31-01-' . ($y + 1),
                ),
                '2' => array(
                    'FIRST' => '01-02-' . $y,
                    'SECOND' => '30-04-' . $y,
                ),
                '3' => array(
                    'FIRST' => '01-05-' . $y,
                    'SECOND' => '30-07-' . $y,
                ),
                '4' => array(
                    'FIRST' => '01-08-' . $y,
                    'SECOND' => '31-10-' . $y,
                ),
            );
        } elseif ($id == 2) {
            $febEnd = days_in_month('02', ($y + 1));
            $quaters = array(
                '1' => array(
                    'FIRST' => '01-12-' . $y,
                    'SECOND' => $febEnd . '-02-' . ($y + 1),
                ),
                '2' => array(
                    'FIRST' => '01-03-' . $y,
                    'SECOND' => '31-05-' . $y,
                ),
                '3' => array(
                    'FIRST' => '01-06-' . $y,
                    'SECOND' => '31-08-' . $y,
                ),
                '4' => array(
                    'FIRST' => '01-09-' . $y,
                    'SECOND' => '30-11-' . $y,
                ),
            );
        } elseif ($id == 3) {
            $quaters = array(
                '1' => array(
                    'FIRST' => '31-01-' . $y,
                    'SECOND' => '31-03-' . $y,
                ),
                '2' => array(
                    'FIRST' => '01-04-' . $y,
                    'SECOND' => '30-06-' . $y,
                ),
                '3' => array(
                    'FIRST' => '01-07-' . $y,
                    'SECOND' => '30-09-' . $y,
                ),
                '4' => array(
                    'FIRST' => '01-10-' . $y,
                    'SECOND' => '31-12-' . $y,
                ),
            );
        } else {
            $quaters = array();
        }
        return $quaters;
    }

    function cDate($date) {
        if (empty($date) || $date == '0000-00-00') {
            return '';
        } else {
            return date('d-m-Y', strtotime($date));
        }
    }

    function vDate($date) {
        if (empty($date) || $date == '0000-00-00') {
            return '';
        } else {
            return date('d/m/Y', strtotime($date));
        }
    }

    function mDate($date) {
        if (empty($date) || $date == '0000-00-00') {
            return '';
        } else {
            return date('Y-m-d', strtotime($date));
        }
    }

    function lDate($date) {
        if (empty($date) || $date == '0000-00-00') {
            return '';
        } else {
            return date('d M Y', strtotime($date));
        }
    }

    function fieldData($field) {
        if (isset($field)) {
            return $field;
        } else {
            return '';
        }
    }

    function sendEmail($email) {
        /* Send mail to the user will do it later on */

        $config = Array(
            'mailtype' => 'html',
            'wordwrap' => TRUE,
            'charset' => 'iso-8859-1',
        );

        $ci = & get_instance();
        $ci->load->library('email', $config);
        $ci->email->set_newline("\r\n");
        $ci->email->from(CASHMAN_FROM_EMAIL_ADDRESS, 'Cashman Admin');
        $ci->email->to($email['To']);
        $ci->email->subject($email['Subject']);
        $ci->email->message($email['Message']);
        if ($ci->email->send()) {
            return TRUE;
        } else {
            //echo show_error($ci->email->print_debugger());die;
            return FALSE;
        }
        return true;
    }

    function calculate_due_vat($type = NULL, $invData, $paidDate = '', $user = array()) {
        if ($type == 'flat') {
            $totalAmount = $invData->InvoiceTotal;
            if (strtotime($paidDate) < strtotime(cDate($user['EndDate']))) {
                $amount = ($totalAmount * $user['PercentRateAfterEndDate'] / 100);
            } else {
                $amount = ($totalAmount * ($user['PercentRate']) / 100);
            }
        } else {
            $amount = $invData->Tax;
        }
        return $amount;
    }

    function getFileName($id = NULL) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        if (!empty($id)) {
            $ci->db->select('FName');
            $query = $ci->db->get_where('files', array('ID' => $id));

            if ($query->num_rows() > 0) {
                $response = $query->result();
                return $response[0]->FName;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    function systemEntries($index) {
        if (count($index) > 0) {
            $ci = & get_instance();
            $prefix = $ci->db->dbprefix;
            $user = $ci->session->userdata('user');

            $data = array(
                'AddedBy' => $user['UserID'],
                'AddedOn' => date('Y-m-d')
            );
            $data[$index['index']] = $index['value'];

            $ci->db->insert('system_entries', $data);
            if ($ci->db->affected_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function checkEntries($date = NULL, $amount = NULL, $items = array(), $sheet = array(), $category = "") {
        //echo '<pre>';print_r($sheet);echo '</pre>';//die;
        $statement_cases = array(
            '0' => 'Dividends'
        );
        if (count($items) > 0) {
            $status = array();

            foreach ($items as $key => $val) {
                if ($amount >= 0) {
                    //echo '<br/>Amount : '.$amount.' - '.$val->InvoiceTotal.' Date : '.$date.' - '.$val->InvoiceDate;DIE;
                    if ($amount == $val->InvoiceTotal && $date == $val->InvoiceDate) {
                        $status[] = array(
                            'ID' => $val->InvoiceID,
                            'Status' => 'match',
                            'UnsetKey' => $key,
                            'Type' => 'I',
                            'Number' => statementType($val->InvoiceID, 'I')
                        );
                    }
                } elseif ($amount < 0) {
                    //echo 'Amount : '.$amount;die;
                    $am = explode('-', $amount);
                    $am = $am[1];
                    if (categoryName($category) != $statement_cases[0]) {
                        //echo 'Amount : '.$am;die;
                        if (in_array($am, $sheet)) {
                            $id = array_search($am, $sheet);
                            $status[] = array(
                                'ID' => $id,
                                'Status' => 'match',
                                'UnsetKey' => $key,
                                'Type' => 'E',
                                'Number' => statementType($id, 'E')
                            );
                            break;
                        }
                    } else {
                        if ($am == $val->NetAmount && $date == $val->DividendDate) {
                            $status[] = array(
                                'ID' => $val->DID,
                                'Status' => 'match',
                                'UnsetKey' => $key,
                                'Type' => 'D',
                                'Number' => statementType($val->DID, 'D')
                            );
                        }
                    }
                } else {
                    $status[] = array(
                        'ID' => 0,
                        'Status' => '',
                        'UnsetKey' => '',
                        'Type' => '',
                        'Number' => ''
                    );
                }
            }
            if (count($status) == 0) {
                $status[] = array(
                    'ID' => 0,
                    'Status' => '',
                    'UnsetKey' => '',
                    'Type' => '',
                    'Number' => ''
                );
            }
            //echo '<pre>';print_r($status);echo '</pre>';die;
            return $status;
        } else {
            $status = array(
                'Date' => '',
                'Title' => '',
                'Status' => 'unmatch',
                'StatementID' => ''
            );
            return FALSE;
        }
    }

    function statementType($id, $type) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        if ($type == 'I') {
            $ci->db->select('InvoiceNumber');
            $query = $ci->db->get_where('invoices', array('InvoiceID' => $id));
            $query = $query->result();
            return $query[0]->InvoiceNumber;
        } elseif ($type == 'E') {
            $ci->db->select('FName');
            $query = $ci->db->get_where('files', array('ID' => $id));
            $query = $query->result();
            return $query[0]->FName;
        } elseif ($type == 'D') {
            $ci->db->select('VoucherNumber');
            $query = $ci->db->get_where('dividends', array('DID' => $id));
            $query = $query->result();
            //echo '<pre>';print_r($id);echo'</pre>';die;
            return $query[0]->VoucherNumber;
        } else {
            return '';
        }
    }

    function clientAccess() {
        $ci = & get_instance();
        $user = $ci->session->userdata('user');
        if (isset($user['AccountantAccess'])) {
            if ($user['AccountantAccess'] != 0) {
                $accountant_access = $user['AccountantAccess'];
            } else {
                $accountant_access = 0;
            }
        } else {
            $accountant_access = 0;
        }
        return $accountant_access;
    }

    function getFileInfo($id = NULL) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('ID,FName,AssociatedWith,FSize');
        $query = $ci->db->get_where('files', array('ID' => $id));
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result[0];
        } else {
            return array();
        }
    }

    function getFileID($name = NULL, $cat = null) {
        if (empty($name)) {
            return 0;
        }

        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $user = $ci->session->userdata('user');
        $ci->db->select('ID,AssociatedWith');
        if ($cat == null) {
            $query = $ci->db->get_where('files', array('FName' => $name, 'UploadedBy' => $user['UserID']));
        } else {
            $query = $ci->db->get_where('files', array('FName' => $name, 'UploadedBy' => $user['UserID'], 'AssociatedWith' => $cat));
        }

        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result[0];
        } else {
            return array();
        }
    }

    function getCategoryID($name = '') {
        if ($name != NULL) {
            $ci = & get_instance();
            $prefix = $ci->db->dbprefix;
            $ci->db->select('ID');
            $query = $ci->db->get_where('expenses_category', array('Title' => $name));
            if ($query->num_rows() > 0) {
                $result = $query->result();
                return $result[0]->ID;
            } else {
                return array();
            }
        } else {
            return FALSE;
        }
    }

    function financial_year() {
        $current_year = date('Y');
        $current_year_date = $current_year . '-04-30';

        $previous_year = $current_year - 1;
        $previous_year = $previous_year . '-04-1';

        $start_year = date('jS F Y', strtotime($previous_year));
        $last_year = date('jS F Y', strtotime($current_year_date));

        //echo 'Previous Year : '.$previous_year;die;
        $options = array(
            //'0'	=>	'Select year',
            ($current_year - 2) . ' / ' . ($current_year - 1) => ($current_year - 2) . ' / ' . ($current_year - 1)
                //'1'	=>	$start_year.' to '.$last_year
        );
        for ($x = $current_year - 1; $x < ($current_year + 4); $x++) {
            //$start_year 	= 	$x.'-04-1';
            //$last_year 		= 	($x+1).'-04-30';
            //$options[] = date('jS F Y',strtotime($start_year)).' to '.date('jS F Y',strtotime($last_year));
            $options[$x . ' / ' . ($x + 1)] = $x . ' / ' . ($x + 1);
        }
        return $options;
    }

    function folder($parent = null) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $user = $ci->session->userdata('user');
        $query = $ci->db->get_where('folders', array('ParentFolder' => 0));
        $db_error = $ci->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $val) {
                $parent_elements[$val->ID] = $val->FolderName;
            }
        } else {
            $parent_elements = array();
        }

        if ($parent == 'parent') {
            return $parent_elements;
        }
        //prd($parent_elements);
        $query = $ci->db->get_where('folders', array('ParentFolder !=' => 0, 'AddedBy' => $user['UserID']));
        if ($query->num_rows() > 0) {
            $children_elements = $query->result();
        } else {
            $children_elements = array();
        }

        /* Final Folder Array */
        $folder = array();
        if (count($children_elements) > 0) {
            foreach ($parent_elements as $key => $val) {
                $folder[$key] = $val;
                foreach ($children_elements as $k => $v) {
                    if ($key == $v->ParentFolder) {
                        $folder[$v->ID] = '&nbsp;&nbsp;&nbsp;' . $v->FolderName;
                    }
                }
            }
        } else {
            $folder = $parent_elements;
        }
        //prd($folder);
        return $folder;
    }

    function numberFormat($number) {
        if (empty($number)) {
            return '';
        } else {
            $number = str_replace('-', '', $number);
            //return '&pound;&nbsp;' . number_format($number, 2, '.', ',');
            return '£ ' . number_format($number, 2, '.', ',');
        }
    }

    function numberFormatPL($number) {
        if (empty($number)) {
            return '';
        } else {
            $number = str_replace('-', '', $number);
            return number_format($number, 2, '.', ',');
        }
    }

    function numberFormatPLNEG($number) {
        if (empty($number)) {
            return '';
        } else {
            $number = str_replace('-', '', $number);
            $number = number_format($number, 2, '.', ',');
            return "(" . $number . ")";
        }
    }

    function directoryStructure($folders, $userID = '', $path = '') {
        $html = '';
        foreach ($folders as $key => $val) {
            //echo '<br/>'.$val;
            $html .= "<tr data-tt-id='" . $key . "' class='active'>
				<td>
					<span class='folder'>&nbsp;&nbsp;" . $val . "</span>
				</td>
				<td>Folder</td>
				<td>--</td>
			</tr>";
            if (is_dir($path . '/' . $val . '/' . $userID)) {
                $directory = directory_map($path . '/' . $val . '/' . $userID, 1);
                //echo '<pre>';print_r($directory);echo '</pre>';die;
                directoryStructure($directory, $userID = '', $val . '/' . $userID);
            }
        }
        return $html;
    }

    function filterNumber($number = 0) {
        if ($number == '') {
            return 0;
        } else {
            return $number;
        }
    }

    function month() {
        $months = array('0' => 'Select month');
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = strftime('%B', mktime(0, 0, 0, $i, 1));
        }
        return $months;
    }

    function year($check = null) {
        $years = array('0' => 'Select Year');
        for ($i = date('Y') - 1; $i <= date('Y') + 1; $i++) {
            if ($check != null) {
                $years[] = $i;
            } else {
                $years[$i] = $i;
            }
        }
        return $years;
    }

    function profit_and_loss($data = array()) {
        if (count($data) > 0) {
            $ci = & get_instance();
            $prefix = $ci->db->dbprefix;
            $user = $ci->session->userdata('user');
            $query = $ci->db->get_where('profit_and_loss', array('ParentFolder' => 0));
            $db_error = $ci->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function itemNumber($id = null, $type = null) {
        if (!empty($id) && !empty($type)) {
            $ci = & get_instance();
            $prefix = $ci->db->dbprefix;
            if ($type == 'I') {
                $ci->db->select('InvoiceNumber');
                $response = $ci->db->get_where('invoices', array('InvoiceID' => $id));
                $response = $response->result();
                $response = $response[0]->InvoiceNumber;
                return $response;
            } elseif ($type == 'D') {
                $ci->db->select('VoucherNumber');
                $response = $ci->db->get_where('dividends', array('DID' => $id));
                $response = $response->result();
                $response = $response[0]->VoucherNumber;
                return $response;
            }
        } else {
            return '';
        }
    }

    /**
     * 	Function to generate the current financial year.
     */
    function currentFinancialYear($date = "") {
        if (!empty($date)) {
            $month = date('m', strtotime($date));
            $year = date('Y', strtotime($date));
        } else {
            $month = date('m');
            $year = date('Y');
            $markerdate = date('d');
        }

        ($month > 3) ? ( ($month == 4) ? ($markerdate > 5 ? $check = 0 : $check = 1) : $check = 0 ) : $check = 1;

        if ($check) {
            // if ($month <= 4 && $markerdate <= 05) {
            $year = ($year - 1) . ' / ' . $year;
        } else {
            $year = $year . ' / ' . ($year + 1);
        }
        return $year;
    }

    /**
     * 	Function to generate 'Vat Quarters' for a 'Company', Based on 'First Year Discount end Date'.
     */
    function calculateVatQuarters($clientId = 0) {
        if ($clientId > 0) {

            $ci = & get_instance();
            $prefix = $ci->db->dbprefix;

            $ci->db->select('tax_rates.EndDate, tax_rates.Type, MONTH("tax_rates.EndDate") as endMonth,company.Params, company.CID');
            $ci->db->from('tax_rates');
            $ci->db->join('company', 'company.ClientID = tax_rates.ClientID', 'left');
            $ci->db->where(array('tax_rates.ClientID' => $clientId));
            $response = $ci->db->get();
            //echo $ci->db->last_query();die;
            if ($response->num_rows() == 1) {
                $row = $response->row();

                $CID = $row->CID;
                $params = unserialize($row->Params);
                // if( $row->Type == "flat" ){
                $selectedQuater = $params["VATQuaters"];

                $Quarters = vatQuaters($selectedQuater);
                $firstQuarter = 1;
                foreach ($Quarters as $key => $Quarter) {

                    // echo date("m", strtotime($Quarter["FIRST"]));echo "<br/>";
                    // echo date("m", strtotime($row->EndDate));echo "<br/>";
                    if (date("m", strtotime($Quarter["FIRST"])) > date("m", strtotime($row->EndDate))) {
                        $Smyear = date("Y", strtotime($Quarter["FIRST"])) + 1;
                    } else {
                        $Smyear = date("Y", strtotime($Quarter["FIRST"]));
                    }
                    $mStart = "01-" . date("m", strtotime($row->EndDate)) . "-" . $Smyear;
                    // echo "<br/>";
                    $mStart = strtotime($mStart);

                    if (strtotime($Quarter["FIRST"]) <= $mStart && $mStart <= strtotime($Quarter["SECOND"])) {
                        $firstQuarter = $key;
                        break;
                    }
                }

                // echo "<pre>"; print_r( $firstQuarter );
                // echo "<pre>"; print_r( $Quarters );die();
                for ($i = 1; $i <= 4; $i++) { // can be MAX 4 Quarters only
                    if ($firstQuarter > 4) {
                        $firstQuarter = 1;
                    }
                    $vatQuaters["q" . $i . "_start"] = date("m", strtotime($Quarters[$firstQuarter]["FIRST"]));
                    $vatQuaters["q" . $i . "_end"] = date("m", strtotime($Quarters[$firstQuarter]["SECOND"]));

                    $firstQuarter++;
                }
                // echo "<pre>"; print_r( $vatQuaters );die();
                $vatData = array(
                    'companyID' => $CID,
                    'ClientID' => $clientId,
                    'AddedOn' => date("Y-m-d")
                );
                $vatData = array_merge($vatQuaters, $vatData);

                $result = $ci->db->get_where('vat_quarters', array('ClientID' => $clientId));
                $vatQua = $result->row();
                if (count($vatQua) > 0) {

                    unset($vatData["AddedOn"]);
                    $vatData["ModifiedOn"] = date("Y-m-d");
                    // echo "<pre>"; print_r( $vatQua->id );
                    $ci->db->where('id', $vatQua->id);
                    $ci->db->update('vat_quarters', $vatData);
                } else {
                    $ci->db->insert('vat_quarters', $vatData);
                }
                // }
                // echo "<pre>"; print_r( );die();
            }
        }
    }

    /**
     * 	Function to generate 'Vat Years' drop down at 'VAT Summary' view.
     */
    function vatYearDropDown($name = 'vatYear', $option = NULL, $selected = 0, $id = NULL) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;

        $ci->load->library('session');
        $user = $ci->session->userdata('user');
        $CompanyEndDate = $user["CompanyEndDate"];
        $clientId = $user["UserID"];
        // pr( $user );
        $ci->db->select('*');
        $result = $ci->db->get_where('vat_quarters', array('ClientID' => $clientId));
        //echo $ci->db->last_query();
        if ($row = $result->row()) {
            $multiple = false;
            if ($row->q1_start > $row->q4_end) {
                $multiple = true;
            }

            $currentYear = date("Y");
            $dropStartYear = date("Y", strtotime($CompanyEndDate)) - 2;
            if (( $dropStartYear ) > APP_START_YEAR) {
                $dropStartYear = APP_START_YEAR;
            }

            for ($startYear = $dropStartYear; $startYear <= $currentYear; $startYear++) {
                if ($multiple) {
                    $option[$startYear] = $startYear . " / " . ($startYear + 1);
                } else {
                    $option[$startYear] = $startYear;
                }
            }
        }

        $VATYear = $ci->session->userdata('VATYear');
        if (!empty($VATYear)) {
            $selected = $VATYear;
        } else if ($selected == 0) {
            $selected = date("Y");
        }
        $id = (empty($id)) ? '' : 'id=' . $id;
        $id = $id . ' class="form-control"';
        $options = array_filter($option);
        return form_dropdown($name, $options, $selected, $id);
    }

    /**
     * 	Function to generate 'Vat Quarters' for a 'Company', for a given Year ( Used in VAT Summary ).
     */
    function getVatQuarters($vYear = NULL) {

        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;

        $ci->load->library('session');
        $user = $ci->session->userdata('user');
        $clientId = $user["UserID"];

        $vatQuaters = false;

        $ci->db->select('*');
        $result = $ci->db->get_where('vat_quarters', array('ClientID' => $clientId));
        if ($row = $result->row()) {

            $VATYear = $ci->session->userdata('VATYear');
            if (!empty($vYear)) {
                $year = $vYear;
            } else if (!empty($VATYear)) {
                $year = $VATYear;
            } else {
                $year = date("Y");
            }
            // echo "<pre>"; print_r( $row );

            for ($i = 1; $i <= 4; $i++) { // can be MAX 4 Quarters only
                $sQuater = "q" . $i . "_start";
                $eQuater = "q" . $i . "_end";

                if (!isset($prevDate)) {
                    $prevDate = $vatQuaters[$i]["FIRST"];
                }

                if (isset($prevDate) && strtotime($prevDate) > strtotime($year . "-" . $row->$sQuater . "-01")) {
                    $year++;
                }
                $prevDate = $vatQuaters[$i]["FIRST"] = $year . "-" . $row->$sQuater . "-01";


                if (strtotime($prevDate) > strtotime($year . "-" . $row->$eQuater . "-" . date('t', strtotime($year . "-" . $row->$eQuater . "-01")))) {
                    $year++;
                }
                $prevDate = $vatQuaters[$i]["SECOND"] = $year . "-" . $row->$eQuater . "-" . date('t', strtotime($year . "-" . $row->$eQuater . "-01"));
            }
        }
        // echo "<pre>"; print_r( $vatQuaters );die();
        return $vatQuaters;
    }

    function emptyNumber($number = 0) {
        if ($number == 0) {
            return '';
        } else {
            return $number;
        }
    }

    function fPrice($price) {
        return number_format((float) $price, 2, '.', ',');
    }

    function generateApiPDF($name = 'PDF', $html1 = NULL, $html2 = NULL, $action = 'D') {
        ob_start();
        ob_clean();
        ini_set('memory_limit', '-1');
        require_once(APPPATH . 'third_party/tcpdf/tcpdf.php');

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetPrintHeader(false);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetFont('dejavusans', '', 10);

        if ($html1 !== NULL) {
            // add a page
            $pdf->AddPage();

            $pdf->Line(5, 5, $pdf->getPageWidth() - 5, 5);
            $pdf->Line($pdf->getPageWidth() - 5, 5, $pdf->getPageWidth() - 5, $pdf->getPageHeight() - 5);
            $pdf->Line(5, $pdf->getPageHeight() - 5, $pdf->getPageWidth() - 5, $pdf->getPageHeight() - 5);
            $pdf->Line(5, 5, 5, $pdf->getPageHeight() - 5);

            // output the HTML content
            $pdf->writeHTML($html1, true, false, true, false, '');
        }

        if ($html2 !== NULL) {
            $pdf->AddPage();

            $pdf->Line(5, 5, $pdf->getPageWidth() - 5, 5);
            $pdf->Line($pdf->getPageWidth() - 5, 5, $pdf->getPageWidth() - 5, $pdf->getPageHeight() - 5);
            $pdf->Line(5, $pdf->getPageHeight() - 5, $pdf->getPageWidth() - 5, $pdf->getPageHeight() - 5);
            $pdf->Line(5, 5, 5, $pdf->getPageHeight() - 5);
            $pdf->writeHTML($html2, true, false, true, false, '');
        }

        $pdf->Output($name . '.pdf', $action);
    }

    /*
      Function To add values to P/L and B/S sheet
     */

    function update_trial_balance($type = "", $data = array(), $from = "", $tType = "", $action = "", $paidstatus = NULL) {
		
        $ci = & get_instance();
        $user = $ci->session->userdata('user');
        if (isset($user["AccountantAccess"]) && !empty($user["AccountantAccess"])) {
            $aAccess = $user["AccountantAccess"];
        } else {
            $aAccess = $user["UserID"];
        }
        // prd( $user );

        if (!empty($type)) {

            if (!empty($from)) {
                $tbSource = "BANK"; // for details on TB entry no relation with $from
            } else {
                $tbSource = strtoupper($type); // for details on TB entry no relation with $from
            }

            switch ($type) {

                case "invoice":
					
                    $ci->load->model("clients");
                    if (is_array($data) && !empty($data)) {
                        $invData = $data;
                    } else if ((int) $data > 0) {
                        $invData = $ci->clients->getInvoiceDetails(array("InvoiceID" => (int) $data));
                        // $invData = $ci->clients->getInvoiceItem( array("InvoiceID" => (int) $data) );
                    } else {
                        return;
                    }
                    // prd( $invData );				
					
                    if (!empty($invData)) {
                        $vat_listing = $ci->clients->getVatType();
                        if ($from != "BANK" && $from != "BANK_DEL") {
                            /*if ($user['VAT_TYPE'] == 'flat') {
                              if ($invData["InvoiceTotal"] != 0 && $invData["PaidOn"] != '') {
                              if (strtotime(cDate($invData["PaidOn"])) <= strtotime(cDate($invData['EndDate']))) {
                              $govtVATUpdate = ($invData["InvoiceTotal"] * $invData['PercentRateAfterEndDate']) / 100;
                              } else {
                              $govtVATUpdate = ($invData["InvoiceTotal"] * $invData['PercentRate']) / 100;
                              }
                              }
                              $amount = $invData["InvoiceTotal"] - $govtVATUpdate;
                              $govtVAT = $govtVATUpdate;
                              } else if ($user['VAT_TYPE'] == 'stand') {
                              $amount = $invData["InvoiceTotal"] - $invData["InvoiceTax"];
                              $govtVAT = $invData["InvoiceTax"];
                              } else {
                              $govtVAT = 0.00;
                              $amount = $invData["InvoiceTotal"];
                              }*/
                            if($invData['VatType'] == 'flat'){
								$govtVAT =$invData['FlatRate'];
							}else{
								$govtVAT =$invData['InvoiceTax'];
							} 
                            $amount = $invData["NetSales"];
                        }

                        if (!empty($from)) {
                            if ($from == "BANK") {								
                                if ($tType == "MONEY_IN") {
                                    $vat_listing = $ci->clients->getVatType();
                                    // "Cash at bank" goes up by "Invoice total amount"									
                                    $TBCatId = get_trial_balance_category($invData['bankTBCategory']); //get category ID for given key
                                    //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"]);
                                    //   if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, $invData['bankTBCategory'], $aAccess, $invData["id"], $invData["InvoiceTotal"], $paidstatus);
                                    //   }
                                    // "Sales suspense" goes down by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category("SALES_SUSPENSES"); //get category ID for given key
                                    //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"], "SUBTRACT");
                                    //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, "SALES_SUSPENSES", $aAccess, $invData["id"], $invData["InvoiceTotal"], $paidstatus, "SUBTRACT");
                                    //  }
                                } else if ($tType == "MONEY_OUT") {
                                    // "Cash at bank" goes up by "Invoice total amount"									
                                     $TBCatId = get_trial_balance_category($invData['bankTBCategory']); //get category ID for given key
                                    // $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"], "SUBTRACT");
                                    // if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, $invData['bankTBCategory'], $aAccess, $invData["id"], $invData["InvoiceTotal"], $paidstatus, "SUBTRACT");
                                    // }

                                    // "Sales suspense" goes down by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category("SALES_SUSPENSES"); //get category ID for given key
                                    //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"]);
                                    // if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, "SALES_SUSPENSES", $aAccess, $invData["id"], $invData["InvoiceTotal"],$paidstatus);
                                    //  }
                                }
                            } else if ($from == "BANK_DEL") {								
                                if ($tType == "MONEY_IN") {
                                    // "Cash at bank" goes down by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category($invData['bankTBCategory']); //get category ID for given key
                                    // $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"], "SUBTRACT");
                                    // if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, $invData['bankTBCategory'], $invData["id"]);
                                    // }

                                    // "Sales suspense" goes up by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category("SALES_SUSPENSES"); //get category ID for given key
                                    //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"]);
                                    //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "SALES_SUSPENSES", $invData["id"]);
                                    //   }
                                } else if ($tType == "MONEY_OUT") {
                                    // "Cash at bank" goes down by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category($invData['bankTBCategory']); //get category ID for given key
                                    //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"]);
                                    // if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, $invData['bankTBCategory'], $invData["id"]);
                                    //  }

                                    // "Sales suspense" goes up by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category("SALES_SUSPENSES"); //get category ID for given key
                                    // $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"], "SUBTRACT");
                                    // if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "SALES_SUSPENSES", $invData["id"]);
                                    //  }
                                }
                            }
                        } else {

                            // Sales goes up By "Sales Amount" Minus "VAT Paid to Govt."
                            $TBCatId = get_trial_balance_category("SALES"); //get category ID for given key
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $amount);
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, "SALES", $invData["id"]);
                              //  }
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $amount, "SUBTRACT");
                             //   if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, "SALES", $aAccess, $invData["id"], $amount, $paidstatus, "SUBTRACT");
                              //  }
                            }

                            // Sales Suspenses goes up By "Sales Amount"
                            $TBCatId = get_trial_balance_category("SALES_SUSPENSES"); //get category ID for given key
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"], "SUBTRACT");
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, "SALES_SUSPENSES", $invData["id"]);
                              //  }
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"]);
                               // if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, "SALES_SUSPENSES", $aAccess, $invData["id"], ($invData["InvoiceTotal"]+$invData["CIS_Deduction"]),$paidstatus);
                              //  }
                            }

                            // VAT Control Suspense goes up By "Flat/Standard Amount"
                            $TBCatId = get_trial_balance_category("VAT_CONTROL");
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $govtVAT);
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, "VAT_CONTROL", $invData["id"]);
                              //  }
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $govtVAT, "SUBTRACT");
                              //  if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, "VAT_CONTROL", $aAccess, $invData["id"], $govtVAT, $paidstatus, "SUBTRACT");
                              //  }
                            }
							
							
							if(isset($invData["CIS_Deduction"]) && $invData["CIS_Deduction"] != 0){
								// CIS Diducction goes up By "CIS Debtor"
								$TBCatId = get_trial_balance_category("CIS_DEBTOR");
								if ($action == "DELETE") {
								  //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $govtVAT);
								  //  if ((int) $TBentryId > 0) {
										rm_trial_details($tbSource, "CIS_DEBTOR", $invData["id"]);
								  //  }
								} else {
								  //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $govtVAT, "SUBTRACT");
								  //  if ((int) $TBentryId > 0) {
										add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, "CIS_DEBTOR", $aAccess, $invData["id"], $invData["CIS_Deduction"], $paidstatus, "SUBTRACT");
								  //  }
								}
							}
							
							
                        }
                        // die();
                    }

                    break;

                case "salary":
                    $ci->load->model('clients/payroll');
                    if (is_array($data) && !empty($data)) {
                        $salData = $data;
                    } else if ((int) $data > 0) {
                        if (!empty($from) && $from == "BANK") {
                            // $salData = ""; // need to bring data from bank statement table salary category
                        } else {
                            $salData = $ci->payroll->getSalaryDetails((int) $data);
                        }
                    } else {
                        return;
                    }
					
                    if (!empty($salData)) {
                        if (!empty($from)) {
                            if ($from == "BANK") {
                                if ($tType == "MONEY_IN") {

                                    // "Cash at bank" goes up
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["GrossSalary"]);
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $salData["PaidDate"], $tbSource, "CASH_AT_BANK", $aAccess, $salData["id"], $salData["GrossSalary"],$paidstatus);
                                  //  }

                                    // "Salary suspense" goes down
                                    $TBCatId = get_trial_balance_category("SALARY_SUSPENSE"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["GrossSalary"], "SUBTRACT");
                                   // if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $salData["PaidDate"], $tbSource, "SALARY_SUSPENSE", $aAccess, $salData["id"], $salData["GrossSalary"],$paidstatus, "SUBTRACT");
                                  //  }
                                } else if ($tType == "MONEY_OUT") {

                                    // "Cash at bank" goes down
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["GrossSalary"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $salData["PaidDate"], $tbSource, "CASH_AT_BANK", $aAccess, $salData["id"], $salData["GrossSalary"],$paidstatus, "SUBTRACT");
                                  //  }

                                    // "Salary suspense" goes down
                                    $TBCatId = get_trial_balance_category("SALARY_SUSPENSE"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["GrossSalary"]);
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $salData["PaidDate"], $tbSource, "SALARY_SUSPENSE", $aAccess, $salData["id"], $salData["GrossSalary"],$paidstatus);
                                  //  }
                                }
                            } else if ($from == "BANK_DEL") {

                                if ($tType == "MONEY_IN") {

                                    // "Cash at bank" goes down
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["GrossSalary"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "CASH_AT_BANK", $salData["id"]);
                                  //  }

                                    // "Salary suspense" goes up
                                    $TBCatId = get_trial_balance_category("SALARY_SUSPENSE"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["GrossSalary"]);
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "SALARY_SUSPENSE", $salData["id"]);
                                  //  }
                                } else if ($tType == "MONEY_OUT") {

                                    // "Cash at bank" goes up
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["GrossSalary"]);
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "CASH_AT_BANK", $salData["id"]);
                                  //  }

                                    // "Salary suspense" goes up
                                    $TBCatId = get_trial_balance_category("SALARY_SUSPENSE"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["GrossSalary"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "SALARY_SUSPENSE", $salData["id"]);
                                 //   }
                                }
                            }
                        } else {

                            if ($salData["IS_Director"] == "1") { // If Director's salary marked as PAID
                                // "Director salary" goes up
                                $TBCatId = get_trial_balance_category("DIRECTORS_SALARIES"); //get category ID for given key
                                $tbDetailType = "DIRECTORS_SALARIES";
                            } else { // If Non Director's salary marked as PAID
                                // "Non Director salary" goes up
                                $TBCatId = get_trial_balance_category("WAGES_AND_SALARIES"); //get category ID for given key
                                $tbDetailType = "WAGES_AND_SALARIES";
                            }

                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["GrossSalary"], "SUBTRACT");
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, $tbDetailType, $salData["id"]);
                              //  }
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["GrossSalary"]);
                              //  if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $salData["PaidDate"], $tbSource, $tbDetailType, $aAccess, $salData["id"], $salData["GrossSalary"],$paidstatus);
                              //  }
                            }

                            // "Salary suspense" goes up
                            $TBCatId = get_trial_balance_category("SALARY_SUSPENSE"); //get category ID for given key
                            if ($action == "DELETE") {
                             //   $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["NetPay"]);
                             //   if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, "SALARY_SUSPENSE", $salData["id"]);
                             //   }
                            } else {
                             //   $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["NetPay"], "SUBTRACT");
                             //   if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $salData["PaidDate"], $tbSource, "SALARY_SUSPENSE", $aAccess, $salData["id"], $salData["NetPay"],$paidstatus, "SUBTRACT");
                             //   }
                            }

                            $payRollSuspense = $salData["GrossSalary"] - $salData["NetPay"];
                            // "Payroll Taxes" difference between "Net" and "Gross"
                            $TBCatId = get_trial_balance_category("PAYROLL_TAX_SUSPENSE"); //get category ID for given key
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $payRollSuspense);
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, "PAYROLL_TAX_SUSPENSE", $salData["id"]);
                              //  }
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $payRollSuspense, "SUBTRACT");
                              //  if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $salData["PaidDate"], $tbSource, "PAYROLL_TAX_SUSPENSE", $aAccess, $salData["id"], $payRollSuspense,$paidstatus, "SUBTRACT");
                              //  }
                            }


                            // "Employer NI" goes up
                            $TBCatId = get_trial_balance_category("EMPLOYERS_NI"); //get category ID for given key
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["Employeer_NIC"], "SUBTRACT");
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, "EMPLOYERS_NI", $salData["id"]);
                              //  }
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["Employeer_NIC"]);
                              //  if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $salData["PaidDate"], $tbSource, "EMPLOYERS_NI", $aAccess, $salData["id"], $salData["Employeer_NIC"],$paidstatus);
                              //  }
                            }

                            // "Payroll tax suspense" goes up
                            $TBCatId = get_trial_balance_category("PAYROLL_TAX_SUSPENSE"); //get category ID for given key
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["Employeer_NIC"]);
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, "PAYROLL_TAX_SUSPENSE", $salData["id"]);
                              //  }
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $salData["PaidDate"], $salData["Employeer_NIC"], "SUBTRACT");
                              //  if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $salData["PaidDate"], $tbSource, "PAYROLL_TAX_SUSPENSE", $aAccess, $salData["id"], $salData["Employeer_NIC"],$paidstatus, "SUBTRACT");
                             //   }
                            }
                        }
                    }

                    break;
				case "purchase":
                
                    $ci->load->model('clients/purchases');
					
                    if (is_array($data) && !empty($data)) {
                        $invData = $data;
                    } else if ((int) $data > 0) {
                        $invData = $ci->purchases->getInvoiceDetails(array("InvoiceID" => (int) $data));
                        // $invData = $ci->clients->getInvoiceItem( array("InvoiceID" => (int) $data) );
                    } else {
                        return;
                    }
                    // prd( $invData );					
					//print_r($invData);
                    if (!empty($invData)) {
                        $vat_listing = $ci->purchases->getVatType();
                        if ($from != "BANK" && $from != "BANK_DEL") {
                            /*if ($user['VAT_TYPE'] == 'flat') {
                              if ($invData["InvoiceTotal"] != 0 && $invData["PaidOn"] != '') {
                              if (strtotime(cDate($invData["PaidOn"])) <= strtotime(cDate($invData['EndDate']))) {
                              $govtVATUpdate = ($invData["InvoiceTotal"] * $invData['PercentRateAfterEndDate']) / 100;
                              } else {
                              $govtVATUpdate = ($invData["InvoiceTotal"] * $invData['PercentRate']) / 100;
                              }
                              }
                              $amount = $invData["InvoiceTotal"] - $govtVATUpdate;
                              $govtVAT = $govtVATUpdate;
                              } else if ($user['VAT_TYPE'] == 'stand') {
                              $amount = $invData["InvoiceTotal"] - $invData["InvoiceTax"];
                              $govtVAT = $invData["InvoiceTax"];
                              } else {
                              $govtVAT = 0.00;
                              $amount = $invData["InvoiceTotal"];
                              } */
                             if($invData['VatType'] == 'flat'){
								$govtVAT =$invData['FlatRate'];
							}else{
								$govtVAT =$invData['InvoiceTax'];
							} 
                            $amount = $invData["NetSales"];
                        }

                        if (!empty($from)) {							
                            if ($from == "BANK") {
                                if ($tType == "MONEY_IN") {
                                    $vat_listing = $ci->purchases->getVatType();
                                    // "Cash at bank" goes up by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category($invData['bankTBCategory']); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"]);
                                 //   if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, $invData['bankTBCategory'], $aAccess, $invData["id"], $invData["InvoiceTotal"], $paidstatus, "SUBTRACT");
                                 //   }
                                    // "Sales suspense" goes down by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category("EXPENSE_SUSPENSE"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, "EXPENSE_SUSPENSE", $aAccess, $invData["id"], $invData["InvoiceTotal"], $paidstatus);
                                  //  }
                                } else if ($tType == "MONEY_OUT") {
                                    // "Cash at bank" goes up by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category($invData['bankTBCategory']); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"], "SUBTRACT");
                                   // if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, $invData['bankTBCategory'], $aAccess, $invData["id"], $invData["InvoiceTotal"], $paidstatus);
                                   // }

                                    // "Sales suspense" goes down by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category("EXPENSE_SUSPENSE"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"]);
                                   // if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, "EXPENSE_SUSPENSE", $aAccess, $invData["id"], $invData["InvoiceTotal"],$paidstatus, "SUBTRACT");
                                  //  }
                                }
                            } else if ($from == "BANK_DEL") {
                                if ($tType == "MONEY_IN") {
                                    // "Cash at bank" goes down by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category($invData['bankTBCategory']); //get category ID for given key
                                    // $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"], "SUBTRACT");
                                    // if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, $invData['bankTBCategory'], $invData["id"]);
                                   // }

                                    // "Sales suspense" goes up by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category("EXPENSE_SUSPENSE"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"]);
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "EXPENSE_SUSPENSE", $invData["id"]);
                                 //   }
                                } else if ($tType == "MONEY_OUT") {
                                    // "Cash at bank" goes down by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category($invData['bankTBCategory']); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"]);
                                   // if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, $invData['bankTBCategory'], $invData["id"]);
                                  //  }

                                    // "Sales suspense" goes up by "Invoice total amount"
                                    $TBCatId = get_trial_balance_category("EXPENSE_SUSPENSE"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"], "SUBTRACT");
                                   // if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "EXPENSE_SUSPENSE", $invData["id"]);
                                  //  }
                                }
                            }
                        } else {
							// Sales goes up By "Sales Amount" Minus "VAT Paid to Govt."
                            //$TBCatId = get_trial_balance_category("SALES"); //get category ID for given key
							$TBCatCatKey = getCategoryKeyById($invData["Category"]);			
							$TBCatId = getCategoryTbKeyId($TBCatCatKey);							
							
							
							
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $amount);
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, $TBCatCatKey, $invData["id"]);
                              //  }
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $amount, "SUBTRACT");
                             //   if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, $TBCatCatKey, $aAccess, $invData["id"], $amount, $paidstatus);
                              //  }
                            }

                            // Sales Suspenses goes up By "Sales Amount"
                            $TBCatId = get_trial_balance_category("EXPENSE_SUSPENSE"); //get category ID for given key
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"], "SUBTRACT");
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, "EXPENSE_SUSPENSE", $invData["id"]);
                              //  }
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $invData["InvoiceTotal"]);
                               // if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, "EXPENSE_SUSPENSE", $aAccess, $invData["id"], $invData["InvoiceTotal"],$paidstatus, "SUBTRACT");
                              //  }
                            }

                            // VAT Control Suspense goes up By "Flat/Standard Amount"
                            $TBCatId = get_trial_balance_category("VAT_CONTROL");
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $govtVAT);
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, "VAT_CONTROL", $invData["id"]);
                              //  }
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $invData["PaidOn"], $govtVAT, "SUBTRACT");
                              //  if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $invData["PaidOn"], $tbSource, "VAT_CONTROL", $aAccess, $invData["id"], $govtVAT, $paidstatus);
                                    
                              //  }
                            }
                        }
                        // die();
                    }

                    break;
                case "expense":
                    
                    $ci->load->model('clients/expense');

                    if (is_array($data) && !empty($data)) {
                        $expData = $data;
                    } else if ((int) $data > 0) {
                        $expData = $ci->expense->getExpenseDetails((int) $data);
                    } else {
                        return false;
                    }
                    // prd($data);
                    // prd($expData);
                    if (!empty($expData)) {

                        $standardClient = false;
                        $ci->load->model('clients');
                        $vat_listing = $ci->clients->getVatType();
                        if ($vat_listing->Type != 'flat') {
                            $standardClient = true;
                        }

                        if (!empty($from)) {

                            if ($from == "BANK") {
                                if ($tType == "MONEY_OUT") {
                                    // "Cash at bank" goes down by "Total amount"
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expData["Amount"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $expData["PaidOn"], $tbSource, "CASH_AT_BANK", $aAccess, $expData["id"], $expData["Amount"],$paidstatus, "SUBTRACT");
                                  //  }

                                    // "Expense suspense" or "Credit Card Suspense" goes down by "Total amount"
                                    $TBCatId = get_trial_balance_category($expData["ExpenseType"]); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expData["Amount"]);
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $expData["PaidOn"], $tbSource, $expData["ExpenseType"], $aAccess, $expData["id"], $expData["Amount"],$paidstatus);
                                  //  }
                                } else if ($tType == "MONEY_IN") {
                                    // "Cash at bank" goes down by "Total amount"
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expData["Amount"]);
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $expData["PaidOn"], $tbSource, "CASH_AT_BANK", $aAccess, $expData["id"], $expData["Amount"],$paidstatus);
                                  //  }

                                    // "Expense suspense" or "Credit Card Suspense" goes down by "Total amount"
                                    $TBCatId = get_trial_balance_category($expData["ExpenseType"]); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expData["Amount"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $expData["PaidOn"], $tbSource, $expData["ExpenseType"], $aAccess, $expData["id"], $expData["Amount"],$paidstatus, "SUBTRACT");
                                  //  }
                                }
                            } else if ($from == "BANK_DEL") {
                                if ($tType == "MONEY_OUT") {
                                    // "Cash at bank" goes down by "Total amount"
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                 //   $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expData["Amount"]);
                                 //   if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "CASH_AT_BANK", $expData["id"]);
                                 //   }

                                    // "Expense suspense" or "Credit Card Suspense" goes down by "Total amount"
                                    $TBCatId = get_trial_balance_category($expData["ExpenseType"]); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expData["Amount"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, $expData["ExpenseType"], $expData["id"]);
                                  //  }
                                } else if ($tType == "MONEY_IN") {
                                    // "Cash at bank" goes down by "Total amount"
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expData["Amount"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "CASH_AT_BANK", $expData["id"]);
                                  //  }

                                    // "Expense suspense" or "Credit Card Suspense" goes down by "Total amount"
                                    $TBCatId = get_trial_balance_category($expData["ExpenseType"]); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expData["Amount"]);
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, $expData["ExpenseType"], $expData["id"]);
                                  //  }
                                }
                            }
                        } else {

                            /* Each "individual entry" of expense same as "trial balance" increases */
                            if (isset($expData["items"]) && !empty($expData["items"])) {
                                foreach ($expData["items"] as $expItem) {

                                    /*
                                      if( $standardClient ){
                                      $expAmount = $expItem["Amount"] -  $expItem["VATAmount"];
                                      }else{
                                      $expAmount = $expItem["Amount"];
                                      }
                                     */
                                    $expAmount = $expItem["Amount"];

                                    $TBCatId = get_trial_balance_category($expItem["key"]);
                                    if ($TBCatId) {

                                        if ($action == "DELETE") {
                                          //  $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expAmount, "SUBTRACT");
                                          //  if ((int) $TBentryId > 0) {
                                                rm_trial_details($tbSource, $expItem["key"], $expItem["id"]);
                                          //  }
                                        } else {
                                          //  $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expAmount);
                                          //  if ((int) $TBentryId > 0) {
                                                add_trial_details($TBCatId, $expData["PaidOn"], $tbSource, $expItem["key"], $aAccess, $expItem["id"], $expAmount,$paidstatus);
                                          //  }
                                        }
                                    }

                                    /* Moved into loop */
                                    if ($expData["ExpenseType"] == "CREDITCARD") {
                                        // if "Credit card" then "credit card suspense" goes up
                                        $TBCatId = get_trial_balance_category("CREDIT_CARD_SUSPENSE");
                                        $tbDetailType = "CREDIT_CARD_SUSPENSE";
                                    } else if ($expData["ExpenseType"] == "EXPENSE") {
                                        // if "Expense" then "Expense suspense" goes up
                                        $TBCatId = get_trial_balance_category("EXPENSE_SUSPENSE");
                                        $tbDetailType = "EXPENSE_SUSPENSE";
                                    }
                                    if ($action == "DELETE") {
                                       // $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expAmount);
                                      //  if ((int) $TBentryId > 0) {
                                            rm_trial_details($tbSource, $tbDetailType, $expItem["id"]);
                                      //  }
                                    } else {
                                      //  $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expAmount, "SUBTRACT");
                                      //  if ((int) $TBentryId > 0) {
                                            add_trial_details($TBCatId, $expData["PaidOn"], $tbSource, $tbDetailType, $aAccess, $expItem["id"], $expAmount,$paidstatus, "SUBTRACT");
                                      //  }
                                    }
                                }
                            }

                            /*
                              if( isset($expData["TotalAmount"]) && $expData["TotalAmount"] != 0 ){

                              if( $expData["ExpenseType"] == "CREDITCARD" ){
                              // if "Credit card" then "credit card suspense" goes up
                              $TBCatId = get_trial_balance_category( "CREDIT_CARD_SUSPENSE" );
                              $tbDetailType = "CREDIT_CARD_SUSPENSE";
                              }else if( $expData["ExpenseType"] == "EXPENSE" ){
                              // if "Expense" then "Expense suspense" goes up
                              $TBCatId = get_trial_balance_category( "EXPENSE_SUSPENSE" );
                              $tbDetailType = "EXPENSE_SUSPENSE";
                              }
                              if( $action == "DELETE" ){
                              $TBentryId = store_trial_entry( $TBCatId, $expData["PaidOn"], $expData["TotalAmount"] );
                              if( (int) $TBentryId > 0 ){
                              rm_trial_details( $TBentryId, $tbSource, $tbDetailType, $expData["id"] );
                              }
                              }else{
                              $TBentryId = store_trial_entry( $TBCatId, $expData["PaidOn"], $expData["TotalAmount"] , "SUBTRACT"  );
                              if( (int) $TBentryId > 0 ){
                              add_trial_details( $TBentryId, $tbSource, $tbDetailType, $aAccess, $expData["id"], $expData["TotalAmount"] , "SUBTRACT"  );
                              }
                              }
                              } */

                            if ($standardClient) {
                                $TBCatId = get_trial_balance_category("VAT_CONTROL");
                                if ($action == "DELETE") {
                                  //  $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expData["TotalVATAmount"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "VAT_CONTROL", $expData["id"]);
                                  //  }
                                } else {
                                 //   $TBentryId = store_trial_entry($TBCatId, $expData["PaidOn"], $expData["TotalVATAmount"]);
                                 //   if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $expData["PaidOn"], $tbSource, "VAT_CONTROL", $aAccess, $expData["id"], $expData["TotalVATAmount"],$paidstatus);
                                 //   }
                                }
                            }

                            // prd($expData);
                            // die();
                        }
                    }
                    break;

                case "payee":
                    $ci->load->model('clients/payroll');
                    if (is_array($data) && !empty($data)) {
                        $payData = $data;
                    } else if ((int) $data > 0) {
                        $payData = $ci->payroll->getPayeeDetails((int) $data);
                    } else {
                        return;
                    }

                    if (!empty($payData)) {

                        if (!empty($from)) {

                            if ($from == "BANK") {

                                if ($tType == "MONEY_IN") {

                                    // "Cash at bank" goes up
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["Total"]);
                                   // if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $payData["PaidDate"], $tbSource, "CASH_AT_BANK", $aAccess, $payData["id"], $payData["Total"],$paidstatus);
                                  //  }

                                    // "Payroll tax suspense" goes down
                                    $TBCatId = get_trial_balance_category("PAYROLL_TAX_SUSPENSE"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["Total"], "SUBTRACT");
                                   // if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $payData["PaidDate"], $tbSource, "PAYROLL_TAX_SUSPENSE", $aAccess, $payData["id"], $payData["Total"],$paidstatus, "SUBTRACT");
                                   // }
                                } else if ($tType == "MONEY_OUT") {

                                    // "Cash at bank" goes down
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["Total"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $payData["PaidDate"], $tbSource, "CASH_AT_BANK", $aAccess, $payData["id"], $payData["Total"],$paidstatus, "SUBTRACT");
                                  //  }

                                    // "Payroll tax suspense" goes down
                                    $TBCatId = get_trial_balance_category("PAYROLL_TAX_SUSPENSE"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["Total"]);
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $payData["PaidDate"], $tbSource, "PAYROLL_TAX_SUSPENSE", $aAccess, $payData["id"], $payData["Total"],$paidstatus);
                                  //  }
                                }
                            } else if ($from == "BANK_DEL") {

                                if ($tType == "MONEY_IN") {

                                    // "Cash at bank" goes down
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["Total"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "CASH_AT_BANK", $payData["id"]);
                                  //  }

                                    // "Payroll tax suspense" goes up
                                    $TBCatId = get_trial_balance_category("PAYROLL_TAX_SUSPENSE"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["Total"]);
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "PAYROLL_TAX_SUSPENSE", $payData["id"]);
                                  //  }
                                } else if ($tType == "MONEY_OUT") {

                                    // "Cash at bank" goes up
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["Total"]);
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "CASH_AT_BANK", $payData["id"]);
                                  //  }

                                    // "Payroll tax suspense" goes up
                                    $TBCatId = get_trial_balance_category("PAYROLL_TAX_SUSPENSE"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["Total"], "SUBTRACT");
                                   // if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "PAYROLL_TAX_SUSPENSE", $payData["id"]);
                                   // }
                                }
                            }
                        } else { // Never comes here .. Call source to come here was commented Then applied at Invoices
                            // "Employer NI" goes up
                            $TBCatId = get_trial_balance_category("EMPLOYERS_NI"); //get category ID for given key
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["NIC_Employer"], "SUBTRACT");
                            } else {
                             //   $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["NIC_Employer"]);
                            }

                            // "Payroll tax suspense" goes up
                            $TBCatId = get_trial_balance_category("PAYROLL_TAX_SUSPENSE"); //get category ID for given key
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["NIC_Employer"]);
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $payData["PaidDate"], $payData["NIC_Employer"], "SUBTRACT");
                            }
                        }
                    }
                    break;

                case "vat":

                    if (is_array($data) && !empty($data)) {
                        $vatdata = $data;
                    } else {
                        return;
                    }

                    if (!empty($vatdata)) {

                        if (!empty($from)) {

                            if ($from == "BANK") {

                                if ($tType == "MONEY_IN") {

                                    // "Cash at bank" goes up
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $vatdata["PaidDate"], $vatdata["Total"]);
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $vatdata["PaidDate"], $tbSource, "CASH_AT_BANK", $aAccess, $vatdata["id"], $vatdata["Total"],$paidstatus);
                                  //  }

                                    // "VAT control suspense" goes down
                                    $TBCatId = get_trial_balance_category("VAT_CONTROL"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $vatdata["PaidDate"], $vatdata["Total"], "SUBTRACT");
                                   // if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $vatdata["PaidDate"], $tbSource, "VAT_CONTROL", $aAccess, $vatdata["id"], $vatdata["Total"],$paidstatus, "SUBTRACT");
                                  //  }
                                } else if ($tType == "MONEY_OUT") {

                                    // "Cash at bank" goes down
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $vatdata["PaidDate"], $vatdata["Total"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $vatdata["PaidDate"], $tbSource, "CASH_AT_BANK", $aAccess, $vatdata["id"], $vatdata["Total"],$paidstatus, "SUBTRACT");
                                  //  }

                                    // "VAT control suspense" goes down
                                    $TBCatId = get_trial_balance_category("VAT_CONTROL"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $vatdata["PaidDate"], $vatdata["Total"]);
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $vatdata["PaidDate"], $tbSource, "VAT_CONTROL", $aAccess, $vatdata["id"], $vatdata["Total"],$paidstatus);
                                  //  }
                                }
                            } else if ($from == "BANK_DEL") {

                                if ($tType == "MONEY_IN") {

                                    // "Cash at bank" goes up
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $vatdata["PaidDate"], $vatdata["Total"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "CASH_AT_BANK", $vatdata["id"]);
                                  //  }

                                    // "VAT control suspense" goes down
                                    $TBCatId = get_trial_balance_category("VAT_CONTROL"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $vatdata["PaidDate"], $vatdata["Total"]);
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "VAT_CONTROL", $vatdata["id"]);
                                  //  }
                                } else if ($tType == "MONEY_OUT") {

                                    // "Cash at bank" goes up
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $vatdata["PaidDate"], $vatdata["Total"]);
                                 //   if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "CASH_AT_BANK", $vatdata["id"]);
                                  //  }

                                    // "VAT control suspense" goes up
                                    $TBCatId = get_trial_balance_category("VAT_CONTROL"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $vatdata["PaidDate"], $vatdata["Total"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "VAT_CONTROL", $vatdata["id"]);
                                  //  }
                                }
                            }
                        } else {
                            return;
                        }
                    }
                    break;

                case "dividend":

                    $ci->load->model('clients/dividends');
                    if (is_array($data) && !empty($data)) {
                        $divdata = $data;
                    } else if ((int) $data > 0) {
                        $divdata = $ci->dividends->getDividendDetails((int) $data);
                    } else {
                        return false;
                    }

                    if (!empty($divdata)) {

                        if (!empty($from)) {
                            if ($from == "BANK") {

                                if ($tType == "MONEY_IN") {

                                    // "Cash at bank" goes up
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["Total"]);
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $divdata["PaidDate"], $tbSource, "CASH_AT_BANK", $aAccess, $divdata["id"], $divdata["Total"],$paidstatus);
                                 //   }

                                    // "Dividend suspense" goes down
                                    $TBCatId = get_trial_balance_category("DIVIDEND_SUSPENSE"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["Total"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $divdata["PaidDate"], $tbSource, "DIVIDEND_SUSPENSE", $aAccess, $divdata["id"], $divdata["Total"],$paidstatus, "SUBTRACT");
                                 //   }
                                } else if ($tType == "MONEY_OUT") {

                                    // "Cash at bank" goes down
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["Total"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $divdata["PaidDate"], $tbSource, "CASH_AT_BANK", $aAccess, $divdata["id"], $divdata["Total"],$paidstatus, "SUBTRACT");
                                  //  }

                                    // "Dividend suspense" goes down
                                    $TBCatId = get_trial_balance_category("DIVIDEND_SUSPENSE"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["Total"]);
                                  //  if ((int) $TBentryId > 0) {
                                        add_trial_details($TBCatId, $divdata["PaidDate"], $tbSource, "DIVIDEND_SUSPENSE", $aAccess, $divdata["id"], $divdata["Total"],$paidstatus);
                                  //  }
                                }
                            } else if ($from == "BANK_DEL") {

                                if ($tType == "MONEY_IN") {

                                    // "Cash at bank" goes down
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["Total"], "SUBTRACT");
                                   // if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "CASH_AT_BANK", $divdata["id"]);
                                   // }

                                    // "Dividend suspense" goes up
                                    $TBCatId = get_trial_balance_category("DIVIDEND_SUSPENSE"); //get category ID for given key
                                  //  $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["Total"]);
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "DIVIDEND_SUSPENSE", $divdata["id"]);
                                  //  }
                                } else if ($tType == "MONEY_OUT") {

                                    // "Cash at bank" goes up
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                 //   $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["Total"]);
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "CASH_AT_BANK", $divdata["id"]);
                                  //  }

                                    // "Dividend suspense" goes up
                                    $TBCatId = get_trial_balance_category("DIVIDEND_SUSPENSE"); //get category ID for given key
                                   // $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["Total"], "SUBTRACT");
                                  //  if ((int) $TBentryId > 0) {
                                        rm_trial_details($tbSource, "DIVIDEND_SUSPENSE", $divdata["id"]);
                                  //  }
                                }
                            }
                        } else {

                            // "Capital redemption reserve - Equity Dividend" goes up
                            $TBCatId = get_trial_balance_category("PLA_EQUITY_DIVIDENDS"); //get category ID for given key
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["NetAmount"], "SUBTRACT");
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, "PLA_EQUITY_DIVIDENDS", $divdata["id"]);
                             //   }
                            } else {
                              //  $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["NetAmount"]);
                              //  if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $divdata["PaidDate"], $tbSource, "PLA_EQUITY_DIVIDENDS", $aAccess, $divdata["id"], $divdata["NetAmount"],$paidstatus);
                              //  }
                            }

                            // "Dividend suspense" goes up
                            $TBCatId = get_trial_balance_category("DIVIDEND_SUSPENSE"); //get category ID for given key
                            if ($action == "DELETE") {
                              //  $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["NetAmount"]);
                              //  if ((int) $TBentryId > 0) {
                                    rm_trial_details($tbSource, "DIVIDEND_SUSPENSE", $divdata["id"]);
                              //  }
                            } else {
                             //   $TBentryId = store_trial_entry($TBCatId, $divdata["PaidDate"], $divdata["NetAmount"], "SUBTRACT");
                              //  if ((int) $TBentryId > 0) {
                                    add_trial_details($TBCatId, $divdata["PaidDate"], $tbSource, "DIVIDEND_SUSPENSE", $aAccess, $divdata["id"], $divdata["NetAmount"],$paidstatus, "SUBTRACT");
                             //   }
                            }
                            return;
                        }
                    }
                    break;

                default:
                    break;
            }

            return true;
        } else {
            return false;
        }
    }

    function store_trial_entry($TBCatId, $paidOn, $transection_amount, $action = "") {

        if (empty($TBCatId) || empty($paidOn) || empty($transection_amount)) {
            return false;
        }

        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->load->library('session');
        $user = $ci->session->userdata('user');
        $clientId = $user["UserID"];
        $CompanyID = $user["CompanyID"];
        // prd( $user );

        if (strtotime($paidOn)) {
            $year = getPaidYear($paidOn);
        } else { // for Journal Entry & Trial balance carry forward only
            $year["value"] = $paidOn;
        }
        $where = array(
            "year" => $year["value"],
            "clientId" => $clientId,
            "companyId" => $CompanyID,
            "category_id" => $TBCatId
        );
        $ci->db->select("*");
        $query = $ci->db->get_where("trial_balance", $where);
        // echo $ci->db->last_query();

        if (!empty($action) && $action == "SUBTRACT") {
            $transection_amount = -1 * $transection_amount; // if action is to subtract amount
        }


        if ($query->num_rows() > 0) { // if total Sales already entry exits for given year
            //echo "<pre>";print_r($query->result());echo"</pre>";DIE;
            $trial_balance_entry = $query->row_array();
            // prd( $trial_balance_sales );
            // update the previous total Sales entry for given year
            $new_amount = $trial_balance_entry["amount"] + $transection_amount;
            $update_data = array("amount" => $new_amount);
            $ci->db->where('id', $trial_balance_entry["id"]);
            $ci->db->update('trial_balance', $update_data);
            // echo $ci->db->last_query();die();
            if ($ci->db->affected_rows() > 0) {
                return $trial_balance_entry["id"];
            } else {
                return false;
            }
        } else {  // No previous entry for Sales for given year
            $sales = array("amount" => $transection_amount);
            $insert_data = array_merge($where, $sales);
            $ci->db->insert('trial_balance', $insert_data);
            // echo $ci->db->last_query();die();
            if ($ci->db->affected_rows() > 0) {
                return $ci->db->insert_id();
            } else {
                return false;
            }
        }
    }

    /*
      Function To get `id` for a given `catKey`
     */

    function get_trial_balance_category($key) {

        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;

        $query = "SELECT id FROM " . $prefix . "trial_balance_categories WHERE catKey='" . $key . "'";
        $query = $ci->db->query($query);
        // echo $ci->db->last_query();//die;
        if ($query->num_rows() > 0) {
            $TBCatId = $query->row_array();
            return $TBCatId["id"];
        } else {
            return false;
        }
    }

    /*
      Function To get `key` for a given `ID`
     */

    function get_expenses_category($ID) {

        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;

        $query = "SELECT * FROM `" . $prefix . "expenses_category` WHERE `CategoryType`='BANK' AND `ID`=" . $ID;
        $query = $ci->db->query($query);
        // echo $ci->db->last_query();//die;
        if ($query->num_rows() > 0) {
            $ECatKey = $query->row_array();
            return $ECatKey;
        } else {
            return false;
        }
    }

    function pl_categories() {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;

        $query = "SELECT * FROM " . $prefix . "trial_balance_categories";
        $query = $ci->db->query($query);
        $categories = $query->result();
        $parent = array('0' => '--Select category--');
        $temp = array();
        foreach ($categories as $key => $val) {
            if ($val->parent == 0) {
                foreach ($categories as $k => $v) {
                    if ($v->parent != 0 && $val->id == $v->parent) {
                        $temp[$v->id] = $v->title;
                    }
                }
                $parent[$val->title] = $temp;
                unset($temp);
            }
        }
        return $parent;
    }

    function journal_cat_name($id) {
        if (empty($id)) {
            return FALSE;
        }
        $ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('title');

        $query = $ci->db->get_where('trial_balance_categories', array('ID' => $id));
        if ($query->num_rows() > 0) {
            $response = $query->result();
            foreach ($response as $key => $val) {
                $result['Name'] = $val->title;
            }
            return $result['Name'];
        } else {
            return FALSE;
        }
    }

    function TBListYears($date = "", $repeat = false) {
        if (!empty($date)) {
            $month = date('m', strtotime($date));
            $year = date('Y', strtotime($date));
            $Time = strtotime($date);
        } else {
            $month = date('m');
            $year = date('Y');
            $Time = time();
        }
        $ci = &get_instance();
        $user = $ci->session->userdata('user');

        $nxtDates = getTBStartEndDate(strtotime($user['CompanyEndDate']), $year);

        if ($nxtDates["start"] <= $Time && $Time <= $nxtDates["end"]) {
            
        } else {
            $nxtDates = getTBStartEndDate(strtotime($user['CompanyEndDate']), $year - 1);
        }
        $yearVal["value"] = date("Y", $nxtDates["start"]) . '/' . date("Y", $nxtDates["end"]);
        $yearVal["title"] = date("Y", $nxtDates["end"]);

        if ($repeat)
            $years[] = $yearVal;
        else
            $years = $yearVal;

        if ($repeat) {
            $nxYear = TBListYears(($year - 1) . "-" . $month . "-" . "01", false);
            $years[] = $nxYear;
        }

        return $years;
    }

    function getTBStartEndDate($EndDate, $year) {

        $endDateDay = date('d', $EndDate);
        $endDateMonth = date('m', $EndDate);

        $nxt["start"] = strtotime(($year) . "-" . $endDateMonth . "-" . $endDateDay) + (24 * 60 * 60);
        //$nxt["end"] = strtotime( ($year+1)."-".$endDateMonth."-".$endDateDay );
        /* Edited by manoj */
        $nxt["end"] = strtotime(($year + 1) . "-" . $endDateMonth . "-" . $endDateDay) + (24 * 60 * 60);
        return $nxt;
    }

    function getTBYear_old() {
        $ci = &get_instance();
        $TBYear = $ci->session->userdata("TBYear");
        // echo $TBYear;die();
        $TBYears = TBListYears("", true);
        if (!empty($TBYear)) {
            $TBYearNew = explode("/", $TBYear);
            $tempYearDate = ($TBYearNew[1]);
            $tempYearDate = $tempYearDate . "-" . date("m") . "-01";
            $TBYears = TBListYears($tempYearDate, true);
            foreach ($TBYears as $tb => $tby) {
                if ($tby["value"] == $TBYear) {
                    $TBYearNew = explode("/", $TBYear);
                    $tempYearDate = ($TBYearNew[1]) . "-" . date("m") . "-01";
                    $TBYears = TBListYears($tempYearDate, true);
                    // pr( $TBYears );
                    break;
                }
            }
        }
        return $TBYears;
    }

    function getTBYear() {
        $ci = &get_instance();
        //pr( $ci->session->all_userdata());
        $TBYear = $ci->session->userdata("TBYear");
        //echo $TBYear;die();
        $TBYears = TBListYears("", true);
        if (!empty($TBYear)) {
            $TBYearNew = explode("/", $TBYear);
            $tempYearDate = ($TBYearNew[1]);
            $tempYearDate = $tempYearDate . "-01-01";
            $TBYears = TBListYears($tempYearDate, true);
            // echo "<pre>";print_r($TBYears);echo "</pre>";die();
            /*
              foreach($TBYears as $tb=>$tby){
              if($tby["value"] == $TBYear){
              $TBYearNew = explode("/", $TBYear);
              $tempYearDate = ($TBYearNew[1])."-".date("m")."-01";
              $TBYears = TBListYears( $tempYearDate , true );
              // pr( $TBYears );
              break;
              }
              }
              echo "<pre>";print_r($TBYears);echo "</pre>";die();
             */
        }
        return $TBYears;
    }

    function numberFormatSigned($number = '0.00') {
        if (empty($number)) {
            $number = str_replace('-', '', $number);
            return '&pound;&nbsp;0.00';
        } else {
            return '&pound;&nbsp;' . number_format($number, 2, '.', ',');
        }
    }

    function settings() {
        $ci = & get_instance();
        $ci->load->database();
        $prefix = $ci->db->dbprefix;
        $query = $ci->db->query("SELECT SettingName,SettingValue FROM " . $prefix . "configuration");
        if ($query->num_rows() > 0) {
            $settings = array();
            foreach ($query->result() as $key => $val) {
                $settings[$val->SettingName] = $val->SettingValue;
            }
            return $settings;
        } else {
            $items = array(
                'Invoice_listing' => 25,
                'Expense_listing' => 25,
                'Expense_report' => 100,
                'Dividend_listing' => 25,
                'Bank_listing' => 25,
                'Journal_listing' => 25,
                'Client_listing' => 25,
                'Accountant_listing' => 25,
                'Salary_listing' => 25,
                'Ledger_listing' => 100,
                'Contact_email' => 'example@gmail.com',
                'Car_mileage_cost' => 45,
                'Bike_mileage_cost' => 25,
                'Bicycle_milege_cost' => 25,
                'Travelling_distance' => 10000,
                'VAT_percentage' => 20,
                'Expense_template_text_one' => '',
                'Expense_template_text_two' => '',
                'Signature_image_limit' => 1024,
                'Logo_image_limit' => 1024,
                'Corporation_tax' => 20,
                'TAX_slab_start_date' => date('Y-m-d'),
                'TAX_slab_end_date' => date('Y-m-d'),
                'Car_mileage_overdue_cost' => 25,
                'Bike_mileage_overdue_cost' => 0,
                'Bicycle_mileage_overdue_cost' => 0,
                'Tax_able_income' => '',
                'Financial_year' => '',
                'Term_listing' => '',
                'Email_listing' => 20,
                'tax_free_dividend_allow' => 5000,
                'basic_dividend_tax' => '7.5',
            );
            return $items;
        }
    }

    function numberFormatSignedprofit($number = '0.00') {
        if (empty($number)) {
            $number = str_replace('-', '', $number);
            return '0.00';
        } else {
            if ($number < 0) {
                $number = str_replace('-', '', $number);
                return '(' . number_format($number, 2, '.', ',') . ")";
            } else {
                return number_format($number, 2, '.', ',');
            }
        }
    }

    function TBDropDownYears() {
        $years = array("start" => (APP_START_YEAR - 2), "end" => date("Y"));
        $ci = & get_instance();
        $user = $ci->session->userdata('user');
        $prefix = $ci->db->dbprefix;

        $clientId = $user["UserID"];
        $CompanyID = $user["CompanyID"];
        $query = $ci->db->query("SELECT year FROM " . $prefix . "trial_balance WHERE `clientId`=" . $clientId . " AND companyId=" . $CompanyID . " ");
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $key => $val) {
                $valYears = explode("/", $val['year']);
                if ($valYears[1] < $years["start"]) {
                    $years["start"] = $valYears[1];
                }
                if ($valYears[1] > $years["end"]) {
                    $years["end"] = $valYears[1];
                }
            }
        }
        return $years;
    }

    function Bulk_TBDropDownYears() {
        $years = array("start" => (APP_START_YEAR - 2), "end" => date("Y"));
        $ci = & get_instance();
        $user = $ci->session->userdata('user');
        $prefix = $ci->db->dbprefix;

        $clientId = $user["UserID"];
        $CompanyID = $user["CompanyID"];

        //$query = $ci->db->query("SELECT year FROM " . $prefix . "trial_balance WHERE `clientId`=" . $clientId . " AND companyId=" . $CompanyID . " ");
        $query = $ci->db->query("SELECT year FROM " . $prefix . "trial_balance");
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $key => $val) {
                $valYears = explode("/", $val['year']);
                if ($valYears[1] < $years["start"]) {
                    $years["start"] = $valYears[1];
                }
                if ($valYears[1] > $years["end"]) {
                    $years["end"] = $valYears[1];
                }
            }
        }
        return $years;
    }

    function numberFormatXLS($number = '0.00') {
        if (empty($number)) {
            $number = str_replace('-', '', $number);
            return '0.00';
        } else {
            return $number;
            // return number_format($number,2,'.',',');
        }
    }

    function days_in_month($month, $year) {
        // calculate number of days in a month
        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    function echoLangVar($lang_var) {
        $ci = & get_instance();
        //echo $ci->lang->line( $lang_var );
        return true;
    }

    function get_previous_check_balance($page = 0) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $user = $ci->session->userdata('user');
        $year = (date('Y') - 1) . '/' . date('Y');
        $query = "SELECT MoneyOut,MoneyIn,Description,CheckBalance FROM " . $prefix . "bank_statements WHERE AddedBy = " . $user['UserID'];
        $query .= " ORDER BY TransactionDate ASC LIMIT 0," . ($page);
        $query = $ci->db->query($query);
        $result = $query->result();
        $check_balance = 0;
        if (count($result) > 0) {

            foreach ($result as $key => $val) {
                if ($key == 0) {
                    $check_balance = negativeNumber($val->CheckBalance) + $val->MoneyIn - $val->MoneyOut;
                } else {
                    $check_balance = $check_balance + $val->MoneyIn - $val->MoneyOut;
                }
            }

            return $check_balance;
        } else {
            return 0;
        }
    }

    function company_starting_balance($date = NULL) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $user = $ci->session->userdata('user');
        if (empty($date)) {
            return 0;
        } else {
            $year = TBListYears($date);
            $year = $year['value'];
            $j1 = explode("-", $user['CompanyEndDate']);
            if ($j1[1] == '12' && $j1[2] == '31') {
                $j2 = explode("/", $year);
                $j3 = $j1[0] - 1;
                $year = $j3 . "/" . $j2[0];
            }
            /* Pick-up the starting balance from cash at bank category i.e. 128 in trial balance table */
            $query = "SELECT amount FROM " . $prefix . "trial_balance WHERE category_id = 128 AND year = '" . $year . "'";
            $query .= " AND clientId=" . $user['UserID'];
            $query = $ci->db->query($query);
            $result = $query->result();

            if (count($result) > 0) {
                $result = negativeNumber($result[0]->amount);
                return $result;
            } else {
                return 0;
            }
        }
    }

    function accountant_role($id = null) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        if (empty($id)) {
            return FALSE;
        } else {
            $query = "SELECT Params FROM " . $prefix . "users WHERE ID=" . $id;
            $query = $ci->db->query($query);
            $query = $query->result();

            $result = unserialize($query[0]->Params);
            if (categoryName($result['EmploymentLevel']) == 'Director') {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    // get columns of any table
    function getColumns($where = array(), $columns = "*", $tbl = "company", $responseType = "ARRAY") {

        $ci = & get_instance();

        if (empty($columns)) {
            $columns = "*";
        }
        if (is_array($columns)) {
            $columns = implode(", ", $columns);
        }

        $prefix = $ci->db->dbprefix;
        $ci->db->select($columns);
        $query = $ci->db->get_where($tbl, $where);
        //log_message("error",$ci->db->last_query());
        if ($query->num_rows() > 0) {
            if ($responseType == "ARRAY") {
                $result = $query->result_array();
            } else {
                $result = $query->result();
            }
            return $result;
        } else {
            return FALSE;
        }
    }

    function getFileYear($date = "") {
        if (empty($date)) {
            $date = date("Y-m-d");
        }
        $nxtDates = getFiledDates(strtotime($date));

        $yearVal["value"] = date("Y", $nxtDates["start"]) . '/' . date("Y", $nxtDates["end"]);
        $yearVal["title"] = date("Y", $nxtDates["end"]);

        $years = $yearVal;

        return $years;
    }

    function getFiledDates($Date) {

        $endDateDay = date('d', $Date);
        $endDateMonth = date('m', $Date);
        $year = date('Y', $Date);

        $nxt["start"] = strtotime(($year - 1) . "-" . $endDateMonth . "-" . $endDateDay) + (24 * 60 * 60);
        $nxt["end"] = strtotime(($year) . "-" . $endDateMonth . "-" . $endDateDay);
        return $nxt;
    }

    // Get quarters in paying sequence for a Accounting Year of a Client
    function getDueVatQuarters($clientId, $vYear = NULL) {

        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $vatQuaters = false;
		//echo "SELECT * FROM (`" . $prefix . "vat_quarters`) WHERE `ClientID` = '" . $clientId . "'"; die;
        $result = $ci->db->query("SELECT * FROM (`" . $prefix . "vat_quarters`) WHERE `ClientID` = '" . $clientId . "'");
        if ($row = $result->row()) {
            if (!empty($vYear)) {
                $year = $vYear;
            } else {
                $year = date("Y");
            }
            // echo "<pre>"; print_r( $row );

            for ($i = 1; $i <= 4; $i++) { // can be MAX 4 Quarters only
                $sQuater = "q" . $i . "_start";
                $eQuater = "q" . $i . "_end";

                if (!isset($prevDate)) {
                    $prevDate = $vatQuaters[$i]["FIRST"];
                }

                if (isset($prevDate) && strtotime($prevDate) > strtotime($year . "-" . $row->$sQuater . "-01")) {
                    $year++;
                }
                $prevDate = $vatQuaters[$i]["FIRST"] = $year . "-" . $row->$sQuater . "-01";


                if (strtotime($prevDate) > strtotime($year . "-" . $row->$eQuater . "-" . date('t', strtotime($year . "-" . $row->$eQuater . "-01")))) {
                    $year++;
                }
                $prevDate = $vatQuaters[$i]["SECOND"] = $year . "-" . $row->$eQuater . "-" . date('t', strtotime($year . "-" . $row->$eQuater . "-01"));
            }
        }
        return $vatQuaters;
    }

    /*
      function company_period()
      {
      $ci = & get_instance();
      $user = $ci->session->userdata('user');
      $end_date = $user['CompanyEndDate'];
      if(strtotime($end_date) < strtotime(date('Y-m-d')))
      {
      $start_date 	= 	date('Y-m-d',strtotime('+1 day',strtotime($end_date)));
      $end_date 		= 	date('Y-m-d',strtotime('+1 year',strtotime($end_date)));
      }else{
      $start_date 	= 	date('Y-m-d',strtotime('-1 year',strtotime($end_date)));
      $start_date 	= 	date('Y-m-d',strtotime('+1 day',strtotime($start_date)));
      $end_date 		= 	date('Y-m-d');
      }

      $time_period = array(
      'start_date'	=>	$start_date,
      'end_date'		=>	$end_date
      );
      return $time_period;
      }
     */

    function getNxtTBYear($date = "") {
        if (empty($date)) {
            $date = date("Y") . date("-m-d");
        } else {
            $date = (date("Y", strtotime($date)) + 1) . date("-m-d", strtotime($date));
        }
        $nxtDates = getFiledDates(strtotime($date));

        $yearVal["value"] = date("Y", $nxtDates["start"]) . '/' . date("Y", $nxtDates["end"]);
        $yearVal["title"] = date("Y", $nxtDates["end"]);

        $years = $yearVal;

        return $years;
    }

    function negativeNumber($number = null) {
        if (!empty($number)) {
            $number = trim(str_replace('-', '', $number));
        }
        $number = (float) $number;
        return number_format($number, 2, '.', '');
    }

    function accountant_list() {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $user = $ci->session->userdata('user');
        $query = "SELECT CONCAT(FirstName,' ',LastName) AS Name,ID,Params FROM " . $prefix . "users WHERE UserType='TYPE_ACC' AND (AddedBy=" . $user['UserID'] . " OR SubParent=" . $user['SubParent'] . ")";
        $query = $ci->db->query($query);
        if ($query->num_rows() > 0) {
            $result = array('0' => '--Select Accountant--');
            $accountant = $query->result();
            foreach ($accountant as $key => $val) {
                $val->Params = unserialize($val->Params);
                if ($val->Params['EmploymentLevel'] == 86) {
                    $result[$val->ID] = $val->Name;
                }
            }
            return $result;
        } else {
            return array();
        }
    }

    function company_year($year) {
        $ci = & get_instance();
        $user = $ci->session->userdata('user');
        $end_date = $user['CompanyEndDate'];

        $temp_date = explode('/', $year);
        $cm_end_date = explode('-', $end_date);
        $f_end_date = trim($temp_date[1]) . '-' . $cm_end_date[1] . '-' . $cm_end_date[2];
        $f_start_date = date('Y-m-d', strtotime('-1 year', strtotime($f_end_date)));
        $f_start_date = date('Y-m-d', strtotime('+1 day', strtotime($f_start_date)));
        $f_start_date = explode('-', $f_start_date);
        $f_start_date = $temp_date[0] . '-' . $f_start_date[1] . '-' . $f_start_date[2];

        $year = array(
            'start_date' => $f_start_date,
            'end_date' => $f_end_date
        );
        return $year;
    }

    function store_trial_entry_acc($TBCatId, $year, $clientId, $CompanyID, $transection_amount, $action = "") {

        if (empty($TBCatId) || empty($year) || empty($clientId) || empty($CompanyID) || empty($transection_amount)) {
            return false;
        }

        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;

        $where = array(
            "year" => $year,
            "clientId" => $clientId,
            "companyId" => $CompanyID,
            "category_id" => $TBCatId
        );
        $ci->db->select("*");
        $query = $ci->db->get_where("trial_balance", $where);
        // echo $ci->db->last_query();

        if (!empty($action) && $action == "SUBTRACT") {
            $transection_amount = -1 * $transection_amount; // if action is to subtract amount
        }


        if ($query->num_rows() > 0) { // if total Sales already entry exits for given year
            //echo "<pre>";print_r($query->result());echo"</pre>";DIE;
            $trial_balance_entry = $query->row_array();
            // prd( $trial_balance_sales );
            // update the previous total Sales entry for given year
            $new_amount = $trial_balance_entry["amount"] + $transection_amount;
            $update_data = array("amount" => $new_amount);
            $ci->db->where('id', $trial_balance_entry["id"]);
            $ci->db->update('trial_balance', $update_data);
            // echo $ci->db->last_query();die();
            if ($ci->db->affected_rows() > 0) {
                return $trial_balance_entry["id"];
            } else {
                return false;
            }
        } else {  // No previous entry for Sales for given year
            $sales = array("amount" => $transection_amount);
            $insert_data = array_merge($where, $sales);
            $ci->db->insert('trial_balance', $insert_data);
            // echo $ci->db->last_query();die();
            if ($ci->db->affected_rows() > 0) {
                return $ci->db->insert_id();
            } else {
                return false;
            }
        }
        // echo $ci->db->last_query();
        // die();
        return true;
    }

    function user_detail($id) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->load->database();
        $ci->db->select('*');
        $query = $ci->db->get_where('users', array('ID' => $id));
        $db_error = $ci->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $response = $query->result();
            return get_object_vars($response[0]);
        } else {
            return 0;
        }
    }

    function TBListYearsDD($year = "") {
        if (empty($year)) {
            $year = date('Y');
        }

        $ci = &get_instance();
        $user = $ci->session->userdata('user');

        $nxtDates = getTBStartEndDate(strtotime($user['CompanyEndDate']), $year);

        $nxtYear = $year + 1;
        if (date("Y", $nxtDates["start"]) == date("Y", $nxtDates["end"])) {
            $nxtYear = $year;
        }

        $yearVal["value"] = $year . '/' . ($nxtYear);
        $yearVal["title"] = ($nxtYear);

        return $yearVal;
    }

    function set_user_session($user_data = array(), $accountant_id = NULL) {
        $user = array(
            'Name' => $user_data->FirstName,
            'UserID' => $user_data->ID,
            'UserType' => $user_data->UserType,
            'CompanyID' => $user_data->CID,
            'CompanyRegNo' => $user_data->CompnayRegNo,
            'Params' => unserialize($user_data->CompanyParams),
            'CompanyEmail' => $user_data->CompanyEmail,
            'PercentRateAfterEndDate' => $user_data->PercentRateAfterEndDate,
            'EndDate' => $user_data->EndDate,
            'PercentRate' => $user_data->PercentRate,
            'CompanyEndDate' => $user_data->CompanyEndDate,
            'AccountantAccess' => $accountant_id,
            'UserParams' => $user_data->Params,
            'VAT_TYPE' => $user_data->Type,
            'IncorporationDate' => $user_data->IncorporationDate,
            'AddedBy' => $user_data->AddedBy,
            'SubParent' => $user_data->SubParent,
            'T_AND_C_Version' => $user_data->T_AND_C_Version
        );
        return $user;
    }

    function set_accountant_session($user_data = array()) {
        $user = array(
            'Name' => $user_data->FirstName,
            'UserID' => $user_data->ID,
            'UserType' => $user_data->UserType,
            'CompanyID' => $user_data->CID,
            'CompanyRegNo' => $user_data->CompnayRegNo,
            'Params' => unserialize($user_data->CompanyParams),
            'PercentRateAfterEndDate' => $user_data->PercentRateAfterEndDate,
            'EndDate' => $user_data->EndDate,
            'PercentRate' => $user_data->PercentRate,
            'CompanyEndDate' => $user_data->CompanyEndDate,
            'UserParams' => $user_data->Params,
            'AddedBy' => $user_data->AddedBy,
            'SubParent' => $user_data->SubParent,
            'T_AND_C_Version' => $user_data->T_AND_C_Version
        );
        return $user;
    }

    function check_filed_account() {
        $ci = & get_instance();
        $user = $ci->session->userdata('user');
        $ci->db->select('year');
        $query = $ci->db->get_where('accounts_filed', array('clientId' => $user['UserID']));
        $db_error = $ci->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
        } else {
            $result = array();
        }

        return $result;
    }

    function get_filed_year() {
        $result = check_filed_account();
        $ci = &get_instance();
        $user = $ci->session->userdata('user');

        if (count($result) > 0) {
            $j_date = date('d-m-Y', strtotime('-1 year', strtotime($user['CompanyEndDate'])));
            $j_date = date('d-m-Y', strtotime('+1 day', strtotime($j_date)));
        } else {
            $end_year = date('Y', strtotime($user['CompanyEndDate']));
            $start_year = date('Y', strtotime($user['IncorporationDate']));
            if ($end_year == $start_year) {
                $j_date = date('d-m-Y', strtotime('-1 year', strtotime($user['CompanyEndDate'])));
                $j_date = date('d-m-Y', strtotime('+1 day', strtotime($j_date)));
            } else {
                $j_date = date('d-m-Y', strtotime('+1 day', strtotime($user['IncorporationDate'])));
            }
        }
        return $j_date;
    }

    function getPaidYear($date = "") {
        if (!empty($date)) {
            $month = date('m', strtotime($date));
            $year = date('Y', strtotime($date));
            $Time = strtotime($date);
        } else {
            $month = date('m');
            $year = date('Y');
            $Time = time();
        }
        $ci = &get_instance();
        $user = $ci->session->userdata('user');

        $nxtDates = getTBPaidStartEndDate(strtotime($date), $year);
        // prd( $nxtDates );
        if ($nxtDates["start"] <= $Time && $Time <= $nxtDates["end"]) {
            
        } else {
            $nxtDates = getTBPaidStartEndDate(strtotime($date), $year - 1);
        }
        $yearVal["value"] = date("Y", $nxtDates["start"]) . '/' . date("Y", $nxtDates["end"]);
        $yearVal["title"] = date("Y", $nxtDates["end"]);

        $year1 = $yearVal['value'];
        $exp = explode('/', $year1);
        if ($exp[0] == $exp[1]) {
            $y2 = $exp[0] - 1;
            $yearVal['value'] = $y2 . "/" . $exp[1];
            return $yearVal;
        } else {
            return $yearVal;
        }
    }

    function getTBPaidStartEndDate($PaidDate, $year) {

        $ci = &get_instance();
        $user = $ci->session->userdata('user');
        $EndDate = $user['CompanyEndDate'];
        $endDateDay = '';
        $endDateMonth = '';
        $endDateDay = date('d', strtotime($EndDate));
        $endDateMonth = date('m', strtotime($EndDate));
        if ($endDateDay == 31 && $endDateMonth == 12) {
            $endDateDay = 30;
            $endDateMonth = $endDateMonth;
        } else {
            $endDateDay = $endDateDay;
            $endDateMonth = $endDateMonth;
        }

        $nxt["start"] = strtotime(($year) . "-" . $endDateMonth . "-" . $endDateDay) + (24 * 60 * 60);
        $nxt["end"] = strtotime(($year + 1) . "-" . $endDateMonth . "-" . $endDateDay);
        if (!empty($user) && isset($user['CompanyEndDate']) && isset($user['IncorporationDate'])) {
            $companyEndYear = date("Y", strtotime($user['CompanyEndDate']));
            $incorporationEndYear = date("Y", strtotime($user['IncorporationDate']));
            if (((int) $companyEndYear - (int) $incorporationEndYear) == 1 && $PaidDate < $nxt["start"]) {// it runs only if no accounts/returns were filed for any single year yet
                $nxtYear = date("Y", strtotime($user['IncorporationDate']));
                $nxt["start"] = strtotime($user['IncorporationDate']);
                $nxt["end"] = strtotime(($nxtYear + 1) . "-" . $endDateMonth . "-" . $endDateDay);
            }
        }

        return $nxt;
    }

    // Add details about Modified "Trial balance"
    function add_trial_details($TBCatId, $paidOn, $tbSource, $tbDetailType, $aAccess, $itemId, $amount, $paidstatus = NULL, $action = "") {
        
		if (empty($TBCatId) || empty($paidOn)) {
            return false;
        }
        $ci = & get_instance();
        $user = $ci->session->userdata('user');
        $clientId = $user["UserID"];
        $companyID = $user["CompanyID"];
        // $TBentryId = (int) $TBentryId;
        $prefix = $ci->db->dbprefix;
        if (!empty($action) && $action == "SUBTRACT") {
            $amount = -1 * $amount; // if amount is -tive
            $itemStatus = 2;
        } else {
            $itemStatus = 1;
        }

        if (strtotime($paidOn)) {
            $year = getPaidYear($paidOn);
        } else { // for Journal Entry & Trial balance carry forward only
            $year["value"] = $paidOn;
        }

        $insert_data = array(
            "category_id" => $TBCatId,
            "year" => $year["value"],
            "itemId" => $itemId,
            "clientId" => $clientId,
            "companyID" => $companyID,
            "itemStatus" => $itemStatus,
            "source" => $tbSource,
            "type" => $tbDetailType,
            "amount" => $amount,
            "addedOn" => date("Y-m-d H:i:s"),
            "AccountantAccess" => $aAccess
        );

        if ($paidstatus == 3) {
            $where = array(
                'itemId' => $itemId,
                'itemStatus' => $itemStatus,
                'clientId' => $clientId,
                'CompanyID' => $companyID,
                'source' => $tbSource,
                'type' => $tbDetailType,
            );

            $ci->db->select('itemId');
            $ci->db->from('tb_details');
            $ci->db->where($where);
            $query = $ci->db->get();
          //  echo   $ci->db->last_query(); die();
           if ($query->num_rows() > 0) {
              //  echo 'update'; die();
                $ci->db->where($where);
                $ci->db->update('tb_details', $insert_data);
               // echo $ci->db->last_query(); 
            } else {
                // echo 'insert'; die();
               $ci->db->insert('tb_details', $insert_data);
            }
        } else {
            //  echo 'old insert'; die();
            $ci->db->insert('tb_details', $insert_data);
        }
        //echo $ci->db->last_query(); 
        if ($ci->db->affected_rows() > 0) {
            return $ci->db->insert_id();
        } else {
            return false;
        }
    }

    // Remove details from "Trial balance"
    function rm_trial_details($tbSource, $tbDetailType, $itemId) {
        $ci = & get_instance();
        $prefix = $ci->db->dbprefix;
        $where = array(
            "itemId" => $itemId,
            "source" => $tbSource,
            "type" => $tbDetailType
        );

        $data = array(
            'deleteStatus' => '1'
        );

        $ci->db->where($where);
        $ci->db->update('tb_details', $data);

        if ($ci->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function transaction_date($id) {
        $ci = & get_instance();
        $ci->load->model('clients/trial_balance');
        $data = $ci->trial_balance->getBankDetails($id);
        return $data[0]['TransactionDate'];
    }

    /* email setting get email template format */

    function emailSetting() {
        $ci = & get_instance();
        $user = $ci->session->userdata('user');
        $userId = $user['UserID'];
        $ci->load->model('accountant/Settings');
        $response = $ci->Settings->getEmailSignature($user['UserID']);
        return $response;
    }

    function checkvatifExist() {
        $ci = & get_instance();
        $ci->load->model('clients');
        $vat_listing = $ci->clients->getVatType();
        return $vat_listing;
    }

    function bankCategory() {
        $ci = & get_instance();
        $ci->load->model('clients/bank');
        $cat_listing = $ci->bank->getCategoryDescript();
        return $cat_listing;
    }

    /* check terms and conditions if exist */

    function checkTermAndConditionVersion($userId = null, $addedBy = NULL) {
        if (!empty($userId) && !empty($addedBy)) {
            $ci = & get_instance();
            $ci->load->model('Clients');
            $data = $ci->Clients->checkTermandconditionversion($userId, $addedBy);
            return $data;
        }
    }

    /*
     * Insert email tracking
     * Params Data
     * Return True
     */

    function emailTracking($data = NULL) {
        if (!empty($data)) {
            $ci = & get_instance();
            $prefix = $ci->db->dbprefix;
            $ci->db->insert('email_tracking', $data);
            if ($ci->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    function getTaxondividend() {
        $array = array("ZRB" => 5000, "BRB" => 27000, "HRB" => 118000);
        return $array;
    }

    function excerpt_limit_words($string, $word_limit) {
        $words = explode(" ", $string);
        return implode(" ", array_splice($words, 0, $word_limit));
    }

    //Update Action Logs
    function update_logs($Source = NULL, $type = NULL, $action = NULL, $userId = NULL, $ItemId = NULL) {
        $ci = & get_instance();
        $user = $ci->session->userdata('user');

        /* if(empty($user['AccountantAccess']) && $user['UserType']=='TYPE_CLI'){
          $userId = $user['UserID'];
          }
          else{
          $userId = $user['AccountantAccess'];
          } */
        $ac = '';
        if (!empty($user['AccountantAccess'])) {
            $ac = $user['AccountantAccess'];
        } else {
            $ac = 0;
        }
        try {
            $data = array(
                'UserId' => $user['UserID'],
                'AccessAccount' => $ac,
                'Source' => $Source,
                'Type' => $type,
                'ItemId' => $ItemId,
                'Action' => $action,
                'Status' => 1,
                'addedOn' => date('Y-m-d h:i:s')
            );

            $ci->load->model('Login');
            $response = $ci->Login->updateLogs($data);
            if ($response)
                return TRUE;
        } catch (Exception $e) {
            echo "Logs error message";
            logging_function($e->getMessage());
            throw $e;
        }
    }

    function getInvoicenumber($Id) {

        $ci = & get_instance();
        $ci->load->model('clients/Log');
        $ino = $ci->Log->getInvoicenumberlog($Id);
        return $ino;
    }

    function getExpensenumber($Id) {
        $ci = & get_instance();
        $ci->load->model('clients/Log');
        $eno = $ci->Log->getExpensenumberlog($Id);
        return $eno;
    }
	function getPurchasenumber($Id) {
        $ci = & get_instance();
        $ci->load->model('clients/Log');
        $eno = $ci->Log->getPurchasenumberlog($Id);
        return $eno;
    }

    function getDividendnumber($Id) {
        $ci = & get_instance();
        $ci->load->model('clients/Log');
        $dno = $ci->Log->getDividendnumberlog($Id);
        return $dno;
    }
	function getAllMenus() {
		$ci       = & get_instance();
        $query    = $ci->db->query('SELECT * FROM cashman_menus where parent = 0');
        $allParentMenus = $query->result_array();		
		if( !empty($allParentMenus) )
		{
			foreach($allParentMenus as $key => $parentMenu)
			{
				$query1    = $ci->db->query('SELECT * FROM cashman_menus where parent = '.$parentMenu['id']);
				$allParentMenus[$key]['subMenus'] = $query1->result_array();		
			}
		}
        return $allParentMenus;
    }
	function getMenusByClientId($userID = null ) {
		$ci       = & get_instance();
        $menusIds = getClientAccssibleMenus($userID);
		$isUrlAccessible = false;
		$page_url		 = $ci->uri->segment(1);
		if( $menusIds != '' ){
			if( $menusIds == 'all')
			{
				$newMenus = getAllMenus();
				return $newMenus;
			}
			$query1    = $ci->db->query('SELECT * FROM cashman_menus where id IN ('.$menusIds.')');
			$allMenus  = $query1->result_array();
			$newMenus  = array();
			if( !empty($allMenus) )
			{
				foreach($allMenus as $menu)
				{
					if( $menu['url'] == $page_url )
						$isUrlAccessible = true;
					if( $menu['parent'] == 0 )
						$newMenus[] = $menu;
					else
					{
						$key = array_search($menu['parent'], array_column($allMenus, 'id'));
						$newMenus[$key]['subMenus'][] = $menu;
					}
				}
			}
		}
		else{
			$newMenus = array();			
		}	
		if($page_url == 'dashboard' || $page_url == 'client')
			$isUrlAccessible = true;
		if( $isUrlAccessible == false)
		{
			$msg = "<br/><div class='alert alert-danger text-center'><i class='glyphicon glyphicon-exclamation-sign'></i>&nbsp;";
            $msg .=$ci->lang->line("NO_ACCESS_TO_TB");
            $msg .="</div>";
            $ci->session->set_flashdata("adminDashboard", $msg);
            setRedirect('client');
		}
        return $newMenus;
    }
	function getClientAccssibleMenus($userID = null ) {
		$ci       = & get_instance();
        $query    = $ci->db->query('SELECT accessible_menus FROM cashman_users where id='.$userID);
        $menusIds = $query->row()->accessible_menus;
		return $menusIds;
	}
	
	#Get client's bank statment uploade last date
	function getBankStatmentLastUploadDate($clientID = null, $cID = null) {
		$ci       = & get_instance();
		$query    = $ci->db->query('SELECT TransactionDate FROM `cashman_bank_statements` where AddedBy ='.$clientID.' AND compnayID='.$cID);
        $TransactionDate = $query->row()->TransactionDate;
	    return $TransactionDate;
	}
	
	#Get compnay quaters details
	function getQuartersDetails($cID = null, $quarters = null) {
		$ci       = & get_instance();
		echo 'SELECT * FROM `cashman_vats` where companyID ='.$cID.' AND quarter='.$quarters;
		$query    = $ci->db->query('SELECT * FROM `cashman_vats` where companyID ='.$cID.' AND quarter='.$quarters);       
	    if ($query->num_rows() > 0) {
            $result = $query->result_array();
        } else {
            $result = array();
        }
		return $result;
	}
	
	#Just Test Function To Get Trial Balance Category Table Listing
	function testFunGetTBCatParent(){		
		$ci = &get_instance();
        $prefix = $ci->db->dbprefix;
        $ci->db->select('id,title');
		$ci->db->where('`id` IN (SELECT parent FROM cashman_trial_balance_categories where AnalysisLedgerParent = 0)', NULL, FALSE);
        $query = $ci->db->get('cashman_trial_balance_categories');
		
		$result = $query->result();
		return $result;		
	}
		
}
