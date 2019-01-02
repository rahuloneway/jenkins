<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bulkupload extends CI_Controller {

    public function Bulkupload() {
        parent::__construct();
        checkUserAccess(array('TYPE_ACC'));

        /* Check if logged in user is Director or not */
        $user = $this->session->userdata('user');

        /*
          if(categoryName($user['UserParams']['EmploymentLevel']) != 'Director')
          {
          show_404();
          }
         */
        $this->load->model('accountant/cpanel');
        $this->load->model('clients/bank');
    }

    public function index() { 
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data['page'] = 'BulkUpload';
        $data['title'] = 'Cashmann | Bulk Upload';
        $data['annual_items'] = $this->cpanel->get_annual_items();
        $data['return_items'] = $this->cpanel->get_return_items();
        $data['vatdue_items'] = $this->cpanel->get_vatdue_items();
        $data['CompanyIdfromCompanyName'] = $this->cpanel->getCompanyIdfromCompanyName();
        $data['items'] = $this->cpanel->getItems(BANK_PAGINATION_LIMIT, $page, '');
        //$data['itesms'] = $this->cpanel->getLastBulkUploadAssociatedWithId();		
        //  $data['current_balance'] = $this->cpanel->get_current_balance();
        // $data['current_balance'] = $data['current_balance']['Balance'];
        $total = $this->cpanel->totalEntries();
        $data['pagination'] = $this->getPagination(BANK_PAGINATION_LIMIT, $total);
        //echo "<pre>"; print_r($total); echo "</pre>";
        $this->load->view('accountant/bulkupload/default', $data);
    }

    private function getPagination($perPage = BANK_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */
        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'bulkupload';
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

    public function executeFxn() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task = $this->encrypt->decode($this->input->post('task'));
            $this->$task();
        } else {
            show_404();
        }
    }

    public function markAccountsFiled() {
        $response = $this->cpanel->markAccountsFiled();
        $this->buildJSONResponse($response);
    }

    public function markReturnsFiled() {
        $response = $this->cpanel->markReturnsFiled();
        $this->buildJSONResponse($response);
    }

    public function buildJSONResponse($response) {
        $msg = "";
        if ($response["success"]) {
            if (count($response["success"]) > 0) {
                $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
                if (is_array($response["success"])) {
                    foreach ($response["success"] as $Smsg) {
                        $msg .= $Smsg . "<br/>";
                    }
                } else {
                    $msg .= $response["success"] . "<br/>";
                }
                $msg .= '</div>';
                $this->session->set_flashdata("dashboardErrors", $msg);
            }
            echo json_encode(array("success" => $msg));
        } else {
            if (count($response["error"]) > 0) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                if (is_array($response["success"])) {
                    foreach ($response["error"] as $err) {
                        $msg .= $err . "<br/>";
                    }
                } else {
                    $msg .= $response["error"] . "<br/>";
                }
                $msg .= '</div>';
                $this->session->set_flashdata("dashboardErrors", $msg);
            }
            echo json_encode(array("error" => $msg));
        }
        exit();
    }

    //New code Started at 16-10-2015 (Rav)

    public function form() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = array();
            $task = $this->encrypt->decode($_POST['task']);
            if (($task == 'createInvoice' || $task == 'createDividend') && $_POST['Others'] != 'bank_ajax_add') {
                $statements = $_SESSION['bank_statements'];
                $statements = json_decode($statements);
                $categories = array(
                    '0' => 75,
                    '1' => 78
                );
                $statement_date = $_POST['Date'];
                $statement_type = $_POST['Type'];
                $statement_desc = $_POST['Description'];
                $statement_mo = $_POST['MoneyOut'];
                $statement_mi = $_POST['MoneyIn'];
                $statement_bal = $_POST['Balance'];
                $statement_cat = $_POST['Category'];
                //pr($_POST);
                foreach ($statements as $key => $val) {
                    $statements[$key]->TransactionDate = mDate($statement_date[$key]);
                    $statements[$key]->Type = $statement_type[$key];
                    $statements[$key]->Description = $statement_desc[$key];
                    $statements[$key]->MoneyOut = $statement_mo[$key];
                    $statements[$key]->MoneyIn = $statement_mi[$key];
                    $statements[$key]->Balance = $statement_bal[$key];
                    $statements[$key]->Category = $statement_cat[$key];
                    if (!in_array($val->Category, $categories)) {
                        $statements[$key]->StatementType = '';
                    } else {
                        if ($val->Category == 75) {
                            $statements[$key]->StatementType = 'I';
                        } elseif ($val->Category == 78) {
                            $statements[$key]->StatementType = 'D';
                        }
                    }
                }
                //pr($statements);die;
                $statements = json_encode($statements);
                $_SESSION['bank_statements'] = $statements;
            } else {
                $data['statements'] = array();
                $json['html'] = $this->load->view('accountant/bulkupload/upload_form', $data, true);
                $json['script'] = '';
                $json['file'] = '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
                die(json_encode($json));
            }
        } else {
            show_404();
        }
    }

    public function upload() {

        $user = $this->session->userdata('user');
        $access = $user['UserID'];
        $data['title'] = 'bulkupload';
        $data['page'] = 'bulkupload';
        $this->session->set_userdata('temp_statement_record', '');
        $CompanyIdfromCompanyName = $this->cpanel->getCompanyIdfromCompanyName();
        $statement_type = array(
            'I' => 'Sales',
            'D' => 'Dividend'
        );

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once(APPPATH . 'third_party/PHPExcel.php');
            $bank_categories = $this->bank->getStatementCategories('statements');
            $file_extensions = array(
                '0' => '.csv',
                '1' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                '2' => 'application/vnd.ms-excel'
            );
            $max_filesize = 2000000;
            if (!in_array($_FILES['file']['type'], $file_extensions)) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('BANK_UPLOAD_FILE_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('bankError', $msg);
                setRedirect('bulkupload');
            }

            // else if (filesize($_FILES['file']['tmp_name']) > $max_filesize){
            // //die('File uploaded exceeds maximum upload size.');
            // $msg = '<div class="alert alert-danger">';
            // $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            // $msg .= $this->lang->line('BANK_UPLOAD_FILE_ERROR');
            // $msg .= '</div>';
            // $this->session->set_flashdata('bankError', $msg);
            // setRedirect('bulkupload');
            // }

            $template_one = array(
                '0' => 'COMP_NAME',
                '1' => 'EFF DATE',
                '2' => 'TRANS_DESCRIPTION',
                '3' => 'RECEIPTS',
                '4' => 'PAYMENTS',
                '5' => 'BALANCE',
                '6' => 'CATEGORY',
            );
            $template_two = array(
                '0' => 'Date',
                '1' => 'Type',
                '2' => 'Description',
                '3' => 'Money Out',
                '4' => 'Money In',
                '5' => 'Category',
                '6' => 'Balance'
            );

            $count_one = count($template_one);
            $count_two = count($template_two);

            $path = $_FILES['file']['tmp_name'];
            $inputFileType = PHPExcel_IOFactory::identify($path);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(TRUE);

            /**  Load $inputFileName to a PHPExcel Object  * */
            $objPHPExcel = $objReader->load($path);
            $total_sheets = $objPHPExcel->getSheetCount();
            $allSheetName = $objPHPExcel->getSheetNames('template');
            //$objPHPExcel->getActiveSheet();
            $objWorksheet = $objPHPExcel->setActiveSheetIndex(1);
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $flag = 0;

            $statement_data = array();
            for ($row = 1; $row <= $highestRow; ++$row) {
                for ($col = 0; $col < $highestColumnIndex; ++$col) {
                    $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                    $arraydata[$row - 1][$col] = $value;
                }

                $temp = $arraydata[$row - 1];

                /* Remove empty row from the statement */
                if (count($temp) > 0) {
                    $statement_data[] = $temp;
                }
            }

            $arraydata = array_filter($statement_data);
            //echo '<pre>';print_r($arraydata);echo '</pre>';die;
            $template_error = 0;

            /* Check which template is uploaded */
            if ($count_one == count($arraydata[0])) {
                $uploaded_template = 1;
            } elseif ($count_two == count($arraydata[0])) {
                $uploaded_template = 2;
            } else {
                $template_error = 1;
            }

            /* Check if uploaded template pattern is correct or not */
            if ($template_error) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('BANK_UPLOAD_PATTERN_MATCH_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('bankError', $msg);
                setRedirect('bulkupload');
            }

            /* Check the column names now */
            $error_flag = 0;
            if ($uploaded_template == 1) {
                foreach ($arraydata[0] as $key => $val) {
                    if (!in_array($val, $template_one)) {
                        $error_flag = 1;
                    }
                }
            } elseif ($uploaded_template == 2) {
                foreach ($arraydata[0] as $key => $val) {
                    if (!in_array($val, $template_two)) {
                        $error_flag = 1;
                    }
                }
            }

            if ($error_flag == 1) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('BANK_UPLOAD_PATTERN_MATCH_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('bankError', $msg);
                setRedirect('bulkupload');
            }

            unset($arraydata[0]);
            //echo '<pre>';print_r($arraydata);echo '</pre>';die;
            if (count($arraydata) <= 0) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $this->lang->line('BANK_UPLOAD_NO_RECORD') . '</div>';
                $this->session->set_flashdata('bankError', $msg);
                setRedirect('bulkupload');
            }

            $file_data = array();
            $match_details = array();
            foreach ($arraydata as $key => $val) {
                if ($uploaded_template == 1) {
                    $csv_companyname = trim($val[0]);
                    $csv_companyname = preg_replace('/\s+/', '', $csv_companyname);
                    $csv_companyname = strtolower($csv_companyname);
                    if (!empty($val[1])) {
                        $date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[1]));
                    } elseif (empty($val[1])) {
                        $date = date('Y-m-d');
                    } else {
                        $date = trim($val[1]);
                    }
                }
                $clientid = 0;
                foreach ($CompanyIdfromCompanyName as $key => $csval) {
                    $name = trim($csval['Name']);
                    $name = preg_replace('/\s+/', '', $name);
                    $name = strtolower($name);
                    if ($name == $csv_companyname) {
                        $clientid = $csval['ClientID'];
                        break;
                    }
                }

                if ($uploaded_template == 1) {
                    $match_details[] = array(
                        'CompnayName' => $val[0],
                        'TransactionDate' => $date,
                        // 'Description' => strtoupper(clean($val[5])),
                        'Description' => strtoupper($val[2]),
                        'MoneyOut' => (float) $val[3],
                        'MoneyIn' => (float) $val[4],
                        'Balance' => (float) $val[5],
                        'Category' => array_search(trim($val[6]), $bank_categories),
                        'AssociatedWith' => $clientid
                    );
                }
            }

            /* Check if filed years enteries are entered or not */
            $j_date = get_filed_year();

            $error_row = array();
            foreach ($match_details as $key => $val) {
                if (strtotime(trim($val['TransactionDate'])) < strtotime($j_date)) {
                    $error_row[] = $key + 1;
                }
            }

            if (count($error_row) > 0) {
                $error_row = implode(',', $error_row);
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= sprintf($this->lang->line('BANK_UPLOAD_FILED_YEAR_ENTRIES'), $error_row);
                $msg .= '</div>';
                $this->session->set_flashdata('bankError', $msg);
                //setRedirect('bank_statements');
            }

            $duplicate_entry = array();
            /* Check if statements already exists in the database */
            $row = 2;
            //pr($bank_categories);
            foreach ($match_details as $key => $val) {
                // $val['Category'] = (empty($val['Category']) ? 0 : $val['Category']);
                $val['Description'] = $val['Description'];
                $response = $this->cpanel->check_duplicate_entry($val);
                if ($response) {
                    $duplicate_entry[] = $key + $row;
                }
            }

            //echo '<pre>';print_r($duplicate_entry);echo '</pre>';die;
            //echo '<pre>';print_r($match_details);echo '</pre>';die;

            $duplicate_entry = implode(',', $duplicate_entry);
            if (!empty($duplicate_entry)) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= sprintf($this->lang->line('BANK_UPLOAD_DUPPLIACATE_ENTRY'), $duplicate_entry);
                $msg .= '</div>';
                $this->session->set_flashdata('bankError', $msg);
                setRedirect('bulkupload');
            }

            $files = $_FILES['file'];

            /* Save the bank Statement to the server folder */
            $counter = $this->cpanel->getMaxFiles();
            //$file_name = explode('.',$_FILES['file']['name']);
            $file_name = 'Statement-' . cDate(date('Y-m-d')) . '-' . 'Bulk-' . $counter . '.xls';
            //die($file_name);
            $config['upload_path'] = './assets/uploads/';
            $config['allowed_types'] = '*';
            $config['max_size'] = '1000';
            $config['max_width'] = '1024';
            $config['max_height'] = '768';
            $config['file_name'] = $file_name;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $error = $this->upload->display_errors();
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $error;
                $msg .= '</div>';
                $this->session->set_flashdata('bankError', $msg);
                //  setRedirect('dashboard');
            }

            /* Save the file records in the database */
            $file_data = array(
                'FName' => $file_name,
                'FType' => $files['type'],
                'FSize' => $files['size'],
                'UploadedOn' => date('Y-m-d'),
                'UploadedBy' => $user['UserID'],
                'Type' => 'B',
                'AccountantAccess' => $access,
            );

            $file_id = $this->cpanel->saveFile($file_data);
            if (empty($file_id)) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_FILE_UPLOAD_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('bankError', $msg);
                setRedirect('bulkupload');
            }

            foreach ($match_details as $key => $val) {
                $val['FileID'] = $file_id;
                $val['AddedBy'] = $user['UserID'];
                $val['AddedOn'] = date('Y-m-d');
                $val['AccountantAccess'] = $access;
                $match_details[$key] = $val;
            }

            //echo "<pre>";print_r($match_details); echo "</pre>";
            //die;
            /* Temporary Store the details in session */
            $details = json_encode($match_details);
            $_SESSION['bulk_bank_statements'] = $details;
            $this->session->set_userdata('statement_file_id', $file_id);
        } else {
            show_404();
        }
    }

    public function before_bulk_upload() {
        /* Get the statements from the session */
        $statements = $_SESSION['bulk_bank_statements'];
        $statements = json_decode($statements);
        //echo "<pre>"; print_r($statements); echo "<pre>";
        if (count($statements) <= 0) {
            setRedirect(site_url() . 'bulkupload');
        } else if (count($statements) > 5000) {
            $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            $msg .= $this->lang->line('UNEXPECTED_MAX_FILE_UPLOAD_ERROR');
            $msg .= '</div>';
            $this->session->set_flashdata('bankError', $msg);
            setRedirect(site_url() . 'bulkupload');
        } else {
            $data['page'] = "BulkUpload";
            $data['title'] = "Cashman | Bulk Uploads";
            $data['items'] = $statements;
            $this->load->view('accountant/bulkupload/bulk_statements_edit', $data);
        }
    }

    public function cancel() {
        $file_id = $this->session->userdata('statement_file_id');
        if (empty($file_id)) {
            show_404();
        }
        $ids = $this->cpanel->getFileStatements($file_id);
        $response = $this->cpanel->deleteFile($file_id);
        if (!$response) {
            $msg = '<div class="alert alert-danger">';
            $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            $msg .= $this->lang->line('BANK_STATEMENT_CANCEL_ERROR');
            $msg .= '</div>';
            $this->session->set_flashdata('bankError', $msg);
            setRedirect(site_url() . 'bulkupload');
        }

        $bank_statements = $_SESSION['bulk_bank_statements'];
        $bank_statements = json_decode($bank_statements);
        $item_ids = array();
        foreach ($bank_statements as $key => $val) {
            if ($val->AssociatedWith != 0) {
                $item_ids[] = array(
                    'ItemID' => $val->AssociatedWith,
                    'ItemType' => $val->StatementType
                );
            }
        }
        $response = $this->cpanel->delete_statement_record($item_ids);
        if (!$response) {
            $msg = '<div class="alert alert-danger">';
            $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            $msg .= $this->lang->line('BANK_STATEMENT_CANCEL_ERROR');
            $msg .= '</div>';
            $this->session->set_flashdata('bankError', $msg);
            setRedirect(site_url() . 'bulkupload');
        }
        $_SESSION['bulk_bank_statements'] = '';
        $this->session->set_userdata('statement_file_id', '');
        setRedirect(site_url() . 'bulkupload');
    }

    public function save_statements() {
        ini_set('max_execution_time', 1200);
        $bank_statements = $_SESSION['bulk_bank_statements'];

        if (empty($bank_statements)) {
            show_404();
        }

        $bank_statements = json_decode($bank_statements);


        $statement_date = $this->input->post('Date');
        $statement_type = $_POST['Type'];
        $statement_desc = $_POST['Description'];
        $statement_mo = $_POST['MoneyOut'];
        $statement_mi = $_POST['MoneyIn'];
        $statement_bal = $_POST['Balance'];
        $statement_cat = $_POST['Category'];

        $match_details = array();

        $item_ids = array();
        $check_balance = 0;

        foreach ($bank_statements as $key => $val) {
            //$match_details[] = get_object_vars($val);
            if ($key == 0) {
                $starting_balance = company_starting_balance(mDate($statement_date[$key]));
                $cc_balance = $this->bank->get_current_balance();
                $cc_balance = $cc_balance['Balance'];
                if ($cc_balance == 0) {
                    $check_balance = $starting_balance;
                } else {
                    $check_balance = $cc_balance + $statement_mi[$key] - $statement_mo[$key];
                }
            } else {
                $check_balance = 0;
            }
            $match_details[] = array(
                'TransactionDate' => mDate($statement_date[$key]),
                'Type' => $statement_type[$key],
                'Description' => $statement_desc[$key],
                'MoneyOut' => (float) $statement_mo[$key],
                'MoneyIn' => (float) $statement_mi[$key],
                'Balance' => (float) $statement_bal[$key],
                'Category' => (int) $statement_cat[$key],
                'StatementType' => '',
                'FileID' => $val->FileID,
                'AddedBy' => $val->AddedBy,
                'AddedOn' => $val->AddedOn,
                'AccountantAccess' => $val->AccountantAccess,
                'AssociatedWith' => $val->AssociatedWith,
                'CheckBalance' => $check_balance
            );
            if ($val->AssociatedWith != 0) {
                $item_ids[] = $val->AssociatedWith;
            }
        }
        $_SESSION['bulk_bank_statements'] = '';
        $this->session->set_userdata('temp_statement_record', '');
        $duplicate_entry = array();
        /* Check if statements already exists in the database */
        $row = 1;
        foreach ($match_details as $key => $val) {
            $val['Category'] = (empty($val['Category'])) ? 0 : $val['Category'];
            $response = $this->bank->check_duplicate_entry($val);
            if ($response) {
                $duplicate_entry[] = $key + $row;
            }
        }
        $duplicate_entry = implode(',', $duplicate_entry);
        if (!empty($duplicate_entry)) {
            $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            $msg .= sprintf($this->lang->line('BANK_UPLOAD_DUPPLIACATE_ENTRY'), $duplicate_entry);
            $msg .= '</div>';
            $this->session->set_flashdata('bankUploadError', $msg);
            echo '1';
        }

        /* STEP - 1 Save the bank statements */
        // $response = $this->bank->save_statements($match_details);


        $match_details = $this->bank->save_statements($match_details);

        if (!$match_details) {
            $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
            $msg .= '</div>';
            $this->session->set_flashdata('bankError', $msg);
            echo 1;
        } else {
            if (count($match_details) > 0) {
                foreach ($match_details as $SEntry) {
                    $catKey = get_expenses_category($SEntry['Category']);
                    if ($catKey && !empty($catKey["key"])) {
                        if (!empty($SEntry["MoneyOut"])) {
                            $amount = $SEntry["MoneyOut"];
                            $tType = "MONEY_OUT";
                        } else if (!empty($SEntry["MoneyIn"])) {
                            $amount = $SEntry["MoneyIn"];
                            $tType = "MONEY_IN";
                        } else {
                            $amount = 0;
                            $tType = "MONEY_IN";
                        }
                        $paidDate = $SEntry["TransactionDate"];
                        $id = $SEntry["id"];
                        $aAccess = '';
                        $companyId = '';


                        switch ($catKey["key"]) {
                            case "SALES":
                                if (isset($SEntry["AssociatedWith"]) && !empty($SEntry["AssociatedWith"])) {
                                    $aAccess = $SEntry["AssociatedWith"];
                                }
                                $companyId = $this->bank->getCompanyId($aAccess);
                                $invData = array("id" => $id, "PaidOn" => $paidDate, "InvoiceTotal" => $amount);
                                update_trial_balance("invoice", $invData, "BANK", $tType, '', $aAccess);
                                break;
                            case "EXPENSE_SUSPENSE":
                            case "CREDIT_CARD_SUSPENSE":
                                if (isset($SEntry["AssociatedWith"]) && !empty($SEntry["AssociatedWith"])) {
                                    $aAccess = $SEntry["AssociatedWith"];
                                }
                                $companyId = $this->bank->getCompanyId($aAccess);
                                $expData = array("id" => $id, "PaidOn" => $paidDate, "Amount" => $amount, "ExpenseType" => $catKey["key"]);
                                update_trial_balance("expense", $expData, "BANK", $tType, '', $aAccess);
                                break;
                            case "SALARY":
                                if (isset($SEntry["AssociatedWith"]) && !empty($SEntry["AssociatedWith"])) {
                                    $aAccess = $SEntry["AssociatedWith"];
                                }
                                $companyId = $this->bank->getCompanyId($aAccess);
                                $saldata = array("id" => $id, "PaidDate" => $paidDate, "GrossSalary" => $amount);
                                update_trial_balance("salary", $saldata, "BANK", $tType, '', $aAccess);
                                break;
                            case "PAYEE_CONTROL":
                                if (isset($SEntry["AssociatedWith"]) && !empty($SEntry["AssociatedWith"])) {
                                    $aAccess = $SEntry["AssociatedWith"];
                                }
                                $companyId = $this->bank->getCompanyId($aAccess);
                                $paydata = array("id" => $id, "PaidDate" => $paidDate, "Total" => $amount);
                                update_trial_balance("payee", $paydata, "BANK", $tType, '', $aAccess);
                                break;
                            case "VAT_CONTROL":
                                $vatdata = array("id" => $id, "PaidDate" => $paidDate, "Total" => $amount);
                                update_trial_balance("vat", $vatdata, "BANK", $tType, '', $aAccess);
                                break;
                            case "DIVIDEND":
                                if (isset($SEntry["AssociatedWith"]) && !empty($SEntry["AssociatedWith"])) {
                                    $aAccess = $SEntry["AssociatedWith"];
                                }
                                $companyId = $this->bank->getCompanyId($aAccess);
                                $divdata = array("id" => $id, "PaidDate" => $paidDate, "Total" => $amount);
                                update_trial_balance("dividend", $divdata, "BANK", $tType, '', $aAccess);
                                break;
                            default:
                                $tbSource = "BANK"; // Used for TB Details no relation to Any other variable used Above
                                if (isset($SEntry["AssociatedWith"]) && !empty($SEntry["AssociatedWith"])) {
                                    $aAccess = $SEntry["AssociatedWith"];
                                }
                                $companyId = $this->bank->getCompanyId($aAccess);
                                if ($tType == "MONEY_IN") {
                                    // "Cash at bank" goes down by "Total amount"
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                    $TBentryId = store_trial_entry($TBCatId, $paidDate, $amount, '', $aAccess, $companyId);
                                    if ((int) $TBentryId > 0) {
                                        add_trial_details($TBentryId, $tbSource, "CASH_AT_BANK", $aAccess, $id, $amount);
                                    }
                                    // "Same category" goes up by "Total amount" 
                                    $TBCatId = get_trial_balance_category($catKey["key"]); //get category ID for given key
                                    if ($TBCatId) {
                                        $TBentryId = store_trial_entry($TBCatId, $paidDate, $amount, "SUBTRACT", $aAccess, $companyId);
                                        if ((int) $TBentryId > 0) {
                                            add_trial_details($TBentryId, $tbSource, $catKey["key"], $aAccess, $id, $amount, "SUBTRACT");
                                        }
                                    }
                                } else if ($tType == "MONEY_OUT") {

                                    // "Cash at bank" goes down by "Total amount"
                                    $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                    $TBentryId = store_trial_entry($TBCatId, $paidDate, $amount, "SUBTRACT", $aAccess, $companyId);
                                    if ((int) $TBentryId > 0) {
                                        add_trial_details($TBentryId, $tbSource, "CASH_AT_BANK", $aAccess, $id, $amount, "SUBTRACT");
                                    }

                                    // "Same category" goes up by "Total amount" 
                                    $TBCatId = get_trial_balance_category($catKey["key"]); //get category ID for given key
                                    if ($TBCatId) {
                                        $TBentryId = store_trial_entry($TBCatId, $paidDate, $amount);
                                        if ((int) $TBentryId > 0) {
                                            add_trial_details($TBentryId, $tbSource, $catKey["key"], $aAccess, $id, $amount);
                                        }
                                    }
                                }

                                break;
                        }
                    }
                }
            }
        }
        $dividend_statement_id = '';
        $invoice_statement_id = '';

        /* STEP - 2 Get Statement IDS for Dividend IDS */
        $raw_record = array();
        $dividend_ids = array();
        $invoice_ids = array();
        if (count($item_ids) > 0) {
            $raw_record = $this->bank->get_statement_ids($item_ids);
            foreach ($raw_record as $key => $val) {
                if ($val->StatementType == 'D') {
                    $dividend_ids[] = array(
                        'DID' => $val->AssociatedWith,
                        'BankStatement' => $val->ID
                    );
                } elseif ($val->StatementType == 'I') {
                    $invoice_ids[] = array(
                        'InvoiceID' => $val->AssociatedWith,
                        'BankStatement' => $val->ID
                    );
                }
            }
        }

        /* Update Dividend Table */
        if (count($dividend_ids) > 0) {
            $response = $this->bank->updateStatements($dividend_ids, 'D');
            if (!$response) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
                $msg .= '</div>';
                $this->session->set_flashdata('bankError', $msg);
                setRedirect('bank_statements');
            }
        }
        //echo '<pre>';print_r($dividend_ids);echo '</pre>';
        //echo '<pre>';print_r($invoice_ids);echo '</pre>';die;

        /* Update Invoice Table */
        if (count($invoice_ids) > 0) {
            $response = $this->bank->updateStatements($invoice_ids, 'I');
            if (!$response) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
                $msg .= '</div>';
                $this->session->set_flashdata('bankError', $msg);
                setRedirect('bank_statements');
            }
        }

        /* If no error then return true */
        $msg = '<div class="alert alert-success">';
        $msg .= '<i class="glyphicon glyphicon-ok"></i>';
        $msg .= $this->lang->line('BANK_STATEMENTS_ADDED_SUCCESSFULLY');
        $msg .= '</div>';
        $this->session->set_flashdata('bankError', $msg);
        echo 1;
        //setRedirect('bulkupload');
    }

    public function search() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = array(
                'companyname' => safe($_POST['companyname']),
                'client_name' => safe($_POST['client_name']),
                'StartDate' => safe($_POST['StartDate']),
                'EndDate' => safe($_POST['EndDate']),
                'FinancialYear' => safe($_POST['FinancialYear'])
            );
            $this->session->set_userdata('Bulk_BankSearch', $search);
            setRedirect('bulkupload');
        } else {
            show_404();
        }
    }

    public function before_search() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search = array(
                'companyname' => safe($_POST['companyname'])
            );
            //$search = $_POST['companyname'];
            $this->session->set_userdata('Bulk_BeforeBankSearch', $search);

            setRedirect('bulkupload/before_bulk_upload');
        } else {
            show_404();
        }
    }

    public function clean() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $this->session->set_userdata('Bulk_BankSearch', '');
            //  $this->session->set_userdata('bankSearchRecords', '');
            $data['items'] = $this->cpanel->getItems(BANK_PAGINATION_LIMIT, 0);
            $json['html'] = $this->load->view('accountant/bulkupload/bulk_bank_listing', $data, TRUE);
            echo json_encode($json);
            exit;
        } else {
            show_404();
        }
    }

    public function delete_statements() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $ids = $_POST['cb'];
            foreach ($ids as $key => $val) {
                $ids[$key] = $this->encrypt->decode($val);
            }

            /* Added for P/L & B/S entries */
            $delDetails = $this->cpanel->getDeleteEntryDetails($ids);
            /* Added for P/L & B/S entries */

            $response = $this->cpanel->delete_statements($ids);
            if (!$response) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
                $msg .= '</div>';
                $this->session->set_flashdata('bankError', $msg);
                setRedirect(site_url() . 'bulkupload');
            } else {

                /* Added for P/L & B/S entries */
                if ($delDetails) {
                    foreach ($delDetails as $SEntry) {
                        if (!empty($SEntry["key"])) {

                            if (!empty($SEntry["MoneyOut"])) {
                                $amount = $SEntry["MoneyOut"];
                                $tType = "MONEY_OUT";
                            } else if (!empty($SEntry["MoneyIn"])) {
                                $amount = $SEntry["MoneyIn"];
                                $tType = "MONEY_IN";
                            } else {
                                $amount = 0;
                                $tType = "MONEY_IN";
                            }
                            $paidDate = $SEntry["TransactionDate"];
                            $id = $SEntry["id"];
                            switch ($SEntry["key"]) {

                                case "SALES":
                                    $invData = array("id" => $id, "PaidOn" => $paidDate, "InvoiceTotal" => $amount);
                                    update_trial_balance("invoice", $invData, "BANK_DEL", $tType);
                                    break;

                                case "EXPENSE_SUSPENSE":
                                case "CREDIT_CARD_SUSPENSE":
                                    $expData = array("id" => $id, "PaidOn" => $paidDate, "Amount" => $amount, "ExpenseType" => $SEntry["key"]);
                                    update_trial_balance("expense", $expData, "BANK_DEL", $tType);
                                    break;

                                case "SALARY":
                                    $saldata = array("id" => $id, "PaidDate" => $paidDate, "GrossSalary" => $amount);
                                    update_trial_balance("salary", $saldata, "BANK_DEL", $tType);
                                    break;

                                case "PAYEE_CONTROL":
                                    $paydata = array("id" => $id, "PaidDate" => $paidDate, "Total" => $amount);
                                    update_trial_balance("payee", $paydata, "BANK_DEL", $tType);
                                    break;

                                case "VAT_CONTROL":
                                    $vatdata = array("id" => $id, "PaidDate" => $paidDate, "Total" => $amount);
                                    update_trial_balance("vat", $vatdata, "BANK_DEL", $tType);
                                    break;

                                case "DIVIDEND":
                                    $divdata = array("id" => $id, "PaidDate" => $paidDate, "Total" => $amount);
                                    update_trial_balance("dividend", $divdata, "BANK_DEL", $tType);
                                    break;

                                default:

                                    $user = $this->session->userdata('user');
                                    if (isset($user["AccountantAccess"]) && !empty($user["AccountantAccess"])) {
                                        $aAccess = $user["AccountantAccess"];
                                    } else {
                                        $aAccess = $user["UserID"];
                                    }
                                    $tbSource = "BANK"; // Used for TB Details no relation to Any other variable used Above 

                                    if ($tType == "MONEY_IN") {
                                        // "Cash at bank" goes down by "Total amount"
                                        $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                        $TBentryId = store_trial_entry($TBCatId, $paidDate, $amount, "SUBTRACT");
                                        if ((int) $TBentryId > 0) {
                                            rm_trial_details($TBentryId, $tbSource, "CASH_AT_BANK", $id);
                                        }

                                        // "Same category" goes up by "Total amount" 
                                        $TBCatId = get_trial_balance_category($SEntry["key"]); //get category ID for given key
                                        if ($TBCatId) {
                                            $TBentryId = store_trial_entry($TBCatId, $paidDate, $amount);
                                            if ((int) $TBentryId > 0) {
                                                rm_trial_details($TBentryId, $tbSource, $SEntry["key"], $id);
                                            }
                                        }
                                    } else if ($tType == "MONEY_OUT") {
                                        // "Cash at bank" goes down by "Total amount"
                                        $TBCatId = get_trial_balance_category("CASH_AT_BANK"); //get category ID for given key
                                        $TBentryId = store_trial_entry($TBCatId, $paidDate, $amount);
                                        if ((int) $TBentryId > 0) {
                                            rm_trial_details($TBentryId, $tbSource, "CASH_AT_BANK", $id);
                                        }

                                        // "Same category" goes up by "Total amount" 
                                        $TBCatId = get_trial_balance_category($SEntry["key"]); //get category ID for given key
                                        if ($TBCatId) {
                                            $TBentryId = store_trial_entry($TBCatId, $paidDate, $amount, "SUBTRACT");
                                            if ((int) $TBentryId > 0) {
                                                rm_trial_details($TBentryId, $tbSource, $SEntry["key"], $id);
                                            }
                                        }
                                    }

                                    break;
                            }
                        }
                    }
                }
                /* Added for P/L & B/S entries */

                $msg = '<div class="alert alert-success">';
                $msg .= '<i class="glyphicon glyphicon-ok"></i>';
                $msg .= $this->lang->line('BANK_STATEMENT_DELETE_SUCCESS');
                $msg .= '</div>';
                $this->session->set_flashdata('bankError', $msg);
                setRedirect('bulkupload');
            }
        } else {
            show_404();
        }
    }

    /*
     * Download bulk statement template
     */

    public function bulk_template_download() {
        $this->load->model('clients/bank');
        $categories = $this->bank->getStatementCategories();
        sort($categories);
        require_once(APPPATH . 'third_party/PHPExcel.php');
        //require_once(APPPATH.'third_party/PHPExcel/Writer/Excel2007.php');
        $name = "Bulk_statement_(+/-)";
        $type = array(
            '1' => 'BAC',
            '2' => 'DPC',
            '3' => 'D/D',
            '4' => 'S/O',
        );

        $setStyle = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 10,
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

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('TransactionType');
        for ($x = 0; $x < count($categories); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($x + 1), $categories[$x]);
        }

        /* for ($x = 1; $x <= count($type); $x++) {
          $objPHPExcel->getActiveSheet()->setCellValue('B' . $x, $type[$x]);
          } */
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setTitle('Bulk Statement');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);


        $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->applyFromArray($setStyle);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'COMP_NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'EFF DATE');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'TRANS_DESCRIPTION');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'RECEIPTS');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'PAYMENTS');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'BALANCE');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'CATEGORY');



        for ($x = 1; $x < 5000; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('G' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('TransactionType!$A$1:$A$' . (count($categories)));
        }

        /* for ($x = 1; $x < 300; $x++) {
          $objValidation = $objPHPExcel->getActiveSheet()->getCell('B' . ($x + 1))->getDataValidation();
          $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
          $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
          $objValidation->setAllowBlank(false);
          $objValidation->setShowInputMessage(true);
          $objValidation->setShowErrorMessage(true);
          $objValidation->setShowDropDown(true);
          $objValidation->setFormula1('TransactionType!$B$1:$B$' . (count($type)));
          } */


        $objPHPExcel->getSheetByName('TransactionType')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $name . '.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

}
