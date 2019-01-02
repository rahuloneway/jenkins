<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//session_start();
class Suppliers extends CI_Controller {

    public function Suppliers() {
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
        $this->load->model('clients/supplier');
    }

    public function index() {
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data['title'] = 'Cashman | Suppliers';
        $data['page'] = "suppliers";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supplierId = $this->input->post('suppplier');
	    if (!empty($supplierId)) {
                $data['supplierId'] = $supplierId;
            }
        } 
        $data['suppliers'] = $this->supplier->getsupplier(ACTION_LOG_PAGINATION_LIMIT, $page, '', $supplierId);
        $data['supplierlist'] = $this->supplier->getsupplierlist();
        $total = $this->supplier->totalsuppliers($supplierId);
		$data['pagination'] = $this->getPagination(ACTION_LOG_PAGINATION_LIMIT, $total);
        $this->load->view('client/suppliers/default', $data);
    }

    private function getPagination($perPage = supplierS_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */
        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'suppliers';
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

    public function supplierForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('id');
	    $id = $this->encrypt->decode($id);
            if (!empty($id)) {
                $editsupplier = $this->supplier->editSupplier($id);
                $data['editsupplier'] = $editsupplier;
            }
        } else {
            $data = array();
        }
        $viewHTML = $this->load->view("client/suppliers/suppliers_form", $data, true);
        echo $viewHTML;
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->input->is_ajax_request()) {
                redirect(site_url('suppliers'), 'refresh');
            } else {
                $sid = $this->input->post('sid');
                $user = $this->session->userdata('user');
                $data = array(
                    'companyname' => $this->input->post('companyname'),
                    'status' => $this->input->post('status'),
                    'first_name' => $this->input->post('firstname'),
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
                    'city' => $this->input->post('city'),
                    'state' => $this->input->post('state'),
                    'postcode' => $this->input->post('postcode'),
                    'country' => $this->input->post('country'),
                    'VAT_Registration_no' => $this->input->post('vat_registration_no'),
                );
                if (!empty($data)) {
                    if (!empty($sid)) {
                        $updateResp = $this->supplier->updatesupplier($sid, $data);
                        if ($updateResp) {
                            $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>';
                            $msg .= $updateResp . " " . $this->lang->line('SUPPLIERS_UPDATE');
                            $msg .= '</div>';
                            $this->session->set_flashdata('suppliersMessage', $msg);
                        }
                    } else {
										
					#count supplier for current user					
					if(getAllSupplier($user['UserID']) == '')
						$countSupplier = 0;
					else
						$countSupplier = count(getAllSupplier($user['UserID']));
					
					$data['TB_Category']= "SUPPLIER_".($countSupplier+1);
					
					#count all supplier category 
					$countSupplierCategory = count(getAllSupplierCategory());
					
					#Save new supplier category in 
					if($countSupplier >= $countSupplierCategory){
						$tbData = array(
										'title' => 'Supplier '.($countSupplier+1),
										'catKey' => 'SUPPLIER_'.($countSupplier+1),
										'type' => 'B/S',
										'parent' => 129,
										'AnalysisLedgerParent' => 242,
										'status' => 1
									);
						$saveSupplierCategory = $this->supplier->saveSupplierCategory($tbData);
					}
										
					$saveResp = $this->supplier->save($data);
					
                        if ($saveResp) {
                            $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>';
                            $msg .= $saveResp . " " . $this->lang->line('SUPPLIERS_SUCCESSS');
                            $msg .= '</div>';
                            $this->session->set_flashdata('suppliersMessage', $msg);
                        }
                    }
                }
            }
        }
    }

    public function deletesupplier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('id');
            $id = $this->encrypt->decode($id);
            if (!empty($id)) {
                $response = $this->supplier->delsupplier($id);
                if ($response) {
                    $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>';
                    $msg .= $response . " " . $this->lang->line('supplier_DELETE_SUCCESSS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('suppliersMessage', $msg);
                }
            }
        }
    }

    public function changesupplierStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('id');
            $id = $this->encrypt->decode($id);
            if (!empty($id)) {
                $response = $this->supplier->changesupplierStatus($id);
                if ($response) {
                    $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>';
                    $msg .= $response . " " . $this->lang->line('SUPPLIER_STATUS_SUCCESSS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('suppliersMessage', $msg);
                }
            }
        }
    }

  public function checkSupplieremail(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = $this->input->post('email');
	if(!empty($email)){
		 $response = $this->supplier->checkemail($email);
		 echo $response;
	}
    }
  }	
}

?>
