<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Abulkclient extends CI_Model {

    public function Abulkclient() {
        parent::__construct();
    }
	
	public function addClient($data = array()){
		if(count($data) <= 0)
		{
			return FALSE;
		}
		foreach($data as $userdata){
			$this->db->insert('users',$userdata);
			$lastid[] = $this->db->insert_id();
		}		
		if($this->db->affected_rows() <= 0)
		{
			return FALSE;
		}
		
		/* No errors */
		return $lastid;
	}
	
	public function addCompany($data = array())
	{
		if(count($data) <= 0)
		{
			return FALSE;
		}
		foreach($data as $companydata){
			$this->db->insert('company',$companydata);
			$lastid[] = $this->db->insert_id();
		}
		
		if($this->db->affected_rows() <= 0)
		{
			return FALSE;
		}
		
		/* No errors */
		return $lastid;
	}
	
	public function addVAT($data,$task = null)
	{
		
		if(count($data) <= 0){
			return FALSE;
		}		
		if($task == 'single'){	
			foreach($data as $wbatchdata){
				$this->db->insert('tax_rates',$wbatchdata);			
			}
			//echo $this->db->last_query();
			if($this->db->affected_rows() <= 0)
			{
				return FALSE;
			}
		}else{
			return FALSE;
		}		
		/* No errors */
		return TRUE;
	}
	
	public function addCustomer($data = array(),$task = null){
		//echo "<pre>"; print_r($data); echo "</pre>";
		//die();
		if(count($data) <= 0)
		{
			return FALSE;
		}
		
		if($task == 'single')
		{	
			foreach($data as $wbatchdata){
				$this->db->insert('company_customers',$wbatchdata);		
			}			
			if($this->db->affected_rows() <= 0)
			{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
		/* No errors */
		return TRUE;
	}
	
	public function addEmployee($data = array())
	{
		$prefix = $this->db->dbprefix;
		foreach($data as $addEmployee){
			$this->db->insert_batch('client_employees',$addEmployee);		
		}
		
		if($this->db->affected_rows() <= 0)
		{
			return FALSE;
		}
		
		/* No errors */
		return TRUE;
	}
	
	public function getEmployeeCompanyId($email)
	{
		$prefix = $this->db->dbprefix;
		$select = "select c.CID from cashman_company c, cashman_users u where u.ID = c.ClientID and u.Email = '".$email."'";
		$query = $this->db->query($select);
		//echo $this->db->last_query();
		//die;
		$result = $query->result_array();		
        return $result;
	}
	
	public function addBank($data)
	{
		
		$prefix = $this->db->dbprefix;
		$tableName = $prefix . 'banks';
		 foreach ($data as $key => $val) {
                $insert_bulk_statments[] = array(
                    'CompanyID' => $val['CompanyID'],
                    'Name' => $val['Name'],
                    'ShortCode' => $val['ShortCode'],
                    'AccountNumber' => $val['AccountNumber'],
                    'OpeningBalance' => $val['OpeningBalance'],
                    'AddedOn' => $val['AddedOn'],
                    'AddedBy' => $val['AddedBy'],
                    'Status' => $val['Status']                  
                );
            }
			//echo "<pre>"; print_r($insert_bulk_statments); echo "</pre>";
		//die;
           $this->db->insert_batch($tableName, $insert_bulk_statments);
		// foreach($data as $addBank){
			// $this->db->insert('banks',$addBank);			
		// }		
		// if($this->db->affected_rows() <= 0)
		// {
			// return FALSE;
		// }
		
		/* No errors */
		return TRUE;
	}
	
	
    public function get_annual_items($id = NULL) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        if (!empty($id)) {
            $where = ' AND u.ID = ' . $id;
        } else {
            $where = '';
        }

        if (empty($user['AddedBy'])) {
            $where .= ' AND u.AddedBy=' . $user['UserID'];
        } else {
            $where .= ' AND (u.AddedBy=' . $user['AddedBy'] . ' OR u.SubParent =' . $user['SubParent'] . ' )';
        }
        $query = "SELECT CONCAT(u.FirstName,' ',u.LastName) AS Name,u.ID,c.Name AS CompanyName,c.RegistrationNo,";
        $query .= "u.SubParent,c.CID ,c.IncorporationDate,c.ReturnDate,c.EndDate FROM " . $prefix . "users AS u";
        $query .= " LEFT JOIN " . $prefix . "company AS c ON c.ClientID = u.ID WHERE u.UserType = 'TYPE_CLI' " . $where;
        $query .= " ORDER BY c.EndDate ASC";
        $query = $this->db->query($query);
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            $result = $query->result();
            /*
              foreach($result as $key=>$res){
              // calculate the year to be marked as filed
              $EndDate = getColumns( $where=array( "CID"=>$res->CID ) , $columns="EndDate" );
              $EndDate = $EndDate[0]["EndDate"];
              $year = getFileYear( $EndDate );
              $year = $year["value"];
              }
             */
            return $result;
        } else {
            return array();
        }
    }

    public function get_return_items($id = NULL) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        if (!empty($id)) {
            $where = ' AND u.ID = ' . $id;
        } else {
            $where = '';
        }
        if (empty($user['AddedBy'])) {
            $where .= ' AND u.AddedBy=' . $user['UserID'];
        } else {
            $where .= ' AND (u.AddedBy=' . $user['AddedBy'] . ' OR u.SubParent =' . $user['SubParent'] . ' )';
        }
        $query = "SELECT CONCAT(u.FirstName,' ',u.LastName) AS Name,u.ID,c.Name AS CompanyName,c.RegistrationNo,";
        $query .= "u.SubParent,c.CID ,c.IncorporationDate,c.ReturnDate,c.EndDate FROM " . $prefix . "users AS u";
        $query .= " LEFT JOIN " . $prefix . "company AS c ON c.ClientID = u.ID WHERE u.UserType = 'TYPE_CLI'" . $where;
        $query .= " ORDER BY c.ReturnDate ASC";
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            $result = $query->result();
            /*
              foreach($result as $key=>$res){
              // calculate the year to be marked as filed
              $ReturnDate = getColumns( $where=array( "CID"=>$res->CID ) , $columns="ReturnDate" );
              $ReturnDate = $ReturnDate[0]["ReturnDate"];
              $year = getFileYear( $ReturnDate );
              $year = $year["value"];


              }
             */
            return $result;
        } else {
            return array();
        }
    }

    public function get_vatdue_items($id = NULL) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        if (!empty($id)) {
            $where = ' AND u.ID = ' . $id;
        } else {
            $where = '';
        }
        if (empty($user['AddedBy'])) {
            $where .= ' AND u.AddedBy=' . $user['UserID'];
        } else {
            $where .= ' AND (u.AddedBy=' . $user['AddedBy'] . ' OR u.SubParent =' . $user['SubParent'] . ' )';
        }
        $query = "SELECT CONCAT(u.FirstName,' ',u.LastName) AS Name,u.ID,c.Name AS CompanyName,c.RegistrationNo,";
        $query .= "u.SubParent,c.ClientID,c.Params AS CompanyParams,c.CID ,c.IncorporationDate,c.ReturnDate,c.EndDate FROM " . $prefix . "users AS u";
        $query .= " LEFT JOIN " . $prefix . "company AS c ON c.ClientID = u.ID WHERE u.UserType = 'TYPE_CLI' " . $where;
        $query = $this->db->query($query);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $newResult = array();
            foreach ($result as $key => $res) {
                // prd( $res );
                $params = unserialize($res["CompanyParams"]);
                $VATQuaters = $params["VATQuaters"];
                $comEndDate = cDate($res["EndDate"]);
                if ((int) $params["VATRegistrationNo"] > 0 && $comEndDate != "" && $comEndDate != "01-01-1970" && $comEndDate != "00-00-0000") { // Only display if Company is VAT Registered 
                    $VQresult = $this->db->query("SELECT * FROM (`" . $prefix . "vat_quarters`) WHERE `ClientID` = '" . $res["ClientID"] . "'");
                    //die($this->db->last_query());
                    if ($row = $VQresult->row()) {
                        /*
                          // need a variable for Dynamic Year End Date generation
                          $result[$key]["dEndDate"] = $res["EndDate"];
                          $multiple = FALSE;
                          if( $row->q1_start > $row->q4_end ){
                          $multiple = TRUE;
                          }
                         */

                        $currentYear = date("Y") + 1;
                        $dropStartYear = date("Y", strtotime($res["EndDate"])) - 2;
                        if (( $dropStartYear ) > APP_START_YEAR) { // if end date is after project start date then reset to project start year
                            $dropStartYear = APP_START_YEAR;
                        }

                        for ($startYear = $dropStartYear; $startYear <= $currentYear; $startYear++) {

                            // get default quarters
                            $quaters = vatQuaters($params["VATQuaters"], $startYear);

                            // Get quarters in paying sequence for a Accounting Year if diff from default quarters
                            $VATquaters = getDueVatQuarters($res["ClientID"], $startYear);
                            if ($VATquaters) {
                                $quaters = $VATquaters;
                            }

                            foreach ($quaters as $Qkey => $qDetails) { // generate different records for every quarter of every year
                                $vatDueArr = array();
                                $vatDueArr = $result[$key];
                                $vatDueArr["year"] = $startYear;
                                $vatDueArr["quarter"] = $Qkey;
                                $vatDueArr["FIRST"] = $qDetails["FIRST"];
                                $vatDueArr["SECOND"] = $qDetails["SECOND"];
                                $vatDueArr["VATRegistrationNo"] = $params["VATRegistrationNo"];

                                /*
                                  // Calculate dynamic end Date for every year
                                  $EndDateMD = date("-m-d", strtotime($res["EndDate"]));
                                  if( $multiple ){
                                  $EndDateY = $startYear+1;
                                  }else{
                                  $EndDateY = $startYear;
                                  }
                                  $vatDueArr["dEndDate"] = $EndDateY.$EndDateMD;
                                 */

                                $newResult[] = $vatDueArr;
                                unset($vatDueArr);
                            }
                        }
                    }
                } else { // if Company is not VAT Registered remove from list and do not display
                    unset($result[$key]);
                }
            }

            if (count($newResult) > 0) {
                foreach ($newResult as $nkey => $nRec) {
                    $CVATresult = $this->db->query("SELECT * FROM (`" . $prefix . "vats`) WHERE `quarter` = '" . $nRec["quarter"] . "' AND `year` = '" . $nRec["year"] . "' 
							AND `companyID` = '" . $nRec["CID"] . "' AND `Status` = '3'");
                    //$CVATresult = $this->db->get($prefix."_vats");
                    // echo $this->db->last_query();

                    if ($CVATresult->num_rows() > 0) { // if VAT paid remove from list and do not display
                        // pr( $newResult[$nkey] );
                        unset($newResult[$nkey]);
                    }
                }
            }

            usort($newResult, array($this, 'sort_as_vatdue')); // Sort according to VAT Due date ASC
            // prd( $newResult );
            return $newResult;
        } else {
            return array();
        }
    }

    function sort_as_vatdue($a, $b) {
        $t1 = strtotime($a['SECOND']);
        $t2 = strtotime($b['SECOND']);
        return $t1 - $t2;
    }

    public function markAccountsFiled() {
        $prefix = $this->db->dbprefix;
        $response = array("error" => "", "success" => "");
        $companyId = $this->encrypt->decode($this->input->post('Identifier'));
        $clientId = $this->encrypt->decode($this->input->post('person'));
        $filedID = 0;
        $TBfiledID = 0;

        // calculate the year to be marked as filed
        $EndDate = getColumns($where = array("CID" => $companyId), $columns = "EndDate");
        $EndDate = $EndDate[0]["EndDate"];
        $year = getFileYear($EndDate);
        $year = $year["value"];

        $nxtTBYear = getNxtTBYear($EndDate); // to move values into next year
        $nxtTBYear = $nxtTBYear["value"];

        // to move values into next year if year end date is 31/12/currentyear
        $mYear = explode("/", $year);
        $mNxtTBYear = explode("/", $nxtTBYear);
        if ($mYear[0] == $mYear[1] && $mNxtTBYear[0] == $mNxtTBYear[1]) {
            $tempYear = $mYear[0] - 1;
            $year = $tempYear . "/" . $mYear[0];
            $nxtTBYear = $mYear[0] . "/" . $mNxtTBYear[0];
        }

        if ((int) $companyId <= 0) {
            // error if company ID not found
            $response['error'][] = $this->lang->line("ERROR_NO_COMPANY_ACCOUNT");
            return $response;
        }

        // Mark Accounts Year as Filed
        $query = " INSERT INTO `" . $prefix . "accounts_filed` (`year`,`clientId`,`companyId`) VALUES ( '$year', '$clientId', '$companyId' ) ";
        $query = $this->db->query($query);
        if ($this->db->insert_id() <= 0) {
            $response['error'][] = $this->lang->line("ERROR_CANT_MARK_ACCOUNT_FILED");
        } else {
            $filedID = $this->db->insert_id();
        }

        // Update the Company Year End date
        $dateUpdated = false;
        $query = " UPDATE `" . $prefix . "company` SET EndDate = DATE_ADD(EndDate, INTERVAL 1 YEAR) WHERE CID=" . $companyId;
        $query = $this->db->query($query);
        if ($this->db->affected_rows() <= 0) {
            $response['error'][] = $this->lang->line("ERROR_CANT_MARK_ACCOUNT_FILED");
        } else {
            $dateUpdated = true;
        }


        //  Trial balance carry forward
        $tbCquery = " SELECT * FROM `" . $prefix . "tb_carry_fwd` WHERE disabled='0' ";
        $tbCres = $this->db->query($tbCquery);
        $carriedFWD = FALSE;
        if ($tbCres->num_rows() > 0) {
            $tbCresult = $tbCres->result_array();
            $toFWD = array();
            $PLAdded = FALSE;
            foreach ($tbCresult as $tbCrow) {

                $where = array();
                $where["year"] = $year;
                $where["clientId"] = $clientId;
                $where["companyId"] = $companyId;
                $where["category_id"] = get_trial_balance_category($tbCrow['host_catKey']);
                $hostAmount = getColumns($where, "amount", $prefix . "trial_balance");

                $amount = 0;
                if ($hostAmount) {
                    if (isset($hostAmount[0])) {
                        $amount = $hostAmount[0]["amount"];
                    } else if (is_array($hostAmount) && count($hostAmount) > 0) {
                        $amount = $hostAmount["amount"];
                    }
                }

                $tKey = $tbCrow['target_catKey'];

                if (!isset($toFWD[$tKey])) {
                    $toFWD[$tKey] = 0;
                }

                if ($tKey == "PLA_BFWD" && !$PLAdded) { // if PLA carry forward all P/L entries
                    $totalPLQuery = "SELECT SUM(`b`.`amount`) as `total_PL` FROM `" . $prefix . "trial_balance_categories` as `a` \n"
                            . " LEFT JOIN `" . $prefix . "trial_balance` as `b` ON `a`.`id`=`b`.`category_id` \n"
                            . " WHERE `a`.`parent`!='0' \n"
                            . " AND `a`.`status`='1' \n"
                            . " AND `a`.`type`='P/L' \n"
                            . " AND `b`.`clientId`='$clientId' \n"
                            . " AND `b`.`companyId`='$companyId' \n"
                            . " AND `b`.`year`='$year' \n";
                    $totalPLQuery = $this->db->query($totalPLQuery);
                    if ($totalPLQuery->num_rows() > 0) {
                        $totalPLResult = $totalPLQuery->result_array();
                        $toFWD[$tKey] = $toFWD[$tKey] + $totalPLResult[0]["total_PL"];
                    }
                    $PLAdded = TRUE;
                }

                switch ($tbCrow['fxn']) {

                    case "SUB":
                        $toFWD[$tKey] = $toFWD[$tKey] - $amount;
                        break;

                    default:
                        $toFWD[$tKey] = $toFWD[$tKey] + $amount;
                        break;
                }
            }

            if (!empty($toFWD) && count($toFWD) > 0) {
                $carriedFWD = $this->carryFwdTB($toFWD, $nxtTBYear, $clientId, $companyId);
            }
        }



        // Mark Trial Balance Year as Filed
        $query = " INSERT INTO `" . $prefix . "tb_filed` (`year`,`clientId`,`companyId`) VALUES ( '$year', '$clientId', '$companyId' ) ";
        $query = $this->db->query($query);
        if ($this->db->insert_id() <= 0) {
            $response['error'][] = $this->lang->line("ERROR_CANT_MARK_ACCOUNT_FILED");
        } else {
            $TBfiledID = $this->db->insert_id();
        }

        if (!empty($response['error'])) { // revert EVERYTHING back if an error
            if ($filedID > 0) {
                $this->removeFiled($prefix . "accounts_filed", $filedID);
                $response['error']["REVERTED"] = $this->lang->line("ERROR_MARK_REVERTED_ACCOUNT");
            }

            if ($TBfiledID > 0) {
                $this->removeFiled($prefix . "tb_filed", $TBfiledID);
                $response['error']["REVERTED"] = $this->lang->line("ERROR_MARK_REVERTED_ACCOUNT");
            }

            if ($dateUpdated) {
                $query = " UPDATE `" . $prefix . "company` SET EndDate = DATE_SUB(EndDate, INTERVAL 1 YEAR) WHERE CID=" . $companyId;
                $query = $this->db->query($query);
                if ($this->db->affected_rows() <= 0) {
                    $response['error'][] = $this->lang->line("ERROR_MARK_NOT_REVERTED_ACCOUNT");
                } else {
                    $response['error']["REVERTED"] = $this->lang->line("ERROR_MARK_REVERTED_ACCOUNT");
                }
            }

            if ($carriedFWD) {
                $this->carryFwdTB($toFWD, $nxtTBYear, $clientId, $companyId, "REVERT");
                $response['error']["REVERTED"] = $this->lang->line("ERROR_MARK_REVERTED_ACCOUNT");
            }
        } else {
            $response['success'][] = $this->lang->line("SUCCESS_MARK_FILED_ACCOUNT");
        }

        return $response;
    }

    public function markReturnsFiled() {
        $prefix = $this->db->dbprefix;
        $response = array("error" => "", "success" => "");
        $companyId = $this->encrypt->decode($this->input->post('Identifier'));
        $clientId = $this->encrypt->decode($this->input->post('person'));
        $filedID = 0;

        // calculate the year to be marked as filed
        $ReturnDate = getColumns($where = array("CID" => $companyId), $columns = "ReturnDate");
        $ReturnDate = $ReturnDate[0]["ReturnDate"];
        $year = getFileYear($ReturnDate);
        $year = $year["value"];

        if ((int) $companyId <= 0) {
            // error
            $response['error'][] = $this->lang->line("ERROR_NO_COMPANY_RETURN");
            return $response;
        }

        // Mark Returns Year as Filed
        $query = " INSERT INTO `" . $prefix . "returns_filed` (`year`,`clientId`,`companyId`) VALUES ( '$year', '$clientId', '$companyId' ) ";
        $query = $this->db->query($query);
        if ($this->db->insert_id() <= 0) {
            $response['error'][] = $this->lang->line("ERROR_CANT_MARK_RETURN_FILED");
        } else {
            $filedID = $this->db->insert_id();
        }

        // Update the Company Return Date
        $dateUpdated = false;
        $query = " UPDATE `" . $prefix . "company` SET ReturnDate = DATE_ADD(ReturnDate, INTERVAL 1 YEAR) WHERE CID=" . $companyId;
        $query = $this->db->query($query);
        if ($this->db->affected_rows() <= 0) {
            $response['error'][] = $this->lang->line("ERROR_CANT_MARK_RETURN_FILED");
        } else {
            $dateUpdated = true;
        }


        if (!empty($response['error'])) { // revert EVERYTHING back if an error
            if ($filedID > 0) {
                $this->removeFiled($prefix . "returns_filed", $filedID);
                $response['error'][] = $this->lang->line("ERROR_MARK_REVERTED_RETURN");
            }

            if ($dateUpdated) {
                $query = " UPDATE `" . $prefix . "company` SET ReturnDate = DATE_SUB(ReturnDate, INTERVAL 1 YEAR) WHERE CID=" . $companyId;
                $query = $this->db->query($query);
                if ($this->db->affected_rows() <= 0) {
                    $response['error'][] = $this->lang->line("ERROR_MARK_NOT_REVERTED_RETURN");
                } else {
                    $response['error'][] = $this->lang->line("ERROR_MARK_REVERTED_RETURN");
                }
            }
        } else {
            $response['success'][] = $this->lang->line("SUCCESS_MARK_FILED_RETURN");
        }

        return $response;
    }

    public function removeFiled($tblName, $ID) {

        $this->db->where('id', $ID);
        $this->db->delete($tblName);
    }

    function carryFwdTB($toFWD, $nxtTBYear, $clientId, $companyId, $action = "") { // "REVERT" 
        $carriedFWD = FALSE;

        $user = $this->session->userdata('user');
        $aAccess = $user['UserID'];

        foreach ($toFWD as $targetKey => $FWDamount) {
            if ($FWDamount != 0) {

                // pr( $toFWD );

                $TBCatId = get_trial_balance_category($targetKey); //get category ID for given key
                if (empty($action) || $action == "") {
                    $TBentryId = store_trial_entry_acc($TBCatId, $nxtTBYear, $clientId, $companyId, $FWDamount);
                    // echo $TBentryId;die();
                    if ((int) $TBentryId > 0) {
                        add_trial_details($TBentryId, "TBFWD", $targetKey, $aAccess, $nxtTBYear, $FWDamount);
                    }
                } else {
                    $TBentryId = store_trial_entry_acc($TBCatId, $nxtTBYear, $clientId, $companyId, $FWDamount, "SUBTRACT");
                    if ((int) $TBentryId > 0) {
                        add_trial_details($TBentryId, "TBFWD", $targetKey, $aAccess, $nxtTBYear, $FWDamount, "SUBTRACT");
                    }
                }
                $carriedFWD = TRUE;
            }
        }
        return $carriedFWD;
    }

    //Bulk Upload Script 16-05-2015
    public function getCompanyIdfromCompanyName() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $userID = $user['UserID'];
        $tbCquery = "SELECT Name,ClientID FROM `" . $prefix . "company` where AddedBy = '" . $userID . "'";
        $tbCres = $this->db->query($tbCquery);
        $result = $tbCres->result_array();
        return $result;
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
        $this->db->insert('files', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return FALSE;
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
        $query .= ' AND Category="' . $entries['Category'] . '" AND (MoneyOut="' . $entries['MoneyOut'] . '" OR MoneyIn="' . $entries['MoneyIn'] . '")' . $where . ' AND Balance = "' . $entries['Balance'] . '" AND AddedBy = ' . $user['UserID'];
        $query = $this->db->query($query);
        //echo $this->db->last_query();

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
        // echo '<pre>';
        // print_r($this->db->last_query());
        // echo '</pre>';
        // die;
    }

    function save_bulk_statements($data) {
     // echo "<pre>"; print_r($data); echo "</pre>sdfsdfsdfsdf";die;
        $prefix = $this->db->dbprefix;
        $tableName = $prefix . 'bank_statements';
        ini_set('max_execution_time', 1200);
        if (count($data) > 0) {
            foreach ($data as $key => $val) {
                $insert_bulk_statments[] = array(
                    'TransactionDate' => $val['TransactionDate'],
                    'Type' => $val['Type'],
                    'Description' => $val['Description'],
                    'MoneyOut' => $val['MoneyOut'],
                    'MoneyIn' => $val['MoneyIn'],
                    'Balance' => $val['Balance'],
                    'Category' => $val['Category'],
                    'StatementType' => $val['StatementType'],
                    'FileID' => $val['FileID'],
                    'AddedBy' => $val['AddedBy'],
                    'AddedOn' => $val['AddedOn'],
                    'AccountantAccess' => $val['AccountantAccess'],
                    'AssociatedWith' => $val['AssociatedWith'],
                    'CheckBalance' => $val['CheckBalance'],
                );
            }
            $this->db->insert_batch($tableName, $insert_bulk_statments);
        }
        $_SESSION['lastAssociateWith'] = $val['AssociatedWith'];
    }

    public function getLastBulkUploadAssociatedWithId() {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $userID = $user['UserID'];
        $orderby = " ORDER BY `cashman_bank_statements`.`ID` DESC LIMIT 1";
        $query = "SELECT AssociatedWith ";
        $query .= " FROM " . $prefix . "bank_statements";
        $query .= " WHERE AddedBy = " . $userID . " AND AssociatedWith != 0 OR AssociatedWith > 0";
        $query .=' ' . $orderby;
        $qr = $this->db->query($query);
        $query_result = $qr->row();
        return $query_result->AssociatedWith;
    }

    public function getItems($limit = BANK_PAGINATION_LIMIT, $start = 0, $filter = null) {
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata('user');
        $order = $this->session->userdata('BankSortingOrder');
        if (isset($order) && !empty($order)) {
            $orderby = " ORDER BY " . $order . ' LIMIT ' . $start . ',' . $limit;
        } else {
            $orderby = " ORDER BY s.TransactionDate,s.ID DESC LIMIT " . $start . "," . $limit;
        }
        $search = $this->session->userdata('Bulk_BankSearch');
        $where = $this->search($start, $limit, $filter);

        $query = "SELECT s.ID,s.TransactionDate,s.Description,s.Type,s.Category,s.Balance,";
        $query .= "s.StatementType,s.MoneyOut,s.MoneyIn,s.CheckBalance,CONCAT( u.FirstName, ' ', u.LastName ) AS Name, c.Name AS CompanyName";
        $query .= " FROM " . $prefix . "bank_statements s, " . $prefix . "users u, " . $prefix . "company c";
        $query .= $where;
        if (!empty($search)) {
            $search_query = $this->db->query($query);
            $this->session->set_userdata('bankSearchRecords', $search_query->num_rows());
        }

        $query .=' ' . $orderby;

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

    public function search($start, $limit, $filter) {
        $getLastBulkUploadId = $this->getLastBulkUploadAssociatedWithId();
        $search = $this->session->userdata('Bulk_BankSearch');
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
                // echo "<pre>";print_r($search);echo '</pre>';
                if (count($search) <= 0) {
                    $where = '';
                } else {
                    foreach ($search as $key => $val) {
                        if ($key == "client_name") {
                            $where[] = " CONCAT_WS( ' ', u.FirstName, u.LastName ) Like'%" . $val . "%'";
                            //echo "<pre>";print_r($where);echo '</pre>';							
                        } elseif ($key == "companyname") {
                            $where[] = " c.Name Like'%" . $val . "%'";
                            //echo "<pre>";print_r($where);echo '</pre>';
                        }
                        if ($key == "StartDate") {
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
            $where = ' WHERE s.AssociatedWith = c.ClientID AND u.ID = c.ClientID AND s.AddedBy = c.AddedBy AND  ' . "s.AddedBy=" . $userID . " AND s.AssociatedWith= '" . $getLastBulkUploadId . "'";
        } else {
            if ($search != '') {
                //$where = ' WHERE s.AssociatedWith = c.ClientID AND u.ID = c.ClientID AND ' . "s.AddedBy=" . $userID ;				
                $where = implode(' AND ', $where);
                //echo "<pre>"; print_r($where);
                $where = ' WHERE ' . $where . ' AND ' . $filter . 's.AssociatedWith = c.ClientID AND u.ID = c.ClientID AND s.AddedBy = c.AddedBy AND s.AddedBy=' . $userID;
                //echo $where;
            } else {
                $where = implode(' AND ', $where);
                $where = ' WHERE ' . $where . ' AND ' . $filter . 's.AddedBy=' . $userID . " or s.AssociatedWith= '" . $getLastBulkUploadId . "'";
            }
        }
        return $where;
    }

    public function BulkSearchUsingClientId() {
        $user = $this->session->userdata('user');
        $userID = $user['UserID'];
        $search = $this->session->userdata('Bulk_BankSearch');
        $companyname = $search['companyname'];
        $client_name = $search['client_name'];
        $select = "SELECT s.ID, s.TransactionDate, s.Description, s.Type, s.Category, s.Balance, s.StatementType, s.MoneyOut, s.MoneyIn, s.CheckBalance, CONCAT( u.FirstName, ' ', u.LastName ) AS Name, c.Name AS CompanyName FROM cashman_bank_statements s, cashman_users u, cashman_company c WHERE s.AssociatedWith = c.ClientID AND u.ID = c.ClientID AND s.AddedBy = '" . $userID . "' AND CONCAT_WS( ' ', u.FirstName, u.LastName ) LIKE '%" . $client_name . "%' OR c.Name LIKE '%" . $companyname . "%' ORDER BY `Name` ASC";
        return $select;
    }

    public function totalEntries() {
        $getLastBulkUploadId = $this->getLastBulkUploadAssociatedWithId();
        $search = $this->session->userdata('bankSearch');
        $user = $this->session->userdata('user');
        $user = $user['UserID'];
        $totalRecord = $this->session->userdata('bankSearchRecords');
        if (isset($totalRecord) && !empty($totalRecord)) {
            return $totalRecord;
        }
        $this->db->where('AddedBy', $user);
        $this->db->where('AssociatedWith = ', $getLastBulkUploadId);
        $records = $this->db->count_all_results('bank_statements');
        //echo $records;
        if ($records > 0) {
            return $records;
        } else {
            return 0;
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
            return TRUE;
        } else {
            return FALSE;
        }

        $query = "DELETE FROM " . $prefix . "tb_details WHERE id IN (" . $id . ") AND source = 'BANK'";
        $query = $this->db->query($query);
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }
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

}
