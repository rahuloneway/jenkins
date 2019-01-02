<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//session_start();
class Dividend extends CI_Controller {

    public function Dividend() {
        parent::__construct();
        /*
         * 	First check if accountant is accessing the Clients account or not.
         * 	Preventing accountant from direct access to the client's dashboard.
         */
		$user = $this->session->userdata('user');
        /*if ($user['UserType'] == 'TYPE_CLI' && empty($user['AccountantAccess'])) {
           setRedirect(site_url());
        } else {
            if (isset($_GET['clientID'])) {
                checkUserAccess(array('TYPE_ACC', 'TYPE_CLI'));
            } else {
                checkUserAccess(array('TYPE_CLI'));
            }
        }*/

        /* Load the expense model */
        $this->load->model('clients/dividends');
    }

    public function index() {
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        $data['title'] = "Dashboard | Dividend";
        $data['share_holders'] = $this->dividends->getShareHoldersList();
        $data['shares'] = $this->dividends->getTotalShares();
        $data['items'] = $this->dividends->getItems(DIVIDEND_PAGINATION_LIMIT, $page);
        $totalItems = $this->dividends->totalDividends();

        $data['pagination'] = $this->getPagination(site_url() . 'dividend', DIVIDEND_PAGINATION_LIMIT, $totalItems);
        $this->load->view('client/dividend/default', $data);
    }

