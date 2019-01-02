<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bank extends CI_Model {

    public function Bank() {
        parent::__construct();
    }

    public function getItems($limit = BANK_PAGINATION_LIMIT, $start = 0, $filter = null, $bankId = NULL) {

        $prefix = $this->db->dbprefix;

        $user = $this->session->userdata('user');
        $order = $this->session->userdata('BankSortingOrder');
        if (isset($order) && !empty($order)) {
            $orderby = " ORDER BY " . $order . ' LIMIT ' . $start . ',' . $limit;
        } else {
            $orderby = " ORDER BY s.TransactionDate,s.ID ASC LIMIT " . $start . "," . $limit;
        }
        $search = $this->session->userdata('BankSearch');
        if ($bankId != "") {
            $where = $this->search($start, $limit, $filter);
            $where .= " AND s.bankId = " . $bankId;
        } else {
            $where = $this->search($start, $limit, $filter);
        }

        $query = "SELECT s.ID,s.bankId,s.TransactionDate,s.Description,s.Type,s.Category,s.Balance,s.AssociatedWith,b.Name as bankName, ";
        $query .= "s.StatementType,s.MoneyOut,s.MoneyIn,s.CheckBalance";
        $query .= " FROM " . $prefix . "bank_statements AS s";
        $query .= " LEFT JOIN " . $prefix . "banks AS b on b.BID = s.bankId";

        $query .= $where;

        if (!empty($search)) {
            $search_query = $this->db->query($query);

            $this->session->set_userdata('bankSearchRecords', $search_query->num_rows());
        }

        $query .=' ' . $orderby;

        $qr = $this->db->query($query);
        //echo $this->db->last_query();
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($qr->num_rows() > 0) {
            $result = $qr->result();
            //echo '<pre>';print_r($query->result());echo '</pre>';die;
            return $result;
        } else {
            return array();
        }
    }
	
	public function getItem($id=NULL, $page=NULL, $total=NULL){
		if($id != NULL){        
			$prefix = $this->db->dbprefix;
			$user = $this->session->userdata('user');      
			
			$query = "SELECT s.ID,s.bankId,s.TransactionDate,s.Description,s.Type,s.Category,s.Balance,s.AssociatedWith,b.Name as bankName, ";
			$query .= "s.StatementType,s.MoneyOut,s.MoneyIn,s.CheckBalance";
			$query .= " FROM " . $prefix . "bank_statements AS s";
			$query .= " LEFT JOIN " . $prefix . "banks AS b on b.BID = s.bankId";

			$query .= " WHERE s.ID = ".$id;
		   
			$qr = $this->db->query($query);
			//echo $this->db->last_query();
			$db_error = $this->db->error();
			if ($db_error['code'] != 0) {
				log_message('error', $db_error['message']);
				return FALSE;
			}

			if ($qr->num_rows() > 0) {
				$result = $qr->result();            
				return $result;
			} else {
				return array();
			}
		}else{
			$prefix = $this->db->dbprefix;
			$user = $this->session->userdata('user');      
			
			$query = "SELECT s.ID,s.bankId,s.TransactionDate,s.Description,s.Type,s.Category,s.Balance,s.AssociatedWith,b.Name as bankName, ";
			$query .= "s.StatementType,s.MoneyOut,s.MoneyIn,s.CheckBalance";
			$query .= " FROM " . $prefix . "bank_statements AS s";
			$query .= " LEFT JOIN " . $prefix . "banks AS b on b.BID = s.bankId";

			$query .= " WHERE s.AssociatedWith = 0 ";
			if($page != NULL && $page != NULL){
				$query .= " LIMIT ".$page.",1";
			}
		   
			$qr = $this->db->query($query);
			//echo $this->db->last_query();
			$db_error = $this->db->error();
			if ($db_error['code'] != 0) {
				log_message('error', $db_error['message']);
				return FALSE;
			}
			if ($qr->num_rows() > 0) {
				$result = $qr->result();            
				return $result;
			} else {
				return array();
			}
		}
    }

    public function search($start, $limit, $filter){
        $search = $this->session->userdata('BankSearch');

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
                //	echo "<pre>";print_r($search);echo '</pre>';die;
                if (count($search) <= 0) {
                    $where = '';
                } else {
                    //$where = 'WHERE ';
                    foreach ($search as $key => $val) {
                        if ($key == "Category") {
                            $where[] = " s.Category=" . $val;
                        } elseif ($key == "Description") {
                            $where[] = " s.Description='" . $val . "'";
                        } elseif ($key == "StartDate") {
                            $where[] = " s.TransactionDate >= '" . mDate($val) . "'";
                        } elseif ($key == "EndDate") {
                            $where[] = " s.TransactionDate <='" . mDate($val) . "'";
                        } elseif ($key == "FinancialYear") {
                            $date = $user['CompanyEndDate'];
                            $date = explode('-', $date);
                            $given_date = explode('/', $val);
                            $start_date = trim($given_date[0]) . '-' . $date[1] . '-' . $date[2];
                            $end_date = trim($given_date[1]) . '-' . $date[1] . '-' . $date[2];
                            $where[] = " s.TransactionDate >='" . $start_date . "' AND s.TransactionDate <= '" . $end_date . "'";
                        }
                    }
                }
            }
        } else {
            $where = '';
        }
        if ($where == '') {
            $where = ' WHERE ' . "s.AddedBy=" . $userID;
        } else {
            $where = implode(' AND ', $where);
            $where = ' WHERE ' . $where . ' AND ' . $filter . 's.AddedBy=' . $userID;
        }
        return $where;
    }

    public function totalEntries() {
        $search = $this->session->userdata('bankSearch');
        $user = $this->session->userdata('user');
        $user = $user['UserID'];
        $totalRecord = $this->session->userdata('bankSearchRecords');
        if (isset($totalRecord) && !empty($totalRecord)) {
            return $totalRecord;
        }
        $this->db->where('AddedBy', $user);
        $records = $this->db->count_all_results('bank_statements');
        if ($records > 0) {
            return $records;
        } else {
            return 0;
        }
    }

    public function getRequestCategories() {
        $prefix = $this->db->dbprefix;
        $this->db->select('Title,ID,CategoryType');
        $query = $this->db->get_where('expenses_category', array('CategoryType' => 'REQ'));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $val) {
                $data[$val->ID] = $val->Title;
            }
            return $data;
        } else {
            return array('0' => 'No reason title added');
        }
    }

    public function save($data) {
        $prefix = $this->db->dbprefix;
        $this->db->insert('messages', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getUserInfo($id) {
        $this->db->select('Email,AddedBy,CONCAT(FirstName," ' . '",LastName) AS Name', false);
        $query = $this->db->get_where('users', array('ID' => $id));
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return FALSE;
        }
    }

    public function getMatchDetails($date = NULL, $op = '+', $amount = 0) {
        $prefix = $this->db->dbprefix;
        if (empty($date)) {
            return array();
        }
        $user = $this->session->userdata('user');
        $CompanyID = $user['CompanyID'];
        switch ($op) {
            case '+':
                $this->db->select('InvoiceID,InvoiceNumber,InvoiceTotal');
                $query = $this->db->get_where('invoices', array('PaidOn' => $date, 'InvoiceTotal' => $amount, 'CustomerCompanyID' => $CompanyID));
                //echo $this->db->last_query();
                if ($query->num_rows() > 0) {
                    $invoices = $query->result();
                } else {

                    $this->db->select('InvoiceID,InvoiceNumber,SubTotal');
                    $query = $this->db->get_where('invoices', array('PaidOn' => $date, 'CustomerCompanyID' => $CompanyID));
                    if ($query->num_rows() > 0) {
                        $invoices = $query->result();
                    } else {
                        $invoices = array();
                    }
                }

                if (count($invoices) > 0) {
                    $numbers = array();
                    foreach ($invoices as $key => $val) {
                        $numbers[] = $val->InvoiceNumber;
                    }
                    $numbers = implode(',', $numbers);
                    return $numbers;
                } else {
                    return '';
                }
                break;
            case '-':
                $record = array();
                $this->db->select('ExpenseID,Purpose,Amount');
                $query = $this->db->get_where('expenses', array('PaidOn' => $date, 'Amount' => $amount, 'AddedBy' => $user['UserID']));
                if ($query->num_rows() > 0) {
                    $expenses = $query->result();
                } else {

                    /* If no expense/dividend found then check the expense template have been founded */
                    $this->db->select('ExpenseID,Purpose,Amount');
                    $query = $this->db->get_where('expenses', array('PaidOn' => $date, 'AddedBy' => $user['UserID']));
                    if ($query->num_rows() > 0) {
                        $expenses = $query->result();
                    } else {
                        $expenses = array();
                    }
                }
                $numbers = array();
                if (count($expenses) > 0) {
                    foreach ($expenses as $key => $val) {
                        $numbers[] = $val->Purpose;
                    }
                }

                $this->db->select('DID,VoucherNumber');
                $query = $this->db->get_where('dividends', array('PaidOn' => $date, 'NetAmount' => $amount, 'CompanyID' => $CompanyID));
                if ($query->num_rows() > 0) {
                    $dividend = $query->result();
                } else {
                    $this->db->select('DID,VoucherNumber');
                    $query = $this->db->get_where('dividends', array('PaidOn' => $date, 'CompanyID' => $CompanyID));
                    if ($query->num_rows() > 0) {
                        $dividend = $query->result();
                    } else {
                        $dividend = array();
                    }
                }

                if (count($dividend) > 0) {
                    foreach ($dividend as $key => $val) {
                        $numbers[] = $val->VoucherNumber;
                    }
                }

                if (count($expenses) > 0) {
                    foreach ($expenses as $key => $val) {
                        $numbers[] = $val->Purpose;
                    }
                }
                $numbers = implode(',', $numbers);
                return $numbers;
                break;
            default:
                break;
        }
    }

    function saveFile($data) {
        $this->db->insert('files', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    /*
      function save_statements($data)
      {
      $this->db->insert_batch('cashman_bank_statements',$data);
      if($this->db->_error_number() != 0)
      {
      log_message('error',$this->db->_error_message());
      return FALSE;
      }
      return TRUE;
      }
     */

    function save_statements($data, $bankId) {
        $prefix = $this->db->dbprefix;
        if (count($data) > 0) {
            $error = false;
            foreach ($data as $key => $val) {
                if ($val['FileID'] == '') {
                    $val['FileID'] = '0';
                }
                $val['bankId'] = $bankId;
				$user = $this->session->userdata('user');
				$val['compnayID'] = $user['CompanyID'];				
                $this->db->insert('bank_statements', $val);
                $db_error = $this->db->error();
                if ($db_error['code'] != 0) {
                    log_message('error', $db_error['message']);
                    if (!$error)
                        $error = true;
                }else {
                    $val["id"] = $this->db->insert_id();
                    $data[$key] = $val;
                }
            }
            //  echo $this->db->last_query(); die();
            return $data;
        } else {
            return FALSE;
        }
    }

    function update_statements($data, $id) {
        $prefix = $this->db->dbprefix;
        $this->db->where('ID', $id);
        $this->db->update('bank_statements', $data);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
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

    function getStatements($id) {
        $prefix = $this->db->dbprefix;
        $query = $this->db->get_where('bank_statements', array('ID' => $id));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $val) {
                $result[$key] = get_object_vars($val);
            }
            return $result[0];
        } else {
            return array();
        }
    }

    public function get_file_statements($id) {
        $prefix = $this->db->dbprefix;
        $query = $this->db->get_where('bank_statements', array('FileID' => $id));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $val) {
                $result[$key] = get_object_vars($val);
            }
            return $result;
        } else {
            return array();
        }
    }

    public function getSystemEntries() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = "SELECT i.InvoiceID,i.InvoiceNumber,i.InvoiceTotal,i.PaidOn as InvoiceDate,";
        //$query .= " e.ExpenseID,e.Amount,e.Purpose,e.PaidOn as ExpenseDate,e.Reconciled,";
        $query .= " d.DID,d.VoucherNumber,d.NetAmount,d.PaidOn AS DividendDate";
        $query .= " FROM " . $prefix . "system_entries AS se";
        $query .= " LEFT JOIN " . $prefix . "invoices AS i on i.InvoiceID = se.InvoiceID";
        //$query .= " LEFT JOIN cashman_expenses AS e on e.ExpenseID = se.ExpenseID";
        $query .= " LEFT JOIN " . $prefix . "dividends AS d on d.DID = se.DID";
        $query .= " WHERE se.AddedBy=" . $user['UserID'] . ' AND ( i.Reconciled = 0 OR d.Reconciled = 0) ORDER BY se.ID DESC';

        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            return array();
        }
    }

    public function getStatementCategories($action = NULL) {
        $prefix = $this->db->dbprefix;
        $this->db->select('ID,Title');
        $query = $this->db->get_where('expenses_category', array('CategoryType' => 'BANK', 'Status' => 1));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $val) {
                if ($action == "statements") {
                    $result[$val->ID] = $val->Title;
                } else {
                    $result[] = $val->Title;
                }
            }
            //echo '<pre>';print_r($result);die;
            return $result;
        } else {
            return array();
        }
    }
	
	public function getTBCategories($action = NULL) {
        $prefix = $this->db->dbprefix;
        $this->db->select('id,title');
        $query = $this->db->get_where('trial_balance_categories', array('AnalysisLedgerParent !=' => 0, 'Status' => 1));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $val) {
                if ($action == "statements") {
                    $result[$val->id] = $val->title;
                } else {
                    $result[] = $val->title;
                }
            }
            //echo '<pre>';print_r($result);die;
            return $result;
        } else {
            return array();
        }
    }

    public function deleteFile($id) {
        $prefix = $this->db->dbprefix;
        if (!empty($id)) {
            /* Get the file name from the table */
            $this->db->select('FName');
            $query = $this->db->get_where('files', array('ID' => $id));
            $query = $query->result();
            $name = $query[0]->FName;

            //die;
            $this->db->delete('files', array('ID' => $id));
            if ($this->db->affected_rows() > 0) {
                /* Delete the file from the folder also */
                $this->load->helper('file');
                $path = FCPATH . 'assets/uploads/' . $name;
                if (file_exists($path)) {
                    unlink($path);
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function getExpenseSheet() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $this->db->select('ID,TotalAmount');
        $query = $this->db->get_where('files', array('UploadedBy' => $user['UserID'], 'Type' => 'E', 'Reconciled !=' => '1'));
        if ($query->num_rows() > 0) {
            $sheet = array();
            foreach ($query->result() as $key => $val) {
                $sheet[$val->ID] = $val->TotalAmount;
            }
            return $sheet;
        } else {
            return array();
        }
    }

    public function getFileStatements($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('ID');
        $query = $this->db->get_where('bank_statements', array('FileID' => $id));
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $ids = array();
            foreach ($result as $key => $val) {
                $ids[] = $val->ID;
            }
            return $ids;
        }
    }

    public function delete_statement_record($id) {
        $prefix = $this->db->dbprefix;
        $did_id = array();
        $invoice_id = array();
        //echo '<pre>';print_r($id);die;
        foreach ($id as $key => $val) {
            if ($val['ItemType'] == 'D') {
                $did_id[] = $val['ItemID'];
            } elseif ($val['ItemType'] == 'I') {
                $invoice_id[] = $val['ItemID'];
            }
        }

        if (count($did_id) > 0) {
            $query = "DELETE FROM " . $prefix . "dividends WHERE DID IN (" . implode(',', $did_id) . ")";
            $this->db->query($query);
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
        }
        if (count($invoice_id) > 0) {
            $query = "DELETE FROM " . $prefix . "invoices WHERE InvoiceID IN (" . implode(',', $invoice_id) . ")";
            $this->db->query($query);
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
        }

        return TRUE;
    }

    public function updateStatements($data, $type) {
        $prefix = $this->db->dbprefix;
        if ($type == 'D') {
            $this->db->update_batch('dividends', $data, 'DID');
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
        } elseif ($type == 'I') {
            $this->db->update_batch('invoices', $data, 'InvoiceID');
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
        }
        return TRUE;
    }

    public function update_bank_association($data, $id) {
        $this->db->update('bank_statements', $data, array('ID' => $id));
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        }
    }

    public function check_duplicate_entry($entries) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        if (isset($entries['ID'])) {
            $where = ' AND ID !=' . $entries['ID'];
        } else {
            $where = '';
        }
        $query = 'SELECT ID FROM cashman_bank_statements WHERE TransactionDate="' . $entries['TransactionDate'] . '"';
        $query .= ' AND Description="' . $entries['Description'] . '" AND Type="' . $entries['Type'] . '"';
        $query .= ' AND Category=' . $entries['Category'] . ' AND (MoneyOut="' . $entries['MoneyOut'] . '" OR MoneyIn="' . $entries['MoneyIn'] . '")' . $where . ' AND Balance = ' . $entries['Balance'] . ' AND AddedBy = ' . $user['UserID'];
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function delete_statements($id) {
        $prefix = $this->db->dbprefix;
        $id = implode(',', $id);
        $query = "DELETE FROM " . $prefix . "bank_statements WHERE ID IN (" . $id . ")";
        $query = $this->db->query($query);		
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($this->db->affected_rows() > 0) {
            //return TRUE;
			$query = "DELETE FROM " . $prefix . "tb_details WHERE itemId IN (" . $id . ") AND source = 'BANK'";
			$query = $this->db->query($query);
			$db_error2 = $this->db->error();
			if ($db_error2['code'] != 0) {
				log_message('error', $db_error['message']);
				return FALSE;
			}else{
				return TRUE;
			}
        } else {
            return FALSE;
        }

        
    }

    public function get_previous_dividends() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = "SELECT d.VoucherNumber,d.DividendDate,d.GrossAmount,d.NetAmount,";
        $query .= "CONCAT(s.FirstName,' ',LastName) as ShareholderName  ";
        $query .= "FROM " . $prefix . "dividends AS d LEFT JOIN " . $prefix . "company_customers AS s ON s.ID = d.ShareholderID";
        $query .= " WHERE d.AddedBy=" . $user['UserID'] . ' AND d.Status <>2';
        //$query = $this->db->query($query);
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
        } else {
            $result = array();
        }
        return $result;
    }

    public function get_previous_invoices() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = 'SELECT i.InvoiceNumber,i.DueDate,';
        $query .= 'CONCAT(u.FirstName," ",u.LastName) AS Name,i.InvoiceTotal';
        $query .= ' FROM ' . $prefix . 'invoices AS i';
        $query .= ' LEFT JOIN ' . $prefix . 'users AS u ON u.ID = i.UserID';
        $query .= ' WHERE i.AddedBy=' . $user['UserID'] . ' AND i.Status <>3';
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
        } else {
            $result = array();
        }
        return $result;
    }

    public function get_statement_ids($ids = array()) {
        $prefix = $this->db->dbprefix;
        if (count($ids) <= 0) {
            return FALSE;
        }
        $this->db->select('ID');
        $query = "SELECT ID,StatementType,AssociatedWith FROM " . $prefix . "bank_statements WHERE AssociatedWith IN (" . implode(',', $ids) . ")";
        $query = $this->db->query($query);
        $response = $query->result();
        return $response;
    }

    public function getDeleteEntryDetails($ids) {
        $prefix = $this->db->dbprefix;
        $id = implode(',', $ids);
        $query = "SELECT `cbs`.`ID` as `id`,`cbs`.*,`cec`.* FROM `" . $prefix . "bank_statements` as `cbs` "
                . "\n LEFT JOIN `" . $prefix . "expenses_category` as `cec` ON  `cec`.`ID`=`cbs`.`Category` "
                . "\n WHERE `cbs`.`ID` IN (" . $id . ") AND `cec`.`CategoryType`='BANK' ";
        $query = $this->db->query($query);
        // echo $this->db->last_query();
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $response = $query->result_array();
            return $response;
        } else {
            return FALSE;
        }
    }

    public function get_current_balance() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = "SELECT TransactionDate,Balance FROM " . $prefix . "bank_statements WHERE AddedBy=" . $user['UserID'] . ' ORDER BY TransactionDate DESC,ID DESC LIMIT 0,1';
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return get_object_vars($result[0]);
        } else {
            return 0;
        }
    }

    public function get_starting_balance() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = "SELECT COUNT(ID) AS TotalRecord FROM " . $prefix . "bank_statements WHERE AddedBy=" . $user['UserID'];
        $query = $this->db->query($query);
        $result = $query->result();
        $result = $result[0]->TotalRecord;
        if ($result > 0) {
            return 0;
        } else {
            $year = (date('Y') - 1) . '/' . date('Y');
            /* Pick-up the starting balance from cash at bank category i.e. 128 in trial balance table */
            $query = "SELECT amount FROM " . $prefix . "trial_balance WHERE category_id = 128 AND year = '" . $year . "'";
            $query .= " AND clientId=" . $user['UserID'];
            $query = $this->db->query($query);
            $result = $query->result();
            $result = $result[0]->amount;
            if ($result > 0) {
                return $result;
            } else {
                return 0;
            }
        }
    }

    public function getCategoryDescript() {
        $query = $this->db->get($prefix . 'category_description');
        if ($query->num_rows() > 0) {
            $cat = '';
            $row = $query->result_array();
            foreach ($row as $key => $value) {
                $cat[$value['expenses_category_id']] = $value['Description'];
            }
            $dt[] = $cat;
            return $dt;
        }
    }

    public function getCompanyId($clientId = NULL) {
        if (!empty($clientId)) {
            $prefix = $this->db->dbprefix;
            $this->db->select('CID');
            $this->db->where('ClientID', $clientId);
            $query = $this->db->get($prefix . 'company');
            $result = $query->result();
            if (count($result) > 0) {
                return $result[0]->CID;
            }
        }
    }

    /*     * ********* getting bank statement detail by id on change category from bank listing page ******* */

    public function getBankStatementDetail($id) {
        $prefix = $this->db->dbprefix;
        $query = "SELECT `cbs`.`ID` as `id`,`cbs`.*,`cec`.* FROM `" . $prefix . "bank_statements` as `cbs` "
                . "\n LEFT JOIN `" . $prefix . "expenses_category` as `cec` ON  `cec`.`ID`=`cbs`.`Category` "
                . "\n WHERE `cbs`.`ID` = " . $id . " AND `cec`.`CategoryType`='BANK' ";
        $query = $this->db->query($query);
        // echo $this->db->last_query();
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $response = $query->row();
            return $response;
        } else {
            return FALSE;
        }
    }

    /*     * ********** updating bank statement category on change category from bank statements llisting page *** */

    function update_statementsCategory($id, $cat_id, $parentId=NULL) {		
        $prefix = $this->db->dbprefix;
		 if ($cat_id == 75) {			
			$data=array('Category'=> $cat_id,'StatementType'=>'I');
		} elseif ($cat_id == 78) {
			$data=array('Category'=> $cat_id,'StatementType'=>'D');
		}else{
			$data=array('Category'=> $cat_id,'StatementType'=>'');
		}
        $this->db->where('ID', $id);
        $this->db->update('bank_statements',$data);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($this->db->affected_rows() > 0) {
            $statemnetDetail = $this->getBankStatementDetail($id);			
            if(empty($statemnetDetail))
				return FALSE;
			else{
												
				$prefix = $this->db->dbprefix;
				//$this->db->set('type',$statemnetDetail->key,'category_id',$parentId);
				$data=array('type'=>$statemnetDetail->key,'category_id'=>$parentId);
				$this->db->where('itemId',$id);
				$this->db->where('source','BANK');
				$this->db->where('type NOT LIKE ','%CASH_AT_BANK%');
				$this->db->update('tb_details',$data);
				//echo $this->db->last_query();die;			 
				$db_error = $this->db->error();
				if($db_error['code'] != 0)
				{
					log_message('error',$db_error['message']);
					return FALSE;
				}
				if($this->db->affected_rows() > 0)
				{
					return TRUE;
				}
			} 
            return TRUE;
        } else {
            return FALSE;
        }
    }

	 /*     * ********** new updating bank statement category on change category from bank statements llisting page *** */

    function new_update_statementsCategory($id, $cat_id, $parentId=NULL) {		
        $prefix = $this->db->dbprefix;
		 if ($cat_id == 2) {			
			$data=array('Category'=> $cat_id,'StatementType'=>'I');
		} elseif ($cat_id == 69) {
			$data=array('Category'=> $cat_id,'StatementType'=>'D');
		}else{
			$data=array('Category'=> $cat_id,'StatementType'=>'');
		}
        $this->db->where('ID', $id);
        $this->db->update('bank_statements',$data);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($this->db->affected_rows() > 0) {
            $statemnetDetail = $this->getBankStatementDetail($id);				
			//if(empty($statemnetDetail)){				
				//return FALSE;
			//}else{	
				$tbType = getCategoryKey($cat_id);
				//$tbCat = getCategoryTbKeyId($tbType);				
				$prefix = $this->db->dbprefix;
				//$this->db->set('type',$statemnetDetail->key,'category_id',$parentId);
				$data=array('type'=>$tbType,'category_id'=>$cat_id);
				$this->db->where('itemId',$id);
				$this->db->where('source','BANK');
				$this->db->where('type NOT LIKE ','%CASH_AT_BANK%');
				$this->db->update('tb_details',$data);
				//echo $this->db->last_query(); //die;			 
				$db_error = $this->db->error();
				if($db_error['code'] != 0)
				{
					log_message('error',$db_error['message']);
					return FALSE;
				}
				if($this->db->affected_rows() > 0)
				{
					return TRUE;
				}
			//} 
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
	
    /*     * *********** getting bank statements description and statement type by user id to auto fill blank categories matching to description ***** */

    public function getBankStatementsDescriptions() {
        $user = $this->session->userdata('user');		        $addedBy = $user['UserID'];		        $compnayID = $user['CompanyID'];		        /*					if($addedBy == '')								return false; 						*/
        $prefix = $this->db->dbprefix;
        $this->db->select('Description,Category,StatementType');
        //$query = $this->db->get_where('bank_statements',array('AddedBy'=>$addedBy));
        $this->db->where('Description!=', '');		$this->db->where('AddedBy',$addedBy);				$this->db->where('compnayID',$compnayID);		
        $query = $this->db->get('bank_statements');		//echo $this->db->last_query(); //die;			
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            return $result;
        } else {
            return false;
        }
    }

    /*     * ******************* Get Banks for Add Bank Statement ******************* */

    public function getBanks($CompanyID = NULL) {
        $prefix = $this->db->dbprefix;
        $this->db->select('*');
        $this->db->from('cashman_banks');
        $this->db->where('CompanyID', $CompanyID);
        $this->db->order_by('BID DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function saveBank($data) {
        $prefix = $this->db->dbprefix;
        $this->db->insert('banks',$data);
        $db_error = $this->db->error();		
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            //return FALSE;
			return "";
        } else {
           //return true;
		   return $this->db->insert_id();
        }
    }
	##############################################
	# Author : Gurdeep Singh                     #
	# Date   : 09 Aug 2016                       #
	# Params : Company Id                        #
	# Description : Getting existing invoices by #
	# compamy id to auto fill customer in invoice# 
	# form by matching description               #
	##############################################
    public function getExistingInvoicesDesc() 
	{
        $user = $this->session->userdata('user');
		$data = array();
        if($user['CompanyID'] == '')
			return $data;
     
		$prefix = $this->db->dbprefix;
        $this->db->select('invoice_items.Description,invoices.UserID,invoices.CustomerCompanyID,invoices.InvoiceID');
        $this->db->where('invoice_items.Description!=', '');
        $this->db->where('invoices.UserID!=', '');
        $this->db->where('invoices.CustomerCompanyID', $user['CompanyID']);
		$this->db->join('invoice_items','invoice_items.InvoiceID = invoices.InvoiceID');
		$this->db->group_by('invoices.InvoiceID');
        $query = $this->db->get('invoices');
		//echo $this->db->last_query();
		if ( $this->db->affected_rows() > 0 ) 
		{
            $data = $query->result_array();
            return $data;
        } 
		else 
		{
            return $data;
        }
    }
		
	#Get bank TB Category
	public function getBankTBCategory($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('TB_Category');
        $this->db->from('cashman_banks');
        $this->db->where('BID', $id);      
        $query = $this->db->get();
		return $query->result();
    }
	
	#Get Ivoice/purchase/expense/dividend etc items to link bank statment
	public function getItemToLinkBankStatment($key,$item) {
		if($key == 'SALES'){
			$prefix = $this->db->dbprefix;
			$this->db->select('InvoiceNumber');
			$this->db->from('cashman_invoices');
			//$this->db->where('Status', 3); 
			$this->db->like('InvoiceNumber', $item);			
			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result();
		}
    }
	
	function update_statements_associate($id, $statmentId, $statmentType, $statmentAmount){	
		
		#Revert vat entry
		$isRevertSuccess = revert_vat_trial_details($id,$statmentId,$statmentType,$statmentAmount);
		$prefix = $this->db->dbprefix;		
		if($statmentType == 'Customer'){
			$data=array('AssociatedWith'=>$id,'StatementType'=> 'I');
		}else if($statmentType == 'Supplier'){
			$data=array('AssociatedWith'=>$id,'StatementType'=> 'P');
		}else if($statmentType == 'Employee'){
			$data=array('AssociatedWith'=>$id,'StatementType'=> 'E');
		}else if($statmentType == 'Shareholder'){
			$data=array('AssociatedWith'=>$id,'StatementType'=> 'D');
		}else{
			$data=array('AssociatedWith'=>$id,'StatementType'=> '');
		}
        $this->db->where('ID', $statmentId);
        $this->db->update('bank_statements',$data);
		//echo $this->db->last_query();
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
}
