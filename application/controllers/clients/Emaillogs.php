<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Emaillogs extends CI_Controller {

    public function __construct() {
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
        $this->load->model('clients/Emaillog');
        $this->load->model('accountant/Term');
        
    }

    public function index() {
        $startdate = $this->input->post('StartDate');
        $enddate = $this->input->post('EndDate');
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data['title'] = 'Cashman | Email Logs';
        $data['page'] = "emaillogs";
        $data['startdate'] = $startdate;
        $data['enddate'] = $enddate;
        $data['emailLogs'] = array();
        $data['emailLogs'] =$this->Emaillog->getemailLogs(BULK_EMAI_LOGS_PAGINATION_LIMIT, $page,$startdate,$enddate);
        $data['client'] = $this->Term->getClient();
        $total = $this->Emaillog->getBulkemailtotal();
        $data['pagination'] = $this->getPagination(BULK_EMAI_LOGS_PAGINATION_LIMIT, $total);
        $this->load->view('client/emaillogs/default', $data);
    }

    private function getPagination($perPage = BULK_EMAI_LOGS_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */

        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'emaillogs';
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

}

?>