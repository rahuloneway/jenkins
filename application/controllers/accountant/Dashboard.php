<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function Dashboard()
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
		
		$this->load->model('accountant/cpanel');
	}
	
	public function index()
	{
		//die('dashboard controller 24');
		$data['page']	=	'accountant_dashboard';
		$data['title']	=	'Cashmann | Dashboard';		
		$data['annual_items']	=	$this->cpanel->get_annual_items();
		$data['return_items']	=	$this->cpanel->get_return_items();
		$data['vatdue_items']	=	$this->cpanel->get_vatdue_items();
				
		//echo "<pre>";print_r($data['vatdue_items']); die;
		
		$this->load->view('accountant/dashboard/default',$data);
	}
	
	public function executeFxn()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$task = $this->encrypt->decode( $this->input->post('task') );
			$this->$task();
		}else{
			show_404();
		}
	}
	
	public function markAccountsFiled(){
		$response = $this->cpanel->markAccountsFiled();
		$this->buildJSONResponse($response);
	}
	
	public function markReturnsFiled(){
		$response = $this->cpanel->markReturnsFiled();
		$this->buildJSONResponse($response);
	}
	
	public function buildJSONResponse( $response ){
		
		$msg ="";
		if($response["success"]){
			if( count($response["success"]) > 0){
				$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
				if(is_array( $response["success"] )){
					foreach($response["success"] as $Smsg){
						$msg .= $Smsg."<br/>";
					}
				}else{
					$msg .= $response["success"]."<br/>";
				}
				$msg .= '</div>';
				$this->session->set_flashdata("dashboardErrors", $msg);
			}
			echo json_encode(array("success"=>$msg));
		}else{
			if( count($response["error"]) > 0){
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
				if(is_array( $response["success"] )){
					foreach($response["error"] as $err){
						$msg .= $err."<br/>";
					}
				}else{
					$msg .= $response["error"]."<br/>";
				}
				$msg .= '</div>';
				$this->session->set_flashdata("dashboardErrors", $msg);
			}
			echo json_encode(array("error"=>$msg));
		}
		exit();
		
	}
	
	/*
	function tbCarryFwd(){
		$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . "tb_ctt.txt";
		$contents = file_get_contents($file);
		$lines = explode("\n", $contents); // this is your array of words
		// prd( $lines );
		$into = false;
		$insert = " INSERT INTO `cashman_tb_carry_fwd`(`target_catKey`, `host_catKey`, `fxn`) VALUES <br/>";
		$i =0;
		foreach($lines as $word){
			if( $i%99 == 0 && $i !=0 ){
				$insert .= "; INSERT INTO `cashman_tb_carry_fwd`(`target_catKey`, `host_catKey`, `fxn`) VALUES <br/>";
			}
			if( !$into ){
				$into = trim($word);
			}
			if( trim($word) == "========================="){
				$into = false;
			}else{
				$insert .= "('".trim($into)."','".trim($word)."','ADD'), <br/>";
				// echo $word; 
				// echo "<br/>"; 
			}
			$i++;
		}
		echo $insert;
	}
	*/
	
}
