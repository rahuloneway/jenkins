<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//session_start();
class Journals extends CI_Controller {

    public function Journals() {
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
        $this->load->model('clients/journal');
        $this->load->model('clients/expense');
    }

    public function index() {
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data['page'] = "journals";
        $data['title'] = "Dashboard | Journals";             
        $TBYears = getTBYear();
        $TBYear = $TBYears[0]["value"];
        $data['items'] = $this->journal->newgetItems(JOURNAL_LISTING_PAGINATION_LIMIT, $page);
        $total = $this->journal->searchtotalEntries($TBYear);      
        $data['pagination'] = $this->getPagination(JOURNAL_LISTING_PAGINATION_LIMIT, $total); 
        $fin_yearexcel = $this->session->userdata('fin_yearexcel');
      /*  if(!empty($fin_yearexcel)){
            $data['items'] = $this->journal->newgetItems(JOURNAL_LISTING_PAGINATION_LIMIT, 0);
            $totals = $this->journal->searchtotalEntries($fin_yearexcel);       
            $data['paginationr'] = $this->getPagination(JOURNAL_LISTING_PAGINATION_LIMIT, $totals); 
             echo "<pre>"; print_r($data); echo "</pre>";
        }*/
		if($this->session->userdata('journalSearch')){
		$search = $this->session->userdata('journalSearch');
		$searchYeartotalA = $search['FinancialYear'];
		}else{
			$searchYeartotalA = $TBYear;
		}
		$data['items_amount'] = $this->journal->getItemsAmount($searchYeartotalA);
		//echo "<pre>"; print_r($_SESSION); echo "</pre>";
        $this->load->view('client/journals/default', $data);      	
		
    }

