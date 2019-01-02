<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bulkclient extends CI_Controller {

    public function Bulkclient() {
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
        $this->load->model('accountant/abulkclient');
        $this->load->model('accountant/account');
    }

    public function index() {
        // $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        // //$data['page'] = 'BulkClient';
       // // $data['title'] = 'Cashmann | Bulk Upload';
        // $data['annual_items'] = $this->cpanel->get_annual_items();
        // $data['return_items'] = $this->cpanel->get_return_items();
        // $data['vatdue_items'] = $this->cpanel->get_vatdue_items();
        // $data['CompanyIdfromCompanyName'] = $this->cpanel->getCompanyIdfromCompanyName();
        // $data['items'] = $this->cpanel->getItems(BANK_PAGINATION_LIMIT, $page, '');
        // //$data['itesms'] = $this->cpanel->getLastBulkUploadAssociatedWithId();		
        // //  $data['current_balance'] = $this->cpanel->get_current_balance();
        // // $data['current_balance'] = $data['current_balance']['Balance'];
        // $total = $this->cpanel->totalEntries();
        // $data['pagination'] = $this->getPagination(BANK_PAGINATION_LIMIT, $total);

        // //echo "<pre>"; print_r($total); echo "</pre>";
        // $this->load->view('accountant/bulkupload/default', $data);
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
			//echo "<pre>"; print_r($_POST); echo "</pre>";
            $json = array();
            $task = $this->encrypt->decode($_POST['task']);
            if ($task == 'uploadBulkClients') { 
            } else {				
                $data['moreclients'] = array();
                $json['html'] = $this->load->view('accountant/bulkclient/upload_client_form', $data, true);
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$VATQuarters = defaultVatQuatersArr();
		$VATType = array(
            '0' => 'Select Rate',
            '1' => 'flat',           
            '2' => 'stand'           
        );
        $title = array(
            '1' => 'Mr.',
            '2' => 'Mrs.',
            '3' => 'Miss',
            '4' => 'Dr.',
            '5' => 'Ms.',
            '6' => 'Prof.',
            '7' => 'Sir'
        );
		$is_director = array(
            '0' => 'No',
            '1' => 'Yes'           
        );
		$is_shareholder = array(
            '0' => 'No',
            '1' => 'Yes'           
        );
		$is_employee = array(
            '0' => 'No',
            '1' => 'Yes'           
        );
		
            require_once(APPPATH . 'third_party/PHPExcel.php');
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
                $this->session->set_flashdata('clientError', $msg);
                setRedirect('client_listing');
            }

            // else if (filesize($_FILES['file']['tmp_name']) > $max_filesize){
            // //die('File uploaded exceeds maximum upload size.');
            // $msg = '<div class="alert alert-danger">';
            // $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            // $msg .= $this->lang->line('BANK_UPLOAD_FILE_ERROR');
            // $msg .= '</div>';
            // $this->session->set_flashdata('clientError', $msg);
            // setRedirect('client_listing');
            // }

			$sheet_one_column = array(
			'0' => 'Title',
			'1' => 'First Name',
			'2' => 'Last Name',
			'3' => 'Email',
			'4' => 'Phone',
			'5' => 'NI Number',
			'6' => 'UTR',
			'7' => 'Address Line 1',
			'8' => 'Address Line 2',
			'9' => 'Address Line 3',
			'10' => 'Post Code',
			'11' => 'Is Director',
			'12' => 'Is Shareholder',
			'13' => 'Is Employee',               
            );
			$sheet_two_column = array(
			'0' => 'Company Name',
			'1' => 'Company Registration Number',
			'2' => 'Date of Incorporation',
			'3' => 'Year End Date',
			'4' => 'Return Date',
			'5' => 'Address 1',
			'6' => 'Address 2',
			'7' => 'Address 3',
			'8' => 'Postal Code',
			'9' => 'VAT Type',
			'10' => 'Quarters',
			'11' => 'Registration Number',
			'12' => 'Percentage',
			'13' => 'First Year Discount End Date',
			'14' => 'Name',
			'15' => 'Account Number',
			'16' => 'Sort Code',               
			'17' => 'Opening Balance'               
            );


           // $first_sheetcount_one = count($sheet_one_column);
			$error_flag = 0;
            $first_sheet = array();
            $second_sheet = array();
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

           // $first_sheet = array_filter($statement_data);
            $first_sheet = array_filter($statement_data);
			 /* Check if first sheet is valid or not */
            if (!isset($first_sheet[1])) {
                $error_flag = 1;
            } else {
                /* Now check the columns name */
                foreach ($first_sheet[1] as $key => $val) {
                    if (!in_array($val, $sheet_one_column)) {
                        $error_flag = 1;
                    }
                }
            }

            if ($error_flag == 1) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                 $msg .= $this->lang->line('BULK_CLIENT_UPLOAD_PATTERN_MATCH_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('clientError', $msg);
                setRedirect('client_listing');
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
            if (!isset($second_sheet[1])) {
				
                $error_flag = 1;
            } else {
				
                foreach ($second_sheet[1] as $key => $val) {
                    if (!in_array($val, $sheet_two_column)) {
                        $error_flag = 1;
                    }
                }
            }
			
			 //$this->kint->dump($data);
			//die;
            if ($error_flag == 1) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('BULK_CLIENT_UPLOAD_PATTERN_MATCH_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('clientError', $msg);
                 setRedirect('client_listing');
            }

            /* Now check if both the templates have same column */
            if (count($first_sheet[1]) != count($sheet_one_column) &&
                    count($second_sheet[1]) != count($sheet_two_column)
            ) {
                $error_flag = 1;
            }

            if ($error_flag == 1) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('BULK_CLIENT_UPLOAD_PATTERN_MATCH_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('clientError', $msg);
				
            }

            /* Check pattern of both the sheets */
            if (count($first_sheet[1]) != count($sheet_one_column) &&
                    count($second_sheet[1]) != count($sheet_two_column)
            ) {
                $error_flag = 1;
            }
		   
		   $item = array(
                'ClientDetails' => array_slice($first_sheet, 2),
                'CompanyDetails' => array_slice($second_sheet, 2),
                'Clienthead' => $first_sheet[0],
                'Companyhead' => $second_sheet[0]
            );
		   
		    if(empty($item['ClientDetails'])){
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
				$msg .= $this->lang->line('BULK_CLIENT_UPLOAD_NO_RECORD');
				$msg .= '</div>';
				$this->session->set_flashdata('clientError', $msg);
				setRedirect('client_listing');
			}			
		   
		    foreach ($item['ClientDetails'] as $key => $val) {				
                $temps = array(
                    'Title' => array_search($val[0], $title),
					'First_name' => $val[1],                      
					'Last_Name' => $val[2],
					'Email' => $val[3],
					'Phone' => $val[4],
					'NI_Number' => $val[5],
					'UTR' => $val[6],
					'Address1' => $val[7],
					'Address2' => $val[8],
					'Address3' => $val[9],
					'Post_code' => $val[10],					
					'is_director' => array_search($val[11], $is_director),
					'is_shareholder' => array_search($val[12], $is_shareholder),
					'is_employee' => array_search($val[13], $is_employee)
                );
                
                $item['ClientDetails'][$key] = (object) $temps;
            }
			
			//echo "<pre>"; print_r($temps);echo "</pre>" ;die;
			if(empty($item['CompanyDetails'])){
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
				$msg .= $this->lang->line('BULK_CLIENT_UPLOAD_NO_RECORD');
				$msg .= '</div>';
				$this->session->set_flashdata('clientError', $msg);
				setRedirect('client_listing');
			}
			
            foreach ($item['CompanyDetails'] as $key => $val) {
                $temps = array(
                    'Company_name' => $val[0],
                        'Company_reg_num' => $val[1],                      
                        'date_of_incop' => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[2])),
                        'year_date_end' => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[3])),
                        'returndate' => date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[4])),
                        'line1' => $val[5],
                        'line2' => $val[6],
                        'line3' => $val[7],
                        'post_codes' => $val[8],                        
                        'vat_type' => array_search($val[9], $VATType),
                        'quarter' => array_search($val[10], $VATQuarters),
                        'reg_number' => $val[11],
                        'percentage' => $val[12],                        
                        'first_year_dis' =>date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($val[13])),                        
                        'name' => $val[14],                        
                        'account_number' => $val[15],                        
                        'sort_code' => $val[16],     
                        'open_balance' => $val[17],     
                );
                $item['CompanyDetails'][$key] = (object) $temps;
            }	   
			
			//echo "<pre>"; print_r($item); die; 
			 /* Save the bank Statement to the server folder */
            $counter = $this->abulkclient->getMaxFiles();	
			
            //$file_name = explode('.',$_FILES['file']['name']);
            $file_name = 'Bulk-' . cDate(date('Y-m-d')) . '-' . 'Client-' . $counter . '.xls';
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
                $this->session->set_flashdata('clientError', $msg);
                $json['error'] = 'error';
            }

         

            $_FILES['file']['name'] = $file_name;
            $files = json_encode($_FILES['file']);
            $this->session->set_userdata('statement_file_id', $files);
            //echo json_encode($json);         
			$details = json_encode($item);		
			
            $_SESSION['bulk_client_data'] = $details;  
			if($_SESSION['bulk_client_data']!=''){
				setRedirect('accountant/bulkclients');
				//$data['item'] = $item;				
				//$json['html'] = $this->load->view('accountant/bulkclient/bulkclient_edit', $data, true);
			}			    
        } else {
            show_404();
        }
    }

    public function before_bulkclient() { 
        /* Get the statements from the session */
		$data['title'] = 'Bulk Client Upload';
        $statements = $_SESSION['bulk_client_data'];
        $statements = json_decode($statements);  
		$ClientDetails= count($statements->ClientDetails);		
		$CompanyDetails = count($statements->CompanyDetails);		
        if ($ClientDetails <= 0 || $CompanyDetails <= 0) {
            setRedirect(site_url() . 'client_listing');
        } 
		elseif ($ClientDetails > 100 ) { 
            $msg = '<div class="alert alert-danger">';
            $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            $msg .= $this->lang->line('BULK_CLIENT_UPLOAD_ERROR_CLIENT');
            $msg .= '</div>';
            $this->session->set_flashdata('clientError', $msg);
            setRedirect(site_url() . 'client_listing');
        } 
		elseif ($CompanyDetails > 100 ) {
            $msg = '<div class="alert alert-danger">';
            $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            $msg .= $this->lang->line('BULK_CLIENT_UPLOAD_ERROR_Company');
            $msg .= '</div>';
            $this->session->set_flashdata('clientError', $msg);
            setRedirect(site_url() . 'client_listing');
        }
        
        $data['item'] = $statements;
		//echo '<pre>';print_r($data);echo '</pre>'; die;
        $this->load->view('accountant/bulkclient/bulkclient_edit', $data);
    }

    public function cancel() {   
        $_SESSION['bulk_client_data'] = '';
        $this->session->set_userdata('statement_file_id', '');
        setRedirect(site_url() . 'client_listing');
    }

    public function save_statements() {      
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {       
			 $cleint_statements = $_SESSION['bulk_client_data'];
        if (empty($cleint_statements)) {
        
            show_404();
        }
		$cleint_statements  = (array) json_decode($cleint_statements);
		
		$user = $this->session->userdata('user');
		$AddedBy = $user['UserID'];
		$AddedOn = date('Y-m-d');
		$status = 1;
		$ClientSalutation = $this->input->post('ClientSalutation');
        $FirstName = $_POST['FirstName'];
        $LastName = $_POST['LastName'];
        $Email = $_POST['email'];
        $Phone = $_POST['phoneNo'];
        $NI_Number = $_POST['niNumber'];
        $UTR = $_POST['utr'];
        $Address1 = $_POST['addressOne'];
        $Address2 = $_POST['addressTwo'];
        $Address3 = $_POST['addressThree'];
        $Post_code = $_POST['postalCode'];
        $is_director = $_POST['Isdirector'];
        $is_shareholder = $_POST['Isshareholder'];
        $is_employee = $_POST['Isemployee'];
        $Company_name = $_POST['CompanyName'];
        $Company_reg_num = $_POST['CompanyRegisteredNo'];
        $date_of_incop = $_POST['IncorporationDate'];
        $year_date_end = $_POST['YearEndDate'];
        $returndate = $_POST['ReturnDate'];
        $line1 = $_POST['CompanyAddOne'];
        $line2 = $_POST['CompanyAddTwo'];
        $line3 = $_POST['CompanyAddThree'];
        $CompanyPostalCode = $_POST['CompanyPostalCode'];
        $VATRegisteredType = $_POST['VATRegisteredType'];
        $VATQuaters = $_POST['VATQuaters'];
        $VATRegisteredNo = $_POST['VATRegisteredNo'];
        $VATRatePercent = $_POST['VATRatePercent'];
        $VATEndDate = $_POST['VATEndDate'];
        $BankName = $_POST['BankName'];
        $account_number = $_POST['AccountNumber'];
        $sort_code = $_POST['ShortCode'];
        $OpenBalance = $_POST['OpeningBalance'];
        
		$match_details = array();
		
		foreach ($cleint_statements['ClientDetails'] as $key => $val) {
			 $client_params = array(
                'Salutation' => safe($ClientSalutation[$key]),              
                'NI_NUMBER' => safe($NI_Number[$key]),
                'UTR' => safe($UTR[$key]),
                'AddressTwo' => safe($Address2[$key]),
                'AddressThree' => safe($Address3[$key])
            );
            $match_details[] = array(
                'UserType' => 'TYPE_CLI',
                'FirstName' => $FirstName[$key],
                'LastName' => $LastName[$key],             
                'Email' => $Email[$key],             
                'ContactNo' => $Phone[$key],                          
                'Address' => $Address1[$key],
                'ZipCode' => $Post_code[$key],   
				'Params' => serialize($client_params),	              
				'AddedBy' => $AddedBy,
				'SubParent' => $user['AddedBy'],
				'AddedOn' => $AddedOn,
				'Status' => 0,							
				);
		}
		
            $accountant_access = 0;
            $record = array();		
            $record['USER'] = $match_details;
			/* BEfore OK PART */
			
			
		foreach ($cleint_statements['CompanyDetails'] as $key => $val) {
            /* Prepare Company array */
           	$company_params = array(
                 'REG_AddressOne' => safe($line1[$key]),
                'REG_AddressTwo' => safe($line2[$key]),
                'REG_AddressThree' => safe($line3[$key]),
                'REG_PostalCode' => safe($CompanyPostalCode[$key]),                
                'VATRegistrationNo' => safe($VATRegisteredNo[$key]),
                'VATQuaters' => $VATQuaters[$key],             
            );
			
            $company_detail[] = array(
                'ClientID' => '',
                'Name' => safe($Company_name[$key]),    
				'CompanyType' => 35,
                'RegistrationNo' => safe($Company_reg_num[$key]),               
                'IncorporationDate' => mDate($date_of_incop[$key]),
                'ReturnDate' => mDate($returndate[$key]),
                'EndDate' => mDate($year_date_end[$key]),             
                'Params' => serialize($company_params),
                'AddedBy' => $AddedBy,
                'AddedOn' => $AddedOn,
                'Status' => $status
            );
		}
            $record['COMPANY'] = $company_detail;
			
			foreach ($cleint_statements['ClientDetails'] as $key => $val) {
				if($is_employee[$key] == ""){
					$is_employee[$key]= 0;
				}
				if($is_director[$key] == ""){
					$is_director[$key]= 0;
				}
				if($is_shareholder[$key] == ""){
					$is_shareholder[$key]= 0;
				}
			//$share_employee = (isset($is_employee) ? $is_employee : array());
            /* Prepare Company's share holder data */

				/* Share Holder detail */
					$director_params = array(
						'Salutation' => safe($ClientSalutation[$key]),              
						'NI_Number' => safe($NI_Number[$key]),
						'UTR' => safe($UTR[$key]),
						'EmployementStartDate' => '',
						'AddressOne' => $Address1[$key],
						'AddressTwo' => $Address2[$key],
						'AddressThree' => $Address3[$key],  
						'PostalCode' => $Post_code[$key], 					
						'ContactNumber' => $Phone[$key],  					
					);
				
					$director_detail[] = array(
						'CompanyID' => '',
						'DesignationType' => 'D',
						'FirstName' => $FirstName[$key],
						'LastName' => $LastName[$key],             
						'Email' => $Email[$key],  
						//'TotalShares' => safe($_POST['DirectorShares']),						
						'IS_ShareHolder' => 1,
						'IS_Director' => 1,
						'IS_Employee' => 1,
						'Params' => serialize($director_params),
						'AddedOn' => $AddedOn,
						'AddedBy' => $AddedBy,
						'Status' => $status,
						'AccountantAccess' => $accountant_access,
					);
				}
				
            $record['DIRECTOR'] = $director_detail;
			
            //echo '------------- Director Detail ---------------';
         
        
            $share_holder_detail = array();
            foreach ($cleint_statements['ClientDetails'] as $key => $val) {
				if($is_shareholder[$key] == 1){					
				//if($val->is_shareholder == 1){
					$share_holder_params = array(
						'Salutation' => safe($share_salutation[$x]),
					   // 'DOB' => mDate($share_dob[$x]),
						'NI_Number' => safe($share_ni_number[$x]),
						'UTR' => safe($share_utr[$x]),
						'EmployementStartDate' => '',
						'AddressOne' => $Address1[$key],
						'AddressTwo' => $Address2[$key],
						'AddressThree' => $Address3[$key],  
						'ContactNumber' => $Phone[$key],
					   // 'Country' => safe($share_country[$x]),
					   'PostalCode' => $Post_code[$key], 
						'AddressOne' => $Address1[$key]						
					);
					$share_holder_detail[] = array(
						'CompanyID' => '',
						'DesignationType' => 'S',
						'FirstName' => $FirstName[$key],
						'LastName' => $LastName[$key],             
						'Email' => $Email[$key],  
						'IS_ShareHolder' => 1,
						 // 'TotalShares' => safe($share_total_shares[$x]),
						'Params' => serialize($share_holder_params),
						'AddedOn' => safe($AddedOn),
						'AddedBy' => safe($AddedBy),
						'Status' => $status,
						'AccountantAccess' => safe($accountant_access)
					);
				}
            }

            $record['SHAREHOLDER'] = $share_holder_detail;
			
		//	echo "<pre>"; print_r($is_employee); die;

            //echo '------------- Employees Detail ---------------';
            /* Prepare Employee Detail Data */
            $employee_detail = array();          
			foreach ($cleint_statements['ClientDetails'] as $key => $val) {
				if($is_employee[$key] == 1){					
				//if($val->is_employee == 1 ){					
						$employee_params = array(
							'Salutation' => 0,                    
							'NI_Number' => safe($NI_Number[$key]),
							'UTR' => '',                
							'AddressOne' => safe($Address1[$key]),
							'AddressTwo' => safe($Address2[$key]),
							'AddressThree' => safe($Address3[$key]),
							'PostalCode' => safe($Post_code[$key]),
							'ContactNumber' => safe($Phone[$key])
							//'Country' => $emp_country[$x]
						);
						$employee_detail[] = array(
							'CompanyID' => '',
							'DesignationType' => 'E',
							'FirstName' => $FirstName[$key],
							'LastName' => $LastName[$key],             
							'Email' => $Email[$key], 
							'TotalShares' => 0,
							'IS_Employee' => 1,
							'AddedBy' => $AddedBy,
							'AddedOn' => $AddedOn,
							'Params' => serialize($employee_params),
							'Status' => $status,
							'AccountantAccess' => $accountant_access
						);
				}	
            }
            $record['EMPLOYEE'] = $employee_detail;
			
			/* Check if client email is already registered or not */
           /* $response = $this->account->checkEmail($record['USER']['Email']);
            if ($response) {
                $msg = $this->lang->line('ACCOUNTANT_CLIENT_EMAIL_EXIST');
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                $this->session->set_flashdata('addError', $msg);
                setRedirect(site_url('client_listing'));
            }*/

			$response = $this->abulkclient->addClient($record['USER']);
			
		    foreach($response as $key=>$val){
			    $record['COMPANY'][$key]['ClientID'] = $val;
		    }		   
            if ($response) {
                $clientID = $response;               
            } 
			 //else {
                // $msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_CLIENT');
                // $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                // $this->session->set_flashdata('clientError', $msg);
                // setRedirect($_SERVER['HTTP_REFERER']);
                // $clientID = '';
            // }

        
            $response = $this->abulkclient->addCompany($record['COMPANY']);
			foreach($response as $key=>$val){
			    //$record['COMPANY'][$key]['ClientID'] = $val;
			    $record['DIRECTOR'][$key]['CompanyID'] = $val;
		    }			
            if ($response) {
                $companyID = $response;
            } else {
                $msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_COMPANY');
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                $this->session->set_flashdata('clientError', $msg);
                setRedirect(site_url('client_listing'));
                $companyID = '';
            }
			
            /* Prepare vat Registration Number */
			foreach ($cleint_statements['CompanyDetails'] as $key => $val) {
				//if (isset($_POST['VATRegistred'])) {
					$vat_entry = 1;
					$vat_details[] = array(
						'ClientID' => $clientID[$key],
						'Type' => safe($VATRegisteredType[$key]),
						'AddedBy' => $AddedBy,
						'AddedOn' => $AddedOn,
						'Status' => $status
					);
					if($VATRegisteredType[$key] == 'flat'){
						$PercentRate_afteryear = $VATRatePercent[$key];
						if(is_numeric($PercentRate_afteryear)){
							$afteryearvatRate = ($PercentRate_afteryear - 1);
						}
						if(is_float($PercentRate_afteryear)){
							$afteryearvatRate = ($PercentRate_afteryear - 1);
						}
						$EndDate = $VATEndDate[$key];
						$PercentRate = $VATRatePercent[$key];
						$vat_details[$key]['EndDate'] = safe(date('Y-m-d', strtotime($EndDate)));
						$vat_details[$key]['PercentRate'] = safe($PercentRate);
						$vat_details[$key]['PercentRateAfterEndDate'] = safe($afteryearvatRate);
					} else {
						 $vat_details[$key]['PercentRate'] = safe($VATRatePercent[$key]);
					}
				}
					if (count($vat_details) > 0) {
						$response = $this->abulkclient->addVAT($vat_details, 'single');
						if (!$response) {
							$msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_VAT');
							$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
							$this->session->set_flashdata('clientError', $msg);
							setRedirect(site_url('client_listing'));
						}
					}
					/* Insert the VAT Details in the database */
				//}
			
			
           // $record['DIRECTOR']['CompanyID'] = $companyID;
		  
            $response = $this->abulkclient->addCustomer($record['DIRECTOR'], 'single');
            if (!$response) {
                $msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_SHARE');
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                $this->session->set_flashdata('clientError', $msg);
                setRedirect(site_url('client_listing'));
            }
						
            if (count($record['SHAREHOLDER']) > 0) {
                foreach ($record['SHAREHOLDER'] as $key => $val) {
					$email = $val['Email'];
					$companyalId = $this->abulkclient->getEmployeeCompanyId($email);
					$record['SHAREHOLDER'][$key]['CompanyID'] = $companyalId[0]['CID'];
					//if($is_shareholder[$key] == 1){	
						//$record['SHAREHOLDER'][$key]['CompanyID'] = $companyID[$key];
					//}
                }

                $response = $this->abulkclient->addCustomer($record['SHAREHOLDER'], 'single');
                if (!$response) {
                    $msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_SHARE');
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                    $this->session->set_flashdata('clientError', $msg);
                    setRedirect(site_url('client_listing'));
                }
            }
			
		        if (count($record['EMPLOYEE']) > 0) {
					foreach ($record['EMPLOYEE'] as $key => $val) {
						$email = $val['Email'];
						$companyalId = $this->abulkclient->getEmployeeCompanyId($email);						
						$record['EMPLOYEE'][$key]['CompanyID'] = $companyalId[0]['CID'];
					}
					
					$response = $this->abulkclient->addCustomer($record['EMPLOYEE'], 'single');
					if (!$response) {
						$msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_EMPLOYEE');
						$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
						$this->session->set_flashdata('clientError', $msg);
						setRedirect(site_url('client_listing'));
					}
				}				
				//echo '------------- Bank Detail ---------------';
					/* Prepare the bank detail */
					foreach ($cleint_statements['CompanyDetails'] as $key => $val) {
						$bank_detail[] = array(
							'CompanyID' => '',
							'Name' => safe($BankName[$key]),
							'ShortCode' => safe($sort_code[$key]),
							'AccountNumber' => safe($account_number[$key]),
							'OpeningBalance' => safe($OpenBalance[$key]),
							'AddedOn' => $AddedOn,
							'AddedBy' => $AddedBy,
							'Status' => $status
						);
					}
				$record['BANK'] = $bank_detail;
							
				foreach ($record['BANK'] as $key => $val) {
					if ($record['BANK'][$key]['Name'] != '') {
						$record['BANK'][$key]['CompanyID'] = $companyID[$key];	
						calculateVatQuarters($clientID[$key]);	
					}
				}
			//	echo "<pre>"; print_r( $record['BANK']); echo "</pre>";
			//die;
				$response = $this->abulkclient->addBank($record['BANK']);
					if (!$response) {
						$msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_BANK');
						$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
						$this->session->set_flashdata('clientError', $msg);
						setRedirect(site_url('client_listing'));
					}
				
			
			
			//echo "<pre>"; print_r( $record); echo "</pre>";
			//die;
			
            /* VAT Calculation */
           
            $msg = $this->lang->line('ACCOUNTANT_ADD_CLIENT_SUCCESS');
            $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;' . $msg . '</div>';
            $this->session->set_flashdata('clientError', $msg);

            setRedirect(site_url('client_listing'));
        } else {
            show_404();
        }
   
    }
	
	public function bulkclientTemplate(){
	 //pr($categories);die;
        require_once(APPPATH . 'third_party/PHPExcel.php');
        //require_once(APPPATH.'third_party/PHPExcel/Writer/Excel2007.php');
        $name = "Bulk Client Upload";
        $title = array(
            '1' => 'Mr.',
            '2' => 'Mrs.',
            '3' => 'Miss',
            '4' => 'Dr.',
            '5' => 'Ms.',
            '6' => 'Prof.',
            '7' => 'Sir'
        );
		$isdirector = array(
            '0' => 'No',
            '1' => 'Yes'           
        );
		$isshareholder = array(
            '0' => 'No',
            '1' => 'Yes'           
        );
		$isemployee = array(
            '0' => 'No',
            '1' => 'Yes'           
        );
		
		$VATType = array(
            '0' => 'Select Rate',
            '1' => 'flat',           
            '2' => 'stand'           
        );
		
		$VATQuarters = defaultVatQuatersArr();
		
		
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
		
		for ($x = 1; $x < count($title); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($x - 0), $title[$x]);
        }
		for ($x = 0; $x < count($isdirector); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('L' . ($x + 1), $isdirector[$x]);
        }
		for ($x = 0; $x < count($isshareholder); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('M' . ($x + 1), $isshareholder[$x]);
        }
		for ($x = 0; $x < count($isemployee); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('N' . ($x + 1), $isemployee[$x]);
        }		
		for ($x = 1; $x < count($VATType); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('J' . ($x - 0), $VATType[$x]);
        }
		for ($x = 1; $x < count($VATQuarters); $x++) {
            $objPHPExcel->getActiveSheet()->setCellValue('K' . ($x - 0), $VATQuarters[$x]);
        }
		
		
        $objPHPExcel->createSheet();
		
        $objPHPExcel->setActiveSheetIndex(1);

		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Client Details');   
		
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);

        $objPHPExcel->getActiveSheet()->getStyle('A2:N2')->applyFromArray($setStyle);
		
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Title');
        $objPHPExcel->getActiveSheet()->setCellValue('B2', 'First Name');
        $objPHPExcel->getActiveSheet()->setCellValue('C2', 'Last Name');
        $objPHPExcel->getActiveSheet()->setCellValue('D2', 'Email');
        $objPHPExcel->getActiveSheet()->setCellValue('E2', 'Phone');
        $objPHPExcel->getActiveSheet()->setCellValue('F2', 'NI Number');
        $objPHPExcel->getActiveSheet()->setCellValue('G2', 'UTR');
        $objPHPExcel->getActiveSheet()->setCellValue('H2', 'Address Line 1');
        $objPHPExcel->getActiveSheet()->setCellValue('I2', 'Address Line 2');
        $objPHPExcel->getActiveSheet()->setCellValue('J2', 'Address Line 3');
        $objPHPExcel->getActiveSheet()->setCellValue('K2', 'Post Code');
        $objPHPExcel->getActiveSheet()->setCellValue('L2', 'Is Director');
        $objPHPExcel->getActiveSheet()->setCellValue('M2', 'Is Shareholder');
        $objPHPExcel->getActiveSheet()->setCellValue('N2', 'Is Employee');
		for ($x = 2; $x < 300; $x++) {
			if($x == 2)
			continue;
            $objPHPExcel->getActiveSheet()->getComment('E'.$x)->getText()->createTextRun('ONLY NUMERIC VALUES');
        }
		for ($x = 2; $x < 300; $x++) {
			if($x == 2)
			continue;
            $objPHPExcel->getActiveSheet()->getComment('F'.$x)->getText()->createTextRun($this->lang->line('ACCOUNTANT_BULK_NI_NUMBER'));
        }
		for ($x = 2; $x < 300; $x++) {
			if($x == 2)
			continue;
            $objPHPExcel->getActiveSheet()->getComment('G'.$x)->getText()->createTextRun($this->lang->line('ACCOUNTANT_BULK_UTR_NUMBER'));
        }
		for ($x = 2; $x < 300; $x++) {
			if($x == 2)
			continue;
            $objPHPExcel->getActiveSheet()->getComment('K'.$x)->getText()->createTextRun($this->lang->line('ACCOUNTANT_BULK_POSTAL_CODE'));
        }
	    for ($x = 2; $x < 300; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('A' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('TransactionType!$A$1:$A$' . (count($title)));
        }
		for ($x = 2; $x < 300; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('L' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('TransactionType!$L$1:$L$' . (count($isdirector)));
        }
		for ($x = 2; $x < 300; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('M' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('TransactionType!$M$1:$M$' . (count($isshareholder)));
        }
		for ($x = 2; $x < 300; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('N' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('TransactionType!$N$1:$N$' . (count($isemployee)));
        }
		
		$objPHPExcel->getActiveSheet()->setTitle('Client Details');
		
		// Add some data to the second sheet, resembling some different data types
        $objPHPExcel->createSheet();
		
        $objPHPExcel->setActiveSheetIndex(2);
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Company Details');        
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'VAT Details');        
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Bank Details');    		      
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'Company Name');
        $objPHPExcel->getActiveSheet()->setCellValue('B2', 'Company Registration Number');
        $objPHPExcel->getActiveSheet()->setCellValue('C2', 'Date of Incorporation');
        $objPHPExcel->getActiveSheet()->setCellValue('D2', 'Year End Date');
        $objPHPExcel->getActiveSheet()->setCellValue('E2', 'Return Date');
        $objPHPExcel->getActiveSheet()->setCellValue('F2', 'Address 1');
        $objPHPExcel->getActiveSheet()->setCellValue('G2', 'Address 2');
        $objPHPExcel->getActiveSheet()->setCellValue('H2', 'Address 3');
        $objPHPExcel->getActiveSheet()->setCellValue('I2', 'Postal Code');
        $objPHPExcel->getActiveSheet()->setCellValue('J2', 'VAT Type');
        $objPHPExcel->getActiveSheet()->setCellValue('K2', 'Quarters');
        $objPHPExcel->getActiveSheet()->setCellValue('L2', 'Registration Number');
        $objPHPExcel->getActiveSheet()->setCellValue('M2', 'Percentage');
        $objPHPExcel->getActiveSheet()->setCellValue('N2', 'First Year Discount End Date');
        $objPHPExcel->getActiveSheet()->setCellValue('O2', 'Name');
        $objPHPExcel->getActiveSheet()->setCellValue('P2', 'Account Number');
        $objPHPExcel->getActiveSheet()->setCellValue('Q2', 'Sort Code');
        $objPHPExcel->getActiveSheet()->setCellValue('R2', 'Opening Balance');
        $objPHPExcel->getActiveSheet()->getStyle('A2:R2')->applyFromArray($setStyle);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
		for ($x = 2; $x < 300; $x++) {
			if($x == 2)
			continue;
            $objPHPExcel->getActiveSheet()->getComment('B'.$x)->getText()->createTextRun($this->lang->line('ACCOUNTANT_BULK_COMPANY_REGISTRATION_NUMBER'));
        }
		for ($x = 2; $x < 300; $x++) {
			if($x == 2)
			continue;
            $objPHPExcel->getActiveSheet()->getComment('I'.$x)->getText()->createTextRun($this->lang->line('ACCOUNTANT_BULK_POSTAL_CODE'));
        }
		for ($x = 2; $x < 300; $x++) {
			if($x == 2)
			continue;
            $objPHPExcel->getActiveSheet()->getComment('L'.$x)->getText()->createTextRun($this->lang->line('ACCOUNTANT_BULK_VAT_REGISTRATION_NUMBER'));
        }
		for ($x = 2; $x < 300; $x++) {
			if($x == 2)
			continue;
            $objPHPExcel->getActiveSheet()->getComment('P'.$x)->getText()->createTextRun($this->lang->line('ACCOUNTANT_BULK_BANK_ACCOUNT_NUMBER'));
        }
		for ($x = 2; $x < 300; $x++) {
			if($x == 2)
			continue;
            $objPHPExcel->getActiveSheet()->getComment('Q'.$x)->getText()->createTextRun($this->lang->line('ACCOUNTANT_BULK_BANK_SHORT_CODE'));
        }		
		
		for ($x = 2; $x < 300; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('J' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('TransactionType!$J$1:$J$' . 2 );
        }
		
		for ($x = 2; $x < 300; $x++) {
            $objValidation = $objPHPExcel->getActiveSheet()->getCell('K' . ($x + 1))->getDataValidation();
            $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setFormula1('TransactionType!$K$1:$K$' . 3 );
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Company Details');		
        $objPHPExcel->getSheetByName('TransactionType')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $name . '.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    
    }	
}
