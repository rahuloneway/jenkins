<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//session_start();
class Client extends CI_Controller {

    public function Client() {
        parent::__construct();

        /**
         * 	First check if accountant is accessing the Clients account or not.
         * 	Preventing accountant from direct access to the client's dashboard.
         */
       
        $this->load->model('clients');
    }

    public function index() {     
	
		$user = $this->session->userdata('user');        
		
        $data['title'] = 'Cashman | Dashboard';
        $data['statistics'] = $this->clients->statistics();

        $this->load->model('clients/bank');
        $balance_record = $this->bank->get_current_balance();

        $data['balances'] = $this->clients->get_balances($data['statistics']['filed_years']);

        $data['current_balance_date'] = $balance_record['TransactionDate'];

        $data['important_dates'] = $this->clients->get_important_dates();
        $this->load->model('clients/dividends');
        $data['share_holders'] = $this->dividends->getShareHoldersList();
        unset($data['share_holders'][0]);

		//echo "<pre>"; print_r($data); echo "</pre>";
		
        $this->load->view('client/dashboard', $data);
    }

	/**
	*
	* 	This function will execute the Invoice tab functionality.
	*
	*/
    public function invoices() {		
		
        $user = $this->session->userdata('user');
        if ($user['UserType'] == 'TYPE_CLI' && empty($user['AccountantAccess'])) {
           //setRedirect(site_url());
        } else {
            if (isset($_GET['clientID'])) {
                checkUserAccess(array('TYPE_ACC', 'TYPE_CLI'));
            } else {
                checkUserAccess(array('TYPE_CLI'));
            }
        }
		
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $user = $this->session->userdata('user');	
		$VATYear = $this->session->userdata('VATYear');
		$quaters = vatQuaters($user['Params']['VATQuaters']);
				
        /* 	Get the customer list of the company. */
        $data['users'] = $this->clients->getUserList();

        $items = $this->clients->getInvoiceList(INVOICE_PAGINATION_LIMIT, $page);
        $data['Invoices'] = $items;
        //pr($data['Invoices']);
        $PaidVatQuarters = $this->clients->getPaidVatQuarters();
		$VATitems = $this->clients->getAllInvoices();
        $vat_listing = $this->clients->getVatType();
		
        $data['EXPitems'] = false;
        if($vat_listing->Type != 'flat') {
            $this->load->model('clients/expense');
            $EXPitems = $this->expense->getAllExpenses();
            $data['EXPitems'] = $EXPitems;
        }
        //echo '<pre>';print_r($PaidVatQuarters);echo '</pre>';die();
		//echo '<pre>';print_r($VATitems);echo '</pre>';//die();
        $data['VATitems'] = $VATitems;
        $data['PaidVatQuarters'] = $PaidVatQuarters;

        $total = $this->clients->totalInvoices();		
        $data['pagination'] = $this->getPagination('invoices', INVOICE_PAGINATION_LIMIT, $total);
        if ($data['users'] == FALSE) {
            $data['users'] = array('0' => 'No users');
        }

        $data['vat_listing'] = $vat_listing;
        $data['page'] = 'invoices';
        $data['title'] = 'Cashman | Invoices';
        $this->load->view('client/invoices/default', $data);
    }