    public function getPagination($url = null, $perPage = DIVIDEND_PAGINATION_LIMIT, $totalItem = 0) {
        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'dividend';
        $config['num_links'] = 2;
        $config['per_page'] = $perPage;
        $config['total_rows'] = $totalItem;
        $config['uri_segment'] = 2;
        $config['full_tag_open'] = '<ul class="pagination pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '<span aria-hidden="true">Prev</span><span class="sr-only">Prev</span>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '<span aria-hidden="true">Next</span><span class="sr-only">Next</span>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['last_link'] = FALSE;
        $config['first_link'] = FALSE;
        $config['cur_tag_open'] = '<li><a><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    /**
     * 	This function loads the form for adding new dividend.
     * 	This form also used for editing the dividend.
     */
    public function newDividend() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            /* Get shareholder list of the client */
            $data['share_holders'] = $this->dividends->getShareHoldersList();

            $data['shares'] = $this->dividends->getTotalShares();
            $task = safe($this->encrypt->decode($_POST['task']));
            $id = safe($this->encrypt->decode($_POST['ID']));
            $data['task'] = $task;
            $data['Directors'] = $this->dividends->getDirectorsList();
            $data['bank_statement_id'] = '';
            $data['bank_paid_date'] = '';
            if ($task == 'editDividend' || $task == 'copyDividend') {
                $data['item'] = $this->dividends->getItem($id);
            } elseif ($task == 'viewDividend') {
                $data['item'] = $this->dividends->getItem($id);
                $data['Name'] = $this->dividends->getShareHolderName($data['item']['ShareholderID']);
                $this->load->view('client/dividend/dividend_status', $data);
                return true;
            }
            $data['ajax_add'] = '';
            $this->load->view('client/dividend/form', $data);
        } else {
            show_404();
        }
    }

    /**
     * 	Ajax function to check if the selected shareholder is director or not.
     * 	Return type: shareholder/director
     * 	
     */
    public function action() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task = $this->encrypt->decode($_POST['task']);
            $task = explode('/', $task);
            if (isset($_POST['PaidDate'])) {
                $task[] = mDate($_POST['PaidDate']);
            }
            /* Check if correct task is being executed */
            $actions = array(
                '1' => 'ACTION_CHECK_USER',
                '2' => 'ACTION_DELETE',
                '3' => 'ACTION_PAID'
            );
            if (!in_array($task[0], $actions)) {
                $msg = '<div class="alert alert-danger"><i class=""></i>';
                $msg = $this->lang->line('DIVIDENT_WRONG_DATA_ERROR');
                $msg = '</div>';
                $this->session->set_flashdata('dividendMessage', $msg);
                die('link');
            }
            $response = $this->dividends->performAction($task);

            if ($task[0] == 'ACTION_CHECK_USER') {
                if ($response) {
                    die($response);
                } else {
                    $msg = '<div class="alert alert-danger"><i class=""></i>';
                    $msg .= $this->lang->line('DIVIDENT_UNEXPECTED_ERROR');
                    $msg .= '</div>';
                    $this->session->set_flashdata('dividendMessage', $msg);
                    die('link');
                }
            } elseif ($task[0] == 'ACTION_DELETE' || $task[0] == 'ACTION_PAID') {
                if ($response) {
                    $json = array();
                    $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
                    $data['items'] = $this->dividends->getItems(DIVIDEND_PAGINATION_LIMIT, $page);
                    $json['pagination'] = $this->getPagination('dividend', DIVIDEND_PAGINATION_LIMIT, count($data['items']));
                    $json['items'] = $this->load->view('client/dividend/dividend_listing', $data, TRUE);
                    echo json_encode($json);
                    exit;
                } else {
                    die('error');
                }
            }
        } else {
            show_404();
        }
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			//echo "<pre>";print_r($_POST);die('lol');
            /* Check if Created by accountant while accessing the client account */
            $accountant_access = clientAccess();

            if (!empty($_POST['bank_paid_date'])) {
                $paidDate = mDate($_POST['bank_paid_date']);
                $status = 2;
            } else {
                $paidDate = (isset($_POST['IsPaid'])) ? mDate($_POST['paidDate']) : '';
                $status = (isset($_POST['IsPaid'])) ? 2 : 1;
            }
            

            if ($_POST['ajax_add'] == 'bank_ajax_add') {
                $this->load->model('clients/bank');
                $bs_id = $this->encrypt->decode($_POST['bank_statement_id']);
                $statement = $this->bank->getStatements($bs_id);
                $paidDate = mDate($statement['TransactionDate']);
                $status = 2;

                /* Check if dividend is already created for this statement or not */
                $response = $this->dividends->check_statement_dividend($bs_id);
                if ($response) {
                    $json['ajax_add'] = 'dividend';
                    $json['msg'] = $this->lang->line('DIVIDEND_STATEMENT_ALREADY_EXISTS');
                    $json['error'] = 'error';
                    die(json_encode($json));
                }
            } else {
                $bs_id = '';
            }

            $user = $this->session->userdata('user');
            $addedBy = $user['UserID'];
            $addedOn = date('Y-m-d');

            $fields = array(
                '1' => 'SID',
                '2' => 'Params',
                '3' => 'TotalShares'
            );
            //$total_shares = $this->dividends->getTotalShares();
            //$tax_amount = ($_POST['dividendAmount'] / DIVIDEND_TAX_PERCENT) / 100;
            //$gross_amount = $_POST['dividendAmount'] + $tax_amount;

            if (!empty($status) && !empty($paidDate)) {
                $exp=  explode('-',$paidDate);
                if($exp[0]<2016){
                $tax_amount = ($_POST['dividendAmount'] / DIVIDEND_TAX_PERCENT) / 100;
                }else{
                 $tax_amount=0;    
                }
                
                $gross_amount = $_POST['dividendAmount'] + $tax_amount;
            } else {
                $tax_amount = 0;
                $gross_amount = 0;
            }
            $tax_amount = number_format((float) $tax_amount, 2, '.', '');
            $gross_amount = number_format((float) $gross_amount, 2, '.', '');
            $data = array(
                'ShareholderID' => $_POST['ShareHolders'],
                'CompanyID' => $user['CompanyID'],
                'DividendDate' => mDate($_POST['dividendDate']),
                'GrossAmount' => $gross_amount,
                'TaxAmount' => $tax_amount,
                'NetAmount' => $_POST['dividendAmount'],
                'PaidByDirectorLoan' => (isset($_POST['directorLoan'])) ? $_POST['directorLoan'] : '',
                'AddedOn' => $addedOn,
                'AddedBy' => $addedBy,
                'PaidOn' => $paidDate,
                'Status' => $status,
                'AccountantAccess' => $accountant_access,
                'BankStatement' => $bs_id,
                'Address' => $_POST['addressParams'],
                'shareholder_address' => $_POST['shareholderaddress'],
            );
            $response = $this->dividends->addDividend($data);
            /* This bloc to check if dividend is created using bank statement */
            if (!empty($_POST['bank_statement_id'])) {
                $temp_did = explode('-', $response);
                $temp_did = end($temp_did);
                $bs_id = $this->encrypt->decode($_POST['bank_statement_id']);
                $temp_record = $this->session->userdata('temp_statement_record');
                $temp_statement_record = json_decode($temp_record);
                if (!is_array($temp_statement_record)) {
                    $temp_statement_record = array();
                }
                $temp_statement_record[$bs_id] = array(
                    'ItemID' => $temp_did,
                    'ItemType' => 'D'
                );
                $this->session->set_userdata('temp_statement_record', json_encode($temp_statement_record));
            }
            if ($response) {
                $msg = sprintf($this->lang->line('DIVIDEND_CREATED_SUCCESSFULLY'), $response);
            } else {
                $msg = $this->lang->line('DIVIDEND_CREATION_ERROR');
            }
            $json['link'] = site_url() . 'dividend';
            $json['ajax_add'] = '';

            /* This bloc to check if dividend is created using bank statement */
            if (!empty($_POST['bank_statement_id'])) {                
                  $associated_with = $response;
                  $did = $response;
                  $associated_with = explode('-',$associated_with);
                  $associated_with = end($associated_with);
                  $data = array(
                  'AssociatedWith'	=>	$associated_with,
				  'StatementType'	=>	'D',
                  );
                  $this->load->model('clients/bank');
                  $response = $this->bank->update_statements($data,$bs_id);
                  if(!$response)
                  {
                  $msg = $this->lang->line('BANK_RECONCILED_EXPENSES_ERROR');
                  }else{
                  $msg = sprintf($this->lang->line('DIVIDEND_CREATED_SUCCESSFULLY'),$did);
                  }
                 
                $json['link'] = site_url() . 'clients/banks/';

                if ($_POST['ajax_add'] == 'ajax_add') {
                    $json['ajax_add'] = 'ajax_add';
                    $file_id = $this->session->userdata('statement_file_id');
                    $response = $_SESSION['bank_statements'];
                    $response = json_decode($response);
                    $data['items'] = $response;
                    $data['page'] = "banks";
                    $data['title'] = 'Cashman | Bank Uploads';
                    $json['html'] = $this->load->view('client/banks/statement_listing', $data, true);
                } elseif ($_POST['ajax_add'] == 'bank_ajax_add') {
                    $this->load->model('clients/bank');
                    $bank_record = array(
                        'AssociatedWith' => $temp_did
                    );
                    $response = $this->bank->update_bank_association($bank_record, $bs_id);
                    $json['ajax_add'] = $_POST['ajax_add'];
                } else {
                    $json['ajax_add'] = '';
                }
            }
            $json['msg'] = $msg;
            die(json_encode($json));
        } else {
            show_404();
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            /* Check if Created by accountant while accessing the client account */
            $accountant_access = clientAccess();

            $user = $this->session->userdata('user');
            $addedBy = $user['UserID'];
            $addedOn = date('Y-m-d');

            $did = $this->encrypt->decode($_POST['did']);
            $task = $this->encrypt->decode($_POST['task']);
            //$tax_amount = ($_POST['dividendAmount'] / DIVIDEND_TAX_PERCENT) / 100;
            $tax_amount = 0;
            $tax_amount = number_format((float) $tax_amount, 2, '.', '');
            //$gross_amount = $_POST['dividendAmount'] + $tax_amount;
            $gross_amount = 0;
            $gross_amount = number_format((float) $gross_amount, 2, '.', '');
            $data = array(
                'ShareholderID' => $_POST['ShareHolders'],
                'CompanyID' => $user['CompanyID'],
                'DividendDate' => mDate($_POST['dividendDate']),
                'GrossAmount' => $gross_amount,
                'TaxAmount' => $tax_amount,
                'NetAmount' => $_POST['dividendAmount'],
                'PaidByDirectorLoan' => (isset($_POST['directorLoan'])) ? $_POST['directorLoan'] : '',
                //'AddedOn'			=>	$addedOn,
                'AddedBy' => $addedBy,
                'PaidOn' => (isset($_POST['IsPaid'])) ? mDate($_POST['paidDate']) : '',
                'Status' => (isset($_POST['IsPaid'])) ? 2 : 1,
                'AccountantAccess' => $accountant_access
            );
            $response = $this->dividends->updateDividend($data, $did);
            if ($response) {
                if(isset($_POST['IsPaid']))
                {
                    update_trial_balance("dividend", $did, "", "", "", "3");
                }
                $msg = sprintf($this->lang->line('DIVIDENT_UPDATE_SUCCESSFUL'), $response);
				update_logs('DIVIDEND', 'DIVIDEND_UPDATED', 'Update', "", $did);
            } else {
                $msg = $this->lang->line('DIVIDENT_UNEXPECTED_ERROR');
            }
            $json['link'] = site_url() . 'dividend';
            $json['msg'] = $msg;
            die(json_encode($json));
        } else {
            show_404();
        }
    }

    /**
     * 	This function checks the type of shareholder chosen from the drop-down in dividend form.
     */
    public function checkShareHolderType() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task = $_POST['task'];
            if (empty($task)) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>';
                $msg .= $this->lang->line('DIVIDEND_UNEXPECTED_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('dividendMessage', $msg);
                die('link');
            } else {
                $response = $this->dividends->checkShareHolder($task);
                if (!empty($response[0]->CompanyID)) {
                    $addresponse = $this->dividends->checkCompanyAddress($response[0]->CompanyID);
                    $addressParams = $addresponse[0]->Params;
                    if (!empty($addressParams))
                        $json['addressParams'] = $addressParams;
                    $json['shareHolderaddressParams'] = $response[0]->Params;
                }
                if ($response) {
                    if ($response[0]->DesignationType == 'D' || $response[0]->Is_Director == 1) {
                        $json['style'] = 'block';
                    } elseif ($response[0]->DesignationType == 'S') {
                        $json['style'] = 'none';
                    } else {
                        $json['style'] = 'none';
                    }
                    $json['shares'] = $response[0]->TotalShares;
                    die(json_encode($json));
                } else {
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>';
                    $msg .= $this->lang->line('DIVIDEND_UNEXPECTED_ERROR');
                    $msg .= '</div>';
                    $this->session->set_flashdata('dividendMessage', $msg);
                    die('link');
                }
            }
        } else {
            show_404();
        }
    }

    public function search() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = array(
                'SharerName' => safe($_POST['SharerName']),
                'dStartDate' => mDate($_POST['dStartDate']),
                'dEndDate' => mDate($_POST['dEndDate']),
                'NetAmount' => safe($_POST['NetAmount']),
                'GrossAmount' => safe($_POST['GrossAmount']),
                'VoucherNumber' => safe($_POST['VoucherNumber'])
            );

            $this->session->set_userdata('DividendSearch', $search);
            setRedirect('dividend');
        } else {
            show_404();
        }
    }

    /**
     * 	This function resets the search form on the default layout of dividend.
     */
    public function clean() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->session->set_userdata('DividendSearch', '');
            $this->session->set_userdata('DividendSearchRecords', '');
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $data['items'] = $this->dividends->getItems(DIVIDEND_PAGINATION_LIMIT, $page);
            $json = array();
            $totalItems = $this->dividends->totalDividends();
            $json['pagination'] = $this->getPagination('clients/dividend/index', DIVIDEND_PAGINATION_LIMIT, $totalItems);
            $data['shares'] = $this->dividends->getTotalShares();
            $json['items'] = $this->load->view('client/dividend/dividend_listing', $data, TRUE);
            echo json_encode($json);
            exit;
        } else {
            show_404();
        }
    }

    public function dividendSort() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $order = safe($this->encrypt->decode($_POST['order']));
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $des_order_value = array(
                'SORT_BY_SHARERNAME' => 'CONCAT(s.FirstName," ",s.LastName) DESC',
                'SORT_BY_DIVIDEND_VOUCHER' => 'd.VoucherNumber DESC',
                'SORT_BY_DATE' => 'd.DividendDate DESC',
                'SORT_BY_NET_AMOUNT' => 'd.NetAmount DESC',
                'SORT_BY_TAX_AMOUNT' => 'd.TaxAmount DESC',
                'SORT_BY_GROSS_AMOUNT' => 'd.GrossAmount DESC'
            );
            $asc_order_value = array(
                'SORT_BY_SHARERNAME' => 'CONCAT(s.FirstName," ",s.LastName) ASC',
                'SORT_BY_DIVIDEND_VOUCHER' => 'd.VoucherNumber ASC',
                'SORT_BY_DATE' => 'd.DividendDate ASC',
                'SORT_BY_NET_AMOUNT' => 'd.NetAmount ASC',
                'SORT_BY_TAX_AMOUNT' => 'd.TaxAmount ASC',
                'SORT_BY_GROSS_AMOUNT' => 'd.GrossAmount ASC'
            );
            $prev_order = $this->session->userdata('DividendSortingOrder');
            $dir = '';
            if (!empty($prev_order)) {
                $order_value = $des_order_value[$order];
                if ($order_value == $prev_order) {
                    $order_value = $asc_order_value[$order];
                    $dir = 'fa-sort-up';
                } else {
                    $order_value = $des_order_value[$order];
                    $dir = 'fa-sort-desc';
                }
            } else {
                $order_value = $des_order_value[$order];
            }
            $this->session->set_userdata('DividendSortingOrder', $order_value);
            $data['items'] = $this->dividends->getItems(DIVIDEND_PAGINATION_LIMIT, $page);

            $d[0] = $this->load->view('client/dividend/dividend_listing', $data, true);
            $d[1] = $dir;
            echo json_encode($d);
            exit;
        } else {
            show_404();
        }
    }

	
    /**
     * 	This function prepares the dividend pdf data.
     */
    public function pdf($id = NULL) { 
        $user = $this->session->userdata('user');
        $id = $this->encrypt->decode($id);
        if (empty($id)) {
            show_404();
        }

        $id = explode('/', $id);
        $user = $this->session->userdata('user');
        $include_signature = end($id);
        $response = $this->dividends->getItem($id[1]);
		//echo "<pre>"; print_r($user);
        $acc_id = clientAccess();
        if (!empty($acc_id)) {
            $accountant_detail = $this->dividends->get_accountant_signature($acc_id);
        } else {
            $accountant_detail = array(
                'Salutation' => '',
                'DOB' => '',
                'NI_NUMBER' => '',
                'UTR' => '',
                'AddressTwo' => '',
                'AddressThree' => '',
                'ImageLink' => '',
                'DigitalSignature' => '',
                'EmploymentLevel' => ''
            );
        }
        if ($response) {
            /* Prepare data */
            $data['accountant_detail'] = $accountant_detail;
            $data['include_signature'] = $include_signature;
            $data['CompanyName'] = companyName($user['CompanyID']);
            $data['Company_details'] = $this->dividends->companyDetails($user['CompanyID']);
            $data['item'] = $response;
			
			$start_date = date('Y-m-d', strtotime('-1 year', strtotime($user['CompanyEndDate'])));
			$end_date = $this->dividends->genrateFinacialYearEndDate($data['item']['PaidOn'], $start_date, $user['CompanyEndDate']);
			
		
            /*if ($data['item']['PaidOn'] < $user['CompanyEndDate']) {
				$end_date = $user['CompanyEndDate'];
            	
            } else {
				
				$start_date = date('Y-m-d', strtotime('-1 year', strtotime($user['CompanyEndDate'])));
				$end_date = date('Y-m-d', strtotime('+1 year', strtotime($user['CompanyEndDate'])));
                if ($data['item']['PaidOn'] > $end_date) {
                    $end_date = date('Y-m-d', strtotime('+1 year', strtotime($end_date)));
                }
            }*/
			
            $data['YearEndDate'] = $end_date;
            $data['Directors'] = $this->dividends->getDirectorsList();
            $data['task'] = 'meeting';

            $html1 = $this->load->view('client/dividend/pdf', $data, TRUE);

            $data['task'] = 'certificate';
            //$name = $data['item']['VoucherNumber'].'_certificate';
            $name = $data['item']['VoucherNumber'];
            $this->load->view('client/dividend/pdf', $data);
            $html2 = $this->load->view('client/dividend/pdf', $data, TRUE);

            $this->generatePDF($html1, $html2, $name, '', 'D');
        } else {
            $msg = '<div class="alert alert-danger"><i class=""></i>';
            $msg = $this->lang->line('DIVIDENT_UNEXPECTED_ERROR');
            $msg = '</div>';
            $this->session->set_flashdata('dividendMessage', $msg);
            setRedirect(site_url() . 'dividend');
        }
    }

    /**
     * 	This function generates the pdf for dividend
     */
    public function generatePDF($html1 = NULL, $html2 = NULL, $name = 'PDF', $path = null, $action = 'D') {
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

        // add a page
        $pdf->AddPage();

        $pdf->Line(5, 5, $pdf->getPageWidth() - 5, 5);
        $pdf->Line($pdf->getPageWidth() - 5, 5, $pdf->getPageWidth() - 5, $pdf->getPageHeight() - 5);
        $pdf->Line(5, $pdf->getPageHeight() - 5, $pdf->getPageWidth() - 5, $pdf->getPageHeight() - 5);
        $pdf->Line(5, 5, 5, $pdf->getPageHeight() - 5);

        // output the HTML content
        $pdf->writeHTML($html1, true, false, true, false, '');

        $pdf->AddPage();

        $pdf->Line(5, 5, $pdf->getPageWidth() - 5, 5);
        $pdf->Line($pdf->getPageWidth() - 5, 5, $pdf->getPageWidth() - 5, $pdf->getPageHeight() - 5);
        $pdf->Line(5, $pdf->getPageHeight() - 5, $pdf->getPageWidth() - 5, $pdf->getPageHeight() - 5);
        $pdf->Line(5, 5, 5, $pdf->getPageHeight() - 5);
        $pdf->writeHTML($html2, true, false, true, false, '');

        $pdf->Output($name . '.pdf', $action);
    }

    /* cron job update dividend address */

    public function dividendcorn() {
        $result = $this->dividends->getDividendAdd();
        foreach ($result as $key => $value) {
            if (empty($value['Address']) && $value['Address'] == '') {
                $result = $this->dividends->insertDividendAdd($value['DID'], $value['CompanyID']);
                echo $result;
            }
        }
    }

    /* cron job update dividend share holder address */

    public function updateshareholderAddress() {
        $result = $this->dividends->getDividendAdd();
        foreach ($result as $key => $value) {
            if (empty($value['shareholder_address']) && $value['shareholder_address'] == '') {
                $result = $this->dividends->insertDividendshareholderAddress($value['ShareholderID'], $value['DID']);
                echo $result;
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */