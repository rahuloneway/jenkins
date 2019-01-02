<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document extends CI_Model {
	public function Document()
	{
		parent::__construct();
	}
	
	function saveFile($data)
	{
		$this->db->insert('files',$data);
		if($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}else{
			return FALSE;
		}
	}
	
	public function saveFolder($data)
	{
		$this->db->insert('folders',$data);
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}else{
			return TRUE;
		}
		if($this->db->affected_rows() > 0)
		{
			return $this->db->insert_id();
		}else{
			return FALSE;
		}
	}
	
	public function checkFolder($id)
	{
		$this->db->select('ID,ParentFolder,FolderPath,FolderName');
		$query = $this->db->get_where('folders',array('ID'=>$id));
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			return $result;
		}else{
			return array();
		}
	}
	
	public function deleteRecord($id)
	{
		/* STEP -1 Delete the file record from the database */
		$user = $this->session->userdata('user');
		$this->db->where(array('AssociatedWith'=>$id,'Type'=>'DOC','AccountantAccess'=>$user['AccountantAccess']));
		$this->db->delete('files');
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}
		
		$this->db->where('ID',$id);
		$this->db->delete('folders');
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}
		
		/* NO error return true*/
		return TRUE;
	}
	
	public function directoryStructure()
	{
		$prefix = $this->db->dbprefix;
		$user = $this->session->userdata('user');
		$folders = folder('parent');
		/* Get parent folders file */
		$query = "SELECT ID,FName,AssociatedWith,FSize FROM ".$prefix."files WHERE UploadedBy=".$user['UserID'];
		//$query .= " AND AccountantAccess=".$user['AccountantAccess'];
		$query = $this->db->query($query);
		$parent_files = array();
		if($query->num_rows() > 0)
		{
			$parent_files = $query->result();
		}
		
		//echo '<pre>';print_r($folders);echo '</pre>';
		$query = "SELECT * FROM ".$prefix."folders WHERE AddedBy=".$user['UserID'];
		$query = $this->db->query($query);
		
		
		$directory = array();
		if($query->num_rows()>0)
		{
			$result = $query->result();
			foreach($folders as $key=>$val)
			{
				$temp_dir = array();
				foreach($result as $k=>$v)
				{
					if($v->ParentFolder == $key)
					{
						$v->DType	=	"FOLDER";
						/* Check if this folder have any files */
						$sub_files = array();
						if(count($parent_files) > 0)
						{
							foreach($parent_files as $xx=>$yy)
							{
								if($v->ID == $yy->AssociatedWith)
								{
									$yy->DType	=	"FILE";
									$sub_files[] = $yy;
								}
							}
						}
						$v->Files = $sub_files;
						$temp_dir[] = $v;
					}
				}
				if(count($temp_dir) > 0)
				{
					$directory[$key] = $temp_dir;
				}else{
					$directory[$key] = '';
				}
				/* Check if parent folders have any files */
				if(count($parent_files) > 0)
				{
					$parent_folder = array();
					foreach($parent_files as $x=>$y)
					{
						if($key == $y->AssociatedWith)
						{
							$y->DType	=	"FILE";
							$directory[$key][] = $y;
						}
					}
				}
			}
			
		}else{
			/* Check if parent folders have any files */
			if(count($parent_files) > 0)
			{
				foreach($folders as $key=>$val)
				{
					$parent_folder = array();
					foreach($parent_files as $x=>$y)
					{
						if($key == $y->AssociatedWith)
						{
							$y->DType	=	"FILE";
							$parent_folder[] = $y;
						}
					}
					$directory[$key] = $parent_folder;
				}
			}
			
			//$directory = $folders;
		}
		
		return $directory;
		//echo '<pre>';print_r($directory);echo '</pre>';die;
	}
	
	public function deleteFiles($id)
	{
		$this->db->delete('files',array('ID'=>$id));
		if($this->db->affected_rows() > 0)
		{
			return TRUE;
		}else{
			return FALSE;
		}
	}
}
