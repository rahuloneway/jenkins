<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Journal extends CI_Model {

    public function Journal() {
        parent::__construct();
    }
    
 public function newgetItems($limit = JOURNAL_LISTING_PAGINATION_LIMIT, $start = 0) {
        $prefix = $this->db->dbprefix;
        $TBYears = getTBYear();
        $user = $this->session->userdata('user');
	$fin_yearexcel = $this->session->userdata('fin_yearexcel');
	//echo $fin_yearexcel; 
        $TBYear = $TBYears[0]["value"];
        $order = $this->session->userdata('journalSortingOrder');
        if (isset($order) && !empty($order)) {
            $orderby = " ORDER BY " . $order . ' LIMIT ' . $start . ',' . $limit;
        } else {
            $orderby = " ORDER BY j.ID DESC LIMIT " . $start . "," . $limit;
        }
        $search = $this->session->userdata('journalSearch');
       // echo "<pre>"; print_r($search); echo "</pre>";
        $where = $this->newsearch($start, $limit);
        $query = "SELECT j.ID,j.JournalType,j.FinancialYear,j.Category,j.Narration,";
        $query .= "j.Status,j.Amount,j.GroupID";
        $query .= " FROM " . $prefix . "journal_entries AS j";
        if (!empty($fin_yearexcel)) {            
           $where .= ' AND FinancialYear="' . $fin_yearexcel . '" ';
        } 
        if(empty($search['FinancialYear']) && empty($fin_yearexcel)){
           $where .= ' AND FinancialYear="' . $TBYear . '" ';
        }           
        $query .= $where;
      // echo $query;
        if (!empty($search['FinancialYear'])) {
            $search_query = $this->db->query($query);
            $this->session->set_userdata('journalSearchRecords', $search_query->num_rows());
        }
        $query .=' ' . $orderby;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    
  public function newsearch($start, $limit) {
        $search = $this->session->userdata('journalSearch');
        $user = $this->session->userdata('user');
        $userID = $user['UserID'];

        /*
         * 	First check if search operation is performed or not.
         * 	Prepare where clause for the query according to the search criteria.
         */

        $where = '';
        if ($search != NULL) {
            if (!is_array($search)) {
                $where = '';
            } else {
                //echo "<pre>";print_r($search);echo '</pre>';die;
                $search = array_filter($search);

                if (count($search) <= 0) {
                    $where = '';
                } else {
                    //$where = 'WHERE ';
                    $fin_yearexcel = $this->session->userdata('fin_yearexcel');
                    if(empty($fin_yearexcel)){
                        foreach ($search as $key => $val) {
                            if($key == 'FinancialYear')
                            $where[] .= 'j.' . $key . '="'.$val.'"';
                        }
                    }
                }
            }
        } else {
            $where = '';
        }
        if ($where == '') {
            $where = ' WHERE ' . "j.AddedBy=" . $userID;
        } else {
            $where = implode(' AND ', $where);
            $where = ' WHERE ' . $where . ' AND j.AddedBy=' . $userID;
        }
        return $where;
    }
    
    public function getItemsAmount($year){
		$prefix = $this->db->dbprefix;
		$user = $this->session->userdata('user');
        $userID = $user['UserID'];
		//$query = "SELECT j.ID,j.JournalType,j.FinancialYear,j.Category,j.Narration,";
        //$query .= "j.Status,j.Amount,j.GroupID";
		$query = "Select SUM(CASE WHEN j.JournalType = 'DB' THEN j.Amount ELSE 0 END) AS debit_amount, SUM(CASE WHEN j.JournalType = 'CR' THEN j.Amount ELSE 0 END) AS credit_amount";
        $query .= " FROM " . $prefix . "journal_entries AS j WHERE FinancialYear='".$year."' AND j.AddedBy=".$userID;
		$query = $this->db->query($query);
		//echo $this->db->last_query();
		if ($query->num_rows() > 0) {
            return $query->result();
        }
	}
    public function getItems($limit = JOURNAL_LISTING_PAGINATION_LIMIT, $start = 0, $filter = null) {
        $prefix = $this->db->dbprefix;
        $TBYears = getTBYear();
		$fin_yearexcel = $this->session->userdata('fin_yearexcel');
	//echo $fin_yearexcel; 
        $TBYear = $TBYears[0]["value"];

        $user = $this->session->userdata('user');
        $order = $this->session->userdata('journalSortingOrder');
        $jSearchYear = $this->session->userdata('jSearchYear');
        
        if (isset($order) && !empty($order)) {
            $orderby = " ORDER BY " . $order . ' LIMIT ' . $start . ',' . $limit;
        } else {
            $orderby = " ORDER BY j.ID DESC LIMIT " . $start . "," . $limit;
        }
        $search = $this->session->userdata('journalSearch');
        $where = $this->search($start, $limit, $filter);
     
        $query = "SELECT j.ID,j.JournalType,j.FinancialYear,j.Category,j.Narration,";
        $query .= "j.Status,j.Amount,j.GroupID";
        $query .= " FROM " . $prefix . "journal_entries AS j";
        if ($fin_yearexcel) {
            $query .= ' WHERE FinancialYear="' . $fin_yearexcel . '" ' . $where;
        } 
       //else if($jSearchYear){           
         //  $query .= ' WHERE FinancialYear="' . $jSearchYear . '" ' . $where;
       // }
        else {
            $query .= ' WHERE FinancialYear="' . $TBYear . '" ' . $where;
        }
        //
	//	  echo  $this->db->last_query();
        if (!empty($search)) {
            $search_query = $this->db->query($query);

            $this->session->set_userdata('journalSearchRecords', $search_query->num_rows());
        }

        $query .=' ' . $orderby;

        echo $query;
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            $result = $query->result();
           //echo '<pre>';print_r($query->result());echo '</pre>';die;
            return $result;
        } else {
            return array();
        }
    }
    
    public function searchgetItems($limit = JOURNAL_LISTING_PAGINATION_LIMIT, $start = 0, $filter = null,$year) {       
        $prefix = $this->db->dbprefix;
        $TBYears = getTBYear();
	$fin_yearexcel = $this->session->userdata('fin_yearexcel');
	//echo $fin_yearexcel; 
        $TBYear = $TBYears[0]["value"];
        $user = $this->session->userdata('user');
        $order = $this->session->userdata('journalSortingOrder');
        $jSearchYear = $this->session->userdata('jSearchYear');        
        if (isset($order) && !empty($order)) {
            $orderby = " ORDER BY " . $order . ' LIMIT ' . $start . ',' . $limit;
        } else {
            $orderby = " ORDER BY j.ID DESC LIMIT " . $start . "," . $limit;
        }
        $search = $this->session->userdata('journalSearch');
        $where = $this->search($start, $limit, $filter);
     
        $query = "SELECT j.ID,j.JournalType,j.FinancialYear,j.Category,j.Narration,";
        $query .= "j.Status,j.Amount,j.GroupID";
        $query .= " FROM " . $prefix . "journal_entries AS j";
       // if ($fin_yearexcel) {
       //     $query .= ' WHERE FinancialYear="' . $fin_yearexcel . '" ' . $where;
      //  } 
      // else if($year){           
           $query .= ' WHERE FinancialYear="' . $year . '" ' . $where;
      //  }
      //  else {
      //      $query .= ' WHERE FinancialYear="' . $TBYear . '" ' . $where;
      //  }
        //
	//	  echo  $this->db->last_query();
        if (!empty($search)) {
            $search_query = $this->db->query($query);

            $this->session->set_userdata('journalSearchRecords', $search_query->num_rows());
        }

        $query .=' ' . $orderby;

        echo $query;
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            $result = $query->result();
           //echo '<pre>';print_r($query->result());echo '</pre>';die;
            return $result;
        } else {
            return array();
        }
    }

    public function search($start, $limit, $filter) {
        $search = $this->session->userdata('journalSearch');

        $user = $this->session->userdata('user');
        $userID = $user['UserID'];

        /*
         * 	First check if search operation is performed or not.
         * 	Prepare where clause for the query according to the search criteria.
         */

        $where = '';
        if ($search != NULL) {
            if (!is_array($search)) {
                $where = '';
            } else {

                $search = array_filter($search);
                	//echo "<pre>";print_r($search);echo '</pre>';die;
                if (count($search) <= 0) {
                    $where = '';
                } else {
                    //$where = 'WHERE ';
                    foreach ($search as $key => $val) {
                       // if ($key == "FinancialYear") {
                          //  $where[] = "j.FinancialYear=" . $val;
                      //  }  
                    }
                }
            }
        } else {
            $where = '';
        }
        if ($where == '') {
            $where = ' AND ' . "j.AddedBy=" . $userID;
        } else {
            $where = implode(' AND ', $where);
            $where = $where . ' AND ' . $filter . 'j.AddedBy=' . $userID;
        }
        return $where;
    }

    public function totalEntries() {
        $prefix = $this->db->dbprefix;
        $search = $this->session->userdata('journalSearch');
        $year = $search['FinancialYear'];
        $user = $this->session->userdata('user');
        $user = $user['UserID'];
        $totalRecord = $this->session->userdata('journalSearchRecords');
        if (isset($totalRecord) && !empty($totalRecord)) {
            return $totalRecord;
        }
        $this->db->where('AddedBy', $user);
        $this->db->where('FinancialYear', $year);
        $records = $this->db->count_all_results('journal_entries');
        if ($records > 0) {
            return $records;
        } else {
            return 0;
        }
    }
    
   

    public function save($data) {
        $prefix = $this->db->dbprefix;
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $key => $val) {
                $this->db->insert('journal_entries', $val);
                if ($this->db->affected_rows() > 0) {
                    $val['id'] = $this->db->insert_id();
                } else {
                    $val['id'] = 0;
                }
                $data[$key] = $val;
            }

            $this->db->where('ID', $val['id']);
            $q = $this->db->get($prefix.'journal_entries');
            $fetch = $q->result_array();
            update_logs('JOURNAL', 'USER_CREATED_JOURNAL', 'CREATE', "",$fetch[0]['GroupID'] );
            return $data;
        } else {
            return FALSE;
        }
    }

    /*
      public function save($data)
      {
      $this->db->insert_batch('cashman_journal_entries',$data);
      if($this->db->affected_rows() > 0)
      {
      return TRUE;
      }else{
      return FALSE;
      }
      }
     */

    function maxGroupID() {
        $prefix = $this->db->dbprefix;
        $this->db->select_max('GroupID');
        $query = $this->db->get('journal_entries');
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result[0]->GroupID + 1;
        } else {
            return 0;
        }
    }

    public function getItem($year = nulls) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = "SELECT j.ID,j.JournalType,j.FinancialYear,j.Category,j.Narration,";
        $query .= "j.Status,j.Amount,j.GroupID";
        $query .= " FROM " . $prefix . "journal_entries AS j";
        $query .= " WHERE j.FinancialYear = " . $this->db->escape($year) . ' AND AddedBy=' . $user['UserID'];
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            return array();
        }
    }
	
	public function getJournaluploadSheet_Category(){
		 $prefix = $this->db->dbprefix;
        // $query = $this->db->get_where('trial_balance_categories', array('parent!=' => '0'));
        // $db_error = $this->db->error();
        // if ($db_error['code'] != 0) {
            // log_message('error', $db_error['message']);
            // return FALSE;
        // }
        // if ($query->num_rows() <= 0) {
            // return array();
        // }

        // $records = $query->result();
        // $data = array();
        // foreach ($records as $key => $val) {
            // $data[$val->id] = trim($val->title);
        // }
        // return $data;
		
	    // $ci = & get_instance();
       // $prefix = $ci->db->dbprefix;

        $query = "SELECT * FROM " . $prefix . "trial_balance_categories";
        $query = $this->db->query($query);
        $categories = $query->result();
        $parent = array('0' => 0);
        $temp = array();
        foreach ($categories as $key => $val) {
            if ($val->parent == 0) {
                foreach ($categories as $k => $v) {
                    if ($v->parent != 0 && $val->id == $v->parent) {
                        $temp[$v->id] = $v->title;
                    }
                }
               // $parent[$val->title] = $temp;
              //  unset($temp);
            }
        }
        return $temp;
   
	}
	
	public function getJournaluploadSheet_Category_ExcelSheet(){
		$prefix = $this->db->dbprefix;
        $query = "SELECT * FROM " . $prefix . "trial_balance_categories";
        $query = $this->db->query($query);
        $categories = $query->result();
        $parent = array();
        $temp = array();
        foreach ($categories as $key => $val) {
            if ($val->parent == 0) {
                foreach ($categories as $k => $v) {
                    if ($v->parent != 0 && $val->id == $v->parent) {
                        $temp[$val->id] = $val->title;
                        $temp[$v->id] = $v->title;
                   }
                }
                $parent[$val->title] = $temp;
               // unset($temp);
          }
        }
        return $temp;   
	}

	function getMaxFiles() {
        $prefix = $this->db->dbprefix;
        $query = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . $this->db->database . "'";
        $query .= " AND   TABLE_NAME   = '" . $prefix . "files'";
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        //echo '<pre>';print_r($query->result());echo '</pre>';die;
        if ($query->num_rows() > 0) {
            $result = $query->result();
            if ($result[0]->AUTO_INCREMENT == 0) {
                return $result[0]->AUTO_INCREMENT + 1;
            } else {
                return $result[0]->AUTO_INCREMENT;
            }
        } else {
            return 0;
        }
    }
    
    public function searchtotalEntries($year){  
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $userID = $user['UserID'];
        $search_query = "SELECT j.ID,j.JournalType,j.FinancialYear,j.Category,j.Narration,";
        $search_query .= "j.Status,j.Amount,j.GroupID";
        $search_query .= " FROM " . $prefix . "journal_entries AS j WHERE FinancialYear='$year' AND j.AddedBy=".$userID." ORDER BY j.ID DESC";  
        $query = $this->db->query($search_query);
        return $query->num_rows(); 
    }
}
