<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchases extends CI_Model {

    public function Purchases() {
        parent::__construct();
    }

    public function getsupplierList() { 
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $user = $user['UserID'];
        $query = $this->db->query('SELECT id,CONCAT(first_name," ",last_name) AS Name FROM ' . $prefix . 'suppliers WHERE status=1 and clientId=' . $user);
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $data = array('0' => '-- Select Supplier --');
            foreach ($result as $key => $val) {
                $data[$val->id] = $val->Name;
            }
            return $data;
        } else {
            return array('0' => 'No Supplier');
        }
    }

    /**
     *  This function will Create/Save the invoice.
     *  Return Value: boolean.
     */
    public function createInvoice($rawdata) {
        $prefix = $this->db->dbprefix;
        //echo '<pre>';print_r($rawdata);echo '</pre>';die;
        $user = $this->session->userdata('user');
        //echo '<pre>';print_r($user);echo '</pre>';die;
        $companyID = $user['CompanyID'];
        /* Fetch company Address */
        $this->db->select('Params');
        $this->db->where('CID', $companyID);
        $query = $this->db->get($prefix . "company");
        $address = $query->result();
        $addressParams = $address[0]->Params;
		
        $addedon = date('Y-m-d');
        $addedby = $user['UserID'];
        /**
         *  Check the task committed by the user
         *  Save as draft : Value = 1
         *  Create Invoice: Value = 2
         *  Paid Invoice  : Value = 3
         */
        $task_status = array(
            'save' => 1,
            'create' => 2,
            'paid' => 3,
            'copy' => 1,
            'createInvoice' => 1
        );
        if (empty($rawdata)) {
            return FALSE;
        }
		
        /* This bloc to check if Invoice is created using bank statement */
        if (!empty($rawdata['bank_statement_id'])) { 
            $bs_id = $this->encrypt->decode($_POST['bank_statement_id']);
            if ($rawdata['task'] == 'create') {
                $status = 3;
            } else {
                $status = $task_status[$rawdata['task']];
            }

            /* Check if invoice is already created for this statement */
            $this->db->select('InvoiceID');
            $query = $this->db->get_where('purchases', array('BankStatement' => $bs_id));
            $response = $query->result_array();
            if (count($response) > 0) {
                return FALSE;
            }
        } else {
            $bs_id = '';
            $status = $task_status[$rawdata['task']];
        }
        /* Check if Created by accountant while accessing the client account */
        $accountant_access = clientAccess(); 
        //echo 'Status : '.$rawdata['task'];die;
        /* Prepare the invoice item record */
        $item_query = array();
        $subTotal = 0;
        $totalVat = 0;
        $invoiceTotal = 0;
        $taxAmount = 0;
        $gbp = 0;
        for ($i = 0; $i < count($rawdata['description']); $i++) {
            $description = $rawdata['description'][$i];
			$Category = $rawdata['Category'][$i];
            $quantity = $rawdata['quantity'][$i];
            $unitprice = (isset($rawdata['unitprice'][$i]) ? $rawdata['unitprice'][$i] : 0);
            $vatpercent = $rawdata['vat'][$i];
            $gbp = (($quantity * $unitprice * $vatpercent) / 100) + ($quantity * $unitprice);
            $subTotal += $quantity * $unitprice;
            $taxAmount += ($quantity * $unitprice * $vatpercent) / 100;
            $invoiceTotal += $gbp;

            $item_query[] = array(
                'InvoiceID' => '',
                'Description' => clean($description),
				'Category' => clean($Category),
                'UnitPrice' => (float) $unitprice,
                'Quantity' => (float) $quantity,
                'Tax' => (float) $rawdata['vat'][$i],
                'TotalAmount' => (float) $quantity * $unitprice,
                'TaxType' => 'VAT',
                'TaxAmount' => (float) ($quantity * $unitprice * $vatpercent) / 100,
                'AddedBy' => $addedby,
                'AddedOn' => $addedon,
                'Status' => 0,
                'AccountantAccess' => $accountant_access
            );
        }

        /* Check if selecting existing user or creating new user with invoice */
        if (!empty($rawdata['customer'])) { 
            $customerID = $rawdata['customer'];
            $invoiceNo = substr(strtoupper(getSuppliername($rawdata['customer'])), 0, 3);
        } else {
			
			#count supplier for current user
			if(getAllSupplier($user['UserID']) == '')
				$countSupplier = 0;
			else
				$countSupplier = count(getAllSupplier($user['UserID']));
			
			$TB_Category= "SUPPLIER_".($countSupplier+1);
			
			#count all supplier category 
			$countSupplierCategory = count(getAllSupplierCategory());
			
			#Save new supplier category in 
			if($countSupplier == $countSupplierCategory){
				$tbData = array(
								'title' => 'Supplier '.($countSupplier+1),
								'catKey' => 'SUPPLIER_'.($countSupplier+1),
								'type' => 'B/S',
								'parent' => 129,
								'AnalysisLedgerParent' => 242,
								'status' => 1
							);
				$this->load->model('clients/supplier');			
				$saveSupplierCategory = $this->supplier->saveSupplierCategory($tbData);
			}
			
			
            $tempData = array(
                'first_name' => safe($rawdata['customerName']),
				'TB_Category' => $TB_Category,
                'address1' => $rawdata['customerAddress'],
                'create_date' => $addedon,
                'clientId' => $addedby,
                'Status' => 1,
                'AccountantAccess' => $accountant_access
            );	
			
            $this->db->insert('suppliers', $tempData);
            if ($this->db->affected_rows() > 0) {
                $customerID = $this->db->insert_id();
            } else {
                return FALSE;
            }
            $invoiceNo = substr(strtoupper(safe($rawdata['customerName'])), 0, 3);
        }		
        /* Create the invoice first */
        $total_amount = (isset($_POST['invoice_type']) && $_POST['invoice_type'] == 'CRN') ? ('-' . ($invoiceTotal)) : ($invoiceTotal);

        $FlatRate = '';
        $NetSales = '';
		
        if (!empty($rawdata['bank_statement_id'])) {
            if ($rawdata['ajax_add'] == 'bank_ajax_add') {
                $this->load->model('clients/bank');
                $bs_id = $this->encrypt->decode($rawdata['bank_statement_id']);
                $statement_data = $this->bank->getStatements($bs_id);
                $paidOn = mDate($statement_data['TransactionDate']);

                /* update invoice flat rate and net sales */
                $vat_listing = checkvatifExist();
                if (empty($vat_listing->Type)) {
                    $FlatRate = '';
                    $NetSales = $total_amount;
                } else if ($vat_listing->Type == 'stand') {
                    $FlatRate = '';
                    $NetSales = $total_amount;
                } else {
                    $user = $this->session->userdata('user');
                    if ($total_amount != 0 && $paidOn != '') {
                        if (strtotime($paidOn) <= strtotime($user['EndDate'])) {
                            $flateRate = ($total_amount * $user['PercentRateAfterEndDate']) / 100;
                        } else {
                            $flateRate = ($total_amount * $user['PercentRate']) / 100;
                        }
                        $FlatRate = $flateRate;
                    } else {
                        $FlatRate = '0.00';
                    }
                    if ($total_amount != 0) {
                        $NetSales = (($total_amount - $FlatRate));
                    } else {
                        $NetSales = '0.00';
                    }
                }


                /* End invoice flat rate and net sales */
            } elseif ($rawdata['ajax_add'] == 'ajax_add') {
                $paidOn = mDate($_POST['bank_paid_date']);
            }
        } else {
            $paidOn = "";
        }
        
        if($this->input->post('Status'))
        {
            $status = $this->input->post('Status');
        }
        
        if($this->input->post('PaidOn'))
        {
            $paidOn = date('Y-m-d', strtotime($this->input->post('PaidOn')));
        }
	
		$supilerInvoiceNumber = $rawdata['supilerInvoiceNumber'];
		
        $data = array(
			'SupilerInvoiceNumber' => $supilerInvoiceNumber,
            'CustomerCompanyID' => $companyID,
            'UserID' => $customerID,
            'SubTotal' => $subTotal,
            'Tax' => $taxAmount,
            'InvoiceTotal' => $total_amount,
            'InvoiceStatus' => 0,
            'DueDate' => (empty($rawdata['invoiceDate'])) ? "" : mDate($rawdata['invoiceDate']),
            'InvoiceDate' => mDate($rawdata['InvoiceDate']),
            'BankDetail' => (isset($rawdata['bankdetail'])) ? 1 : 0,
            'AddedBy' => $addedby,
            'AddedOn' => $addedon,
            'Status' => $status,
            'AccountantAccess' => $accountant_access,
            'BankStatement' => $bs_id,
            'PaidOn' => $paidOn,
            'FlatRate' => $FlatRate,
            'NetSales' => $NetSales,
            'Params' => $addressParams,
        );		
        
        $query = $this->db->insert('purchases', $data);
		
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {

            log_message('error', $db_error['message']);
            return FALSE;
        }
		
        if ($this->db->affected_rows() > 0) {
	    $insertid = $this->db->insert_id();
            $user = $this->session->userdata('user');
            $data = array(
                'VatType' => $user['VAT_TYPE'],
                'PercentRateAfterEndDate' => $user['PercentRateAfterEndDate'],
                'PercentRate' => $user['PercentRate'],
                'EndDate' => $user['EndDate']
            );
            $this->db->where('InvoiceID', $insertid);
            $this->db->update('purchases', $data);
        } else {
            return FALSE;
        }

        if (isset($_POST['invoice_type']) && $_POST['invoice_type'] == 'CRN') {
            $invoiceNo = 'CRN';
        }

        if ($rawdata['task'] == 'create') {
            update_logs('PURCHASE', 'USER_CREATED_PURCHASE', 'CREATE', "", $insertid);
            $invoiceNo .= '-' . date('Y') . date('m') . date('d') . '-' . $insertid;
        } else {

            if (isset($_POST['type']) && $_POST['type'] == 'CRN') {
                update_logs('PURCHASE', 'USER_SAVED_PURCHASE_CRN_SAVED_AS_DRAFT', 'SAVED', "", $insertid);
                $invoiceNo = 'CRN-SAVED AS DRAFT';
            } else {
                update_logs('PURCHASE', 'USER_SAVED_PURCHASE_IN_DARFT', 'SAVED', "", $insertid);
                $invoiceNo = 'SAVED AS DRAFT';
            }
        }

        /* Update the invoice number of the newly created invoice */
        $this->db->update('purchases', array('InvoiceNumber' => $invoiceNo), array('InvoiceID' => $insertid));

        /* Add invoice items to cashman_purchases_items */
        foreach ($item_query as $key => $val) {
            $val['InvoiceID'] = $insertid;
            $item_query[$key] = $val;
        }

        $query = $this->db->insert_batch('purchases_items', $item_query);
        if ($this->db->affected_rows() > 0) {
            $item_insertid = $this->db->insert_id();
        } else {
            return FALSE;
        }

        /* This bloc to check if dividend is created using bank statement */
        if (!empty($rawdata['bank_statement_id'])) {
            $associated_with = $insertid;
            $invoice_id = $insertid;
			
            update_trial_balance("purchase", $invoice_id);
            if ($rawdata['ajax_add'] == 'bank_ajax_add') {

                $associated_with = explode('-', $associated_with);
                $associated_with = end($associated_with);
                $data = array(
                    'AssociatedWith' => $associated_with,
                    'StatementType' => 'I'
                );
                $this->load->model('clients/bank');
                $response = $this->bank->update_statements($data, $bs_id);
                if (!$response) {
                    return FALSE;
                }
            } elseif ($rawdata['ajax_add'] == 'ajax_add') {
                $temp_record = $this->session->userdata('temp_statement_record');
                $temp_statement_record = json_encode($temp_record);
                if (!is_array($temp_statement_record)) {
                    $temp_statement_record = array();
                }
                $temp_statement_record[$bs_id] = array(
                    'ItemID' => $invoice_id,
                    'ItemType' => 'I'
                );
                $this->session->set_userdata('temp_statement_record', json_encode($temp_statement_record));
            } else {
                return true;
            }
			//update_trial_balance("invoice",  $invoice_id, "", "", "", $status);
        }else{
			update_trial_balance("purchase",  $insertid, "", "", "", $status);
		}


        /* If no error occurred return true */
        return $invoiceNo;
    }

    /*
     *  This function will return the list of all purchases generated by the client.
     */

    public function getInvoiceList($limit = INVOICE_PAGINATION_LIMIT, $start = 0) {
        $prefix = $this->db->dbprefix;
        //echo 'Operation : '.$operation.'<br/>';
        $order = $this->session->userdata('PurchaseSortingOrder');
        if (isset($order) && !empty($order)) {
            $orderby = " ORDER BY " . $order . ' LIMIT ' . $start . ',' . $limit;
        } else {
            $orderby = " ORDER BY i.InvoiceID DESC LIMIT " . $start . "," . $limit;
        }
        $search = $this->session->userdata('PurchaseSearch');
		
        $where = $this->searchQuery($start, $limit);
        $query = "SELECT i.InvoiceID,i.InvoiceNumber,i.PaidOn,i.Tax,i.InvoiceTotal,i.SubTotal,i.AddedOn,i.InvoiceDate,i.Status,i.DueDate,i.FlatRate,
		i.NetSales,i.VatType,i.PercentRateAfterEndDate,i.PercentRate,i.EndDate,";
        $query .= " CONCAT(c.first_name,' ',c.last_name) AS Name FROM " . $prefix . "purchases AS i";
        $query .= " LEFT JOIN " . $prefix . "suppliers AS c ON c.ID=i.UserID";
       $query .= $where;
		//echo $query;
		
        if (!empty($search)) {
            $search_query = $this->db->query($query);
			//echo '<pre>'; print_r($search_query); echo '</pre>';
			//die('purchase 381');
				//$rr = $search_query->count(num_rows());
				//echo '<pre>'; print_r($rr); echo '</pre>';
				
				//die('purchase 381');
           // return $this->session->set_userdata('PurchaseSearchRecords', $search_query->num_rows());
			 return $this->session->set_userdata('PurchaseSearchRecords', $search_query->num_rows());
			//die('purchase 389');
        }
		
        $query .=' ' . $orderby;
        $query = $this->db->query($query);
        //echo $this->db->last_query();
        //die;
        if ($query->num_rows() > 0) {
            $record = $query->result();
            return $record;
        } else {
            return array();
        }
    }

    public function searchQuery($start, $limit) {
        $prefix = $this->db->dbprefix;
        $search = $this->session->userdata('PurchaseSearch');

        $user = $this->session->userdata('user');
        $userID = $user['UserID'];

        /*
         *  First check if search operation is performed or not.
         *  Prepare where clause for the query according to the search criteria.
         */

        $where = '';
        if ($search != NULL) {
            if (!is_array($search)) {
                $where = '';
            } else {
                //echo "<pre>";print_r($search);echo '</pre>';
                $search = array_filter($search);

                if (count($search) <= 0) {
                    $where = '';
                } else {
                    //$where = 'WHERE ';
                    foreach ($search as $key => $val) {
                        if ($key == 'CustomerName') {
                            $where[] .= 'CONCAT(u.FirstName," ",u.LastName) LIKE "%' . $val . '%"';
                        } elseif ($key == 'Status') {
                            $where[] .= 'i.' . $key . '=' . $val;
                        } elseif ($key == 'sCreatedStart') {
                            $where[] .= 'i.AddedOn >= "' . date('Y-m-d', strtotime($val)) . '"';
                        } elseif ($key == 'sCreatedEnd') {
                            $where[] .= 'i.AddedOn <= "' . date('Y-m-d', strtotime($val)) . '"';
                        } elseif ($key == 'sDueStart') {
                            $where[] .= 'i.DueDate >= "' . date('Y-m-d', strtotime($val)) . '"';
                        } elseif ($key == 'sDueEnd') {
                            $where[] .= 'i.DueDate <= "' . date('Y-m-d', strtotime($val)) . '"';
                        } elseif ($key == 'invoice_financialyear') {
                            $year = company_year($val);
                            $temp = 'i.PaidOn >= "' . date('Y-m-d', strtotime($year['start_date'])) . '"';
                            $temp .= ' AND i.PaidOn <= "' . date('Y-m-d', strtotime($year['end_date'])) . '"';
                            $where[] .= $temp;
                        } else {
                            $where[] .= 'i.' . $key . ' LIKE "%' . $val . '%"';
                        }
                    }
                }
            }
        } else {
            $where = '';
        }
        if ($where == '') {
            $where = ' WHERE ' . "i.AddedBy=" . $userID;
        } else {
            $where = implode(' AND ', $where);
            $where = ' WHERE ' . $where . ' AND i.AddedBy=' . $userID;
        }
        return $where;
    }

    /*
     *  This function return the total records in the purchases table.
     *  Useful for pagination.
     */

    public function totalInvoices() {
        $prefix = $this->db->dbprefix;
        $search = $this->session->userdata('PurchaseSearch');
        $user = $this->session->userdata('user');
        $user = $user['UserID'];
        $totalRecord = $this->session->userdata('PurchaseSearchRecords');
        if (isset($totalRecord) && !empty($totalRecord)) {
            return $totalRecord;
        }
        $this->db->where('AddedBy', $user);
        $records = $this->db->count_all_results('purchases');
        if ($records > 0) {
            return $records;
        } else {
            return 0;
        }
    }

    public function getInvoiceItem($item) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $queryy = 'SELECT i.InvoiceID,i.Tax,i.InvoiceNumber,i.SupilerInvoiceNumber,i.FlatRate,i.NetSales,it.Category,i.CustomerCompanyID,i.DueDate,i.InvoiceDate,i.PaidOn,i.Status,i.UserID,i.Params,';
        $queryy .= 'i.BankDetail,CONCAT(c.first_name," ",c.last_name) AS Name,i.InvoiceTotal,';
        $queryy .= 'c.address1,c.address2,c.address3,it.ID AS ItemID,it.Description,it.UnitPrice,it.Quantity,it.Tax';
        $queryy .= ' FROM ' . $prefix . 'purchases AS i LEFT JOIN ' . $prefix . 'purchases_items AS it ON it.InvoiceID = i.InvoiceID';
        $queryy .= ' LEFT JOIN ' . $prefix . 'suppliers AS c ON c.id = i.UserID';
        $queryy .= ' WHERE i.InvoiceID=' . $item['InvoiceID']; //.' AND i.CustomerCompanyID=1';
        $query = $this->db->query($queryy); 
		//echo $this->db->last_query(); 
        if ($query->num_rows() > 0) {            
            $results = $query->result();
            /* Prepare invoice */
            $invoice = array(
                'InvoiceID' => $results[0]->InvoiceID,
                'InvoiceNumber' => $results[0]->InvoiceNumber,
				'SupilerInvoiceNumber' => $results[0]->SupilerInvoiceNumber,
                'DueDate' => date('d-m-Y', strtotime($results[0]->DueDate)),
                'InvoiceDate' => date('d-m-Y', strtotime($results[0]->InvoiceDate)),
                'PaidOn' => date('d-m-Y', strtotime($results[0]->PaidOn)),
                //'DueDate' => $results[0]->DueDate,
                //'InvoiceDate' => $results[0]->InvoiceDate,
                'UserID' => $results[0]->UserID,
                'Name' => $results[0]->Name,
                'BankDetail' => $results[0]->BankDetail,
                'CompanyID' => $results[0]->CustomerCompanyID,
                'Address' => $results[0]->address1 . ' ' . $results[0]->address2 . ' ' . $results[0]->address3,
                'InvoiceTotal' => $results[0]->InvoiceTotal,
                'Status' => $results[0]->Status,
                'PaidDate' => $results[0]->PaidOn
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
                if (!empty($results[0]->Params)) {
                    $cmpAddress = $results[0]->Params;
                } else {
                    $cmpAddress = $company_detail[0]->Params;
                }
                $company_details = unserialize($cmpAddress);
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
     *  This function changes the invoice status to paid/delete/generate pdf
     */

    public function performAction($task, $paidDate = NULL) {		
		
        $prefix = $this->db->dbprefix;
        /* Check if Created by accountant while accessing the client account */
        $accountant_access = clientAccess();
        if (!is_numeric($task[2])) {
            $task[2] = 0;
        }
        $user = $this->session->userdata('user');
        $data = array(
            'Status' => '3',
            'PaidOn' => date('Y-m-d', strtotime($paidDate)),
            'AccountantAccess' => $accountant_access
        );
        $action = array(
            '0' => 'ACTION_PAID',
            '1' => 'ACTION_COPY',
            '2' => 'ACTION_DELETE',
            '3' => 'ACTION_PDF'
        );
        $id = $task[1];
        $this->db->select('InvoiceNumber');
        $query = $this->db->get_where('purchases', array('InvoiceID' => $id));
        $query = $query->result();
        $no = $query[0]->InvoiceNumber;

        if ($task[0] == $action[0]) {
            //$this->db->where();
            $where = "InvoiceID=" . $id;
            /* Add this entry in the system entries table */
            systemEntries(array('index' => 'InvoiceID', 'value' => $id));
            $query = $this->db->update_string('purchases', $data, $where);
            $this->db->query($query);
            if ($this->db->affected_rows() <= 0) {
                // if(false)
                return FALSE;
            } else {
                /* Added for P/L & B/S entries */
                $vat_listing = checkvatifExist();
                $user = $this->session->userdata('user');

                $this->db->select('VatType');
                $queryVat = $this->db->get_where('purchases', array('InvoiceID' => $id));
                $queryvatresult = $queryVat->result();

                if (empty($queryvatresult[0]->VatType)) {
                    $this->db->select('InvoiceTotal');
                    $query1 = $this->db->get_where('purchases', array('InvoiceID' => $id));
                    $query2 = $query1->result();
                    $InvoiceTotal = $query2[0]->InvoiceTotal;
                    $var = array('FlatRate' => 0, 'NetSales' => $InvoiceTotal);
                    $this->db->where('InvoiceID', $id);
                    $this->db->update('purchases', $var);
                } else if ($queryvatresult[0]->VatType == 'stand') {
                    $this->db->select('InvoiceTotal,SubTotal');
                    $query1 = $this->db->get_where('purchases', array('InvoiceID' => $id));
                    $query2 = $query1->result();
                    $InvoiceTotal = $query2[0]->SubTotal;
                    $FlatRate = '0.00';
                    $var = array('VatType' => $user['VAT_TYPE'], 'PercentRateAfterEndDate' => '0.00', 'PercentRate' => $user['PercentRate'], 'EndDate' => $user['EndDate'], 'FlatRate' => trim($FlatRate), 'NetSales' => trim($InvoiceTotal));
                    $this->db->where('InvoiceID', $id);
                    $this->db->update('purchases', $var);
                } else {
                    $this->db->select('*');
                    $query2 = $this->db->get_where('purchases', array('InvoiceID' => $id));
                    $query3 = $query2->result();
                    $FlatRate = '';
                    $NetSales = '';
                    if ($query3[0]->InvoiceTotal != 0 && $query3[0]->PaidOn != '') {
                        if (strtotime($query3[0]->PaidOn) <= strtotime($user['EndDate'])) {
                            $flateRate = ($query3[0]->InvoiceTotal * $query3[0]->PercentRateAfterEndDate) / 100;
                        } else {
                            $flateRate = ($query3[0]->InvoiceTotal * $query3[0]->PercentRate) / 100;
                        }
                        $FlatRate = $flateRate;
                    } else {
                        $FlatRate = '0.00';
                    }
                    if ($query3[0]->InvoiceTotal != 0) {
                        $NetSales = (($query3[0]->InvoiceTotal - $FlatRate));
                    } else {
                        $NetSales = '0.00';
                    }
                    $var = array('VatType' => $user['VAT_TYPE'], 'PercentRateAfterEndDate' => $user['PercentRateAfterEndDate'], 'PercentRate' => $user['PercentRate'], 'EndDate' => $user['EndDate'], 'FlatRate' => trim($FlatRate), 'NetSales' => trim($NetSales));
                    $this->db->where('InvoiceID', $id);
                    $this->db->update('purchases', $var);
                }
                update_trial_balance("purchase", $id);
                update_logs('PURCHASE', 'USER_MARKED_INVOICE_PAID', 'PAID', "", $id);
            }
            /* Added for P/L & B/S entries */
        } elseif ($task[0] == $action[1]) {
            $query = $this->db->get_where('purchases', array('InvoiceID' => $id));
            if ($query->num_rows() > 0) {
                $invoiceNumber = 'SAVE AS DRAFT';
                unset($results[0]->InvoiceID);
                $results[0]->InvoiceNumber = $invoiceNumber;
                $results[0]->AddedBy = $user['UserID'];
                $results[0]->AddedOn = date('Y-m-d');

                /* Add the copied invoice to the table */
                $query = $this->db->insert('purchases', $results[0]);

                if ($this->db->affected_rows() > 0) {
                    $invoiceID = $this->db->insert_id();
                    /* Add duplicate invoice item to the invoice item table */
                    //echo 'ID : '.$id;
                    $query = $this->db->get_where('purchases_items', array('InvoiceID' => $id));
                    $items = $query->result_array();
                    //echo '<pre>';print_r($items);echo '</pre>';die;
                    foreach ($items as $key => $val) {
                        unset($items[$key]['ID']);
                        $items[$key]['InvoiceID'] = $invoiceID;
                        $items[$key]['AddedOn'] = date('Y-m-d');
                        $items[$key]['AddedBy'] = $user['UserID'];
                    }
                    //echo '<pre>';print_r($items);echo '</pre>';die;
                    $query = $this->db->insert_batch('purchases_items', $items);
                    if ($this->db->affected_rows() <= 0) {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } elseif ($task[0] == $action[2]) {
            //die('ID : '.$task[2]);
            $invData = $this->getInvoiceDetails(array("InvoiceID" => (int) $id));

            $this->db->delete('purchases', array('InvoiceID' => $id));
            update_logs('PURCHASE', 'USER_DELETED_PURCHASE', 'DELETE', "", "$task[1]");
            if ($invData["Status"] == "3") {
                update_trial_balance("purchase", $invData, "", "", "DELETE");
            }

            /* Update the bank statements */
            $bank_data = array(
                'AssociatedWith' => 0
            );
            if (!empty($invData['BankStatement'])) {
                $this->db->where('ID', $invData['BankStatement']);
                $this->db->update('bank_statements', $bank_data);
            }

            /* Update ledger table */
            $this->db->delete('tb_details', array('itemId' => $id, 'source' => 'PURCHASE'));
        }

        if ($task[3] == 'ajaxcall') {
            return $no;
        } else {
            return TRUE;
        }
    }

    /**
     *  Function to update the  Invoice
     */
    public function updateInvoice($rawdata) {
        $prefix = $this->db->dbprefix;
        //echo '<pre>';print_r($rawdata);echo '</pre>';die;

        $user = $this->session->userdata('user');
        $companyID = $user['CompanyID'];
        if ($rawdata['invoice_type'] == 'CRN') {
            $invoiceNo = 'CRN';
        } else {
            $invoiceNo = substr(strtoupper(safe($rawdata['customerName'])), 0, 3);
        }

        $id = $this->encrypt->decode($rawdata['InvoiceID']);

        $id = explode('/', $id);
        //echo 'Invoice ID : ';PRINT_R($id);
        $id = $id[0];

        /* Check if Created by accountant while accessing the client account */
        $accountant_access = clientAccess();

        /* Check the status of the invoice weather the invoice is PAID/CREATED */

        if ($rawdata['task'] == 'createInvoice') {
            $rawdata['task'] = 'create';
            $response = $this->createInvoice($rawdata);
            update_logs('INVOICE', 'USER_DRAFT_PURCHASE_CREATED', 'UPDATE', "", $id);
            if ($response) {
                return $response;
            } else {
                return FALSE;
            }
        }

        $subTotal = 0;
        $totalVat = 0;
        $invoiceTotal = 0;

        $user = $this->session->userdata('user');
        $addedBy = $user['UserID'];
        $addedOn = date('Y-m-d');
        if (!is_numeric($id)) {
            $id = 0;
        }

        /* Insert/Update invoice item depending on the fields */
        if (isset($rawdata['ItemID'])) {
            foreach ($rawdata['ItemID'] as $key => $val) {
                $rawdata['ItemID'][$key] = $this->encrypt->decode($val);
            }
            //echo '<pre>';print_r($rawdata['eItemID']);echo '</pre>';

            $query = 'SELECT ID FROM ' . $prefix . 'purchases_items WHERE ID IN ("' . implode('","', $rawdata['ItemID']) . '") AND InvoiceID=' . $id;
            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                $presentID = $query->result();
            }
        } else {
            $this->db->where('InvoiceID', $id);
            $this->db->delete('purchases_items');
            if ($this->db->affected_rows() > 0) {
                $presentID = array();
            }
        }

        //echo 'Existing IDS : <pre>';print_r($presentID);die;
        $tax_amount = 0;
        for ($i = 0; $i < count($rawdata['description']); $i++) {
            $description = safe($rawdata['description'][$i]);
            $unitprice = safe($rawdata['unitprice'][$i]);
            $quantity = safe($rawdata['quantity'][$i]);
            $vat = safe($rawdata['vat'][$i]);
            $subTotal += $quantity * $unitprice;
            $totalVat += ($quantity * $unitprice * $vat) / 100;
            $invoiceTotal += $quantity * $unitprice + ($quantity * $unitprice * $vat) / 100;
            $tax_amount += ($quantity * $unitprice * $vat) / 100;
            $item = array(
                'InvoiceID' => $id,
                'Description' => $description,
                'UnitPrice' => (float) $unitprice,
                'Quantity' => (float) $quantity,
                'TotalAmount' => (float) ($quantity * $unitprice),
                'Tax' => (float) $vat,
                'TaxType' => 'VAT',
                'TaxAmount' => (float) $tax_amount,
                'AddedBy' => $addedBy,
                'AddedOn' => $addedOn,
                'Status' => 0,
                'AccountantAccess' => $accountant_access
            );
            if (($i + 1) <= count($presentID)) {
                $this->db->update('purchases_items', $item, array('ID' => $presentID[$i]->ID));
                if ($this->db->affected_rows() < 0) {
                    return FALSE;
                }
            } else {
                $this->db->insert('purchases_items', $item);
                if ($this->db->affected_rows() < 0) {
                    return FALSE;
                }
            }
        }
        $invoiceNo .= '-' . date('Y') . date('m') . date('d') . '-' . $id;
        if ($rawdata['task'] == 'update') {
            update_logs('INVOICE', 'USER_UPDATED_PURCHASE', 'UPDATE', "", $id);
            $invoiceNo = 'SAVED AS DRAFT';
        } else {
            update_logs('INVOICE', 'USER_DRAFT_PURCHASE_CREATED', 'UPDATE', "", $id);
        }
        //echo $rawdata['task'].$invoiceNo;
                
        if($rawdata['task'] == 'create' || $rawdata['task'] == 'createInvoice' || $rawdata['task'] == 'uCreateInvoice')
        {
            if($this->input->post('Status'))
            {
                $status = $this->input->post('Status');
            }
            else{
                $status = 2;
            }
        }
        else{
         $status = 1;
        }
        
        if($this->input->post('PaidOn')!="")
        {
            $paidOn = date('Y-m-d', strtotime($this->input->post('PaidOn')));
        }
        else{
            $paidOn = "";
        }
        
        $total_amount = (isset($rawdata['invoice_type']) && $rawdata['invoice_type'] == 'CRN') ? ('-' . ($invoiceTotal)) : ($invoiceTotal);
        /* Update data in invoice table */
        $invoiceData = array(
            'UserID' => $rawdata['customer'],
            'InvoiceNumber' => $invoiceNo,
            'CustomerCompanyID' => $companyID,
            'SubTotal' => $subTotal,
            'Tax' => $tax_amount,
            'InvoiceTotal' => $total_amount,
            'InvoiceStatus' => 0,
            'DueDate' => date('Y-m-d', strtotime($rawdata['invoiceDate'])),
            'PaidOn'  => $paidOn,  
            'InvoiceDate' => mDate($rawdata['InvoiceDate']),
            'BankDetail' => (isset($rawdata['bankdetail'])) ? 1 : 0,
            'AddedBy' => $addedBy,
            'AddedOn' => $addedOn,
            'Status' => $status,
            'AccountantAccess' => $accountant_access
        );
        $this->db->update('purchases', $invoiceData, array('InvoiceID' => $id));
        if ($this->db->affected_rows() < 0) {
            return FALSE;
        }
        else{
            update_trial_balance("purchase", $id);
        }

        return $invoiceNo;
    }

    function getUserDetail($id) {
        $prefix = $this->db->dbprefix;
        $query = 'SELECT CONCAT(FirstName," ",LastName) AS Name,Address FROM ' . $prefix . 'users WHERE ID=' . $id;
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function getcustomerDetail($id) {
        $prefix = $this->db->dbprefix;
        $query = 'SELECT CONCAT(first_name," ",last_name) AS Name, CONCAT(address1 , " " , address2 , " " , address3) as Address  FROM ' . $prefix . 'suppliers WHERE ID=' . $id;
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }

    function getUserID($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('UserID');
        $query = $this->db->get_where('purchases', array('InvoiceID' => $id));
        if ($query->num_rows() > 0) {
            $id = $this->db->result();
            $id = $id[0]->UserID;
            return $id;
        } else {
            return FALSE;
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

    function getVatfaltType($clientId = NULL) {
        $prefix = $this->db->dbprefix;
        $query = $this->db->get_where('tax_rates', array('ClientID' => $clientId));
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data[0];
        } else {
            return tableColumns($prefix . 'tax_rates');
        }
    }

    function getCountry($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('Country');
        $query = $this->db->get_where('users', array('ID' => $id));
        if ($query->num_rows() > 0) {
            $response = $query->result();
            return $response[0]->Country;
        } else {
            return FALSE;
        }
    }

    /* This function to be run manually to add the invoice,dividends,expenses entries in the system table */
    /* This function code will be removed after the product has been developed */

    function systemTable() {
        $prefix = $this->db->dbprefix;
        //$user = $this->session->userdata('user');

        $this->db->select('InvoiceID,AddedBy');
        $query = $this->db->get_where('purchases', array('Status' => '3'));
        $result = $query->result_array();
        //echo '<pre>';print_r($result);
        $this->db->insert_batch('system_entries', $result);



        $this->db->select('ExpenseID,AddedBy');
        $query = $this->db->get_where('expenses', array('Status' => '2'));
        $result = $query->result_array();
        //echo '<pre>';print_r($result);
        $this->db->insert_batch('system_entries', $result);


        $this->db->select('DID,AddedBy');
        $query = $this->db->get_where('dividends', array('Status' => '2'));
        $result = $query->result_array();
        //echo '<pre>';print_r($result);
        $this->db->insert_batch('system_entries', $result);

        die;
    }

    function getFileInfo($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('ID,FName,AssociatedWith');
        $query = $this->db->get_where('files', array('ID' => $id));
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result[0];
        } else {
            return array();
        }
    }

    public function getAllInvoices() {
        $prefix = $this->db->dbprefix;
        //echo 'Operation : '.$operation.'<br/>';

        $VATYear = $this->session->userdata('VATYear');
        $user = $this->session->userdata('user');
        $userID = $user['UserID'];

        $vatQuarters = getVatQuarters();
        if ($vatQuarters) {
            // echo "<pre>"; print_r( $VatQuarters ); die();
            $where[] = " " . $vatQuarters[1]["FIRST"] . " <= i.PaidOn <= " . $vatQuarters[4]["SECOND"] . " ";
        }
        $where[] = " i.AddedBy=" . $userID . " ";
        $where[] = " i.Status=3 ";

        $where = " WHERE " . implode(" AND ", $where);
        $query = "SELECT i.InvoiceID,i.InvoiceNumber,i.PaidOn,i.Tax,i.InvoiceTotal,i.SubTotal,i.AddedOn,i.Status,i.DueDate,i.VatType,i.PercentRateAfterEndDate,";
        $query .= " CONCAT(u.FirstName,' ',u.LastName) AS Name FROM " . $prefix . "purchases AS i";
        $query .= " LEFT JOIN " . $prefix . "users AS u ON u.ID=i.UserID";
        $query .= $where;

        $result = $this->db->query($query);

        if ($result->num_rows() > 0) {
            $records = $result->result();
            return $records;
        } else {
            return false;
        }
    }

    public function markVATPaid() {
        $prefix = $this->db->dbprefix;
        $q = $this->input->post("quarter");
        $VATYear = $this->input->post("VATYear");
        $paidDate = $this->input->post("paidDate");

        $return = array("error" => array(), "success" => false);

        if (!empty($q) && !empty($VATYear) && !empty($paidDate)) {

            $vatQuarters = getVatQuarters($VATYear);
            $purchases = array();
            $INVrecords = array();

            // echo "<pre>"; print_r( $vatQuarters ); die();
            if ($vatQuarters) {

                $q = $this->encrypt->decode($q);
                $total_sales = 0;
                $total_vat = 0;
                $expenseIds = "";
                $user = $this->session->userdata('user');
                $userID = $user['UserID'];
                $CompanyID = $user['CompanyID'];
                $vat_listing = $this->getVatType();

                if ($vat_listing->Type != 'flat') {
                    $expWhere[] = " " . $vatQuarters[$q]["FIRST"] . " <= e.PaidOn <= " . $vatQuarters[$q]["SECOND"] . " ";
                    $expWhere[] = " e.AddedBy=" . $userID . " ";
                    $expWhere[] = " e.Status=3 ";

                    $expWhere = " WHERE " . implode(" AND ", $expWhere);
                    $expQuery = "SELECT CONCAT(ce.FirstName,' ',ce.LastName) AS EmployeeName,e.ID,e.ExpenseNumber,e.ExpenseType";
                    $expQuery .= ",e.Month,e.Year,e.TotalMiles,e.FileID,e.Status,e.TotalAmount,e.TotalVATAmount,e.PaidOn FROM " . $prefix . "expenses AS e";
                    $expQuery .= " LEFT JOIN " . $prefix . "company_customers AS ce ON ce.ID = e.EmployeeID ";
                    $expQuery .= $expWhere;

                    $expResult = $this->db->query($expQuery);

                    if ($expResult->num_rows() > 0) {
                        $EXPrecords = $expResult->result();
                        if (count($EXPrecords) > 0) {
                            $expenses = array();
                            foreach ($EXPrecords as $eKey => $eVal) {
                                $total_vat -= $eVal->TotalVATAmount;
                                $expenses[] = $eVal->ID;
                            }
                            $expenseIds = implode(",", $expenses);
                        }
                    }
                }

                // echo "<pre>"; print_r( $user ); die();
                $where[] = " '" . $vatQuarters[$q]['SECOND'] . "' >= i.PaidOn ";
                $where[] = " '" . $vatQuarters[$q]['FIRST'] . "' <= i.PaidOn ";
                $where[] = " i.AddedBy=" . $userID . " ";
                $where[] = " i.Status=3 ";

                $where = " WHERE " . implode(" AND ", $where);
                $query = " SELECT i.InvoiceID,i.InvoiceNumber,i.PaidOn,i.Tax,i.InvoiceTotal,i.SubTotal,i.AddedOn,i.Status,i.DueDate,";
                $query .= " CONCAT(u.FirstName,' ',u.LastName) AS Name FROM " . $prefix . "purchases AS i";
                $query .= " LEFT JOIN " . $prefix . "users AS u ON u.ID=i.UserID";
                $query .= $where;

                $result = $this->db->query($query);
                // $this->db->last_query();

                if ($result->num_rows() > 0) {

                    $INVrecords = $result->result();
                    if (count($INVrecords) > 0) {

                        foreach ($INVrecords as $key => $val) {
                            $date = cDate($val->PaidOn);
                            $total_sales += $val->InvoiceTotal;
                            $total_vat += calculate_due_vat($vat_listing->Type, $val, $date, $user);
                            $purchases[] = $val->InvoiceID;
                        }
                    }
                }

                //if( $total_sales > 0 || $total_vat!= 0 ){
                if (1) {

                    $acceptVATZero = false;
                    if ($vat_listing->Type != 'flat') {
                        $acceptSalesZero = true;
                        if ($total_vat != 0) {
                            $acceptVATZero = true;
                        }
                    } else {
                        $acceptVATZero = true;
                        if ($total_sales > 0) {
                            $acceptSalesZero = true;
                        } else {
                            $acceptSalesZero = false;
                        }
                    }

                    //if( $acceptSalesZero && $acceptVATZero ){
                    if (1) {
                        $data = array(
                            "companyID" => $CompanyID,
                            "fromDate" => $vatQuarters[$q]['FIRST'],
                            "toDate" => $vatQuarters[$q]['SECOND'],
                            "totalSales" => $total_sales,
                            "totalDue" => $total_vat,
                            "purchases" => implode(",", $purchases),
                            "expenses" => $expenseIds,
                            "quarter" => $q,
                            "year" => $VATYear,
                            "paidDate" => mDate($paidDate),
                            "AddedBy" => $userID,
                            "AddedOn" => date("Y-m-d"),
                            "Status" => 3
                        );
                        $this->db->insert('vats', $data);

                        if ($this->db->affected_rows() > 0) {
                            $vatDetailsId = $this->db->insert_id();

                            $return['success'] = true;

                            /* added For P/L and B/S entries */
                            // $TBCatId = get_trial_balance_category( "VAT_CONTROL" );
                            // store_trial_entry( $TBCatId, mDate($paidDate), $total_vat );
                            /* added For P/L and B/S entries */

                            if (count($INVrecords) > 0) {
                                foreach ($INVrecords as $key => $val) {
                                    $data = array(
                                        'vatPaidId' => $vatDetailsId
                                    );

                                    $this->db->where('InvoiceID', $val->InvoiceID);
                                    $this->db->update('purchases', $data);
                                    if ($this->db->affected_rows() != 1) {
                                        if ($return['success'])
                                            $return['success'] = false;
                                        $return['error'][$val->InvoiceID] = $this->lang->line("ERROR_UPDATING_VAT_ID_IN_INVOICE");
                                    }
                                }
                            }

                            if ($vat_listing->Type != 'flat') {
                                if (count($EXPrecords) > 0) {
                                    foreach ($EXPrecords as $eKey => $eVal) {
                                        $data = array(
                                            'vatPaidId' => $vatDetailsId
                                        );

                                        $this->db->where('ID', $eVal->ID);
                                        $this->db->update('expenses', $data);
                                        if ($this->db->affected_rows() != 1) {
                                            if ($return['success'])
                                                $return['success'] = false;
                                            $return['error'][$eVal->ID] = $this->lang->line("ERROR_UPDATING_VAT_ID_IN_EXPENSE");
                                        }
                                    }
                                }
                            }

                            // if something went wrong revert everything
                            if (!$return['success']) {

                                $this->db->where('id', $vatDetailsId);
                                $this->db->delete('vats');

                                $data = array(
                                    'vatPaidId' => '0'
                                );
                                $this->db->where('vatPaidId', $vatDetailsId);
                                $this->db->update('expenses', $data);

                                $this->db->where('vatPaidId', $vatDetailsId);
                                $this->db->update('purchases', $data);
                                $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_REVERTED");
                            }
                        } else {
                            $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID");
                        }
                    } else {
                        $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_SALES_ZERO");
                    }

                    // return $records;
                } else {
                    $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_NO_INVOICE");
                }
            } else {
                $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_NO_QUARTERS");
            }
        } else {
            if (!empty($q)) {
                $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_NO_QUARTER_SELECTED");
            }if (!empty($VATYear)) {
                $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_NO_YEAR_SELECTED");
            }if (!empty($paidDate)) {
                $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_NO_PAID_DATE_CHOOSEN");
            }
        }
        // print_R( $return );die();
        return $return;
    }

    //02-11-2015 Vat Summary tab feature for updating Vat Summary
    public function VatSummary_markVATPaid() {
        $prefix = $this->db->dbprefix;
        $q = $this->input->post("quarter");
        $VATYear = $this->input->post("VATYear");
        $paidDate = $this->input->post("paidDate");
        //$paidDate = date('Y-m-d');
        $vatDueAc = $this->input->post("vatDueAc");
        $TDue = $this->input->post("TDue");
        $TgEC = $this->input->post("TgEC");
        $TaEC = $this->input->post("TaEC");
        $return = array("error" => array(), "success" => false);

        if (!empty($q) && !empty($VATYear) && !empty($paidDate)) {

            $vatQuarters = getVatQuarters($VATYear);
            $purchases = array();
            $INVrecords = array();

            // echo "<pre>"; print_r( $vatQuarters ); die();
            if ($vatQuarters) {

                $q = $this->encrypt->decode($q);
                $total_sales = 0;
                $total_vat = 0;
                $expenseIds = "";
                $user = $this->session->userdata('user');
                $userID = $user['UserID'];
                $AddedBy_accountant = $user['AddedBy'];
                $CompanyID = $user['CompanyID'];
                $vat_listing = $this->getVatType();

                if ($vat_listing->Type != 'flat') {
                    $expWhere[] = " " . $vatQuarters[$q]["FIRST"] . " <= e.PaidOn <= " . $vatQuarters[$q]["SECOND"] . " ";
                    $expWhere[] = " e.AddedBy=" . $userID . " ";
                    $expWhere[] = " e.Status=3 ";

                    $expWhere = " WHERE " . implode(" AND ", $expWhere);
                    $expQuery = "SELECT CONCAT(ce.FirstName,' ',ce.LastName) AS EmployeeName,e.ID,e.ExpenseNumber,e.ExpenseType";
                    $expQuery .= ",e.Month,e.Year,e.TotalMiles,e.FileID,e.Status,e.TotalAmount,e.TotalVATAmount,e.PaidOn FROM " . $prefix . "expenses AS e";
                    $expQuery .= " LEFT JOIN " . $prefix . "company_customers AS ce ON ce.ID = e.EmployeeID ";
                    $expQuery .= $expWhere;

                    $expResult = $this->db->query($expQuery);

                    if ($expResult->num_rows() > 0) {
                        $EXPrecords = $expResult->result();
                        if (count($EXPrecords) > 0) {
                            $expenses = array();
                            foreach ($EXPrecords as $eKey => $eVal) {
                                $total_vat -= $eVal->TotalVATAmount;
                                $expenses[] = $eVal->ID;
                            }
                            $expenseIds = implode(",", $expenses);
                        }
                    }
                }

                // echo "<pre>"; print_r( $user ); die();
                $where[] = " '" . $vatQuarters[$q]['SECOND'] . "' >= i.PaidOn ";
                $where[] = " '" . $vatQuarters[$q]['FIRST'] . "' <= i.PaidOn ";
                $where[] = " i.AddedBy=" . $userID . " ";
                $where[] = " i.Status=3 ";

                $where = " WHERE " . implode(" AND ", $where);
                $query = " SELECT i.InvoiceID,i.InvoiceNumber,i.PaidOn,i.Tax,i.InvoiceTotal,i.SubTotal,i.AddedOn,i.Status,i.DueDate,";
                $query .= " CONCAT(u.FirstName,' ',u.LastName) AS Name FROM " . $prefix . "purchases AS i";
                $query .= " LEFT JOIN " . $prefix . "users AS u ON u.ID=i.UserID";
                $query .= $where;

                $result = $this->db->query($query);
                // $this->db->last_query();

                if ($result->num_rows() > 0) {

                    $INVrecords = $result->result();
                    if (count($INVrecords) > 0) {

                        foreach ($INVrecords as $key => $val) {
                            $date = cDate($val->PaidOn);
                            $total_sales += $val->InvoiceTotal;
                            $total_vat += calculate_due_vat($vat_listing->Type, $val, $date, $user);
                            $purchases[] = $val->InvoiceID;
                        }
                    }
                }

                //if( $total_sales > 0 || $total_vat!= 0 ){
                if (1) {

                    $acceptVATZero = false;
                    if ($vat_listing->Type != 'flat') {
                        $acceptSalesZero = true;
                        if ($total_vat != 0) {
                            $acceptVATZero = true;
                        }
                    } else {
                        $acceptVATZero = true;
                        if ($total_sales > 0) {
                            $acceptSalesZero = true;
                        } else {
                            $acceptSalesZero = false;
                        }
                    }
                    //if( $acceptSalesZero && $acceptVATZero ){
                    if (1) {
                        $data = array(
                            "companyID" => $CompanyID,
                            "fromDate" => $vatQuarters[$q]['FIRST'],
                            "toDate" => $vatQuarters[$q]['SECOND'],
                            "totalSales" => $total_sales,
                            "totalDue" => $total_vat,
                            "purchases" => implode(",", $purchases),
                            "expenses" => $expenseIds,
                            "quarter" => $q,
                            "year" => $VATYear,
                            "paidDate" => mDate($paidDate),
                            "AddedBy" => $AddedBy_accountant,
                            "AddedOn" => date("Y-m-d"),
                            "Status" => 3,
                            "vat_due_acq_ec" => $vatDueAc,
                            "acutal_total_vat_due" => $TDue,
                            "total_supply_goods_ec" => $TgEC,
                            "total_acq_goods_ec" => $TaEC,
                            "accountant_submit" => 1,
                        );
                        $this->db->insert('vats', $data);

                        if ($this->db->affected_rows() > 0) {
                            $vatDetailsId = $this->db->insert_id();

                            $return['success'] = true;

                            /* added For P/L and B/S entries */
                            // $TBCatId = get_trial_balance_category( "VAT_CONTROL" );
                            // store_trial_entry( $TBCatId, mDate($paidDate), $total_vat );
                            /* added For P/L and B/S entries */

                            if (count($INVrecords) > 0) {
                                foreach ($INVrecords as $key => $val) {
                                    $data = array(
                                        'vatPaidId' => $vatDetailsId
                                    );

                                    $this->db->where('InvoiceID', $val->InvoiceID);
                                    $this->db->update('purchases', $data);
                                    if ($this->db->affected_rows() != 1) {
                                        if ($return['success'])
                                            $return['success'] = false;
                                        $return['error'][$val->InvoiceID] = $this->lang->line("ERROR_UPDATING_VAT_ID_IN_INVOICE");
                                    }
                                }
                            }

                            if ($vat_listing->Type != 'flat') {
                                if (count($EXPrecords) > 0) {
                                    foreach ($EXPrecords as $eKey => $eVal) {
                                        $data = array(
                                            'vatPaidId' => $vatDetailsId
                                        );

                                        $this->db->where('ID', $eVal->ID);
                                        $this->db->update('expenses', $data);
                                        if ($this->db->affected_rows() != 1) {
                                            if ($return['success'])
                                                $return['success'] = false;
                                            $return['error'][$eVal->ID] = $this->lang->line("ERROR_UPDATING_VAT_ID_IN_EXPENSE");
                                        }
                                    }
                                }
                            }

                            // if something went wrong revert everything
                            if (!$return['success']) {

                                $this->db->where('id', $vatDetailsId);
                                $this->db->delete('vats');

                                $data = array(
                                    'vatPaidId' => '0'
                                );
                                $this->db->where('vatPaidId', $vatDetailsId);
                                $this->db->update('expenses', $data);

                                $this->db->where('vatPaidId', $vatDetailsId);
                                $this->db->update('purchases', $data);
                                $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_REVERTED");
                            }
                        } else {
                            $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID");
                        }
                    } else {
                        $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_SALES_ZERO");
                    }

                    // return $records;
                } else {
                    $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_NO_INVOICE");
                }
            } else {
                $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_NO_QUARTERS");
            }
        } else {
            if (!empty($q)) {
                $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_NO_QUARTER_SELECTED");
            }if (!empty($VATYear)) {
                $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_NO_YEAR_SELECTED");
            }if (!empty($paidDate)) {
                $return['error'][] = $this->lang->line("ERROR_ADDING_VAT_AS_PAID_NO_PAID_DATE_CHOOSEN");
            }
        }
        // print_R( $return );die();
        return $return;
    }

    public function getPaidVatQuarters() {
        $prefix = $this->db->dbprefix;
        $VATYear = $this->session->userdata('VATYear');
        $user = $this->session->userdata('user');

        if (!empty($VATYear)) {
            $year = $VATYear;
        } else {
            $year = date("Y");
        }

        $userID = $user['UserID'];
        $CompanyID = $user['CompanyID'];

        $this->db->select("*");
        $this->db->where("year", $year);
        $this->db->where("companyID", $CompanyID);
        $this->db->where("Status", 3);
        $result = $this->db->get('vats');
        $this->db->last_query();

        if ($result->num_rows() > 0) {
            $records = $result->result();
            foreach ($records as $value) {
                $newrecords[$value->quarter] = $value;
            }
            return $newrecords;
        } else {
            return false;
        }
    }

    public function VatSummary_getPaidVatQuarters() {
        $prefix = $this->db->dbprefix;
        $VATYear = $this->session->userdata('VATYear');
        $user = $this->session->userdata('user');
        if (!empty($VATYear)) {
            $year = $VATYear;
        } else {
            $year = date("Y");
        }

        $userID = $user['UserID'];
        $AddedBy = $user['AddedBy'];
        $CompanyID = $user['CompanyID'];

        $this->db->select("*");
        $this->db->where("year", $year);
        $this->db->where("companyID", $CompanyID);
        $this->db->where("AddedBy", $AddedBy);
        $this->db->where("Status", 3);
        $result = $this->db->get('vats');
        $this->db->last_query();

        if ($result->num_rows() > 0) {
            $records = $result->result();
            foreach ($records as $value) {
                $newrecords[$value->quarter] = $value;
            }
            return $newrecords;
        } else {
            return false;
        }
    }

    function getQuarterDetails($q = 1, $VATYear) {
        $prefix = $this->db->dbprefix;
        if (!isset($VATYear)) {
            $VATYear = date("Y");
        }
        $vatQuarters = getVatQuarters($VATYear);

        $user = $this->session->userdata('user');
        $userID = $user['UserID'];

        $vat_listing = $this->getVatType();

        // echo "<pre>"; print_r( $user ); die();
        $where[] = " '" . $vatQuarters[$q]['SECOND'] . "' >= i.PaidOn ";
        $where[] = " '" . $vatQuarters[$q]['FIRST'] . "' <= i.PaidOn ";
        $where[] = " i.AddedBy=" . $userID . " ";
        $where[] = " i.Status=3 ";

        $where = " WHERE " . implode(" AND ", $where);
        $query = " SELECT i.*, c.Name as companyName";
        $query .= " ,CONCAT(u.FirstName,' ',u.LastName) AS Name FROM " . $prefix . "purchases AS i";
        $query .= " LEFT JOIN " . $prefix . "users AS u ON u.ID=i.UserID ";
        $query .= " LEFT JOIN " . $prefix . "company AS c ON c.CID=i.CustomerCompanyID ";
        $query .= $where;

        $result = $this->db->query($query);
        //  echo $this->db->last_query();die();

        if ($result->num_rows() > 0) {
            $INVrecords = $result->result();
            return $INVrecords;
        } else {
            return false;
        }
    }

    public function getInvoiceDetails($item) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = 'SELECT i.InvoiceID, i.InvoiceID as id,i.FlatRate,i.NetSales,i.Status,i.InvoiceNumber,i.CustomerCompanyID,i.PaidOn,i.DueDate,i.UserID,i.BankStatement,i.VatType,i.PercentRateAfterEndDate,i.PercentRate,i.EndDate,';
        $query .= 'i.BankDetail,CONCAT(u.FirstName," ",u.LastName) AS Name,i.InvoiceTotal,i.Tax as InvoiceTax,i.SubTotal as InvoiceSub,';
        $query .= 'u.Address,it.ID AS ItemID,it.Category,it.Description,it.UnitPrice,it.Quantity,it.Tax';
        $query .= ' FROM ' . $prefix . 'purchases AS i LEFT JOIN ' . $prefix . 'purchases_items AS it ON it.InvoiceID = i.InvoiceID';
        $query .= ' LEFT JOIN ' . $prefix . 'users AS u ON u.ID = i.UserID';
        $query .= ' WHERE i.InvoiceID=' . $item['InvoiceID']; //.' AND i.CustomerCompanyID=1';
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            //echo "<pre>";print_r($query->result());echo"</pre>";DIE;
            $invoice = $query->row_array();
            return $invoice;
        } else {
            return FALSE;
        }
    }

    public function statistics($id = null) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $record = array();
        if (!empty($id)) {
            $user['UserID'] = $id;
        }

        $search = $this->session->userdata('search_accounting_year');

        $trail_balance_year = '';
        $current_year_data = 1;
        $previous_year_data = 1;

        $filed_years = check_filed_account();
        $record['filed_years'] = $filed_years;
        foreach ($filed_years as $key => $val) {
            $filed_years[$key] = $val['year'];
        }


        if (!empty($search)) {
            $y = $search;
            $chart_years = $y;

            $current_year = $search;

            /* First Check if previous year is filed or not */
            $previous_year = $current_year;

            $previous_year = explode('/', $previous_year);
            $previous_year[0] = $previous_year[0] - 1;
            $previous_year[1] = $previous_year[1] - 1;
            $previous_year = $previous_year[0] . '/' . $previous_year[1];

            if (in_array($current_year, $filed_years)) {
                $current_year_data = 0;
            }
            if (in_array($previous_year, $filed_years)) {
                $previous_year_data = 0;
            }

            $current_year = company_year($current_year);
            $previous_year = company_year($previous_year);

            $year = company_year($y);
            $chart_years = company_year($chart_years);
            $trail_balance_year = $y;
        } else {
            $TBYears = getTBYear();
            //prd( $statistics );
            $TBYear = $TBYears[0]["value"];
            $year = company_year($TBYear);
            $current_year = $TBYear;

            $previous_year = explode('/', $current_year);
            $previous_year[0] = $previous_year[0] - 1;
            $previous_year[1] = $previous_year[1] - 1;
            $previous_year = $previous_year[0] . '/' . $previous_year[1];

            if (in_array($current_year, $filed_years)) {
                $current_year_data = 0;
            }
            if (in_array($previous_year, $filed_years)) {
                $previous_year_data = 0;
            }


            $current_year = company_year($TBYear);
            $previous_year = company_year($previous_year);

            $trail_balance_year = $TBYear;
            $chart_years = $year;
        }


        /* STEP - 1 : Get sales of current and previous year */
        /* Sales category id: 2 */
        $current_year = date('Y', strtotime($current_year['start_date'])) . '/' . date('Y', strtotime($current_year['end_date']));
        $previous_year = date('Y', strtotime($previous_year['start_date'])) . '/' . date('Y', strtotime($previous_year['end_date']));
        $record['comp_current_year'] = date('Y', strtotime($chart_years['end_date']));
        $record['comp_past_year'] = date('Y', strtotime($chart_years['start_date']));



        $this->db->select('amount');
        $where = array(
            'category_id' => 2,
            'clientId' => $user['UserID'],
            'year' => $current_year
        );
        $query = $this->db->get_where('trial_balance', $where);

        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
            if (!empty($result[0]->amount)) {
                $record['current_year_sale'] = negativeNumber($result[0]->amount);
            } else {
                $record['current_year_sale'] = 0;
            }
        } else {
            $record['current_year_sale'] = 0;
        }



        $this->db->select('amount');
        $where = array(
            'category_id' => 2,
            'clientId' => $user['UserID'],
            'year' => $previous_year
        );
        $query = $this->db->get_where('trial_balance', $where);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
            if (!empty($result[0]->amount)) {
                $record['previous_year_sale'] = negativeNumber($result[0]->amount);
            } else {
                $record['previous_year_sale'] = 0;
            }
        } else {
            $record['previous_year_sale'] = 0;
        }


        /* STEP - 2: Expenses of the company for the current year */
        $record['current_year_corporation_taxes'] = $this->get_corporation_tax($current_year);
        $record['previous_year_corporation_taxes'] = $this->get_corporation_tax($previous_year);
        //prd($record);
        $SHselected = date("Y") . " / " . (date("Y") + 1);
        $SHselected = explode('/', $SHselected);
        $SHselected = array(
            'start_date' => trim($SHselected[0]) . '-' . '04' . '-' . '06',
            'end_date' => trim($SHselected[1]) . '-' . '04' . '-' . '05'
        );
        $record['gross_salary'] = $this->get_gross_salary('', $SHselected);
        $record['gross_dividend'] = $this->get_gross_dividend('', $SHselected);

        /* Get Profit brought forward for the current year */
        /* B/Fwd category id : 173 */
        $this->db->select('amount');
        $where = array(
            'category_id' => 173,
            'clientId' => $user['UserID'],
            'year' => $current_year
        );
        $query = $this->db->get_where('trial_balance', $where);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
            if (!empty($result[0]->amount)) {
                $record['current_year_profit_bf'] = $result[0]->amount;
            } else {
                $record['current_year_profit_bf'] = 0;
            }
        } else {
            $record['current_year_profit_bf'] = 0;
        }
        $this->db->select('amount');
        $where = array(
            'category_id' => 173,
            'clientId' => $user['UserID'],
            'year' => $previous_year
        );
        $query = $this->db->get_where('trial_balance', $where);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
            if (!empty($result[0]->amount)) {
                $record['previous_year_profit_bf'] = $result[0]->amount;
            } else {
                $record['previous_year_profit_bf'] = 0;
            }
        } else {
            $record['previous_year_profit_bf'] = 0;
        }

        $record['purchases'] = $this->get_purchases_statistics($chart_years);
        $record['expenses'] = $this->get_expenses_statistics($chart_years);
        //$record['balances']   =   $this->get_balances($trail_balance_year);
        $record['balance_date'] = $this->get_last_statement_date();
        $record['comparitive_dividend'] = $this->get_comparative_dividends($trail_balance_year);

        return $record;
    }

    public function get_corporation_tax($year = null) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = "SELECT tb.amount,tc.id as assets_id,tc.CategoryType FROM " . $prefix . "trial_balance AS tb";
        $query .= " LEFT JOIN " . $prefix . "trial_balance_categories AS tc ON tc.id = tb.category_id";
        $query .= " WHERE (tb.clientId=" . $user['UserID'] . " AND tb.year ='" . $year . "') AND tc.CategoryType !=' '";
        $query = $this->db->query($query);
        //die($this->db->last_query());
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            $result = $query->result();
            //echo '<pre>';print_r($result);echo '</pre>';die;
            $expenses = 0;
            $income = 0;
            $assets = 0;
            $entertainment = 0;
            $depriciation = 0;
            foreach ($result as $key => $val) {
                if ($val->CategoryType == "EXPENSE") {
                    $expenses += $val->amount;
                } elseif ($val->CategoryType == "INCOME") {
                    $income += $val->amount;
                } elseif ($val->CategoryType == "ENTERTAINMENT") {
                    $entertainment += $val->amount;
                } elseif ($val->CategoryType == "DEPRICIATION") {
                    $depriciation += $val->amount;
                }

                if ($val->CategoryType == "ASSETS" || $val->assets_id == 94) {
                    $assets += $val->amount;
                }
            }
            $record = array(
                'income' => negativeNumber($income),
                'expense' => negativeNumber($expenses),
                'assets' => negativeNumber($assets),
                'entertainment' => negativeNumber($entertainment),
                'depreciation' => negativeNumber($depriciation)
            );
        } else {
            $record = array(
                'income' => 0,
                'expense' => 0,
                'assets' => 0,
                'entertainment' => 0,
                'depreciation' => 0
            );
        }
        return $record;
    }

    public function get_purchases_statistics($year) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $this->db->select('*');

        $where = array(
            'AddedBy' => $user['UserID'],
            'Status' => 3,
            'DueDate >=' => $year['start_date'],
            'DueDate <=' => $year['end_date']
        );
        $query = $this->db->get_where('purchases', $where);

        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            $result = $query->result();
            $record['monthly_paid_purchases'] = $this->monthly_record($result, 'PaidOn');
        } else {
            $record['monthly_paid_purchases'] = $this->monthly_record(array());
        }

        //pr($record['monthly_paid_purchases']);die;

        $this->db->select('*');
        $where = array(
            'AddedBy' => $user['UserID'],
            'Status !=' => 3,
            'DueDate >=' => $year['start_date'],
            'DueDate <=' => $year['end_date']
        );
        $query = $this->db->get_where('purchases', $where);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {

            $result = $query->result();
            $record['monthly_due_purchases'] = $this->monthly_record($result, 'DueDate');
        } else {

            $record['monthly_due_purchases'] = $this->monthly_record(array());
        }

        $record['chart_months'] = array();
        $m = date('m', strtotime($year['start_date']));
        $m = $m + 1 - 1;
        for ($i = 1; $i <= 12; $i++) {
            if ($m < 10) {
                $record['chart_months'][$i] = '0' . $m;
            } else {
                $record['chart_months'][$i] = $m;
            }

            if ($m == 12) {
                $m = 1;
            } else {
                $m++;
            }
        }
        return $record;
    }

    public function get_expenses_statistics($year) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $month = date('m', strtotime($year['start_date']));
        $month = $month + 1 - 1;
        $expense_year = date('Y', strtotime($year['start_date']));
        $this->db->select('*');

        $query = "SELECT * FROM " . $prefix . "expenses WHERE AddedBy=" . $user['UserID'] . " AND ExpenseDate BETWEEN '" . $year['start_date'] . "' AND '" . $year['end_date'] . "' AND Status = 3";

        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $record['monthly_paid_expenses'] = $this->monthly_record($result, 'Month');
        } else {
            $record['monthly_paid_expenses'] = $this->monthly_record(array());
        }

        $this->db->select('*');

        if ($month < 10) {
            $month = trim(str_replace('0', '', $month));
        }
        $query = "SELECT * FROM " . $prefix . "expenses WHERE AddedBy=" . $user['UserID'] . " AND ExpenseDate BETWEEN '" . $year['start_date'] . "' AND '" . $year['end_date'] . "' AND Status != 3";

        $query = $this->db->query($query);
        //echo $this->db->last_query();
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            $result = $query->result();
            $record['monthly_total_expenses'] = $this->monthly_record($result, 'Month');
        } else {
            $record['monthly_total_expenses'] = $this->monthly_record(array());
        }

        $m = date('m', strtotime($year['start_date']));
        $m = $m + 1 - 1;
        for ($i = 1; $i <= 12; $i++) {
            if ($m < 10) {
                $record['chart_months'][$i] = '0' . $m;
            } else {
                $record['chart_months'][$i] = $m;
            }


            if ($m == 12) {
                $m = 1;
            } else {
                $m++;
            }
        }

        return $record;
    }

    function get_balances($filed_years = null) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $TBYears = getTBYear();
        $year = $TBYears[0]["value"];

        /* Get Filed Accounts */
        if (count($filed_years) > 0) {
            foreach ($filed_years as $key => $val) {
                //echo '<br/>'.$val['year'].' - '.$year;
                if ($val['year'] != $year) {
                    $year = $TBYears[1]["value"];
                }
            }
        }

        // 128 = Cash at bank,176 = Savings account //

        $query = $this->db->query("SELECT `amount` AS SavingAmount FROM (`" . $prefix . "trial_balance`) WHERE `category_id` = 176 AND `clientId` = " . $user['UserID'] . " AND `year` = '" . $year . "'");

        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        $balance = array();

        if ($query->num_rows() > 0) {
            $result = $query->result();

            //echo "<pre>";print_r($result);echo "</pre>";
            $balance['SavingAmount'] = $result[0]->SavingAmount;
        } else {
            $balance['SavingAmount'] = 0;
        }
        //$query = $this->db->query("SELECT `amount` AS CurrentAmount FROM (`".$prefix."trial_balance`) WHERE `category_id` = 128 AND `clientId` = ".$user['UserID']);
        //$query = $this->db->query("SELECT TransactionDate,Balance FROM ".$prefix."bank_statements WHERE AddedBy=".$user['UserID'].' ORDER BY TransactionDate DESC LIMIT 0,1');
        $query = $this->db->query("SELECT TransactionDate,Balance FROM " . $prefix . "bank_statements WHERE AddedBy=" . $user['UserID'] . ' ORDER BY TransactionDate DESC,ID DESC LIMIT 0,1');

        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $balance['CurrentAmount'] = $result[0]->Balance;
        } else {
            $balance['CurrentAmount'] = 0;
        }
        //echo "<pre>";print_r($balance);echo "</pre>";
        return $balance;
    }

    function get_gross_salary($id = NULL, $year = '') {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');

        $year = FALSE;
        if ($this->input->is_ajax_request()) {
            $year = $this->input->post('year');
        }

        /* Financial Year */
        if (!$year) {
            $SHyear = date('Y');
        } else {
            $year = explode("/", $year);
            $SHyear = $year[0];
        }

        $year = array(
            'start_date' => $SHyear . '-04-05',
            'end_date' => ($SHyear + 1) . '-04-06',
        );


        if (empty($id)) {
            $query = "SELECT ID FROM " . $prefix . "company_customers WHERE CompanyID=" . $user['CompanyID'] . " AND IS_Employee = 1 ORDER BY ID ASC LIMIT 0,1";
            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                $result = $query->result();
                $shareholder_id = $result[0]->ID;
            } else {
                $shareholder_id = 0;
            }
        } else {
            $shareholder_id = $id;
        }

        /* STEP - 3: Get gross salary for the current year */
        $this->db->select('SUM(GrossSalary) AS Salary');
        $y = (date('Y', strtotime($year['end_date'])) - 1) . ' / ' . date('Y', strtotime($year['end_date']));
        //echo $y;
        $where = array(
            //  'Status'        =>  1,
            'EID' => $shareholder_id,
            'FinancialYear' => $y
                //'PaidDate >=' =>  $year['start_date'],
                //'PaidDate <=' =>  $year['end_date']
        );

        $query = $this->db->get_where('salary', $where);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
            if (!empty($result[0]->Salary)) {
                $gross_salary = $result[0]->Salary;
            } else {
                $gross_salary = 0;
            }
        } else {
            $gross_salary = 0;
        }
        return $gross_salary;
    }

    function get_gross_dividend($id = NULL, $year = NULL) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');

        if (empty($id)) {
            $query = "SELECT ID FROM " . $prefix . "company_customers WHERE CompanyID=" . $user['CompanyID'] . " AND IS_ShareHolder = 1 ORDER BY ID ASC LIMIT 0,1";
            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                $result = $query->result();
                $shareholder_id = $result[0]->ID;
            } else {
                $shareholder_id = 0;
            }
        } else {
            $shareholder_id = $id;
        }

        /* STEP - 3: Get gross salary for the current year */

        $this->db->select('SUM(GrossAmount) AS Dividend');
        $where = array(
            'Status' => 2,
            'ShareholderID' => $shareholder_id,
            'PaidOn >=' => $year['start_date'],
            'PaidOn <=' => $year['end_date']
        );

        $query = $this->db->get_where('dividends', $where);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            $result = $query->result();
            if (!empty($result[0]->Dividend)) {
                $gross_dividend = $result[0]->Dividend;
            } else {
                $gross_dividend = 0;
            }
        } else {
            $gross_dividend = 0;
        }

        return $gross_dividend;
    }

    public function monthly_record($record = array(), $type = null) {
        $prefix = $this->db->dbprefix;
        $months = array(
            '01' => 0,
            '02' => 0,
            '03' => 0,
            '04' => 0,
            '05' => 0,
            '06' => 0,
            '07' => 0,
            '08' => 0,
            '09' => 0,
            '10' => 0,
            '11' => 0,
            '12' => 0
        );
        if (count($record) != 0) {
            foreach ($record as $key => $val) {
                if ($type == 'Month') {
                    if ($val->Month >= 10) {
                        $m = $val->Month;
                    } else {
                        $m = '0' . $val->Month;
                    }
                } else {
                    $m = date('m', strtotime($val->{$type}));
                }
                if (isset($val->InvoiceTotal)) {
                    $months[$m] = number_format(negativeNumber($months[$m] + $val->InvoiceTotal), 2, '.', '');
                } elseif (isset($val->TotalAmount)) {
                    $months[$m] = number_format(negativeNumber($months[$m] + $val->TotalAmount), 2, '.', '');
                }
            }
        }
        return $months;
    }

    public function get_shareholder_detail($id, $year) {
        $prefix = $this->db->dbprefix;
        $year = explode('/', $year);
        $year = array(
            'start_date' => trim($year[0]) . '-' . '04' . '-' . '06',
            'end_date' => trim($year[1]) . '-' . '04' . '-' . '05'
        );
        $record['gross_salary'] = $this->get_gross_salary($id, $year);
        $record['gross_dividend'] = $this->get_gross_dividend($id, $year);
        return $record;
    }

    public function get_payee_due() {
        $prefix = $this->db->dbprefix;
        $search = $this->session->userdata('search_accounting_year');
        if (!empty($search)) {
            $year = $search;
        } else {
            $TBYears = getTBYear();
            $year = $TBYears[0]["value"];
        }

        $year = explode('/', $year);
        $year = $year[0] . ' / ' . $year[1];

        $user = $this->session->userdata('user');
        //$query = $this->db->query("SELECT `Total`,`EndDate` FROM (`".$prefix."payee`) WHERE `AddedBy` = '".$user['UserID']."' AND `FinancialYear` = '".$year."' AND `Status` = 0");
        $query = $this->db->query("SELECT `Total`,`EndDate` FROM (`" . $prefix . "payee`) WHERE `AddedBy` = '" . $user['UserID'] . "' AND `Status` = 0");
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

    public function get_important_dates($year = null) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $this->load->model('accountant/cpanel');
        $data['acount_due_date'] = $this->cpanel->get_annual_items($user['UserID']);
        $data['return_due_date'] = $this->cpanel->get_return_items($user['UserID']);
        $data['vat_due_date'] = $this->cpanel->get_vatdue_items($user['UserID']);
        $data['paye_due_data'] = $this->get_payee_due();

        $due_dates = array();
        $sort_key = 1;
        foreach ($data['acount_due_date'] AS $key => $val) {
            //$datetime1 = new DateTime();
            $datetime1 = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
            $d = date('Y-m-d', strtotime('+9 month', strtotime($val->EndDate)));
            $datetime2 = DateTime::createFromFormat('Y-m-d', $d);
            $interval = $datetime1->diff($datetime2);
            $no_days = $interval->format('%R%a days');
            $d = date('Y-m-t', strtotime($d));
            if (negativeNumber($no_days) <= 183) {
                $due_dates[] = array(
                    'Date' => $d,
                    'Event' => 'Annual Account due',
                    'DaysLeft' => $no_days,
                    'SortKey' => $sort_key
                );
                $sort_key++;
            }
        }

        foreach ($data['return_due_date'] AS $key => $val) {

            $datetime1 = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
            $datetime2 = DateTime::createFromFormat('Y-m-d', $val->ReturnDate);
            $interval = $datetime1->diff($datetime2);
            $no_days = $interval->format('%R%a days');
            if (negativeNumber($no_days) <= 183) {
                $due_dates[] = array(
                    'Date' => $val->ReturnDate,
                    'Event' => 'Annual Return due',
                    'DaysLeft' => $no_days,
                    'SortKey' => $sort_key
                );
                $sort_key++;
            }
        }

        foreach ($data['vat_due_date'] AS $key => $val) {
            $d = $val['SECOND'];
            $d = date('Y-m-d', strtotime('+2 month', strtotime($d)));
            $d = explode('-', $d);
            $d = $d[0] . '-' . $d[1] . '-07';
            //$d = date('Y-m-d',strtotime('+8 day',strtotime($d)));
            $datetime1 = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));

            $datetime2 = DateTime::createFromFormat('Y-m-d', $d);
            $interval = $datetime1->diff($datetime2);
            $no_days = $interval->format('%R%a days');
            if (negativeNumber($no_days) <= 183) {
                $due_dates[] = array(
                    'Date' => $d,
                    'Event' => 'VAT due ' . date('M Y', strtotime($val['FIRST'])) . ' to ' . date('M Y', strtotime($val['SECOND'])),
                    'DaysLeft' => $no_days,
                    'SortKey' => $sort_key
                );
                $sort_key++;
            }
        }
        $dates = array();
        foreach ($due_dates as $key => $val) {
            $dates[$val['SortKey']] = $val['Date'];
        }
        asort($dates);

        $imp_dates = array();
        foreach ($dates as $key => $val) {
            foreach ($due_dates as $k => $v) {
                if ($v['SortKey'] == $key) {
                    $imp_dates[] = $v;
                }
            }
        }

        $payee = array();
        if (count($data['paye_due_data']) > 0) {
            foreach ($data['paye_due_data'] as $key => $val) {
                $d = $val->EndDate;
                $datetime1 = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
                $datetime2 = DateTime::createFromFormat('Y-m-d', $d);
                $interval = $datetime1->diff($datetime2);
                $no_days = $interval->format('%R%a days');
                if (negativeNumber($no_days) <= 183) {
                    $payee = array(
                        'Date' => $d,
                        'Event' => 'PAYE due',
                        'DaysLeft' => $no_days,
                        'SortKey' => $sort_key
                    );
                    $imp_dates[] = $payee;
                    $sort_key++;
                }
            }
        }

        $sorted_array = array();
        $dates_array = array();

        foreach ($imp_dates as $key => $val) {
            $dates_array[] = $val['Date'];
        }

        asort($dates_array);
        $array_keys = array_keys($dates_array);

        foreach ($array_keys as $key => $val) {
            $sorted_array[] = $imp_dates[$val];
        }
        return $sorted_array;
    }

    public function get_last_statement_date() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $query = "SELECT TransactionDate FROM " . $prefix . "bank_statements WHERE AddedBy=" . $user['UserID'] . " ORDER BY TransactionDate DESC LIMIT 0,1";
        $query = $this->db->query($query);

        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result[0]->TransactionDate;
        } else {
            return '';
        }
    }

    public function get_comparative_dividends($year = null) {
        //die("get_comparative_dividends");
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');

        $query = $this->db->query("SELECT `amount` FROM (`" . $prefix . "trial_balance`) WHERE `category_id` = '174' AND `clientId` = '" . $user['UserID'] . "' AND `year` = '" . $year . "'");
        $this->db->select('amount');
        $record = array();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $record['current_year'] = $result[0]->amount;
        } else {
            $record['current_year'] = 0;
        }

        $year = explode('/', $year);
        $year[0] = ($year[0] - 1);
        $year[1] = ($year[1] - 1);
        $year = implode('/', $year);
        $query = $this->db->query("SELECT `amount` FROM (`" . $prefix . "trial_balance`) WHERE `category_id` = '174' AND `clientId` = '" . $user['UserID'] . "' AND `year` = '" . $year . "'");
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $record['previous_year'] = $result[0]->amount;
        } else {
            $record['previous_year'] = 0;
        }

        $year = explode('/', $year);
        $year[0] = ($year[0] - 1);
        $year[1] = ($year[1] - 1);
        $year = implode('/', $year);
        $query = $this->db->query("SELECT `amount` FROM (`" . $prefix . "trial_balance`) WHERE `category_id` = '174' AND `clientId` = '" . $user['UserID'] . "' AND `year` = '" . $year . "'");
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $record['last_previous_year'] = $result[0]->amount;
        } else {
            $record['last_previous_year'] = 0;
        }
        //prd($record);
        return $record;
    }

    /*
     * Check Terms & Conditions Version
     * Params Session userId,Added By
     */

    // function for deleting invoice item
    public function delinvoiceItem($data = NULL) {
        if (!empty($data)) {
            $prefix = $this->db->dbprefix;
            $exp = explode(',', $data);
            $this->db->where_in('ID', $exp);
            $this->db->delete($prefix . 'purchases_items');
			echo $this->db->last_query();
            return TRUE;
        } else {
            return false;
        }
    }

    /*
     * Check Terms & Conditions Version
     * Params Session userId,Added By
     */

    public function checkTermandconditionversion($userId = null, $addedBy = NULL) {
        $prefix = $this->db->dbprefix;
        $query = $this->db->query("select * from cashman_term_conditions where ClientId=$userId and AccountantAccess=$addedBy");
        return $query->result();
    }

    /*
     * Flat Invoice
     */

    public function flatInvoice() {
        $prefix = $this->db->dbprefix;
        $query = $this->db->get($prefix . "purchases");
        return $query->result();
    }

    /*
     * Get client from company using CID
     */

    public function getClientId($CID = NULL) {
        if (!empty($CID)) {
            $prefix = $this->db->dbprefix;
            $this->db->select('ClientID');
            $this->db->where('CID', $CID);
            $query = $this->db->get($prefix . "company");
            $result = $query->result();
            return $result[0]->ClientID;
        }
    }

    /*
     * Update flat rate and net sales in invoice table
     */

    public function updateFlatandnetsales($invoiceId, $data) {
        if (!empty($invoiceId) && !empty($data)) {
            $prefix = $this->db->dbprefix;
            $this->db->where('InvoiceID', $invoiceId);
            $this->db->update($prefix . 'purchases', $data);
            return "Invoice Id has been updated=" . $invoiceId . " Flat Rate=" . $data['FlatRate'] . " Net Sales=" . $data['NetSales'];
        }
    }

    /*
     * Address Update in invoice
     */

    public function companyAddress($cmpId = NULL) {
        if (!empty($cmpId)) {
            $prefix = $this->db->dbprefix;
            $this->db->select('Params');
            $this->db->where('CID', $cmpId);
            $query = $this->db->get($prefix . "company");
            return $query->result();
        }
    }

    public function invoiceAdressupdate($invoicId = null, $data = NULL) {
        if (!empty($invoicId) && !empty($data)) {
            $prefix = $this->db->dbprefix;
            $this->db->where('InvoiceID', $invoicId);
            $this->db->update($prefix . 'purchases', $data);
            return "Invoice Address has been updated=" . $invoicId;
        }
    }

    public function getsupplierUserDetail($id) {
        if (!empty($id)) {
            $prefix = $this->db->dbprefix;
            $query = 'SELECT CONCAT(first_name," ",last_name) AS Name, CONCAT(address1 , " " , address2 , " " , address3) as Address  FROM ' . $prefix . 'suppliers WHERE id=' . $id;
            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return FALSE;
            }
        }
    }
    
    /*----- Vijay (02-08-2016) Function for delete Purchase item in trial blance (tb_details) ----------*/
    
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
       // echo $this->db->last_query(); die();
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
	
	/*
     *  This function will return the list of all purchases generated by the client.
     */

    public function getInvoiceListForLink($catKey = NULL) {
        if($catKey != NULL){
			$prefix = $this->db->dbprefix;      
			$query = "SELECT i.InvoiceID,i.InvoiceNumber,i.PaidOn,i.Tax,i.InvoiceTotal,i.SubTotal,i.AddedOn,i.InvoiceDate,i.Status,i.DueDate,i.FlatRate,i.NetSales,i.VatType,i.PercentRateAfterEndDate,i.PercentRate,i.EndDate,";
			$query .= " CONCAT(c.first_name,' ',c.last_name) AS Name FROM " . $prefix . "purchases AS i";
			$query .= " LEFT JOIN " . $prefix . "suppliers AS c ON c.ID=i.UserID";
			$query .= " WHERE i.Status = 2";
			$query .= " AND i.CustomerCompanyID = ".$user['CompanyID'];		
			$query .= " AND i.UserID IN (SELECT id FROM `cashman_suppliers` WHERE TB_Category ='".$catKey."' )";
			
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
			$query = "SELECT i.InvoiceID,i.InvoiceNumber,i.PaidOn,i.Tax,i.InvoiceTotal,i.SubTotal,i.AddedOn,i.InvoiceDate,i.Status,i.DueDate,i.FlatRate,i.NetSales,i.VatType,i.PercentRateAfterEndDate,i.PercentRate,i.EndDate,";
			$query .= " CONCAT(c.first_name,' ',c.last_name) AS Name FROM " . $prefix . "purchases AS i";
			$query .= " LEFT JOIN " . $prefix . "suppliers AS c ON c.ID=i.UserID";
			$query .= " WHERE i.Status = 2";
			
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

}

?>