<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll extends CI_Model {
	public function Payroll()
	{
		parent::__construct();
	}
	
	/**
	 *	Function to get the employee list of the client.
	 */
	public function employees($check = null)
	{
		$prefix = $this->db->dbprefix;
		$user = $this->session->userdata('user');
		$this->db->select('ID,CONCAT(FirstName," '.'",LastName) AS Name',false);
		$query = $this->db->get_where('company_customers',array('CompanyID'=>$user['CompanyID'],'IS_Employee'=>'1','DesignationType !=' => 'E'));
		//echo "<br>##".$this->db->last_query();//die;
		if($query->num_rows() > 0)
		{
			$query = $query->result();
			$record = array('0'=>'Select Employee');
			foreach($query as $key=>$val)
			{
				//$record[$this->encrypt->encode($val->EID)] = $val->Name;
				if(!empty($check))
				{
					$record[$val->ID] = $val->Name;
				}else{
					$record[] = $val->Name;
				}
				
			}
			return array_unique($record);
		}else{
			$no_record = array('0'=>'No Employees');
			return $no_record;
		}
	}
	
	public function maxFiles()
	{
		$prefix = $this->db->dbprefix;
		$query = "SELECT MAX(ID) AS ID FROM  ".$prefix."files";
		$query = $this->db->query($query);
		if($query->num_rows() > 0)
		{
			$response = $query->result();
			return $response[0]->ID;
		}else{
			return 0;
		}
	}
	
	public function saveFile($data)
	{
		$prefix = $this->db->dbprefix;
		$this->db->insert('files',$data);
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
	
	public function emlpoyeeID($name = NULL)
	{
		if(!empty($name))
		{
			$user = $this->session->userdata('user');
			$this->db->select('ID');
			//$query = $this->db->get_where('company_customers',array('CONCAT(FirstName," ",LastName)='=>$name,'CompanyID'=>$user['CompanyID'],'DesignationType' => 'E'));
			$query = $this->db->get_where('company_customers',array('CONCAT(FirstName," ",LastName)='=>$name,'CompanyID'=>$user['CompanyID']));
			//echo $this->db->last_query();
			if($query->num_rows() > 0)
			{
				$response = $query->result();
				return $response[0]->ID;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	public function saveEntries($data)
	{
		$prefix = $this->db->dbprefix;
		$this->db->insert_batch('salary',$data);
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}else{
			$insertid = $this->db->insert_id();
			update_logs('PAYROLL', 'USER_UPLOAD_PAYROLL', 'CREATE', "", $insertid);
			return TRUE;
		}
	}
	
	public function getItems($limit = SALARY_PAGINATION_LIMIT,$start = 0)
	{
		$prefix = $this->db->dbprefix;
		//echo 'Operation : '.$operation.'<br/>';
		$order = $this->session->userdata('SalarySortingOrder');
		if(isset($order) && !empty($order))
		{
			$orderby = " ORDER BY ".$order.' LIMIT '.$start.','.$limit;
		}else{
			$orderby = " ORDER BY s.ID DESC LIMIT ".$start.",".$limit;
		}
		$search = $this->session->userdata('SalarySearch');
		
		$where = $this->search($start,$limit);
		$query = "SELECT CONCAT(ce.FirstName,' ',ce.LastName) AS EmployeeName,";
		$query .= "s.ID,s.EID,s.FinancialYear,s.PayDate,s.NIC_Employee,s.Employeer_NIC,s.SMP,s.IncomeTax,";
		$query .= "s.NetPay,s.GrossSalary,s.AddedBy,s.AddedOn,s.PaidDate,s.Status";
		$query .= " FROM ".$prefix."salary AS s";
		$query .= " LEFT JOIN ".$prefix."company_customers AS ce ON ce.ID = s.EID";
		$query .= $where;

		if(!empty($search))
		{
		 $search_query = $this->db->query($query);
		 $this->session->set_userdata('SalarySearchRecords',$search_query->num_rows());
		}
		$query .=' '.$orderby; 
		
		$query = $this->db->query($query);
        //echo $this->db->last_query();      
		if($query->num_rows() > 0)
		{
			return $query->result();
		}else{
			return array();
		}
	}
	
	public function search($start,$limit)
	{
		$search = $this->session->userdata('SalarySearch');
		
		$user = $this->session->userdata('user');
		$userID = $user['UserID'];
		
		/* 
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
				
				if(count($search) <= 0)
				{
					$where = '';
				}else{
					//$where = 'WHERE ';
					foreach($search as $key=>$val)
					{
						if($key == 'FinancialYear')
						{
							$where[] = 's.'.$key.'="'.$val.'"';
						}else{
							$where[] = 's.'.$key.' = '.$val;
						}
					}
				}
			}
		}else{
			$where = '';
		}
		if($where == '')
		{
			$first_employee = $this->employees('check');
			
			unset($first_employee[0]);
			$current_year = currentFinancialYear();
			if(count($first_employee) > 0)
			{
				reset($first_employee);
				$first_key = key($first_employee);
				$first_employee = $first_key;
				
				//echo '<pre>';print_r($current_year);die;
				$where = " WHERE FinancialYear='".$current_year."' AND EID=".$first_employee." AND s.AddedBy=".$userID;
			}else{
				$where = " WHERE FinancialYear='".$current_year."' AND s.AddedBy=".$userID;
			}
		}else{
			$where = implode(' AND ',$where);
			$where = ' WHERE '.$where.' AND s.AddedBy='.$userID;
		}
		return $where;
	}
	
	public function totalEntries()
	{
		$prefix = $this->db->dbprefix;
		$search = $this->session->userdata('SalarySearch');
		$user = $this->session->userdata('user');
		$user = $user['UserID'];
		$totalRecord = $this->session->userdata('SalarySearchRecords');
		if(isset($totalRecord) && !empty($totalRecord))
		{
			return $totalRecord;
		}
		$this->db->where('AddedBy',$user);
		$records = $this->db->count_all_results('salary');
		if($records >0)
		{	
			return $records;
		}else{
			return 0;
		}
	}
	
		public function IndexPagegetPayee($year = null){
		$prefix = $this->db->dbprefix;
		$user = $this->session->userdata('user');
		$curf = currentFinancialYear();
		// if(empty($year))
		// {
			// $year = (date('Y')-1).' / '.date('Y');
			// $where = "WHERE AddedBy=".$user['UserID'].' AND FinancialYear="'.$year.'"';
		// }else{
			$where = "WHERE AddedBy=".$user['UserID'].' AND FinancialYear="'.$curf.'"';
		//}
		$query = "SELECT ID,Quarter,FinancialYear,IncomeTax,NIC_Employee,NIC_Employer,PayeeOfficeReference";
		$query .= ",HMRC_Refunds,PaidDate,Status,StartDate,EndDate,Total ";
		$query .= "FROM ".$prefix."payee ".$where.' ORDER BY Quarter LIMIT 0,4';
		$query = $this->db->query($query);
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
			$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
			$msg .= '</div>';
			log_message('error',$db_error['message']);
			$this->session->set_flashdata('payUploadError',$msg);
			return 'db_error';
		}
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			return $result;
		}else{
			return array();
		}
	}
	
	public function getPayee($year = null)
	{
		$prefix = $this->db->dbprefix;
		$user = $this->session->userdata('user');
		$curf = currentFinancialYear();
		if(empty($year))
		{
			$year = (date('Y')-1).' / '.date('Y');
			$where = "WHERE AddedBy=".$user['UserID'].' AND FinancialYear="'.$year.'"';
		}else{
			$where = "WHERE AddedBy=".$user['UserID'].' AND FinancialYear="'.$year.'"';
		}
		$query = "SELECT ID,Quarter,FinancialYear,IncomeTax,NIC_Employee,NIC_Employer,PayeeOfficeReference";
		$query .= ",HMRC_Refunds,PaidDate,Status,StartDate,EndDate,Total ";
		$query .= "FROM ".$prefix."payee ".$where.' ORDER BY Quarter LIMIT 0,4';
		$query = $this->db->query($query);
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
			$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
			$msg .= '</div>';
			log_message('error',$db_error['message']);
			$this->session->set_flashdata('payUploadError',$msg);
			return 'db_error';
		}
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			return $result;
		}else{
			return array();
		}
	}
	
	public function save_payee($data)
	{
		$prefix = $this->db->dbprefix;
		$this->db->insert_batch('payee',$data);
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
			$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
			$msg .= '</div>';
			log_message('error',$db_error['message']);
			$this->session->set_flashdata('payUploadError',$msg);
			return 'db_error';
		}
		
		if($this->db->affected_rows() > 0)
		{
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function quarterRange($range = null)
	{
		$prefix = $this->db->dbprefix;
		if($range == null)
		{
			$year = (date('Y')-1).' / '.date('Y');
		}else{
			$year = $range;
		}
	
		$user = $this->session->userdata('user');
		$query = "SELECT ID AS TotalRecord,Quarter FROM ".$prefix."payee WHERE FinancialYear = '".$year."' AND AddedBy=".$user['UserID'];
		$query = $this->db->query($query);
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
			$msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
			$msg .= '</div>';
			log_message('error',$db_error['message']);
			$this->session->set_flashdata('payUploadError',$msg);
			return 'db_error';
		}
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			return $result;
		}else{
			return 4;
		}
	}
	
	public function performAction($task)
	{
		$prefix = $this->db->dbprefix;
		switch($task[0])
		{	
			case 'ACTION_PAID':
				$data = array(
					'Status'	=>	1,
					'PaidDate'	=>	(!empty($task['PaidDate']))?mDate($task['PaidDate']):date('Y-m-d')
				);
				$this->db->where('ID',$task[1]);
				$this->db->update('payee',$data);
				$db_error = $this->db->error();
				if($db_error['code'] != 0)
				{
					log_message('error',$db_error['message']);
					return FALSE;
				}else{
					// update_trial_balance( "payee", $task[1] );
					return TRUE;
				}
				break;
			case 'ACTION_DELETE':
				$payData = $this->getPayeeDetails( (int) $task[1] );
				$this->db->where('ID',$task[1]);
				$this->db->delete('payee');
				$db_error = $this->db->error();
				if($db_error['code'] != 0)
				{
					log_message('error',$db_error['message']);
					return FALSE;
				}else{
					if($payData["Status"] == "1"){
						update_trial_balance( "payee", $payData , "" , "" , "DELETE" );
					}
					return TRUE;
				}
				
				
					
				break;
			default:
				break;
		}
	}
	
	public function getPayeeItem($ids)
	{
		$prefix = $this->db->dbprefix;
		$query = "SELECT * FROM ".$prefix."payee WHERE ID IN (".implode(',',$ids).")";
		$query = $this->db->query($query);
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}else{
			if($query->num_rows() > 0)
			{
				$result = $query->result();
				return $result;
			}else{
				return array();
			}
		}
	}
	
	public function updatePayeeItem($data)
	{
		$prefix = $this->db->dbprefix;
		$this->db->update_batch('payee',$data,'ID');
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	public function salaryAction($task)
	{
		$prefix = $this->db->dbprefix;
		switch($task[0])
		{	
			case 'ACTION_PAID':
				$data = array(
					'Status'	=>	1,
					'PaidDate'	=>	(!empty($task['PaidDate']))?mDate($task['PaidDate']):date('Y-m-d')
				);
				$this->db->where('ID',$task[1]);
				$this->db->update('salary',$data);
				$db_error = $this->db->error();
				if($db_error['code'] != 0)
				{
					log_message('error',$db_error['message']);
					return FALSE;
				}else{
					update_trial_balance( "salary", $task[1] );
					return TRUE;
				}
				break;
			case 'ACTION_DELETE':
				$salData = $this->getSalaryDetails( (int) $task[1] );
				$this->db->where('ID',$task[1]);
				$this->db->delete('salary');
				$db_error = $this->db->error();
				if($db_error['code'] != 0)
				{
					log_message('error',$db_error['message']);
					return FALSE;
				}else{
					if($salData["sStatus"] == "1"){
						update_trial_balance( "salary", $salData , "" , "" , "DELETE" );
					}
					/* Update ledger table */
					$this->db->delete('tb_details',array('itemId'=>$task[1],'source'=>'SALARY'));
					return TRUE;
				}
				break;
			default:
				break;
		}
	}
	
	public function getSalaryStatements()
	{
		$prefix = $this->db->dbprefix;
		$user = $this->session->userdata('user');
		$this->db->select('ID,EID,FinancialYear,PayDate,Status');
		$query = $this->db->get_where('salary',array('AddedBy'=>$user['UserID']));
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result;
		}else{
			return array();
		}
	}
	
	public function updateEntries($data)
	{
		$prefix = $this->db->dbprefix;
		$this->db->update_batch('salary',$data,'ID');
		$db_error = $this->db->error();
		if($db_error['code'] != 0)
		{
			log_message('error',$db_error['message']);
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	public function getSalaryDetails( $item )
	{
		$prefix = $this->db->dbprefix;
		$this->db->select( "*, cs.Status as sStatus, cs.ID as id" );
		$this->db->from( "salary as cs" );
		$this->db->join( "company_customers as ccc" , "cs.EID = ccc.ID", "left");
		$this->db->where( "cs.ID" , $item );
		$query = $this->db->get();
		// echo $this->db->last_query();
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			// prd( $result );
			return $result;
		}else{
			return false;
		}
		
	}
	
	public function getPayeeDetails( $item )
	{
		$prefix = $this->db->dbprefix;
		$this->db->select( "*" );
		$this->db->from( "payee as cp" );
		$this->db->where( "cp.ID" , $item );
		$query = $this->db->get();
		// echo $this->db->last_query();
		if( $query->num_rows() > 0 ){
			$result = $query->row_array();
			// prd( $result );
			return $result;
		}else{
			return false;
		}
		
	}
}
