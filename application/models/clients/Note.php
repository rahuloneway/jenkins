<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Note extends CI_Model {
	public function Note()
	{
		parent::__construct();
	}
	
	public function getItems()
	{
		$prefix = $this->db->dbprefix;
		$user = $this->session->userdata('user');
		$query = "SELECT * FROM ".$prefix."notes WHERE ClientID=".$user['UserID'];
		$query = $this->db->query($query);
		$result = $query->result();
		return $result;
	}
	
	public function save($data)
	{
		$this->db->insert('notes',$data);
		if($this->db->affected_rows() > 0)
		{
			$insert_id = $this->db->insert_id();
			update_logs('NOTES', 'USER_CREATED_NOTE', 'CREATE', "", $insert_id);
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function delete($id)
	{
		$this->db->where('ID',$id);
		$this->db->delete('notes');
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}
		if($this->db->affected_rows() < 0)
		{
			return FALSE;
		}else{
			update_logs('NOTES', 'USER_DELETED_NOTE', 'DELETE', "", $id);
			return TRUE;
		}
	}
}