<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer extends CI_Model {

    public function Customer() {
        parent::__construct();
    }

    public function getCustomer($limit = CUSTOMERS_PAGINATION_LIMIT, $start = 0, $filter = null, $customerId = NULL, $companyname = NULL, $email = NULL, $mobile = NULL) {
        $user = $this->session->userdata('user');
        $userId = $user['UserID'];
		$userCId = $user['CompanyID'];	
        $prefix = $this->db->dbprefix;
        $table = $prefix . "customers";
        $accId = $user['AccountantAccess'];
        $userId = $user['UserID'];
        if (!empty($customerId)) { 
            //$query = $this->db->query("select * from $table where id = $customerId and clientId=$userId  and ClientCID=$userCId and status=1 order by Id DESC limit  $start, $limit");
            $query = $this->db->query("select * from $table where id = $customerId and clientId=$userId  and ClientCID=$userCId order by Id DESC limit  $start, $limit");
        }
        else if(!empty($companyname)){ 
            $query = $this->db->query("select * from $table where  clientId = $userId  and ClientCID=$userCId and  companyname=$companynam  order by Id DESC limit  $start, $limit");
        } else { 
            
            $query = $this->db->query("select * from $table where clientId=$userId and ClientCID=$userCId  order by Id DESC limit  $start, $limit");
        }		
        $resutl = $query->result_array();		
        if (count($resutl) > 0) {

            return $resutl;
        }
    }

    public function getCustomerlist() {
        $user = $this->session->userdata('user');
        $userId = $user['UserID'];
        $userCId = $user['CompanyID'];		
        $prefix = $this->db->dbprefix;
        $table = $prefix . "customers";
        //$query = $this->db->query("select Id,CONCAT(first_name,' ',first_name) as customername  from $table where clientId=$userId AND ClientCID=$userCId order by first_name ASC");
        $query = $this->db->query("select Id, companyname as customername  from $table where clientId=$userId AND ClientCID=$userCId order by first_name ASC");
        //echo $this->db->last_query(); //die;
		$resutl = $query->result_array();
        if (count($resutl) > 0) {
            return $resutl;
        }
    }

    public function totalcustomers($customerId = NULL) {
        $user = $this->session->userdata('user');
        $userId = $user['UserID'];
		$userCId = $user['CompanyID'];		
        $prefix = $this->db->dbprefix;
        $table = $prefix . "customers";
        if (!empty($customerId)) {
            $query = $this->db->query("select * from $table where  id = $customerId and clientId=$userId  and ClientCID=$userCId");
        } else {
            $query = $this->db->query("select * from $table where clientId=$userId");
        }
        $resutl = $query->result_array();
        if (count($resutl) > 0) {
            return count($resutl);
        }
    }

    public function save($data = NULL,$page = NULL) {
        if (!empty($data)) {
            $prefix = $this->db->dbprefix;
            $this->db->insert($prefix . 'customers', $data);
			//echo $this->db->last_query(); //die;
            if($page !== ''){
				$result = array();
				//$result['name'] = $data['first_name']." ".$data['last_name']; 				
				$result['name'] = $data['companyname']; 				
				$result['id'] =  categoryIDTrialCat($data['TB_Category']);  
				return $result;
			}else{
				return $data['first_name'] . " " . $data['last_name'];
			}
			
        }
    }

    public function delcustomer($id = NULL) {
        if (!empty($id)) {
            $prefix = $this->db->dbprefix;
            $table = $prefix . "customers";
            $query = $this->db->query("select * from $table where  id = $id");
            $resutl = $query->result_array();
            $this->db->delete($prefix . 'customers', array('id' => $id));
            return $resutl[0]['first_name'] . " " . $resutl[0]['last_name'];
        }
    }

    public function editCustomer($id = NULL) {
        if (!empty($id)) {
            $prefix = $this->db->dbprefix;
            $table = $prefix . "customers";
            $query = $this->db->query("select * from $table where  id = $id");
            $resutl = $query->result_array();
            return $resutl;
        }
    }

    public function updateCustomer($cid = NULL, $data = NULL) {
        if (!empty($cid) && !empty($data)) {
            $prefix = $this->db->dbprefix;
            $this->db->where('id', $cid);
            $this->db->update($prefix . "customers", $data);
			//echo $this->db->last_query(); die; 
            return $data['companyname'];
        }
    }

    public function changecustomerStatus($id = NULL) {
        $prefix = $this->db->dbprefix;
        $table = $prefix . "customers";
        $query = $this->db->query("select * from $table where  id = $id");
        $resutl = $query->result_array();

        $this->db->where('id', $id);
        if ($resutl[0]['status'] == 1) {
            $status = 2;
        } else {
            $status = 1;
        }
        $data = array(
            'status' => $status
        );
        $this->db->update($prefix . "customers", $data);
        return $resutl[0]['first_name'] . " " . $resutl[0]['last_name'];
    }

    public function checkemail($email = null, $id = null) {
        if (!empty($email)) {
            $prefix = $this->db->dbprefix;
            $table = $prefix . "customers";

            if (empty($id)) {
                $query = "SELECT email FROM $table  WHERE email='" . $email . "'";
            } else {
                $query = "SELECT email FROM  $table  WHERE email='" . $email . "' AND id !=" . $id;
            }

            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
	
	#insert new customer category in trial balance category table
	public function saveCustomerCategory($data = NULL) {
        if(!empty($data)){
            $prefix = $this->db->dbprefix;
            $this->db->insert($prefix . 'trial_balance_categories', $data);
			//echo $this->db->last_query();           
            return TRUE;          
        }
    }

}

?>