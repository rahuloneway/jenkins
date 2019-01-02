<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Trial_balance extends CI_Model {

    public function Trial_balance() {
        parent::__construct();
    }

    /**
     * 	Function to get the list of Trial Balance categories.
     * 	@Return value: array of elements.
     */
    public function getItems() {
		
        $prefix = $this->db->dbprefix;
        $user = $this->session->userdata("user");
        $clientId = $user["UserID"];
        $companyId = $user["CompanyID"];

        $TBYears = getTBYear();
        //echo "<pre>";print_r( $TBYears ); echo "</pre>";
        foreach ($TBYears as $TBKey => $TBYear) {
			//$tbYear = explode('/',$TBYear["value"]);
            $this->db->select("*");
            $this->db->select_sum("amount");
            $this->db->where("year", $TBYear["value"]);
            $this->db->where("clientId", $clientId);
            $this->db->where("companyId", $companyId);
            $this->db->where("deleteStatus!=1");
            $this->db->group_by('category_id');
            $this->db->from("tb_details");
            $query = $this->db->get();
            //echo $this->db->last_query(); //die();
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
				foreach ($result as $value) {
					if($value['amount'] != 0) {
						$newresult[$value["category_id"]] = $value;
					}
                }
                $data[$TBYear["title"]] = $newresult;
                unset($newresult);
                unset($result);
            } else {
                $data[$TBYear["title"]] = array();
            }
        }

        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }
    }

	#Old function which was used befor new trial balance categories.
    public function getTBCats() {
        $prefix = $this->db->dbprefix;
        $query = "SELECT * FROM `" . $prefix . "trial_balance_categories` ";
        $query = $this->db->query($query);

        if ($query->num_rows() > 0) {
            $result = $query->result_array();

            foreach ($result as $record) {
                if ($record["parent"] > 0) {
                    $TBCats[$record["parent"]]["childrens"][$record["id"]] = $record;
                } else {
                    if (isset($TBCats[$record["id"]]["childrens"]))
                        $childrens = $TBCats[$record["id"]]["childrens"];
                    else
                        $childrens = "";
                    $TBCats[$record["id"]] = $record;
                    $TBCats[$record["id"]]["childrens"] = $childrens;
                }
            }
            return $TBCats;
        }else {
            return false;
        }
    }
	public function getTBCats_New() {
        $prefix = $this->db->dbprefix;
        $queryParent = "SELECT * FROM `" . $prefix . "trial_balance_categories` WHERE `parentType` = 2 AND `AnalysisLedgerParent` = 0 ORDER BY `title` ASC";
		$queryParent = $this->db->query($queryParent);
		$resultParent = $queryParent->result_array();
		foreach($resultParent as $valParent){	
			$TBCats[$valParent["id"]] = $valParent;
			$queryChild = "SELECT * FROM `" . $prefix . "trial_balance_categories` WHERE `AnalysisLedgerParent` = ".$valParent['id']." ";
			$queryChild = $this->db->query($queryChild);
			$resultChild = $queryChild->result_array();
			foreach($resultChild as $valChild){
				if ($valChild["parent"] > 0) {
                    $TBCats[$valChild["parent"]]["childrens"][$valChild["id"]] = $valChild;
                } else {
                    if (isset($TBCats[$valChild["id"]]["childrens"]))
                        $childrens = $TBCats[$valChild["id"]]["childrens"];
                    else
                        $childrens = "";
                    
                    $TBCats[$valChild["id"]]["childrens"] = $childrens;
                }
			}
		}		
		
		 return $TBCats;	
        
    }

    public function getplTBCats() {
        $prefix = $this->db->dbprefix;
        $query = "SELECT * FROM `" . $prefix . "trial_balance_categories` order by cat_type DESC ";
        $query = $this->db->query($query);

        if ($query->num_rows() > 0) {
            $result = $query->result_array();

            foreach ($result as $record) {
                if ($record["parent"] > 0) {
                    $TBCats[$record["parent"]]["childrens"][$record["id"]] = $record;
                } else {
                    if (isset($TBCats[$record["id"]]["childrens"]))
                        $childrens = $TBCats[$record["id"]]["childrens"];
                    else
                        $childrens = "";
                    $TBCats[$record["id"]] = $record;
                    $TBCats[$record["id"]]["childrens"] = $childrens;
                }
            }
            return $TBCats;
        }else {
            return false;
        }
    }

    public function getLedgerDetails($TBcatID) {
        $prefix = $this->db->dbprefix;
        $TBcatID = $this->encrypt->decode($TBcatID);
        $TBYears = getTBYear();
        // pr( $TBYears );
        $TBYear = $TBYears[0]["value"];
        $user = $this->session->userdata("user");
        $UserID = $user["UserID"];
        $CompanyID = $user["CompanyID"];
        // prd( $user );

        $where = array(
            "`tbD`.`year`" => $TBYear,
            "`tbD`.`clientId`" => $UserID,
            "`tbD`.`companyId`" => $CompanyID,
            "`tbD`.`category_id`" => $TBcatID
        );
        
        $where['`tbD`.`deleteStatus`'] = '!=1';
        
        // For changes in category of Entry in Ledger Listing
        $ledgerSource = $this->session->userdata('ledgerSource');
        if (isset($_POST["source"]) && !empty($_POST["source"])) { // if category is modified use POST
            $where["`tbD`.`source`"] = $this->input->post("source");
            $this->session->set_userdata('ledgerSource', $this->input->post("source"));
        } else if (!empty($ledgerSource)) { // Else use session data
            $where["`tbD`.`source`"] = $ledgerSource;
        }
        // For changes in category of Entry in Ledger Listing

        $whereQuery = '';
        foreach ($where as $k => $whr) {
            $whereQuery .= " $k='$whr' AND";
        }
        $whereQuery = " WHERE " . rtrim($whereQuery, "AND");

        $query = "SELECT tbD.*,`amount` as `trans_amount` FROM `" . $prefix . "tb_details` as `tbD` "
                . $whereQuery
                . " GROUP BY `itemId` ";
        $query = $this->db->query($query);
        //echo $this->db->last_query(); die('------------');

        $newResult = FALSE;
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            // prd( $result );
            foreach ($result as $key => $row) {
                if ($row["source"] == "JOURNAL") {
                    $detQuery = " SELECT `je`.*,`tbD`.*,`tbD`.`amount` as `trans_amount`, "
                            . "\n CONCAT(`u`.`FirstName` , ' ',`u`.`LastName` ) as  `aAcess` "
                            . "\n FROM `" . $prefix . "tb_details` as `tbD` "
                            . "\n RIGHT JOIN `" . $prefix . "journal_entries` as `je` ON  `je`.`GroupID` = `tbD`.`itemId` "
                            . "\n RIGHT JOIN `" . $prefix . "users` as `u` ON  `tbD`.`AccountantAccess` = `u`.`ID` "
                            . "\n WHERE `tbD`.`itemId`='" . $row["itemId"] . "' "
                            . "\n AND `je`.`AddedBy`=`tbD`.`clientId` "
                            . "\n AND `tbD`.`source`='JOURNAL' "
                            . "\n AND `tbD`.`clientId`='$UserID' "
                            . "\n GROUP BY `je`.`ID` ";
                } else {
                    $detQuery = " SELECT `tbD`.*,`tbD`.`amount` as `trans_amount`, "
                            . "\n CONCAT(`u`.`FirstName` , ' ',`u`.`LastName` ) as  `aAcess` "
                            . "\n FROM `" . $prefix . "tb_details` as `tbD` "
                            . "\n RIGHT JOIN `" . $prefix . "users` as `u` ON  `tbD`.`AccountantAccess` = `u`.`ID` "
                            . "\n WHERE `tbD`.`itemId`='" . $row["itemId"] . "' "
                            . "\n AND `tbD`.`clientId`='$UserID' "
                            . "\n AND `tbD`.`deleteStatus`!='1' ";
                }
                $detQuery = $this->db->query($detQuery);
               // echo $this->db->last_query(); die();
                if ($detQuery->num_rows() > 0) {
                    $resQuery = $detQuery->result_array();

                    foreach ($resQuery as $resKey => $resRow) {
                        // Get name of the source type to be displayed to user from DB
                        $resRow["type_name"] = $this->getSourceTypeName($resRow);
                        // Get name of the source to be displayed to user According to Entry
                        $resRow["source_name"] = $this->getSourceName($resRow["source"]);
                        // Get URL for details to be displayed to user in popup according to Entry
                        $resRow["details_url"] = $this->getDetailsLink($resRow);

                        if ($row["source"] == "JOURNAL") {

                            // Get name of the Transection type ( CR or DB ) to be displayed to user According to Amount
                            $resRow["trans_type"] = $resRow["JournalType"];

                            if ($resRow["JournalType"] == "CR")
                                $resRow["trans_amount"] = -1 * $resRow["Amount"];
                            else
                                $resRow["trans_amount"] = $resRow["Amount"];

                            if ($resRow["Category"] == $TBcatID && !isset($newResult[$row["itemId"]]["primary"]) && empty($newResult[$row["itemId"]]["primary"])) {
                                $newResult[$row["itemId"]]["primary"] = $resRow;
                            } else {
                                $newResult[$row["itemId"]][$resRow["Category"]] = $resRow;
                            }
                        } else {

                            // Get name of the Transaction type ( CR or DB ) to be displayed to user According to Amount
                            $resRow["trans_type"] = $this->getTransType($resRow["trans_amount"]);

                            if ($resRow["category_id"] == $TBcatID) {
                                if (isset($newResult[$row["itemId"]]["primary"]) && !empty($newResult[$row["itemId"]]["primary"])) {
                                    $resRow["trans_amount"] = $newResult[$row["itemId"]]["primary"]["trans_amount"] + $resRow["trans_amount"];
                                }
                                $newResult[$row["itemId"]]["primary"] = $resRow;
                            } else {
                                if (isset($newResult[$row["itemId"]][$resRow["category_id"]]) && !empty($newResult[$row["itemId"]][$resRow["category_id"]])) {
                                    $resRow["trans_amount"] = $newResult[$row["itemId"]][$resRow["category_id"]]["trans_amount"] + $resRow["trans_amount"];
                                }
                                $newResult[$row["itemId"]][$resRow["category_id"]] = $resRow;
                            }
                        }
                    }
                }
            }
        }

        // prd( $newResult );
        return $newResult;
    }

    function getSourceTypeName($source, $column = "title") {
        $prefix = $this->db->dbprefix;
        if ($source["source"] == "JOURNAL") {
            $type_name = getColumns(array("id" => $source["Category"]), $column, $prefix . "trial_balance_categories");
        } else {
            $type_name = getColumns(array("catKey" => $source["type"]), $column, $prefix . "trial_balance_categories");
        }

        return $type_name[0][$column];
    }

    function getTransType($amount) {
        if ($amount < 0)
            return "CR";
        else
            return "DB";
    }

    function getSourceName($source) {
        switch ($source) {

            case "INVOICE":
                $source_name = "Invoice";
                break;

            case "SALARY":
                $source_name = "Salary";
                break;

            case "EXPENSE":
                $source_name = "Expense";
                break;

            case "PAYEE": // Never comes here, NO entry for PAYEE is coming from System, it only comes from INVOICE or BANK
                $source_name = "Payee";
                break;

            case "VAT": // Never comes here, NO entry for VAT is coming from System, it only comes from INVOICE or BANK
                $source_name = "VAT";
                break;

            case "DIVIDEND":
                $source_name = "Dividend";
                break;

            case "JOURNAL":
                $source_name = "Journal";
                break;

            case "TBFWD":
                $source_name = "Entry carried forward";
                break;

            case "BANK":
                $source_name = "Bank statement";
                break;

            default:
                $source_name = "Bank statement";
                break;
        }
        return $source_name;
    }

    function getDetailsLink($resRow) {
        $prefix = $this->db->dbprefix;
        $source = $resRow["source"];
        $itemId = $resRow["itemId"];

        $linkAttrs = "";
        switch ($source) {

            case "INVOICE":
                $query = "SELECT * FROM `" . $prefix . "invoices` "
                        . "\n WHERE `InvoiceID`='" . $itemId . "' ";
                $query = $this->db->query($query);
                if ($query->num_rows() > 0) {
                    $result = $query->row_array();
                    $link = $this->encrypt->encode($itemId);
                    $linkAttrs = "<a href='" . $link . "' class='showPaid' >" . $result["InvoiceNumber"] . "</a>";
                }
                break;

            case "SALARY":
                $link = $this->encrypt->encode($itemId);
                $linkAttrs = "<a href='" . $link . "' class='showLedgerDetails' data-type='" . $this->encrypt->encode('viewSalary') . "' >Salary</a>";
                break;

            case "EXPENSE":
                $query = "SELECT `e`.* FROM `" . $prefix . "expenses` as `e` "
                        . "\n INNER JOIN `" . $prefix . "expense_items` as `ei` ON `ei`.`ExpenseID` = `e`.`ID`  "
                        . "\n WHERE `ei`.`ID`='" . $itemId . "' ";
                $query = $this->db->query($query);
				echo $this->db->last_query();
                if ($query->num_rows() > 0) {
                    $result = $query->row_array();
                    if ($result["ExpenseType"] == 'CREDITCARD') {
                        $class = " class='viewExpense creditcard' ";
                    } else {
                        $class = " class='viewExpense' ";
                    }
                    $href = $this->encrypt->encode($result["ID"]);
                    $linkAttrs = "<a  href='" . $href . "' $class >" . $result["ExpenseNumber"] . "</a>";
                }
                break;

            case "PAYEE": // Never comes here, NO entry for PAYEE is coming from System, it only comes from INVOICE or BANK
                $linkAttrs = "Payee";
                break;

            case "VAT": // Never comes here, NO entry for VAT is coming from System, it only comes from INVOICE or BANK
                $linkAttrs = " ";
                break;

            case "DIVIDEND":
                $query = "SELECT * FROM `" . $prefix . "dividends` "
                        . "\n WHERE `DID`='" . $itemId . "' ";
                $query = $this->db->query($query);
                if ($query->num_rows() > 0) {
                    $result = $query->row_array();
                    $link = $this->encrypt->encode($itemId);
                    $linkAttrs = "<a href='" . $link . "' class='viewDividend' >" . $result["VoucherNumber"] . "</a>";
                }
                break;

            case "JOURNAL":
                $link = $this->encrypt->encode($itemId);
                $linkAttrs = "<a href='" . $link . "' class='showLedgerDetails' data-type='" . $this->encrypt->encode('viewJournal') . "' >Journal</a>";
                break;

            case "TBFWD":
                $link = $this->encrypt->encode($resRow["tbId"]);
                // pass trial balance record ID cause there is no Primary key while Carry Forward is performed
                $linkAttrs = "<a href='" . $link . "'  class='showLedgerDetails' data-type='" . $this->encrypt->encode('viewTBFWD') . "' >Entry carried forward</a>";
                break;

            case "BANK":
                $link = $this->encrypt->encode($itemId);
                $linkAttrs = "<a href='" . $link . "' class='showLedgerDetails' data-type='" . $this->encrypt->encode('viewBank') . "' >Bank statement</a>";
                break;

            default:
                break;
        }
        return $linkAttrs;
    }

    function getSalaryDetails($itemId = 0) {
        $prefix = $this->db->dbprefix;
        if ($itemId > 0) {
            $query = "SELECT CONCAT(ce.FirstName,' ',ce.LastName) AS EmployeeName,";
            $query .= "s.ID,s.EID,s.FinancialYear,s.PayDate,s.NIC_Employee,s.Employeer_NIC,s.SMP,s.IncomeTax,";
            $query .= "s.NetPay,s.GrossSalary,s.AddedBy,s.AddedOn,s.PaidDate,s.Status";
            $query .= " FROM " . $prefix . "salary AS s";
            $query .= " LEFT JOIN " . $prefix . "company_customers AS ce ON ce.ID = s.EID";
            $query .= " WHERE `s`.`ID`='" . $itemId . "' ";
            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getJournalDetails($itemId = 0) {
        $prefix = $this->db->dbprefix;
        if ($itemId > 0) {
            $query = "SELECT j.ID,j.JournalType,j.FinancialYear,j.Category,";
            $query .= "j.Status,j.Amount,j.GroupID";
            $query .= " FROM " . $prefix . "journal_entries AS j";
            $query .= " WHERE `GroupID`='" . $itemId . "' ";
            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getTBFWDDetails($itemId = 0) {
        $prefix = $this->db->dbprefix;
        if ($itemId > 0) {
            $query = "SELECT `tbd`.*,`tbc`.`title` as `catTitle` FROM `" . $prefix . "tb_details` AS `tb` ";
            $query .= " LEFT JOIN `" . $prefix . "trial_balance_categories` as `tbc` ON `tbd`.`type`=`tbc`.`catKey` ";
            $query .= " WHERE `tbd`.`tbId`='" . $itemId . "' AND `tbd`.`source`='TBFWD' ";
            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getBankDetails($itemId = 0) {
        $prefix = $this->db->dbprefix;
        if ($itemId > 0) {
            $query = "SELECT s.ID,s.TransactionDate,s.Description,s.Type,s.Category,s.Balance,s.AssociatedWith,b.Name as bankName, ";
            $query .= "s.StatementType,s.MoneyOut,s.MoneyIn,s.CheckBalance";
            $query .= " FROM " . $prefix . "bank_statements AS s";
            $query .= " LEFT JOIN " . $prefix . "banks AS b on b.BID = s.bankId";
            $query .= " WHERE `s`.`ID`='" . $itemId . "' ";
            $query = $this->db->query($query);
            if ($query->num_rows() > 0) {
                $result = $query->result_array();
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}

?>
