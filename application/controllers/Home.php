<?php
//error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 	The base controller class for the cashman application.
 * 	This will display the login screen for the user to login.
 */
class Home extends CI_Controller {

    public function Home() {	
		
		
        parent::__construct();
        /* Check if various forms are submitted or not */
        if (isset($_POST['loginForm'])) {
            $formAction = $_POST['action'];
            $formAction = $this->encrypt->decode($formAction);
            if ($formAction == 'accessLogin') {
                $this->accessLogin();
            }
        } elseif (isset($_POST['passwordRecovery'])) {

            $formAction = $_POST['action'];
            $formAction = $this->encrypt->decode($formAction);
            if ($formAction == 'password_recovery') {
                $this->password_recovery('recoverPassword');
            }
        } elseif (isset($_POST['resetPassword'])) {
            $formAction = safe($this->encrypt->decode($_POST['action']));
            if ($formAction == 'updateUserPassword') {
                $this->updatePassword();
            } else {
                show_404();
            }
        } elseif (isset($_POST['setPassword'])) {
            $formAction = safe($this->encrypt->decode($_POST['action']));
            if ($formAction == 'setPassword') {
                $this->setPassword();
            } else {
                show_404();
            }
        } elseif (isset($_POST['chooseCompany'])) { 
            $formAction = safe($this->encrypt->decode($_POST['action']));
            if ($formAction == 'chooseCompany') {
                $this->chooseCompany();
            } else {
                show_404();
            }
        }
    }

    public function index() {	
	
        /*
         * 	Check if user is already logged in
         * 	If logged in, then setRedirect to their dashboard
         */
        $userLoggedIn = $this->session->userdata('user');
        if (isset($userLoggedIn) && $userLoggedIn['UserID'] != '') {
            userDasboard($userLoggedIn['UserType']);
        }
        $data['formAction'] = $this->encrypt->encode('accessLogin');
        $this->load->view('login', $data);
    }

