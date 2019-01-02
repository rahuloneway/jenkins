<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contactus extends CI_Controller {
	public function Contactus()
	{
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		/*
		 * 	First check if accountant is accessing the Clients account or not.
		 *	Preventing accountant from direct access to the client's dashboard.
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
		$this->load->model('clients/contact');
	}
	
	public function index()
	{
		$data['title']	=	'Cashman | Contact Us';
		$data['page']	=	'contactus';
		$data['reason']	=	$this->contact->getRequestCategories();
		$this->load->view('client/contactus/default',$data);
	}
	
	public function save()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			/* Check if Created by accountant while accessing the client account */
			$accountant_access = clientAccess();
			
			$user = $this->session->userdata('user');
			$addedOn = date('Y-m-d');
			$addedBy = $user['UserID'];
			$data = array(
				//'Subject'		=>	safe($_POST['Subject']),
				'Reason'		=>	safe($_POST['Reason']),
				'Description'	=>	$this->db->escape($_POST['Description']),
				'AddedOn'		=>	$addedOn,
				'AddedBy'		=>	$addedBy,
				'AccountantAccess'	=>	$accountant_access
			);
			
			$response = $this->contact->save($data);
			if($response)
			{
				$userifno = $this->contact->getUserInfo($addedBy);
				//echo '<pre>';print_r($userifno);echo '</pre>';
				$accountant = $this->contact->getUserInfo($userifno[0]->AddedBy);
				$message = 'Question : '.categoryName($data['Reason']).'<br/><br/>'.'Description : '.$data['Description'];
				$message .= '<br/><br/>From : '.getUserName($addedBy);
				$message .= '<br/><br/>Email : '.$userifno[0]->Email;
				$email = array(
					'Subject'	=>	$this->lang->line('CONTACT_EMAIL_SUBJECT'),
					'Message'	=>	$message,
					'From'		=>	getUserName($addedBy),
					//'To'		=>	$accountant[0]->Email
					'To'		=>	CONTACTUS_EMAIL
				);
				//echo '<pre>';print_r($email);echo '</pre>';die;
				$response = sendEmail($email);
				if($response)
				{
					$msg = '<div class="alert alert-success">';
					$msg .= '<i class="glyphicon glyphicon-ok"></i>'.$this->lang->line('CONTACT_REQUEST_SUCCESS');
					$msg .= '</div>';
					$this->session->set_flashdata('contactMessage',$msg);
				}else{
					$msg = '<div class="alert alert-danger">';
					$msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>'.$this->lang->line('CONTACT_REQUEST_FAILURE');
					$msg .= '</div>';
					$this->session->set_flashdata('contactMessage',$msg);
				}
				setRedirect(site_url().'contactus');
			}else{
				$msg = $this->lang->line('CONTACT_REQUEST_FAILURE');
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'.$msg.'</div>';
				$this->session->set_flashdata('contactMessages',$msg);
				setRedirect($_SERVER['HTTP_REFERER']);
			}
		}else{
			show_404();
		}
	}
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */