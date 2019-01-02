<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//session_start();
class Customers extends CI_Controller {

    public function Customers() { 
        parent::__construct();
		$user = $this->session->userdata('user');
        if ($user['UserType'] == 'TYPE_CLI' && empty($user['AccountantAccess'])) {
           setRedirect(site_url());
        } else {
            if (isset($_GET['clientID'])) {
                checkUserAccess(array('TYPE_ACC', 'TYPE_CLI'));
            } else {
                checkUserAccess(array('TYPE_CLI'));
            }
        }
        $this->load->model('clients/customer');
    }

    public function index() { 		
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data['title'] = 'Cashman | Customers';
        $data['page'] = "customers";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = $this->input->post('customer');
            if (!empty($customerId)) {
                $data['customerId'] = $customerId;
            }
            $companyname = $this->input->post('companyname');
            if (!empty($companyname)) {
                $data['companyname'] = $companyname;
            }
            $email = $this->input->post('email');
            if (!empty($email)) {
                $data['email'] = $email;
            }
            $mobile = $this->input->post('mobile');
            if (!empty($mobile)) {
                $data['mobile'] = $mobile;
            }
        }
 
        $data['customers'] = $this->customer->getCustomer(ACTION_LOG_PAGINATION_LIMIT, $page, '', $customerId, $companyname, $eamil, $mobile) ;
        $data['customerlist'] = $this->customer->getCustomerlist();
        $total = $this->customer->totalcustomers($customerId);
        $data['pagination'] = $this->getPagination(ACTION_LOG_PAGINATION_LIMIT, $total);		
        $this->load->view('client/customers/default', $data);
    }

	
	public function invoices($id, $page, $year) { 
		$page = $this->encrypt->decode($page);
		$id = $this->encrypt->decode($id);
		$year = $this->encrypt->decode($year); 
		$this->load->model('clients');
		if($year == 0){
			//$this->session->set_userdata('InvoiceSearch', '');
		}
		$items = $this->clients->getCustomerInvoiceList($id,INVOICE_PAGINATION_LIMIT, $page);
		$data['Invoices'] = $items;		
		$InvoiceTotal = 0;
		$PaidInvoiceTotal = 0;
		foreach($data['Invoices'] as $invoice){			
			$InvoiceTotal += $invoice->InvoiceTotal;
			if($invoice->Status == 3){
				$PaidInvoiceTotal += $invoice->InvoiceTotal;
			}
		}
		$data['InvoiceTotal'] = $InvoiceTotal;
		$data['PaidInvoiceTotal'] = $PaidInvoiceTotal;
		$data['id'] = $id;
		$data['page'] = $page;		
		$this->load->view('client/customers/invoices', $data);
	}
	
	public function customerInvoiceSearch() { 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
						
			$id   = $this->encrypt->encode($_POST['id']);
			$page = $this->encrypt->encode($_POST['page']);
			$year = $this->encrypt->encode($_POST['year']);			
			
			$setRedirectUrl = "customer_invoices/".$id."/".$page."/".$year;
			
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
            setRedirect($setRedirectUrl);
			
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
	
    private function getPagination($perPage = CUSTOMERS_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */
        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'customers';
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
        $config['cur_tag_open'] = '<li><a ><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    public function customerForm() {		
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('id');
            $id = $this->encrypt->decode($id);
			$page = $this->input->post('page');
			if($page == 'bankstatment'){
				$data['page'] = $page;
			}			
            if (!empty($id)) {
                $editCustomer = $this->customer->editCustomer($id);
                $data['editcustomer'] = $editCustomer;
            }
						
        } else {
            $data = array();
        }		
        $viewHTML = $this->load->view("client/customers/customers_form", $data, true);
        echo $viewHTML;
    }
	
	 public function customerFormBank() {		
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('id');
            $id = $this->encrypt->decode($id);
			$page = $this->input->post('page');
			if($page == 'bankstatment'){
				$data['page'] = $page;
			}			
            if (!empty($id)) {
                $editCustomer = $this->customer->editCustomer($id);
                $data['editcustomer'] = $editCustomer;
            }
						
        } else {
            $data = array();
        }		
        $viewHTML = $this->load->view("client/customers/customers_form_bank", $data, true);
        echo $viewHTML;
    }

    public function save() { 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {			
			if (!$this->input->is_ajax_request()) {
                redirect(site_url('customers'), 'refresh');
            } else {								
                $cid = $this->input->post('cid');
                $user = $this->session->userdata('user');				
				$page = $this->input->post('page');
                $data = array(
                    'companyname' => $this->input->post('companyname'),
                    'status' => ($this->input->post('status') != '' ? $this->input->post('status'):1),
                    'first_name' => ($this->input->post('firstname') != '' ? $this->input->post('firstname') : $this->input->post('companyname') ),
                    'last_name' => $this->input->post('lastname'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'mobile' => $this->input->post('mobile'),
                    'address1' => $this->input->post('address1'),
                    'address2' => $this->input->post('address2'),
                    'address3' => $this->input->post('address3'),
                    'create_date' => date('Y-m-d'),
                    'AccountantAccess' => $user['AccountantAccess'],
                    'clientId' => $user['UserID'],
					'clientCID' => $user['CompanyID'],
                    'city' => $this->input->post('city'),
                    'state' => $this->input->post('state'),
                    'postcode' => $this->input->post('postcode'),
					'paymentterms' => $this->input->post('paymentterms'),
                    'country' => $this->input->post('country'),
                    'VAT_Registration_no' => $this->input->post('vat_registration_no'),					
                );								
                if (!empty($data)) {
                    if (!empty($cid)) {
                        $updateResp = $this->customer->updateCustomer($cid, $data);						
                        if ($updateResp) {
                            $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>';
                            $msg .= $updateResp . " " . $this->lang->line('CUSTOMER_UPDATE');
                            $msg .= '</div>';
                            $this->session->set_flashdata('customersMessage', $msg);
                        }
                    } else {
						
						#count customer for current user
						if(getAllCustomer($user['UserID']) == '')
							$countCustomer = 0;
						else
							$countCustomer = count(getAllCustomer($user['UserID'])); 
						
						$data['TB_Category']= "CUSTOMER_".($countCustomer+1);
						
						#count all customer category 
						$countCustomerCategory = count(getAllCustomerCategory());
						
						if($countCustomer >= $countCustomerCategory){
							$tbData = array(
											'title' => 'Customer '.($countCustomer+1),
											'catKey' => 'CUSTOMER_'.($countCustomer+1),
											'type' => 'B/S',
											'parent' => 129,
											'AnalysisLedgerParent' => 235,
											'status' => 1
										);
							$saveCustomerCategory = $this->customer->saveCustomerCategory($tbData);
						}
						
						if($page != ''){
							$saveResp = $this->customer->save($data,$page);							
							echo json_encode($saveResp); 
						}else{
							$saveResp = $this->customer->save($data);
							if ($saveResp) {
								$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>';
								$msg .= $saveResp . " " . $this->lang->line('CUSTOMER_SUCCESSS');
								$msg .= '</div>';
								$this->session->set_flashdata('customersMessage', $msg);
							}
						}
                    }
                }
            }
        }
    }

    public function deleteCustomer() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('id');
            $id = $this->encrypt->decode($id);
            if (!empty($id)) {
                $response = $this->customer->delcustomer($id);
                if ($response) {
                    $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>';
                    $msg .= $response . " " . $this->lang->line('CUSTOMER_DELETE_SUCCESSS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('customersMessage', $msg);
                }
            }
        }
    }

    public function changeCustomerStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('id');
            $id = $this->encrypt->decode($id);
            if (!empty($id)) {
                $response = $this->customer->changecustomerStatus($id);
                if ($response) {
                    $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>';
                    $msg .= $response . " " . $this->lang->line('CUSTOMER_STATUS_SUCCESSS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('customersMessage', $msg);
                }
            }
        }
    }

    public function checkSupplieremail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->input->post('email');
            $id = $this->input->post('id');    
            $response = $this->customer->checkemail($email,$id);            
            if ($response) {
                echo 1;
            } else {
                echo 2;
            }
        }
    }
    
   
    

}

?>