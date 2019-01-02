<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dividends extends CI_Model {

    public function Dividend() {
        parent::__construct();
    }

    public function getShareHoldersList() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        //$query = $this->db->get_where($prefix.'company_customers',array('CompanyID'=>$user['CompanyID'],'IS_ShareHolder'=>'1'));
        $query = $this->db->query("SELECT * FROM (`" . $prefix . "company_customers`) WHERE `CompanyID` = '" . $user['CompanyID'] . "' AND (`IS_ShareHolder` = '1')");
        //echo $this->db->last_query();
		if ($query->num_rows() > 0) {
            $data = array('0' => 'Select Shareholder');
            foreach ($query->result() as $key => $val) {
                //$id = $this->encrypt->encode('ACTION_CHECK_USER/'.$val->ID);
                $data[$val->ID] = $val->FirstName . ' ' . $val->LastName;
            }
            return $data;
        } else {
            return array('0' => 'No Share Holders');
        }
    }

    public function performAction($task) {
        $prefix = $this->db->dbprefix;
        /* Check if Created by accountant while accessing the client account */
        $accountant_access = clientAccess();

        switch ($task[0]) {
            case 'ACTION_PAID':
                /* Get the voucher number first */
                $vc_no = $this->getVoucherNumber($task[1]);
                
                $this->db->select('NetAmount');
                $this->db->from($prefix.'dividends');
                $this->db->where('DID', $task[1]);
                $query = $this->db->get();
                $result = $query->result_array();
                $exp =  explode('-', $task[2]);
                if($exp[0]>=2016){
                    $tax_amount;
                }else{
                    $tax_amount = ($result[0]['NetAmount'] / DIVIDEND_TAX_PERCENT) / 100;
                }
                
                $gross_amount = $result[0]['NetAmount'] + $tax_amount;
                
                $this->db->where('DID', $task[1]);
                $this->db->update('dividends', array('GrossAmount' => $gross_amount,'TaxAmount' => $tax_amount,'Status' => '2', 'PaidOn' => $task[2], 'AccountantAccess' => $accountant_access));
                systemEntries(array('index' => 'DID', 'value' => $task[1]));
                if ($this->db->affected_rows() > 0) {
                    update_trial_balance("dividend", $task[1]);
                    return $vc_no;
                } else {
                    return FALSE;
                }
                break;
            case 'ACTION_DELETE':
                $divdata = $this->getDividendDetails((int) $task[1]);
                /* Get the voucher number first */
                $vc_no = $this->getVoucherNumber($task[1]);
                $this->db->where('DID', $task[1]);
                $this->db->delete('dividends');
                if ($this->db->affected_rows() > 0) {
                    if ($divdata["Status"] == "2") {
                        update_trial_balance("dividend", $divdata, "", "", "DELETE");
                    }

                    /* Update the bank statements */
                    $bank_data = array(
                        'AssociatedWith' => 0
                    );
                    $this->db->where('ID', $divdata['BankStatement']);
                    $this->db->update('bank_statements', $bank_data);

                    /* Update ledger table */
                    $this->db->delete('tb_details', array('itemId' => $task[1], 'source' => 'DIVIDEND'));

                    return $vc_no;
                } else {
                    return FALSE;
                }
                break;
            default:
                break;
        }
    }

    public function addDividend($data) {
        $prefix = $this->db->dbprefix;
        $this->db->insert('dividends', $data);
        if ($this->db->affected_rows() > 0) {
            /* Get the inserted ID of the dividend */
            $did = $this->db->insert_id();

            /* Also store this new id in system entries table */
            systemEntries(array('index' => 'DID', 'value' => $did));
            /* Get the shareholder first name three words */
            $this->db->select('FirstName');
            $query = $this->db->get_where('company_customers', array('ID' => $data['ShareholderID']));
            $response = $query->result();
            $response = strtoupper($response[0]->FirstName);

            /* Prepare the voucher number */
            $voucher_no = substr($response, 0, 3) . '-' . date('Y') . date('m') . date('d') . '-' . $did;

            /* Now update the voucher number */
            $this->db->where('DID', $did);
            $this->db->update('dividends', array('VoucherNumber' => $voucher_no));

            if ($data["Status"] == 2 && isset($data["PaidOn"]) && !empty($data["PaidOn"])) {
				update_logs('DIVIDEND', 'USER_CREATED_PAID_DIVIDEND', 'CREATE', "", $did);
                update_trial_balance("dividend", $did);
            }else{
				update_logs('DIVIDEND', 'USER_CREATED_DIVIDEND', 'CREATE', "", $did);
			}

            /* Everything successfull then return true */
            return $voucher_no;
        } else {
            return FALSE;
        }
    }

    public function getItems($limit = DIVIDEND_PAGINATION_LIMIT, $start = 0) {
        $user = $this->session->userdata('user');
		$prefix = $this->db->dbprefix;
        //echo 'Operation : '.$operation.'<br/>';
        $order = $this->session->userdata('DividendSortingOrder');
        if (isset($order) && !empty($order)) {
            $orderby = " ORDER BY " . $order . ' LIMIT ' . $start . ',' . $limit;
        } else {
            $orderby = " ORDER BY d.DID DESC LIMIT " . $start . "," . $limit;
        }
        $search = $this->session->userdata('DividendSearch');

        $where = $this->search($start, $limit);
        $query = "SELECT CONCAT(s.FirstName,' ',s.LastName) AS ShareholderName,s.TotalShares,";
        $query .= "d.DID,d.VoucherNumber,d.DividendDate,d.PaidOn,d.GrossAmount,d.TaxAmount,d.NetAmount,d.Status,d.PaidByDirectorLoan";
        $query .= " FROM " . $prefix . "dividends AS d";
        $query .= " LEFT JOIN " . $prefix . "company_customers AS s ON s.ID = d.ShareholderID";
        $query .= $where;
		$query .= " AND d.CompanyID = ".$user['CompanyID'];
		
        if (!empty($search)) {
            $search_query = $this->db->query($query);
            $this->session->set_userdata('DividendSearchRecords', $search_query->num_rows());
        }
        $query .=' ' . $orderby;
        $query = $this->db->query($query);

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function search($start, $limit) {
        $search = $this->session->userdata('DividendSearch');

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
                        if ($key == 'SharerName') {
                            $where[] .= 'd.ShareholderID =' . $val;
                        } elseif ($key == 'VoucherNumber') {
                            $where[] .= 'd.' . $key . ' LIKE "%' . $val . '%"';
                        } elseif ($key == 'dStartDate') {
                            $where[] .= 'd.DividendDate >= "' . $val . '"';
                        } elseif ($key == 'dEndDate') {
                            $where[] .= 'd.DividendDate <= "' . $val . '"';
                        } else {
                            $where[] .= 'd.' . $key . '="' . $val . '"';
                        }
                    }
                }
            }
        } else {
            $where = '';
        }
        if ($where == '') {
            $where = ' WHERE ' . "d.AddedBy=" . $userID;
        } else {
            $where = implode(' AND ', $where);
            $where = ' WHERE ' . $where . ' AND d.AddedBy=' . $userID;
        }
        return $where;
    }

    public function totalDividends() {
        $prefix = $this->db->dbprefix;
        $search = $this->session->userdata('DividendSearch');
        $user = $this->session->userdata('user');
        $user = $user['UserID'];
        $totalRecord = $this->session->userdata('DividendSearchRecords');

        if (isset($totalRecord) && !empty($totalRecord)) {
            return $totalRecord;
        }

        $this->db->where('AddedBy', $user);
        $records = $this->db->count_all_results('dividends');

        if ($records > 0) {
            return $records;
        } else {
            return 0;
        }
    }

    public function getItem($id = NULL) {
        $prefix = $this->db->dbprefix;
        if (empty($id)) {
            return FALSE;
        }
        $query = "SELECT d.DID,d.ShareholderID,d.VoucherNumber,d.DividendDate,d.GrossAmount,d.NetAmount,d.TaxAmount,d.PaidOn,d.Address,d.shareholder_address";
        $query .= ",d.PaidByDirectorLoan,d.Status,s.TotalShares,s.DesignationType,CONCAT(s.FirstName,' ',LastName) as SharerName  ";
        $query .= "FROM " . $prefix . "dividends AS d LEFT JOIN " . $prefix . "company_customers AS s ON s.ID = d.ShareholderID";
        $query .= " WHERE d.DID=" . $id;
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            $record = $query->result();
            $record = get_object_vars($record[0]);
            $record['Params'] = unserialize($record['Params']);
            //$record['ShareholderID'] = $this->encrypt->encode('ACTION_CHECK_USER/'.$record['ShareholderID']);
            //echo '<pre>';print_r($record);echo '</pre>';
            return $record;
            //die;
        } else {
            return FALSE;
        }
    }

    public function checkShareHolder($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('CompanyID,DesignationType,TotalShares,Is_Director,Params');
        $query = $this->db->get_where('company_customers', array('ID' => $id));
        $response = $query->result();
        //echo '<pre>';print_r($response);echo '</pre>';die;
        return $response;
        //echo '<pre>';print_r($response);echo '</pre>';die;
    }

    public function getShareHolderFields($fields, $op = '=', $id = null) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        if ($id == NULL) {
            $query = "SELECT " . implode(',', $fields) . " FROM " . $prefix . "company_customers WHERE CompanyID = " . $user['CompanyID'];
        } else {
            $query = "SELECT " . implode(',', $fields) . " FROM " . $prefix . "company_customers WHERE ID " . $op . $id . " AND CompanyID=" . $user['CompanyID'];
        }

        $query = $this->db->query($query);
        $result = $query->result();
        if (count($result) == 1) {
            $result[0]->Params = unserialize($result[0]->Params);
        } else {
            foreach ($result as $key => $val) {
                $val->Params = unserialize($val->Params);
            }
        }
        return $query->result();
    }

    public function getTotalShares() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = "SELECT Params FROM " . $prefix . "company WHERE CID=" . $user['CompanyID'];
        $query = $this->db->query($query);
        $response = $query->result();
        $response = unserialize($response[0]->Params);
        //echo '<pre>';print_r($response);echo '</pre>';die;
        if ($query->num_rows() < 0) {
            return 0;
        } else {
            return $response['CompanyShares'];
        }
    }

    public function updateDividend($data, $id) {
        $prefix = $this->db->dbprefix;
        $this->db->where('DID', $id);
        $this->db->update('dividends', $data);
        /* Check if marked as paid */
        if ($data['PaidOn'] == 2) {
            systemEntries(array('index' => 'DID', 'value' => $id));
        }

        $this->db->select('VoucherNumber');
        $query = $this->db->get_where('dividends', array('DID' => $id));
        $query = $query->result();
        return $query[0]->VoucherNumber;
    }

    public function getVoucherNumber($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('VoucherNumber');
        $query = $this->db->get_where('dividends', array('DID' => $id));
        $vc_no = $query->result();
        return $vc_no[0]->VoucherNumber;
    }

    public function getShareHolderName($id = NULL) {
        $prefix = $this->db->dbprefix;
        if (empty($id)) {
            return FALSE;
        }
        $this->db->select('ID,CONCAT(FirstName," ' . '",LastName) AS Name', false);
        $query = $this->db->get_where('company_customers', array('ID' => $id));
        $name = $query->result();
        //echo '<pre>';print_r($name);echo '</pre>';die;
        return $name[0]->Name;
    }

    public function getShares($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('Params');
        $query = $this->db->get_where('company_customers', array('ID' => $id));
		
        if ($query->num_rows() <= 0) {
            return 0;
        }
        //$query = $
    }

    public function getDirectorsList() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        //$this->db->select('ID,CONCAT(FirstName," '.'",LastName) AS Name',false);
        $query = 'SELECT ID,CONCAT(FirstName," ' . '",LastName) AS Name FROM ' . $prefix . 'company_customers ';
        $query .= ' WHERE (DesignationType = "D" OR Is_Director=1) AND CompanyID = ' . $user['CompanyID'];
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $val) {
                $data[$val->ID] = $val->Name;
            }
            return $data;
        } else {
            return FALSE;
        }
    }

    public function companyDetails($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('Params,RegistrationNo');
        $response = $this->db->get_where('company', array('CID' => $id));
        if (count($response) > 0) {
            $company_detail = $response->result();
            $company_details = unserialize($company_detail[0]->Params);
            $company_details['RegistrationNumber'] = $company_detail[0]->RegistrationNo;
            return $company_details;
        } else {
            return array();
        }
    }

    public function get_accountant_signature($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('Params');
        $query = $this->db->get_where('users', array('ID' => $id));
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        $result = '';
        if ($query->num_rows() > 0) {
            $result = $query->result();
            ///echo '<pre>';var_dump($result);echo '</pre>';
            $result = unserialize($result[0]->Params);
            //echo '<pre>';print_r($result);echo '</pre>';die;
            return $result;
        } else {
            return array();
        }
    }

    public function getDividendDetails($item) {
        $prefix = $this->db->dbprefix;
        $this->db->select("*, DID as id, PaidOn as PaidDate, GrossAmount as Total");
        $this->db->from("dividends");
        $this->db->where("DID", $item);
        $query = $this->db->get();
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            // prd( $result );
            return $result;
        } else {
            return false;
        }
    }

    public function check_statement_dividend($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('DID');
        $query = $this->db->get_where('dividends', array('BankStatement' => $id));
        $response = $query->result_array();
        //echo '<pre>';print_r($response);echo '</pre>';die;
        if (count($response) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /* check Company Address */

    public function checkCompanyAddress($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('Params');
        $query = $this->db->get_where($prefix . 'company', array('CID' => $id));
        $response = $query->result();
        return $response;
    }

    /* get dividend address */

    public function getDividendAdd() {
        $prefix = $this->db->dbprefix;
        $this->db->select('DID,CompanyID,ShareholderID,Address,shareholder_address');
        $this->db->from($prefix . "dividends");
        $query = $this->db->get();
        $result = $query->result_array();
        if (!empty($result)) {
            return $result;
        } else {
            return '';
        }
    }

    /* update diividend address */

    public function insertDividendAdd($DID = NULL, $cmpId = NULL) {
        $prefix = $this->db->dbprefix;
        $this->db->select('Params');
        $this->db->from($prefix . "company");
        $this->db->where('CID', $cmpId);
        $query = $this->db->get();
        $result = $query->row();
        if (!empty($result)) {
            $data = array('Address' => $result->Params);
            $this->db->where('DID', $DID);
            $this->db->update($prefix . 'dividends', $data);
            return "A dividend was updated==" . $DID . "<br/>";
        }
    }

    /* update dividend share holder address */

    public function insertDividendshareholderAddress($ShareholderID = NULL,$DID=NULL) {
        $prefix = $this->db->dbprefix;
        $this->db->select('Params');
        $this->db->from($prefix . "company_customers");
        $this->db->where('ID', $ShareholderID);
        $query = $this->db->get();
        $result = $query->row();
        if (!empty($result)) {
            $data = array('shareholder_address' => $result->Params);
            $this->db->where('DID', $DID);
            $this->db->where('ShareholderID', $ShareholderID);
            $this->db->update($prefix . 'dividends', $data);
            return "A dividend was updated==" . $DID . "<br/>";
        }
    }
	
	/*
    *  This function will return the list of all purchases generated by the client.
    */

    public function getDividendListForLink($catKey = NULL) {
		$user = $this->session->userdata('user');
        $userID = $user['UserID'];
        $CompanyID = $user['CompanyID'];		
        if($catKey != NULL){
			$prefix = $this->db->dbprefix;  
			$query = "SELECT CONCAT(s.FirstName,' ',s.LastName) AS ShareholderName,s.TotalShares,";
			$query .= "d.DID,d.VoucherNumber,d.DividendDate,d.PaidOn,d.GrossAmount,d.TaxAmount,d.NetAmount,d.Status,d.PaidByDirectorLoan";
			$query .= " FROM " . $prefix . "dividends AS d";
			$query .= " LEFT JOIN " . $prefix . "company_customers AS s ON s.ID = d.ShareholderID";
			$query .= " WHERE d.Status = 2 AND d.CompanyID = ".$CompanyID;		
			$query .= " AND d.ShareholderID IN (SELECT id FROM `cashman_company_customers` WHERE TbCategoryshareholder ='".$catKey."')";
			$query = $this->db->query($query);
			//echo $this->db->last_query();
			if ($query->num_rows() > 0) {
				$record = $query->result();
				return $record;
			} else {
				return array();
			}
		}else{
			$prefix = $this->db->dbprefix;
			$query = "SELECT CONCAT(s.FirstName,' ',s.LastName) AS ShareholderName,s.TotalShares,";
			$query .= "d.DID,d.VoucherNumber,d.DividendDate,d.PaidOn,d.GrossAmount,d.TaxAmount,d.NetAmount,d.Status,d.PaidByDirectorLoan";
			$query .= " FROM " . $prefix . "dividends AS d";
			$query .= " LEFT JOIN " . $prefix . "company_customers AS s ON s.ID = d.ShareholderID";
			$query .= " WHERE d.Status = 2 AND d.CompanyID =".$CompanyID;			
			$query = $this->db->query($query);
			//echo $this->db->last_query();
			if ($query->num_rows() > 0) {
				$record = $query->result();
				return $record;
			} else {
				return array();
			}
		}
    }
	
	/**
     * 	This function to genrate finacial yera by dividend paid date date
     */
	
	public function genrateFinacialYearEndDate($pdate = NULL, $sdate = NULL, $edate = NULL,$action = NULL) { 
		
		for($i=0; $i<date('Y', strtotime($edate)); $i++ ){
			if(strtotime($user['pdate']) >= strtotime($user['sdate']) && strtotime($user['pdate']) <= strtotime($user['edate'])){
				echo $edate;
				break;
			}else{
				$sdate = date('Y-m-d', strtotime('-1 year', strtotime($sdate)));
				$edate = date('Y-m-d', strtotime('-1 year', strtotime($edate)));				
			}
		}
		
	}

}
