<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accountant extends CI_Model {
	public function Accountant()
	{
		parent::__construct();
	}
	
	public function getItems($limit = ACCOUNTANT_LISTING_PAGINATION_LIMIT,$start = 0)
	{
		$prefix = $this->db->dbprefix;
		$order = $this->session->userdata('accountant_sorting');
		if(isset($order) && !empty($order))
		{
			$orderby = " ORDER BY ".$order.' LIMIT '.$start.','.$limit;
		}else{
			$orderby = " ORDER BY u.ID DESC LIMIT ".$start.",".$limit;
		}
		$search = $this->session->userdata('accountant_search');
		
		$where = $this->search($start,$limit);
		$query  = 'SELECT CONCAT(u.FirstName," ",u.LastName) AS Name,u.ID,u.ContactNo,u.Email,c.EndDate,c.Name AS CompanyName,u.Status,u.State,';
		$query .= 'u.Activation,u.Params';
		$query .= ' FROM '.$prefix.'users AS u LEFT JOIN '.$prefix.'company AS c ON c.ClientID = u.ID';
		$query .= $where;
		//$query .= "AND u.status != 0";
		
		if(!empty($search))
		{
			$search_query = $this->db->query($query);
			
			$this->session->set_userdata('accountant_search_records',$search_query->num_rows());
		}
		$query .=' '.$orderby; 
		$query = $this->db->query($query,true);
	
		//echo $this->db->last_query();
	
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
	}
	
	public function search($start,$limit)
	{
		$search = $this->session->userdata('accountant_search');
		
		$user = $this->session->userdata('user');
		$userID = $user['UserID'];
		
		/** 
		 *	First check if search operation is performed or not.
		 *	Prepare where clause for the query according to the search criteria.
		 */
		
		$where = '';
		if($search != NULL)
		{
			if(!is_array($search))
			{
				$where = '';
			}else{
				//echo "<pre>";print_r($search);echo '</pre>';die;
				$search = array_filter($search);
				//echo "<pre>";print_r($search);echo '</pre>';die;
				if(count($search) <= 0)
				{
					$where = '';
				}else{
					foreach($search as $key=>$val)
					{
						if($key == 'Name')
						{
							$where[] .= 'CONCAT(u.FirstName," ",u.LastName) LIKE "%'.$val.'%"';
						}elseif($key == 'Email'){
							$where[] .= 'u.'.$key.'="'.$val.'"';
						}else{
							$where[] .= 'u.'.$key.'='.$val;
						}
					}
				}
			}
		}else{
			$where = '';
		}
		/*
		if($where == '')
		{
			$where = ' WHERE '."u.AddedBy=".$userID;
		}else{
			$where = implode(' AND ',$where);
			$where = ' WHERE '.$where.' AND u.AddedBy='.$userID;
		}
		*/
		
		if($where != '')
		{
			$where = implode(' AND ',$where);
			$where = ' WHERE '.$where;//.' AND u.AddedBy='.$userID;
			$where .= ' AND u.UserType="TYPE_ACC" AND u.ID <>'.$userID;
		}else{
			$where .= ' WHERE u.UserType="TYPE_ACC" AND u.ID <>'.$userID;
		}
		if(empty($user['AddedBy']))
		{
			$where .= ' AND u.AddedBy='.$userID;
		}else{
			$where .= ' AND (u.AddedBy='.$userID.' OR u.SubParent ='.$user['AddedBy'].' )';
		}
		return $where;
	}
	
	public function totalItems()
	{
		$search = $this->session->userdata('accountant_search');
		$user = $this->session->userdata('user');
		$user = $user['UserID'];
		$totalRecord = $this->session->userdata('accountant_search_records');
		if(isset($totalRecord) && !empty($totalRecord))
		{
			return $totalRecord;
		}
		$this->db->where('AddedBy',$user);
		$records = $this->db->count_all_results('users');
		if($records >0)
		{	
			return $records;
		}else{
			return 0;
		}
	}
	
	public function getItem($id = NULL)
	{
		$prefix = $this->db->dbprefix;
		if($id == NULL)
		{
			return FALSE;
		}
		$query  = 'SELECT u.FirstName,u.LastName,u.ID,u.ContactNo,u.Email,u.Params,u.Status,u.Activation';
		$query .= ' FROM '.$prefix.'users AS u WHERE ID = '.$id;
		$query = $this->db->query($query);
		/* CHECK FOR DB ERRORS */
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
		}else{
			$result = array();
		}
		$result = get_object_vars($result[0]);
		$result['Params'] = unserialize($result['Params']);
		return $result;
	}
	
	public function checkStatus($id)
	{
		$prefix = $this->db->dbprefix;
		if(empty($id))
		{
			return FALSE;
		}
		$query = $this->db->get_where('users',array('ID'=>$id,'Status'=>'1'));
		/* CHECK FOR DB ERRORS */
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function checkEmail($email = NULL,$id =NULL)
	{
		$prefix = $this->db->dbprefix;
		if(empty($id))
		{
			$query = "SELECT email FROM ".$prefix."users WHERE Email='".$email."'";
		}else{
			$query = "SELECT email FROM ".$prefix."users WHERE Email='".$email."' AND ID !=".$id;
		}
		$query = $this->db->query($query);
		if($query->num_rows() > 0)
		{
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function changeStatus($id,$status)
	{
		$prefix = $this->db->dbprefix;
		if($id == null)
		{
			return FALSE;
		}
		
		$this->db->update('users',array('Status'=>$status),array('ID'=>$id));
		/* CHECK FOR DB ERRORS */
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}
		return TRUE;
	}
	
	public function changeState($id)
	{
		$prefix = $this->db->dbprefix;
		$this->db->where('ID',$id);
		$this->db->update('users',array('State'=>'1'));
		/* CHECK FOR DB ERRORS */
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}
		return true;
	}
	
	public function delete_client($id)
	{
		$prefix = $this->db->dbprefix;
		$this->db->where('ID',$id);
		$this->db->delete('users');
		return true;
	}
	
	public function getEmail($id)
	{
		$prefix = $this->db->dbprefix;
		$this->db->select('');
		$this->db->select('Email,CONCAT(FirstName," '.'",LastName) AS Name',false);
		$query = $this->db->get_where('users',array('ID'=>$id));
		if($query->num_rows() > 0)
		{
			$data = $query->result();
			return $data;
		}else{
			return FALSE;
		}
	}
	
	public function clientLoginDetail($id = NULL)
	{
		$prefix = $this->db->dbprefix;
		$this->db->select('Email,Password');
		$query = $this->db->get_where('users',array('ID'=>$id));
		if($query->num_rows() > 0)
		{
			$query = $query->result();
			return $query;
		}else{
			return FALSE;
		}
	}
	
	public function save($data)
	{
		$prefix = $this->db->dbprefix;
		$this->db->insert('users',$data);
		/* CHECK FOR DB ERRORS */
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}
		$insert_id = $this->db->insert_id();
		if($this->db->affected_rows() > 0)
		{
			return $insert_id;
		}else{
			return FALSE;
		}
	}
	
	public function update($data,$id)
	{
		$prefix = $this->db->dbprefix;
		$this->db->update('users',$data,array('ID'=>$id));
		/* CHECK FOR DB ERRORS */
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}
		return TRUE;
	}
}
