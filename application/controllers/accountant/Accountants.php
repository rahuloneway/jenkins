<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accountants extends CI_Controller {

	public function Accountants()
	{
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
		
		$this->load->model('accountant/accountant');
	}
	public function index()
	{
		$data['page']	=	'accountants';
		$data['title']	=	'Cashman | Accountants';
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		$data['items']	=	$this->accountant->getItems(ACCOUNTANT_LISTING_PAGINATION_LIMIT,$page);
		$total = $this->accountant->totalItems();
		$data['pagination'] = $this->getPagination('accountants',ACCOUNTANT_LISTING_PAGINATION_LIMIT,$total);
		$this->load->view('accountant/accountants/default',$data);
	}
	
	public function forms($id = NULL)
	{
		$data['page']	=	'accountants';
		$data['title']	=	'Cashman | Accountants';
		$data['task']	=	'';
		if(!empty($id))
		{
			$data['form_title']	=	'Update Accountant Details';
			$id = $this->encrypt->decode($id);
			$data['action']		=	'edit';
			$data['form_link'] 	= 	site_url().'accountant_update';
			$data['form_id'] 	= 	'update_accountant';
			$data['item']		=	$this->accountant->getItem($id);
		}else{
			$data['form_title']	=	'Add Accountant';
			$data['action']		=	'add';
			$data['form_link'] 	= 	site_url().'accountant_save';
			$data['form_id'] 	= 	'addAccountant';
			$data['item']		=	array();
		}
		$this->load->view('accountant/accountants/form',$data);
	}
	
	public function getPagination($url = null,$perPage = CLIENT_LISTING_PAGINATION_LIMIT,$totalItem = 0)
	{
		/* Create Pagination links */
		$this->load->library('pagination');
		$config['base_url'] = site_url().$url;
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
	
	public function search()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			//echo '<pre>';print_r($_POST);echo '</pre>';die;
			//$date = safe($_POST['YearEndDate']);
			$search = array(
				'Name'			=>	safe($_POST['Name']),
				'Email'		=>	safe($_POST['Email']),
				'Status' 		=> 	safe($_POST['Status'])
			);
			$this->session->set_userdata('accountant_search',$search);
			setRedirect('accountants');
		}else{
			show_404();
		}
	}
	
	public function reset()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$this->session->set_userdata('accountant_search','');
			$data['items'] = $this->accountant->getItems(ACCOUNTANT_LISTING_PAGINATION_LIMIT,0);
			$json = array();
			$json['pagination'] = $this->getPagination('accountants',ACCOUNTANT_LISTING_PAGINATION_LIMIT,count($data['items']));
			$json['items'] 		= $this->load->view('accountant/accountants/accountant_listing',$data,TRUE);
			echo json_encode($json);exit;
		}else{
			show_404();
		}
	}
	
	public function sorting()
	{
		
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			
			$order = safe($this->encrypt->decode($_POST['order']));
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			
			$des_order_value = array(
				'SORT_BY_NAME'		=>	'CONCAT(u.FirstName," ",u.LastName) DESC',
				'SORT_BY_CONTACTNO'	=>	'u.ContactNo DESC',
				'SORT_BY_STATUS'	=>	'u.Status DESC'
			);
			$asc_order_value = array(
				'SORT_BY_NAME'		=>	'CONCAT(u.FirstName," ",u.LastName) ASC',
				'SORT_BY_CONTACTNO'	=>	'u.ContactNo ASC',
				'SORT_BY_STATUS'	=>	'u.Status ASC',
			);
			$prev_order = $this->session->userdata('accountant_sorting');
			$dir = '';
			if(!empty($prev_order))
			{
				$order_value = $des_order_value[$order];
				if($order_value == $prev_order)
				{
					$order_value = $asc_order_value[$order];
					$dir = 'fa-sort-up';
				}else{
					$order_value = $des_order_value[$order];
					$dir = 'fa-sort-desc';
				}
			}else{
				$order_value = $des_order_value[$order];
			}
			$this->session->set_userdata('accountant_sorting',$order_value);
			$data['items'] = $this->accountant->getItems(CLIENT_LISTING_PAGINATION_LIMIT,$page);
			
			$d[0] = $this->load->view('accountant/accountants/accountant_listing',$data,true);
			$d[1] = $dir;
			echo json_encode($d);exit;
		}else{
			show_404();
		}
	}
	
	public function changeStatus($id = null)
	{
		if(!empty($id))
		{
			$status_level = array(
				'ACTION_ENABLE'		=>	1,
				'ACTION_DISABLE'	=>	0
			);
			$id = $this->encrypt->decode($id);
			$id = explode('/',$id);
			$status = $status_level[$id[0]];
			//die('ID : '.$id[1].' Status : '.$status);
			$response = $this->account->changeStatus($id[1],$status);
			if($response)
			{
				if($status == '1')
				{
					$msg = '<div class="alert alert-success"><i class="fa fa-check-circle"></i>&nbsp;';
					$msg .= $this->lang->line('ACCOUNTANT_CLIENT_DISABLE_STATUS_SUCCESSFULL');
					$msg .= '</div>';
				}else{
					$msg = '<div class="alert alert-success"><i class="fa fa-check-circle"></i>&nbsp;';
					$msg .= $this->lang->line('ACCOUNTANT_CLIENT_ENABLE_STATUS_SUCCESSFULL');
					$msg .= '</div>';
				}
				
				$this->session->set_flashdata('clientError',$msg);
				setRedirect(site_url().'client_listing');
			}else{
				setRedirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			setRedirect($_SERVER['HTTP_REFERER']);
		}
	}
	
	public function checkEmail()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$this->load->model('accountant/account');
			$email = safe($_POST['email']);
			if(isset($_POST['ID']))
			{
				$id = safe($this->encrypt->decode($_POST['ID']));
			}else{
				$id = null;
			}
			if( $this->session->userdata('lastAddedClientId') != '' && $this->session->userdata('lastAddedClientId') > 0 )
				$id = $this->session->userdata('lastAddedClientId');
			$response = $this->account->checkEmail($email,$id);
			if($response)
			{
				die('wrong');
			}else{
				die('correct');
			}
		}else{
			show_404();
		}
	}
	
	public function resendEmail($id = NULL)
	{
		$userID = $this->encrypt->decode($id);
		if(empty($userID) || !is_numeric($userID))
		{
			show_404();
		}
		$response = $this->accountant->getEmail($userID);
		
		/* Send emil to newly created account to set the password */
		$this->load->model('login');
		$token = do_hash(random_string('alnum',5));
		$set_link = site_url().'home/set_password/'.$this->encrypt->encode($token.'/'.$response[0]->Email);
		$this->login->addToken($token,$response[0]->Email);
		$email = array(
			'Name'		=>	$response[0]->Name,
			'domain'	=>	site_url(),
			'Email'		=>	$response[0]->Email,
			'link'		=>	$set_link
		);
		//Mail Setup
        $email_setting = emailSetting();
        $msetup ='';
        if(!empty($email_setting[0]->Email_Signature)){
            $msetup=$email_setting[0]->Email_Text.$email_setting[0]->Email_Signature;
        }else{
            $msetup=$this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_RESET_MESSAGE');
        }
        $sendEmail = array(
            'Subject' => $this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_SET_SUBJECT'),
              //'Message' => sprintf($this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_RESET_MESSAGE').$msetup,$email['Name'], $email['domain'], $email['Email'], '<a href="'.$email['link'].'" title="Click Here">Click Here</a>'),
              'Message' => sprintf($msetup,$email['Name'],$email['domain'],$email['Email'],'<a href="'.$email['link'].'" title="Click Here">Click Here</a>'),
            'To' => $email['Email'],
            'From' => CASHMAN_FROM_EMAIL_ADDRESS
        );
		$response = sendEmail($sendEmail);
		if(!$response)
		{
			$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
			$msg .= $this->lang->line('ACCOUNTENT_RESEND_EMAIL_FAILURE');
			$msg .= "</div>";
			$this->session->set_flashdata('accountantsError',$msg);
		}else{
			$this->accountant->changeState($userID);
			$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
			$msg .= sprintf($this->lang->line('ACCOUNTENT_RESEND_EMAIL_SUCCESS'),$email['Email']);
			$msg .= "</div>";
			$this->session->set_flashdata('accountantsError',$msg);
		}
		setRedirect(site_url().'accountants');
	}
	
	public function clientAccess($id = NULL)
	{
		$id = $this->encrypt->decode($id);
		
		if(empty($id) || !is_numeric($id))
		{
			show_404();
		}
		$user = $this->session->userdata('user');
		
		/* STEP - 1 Get Client login detail */
		$client = $this->account->clientLoginDetail($id);
		$username = $client[0]->Email;
		$password = $client[0]->Password;
		
		/* STEP - 2 Get User record to store it in session */
		$this->load->model('login');
		$response = $this->login->isUserExists($username,$password);
		$client = array(
			'Name'			=>	$response[0]->FirstName,
			'UserID'		=>	$response[0]->ID,
			'UserType'		=>	$response[0]->UserType,
			'CompanyID'		=>	$response[0]->CID,
			'CompanyRegNo'	=> 	$response[0]->CompnayRegNo,
			'Params'		=>	$response[0]->Params,
			'PercentRateAfterEndDate'		=>	$response[0]->PercentRateAfterEndDate,
			'EndDate'		=>	$response[0]->EndDate,
			'PercentRate'		=>	$response[0]->PercentRate,
			'CompanyEndDate'=>	$response[0]->CompanyEndDate,
			'AccountantAccess'	=>	$user['UserID']
		);
		$this->session->set_userdata('user','');
		$this->session->set_userdata('user',$client);
		setRedirect(site_url().'client_listing');
	}
	
	public function logout()
	{
		update_logs('LOGIN/LOGOUT', 'USER_LOGOUT', 'LOGOUT',"","");
		$this->session->sess_destroy();
		setRedirect(site_url().'home/');
	}
	
	
	public function save()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$user = $this->session->userdata('user');	
			$task = safe($_POST['task']);
			$task = $this->encrypt->decode($task);
			$allowed_extension = array(
				'1' => 'image/jpeg',
				'2' => 'image/jpg',
				'3' => 'image/png'
			);
			/* STEP - 1 Check if correct image is loaded or not */
			if($_FILES['file']['error'] == 0)
			{
				if(!in_array($_FILES['file']['type'],$allowed_extension))
				{
					$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
					$msg .= $this->lang->line('ACCOUNTENT_WRONG_FILE_UPLOADED');
					$msg .= "</div>";
					$this->session->set_flashdata('accountantError',$msg);
					setRedirect(site_url().'add_accountant');
				}
				
				if(round(($_FILES['file']['size'])/1024) > LOGO_UPLOAD_FILE_SIZE)
				{
					$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
					$msg .= $this->lang->line('ACCOUNTENT_FILE_SIZE_ERROR');
					$msg .= "</div>";
					$this->session->set_flashdata('accountantError',$msg);
					setRedirect(site_url().'add_accountant');
				}
				$file_extension = explode('.',$_FILES['file']['name']);
				$file_extension = end($file_extension);
				$file_name = random_string('alnum', strlen($_FILES['file']['name']));
				$file_name = strtoupper(substr($_POST['FirstName'],0,3)).'-'.$file_name.'.'.$file_extension;
				$config['upload_path'] = './assets/uploads/signatures/';
				$config['allowed_types'] = 'jpeg|jpg|png';
				$config['max_size']	= '1000';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';
				$config['file_name']  = $file_name;

				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('file'))
				{
					$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
					$msg .= $this->lang->line('ACCOUNTENT_FILE_UPLOAD_UPLOADED');
					$msg .= "</div>";
					$this->session->set_flashdata('accountantError',$msg);
					setRedirect(site_url().'add_accountant');
				}
				$file_link = 'assets/uploads/signatures/'.$file_name;
			}else{
				$file_link = '';
			}
			$params = array(
				'Salutation'	=>		safe($_POST['salutation']),
				'DOB'			=>		mDate($_POST['DOB']),
				'NI_NUMBER'		=>		'',
				'UTR'			=>		'',
				'AddressTwo'	=>		'',
				'AddressThree'	=>		'',
				'ImageLink'		=>		$file_link,
				'DigitalSignature'=>	$_POST['DigitalSignature'],
				'EmploymentLevel'=>		$_POST['EmploymentLevel']
			);
			$accountant_detail = array(
				'FirstName'		=>	safe($_POST['FirstName']),
				'LastName'		=>	safe($_POST['LastName']),
				'Email'			=>	safe($_POST['Email']),
				'ContactNo'		=>	safe($_POST['ContactNumber']),
				'UserType'		=>	'TYPE_ACC',
				'Params'		=>	serialize($params),
				'Status'		=>	0,
				'AddedOn'		=>	date('Y-m-d'),
				'AddedBy'		=>	$user['UserID'],
				'SubParent'		=>	(!empty($user['AddedBy'])) ? $user['AddedBy'] : 0 
			);
			
			//echo '<pre>';print_r($accountant_detail);echo '</pre>';die; 
			$response = $this->accountant->save($accountant_detail);
			//echo '<pre>';print_r($accountant_detail);echo '</pre>';die; 
			if(!$response)
			{
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
				$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
				$msg .= "</div>";
				$this->session->set_flashdata('accountantsError',$msg);
				setRedirect(site_url().'accountants');
			}else{
				
				/* Check if email has to be send */
				if($task == 'create_accountant')
				{
					/* Send email to newly created account to set the password */
					$this->load->model('login');
					$token = do_hash(random_string('alnum',5));
					$link = site_url().'home/set_password/'.$this->encrypt->encode($token.'/'.$accountant_detail['Email']);
					$link = "<a herf=".$link."> Click Here </a>";
					$this->login->addToken($token,$accountant_detail['Email']);
					  //Mail Setup
                    $email_setting = emailSetting();
                    $msetup = '';
                    if (!empty($email_setting[0]->Email_Text_Created)) {
                        $msetup = $email_setting[0]->Email_Text_Created . $email_setting[0]->Email_Signature;
                    } else {
                        $msetup = $this->lang->line('ACCOUNTANT_NEW_ACCOUNT_MESSAGE');
                    }

                    $email = array(
                        'Subject' => $this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_SET_SUBJECT'),
                        //'Message' => sprintf($this->lang->line('ACCOUNTANT_NEW_ACCOUNT_MESSAGE'), $accountant_detail['FirstName'], site_url(), $accountant_detail['Email'], $link),
                        'Message' => sprintf($msetup, $accountant_detail['FirstName'], site_url(), $accountant_detail['Email'], $link),
                        'To' => $accountant_detail['Email'],
                        'From' => CASHMAN_FROM_EMAIL_ADDRESS
                    );
					
					//echo "<pre>"; print_r();
					
					if(sendEmail($email))
					{
						$this->accountant->changeState($response);
						$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
						$msg .= $this->lang->line('ACCOUNTENT_CREATE_SUCCESS');
						$msg .= "</div>";
						$this->session->set_flashdata('accountantsError',$msg);
						setRedirect(site_url().'accountants');
					}else{
						$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
						$msg .= $this->lang->line('SERVER_MAIL_ERROR');
						$msg .= "</div>";
						$this->session->set_flashdata('accountantsError',$msg);
						setRedirect(site_url().'accountants');
					}
				}

				$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
				$msg .= $this->lang->line('ACCOUNTENT_CREATE_SUCCESS');
				$msg .= "</div>";
				$this->session->set_flashdata('accountantsError',$msg);
				setRedirect(site_url().'accountants');
			}
		}else{
			show_404();
		}
	}
	
	public function update()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$task = safe($_POST['task']);
			$task = $this->encrypt->decode($task);
			//echo '<pre>';print_r($_POST);echo '</pre>';DIE;
			$user = $this->session->userdata('user');
			$id = safe($_POST['ID']);
			$id = $this->encrypt->decode($id);
			if(!is_numeric($id))
			{
				show_404();
			}
			$allowed_extension = array(
				'1' => 'image/jpeg',
				'2' => 'image/jpg',
				'3' => 'image/png'
			);
			$prev_image = $_POST['image_link'];
			/* STEP - 1 Check if correct image is loaded or not */
			if($_FILES['file']['error'] == 0)
			{
				/* Delete the previous image */
					
				if(!empty($prev_image))
				{
				
					if(file_exists($prev_image))
					{
						unlink($prev_image);
					}
				}
				
				if(!in_array($_FILES['file']['type'],$allowed_extension))
				{
					log_message('error',$_FILES['file']['type'].'File type not allowed');
					$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
					$msg .= $this->lang->line('ACCOUNTENT_WRONG_FILE_UPLOADED');
					$msg .= "</div>";
					$this->session->set_flashdata('accountantError',$msg);
					setRedirect($_SERVER['HTTP_REFERER']);
				}
				
				if(round(($_FILES['file']['size'])/1024) > LOGO_UPLOAD_FILE_SIZE)
				{
					$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
					$msg .= $this->lang->line('ACCOUNTENT_FILE_SIZE_ERROR');
					$msg .= "</div>";
					$this->session->set_flashdata('accountantError',$msg);
					setRedirect($_SERVER['HTTP_REFERER']);
				}
				$file_extension = explode('.',$_FILES['file']['name']);
				$file_extension = end($file_extension);
				$file_name = random_string('alnum', strlen($_FILES['file']['name']));
				$file_name = strtoupper(substr($_POST['FirstName'],0,3)).'-'.$file_name.'.'.$file_extension;
				$config['upload_path'] = './assets/uploads/signatures/';
				$config['allowed_types'] = 'jpeg|jpg|png';
				$config['max_size']	= '1000';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';
				$config['file_name']  = $file_name;

				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('file'))
				{
					$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
					$msg .= $this->lang->line('ACCOUNTENT_FILE_UPLOAD_UPLOADED');
					$msg .= "</div>";
					$this->session->set_flashdata('accountantError',$msg);
					setRedirect($_SERVER['HTTP_REFERER']);
				}
				$file_link = 'assets/uploads/signatures/'.$file_name;
			}else{
				$file_link = $prev_image;
			}
			$params = array(
				'Salutation'	=>		safe($_POST['salutation']),
				'DOB'			=>		mDate($_POST['DOB']),
				'NI_NUMBER'		=>		'',
				'UTR'			=>		'',
				'AddressTwo'	=>		'',
				'AddressThree'	=>		'',
				'ImageLink'		=>		$file_link,
				'DigitalSignature'=>	$_POST['DigitalSignature'],
				'EmploymentLevel'=>		$_POST['EmploymentLevel']
			);
			$accountant_detail = array(
				'FirstName'		=>	safe($_POST['FirstName']),
				'LastName'		=>	safe($_POST['LastName']),
				'Email'			=>	safe($_POST['Email']),
				'ContactNo'	=>	safe($_POST['ContactNumber']),
				'Params'		=>	serialize($params),
				'Status'		=>	(isset($_POST['Status']))?1:0,
				'ModifiedOn'	=>	date('Y-m-d'),
			);
			//echo $id;
			//echo '<pre>';print_r($accountant_detail);echo '</pre>';die; 
			$response = $this->accountant->update($accountant_detail,$id);
			if(!$response)
			{
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
				$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
				$msg .= "</div>";
				$this->session->set_flashdata('accountantsError',$msg);
				setRedirect(site_url().'accountants');
			}else{
				
				/* Check if email has to be send */
				if($task == 'update_status_accountant')
				{
					/* Send email to newly created account to set the password */
					$this->load->model('login');
					$token = do_hash(random_string('alnum',5));
					$link = site_url().'home/set_password/'.$this->encrypt->encode($token.'/'.$accountant_detail['Email']);
					$this->login->addToken($token,$accountant_detail['Email']);
					$email = array(
						'Subject'		=>	$this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_SET_SUBJECT'),
						'Message'		=>	sprintf($this->lang->line('ACCOUNTANT_NEW_ACCOUNT_MESSAGE'),$accountant_detail['FirstName'],site_url(),$accountant_detail['Email'],$link),
						'To'			=>	$accountant_detail['Email'],
						'From'			=>  CASHMAN_FROM_EMAIL_ADDRESS
					);
					sendEmail($email);
				}
				
				$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
				$msg .= $this->lang->line('ACCOUNTENT_UPDATE_SUCCESS');
				$msg .= "</div>";
				$this->session->set_flashdata('accountantsError',$msg);
				setRedirect(site_url().'accountants');
			}
		}else{
			show_404();
		}
	}
	
	public function profile($id)
	{
		$id = $this->encrypt->decode($id);
		if(!is_numeric($id))
		{
			show_404();
		}
		$data['page']	=	'accountants';
		$data['title']	=	'Cashman | Accountants';
		$data['task']	=	'';
		$data['form_title']	=	'Update Profile';
		$data['action']		=	'edit';
		$data['form_link'] 	= 	site_url().'accountant_update_profile';
		$data['form_id'] 	= 	'update_accountant';
		$data['item']		=	$this->accountant->getItem($id);
		$this->load->view('accountant/accountants/form',$data);
	}
	
	public function update_profile()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$task = safe($_POST['task']);
			$task = $this->encrypt->decode($task);
			
			$user = $this->session->userdata('user');
			$id = safe($_POST['ID']);
			$id = $this->encrypt->decode($id);
			if(!is_numeric($id))
			{
				show_404();
			}
			$allowed_extension = array(
				'1' => 'image/jpeg',
				'2' => 'image/jpg',
				'3' => 'image/png'
			);
			$prev_image = $_POST['image_link'];
			/* STEP - 1 Check if correct image is loaded or not */
			
			if($_FILES['file']['error'] == 0)
			{
				/* Delete the previous image */
				
				if(!empty($prev_image))
				{
				
					if(file_exists($prev_image))
					{
						unlink($prev_image);
					}
				}
				
				if(!in_array($_FILES['file']['type'],$allowed_extension))
				{
					log_message('error',$_FILES['file']['type'].'File type not allowed');
					$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
					$msg .= $this->lang->line('ACCOUNTENT_WRONG_FILE_UPLOADED');
					$msg .= "</div>";
					$this->session->set_flashdata('accountantError',$msg);
					setRedirect($_SERVER['HTTP_REFERER']);
				}
				
				if(round(($_FILES['file']['size'])/1024) > LOGO_UPLOAD_FILE_SIZE)
				{
					$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
					$msg .= $this->lang->line('ACCOUNTENT_FILE_SIZE_ERROR');
					$msg .= "</div>";
					$this->session->set_flashdata('accountantError',$msg);
					setRedirect($_SERVER['HTTP_REFERER']);
				}
				$file_extension = explode('.',$_FILES['file']['name']);
				$file_extension = end($file_extension);
				$file_name = random_string('alnum', strlen($_FILES['file']['name']));
				$file_name = strtoupper(substr($_POST['FirstName'],0,3)).'-'.$file_name.'.'.$file_extension;
				$config['upload_path'] = './assets/uploads/signatures/';
				$config['allowed_types'] = 'jpeg|jpg|png';
				$config['max_size']	= '1000';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';
				$config['file_name']  = $file_name;

				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('file'))
				{
					$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
					$msg .= $this->lang->line('ACCOUNTENT_FILE_UPLOAD_UPLOADED');
					$msg .= "</div>";
					$this->session->set_flashdata('accountantError',$msg);
					setRedirect($_SERVER['HTTP_REFERER']);
				}
				$file_link = 'assets/uploads/signatures/'.$file_name;
			}else{
				$file_link = $prev_image;
			}
			
			$params = array(
				'Salutation'	=>		safe($_POST['salutation']),
				'DOB'			=>		mDate($_POST['DOB']),
				'NI_NUMBER'		=>		'',
				'UTR'			=>		'',
				'AddressTwo'	=>		'',
				'AddressThree'	=>		'',
				'ImageLink'		=>		$file_link,
				'DigitalSignature'=>	$_POST['DigitalSignature'],
				'EmploymentLevel'=>		$_POST['EmploymentLevel']
			);
			
			$accountant_detail = array(
				'FirstName'		=>	safe($_POST['FirstName']),
				'LastName'		=>	safe($_POST['LastName']),
				'Email'			=>	safe($_POST['Email']),
				'ContactNo'		=>	safe($_POST['ContactNumber']),
				'Params'		=>	serialize($params),
				'Status'		=>	(isset($_POST['Status']))?1:0,
				'ModifiedOn'	=>	date('Y-m-d'),
			);
			//echo $id;
			//echo '<pre>';print_r($accountant_detail);echo '</pre>';die; 
			$response = $this->accountant->update($accountant_detail,$id);
			if(!$response)
			{
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
				$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
				$msg .= "</div>";
				$this->session->set_flashdata('accountantsError',$msg);
				setRedirect(site_url().'accountants');
			}else{
				
				/* Check if email has to be send */
				if($task == 'update_status_accountant')
				{
					/* Send email to newly created account to set the password */
					$this->load->model('login');
					$token = do_hash(random_string('alnum',5));
					$link = site_url().'home/set_password/'.$this->encrypt->encode($token.'/'.$accountant_detail['Email']);
					$this->login->addToken($token,$accountant_detail['Email']);
					$email = array(
						'Subject'		=>	$this->lang->line('ACCOUNTANT_CLIENT_PASSWORD_SET_SUBJECT'),
						'Message'		=>	sprintf($this->lang->line('ACCOUNTANT_NEW_ACCOUNT_MESSAGE'),$accountant_detail['FirstName'],site_url(),$accountant_detail['Email'],$link),
						'To'			=>	$accountant_detail['Email'],
						'From'			=>  CASHMAN_FROM_EMAIL_ADDRESS
					);
					sendEmail($email);
				}
				
				$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
				$msg .= $this->lang->line('ACCOUNTENT_UPDATE_SUCCESS');
				$msg .= "</div>";
				$this->session->set_flashdata('dashboardErrors',$msg);
				setRedirect(site_url());
			}
		}else{
			show_404();
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */