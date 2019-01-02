<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Expense extends CI_Model {

    public function Expense() {
        parent::__construct();
    }

    /**
     * 	Function to get the list of expenses.
     * 	@Return value: arrray of elements.
     */
    public function getItems($limit = EXPENSE_PAGINATION_LIMIT, $start = 0){
		$user = $this->session->userdata('user');
        $prefix = $this->db->dbprefix;
        $order = $this->session->userdata('expenseSortingOrder');
        if (isset($order) && !empty($order)) {
            $orderby = " ORDER BY " . $order . ' LIMIT ' . $start . ',' . $limit;
        } else {
            $orderby = " ORDER BY e.ID DESC LIMIT " . $start . "," . $limit;
        }
        $search = $this->session->userdata('ExpenseSearch');
        $where = $this->search($start, $limit);
        $query = "SELECT CONCAT(ce.FirstName,' ',ce.LastName) AS EmployeeName,e.ID,e.ExpenseNumber,e.ExpenseType";
        $query .= ",e.Month,e.Year,e.TotalMiles,e.FileID,e.Status,e.TotalAmount,e.TotalVATAmount,e.PaidOn FROM " . $prefix . "expenses AS e";
        $query .= " LEFT JOIN " . $prefix . "company_customers AS ce ON ce.ID = e.EmployeeID";
        $query .= $where;
		$query .= " AND e.CustomerCompanyID = ".$user['CompanyID'];
        if (!empty($search)) {
            $search_query = $this->db->query($query);

            $this->session->set_userdata('ExpenseSearchRecords', $search_query->num_rows());
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

    /**
     * 	Function to get the particular item from the database.
     */
    public function getItem($id = NULL) {
        $prefix = $this->db->dbprefix;
        if (empty($id)) {
            return FALSE;
        }
        $query = "SELECT e.ID,e.ExpenseType,e.EmployeeID,e.Month,e.Year,e.TotalAmount,e.TotalMiles,e.PaidOn,e.Status";
        $query .= ",e.TotalVATAmount FROM " . $prefix . "expenses AS e ";
        $query .= " WHERE e.ID = " . $id;
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $expense = $query->result();
        }

        $query = "SELECT i.ID,i.Category,i.ItemType,i.ItemDate,i.LocationFrom,i.LocationTo,i.Purpose,i.Category,i.Description,";
        $query .= "i.Amount,i.Miles,i.VATAmount FROM " . $prefix . "expense_items AS i WHERE i.ExpenseID = " . $id;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $expense_item = $query->result();
        } else {
            $expense_item = array();
        }
        $expense_items = array();
        $expense_mileage = array();

        if (count($expense_item) > 0) {

            foreach ($expense_item as $key => $val) {
                if ($val->ItemType == 'EXPENSE') {
                    $expense_items[] = $val;
                } else {
                    $expense_mileage[] = $val;
                }
            }
        }

        $expense[0]->ExpenseItems = $expense_items;
        $expense[0]->ExpenseMileage = $expense_mileage;
        $expense[0]->Miles = $this->getCarMiles($expense[0]->EmployeeID, $expense[0]->Year);
        //echo '<pre>';print_r($expense);echo '</pre>';die;
        return get_object_vars($expense[0]);
    }

    public function search($start, $limit) {
        $search = $this->session->userdata('ExpenseSearch');

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
                    foreach ($search as $key => $val) {
                        $where[] .= 'e.' . $key . '=' . $val;
                    }
                }
            }
        } else {
            $where = '';
        }
        if ($where == '') {
            $where = ' WHERE ' . "e.AddedBy=" . $userID;
        } else {
            $where = implode(' AND ', $where);
            $where = ' WHERE ' . $where . ' AND e.AddedBy=' . $userID;
        }
        return $where;
    }

    public function totalExpenses() {
        $prefix = $this->db->dbprefix;
        $search = $this->session->userdata('ExpenseSearch');
        $user = $this->session->userdata('user');
        $user = $user['UserID'];
        $totalRecord = $this->session->userdata('ExpenseSearchRecords');
        if (isset($totalRecord) && !empty($totalRecord)) {
            return $totalRecord;
        }
        $this->db->where('AddedBy', $user);
        $records = $this->db->count_all_results('expenses');
        if ($records > 0) {
            return $records;
        } else {
            return 0;
        }
    }

    /**
     * 	Function to save the expense detail.
     * 	Return Type: insert id.
     */
    function save($data) {
		//echo "<pre>"; print_r($data); die('Expense model 166');
        $prefix = $this->db->dbprefix;
        $this->db->insert('expenses', $data);
		//echo $this->db->last_query(); die;
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        $insert_id = $this->db->insert_id();
        if ($data['PaidOn'] != '') {
            systemEntries(array('index' => 'ExpenseID', 'value' => $insert_id));
        }
        if ($insert_id <= 0) {
            return FALSE;
        } else {
            return $insert_id;
        }
    }

    /**
     * 	Function to save the expense items detail.
     * 	Return Type: insert id.
     */
    public function saveItems($data = array()) {
        $prefix = $this->db->dbprefix;
        $this->db->insert_batch('expense_items', $data);
        //echo $this->db->last_query();
        //echo '<pre>';print_r($data);echo '</pre>';die;
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($this->db->affected_rows() <= 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 	Function to delete the expense.
     * 	Return Type: boolean.
     */
    public function delete($id) {
        $prefix = $this->db->dbprefix;
        $this->db->where('ID', $id);
        $this->db->delete('expenses');
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($this->db->affected_rows() < 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 	Function to get the employee list of the client.
     */
    public function getEmployeeList($check = null) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $this->db->select('ID,CONCAT(FirstName," ' . '",LastName) AS Name', false);
        $query = $this->db->get_where('company_customers', array('CompanyID' => $user['CompanyID'], 'IS_Employee' => '1'));
        //echo $this->db->last_query();//die;
        if ($query->num_rows() > 0) {
            $query = $query->result();
            $record = array('0' => 'Select Employee');
            foreach ($query as $key => $val) {
                //$record[$this->encrypt->encode($val->EID)] = $val->Name;
                if (!empty($check)) {
                    $record[$val->ID] = $val->Name;
                } else {
                    $record[] = $val->Name;
                }
            }
            $record = array_unique($record);
            if (empty($check)) {
                $temp = array();
                foreach ($record as $key => $val) {
                    $temp[] = $val;
                }
                $record = $temp;
            }
            return array_unique($record);
        } else {
            $no_record = array('0' => 'No Employees');
            return $no_record;
        }
    }

    public function updateAccmount($data = null, $id = null) {

        $prefix = $this->db->dbprefix;
        $this->db->where('ID', $id);
        $this->db->update('expenses', $data);
    }

    public function performAction($task = NULL, $id = NULL, $date = NULL) {
		
        $prefix = $this->db->dbprefix;
        if ($task == NULL || $id == NULL) {
            return FALSE;
        }

        if ($date == NULL) {
            $date = date('Y-m-d');
        }

        /* Check if Created by accountant while accessing the client account */
        $accountant_access = clientAccess();

        switch ($task) {
            case 'ACTION_RECONCILED':
                $data = array('Reconciled' => '1', 'AccountantAccess' => $accountant_access);
                $this->db->where('ID', $id);
                $this->db->update('expenses', $data);
                if ($this->db->affected_rows() < 0) {
                    return FALSE;
                }
                break;
            case 'ACTION_DELETE':
                $expData = $this->getExpenseDetails((int) $id);
                $this->db->where('ID', $id);
                $this->db->delete('expenses');
                $db_error = $this->db->error();
                if ($db_error['code'] != 0) {
                    log_message('error', $db_error['message']);
                    return FALSE;
                }
                if ($this->db->affected_rows() <= 0) {
                    return FALSE;
                }
                
                if ($expData["Status"] == "3" || $expData["Status"] == "2") {
                    update_trial_balance("expense", $expData, "", "", "DELETE");
                }

                /* Update ledger table */
                $this->db->delete('tb_details', array('itemId' => $id, 'source' => 'EXPENSE'));
				
                break;
            case 'ACTION_PAID':
                $data = array('Status' => '3', 'PaidOn' => mDate($date), 'AccountantAccess' => $accountant_access);
                $this->db->where('ID', $id);
                $this->db->update('expenses', $data);
                $db_error = $this->db->error();
                if ($db_error['code'] != 0) {
                    log_message('error', $db_error['message']);
                    return FALSE;
                }
                if ($this->db->affected_rows() <= 0) {
                    return FALSE;
                }
                systemEntries(array('index' => 'ExpenseID', 'value' => $id));
                //update_trial_balance("expense", $id);
                break;
            default:
                break;
        }
        return TRUE;
    }

    public function update($data = array(), $id = 0) {
        $prefix = $this->db->dbprefix;
        if (count($data) == 0 && empty($id)) {
            return FALSE;
        }

        $this->db->where('ID', $id);
        $this->db->update('expenses', $data);
       // echo $this->db->last_query(); die();
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($this->db->affected_rows() < 0) {
            return FALSE;
        }
        return TRUE;
		update_logs('EXPENSE', 'USER_UPDATED_EXPENSES', 'UPDATE', "", $id);
    }

    public function updateItems($data) {
        $prefix = $this->db->dbprefix;
        if (count($data) == 0 && empty($id)) {
            return FALSE;
        }

        //	echo '<pre>';print_r($data);echo '</pre>';die;
        foreach ($data as $key => $val) {
            if ($val['ID'] != '') {
                $this->db->where('ID', $val['ID']);
                $this->db->update('expense_items', $val);
                $db_error = $this->db->error();
                if ($db_error['code'] != 0) {
                    log_message('error', $db_error['message']);
                    return FALSE;
                }
            } else {
                unset($val['ID']);
                $this->db->insert('expense_items', $val);
                $db_error = $this->db->error();
                if ($db_error['code'] != 0) {
                    log_message('error', $db_error['message']);
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    // function for deleting expense item 
    public function delexpItem($data = NULL) {
        if (!empty($data)) {
            $prefix = $this->db->dbprefix;
            $exp = explode(',', $data);
            $this->db->where_in('ID', $exp);
            $this->db->delete($prefix . 'expense_items');
            return TRUE;
        } else {
            return false;
        }
    }

    public function saveUploads($data = array()) {
        $prefix = $this->db->dbprefix;
        if (count($data) <= 0) {
            return FALSE;
        }
        $user = $this->session->userdata('user');
        $this->db->select_max('ExpenseID');
        $query = $this->db->get('expenses');
        $current_id = $query->result();
        $current_id = $current_id[0]->ExpenseID;
        //ECHO 'Current ID : '.$current_id;DIE;
        $this->db->insert_batch('expenses', $data);
        //echo 'TotalRecord : '.$this->db->affected_rows();die;
        if ($this->db->affected_rows() < 0) {

            return FALSE;
        }

        $query = "SELECT ExpenseID,Status FROM " . $prefix . "expenses WHERE ExpenseID > " . $current_id . ' AND AddedBy=' . $user['UserID'];
        $query = $this->db->query($query);
        //echo 'TotalRecord : '.$query->num_rows();die;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $val) {
                if ($val->Status == 2) {
                    systemEntries(array('index' => 'ExpenseID', 'value' => $val->ExpenseID));
                }
            }
        }
        return TRUE;
    }

    public function getCategories() {
        $prefix = $this->db->dbprefix;
        $query = $this->db->get_where('expenses_category', array('CategoryType' => 'GEN'));
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() <= 0) {
            return array();
        }

        $records = $query->result();
        $data = array();
        foreach ($records as $key => $val) {
            $data[$val->ID] = trim($val->Title);
        }
        return $data;
    }

    public function checkEmployee($id) {
        $prefix = $this->db->dbprefix;
        $query = $this->db->get_where('company_customers', array('ID' => $id));
        if ($query->num_rows() <= 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getEmployeeName() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        //echo '<pre>';print_r($user);echo '</pre>';die;
        $query = "SELECT ID,CONCAT(FirstName,' ',LastName) AS Name FROM " . $prefix . "company_customers ";
        $query .= "WHERE (DesignationType='E' OR IS_Employee=1) AND CompanyID=" . $user['CompanyID'];
        $query = $this->db->query($query);

        if ($query->num_rows() > 0) {
            $name = array('0' => 'Select Employee');
            foreach ($query->result() as $key => $val) {
                $name[$val->ID] = $val->Name;
            }
            $name = array_unique($name);
            return $name;
            //echo '<pre>';print_r($name);echo '</pre>';die;
        } else {
            return array('' => 'You have no employee');
        }
    }

    public function getECategories() {
        $prefix = $this->db->dbprefix;
        $query = 'SELECT Title FROM ' . $prefix . 'expenses_category WHERE CategoryType ="GEN"';
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            $data = array();
            foreach ($query->result() as $key => $val) {
                $data[] = $val->Title;
            }
            return $data;
        }
    }

    public function getMethods($check = NULL) {
        $prefix = $this->db->dbprefix;
        $this->db->select('Title,ID');
        $query = $this->db->get_where('expenses_category', array('CategoryType' => 'VECH'));
        if ($query->num_rows() > 0) {
            $data = array();
            foreach ($query->result() as $key => $val) {
                if ($check != null) {
                    $data[] = $val->Title;
                } else {
                    $data[$val->ID] = $val->Title;
                }
            }
            return $data;
        }
    }

    public function getEmployeeID($name = NULL) {
        $prefix = $this->db->dbprefix;
        $query = "SELECT ID FROM " . $prefix . "company_customers WHERE CONCAT(FirstName,' ',LastName) = '" . $name . "'";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data[0]->ID;
        } else {
            return 0;
        }
    }

    public function getCategoryID($name = NULL) {
        $prefix = $this->db->dbprefix;
        $query = "SELECT ID FROM " . $prefix . "expenses_category WHERE Title = '" . $name . "'";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data[0]->ID;
        } else {
            return 0;
        }
    }

    function getVatType() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = $this->db->get_where('tax_rates', array('ClientID' => $user['UserID']));
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data[0];
        } else {
            return tableColumns($prefix . 'tax_rates');
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

    function saveFile($data) {
        $prefix = $this->db->dbprefix;
        $this->db->insert('files', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    function updateAmount($data, $id) {
        $prefix = $this->db->dbprefix;
        $this->db->update('files', array('TotalAmount' => $data), array('ID' => $id));
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function draftexpupdate($id = NULL, $data = NULL) {
        $prefix = $this->db->dbprefix;
        if (!empty($id) && !empty($data)) {
            $this->db->where('id', $id);
            $this->db->update($prefix . 'expenses', $data);
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    /**
     * 	Function to get the previous car mileage.
     */
    public function getCarMiles($id = null, $year = null, $item_id = null) {
        $prefix = $this->db->dbprefix;
        if (empty($id)) {
            return 0;
        }
        $f_start_date = ($year - 1) . '-04-06';
        $f_end_date = $year . '-04-05';

        $query = "SELECT i.Miles FROM " . $prefix . "expense_items AS i";
        $query .= " LEFT JOIN " . $prefix . "expenses AS e ON e.ID = i.ExpenseID ";
        /*
          if(empty($item_id))
          {
          //$query .= " WHERE i.ItemType='MILEAGE' AND i.Category=32 AND e.EmployeeID=".$id.' AND e.Year='.$year;
          $query .= " WHERE i.ItemType='MILEAGE' AND i.Category=32 AND e.EmployeeID=".$id;
          //$query .= " AND (i.ItemDate >= ".$f_start_date." AND i.ItemDate <= ".$f_end_date.")";
          $query .= " AND (i.ItemDate BETWEEN '".$f_start_date."' AND '".$f_end_date."')";
          }else{
          $query .= " WHERE i.ItemType='MILEAGE' AND i.Category=32 AND e.EmployeeID=".$id.' AND i.ExpenseID <'.$item_id;
          }
         */
        $query .= " WHERE i.ItemType='MILEAGE' AND i.Category=32 AND e.EmployeeID=" . $id;
        $query .= " AND (i.ItemDate BETWEEN '" . $f_start_date . "' AND '" . $f_end_date . "')";
        $query .= " AND e.Status!=1";
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        //echo '<pre>';print_r($query->result());echo '</pre>';die;
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $total_miles = 0;
            foreach ($result as $key => $val) {
                $total_miles += $val->Miles;
            }
            return $total_miles;
        } else {
            return 0;
        }
    }

    public function getCarPrevMilee($id = null, $year = null, $item_id = null) {
        $prefix = $this->db->dbprefix;
        if (empty($id)) {
            return 0;
        }
        $f_start_date = ($year - 1) . '-04-06';
        $f_end_date = $year . '-04-05';

        $query = "SELECT i.Miles FROM " . $prefix . "expense_items AS i";
        $query .= " LEFT JOIN " . $prefix . "expenses AS e ON e.ID = i.ExpenseID ";
        /*
          if(empty($item_id))
          {
          //$query .= " WHERE i.ItemType='MILEAGE' AND i.Category=32 AND e.EmployeeID=".$id.' AND e.Year='.$year;
          $query .= " WHERE i.ItemType='MILEAGE' AND i.Category=32 AND e.EmployeeID=".$id;
          //$query .= " AND (i.ItemDate >= ".$f_start_date." AND i.ItemDate <= ".$f_end_date.")";
          $query .= " AND (i.ItemDate BETWEEN '".$f_start_date."' AND '".$f_end_date."')";
          }else{
          $query .= " WHERE i.ItemType='MILEAGE' AND i.Category=32 AND e.EmployeeID=".$id.' AND i.ExpenseID <'.$item_id;
          }
         */
        $query .= " WHERE i.ItemType='MILEAGE' AND i.Category=32 AND e.EmployeeID=" . $id;
        $query .= " AND (i.ItemDate BETWEEN '" . $f_start_date . "' AND '" . $f_end_date . "')";
        $query .= " AND e.Status!=1";
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        //echo '<pre>';print_r($query->result());echo '</pre>';die;
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $total_miles = 0;
            foreach ($result as $key => $val) {
                $total_miles += $val->Miles;
            }
            return $total_miles;
        } else {
            return 0;
        }
    }

    public function getCarPrevMileetotal($id = null, $year = NULL, $itemdate = NULL) {
        $prefix = $this->db->dbprefix;
        if (empty($id)) {
            return 0;
        }
        if ($itemdate > $year . '-04-05') {
            $f_start_date = $year . '-04-06';
            $f_end_date = ($year + 1) . '-04-05';
        } else {
            $f_start_date = ($year - 1) . '-04-06';
            $f_end_date = $year . '-04-05';
        }

        $query = "SELECT i.Miles FROM " . $prefix . "expense_items AS i";
        $query .= " LEFT JOIN " . $prefix . "expenses AS e ON e.ID = i.ExpenseID ";
        $query .= " WHERE i.ItemType='MILEAGE' AND i.Category=32 AND e.EmployeeID=" . $id;
        $query .= " AND (i.ItemDate BETWEEN '" . $f_start_date . "' AND '" . $f_end_date . "')";
        $query .= " AND e.Status!=1";
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $total_miles = 0;
            foreach ($result as $key => $val) {
                $total_miles += $val->Miles;
            }
            return $total_miles;
        } else {
            return 0;
        }
    }

    /**
     * 	This function gets the previous car miles based on the mileage date chosen.
     */
    public function get_car_previous_miles($id, $date) {
       

        $prefix = $this->db->dbprefix;
        $date_check = date('Y', strtotime($date)) . '-04-06';
        /**
         * 	@date is the mileage date.
         * 	@date_check is the financial year date.
         */
		 
		//echo $date." < ".$date_check;
        if (strtotime($date) < strtotime($date_check)) {
            $f_start_date = (date('Y', strtotime($date)) - 1) . '-04-06';
            //$f_end_date = date('Y-m-d',strtotime($date));
            $f_end_date = date('Y', strtotime($date)). '-04-05';			
        } else if (strtotime($date) >= strtotime($date_check)) {
            $f_start_date = (date('Y', strtotime($date))) . '-04-06';
            //$f_start_date = $date;
            //$f_end_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 365 day"));
			$f_end_date = (date('Y', strtotime($date)) + 1) . '-04-05';
        } else { 		
            $f_start_date = (date('Y', strtotime($date))) . '-04-06';
            $f_end_date = date('Y-m-d',  strtotime($date));
            //$f_end_date =  date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " + 365 day"));
        }

        $query = "SELECT i.Miles FROM " . $prefix . "expense_items AS i";
        $query .= " LEFT JOIN " . $prefix . "expenses AS e ON e.ID = i.ExpenseID ";
        $query .= " WHERE i.ItemType='MILEAGE' AND i.Category=32 AND e.EmployeeID=" . $id;
        //$query .= " AND (i.ItemDate >= ".$f_start_date." AND i.ItemDate <= ".$f_end_date;prd($query).")";
        $query .= " AND i.ItemDate BETWEEN '" . $f_start_date . "' AND '" . $f_end_date . "'";
        $query .= " AND e.Status!=1";
        //pr($query);die;
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
		//echo $this->db->last_query();
        //echo '<pre>';print_r($query->result());echo '</pre>';//die;
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $total_miles = 0;
            foreach ($result as $key => $val) {
                $total_miles += $val->Miles;
            }
            return $total_miles;
        } else {
            return 0;
        }
    }

    /**
     * 	Function to get the maximum value of ID.
     */
    public function max_id() {
        $prefix = $this->db->dbprefix;
        $query = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . $this->db->database . "'";
        $query .= " AND   TABLE_NAME   = '" . $prefix . "expenses';";
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

    public function getExpenseDetails($item) {
        $prefix = $this->db->dbprefix;
        $query = "SELECT `cei`.`ID` as `id`,`cei`.*,`cec`.* FROM `" . $prefix . "expense_items` as `cei` "
                . "\n LEFT JOIN `" . $prefix . "expenses_category` as `cec` ON `cec`.`ID` =`cei`.`Category` "
                . "\n WHERE `cei`.`ExpenseID`=" . $item;
        $query = $this->db->query($query);
        //echo $this->db->last_query();
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        // echo '<pre>';print_r($query->result_array());echo '</pre>';die;
        if ($query->num_rows() > 0) {
            $result = $query->result_array();

            /* Get Expense Details */
            $query = "SELECT *,`ID` as `id` FROM `" . $prefix . "expenses` WHERE `ID`=" . $item;
            $query = $this->db->query($query);
            // echo $this->db->last_query();
            if ($query->num_rows() > 0) {
                $result_arr = $query->row_array();
            }

            $result_arr["items"] = $result;  /* Add all child Expense Items to Expense Details  */
            return $result_arr;
        } else {
            return false;
        }
    }

    function getAllExpenses() {
        //echo 'Operation : '.$operation.'<br/>';
        $prefix = $this->db->dbprefix;
        $VATYear = $this->session->userdata('VATYear');
        $user = $this->session->userdata('user');
        $userID = $user['UserID'];

        $vatQuarters = getVatQuarters();
        if ($vatQuarters) {
            // echo "<pre>"; print_r( $VatQuarters ); die();
            $where[] = " " . $vatQuarters[1]["FIRST"] . " <= e.PaidOn <= " . $vatQuarters[4]["SECOND"] . " ";
        }
        $where[] = " e.AddedBy=" . $userID . " ";
        $where[] = " e.Status=3 ";

        $where = " WHERE " . implode(" AND ", $where);
        $query = "SELECT CONCAT(ce.FirstName,' ',ce.LastName) AS EmployeeName,e.ID,e.ExpenseNumber,e.ExpenseType";
        $query .= ",e.Month,e.Year,e.TotalMiles,e.FileID,e.Status,e.TotalAmount,e.TotalVATAmount,e.PaidOn FROM " . $prefix . "expenses AS e";
        $query .= " LEFT JOIN " . $prefix . "company_customers AS ce ON ce.ID = e.EmployeeID ";
        $query .= $where;

        $result = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($result->num_rows() > 0) {
            $records = $result->result();
            return $records;
        } else {
            return false;
        }
    }

    function getQuarterExpDetails($q = 1, $VATYear) {
        $prefix = $this->db->dbprefix;
        if (!isset($VATYear)) {
            $VATYear = date("Y");
        }
        $vatQuarters = getVatQuarters($VATYear);

        $user = $this->session->userdata('user');
        $userID = $user['UserID'];
        $vat_listing = $this->getVatType();

        $user = $this->session->userdata('user');
        $userID = $user['UserID'];

        $vatQuarters = getVatQuarters();
        if ($vatQuarters) {
            // echo "<pre>"; print_r( $VatQuarters ); die();
            $where[] = " " . $vatQuarters[$q]["FIRST"] . " <= e.PaidOn <= " . $vatQuarters[$q]["SECOND"] . " ";
        }
        $where[] = " e.AddedBy=" . $userID . " ";
        $where[] = " e.Status=3 ";

        $where = " WHERE " . implode(" AND ", $where);
        $query = "SELECT CONCAT(ce.FirstName,' ',ce.LastName) AS EmployeeName,e.ID,e.ExpenseNumber,e.ExpenseType";
        $query .= ",e.Month,e.Year,e.TotalMiles,e.FileID,e.Status,e.TotalAmount,e.TotalVATAmount,e.PaidOn,e.AddedOn FROM " . $prefix . "expenses AS e";
        $query .= " LEFT JOIN " . $prefix . "company_customers AS ce ON ce.ID = e.EmployeeID ";
        $query .= $where;

        $result = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($result->num_rows() > 0) {
            $records = $result->result();
            return $records;
        } else {
            return false;
        }
    }
	
	
	/*----------------------------  Expense Report  ------------------------------*/
	public function getReportItems($limit = EXPENSE_REPORT_PAGINATION_LIMIT, $start = 0) {
		$prefix = $this->db->dbprefix;
		$currentdate= date('Y-m-d');
		$date = strtotime("$currentdate -1 year");
		$lastdate= date('Y-m-d',$date);
		$where=$this->searchExpense($start, $limit);
		$query="SELECT ce.Month, ce.Year, ce.ExpenseNumber, cec.`Title`, cec.`key` ,cei.* FROM ". $prefix ."expenses_category as cec Right JOIN ". $prefix ."expense_items as cei ON cec.`ID` = cei.`Category` Right JOIN ". $prefix ."expenses as ce ON ce.ID = cei.ExpenseID ".$where." order by cei.AddedOn Desc limit $start ,$limit";
        $query = $this->db->query($query);
		if ($query->num_rows() > 0) {
            return $query->result();
        }
		//$this->session->unset_userdata('ExpensereportSearch');
    }

	public function getReportItemsforsheet() {
		$prefix = $this->db->dbprefix;
		$currentdate= date('Y-m-d');
		$date = strtotime("$currentdate -1 year");
		$lastdate= date('Y-m-d',$date);
		$where=$this->searchExpense($start, $limit);
		$query="SELECT ce.Month, ce.Year, ce.ExpenseNumber, cec.`Title`, cec.`key` ,cei.* FROM ". $prefix ."expenses_category as cec Right JOIN ". $prefix ."expense_items as cei ON cec.`ID` = cei.`Category` Right JOIN ". $prefix ."expenses as ce ON ce.ID = cei.ExpenseID ".$where." order by cei.AddedOn Desc";
        $query = $this->db->query($query);
		if ($query->num_rows() > 0) {
            return $query->result();
        }
		//$this->session->unset_userdata('ExpensereportSearch');
    }
	public function getReportItemscount($limit = EXPENSE_REPORT_PAGINATION_LIMIT, $start = 0) {
		$prefix = $this->db->dbprefix;
		$currentdate= date('Y-m-d');
		$date = strtotime("$currentdate -1 year");
		$lastdate= date('Y-m-d',$date);
		$where=$this->searchExpense($start, $limit);
		$query="SELECT ce.Month, ce.Year, ce.ExpenseNumber, cec.`Title`, cec.`key` ,cei.* FROM ". $prefix ."expenses_category as cec Right JOIN ". $prefix ."expense_items as cei ON cec.`ID` = cei.`Category` Right JOIN ". $prefix ."expenses as ce ON ce.ID = cei.ExpenseID ".$where." order by cei.AddedOn Desc";
        $query = $this->db->query($query);	
		return $query->num_rows();
    }
	public function getReportItemstotalamt() {
		$res=$this->getReportItems($limit = EXPENSE_REPORT_PAGINATION_LIMIT, $start = 0);
		if($res){
			foreach($res as  $res1){
				$total+= $res1->Amount;
			}
			return numberFormat($total);
		}

    }
	public function searchExpense($start, $limit) {
		$prefix = $this->db->dbprefix;
        $search = $this->session->userdata('ExpensereportSearch');
		$user = $this->session->userdata('user');
        $userID = $user['UserID'];
		
		$query = "SELECT `EndDate` FROM ".$prefix."company WHERE `ClientID`=".$userID;
        $query = $this->db->query($query);
		$result=$query->row();
		$end=$result->EndDate;
		$monthday= date('m-d',strtotime($end));
		if(isset($search['Year']) && $search['Year']>0)	{
			$year=$search['Year']-1;
			$newdate=$year.'-'.$monthday;
		}else{
			$currentdate= date('Y-m-d');		
			$date = strtotime("$currentdate -1 year");
			$year= date('Y',$date);
			$newdate=$year.'-'.$monthday;
		}		
		$endd = strtotime("$newdate +1 day");
		$startdate= date('Y-m-d',$endd);
		if(isset($search['Year']) && $search['Year']>0)	{
			$newto=$search['Year']. '-' .$monthday;	
		}else{
			$newto=date('Y'). '-' .$monthday;	
		}	
			
		$totime = strtotime($newto);
		$enddate = date('Y-m-d',$totime);

		if(isset($search['Category']) && $search['Category']>0) {
			$categoryse=$search['Category'];
			$where = "where cei.ItemDate >= '".$startdate."' and  cei.ItemDate <= '".$enddate ."' and ce.AddedBy=" . $userID." and cei.Category =".$categoryse;
		}else{
			$where = "where cei.ItemDate >= '".$startdate."' and  cei.ItemDate <= '".$enddate ."' and ce.AddedBy=" . $userID;
		}
        return $where;
    }
	/*----------------------------  Expense Report  ------------------------------ */
    
    /*----- Vijay (02-08-2016) Function for delete Expense item in trial blance (tb_details) ----------*/
    
    function deletetbDetails($itemId = null,$tbSource) {
        $exp = explode(',', $itemId);
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $clientId = $user["UserID"];
        $CompanyID = $user["CompanyID"];
        $where = array(
            "source" => $tbSource,
            "clientId" => $clientId,
            "companyId" =>  $CompanyID
        );
        
        $data = array (
            'deleteStatus' => '1'
        );
        
        $this->db->where_in('itemId', $exp);
        $this->db->where($where);
        $this->db->update('tb_details',$data);
      //  echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
	
	
	/**
     * 	Function to get the list of expenses for link bank statment.
     * 	@Return value: arrray of elements.
     */
    public function getExpenseListForLink($catKey = NULL){
		$user = $this->session->userdata('user');
        $prefix = $this->db->dbprefix;    
        
		 if($catKey != NULL){
			$query = "SELECT CONCAT(ce.FirstName,' ',ce.LastName) AS EmployeeName,e.ID,e.ExpenseNumber,e.ExpenseType";
			$query .= ",e.Month,e.Year,e.TotalMiles,e.FileID,e.Status,e.TotalAmount,e.TotalVATAmount,e.PaidOn FROM " . $prefix . "expenses AS e";
			$query .= " LEFT JOIN " . $prefix . "company_customers AS ce ON ce.ID = e.EmployeeID";
			$query .= " WHERE e.CustomerCompanyID = ".$user['CompanyID'];
			$query .= " AND e.EmployeeID IN (SELECT id FROM `cashman_company_customers` WHERE TbCategoryEmployee ='".$catKey."')";
			
			$query = $this->db->query($query);
			//echo $this->db->last_query();
			$db_error = $this->db->error();
			if ($db_error['code'] != 0) {
				log_message('error', $db_error['message']);
				return FALSE;
			}
			if ($query->num_rows() > 0) {
				return $query->result();
			}else{
				return "";
			}
		 }else{
			$query = "SELECT CONCAT(ce.FirstName,' ',ce.LastName) AS EmployeeName,e.ID,e.ExpenseNumber,e.ExpenseType";
			$query .= ",e.Month,e.Year,e.TotalMiles,e.FileID,e.Status,e.TotalAmount,e.TotalVATAmount,e.PaidOn FROM " . $prefix . "expenses AS e";
			$query .= " LEFT JOIN " . $prefix . "company_customers AS ce ON ce.ID = e.EmployeeID ";
			$query .= " WHERE e.CustomerCompanyID = ".$user['CompanyID'];
			
			$query = $this->db->query($query);
			//echo $this->db->last_query();
			$db_error = $this->db->error();
			if ($db_error['code'] != 0) {
				log_message('error', $db_error['message']);
				return FALSE;
			}
			if ($query->num_rows() > 0) {
				return $query->result();
			}else{
				return "";
			}
		 }
    }
	
	/**
     * 	Function to check vat applicable or not.
     */
    public function checkVatApplicable($id) {
		$prefix = $this->db->dbprefix;
        $this->db->select('vatApplicable');
        $query = $this->db->get_where('trial_balance_categories', array('id' => $id));
		//$query = $this->db->query($query);
		//echo $this->db->last_query();
		$db_error = $this->db->error();
		if ($db_error['code'] != 0) {
			log_message('error', $db_error['message']);
			return FALSE;
		}
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return $result[0]->vatApplicable;
		}else{
			return "";
		}
	}
}
