<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notes extends CI_Controller 
{
	public function Notes()
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
		$this->load->model('clients/note');
	}
	
	public function index()
	{
		$data['page']		= 'notes';
		$data['title']		= 'Cashmann | Notes';
		$data['items']		=	$this->note->getItems();
		$this->load->view('client/notes/default',$data);
	}
	
	public function save()
	{
		if($this->input->is_ajax_request())
		{
			$user = $this->session->userdata('user');
			$data	=	array(
				'Description'	=>	$this->db->escape(strip_tags(safe($_POST['Description']))),
				'ClientID'		=>	$user['UserID'],
				'AddedOn'		=>	date('Y-m-d'),
				'AddedBy'		=>	clientAccess()
			);
			$response = $this->note->save($data);
			if($response)
			{
				$data['items']		=	$this->note->getItems();
				$json['html']		=	$this->load->view('client/notes/notes_listing',$data,true);
				$json['error'] 		= 	'';
			}else{
				$msg = $this->lang->line('UNEXPECTED_ERROR_OCCURED');
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'.$msg.'</div>';
				$this->session->set_flashdata('notesMessage',$msg);
				$json['error'] = 'error';
			}
			echo json_encode($json);die;
		}else{
			show_404();
		}
	}
	
	public function delete()
	{
		if($this->input->is_ajax_request())
		{
			$task = safe($_POST['task']);
			$id = $this->encrypt->decode($task);
			$response = $this->note->delete($id);
			if($response)
			{
				$data['items']		=	$this->note->getItems();
				$json['html']		=	$this->load->view('client/notes/notes_listing',$data,true);
				$json['error'] 		= 	'';
			}else{
				$msg = $this->lang->line('UNEXPECTED_ERROR_OCCURED');
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;'.$msg.'</div>';
				$this->session->set_flashdata('notesMessage',$msg);
				$json['error'] = 'error';
			}
			echo json_encode($json);die;
		}else{
			show_404();
		}
	}
}
