<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Term extends CI_Model {

    public function Term() {
        parent::__construct();
    }

    function saveFile($data) {
        $prefix = $this->db->dbprefix;
        $this->db->insert($prefix . 'term_conditions', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    public function saveFolder($data) {
        $this->db->insert('folders', $data);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        } else {
            return TRUE;
        }
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    public function checkFolder($id) {
        $this->db->select('ID,ParentFolder,FolderPath,FolderName');
        $query = $this->db->get_where('folders', array('ID' => $id));
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            return array();
        }
    }

    public function deleteRecord($id) {
        /* STEP -1 Delete the file record from the database */
        $user = $this->session->userdata('user');
        $this->db->where(array('AssociatedWith' => $id, 'Type' => 'DOC', 'AccountantAccess' => $user['AccountantAccess']));
        $this->db->delete('files');
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        $this->db->where('ID', $id);
        $this->db->delete('folders');
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        /* NO error return true */
        return TRUE;
    }

    public function getFileStructure($clientId = NULL, $limit = TERM_CONDITIONS_PAGINATION_LIMIT, $start = 0, $status, $companyName) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $this->db->select('a.*,b.ID,b.FirstName,b.LastName,b.T_AND_C_Version,c.Name');
        $this->db->from($prefix . 'term_conditions a');
        $this->db->join($prefix . 'users b', 'b.ID = a.AccountantAccess', 'left');
        $this->db->join($prefix . 'company c', 'c.ClientID = a.ClientId', 'left');
        $this->db->where('a.AccountantAccess', $user['UserID']);
        if (!empty($clientId)) {
            $this->db->where('a.ClientId', $clientId);
        } else if (isset($status) && empty($clientId) && empty($companyName)) {
            $this->db->where('a.Status', $status);
        } else if (empty($status) && empty($clientId) && !empty($companyName)) {
            $this->db->like('c.Name', $companyName);
        } else {
            $this->db->or_where('b.SubParent', $user['SubParent']);
            $this->db->order_by('a.Id', 'desc');
            $this->db->limit($limit, $start);
        }

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getFiletotal() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $this->db->select('a.*,b.ID,b.FirstName,b.LastName,b.T_AND_C_Version');
        $this->db->from($prefix . 'term_conditions a');
        $this->db->join($prefix . 'users b', 'b.ID = a.AccountantAccess', 'left');
        $this->db->where('a.AccountantAccess', $user['UserID']);
        $this->db->order_by('a.Id', 'desc');
        $query = $this->db->get();
        $result = $query->result();
        return count($result);
    }

    public function deleteFiles($id) {
        $this->db->delete('files', array('ID' => $id));
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* Get term and conditons max version */

    public function getTermversion($userId = NULL) {
        if (!empty($userId)) {
            $prefix = $this->db->dbprefix;
            $this->db->select('*');
            $this->db->where('AccountantAccess', $userId);
            $this->db->order_by('Id', 'DESC');
            $this->db->limit(1);
            $query = $this->db->get($prefix . 'term_conditions');
            $result = $query->result_array();
            if (count($result) == 0) {
                return 1;
            } else {
                return $result[0]['Version'] + 1;
            }
        }
    }

    public function checkTermsconditions($clientId = NULL) {
        if (!empty($clientId)) {
            $prefix = $this->db->dbprefix;
            $this->db->select('*');
            $this->db->where('ClientId', $clientId);
            $query = $this->db->get($prefix . 'term_conditions');
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return false;
            }
        }
    }

    public function updateFile($clientId = NULL, $records = NULL) {
        if (!empty($clientId) && !empty($records)) {
            $prefix = $this->db->dbprefix;
            $this->db->where('ClientId', $clientId);
            $this->db->update($prefix . 'term_conditions', $records);
            return TRUE;
        } else {
            return false;
        }
    }

    public function getClient() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $this->db->select('ID,FirstName,LastName');
        $this->db->where('AddedBy', $user['UserID']);
        $this->db->or_where('SubParent', $user['SubParent']);
        $this->db->where_not_in('ID', $user['UserID']);
        $this->db->order_by('FirstName', 'ASC');
        $query = $this->db->get($prefix . 'users');
        $result = $query->result();
        return $result;
    }

    public function getCompany() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $this->db->select('CID,ClientID,Name');
        //$this->db->where('AddedBy', $user['UserID']);
        //$this->db->or_where('SubParent', $user['SubParent']);
        //$this->db->where_not_in('ID', $user['UserID']);
        $this->db->order_by('Name', 'ASC');
        $query = $this->db->get($prefix . 'company');
        $result = $query->result();
        return $result;
    }

    public function getClientinfo($id = NULL) {
        if (!empty($id)) {
            $prefix = $this->db->dbprefix;
            $this->db->select('b.ID,b.FirstName,b.LastName,b.Email,c.CID,c.ClientID,c.Name,c.TradingName,c.RegistrationNo,c.IncorporationDate,c.ReturnDate,c.EndDate,c.Params,c.AddedOn');
            //$this->db->from($prefix . 'term_conditions a');
			$this->db->from($prefix . 'users b');
            //$this->db->join($prefix . 'users b', 'b.ID = a.ClientId', 'left');
            $this->db->join($prefix . 'company c', 'c.ClientID = b.ID', 'left');
            $this->db->where('b.ID', $id);
            $query = $this->db->get();
            $result = $query->result();
			//echo $this->db->last_query(); //die;
			//print_r($result); die;
            //if (!empty($result)) {
                return $result;
            /*} else {
                return FALSE;
            }*/
        }
    }

    public function getClientname($cid = NULL) {
        if (!empty($cid)) {
            $prefix = $this->db->dbprefix;
            $this->db->select('ClientID');
            $this->db->where('CID', $cid);
            $query = $this->db->get($prefix . "company");
            $result = $query->result();
            if (!empty($result[0]->ClientID)) {
                $this->db->select('ID,FirstName,LastName');
                $this->db->where('ID', $result[0]->ClientID);
                $query1 = $this->db->get($prefix . "users");
                $result1 = $query1->result();
                if(!empty($result1)){
                    return $result1;
                }else{
                    return array();
                }
            }
        }
    }

    public function activateTermAndCondtion($userId = NULL, $term_version = NULL) {
        if (!empty($userId) && !empty($term_version)) {
            $prefix = $this->db->dbprefix;
            $this->db->where('ID', $userId);
            $this->db->update($prefix . 'users', array('T_AND_C_Version' => $term_version));
            $this->db->where('ClientId', $userId);
            $this->db->update($prefix . 'term_conditions', array('Status' => 1, 'ModifiedOn' => date('Y-m-d')));
            return true;
        }
    }

    public function viewTermAndConditions($id = NULL) {
        $this->db->where('Id', $id);
        $query = $this->db->get('cashman_term_conditions');
        $resutl = $query->result_array();
        return $resutl[0]['FName'];
    }

}
