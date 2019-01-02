<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Emaillog extends CI_Model {

    public function Emaillog() {
        parent::__construct();
    }

    public function getemailLogs($limit = BULK_EMAI_LOGS_PAGINATION_LIMIT, $start = 0, $start_date = NULL, $end_date = NULL) {
        $user = $this->session->userdata('user');
        $this->db->select('a.*,b.ID,b.FirstName,b.LastName,b.Email,c.Name');
        $this->db->from($prefix . 'email_tracking a');
        $this->db->join($prefix . 'users b', 'b.ID = a.ClientId', 'left');
        $this->db->join($prefix . 'company c', 'c.ClientID = a.ClientId', 'left');
        $this->db->where('a.ClientId', $user['UserID']);
        if (!empty($start_date) && !empty($end_date)) {
           // $this->db->where('a.AddedOn BETWEEN "' . date('Y-m-d', strtotime($start_date)) . '" and "' . date('Y-m-d', strtotime($end_date)) . '"');
            $this->db->where('a.AddedOn >=', date('Y-m-d', strtotime($start_date)));
            $this->db->where('a.AddedOn <=', date('Y-m-d', strtotime($end_date. ' +1 day')));
        }
        $this->db->order_by('a.AddedOn DESC');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getBulkemailtotal() {
        $this->db->select('a.*,b.ID,b.FirstName,b.LastName,c.Name');
        $this->db->from($prefix . 'email_tracking a');
        $this->db->join($prefix . 'users b', 'b.ID = a.ClientId', 'left');
        $this->db->join($prefix . 'company c', 'c.ClientID = a.ClientId', 'left');
        $this->db->where('a.AddedBy', $user['UserID']);
        $query = $this->db->get();
        $result = $query->result();
        return count($result);
    }

}

?>