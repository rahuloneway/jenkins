<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Model {
	public function Contact()
	{
		parent::__construct();
	}
	
	public function getRequestCategories()
	{
		$this->db->select('Title,ID,CategoryType');
		$query = $this->db->get_where('expenses_category',array('CategoryType'=>'REQ'));
		if($query->num_rows() > 0)
		{
			$data = array(''=>'Please select the reason');
			foreach($query->result() as $key=>$val)
			{
				$data[$val->ID] = $val->Title;
			}
			return $data;
		}else{
			return array('0'=>'No reason title added');
		}
	}
	
	public function save($data)
	{
		$this->db->insert('messages',$data);
		if($this->db->affected_rows() > 0)
		{
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function getUserInfo($id)
	{
		$this->db->select('Email,AddedBy,CONCAT(FirstName," '.'",LastName) AS Name',false);
		$query = $this->db->get_where('users',array('ID'=>$id));
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			return $data;
		}else{
			return FALSE;
		}
	}
}