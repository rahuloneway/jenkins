<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Salary extends CI_Controller {

    public function Salary() {
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
        $this->load->model('clients/payroll');
    }

    public function index($arg = 0) {
        if (!is_numeric($arg) || !in_array($arg, array(0, 1))) {
            show_404();
        } else {
            $data['tab'] = $arg;
        }

        $data['title'] = 'Cashman | Salary';
        $data['employees'] = $this->payroll->employees('check');
        $data['page'] = 'salary';

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $items = $this->payroll->getItems(SALARY_PAGINATION_LIMIT, $page);
        $data['items'] = $items;
        $data['quarters'] = $this->quarters();
        $data['quarter_range'] = $this->payroll->quarterRange();
        if ($data['quarter_range'] == 'db_error') {
            $data['quarter_range'] = 4;
        }
        $payee = $this->payroll->IndexPagegetPayee();
        if ($payee == 'db_error') {
            $data['payee'] = '';
        } else {
            $data['payee'] = $this->payroll->IndexPagegetPayee();
        }

        $total = $this->payroll->totalEntries();
        $data['pagination'] = $this->getPagination(SALARY_PAGINATION_LIMIT, $total);
        //$this->session->set_userdata('AddPaye', '');
       // echo "<pre>"; print_r($data); echo "</pre>";
        //	die;
        $this->load->view('client/salary/default', $data);
    }

    public function getPagination($perPage = SALARY_PAGINATION_LIMIT, $totalItem = 0) {
        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'salary';
        $config['num_links'] = 2;
        $config['per_page'] = $perPage;
        $config['total_rows'] = $totalItem;
        $config['uri_segment'] = 4;
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
     * 	This function executes on Ajax call through reset button.
     * 	It shows the salary listing on the salary tab in payroll section.
     */
    public function ajax_listing() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $emp = safe($_POST['emp']);
            $fy = safe($_POST['fy']);
            $data = array(
                'EID' => $emp,
                'FinancialYear' => $fy
            );
            $this->session->set_userdata('SalarySearch', $data);

            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $items = $this->payroll->getItems(SALARY_PAGINATION_LIMIT, $page);
            $json['items'] = $items;
            $json['items'] = $this->load->view('client/salary/salary_listing', $json, true);
            $total = $this->payroll->totalEntries();
            /* $json['pagination'] = $this->getPagination(SALARY_PAGINATION_LIMIT,$total); */
            $json = json_encode($json);
            die($json);
        } else {
            show_404();
        }
    }

    /**
     * 	This function generates the Salary template.
     */
    public function template() {
        /* STEP - 1 : Get employees list */
        $employees = $this->payroll->employees();
        unset($employees[0]);
        $total_emp = count($employees);

        /* STEP - 2 : Get financial year list */
        $year = financial_year();
        unset($year[0]);
        $total_year = count($year);

        /* STEP - 3 : Generate the excel sheet */
        require_once(APPPATH . 'third_party/PHPExcel.php');
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0);
        //$objPHPExcel->getActiveSheet()->setCellValue('A1', 'EmployeeName');

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
        $objPHPExcel->getActiveSheet()->setTitle('employees');
        $x = 1;
        foreach ($employees as $key => $val) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($x), $val);
            $x++;
        }

        $x = 1;
        foreach ($year as $key => $val) {
            $objPHPExcel->getActiveSheet()->setCellValue('B' . ($x), $val);
            $x++;
        }

        // Create a new worksheet, after the default sheet
        $objPHPExcel->createSheet();

        // Add some data to the second sheet, resembling some different data types
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Financial Year');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Pay Period');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Total Payments');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Tax Deducted');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Employee NIC');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Net Pay');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Employer NIC');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Other Deduction');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($setStyle);
        $objPHPExcel->getActiveSheet()->setTitle('template');
        for ($x = 1; $x < 50; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('A' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('employees!$A$1:$A$' . ($total_emp));
        }


        for ($x = 1; $x < 50; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('B' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('employees!$B$1:$B$' . $total_year);
        }

        $objPHPExcel->getSheetByName('employees')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
        // Redirect output to a client?s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="salary.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        die;
    }

    /**
     * 	This function process the salary template.
     */
	 public function uploadOld() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->session->userdata('user');
            $AddedBy = $user['UserID'];
            $AddedOn = date('Y-m-d');

            /* Check if uploaded by admin */
            if (isset($user['AccountantAccess'])) {
                $access = $user['AccountantAccess'];
            } else {
                $access = 0;
            }
            require_once(APPPATH . 'third_party/PHPExcel.php');
            $file_extensions = array(
                '0' => '.csv',
                '1' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                '2' => 'application/vnd.ms-excel'
            );

            /* STEP - 1 Check if correct file is uploaded or not */
            if (!in_array($_FILES['file']['type'], $file_extensions)) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_FILE_UPLOAD_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect('salary');
            }

            $match_data = array(
                '0' => 'Name',
                '1' => 'Financial Year',
                '2' => 'Pay Date',
                '3' => 'Gross Salary',
                '4' => 'Income Tax',
                '5' => 'NIC Employee',
                '6' => 'Employer NIC',
                '7' => 'SMP',
                '8' => 'Net Pay',
            );

            $count_one = count($match_data);

            /* STEP - 2 Extract data from the excel sheet */
            $path = $_FILES['file']['tmp_name'];
            $inputFileType = PHPExcel_IOFactory::identify($path);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(TRUE);

            $objPHPExcel = $objReader->load($path);
            $total_sheets = $objPHPExcel->getSheetCount();
            $allSheetName = $objPHPExcel->getSheetNames('template');
            $objWorksheet = $objPHPExcel->setActiveSheetIndex();
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
                    $arraydata[$row - 1][$col] = $value;
                }
                $arraydata[$row - 1] = $arraydata[$row - 1];
            }

            $arraydata = array_filter($arraydata);

            /* Check the uploaded data */
            $uploaded_count = count($arraydata[0]);

            /* STEP - 3 Check if file and data is correct */
            if ($count_one > $uploaded_count) {
                $count_one = $count_one - 1;
            } else {
                $count_one = $count_one;
            }
            if ($uploaded_count != $count_one) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_UPLOAD_FILE_PATTERN_MISMATCH');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect('salary');
            }

            unset($arraydata[0]);
			echo "<pre>";print_r($arraydata);die('lol');
            if (count($arraydata) <= 0) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('BANK_UPLOAD_NO_RECORD');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect('salary');
            }

            /* STEP - 4 Upload the salary file to the server */
            $files = $_FILES['file'];
            $counter = $this->payroll->maxFiles();
            $counter++;
            $file_name = 'salary-' . cDate(date('Y-m-d')) . '-' . 'S-' . $counter . '.xls';
            $config['upload_path'] = './assets/uploads/';
            $config['allowed_types'] = '*';
            $config['max_size'] = '1000';
            $config['max_width'] = '1024';
            $config['max_height'] = '768';
            $config['file_name'] = $file_name;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $error = $this->upload->display_errors();
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= strip_tags($error);
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }

            /* STEP - 5 Save the file record in the database */
            $file_data = array(
                'FName' => $file_name,
                'FType' => $files['type'],
                'FSize' => $files['size'],
                'UploadedOn' => date('Y-m-d'),
                'UploadedBy' => $user['UserID'],
                'Type' => 'S',
                'AccountantAccess' => 0,
            );

            $file_id = $this->payroll->saveFile($file_data);
            if (empty($file_id)) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_FILE_INSERT_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }

            /* STEP - 5 Save the file data in the salary table */
            $file_data = array();
            $match_details = array();
            $invalid_data = array();
            $wrong_file = 0;
            foreach ($arraydata as $key => $val) {
                if (!empty($val[0]) && !empty($val[1]) && !empty($val[2])) {
                    $date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[2]));
                    if ($this->payroll->emlpoyeeID($val[0]) == 0) {
                        $wrong_file++;
                    }
                    $match_details[] = array(
                        'EID' => $this->payroll->emlpoyeeID($val[0]),
                        'CompanyID' => $user['CompanyID'],
                        'FinancialYear' => $val[1],
                        'PayDate' => $date,
                        'GrossSalary' => filterNumber($val[3]),
                        'IncomeTax' => filterNumber($val[4]),
                        'NIC_Employee' => filterNumber($val[5]),
                        'Employeer_NIC' => filterNumber($val[6]),
                        'SMP' => filterNumber($val[7]),
                        'NetPay' => filterNumber($val[8]),
                        'AddedBy' => $AddedBy,
                        'AddedOn' => $AddedOn,
                        'AccountantAccess' => $access,
                        'Status' => 0,
                        'Reconciled' => 0,
                        'FileID' => $file_id
                    );
                } else {
                    $invalid_data[] = $key + 1;
                }
            }
            /* Check if Wrong file is uploaded or not */
            if ($wrong_file > 0) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= sprintf($this->lang->line('SALARY_WRONG_STATEMENT'), implode(',', $invalid_data));
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }

            if (count($invalid_data) > 0) {
                $invalid_message = sprintf($this->lang->line('SALARY_INVALID_RECORD_AT_ROW'), implode(',', $invalid_data));
            } else {
                $invalid_message = '';
            }

            if (count($match_details) <= 0) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= sprintf($this->lang->line('SALARY_INAPROPIATE_DATA'), implode(',', $invalid_data));
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }

            /* Override the previous same entries */

            $salaries = $this->payroll->getSalaryStatements();
            $update_data = array();
            $insert_data = array();
            $temp_insert = array();
            if (count($salaries) > 0) {
                $flag = 0;

                foreach ($match_details as $key => $val) {
                    $u_flag = 0;
                    $i_flag = 0;
                    foreach ($salaries as $k => $v) {
                        if ($val['FinancialYear'] == $v['FinancialYear'] && $val['EID'] == $v['EID'] && strtotime($val['PayDate']) == strtotime($v['PayDate']) && $v['Status'] != 1) {
                            $val['ID'] = $v['ID'];

                            $u_flag = 1;
                        }

                        if ($val['FinancialYear'] == $v['FinancialYear'] && $val['EID'] == $v['EID'] && strtotime($val['PayDate']) == strtotime($v['PayDate'])) {
                            $i_flag = 1;
                        }
                    }
                    if ($u_flag == 1) {
                        $update_data[] = $val;
                    }
                    if ($i_flag != 1) {
                        $insert_data[] = $val;
                    }
                }

                /* UPDATE THE SALARIES */
                if (count($update_data)) {
                    $response = $this->payroll->updateEntries($update_data);
                    if (!$response) {
                        $msg = '<div class="alert alert-danger">';
                        $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                        $msg .= $this->lang->line('SALARY_UPDATE_ENTRIES') . $invalid_message;
                        $msg .= '</div>';
                        $this->session->set_flashdata('payUploadError', $msg);
                        if (count($insert_data) <= 0) {
                            setRedirect(site_url() . 'salary');
                        }
                    }
                }
                $match_details = $insert_data;
            }
            //echo "<pre>"; print_r($match_details); echo "</pre>";
            //die;

            if (count($match_details) <= 0) {
                $msg = '<div class="alert alert-success">';
                $msg .= '<i class="glyphicon glyphicon-ok"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_STATEMENT_UPDATE_SUCCESS') . $invalid_message;
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }
            $response = $this->payroll->saveEntries($match_details);
            if (!$response) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_FILE_RECORD_INSERT_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            } else {
                $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_ENTRIES_SAVE_SUCCESSFUL') . $invalid_message;
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }
        } else {
            show_404();
        }
    }
    public function upload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->session->userdata('user');
            $AddedBy = $user['UserID'];
            $AddedOn = date('Y-m-d');

            /* Check if uploaded by admin */
            if (isset($user['AccountantAccess'])) {
                $access = $user['AccountantAccess'];
            } else {
                $access = 0;
            }
            require_once(APPPATH . 'third_party/PHPExcel.php');
            $file_extensions = array(
                '0' => '.csv',
                '1' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                '2' => 'application/vnd.ms-excel'
            );

            /* STEP - 1 Check if correct file is uploaded or not */
            if (!in_array($_FILES['file']['type'], $file_extensions)) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_FILE_UPLOAD_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect('salary');
            }

			$match_data = array(
                '0' => 'Name',
                '1' => 'Financial Year',
                '2' => 'Pay Period',
                '3' => 'Total Payments',
                '4' => 'Tax Deducted',
                '5' => 'Employee NIC',
                '6' => 'SMP',
                '7' => 'Net Pay',
                '8' => 'Employer NIC',
                '9' => 'Other Deduction'
            );

            $count_one = count($match_data);

            /* STEP - 2 Extract data from the excel sheet */
            $path = $_FILES['file']['tmp_name'];
            $inputFileType = PHPExcel_IOFactory::identify($path);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(TRUE);

            $objPHPExcel = $objReader->load($path);
            $total_sheets = $objPHPExcel->getSheetCount();
            $allSheetName = $objPHPExcel->getSheetNames('template');
            $objWorksheet = $objPHPExcel->setActiveSheetIndex();
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $flag = 0;
			$financial_year = '';
			$empName = '';
			$nameRow = 6;
			$headingRow = 6;
            for ($row = 2; $row <= $highestRow; ++$row) { 
				$unsetRow = 0;
                for ($col = 0; $col < $highestColumnIndex; ++$col) 
				{			
					$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
					if($row == 2 && $col == 0)
					{
						if ($value != '') 
						{
							preg_match('/\d{4}-\d{2}$/', $value, $matches);
							$financial_year = $matches[0];
							$financial_year = explode('-',$financial_year);
							$firstTwoDigits = substr($financial_year[0],0,2);
							$financial_year = $financial_year[0].' / ' .$firstTwoDigits.''.$financial_year[1];
						}
					}
					if( $financial_year == '' )
					{ 
						$msg = '<div class="alert alert-danger">';
						$msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
						$msg .= $this->lang->line('SALARY_UPLOAD_FILE_FINANCIAL_YEAR_MISMATCH');
						$msg .= '</div>';
						$this->session->set_flashdata('payUploadError', $msg);
						setRedirect('salary');
					} 
					if( $row > 2 && $row < 6)
						continue;
					if( $row == $nameRow )
					{
						$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
						$empName =  $value;
						$nameRow = $nameRow + 16;
						$unsetRow = 1;
					}
					
                    $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
					if ($value == '') {
                        $flag +=1;
                    }
                    $arraydata[$row - 1][$col+2] = $value;                    
                }
				$arraydata[$row - 1]['0'] = $empName;
                $arraydata[$row - 1]['1'] = $financial_year;
				$arraydata[$row - 1] = $arraydata[$row - 1];
				if( ($row-1) == 2)
					unset($arraydata[$row-1]);
				if( ($row-1) == ($nameRow - 1) ||  ($row-1) == ($nameRow - 2)||  ($row-1) == ($nameRow - 3))
				{
					$unsetRow = 1;
				}
				if( ( $row - 1 ) == 1)
					unset($arraydata[$row-1]);
				$unsetHeading = ($nameRow-16);
				if( $unsetHeading > 0 && ($row-1) == $unsetHeading) 
					unset($arraydata[$row-1]);							
				if( $unsetRow == 1)
				{ 
					$headingRow = $headingRow + 16;
					unset($arraydata[$row-1]);
				}
            }
            $arraydata = array_filter(array_values($arraydata));
            //echo "<pre>";print_r($arraydata);//die;
			/* Check the uploaded data */
            $uploaded_count = count($arraydata[1]);
			
            /* STEP - 3 Check if file and data is correct */
            if ($count_one > $uploaded_count) {
                $count_one = $count_one - 1;
            } else {
                $count_one = $count_one;
            }
            if ($uploaded_count != $count_one) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_UPLOAD_FILE_PATTERN_MISMATCH');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect('salary');
            }
			//echo "<pre>";print_r($arraydata);die;
            if (count($arraydata) <= 0) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('BANK_UPLOAD_NO_RECORD');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect('salary');
            }

            /* STEP - 4 Upload the salary file to the server */
            $files = $_FILES['file'];
            $counter = $this->payroll->maxFiles();
            $counter++;
            $file_name = 'salary-' . cDate(date('Y-m-d')) . '-' . 'S-' . $counter . '.xls';
            $config['upload_path'] = './assets/uploads/';
            $config['allowed_types'] = '*';
            $config['max_size'] = '1000';
            $config['max_width'] = '1024';
            $config['max_height'] = '768';
            $config['file_name'] = $file_name;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $error = $this->upload->display_errors();
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= strip_tags($error);
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }

            /* STEP - 5 Save the file record in the database */
            $file_data = array(
                'FName' => $file_name,
                'FType' => $files['type'],
                'FSize' => $files['size'],
                'UploadedOn' => date('Y-m-d'),
                'UploadedBy' => $user['UserID'],
                'Type' => 'S',
                'AccountantAccess' => 0,
            );

            $file_id = $this->payroll->saveFile($file_data);
            if (empty($file_id)) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_FILE_INSERT_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }

            /* STEP - 5 Save the file data in the salary table */
            $file_data = array();
            $match_details = array();
            $invalid_data = array();
            $wrong_file = 0;
			$employeeIDD = '';
			
            foreach ($arraydata as $key => $val) { 	
				//echo "<pre>";print_r($val);	die('lol');
                if ( !empty($val[1]) && !empty($val[2])) {
                    $date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[2]));
                    if ($this->payroll->emlpoyeeID($val[0]) == 0 && $employeeIDD == '') {
                        $wrong_file++;
                    }
					else
					{ 
						if($val[0] != '' )
							$employeeIDD = $this->payroll->emlpoyeeID($val[0]);						
					}
                    $match_details[] = array(
                        'EID' => $employeeIDD,
                        'CompanyID' => $user['CompanyID'],
                        'FinancialYear' => $val[1],
                        'PayDate' => $date,
                        'GrossSalary' => filterNumber($val[3]),
                        'IncomeTax' => filterNumber($val[4]),
                        'NIC_Employee' => filterNumber($val[5]),
                        'Employeer_NIC' => filterNumber($val[7]),
                        'SMP' => filterNumber($val[8]),
                        'NetPay' => filterNumber($val[6]),
                        'AddedBy' => $AddedBy,
                        'AddedOn' => $AddedOn,
                        'AccountantAccess' => $access,
                        'Status' => 0,
                        'Reconciled' => 0,
                        'FileID' => $file_id
                    );
                } else {
                    $invalid_data[] = $key + 1;
                }
            } 
            /* Check if Wrong file is uploaded or not */
            if ($wrong_file > 0) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= sprintf($this->lang->line('SALARY_WRONG_STATEMENT'), implode(',', $invalid_data));
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }

            if (count($invalid_data) > 0) {
                $invalid_message = sprintf($this->lang->line('SALARY_INVALID_RECORD_AT_ROW'), implode(',', $invalid_data));
            } else {
                $invalid_message = '';
            }

            if (count($match_details) <= 0) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= sprintf($this->lang->line('SALARY_INAPROPIATE_DATA'), implode(',', $invalid_data));
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }

            /* Override the previous same entries */

            $salaries = $this->payroll->getSalaryStatements();
            $update_data = array();
            $insert_data = array();
            $temp_insert = array();
            if (count($salaries) > 0) {
                $flag = 0;

                foreach ($match_details as $key => $val) {
                    $u_flag = 0;
                    $i_flag = 0;
                    foreach ($salaries as $k => $v) {
                        if ($val['FinancialYear'] == $v['FinancialYear'] && $val['EID'] == $v['EID'] && strtotime($val['PayDate']) == strtotime($v['PayDate']) && $v['Status'] != 1) {
                            $val['ID'] = $v['ID'];

                            $u_flag = 1;
                        }

                        if ($val['FinancialYear'] == $v['FinancialYear'] && $val['EID'] == $v['EID'] && strtotime($val['PayDate']) == strtotime($v['PayDate'])) {
                            $i_flag = 1;
                        }
                    }
                    if ($u_flag == 1) {
                        $update_data[] = $val;
                    }
                    if ($i_flag != 1) {
                        $insert_data[] = $val;
                    }
                }

                /* UPDATE THE SALARIES */
                if (count($update_data)) {
                    $response = $this->payroll->updateEntries($update_data);
                    if (!$response) {
                        $msg = '<div class="alert alert-danger">';
                        $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                        $msg .= $this->lang->line('SALARY_UPDATE_ENTRIES') . $invalid_message;
                        $msg .= '</div>';
                        $this->session->set_flashdata('payUploadError', $msg);
                        if (count($insert_data) <= 0) {
                            setRedirect(site_url() . 'salary');
                        }
                    }
                }
                $match_details = $insert_data;
            }
            //echo "<pre>"; print_r($match_details); echo "</pre>";
            //die;

            if (count($match_details) <= 0) {
                $msg = '<div class="alert alert-success">';
                $msg .= '<i class="glyphicon glyphicon-ok"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_STATEMENT_UPDATE_SUCCESS') . $invalid_message;
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }
            $response = $this->payroll->saveEntries($match_details);
            if (!$response) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_FILE_RECORD_INSERT_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            } else {
                $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
                $msg .= $this->lang->line('SALARY_ENTRIES_SAVE_SUCCESSFUL') . $invalid_message;
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }
        } else {
            show_404();
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

    /**
     * 	This function saves the payee data in the database table cashman_payee.
     */
    public function save_payee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $year = $_POST['newpayeefinancialyear'];
            $quarters = $_POST['quarters'];
            $income_tax = $_POST['IncomeTax'];
            $NIC_Employees = $_POST['NIC_Employee'];
            $NIC_Employer = $_POST['NIC_Employer'];
            $total = $_POST['Total'];
            $PayeOfficeReference = $_POST['PayeOfficeReference'];
            $HMRC_Refunds = $_POST['HMRC_Refunds'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $user = $this->session->userdata('user');
            $AddedBy = $user['UserID'];
            $AddedOn = date('Y-m-d');
            if (isset($user['AccountantAccess'])) {
                $access = $user['AccountantAccess'];
            } else {
                $access = 0;
            }
            /* STEP - 1 Check if fields are empty */
            $PayeOfficeReference = array_filter($PayeOfficeReference);
            //echo '<pre>';print_r($income_tax);echo '</pre>';DIE;
            if (count($PayeOfficeReference) == 0) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('PAYEE_EMPTY_QUARTER_IBFO');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }
            //$total = count($income_tax);
            $detail = array();
            //for($x = 0; $x < $total;$x++)
            foreach ($income_tax as $x => $y) {
                $detail[] = array(
                    'CompanyID' => $user['CompanyID'],
                    'FinancialYear' => $year,
                    'Quarter' => $quarters[$x],
                    'IncomeTax' => $income_tax[$x],
                    'NIC_Employee' => $NIC_Employees[$x],
                    'NIC_Employer' => $NIC_Employer[$x],
                    'Total' => $total[$x],
                    'PayeeOfficeReference' => $PayeOfficeReference[$x],
                    'HMRC_Refunds' => $HMRC_Refunds[$x],
                    'StartDate' => mDate($start_date[$x]),
                    'EndDate' => mDate($end_date[$x]),
                    'Status' => 0,
                    'AddedBy' => $AddedBy,
                    'AddedOn' => $AddedOn,
                    'AccountantAccess' => $access
                );
            }

            //echo '<pre>';var_dump($detail);echo '</pre>';die;
            $response = $this->payroll->save_payee($detail);
            if ($response === 'db_error') {
                setRedirect(site_url() . 'salary');
            }
            if ($response) {
                $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
                $msg .= $this->lang->line('PAYEE_DETAIL_SAVE_SUCCESS');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                //$data['response'] = $response;
                $data['ts'] = 1;
                $this->session->set_userdata('AddPayese', $data);
                setRedirect(site_url() . 'salary');
                $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
                $msg .= $this->lang->line('PAYEE_DETAIL_SAVE_SUCCESS');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                // $this->load->view('client/salary/default',$data);
            } else {
                $msg = '<div class="alert alert-danger">';
                $msg .='<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                setRedirect(site_url() . 'salary');
            }
        } else {
            show_404();
        }
    }

    /**
     * 	This function loads the payee form for adding the payee detail.
     */
    public function payeeform() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task = $this->encrypt->decode('task');
            $data['title'] = 'Cashman | Add Payee';
            $year = $_POST['fsyear'];
            $data['quarters'] = $this->quarters($year);

            /* Check quarter range */
            $data['quarter_range'] = $this->payroll->quarterRange($year);
            $json['error'] = '';
            if ($data['quarter_range'] == 'db_error') {
                $json['error'] = 'error';
            }
			$curf = currentFinancialYear();
			$start_year = explode('/', $curf);
			$start_year = $start_year[0];
            $data['start_year'] = $start_year;
            $html = $this->load->view('client/salary/payee_form', $data, true);
            $json['html'] = $html;
            echo json_encode($json);
            die;
        } else {
            show_404();
        }
    }

    /**
     * 	This function executes when hitting the reset button.
     * 	It show the listing of the payee detail in payee liabilities.
     */
    function ajax_payee_listing() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $year = $_POST['year'];
            $start_year = explode('/', $year);
            $start_year = $start_year[0];
            $data['quarters'] = $this->quarters($year);
            /* Check quarter range */
            $data['quarter_range'] = $this->payroll->quarterRange($year);
            $json['error'] = '';
            if ($data['quarter_range'] == 'db_error') {
                $json['error'] = 'error';
            }
            $data['start_year'] = $start_year;
            $html = $this->load->view('client/salary/payee_listing', $data, true);
            $json['html'] = $html;
            echo json_encode($json);
            die;
        } else {
            show_404();
        }
    }

    /**
     * 	This function loads the html of payee listing detail.
     */
    public function payee_list() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $year = $_POST['year'];

            $data['quarters'] = $this->quarters($year);
            $payee = $this->payroll->getPayee($year);
            if ($payee == 'db_error') {
                $data['payee'] = '';
            } else {
                $data['payee'] = $payee;
            }
            $json['count'] = count($payee);
            $html = $this->load->view('client/salary/payee_list', $data, true);
            $json['html'] = $html;
            echo json_encode($json);
            die;
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
            //echo "<pre>"; print_r($task); echo "</pre>";
            //die;
            $task = explode('/', $task);
            if (isset($_POST['PaidDate'])) {
                $task['PaidDate'] = $_POST['PaidDate'];
            }
            /* Check if correct task is being executed */
            $actions = array(
                '1' => 'ACTION_DELETE',
                '2' => 'ACTION_PAID'
            );
            $json['error'] = '';
            $json['suc'] = '';
            if (!in_array($task[0], $actions)) {
                $json['error'] = 'error';
                $json['html'] = $this->lang->line('UNEXPECTED_ERROR_OCCURED');
            }
            $response = $this->payroll->performAction($task);
            //echo "<pre>"; print_r($response); echo "</pre>";
            //echo "<pre>"; print_r($task[0]); echo "</pre>";
            //die;
            if ($response) {
                if ($task[0] == 'ACTION_DELETE') {
                    $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
                    $msg .= $this->lang->line('PAYEE_QUARTER_DELETE_SUCCESS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('payUploadError', $msg);
                    $json['error'] = '';
                    $json['suc'] = 'del';
                    $json['id'] = $task[1];
                    $year = $_POST['PaidDate'];
                    $data['quarters'] = $this->quarters($year);
                    // $data['quarter_range'] 	= 	$this->payroll->quarterRange();
                    // if($data['quarter_range'] == 'db_error')
                    // {
                    // $data['quarter_range'] = 4;
                    // }
                    $data['payee'] = $this->payroll->getPayee($year);
                    $json['count'] = count($data['payee']);
                    $json['payee_filtervew'] = $this->load->view('client/salary/payee_list', $data, TRUE);
                } elseif ($task[0] == 'ACTION_PAID') {
                    $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
                    $msg .= $this->lang->line('PAYEE_QUARTER_PAID_SUCCESS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('payUploadError', $msg);
                    $json['error'] = '';
                    $json['suc'] = 'paid';
                    $json['id'] = $task[1];
                }
            } else {
                $json['error'] = 'error';
                $json['html'] = $this->lang->line('UNEXPECTED_ERROR_OCCURED');
            }
            echo json_encode($json);
            die;
        } else {
            show_404();
        }
    }

    public function payee_edit_form() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['ID'];
            $year = $_POST['year'];
            foreach ($ids as $key => $val) {
                $ids[$key] = $this->encrypt->decode($val);
            }
            $json['error'] = '';
            $response = $this->payroll->getPayeeItem($ids);
            if ($response) {
                $data['quarters'] = $this->quarters($year);
                $data['payee'] = $response;
                $json['html'] = $this->load->view('client/salary/payee_edit', $data, TRUE);
                $json['error'] = '';
            } else {
                $json['error'] = 'error';
                $json['html'] = $this->lang->line('UNEXPECTED_ERROR_OCCURED');
            }
            echo json_encode($json);
            die;
        } else {
            show_404();
        }
    }

    public function updatePayee() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ids = $_POST['ids'];
            $IncomeTax = $_POST['IncomeTax'];
            $NIC_Employee = $_POST['NIC_Employee'];
            $NIC_Employer = $_POST['NIC_Employer'];
            $Total = $_POST['Total'];
            $PayeOfficeReference = $_POST['PayeOfficeReference'];
            $HMRC_Refunds = $_POST['HMRC_Refunds'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            foreach ($ids as $key => $val) {
                $update_payee[] = array(
                    'ID' => $this->encrypt->decode($val),
                    'IncomeTax' => $IncomeTax[$key],
                    'NIC_Employee' => $NIC_Employee[$key],
                    'Total' => $Total[$key],
                    'PayeeOfficeReference' => $PayeOfficeReference[$key],
                    'HMRC_Refunds' => $HMRC_Refunds[$key],
                    'StartDate' => mDate($start_date[$key]),
                    'EndDate' => mDate($end_date[$key])
                );
            }
            //	echo '<pre>';print_r($update_payee);echo '</pre>';die;
            $json['error'] = '';
            $response = $this->payroll->updatePayeeItem($update_payee);
            if ($response) {
                $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
                $msg .= $this->lang->line('PAYEE_QUARTER_UPDATE_SUCCESS');
                $msg .= '</div>';
                $this->session->set_flashdata('payUploadError', $msg);
                $data['ts'] = 1;
                $this->session->set_userdata('AddPayese', $data);
                $json['error'] = '';
            } else {
                $json['error'] = 'error';
                $json['html'] = $this->lang->line('UNEXPECTED_ERROR_OCCURED');
            }
            echo json_encode($json);
            die;
        } else {
            show_404();
        }
    }

    public function salaryAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task = $this->encrypt->decode($_POST['task']);
            $task = explode('/', $task);
            if (isset($_POST['PaidDate'])) {
                $task['PaidDate'] = $_POST['PaidDate'];
            }
            /* Check if correct task is being executed */
            $actions = array(
                '1' => 'ACTION_DELETE',
                '2' => 'ACTION_PAID'
            );
            $json['error'] = '';
            if (!in_array($task[0], $actions)) {
                /*
                  $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>';
                  $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
                  $msg .= '</div>';
                  $this->session->set_flashdata('payUploadError',$msg);
                 */
                $json['error'] = 'error';
                $json['html'] = $this->lang->line('UNEXPECTED_ERROR_OCCURED');
            }
            $response = $this->payroll->salaryAction($task);
            if ($response) {
                if ($task[0] == 'ACTION_DELETE') {
                    $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
                    $msg .= $this->lang->line('SALARY_DELETE_SUCCESS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('payUploadError', $msg);
                    //$json['error']	=	'';
                    $json['sucdel'] = 'saldel';
                    $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
                    $items = $this->payroll->getItems(SALARY_PAGINATION_LIMIT, $page);
                    $data['items'] = $items;
                    $json['filtervew'] = $this->load->view('client/salary/salary_listing', $data, TRUE);
                } elseif ($task[0] == 'ACTION_PAID') {
                    $json['salsuc'] = 'salpaid';
                    $json['salid'] = $task[1];
                    // $data['ts'] = 1;
                    // $data['title']		=	'Cashman | Salary';
                    // $data['employees']	= 	$this->payroll->employees('check');
                    // $data['page']		=	'salary';
                    // $page 	= 	($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
                    // $items 	= 	$this->payroll->getItems(SALARY_PAGINATION_LIMIT,$page);
                    // $data['items']			= 	$items;
                    // $data['quarters'] 		= 	$this->quarters();
                    // $data['quarter_range'] 	= 	$this->payroll->quarterRange();
                    // if($data['quarter_range'] == 'db_error')
                    // {
                    // $data['quarter_range'] = 4;
                    // }
                    // $payee 	= 	$this->payroll->getPayee();
                    // if($payee == 'db_error')
                    // {
                    // $data['payee'] = '';
                    // }else{
                    // $data['payee'] = $this->payroll->getPayee();
                    // }
                    // $total 	= $this->payroll->totalEntries();
                    // $data['pagination'] = $this->getPagination(SALARY_PAGINATION_LIMIT,$total);
                    $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
                    $msg .= $this->lang->line('SALARY_PAID_SUCCESS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('payUploadError', $msg);
                    // $this->load->view('client/salary/default',$data);
                    $json['error'] = '';
                }
            } else {
                $json['error'] = 'error';
                $json['html'] = $this->lang->line('UNEXPECTED_ERROR_OCCURED');
            }
            echo json_encode($json);
            die;
        } else {
            show_404();
        }
    }

    public function salary_sorting() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $order = safe($this->encrypt->decode($_POST['order']));
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            if ($order == 'SORT_BY_PAID_DATE') {
                $des_order_value = array(
                    'SORT_BY_PAID_DATE' => 's.PayDate DESC'
                );
                $asc_order_value = array(
                    'SORT_BY_PAID_DATE' => 's.PayDate ASC'
                );
            } else {
                $des_order_value = array(
                    'SORT_BY_PAID_ON' => 's.PaidDate DESC'
                );
                $asc_order_value = array(
                    'SORT_BY_PAID_ON' => 's.PaidDate ASC'
                );
            }

            $prev_order = $this->session->userdata('SalarySortingOrder');
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

            $this->session->set_userdata('SalarySortingOrder', $order_value);
            $data['items'] = $this->payroll->getItems(SALARY_PAGINATION_LIMIT, $page);
            $d[0] = $this->load->view('client/salary/salary_listing', $data, true);
            $d[1] = $dir;
            echo json_encode($d);
            exit;
        } else {
            show_404();
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>