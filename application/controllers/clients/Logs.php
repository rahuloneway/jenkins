<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logs extends CI_Controller {

    public function Logs() {
        parent::__construct();

        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        /**
         * 	First check if accountant is accessing the Clients account or not.
         * 	Preventing accountant from direct access to the client's dashboard.
         */
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

        /* Load the expense model */
        $this->load->model('clients/Log');
    }

    public function index($start_from = NULL) { 
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data['title'] = 'Cashman | Logs';
        $data['page'] = 'logs';
        $limit = 10;
        $data['actionlogs'] = $this->Log->getLog(ACTION_LOG_PAGINATION_LIMIT,$page,'');
        $total = $this->Log->totalEntries();
        $data['pagination'] = $this->getPagination(ACTION_LOG_PAGINATION_LIMIT, $total);
		//echo "<pre>"; print_r($data); die;
        $this->load->view('client/logs/default', $data);
    }

    private function getPagination($perPage = ACTION_LOG_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */
        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'logs';
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

    public function searchLog() {
        $sd = $this->input->post('StartDate');
        $ed = $this->input->post('EndDate');
        $StartDate = '';
        $EndDate = '';
        if (!empty($sd) && !empty($ed)) {
            $StartDate = date('Y-m-d', strtotime($sd));
            $EndDate = date('Y-m-d', strtotime($ed. ' +1 day'));
        } else {
            $StartDate = '';
            $EndDate = '';
        }
        $logType = trim($this->input->post('logType'));
        $response = $this->Log->searchLog($StartDate, $EndDate, $logType);
        $data['actionlogs'] = $response;
        $filter_view = $this->load->view('client/logs/log_listing', $data, TRUE);
        echo json_encode($filter_view);
    }

    /*
      To show log details
     */

    function showLogDetails() {
        if ($this->input->is_ajax_request()) {
            $task = $this->encrypt->decode($this->input->post('task'));
            $Id = $this->encrypt->decode($this->input->post('ID'));
            $viewHTML = "<div class='alert alert-danger text-center'><i class='glyphicon glyphicon-exclamation-sign'></i>&nbsp;";
            $viewHTML .= $this->lang->line("ERROR_LOADING_LOG_POPUP_DETAILS");
            $viewHTML .= "</div>";
            $data['details'] = $this->Log->getLogDetails($Id);
            switch ($task) {
                case "LOGIN/LOGOUT":
                    $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                    break;
                case "INVOICE":
                    $item['InvoiceItems'] = $this->Log->logInvoiceDetail($data['details'][0]['ItemId']);
                    if ($data['details'][0]['Action'] == 'DELETE') {
                        $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                        break;
                    } else {
                        if (empty($item['InvoiceItems'])) {
                            echo '<strong>This INVOICE has been Deleted</strong>';
                            $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                            break;
                        } else {
                            $viewHTML = $this->load->view("client/logs/log_details/invoice_popup", $item, true);
                            break;
                        }
                    }
				case "PURCHASE":
                    $item['InvoiceItems'] = $this->Log->logPurchaseDetail($data['details'][0]['ItemId']);
                    if ($data['details'][0]['Action'] == 'DELETE') {
                        $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                        break;
                    } else {
                        if (empty($item['InvoiceItems'])) {
                            echo '<strong>This INVOICE has been Deleted</strong>';
                            $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                            break;
                        } else {
                            $viewHTML = $this->load->view("client/logs/log_details/purchase_popup", $item, true);
                            break;
                        }
                    }
                case "DIVIDEND":
                    $this->load->model('clients/dividends');
                    $result['share_holders'] = $this->dividends->getShareHoldersList();
                    $result['shares'] = $this->dividends->getTotalShares();
                    $result['item'] = $this->dividends->getItem($data['details'][0]['ItemId']);
                    $result['Name'] = $this->dividends->getShareHolderName($result['item']['ShareholderID']);
                    if ($data['details'][0]['Action'] == 'DELETE') {
                        $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                        break;
                    } else {
                        if (empty($result['item'])) {
                            echo '<strong>This DIVIDEND has been Deleted</strong>';
                            $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                            break;
                        } else {
                            $viewHTML = $this->load->view("client/logs/log_details/dividend_popup", $result, true);
                            break;
                        }
                    }

                case "EXPENSE":
                    $this->load->model('clients/expense');
                    if ($data['details'][0]['Action'] != 'DELETE') {
                        $result['item'] = $this->expense->getItem($data['details'][0]['ItemId']);
                        if (!empty($result['item'])) {
                            $car_miles = $bike_miles = $bicycle_miles = 0;
                            foreach ($result['item']['ExpenseMileage'] as $key => $val) {
                                if (categoryName($val->Category) == 'Car') {
                                    $car_miles += $val->Miles;
                                } elseif (categoryName($val->Category) == 'Bike') {
                                    $bike_miles += $val->Miles;
                                } elseif (categoryName($val->Category) == 'Bicycle') {
                                    $bicycle_miles += $val->Miles;
                                }
                            }
                            $result['item']['mileage_cost'] = $this->calMileage($data['item']['Miles'], $car_miles, $bike_miles, $bicycle_miles);
                        } else {
                            $result = array();
                        }
                    }
                    if ($data['details'][0]['Action'] == 'DELETE') {
                        echo '<strong>This EXPENSE has been Deleted</strong>';
                        $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                        break;
                    } else {
                        if (empty($result)) {
                            echo '<strong>This EXPENSE has been Deleted</strong>';
                            $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                            break;
                        } else {
                            $viewHTML = $this->load->view("client/logs/log_details/expense_popup", $result, true);
                            break;
                        }
                    }

                case "PAYROLL":
                    if ($data['details'][0]['Action'] == 'DELETE') {
                        $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                        break;
                    } else if ($data['details'][0]['Action'] == 'PAID') {
                        $result['payee'] = $this->Log->logPayrollPaidDetail($data['details'][0]['ItemId']);
                        $viewHTML = $this->load->view("client/logs/log_details/payroll_popup", $result, true);
                        break;
                    } else {
                        $result['payee'] = $this->Log->logPayrollDetail($data['details'][0]['ItemId']);
                        if (empty($result)) {
                            echo '<strong>This PAYROLL has been Deleted</strong>';
                            $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                            break;
                        } else {
                            $viewHTML = $this->load->view("client/logs/log_details/payroll_popup", $result, true);
                            break;
                        }
                    }

                case "SALARY":
                    if ($data['details'][0]['Action'] == 'DELETE') {
                        $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                        break;
                    } else if ($data['details'][0]['Action'] == 'PAID') {
                        $data['details'] = $this->Log->getSalaryPaidDetails($data['details'][0]['ItemId']);
                        $viewHTML = $this->load->view("client/logs/log_details/salary_popup", $data, true);
                        break;
                    } else {
                        $data['details'] = $this->Log->getSalaryDetails($data['details'][0]['ItemId']);						
                        if (empty($data)) {
                            echo '<strong>This SALARY has been Deleted</strong>';
                            $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                            break;
                        } else {
                            $viewHTML = $this->load->view("client/logs/log_details/salary_popup", $data, true);
                            break;
                        }
                    }
                case "BANK":
                    if ($data['details'][0]['Action'] == 'DELETE') {
                        $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                        break;
                    } else {
                        $data['items'] = $this->Log->getbankDetails($data['details'][0]['ItemId']);
                        if (empty($data)) {
                            echo '<strong>This SALARY has been Deleted</strong>';
                            $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                            break;
                        } else {
                            $viewHTML = $this->load->view("client/logs/log_details/bank_popup", $data, true);
                            break;
                        }
                    }

                case "JOURNAL":
                    $this->load->model("clients/trial_balance");
                    $data['details'] = $this->trial_balance->getJournalDetails($data['details'][0]['ItemId']);
                    $viewHTML = $this->load->view("client/logs/log_details/journal_popup", $data, true);
                    break;

                case "NOTES":
                    $result['stuff'] = $this->Log->logNoteDetail($data['details'][0]['ItemId']);
                    if ($data['details'][0]['Action'] == 'DELETE') {
                        $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                        break;
                    } else {
                        if (empty($result['stuff'])) {
                            echo '<strong>This NOTES has been Deleted</strong>';
                            $viewHTML = $this->load->view("client/logs/log_details/log_popup", $data, true);
                            break;
                        } else {
                            $viewHTML = $this->load->view("client/logs/log_details/note_popup", $result, true);
                            break;
                        }
                    }

                default:
                    break;
            }
            die($viewHTML);
        } else {
            die("You are not allowed to perform this function!");
        }
    }

    public function calMileage($prev_car_miles = 0, $car_miles = 0, $bike_miles = 0, $bicycle_miles = 0, $call = '') {
        $car_cost = 0;
        $bike_cost = 0;
        $bicycle_cost = 0;
        $total_cost = 0;
        /* CASE - ONE : Car */
        if ($prev_car_miles > MILEAGE_DISTANCE_LIMIT) {
            $car_cost = ($car_miles * MILEAGE_EXCEED_COST) / 100;
        } else {
            $total_miles = $prev_car_miles + $car_miles;
            if ($total_miles < MILEAGE_DISTANCE_LIMIT) {
                $car_cost = ($car_miles * CAR_MILEAGE_COST) / 100;
            } else {
                $car_cost_am = $total_miles - MILEAGE_DISTANCE_LIMIT;
                $car_cost_bm = $car_miles - $car_cost_am;
                $car_cost = ($car_cost_am * MILEAGE_EXCEED_COST) / 100 + ($car_cost_bm * CAR_MILEAGE_COST) / 100;
            }
        }

        /* CASE - TWO : Bike */
        if ($bike_miles > 0) {
            $bike_cost = ($bike_miles * BIKE_MILEAGE_COST) / 100;
        }

        /* CASE - THREE : Bicycle */
        if ($bicycle_miles > 0) {
            $bicycle_cost = ($bicycle_miles * CYCLE_MILEAGE_COST) / 100;
        }
        /*
          if($bike_miles < MILEAGE_DISTANCE_LIMIT)
          {
          $bike_cost = ($bike_miles * BIKE_MILEAGE_COST)/100;
          }else{
          $above_miles = $bike_miles - MILEAGE_DISTANCE_LIMIT;
          $below_miles = $bike_miles - $above_miles;
          $bike_cost = ($above_miles * MILEAGE_EXCEED_COST)/100 + ($below_miles * BIKE_MILEAGE_COST)/100;
          }


          if($bicycle_miles < MILEAGE_DISTANCE_LIMIT)
          {
          $bicycle_cost = ($bicycle_miles * CYCLE_MILEAGE_COST)/100;
          }else{
          $above_miles = $bicycle_miles - MILEAGE_DISTANCE_LIMIT;
          $below_miles = $bicycle_miles - $above_miles;
          $bicycle_cost = ($above_miles * MILEAGE_EXCEED_COST)/100 + ($below_miles * CYCLE_MILEAGE_COST)/100;
          }
         */

        $total_cost = $car_cost + $bike_cost + $bicycle_cost;

        if ($call == 'ajax') {
            $json['cost'] = $total_cost;
            die(json_encode($json));
        } else {
            return $total_cost;
        }
    }

    public function quarters($year = NULL) {
        $user = $this->session->userdata('user');
        $quarter = array('0' => 'Select Quarters');
        $end_date = $user['CompanyEndDate'];
        if (!empty($year)) {

            $year = explode('/', $year);
            $financial_year = trim($year[0]) . '-04-06';

            $financial_year = $financial_year . '-04-06';
        } else {
            $financial_year = date('Y') - 1;
            $financial_year = $financial_year . '-04-06';
        }

        for ($x = 0; $x < 4; $x++) {
            $second_quarter = date('Y-m-d', strtotime('+3 month', strtotime($financial_year) - (1 * 24 * 60 * 60)));
            $quarter[] = date('jS F Y', strtotime($financial_year)) . ' - ' . date('jS F Y', strtotime($second_quarter));
            $second_quarter = date('Y-m-d', strtotime('+3 month', strtotime($financial_year)));
            $financial_year = $second_quarter;
        }
        return $quarter;
    }
  
   
}
