<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Supplier extends CI_Model {

    public function Supplier() {
        parent::__construct();
    }

    public function getsupplier($limit = suppliers_PAGINATION_LIMIT, $start = 0, $filter = null, $supplierId = NULL) {
        $user = $this->session->userdata('user');
        $userId = $user['UserID'];
        $prefix = $this->db->dbprefix;
        $table = $prefix . "suppliers";
        $accId = $user['AccountantAccess'];
        $userId = $user['UserID'];
        if (!empty($supplierId)) {
            $query = $this->db->query("select * from $table where id = $supplierId and clientId=$userId  order by Id DESC limit  $start, $limit");
        } else {
            $query = $this->db->query("select * from $table where clientId=$userId order by Id DESC limit  $start, $limit");
        }
        $resutl = $query->result_array();
        if (count($resutl) > 0) {
            return $resutl;
        }
    }

    public function getsupplierlist() {
        $user = $this->session->userdata('user');
        $userId = $user['UserID'];
        $prefix = $this->db->dbprefix;
        $table = $prefix . "suppliers";
        $query = $this->db->query("select Id,CONCAT(first_name,' ',first_name) as suppliername  from $table where clientId=$userId order by first_name ASC");
        $resutl = $query->result_array();
        if (count($resutl) > 0) {
            return $resutl;
        }
    }

    public function totalsuppliers($supplierId = NULL) {
        $user = $this->session->userdata('user');
        $userId = $user['UserID'];
        $prefix = $this->db->dbprefix;
        $table = $prefix . "suppliers";
        if (!empty($customerId)) {
            $query = $this->db->query("select * from $table where  id = $supplierId and clientId=$userId");
        } else {
            $query = $this->db->query("select * from $table where clientId=$userId");
        }
        $resutl = $query->result_array();
        if (count($resutl) > 0) {
            return count($resutl);
        }
    }

    public function save($data = NULL) {
        if (!empty($data)) {
            $prefix = $this->db->dbprefix;
            $this->db->insert($prefix . 'suppliers', $data);
            return $data['first_name'] . " " . $data['last_name'];
        }
    }

    public function delsupplier($id = NULL) {
        if (!empty($id)) {
            $prefix = $this->db->dbprefix;
            $table = $prefix . "suppliers";
            $query = $this->db->query("select * from $table where  id = $id");
            $resutl = $query->result_array();
            $this->db->delete($prefix . 'suppliers', array('id' => $id));
            return $resutl[0]['first_name'] . " " . $resutl[0]['last_name'];
        }
    }

    public function editSupplier($id = NULL) {
        if (!empty($id)) {
            $prefix = $this->db->dbprefix;
            $table = $prefix . "suppliers";
            $query = $this->db->query("select * from $table where  id = $id");
            $resutl = $query->result_array();
            return $resutl;
        }
    }

    public function updateSupplier($sid = NULL, $data = NULL) {
        if (!empty($sid) && !empty($data)) {
            $prefix = $this->db->dbprefix;
            $this->db->where('id', $sid);
            $this->db->update($prefix . "suppliers", $data);
            return $data['first_name'] . " " . $data['last_name'];
        }
    }

    public function changesupplierstatus($id = NULL) {
        $prefix = $this->db->dbprefix;
        $table = $prefix . "suppliers";
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
        $this->db->update($prefix . "suppliers", $data);
        return $resutl[0]['first_name'] . " " . $resutl[0]['last_name'];
    }

    public function checkemail($email = null) {
        if (!empty($email)) {
            $prefix = $this->db->dbprefix;
            $table = $prefix . "suppliers";
            $query = $this->db->query("select * from $table where  email = '$email'");
            $resutl = $query->result_array();

            if (count($resutl) > 0) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }
	
	#insert new customer category in trial balance category table
	public function saveSupplierCategory($data = NULL) {
        if(!empty($data)){
            $prefix = $this->db->dbprefix;
            $this->db->insert($prefix . 'trial_balance_categories', $data);
			//echo $this->db->last_query();           
            return TRUE;          
        }
    }

}

?>
