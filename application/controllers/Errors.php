<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Errors extends CI_Controller 
{
	public function Errors()
	{
		parent::__construct();
	}
	/*
	public function index()
	{
		$data['page'] 	=	'errors';
		$data['title']	=	'Unknown Error';
		$this->output->set_status_header('404'); 
		$data['content'] = 'error_404'; // View name 
		///$this->load->view('index',$data);//loading in my template 
		$this->load->view('errors/default',$data);
	}
	*/
	
	public function index() 
    { 
        $this->output->set_status_header('404');
		$data['page'] 	=	'errors';
		$data['title']	=	'Unknown Error';		
        $data['content'] = 'error_404'; // View name 
        $this->load->view('errors/html/error_404',$data);//loading in my template 
    }
}