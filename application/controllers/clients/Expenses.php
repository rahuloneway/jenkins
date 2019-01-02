<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expenses extends CI_Controller {

    public function Expenses() {
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
        $this->load->model('clients/expense');
    }

    public function index() {
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        //print_r($page);
        $data['title'] = "Dashboard | Expenses";
        $items = $this->expense->getItems(EXPENSE_PAGINATION_LIMIT, $page);
		//echo "<pre>"; print_r($items); echo "</pre>";
        $data['items'] = $items;
        $data['employees'] = $this->expense->getEmployeeList('check');
        $total = $this->expense->totalExpenses();
        $data['pagination'] = $this->getPagination('expenses', EXPENSE_PAGINATION_LIMIT, $total);
        $this->load->view('client/expenses/default', $data);
    }

    public function getPagination($url = null, $perPage = EXPENSE_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */
        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'expenses';
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
     * 	This function loads the expense form for adding the expense items.
     * 	It also loads the form for editing the expense items.
     */
    public function expenseForm() { 
	
		$this->session->set_flashdata('carMileageSession', '');
	
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['users'] = $this->expense->getEmployeeList('include');
            $data['vat_listing'] = $this->expense->getVatType();
            $data['form_id'] = 'expenseForm';
            $data['form_link'] = site_url() . 'expenses_save';
            if (isset($_POST['task'])) {
                $task = safe($this->encrypt->decode($_POST['task']));
                $id = safe($this->encrypt->decode($_POST['ID']));
                $data['task'] = $task;
                $data['item'] = array();
                $date['mileage_cost'] = 0;
                if ($task == 'editExpense') {
                    $data['item'] = $this->expense->getItem($id);
                    $data['form_id'] = 'updateExpenseForm';
                    $data['form_link'] = site_url() . 'update_expense';
                } elseif ($task == 'copyExpense') {
                    $data['item'] = $this->expense->getItem($id);
                    $data['form_id'] = 'expenseForm';
                    $data['form_link'] = site_url() . 'expenses_save';
                } elseif ($task == 'viewExpense') {
                    $data['item'] = $this->expense->getItem($id);
                    $data['form_id'] = 'updateExpenseForm';
                    $data['form_link'] = site_url() . 'update_expense';
                    $car_miles = $bike_miles = $bicycle_miles = 0;
                    foreach ($data['item']['ExpenseMileage'] as $key => $val) {
                        if (categoryName($val->Category) == 'Car') {
                            $car_miles += $val->Miles;
                        } elseif (categoryName($val->Category) == 'Bike') {
                            $bike_miles += $val->Miles;
                        } elseif (categoryName($val->Category) == 'Bicycle') {
                            $bicycle_miles += $val->Miles;
                        }
                    }

                    $data['item']['mileage_cost'] = $this->calMileage($data['item']['Miles'], $car_miles, $bike_miles, $bicycle_miles);
                }
            }			
            if ($task == 'viewExpense') {
                $this->load->view('client/expenses/expense_status', $data);
            } elseif ($task == 'uploadExpense') {
                $data['form_id'] = 'uExpense';
                $data['form_link'] = site_url() . 'expense_upload_expense';
                $data['form_type'] = 'expense';
                $this->load->view('client/expenses/upload_expense', $data);
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
            } elseif ($task == 'uploadCredit') {
                $data['form_id'] = 'Credit';
                $data['form_link'] = site_url() . 'expense_upload_credit';
                $data['form_type'] = 'credit';
                $this->load->view('client/expenses/upload_expense', $data);
                echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
            } else {
                $this->session->set_userdata('expense_file', '');
                $this->load->view('client/expenses/form', $data);
            }
        } else {
            show_404();
        }
    }

    public function save() {
        $user = $this->session->userdata('user');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {			
						
            /* Check if added from file or not */
            $files = $this->session->userdata('expense_file');
            if (!empty($files)) {
                $file_id = 0;
                $files = json_decode($files);
                $accountant_access = clientAccess();
                $file_counter = $this->expense->getMaxFiles();
                $file_data = array(
                    'FName' => $files->name,
                    'FType' => $files->type,
                    'FSize' => $files->size,
                    'UploadedOn' => date('Y-m-d'),
                    'UploadedBy' => $user['UserID'],
                    'Type' => 'E',
                    'AccountantAccess' => $accountant_access,
                );
                if (file_exists('assets/uploads/' . $file_data['FName'])) {
                    $file_id = $this->expense->saveFile($file_data);
                    if (empty($file_id)) {
                        $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                        $msg .= $this->lang->line('UNEXPECTED_FILE_UPLOAD_ERROR');
                        $msg .= '</div>';
                        $this->session->set_flashdata('expenseError', $msg);
                        setRedirect('expenses');
                    }
                } else {
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('UPLOADED_FILE_DOES_NOT_EXISTS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('expenseError', $msg);
                    setRedirect('expenses');
                }
                $this->session->set_userdata('expense_file', '');
            } else {
                $file_id = 0;
            }

            $task = $this->encrypt->decode($_POST['task']);
            if (isset($_POST['MileageDate'])) {
                $expense_type = 'EXPENSE';
                $tag = 'EXPENSE';
            } else {
                $expense_type = 'CREDITCARD';
                $tag = 'CREDITCARD';
            }

            /* Check if Created by accountant while accessing the client account */
            $accountant_access = clientAccess();
            $AddedOn = date('Y-m-d');
            $AddedBy = $user['UserID'];

            /* Get the previous car mileage of the chosen client */

            $prev_car_miles = $this->expense->getCarMiles($_POST['eCustomer'], $_POST['Year'], null);

            /* STEP - 1 Prepare expense detail */
            if ($task != 'save') {
                $expense_number = $this->expense->max_id();
                $name = getEmployeeInfo($_POST['eCustomer']);
                $name = substr(strtoupper($name), 0, 3);
                $expense_number = $name . '-' . date('Y') . date('m') . date('d') . '-' . $tag . '-' . $expense_number;
            } else {
                $expense_number = 'SAVED AS DRAFT';
            }
            //echo 'Expense Number : '.$expense_number;die;

            $expense = array(
                'EmployeeID' => $_POST['eCustomer'],
				'CustomerCompanyID' => $user['CompanyID'],
                'ExpenseType' => $expense_type,
                'ExpenseNumber' => $expense_number,
                'Month' => $_POST['Month'],
                'Year' => $_POST['Year'],
                'ExpenseDate' => $_POST['Year'] . '-' . $_POST['Month'] . '-01',
                'TotalAmount' => 0,
                'TotalMiles' => 0,
                'AddedOn' => $AddedOn,
                'AddedBy' => $AddedBy,
                'PaidOn' => (isset($_POST['ExpensePaid'])) ? $AddedOn : '',
                'Status' => ($task == 'save') ? 1 : 2,
                'Reconciled' => 0,
                'FileID' => $file_id,
                'AccountantAccess' => $accountant_access
            );

			//echo "<pre>";print_r($expense); die;
			
            $vat_amount = array();
            //if ($user['VAT_TYPE'] == 'stand') {
                $vat_amount = $_POST['VatAmount'];
            //}
			
            $TotalVATAmount = 0;

            /* STEP - 2 Prepare expense item detail */
            $ExpenseItemDate = $_POST['ExpenseItemDate'];
            $ExpenseCategory = $_POST['Category'];
            $ExpenseItemAmount = $_POST['ExpenseItemAmount'];
            $Description = $_POST['Description'];
            $TotalItemAmount = 0;
            $ExpenseItemDate = array_filter($ExpenseItemDate);

            if (count($ExpenseItemDate) > 0) {
                foreach ($ExpenseItemDate as $key => $val) {					
                    $temp_data = array(
                        'ExpenseID' => '',						
                        'ItemType' => 'EXPENSE',
                        'Category' => $ExpenseCategory[$key],
                        'ItemDate' => mDate($val),
                        'LocationFrom' => '',
                        'LocationTo' => '',
                        'Purpose' => '',
                        'Miles' => 0,
                        'Amount' => $ExpenseItemAmount[$key],
                        'AddedOn' => $AddedOn,
                        'AddedBy' => $AddedBy,
                        'AccountantAccess' => $accountant_access,
                        'FileID' => $file_id,
                        'Reconciled' => 0,
                        'Status' => ($task == 'save') ? 1 : 2,
                        'VATAmount' => 0,
                        'Description' => $Description[$key]
                    );
                    if (count($vat_amount) > 0) {
                        $temp_data['VATAmount'] = $vat_amount[$key];
                        $TotalVATAmount += $vat_amount[$key];
                    }
                    $expense_item[] = $temp_data;
                    $TotalItemAmount += $ExpenseItemAmount[$key];
                }
            } else {
                $expense_item = array();
            }

            $total_mileage_amount = 0;
            /* STEP - 3 Prepare expense mileage detail */
            if (isset($_POST['MileageDate'])) {
                $MileageDate = $_POST['MileageDate'];
                $LocationFroM = $_POST['LocationFrom'];
                $LocationTo = $_POST['LocationTo'];
                $ExpenseMileage = $_POST['ExpenseMileage'];
                $Purpose = $_POST['Purpose'];
                $Miles = $_POST['Miles'];
                $MileageExpensed = $_POST['MileageExpensed'];
                $TotalExpenseAmount = 0;
                $MileageDate = array_filter($MileageDate);

                $car_miles = 0;
                $bike_miles = 0;
                $bicycle_miles = 0;


                if (count($MileageDate) > 0) {
                    $temp_amount = 0;
                    $temp_car_amount = 0;
                    $mileage_amount = 0;
                    $total_mileage_amount = 0;
                    foreach ($MileageDate as $key => $val) {
                        if (categoryName($ExpenseMileage[$key]) == 'Car') {
                            $prev_car_miles = $this->expense->get_car_previous_miles($_POST['eCustomer'], mDate($val));
                            $temp_car_amount = $this->calMileage($prev_car_miles, $Miles[$key], 0, 0);
                            $mileage_amount = $temp_car_amount;
                        } elseif (categoryName($ExpenseMileage[$key]) == 'Bike') {
                            $temp_amount = $this->calMileage(0, 0, $Miles[$key], 0);
                            $mileage_amount = $temp_amount;
                        } else {
                            $temp_amount = $this->calMileage(0, 0, 0, $Miles[$key]);
                            $mileage_amount = $temp_amount;
                        }
                        $expense_mileage[] = array(
                            'ExpenseID' => '',
                            'ItemType' => 'MILEAGE',
                            'Category' => $ExpenseMileage[$key],
                            'ItemDate' => mDate($val),
                            'LocationFrom' => $LocationFroM[$key],
                            'LocationTo' => $LocationTo[$key],
                            'Purpose' => $Purpose[$key],
                            'Miles' => $Miles[$key],
                            'Amount' => $mileage_amount,
                            'AddedOn' => $AddedOn,
                            'AddedBy' => $AddedBy,
                            'AccountantAccess' => $accountant_access,
                            'FileID' => $file_id,
                            'Reconciled' => 0,
                            'Status' => (isset($_POST['ExpensePaid'])) ? 1 : 0,
                            'VATAmount' => 0,
                            'Description' => ''
                        );
                        if (categoryName($ExpenseMileage[$key]) == 'Car') {
                            $car_miles += $Miles[$key];
                        } elseif (categoryName($ExpenseMileage[$key]) == 'Bike') {
                            $bike_miles += $Miles[$key];
                        } elseif (categoryName($ExpenseMileage[$key]) == 'Bicycle') {
                            $bicycle_miles += $Miles[$key];
                        }
                        $total_mileage_amount += $mileage_amount;
                    }
                } else {
                    $expense_mileage = array();
                }
				
                $expense_item = array_merge($expense_item, $expense_mileage);								

                /* STEP - 4 Calculate the expense mileage cost */

                $total_expense_cost = $TotalItemAmount + $this->calMileage($prev_car_miles, $car_miles, $bike_miles, $bicycle_miles);
                $total_expense_cost = $TotalItemAmount + $total_mileage_amount;

                $total_miles = $car_miles + $bike_miles + $bicycle_miles;


                /* STEP - 5 Insert the expense detail in expense table */
                foreach ($expense as $key => $val) {
                    $expense['TotalAmount'] = $total_expense_cost;
                    $expense['TotalMiles'] = $total_miles;
                }
            } else {
                $expense['TotalAmount'] = $TotalItemAmount + $TotalVATAmount;
                $expense['TotalMiles'] = 0;
            }
            $expense['TotalVATAmount'] = $TotalVATAmount;
            $expense['TotalAmount'] = $expense['TotalAmount'] + $TotalVATAmount;
            //pr($expense);
            //print_r($expense_item);die('expense controller 361');
            $response = $this->expense->save($expense);			
            if (!$response) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED') . '</div>';
                $this->session->set_flashdata('expenseError', $msg);
                setRedirect('expenses');
            }

            /* STEP - 5 Insert the expense items detail in expense_item table */
            $expense_id = $response;
            foreach ($expense_item as $key => $val) {
                $expense_item[$key]['ExpenseID'] = $expense_id;
            }
			
            $response = $this->expense->saveItems($expense_item);
            if (!$response) {
                /* If item has not been saved then delete the expense also */
                $this->expense->delete($expense_id);
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED') . '</div>';
                $this->session->set_flashdata('expenseError', $msg);
                setRedirect('expenses');
            } else {
                //if (isset($_POST['ExpensePaid'])) {
                    update_trial_balance("expense", $expense_id);					
                //}
            }

			
            /* If no error then return true */
            $msg = '<div class="alert alert-success">';
            $msg .= '<i class="glyphicon glyphicon-ok"></i>&nbsp;';
            if ($task == 'save') {
                update_logs('EXPENSE', 'USER_SAVED_EXPENSES', 'SAVED', "", $expense_id);
                $msg .= $this->lang->line('EXPENSE_SAVE_SUCCESS') . '</div>';
            } else {
                update_logs('EXPENSE', 'USER_CREATED_EXPENSES', 'CREATE', "", $expense_id);
                $msg .= sprintf($this->lang->line('EXPENSE_CREATE_SUCCESS'), $expense_number) . '</div>';
            }
            $this->session->set_flashdata('expenseError', $msg);
            setRedirect('expenses');
        } else {
            show_404();
        }
    }

    /**
     * 	Function to update the invoice.
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['delexpItem']) && $_POST['delexpItem'] != 0 && $_POST['delexpItem'] != NULL) {
                $response = $this->expense->delexpItem($_POST['delexpItem']);
                if ($response) {
                    if ($this->input->post('status') == 3) {
                        $this->expense->deletetbDetails($_POST['delexpItem'], 'EXPENSE');
                    }
                }
            }
            $user = $this->session->userdata('user');
            $task = $this->encrypt->decode($_POST['task']);
            $expense_id = $this->encrypt->decode($_POST['id']);
            /* Check if Created by accountant while accessing the client account */
            $accountant_access = clientAccess();
            $AddedOn = date('Y-m-d');
            $AddedBy = $user['UserID'];
            if (isset($_POST['MileageDate'])) {
                $expense_type = 'EXPENSE';
                $tag = 'EXPENSE';
            } else {
                $expense_type = 'CREDITCARD';
                $tag = 'CREDITCARD';
            }

            /* Get the previous car mileage of the chosen client */
            $prev_car_miles = $this->expense->getCarPrevMilee($_POST['eCustomer']);
            /* STEP - 1 Prepare expense detail */
            if ($task != 'update') {
                $expense_number = $expense_id;
                $name = getEmployeeInfo($_POST['eCustomer']);
                $name = substr(strtoupper($name), 0, 3);
                $expense_number = $name . '-' . date('Y') . date('m') . date('d') . '-' . $tag . '-' . $expense_number;
                if ($this->input->post('status')) {
                    $status = $this->input->post('status');
                } else {
                    $status = 2;
                }
            } else {
                $expense_number = 'SAVED AS DRAFT';
                $status = 1;
            }

            if ($this->input->post('PaidOn') != "") {
                $PaidOn = date('Y-m-d', strtotime($this->input->post('PaidOn')));
            } else {
                $PaidOn = "";
            }

            $expense = array(
                'EmployeeID' => $_POST['eCustomer'],
                'ExpenseType' => $expense_type,
                'ExpenseNumber' => $expense_number,
                'Month' => $_POST['Month'],
                'Year' => $_POST['Year'],
                'TotalAmount' => 0,
                'TotalMiles' => 0,
                'AddedBy' => $AddedBy,
                'PaidOn' => $PaidOn,
                'Status' => $status,
                'Reconciled' => 0,
                'AccountantAccess' => $accountant_access
            );

            /* STEP - 2 Prepare expense item detail */
            $ExpenseItemDate = $_POST['ExpenseItemDate'];
            $ExpenseCategory = $_POST['ExpenseCategory'];
            $ExpenseItemAmount = $_POST['ExpenseItemAmount'];
            $Description = $_POST['Description'];
            $TotalItemAmount = 0;
            $expense_item_id = $_POST['expense_item_id'];
            $ExpenseItemDate = array_filter($ExpenseItemDate);
            if (count($ExpenseItemDate) > 0) {
                foreach ($ExpenseItemDate as $key => $val) {
                    $item_id = $this->encrypt->decode($expense_item_id[$key]);
                    $expense_item[] = array(
                        'ID' => $item_id,
                        'ExpenseID' => $expense_id,
                        'ItemType' => 'EXPENSE',
                        'Category' => $ExpenseCategory[$key],
                        'ItemDate' => mDate($val),
                        'LocationFrom' => '',
                        'LocationTo' => '',
                        'Purpose' => '',
                        'Miles' => 0,
                        'Amount' => $ExpenseItemAmount[$key],
                        'AddedOn' => $AddedOn,
                        'AddedBy' => $AddedBy,
                        'AccountantAccess' => $accountant_access,
                        'FileID' => 0,
                        'Reconciled' => 0,
                        'Status' => $status,
                        'Description' => $Description[$key],
                    );
                    $TotalItemAmount += $ExpenseItemAmount[$key];
                }
            } else {
                $expense_item = array();
            }

            /* STEP - 3 Prepare expense mileage detail */
            $MileageDate = $_POST['MileageDate'];
            $LocationFroM = $_POST['LocationFrom'];
            $LocationTo = $_POST['LocationTo'];
            $ExpenseMileage = $_POST['ExpenseMileage'];
            $Purpose = $_POST['Purpose'];
            $Miles = $_POST['Miles'];
            $MileageExpensed = $_POST['MileageExpensed'];
            $TotalExpenseAmount = 0;
            $MileageDate = array_filter($MileageDate);
            $expense_mileage_id = $_POST['expense_mileage_id'];
            $car_miles = 0;
            $bike_miles = 0;
            $bicycle_miles = 0;

            if (count($MileageDate) > 0) {
                foreach ($MileageDate as $key => $val) {
                    $item_id = $this->encrypt->decode($expense_mileage_id[$key]);
                    $expense_mileage[] = array(
                        'ID' => $item_id,
                        'ExpenseID' => $expense_id,
                        'ItemType' => 'MILEAGE',
                        'Category' => $ExpenseMileage[$key],
                        'ItemDate' => mDate($val),
                        'LocationFrom' => $LocationFroM[$key],
                        'LocationTo' => $LocationTo[$key],
                        'Purpose' => $Purpose[$key],
                        'Miles' => $Miles[$key],
                        'Amount' => 0,
                        'AddedOn' => $AddedOn,
                        'AddedBy' => $AddedBy,
                        'AccountantAccess' => $accountant_access,
                        'FileID' => 0,
                        'Reconciled' => 0,
                        'Status' => $status
                    );
                    if (categoryName($ExpenseMileage[$key]) == 'Car') {
                        $car_miles += $Miles[$key];
                    } elseif (categoryName($ExpenseMileage[$key]) == 'Bike') {
                        $bike_miles += $Miles[$key];
                    } elseif (categoryName($ExpenseMileage[$key]) == 'Bicycle') {
                        $bicycle_miles += $Miles[$key];
                    }
                }
            } else {
                $expense_mileage = array();
            }


            $expense_item = array_merge($expense_item, $expense_mileage);

            /* STEP - 4 Calculate the expense mileage cost */

            $total_expense_cost = $TotalItemAmount + $this->calMileage($prev_car_miles, $car_miles, $bike_miles, $bicycle_miles);

            $total_miles = $car_miles + $bike_miles + $bicycle_miles;


            /* STEP - 4 Insert the expense detail in expense table */
            foreach ($expense as $key => $val) {
                $expense['TotalAmount'] = $total_expense_cost;
                $expense['TotalMiles'] = $total_miles;
            }


            $data = array();
            $prev_car_miles = 0;
            $prev_car_miles = $this->expense->getCarPrevMileetotal($_POST['eCustomer']);
            foreach ($expense_item as $key => $value) {
                if ($value['Category'] == 32 && empty($value['Amount'])) {
                    $amt = $this->calMileage($prev_car_miles, $value['Miles'], $bike_miles, $bicycle_miles);
                    $data = array('Amount' => $amt);
                    $this->expense->updateAccmount($data, $value['ID']);
                    $prev_car_miles = $prev_car_miles + $value['Miles'];
                    $expense_item[$key]['Amount'] = $amt;
                }
            }

            /* echo '<pre>';print_r($expense);echo '</pre>';
              echo '<br/>';
              echo '<pre>';print_r($expense_item);echo '</pre>';
              die; */

            $response = $this->expense->update($expense, $expense_id);
            if (!$response) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED') . '</div>';
                $this->session->set_flashdata('expenseError', $msg);
                setRedirect('expenses');
            }
            //echo '<pre>';print_r($expense_item);echo '</pre>';die;
            /* STEP - 5 Insert the expense items detail in expense_item table */

            $response = $this->expense->updateItems($expense_item);
            if (!$response) {
                /* If item has not been saved then delete the expense also */
                $this->expense->delete($expense_id);
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED') . '</div>';
                $this->session->set_flashdata('expenseError', $msg);
                setRedirect('expenses');
            } else {
                if ($this->input->post('status') == 3) {
                update_trial_balance("expense", $expense_id, "", "", "", "3");
                }
            }

           

            /* If no error then return true */
            $msg = '<div class="alert alert-success">';
            $msg .= '<i class="glyphicon glyphicon-ok"></i>&nbsp;';
            if ($task == 'save') {
                update_logs('EXPENSE', 'USER_UPDATED_EXPENSES', 'UPDATE', "", $expense_id);
                $msg .= $this->lang->line('EXPENSE_UPDATE_SUCCESS') . '</div>';
            } else if ($task == 'update') {
                update_logs('EXPENSE', 'USER_UPDATED_EXPENSES', 'UPDATE', "", $expense_id);
                $msg .= $this->lang->line('EXPENSE_UPDATE_SUCCESS') . '</div>';
            } else {
                update_logs('EXPENSE', 'USER_EXPENSES_DRAFT_CREATE', 'INSERT', "", $expense_id);
                $msg .= sprintf($this->lang->line('EXPENSE_CREATE_SUCCESS'), $expense_number) . '</div>';
            }

            $this->session->set_flashdata('expenseError', $msg);
            setRedirect('expenses');
        } else {
            show_404();
        }
    }

    /**
     * 	Function to set credentials for search in expense listing.
     * 	Here we will set the searching fields in session variable.
     */
    public function search() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //	echo '<pre>';print_r($_POST);echo '</pre>';die;
            $search = array(
                'EmployeeID' => safe($_POST['EmployeeID']),
                'Month' => safe($_POST['Month']),
                'Year' => safe($_POST['Year'])
            );
            $this->session->set_userdata('ExpenseSearch', $search);
            setRedirect('expenses');
        } else {
            show_404();
        }
    }

    /**
     *
     */
    public function expenseSort() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $order = safe($this->encrypt->decode($_POST['order']));
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $des_order_value = array(
                'SORT_BY_EXPENSE' => 'e.ExpenseNumber DESC',
                'SORT_BY_NAME' => 'CONCAT(ce.FirstName," ",ce.LastName) DESC',
                'SORT_BY_MONTH' => 'e.ExpenseDate DESC',
                'SORT_BY_MILES' => 'e.TotalMiles DESC',
                'SORT_BY_AMOUNT' => 'e.TotalAmount DESC',
                'SORT_BY_FILES' => 'e.FileID DESC',
                'SORT_BY_STATUS' => 'e.Status DESC'
            );
            $asc_order_value = array(
                'SORT_BY_EXPENSE' => 'e.ExpenseNumber ASC',
                'SORT_BY_NAME' => 'CONCAT(ce.FirstName," ",ce.LastName) ASC',
                'SORT_BY_MONTH' => 'e.ExpenseDate ASC',
                'SORT_BY_MILES' => 'e.TotalMiles ASC',
                'SORT_BY_AMOUNT' => 'e.TotalAmount ASC',
                'SORT_BY_FILES' => 'e.FileID ASC',
                'SORT_BY_STATUS' => 'e.Status ASC'
            );
            $prev_order = $this->session->userdata('expenseSortingOrder');
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
            $this->session->set_userdata('expenseSortingOrder', $order_value);
            $data['vat_listing'] = $this->expense->getVatType();
            $data['items'] = $this->expense->getItems(EXPENSE_PAGINATION_LIMIT, $page);

            $json['html'] = $this->load->view('client/expenses/expense_listing', $data, true);
            $json['dir'] = $dir;
            echo json_encode($json);
            exit;
        } else {
            show_404();
        }
    }

    /**
     * 	Function to perform different action depending on the user click.
     *  This function executes only through ajax call.
     */
    public function action() {
        $user_task = array(
            '1' => 'ACTION_COPY',
            '2' => 'ACTION_RECONCILED',
            '3' => 'ACTION_DELETE',
            '4' => 'ACTION_PAID'
        );

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->encrypt->decode(safe($_POST['id']));
            if (isset($_POST['PaidDate'])) {
                $paidDate = safe($_POST['PaidDate']);
            } else {
                $paidDate = '';
            }

            $id = explode('/', $id);
            $task = $id[0];
            $id = $id[1];
            $type = safe($_POST['type']);

            if (!in_array($task, $user_task)) {
                show_404();
            }
            $response = $this->expense->performAction($task, $id, $paidDate);
			
			if ($response) {
                $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
                $total = $this->expense->totalExpenses();
                $data['pagination'] = $this->getPagination('expenses', EXPENSE_PAGINATION_LIMIT, $total);
                $data['items'] = $this->expense->getItems(EXPENSE_PAGINATION_LIMIT, $page);
                if ($task == 'ACTION_RECONCILED') {
                    $msg = $this->lang->line('CLIENT_EXPENSE_ACTION_SUCCESS_RECONCILED');
                } elseif ($task == 'ACTION_DELETE') {
                    update_logs('EXPENSE', 'USER_DELETED_EXPENSES', 'DELETE', "", $id);
                    $msg = $this->lang->line('CLIENT_EXPENSE_ACTION_SUCCESS_DELETE');
                } elseif ($task == 'ACTION_PAID') {
                    update_logs('EXPENSE', 'MARK_EXPENSES_PAID', 'PAID', "", $id);
                    $msg = $this->lang->line('CLIENT_EXPENSE_ACTION_SUCCESS_PAID');
                }
                $json['error'] = "";
                $json = array(
                    'MSG' => $msg,
                    'LIST' => $this->load->view('client/expenses/expense_listing', $data, TRUE),
                    'PAGINATION' => $data['pagination']
                );
                echo json_encode($json);
                exit;
            } else {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED') . '</div>';
                $this->session->set_flashdata('expenseError', $msg);
                $json['error'] = "error";
                echo json_encode($json);
                exit;
            }
        } else {
            show_404();
        }
    }

    /**
     * 	Function to reset the search fields.
     *
     */
    public function clean() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->session->set_userdata('ExpenseSearch', '');
            $this->session->set_userdata('ExpenseSearchRecords', '');
            $data['items'] = $this->expense->getItems(EXPENSE_PAGINATION_LIMIT, 0);
            $json = array();
            $total = $this->expense->totalExpenses();
            $json['pagination'] = $this->getPagination('expenses', EXPENSE_PAGINATION_LIMIT, $total);
            $json['html'] = $this->load->view('client/expenses/expense_listing', $data, TRUE);
            echo json_encode($json);
            exit;
        } else {
            show_404();
        }
    }

    /**
     * 	Function to get the total distance travelled by the Employee of the Client.
     */
    public function getDistance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $vid = safe($_POST['VID']);
            $eid = safe($_POST['EID']);
            $response = $this->expense->getDistance($vid, $eid);
            if ($response) {
                $response = json_encode($response);
                echo $response;
                exit;
            } else {
                echo json_encode('0');
                exit;
            }
        } else {
            show_404();
        }
    }

    /**
     * 	Function to process the uploaded expense template.
     * 	After processing the fields, loads the expense form i.e. form.php
     */
    public function uploadExpenses() {
        $data['title'] = "Dashboard | Upload Expenses";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            /* Check if Created by accountant while accessing the client account */
            $accountant_access = clientAccess();
            $methods = $this->expense->getMethods();
            $categoryList = $this->expense->getCategories();
            $emplyee_name = $this->expense->getEmployeeName();
            $month = month();
            $year = year();
            $json = array();
            $json['error'] = '';
            $user = $this->session->userdata('user');
            $file_types = array(
                '1' => 'application/vnd.ms-excel',
                '2' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            );

            require_once(APPPATH . 'third_party/PHPExcel.php');
            if (!in_array($_FILES['file']['type'], $file_types)) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $this->lang->line('EXPENSE_UPLOAD_FILE_ERROR') . '</div>';
                $this->session->set_flashdata('uploadError', $msg);
                $json['error'] = 'error';
                echo json_encode($json);
                die;
            }

            if ($user['VAT_TYPE'] == 'stand') {
                $sheet_one_column = array(
                    '0' => 'Date',
                    '1' => 'Category',
                    '2' => 'Amount',
                    '3' => 'VAT Paid',
                );
            } else {
                $sheet_one_column = array(
                    '0' => 'Date',
                    '1' => 'Category',
                    '2' => 'Amount'
                );
            }
            $sheet_two_column = array(
                '0' => 'MileageDate',
                '1' => 'From',
                '2' => 'To',
                '3' => 'Method',
                '4' => 'Purpose',
                '5' => 'Miles'
            );
            $error_flag = 0;
            $first_sheet = array();
            $second_sheet = array();
            $path = $_FILES['file']['tmp_name'];
            $inputFileType = PHPExcel_IOFactory::identify($path);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(TRUE);
            $objPHPExcel = $objReader->load($path);
            $total_sheets = $objPHPExcel->getSheetCount();
            $allSheetName = $objPHPExcel->getSheetNames('template');

            /* STEP - 1 Get First sheet data */
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(1);
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $flag = 0;

            for ($row = 1; $row <= $highestRow; ++$row) {
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                    if ($value == '') {
                        $flag +=1;
                    }
                    if ($user['VAT_TYPE'] == 'stand') {
                        if ($col <= 3) {
                            $arraydata[$row - 1][$col] = trim($value);
                        }
                    } else {
                        if ($col <= 2) {
                            $arraydata[$row - 1][$col] = trim($value);
                        }
                    }
                }
            }
            $first_sheet = $arraydata;

            /* Check if first sheet is valid or not */
            if (!isset($first_sheet[4])) {
                $error_flag = 1;
            } else {
                /* Now check the columns name */
                foreach ($first_sheet[4] as $key => $val) {
                    if (!in_array($val, $sheet_one_column)) {
                        $error_flag = 1;
                    }
                }
            }

            if ($error_flag == 1) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('EXPENSE_UPLOAD_PATTERN_MATCH_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('expenseError', $msg);
                $json['error'] = 'error';
                echo json_encode($json);
                exit;
            }

            $error_flag = 0;

            /* STEP - 2 Get second sheet data */
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(2);
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $flag = 0;
            unset($arraydata);
            for ($row = 1; $row <= $highestRow; ++$row) {
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    if ($value == '') {
                        $flag +=1;
                    }
                    $arraydata[$row - 1][$col] = $value;
                }
            }
            $second_sheet = $arraydata;
            if (!isset($second_sheet[0])) {
                $error_flag = 1;
            } else {
                foreach ($second_sheet[0] as $key => $val) {
                    if (!in_array($val, $sheet_two_column)) {
                        $error_flag = 1;
                    }
                }
            }
            if ($error_flag == 1) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('EXPENSE_UPLOAD_PATTERN_MATCH_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('expenseError', $msg);
                $json['error'] = 'error';
                echo json_encode($json);
                die;
            }

            /* Now check if both the templates have same column */
            if (count($first_sheet[4]) != count($sheet_one_column) &&
                    count($second_sheet[0]) != count($sheet_two_column)
            ) {
                $error_flag = 1;
            }

            if ($error_flag == 1) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('EXPENSE_UPLOAD_PATTERN_MATCH_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('expenseError', $msg);
                $json['error'] = 'error';
                echo json_encode($json);
                die;
            }

            /* Check pattern of both the sheets */
            if (count($first_sheet[4]) != count($sheet_one_column) &&
                    count($second_sheet[0]) != count($sheet_two_column)
            ) {
                $error_flag = 1;
            }


            $emplyee_name = $this->expense->getEmployeeName();
            $expense_year = array_search($first_sheet[1][2], $year);
            $employee_id = array_search($first_sheet[1][0], $emplyee_name);
            $item = array(
                'EmployeeID' => array_search($first_sheet[1][0], $emplyee_name),
                'Month' => array_search($first_sheet[1][1], $month),
                'Year' => array_search($first_sheet[1][2], $year),
                'ExpenseItems' => array_slice($first_sheet, 5),
                'ExpenseMileage' => array_slice($second_sheet, 1),
            );

            foreach ($item['ExpenseItems'] as $key => $val) {
                $temp = array(
                    'ID' => 0,
                    'Category' => array_search($val[1], $categoryList),
                    'ItemType' => 'EXPENSE',
                    'ItemDate' => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[0])),
                    'LocationFrom' => '',
                    'LocationTo' => '',
                    'Purpose' => '',
                    'Amount' => (float) $val[2],
                    'Miles' => 0
                );
                if ($user['VAT_TYPE'] == 'stand') {
                    $temp['VATAmount'] = (int) $val[3];
                }
                $item['ExpenseItems'][$key] = (object) $temp;
            }

            foreach ($item['ExpenseMileage'] as $key => $val) {
                $temp = array(
                    'ID' => 0,
                    'Category' => array_search($val[3], $methods),
                    'ItemType' => 'MILEAGE',
                    'ItemDate' => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[0])),
                    'LocationFrom' => $val[1],
                    'LocationTo' => $val[2],
                    'Purpose' => $val[4],
                    'Amount' => 0,
                    'Miles' => (int) $val[5]
                );
                $item['ExpenseMileage'][$key] = (object) $temp;
            }

            $item['ID'] = '';
            $item['Miles'] = $this->expense->getCarMiles($employee_id, $expense_year);
            $item['PaidOn'] = '';
            $item['ExpenseType'] = 'EXPENSES';
            $data['users'] = $this->expense->getEmployeeList('include');
            $data['task'] = 'addExpense';

            $date['mileage_cost'] = 0;
            $data['item'] = $item;
            $data['form_id'] = 'expenseForm';
            $data['form_link'] = site_url() . 'expenses_save';
            $json['html'] = $this->load->view('client/expenses/form', $data, true);

            /* Temporary store the file in upload folder */
            $path = 'assets/uploads/' . $_FILES['file']['name'];
            $target_file = 'assets/uploads/';
            if (file_exists($path)) {
                unlink($path);
            } else {
                $file_name = explode('.', $_FILES['file']['name']);
                $fcounter = $this->expense->getMaxFiles();
                $file_name = $file_name[0] . '-' . ($fcounter) . '.xls';
                $file_name = str_replace(' ', '_', $file_name);

                $config['upload_path'] = './assets/uploads/';
                $config['allowed_types'] = '*';
                $config['max_size'] = '1000';
                $config['max_width'] = '1024';
                $config['max_height'] = '768';
                $config['file_name'] = $file_name;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('file')) {
                    $error = $this->upload->display_errors();
                    $msg .= '<div class="alert alert-danger">';
                    $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('FILE_UPLOAD_ERROR') . ' ' . $error;
                    $msg .= '</div>';
                    $this->session->set_flashdata('uploadError', $msg);
                    $json['error'] = 'error';
                }
            }

            $_FILES['file']['name'] = $file_name;
            $files = json_encode($_FILES['file']);
            $this->session->set_userdata('expense_file', $files);
            echo json_encode($json);
            die;
        } else {
            $this->load->view('client/expenses/upload_expense', $data);
        }
    }

    /**
     * 	This Function generates the excel sheet of expense.
     */
    public function expenseTemplate() {
        $user = $this->session->userdata('user');
        $categories = $this->expense->getECategories();
        sort($categories);
        $cat_total = count($categories);

        $employees = $this->expense->getEmployeeList();
        $emp_total = count($employees);
        unset($employees[0]);
        $methods = $this->expense->getMethods('check');
        $met_total = count($methods);

        $month = month();
        $year = year('check');
        require_once(APPPATH . 'third_party/PHPExcel.php');

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('record');

        for ($x = 1; $x <= count($employees); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($x), $employees[$x]);
        }

        for ($x = 0; $x < count($categories); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('B' . ($x + 1), $categories[$x]);
        }

        for ($x = 0; $x < count($methods); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('C' . ($x + 1), $methods[$x]);
        }

        for ($x = 1; $x <= 12; $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('D' . ($x), $month[$x]);
        }

        for ($x = 1; $x <= 3; $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($x), $year[$x]);
        }

        // Create a new worksheet, after the default sheet
        $objPHPExcel->createSheet();

        // Add some data to the second sheet, resembling some different data types
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Employee');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Month');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Year');

        //$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A2:F2');
        $cellStyle = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10,
                'bold' => TRUE,
                'color' => array(
                    'rgb' => 'E80000'
                ),
            )
        );

        $setStyle = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 12,
                'bold' => TRUE,
                'color' => array(
                    'rgb' => 'FFFFFF'
                ),
            ),
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                ),
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                ),
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                ),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => '2685E1',
                ),
            ),
        );

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

        if ($user['VAT_TYPE'] == 'stand') {
            $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->applyFromArray($setStyle);
        } else {
            $objPHPExcel->getActiveSheet()->getStyle('A5:C5')->applyFromArray($setStyle);
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($setStyle);


        $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Date');


        $objPHPExcel->getActiveSheet()->setCellValue('B5', 'Category');


        $objPHPExcel->getActiveSheet()->setCellValue('C5', 'Amount');
        if ($user['VAT_TYPE'] == 'stand') {
            $objPHPExcel->getActiveSheet()->setCellValue('D5', 'VAT Paid');
        }

        $objPHPExcel->setActiveSheetIndex(1)->mergeCells('E1:N1');
        $objPHPExcel->setActiveSheetIndex(1)->mergeCells('E2:O2');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', EXPENSE_TEMPLATE_FIRST_TEXT);
        $objPHPExcel->getActiveSheet()->setCellValue('E2', EXPENSE_TEMPLATE_SECOND_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($cellStyle);
        $objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($cellStyle);
        /* Set employee drop-down list at A1 */
        $objValidation = $objPHPExcel->getActiveSheet()->getCell('A2')->getDataValidation();
        $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
        $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
        $objValidation->setAllowBlank(false);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);
        $objValidation->setShowDropDown(true);
        $objValidation->setFormula1('record!$A$1:$A$' . ($emp_total - 1));

        /* Set Month drop-down list at B1 */
        $objValidation = $objPHPExcel->getActiveSheet()->getCell('B2')->getDataValidation();
        $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
        $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
        $objValidation->setAllowBlank(false);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);
        $objValidation->setShowDropDown(true);
        $objValidation->setFormula1('record!$D$1:$D$' . (12));

        /* Set Year drop-down list at B1 */
        $objValidation = $objPHPExcel->getActiveSheet()->getCell('C2')->getDataValidation();
        $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
        $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
        $objValidation->setAllowBlank(false);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);
        $objValidation->setShowDropDown(true);
        $objValidation->setFormula1('record!$E$1:$E$' . (count($year) - 1));

        /* Set Month drop-down from 4th row at B4 */
        for ($x = 6; $x <= 100; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('B' . ($x))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('record!$B$1:$B$' . ($cat_total));
        }

        $objPHPExcel->getActiveSheet()->setTitle('ExpenseSheet');

        $objPHPExcel->createSheet();

        // Add some data to the second sheet, resembling some different data types
        $objPHPExcel->setActiveSheetIndex(2);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'MileageDate');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'From');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'To');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Method');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Purpose');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Miles');
        $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($setStyle);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        //$objPHPExcel->getActiveSheet()->setTitle('template');
        for ($x = 1; $x <= 100; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('D' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('record!$C$1:$C$' . ($met_total));
        }
        $objPHPExcel->getActiveSheet()->setTitle('MileageSheet');
        $objPHPExcel->getSheetByName('record')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
        $objPHPExcel->setActiveSheetIndex(1);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="expense_template.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        die;
    }

    /**
     * 	This Function generates the excel sheet of credit.
     */
    public function credit_template() {

        $categories = $this->expense->getECategories();
        sort($categories);
        $cat_total = count($categories);

        $employees = $this->expense->getEmployeeList();
        $emp_total = count($employees);
        unset($employees[0]);
        $methods = $this->expense->getMethods('check');
        $met_total = count($methods);

        $month = month();
        $year = year('check');
        require_once(APPPATH . 'third_party/PHPExcel.php');

        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('record');

        for ($x = 1; $x <= count($employees); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($x), $employees[$x]);
        }

        for ($x = 0; $x < count($categories); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('B' . ($x + 1), $categories[$x]);
        }

        for ($x = 0; $x < count($methods); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('C' . ($x + 1), $methods[$x]);
        }

        for ($x = 1; $x <= 12; $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('D' . ($x), $month[$x]);
        }

        for ($x = 1; $x <= 3; $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($x), $year[$x]);
        }

        // Create a new worksheet, after the default sheet
        $objPHPExcel->createSheet();

        // Add some data to the second sheet, resembling some different data types
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Employee');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Month');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Year');

        //$objPHPExcel->setActiveSheetIndex(1)->mergeCells('A2:F2');
        $cellStyle = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10,
                'bold' => TRUE,
                'color' => array(
                    'rgb' => 'E80000'
                ),
            )
        );

        $setStyle = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 12,
                'bold' => TRUE,
                'color' => array(
                    'rgb' => 'FFFFFF'
                ),
            ),
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                ),
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                ),
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => '000000'
                    )
                ),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => '2685E1',
                ),
            ),
        );

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);


        $objPHPExcel->getActiveSheet()->getStyle('A5:D5')->applyFromArray($setStyle);
        $objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($setStyle);


        $objPHPExcel->getActiveSheet()->setCellValue('A5', 'Date');
        $objPHPExcel->getActiveSheet()->setCellValue('B5', 'Category');
        $objPHPExcel->getActiveSheet()->setCellValue('C5', 'Amount');
        $objPHPExcel->getActiveSheet()->setCellValue('D5', 'VatAmount');

        /* Set employee drop-down list at A1 */
        $objValidation = $objPHPExcel->getActiveSheet()->getCell('A2')->getDataValidation();
        $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
        $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
        $objValidation->setAllowBlank(false);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);
        $objValidation->setShowDropDown(true);
        $objValidation->setFormula1('record!$A$1:$A$' . ($emp_total - 1));

        /* Set Month drop-down list at B1 */
        $objValidation = $objPHPExcel->getActiveSheet()->getCell('B2')->getDataValidation();
        $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
        $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
        $objValidation->setAllowBlank(false);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);
        $objValidation->setShowDropDown(true);
        $objValidation->setFormula1('record!$D$1:$D$' . (12));

        /* Set Year drop-down list at B1 */
        $objValidation = $objPHPExcel->getActiveSheet()->getCell('C2')->getDataValidation();
        $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
        $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
        $objValidation->setAllowBlank(false);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);
        $objValidation->setShowDropDown(true);
        $objValidation->setFormula1('record!$E$1:$E$' . (count($year) - 1));

        /* Set Category drop-down from 6th row at B4 */
        for ($x = 6; $x < 50; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('B' . ($x))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('record!$B$1:$B$' . ($cat_total));
        }
        $objPHPExcel->getActiveSheet()->setTitle('ExpenseSheet');

        $objPHPExcel->getSheetByName('record')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="credit_template.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        die;
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

    public function getCarMiles() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID'];
            $year = $_POST['Year'];
            $miles = $this->expense->getCarMiles($id, $year);
            $json['error'] = '';
            $json['miles'] = $miles;
            die(json_encode($json));
        } else {
            $json['error'] = 'error';
            die(json_encode($json));
        }
    }

    public function get_car_cost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ID'];
            $date = $_POST['Year'];
            $date = mDate($date);
            $car_miles = $_POST['miles'];
			
			/*$carMileageSession = $this->session->userdata('carMileageSession');
			if($carMileageSession == ''){
				$carMileageSession = array(
					"date" => $date,
					"car_miles" =>  $car_miles ,
				);
			}else{
				$newData = array(
					"date" => $date,
					"car_miles" =>  $car_miles
				);
				$carMileageSession =array_merge($this->session->userdata('carMileageSession'),$newData;
			}*/
			
			
			
            $miles = $this->expense->get_car_previous_miles($id, $date);
            $cost = $this->calMileage($miles, $car_miles, 0, 0, '');
            $json['error'] = '';
            $json['cost'] = $cost;
            die(json_encode($json));
        } else {
            $json['error'] = 'error';
            die(json_encode($json));
        }
    }

    /**
     * 	Function to process the content of credit template.
     */
    public function uploadCredit() {
        $data['title'] = "Dashboard | Upload Expenses";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            /* Check if Created by accountant while accessing the client account */
            $accountant_access = clientAccess();
            $methods = $this->expense->getMethods();
            $categoryList = $this->expense->getCategories();
            $emplyee_name = $this->expense->getEmployeeName();
            $month = month();
            $year = year();
            $json = array();
            $json['error'] = '';

            $file_types = array(
                '1' => 'application/vnd.ms-excel',
                '2' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            );

            require_once(APPPATH . 'third_party/PHPExcel.php');
            if (!in_array($_FILES['file']['type'], $file_types)) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $this->lang->line('EXPENSE_UPLOAD_FILE_ERROR') . '</div>';
                $this->session->set_flashdata('uploadError', $msg);
                $json['error'] = 'error';
                echo json_encode($json);
                die;
            }

            $sheet_one_column = array(
                '0' => 'Date',
                '1' => 'Category',
                '2' => 'Amount',
                '3' => 'VatAmount'
            );

            $error_flag = 0;
            $first_sheet = array();

            $path = $_FILES['file']['tmp_name'];
            $inputFileType = PHPExcel_IOFactory::identify($path);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);

            $objReader->setReadDataOnly(TRUE);
            $objPHPExcel = $objReader->load($path);
            $total_sheets = $objPHPExcel->getSheetCount();
            $allSheetName = $objPHPExcel->getSheetNames('template');

            /* STEP - 1 Get First sheet data */
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(1);
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            //pr($highestColumn);die;
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $flag = 0;

            for ($row = 1; $row <= $highestRow; ++$row) {
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                    if ($col <= 3) {
                        $arraydata[$row - 1][$col] = trim($value);
                    }
                }
            }
            $first_sheet = $arraydata;

            /* Check if first sheet is valid or not */
            if (!isset($first_sheet[4])) {
                $error_flag = 1;
            } else {
                /* Now check the columns name */
                foreach ($first_sheet[4] as $key => $val) {
                    if (!in_array($val, $sheet_one_column)) {
                        $error_flag = 1;
                    }
                }
            }

            if ($error_flag == 1) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('EXPENSE_UPLOAD_PATTERN_MATCH_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('expenseError', $msg);
                $json['error'] = 'error';
                echo json_encode($json);
                die;
            }

            /* Now check if both the templates have same column */
            if (count($first_sheet[4]) != count($sheet_one_column)) {
                $error_flag = 1;
            }

            if ($error_flag == 1) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('EXPENSE_UPLOAD_PATTERN_MATCH_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('expenseError', $msg);
                $json['error'] = 'error';
                echo json_encode($json);
                die;
            }

            /* Check if uploaded empty sheet */
            if (!isset($first_sheet[5])) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('EXPENSE_UPLOAD_EMPTY_CREDIT_STATEMENT');
                $msg .= '</div>';
                $this->session->set_flashdata('expenseError', $msg);
                $json['error'] = 'error';
                echo json_encode($json);
                die;
            }

            $emplyee_name = $this->expense->getEmployeeName();

            $item = array(
                'EmployeeID' => array_search($first_sheet[1][0], $emplyee_name),
                'Month' => array_search($first_sheet[1][1], $month),
                'Year' => array_search($first_sheet[1][2], $year),
                'ExpenseItems' => array_slice($first_sheet, 5),
                'ExpenseMileage' => array(),
                'TotalVATAmount' => 0
            );

            $total_vat_amt = 0;
            foreach ($item['ExpenseItems'] as $key => $val) {
                $category = trim($val[1]);
                $temp = array(
                    'ID' => 0,
                    'Category' => array_search($category, $categoryList),
                    'ItemType' => 'CREDITCARD',
                    'ItemDate' => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[0])),
                    'LocationFrom' => '',
                    'LocationTo' => '',
                    'Purpose' => '',
                    'Amount' => $val[2],
                    'Miles' => 0,
                    'VATAmount' => (float) $val[3]
                );
                $total_vat_amt += $val[3];
                $item['ExpenseItems'][$key] = (object) $temp;
            }
            $item['TotalVATAmount'] = $total_vat_amt;
            $item['ID'] = '';
            $item['PaidOn'] = '';
            $item['ExpenseType'] = 'CREDITCARD';
            $data['users'] = $this->expense->getEmployeeList('include');
            $data['task'] = 'addCreditCard';

            $date['mileage_cost'] = 0;
            $data['item'] = $item;
            $data['form_id'] = 'expenseForm';
            $data['form_link'] = site_url() . 'expenses_save';
            $json['html'] = $this->load->view('client/expenses/form', $data, true);

            /* Temporary store the file in upload folder */
            $path = 'assets/uploads/' . $_FILES['file']['name'];
            $target_file = 'assets/uploads/';
            if (file_exists($path)) {
                unlink($path);
            } else {
                $file_name = explode('.', $_FILES['file']['name']);
                $fcounter = $this->expense->getMaxFiles();
                $file_name = $file_name[0] . '-' . ($fcounter) . '.xls';
                $file_name = str_replace(' ', '_', $file_name);
                $config['upload_path'] = './assets/uploads/';
                $config['allowed_types'] = '*';
                $config['max_size'] = '1000';
                $config['max_width'] = '1024';
                $config['max_height'] = '768';
                $config['file_name'] = $file_name;

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('file')) {
                    $error = $this->upload->display_errors();
                    $msg .= '<div class="alert alert-danger">';
                    $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('FILE_UPLOAD_ERROR') . ' ' . $error;
                    $msg .= '</div>';
                    $this->session->set_flashdata('uploadError', $msg);
                    $json['error'] = 'error';
                }
            }

            $_FILES['file']['name'] = $file_name;
            $files = json_encode($_FILES['file']);
            $this->session->set_userdata('expense_file', $files);
            echo json_encode($json);

            die;
        } else {
            $this->load->view('client/expenses/upload_expense', $data);
        }
    }
	
	// Check vat is applicable or not by trialbalance category id.
	public function checkVatApplicable() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$id = $_POST['id'];		
			$vatApplicable = $this->expense->checkVatApplicable($id);
			$json['error'] = '';
			$json['vatApplicable'] = $vatApplicable;
			die(json_encode($json));
		} else {
			$json['error'] = 'error';
			die(json_encode($json));
		}
	}

}