	/**
	* 	This function save the invoice in the database table cashman_invoices
	*/
    public function saveInvoice() {		
		
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
            $this->load->model('clients');

            $_POST['task'] = safe($this->encrypt->decode($_POST['task']));
			//echo "<pre>";print_r($_POST);die('lol');
            if ($_POST['task'] != 'save') {
                $_POST['description'] = array_values(array_filter($_POST['description']));
                $_POST['quantity'] = array_values(array_filter($_POST['quantity']));
                $_POST['unitprice'] = array_values(array_filter($_POST['unitprice']));
                $_POST['vat'] = $_POST['vat'];
            }			
            $result = $this->clients->createInvoice($_POST);
			
            if ($result) {
                $this->load->model('clients/bank');
                $json['ajax_add'] = $_POST['ajax_add'];
                if ($json['ajax_add'] == 'ajax_add') {
                    $json['link'] = site_url() . 'bank_statements';
                    $file_id = $this->session->userdata('statement_file_id');
                    $response = $_SESSION['bank_statements'];
                    $response = json_decode($response);
                    $data['items'] = $response;
                    $data['page'] = "banks";
                    $data['title'] = 'Cashman | Bank Uploads';
                    $json['html'] = $this->load->view('client/banks/statement_listing', $data, true);
                } elseif ($json['ajax_add'] == 'bank_ajax_add') {
                    $this->load->model('bank');
                    $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
                    $json['link'] = site_url() . 'bank_statements';
                    $file_id = $this->session->userdata('statement_file_id');
                    //$response = $_SESSION['bank_statements'];
                    //$response = json_decode($response);
                    $data['items'] = $this->bank->getItems(BANK_PAGINATION_LIMIT, $page, '');
                    $data['page'] = "banks";
                    $data['title'] = 'Cashman | Bank Uploads';
                    $json['html'] = $this->load->view('client/banks/statement_listing', $data, true);
                }
                if ($_POST['task'] == 'save') {
                    $json['msg'] = $this->lang->line('CLIENT_INVOICE_SAVE_SUCCESS');
                } else {
                    $json['msg'] = sprintf($this->lang->line('CLIENT_INVOICE_CREATE_SUCCESS'), $result);
                }

                //return TRUE;
            } else {
                if (!empty($_POST['bank_statement_id'])) {
                    $json['ajax_add'] = 'invoice';
                    $json['msg'] = $this->lang->line('STATEMENT_INVOICE_EXISTS_ALREADY');
                } else {
                    $json['msg'] = $this->lang->line('UNEXPECTED_ERROR_OCCURED');
                }
            }
            //$this->session->set_userdata('statement_file_id','');
            echo json_encode($json);
            exit;
        } else {
            show_404();
        }
    }

    /*
     * 	This function will update the invoice
     */

    public function updateInvoice($task = NULL) {
      //echo '<pre>';  print_r($this->input->post()); die();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['delinvoiceId']) && $_POST['delinvoiceId'] != 0 && $_POST['delinvoiceId'] != NULL) {
                $response = $this->clients->delinvoiceItem($_POST['delinvoiceId']);
                if ($response) {
                    if ($this->input->post('status') == 3) {
                        $this->clients->deletetbDetails($_POST['delinvoiceId'], 'INVOICE');
                    }
                }
            }
            $this->load->model('clients');
            $_POST['task'] = safe($this->encrypt->decode($_POST['task']));
            $task = $_POST['task'];
            //echo '<pre>';print_r($_POST);echo '</pre>';die;
            if ($_POST['task'] == 'copy' || $_POST['task'] == 'CreateCopy') {
                $_POST['description'] = array_filter($_POST['editDescription']);
                $_POST['quantity'] = array_filter($_POST['editQuantity']);
                $_POST['unitprice'] = array_filter($_POST['editUnitprice']);
                $_POST['vat'] = array_filter($_POST['editVat']);
                $_POST['customerName'] = safe($_POST['editName']);
                $_POST['customerAddress'] = '';
                $_POST['invoiceDate'] = safe($_POST['editInvoiceDate']);
                $_POST['customer'] = $this->clients->getUserID($this->encrypt->decode($_POST['eInvoiceID']));
                $_POST['task'] = ($_POST['task'] == 'CreateCopy') ? 'create' : '';

                $result = $this->clients->createInvoice($_POST);
                if ($result) {
                    if ($_POST['task'] == 'copy') {
                        $json['msg'] = 'Successfully created the ' . $result . ' invoice';
                    } else {
                        $json['msg'] = 'Successfully created the invoice ' . $result;
                    }
                    exit;
                } else {
                    $json['msg'] = 'UNEXPECED ERROR OCCURED, PLEASE TRY AGAIN LATER';
                }
            } else {
                $result = $this->clients->updateInvoice($_POST);
                if ($result) {
                    if ($task == 'update') {
                        $json['msg'] = sprintf($this->lang->line('CLIENT_INVOICE_UPDATE_SUCCESS'), $result);
                    } else {
                        $json['msg'] = sprintf($this->lang->line('CLIENT_INVOICE_CREATE_SUCCESS'), $result);
                    }
                } else {
                    $json['msg'] = 'UNEXPECED ERROR OCCURED, PLEASE TRY AGAIN LATER';
                }
            }
            echo json_encode($json);
            die;
        } else {
            show_404();
        }
    }

    public function invoiceSearch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = array(
                'InvoiceNumber' => safe($_POST['sInvoiceNumber']),
                'CustomerName' => safe($_POST['sCustomerName']),
                'Status' => safe($_POST['sInvoiceStatus']),
                'sCreatedStart' => safe($_POST['sCreatedStart']),
                'sCreatedEnd' => safe($_POST['sCreatedEnd']),
                'sDueStart' => safe($_POST['sDueStart']),
                'sDueEnd' => safe($_POST['sDueEnd']),
                'invoice_financialyear' => safe($_POST['invoice_financialyear'])
            );
            $this->session->set_userdata('InvoiceSearch', $search);
            setRedirect('invoices');
        } else {
            show_404();
        }
    }
	
	public function customerInvoiceSearch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = array(
                /*'InvoiceNumber' => safe($_POST['sInvoiceNumber']),
                'CustomerName' => safe($_POST['sCustomerName']),
                'Status' => safe($_POST['sInvoiceStatus']),
                'sCreatedStart' => safe($_POST['sCreatedStart']),
                'sCreatedEnd' => safe($_POST['sCreatedEnd']),
                'sDueStart' => safe($_POST['sDueStart']),
                'sDueEnd' => safe($_POST['sDueEnd']),*/
				'id' => safe($_POST['cus-id']),
				'page' => safe($_POST['page-no']),
                'invoice_financialyear' => safe($_POST['invoice_financialyear'])
            );	
			//echo "<pre>"; print_r($search); die;
            $this->session->set_userdata('InvoiceSearch', $search);			
			$redirectLink = "customer_invoices/".$this->encrypt->encode($search['id'])."/".$this->encrypt->encode($search['page'])."/".$this->encrypt->encode($search['invoice_financialyear']);
			setRedirect($redirectLink);
        } else {
            show_404();
        }
    }

    public function editInvoice() {		

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item['bank_statement_id'] = '';
            $item['bank_paid_date'] = '';
            $item['ajax_add'] = '';
            $this->load->model('clients');
            $item['users'] = $this->clients->getcustomerList();
            if ($item['users'] == FALSE) {
                $item['users'] = array('0' => 'No users');
            }
            $data = $_POST;
            
            $data['task'] = safe($this->encrypt->decode($data['task']));
			
            if ($data['task'] == "addinvoice") {

                $vat_listing = $this->clients->getVatType();
                $item['vat_listing'] = $vat_listing;
                $item['task'] = $data['task'];
                $item['invoice_type'] = '';
				//echo "<pre>";print_r($item);die('lol');				
                $this->load->view('client/invoices/form', $item);
                return TRUE;
            }

            if ($data['task'] == "addCreditnote") {
                $vat_listing = $this->clients->getVatType();
                $item['vat_listing'] = $vat_listing;
                $item['task'] = $data['task'];
                $item['invoice_type'] = 'CRN';
                $this->load->view('client/invoices/form', $item);
                return TRUE;
            }

            $data['InvoiceID'] = safe($this->encrypt->decode($data['InvoiceID']));

            if (is_numeric($data['InvoiceID'])) {
                $result = $this->clients->getInvoiceItem($data);
                if ($result) {
                    $item['item'] = $result;
                    $vat_listing = $this->clients->getVatType();
                    $item['vat_listing'] = $vat_listing;
                    $item['task'] = $data['task'];
                    $item['invoice_type'] = ($item['item']['InvoiceTotal'] < 0) ? 'CRN' : '';
                    //print_r($item);die;
                    if ($item['task'] == 'changeInvoiceStatus' || $item['task'] == 'displayInvoice') { 
                        $this->load->view('client/invoices/invoice_status', $item);
                    } else { 
                        $this->load->view('client/invoices/form', $item);
                    }
                    return true;
                } else {
                    echo 'No result found for your query';
                    die;
                }
            }
            die('some error occured,please try again later');
        } else {
            show_404();
        }
        //
    }

    /**
     *
     * 	This function load different views in the modal at the dashboard.
     *
     */
    public function getModalView() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task = $_POST['task'];
            $view = $_POST['view'];
            $task = safe($_POST['task']);
            if ($task == 'invoice') {

                /* Get the active users */
                $this->load->model('clients');
                $data['users'] = $this->clients->getUserList();

                if ($view == 'invoice') {
                    $data['bank_statement_id'] = '';
                    $data['bank_paid_date'] = '';
                    $data['ajax_add'] = '';
                    $data['invoice_type'] = '';
                    $vat_listing = $this->clients->getVatType();
                    $data['vat_listing'] = $vat_listing;
                    $json['script'] = $this->load->view('client/invoices/invoice_js', '', true);
                    /*$json['script'] = trim(str_replace(array('<script>','</script>'), '', $json['script']));*/
                    $json['html'] = $this->load->view('client/invoices/form', $data, true);
                    $json['file'] = '';
                    die(json_encode($json));
                }
            } elseif ($task == 'expense') {
                $this->load->model('clients/expense');
                $data['users'] = $this->expense->getEmployeeList('include');
                $data['task'] = $task;
                $data['item'] = array();
                $data['form_id'] = 'expenseForm';
                $data['form_link'] = site_url() . 'clients/expenses/save';
                $data['vat_listing'] = $this->expense->getVatType();

                $json['script'] = $this->load->view('client/expenses/expenses_js', '', true);
                /*$json['script'] = str_replace(array('<script>','</script>'), '', $json['script']);*/
                $json['html'] = $this->load->view('client/expenses/form', $data, true);
                $json['file'] = '';
                die(json_encode($json));
            } elseif ($task == 'uploadexpense') {
                $data['form_id'] = 'uExpense';
                $data['form_link'] = site_url() . 'clients/expenses/uploadExpenses';
                $data['form_type'] = 'expense';
                $data['no_header_footer'] = 'no';
                $json['script'] = $this->load->view('client/expenses/expenses_js', '', true);
                /*$json['script'] = str_replace(array('<script>','</script>'), '', $json['script']);*/
                $json['file'] = '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                $json['html'] = $this->load->view('client/expenses/upload_expense', $data, true);
                die(json_encode($json));
            } elseif ($task == 'newDividend') {
                $this->load->model('clients/dividends');

                $data['share_holders'] = $this->dividends->getShareHoldersList();
                $data['shares'] = $this->dividends->getTotalShares();
                //$data['dividend_no'] = $this->dividends->getDividendNumber();
                $task = safe($this->encrypt->decode($_POST['task']));
                //$id 	= safe($this->encrypt->decode($_POST['ID']));
                $data['task'] = $task;
                $data['ajax_add'] = '';
                $data['bank_statement_id'] = '';
                $data['bank_paid_date'] = '';
                $data['Directors'] = $this->dividends->getDirectorsList();
                $json['script'] = $this->load->view('client/dividend/dividend_js', '', true);
                /*$json['script'] = trim(str_replace(array('<script>','</script>'), '', $json['script']));*/
                $json['file'] = '';
                $json['html'] = $this->load->view('client/dividend/form', $data, true);
                die(json_encode($json));
            } elseif ($task == 'banks') {
                $data['statements'] = array();
                $json['html'] = $this->load->view('client/banks/upload_form', $data, true);
                $json['script'] = $this->load->view('client/banks/banks_js', '', true);
                /*$json['script'] = str_replace(array('<script>','</script>'), '', $json['script']);*/
                $json['file'] = '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                die(json_encode($json));
            }
        } else {
            show_404();
        }
    }

    /**
     * 	This function will perform four different task.
     * 	Paid,Delete,generate pdf,copy
     */
    public function action($task = NULL) {
        $action = array(
            '0' => 'ACTION_PAID',
            '1' => 'ACTION_COPY',
            '2' => 'ACTION_DELETE',
            '3' => 'ACTION_PDF'
        );
        /* For Ajax call */

        $response = '';
        if (isset($_POST) && count($_POST) > 0) {
            $task = $_POST['task'];
            $response = $_POST['call'];
        } else {
            show_404();
        }
        if (isset($_POST['PaidDate'])) {
            $paidDate = safe($_POST['PaidDate']);
        } else {
            $paidDate = date('Y-m-d');
        }
        //echo '<pre>';print_r($_POST);echo '</pre>';die;
        $this->load->model('clients');
        if ($task != NULL) {
            $task = $this->encrypt->decode($task);
            $task = explode('/', $task);
            $task[3] = $response;

            //echo '<pre>';print_r($task);echo '</pre>';die;

            if (in_array($task[0], $action) && is_numeric($task[1])) {
                if ($task[0] == "ACTION_COPY") {
                    $_POST['task'] = 'copy';
                    //echo '<pre>';print_r($_POST);echo '</pre>';DIE;
                    $result = $this->clients->createInvoice($_POST);
                } else {
                    $result = $this->clients->performAction($task, $paidDate);
                }
                //pr($result);
                if ($result) {
                    if ($task[3] == 'ajaxcall' && $task[0] == 'ACTION_PAID') {
                        $json['msg'] = sprintf($this->lang->line('CLIENT_INVOICE_PAID_SUCCESS'), $result);
                        echo json_encode($json);
                        die;
                    } elseif ($task[3] == 'ajaxcall' && $task[0] == "ACTION_DELETE") {
                        $json['msg'] = sprintf($this->lang->line('CLIENT_INVOICE_DELETE_SUCCESS'), $result);
                        echo json_encode($json);
                        die;
                    } elseif ($task[3] == 'ajaxcall' && $task[0] == "ACTION_COPY") {
                        $json['msg'] = sprintf($this->lang->line('CLIENT_INVOICE_COPY_SUCCESS'), $result);
                        echo json_encode($json);
                        die;
                    }
                    if (empty($task[2])) {
                        $start = 0;
                    } else {
                        $start = $task[2];
                    }
                    //pr($start);
                    $result = $this->clients->getInvoiceList(INVOICE_PAGINATION_LIMIT, $start);
                    if (count($result) == 0) {
                        $result = array();
                    }
                    $data['Invoices'] = $result;

                    $data['vat_listing'] = $this->clients->getVatType();
                    $json = array(
                        'vat' => $this->load->view('client/invoices/vat_summary', $data, true),
                        'invoice' => $this->load->view('client/invoices/invoice_listing', $data, true)
                    );
                    echo json_encode($json);
                    die;
                } else {
                    echo 'ERROR';
                    DIE;
                }
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    public function getInvoiceUserDetail() { 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {			
            $data = $_POST;
            $this->load->model('clients');
            $result = $this->clients->getcustomerDetail($data['ID']);		
			echo "<pre>";print_r($result); //die;
			if($result[0]->paymentterms)
			{ 
				$result[0]->paymentterms = date('d-m-Y', strtotime("+".$result[0]->paymentterms." days")); 
			}else{
				$result[0]->paymentterms = date('d-m-Y');
			}
            if ($result) {
                $result = json_encode($result);
                echo $result;
                exit;
            } else {
                return FALSE;
            }
        } else {
            show_404();
        }
    }

    public function getPDF($id = null) {
        if ($id != null) {
            $this->load->model('clients');
            $user = $this->session->userdata('user');
            $id = $this->encrypt->decode($id);
            $id = array('InvoiceID' => $id);
            $this->load->model('clients');
            $vat_listing = $this->clients->getVatType();
            $data['vat_listing'] = $vat_listing;
            $data["CompanyEmail"] = $user["CompanyEmail"];
            $data['item'] = $this->clients->getInvoiceItem($id);
            //pr($user);die;
            $data['Country'] = countryName($this->clients->getCountry($user['UserID']));
            //echo '<pre>';print_r($data['item']);echo '</pre>';die;
            //$this->load->view('client/invoices/invoice_pdf',$data);
            $html = $this->load->view('client/invoices/invoice_pdf', $data, true);
            //pr($html);die;
            pdf($html, $data['item']['InvoiceNumber']);
        } else {
            show_404();
        }
    }

    private function getPagination($url = null, $perPage = INVOICE_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */
        $this->load->library('pagination');
        $config['base_url'] = site_url() . $url;
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

    public function invoiceSort() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->load->model('clients');
            $order = safe($this->encrypt->decode($_POST['order']));
            $dr = 0;
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $des_order_value = array(
                'SORT_BY_ID' => 'i.InvoiceNumber DESC',
                'SORT_BY_NAME' => 'CONCAT(u.FirstName," ",u.LastName)  DESC',
                'SORT_BY_AMOUNT' => 'i.InvoiceTotal DESC',
                'SORT_BY_CDATE' => 'i.AddedOn DESC',
                'SORT_BY_DDATE' => 'i.DueDate DESC'
            );
            $asc_order_value = array(
                'SORT_BY_ID' => 'i.InvoiceNumber ASC',
                'SORT_BY_NAME' => 'CONCAT(u.FirstName," ",u.LastName) ASC',
                'SORT_BY_AMOUNT' => 'i.InvoiceTotal ASC',
                'SORT_BY_CDATE' => 'i.AddedOn ASC',
                'SORT_BY_DDATE' => 'i.DueDate ASC'
            );
            $prev_order = $this->session->userdata('invoiceSortingOrder');
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
            $this->session->set_userdata('invoiceSortingOrder', $order_value);
            $data['vat_listing'] = $this->clients->getVatType();
            $data['Invoices'] = $this->clients->getInvoiceList(INVOICE_PAGINATION_LIMIT, $page);
            $d[0] = $this->load->view('client/invoices/invoice_listing', $data, true);
            $d[1] = $dr;
            echo json_encode($d);
            exit;
        } else {
            show_404();
        }
    }

    public function clean() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->session->set_userdata('InvoiceSearch', NULL);
            $this->session->set_userdata('InvoiceSearchRecords', NULL);
            $this->load->model('clients');
            $data['vat_listing'] = $this->clients->getVatType();
            $data['Invoices'] = $this->clients->getInvoiceList(INVOICE_PAGINATION_LIMIT, 0);
            $data['pagination'] = $this->getPagination('client/invoices', INVOICE_PAGINATION_LIMIT, count($data['Invoices']));
            $this->load->view('client/invoices/invoice_listing', $data);
        } else {
            show_404();
        }
    }

    public function accessAccountant() {
        $user = $this->session->userdata('user');

        $this->session->set_userdata('ledgerSource', '');
        $this->session->set_userdata('ledgerCategory', '');
        $this->session->set_userdata('TBYear', '');


        $accountant_id = $user['AccountantAccess'];
        $user_id = $user['UserID'];

        /* STEP - 1 Get Client login detail */
        $this->load->model('accountant/account');
        $client = $this->account->clientLoginDetail($accountant_id);
        $username = $client[0]->Email;
        $password = $client[0]->Password;

        /* STEP - 2 Get User record to store it in session */
        $this->load->model('login');
        $response = $this->login->isUserExists($username, $password);
        $accountant = set_accountant_session($response);

        $this->session->set_userdata('user', '');
        $this->session->set_userdata('user', $accountant);
        setRedirect(site_url() . 'client_listing');
    }

    public function logout() {
		update_logs('LOGIN/LOGOUT', 'USER_LOGOUT', 'LOGOUT', "", "");
        $this->session->sess_destroy();
        setRedirect(site_url());
    }

    public function markVATPaid() {

        $this->load->model('clients');
        $response = $this->clients->markVATPaid();
        if ($response["success"]) {
            $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
            $msg .= $this->lang->line('VAT_MARK_AS_PAID_SUCCESS_MSG');
            $msg .= '</div>';
            echo json_encode(array("success" => $msg));
        } else {
            if (count($response["error"]) > 0) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                foreach ($response["error"] as $err) {
                    $msg .= $err . "<br/>";
                }
                $msg .= '</div>';
                $this->session->set_flashdata("actionMessage", $msg);
            }
            echo json_encode(array("error" => $msg));
        }
        exit();
    }
	
	//02-11-2015 Vat Summary Tab feature on VatSummary inner tab
	public function VatSum_markVATPaid() {
        $this->load->model('clients');
        $response = $this->clients->VatSummary_markVATPaid();

        if ($response["success"]) {
            $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
            $msg .= $this->lang->line('VAT_MARK_AS_PAID_SUCCESS_MSG');
            $msg .= '</div>';
            echo json_encode(array("success" => $msg));

        } else {
            if (count($response["error"]) > 0) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                foreach ($response["error"] as $err) {
                    $msg .= $err . "<br/>";
                }
                $msg .= '</div>';
                $this->session->set_flashdata("actionMessage", $msg);
            }
            echo json_encode(array("error" => $msg));
        }
        exit();
    }
	#########################################
	# Author : Gurdeep Singh				#
	# Create date : 02 Aug 2016 			#
	# params : vat form inputs 				#
	#########################################
	public function onlineVatSubmission() 
	{
        $this->load->library('GovTalk');
		$this->load->library('Hmrc');
		$this->load->library('HmrcVat');
			
		if($this->input->is_ajax_request())
		{ 		
			$hmrcUserId   = $this->input->post('Vat_userid');
			$hmrcPassword = $this->input->post('Vat_pass');
			$capacity	  = $this->input->post('Vat_capacity');
			$hmrcMode 	  = $this->config->item('hmrcMode');
			$hmrcVat 	  = new HmrcVat($hmrcUserId, $hmrcPassword, $hmrcMode);
			$vatnumber    = $this->input->post('vat_number');
			
			$box1 = $this->input->post('box1');
			$box2 = $this->input->post('box2');
			$box4 = $this->input->post('box4');
			$box6 = $this->input->post('box6');
			$box7 = $this->input->post('box7');
			$box8 = $this->input->post('box8');
			$box9 = $this->input->post('box9');
			
			$quarterEndDate = $this->input->post('qEndDate');
			if( $quarterEndDate == '')
			{
				$errors['message']   = 'Quarter month is missing. Please try again.';
				$errors['label']     = '';
				$output['success']   = false; 
				$output['error_msg'] = $errors; 
				echo json_encode($output);die;
			} 
			$quarterEndDate = explode('-',$quarterEndDate);
			if( $quarterEndDate[1] == '')
			{
				$errors['message']   = 'Quarter month is missing. Please try again.';
				$errors['label']     = '';
				$output['success']   = false; 
				$output['error_msg'] = $errors; 
				echo json_encode($output);die;
			}
			$VATYear = $this->session->userdata('VATYear');
			if (!empty($VATYear) && $VATYear != '') {
				$periodid = $VATYear;
				$periodid = $periodid.'-'.$quarterEndDate[1];
			} else {				
				$periodid = $periodid.'-'.$quarterEndDate[1];
			}
			
			$pollResponseData = array();
	
			//echo $vatnumber.$periodid; die;
			
			if ($pollRequest = $hmrcVat->declarationRequest($vatnumber, $periodid, $capacity, $box1, $box2, $box4, $box6, $box7, $box8, $box9)) 
			//if ($pollRequest = $hmrcVat->declarationRequest('999900001', '2016-01', $capacity, $box1, $box2, $box4, $box6, $box7, $box8, $box9)) 
			{
				
				$endpoint 	   = $pollRequest['endpoint'];
				$interval 	   = $pollRequest['interval'];
				$correlationid = $pollRequest['correlationid'];
				sleep($interval + 20);
				
				if (isset($endpoint) && isset($correlationid)) 
				{
					$hmrcVat = new HmrcVat($hmrcUserId, $hmrcPassword);
					if ($pollResponse = $hmrcVat->declarationResponsePoll($correlationid, $endpoint)) 
					{				
						
						if (isset($pollResponse['endpoint'])) 
						{
							$errors['label'] 		   = 'Response pending.  Please wait '.$pollResponse['interval'].' seconds and then try again.';
							$errors['message'] = '';
							$failure           = true;
							/*$vatData['hmrcUserId']   = $hmrcUserId;
							$vatData['hmrcPassword'] = $hmrcPassword;
							$vatData['capacity'] = $capacity;
							$vatData['hmrcMode'] = $hmrcMode;
							$vatData['vatnumber']= $vatnumber;
							$vatData['box1']	 = $box1;
							$vatData['box2'] 	 = $box2;
							$vatData['box4'] 	 = $box4;
							$vatData['box6'] 	 = $box6;
							$vatData['box7'] 	 = $box7;
							$vatData['box8'] 	 = $box8;
							$vatData['box9'] 	 = $box9;
							$vatData['VATYear']  = $VATYear;
							$vatData['periodid'] = $periodid;
							$this->submitVatAgain($vatData);*/							
						} 
						else 
						{
							$success['label']   = 'Response received, delete command sent.  See below:';
							$failure            = false;
							$pollResponseData   = serialize($pollResponse);							
							if ($hmrcVat->sendDeleteRequest()) {																
								$success['message'] = 'Delete request successful. Resource no longer exists on Gateway.';
							} else {
								$success['message'] = 'Delete request failed. Resource may still exist on Gateway.';
							}
						}					
					} 
					else 
					{			
						$errorssss 		   = $hmrcVat->getResponseErrors();
						$errors['message'] = '';
						if(!empty($errorssss))
						{
							$msg= 'You can submit VAT Online only for '.date('Y').'.';
							$errors['message'] = $msg;
						}
						$errors['label']   = 'Government Gateway returned errors in response to poll request.';
						$failure           = true;						
					}
				} 
				else 
				{
					$errors['label']   = 'Unable to poll Government Gateway: missing arguments.';
					$errors['message'] = 'Endpoint or Correlation id is missing.';	
					$failure           = true;
				}				
			} 
			else 
			{
				$errors['label']   = 'Return was rejected by the Government Gateway. May be VAT number or period is wrong.';
				$errors['message'] = '';	
				$failure           = true;  
			}
			if($failure)
			{
				$output['success']   = false; 
				$output['error_msg'] = $errors; 				
			}
			else
			{
				$output['success'] 	        = true; 
				$output['success_msg']      = $success; 
				$output['pollResponseData'] = $pollResponseData; 
			}
			echo json_encode($output);exit;
		}		
        exit();
    }
	public function submitVatAgain($vatData) 
	{
		$this->load->library('GovTalk');
		$this->load->library('Hmrc');
		$this->load->library('HmrcVat');
		
		$hmrcUserId   = $vatData['hmrcUserId'];
		$hmrcPassword = $vatData['hmrcPassword'];
		$capacity	  = $vatData['capacity'];
		$hmrcMode 	  = $vatData['hmrcMode'];
		$hmrcVat 	  = new HmrcVat($hmrcUserId, $hmrcPassword, $hmrcMode);
		$vatnumber    = $vatData['vatnumber'];
				
		$box1 = $vatData['box1'];
		$box2 = $vatData['box2'];
		$box4 = $vatData['box4'];
		$box6 = $vatData['box6'];
		$box7 = $vatData['box7'];
		$box8 = $vatData['box8'];
		$box9 = $vatData['box9'];		
		$VATYear  = $vatData['VATYear'];
		$periodid = $vatData['periodid'];
		
		$pollResponseData = array();
		if ($pollRequest = $hmrcVat->declarationRequest($vatnumber, $periodid, $capacity, $box1, $box2, $box4, $box6, $box7, $box8, $box9)) 
		{
			$endpoint 	   = $pollRequest['endpoint'];
			$interval 	   = $pollRequest['interval'];
			$correlationid = $pollRequest['correlationid'];
			sleep($interval + 5);
			if (isset($endpoint) && isset($correlationid)) 
			{
				$hmrcVat = new HmrcVat($hmrcUserId, $hmrcPassword);
				if ($pollResponse = $hmrcVat->declarationResponsePoll($correlationid, $endpoint)) 
				{				
					if (isset($pollResponse['endpoint'])) 
					{ 
						$errors['label'] 		   = 'Response pending.  Please wait '.$pollResponse['interval'].' seconds and then try again.';
						$errors['message'] = '';
						$failure           = true;	
					} 
					else 
					{
						$success['label']   = 'Response received, delete command sent.  See below:';
						$failure            = false;
						$pollResponseData   = serialize($pollResponse);							
						if ($hmrcVat->sendDeleteRequest()) {
							$success['message'] = 'Delete request successful. Resource no longer exists on Gateway.';
						} else {
							$success['message'] = 'Delete request failed. Resource may still exist on Gateway.';
						}
					}					
				} 
				else 
				{			
					$errorssss 		   = $hmrcVat->getResponseErrors();
					$errors['message'] = '';
					if(!empty($errorssss))
					{
						$msg= 'You can submit VAT Online only for '.date('Y').'.';
						$errors['message'] = $msg;
					}
					$errors['label']   = 'Government Gateway returned errors in response to poll request.';
					$failure           = true;						
				}
			} 
			else 
			{
				$errors['label']   = 'Unable to poll Government Gateway: missing arguments.';
				$errors['message'] = 'Endpoint or Correlation id is missing.';	
				$failure           = true;
			}				
		} 
		else 
		{
			$errors['label']   = 'Return was rejected by the Government Gateway. May be VAT number or period is wrong.';
			$errors['message'] = '';	
			$failure           = true;  
		}
		if($failure)
		{
			$output['success']   = false; 
			$output['error_msg'] = $errors; 				
		}
		else
		{
			$output['success'] 	        = true; 
			$output['success_msg']      = $success; 
			$output['pollResponseData'] = $pollResponseData; 
		}
		echo json_encode($output);exit;
		
	}
    public function loadVatDetails() {
        $user = $this->session->userdata('user');
        $vatYear = $this->input->post("vatYear");
        $this->session->set_userdata("VATYear", $vatYear);

        /* Get the active users */
        $this->load->model('clients');

        $PaidVatQuarters = $this->clients->getPaidVatQuarters();
        $VATitems = $this->clients->getAllInvoices();
        $vat_listing = $this->clients->getVatType();

        $data['EXPitems'] = false;
        if ($vat_listing->Type != 'flat') {
            $this->load->model('clients/expense');
            $EXPitems = $this->expense->getAllExpenses();
            $data['EXPitems'] = $EXPitems;
        }
        // echo '<pre>asd';print_r($VATitems);echo '</pre>';die();

        $data['VATitems'] = $VATitems;
        $data['PaidVatQuarters'] = $PaidVatQuarters;
        $data['vat_listing'] = $vat_listing;
        $jsonHTML["HTML"] = $this->load->view('client/invoices/vat_summary', $data, true);
        die(json_encode($jsonHTML));
    }

    public function loadQuarterDetails() {
        /* Get the active users */
        $this->load->model('clients');

        $q = $this->input->post("quarter");
        $q = $this->encrypt->decode($q);
        $VATYear = $this->input->post("VATYear");
        $quarterDetails = $this->clients->getQuarterDetails($q, $VATYear);
		
        $vat_listing = $this->clients->getVatType();
        $data['EXPitems'] = false;
        if ($vat_listing->Type != 'flat') {
            $this->load->model('clients/expense');
            $EXPitems = $this->expense->getQuarterExpDetails($q, $VATYear);
            $data['EXPitems'] = $EXPitems;
        }

        $data['vat_listing'] = $vat_listing;
        $data['quarterDetails'] = $quarterDetails;
        $jsonHTML = $this->load->view('client/invoices/quarter_details', $data, true);
        die($jsonHTML);
    }

    public function getQuarterPDF($q = null) {
        if ($q != null) {

            $this->load->model('clients');
            $user = $this->session->userdata('user');
            $q = $this->encrypt->decode($q);

            $this->load->model('clients');
            $VATYear = $this->session->userdata('VATYear');

            $quarterDetails = $this->clients->getQuarterDetails($q, $VATYear);
			
			//echo $VATYear."<pre>";print_r( $quarterDetails); die('client controller 928');
			
			
            if (!isset($VATYear)) {
                $VATYear = date("Y");
            }
            $vatQuarters = getVatQuarters($VATYear);
            $pdfTitle = $this->lang->line("VAT_QUARTER_DETAILS_POPUP_TITLE") . "$q(" . cDate($vatQuarters[$q]['FIRST']) . " : " . cDate($vatQuarters[$q]['SECOND']) . ")";

            $data['pdfTitle'] = $pdfTitle;

            $vat_listing = $this->clients->getVatType();
            $data['EXPitems'] = false;
            if ($vat_listing->Type != 'flat') {
                $this->load->model('clients/expense');
                $EXPitems = $this->expense->getQuarterExpDetails($q, $VATYear);
                $data['EXPitems'] = $EXPitems;
            }
			
            $data['vat_listing'] = $vat_listing;
            $data['quarterDetails'] = $quarterDetails;
            $html = $this->load->view('client/invoices/invoice_vat_quarter', $data, true);
            pdf($html, $pdfTitle, NULL, 'D');
        } else {
            show_404();
        }
    }

	public function getQuarterPDFMail($url = null) {
				
		$url = explode("/",$this->encrypt->decode($url));
		
		$q = $url[0];
		$clientId = $url[1];
		$first = $url[2];
		$second = $url[3];
		$companyName = $url[4];
		$VATRegistrationNo = $url[5];
		
        if ($q != null) {

            $this->load->model('clients');
			$data['companyName'] = $companyName;
			$data['VATRegistrationNo'] = $VATRegistrationNo;
            
            //$VATYear = $this->session->userdata('VATYear'); 
			if (!isset($VATYear)) {
                $VATYear = date("Y");
            }
			$this->load->model('clients');
            $quarterDetails = $this->clients->getQuarterDetails($q, $VATYear, $clientId);
			
            $vatQuarters = getVatQuarters($VATYear,$clientId);
            $pdfTitle = $this->lang->line("VAT_QUARTER_DETAILS_POPUP_TITLE") . "$q( " . $first . " : " . $second . ")";

            $data['pdfTitle'] = $pdfTitle;

            $vat_listing = $this->clients->getVatType();
            $data['EXPitems'] = false;
            if ($vat_listing->Type != 'flat') {
                $this->load->model('clients/expense');
                $EXPitems = $this->expense->getQuarterExpDetails($q, $VATYear);
                $data['EXPitems'] = $EXPitems;
            }

            $data['vat_listing'] = $vat_listing;
            $data['quarterDetails'] = $quarterDetails;
				
            $html = $this->load->view('client/invoices/invoice_vat_quarter_mail', $data, true); 	
			
            pdf($html, $pdfTitle, NULL, 'D');
        } else {
            show_404();
        }
    }
	
    public function get_shareholder_detail() {
        if ($this->input->is_ajax_request()) {
            $id = (int) $_POST['id'];
            $year = $_POST['year'];
            $response = $this->clients->get_shareholder_detail($id, $year);
            $data['statistics'] = $response;
            $data['financial_year'] = $year;
            $json['html'] = $this->load->view('client/dashboard/shareholder_detail', $data, true);
            $json['error'] = '';
            echo json_encode($json);
            die;
        } else {
            /*
              $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
              $msg .= $this->lang->line('UNEXPECTED_ERROR');
              $msg .= '</div>';
              $this->session->set_flashdata('dasboardError',$msg);
              setRedirect(site_url());
             */
            show_404();
        }
    }

    public function get_tax_implications() {
        if ($this->input->is_ajax_request()) {
            $config = settings();
            $fy_date = date('Y') . '-04-30';
            $c_date = date('Y-m-d');
            if ($c_date > $fy_date) {
                $current_year = date('Y') . ' / ' . (date('Y') + 1);
            } else {
                $current_year = (date('Y') - 1) . ' / ' . date('Y');
            }
            $div_avail = $_POST['div_avail'];

            $config['Financial_year'] = explode(',', $config['Financial_year']);
            $year = $_POST['year'];
            //$year = $config['Financial_year'][$_POST['year']];
            $config['Tax_able_income'] = explode(',', $config['Tax_able_income']);
            $annual_tax = $config['Tax_able_income'][array_search($year, $config['Financial_year'])];
            $id = (int) $_POST['id'];
            $amount_needed = (int) $_POST['amount'];

            $response = $this->clients->get_shareholder_detail($id, $year);
            $json['error'] = '';

            $amount_left = (($annual_tax - ($response['gross_salary'] + $response['gross_dividend'])) * 0.9);
            //echo '<br/>Gross Salary : '.$response['gross_salary'];
            //echo '<br/>Gross Dividend: '.$response['gross_dividend'];
            //pr($response);
            //print('<br/>'.$annual_tax);die;

            $temp_amount_needed = $amount_needed;
            if ($amount_needed > $div_avail) {
                $json['implication'] = 'G';
                $json['amount'] = '&pound; 0';
            } else {
                if ($amount_left < 0) {
                    $temp_amount_needed = (($temp_amount_needed * 25) / 100);
                    $json['amount'] = '&pound ' . number_format($temp_amount_needed, 2, '.', ',');
                    $json['color'] = 'red';
                } else {
                    if ($amount_needed > $amount_left) {
                        $temp_amount_needed = $amount_needed - $amount_left;
                        $temp_amount_needed = (($temp_amount_needed * 25) / 100);
                        $json['amount'] = '&pound ' . number_format($temp_amount_needed, 2, '.', ',');
                    } else {
                        $json['amount'] = '&pound; 0';
                    }
                    $json['color'] = 'green';
                }

                $json['implication'] = 'L';
            }
            //echo negativeNumber($amount_left);


            echo json_encode($json);
            die;
        } else {
            /*
              $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
              $msg .= $this->lang->line('UNEXPECTED_FILE_UPLOAD_ERROR');
              $msg .= '</div>';
              $this->session->set_flashdata('dasboardError',$msg);
              setRedirect(site_url());
             */
            show_404();
        }
    }

	public function get_tax_implications_new(){
        if ($this->input->is_ajax_request()) {
			$totalamttax1=$_POST['gross_dividend']+$_POST['extra_dividend'];
			if($_POST['gross_salary']<11000){
				$rebatamt1=11000 - $_POST['gross_salary'];
			}else{
				$rebatamt1= 0;
			}
			$prebastax1= $totalamttax1-$rebatamt1-5000;
			$totalincome1=$_POST['gross_income'] + $_POST['extra_dividend'];
			$non_taxable_dueto32tax1 =  $totalincome1 - $_POST['annual_tax'];
			if($totalincome1>$_POST['annual_tax']){
				$totalbastax1=$prebastax1-$non_taxable_dueto32tax1;
				$hrbtax1= $non_taxable_dueto32tax1 * HRB_PERCENTAGE ;
			}else{
				$totalbastax1=$prebastax1;
				$hrbtax1=0;
			}
			$btax1=$totalbastax1 * BRB_PERCENTAGE ;
			$taximplicationpre1= $btax1 + $hrbtax1;
			$taximplication1=$taximplicationpre1 - $_POST['totaltax'];
			echo numberFormat($taximplication1);
            die;
        } else {
            show_404();
        }
    }

    public function get_accounting_year_data() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ac_year = $_POST['AccountingYear'];

            $this->session->set_userdata('search_accounting_year', $ac_year);
            setRedirect(site_url());
        } else {
            show_404();
        }
    }

    /* get latest terms and conditions */

    public function termCondition() {
        $user = $this->session->userdata('user');
        $term_version = $this->input->post('term_version');
        $this->load->model('accountant/Term');
        $userId = $user['UserID'];
        $this->load->model('login');
        $response = $this->Term->activateTermAndCondtion($userId, $term_version);
        if ($response == 1) {
            $user['T_AND_C_Version'] = $term_version;
            $this->session->set_userdata('user', $user);
            return TRUE;
        }
    }

    /* view latest terms and conditions */

    public function viewTermandconditons() {
        $this->output->set_content_type('application/json');
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->load->model('accountant/Term');
            $response = $this->Term->viewTermAndConditions($id);
            if (!empty($response))
                $fileurl = base_url() . "assets/uploads/terms/" . $response;
            echo '<iframe width="100%" scrolling="no" height="400" frameborder="0" style="width:100%; height:400px;" src="' . $fileurl . '" /></iframe>';
        }
    }

    /* get flat rate and net sales from invoice */

    public function getflatInvoice() {
        $result = $this->clients->flatInvoice();

        foreach ($result as $key => $value) {
            $clientId = $this->clients->getClientId($value->CustomerCompanyID);
            $result[$key]->ClientId = $clientId;
        }

        foreach ($result as $key => $value) {
            $getTaxdetails = $this->clients->getVatfaltType($value->ClientId);
            $PercentRateAfterEndDate = $getTaxdetails->PercentRateAfterEndDate;
            $StartDate = $getTaxdetails->StartDate;
            $EndDate = $getTaxdetails->EndDate;
            $PercentRate = $getTaxdetails->PercentRate;

            $result[$key]->Type = $Type;
            $result[$key]->PercentRateAfterEndDate = $PercentRateAfterEndDate;
            $result[$key]->StartDate = $StartDate;
            $result[$key]->EndDate = $EndDate;
            $result[$key]->PercentRate = $PercentRate;
        }
        foreach ($result as $key => $vat_listing) {
            $FlatRate = '';
            $NetSales = '';
            if (empty($vat_listing->Type) && empty($vat_listing->PercentRateAfterEndDate) && empty($vat_listing->PercentRate)) {
                $var = array('FlatRate' => 0, 'NetSales' => $vat_listing->InvoiceTotal);
            } else {
                if ($vat_listing->InvoiceTotal != 0 && $vat_listing->PaidOn != '') {
                    if (strtotime($vat_listing->PaidOn) <= strtotime($vat_listing->EndDate)) {
                        $flateRate = ($vat_listing->InvoiceTotal * $vat_listing->PercentRateAfterEndDate) / 100;
                    } else {
                        $flateRate = ($vat_listing->InvoiceTotal * $vat_listing->PercentRate) / 100;
                    }
                    $FlatRate = $flateRate;
                } else {
                    $FlatRate = '0.00';
                }
                if ($vat_listing->InvoiceTotal != 0) {
                    $NetSales = (($vat_listing->InvoiceTotal - $FlatRate));
                } else {
                    $NetSales = '0.00';
                }
                $var = array('FlatRate' => trim($FlatRate), 'NetSales' => trim($NetSales));
            }
            $response = $this->clients->updateFlatandnetsales($vat_listing->InvoiceID, $var);
            echo $response . "<br/>";
        }
    }

    /*
     * Invoice address update
     */

    public function invoiceAddress() {
        $result = $this->clients->flatInvoice();
        foreach ($result as $key => $value) {
            $getAdress = $this->clients->companyAddress($value->CustomerCompanyID);
            $data = array('Params' => $getAdress[0]->Params);
            $response = $this->clients->invoiceAdressupdate($value->InvoiceID, $data);
            echo $response . "<br/>";
        }
    }
	
	/***  Function to Get Child Categories by parent category id *****/
	public function getParentCategoryChild() {
		$childList = '<select id="Category[]" class="form-control category sm-width-box tdtab" name="Category[]">';
		$childList .= '<option value="0" > Select Sub Category</option>'; 
		if(isset($_POST['parentid'])){ 
			$childCat = getCategoryParentChild($_POST['parentid']); 
			$i=0;
			if(isset($childCat) && $childCat != ''){
				foreach($childCat as $val){
					$childList .= '<option value="'.$val->id.'" > '.$val->title.' </option>';
					$i += 1;
				}
			}
			if($_POST['parentid'] == 235){
				$childList .= '<option value="addnew" > Add New Customer </option>';
			}
			$childList .= '</select>'; 		
		}else{
			$childList .= '</select>';	
		}
		//$childList .= '<input class="linkRefrence" type="hidden" value="" name="linkRefrence[]">';
		//$childList .= '<div class="input-group drop-downs srch-div">';
		//$childList .= '<input type="textbox" placeholder="Chris" name="linkbankstatment" required="required" class="form-control linkbankstatment">';
		//$childList .= '<span class="glyphicon glyphicon-search ico" aria-hidden="true"></span>';
		//$childList .= '<div class="div-position" id="clientList" style="display:none">';
		//$childList .= '<ul id="clientUl">';
		//$childList .= '</ul>';
		//$childList .= '</div>';
		//$childList .= '</div>';
		echo $childList;
	}
	/**  Function to Get All Parent Categories **/
	public function getParentCategory() {
		$postID = $_POST['id'];
		$parentList = '<select id="ExpenseCategory[]" class="form-control xxx ExpenseCategory exParentCat" name="ExpenseParentCategory[]">';
		$parentList .= '<option value="-1" > Select Sub Category</option>';   
		$parentCat = getCategoryParent($_POST['CatType']);    
		if( !empty( $parentCat ))
		{
			foreach($parentCat as $val){
				if( getCategoryParentId($postID) == $val->id)
					$parentList .= '<option value="'.$val->id.'" selected="selected"> '.$val->title.' </option>';
				else	
					$parentList .= '<option value="'.$val->id.'" > '.$val->title.' </option>';
			}
		}
		
		$parentList .= '</select>'; 
		echo $parentList;  
	}
	
	/***  Function to Get Vat Credential.****/
	public function getVatCredential() {
		$user = $this->session->userdata('user');
		if(isset($user['Params']['VATRegistrationNo']))
			$vat_number = $user['Params']['VATRegistrationNo'];
		else
			$vat_number = '';
		$this->load->model('accountant/settings');
		$items = $this->settings->getItems();
		$data['Vat_userid']     = $items['Vat_userid'];
		$data['Vat_pass'] 		= $items['Vat_pass'];
		$data['Vat_capacity']   = $items['Vat_capacity'];
		$data['vat_number']     = $vat_number;
		echo json_encode($data);
		die;  
	}
	#####################################################
	# Author : Gurdeep Singh 							#	
	# Date   : 17 Aug 2016								#
	# Params : 											#
	# Description : get client access and update access #
	#####################################################
	public function editClientAccess() {
		if($this->input->is_ajax_request())
		{ 	
			$task 	  = $this->input->post('task');
			$clientId = $this->input->post('clientId');
			$clientId = $this->encrypt->decode($clientId);
			if( $task == 'view' && $clientId != '')
			{
				$data['privilgedMenusIds'] = getClientAccssibleMenus($clientId);
				$data['allMenus'] 		   = getAllMenus();
				$data['clientId']		   = $clientId;
				$this->load->view('client/edit_access', $data);
			}
			else if( $task == 'update' && $clientId != '')
			{
				$checkAll = ($this->input->post('checkAll')) ? 'all' : '';
				if( $checkAll == 'all')
				{
					$params['menus'] = 'all';
				}
				else
				{
					$menus = ($this->input->post('menus')) ? $this->input->post('menus') : array();
					if( empty($menus))
						$params['menus'] = '';
					else
						$params['menus']    = implode(',',$menus);
				}				
				$params['clientId'] = $clientId;
				$response = $this->clients->updateClientAccessibleMenus($params);
				if($response)
				{
					$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
					$msg .= $this->lang->line('CLIENT_PRIVILGE_SUCCESS');
					$msg .= '</div>';
					$output['success']     = true;
					$output['success_msg'] = $msg;
					echo json_encode($output);exit;	
				}
				else
				{
					$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
					$msg .= $this->lang->line('HAPPENS_WRONG');
					$msg .= '</div>';
					
					$output['success']   = false;
					$output['error_msg'] = $msg;
					echo json_encode($output);exit;
				}				
			}
			else
			{
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('HAPPENS_WRONG');
                $msg .= '</div>';
				
				$output['success']   = false;
				$output['error_msg'] = $msg;
				echo json_encode($output);exit;
			}
		}               
    }
	
	// client status enable or disable 
	public function changeclientstatus() {
		$this->clients->changeclientstatus();
	} 
	

}

/* End of file client.php */
/* Location: ./application/controllers/client.php */