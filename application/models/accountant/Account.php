<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account extends CI_Model {

    public function Account() {
        parent::__construct();
    }

    public function addClient($data = array()) {
        if (count($data) <= 0) {
            return FALSE;
        }
		if( $this->session->userdata('lastAddedClientId') != '' && $this->session->userdata('lastAddedClientId') > 0 )
		{
			$this->db->where('id', $this->session->userdata('lastAddedClientId'));
			$this->db->update('users', $data);
			$client_id = $this->session->userdata('lastAddedClientId');
		}
		else
		{
			$this->db->insert('users', $data);
			if ($this->db->affected_rows() <= 0) {
				return FALSE;
			}
			$client_id = $this->db->insert_id();
			$this->session->set_userdata('lastAddedClientId',$client_id);			
		}			
        /* No errors */
        return $client_id;
    }

    public function addCompany($data = array()) {
		if (count($data) <= 0) {
            return FALSE;
        }
		
        $this->db->insert('company', $data);
        if ($this->db->affected_rows() <= 0) {
            return FALSE;
        }
        /* No errors */
        return $this->db->insert_id();
    }

    public function addVAT($data = array(), $task = null) {	
	
        if (count($data) <= 0) {
            return FALSE;
        }

        if ($task == 'batch') {
            $this->db->insert_batch('tax_rates', $data);			
            if ($this->db->affected_rows() <= 0) {
                return FALSE;
            }
        } elseif ($task == 'single') {
            $this->db->insert('tax_rates', $data);			
            if ($this->db->affected_rows() <= 0) {
                return FALSE;
            }
        } else {
            return FALSE;
        }

        /* No errors */
        return TRUE;
    }

    public function addCustomer($data = array(), $task = null) {
        if (count($data) <= 0) {
            return FALSE;
        }

        if ($task == 'batch') {
            $this->db->insert_batch('company_customers', $data);
            //echo $this->db->last_query();
			if ($this->db->affected_rows() <= 0) {
                return FALSE;
            }
        } elseif ($task == 'single') {
            $this->db->insert('company_customers', $data);
			//echo $this->db->last_query();
            if ($this->db->affected_rows() <= 0) {
                return FALSE;
            }
        } else {
            return FALSE;
        }

        /* No errors */
        return TRUE;
    }

    public function addEmployee($data = array()) {
        $prefix = $this->db->dbprefix;
        $this->db->insert_batch('client_employees', $data);
        if ($this->db->affected_rows() <= 0) {
            return FALSE;
        }

        /* No errors */
        return TRUE;
    }

    public function addBank($data = array()) {
        $prefix = $this->db->dbprefix;
		$this->db->insert_batch('banks', $data);
		if ($this->db->affected_rows() <= 0) {
            return FALSE;
        }
        
		$count = count($data);
		$this->db->select('BID');
		$this->db->from('banks');
		$this->db->order_by('BID desc');
		$this->db->limit($count);
		$query = $this->db->get();
		
		$result = $query->result_array();
		
		/*for ($x = 0; $x < count($result); $x++) {
				$bank_detail[] = array(
				'title' => 'Cash at bank',
				'catkey' => 'CASH_AT_BANK',
				'type' => 'B/S',
				'parent' => '127',
				'status' => '1',
				'cat_type' => '3',
				'bankId' => $result[$x]['BID']
				);
			}	
		
		$prefix = $this->db->dbprefix;	
		$this->db->insert_batch('trial_balance_categories', $bank_detail);		
		if ($this->db->affected_rows() <= 0) {
            return FALSE;
        }*/

        /* No errors */
        return TRUE;
    }

    public function getItems($limit = CLIENT_LISTING_PAGINATION_LIMIT, $start = 0) {
        $prefix = $this->db->dbprefix;
        //echo 'Operation : '.$operation.'<br/>';
        $order = $this->session->userdata('accountantSortingOrder');
        if (isset($order) && !empty($order)) {
            $orderby = " ORDER BY " . $order . ' LIMIT ' . $start . ',' . $limit;
        } else {
            $orderby = " ORDER BY u.ID DESC LIMIT " . $start . "," . $limit;
        }
        $search = $this->session->userdata('accountantSearch');

        $where = $this->search($start, $limit);
        $query = 'SELECT CONCAT(u.FirstName," ",u.LastName) AS Name,c.CID,u.ID,u.ContactNo,u.Email,c.EndDate,c.Name AS CompanyName,u.Status,u.State,u.Activation,u.Relation_with';
        $query .= ' FROM ' . $prefix . 'users AS u LEFT JOIN ' . $prefix . 'company AS c ON c.ClientID = u.ID';
        $query .= $where;
        //$query .= 'GROUP BY u.id';
		
        if (!empty($search)) {
            $search_query = $this->db->query($query);

            $this->session->set_userdata('accountantSearchRecords', $search_query->num_rows());
        }
        $query .=' ' . $orderby;
        $query = $this->db->query($query);

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function search($start, $limit) {
        $search = $this->session->userdata('accountantSearch');

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
                //echo "<pre>";print_r($search);echo '</pre>';die;
                if (count($search) <= 0) {
                    $where = '';
                } else {
                    foreach ($search as $key => $val) {
                        if ($key == 'Name') {
                            $v = str_replace("'", "", '"%' . $this->db->escape($val) . '%"');
                            $where[] .= 'CONCAT(u.FirstName," ",u.LastName) LIKE ' . $v;
                        } elseif ($key == 'Email') {
                            $where[] .= 'u.' . $key . '=' . $this->db->escape($val);
                        } elseif ($key == 'Status') {
                            $where[] .= 'u.' . $key . '=' . (($val == "1") ? $this->db->escape($val) : '0');
                        } elseif ($key == 'CompanyName') {
                            $v = str_replace("'", "", '"%' . $this->db->escape($val) . '%"');
                            $where[] .= 'c.Name LIKE ' . $v;
                        } elseif ($key == 'EndDate') {
                            $v = str_replace("'", "", '"%' . $this->db->escape($val) . '%"');
                            $where[] .= 'c.EndDate LIKE ' . $v;
                        } elseif ($key == 'Relation_with') {
                            $where[] .= 'u.' . $key . '=' . $this->db->escape($val);
                        }
                    }
                }
            }
        } else {
            $where = '';
        }
        /*
          if($where == '')
          {
          $where = ' WHERE '."u.AddedBy=".$userID;
          }else{
          $where = implode(' AND ',$where);
          $where = ' WHERE '.$where.' AND u.AddedBy='.$userID;
          }
         */

        if ($where != '') {
            $where = implode(' AND ', $where);
            $where = ' WHERE ' . $where; //.' AND u.AddedBy='.$userID;
            $where .= ' AND UserType="TYPE_CLI"';
        } else {
            $where .= ' WHERE UserType="TYPE_CLI"';
        }

        if (empty($user['AddedBy'])) {
            $where .= ' AND u.AddedBy=' . $userID;
        } else {
            $where .= ' AND (u.AddedBy=' . $userID . ' OR u.SubParent =' . $user['AddedBy'] . ')';
        }
        return $where;
    }

    public function totalItems() {
        $prefix = $this->db->dbprefix;
        $search = $this->session->userdata('accountantSearch');
        $user = $this->session->userdata('user');
        $totalRecord = $this->session->userdata('accountantSearchRecords');
        if (isset($totalRecord) && !empty($totalRecord)) {
            return $totalRecord;
        }
        $where = "UserType = 'TYPE_CLI' AND (AddedBy = " . $user['UserID'] . " OR SubParent = " . $user['AddedBy'] . ")";
        $this->db->where($where);
        //echo $this->db->last_query();die;

        $records = $this->db->count_all_results('users');

        //echo $this->db->last_query();die;
        if ($records > 0) {
            return $records;
        } else {
            return 0;
        }
    }
	####################################
	## Author Name  : Gurdeep Singh   ##
	## Create Date  : 08 July 2016    ##
	## Parameters   : Client Id       ##
	## Function     : getClientDetail ##
	####################################
	
	public function getClientDetail($id = NULL) 
	{
        $prefix = $this->db->dbprefix;
        if ($id == NULL) {
            return FALSE;
        }
        $record = array();
        $query = $this->db->get_where('users', array('ID' => $id));
        $query = $query->row();
        $record['USER'] = $query;
        $record['USER']->Params = unserialize($record['USER']->Params);
		$temp_data = tableColumns($prefix . 'company');
		$company_params = array(
			'REG_AddressOne' => '',
			'REG_AddressTwo' => '',
			'REG_AddressThree' => '',
			'REG_PostalCode' => '',
			'REG_Country' => '',
			'REG_PhoneNo' => '',
			'CON_AddressOne' => '',
			'CON_AddressTwo' => '',
			'CON_AddressThree' => '',
			'CON_PostalCode' => '',
			'CON_Country' => '',
			'CON_PhoneNo' => '',
			'CompanySIDate' => '',
			'CompanyMonthlyFee' => '',
			'VATRegistrationNo' => '',
			'VATQuaters' => '',
			'CompanyShares' => '',
			'LogoLink' => ''
		);
		$temp_data->Params = $company_params;
		$record['COMPANY'] = $temp_data;
					
		$temp_data = tableColumns($prefix . 'tax_rates');
		$record['VAT'] = $temp_data;
		
		$temp_data = tableColumns($prefix . 'company_customers');
		$share_holder_params = array(
			'Salutation' => '',
			'DOB' => '',
			'NI_Number' => '',
			'UTR' => '',
			'EmployementStartDate' => '',
			'AddressOne' => '',
			'AddressTwo' => '',
			'AddressThree' => '',
			'ContactNumber' => '',
			'Country' => '',
			'PostalCode' => ''
		);
		$temp_data->Params = $share_holder_params;
		$record['SHARES'][] = $temp_data;
		
		$record['BANKS'][] = tableColumns($prefix . 'banks');
        return $record;
    }
	
    public function getItem($id = NULL) {
        $prefix = $this->db->dbprefix;
        if ($id == NULL) {
            return FALSE;
        }
        $record = array();
        $query = $this->db->get_where('users', array('ID' => $id));
        $query = $query->result();
        $record['USER'] = $query[0];
        $record['USER']->Params = unserialize($record['USER']->Params);
		if( $this->session->userdata('updateAbleCompanyId') != '' )
			$this->db->where('CID',$this->session->userdata('updateAbleCompanyId'));
        $query = $this->db->get_where('company', array('ClientID' => $id));
        $query = $query->result();
		if( !empty($query))
		{
			$record['COMPANY'] = $query[0];
			$record['COMPANY']->Params = unserialize($record['COMPANY']->Params);
			$query = $this->db->get_where('tax_rates', array('ClientID' => $id,'CompanyID' => $record['COMPANY']->CID));
			if ($query->num_rows() > 0) {
				$query = $query->result();
				$record['VAT'] = $query[0];
			} else {
				$temp_data = tableColumns($prefix . 'tax_rates');
				$record['VAT'] = $temp_data;
			}
			
			$query = $this->db->get_where('company_customers', array('CompanyID' => $record['COMPANY']->CID));
			
			$num_rows = $query->num_rows();

			if ($num_rows > 0) {
				$query = $query->result();
				$record['SHARES'] = $query;
				foreach ($record['SHARES'] as $key => $val) {
					$val->Params = unserialize($val->Params);
				}
				if ($num_rows == 1) {
					$temp_data = tableColumns($prefix . 'company_customers');
					$share_holder_params = array(
						'Salutation' => '',
						'DOB' => '',
						'NI_Number' => '',
						'UTR' => '',
						'EmployementStartDate' => '',
						'AddressOne' => '',
						'AddressTwo' => '',
						'AddressThree' => '',
						'ContactNumber' => '',
						'Country' => '',
						'PostalCode' => ''
					);
					$temp_data->Params = $share_holder_params;
					$record['SHARES'][] = $temp_data;
				}
			} else {
				$temp_data = tableColumns($prefix . 'company_customers');
				$share_holder_params = array(
					'Salutation' => '',
					'DOB' => '',
					'NI_Number' => '',
					'UTR' => '',
					'EmployementStartDate' => '',
					'AddressOne' => '',
					'AddressTwo' => '',
					'AddressThree' => '',
					'ContactNumber' => '',
					'Country' => '',
					'PostalCode' => ''
				);
				$temp_data->Params = $share_holder_params;
				$record['SHARES'][] = $temp_data;
			}
			$query = $this->db->get_where('banks', array('CompanyID' => $record['COMPANY']->CID));
			if ($query->num_rows() > 0) {
				$query = $query->result();
				$record['BANKS'] = $query;
			} else {
				$record['BANKS'][] = tableColumns($prefix . 'banks');
			}			
		}
		else {
            $temp_data = tableColumns($prefix . 'company');
			$company_params = array(
				'REG_AddressOne' => '',
				'REG_AddressTwo' => '',
				'REG_AddressThree' => '',
				'REG_PostalCode' => '',
				'REG_Country' => '',
				'REG_PhoneNo' => '',
				'CON_AddressOne' => '',
				'CON_AddressTwo' => '',
				'CON_AddressThree' => '',
				'CON_PostalCode' => '',
				'CON_Country' => '',
				'CON_PhoneNo' => '',
				'CompanySIDate' => '',
				'CompanyMonthlyFee' => '',
				'VATRegistrationNo' => '',
				'VATQuaters' => '',
				'CompanyShares' => '',
				'LogoLink' => ''
			);
			$temp_data->Params = $company_params;
            $record['COMPANY'] = $temp_data;
						
			$temp_data = tableColumns($prefix . 'tax_rates');
			$record['VAT'] = $temp_data;
			
			$temp_data = tableColumns($prefix . 'company_customers');
			$share_holder_params = array(
				'Salutation' => '',
				'DOB' => '',
				'NI_Number' => '',
				'UTR' => '',
				'EmployementStartDate' => '',
				'AddressOne' => '',
				'AddressTwo' => '',
				'AddressThree' => '',
				'ContactNumber' => '',
				'Country' => '',
				'PostalCode' => ''
			);
			$temp_data->Params = $share_holder_params;
			$record['SHARES'][] = $temp_data;
			
			$record['BANKS'][] = tableColumns($prefix . 'banks');
        }
        return $record;
    }

    public function updateDetails($data) {
		
		//echo "<pre>"; print_r($data); echo "</pre>";
		
        /* Update Client detail in user table */
        $client_id = $data['USER']['ClientID'];
        $data['USER']['Params'] = serialize($data['USER']['Params']);
        unset($data['USER']['ClientID']);
        $this->db->where('ID', $client_id);
        $this->db->update('users', $data['USER']);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        /* Update Company Details in Company Table */
        $company_id = $data['COMPANY']['CompanyID'];
        $data['COMPANY']['Params'] = serialize($data['COMPANY']['Params']);
        unset($data['COMPANY']['CompanyID']);
		if($company_id != '')
			$this->db->update('company', $data['COMPANY'], array('CID' => $company_id));
		else
		{ 
			$data['COMPANY']['ClientID'] = $client_id;
			$this->db->insert('company', $data['COMPANY']);
			$company_id = $this->db->insert_id();
		}
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
		if( $company_id != '' && $company_id > 0 )
		{
			
			/* Update VAT Details in Company Table */
			if (isset($data['VAT']['Type']) && $data['VAT']['Type'] != '' ) {
				$vat_id = $data['VAT']['VID'];
				unset($data['VAT']['VID']);
				$data['VAT']['CompanyID'] = $company_id;
				if (empty($vat_id) && (int) $vat_id <= 0) {
					$data['VAT']['ClientID'] = $client_id;
					$this->addVAT($data['VAT'], 'single');
				} else {
					$this->db->update('tax_rates', $data['VAT'], array('VID' => $vat_id));
					$db_error = $this->db->error();
					if ($db_error['code'] != 0) {
						log_message('error', $db_error['message']);
						return FALSE;
					}
					if ($this->db->affected_rows() <= 0) {
						//return FALSE;
					}
				}
			}
			/* Update Share Holder Detail */
			/* First Update Director Detail */
			$director_id = $data['DIRECTOR']['ID'];
			$data['DIRECTOR']['CompanyID'] = $company_id;
			$data['DIRECTOR']['Params'] = serialize($data['DIRECTOR']['Params']);		
			if (empty($director_id) && (int) $director_id <= 0) {
				//if( isset($data['DIRECTOR']['DFirstName']) && $data['DIRECTOR']['DFirstName'] != '' )
					$this->db->insert('company_customers', $data['DIRECTOR']);
			}
			else{
				$this->db->update('company_customers', $data['DIRECTOR'], array('ID' => $director_id));	
			}   
			//echo $this->db->last_query();
			$db_error = $this->db->error();
			if ($db_error['code'] != 0) {
				log_message('error', $db_error['message']);
				return FALSE;
			}

			/* Now update the share holders data */
			$update_data = array();
			$insert_data = array();
			if (count($data['SHARES']) > 0) {
				foreach ($data['SHARES'] as $key => $val) {
					if( isset($val['SFirstName']) && $val['SFirstName'] != '' )
						continue;
					$val['Params'] = serialize($val['Params']);
					if (empty($val['ID'])) {
						$val['CompanyID'] = $company_id;
						unset($val['ID']);
						$insert_data[] = $val;
					} else {
						$update_data[] = $val;
					}
				}
			
				if (count($update_data) > 0) {
					$this->db->update_batch('company_customers', $update_data, 'ID');
					//echo $this->db->last_query();
					$db_error = $this->db->error();
					if ($db_error['code'] != 0) {
						log_message('error', $db_error['message']);
						return FALSE;
					}
					//echo 'SHARER : '.$this->db->affected_rows();
					if ($this->db->affected_rows() <= 0) {
						//return FALSE;
					}
				}
				if (count($insert_data) > 0) {
					$this->db->insert_batch('company_customers', $insert_data);
					//echo $this->db->last_query();
					$db_error = $this->db->error();
					if ($db_error['code'] != 0) {
						log_message('error', $db_error['message']);
						return FALSE;
					}

					if ($this->db->affected_rows() <= 0) {
						return FALSE;
					}
				}
			}
			
			/* Update Bank Details */
			$update_bankdata = array();
			$insert_bankdata = array();
			if (count($data['BANKS']) > 0) {
				foreach ($data['BANKS'] as $key => $val) { 
					if( isset($val['Name']) && $val['Name'] != '' )
					{
						if (empty($val['BID'])) {
							$val['CompanyID'] = $company_id;
							unset($val['BID']);
							$insert_bankdata[] = $val;
						} else {
							$update_bankdata[] = $val;
						}
					}					
				}
				if (count($update_bankdata) > 0) {
					$this->db->update_batch('banks', $update_bankdata, 'BID');
					$db_error = $this->db->error();
					if ($db_error['code'] != 0) {
						log_message('error', $db_error['message']);
						return FALSE;
					}
				}
				if (count($insert_bankdata) > 0) {
					$this->db->insert_batch('banks', $insert_bankdata);
					$db_error = $this->db->error();
					if ($db_error['code'] != 0) {
						log_message('error', $db_error['message']);
						return FALSE;
					}
					
					$count = count($insert_bankdata);
					$this->db->select('BID');
					$this->db->from('banks');
					$this->db->order_by('BID desc');
					$this->db->limit($count);
					$query = $this->db->get();
					
					$result = $query->result_array();
					
					for ($x = 0; $x < count($result); $x++) {
						$bank_detail[] = array(
						'title' => 'Cash at bank',
						'catkey' => 'CASH_AT_BANK',
						'type' => 'B/S',
						'parent' => '127',
						'status' => '1',
						'cat_type' => '3',
						'bankId' => $result[$x]['BID']
						);
					}	
					
					$prefix = $this->db->dbprefix;	
					$this->db->insert_batch('trial_balance_categories', $bank_detail);		
					if ($this->db->affected_rows() <= 0) {
						return FALSE;
					}
					
				}
			}
		}
        
        return TRUE;
    }

    public function checkStatus($id) {
        if (empty($id)) {
            return FALSE;
        }
        $query = $this->db->get_where('users', array('ID' => $id, 'Status' => '1'));
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function checkEmail($email = NULL, $id = NULL) {
        $prefix = $this->db->dbprefix;
        if (empty($id)) {
            $query = "SELECT email FROM " . $prefix . "users WHERE Email='" . $email . "'";
        } else {
            $query = "SELECT email FROM " . $prefix . "users WHERE Email='" . $email . "' AND ID !=" . $id;
        }
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function changeStatus($id, $status) {
        if ($id == null) {
            return FALSE;
        }

        $this->db->update('users', array('Status' => $status), array('ID' => $id));
        return TRUE;
    }

    public function changeState($id) {
        $this->db->where('ID', $id);
        $this->db->update('users', array('State' => '1'));
        return true;
    }

    public function delete_client($id) {
        $this->db->where('ID', $id);
        $this->db->delete('users');
        return true;
    }

    public function getEmail($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('');
        $this->db->select('Email,CONCAT(FirstName," ' . '",LastName) AS Name', false);
        $query = $this->db->get_where('users', array('ID' => $id));
        if ($query->num_rows() > 0) {
            $data = $query->result();
            return $data;
        } else {
            return FALSE;
        }
    }

    public function clientLoginDetail($id = NULL) {
        $this->db->select('Email,Password');
        $query = $this->db->get_where('users', array('ID' => $id));
        if ($query->num_rows() > 0) {
            $query = $query->result();
            return $query;
        } else {
            return FALSE;
        }
    }

    /* ---- TO BE DELETE -------
      public function addAccountant($data)
      {
      $this->db->insert('cashman_users',$data);
      if($this->db->_error_number() != 0)
      {
      log_message('error',$this->db->_error_message());
      return FALSE;
      }
      if($this->db->affected_rows() > 0)
      {
      return TRUE;
      }else{
      return FALSE;
      }
      }


      public function getAccountants($limit = CLIENT_LISTING_PAGINATION_LIMIT,$start = 0)
      {
      //echo 'Operation : '.$operation.'<br/>';
      $order = $this->session->userdata('accountantSortingOrder');
      if(isset($order) && !empty($order))
      {
      $orderby = " ORDER BY ".$order.' LIMIT '.$start.','.$limit;
      }else{
      $orderby = " ORDER BY u.ID DESC LIMIT ".$start.",".$limit;
      }
      $search = $this->session->userdata('accountantSearch');

      $where = $this->accSearch($start,$limit);
      $query  = 'SELECT CONCAT(u.FirstName," ",u.LastName) AS Name,u.ID,u.ContactNo,u.Email,c.EndDate,c.Name AS CompanyName,u.Status,u.State';
      $query .= ' FROM cashman_users AS u LEFT JOIN cashman_company AS c ON c.ClientID = u.ID';
      $query .= $where;

      if(!empty($search))
      {
      $search_query = $this->db->query($query);

      $this->session->set_userdata('accountantSearchRecords',$search_query->num_rows());
      }
      $query .=' '.$orderby;
      $query = $this->db->query($query);

      if($query->num_rows() > 0)
      {
      //echo '<pre>';print_r($query->result());echo '</pre>';
      return $query->result();
      }
      }


      public function accSearch($start,$limit)
      {
      $search = $this->session->userdata('accountantSearch');

      $user = $this->session->userdata('user');
      $userID = $user['UserID'];

      /*
     * 	First check if search operation is performed or not.
     * 	Prepare where clause for the query according to the search criteria.


      $where = '';
      if($search != NULL)
      {
      if(!is_array($search))
      {
      $where = '';
      }else{
      //echo "<pre>";print_r($search);echo '</pre>';die;
      $search = array_filter($search);
      //echo "<pre>";print_r($search);echo '</pre>';die;
      if(count($search) <= 0)
      {
      $where = '';
      }else{
      foreach($search as $key=>$val)
      {
      if($key == 'Name')
      {
      $where[] .= 'CONCAT(u.FirstName," ",u.LastName) LIKE "%'.$val.'%"';
      }elseif($key == 'ContactNo'){
      $where[] .= 'u.'.$key.'='.$val;
      }elseif($key == 'Status'){
      $where[] .= 'u.'.$key.'='.(($val == "1")?$val:'0');
      }elseif($key == 'CompanyName'){
      $where[] .= 'c.Name LIKE "%'.$val.'%"';
      }elseif($key == 'EndDate'){
      $where[] .= 'c.EndDate LIKE "%'.$val.'%"';
      }
      }
      }
      }
      }else{
      $where = '';
      }
      if($where == '')
      {
      $where = ' WHERE '."u.AddedBy=".$userID;
      }else{
      $where = implode(' AND ',$where);
      $where = ' WHERE '.$where.' AND u.AddedBy='.$userID;
      }
      $where .= ' AND UserType="TYPE_ACC"';
      return $where;
      }
     */

    public function delete_logo($id) {
        $prefix = $this->db->dbprefix;
        $this->db->select('Params');
        $query = $this->db->get_where('company', array('ClientID' => $id));
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
        if ($query->num_rows() > 0) {
            $result = $query->result();
            $result = unserialize($result[0]->Params);
            $link = $result['LogoLink'];
            $result['LogoLink'] = '';
            $result = serialize($result);
            $query = "UPDATE " . $prefix . "company SET Params='" . $result . "' WHERE ClientID=" . $id;
            $this->db->query($query);
            return $link;
        } else {
            return false;
        }
    }
	###########################################################
	### Author : Gurdeep Singh             					###
	### Date   : 13 July 2016              					###
	### Parameters   : ClientId ( Logged Client id) 		###
	### Getting all companies list by logged in client id   ###
	###########################################################
	public function getAllCompaniesByClientId($clientId = NULL,$fields = NULL) {
        $prefix = $this->db->dbprefix;
        if ($clientId == NULL) {
            return FALSE;
        }
		if( $fields != NULL)
			$this->db->select($fields);
        $record = array();
        $query = $this->db->get_where('company', array('ClientID' => $clientId));
        $query = $query->result();
		return $query;	
	}
	
	###########################################################	
	###			 Add new shareholder category 		    ###
	###########################################################
	public function addShareholderCategory($data) {
        if(!empty($data)){
            $prefix = $this->db->dbprefix;
            $this->db->insert($prefix . 'trial_balance_categories', $data);
			//echo $this->db->last_query();           
            return TRUE;          
        }	
	}
	
	###########################################################	
	###			 Validate company name  				    ###
	###########################################################
	public function validateCompanyName($cName) {
        $this->db->select('CID');
        $query = $this->db->get_where('company', array('Name' => $cName));
        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
	}

}
