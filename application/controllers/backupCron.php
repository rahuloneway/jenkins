<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class backupCron extends CI_Controller {

   	public function __construct()
	{
		parent::__construct();
		$this->load->model('backupCron_model');
	}
		
	public function index(){
	$db = $this->backupCron_model->database();
	
	$date = date('d-m-y', time()); 
	$folder = 'DB_Backup/';
	$filename = $folder."db-".$db."-".$date; 
	
	if(file_exists($filename.'.sql') ==1 )
	{
		echo "Backup Already Exists with filname ".$filename;
		exit;		
	}

	$return = $this->backupCron_model->all_tables();


// Create Backup Folder
$folder = 'DB_Backup/';
if (!is_dir($folder))
mkdir($folder, 0777, true);
chmod($folder, 0777);

$date = date('d-m-y', time()); 
$filename = $folder."db-".$db."-".$date; 
$handle = fopen($filename.'.sql','w+');
fwrite($handle,$return);
fclose($handle);
}




}
