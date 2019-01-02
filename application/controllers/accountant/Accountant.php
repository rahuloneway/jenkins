<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accountant extends CI_Controller {

    public function Accountant() {
        parent::__construct();
        checkUserAccess(array('TYPE_ACC'));
        $this->load->model('accountant/account');
    }

    public function index() {
		
        $data['page'] = 'accountant_dashboard';
        $data['title'] = 'Accountant | Dashboard';
        $this->load->view('accountant/dashboard/default', $data);
    }

    /**
     * 	Function to load the view of client listing.
     */
    public function client() {		
		
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        if (!is_numeric($page)) {
            show_404();
        }
        $data['page'] = 'client_listing';
        $data['title'] = 'Accountant | Client';
		
		$this->session->set_userdata('lastAddedClientId','');
		$this->session->set_userdata('updateAbleCompanyId','');
		$this->session->set_userdata('addNewCompany','');

        $data['items'] = $this->account->getItems(CLIENT_LISTING_PAGINATION_LIMIT, $page);	
        $total = $this->account->totalItems();

        $data['pagination'] = $this->getPagination('client_listing', CLIENT_LISTING_PAGINATION_LIMIT, $total);
        $this->load->view('accountant/clients', $data);
    }

    public function item($id = NULL, $cID= NULL) {
        if ($id == NULL) {
            show_404();
        }
		
        $id = $this->encrypt->decode($id);
        $cID = $this->encrypt->decode($cID);
		
        if (!is_numeric($id)) {
            show_404();
        }
		
		if ($cID != NULL  && $this->session->userdata('addNewCompany') == '') {
			$this->session->set_userdata('updateAbleCompanyId',$cID);
		}
		
		
        $data['page'] = 'client_update';
        $data['title'] = 'Accountant | Client';
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		
		if( $this->session->userdata('addNewCompany') != '' && $this->session->userdata('updateAbleCompanyId') == ''){
			$item = $this->account->getClientDetail($id);	
		}else{			
			$item = $this->account->getItem($id);
		}
		
		if (count($item) <= 0) {
            $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            $msg .= $this->lang->line('ACCOUNTANT_NO_CLIENT_RECORD');
            $msg .= '</div>';
            $this->session->set_flashdata('clientError', $msg);
            setRedirect('client_listing');
        }
		$listComanies 		  = array();
		$allCompanies 		  = $this->account->getAllCompaniesByClientId($id,'CID,Name');
		if(!empty($allCompanies))
		{			
			foreach($allCompanies as $company)
			{
				$listComanies[$company->CID] = $company->Name;
			}
        }
		$data['allCompanies'] = $listComanies;
        $data['item'] 		  = $item;
		//echo "<pre>";print_r($data); echo "</pre>";//die('lol');
        $this->load->view('accountant/update_client', $data);
    }

    public function addClient() {
		
        $data['page'] = 'add_client';
        $data['title'] = 'Accountant | Client';
		if( $this->session->userdata('lastAddedClientId') != '' && $this->session->userdata('lastAddedClientId') > 0 ) 
			$item = $this->account->getClientDetail($this->session->userdata('lastAddedClientId'));	
		else
		{
			$prefix = $this->db->dbprefix;
			$temp_data = tableColumns($prefix . 'users');
			$client_params = array(
				'Salutation' => '',
				'DOB' => '',
				'NI_NUMBER' => '',
				'UTR' => '',
				'AddressTwo' => '',
				'AddressThree' => ''
			);			
			$temp_data->Params = $client_params;					
			$item['USER'] = $temp_data;
		}			
		$data['item'] = $item;
        $this->load->view('accountant/add_client', $data);
    }

    public function saveClient() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			
			//echo  "<pre>"; print_r($_POST); echo "</prE>";	
			
            $this->load->library('upload');

            /* Check if Company Logo is uploaded or not */
            $allowed_extension = array(
                '1' => 'image/jpeg',
                '2' => 'image/jpg',
                '3' => 'image/png'
            );
            if ($_FILES['file']['error'] == 0) {
                if (!in_array($_FILES['file']['type'], $allowed_extension)) {
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('ACCOUNTENT_WRONG_FILE_UPLOADED');
                    $msg .= "</div>";
                    $this->session->set_flashdata('addError', $msg);
                    setRedirect($_SERVER['HTTP_REFERER']);
                }

                if ($_FILES['file']['size'] > LOGO_UPLOAD_FILE_SIZE) {
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('ACCOUNTENT_FILE_SIZE_ERROR');
                    $msg .= "</div>";
                    $this->session->set_flashdata('addError', $msg);
                    setRedirect($_SERVER['HTTP_REFERER']);
                }
                $file_extension = explode('.', $_FILES['file']['name']);
                $file_extension = end($file_extension);
                $file_name = random_string('alnum', strlen($_FILES['file']['name']));
                $file_name = strtoupper(substr(safe($_POST['CompanyName']), 0, 3)) . '-' . $file_name . '.' . $file_extension;
                $config['upload_path'] = './assets/uploads/logos/';
                $config['allowed_types'] = 'jpeg|jpg|png';
                $config['max_size'] = '1000';
                $config['max_width'] = '1024';
                $config['max_height'] = '768';
                $config['file_name'] = $file_name;

                //$this->load->library('upload', $config);
				$this->upload->initialize($config);
                if (!$this->upload->do_upload('file')) {
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('ACCOUNTENT_FILE_UPLOAD_UPLOADED');
                    $msg .= "</div>";
                    $this->session->set_flashdata('addError', $msg);
                    setRedirect($_SERVER['HTTP_REFERER']);
                }
                $file_link = 'assets/uploads/logos/' . $file_name;
            } else {
                $file_link = '';
            }

            $accountant_access = 0;

            if (isset($_POST['sec'])) {
                $sendMail = 1;
            } else {
                $sendMail = 0;
            }

            $record = array();

            $user = $this->session->userdata('user');
            $AddedBy = $user['UserID'];
            $AddedOn = date('Y-m-d');
            $status = 1;

            $client_params = array(
                'Salutation' => safe($_POST['ClientSalutation']),
                'DOB' => mDate($_POST['DOB']),
                'NI_NUMBER' => safe($_POST['niNumber']),
                'UTR' => safe($_POST['utr']),
                'AddressTwo' => safe($_POST['addressTwo']),
                'AddressThree' => safe($_POST['addressThree'])
            );

            $client_detail = array(
                'UserType' => 'TYPE_CLI',
                'FirstName' => safe($_POST['FirstName']),
                'LastName' => safe($_POST['LastName']),
                'Address' => safe($_POST['addressOne']),
                'ZipCode' => safe($_POST['postalCode']),
                'ContactNo' => safe($_POST['phoneNo']),
                'Email' => safe($_POST['email']),
                'Country' => safe($_POST['Country']),
                'Params' => serialize($client_params),
                'AddedBy' => $AddedBy,
                'SubParent' => $user['AddedBy'],
                'AddedOn' => $AddedOn,
                'Status' => 0,
                'Relation_with' => safe($_POST['relationship_manager'])
                //'AccountantAccess' =>	$accountant_access,
            );

            $record['USER'] = $client_detail;

            /* Prepare Company array */
            $company_params = array(
                'REG_AddressOne' => safe($_POST['CompanyAddOne']),
                'REG_AddressTwo' => safe($_POST['CompanyAddTwo']),
                'REG_AddressThree' => safe($_POST['CompanyAddThree']),
                'REG_PostalCode' => safe($_POST['CompanyPostalCode']),
                'REG_Country' => safe($_POST['CompanyCountry']),
                'REG_PhoneNo' => safe($_POST['CompanyPhoneNo']),
                'CON_AddressOne' => safe($_POST['CCAddressOne']),
                'CON_AddressTwo' => safe($_POST['CCAddressTwo']),
                'CON_AddressThree' => safe($_POST['CCAddressThree']),
                'CON_PostalCode' => safe($_POST['CCpostalcode']),
                'CON_Country' => safe($_POST['CCompanyCountry']),
                'CON_PhoneNo' => safe($_POST['CCPhoneNo']),
                'CompanySIDate' => safe($_POST['CompanySIDate']),
                'CompanyMonthlyFee' => safe($_POST['CompanyMonthlyFee']),
                'VATRegistrationNo' => safe($_POST['VATRegisteredNo']),
                'VATQuaters' => safe($_POST['VATQuaters']),
                'CompanyShares' => safe($_POST['TotalShares']),
                'LogoLink' => $file_link
            );
			
			$company_params['isCISRegistered'] = isset($_POST['isCISRegistered']) ? safe($_POST['isCISRegistered']) : '';
            $company_params['cis_percentage'] = isset($_POST['cis_percentage']) ? safe($_POST['cis_percentage']) : '';
			
			$company_detail = array(
				'ClientID' => '',
				'Name' => safe($_POST['CompanyName']),
				'TradingName' => safe($_POST['TradingName']),
				'Description' => safe($_POST['BussinessDescription']),
				'AnnualAmount' => safe($_POST['ExpectedAmount']),
				'CompanyType' => safe($_POST['CompanyType']),
				'PayeReference' => safe($_POST['PayeReference']),
				'PayeAcountReference' => safe($_POST['PayeAccountReference']),
				'RegistrationNo' => safe($_POST['CompanyRegisteredNo']),
				'TaxReference' => safe($_POST['TaxReference']),
				'IncorporationDate' => mDate($_POST['IncorporationDate']),
				'ReturnDate' => mDate($_POST['ReturnDate']),
				'EndDate' => mDate($_POST['YearEndDate']),
				'FaxNumber' => safe($_POST['FaxNo']),
				'Email' => safe($_POST['CompanyEmail']),
				'Website' => safe($_POST['CompanyWebsite']),
				'Params' => serialize($company_params),
				'AddedBy' => $AddedBy,
				'AddedOn' => $AddedOn,
				'Status' => $status
			);
			
			$record['COMPANY'] = $company_detail;
            $share_employee = (isset($_POST['IsEmployee']) ? $_POST['IsEmployee'] : array());  
			$share_director = (isset($_POST['IsDirector']) ? $_POST['IsDirector'] : array());			
			$share_holder   = (isset($_POST['IsShareholder']) ? $_POST['IsShareholder'] : array());		
			
			//****************** befor add set employee and share holder cagegory ******************
			#count all shareholder category 
			$countShareholderCategory = count(getAllShareholderCategory());
			#count all employees category 
			$countEmployeeCategory = count(getAllemployeesCategory());
			#count all both category 
			$countBothCategory = count(getAllBothCategory());
			
			$countShareHolder = 0;
			$countEmployee = 0;
			$countBoth = 0;
						
			/* Prepare Company's share holder data */
            /* Share Holder detail */
            $director_params = array(
                'Salutation' => safe($_POST['directorSalutation']),
                'DOB' => mDate($_POST['Ddob']),
                'NI_Number' => safe($_POST['DNINumber']),
                'UTR' => safe($_POST['DUTR']),
                'EmployementStartDate' => '',
                'AddressOne' => safe($_POST['DAddressOne']),
                'AddressTwo' => safe($_POST['DAddressTwo']),
                'AddressThree' => safe($_POST['DAddressThree']),
                'PostalCode' => safe($_POST['DPostalCode']),
                'ContactNumber' => safe($_POST['DPhoneNo']),
                'Country' => safe($_POST['DCountry'])
            );
            $director_detail = array(
                'CompanyID' => '',
                'DesignationType' => 'D',
                'FirstName' => safe($_POST['DFirstName']),
                'LastName' => safe($_POST['DLastName']),
                'TotalShares' => safe($_POST['DirectorShares']),
                'Email' => safe($_POST['DEmail']),
                'IS_ShareHolder' => (isset($share_holder[1])) ? 1 : 0,
                'IS_Director' => (isset($share_director[1])) ? 1 : 0,
                'IS_Employee' => (isset($share_employee[1])) ? 1 : 0,
				'TbCategoryEmployee' => (isset($share_employee[1])) ? 'EMPLOYEE1' : '',
				'TbCategoryshareholder' => (isset($share_holder[1])) ? 'SHAREHOLDER1' : '',
                'Params' => serialize($director_params),
                'AddedOn' => $AddedOn,
                'AddedBy' => $AddedBy,
                'Status' => $status,
                'AccountantAccess' => $accountant_access,
            );

            $record['DIRECTOR'] = $director_detail;
			
            //echo '------------- Director Detail ---------------';
            //echo '<pre>';print_r($director_detail);echo '</pre>';
            $share_first_name = $_POST['SFirstName'];
            $share_last_name = $_POST['SLastName'];
            $share_dob = $_POST['SDOB'];
            $share_ni_number = $_POST['SNINumber'];
            $share_email = $_POST['SEmail'];
            $share_utr = $_POST['SUTR'];
            $share_address_one = $_POST['SAddressOne'];
            $share_address_two = $_POST['SAddressTwo'];
            $share_address_three = $_POST['SAddressThree'];
            $share_postal_code = $_POST['SPostalCode'];
            $share_phone_number = $_POST['SPhoneNumber'];
            $share_country = $_POST['SCountry'];
            
            $share_salutation = $_POST['salutation'];
            $share_total_shares = $_POST['ShareHolderShares'];

            $share_holder_detail = array();
			
            for ($x = 0; $x < count($share_first_name); $x++) {
				
				/*/if(isset($share_holder[$x + 2]) && isset($share_employee[$x + 2])){
					$countBoth = $countBoth+1;
					$TB_Category = 'BOTH'.($countBoth);
					
					#insert new shareholder both category 
					if(($countBoth) > $countBothCategory){
						$tbData = array(
											'title' => 'Both'.($countBoth+1),
											'catKey' => 'BOTH'.($countBoth+1),
											//'type' => 'B/S',
											'parent' => 279,
											'AnalysisLedgerParent' => 279,
											'status' => 1
										);
						$addShareholderCategory = $this->supplier->addShareholderCategory($tbData);
					}
						
				}else if(isset($share_holder[$x + 2])){
					$countShareHolder = $countShareHolder+1;
					$TB_Category = 'SHAREHOLDER'.($countShareholderCategory+1);
					#insert new shareholder category 
					if(($countShareHolder) > $countBothCategory){
						$tbData = array(
										'title' => 'Shareholder'.($countShareHolder),
										'catKey' => 'SHAREHOLDER'.($countShareHolder),
										'type' => 'B/S',
										'parent' => 279,
										'AnalysisLedgerParent' => 279,
										'status' => 1
									);
						$addShareholderCategory = $this->supplier->addShareholderCategory($tbData);
					}
				}else{
					$countEmployee = $countEmployee+1;
					$TB_Category = 'EMPLOYEE'.($countEmployee+1);					
					#insert new shareholder employee category 
					if(($countEmployee) > $countBothCategory){
						$tbData = array(
										'title' => 'EMPLOYEE'.($countShareHolder),
										'catKey' => 'EMPLOYEE'.($countShareHolder),
										'type' => 'B/S',
										'parent' => 279,
										'AnalysisLedgerParent' => 279,
										'status' => 1
									);
						$addShareholderCategory = $this->supplier->addShareholderCategory($tbData);
					}
				}*/
				
                if ($share_first_name[$x] == '') {
                    continue;
                }
                $share_holder_params = array(
                    'Salutation' => safe($share_salutation[$x]),
                    'DOB' => mDate($share_dob[$x]),
                    'NI_Number' => safe($share_ni_number[$x]),
                    'UTR' => safe($share_utr[$x]),
                    'EmployementStartDate' => '',
                    'AddressOne' => safe($share_address_one[$x]),
                    'AddressTwo' => safe($share_address_two[$x]),
                    'AddressThree' => safe($share_address_three[$x]),
                    'ContactNumber' => safe($share_phone_number[$x]),
                    'Country' => safe($share_country[$x]),
                    'PostalCode' => safe($share_postal_code[$x]),
                );
				if(isset($share_employee[$x+2])){
					$TbCategoryEmployee = 'EMPLOYEE'.($x+2);
				}else{
					$TbCategoryEmployee = '';
				}
				if(isset($share_holder[$x+2])){
					$TbCategoryshareholder = 'SHAREHOLDER'.($x+2);
				}else{
					$TbCategoryshareholder = '';
				}
				
                $share_holder_detail[] = array(
                    'CompanyID' => '',
                    'DesignationType' => 'S',
                    'FirstName' => safe($share_first_name[$x]),
                    'LastName' => safe($share_last_name[$x]),
					//'TB_Category' => $TB_Category,
                    'Email' => safe($share_email[$x]),
                    'IS_ShareHolder' => (isset($share_holder[$x + 2])) ? 1 : 0,
                    'IS_Director' => (isset($share_director[$x + 2])) ? 1 : 0,
                    'IS_Employee' => (isset($share_employee[$x + 2])) ? 1 : 0,
					'TbCategoryEmployee' => $TbCategoryEmployee,
					'TbCategoryshareholder' => $TbCategoryshareholder,
                    'TotalShares' => safe($share_total_shares[$x]),
                    'Params' => serialize($share_holder_params),
                    'AddedOn' => safe($AddedOn),
                    'AddedBy' => safe($AddedBy),
                    'Status' => $status,
                    'AccountantAccess' => safe($accountant_access),
                );
					
				#insert new shareholder category 
				/*if(($countShareHolder) > $countShareholderCategory){
					$tbData = array(
										'title' => 'Shareholder'.($x+1),
										'catKey' => 'SHAREHOLDER'.($x+1),
										'type' => 'B/S',
										'parent' => 279,
										'AnalysisLedgerParent' => 279,
										'status' => 1
									);
					$addShareholderCategory = $this->supplier->addShareholderCategory($tbData);
				}*/
								
            }

			//echo "<pre>"; print_r( $share_holder_detail); die('Accountant controller 438');
			
            $record['SHAREHOLDER'] = $share_holder_detail;

            //echo '------------- Bank Detail ---------------';
            /* Prepare the bank detail */
			$bank_name 			 = $_POST['BankName'];
			$bank_short_code 	 = $_POST['ShortCode'];
			$bank_account_number = $_POST['AccountNumber'];
			$bank_detail		 = array();
			for ($x = 0; $x < count($bank_name); $x++) {
                if ($bank_name[$x] == '') {
                    continue;
                }
				if($x <4){
					$TB_Category = 'CASH_AT_BANK'.$x+1;
				}else{
					$TB_Category = 'CASH_AT_BANK4';
				}
				$bank_detail[] = array(
					'CompanyID' => '',
					'Name' => safe($bank_name[$x]),
					'ShortCode' => safe($bank_short_code[$x]),
					'AccountNumber' => safe($bank_account_number[$x]),
					'AddedOn' => $AddedOn,
					'AddedBy' => $AddedBy,
					'Status' => $status,
					'TB_Category' => $TB_Category
				);
			}	
            $record['BANKS'] = $bank_detail;
			
			if( $this->session->userdata('lastAddedClientId') != '' && $this->session->userdata('lastAddedClientId') > 0 )
				$id = $this->session->userdata('lastAddedClientId');
			else
				$id = null;
			
			//echo "<pre>";print_r($record);
            /* Check if client email is already registered or not */
            $response = $this->account->checkEmail($record['USER']['Email'],$id);
			//echo "<pre>";print_r($response);die('dd');
            if ($response) {
                $msg = $this->lang->line('ACCOUNTANT_CLIENT_EMAIL_EXIST');
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                $this->session->set_flashdata('addError', $msg);
                setRedirect(site_url('add_client'));
            }

			//echo "<pre>"; print_r($record); die("ACCOUNTANT Controller 498 no line.");
			
            $response = $this->account->addClient($record['USER']);
						
            if ($response) {
                $clientID = $response;
                if (!empty($clientID)) {
                    if (!empty($_FILES['term_conditions']['name'])) {
                        $file = $_FILES['term_conditions']['name'];
                        $ext = explode('.', $file);
                        if ($ext[1] == 'pdf' || $ext[1] == 'PDF') {
                            $this->load->model('accountant/Term');
                            $path = 'assets/uploads/terms';
                            /* STEP - 1 Check if the folder not exist */
                            if (!file_exists($path)) {
                                //echo 'Path : '.$path;die;
                                /* If not exists then create one */
                                if (!mkdir($path, 0777, TRUE)) {
                                    log_message('error', 'Error in uploading the file to the server');
                                    $msg = '<div class="alert alert-danger">';
                                    $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>';
                                    $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURRED');
                                    $msg .= '</div>';
                                    $this->session->set_flashdata('uploadDocumentError', $msg);
                                    setRedirect($_SERVER['HTTP_REFERER']);
                                }
                            }
                            $file_name = $_FILES['term_conditions']['name'];
                            $exp = explode('.', $file_name);
                            $file_name = "UK1014" . rand(100, 1000) . "." . $exp[1];

                            $file_record = array(
                                'VERSION' => 1,
                                'ClientId' => $clientID,
                                'FName' => $file_name,
                                'FType' => $_FILES['term_conditions']['type'],
                                'FSize' => $_FILES['term_conditions']['size'],
                                'AddedOn' => date('Y-m-d'),
                                'Type' => 'PDF',
                                'AccountantAccess' => $user['UserID'],
                            );
                            /* If folder already exists then copy the file to the destination folder */
                            $destination_path = $path;
                            $config['upload_path'] = $destination_path;
                            $config['allowed_types'] = 'pdf';
                            $config['max_size'] = '1000';
                            $config['max_width'] = '1024';
                            $config['max_height'] = '768';
                            $config['file_name'] = $file_name;
                            $this->load->library('upload', $config);
                            $this->load->library('upload'); //initialize
                            $this->upload->initialize($config); //Alternately you can set preferences by calling the initialize function. Useful if you auto-load the class
                            $this->upload->do_upload(); // do upload
                            if (!$this->upload->do_upload('term_conditions')) {
                                //error in upload
                                // var_dump($this->upload->display_errors());
                                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                                $msg .= $this->lang->line('ACCOUNTENT_FILE_UPLOAD_UPLOADED');
                                $msg .= "</div>";
                                $this->session->set_flashdata('addError', $msg);
                                setRedirect($_SERVER['HTTP_REFERER']);
                            }
                            /* STEP - 2 After successful upload add the file record in the database name */
                            $file_id = $this->Term->saveFile($file_record);
                        }
                    }
                }
            } else {
                $msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_CLIENT');
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                $this->session->set_flashdata('clientError', $msg);
                setRedirect($_SERVER['HTTP_REFERER']);
                $clientID = '';
            }
            $record['COMPANY']['ClientID'] = $clientID;
			if($_POST['CompanyName'] == '' || $_POST['CompanyRegisteredNo'] == '' || $_POST['YearEndDate'] == '')
			{
				$record['COMPANY'] = array();
			}
            $response = $this->account->addCompany($record['COMPANY']);
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
            if (isset($_POST['VATRegistred'])) {
                $vat_entry = 1;
                $vat_details = array(
                    'ClientID' => $clientID,
                    'CompanyID' => $companyID,
                    'Type' => safe($_POST['VATRegisteredType']),
                    'AddedBy' => $AddedBy,
                    'AddedOn' => $AddedOn,
                    'Status' => $status
                );
                $vat_flat = array();
                if ($vat_details['Type'] == 'flat') {
					$EndDate = $_POST['VATEndDate'];
                    $VATEffectiveDate = $_POST['VATEffectiveDate'];
                    $PercentRate = $_POST['VATRatePercent'];
					$vat_details['StartDate'] = safe(date('Y-m-d', strtotime($VATEffectiveDate)));
                    $vat_details['EndDate'] = safe(date('Y-m-d', strtotime($EndDate)));
                    $vat_details['PercentRate'] = safe($PercentRate);
                    $vat_details['PercentRateAfterEndDate'] = safe($_POST['VATRatePercentAfterYear']);
                } else {
					$VATEffectiveDate = $_POST['VATEffectiveDate'];
                    $vat_details['PercentRate'] = safe($_POST['VATStanderedRate']);
					$vat_details['StartDate'] = safe(date('Y-m-d', strtotime($VATEffectiveDate)));
                }

                if (count($vat_details) > 0) {
                    $response = $this->account->addVAT($vat_details, 'single');					
                    if (!$response) {
                        $msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_VAT');
                        $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                        $this->session->set_flashdata('clientError', $msg);
                        setRedirect(site_url('client_listing'));
                    }
                }
                /* Insert the VAT Details in the database */
            }
            $record['DIRECTOR']['CompanyID'] = $companyID;
            $response = $this->account->addCustomer($record['DIRECTOR'], 'single');
            if (!$response) {				
                $msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_SHARE');
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                $this->session->set_flashdata('clientError', $msg);
                setRedirect(site_url('client_listing'));
            }

            if (count($record['SHAREHOLDER']) > 0) {
                foreach ($record['SHAREHOLDER'] as $key => $val) {
                    $record['SHAREHOLDER'][$key]['CompanyID'] = $companyID;
                }

                $response = $this->account->addCustomer($record['SHAREHOLDER'], 'batch');				
                if (!$response) {
					//die('SHAREHOLDER ERROR');
                    $msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_SHARE');
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                    $this->session->set_flashdata('clientError', $msg);
                    setRedirect(site_url('client_listing'));
                }
            }
			if (count($record['BANKS']) > 0) {				
				$tnob = 1;
                foreach ($record['BANKS'] as $key => $val) {
                    $record['BANKS'][$key]['CompanyID'] = $companyID;
					if($tnob > 4) {
						$tnob = 4;	
					}
					$record['BANKS'][$key]['TB_Category'] = "CASH_AT_BANK".$tnob;
					$tnob = $tnob+1;
                }
				
                $response = $this->account->addBank($record['BANKS']);
                if (!$response) {
                    $msg = $this->lang->line('UNEXPECTED_ERROR_DURING_SAVING_BANK');
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                    $this->session->set_flashdata('clientError', $msg);
                    setRedirect(site_url('client_listing'));
                }
            }

            /* VAT Calculation */
            calculateVatQuarters($clientID,$companyID);
		
            /* No Error */
            /*
              if($sendMail)
              {
              /* Send email to newly created account to set the password
              $this->load->model('login');
              $token = do_hash(random_string('alnum',5));
              $link = site_url().'home/set_password/'.$this->encrypt->encode($token.'/'.$record['USER']['Email']);
              $this->login->addToken($token,$record['USER']['Email']);
              $email = array(
              'Name'		=>	$record['USER']['FirstName'],
              'domain'	=>	site_url(),
              'Email'		=>	$record['USER']['Email'],
              'link'		=>	$link
              );
              $sendEmail = array(
              'Subject'		=>	$this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_SET_SUBJECT'),
              'Message'		=>	sprintf($this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_RESET_MESSAGE'),$email['Name'],$email['domain'],$email['Email'],$email['link']),
              'To'			=>	$email['Email'],
              'From'			=>  CASHMAN_FROM_EMAIL_ADDRESS
              );
              $response = sendEmail($sendEmail);
              if(!$response)
              {
              /* If failed change the state to disable
              $response = $this->account->delete_client($clientID);
              $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
              $msg .= $this->lang->line('ACCOUNTANT_CLIENT_EMAIL_FAILURE');
              $msg .= "</div>";
              $this->session->set_flashdata('addError',$msg);
              setRedirect(site_url($_SERVER['HTTP_REFERER']));
              }else{

              /* If success then change state to 1 else 0
              $this->account->changeState($clientID);
              $msg = $this->lang->line('ACCOUNTANT_ADD_CLIENT_SUCCESS');
              $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;'.$msg.'</div>';
              }
              }else{
              $msg = $this->lang->line('ACCOUNTANT_ADD_CLIENT_SUCCESS');
              $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;'.$msg.'</div>';
              }
             */
            $msg = $this->lang->line('ACCOUNTANT_ADD_CLIENT_SUCCESS');
            $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;' . $msg . '</div>';
            $this->session->set_flashdata('clientError', $msg);
			if(isset($_POST['createandfinish']))
			{
				$this->session->set_userdata('lastAddedClientId','');
				setRedirect(site_url('client_listing'));
			}
			else if( $this->session->userdata('lastAddedClientId') != '' && $this->session->userdata('lastAddedClientId') > 0 )
				setRedirect(site_url('add_client'));
			else
				setRedirect(site_url('client_listing'));
        } else {
            show_404();
        }
    }

    public function getPagination($url = null, $perPage = CLIENT_LISTING_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */
        $this->load->library('pagination');

        $config['base_url'] = site_url() . 'client_listing';
        $config['num_links'] = 3;
        $config['per_page'] = $perPage;
        //die($totalItem);
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

    public function search() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = safe($_POST['YearEndDate']);
            $search = array(
                'Name' => safe($_POST['Name']),
                'Email' => safe($_POST['Email']),
                'EndDate' => ($date == '') ? '' : date('Y-m-d', strtotime($date)),
                'CompanyName' => safe($_POST['CompanyName']),
                'Status' => safe($_POST['Status']),
                'Relation_with' => safe($_POST['Relation_with'])
            );
            $this->session->set_userdata('accountantSearch', $search);
            //echo "<pre>";print_r($this->session->all_userdata());die;
            setRedirect('client_listing');
            exit;
        } else {
            show_404();
        }
    }

    public function reset() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->session->set_userdata('accountantSearch', '');
            //echo CLIENT_LISTING_PAGINATION_LIMIT;die;
            $data['items'] = $this->account->getItems(CLIENT_LISTING_PAGINATION_LIMIT, 0);
            $json = array();
            $total = $this->account->totalItems();
            $json['pagination'] = $this->getPagination('client_listing', CLIENT_LISTING_PAGINATION_LIMIT, $total);
            $json['items'] = $this->load->view('accountant/client_listing', $data, TRUE);
            echo json_encode($json);
            exit;
        } else {
            show_404();
        }
    }

    public function client_sort() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $order = safe($this->encrypt->decode($_POST['order']));
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $des_order_value = array(
                'SORT_BY_NAME' => 'CONCAT(u.FirstName," ",u.LastName) DESC',
                'SORT_BY_CONTACTNO' => 'u.ContactNo DESC',
                'SORT_BY_DATE' => 'c.EndDate DESC',
                'SORT_BY_STATUS' => 'u.Status DESC'
            );
            $asc_order_value = array(
                'SORT_BY_NAME' => 'CONCAT(u.FirstName," ",u.LastName) ASC',
                'SORT_BY_CONTACTNO' => 'u.ContactNo ASC',
                'SORT_BY_DATE' => 'c.EndDate ASC',
                'SORT_BY_STATUS' => 'u.Status ASC',
            );
            $prev_order = $this->session->userdata('accountantSortingOrder');
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
            $this->session->set_userdata('accountantSortingOrder', $order_value);
            $data['items'] = $this->account->getItems(CLIENT_LISTING_PAGINATION_LIMIT, $page);

            $d[0] = $this->load->view('accountant/client_listing', $data, true);
            $d[1] = $dir;
            echo json_encode($d);
            exit;
        } else {
            show_404();
        }
    }

    public function review() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $accountant_access = '';
            $record = array();
            $user = $this->session->userdata('user');
            $AddedBy = $user['UserID'];
            $ModifiedOn = date('Y-m-d');
            $status = 1;

            if (isset($_POST['sec'])) {
                $sendMail = 1;
            } else {
                $sendMail = 0;
            }
            /* check if terms and conditions is uploaded or not */
            if (!empty($_FILES['term_conditions'])) {
                $file = $_FILES['term_conditions']['name'];
                $ext = explode('.', $file);
                if ($ext[1] == 'pdf' || $ext[1] == 'PDF') {
                    $this->load->model('accountant/Term');
                    $ClientId = safe($this->encrypt->decode($_POST['client_id']));


                    $path = 'assets/uploads/terms';
                    /* STEP - 1 Check if the folder not exist */
                    if (!file_exists($path)) {
                        //echo 'Path : '.$path;die;
                        /* If not exists then create one */
                        if (!mkdir($path, 0777, TRUE)) {
                            log_message('error', 'Error in uploading the file to the server');
                            $msg = '<div class="alert alert-danger">';
                            $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>';
                            $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURRED');
                            $msg .= '</div>';
                            $this->session->set_flashdata('uploadDocumentError', $msg);
                            setRedirect($_SERVER['HTTP_REFERER']);
                        }
                    }

                    /* check if already terms and conditions exist */
                    $checkTC = $this->Term->checkTermsconditions($ClientId);
                    if (!empty($checkTC)) {
                        $file_name = $_FILES['term_conditions']['name'];
                        $exp = explode('.', $file_name);
                        $file_name = "UK1014" . rand(100, 1000) . "." . $exp[1];
                        $file_record = array(
                            'VERSION' => $checkTC[0]->Version + 1,
                            'FName' => $file_name,
                            'FType' => $_FILES['term_conditions']['type'],
                            'FSize' => $_FILES['term_conditions']['size'],
                            'AddedOn' => date('Y-m-d'),
                            'Type' => 'PDF',
                            'Status' => '0',
                            'ModifiedOn' => '(NULL)',
                        );
                        unlink($path . "/" . $checkTC[0]->FName);
                        $file_id = $this->Term->updateFile($ClientId, $file_record);
                    } else {
                        $file_name = $_FILES['term_conditions']['name'];
                        $exp = explode('.', $file_name);
                        $file_name = "UK1014" . rand(100, 1000) . "." . $exp[1];
                        $file_record = array(
                            'VERSION' => 1,
                            'ClientId' => $ClientId,
                            'FName' => $file_name,
                            'FType' => $_FILES['term_conditions']['type'],
                            'FSize' => $_FILES['term_conditions']['size'],
                            'AddedOn' => date('Y-m-d'),
                            'Type' => 'PDF',
                            'AccountantAccess' => $user['UserID'],
                        );
                        $file_id = $this->Term->saveFile($file_record);
                    }

                    /* If folder already exists then copy the file to the destination folder */
                    $destination_path = $path;
                    $config['upload_path'] = $destination_path;
                    $config['allowed_types'] = 'pdf';
                    $config['max_size'] = '1000';
                    $config['max_width'] = '1024';
                    $config['max_height'] = '768';
                    $config['file_name'] = $file_name;
                    $this->load->library('upload', $config);
                    $this->load->library('upload'); //initialize
                    $this->upload->initialize($config); //Alternately you can set preferences by calling the initialize function. Useful if you auto-load the class
                    $this->upload->do_upload(); // do upload
                    if (!$this->upload->do_upload('term_conditions')) {
                        //error in upload
                        // var_dump($this->upload->display_errors());
                        $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                        $msg .= $this->lang->line('ACCOUNTENT_FILE_UPLOAD_UPLOADED');
                        $msg .= "</div>";
                        $this->session->set_flashdata('addError', $msg);
                        setRedirect($_SERVER['HTTP_REFERER']);
                    }
                    /* STEP - 2 After successful upload add the file record in the database name */
                }
            }


            /* Check if Company Logo is uploaded or not */
            $allowed_extension = array(
                '1' => 'image/jpeg',
                '2' => 'image/jpg',
                '3' => 'image/png'
            );
            $file_link = '';
            if (isset($_POST['task']) && $_POST['task'] == 'update') {
                if (isset($_FILES['file'])) {
                    if ($_FILES['file']['error'] == 0) {
                        if (!in_array($_FILES['file']['type'], $allowed_extension)) {
                            $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                            $msg .= $this->lang->line('ACCOUNTENT_WRONG_FILE_UPLOADED');
                            $msg .= "</div>";
                            $this->session->set_flashdata('addError', $msg);
                            $json['link'] = $_SERVER['HTTP_REFERER'];
                            echo json_encode($json);
                            die;
                        }
                        if ($_FILES['file']['size'] > LOGO_UPLOAD_FILE_SIZE) {
                            $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                            $msg .= $this->lang->line('ACCOUNTENT_FILE_SIZE_ERROR');
                            $msg .= "</div>";
                            $this->session->set_flashdata('addError', $msg);
                            $json['link'] = $_SERVER['HTTP_REFERER'];
                            echo json_encode($json);
                            die;
                        }
                        $file_extension = explode('.', $_FILES['file']['name']);
                        $file_extension = end($file_extension);
                        $file_name = random_string('alnum', strlen($_FILES['file']['name']));
                        $file_name = strtoupper(substr(safe($_POST['CompanyName']), 0, 3)) . '-' . $file_name . '.' . $file_extension;
                        $config['upload_path'] = './assets/uploads/logos/';
                        $config['allowed_types'] = 'jpeg|jpg|png';
                        $config['max_size'] = '1000';
                        $config['max_width'] = '1024';
                        $config['max_height'] = '768';
                        $config['file_name'] = $file_name;

                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('file')) {
                            $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                            $msg .= $this->lang->line('ACCOUNTENT_FILE_UPLOAD_UPLOADED');
                            $msg .= "</div>";
                            $this->session->set_flashdata('addError', $msg);
                            $json['link'] = $_SERVER['HTTP_REFERER'];
                            echo json_encode($json);
                            die;
                        }
                        $file_link = 'assets/uploads/logos/' . $file_name;
                    } else {
                        $file_link = $_POST['image_link'];
                    }
                }
            }

            if (isset($_POST['client_id'])) { 
                $client_id = safe($this->encrypt->decode($_POST['client_id']));
            } else {
                $client_id = '';
            }
			/*if ($client_id == '' && $client_id <= 0 ) {
                    $msg = $this->lang->line('ACCOUNTANT_CLIENT_ID_MISSING');
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                    $this->session->set_flashdata('addError', $msg);
                    $link['link'] = $_SERVER['HTTP_REFERER'];
                    die(json_encode($link));
                }*/
            if (isset($_POST['company_id'])) {
                $companyID = safe($this->encrypt->decode($_POST['company_id']));
            } else {
                $companyID = '';
            }



            //echo '------------- Client Detail ---------------';
            $client_params = array(
                'Salutation' => safe($_POST['ClientSalutation']),
                'DOB' => mDate($_POST['DOB']),
                'NI_NUMBER' => safe($_POST['niNumber']),
                'UTR' => safe($_POST['utr']),
                'AddressTwo' => safe($_POST['addressTwo']),
                'AddressThree' => safe($_POST['addressThree'])
            );

            $client_detail = array(
                'ClientID' => $client_id,
                'UserType' => 'TYPE_CLI',
                'FirstName' => safe($_POST['FirstName']),
                'LastName' => safe($_POST['LastName']),
                'Address' => safe($_POST['addressOne']),
                'ZipCode' => safe($_POST['postalCode']),
                'ContactNo' => safe($_POST['phoneNo']),
                'Email' => safe($_POST['email']),
                'Country' => safe($_POST['Country']),
                'Params' => $client_params,
                'AddedBy' => $AddedBy,
                'AddedOn' => $ModifiedOn,
                'Status' => 0,
                'State' => $sendMail,
                'Relation_with' => safe($_POST['relationship_manager'])
            );
            $record['USER'] = $client_detail;
            //echo '------------- Company Detail ---------------';

            /* Prepare Company array */
            $company_params = array(
                'REG_AddressOne' => safe($_POST['CompanyAddOne']),
                'REG_AddressTwo' => safe($_POST['CompanyAddTwo']),
                'REG_AddressThree' => safe($_POST['CompanyAddThree']),
                'REG_PostalCode' => safe($_POST['CompanyPostalCode']),
                'REG_Country' => safe($_POST['CompanyCountry']),
                'REG_PhoneNo' => safe($_POST['CompanyPhoneNo']),
                'CON_AddressOne' => safe($_POST['CCAddressOne']),
                'CON_AddressTwo' => safe($_POST['CCAddressTwo']),
                'CON_AddressThree' => safe($_POST['CCAddressThree']),
                'CON_PostalCode' => safe($_POST['CCpostalcode']),
                'CON_Country' => safe($_POST['CCompanyCountry']),
                'CON_PhoneNo' => safe($_POST['CCPhoneNo']),
                'CompanySIDate' => safe($_POST['CompanySIDate']),
                'CompanyMonthlyFee' => safe($_POST['CompanyMonthlyFee']),
                'VATRegistrationNo' => safe($_POST['VATRegisteredNo']),
                'VATQuaters' => safe($_POST['VATQuaters']),
                'CompanyShares' => safe($_POST['TotalShares']),
                'LogoLink' => $file_link
            );

            if (!isset($_POST['VATRegistred'])) {
                $company_params['VATRegistrationNo'] = '';
                $company_params['VATQuaters'] = '';
            }
			$company_params['isCISRegistered'] = isset($_POST['isCISRegistered']) ? safe($_POST['isCISRegistered']) : '';
            $company_params['cis_percentage'] = isset($_POST['cis_percentage']) ? safe($_POST['cis_percentage']) : '';
			
            $company_detail = array(
                'CompanyID' => $companyID,
                'Name' => safe($_POST['CompanyName']),
                'TradingName' => safe($_POST['TradingName']),
                'Description' => safe($_POST['BussinessDescription']),
                'AnnualAmount' => safe($_POST['ExpectedAmount']),
                'CompanyType' => safe($_POST['CompanyType']),
                'PayeReference' => safe($_POST['PayeReference']),
                'PayeAcountReference' => safe($_POST['PayeAccountReference']),
                'RegistrationNo' => safe($_POST['CompanyRegisteredNo']),
                'TaxReference' => safe($_POST['TaxReference']),
                'IncorporationDate' => mDate($_POST['IncorporationDate']),
                'ReturnDate' => mDate($_POST['ReturnDate']),
                'EndDate' => mDate($_POST['YearEndDate']),
                'FaxNumber' => safe($_POST['FaxNo']),
                'Email' => safe($_POST['CompanyEmail']),
                'Website' => safe($_POST['CompanyWebsite']),
                'Params' => $company_params,
                'AddedBy' => $AddedBy,
                'AddedOn' => $ModifiedOn,
                'Status' => $status
            );
            $record['COMPANY'] = $company_detail;
            /*  VAT Details */
            //echo serialize($client_params);
            /* Prepare vat Registration Number */

            if (isset($_POST['VATRegistred'])) {
                $vat_entry = 1;
                $vat_details = array(
                    'Type' => safe($_POST['VATRegisteredType']),
                    'AddedBy' => $AddedBy,
                    'AddedOn' => $ModifiedOn,
                    'Status' => $status
                );

                $vat_flat = array();
                if ($vat_details['Type'] == 'flat') {
                    $EndDate = $_POST['VATEndDate'];
					$VATEffectiveDate = $_POST['VATEffectiveDate'];
                    $PercentRate = $_POST['VATRatePercent'];
					$vat_details['StartDate'] = safe(date('Y-m-d', strtotime($VATEffectiveDate)));
                    $vat_details['EndDate'] = safe(date('Y-m-d', strtotime($EndDate)));
                    $vat_details['PercentRate'] = safe($PercentRate);
                    $vat_details['VID'] = safe($this->encrypt->decode($_POST['flat_id']));
                    $vat_details['PercentRateAfterEndDate'] = safe($_POST['VATRatePercentAfterYear']);
                    //$vat_details = array();
                } else {
					$VATEffectiveDate = $_POST['VATEffectiveDate'];
                    $vat_details['PercentRate'] = safe($_POST['VATStanderedRate']);
					$vat_details['StartDate'] = safe(date('Y-m-d', strtotime($VATEffectiveDate)));
                    $vat_details['VID'] = safe($this->encrypt->decode($_POST['stand_id']));
                }
                //echo '<pre>';print_r($vat_details);echo '</pre>';die;
                $record['VAT'] = $vat_details;
            } else {
                $vat_details = array(
                    'Type' => '',
                    'AddedBy' => $AddedBy,
                    'AddedOn' => $ModifiedOn,
                    'Status' => '',
                    'PercentRate' => '',
                    'PercentRateAfterEndDate' => '',
                    'VID' => safe($this->encrypt->decode($_POST['flat_id']))
                );
                $record['VAT'] = $vat_details;
            }

			$share_employee = (isset($_POST['IsEmployee']) ? $_POST['IsEmployee'] : array());  
			$share_director = (isset($_POST['IsDirector']) ? $_POST['IsDirector'] : array());			
			$share_holder   = (isset($_POST['IsShareholder']) ? $_POST['IsShareholder'] : array());			
			
            //echo '<pre>';print_r($_POST['VATRegistred']);echo '</pre>';die;
            //echo '------------- Director Detail ---------------';
            $director_params = array(
                'Salutation' => safe($_POST['directorSalutation']),
                'DOB' => mDate($_POST['Ddob']),
                'NI_Number' => safe($_POST['DNINumber']),
                'UTR' => safe($_POST['DUTR']),
                'EmployementStartDate' => '',
                'AddressOne' => safe($_POST['DAddressOne']),
                'AddressTwo' => safe($_POST['DAddressTwo']),
                'AddressThree' => safe($_POST['DAddressThree']),
                'PostalCode' => safe($_POST['DPostalCode']),
                'ContactNumber' => safe($_POST['DPhoneNo']),
                'Country' => safe($_POST['DCountry'])
            );
            $director_detail = array(
                'ID' => safe($this->encrypt->decode($_POST['director_id'])),
                'CompanyID' => $companyID,
                'DesignationType' => 'D',
                'FirstName' => safe($_POST['DFirstName']),
                'LastName' => safe($_POST['DLastName']),
                'TotalShares' => safe($_POST['DirectorShares']),
                'Email' => safe($_POST['DEmail']),
                'IS_ShareHolder' => 1,
                'IS_Director' => 1,
				'IS_ShareHolder' => (isset($share_holder[1])) ? 1 : 0,
				'IS_Director' => (isset($share_director[1])) ? 1 : 0,
				'IS_Employee' => (isset($share_employee[1])) ? 1 : 0,
                'Params' => $director_params,
                'AddedOn' => $ModifiedOn,
                'AddedBy' => $AddedBy,
                'Status' => $status,
                'AccountantAccess' => $accountant_access,
            );
			
            $record['DIRECTOR'] = $director_detail;


            $share_first_name = $_POST['SFirstName'];
            $share_last_name = $_POST['SLastName'];
            $share_dob = $_POST['SDOB'];
            $share_ni_number = $_POST['SNINumber'];
            $share_email = $_POST['SEmail'];
            $share_utr = $_POST['SUTR'];
            $share_address_one = $_POST['SAddressOne'];
            $share_address_two = $_POST['SAddressTwo'];
            $share_address_three = $_POST['SAddressThree'];
            $share_postal_code = $_POST['SPostalCode'];
            $share_phone_number = $_POST['SPhoneNumber'];
            $share_country = $_POST['SCountry'];
            $share_ids = $_POST['share_holder_id'];

            $share_director = (isset($_POST['IsDirector']) ? $_POST['IsDirector'] : array());
            $share_salutation = $_POST['salutation'];
            $share_total_shares = $_POST['ShareHolderShares'];

            //echo '<pre>';print_r($share_director);echo '</pre>';
            $share_holder_detail = array();
			#count all shareholde category 
			$countShareholderCategory = count(getAllShareholderCategory());
			
            for ($x = 0; $x < count($share_first_name); $x++) {
                if ($share_first_name[$x] == '') {
                    continue;
                }
                $share_holder_params = array(
                    'Salutation' => safe($share_salutation[$x]),
                    'DOB' => mDate($share_dob[$x]),
                    'NI_Number' => safe($share_ni_number[$x]),
                    'UTR' => $share_utr[$x],
                    'EmployementStartDate' => '',
                    'AddressOne' => safe($share_address_one[$x]),
                    'AddressTwo' => safe($share_address_two[$x]),
                    'AddressThree' => safe($share_address_three[$x]),
                    'ContactNumber' => safe($share_phone_number[$x]),
                    'Country' => $share_country[$x],
                    'PostalCode' => safe($share_postal_code[$x]),
                );
				
				if(isset($share_employee[$x+2])){
					$TbCategoryEmployee = 'EMPLOYEE'.($x+1);
				}else{
					$TbCategoryEmployee = '';
				}
				if(isset($share_holder[$x+2])){
					$TbCategoryshareholder = 'SHAREHOLDER'.($x+1);
				}else{
					$TbCategoryshareholder = '';
				}
				
                $share_holder_detail[] = array(
                    'ID' => safe($this->encrypt->decode($share_ids[$x])),
                    'CompanyID' => $companyID,
                    'DesignationType' => 'S',
                    'FirstName' => safe($share_first_name[$x]),
                    'LastName' => safe($share_last_name[$x]),
					'TB_Category' => 'SHAREHOLDER'.($x+1),
                    'Email' => safe($share_email[$x]),					
					'IS_ShareHolder' => (isset($share_holder[$x + 2])) ? 1 : 0,
					'IS_Director' => (isset($share_director[$x + 2])) ? 1 : 0,
					'IS_Employee' => (isset($share_employee[$x + 2])) ? 1 : 0,
					'TbCategoryEmployee' => $TbCategoryEmployee,
					'TbCategoryshareholder' => $TbCategoryshareholder,
                    'TotalShares' => safe($share_total_shares[$x]),
                    'Params' => $share_holder_params,
                    'AddedOn' => $ModifiedOn,
                    'AddedBy' => $AddedBy,
                    'Status' => $status,
                    'AccountantAccess' => $accountant_access,
                );
				
				#insert new shareholder category 
				/*if(($x+1) > $countShareholderCategory){
					$tbData = array(
										'title' => 'Shareholder'.($x+1),
										'catKey' => 'SHAREHOLDER'.($x+1),
										'type' => 'B/S',
										'parent' => 279,
										'AnalysisLedgerParent' => 279,
										'status' => 1
									);
					$addShareholderCategory = $this->supplier->addShareholderCategory($tbData);
				}*/
				
            }
            $record['SHARES'] = $share_holder_detail;
            //echo '------------- Bank Detail ---------------';
			/* Prepare the bank detail */
			$bank_id 			 = $_POST['bank_id'];
			$bank_name 			 = $_POST['BankName'];
			$bank_short_code 	 = $_POST['ShortCode'];
			$bank_account_number = $_POST['AccountNumber'];
			$bank_detail		 = array();
			
			for ($x = 0; $x < count($bank_name); $x++) {
                if ($bank_name[$x] == '') {
                    continue;
                }
				if (isset($_POST['task']) && $_POST['task'] == 'update') {
					$bank_detail[] = array(
						'BID' => safe($this->encrypt->decode($bank_id[$x])),
						'Name' => safe($bank_name[$x]),
						'ShortCode' => safe($bank_short_code[$x]),
						'AccountNumber' => safe($bank_account_number[$x]),
						'AddedOn' => $ModifiedOn,
						'AddedBy' => $AddedBy,
						'Status' => $status
					);
				}else{
					$bank_detail[] = array(
						'Name' => safe($bank_name[$x]),
						'ShortCode' => safe($bank_short_code[$x]),
						'AccountNumber' => safe($bank_account_number[$x]),
						'AddedOn' => $ModifiedOn,
						'AddedBy' => $AddedBy,
						'Status' => $status
					);
				}
			}            
            $record['BANKS'] = $bank_detail;
            $data['record'] = $record;

            //echo '<pre>';print_r($record);echo '</pre>';die;
            //die('Accountant controller 1307');
            if (isset($_POST['task']) && $_POST['task'] == 'update') {
                //pr($record);die;
                /* Check if client email is already registered or not */
                $response = $this->account->checkEmail($client_detail['Email'], $client_id);
                //DIE($this->lang->line('ACCOUNTANT_CLIENT_EMAIL_EXIST').' - '.$response);
                if ($response) {
                    $msg = $this->lang->line('ACCOUNTANT_CLIENT_EMAIL_EXIST');
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;' . $msg . '</div>';
                    $this->session->set_flashdata('addError', $msg);
					$json['link'] = $_SERVER['HTTP_REFERER'];
					echo json_encode($json);
					die;					
                }
                /* Check if Status is already enabled */
                $response = $this->account->checkStatus($client_id);
                if ($response) {
                    $record['USER']['Status'] = 1;
                }
                //pr($record);die;
                $response = $this->account->updateDetails($record);
				//die('Accountant controller 1328');
                /* VAT Calculation */
                if (!empty($client_id)) {
                    if (isset($_POST['VATRegistred'])) {
                        calculateVatQuarters($client_id);
                    }
                }
                if ($response) {
                    if ($sendMail) {
                        /* Send emil to newly created account to set the password */
                        $this->load->model('login');
                        $token = do_hash(random_string('alnum', 5));
                        $set_link = site_url() . 'home/set_password/' . $this->encrypt->encode($token . '/' . $client_detail['Email']);
                        $this->login->addToken($token, $client_detail['Email']);
                        $email = array(
                            'Name' => $client_detail['FirstName'],
                            'domain' => site_url(),
                            'Email' => $client_detail['Email'],
                            'link' => $set_link
                        );
                        $sendEmail = array(
                            'Subject' => $this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_SET_SUBJECT'),
                            'Message' => sprintf($this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_RESET_MESSAGE'), $email['Name'], $email['domain'], $email['Email'], $email['link']),
                            'To' => $email['Email'],
                            'From' => CASHMAN_FROM_EMAIL_ADDRESS
                        );
                        $response = sendEmail($sendEmail);
                        if (!$response) {
                            $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                            $msg .= $this->lang->line('ACCOUNTANT_CLIENT_INVALID_EMAIL_FAILURE');
                            $msg .= "</div>";
                            $this->session->set_flashdata('addError', $msg);
                            die(json_encode(array('link' => $_SERVER['HTTP_REFERER'])));
                        } else {
                            $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
                            $msg .= $this->lang->line('ACCOUNTANT_CLIENT_DETAIL_UPDATE_SUCCESS');
                            $msg .= "</div>";
                        }
                    } else {
                        $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
                        $msg .= $this->lang->line('ACCOUNTANT_CLIENT_DETAIL_UPDATE_SUCCESS');
                        $msg .= "</div>";
                    }
                    $this->session->set_flashdata('clientError', $msg);
                } else {
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('ACCOUNTANT_CLIENT_DETAIL_UPDATE_FAILURE');
                    $msg .= "</div>";
                    $this->session->set_flashdata('clientError', $msg);
                }
				if( $this->session->userdata('addNewCompany') == 'yes' )
					$link['link'] = $_SERVER['HTTP_REFERER'];
				else
					$link['link'] = site_url() . 'client_listing';
				$json['link'] = $link['link'];
				echo json_encode($json);
				die;
            }
            $link['link'] = $this->load->view('accountant/review_detail', $data, true);
            if ($record['USER']['FirstName'] == '' || $record['USER']['LastName'] == '' || $record['USER']['Email'] == '') {
                $link['style'] = 'none';
            }
			$link['url'] = 'no';
            echo json_encode($link);
            exit;
        } else {
            show_404();
        }
    }

    public function changeStatus($id = null) {
        if (!empty($id)) {
            $status_level = array(
                'ACTION_ENABLE' => 1,
                'ACTION_DISABLE' => 0
            );
            $id = $this->encrypt->decode($id);
            $id = explode('/', $id);
            $status = $status_level[$id[0]];
            //die('ID : '.$id[1].' Status : '.$status);
            $response = $this->account->changeStatus($id[1], $status);
            if ($response) {
                if ($status == '1') {
                    $msg = '<div class="alert alert-success"><i class="fa fa-check-circle"></i>&nbsp;';
                    $msg .= $this->lang->line('ACCOUNTANT_CLIENT_DISABLE_STATUS_SUCCESSFULL');
                    $msg .= '</div>';
                } else {
                    $msg = '<div class="alert alert-success"><i class="fa fa-check-circle"></i>&nbsp;';
                    $msg .= $this->lang->line('ACCOUNTANT_CLIENT_ENABLE_STATUS_SUCCESSFULL');
                    $msg .= '</div>';
                }

                $this->session->set_flashdata('clientError', $msg);
                setRedirect(site_url() . 'client_listing');
            } else {
                setRedirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            setRedirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function checkEmail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = safe($_POST['email']);
            if (isset($_POST['ID'])) {
                $id = safe($this->encrypt->decode($_POST['ID']));
            } else {
                $id = null;
            }
            $response = $this->account->checkEmail($email, $id);
            if ($response) {
                die('wrong');
            } else {
                die('correct');
            }
        } else {
            show_404();
        }
    }

    public function resendEmail($id = NULL) {
        $userID = $this->encrypt->decode($id);
        if (empty($userID) || !is_numeric($userID)) {
            show_404();
        }
        $response = $this->account->getEmail($userID);

        /* Send email to newly created account to set the password */
        $this->load->model('login');
        $token = do_hash(random_string('alnum', 5));
        $set_link = site_url() . 'home/set_password/' . $this->encrypt->encode($token . '/' . $response[0]->Email);
        $this->login->addToken($token, $response[0]->Email);
        $email = array(
            'Name' => $response[0]->Name,
            'domain' => site_url(),
            'Email' => $response[0]->Email,
            'link' => $set_link
        );

        //Mail Setup
        $email_setting = emailSetting();
        $msetup = '';
        if (!empty($email_setting[0]->Email_Signature)) {
            $msetup = $email_setting[0]->Email_Text . $email_setting[0]->Email_Signature;
        } else {
            $msetup = $this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_RESET_MESSAGE');
        }
        $sendEmail = array(
            'Subject' => $this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_SET_SUBJECT'),
            //'Message' => sprintf($this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_RESET_MESSAGE').$msetup,$email['Name'], $email['domain'], $email['Email'], '<a href="'.$email['link'].'" title="Click Here">Click Here</a>'),
            'Message' => sprintf($msetup, $email['Name'], $email['domain'], $email['Email'], '<a href="' . $email['link'] . '" title="Click Here">Click Here</a>'),
            'To' => $email['Email'],
            'From' => CASHMAN_FROM_EMAIL_ADDRESS
        );

        $response = sendEmail($sendEmail);
        if (!$response) {
            $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            $msg .= $this->lang->line('ACCOUNTENT_RESEND_EMAIL_FAILURE');
            $msg .= "</div>";
            $this->session->set_flashdata('clientError', $msg);
        } else {
            $this->account->changeState($userID);
            $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
            $msg .= sprintf($this->lang->line('ACCOUNTENT_RESEND_EMAIL_SUCCESS'), $email['Email']);
            $msg .= "</div>";
            $this->session->set_flashdata('clientError', $msg);
        }
        setRedirect(site_url() . 'client_listing');
    }

    public function clientAccess($id = NULL, $cid = NULL) { 
        $id = $this->encrypt->decode($id);
        $cid = $this->encrypt->decode($cid);
		$this->session->set_userdata('choosedCompanyId',$cid);
        if (empty($id) || !is_numeric($id)) {
            show_404();
        }
        $user = $this->session->userdata('user');

        /* STEP - 1 Get Client login detail */
        $client = $this->account->clientLoginDetail($id);
        $username = $client[0]->Email;
        $password = $client[0]->Password;

        /* STEP - 2 Get User record to store it in session */
        $this->load->model('login');
        $response = $this->login->clientLogin($username, $password, 1);
        $client = set_user_session($response, $user['UserID']);

        $this->session->set_userdata('user', '');
        $this->session->set_userdata('user', $client);

        $status = $this->login->checkStatus($response->ID);
        if (!$status) {
            $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            $msg .= $this->lang->line('CASHMAN_DISABLED_ACCOUNT_MESSAGE');
            $msg .= "</div>";
            $this->session->set_flashdata('loginMessage', $msg);
			$this->session->set_userdata('choosedCompanyId','');
			$this->session->set_userdata('chooseCompanyRequired','no');	
				
            setRedirect(site_url());
        }
        setRedirect(site_url() . 'client_dashboard');
    }

    public function deleteImage() {
        if ($this->input->is_ajax_request()) {
            $id = $this->encrypt->decode($_POST['ID']);
            $response = $this->account->delete_logo($id);
            if ($response) {
                /* Remove the image from the server also */
                if (unlink($response)) {
                    $json['error'] = '';
                } else {
                    $json['error'] = 'error';
                }
            } else {
                $json['error'] = 'error';
            }
            $json = json_encode($json);
            die($json);
        } else {
            show_404();
        }
    }

    public function logout() {
		update_logs('LOGIN/LOGOUT', 'USER_LOGOUT', 'LOGOUT',"","");
        $this->session->sess_destroy();
        setRedirect(site_url() . 'home/');
    }
	#####################################################################
	# Author : Gurdeep Singh											#			
	# Date   : 13 July 2016 											#
	# Params : Companyid ( id )											#
	# Updating company id in session to update selected company detail  #
	#####################################################################
	public function updateablecompanyid() {
		if($this->input->is_ajax_request())
		{
			$companyID = $this->input->post('id');
			if( $companyID == '' || $companyID <= 0 )
			{
				$output['success'] = false;
				echo json_encode($output);exit;
			}
			else{
				$this->session->set_userdata('updateAbleCompanyId',$companyID);
				$output['success'] 	= true;
				echo json_encode($output);exit;
			}
		}
	}
	##############################################################################
	# Author : Gurdeep Singh													 #			
	# Date   : 14 July 2016 													 #
	# Setting session variable for add new company in update client detail page  #
	##############################################################################
	public function addNewCompanySession() {
		if($this->input->is_ajax_request())
		{
			$sessionVal = $this->input->post('addCompany');
			if( $sessionVal != '' && $sessionVal == 'yes')
			{
				$this->session->set_userdata('addNewCompany','yes');
				$this->session->set_userdata('updateAbleCompanyId','');				
			}
			else
				$this->session->set_userdata('addNewCompany','');
			$output['success'] 	= true;
			echo json_encode($output);exit;
		}
	}
	
	public function validateCompanyName() {
		$companyName = $this->input->post('CompanyName');
		$response = $this->account->validateCompanyName($companyName);
		if($response == 1){
			$output['success']   = true;			
		}else{
			$output['success']   = false;	
		}
		echo json_encode($output);exit;	
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */