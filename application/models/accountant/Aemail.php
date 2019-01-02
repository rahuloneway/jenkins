<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Aemail extends CI_Model {

    public function Aemail() {
        parent::__construct();
    }

    public function get_annual_items($id = NULL, $limit = EMAIL_PAGINATION_LIMIT, $start = 0, $filter = null) {
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
        $query .= " ORDER BY c.EndDate ASC limit $start,$limit";
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            return array();
        }
    }

    public function totalentriesAccountdue() {
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
            return count($result);
        } else {
            return 0;
        }
    }

    public function get_return_items($id = NULL, $limit = EMAIL_PAGINATION_LIMIT, $start = 0, $filter = null) {
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
        $query .= " ORDER BY c.ReturnDate ASC limit $start,$limit";
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            return array();
        }
    }

    public function totalentriesReturnitem() {
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
            return count($result);
        } else {
            return 0;
        }
    }

    public function get_vatdue_items($id = NULL, $limit = EMAIL_PAGINATION_LIMIT, $start = 0,	$filter = null) {
		
        $prefix 		= $this->db->dbprefix;
        $user 			= $this->session->userdata('user');		
		
		if (!empty($id)) {
            $where 		.= ' u.ID = ' . $id;
        }else{			
			$where 		= '';
		}								
		
        if (empty($user['AddedBy'])) {
            $where .= ' AND u.AddedBy=' . $user['UserID'];
        } else {
            $where .= ' AND (u.AddedBy=' . $user['AddedBy'] . ' OR u.SubParent =' . $user['SubParent'] . ' )';
        }
        $query = "SELECT CONCAT(u.FirstName,' ',u.LastName) AS Name,u.ID,c.Name AS CompanyName,c.RegistrationNo,";
        $query .= "u.SubParent,c.ClientID,c.Params AS CompanyParams,c.CID ,c.IncorporationDate,c.ReturnDate,c.EndDate,c.AddedOn FROM " . $prefix . "users AS u";
        $query .= " LEFT JOIN " . $prefix . "company AS c ON c.ClientID = u.ID WHERE u.UserType = 'TYPE_CLI'  $where limit $start,$limit";
		$query = $this->db->query($query);
		//echo $this->db->last_query(); //die;
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $newResult = array();
            foreach ($result as $key => $res) {
                // prd( $res );
				//echo "<pre>";print_r($res); die;
                $params = unserialize($res["CompanyParams"]);
                $VATQuaters = $params["VATQuaters"];
                $comEndDate = cDate($res["EndDate"]);
                if ((int) $params["VATRegistrationNo"] > 0 && $comEndDate != "" && $comEndDate != "01-01-1970" && $comEndDate != "00-00-0000") { // Only display if Company is VAT Registered 
                    $VQresult = $this->db->query("SELECT * FROM (`" . $prefix . "vat_quarters`) WHERE `ClientID` = '" . $res["ClientID"] . "'");
                    //echo $this->db->last_query(); die;
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
                        //$dropStartYear = date("Y", strtotime($res["EndDate"])) - 2;
						$dropStartYear = date("Y")-1;
                        //if (( $dropStartYear ) > APP_START_YEAR) { // if end date is after project start date then reset to project start year
                            //$dropStartYear = APP_START_YEAR;
                        //}

                        for ($startYear = $dropStartYear; $startYear <= $currentYear; $startYear++) {

                            // get default quarters
                            $quaters = vatQuaters($params["VATQuaters"], $startYear);

                            // Get quarters in paying sequence for a Accounting Year if diff from default quarters
                            $VATquaters = getDueVatQuarters($res["ClientID"], $startYear);
							//echo "<pre>";print_r($VATquaters); die;
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

			//echo "<pre>"; print_r($newResult); echo "</prE>"; die;
			
            if (count($newResult) > 0) {
                foreach ($newResult as $nkey => $nRec) {
                    //$CVATresult = $this->db->query("SELECT * FROM (`" . $prefix . "vats`) WHERE `quarter` = '" . $nRec["quarter"] . "' AND `year` = '" . $nRec["year"] . "' AND `companyID` = '" . $nRec["CID"] . "' AND `Status` = '3'");
					$CVATresult = $this->db->query("SELECT * FROM (`" . $prefix . "vats`) WHERE `quarter` = '" . $nRec["quarter"] . "' AND `year` = '" . $nRec["year"] . "' AND `companyID` = '" . $nRec["CID"] . "' AND `vatDueRequest` = '1'");                    
					//$CVATresult = $this->db->get($prefix."_vats");                  
                    if ($CVATresult->num_rows() > 0) {
						// if VAT paid remove from list and do not display
                        // pr( $newResult[$nkey] );
                        unset($newResult[$nkey]);
                    }else{
						//unset($newResult[$nkey]);
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

    function uniqueAssocArray($array, $uniqueKey) {
        if (!is_array($array)) {
            return array();
        }
        $uniqueKeys = array();
        foreach ($array as $key => $item) {
            $groupBy = $item[$uniqueKey];
            if (isset($uniqueKeys[$groupBy])) {
                //compare $item with $uniqueKeys[$groupBy] and decide if you 
                //want to use the new item
                $replace = false;
            } else {
                $replace = true;
            }
            if ($replace)
                $uniqueKeys[$groupBy] = $item;
        }
        return $uniqueKeys;
    }

    public function totalentriesVatdue() {
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
        //echo $this->db->last_query()."###";
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
                    //echo $this->db->last_query();
                    if ($row = $VQresult->row()) {
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
            return count($this->uniqueAssocArray($newResult, 'RegistrationNo'));
        } else {
            return 0;
        }
    }

    function sort_as_vatdue($a, $b) {
        $t1 = strtotime(@$a['SECOND']);
        $t2 = strtotime(@$b['SECOND']);
        return $t1 - $t2;
    }

    public function saveTemplate($data = NULL) {
        if (!empty($data)) {
            $prefix = $this->db->dbprefix;
            $this->db->insert($prefix . 'email_template', $data);
            return TRUE;
        }
    }

    public function getTemplatename($type) {
        $prefix = $this->db->dbprefix;
        $this->db->select('Id,Template_Name');
		$this->db->where('Template_Type', $type);
        $this->db->order_by('Template_Name', 'ASC');
        $query = $this->db->get($prefix . "email_template");
        $result = $query->result_array();
        return $result;
    }

    public function choseTemplatename($id = NULL) {
        if (!empty($id)) {
            $prefix = $this->db->dbprefix;
            $this->db->select('Template_Text');
            $this->db->where('Id', $id);
            $query = $this->db->get($prefix . "email_template");
            $result = $query->result_array();
            return $result[0]['Template_Text'];
        }
    }
	public function getemailDetails($id=NULL){
        if(!empty($id)){
            $prefix = $this->db->dbprefix;
            $this->db->select('Subject,Body');
            $this->db->where('Id', $id);
            $query = $this->db->get($prefix . "email_tracking");
            $result = $query->result_array();
            return $result;
        }        
    }
	public function dueVatMailAcction($cId,$quarter,$acction,$year){
	
        $prefix = $this->db->dbprefix;
		$this->db->where('companyID', $cId);
		$this->db->where('quarter', $quarter);
		$this->db->where('year', $year);	
		if($acction == 'accept'){
			$this->db->set('vatDueRequest','1');			
		}
		if($acction == 'rejects'){
			$this->db->set('vatDueRequest','2');			
		}	
		$this->db->update($prefix . 'vats');	
		//echo $this->db->last_query();
	}	
	
	public function totalentriesBankstatementitem() {
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
            return count($result);
        } else {
            return 0;
        }
    }
	
	public function get_bankStatmentdue_items($id = NULL, $limit = EMAIL_PAGINATION_LIMIT, $start = 0,	$filter = null) {
				
		$prefix 		= $this->db->dbprefix;
        $user 			= $this->session->userdata('user');		
		
		if (!empty($id)) {
            $where 		.= ' u.ID = ' . $id;
        }else{			
			$where 		= '';
		}				
		
        if (empty($user['AddedBy'])) {
            $where .= ' AND u.AddedBy=' . $user['UserID'];
        } else {
            $where .= ' AND (u.AddedBy=' . $user['AddedBy'] . ' OR u.SubParent =' . $user['SubParent'] . ' )';
        }
        $query = "SELECT CONCAT(u.FirstName,' ',u.LastName) AS Name,u.ID,c.Name AS CompanyName,c.RegistrationNo,";
        $query .= "u.SubParent,c.ClientID,c.Params AS CompanyParams,c.CID ,c.IncorporationDate,c.ReturnDate,c.EndDate FROM " . $prefix . "users AS u";
        $query .= " LEFT JOIN " . $prefix . "company AS c ON c.ClientID = u.ID WHERE u.UserType = 'TYPE_CLI'  $where limit $start,$limit";
		$query = $this->db->query($query);
		//echo $this->db->last_query(); //die;
        if ($query->num_rows() > 0) {
            $result = $query->result_array();		
			
			$last_uplode_date = array();
			$row = 0;
			/*foreach($result as $val){
				//echo "<pre>";print_r($val); die;
				$last_uplode_date = getBankStatmentLastUploadDate($val['ID'],$val['CID']);
				$result[$row]['last_uplode_date'] = $last_uplode_date;
				$row = $row+1;
			}*/		
			
            $newResult = $result;
            usort($newResult, array($this, 'sort_as_vatdue')); // Sort according to VAT Due date ASC
            // prd( $newResult );	
			return $newResult;
        } else {
            return array();
        }		
    }
			
    #Function to generate 'Vat Quarters' for a 'Company', 'Client', for a given Year ( Used in VAT Summary ).    
    /*function getVatQuarters($clientId,$companyId,$vYear = NULL) {     
        $prefix 		= $this->db->dbprefix;	   
        $vatQuaters = false;
        $this->db->select('*');
        $result = $this->db->get_where('vat_quarters', array('ClientID' => $clientId,'companyID' => $companyId));
		//echo $this->db->last_query();
        if ($row = $result->row()) {

            $VATYear = $this->session->userdata('VATYear');
            if (!empty($vYear)) {
                $year = $vYear;
            } else if (!empty($VATYear)) {
                $year = $VATYear;
            } else {
                $year = date("Y");
            }
            // echo "<pre>"; print_r( $row );

            for ($i = 1; $i <= 4; $i++) { // can be MAX 4 Quarters only
                $sQuater = "q" . $i . "_start";
                $eQuater = "q" . $i . "_end";

                if (!isset($prevDate)) {
                    $prevDate = $vatQuaters[$i]["FIRST"];
                }

                if (isset($prevDate) && strtotime($prevDate) > strtotime($year . "-" . $row->$sQuater . "-01")) {
                    $year++;
                }
                $prevDate = $vatQuaters[$i]["FIRST"] = $year . "-" . $row->$sQuater . "-01";


                if (strtotime($prevDate) > strtotime($year . "-" . $row->$eQuater . "-" . date('t', strtotime($year . "-" . $row->$eQuater . "-01")))) {
                    $year++;
                }
                $prevDate = $vatQuaters[$i]["SECOND"] = $year . "-" . $row->$eQuater . "-" . date('t', strtotime($year . "-" . $row->$eQuater . "-01"));
            }
        }
        // echo "<pre>"; print_r( $vatQuaters );die();
        return $vatQuaters;
    }*/
	
	#Get all invoice details by client and company id
	/*public function getVatDetails($clientId, $companyId, $vatQuatersFirst, $vatQuatersSecond) {
	
        $prefix = $this->db->dbprefix;
        $userID = $clientId;
        // echo "<pre>"; print_r( $VatQuarters ); die();		
        $where[] = " i.PaidOn >= '". $vatQuatersFirst . "' AND i.PaidOn <= '" . $vatQuatersSecond . "' ";
        
        $where[] = " i.AddedBy=" . $userID . " ";
		$where[] = " i.CustomerCompanyID=" . $companyId . " ";
        $where[] = " i.Status=3 ";

        $where = " WHERE " . implode(" AND ", $where);
        $query = "SELECT SUM(FlatRate) as total";
        $query .= " FROM " . $prefix . "invoices AS i";      
        $query .= $where;
		
        $result = $this->db->query($query);
		echo $this->db->last_query();
        if ($result->num_rows() > 0) {
            $records = $result->result();
            return $records['total'];
        } else {
            return false;
        }
    }*/
	
	#Function to generate 'Vat Quarters' for a 'Company', 'Client', for a given Year ( Used in VAT Summary ).    
    function getVatQuarters($clientId,$companyId,$first,$second){

        $prefix = $this->db->dbprefix;	   
        $vatQuaters = false;
        $this->db->select('*');
        $result = $this->db->get_where('vat_quarters', array('ClientID' => $clientId,'companyID' => $companyId));
		if ($row = $result->row()) {

            $VATYear = $this->session->userdata('VATYear');
            if (!empty($vYear)) {
                $year = $vYear;
            } else if (!empty($VATYear)) {
                $year = $VATYear;
            } else {
                $year = date("Y");
            }	
						
			if(date("m", strtotime($first)) == $row->q1_start){
				$quarter = 1;
			}
			if(date("m", strtotime($first)) == $row->q2_start){
				$quarter = 2;
			}
			if(date("m", strtotime($first)) == $row->q3_start){
				$quarter = 3;
			}
			if(date("m", strtotime($first)) == $row->q4_start){
				$quarter = 4;
			}					

            /*for ($i = 1; $i <= 4; $i++) { // can be MAX 4 Quarters only
                $sQuater = "q" . $i . "_start";
                $eQuater = "q" . $i . "_end";

                if (!isset($prevDate)) {
                    $prevDate = $vatQuaters[$i]["FIRST"];
                }

                if (isset($prevDate) && strtotime($prevDate) > strtotime($year . "-" . $row->$sQuater . "-01")) {
                    $year++;
                }
                $prevDate = $vatQuaters[$i]["FIRST"] = $year . "-" . $row->$sQuater . "-01";


                if (strtotime($prevDate) > strtotime($year . "-" . $row->$eQuater . "-" . date('t', strtotime($year . "-" . $row->$eQuater . "-01")))) {
                    $year++;
                }
                $prevDate = $vatQuaters[$i]["SECOND"] = $year . "-" . $row->$eQuater . "-" . date('t', strtotime($year . "-" . $row->$eQuater . "-01"));
            }*/
        }
       
        //return $vatQuaters;
		return $quarter;
    }
	
	#Function to get quater by paid date
    function getVatQuarterByPaidDate($clientId,$companyId,$paidDate){
		$prefix = $this->db->dbprefix;	   
        $vatQuaters = false;
        $this->db->select('*');
        $result = $this->db->get_where('vat_quarters', array('ClientID' => $clientId,'companyID' => $companyId));
		
		if ($row = $result->row()) {
			//echo "<pre>"; print_r($row); echo "</pre>";
            $VATYear = $this->session->userdata('VATYear');
            if (!empty($vYear)) {
                $year = $vYear;
            } else if (!empty($VATYear)) {
                $year = $VATYear;
            } else {
                $year = date("Y");
            }	
				
			if($row->q1_end == 01 || $row->q1_end == 02){				
				$q1_end = $row->q1_end + 12;
				$m1 = date("m", strtotime($paidDate)) +12;
			}else{				
				$q1_end = $row->q1_end;
				$m1 = date("m", strtotime($paidDate));
			}
			if($row->q2_end == 01 || $row->q2_end == 02){
				$q2_end = $row->q2_end + 12;
				$m2 = date("m", strtotime($paidDate)) +12;
			}else{
				$q2_end = $row->q2_end;
				$m2 = date("m", strtotime($paidDate));
			}
			if($row->q3_end == 01 || $row->q3_end == 02){
				$q3_end = $row->q3_end + 12;
				$m3 = date("m", strtotime($paidDate))+12;
			}else{
				$q3_end = $row->q3_end;
				$m3 = date("m", strtotime($paidDate));
			}
			if($row->q4_end == 01 || $row->q4_end == 02){
				$q4_end = $row->q4_end + 12;
				$m4 = date("m", strtotime($paidDate))+12;
			}else{
				$q4_end = $row->q4_end;
				$m4 = date("m", strtotime($paidDate));
			}
							
			if($m1 >= $row->q1_start && $m1 <= $q1_end){
				$quarter = 1;
			}else if($m2 >= $row->q2_start && $m2 <= $q2_end){
				$quarter = 2;
			}else if($m3 >= $row->q3_start && $m3 <= $q3_end){
				$quarter = 3;
			}else if($m4 >= $row->q4_start && $m4 <= $q4_end){
				$quarter = 4;
			}					
							
						
        }       
		return $quarter;
	}	
}
?>