    /**
     *
     * 	This function will process the login details.
     *
     */
    public function accessLogin() { 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {			
            $username = safe($_POST['uname']);
            $password = do_hash($_POST['password']);
            $this->form_validation->set_rules('uname', 'User ID', 'trim|required|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
            if ($this->form_validation->run()) {
                $this->load->model('login');
                $userData = $this->login->isUserExists($username, $password);
                if ($userData) {
                    $user = set_user_session($userData);
                    $user['AcType'] = $userData->UserType;
                    $this->session->set_userdata('user', $user);
					//echo "<pre>";print_r($this->session->userdata('user'));die('in login');
                    //Login Logs
                    update_logs('LOGIN/LOGOUT', 'USER_LOGIN', "LOGGED", $userData->ID, "");
                    /* setRedirect user to the specified area */
                    userDasboard($user['UserType']);
                } else {

                    /* Check which error has occured */
                    $response = $this->login->check_username_password($username, $password);
                    $msg = '';
                    if ($response == 'id_error') {
                        $msg = '<div class="alert alert-danger"><i class="fa fa-user-secret"></i>';
                        $msg .= sprintf($this->lang->line('LOGIN_USER_NOT_EXISTS'), '<span style="color:red;">' . $username . '</span>');
                        $msg .= '</div>';
                    } elseif ($response == 'password_error') {
                        $msg = '<div class="alert alert-danger"><i class="fa fa-user"></i>';
                        $msg .= $this->lang->line('LOGIN_USER_PASSWORD_WRONG');
                        $msg .= '</div>';
                    } else {
                        $msg = '<div class="alert alert-danger"><i class="fa fa-user"></i>';
                        $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
                        $msg .= '</div>';
                    }
                    $this->session->set_flashdata('loginMessage', $msg);
                    setRedirect(site_url() . 'home/');
                }
            }
        } else {
            show_404();
        }
    }
	
	##############################################################################
	# Author : Gurdeep Singh													 #			
	# Date   : 14 July 2016 													 #
	# Setting session variable for add new company in update client detail page  #
	##############################################################################
	public function setCompanySession() {
		if($this->input->is_ajax_request())
			$userData['UserID'] = $this->encrypt->decode(safe($_POST['client_id']));
		else
			$userData = $this->session->userdata('user');
		$this->load->model('login');
		$this->load->model('accountant/account');
		$allCompanies   = $this->login->getClientCompanies($userData['UserID'],'CID,Name');
		$countCompanies = count($allCompanies); 
		if($countCompanies == 1)
		{
			if($this->input->is_ajax_request())
			{
				$this->session->set_userdata('choosedCompanyId',$allCompanies[0]->CID);
				$this->session->set_userdata('chooseCompanyRequired','no');	
				
				$data['url'] = site_url() . 'client_access/'.$_POST['client_id'];
				$data['allCompanies'] = array();
				$data['success'] 	  = true;
				echo json_encode($data);exit;		
			}				
			else
				setRedirect(site_url() . 'client');			
		}
		else if( $countCompanies > 1)
		{			
			$formAction = safe($this->encrypt->encode('chooseCompany'));
			$data['allCompanies'] = $allCompanies;
			$data['formAction']   = $formAction;
			if($this->input->is_ajax_request())
			{
				foreach($allCompanies as $val)
				{
					$val->CID  = $this->encrypt->encode(safe($val->CID));
					$val->Name = ucfirst($val->Name); 
				}
				$data['allCompanies'] = $allCompanies;
				$data['url'] 		  = '';
				$data['success'] 	  = true;
				echo json_encode($data);exit;
			}
			$this->session->set_userdata('chooseCompanyRequired','yes');
			$this->load->view('choosecompany', $data);			
		}
	}
	function chooseCompany() { 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {  
			if($this->input->is_ajax_request())
				$this->form_validation->set_rules('client_id', 'Client', 'trim|required');			
            $this->form_validation->set_rules('company', 'Company', 'trim|required');
            if ($this->form_validation->run()) {				
				$company = $this->encrypt->decode(safe($_POST['company']));
                $this->session->set_userdata('choosedCompanyId',$company);
				$this->session->set_userdata('chooseCompanyRequired','no');		
				if($this->input->is_ajax_request())
				{
					$data['success']   = true;
					$data['url']       = site_url('client_access/'.$_POST['client_id']);
					echo json_encode($data);exit;
				}
				else
					$userData = $this->session->userdata('user');
				
				
				$this->load->model('login');
				$this->load->model('accountant/account');
				$client   = $this->account->clientLoginDetail($userData['UserID']);
				$username = $client[0]->Email;
				$password = $client[0]->Password;
				
				$clientData 	= $this->login->clientLogin($username, $password, 0);
				$user 			= set_user_session($clientData);
				$user['AcType'] = $clientData->UserType;
				$this->session->set_userdata('user', '');
				$this->session->set_userdata('user', $user);
				
				$this->session->set_userdata('chooseCompanyRequired','no');
				setRedirect(site_url() . 'client');	
            }
        } else {
            show_404();
        }
    }
    /**
     * 	This function perform two task depending on the parameter value i.e. task.
     * 	First  : load the recovery form.
     * 	Second : validates/check if email is registered with the system or not.
     */
    public function password_recovery($task = null) {
        $data['task'] = 'password_recovery';
        $data['action'] = $this->encrypt->encode('password_recovery');
        if ($task == 'recoverPassword' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = safe($_POST['email']);
            $this->form_validation->set_rules('email', $this->lang->line('RECOVERY_EMAIL_REGISTERED_LABEL'), 'trim|xss_clean|required|valid_email|callback_checkEmail');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
            if ($this->form_validation->run()) {
                $this->load->model('login');
                $token = do_hash(random_string('alnum', 5));
                $link = site_url() . 'home/changePassword/' . $this->encrypt->encode($token . '/' . $email);
                $check = $this->login->addToken($token, $email);
                if ($check == TRUE) {
                    $sendEmail = array(
                        'Subject' => $this->lang->line('RECOVERY_EMAIL_SUBJECT'),
                        'Message' => sprintf($this->lang->line('RECOVERY_EMAIL_MESSAGE'), $this->record[0]->FirstName, $email, $link),
                        'To' => $email,
                        'From' => ''
                    );
                    $msg = sprintf('<div class="alert alert-success"><i class="fa fa-check-circle"></i>' . $this->lang->line('RECOVERY_EMAIL_SUCCESS') . '</div>', $email);
                    if (sendEmail($sendEmail)) {
                        $this->session->set_flashdata('loginMessage', $msg);
                        setRedirect(site_url());
                    } else {
                        $msg = '<div class="alert alert-danger">' . $this->lang->line('UNEXPECTED_ERROR_OCCURED') . '</div>';
                        $this->session->set_flashdata('otherMessage', $msg);
                    }
                } else {
                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>' . $this->lang->line('UNEXPECTED_ERROR_OCCURED') . '</div>';
                    $this->session->set_flashdata('otherMessage', $msg);
                }
                setRedirect(site_url() . 'home/password_recovery');
            }
        } else {
            $this->load->view('others', $data);
        }
    }

    /**
     *
     * 	Function to check if Email is registered with us or not
     * 	Return value : boolean
     *
     */
    function checkEmail($email = NULL) {
        if ($email != NULL) {
            $this->load->model('login');
            $returnValue = $this->login->checkEmail($email);
            if ($returnValue) {
                $this->record = $returnValue;
                return TRUE;
            } else {
                $msg = '<span class="glyphicon glyphicon-exclamation-sign"></span>';
                $msg .= sprintf($this->lang->line('RECOVERY_EMAIL_FAILURE'), '<em style="color:red;">' . $email . '</em>');
                $this->form_validation->set_message('checkEmail', $msg);
                return FALSE;
            }
        } else {
            show_404();
        }
    }

    function changePassword($token = null) {
        if (!empty($token)) {
            $token = safe($this->encrypt->decode($token));
            $token = explode('/', $token);
            $token[0] = safe($token[0]);
            $token[1] = safe($token[1]);
            if (!is_array($token)) {
                show_404();
            }
            $this->load->model('login');
            $response = $this->login->checkToken($token);
            if ($response) {
                $data['task'] = 'changePasswordForm';
                $data['UserID'] = $this->encrypt->encode($response[0]->ID);
                $data['action'] = $this->encrypt->encode('updateUserPassword');
                $this->load->view('others', $data);
            } else {
                setRedirect(site_url());
            }
        } else {
            show_404();
        }
    }

    function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->encrypt->decode(safe($_POST['id']));
            $this->form_validation->set_rules('rNewPassword', 'New password', 'trim|required');
            $this->form_validation->set_rules('rConfirmPassword', 'Confirm password', 'trim|required|matches[rNewPassword]');
            //$this->form_validation->set_rules('SecurityQuestions','Security Question','trim|required|callback_checkQuestion');
            //$this->form_validation->set_rules('answer','Answer','trim|required');
            if ($this->form_validation->run()) {
                //echo '<pre>';print_r($_POST);echo '</pre>';die;
                $match_data = array(
                    'ID' => safe($this->encrypt->decode($_POST['id'])),
                    'Password' => do_hash(safe($_POST['rNewPassword']))
                );
                $this->load->model('login');
                $response = $this->login->updateUserPassword($match_data);
                if ($response == TRUE) {
                    $this->session->set_flashdata('loginMessage', '<div class="alert alert-success"><i class="fa fa-check-circle"></i>' . $this->lang->line('RESET_PASSWORD_SUCCESS') . '</div>');
                } else {
                    $this->session->set_flashdata('loginMessage', '<div class="alert alert-danger"><i class="fa fa-close"></i>' . $this->lang->line('UNEXPECTED_ERROR_OCCURED') . '</div>');
                }
                setRedirect(site_url());
            }
        } else {
            show_404();
        }
    }

    /*
      function checkQuestion($record)
      {
      if($record != NULL)
      {
      $this->load->model('login');
      $data['ID'] = $this->encrypt->decode($_POST['id']);
      $data['QuestionID'] = $record;
      $data['QuestionAnswer'] = safe($_POST['answer']);
      $returnValue = $this->login->checkQuestionAnswer($data);
      if($returnValue == TRUE)
      {
      return TRUE;
      }else{
      $msg = $this->lang->line('RESET_QUESTION_FAILURE');
      $this->form_validation->set_error_delimiters('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>&nbsp;','</div>');
      $this->form_validation->set_message('checkQuestion',$msg);
      return FALSE;
      }
      }else{
      return FALSE;
      }
      }
     */

    /**
     * 	Function to set the password of Client.
     *  This function will be called from the link given in the email to the newly created user.
     */
    public function set_password($token = NULL) {
        if (!empty($token)) {

            $token = safe($this->encrypt->decode($token));
            $token = explode('/', $token);

            if (count($token) == 1) {
                show_404();
            }
            $token[0] = safe($token[0]);
            $token[1] = safe($token[1]);
            if (!is_array($token)) {
                show_404();
            }
            $this->load->model('login');
            $response = $this->login->checkToken($token);
            //echo "<pre>Response : "; print_r($response);echo "</pre>";die;
            if ($response) {
                $data['task'] = 'setPassword';
                $data['UserID'] = $this->encrypt->encode($response[0]->ID);
                $data['action'] = $this->encrypt->encode('setPassword');
                $this->load->view('others', $data);
            } else {
                setRedirect(site_url());
            }
        } else {
            show_404();
        }
    }

    function setPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->encrypt->decode(safe($_POST['id']));
            $this->form_validation->set_rules('rNewPassword', 'New password', 'trim|required');
            $this->form_validation->set_rules('rConfirmPassword', 'Confirm password', 'trim|required|matches[rNewPassword]');
            //$this->form_validation->set_rules('SecurityQuestions','Security Question','trim|required');
            //$this->form_validation->set_rules('answer','Answer','trim|required');
            if ($this->form_validation->run()) {
                $match_data = array(
                    'ID' => safe($this->encrypt->decode($_POST['id'])),
                    'Password' => do_hash(safe($_POST['rNewPassword'])),
                    //'QuestionID'		=>	$_POST['SecurityQuestions'],
                    //'QuestionAnswer'	=>	$_POST['answer'],
                    'Status' => 1,
                    'Activation' => 1,
                    'ActivatedOn' => date('Y-m-d')
                );
                $this->load->model('login');
                $response = $this->login->updateClientPassword($match_data);
                if ($response == TRUE) {
                    $this->session->set_flashdata('loginMessage', '<div class="alert alert-success"><i class="fa fa-check-circle"></i>' . $this->lang->line('SET_PASSWORD_SUCCESS') . '</div>');
                } else {
                    $this->session->set_flashdata('loginMessage', '<div class="alert alert-danger"><i class="fa fa-close"></i>' . $this->lang->line('UNEXPECTED_ERROR_OCCURED') . '</div>');
                }
                setRedirect(site_url());
            }
        } else {
            show_404();
        }
    }
	
	

    public function term_conditions() {
        $user = $this->session->userdata('user');
        $term_version = $this->input->post('term_version');
        $userId = $user['UserID'];
        $this->load->model('login');
        $response = $this->login->activateTermAndCondtion($userId, $term_version);
        if ($response == 1) {
            $user['T_AND_C_Version'] = $term_version;
            $this->session->set_userdata('user', $user);
            return TRUE;
        }
    }

    function logout() {

        update_logs('LOGIN/LOGOUT', 'USER_LOGOUT', 'LOGOUT', "", "");
        $this->session->sess_destroy();
        setRedirect(site_url());
    }
	
	public function dueVatMailAcction($link){
		$link = $this->encrypt->decode($link);			
		$link = explode('-',$link);		
		if(isset($link[2])){
			$this->load->model('accountant/Aemail');
			$data['mailAcceptStatus'] = $this->Aemail->dueVatMailAcction($link[0],$link[1],$link[2],$link[3]);			
			$data['companyID'] = $link[0];
			$data['quarter'] = $link[1];
			$data['acction'] = $link[2];
			$data['qEndDate'] = $link[3];
			$this->load->view('vatRequest',$data);
		}	
		
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
