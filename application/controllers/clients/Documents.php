<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Documents extends CI_Controller {
	public function Documents()
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
		$this->load->model('clients/document');
	}
	
	public function index()
	{
		$data['title']	=	'Cashman | My Documents';
		$data['page']	=	'documents';
		$data['access']	=	clientAccess();
		$data['directory_structure'] = $this->document->directoryStructure();
		$this->load->view('client/documents/default',$data);
	}
	
	public function uploadDocuments()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			/* Check if Created by accountant while accessing the client account */
			$accountant_access = clientAccess();
			
			$user = $this->session->userdata('user');
			
			$category_folder = folder();
			/* Check if chosen folder is children or parent */
			$response = $this->document->checkFolder(safe($_POST['documentsCategory']));
			$response = $response[0];
			if($response->ParentFolder == 0)
			{
				$category_folder = $category_folder[$response->ID];
				$path = 'assets/uploads/documents/'.$category_folder.'/'.$user['UserID'].'/';
			}else{
				$parent = $category_folder[$response->ParentFolder];
				$children = str_replace('&nbsp;','',$category_folder[$response->ID]);
				$path = 'assets/uploads/documents/'.$parent.'/'.$user['UserID'].'/'.$children.'/';
			}
			//$category_folder = trim($category_folder);
			//echo 'Category : '.$category_folder;
			//echo '<pre>';print_r($response);echo '</pre>';
			//echo '<pre>';print_r($_FILES);echo '</pre>';die;
			
			
			
			/* STEP - 1 Check if the folder of client exists in the corresponding Category Folder */
			if(!file_exists($path))
			{
				//echo 'Path : '.$path;die;
				/* If not exists then create one */
				if(!mkdir($path,0777, TRUE))
				{
					log_message('error','Error in uploading the file to the server');
					$msg = '<div class="alert alert-danger">';
					$msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>';
					$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURRED');
					$msg .= '</div>';
					$this->session->set_flashdata('uploadDocumentError',$msg);
					setRedirect('documents');
				}
			}

			
			if(!file_exists($path.'/'.$_FILES['file']['name']))
			{
				$file_name = str_replace(' ','_',$_FILES['file']['name']);
			}else{
				$op = $_POST['operation'];
				if($op == 'keep')
				{
					$number = count(glob($path.$_FILES['file']['name']));
					//$file_name = explode('.',$_FILES['file']['name']);
					$file_name = $this->file_newname($path, $_FILES['file']['name']);
				}elseif($op == 'replace'){
					$file_name = str_replace(' ','_',$_FILES['file']['name']);
					if(!unlink($path.'/'.$_FILES['file']['name']))
					{
						log_message('error','Unable to replace the file');
						$msg = '<div class="alert alert-danger">';
						$msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
						$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
						$msg .= '</div>';
						$this->session->set_flashdata('uploadDocumentError',$msg);
						setRedirect(site_url().'documents');
					}
					//echo '<pre>';print_r($response);echo '</pre>';die;
					$delete_file_id = getFileID($file_name,$response->ID);
					//echo '<pre>';print_r($delete_file_id);echo '</pre>';die;
					$response = $this->document->deleteFiles($delete_file_id->ID);
					if(!$response)
					{
						log_message('error','Unable to delete file record from the database');
						$msg = '<div class="alert alert-danger">';
						$msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
						$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
						$msg .= '</div>';
						$this->session->set_flashdata('uploadDocumentError',$msg);
						setRedirect(site_url().'documents');
					}
				}else{
					$file_name = str_replace(' ','_',$_FILES['file']['name']);
				}
			}
			
			$file_record = array(
				'FName'				=>	$file_name,
				'FType'				=>	$_FILES['file']['type'],
				'FSize'				=>	$_FILES['file']['size'],
				'UploadedOn'		=>	date('Y-m-d'),
				'UploadedBy'		=>	$user['UserID'],
				'Type'				=>  'DOC',
				'AccountantAccess'	=>	$accountant_access,
				'AssociatedWith'	=>	safe($_POST['documentsCategory'])
			);
			/* If folder already exists then copy the file to the destination folder */
			$destination_path = $path;
			$config['upload_path'] = $destination_path;
			$config['allowed_types'] = '*';
			$config['max_size']	= '1000';
			$config['max_width']  = '1024';
			$config['max_height']  = '768';
			$config['file_name']  = $file_name;

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('file'))
			{
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
				$msg .= $this->upload->display_errors();
				$msg .= '</div>';
				$this->session->set_flashdata('uploadDocumentError',$msg);
				setRedirect(site_url().'documents');
			}
			
			/* STEP - 2 After successful upload add the file record in the database name */
			$file_id = $this->document->saveFile($file_record);
			if(empty($file_id))
			{
				$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
				$msg .= $this->lang->line('DOCUMENT_FILE_UPLOAD_ERROR');
				$msg .= '</div>';
				$this->session->set_flashdata('uploadDocumentError',$msg);
				setRedirect(site_url().'documents');
			}
			
			/* STEP - 3 If all steps processed successfully return to the listing view */
			$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
			$msg .= $this->lang->line('DOCUMENT_FILE_UPLOAD_SUCCESS');
			$msg .= '</div>';
			$this->session->set_flashdata('uploadDocumentError',$msg);
			setRedirect(site_url().'documents');
		}else{
			show_404();
		}
	}
	
	public function download($id = null)
	{
		$id  = $this->encrypt->decode($id);
		$id = explode('/',$id);
		//echo 'Count : '.count($id);
		
		if(!is_numeric(end($id)))
		{
			show_404();
		}
		
		$user = $this->session->userdata('user');
		$file = getFileInfo(end($id));
	
		/* Replace the file id with its name */
		$id[array_search(end($id),$id)] = $file->FName;
		$name = $file->FName;
		$id = implode('/',$id);
		
		$path = 'assets/uploads/documents/'.$id;
		
		//echo 'Numeric : '.$path;
		//echo 'Name : '.$name;die;
		if(!file_exists($path))
		{
			show_404();
		}
		$this->load->helper('download');
		$data = file_get_contents($path);
		//echo '<pre>';print_r($data);echo '</pre>';die;
		force_download($name, $data);
	}
	
	public function form()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$json['html'] = $this->load->view('client/documents/document_folder','',true);
			echo json_encode($json);die;
		}else{
			show_404();
		}
	}
	
	public function saveFolder()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			//echo '<pre>';print_r($_POST);echo '</pre>';die;
			$user = $this->session->userdata('user');
			$folder = array(
				'FolderName'	=>	safe($_POST['FolderName']),
				'ParentFolder'	=>	safe($_POST['ParentFolder']),
				'AddedBy'		=>	$user['UserID'],
				'AddedOn'		=>	date('Y-m-d'),
				'AccountantAccess'=>	$user['AccountantAccess']
			);
			$category_name = folder('parent');
			$category_name = $category_name[$folder['ParentFolder']];
			$path = 'assets/uploads/documents/'.$category_name.'/'.$user['UserID'].'/';
			
			/* Check if folder already exists */
			if(file_exists($path.$folder['FolderName']))
			{
				$msg = '<div class="alert alert-info">';
				$msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
				$msg .= sprintf($this->lang->line('DOCUMENT_FOLDER_EXISTS_ALREADY'),$folder['FolderName']);
				$msg .= '</div>';
				$this->session->set_flashdata('uploadDocumentError',$msg);
				setRedirect(site_url().'documents');
			}
			
			if (!mkdir($path.$folder['FolderName'], 0777, true)){
				log_message('error','Problem in creating folder');
				$msg = '<div class="alert alert-danger">';
				$msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
				$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
				$msg .= '</div>';
				$this->session->set_flashdata('uploadDocumentError',$msg);
				setRedirect(site_url().'documents');
			}
			$folder['FolderPath'] = $path;
			$response = $this->document->saveFolder($folder);
			if($response)
			{
				$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
				$msg .= $this->lang->line('DOCUMENT_FOLDER_CREATION_SUCCESS');
				$msg .= '</div>';
				$this->session->set_flashdata('uploadDocumentError',$msg);
				setRedirect(site_url().'documents');
			}else{
				$msg = '<div class="alert alert-danger">';
				$msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
				$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
				$msg .= '</div>';
				$this->session->set_flashdata('uploadDocumentError',$msg);
				setRedirect(site_url().'documents');
			}
		}else{
			show_404();
		}
	}
	
	public function deleteFolder($id = NULL)
	{
		$id = $this->encrypt->decode($id);
		if(empty($id) && !is_numeric($id))
		{
			show_404();
		}
		
		$user = $this->session->userdata('user');
		/* STEP - 1 Delete the folder from the server */
		$id = explode('/',$id);
		$file_id = end($id);
		unset($id[array_search($file_id,$id)]);
		$id = implode('/',$id);
		$path = $id;
		//echo '<pre>';print_r($path);echo '</pre>';die;
		$this->load->helper('file');
		if(!delete_files($path,true))
		{
			log_message('error','Unable to delete the folder');
			$msg = '<div class="alert alert-danger">';
			$msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
			$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
			$msg .= '</div>';
			$this->session->set_flashdata('uploadDocumentError',$msg);
			setRedirect(site_url().'documents');
		}
		
		/* STEP - 2 Delete the empty folder */
		rmdir($path);
		
		/* STEP - 3 Delete the files and folder record from the database also */
		$response = $this->document->deleteRecord($file_id);
		if($response)
		{
			$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
			$msg .= $this->lang->line('DOCUMENT_FOLDER_DELETE_SUCCESS');
			$msg .= '</div>';
			$this->session->set_flashdata('uploadDocumentError',$msg);
			setRedirect(site_url().'documents');
		}else{
			$msg = '<div class="alert alert-danger">';
			$msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
			$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
			$msg .= '</div>';
			$this->session->set_flashdata('uploadDocumentError',$msg);
			setRedirect(site_url().'documents');
		}
	}
	
	public function deleteFile($id = NULL)
	{
		$id  = $this->encrypt->decode($id);
		$id = explode('/',$id);
		//echo 'Count : '.count($id);
		//echo '<pre>';print_r($id);echo '</pre>';die;
		if(!is_numeric(end($id)))
		{
			show_404();
		}
		
		$user = $this->session->userdata('user');
		$file = getFileInfo(end($id));
		$file_id = end($id);
		/* Replace the file id with its name */
		$id[array_search(end($id),$id)] = $file->FName;
		$name = $file->FName;
		
		$id = implode('/',$id);
		
		$path = 'assets/uploads/documents/'.$id;
		
		/* STEP - 2 Delete file from the folder */
		try{
			unlink($path);
		}catch(Exception $e){
			$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
			//$msg .= $this->lang->line('DOCUMENTS_DELETE_ERROR');
			$msg .= $e->getMessage();
			$msg .= '</div>';
			$this->session->set_flashdata('uploadDocumentError',$msg);
			setRedirect(site_url().'documents');
		}
		
		/* STEP - 3 Delete file record from the database */
		$response = $this->document->deleteFiles($file_id);
		if(!$response)
		{
			$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
			$msg .= $this->lang->line('DOCUMENTS_DELETE_FILE_RECORD_ERROR');
			$msg .= '</div>';
			$this->session->set_flashdata('uploadDocumentError',$msg);
			setRedirect(site_url().'documents');
		}else{
			$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
			$msg .= $this->lang->line('DOCUMENT_DELETE_FILE_SUCCESS');
			$msg .= '</div>';
			$this->session->set_flashdata('uploadDocumentError',$msg);
			setRedirect(site_url().'documents');
		}
	}
	
	public function checkFile()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$user = $this->session->userdata('user');
			$file_name = $_POST['FileName'];
			$folder_name = $_POST['CatName'];
			//echo 'Folder Name : '.$folder_name;
			$file_name = str_replace(' ','_',$file_name);
			/* Get Folder PATH */
			$response = $this->document->checkFolder($folder_name);
			if($response[0]->ParentFolder == 0)
			{
				$path = $response[0]->FolderPath.$response[0]->FolderName.'/'.$user['UserID'].'/'.$file_name;
			}else{
				$path = $response[0]->FolderPath.$response[0]->FolderName.'/'.$file_name;
			}
			
			$json['error'] = '';
			//echo '<br/>'.$path.' - '.file_exists($path);die;
			if(file_exists($path))
			{
				$json['error'] = 'error';
			}
			echo json_encode($json);die;
		}else{
			show_404();
		}
	}
	
	function file_newname($path, $filename){
		if ($pos = strrpos($filename, '.')) {
			   $name = substr($filename, 0, $pos);
			   $ext = substr($filename, $pos);
		} else {
			   $name = $filename;
		}

		$newpath = $path.'/'.$filename;
		$newname = $filename;
		$counter = 0;
		while (file_exists($newpath)) {
			   $newname = $name .'_'. $counter . $ext;
			   $newpath = $path.'/'.$newname;
			   $counter++;
		 }

		return $newname;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */