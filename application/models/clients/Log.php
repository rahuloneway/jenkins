<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Log extends CI_Model {

    public function Log() {
        parent::__construct();
    }

    public function getLog($limit = ACTION_LOG_PAGINATION_LIMIT, $start = 0, $filter = null) { 
        $user = $this->session->userdata('user');
        $accId = $user['AccountantAccess'];
        $userId = $user['UserID'];
		if($accId == '')
			$accId = 0;
        $query = $this->db->query("select * from cashman_logs where UserId=$userId and AccessAccount='0' or UserId=$userId and AccessAccount=$accId order by Id DESC limit  $start, $limit");
		$resutl = $query->result_array();
		
        if (count($resutl) > 0) {
            return $resutl;
        } else {
            $no_record = array('0' => 'No Employees');
            return $no_record;
        }
    }
	

    /* public function getLog($limit = NULL, $start = 0) {
      $user = $this->session->userdata('user');
      $accId = $user['AccountantAccess'];
      $userId = $user['UserID'];
      $query = $this->db->query("
      select cl.UserId,cl.AccessAccount,cl.ItemId,cl.Source,cl.TYPE,cl.addedOn,ci.InvoiceNumber,ce.ExpenseNumber,cd.VoucherNumber from cashman_logs cl
      INNER JOIN cashman_invoices ci
      ON ci.InvoiceID = cl.ItemId
      INNER JOIN cashman_expenses ce
      ON ce.ID = cl.ItemId
      INNER JOIN cashman_dividends cd
      ON cd.DID = cl.ItemId
      where cl.UserId=$userId and cl.AccessAccount='0'
      or cl.UserId=$userId and cl.AccessAccount=$accId
      order by cl.Id DESC limit 25");
      $resutl = $query->result_array();
      if (count($resutl) > 0) {
      return $resutl;
      } else {
      $no_record = array('0' => 'No Employees');
      return $no_record;
      }
      } */

    /*
      Not in use till "-) i dont know indefinitely
     */

    public function totalEntries() {
        $user = $this->session->userdata('user');
        $accId = $user['AccountantAccess'];
        $userId = $user['UserID'];
		if($accId == '')
			$accId = 0;
        $query = $this->db->query("select * from cashman_logs where UserId=$userId and AccessAccount='0' or UserId=$userId and AccessAccount=$accId order by Id DESC limit 25");
        $resutl = $query->result_array();
        if (count($resutl) > 0) {
            return count($resutl);
        } else {
            return 0;
            ;
        }
    }

    public function searchLog($start_date = NULL, $end_date = NULL, $log_type = NULL) {
        
        $user = $this->session->userdata('user');
        $this->db->where('AccessAccount', $user['UserID']);
        $resutl = '';
        $user = $this->session->userdata('user');
        $accId = $user['AccountantAccess'];
        $userId = $user['UserID'];
        if (!empty($start_date) && !empty($end_date) && empty($log_type)) {
            $query = $this->db->query("select * from cashman_logs where UserId=$userId or addedOn<=$start_date AND addedOn>=$end_date  order by addedOn DESC");
           
        } else if (!empty($start_date) && !empty($end_date) && !empty($log_type)) {
            $query = $this->db->query("select * from cashman_logs where UserId=$userId and Source='$log_type' or addedOn<=$start_date AND addedOn>=$end_date order by addedOn DESC");
           
        } else {
            $query = $this->db->query("select * from cashman_logs where Source='$log_type' AND UserId=$userId  order by addedOn DESC");
         }
        $resutl = $query->result_array();
        return $resutl;
    }

    /////////////////////////////////
    // Log pop up detail functions //
    /////////////////////////////////

    /*
      For showing the log pop-up detail for logIn/Out and delete logs
     */
    function getLogDetails($Id = 0) {
        if ($Id > 0) {
            $this->db->select(" * ");
            $this->db->from("cashman_logs e");
            $this->db->join("cashman_users ed", "e.UserId = ed.ID");
            $this->db->where("e.ID ='" . $Id . "'");
            $result = $this->db->get()->result_array();
            if ($result > 0) {
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
      For showing log pop-up detail for invoices
     */

    public function logInvoiceDetail($Id = NULL) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = 'SELECT i.InvoiceID,i.Tax,i.InvoiceNumber,i.FlatRate,i.NetSales,i.CustomerCompanyID,i.DueDate,i.InvoiceDate,i.UserID,';
        $query .= 'i.BankDetail,CONCAT(u.FirstName," ",u.LastName) AS Name,i.InvoiceTotal,';
        $query .= 'u.Address,it.ID AS ItemID,it.Description,it.UnitPrice,it.Quantity,it.Tax';
        $query .= ' FROM ' . $prefix . 'invoices AS i LEFT JOIN ' . $prefix . 'invoice_items AS it ON it.InvoiceID = i.InvoiceID';
        $query .= ' LEFT JOIN ' . $prefix . 'users AS u ON u.ID = i.UserID';
        $query .= ' WHERE i.InvoiceID=' . $Id; //.' AND i.CustomerCompanyID=1';
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            //echo "<pre>";print_r($query->result());echo"</pre>";DIE;
            $results = $query->result();
            /* Prepare invoice */
            $invoice = array(
                'InvoiceID' => $results[0]->InvoiceID,
                'InvoiceNumber' => $results[0]->InvoiceNumber,
                'DueDate' => date('d-m-Y', strtotime($results[0]->DueDate)),
                'InvoiceDate' => date('d-m-Y', strtotime($results[0]->InvoiceDate)),
                'UserID' => $results[0]->UserID,
                'Name' => $results[0]->Name,
                'BankDetail' => $results[0]->BankDetail,
                'CompanyID' => $results[0]->CustomerCompanyID,
                'Address' => $results[0]->Address,
                'InvoiceTotal' => $results[0]->InvoiceTotal
            );

            /* Check if bank details are needed */
            if ($invoice['BankDetail'] == 1) {
                $response = $this->db->get_where('banks', array('CompanyID' => $invoice['CompanyID']));
                if ($response->num_rows() > 0) {
                    $bank = $response->result();
                    $invoice['Bank_Details'] = get_object_vars($bank[0]);
                } else {
                    $invoice['Bank_Details'] = array();
                }
            } else {
                $invoice['Bank_Details'] = array();
            }

            /* Add compnay details */
            //echo $invoice['CompanyID'];die;
            $response = '';
            $this->db->select('Params,Name,RegistrationNo');
            $response = $this->db->get_where('company', array('CID' => $invoice['CompanyID']));
            if (count($response) > 0) {
                //echo $this->db->last_query();
                $company_detail = $response->result();
                //echo '<pre>';print_r($company_detail);echo '</pre>';
                $company_details = unserialize($company_detail[0]->Params);
                $company_details['Name'] = $company_detail[0]->Name;

                $invoice['Company_details'] = $company_details;
                $invoice['RegistrationNumber'] = $company_detail[0]->RegistrationNo;
            }

            foreach ($results as $key => $val) {
                unset($val->InvoiceID);
                unset($val->InvoiceNumber);
                unset($val->DueDate);
                unset($val->InvoiceDate);
                unset($val->UserID);
                unset($val->Name);
                unset($val->BankDetail);
                unset($val->Address);
                unset($val->CustomerCompanyID);
                unset($val->InvoiceTotal);
                $invoice['InvoiceItems'][] = $val;
            }
            //echo "<pre>";print_r($invoice);echo"</pre>";die;
            return $invoice;
        } else {
            return FALSE;
        }
    }
	
	
	/*
      For showing log pop-up detail for purchase
     */

    public function logPurchaseDetail($Id = NULL) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = 'SELECT i.InvoiceID,i.Tax,i.InvoiceNumber,i.FlatRate,i.NetSales,i.CustomerCompanyID,i.DueDate,i.InvoiceDate,i.UserID,';
        $query .= 'i.BankDetail,CONCAT(u.FirstName," ",u.LastName) AS Name,i.InvoiceTotal,';
        $query .= 'u.Address,it.ID AS ItemID,it.Description,it.UnitPrice,it.Quantity,it.Tax';
        $query .= ' FROM ' . $prefix . 'purchases AS i LEFT JOIN ' . $prefix . 'purchases_items AS it ON it.InvoiceID = i.InvoiceID';
        $query .= ' LEFT JOIN ' . $prefix . 'users AS u ON u.ID = i.UserID';
        $query .= ' WHERE i.InvoiceID=' . $Id; //.' AND i.CustomerCompanyID=1';
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            //echo "<pre>";print_r($query->result());echo"</pre>";DIE;
            $results = $query->result();
            /* Prepare invoice */
            $invoice = array(
                'InvoiceID' => $results[0]->InvoiceID,
                'InvoiceNumber' => $results[0]->InvoiceNumber,
                'DueDate' => date('d-m-Y', strtotime($results[0]->DueDate)),
                'InvoiceDate' => date('d-m-Y', strtotime($results[0]->InvoiceDate)),
                'UserID' => $results[0]->UserID,
                'Name' => $results[0]->Name,
                'BankDetail' => $results[0]->BankDetail,
                'CompanyID' => $results[0]->CustomerCompanyID,
                'Address' => $results[0]->Address,
                'InvoiceTotal' => $results[0]->InvoiceTotal
            );

            /* Check if bank details are needed */
            if ($invoice['BankDetail'] == 1) {
                $response = $this->db->get_where('banks', array('CompanyID' => $invoice['CompanyID']));
                if ($response->num_rows() > 0) {
                    $bank = $response->result();
                    $invoice['Bank_Details'] = get_object_vars($bank[0]);
                } else {
                    $invoice['Bank_Details'] = array();
                }
            } else {
                $invoice['Bank_Details'] = array();
            }

            /* Add compnay details */
            //echo $invoice['CompanyID'];die;
            $response = '';
            $this->db->select('Params,Name,RegistrationNo');
            $response = $this->db->get_where('company', array('CID' => $invoice['CompanyID']));
            if (count($response) > 0) {
                //echo $this->db->last_query();
                $company_detail = $response->result();
                //echo '<pre>';print_r($company_detail);echo '</pre>';
                $company_details = unserialize($company_detail[0]->Params);
                $company_details['Name'] = $company_detail[0]->Name;

                $invoice['Company_details'] = $company_details;
                $invoice['RegistrationNumber'] = $company_detail[0]->RegistrationNo;
            }

            foreach ($results as $key => $val) {
                unset($val->InvoiceID);
                unset($val->InvoiceNumber);
                unset($val->DueDate);
                unset($val->InvoiceDate);
                unset($val->UserID);
                unset($val->Name);
                unset($val->BankDetail);
                unset($val->Address);
                unset($val->CustomerCompanyID);
                unset($val->InvoiceTotal);
                $invoice['InvoiceItems'][] = $val;
            }
            //echo "<pre>";print_r($invoice);echo"</pre>";die;
            return $invoice;
        } else {
            return FALSE;
        }
    }
	

    /*
      For showing log pop-up detail for dividends
     */

    public function logDividendDetail($Id) {
        if ($Id > 0) {
            $this->db->select(" * ");
            $this->db->from("cashman_dividends");
            $this->db->where(" DID ='" . $Id . "'");
            $result = $this->db->get()->result_array();
            if ($result > 0) {
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
      For showing log pop-up detail for expense
     */

    public function logExpenseDetail($Id) {
        if ($Id > 0) {
            $this->db->select(" * ");
            $this->db->from("cashman_expenses ce");
            $this->db->join("cashman_expense_items cei", "ce.ID = cei.ExpenseID");
            $this->db->join("cashman_expenses_category cec", "cei.Category = cec.ID");
            $this->db->where(" ce.ID ='" . $Id . "'");
            $result = $this->db->get()->result_array();
            if ($result > 0) {
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
      For showing log pop-up detail for payrolls
    */

    public function logPayrollDetail($Id) {
        $prefix = $this->db->dbprefix;
        $query = "SELECT * FROM " . $prefix . "payee WHERE Randomnumber=$Id";
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        } else {
            if ($query->num_rows() > 0) {
                $result = $query->result();
                return $result;
            } else {
                return array();
            }
        }
    }

    public function logPayrollPaidDetail($Id) {
        $prefix = $this->db->dbprefix;
        $query = "SELECT * FROM " . $prefix . "payee WHERE ID=$Id";
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        } else {
            if ($query->num_rows() > 0) {
                $result = $query->result();
                return $result;
            } else {
                return array();
            }
        }
    }

    /*
      For showing log pop-up detail for salary
     */

    public function getSalaryDetails($Id) {
        if ($Id > 0) {
            $this->db->select(" * ");
            $this->db->from("cashman_salary");
            $this->db->where("FileID ='" . $Id . "'");
            $result = $this->db->get()->result_array();			
            if ($result > 0) {
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getSalaryPaidDetails($Id) {
        if ($Id > 0) {
            $this->db->select(" * ");
            $this->db->from("cashman_salary");
            $this->db->where("ID='" . $Id . "'");
            $result = $this->db->get()->result_array();
            if ($result > 0) {
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
      For showing log pop-up detail for journals
     */

    public function logJournalDetail($Id) {
        if ($Id > 0) {
            $this->db->select(" * ");
            $this->db->from("cashman_journal_entries");
            $this->db->where("ID ='" . $Id . "'");
            $result = $this->db->get()->result_array();
            if ($result > 0) {
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
      For showing log pop-up detail for notes
     */

    public function logNoteDetail($Id) {
        if ($Id > 0) {
            $this->db->select(" * ");
            $this->db->from("cashman_notes");
            $this->db->where("ID ='" . $Id . "'");
            $result = $this->db->get()->result_array();
            if ($result > 0) {
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getbankDetails($Id) {
        if (!empty($Id)) {
            $prefix = $this->db->dbprefix;
            $user = $this->session->userdata('user');
            $order = $this->session->userdata('BankSortingOrder');
            if (isset($Id) && !empty($Id)) {
                $where = 'where s.Randomnumber=' . $Id;
            }
            $search = $this->session->userdata('BankSearch');
            $query = "SELECT s.ID,s.TransactionDate,s.Description,s.Type,s.Category,s.Balance,s.AssociatedWith,";
            $query .= "s.StatementType,s.MoneyOut,s.MoneyIn,s.CheckBalance";
            $query .= " FROM " . $prefix . "bank_statements AS s ";
            $query .= $where;
            $qr = $this->db->query($query);
            // die($this->db->last_query());
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
            if ($qr->num_rows() > 0) {
                $result = $qr->result();
                //echo '<pre>';print_r($query->result());echo '</pre>';die('');
                return $result;
            } else {
                return array();
            }
        }
    }

    public function getInvoicenumberlog($Id = NULL) {
        if (!empty($Id)) {
            $prefix = $this->db->dbprefix;
            $query = "SELECT InvoiceNumber FROM " . $prefix . "invoices WHERE InvoiceID=$Id";
            $query = $this->db->query($query);
            $data = $query->result_array();
            if (!empty($data)) {
                return $data[0]['InvoiceNumber'];
            } else {
                return '--';
            }
        }
    }

    public function getExpensenumberlog($Id = NULL) {
        if (!empty($Id)) {
            $prefix = $this->db->dbprefix;
            $query = "SELECT ExpenseNumber FROM " . $prefix . "expenses WHERE ID=$Id";
            $query = $this->db->query($query);
            $data = $query->result_array();
            if (!empty($data)) {
                return $data[0]['ExpenseNumber'];
            } else {
                return '--';
            }
        }
    }
	public function getPurchasenumberlog($Id = NULL) {
        if (!empty($Id)) {
            $prefix = $this->db->dbprefix;
            $query = "SELECT InvoiceNumber FROM " . $prefix . "purchases WHERE InvoiceID=$Id";
            $query = $this->db->query($query);
            $data = $query->result_array();
            if (!empty($data)) {
                return $data[0]['InvoiceNumber'];
            } else {
                return '--';
            }
        }
    }

    public function getDividendnumberlog($Id = NULL) {
        if (!empty($Id)) {
             $prefix = $this->db->dbprefix;
            $query = "SELECT VoucherNumber FROM " . $prefix . "dividends WHERE DID=$Id";
            $query = $this->db->query($query);
            $data = $query->result_array();
            if (!empty($data)) {
                return $data[0]['VoucherNumber'];
            } else {
                return '--';
            }
        }
    }

}

?>
