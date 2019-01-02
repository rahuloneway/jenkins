<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require_once APPPATH . '/libraries/REST_Controller.php';

class Clientapi extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // $this->load->config($config);
    }


    public function login_get()
    {		
		
		
		$username = safe($this->get('uname'));
        $password = do_hash($this->get('password'));
        $deviceId = $this->get('deviceId');
        if (!empty($username)  && !empty($password) && !empty($deviceId) )
        {
            $userData = $this->_get_userdata($username, $password);			
			// Check if the user exist
            if ($userData)
            {
				$this->_get_session($userData);
				$userData = $this->session->userdata('user');
				$userData = (object) $userData;
				$userType = $userData->UserType;
				// Check user type, client only access
                if($userType == 'TYPE_CLI')
                {
					$allCompanies  = $this->_get_usercompanies($userData->UserID,'CID,Name');	
					// Check if its user first login, then create entry in key table
                    if(!$this->_get_key_user($userData->UserID, $deviceId))
                    {
                        $apiKey = $this->createKey($userData, $password, $deviceId);
                    }
                    // Else regenerate key for current user
                    else
                    { 
                        $apiKey = $this->regenerateKey($userData->UserID, $deviceId);
                    }
                    $userDetail = [ 'ID'           => $userData->UserID ,
                                    'Email'        => $userData->Email ,
                                    'Name'         => $userData->Name ,
                                    'CompanyRegNo' => $userData->CompanyRegNo ,
                                    'VatRegNo'     => $userData->Params['VATRegistrationNo'] ,
                                    'ApiKey'       => $apiKey ,
									'Companies'    => $allCompanies
								];
                    $this->response([$userDetail], REST_Controller::HTTP_OK); // OK (200) HTTP response code
                }
                else
                    $this->response([], REST_Controller::HTTP_UNAUTHORIZED); // Unauthorized Access (401)
            }
            else
            {
                $this->response([], REST_Controller::HTTP_NOT_FOUND); // Not Found (404)
            }
        }
        else
        {
            $this->response( [], REST_Controller::HTTP_BAD_REQUEST); // Bad Request (400)
        }
    }

    public function logout_get()
    {
        $userId   = $this->_get_key($this->get('API-KEY'))->UserId;
		$deviceId = $this->get('deviceId');
        $this->_update_key($userId, NULL, $deviceId);
        $this->response(['STATUS'=>REST_Controller::HTTP_OK, 'CONTENT'=>'Logout Successful'], REST_Controller::HTTP_OK);
    }

    public function dashboard_get()
    {
		$companyId = $this->get('companyId');
		$user 	   = $this->session->userdata('user');		
		$deviceId  = $this->get('deviceId');
		if (!empty($companyId) && !empty($user) && !empty($deviceId))
		{
			$this->updateCompanyId($user['UserID'], $deviceId,$companyId);
			
			$this->load->model('login');
			$this->load->model('accountant/account');
			$this->session->set_userdata('choosedCompanyId',$companyId);		
			
			$client   = $this->account->clientLoginDetail($user['UserID']);
			$username = $client[0]->Email;
			$password = $client[0]->Password;			
			$clientData 	= $this->login->clientLogin($username, $password, 0);					
			$user 			= set_user_session($clientData);
			$user['AcType'] = $clientData->UserType;
			$this->session->set_userdata('user', '');
			$this->session->set_userdata('user', $user);	
			$user 	   = $this->session->userdata('user');
			
			$this->load->model('clients');
			$this->load->model('clients/bank');
			
			$data['Statistics']         = $this->clients->statistics($user['UserID']);			
			$data['Balances']           = $this->clients->get_balances($data['Statistics']['filed_years']);
			$data['CurrentBalanceDate'] = ($this->bank->get_current_balance() == 0) ? NULL : current($this->bank->get_current_balance());
			$data['ImportantDates']     = $this->clients->get_important_dates();
			if(!empty($data['ImportantDates']))
			{
				foreach( $data['ImportantDates'] as $key=>$datesss )
				{
					$data['ImportantDates'][$key]['DaysLeft'] = str_replace( ' days','',$datesss['DaysLeft']);
					
				}
			}
			$this->load->model('clients/dividends');
			$data['share_holders'] = $this->dividends->getShareHoldersList();
			unset($data['share_holders'][0]);
			$this->response([$data], REST_Controller::HTTP_OK);
		}
		else
        {
            $this->response( [], REST_Controller::HTTP_BAD_REQUEST); // Bad Request (400)
        }
    }

    public function invoices_get()
    {
        $this->load->model('clients');

        $page          = $this->get('page');
        $pageLimit     = $this->config->item('rest_page_limit');
        $data['Total'] = $this->clients->totalInvoices();
		
		//die('====');
		
        if(isset($page))
        {
            $page = $pageLimit * $page;
            if($page < $data['Total'])
            {
                $data['Invoices'] = $this->clients->getInvoiceList($pageLimit, $page);
                $this->response([$data], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response([], REST_Controller::HTTP_CONFLICT);
            }
        }
        else
        {
            $this->response( [], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function invoice_get()
    {
        $this->load->model('clients');

        $itemId['InvoiceID'] = $this->get('itemId');
        if(isset($itemId['InvoiceID']))
        {
            $data['Invoice'] = $this->clients->getInvoiceItem($itemId);
            $this->response([$data], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response( [], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function invoicePdf_get()
    {
        $itemId['InvoiceID'] = $this->get('itemId');
        if(isset($itemId['InvoiceID']))
        {
            $this->load->model('clients');

            $user                 = $this->session->userData('user');
            $data['CompanyEmail'] = $user['CompanyEmail'];
            $data['item']         = $this->clients->getInvoiceItem($itemId);
			if($data['item'])
            {
                $data['Country'] = countryName($this->clients->getCountry($user['UserID']));
				$html            = $this->load->view('client/invoices/invoice_pdf', $data, true);
				$this->response([generateApiPDF($data['item']['InvoiceNumber'], $html)], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response([], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        else
        {
            $this->response( [], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function invoice_post()
    { 
        $this->load->model('clients');

        if(NULL !=($this->post('Data')) && NULL !=($this->post('Description')) && NULL !=($this->post('Quantity')) && NULL !=($this->post('UnitPrice')) && NULL !=($this->post('Vat')))
        {
            $postData        = explode('|', $this->post('Data'));
            $postDescription = (array_filter(explode('|', $this->post('Description'))));
            $postQuantity    = (array_filter(explode('|', $this->post('Quantity'))));
            $postUnitPrice   = (array_filter(explode('|', $this->post('UnitPrice'))));
            $postVat         = (array_filter(explode('|', $this->post('Vat'))));

            if($postData[0] == 0 )
            {
                $customerName    = $postData[4];
                $customerAddress = $postData[5];
            }
            else
            {
                $invoiceUser     = $this->clients->getcustomerDetail($postData[0]);
                $customerName    = $invoiceUser[0]->Name;
                $customerAddress = $invoiceUser[0]->Address;
            }
            $data = array(  'customer'          => $postData[0] ,
                            'invoiceDate'       => $postData[1] ,
                            'InvoiceDate'       => $postData[2] ,
                            'bankdetail'        => ($postData[3] == 1 ) ? 'on' : (($postData[3] == 0) ? NULL : $this->response(['STATUS'=>REST_Controller::HTTP_NOT_FOUND, 'CONTENT'=>'Incorrect value for Bank Details'], REST_Controller::HTTP_CONFLICT)) ,
                            'customerName'      => $customerName ,
                            'customerAddress'   => $customerAddress ,
                            'description'       => $postDescription ,
                            'quantity'          => $postQuantity ,
                            'unitprice'         => $postUnitPrice ,
                            'vat'               => $postVat ,
                            'delinvoiceId'      => '' ,
                            'bank_statement_id' => '' ,
                            'ajax_add'          => '' ,
                            'invoice_type'      => ($postData[6] == 1) ? ' ' : (($postData[6] == 2) ? 'crn' : $this->response(['STATUS'=>REST_Controller::HTTP_NOT_FOUND, 'CONTENT'=>'Wrong Invoice Type'], REST_Controller::HTTP_CONFLICT)) ,
                            'bank_paid_date'    => '' ,
                            'task'              => ($postData[7] == 1) ? 'save' : (($postData[7] == 2) ? 'create' : $this->response(['STATUS'=>REST_Controller::HTTP_NOT_FOUND, 'CONTENT'=>'Wrong Invoice Task'], REST_Controller::HTTP_CONFLICT)) ,
                            'call'              => 'none' , );
			
            $result = $this->clients->createInvoice($data);
			$result = (array) $result;
			$result = (object) $result;
			$this->response($result, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response( [], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function invoice_put()
    {
        $this->load->model('clients');

        if(NULL !=$this->put('InvoiceID') && NULL !=$this->put('Data') && NULL !=$this->put('ItemID') && NULL !=$this->put('Description') && NULL !=$this->put('Quantity') && NULL !=$this->put('UnitPrice') && NULL !=$this->put('Vat'))
        {
            $putInvoiceID   = $this->put('InvoiceID');
            $putData        = explode('|', $this->put('Data'));
            $putItemId      = (array_filter(explode('|', $this->put('ItemID'))));
            $putDescription = (array_filter(explode('|', $this->put('Description'))));
            $putQuantity    = (array_filter(explode('|', $this->put('Quantity'))));
            $putUnitPrice   = (array_filter(explode('|', $this->put('UnitPrice'))));
            $putVat         = (array_filter(explode('|', $this->put('Vat'))));
            $putDelItemId   = (implode("," , (array_filter(explode('|', $this->put('DelItemId')), trim))));
            // $putDelItemId   = (rtrim(str_replace('|', ',', $this->put('DelItemId')), ','));

            /** To delete Invoice Items */
            if(!empty($putDelItemId) && $putDelItemId != 0 && $putDelItemId != NULL)
            {
                $delResult = $this->clients->delinvoiceItem($putDelItemId);
            }

            /** To encrypt Invoice Items Id */
            foreach ($putItemId as &$value)
            {
                $value = $this->encrypt->encode($value);
            }

            if($putData[0] == 0 )
            {
                $customerName    = $putData[4];
                $customerAddress = $putData[5];
            }
            else
            {
                $invoiceUser     = $this->clients->getcustomerDetail($putData[0]);
                $customerName    = $invoiceUser[0]->Name;
                $customerAddress = $invoiceUser[0]->Address;
            }

            // $duedate = explode('-', $putData[1]);
            // $DueDate = $duedate[1]."-".$duedate[0]."-".$duedate[2];
            // $invoicedate = explode('-', $putData[2]);
            // $InvoiceDate = $invoicedate[1]."-".$invoicedate[0]."-".$invoicedate[2];

            $data = array(  'customer'          => $putData[0] ,
                            'invoiceDate'       => $putData[1] ,
                            'InvoiceDate'       => $putData[2] ,
                            'bankdetail'        => ($putData[3] == 1 ) ? 'on' : (($putData[3] == 0) ? NULL : $this->response(['STATUS'=>REST_Controller::HTTP_NOT_FOUND, 'CONTENT'=>'Incorrect value for Bank Details'], REST_Controller::HTTP_CONFLICT)) ,
                            'customerName'      => $customerName ,
                            'customerAddress'   => $customerAddress ,
                            'InvoiceID'         => $this->encrypt->encode($putInvoiceID) ,
                            'ItemID'            => $putItemId ,
                            'description'       => $putDescription ,
                            'quantity'          => $putQuantity ,
                            'unitprice'         => $putUnitPrice ,
                            'vat'               => $putVat ,
                            'delinvoiceId'      => $putDelItemId ,
                            'bank_statement_id' => '' ,
                            'ajax_add'          => '' ,
                            'invoice_type'      => ($putData[6] == 1) ? ' ' : (($putData[6] == 2) ? 'crn' : $this->response(['STATUS'=>REST_Controller::HTTP_NOT_FOUND, 'CONTENT'=>'Wrong Invoice Type'], REST_Controller::HTTP_CONFLICT)) ,
                            'bank_paid_date'    => '' ,
                            'task'              => ($putData[7] == 1) ? 'update' : (($putData[7] == 2) ? 'create' : $this->response(['STATUS'=>REST_Controller::HTTP_NOT_FOUND, 'CONTENT'=>'Wrong Invoice Task'], REST_Controller::HTTP_CONFLICT)) ,
                            'call'              => 'none' , );

            $result = $this->clients->updateInvoice($data);
            $this->response(['STATUS'=>REST_Controller::HTTP_OK, 'CONTENT'=>$result], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response( ['STATUS'=>REST_Controller::HTTP_BAD_REQUEST, 'CONTENT' => 'Incomplete request, check request content'], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function invoiceUserList_get()
    {
        $this->load->model('clients');

        $data['UserList'] = $this->clients->getcustomerList();
        $this->response([$data], REST_Controller::HTTP_OK);
    }

    public function invoiceUserDetail_get()
    {
        $this->load->model('clients');

        $id   = $this->get('userId');
        $data = $this->clients->getcustomerDetail($id);
		if(!empty($data))
		{ 
			if($data[0]->paymentterms)
				$data[0]->paymentterms = date('d-m-Y', strtotime("+".$data[0]->paymentterms." days")); 
			else
				$data[0]->paymentterms = date('d-m-Y');
		}
        $this->response([$data], REST_Controller::HTTP_OK);
    }

    public function expenses_get()
    {
        $this->load->model('clients/expense');
        $page          = $this->get('page');
        $pageLimit     = $this->config->item('rest_page_limit');
        $data['Total'] = $this->expense->totalExpenses();
        if(isset($page))
        {
            $page = $pageLimit * $page;
            if($page < $data['Total'])
            {
                $data['Expenses'] = $this->expense->getItems($pageLimit, $page);
                $this->response([$data], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response([], REST_Controller::HTTP_CONFLICT);
            }
        }
        else
        {
            $this->response( [], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function expense_get()
    {
        $this->load->model('clients/expense');
        $itemId = $this->get('itemId');
        if(isset($itemId))
        {
            $data['Expense'] = $this->expense->getItem($itemId);
            $this->response([$data], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response( [], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function expense_post()
    {
        $this->load->model('clients/expense');

        $user     = $this->session->userdata('user');
        $postData = explode('|', $this->post('Data'));

        if(NULL != ($this->post('ExpenseDate')))
        {
            $postExpenseDate        =  array_filter(explode('|' ,$this->post('ExpenseDate')));
            $postExpenseCategory    =  array_filter(explode('|' ,$this->post('ExpenseCategory')));
            $postExpenseDescription =  array_filter(explode('|' ,$this->post('ExpenseDescription')));
            $postExpenseAmount      =  array_filter(explode('|' ,$this->post('ExpenseAmount')));

            $totalExpenseVAT    = 0;
            $totalExpenseAmount = 0;

            if($user['VAT_TYPE'] == 'stand')
            {
                $postExpenseVat =  array_filter(explode('|' ,$this->post('ExpenseVat')));

                for ($i = 0; $i < sizeof($postExpenseAmount); $i++)
                {
                    $totalExpenseVAT    += $postExpenseVat[$i];
                    $totalExpenseAmount += $postExpenseAmount[$i] + $postExpenseVat[$i];
                }
            }
            else
            {
                foreach ($postExpenseAmount as $value)
                {
                    $totalExpenseAmount += $value;
                }
            }
        }
        else
        {
            $totalExpenseVAT    = 0;
            $totalExpenseAmount = 0;
        }

        if(NULL != ($this->post('MileageDate')))
        {
            $postMileageDate     =  array_filter(explode('|' ,$this->post('MileageDate')));
            $postMileageFrom     =  array_filter(explode('|' ,$this->post('MileageFrom')));
            $postMileageTo       =  array_filter(explode('|' ,$this->post('MileageTo')));
            $postMileageMethod   =  array_filter(explode('|' ,$this->post('MileageMethod')));
            $postMileagePurpose  =  array_filter(explode('|' ,$this->post('MileagePurpose')));
            $postMileageMiles    =  array_filter(explode('|' ,$this->post('MileageMiles')));

            $totalMileageMiles = 0;
            $totalMilageAmount = 0;
            $postMileageAmount  = array();

            foreach ($postMileageMiles as $value)
            {
                $totalMileageMiles += $value;
            }

            for ($i = 0; $i < sizeof($postMileageMethod) ; $i++)
            {
                $prevMile = ( $postMileageMethod[$i] == 32) ? $this->expense->get_car_previous_miles($postData[0], mDate($postMileageDate[$i])) : 0 ;
				if( $postMileageMethod[$i] == 32 )
					$postMileageAmount[$i] = $this->calMileage($prevMile, $postMileageMiles[$i], 0, 0);
					//$postMileageAmount[$i] = $this->expenseCalMileage_get($postMileageMethod[$i], $postMileageMiles[$i], $prevMile, true);
				else if( $postMileageMethod[$i] == 31 )
					$postMileageAmount[$i] = $this->calMileage(0, 0, $postMileageMiles[$i], 0);
					//$postMileageAmount[$i] = $this->expenseCalMileage_get($postMileageMethod[$i], $postMileageMiles[$i], $prevMile, true);
				else
					$postMileageMiles[$i] = $this->calMileage(0, 0, 0, $postMileageMiles[$i]);
					//$postMileageAmount[$i] = $this->expenseCalMileage_get($postMileageMethod[$i], $postMileageMiles[$i], $prevMile, true);
                $totalMilageAmount += $postMileageAmount[$i];
            }
        }
        else
        {
            $totalMilageAmount = 0;
        }

        if( $postData[5] == 1)
        {
            $expenseNumber = 'SAVED AS DRAFT';
        }
        elseif ($postData[5] == 2)
        {
            $expenseNumber = $this->expense->max_id();
            $name = getEmployeeInfo($postData[0]);
            $name = substr(strtoupper($name), 0, 3);
            $expenseNumber = $name . '-' . date('Y') . date('m') . date('d') . '-' . ($postData[1] == 1 ? 'EXPENSE' : 'CREDITCARD') . '-' . $expenseNumber;
        }

        $totalAmount = $totalExpenseAmount + $totalMilageAmount;

        $data = array(  'EmployeeID'       => $postData[0],
						'CustomerCompanyID' => $user['CompanyID'],
                        'ExpenseType'      => $postData[1] == 1 ? 'EXPENSE' : 'CREDITCARD' ,
                        'ExpenseNumber'    => $expenseNumber,
                        'Month'            => $postData[2],
                        'Year'             => $postData[3],
                        'ExpenseDate'      => $postData[3].'-'.$postData[2].'-'.'01',
                        'TotalAmount'      => $totalAmount,
                        'TotalMiles'       => ($totalMileageMiles == 0) ? 0 : $totalMileageMiles,
                        'AddedOn'          => date('Y-m-d'),
                        'AddedBy'          => $user['UserID'],
                        'PaidOn'           => isset($postData[4]) ? $postData[4] : '' ,
                        'Status'           => $postData[5],
                        'Reconciled'       => 0,
                        'FileID'           => '',
                        'AccountantAccess' => clientAccess() ,
                        'TotalVATAmount'   => ($totalExpenseVAT == 0) ? 0 : $totalExpenseVAT );
		//echo "<pre>";print_r($data);
        $result = $this->expense->save($data);

        if(NULL != ($this->post('ExpenseDate')))
        {
            for ($i = 0; $i < sizeof($postExpenseCategory); $i++)
            {
                $expenseItem[$i] = array(   'ExpenseID'        => $result ,
                                            'ItemType'         => 'EXPENSE' ,
                                            'Category'         => $postExpenseCategory[$i] ,
                                            'ItemDate'         => $postExpenseDate[$i] ,
                                            'LocationFrom'     => '' ,
                                            'LocationTo'       => '' ,
                                            'Purpose'          => '' ,
                                            'Miles'            => 0 ,
                                            'Amount'           => $postExpenseAmount[$i] ,
                                            'AddedOn'          => date('Y-m-d') ,
                                            'AddedBy'          => $user['UserID'] ,
                                            'AccountantAccess' => clientAccess() ,
                                            'FileID'           => '' ,
                                            'Reconciled'       => 0 ,
                                            'Status'           => $postData[5] ,
                                            'VATAmount'        => (isset($postExpenseVat)) ? $postExpenseVat[$i] : 0 ,
                                            'Description'      => $postExpenseDescription[$i] );
            }
        }
        else
        {
            $expenseItem = array();
        }

        if(NULL != ($this->post('MileageDate')))
        {
            for ($i = 0; $i < sizeof($postMileageDate); $i++)
            {
                $mileageItem[$i] = array(   'ExpenseID'        => $result ,
                                            'ItemType'         => 'MILEAGE' ,
                                            'Category'         => $postMileageMethod[$i] ,
                                            'ItemDate'         => $postMileageDate[$i] ,
                                            'LocationFrom'     => $postMileageFrom[$i] ,
                                            'LocationTo'       => $postMileageTo[$i] ,
                                            'Purpose'          => $postMileagePurpose[$i] ,
                                            'Miles'            => $postMileageMiles[$i] ,
                                            'Amount'           => $postMileageAmount[$i] ,
                                            'AddedOn'          => date('Y-m-d') ,
                                            'AddedBy'          => $user['UserID'] ,
                                            'AccountantAccess' => clientAccess() ,
                                            'FileID'           => '' ,
                                            'Reconciled'       => 0 ,
                                            'Status'           => ($postData[4]) ? 1 : 0 ,
                                            'VATAmount'        => 0 ,
                                            'Description'      => '' );
            }
        }
        else
        {
            $mileageItem = array();
        }
		//echo "<pre>";print_r($item);die;
        $item = array_merge($expenseItem, $mileageItem);
        $itemResult = $this->expense->saveItems($item);
		
		$expenseNumber = (array) $expenseNumber;
		$expenseNumber = (object) $expenseNumber;
        $this->response($expenseNumber, REST_Controller::HTTP_OK);
    }

    public function expense_put()
    {
        $this->load->model('clients/expense');

        $user    = $this->session->userdata('user');
		//echo "<pre>";print_r($user);die;
		$id      = $this->put('ExpenseID');
        $putData = explode('|', $this->put('Data'));
		
        $putDelItemId   = (implode("," , (array_filter(explode('|', $this->put('DelItemId')), trim))));
        // $putDelItemId   = (rtrim(str_replace('|', ',', $this->put('DelItemId')), ','));

        /** To delete Expense Items */
        if(!empty($putDelItemId) && $putDelItemId != 0 && $putDelItemId != NULL)
        {
            $delResult = $this->expense->delexpItem($putDelItemId);
        }

        if(NULL != ($this->put('ExpenseDate')))
        {
            $putExpenseId          =  array_filter(explode('|' ,$this->put('ExpenseId')));
            $putExpenseDate        =  array_filter(explode('|' ,$this->put('ExpenseDate')));
            $putExpenseCategory    =  array_filter(explode('|' ,$this->put('ExpenseCategory')));
            $putExpenseDescription =  array_filter(explode('|' ,$this->put('ExpenseDescription')));
            $putExpenseAmount      =  array_filter(explode('|' ,$this->put('ExpenseAmount')));

            $totalExpenseVAT    = 0;
            $totalExpenseAmount = 0;

            if($user['VAT_TYPE'] == 'stand')
            {
                $putExpenseVat =  array_filter(explode('|' ,$this->put('ExpenseVat')));

                for ($i = 0; $i < sizeof($putExpenseAmount); $i++)
                {
                    $totalExpenseVAT    += $putExpenseVat[$i];
                    $totalExpenseAmount += $putExpenseAmount[$i] + $putExpenseVat[$i];
                }
            }
            else
            {
                foreach ($putExpenseAmount as $value)
                {
                    $totalExpenseAmount += $value;
                }
            }
        }
        else
        {
            $totalExpenseVAT    = 0;
            $totalExpenseAmount = 0;
        }

        if(NULL != ($this->put('MileageDate')))
        {
            $putMileageId      =  array_filter(explode('|' ,$this->put('MileageId')));
            $putMileageDate    =  array_filter(explode('|' ,$this->put('MileageDate')));
            $putMileageFrom    =  array_filter(explode('|' ,$this->put('MileageFrom')));
            $putMileageTo      =  array_filter(explode('|' ,$this->put('MileageTo')));
            $putMileageMethod  =  array_filter(explode('|' ,$this->put('MileageMethod')));
            $putMileagePurpose =  array_filter(explode('|' ,$this->put('MileagePurpose')));
            $putMileageMiles   =  array_filter(explode('|' ,$this->put('MileageMiles')));

            $totalMileageMiles = 0;
            $totalMilageAmount = 0;
            $putMileageAmount  = array();

            foreach ($putMileageMiles as $value)
            {
                $totalMileageMiles += $value;
            }

            for ($i = 0; $i < sizeof($putMileageMethod) ; $i++)
            {
                $prevMile = ( $putMileageMethod[$i] == 32) ? $this->expense->get_car_previous_miles($putData[0], mDate($putMileageDate[$i])) : 0 ;
                
				if( $putMileageMethod[$i] == 32 )
					$putMileageAmount[$i] = $this->calMileage($prevMile, $putMileageMiles[$i], 0, 0);
				else if( $putMileageMethod[$i] == 31 )
					$putMileageAmount[$i] = $this->calMileage(0, 0, $putMileageMiles[$i], 0);
				else
					$putMileageAmount[$i] = $this->calMileage(0, 0, 0, $putMileageMiles[$i]);
                $totalMilageAmount += $putMileageAmount[$i];				
            }
        }
        else
        {
            $totalMilageAmount = 0;
        }

        if( $putData[5] == 1)
        {
            $expenseNumber = 'SAVED AS DRAFT';
        }
        elseif ($putData[5] == 2)
        {
            $expenseNumber = $id;
            $name = getEmployeeInfo($putData[0]);
            $name = substr(strtoupper($name), 0, 3);
            $expenseNumber = $name . '-' . date('Y') . date('m') . date('d') . '-' . ($putData[1] == 1 ? 'EXPENSE' : 'CREDITCARD') . '-' . $expenseNumber;
        }

        $totalAmount = $totalExpenseAmount + $totalMilageAmount;

        $data = array(  'EmployeeID'       => $putData[0],
						//'CustomerCompanyID' => $user['CompanyID'],
                        'ExpenseType'      => $putData[1] == 1 ? 'EXPENSE' : 'CREDITCARD' ,
                        'ExpenseNumber'    => $expenseNumber,
                        'Month'            => $putData[2],
                        'Year'             => $putData[3],
                        'TotalAmount'      => $totalAmount,
                        'TotalMiles'       => ($totalMileageMiles == 0) ? 0 : $totalMileageMiles,
                        'AddedOn'          => date('Y-m-d'),
                        'AddedBy'          => $user['UserID'],
                        'PaidOn'           => isset($putData[4]) ? $putData[4] : '' ,
                        'Status'           => $putData[5],
                        'Reconciled'       => 0,
                        'AccountantAccess' => clientAccess() ,
                        'TotalVATAmount'   => ($totalExpenseVAT == 0) ? 0 : $totalExpenseVAT );

        $result = $this->expense->update($data, $id);

        if(NULL != ($this->put('ExpenseDate')))
        {
            for ($i = 0; $i < sizeof($putExpenseCategory); $i++)
            {
                $expenseItem[$i] = array(   'ID'               => $putExpenseId[$i] ,
                                            'ExpenseID'        => $id ,
                                            'ItemType'         => 'EXPENSE' ,
                                            'Category'         => $putExpenseCategory[$i] ,
                                            'ItemDate'         => $putExpenseDate[$i] ,
                                            'LocationFrom'     => '' ,
                                            'LocationTo'       => '' ,
                                            'Purpose'          => '' ,
                                            'Miles'            => 0 ,
                                            'Amount'           => $putExpenseAmount[$i] ,
                                            'AddedOn'          => date('Y-m-d') ,
                                            'AddedBy'          => $user['UserID'] ,
                                            'AccountantAccess' => clientAccess() ,
                                            'FileID'           => '' ,
                                            'Reconciled'       => 0 ,
                                            'Status'           => $putData[5] ,
                                            'VATAmount'        => (isset($putExpenseVat)) ? 0 : $putExpenseVat[$i] ,
                                            'Description'      => $putExpenseDescription[$i] );
            }
        }
        else
        {
            $expenseItem = array();
        }

        if(NULL != ($this->put('MileageDate')))
        {
            for ($i = 0; $i < sizeof($putMileageDate); $i++)
            {
                $mileageItem[$i] = array(   'ID'               => $putMileageId[$i] ,
                                            'ExpenseID'        => $id ,
                                            'ItemType'         => 'MILEAGE' ,
                                            'Category'         => $putMileageMethod[$i] ,
                                            'ItemDate'         => $putMileageDate[$i] ,
                                            'LocationFrom'     => $putMileageFrom[$i] ,
                                            'LocationTo'       => $putMileageTo[$i] ,
                                            'Purpose'          => $putMileagePurpose[$i] ,
                                            'Miles'            => $putMileageMiles[$i] ,
                                            'Amount'           => $putMileageAmount[$i] ,
                                            'AddedOn'          => date('Y-m-d') ,
                                            'AddedBy'          => $user['UserID'] ,
                                            'AccountantAccess' => clientAccess() ,
                                            'FileID'           => '' ,
                                            'Reconciled'       => 0 ,
                                            'Status'           => ($putData[4]) ? 1 : 0 ,
                                            'VATAmount'        => 0 ,
                                            'Description'      => '' );
            }
        }
        else
        {
            $mileageItem = array();
        }

        $item = array_merge($expenseItem, $mileageItem);
        $itemResult = $this->expense->updateItems($item);		
		
		$expenseNumber = (array) $expenseNumber;
		$expenseNumber = (object) $expenseNumber;
        $this->response($expenseNumber, REST_Controller::HTTP_OK);
		
    }

	public function calMileage($prev_car_miles = 0, $car_miles = 0, $bike_miles = 0, $bicycle_miles = 0, $call = '') 
	{
        $car_cost 		= 0;
        $bike_cost 		= 0;
        $bicycle_cost 	= 0;
        $total_cost 	= 0;
        /* CASE - ONE : Car */
        if ($prev_car_miles > MILEAGE_DISTANCE_LIMIT) 
		{
            $car_cost = ($car_miles * MILEAGE_EXCEED_COST) / 100;
        } 
		else 
		{
            $total_miles = $prev_car_miles + $car_miles;
            if ($total_miles < MILEAGE_DISTANCE_LIMIT) 
			{
                $car_cost = ($car_miles * CAR_MILEAGE_COST) / 100;
            } 
			else 
			{
                $car_cost_am = $total_miles - MILEAGE_DISTANCE_LIMIT;
                $car_cost_bm = $car_miles - $car_cost_am;
                $car_cost 	 = ($car_cost_am * MILEAGE_EXCEED_COST) / 100 + ($car_cost_bm * CAR_MILEAGE_COST) / 100;
            }
        }
        /* CASE - TWO : Bike */
        if ($bike_miles > 0) 
		{
            $bike_cost = ($bike_miles * BIKE_MILEAGE_COST) / 100;
        }
        /* CASE - THREE : Bicycle */
        if ($bicycle_miles > 0) 
		{
            $bicycle_cost = ($bicycle_miles * CYCLE_MILEAGE_COST) / 100;
        }
        $total_cost = $car_cost + $bike_cost + $bicycle_cost;
        return $total_cost;
    }

    public function expenseCalMileage_get()
    { 
        $vehicleType = $this->get('vehicleType');
        $miles       = $this->get('miles');
        $prevMiles   = $this->get('previousMiles');
        
        switch ($vehicleType) {

            /* CASE - Bike */
            case 31:
                $data['Amount'] = ($miles * BIKE_MILEAGE_COST) / 100;
                if($check != true)
                {
                    $this->response([$data], REST_Controller::HTTP_OK);
                    break;
                }
                else
                {
                    return $data['Amount'];
                    break;
                }
            /* CASE - Car */
            case 32:
                if ($prevMiles > MILEAGE_DISTANCE_LIMIT)
                {
                    $data['Amount'] = ($miles * MILEAGE_EXCEED_COST) / 100;
                }
                else
                {
                    $totalMiles = $prevMiles + $miles;
                    if ($totalMiles < MILEAGE_DISTANCE_LIMIT)
                    {
                        $data['Amount'] = ($miles * CAR_MILEAGE_COST) / 100;
                    }
                    else
                    {
                        $milesAfter     = $totalMiles - MILEAGE_DISTANCE_LIMIT;
                        $milesBefore    = $miles - $milesAfter;
                        $data['Amount'] = ($milesAfter * MILEAGE_EXCEED_COST) / 100 + ($milesBefore * CAR_MILEAGE_COST) / 100;
                    }
                }
                if($check != true)
                {
                    $this->response([$data], REST_Controller::HTTP_OK);
                    break;
                }
                else
                {
                    return $data['Amount'];
                    break;
                }


            /* CASE - Bicycle */
            case 33:
                $data['Amount'] = ($miles * CYCLE_MILEAGE_COST) / 100;
                if($check != true)
                {
                    $this->response([$data], REST_Controller::HTTP_OK);
                    break;
                }
                else
                {
                    return $data['Amount'];
                    break;
                }

            default:
                $this->response([], REST_Controller::HTTP_CONFLICT);
                break;
        }
    }


    public function expenseEmployeeList_get()
    {
        $this->load->model('clients/expense');
        $data['Users'] = $this->expense->getEmployeeList('include');
        $this->response([$data], REST_Controller::HTTP_OK);
    }

    public function expenseCategoryList_get()
    {
        $this->load->model('clients/expense');
		$parentcat = getCategoryParent('GEN');
		if(!empty($parentcat))
		{
			foreach($parentcat as $key=>$value)
			{
				$childCat = getCategoryParentChild($value->id);
				$value->childCats = $childCat;
			}
		}			
        $data['Categories'] = $parentcat;
        $this->response([$data], REST_Controller::HTTP_OK);
    }

    public function expenseVehicleList_get()
    {
        $this->load->model('clients/expense');

        $data['VehicleList'] = $this->expense->getMethods();
        $this->response([$data], REST_Controller::HTTP_OK);
    }

    public function expenseVatType_get()
    {
        $this->load->model('clients/expense');

        $data['VatType'] = $this->expense->getVatType();
        $this->response([$data], REST_Controller::HTTP_OK);
    }

    public function expenseMiles_get()
    {
        $this->load->model('clients/expense');

        $id   = $this->get('userId');
        $date = $this->get('date');
        if(isset($id) && isset($date))
        {
            $data['Miles'] = $this->expense->get_car_previous_miles($id , mDate($date));
            $this->response([$data], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response( [], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function dividends_get()
    {
        $this->load->model('clients/dividends');

        $page          = $this->get('page');
        $pageLimit     = $this->config->item('rest_page_limit');
        $data['Total'] = $this->dividends->totalDividends();
        if(isset($page))
        {
            $page = $pageLimit * $page;
            if($page < $data['Total'])
            {
                $data['Dividends'] = $this->dividends->getItems($pageLimit, $page);
                $this->response(['STATUS'=>REST_Controller::HTTP_OK, 'CONTENT'=>$data], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response(['STATUS'=>REST_Controller::HTTP_NOT_FOUND, 'CONTENT'=>'Wrong page no.'], REST_Controller::HTTP_CONFLICT);
            }
        }
        else
        {
            $this->response( ['STATUS'=>REST_Controller::HTTP_BAD_REQUEST, 'CONTENT' => 'Incomplete request, without page number'], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function dividend_get()
    {
        $this->load->model('clients/dividends');

        $itemId = $this->get('itemId');
        if(isset($itemId))
        {
            $data['Dividend'] = $this->dividends->getItem($itemId);
            $data['Dividend']['Address'] = (unserialize($data['Dividend']['Address']));
            $this->response(['STATUS'=>REST_Controller::HTTP_OK, 'CONTENT'=>$data], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response( ['STATUS'=>REST_Controller::HTTP_BAD_REQUEST, 'CONTENT' => 'Incomplete request, without dividend id'], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function dividendPdf_get()
    {
        $id = $this->get('itemId');
        if(isset($id))
        {
            $this->load->model('clients/dividends');

            $user         = $this->session->userdata('user');
            $data['item'] = $this->dividends->getItem($id);
            $acc_id       = clientAccess();
            // $include_signature = end($id);
            if (!empty($acc_id))
            {
                $accountant_detail = $this->dividends->get_accountant_signature($acc_id);
            }
            else
            {
                $accountant_detail = array( 'Salutation'       => '' ,
                                            'DOB'              => '' ,
                                            'NI_NUMBER'        => '' ,
                                            'UTR'              => '' ,
                                            'AddressTwo'       => '' ,
                                            'AddressThree'     => '' ,
                                            'ImageLink'        => '' ,
                                            'DigitalSignature' => '' ,
                                            'EmploymentLevel'  => '' );
            }
            if ($data['item'])
            {
                $data['accountant_detail'] = $accountant_detail;
                $data['include_signature'] = $include_signature;
                $data['CompanyName']       = companyName($user['CompanyID']);
                $data['Company_details']   = $this->dividends->companyDetails($user['CompanyID']);
                if ($data['item']['PaidOn'] < $user['CompanyEndDate'])
                {
                    $end_date = $user['CompanyEndDate'];
                }
                else
                {
                    $end_date = date('Y-m-d', strtotime('+1 year', strtotime($user['CompanyEndDate'])));
                    if ($data['item']['PaidOn'] > $end_date)
                    {
                        $end_date = date('Y-m-d', strtotime('+1 year', strtotime($end_date)));
                    }
                }
                $data['YearEndDate'] = $end_date;
                $data['Directors']   = $this->dividends->getDirectorsList();
                $data['task']        = 'meeting';

                $html1 = $this->load->view('client/dividend/pdf', $data, TRUE);

                $data['task'] = 'certificate';
                $name         = $data['item']['VoucherNumber'];
                $this->load->view('client/dividend/pdf', $data);

                $html2 = $this->load->view('client/dividend/pdf', $data, TRUE);

                $this->response(['STATUS'=>REST_Controller::HTTP_OK, 'CONTENT'=>generateApiPDF($name, $html1, $html2)], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response(['STATUS'=>REST_Controller::HTTP_INTERNAL_SERVER_ERROR, 'CONTENT'=>'Request Could not be completed, Check dividend Id'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        else
        {
            $this->response( ['STATUS'=>REST_Controller::HTTP_BAD_REQUEST, 'CONTENT' => 'Incomplete request, without dividend id'], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function dividend_post()
    {
        $this->load->model('clients/dividends');

        $user        = $this->session->userdata('user');
        $postData    = explode('|', $this->post('Data'));

        $taxAmount   = number_format((float)(($postData[2]/ DIVIDEND_TAX_PERCENT) / 100), 2, '.', '') ;
        $grossAmount = $postData[2] + $taxAmount;

        $data = array(  'ShareholderID'      =>  $postData[0] ,
                        'CompanyID'          =>  $user['CompanyID'] ,
                        'DividendDate'       =>  $postData[1] ,
                        'GrossAmount'        =>  $grossAmount ,
                        'TaxAmount'          =>  $taxAmount ,
                        'NetAmount'          =>  $postData[2] ,
                        'PaidByDirectorLoan' =>  ($postData[3] == '1') ? 1 : (($postData[3] == '0') ? 0 : ' ') ,
                        'AddedOn'            =>  date('Y-m-d') ,
                        'AddedBy'            =>  $user['UserID'] ,
                        // 'PaidOn'             =>  ($postData[4] == 2) ? ((empty($postData[5])) ? $this->response( ['STATUS'=>REST_Controller::HTTP_BAD_REQUEST, 'CONTENT' => 'Incomplete request, without Paid Date'], REST_Controller::HTTP_NOT_ACCEPTABLE) : $paidDate = $postData[5]) : '' ,
                        'PaidOn'             =>  '' ,
                        'Status'             =>  ($postData[4] == 1) ? '1' : $this->response(['STATUS'=>REST_Controller::HTTP_NOT_FOUND, 'CONTENT'=>'Incorrect Value for Status'], REST_Controller::HTTP_CONFLICT) ,
                        'AccountantAccess'   =>  clientAccess() ,
                        'BankStatement'      =>  '' ,
                        'Address'            =>  serialize($user['Params']) );

        $result = $this->dividends->addDividend($data);
        $this->response(['STATUS'=>REST_Controller::HTTP_OK, 'CONTENT'=>$result], REST_Controller::HTTP_OK);
    }

    public function dividend_put()
    {
        $this->load->model('clients/dividends');

        $id          = $this->put('itemId');
        $user        = $this->session->userdata('user');
        $putData     = explode('|', $this->put('Data'));

        $taxAmount   = ($putData[2]/ DIVIDEND_TAX_PERCENT) / 100;
        $grossAmount = $putData[2] + $taxAmount;

        $data = array(  'ShareholderID'      => $putData[0] ,
                        'CompanyID'          => $user['CompanyID'] ,
                        'DividendDate'       => $putData[1] ,
                        'GrossAmount'        => $grossAmount ,
                        'TaxAmount'          => $taxAmount ,
                        'NetAmount'          => $putData[2] ,
                        'PaidByDirectorLoan' => ($putData[3] == 'on') ? 1 : ($putData[3] == 'off') ? 0 : ' ' ,
                        'AddedBy'            => $user['UserID'] ,
                        // 'PaidOn'             => ($putData[4] == 2) ? ((empty($putData[5])) ? $this->response( ['STATUS'=>REST_Controller::HTTP_BAD_REQUEST, 'CONTENT' => 'Incomplete request, without Paid Date'], REST_Controller::HTTP_NOT_ACCEPTABLE) : $paidDate = $putData[5]) : '' ,
                        'PaidOn'             => '' ,
                        'Status'             => ($putData[4] == 1) ? '1' : $this->response(['STATUS'=>REST_Controller::HTTP_NOT_FOUND, 'CONTENT'=>'Incorrect Value for Status'], REST_Controller::HTTP_CONFLICT) ,
                        'AccountantAccess'   => clientAccess() );

        $result = $this->dividends->updateDividend($data, $id);
        $this->response(['STATUS'=>REST_Controller::HTTP_OK, 'CONTENT'=>$result], REST_Controller::HTTP_OK);
    }

    public function dividendShareHolderList_get()
    {
        $this->load->model('clients/dividends');

        $data['UserList'] = $this->dividends->getShareHoldersList();
        $this->response(['STATUS'=>REST_Controller::HTTP_OK, 'CONTENT'=>$data], REST_Controller::HTTP_OK);
    }

    public function dividendDirectorsList_get()
    {
        $this->load->model('clients/dividends');

        $data['Directors'] = $this->dividends->getDirectorsList();
        $this->response(['STATUS'=>REST_Controller::HTTP_OK, 'CONTENT'=>$data], REST_Controller::HTTP_OK);
    }

    public function dividendTotalShare_get()
    {
        $this->load->model('clients/dividends');

        $data['TotalShares'] = $this->dividends->getTotalShares();
        $this->response(['STATUS'=>REST_Controller::HTTP_OK, 'CONTENT'=>$data], REST_Controller::HTTP_OK);
    }

    public function dividendUserShare_get()
    {
        $this->load->model('clients/dividends');

        $id = $this->get('userId');
        if($id)
        {
            $data['UserShares'] = $this->dividends->getShares($id);
            $this->response(['STATUS'=>REST_Controller::HTTP_OK, 'CONTENT'=>$data], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response( ['STATUS'=>REST_Controller::HTTP_BAD_REQUEST, 'CONTENT' => 'Incomplete request, without User Id'], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function contactCategories_get()
    {
        $this->load->model('clients/contact');

        $data['Contact'] = $this->contact->getRequestCategories();
        $this->response([$data], REST_Controller::HTTP_OK);
    }

    public function contact_post()
    {
        $this->load->model('clients/contact');

        $user        = $this->session->userdata('user');
        $addedOn     = date('Y-m-d');
        $addedBy     = $user['UserID'];
        $reason      = $this->post('Reason');
        $description = $this->db->escape($this->post('Description'));
        if($reason && $description)
        {
            $data = array(  'Reason'      => $reason ,
                            'Description' => $description ,
                            'AddedOn'     => $addedOn ,
                            'AddedBy'     => $addedBy );
            $saveRes = $this->contact->save($data);
            if($saveRes)
            {
                $userInfo = $this->contact->getUserInfo($addedBy);
                $message  = 'Question : ' . categoryName($data['Reason']) . '<br/><br/>' . 'Description : ' . $data['Description'];
                $message .= '<br/><br/>From : ' . getUserName($addedBy);
                $message .= '<br/><br/>Email : ' . $userInfo[0]->Email;
                $email = array( 'Subject' => $this->lang->line('CONTACT_EMAIL_SUBJECT') ,
                                'Message' => $message ,
                                'From'    => getUserName($addedBy) ,
                                'To'      => CONTACTUS_EMAIL );

                $emailRes = sendEmail($email);
                if($emailRes)
                {
                    $data = array(  'ClientId'   => $user['UserID'] ,
                                    'CompanyId'  => $user['CompanyID'] ,
                                    'ToAddress'  => CONTACTUS_EMAIL ,
                                    'CCAddress'  => '' ,
                                    'BCCAddress' => '' ,
                                    'Body'       => cleanString($message) ,
                                    'SUBJECT'    => $this->lang->line('CONTACT_EMAIL_SUBJECT') ,
                                    'EmailType'  => 'OUTBOUND',
                                    'AddedOn'    => $addedOn ,
                                    'AddedBy'    => $addedBy );

                    emailTracking($data);
					$done = new stdClass();
                    $this->response($done, REST_Controller::HTTP_OK);
                }
                else
                {
					$done = new stdClass();
                    $this->response($done, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
            else
            {
				$done = new stdClass();
                $this->response($done, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        else
        {
			$done = new stdClass();
            $this->response( $done, REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }

	public function payroll_get()
    {
		$this->load->model('clients/payroll');
		$page          = $this->get('page');
		$EID           = $this->get('eid');
		$fYear 		   = $this->get('fyear');
        $pageLimit     = $this->config->item('rest_page_limit');	
        if(isset($page))
        {
			$employees = $this->payroll->employees('check');
			unset($employees[0]);
			$data['employees'] = $employees;
			reset($employees);
			$firstEmp = key($employees);
			$financial_year = financial_year();
			$currentfyear   = currentFinancialYear();
			if( !empty($financial_year))
			{
				foreach( $financial_year as $keyy =>$vall)
				{
					$financial_year[$keyy] = str_replace(' / ','-',$keyy);
				}
				$financial_year = array_flip($financial_year);
			}	
			$data['financial_year'] = $financial_year;
			$searchdata = array();
			if( $fYear == '' || $fYear == 'null' )
				$fYear = $currentfyear;
			else
				$fYear = str_replace('-',' / ',$fYear);
			
			if( $EID == '' || $EID == 'null' )
				$EID = $firstEmp;
			$searchdata = array(
				'FinancialYear' => $fYear,
				'EID' => $EID
			);
			$this->session->set_userdata('SalarySearch', $searchdata);
			$data['Total'] = $this->payroll->totalEntries();			
            $page = $pageLimit * $page;
            if($page < $data['Total'])
            {
				$data['items']	  = $this->payroll->getItems($pageLimit, $page);
                $this->response([$data], REST_Controller::HTTP_OK);
            }
            else
            {
                $this->response([], REST_Controller::HTTP_CONFLICT);
            }
        }
        else
        {
            $this->response( [], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }
	
	public function invoice_delete()
    {
		$invoiceId  = $this->get('invoiceId');
        if($invoiceId)
        {
			$task[0] = 'ACTION_DELETE';
			$task[1] = $invoiceId;
			$task[2] = '0';
			$task[3] = 'noajaxcall';
			$paidDate = date('Y-m-d');
			$this->load->model('clients');
			$result   = $this->clients->performAction($task, $paidDate);
			$this->response([], REST_Controller::HTTP_OK);            
        }
        else
        {
            $this->response( [], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }
	public function expense_delete()
    {
		$expenseId  = $this->get('expenseId');
        if($expenseId)
        {
			$this->load->model('clients/expense');
			$response = $this->expense->performAction('ACTION_DELETE', $expenseId, '');
			$this->response([], REST_Controller::HTTP_OK);            
        }
        else
        {
            $this->response( [], REST_Controller::HTTP_NOT_ACCEPTABLE);
        }
    }
		
	
	public function userCompanies_get()
    { 	
		$userid  = $this->get('userid');
		$deviceId = $this->get('deviceId');
		//$userData = $this->_get_userdata($username, $password);			
		// Check if the user exist
		if ($userid)
		{
			$allCompanies  = $this->_get_usercompanies( $userid,'CID,Name');	
				
			$userDetail = [ 'ID'           => $userid ,							
							'Companies'    => $allCompanies
						];
			$this->response([$userDetail], REST_Controller::HTTP_OK); // OK (200) HTTP response code
		}else{
			$this->response([], REST_Controller::HTTP_UNAUTHORIZED); // Unauthorized Access (401)
		}	
       
    }
}