    public function getPagination($perPage = JOURNAL_LISTING_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */
        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'journals';
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

    public function forms() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = array();
            $task = $this->encrypt->decode($_POST['task']);
            if ($task == 'add_journal_entry') {
                $data['task'] = 'add_journal_entry';
                $json['html'] = $this->load->view('client/journals/form', $data, true);
            } elseif ($task == 'edit_journal_entry') {
                $data['task'] = 'edit_journal_entry';
                $json['html'] = $this->load->view('client/journals/form', $data, true);
            }
            echo json_encode($json);
        } else {
            show_404();
        }
    }

    /**
     * 	This function loads the expense form for adding the expense items.
     * 	It also loads the form for editing the expense items.
     */
    public function journaluploadForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['form_id'] = 'journalupform';
            $data['form_link'] = site_url() . 'client/journals/save';
            $this->load->view('client/journals/upload_journal', $data);
            echo '<script type="text/javascript" src="' . site_url() . 'assets/js/filestyle.js"></script>';
        } else {
            show_404();
        }
    }

    public function journaluploadSheet() {
        $data['title'] = "Dashboard | Upload Journals";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            /* Check if Created by accountant while accessing the client account */
            $accountant_access = clientAccess();
            $categoryList = $this->journal->getJournaluploadSheet_Category();
            $json = array();
            $json['error'] = '';
            $user = $this->session->userdata('user');
            require_once(APPPATH . 'third_party/PHPExcel.php');
            $file_extensions = array(
                '0' => '.csv',
                '1' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                '2' => 'application/vnd.ms-excel'
            );

            if (!in_array($_FILES['file']['type'], $file_extensions)) {
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('EXPENSE_UPLOAD_FILE_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('uploadError', $msg);
                $json['error'] = 'error';
                echo json_encode($json);
                die;
            }

            $sheet_one_column = array(
                '0' => 'Item',
                '1' => 'Type',
                '2' => 'Category',
                '3' => 'Narration',
                '4' => 'Amount'
            );

            $type_accs = array('DB' => 'DB', 'CR' => 'CR');
            // $TBDDYears = TBDropDownYears();
            // for ($i = $TBDDYears["end"]; $i >= $TBDDYears["start"]; $i--) {
                // $arrYear = TBListYearsDD($i);
                // $arrYears[$arrYear["value"]] = $arrYear["title"];
                // unset($arrYear);
            // }
					$filed_year = check_filed_account();
						if(empty($filed_year))
						{
							$TBDDYears = TBDropDownYears();
							for( $i=$TBDDYears["end"];$i >= $TBDDYears["start"]; $i-- )
							{
								$arrYear = TBListYearsDD( $i );
								$arrYears[$arrYear["value"]] = $arrYear["title"];								
								unset($arrYear);
							}
							
						}
						else
						{
							$filed_year = explode("/",$filed_year[0]["year"]);
							
							$TBDDYears = TBDropDownYears();
							
							for( $i=$TBDDYears["end"];$i >= $TBDDYears["start"]; $i-- )
							{
								$arrYear = TBListYearsDD( $i );
								if($arrYear["title"] > $filed_year[1])
								{
									$arrYears[$arrYear["value"]] = $arrYear["title"];									
									unset($arrYear);
								}
							}
							
						}
           //echo "<pre>"; print_r($arrYears); echo "</pre>";
            $count_one = count($sheet_one_column);
            $error_flag = 0;
            $first_sheet = array();
            $Fsheet = array();
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
            $maxCell = $objWorksheet->getHighestRowAndColumn();
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
                        if ($col <= 5) {
                            $arraydata[$row - 1][$col] = trim($value);
                        }
                    } else {
                        if ($col <= 5) {
                            $arraydata[$row - 1][$col] = trim($value);
                        }
                    }
                }
            }
            $first_sheet = $arraydata;

            /* Check if first sheet is valid or not */
            if (!isset($first_sheet[2])) {
                $error_flag = 1;
            } else {
                /* Now check the columns name */
                foreach ($first_sheet[2] as $key => $val) {
                    if (!in_array($val, $sheet_one_column)) {
                        $error_flag = 1;
                    }
                }
            }
            if ($error_flag == 1) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('JOURNAL_UPLOAD_PATTERN_MATCH_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('journalError', $msg);
                $json['error'] = 'error';
				
				echo json_encode($json);
                exit;
            }
			
			// if ($error_flag == 1) {
                // $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                // $msg .= $this->lang->line('JOURNAL_UPLOAD_PATTERN_MATCH_ERROR');
                // $msg .= '</div>';
                // $this->session->set_flashdata('journalError', $msg);
				// setRedirect('journals');
            // }

            /* Now check if both the templates have same column */
            if (count($first_sheet[2]) != count($sheet_one_column)) {
                $error_flag = 1;
            }

            // if ($error_flag == 1) {
                // $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                // $msg .= $this->lang->line('JOURNAL_UPLOAD_PATTERN_MATCH_ERROR');
                // $msg .= '</div>';
                // $this->session->set_flashdata('journalError', $msg);
                // $json['error'] = 'error';
                // echo json_encode($json);
                // die;
            // }
			
			// if ($error_flag == 1) {
                // $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                 // $msg .= $this->lang->line('JOURNAL_UPLOAD_PATTERN_MATCH_ERROR');
                // $msg .= '</div>';
                // $this->session->set_flashdata('journalError', $msg);
                // setRedirect('journals');
            // }

            $item = array(
                'JournalType' => array_slice($first_sheet, 3),
                'FinancialYear' => array_search($first_sheet[0][3], $arrYears)
            );

            foreach ($item['JournalType'] as $key => $val) {
                $category = trim($val[2]);
                $jtype = strtoupper($val[1]);
                $temp = array(
                    'JournalType' => array_search($jtype, $type_accs),
                    'Category' => array_search($category, $categoryList),
                    'Narration' => $val[3],
                    'Amount' => (float) $val[4]
                );
                $item['JournalType'][$key] = (object) $temp;
            }
            $data['item'] = $item;
            //$excelsheet_finayear = $data['item']['FinancialYear'];
            //$this->session->set_userdata('fin_yearexcel', $excelsheet_finayear);	
            $json['html'] = $this->load->view('client/journals/form', $data, true);
            /* Temporary store the file in upload folder */
            $path = 'assets/uploads/' . $_FILES['file']['name'];
            $target_file = 'assets/uploads/';
            if (file_exists($path)) {
                unlink($path);
            } else {
                $file_name = explode('.', $_FILES['file']['name']);
                $fcounter = $this->journal->getMaxFiles();
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
            $this->session->set_userdata('journal_file', $files);
            echo json_encode($json);
            die;
        } else {
            $this->load->view('client/journals/upload_journal', $data);
        }
    }

    /**
     * 	This Function generates the excel sheet of journal.
     */
    public function journalTemplate() {
        $user = $this->session->userdata('user');
        // $TBDDYears = TBDropDownYears();
        // for ($i = $TBDDYears["end"]; $i >= $TBDDYears["start"]; $i--) {
            // $arrYear = TBListYearsDD($i);
            // $arrYears[] = $arrYear["title"];
            // unset($arrYear);
        // }
					$filed_year = check_filed_account();
						if(empty($filed_year))
						{
							$TBDDYears = TBDropDownYears();
							for( $i=$TBDDYears["end"];$i >= $TBDDYears["start"]; $i-- )
							{
								$arrYear = TBListYearsDD( $i );
								//$arrYears[$arrYear["value"]] = $arrYear["title"];
								$arrYears[] = $arrYear["title"];
								unset($arrYear);
							}
							
						}
						else
						{
							$filed_year = explode("/",$filed_year[0]["year"]);
							
							$TBDDYears = TBDropDownYears();
							
							for( $i=$TBDDYears["end"];$i >= $TBDDYears["start"]; $i-- )
							{
								$arrYear = TBListYearsDD( $i );
								if($arrYear["title"] > $filed_year[1])
								{
									//$arrYears[$arrYear["value"]] = $arrYear["title"];
									$arrYears[] = $arrYear["title"];
									unset($arrYear);
								}
							}
							
							
						}
        $cat_total = count($arrYears);

        $account_type = array('0' => 'DB', '1' => 'CR');
        $categoryLiset = $this->journal->getJournaluploadSheet_Category_ExcelSheet();
        //sort($categoryLiset);
        $category_total = count($categoryLiset);
        /* foreach($categoryList as $label => $opt){ ?>
          <optgroup label="<?php echo $label; ?>">
          <?php foreach ($opt as $id => $name): ?>
          <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
          <?php endforeach; ?>
          </optgroup>
          <?php } */
        //echo "<pre>"; print_r($categoryLiset); echo "</pre>";		
        //die;
        //echo $category_total;
        //die;

        require_once(APPPATH . 'third_party/PHPExcel.php');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('record');

        for ($x = 0; $x < count($account_type); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('B' . ($x + 1), $account_type[$x]);
        }

        $categoryLiset = array_filter($categoryLiset);
        // foreach($categoryLiset as $key=>$val){
        // $objPHPExcel->getActiveSheet()->setCellValue('C' . ($key + 1), $val);	
        // //echo $key.'=='.$val.'<br>';
        // }

        for ($x = 2; $x <= count($categoryLiset); $x++) {
            if (!empty($categoryLiset[$x])) {
                $test = $categoryLiset[$x];
                $objPHPExcel->getActiveSheet()->setCellValue('C' . ($x - 1), $test);
            }
        }

        for ($x = 0; $x < count($arrYears); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('D' . ($x + 1), $arrYears[$x]);
        }

        // Create a new worksheet, after the default sheet
        $objPHPExcel->createSheet();

        // Add some data to the second sheet, resembling some different data types
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Company Accounting Year 31st Mar');

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
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);


        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($setStyle);


        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($setStyle);


        $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Item');

        $objPHPExcel->getActiveSheet()->setCellValue('B3', 'Type');

        $objPHPExcel->getActiveSheet()->setCellValue('C3', 'Category');

        $objPHPExcel->getActiveSheet()->setCellValue('D3', 'Narration');

        $objPHPExcel->getActiveSheet()->setCellValue('E3', 'Amount');

        /* Set Account Type drop-down list at B4 */
        for ($x = 3; $x <= 100; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('B' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('record!$B$1:$B$' . (2));
        }

        /* Set Year drop-down list at B1 */
        for ($x = 3; $x <= 1000; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('C' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('record!$C$1:$C$' . ($category_total));
        }
        /* Set Month drop-down from 4th row at B4 */
        for ($x = 0; $x <= 100; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('D1')->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('record!$D$1:$D$' . ($cat_total));
        }

        $objPHPExcel->getActiveSheet()->setTitle('JournalSheet');

        $objPHPExcel->createSheet();

        $objPHPExcel->getSheetByName('record')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
        $objPHPExcel->setActiveSheetIndex(1);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="journal_template.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        die;
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // echo "<pre>"; print_r($_POST); echo "</pre>";
            //die;
            $user = $this->session->userdata('user');
            $access = clientAccess();
            $financial_year = $_POST['journal_financialyear'];
            $journal_type = $_POST['Type'];
            $journal_category = $_POST['JournalCategories'];
            $journal_amount = $_POST['J_Amount'];
            $J_Narration = $_POST['J_Narration'];
            $AddedBy = $user['UserID'];
            $AddedOn = date('Y-m-d');
            $group_id = $this->journal->maxGroupID();
            foreach ($journal_amount as $key => $val) {
                $data[$key] = array(
                    'JournalType' => $journal_type[$key],
                    'FinancialYear' => $financial_year,
                    'Category' => $journal_category[$key],
                    'Amount' => $val,
                    'GroupID' => $group_id,
                    'Narration' => $J_Narration[$key],
                    'AddedBy' => $AddedBy,
                    'AddedOn' => $AddedOn,
                    'Status' => 0,
                    'AccountantAccess' => $access
                );
            }

            $response = $this->journal->save($data);
            if (!$response) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
                $msg .= '</div>';
                $this->session->set_flashdata('journalError', $msg);
            } else {

                /* Add the entries in trial balance table */
                $user = $this->session->userdata('user');
                if (isset($user["AccountantAccess"]) && !empty($user["AccountantAccess"])) {
                    $aAccess = $user["AccountantAccess"];
                } else {
                    $aAccess = $user["UserID"];
                }
                foreach ($journal_type as $key => $val) {
                    if ($val == 'DB') {
                        $TBentryId = store_trial_entry($journal_category[$key], $financial_year, $journal_amount[$key]);
                        if ((int) $TBentryId > 0) {
                            add_trial_details($TBentryId, "JOURNAL", $journal_category[$key], $aAccess, $group_id, $journal_amount[$key]);
                        }
                    } elseif ($val == 'CR') {
                        $TBentryId = store_trial_entry($journal_category[$key], $financial_year, $journal_amount[$key], "SUBTRACT");
                        if ((int) $TBentryId > 0) {
                            add_trial_details($TBentryId, "JOURNAL", $journal_category[$key], $aAccess, $group_id, $journal_amount[$key], "SUBTRACT");
                        }
                    }
                }
                $msg = '<div class="alert alert-success">';
                $msg .= '<i class="glyphicon glyphicon-ok"></i>';
                $msg .= $this->lang->line('JOURNAL_ADD_ENTRIES_SUCCESSFUL');
                $msg .= '</div>';
                $this->session->set_flashdata('journalError', $msg);
            }
            $this->session->set_userdata('fin_yearexcel', $financial_year);
            /* If no error then return true */
            setRedirect('journals');
        } else {
            show_404();
        }
    }

    public function search() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             $data['title'] = "Dashboard | Journals";
            $data['page'] = "journals";
            $year = $_POST['financialyear'];
            $total = $this->journal->searchtotalEntries($year); 
          //  echo '<pre>';print_r($total);echo '</pre>';die;
            $search = array(
                'FinancialYear' => safe($_POST['financialyear']),
                'totalCount' => $total
            );
            $this->session->set_userdata('journalSearch', $search);
            if($total > JOURNAL_LISTING_PAGINATION_LIMIT){
              $data['pagination'] = $this->getPagination('journals',JOURNAL_LISTING_PAGINATION_LIMIT, $total);  
           }
            //$this->load->view('client/journals/default', $data);
            setRedirect('journals');
            } else {
                show_404();
            }
        }

    public function clean() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->session->set_userdata('journalSearch', '');
            setRedirect('journals');            
        } else {
            show_404();
        }
    }

    public function change_category() {
        if ($this->input->is_ajax_request()) {
            $id = $_POST['ID'];
            $class = $_POST['DClass'];
            $categories = pl_categories('db_tb_sub_categories[]', '', $id, 'class="form-control ' . $class . '"');
            $json['categories'] = $categories;
            echo json_encode($json);
            die;
        } else {
            show_404();
        }
    }

}
