<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Trial_balances extends CI_Controller {

    public function Trial_balances() {
        parent::__construct();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        /*
         * 	First check if accountant is accessing the Clients account or not.
         * 	Preventing accountant from direct access to the client's dashboard.
        */
		$user = $this->session->userdata('user');
        if ($user['UserType'] == 'TYPE_CLI' && empty($user['AccountantAccess'])) {
           setRedirect(site_url());
        } else {
            if (isset($_GET['clientID'])) {
                checkUserAccess(array('TYPE_ACC', 'TYPE_CLI'));
            } else {
                checkUserAccess(array('TYPE_CLI'));
            }
        }
        
        /* Load the trial balance model */
        $this->load->model("clients/trial_balance");
    }

    public function index() {	
		$user = $this->session->userdata('user');
		//echo "<pre>";print_r($user);echo "</pre>";
        $ajax = $this->input->post("ajax");
        if (isset($ajax) && $ajax == "true") { 
            $ajax = true;
            $TBYear = $this->input->post("TBYear");
		    $user = $this->session->userdata('user');
            $end_date = $user['CompanyEndDate'];
            $exp = explode('-', $end_date);	
			//print_r($exp);
            if ($exp[1] == 12 && $exp[2] == 31) {
                $expYear = explode('/', $TBYear);
                //$TBYear = ($expYear[0] + 1) . "/" . ($expYear[1] - 1);
                $TBYear = ($expYear[0] + 1) . "/" . ($expYear[1]);
            }
			//echo $TBYear;
            $financial_date = company_year($TBYear);
			//print_r($financial_date);
            $financial_date = $financial_date['end_date'];
            $financial_date = date('jS M', strtotime($financial_date));
            $data['count'] = 1;
            $this->session->set_userdata('TBYear', $TBYear);
        }

        $data['title'] = "Dashboard | Bank Statements";
        $data['page'] = "trial_balances";
        $TBCats = $this->trial_balance->getTBCats();
        //echo "<pre>"; print_r($TBCats); echo "</pre>"; //die;
        if ($TBCats) {
            $data["TBCats"] = $TBCats;
            $TBData = $this->trial_balance->getItems();
		    //echo "<pre>";print_r($TBData); echo "</pre>"; //die('lol');           
		    if ($TBData) {
                $data["TBData"] = $TBData;
            } else {
                $data["TBData"] = false;
            }
		    // echo '<pre>'; print_r($data); die();
            if (!$ajax) {
                $this->load->view('client/trial_balance/default', $data);
            } else {
                $returnData["HTML"] = $this->load->view('client/trial_balance/listing', $data, true);
                $returnData["FIN_DATE"] = $financial_date;
                echo json_encode($returnData);
                die();
            }
        } else {
            die("error");
        }
    }

    public function getTBToFile() {
        require_once(APPPATH . 'third_party/PHPExcel.php');
        $name = "trial_balance";

        $setStyle = array(
            'font' => array(
                'name' => 'Arial',
                'size' => 12,
                'bold' => TRUE,
                'color' => array(
                    'rgb' => '2685E1'
                ),
            )
        );

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('TrialBalances');
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);

        $TBCats = $this->trial_balance->getTBCats();
        if ($TBCats) {

            $TBData = $this->trial_balance->getItems();

            $TBYears = getTBYear();
            $TBYear = $TBYears[0]["title"];
            $TBPrevYear = $TBYears[1]["title"];
            $totalCurrYear = 0;
            $totalPrevYear = 0;

            // $objPHPExcel->getActiveSheet()->setCellValue('A1', $this->lang->line("TB_ROW_SRNO"));
            $objPHPExcel->getActiveSheet()->setCellValue('C1', $this->lang->line("TB_ROW_TYPE"));
            $objPHPExcel->getActiveSheet()->setCellValue('D1', $TBYear);
            $objPHPExcel->getActiveSheet()->setCellValue('E1', $TBPrevYear);
            $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($setStyle);
            $limit = 0;
            if ($TBData) {
                $PL = $BS = 0;
                $count = 3;
                foreach ($TBCats as $TBid => $TBCat) {
                    $parent = true;
                    if ($PL == 0 && $TBCat["type"] == "P/L") {
                        $objPHPExcel->getActiveSheet()->setCellValue('A2', $this->lang->line("TB_PL_ACCOUNT"));
                        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($setStyle);
                        $PL = 1;
                    } else if ($BS == 0 && $TBCat["type"] == "B/S") {
                        $count += 3;
                        if ($limit == 0) {
                            $limit = $count;
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $count, $this->lang->line("TB_BS_ACCOUNT"));
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $count . ':E' . $count)->applyFromArray($setStyle);
                        $BS = 1;
                        $count++;
                    }

                    if (!empty($TBCat["childrens"]) && count($TBCat["childrens"]) > 0) {

                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $count, $TBCat["title"]);

                        foreach ($TBCat["childrens"] as $tbChildId => $tbChild) {

                            if ($parent) {
                                $parent = false;
                            }

                            $rowAmount = numberFormatXLS("0.00");
                            $rowPrevAmount = numberFormatXLS("0.00");
                            if (isset($TBData[$TBYear][$tbChildId])) {
                                if ($TBCat["type"] == "P/L") {
                                    $totalCurrYear = $totalCurrYear + $TBData[$TBYear][$tbChildId]["amount"];
                                } else if ($TBCat["type"] == "B/S") {
                                    $totalCurrYear = $totalCurrYear + $TBData[$TBYear][$tbChildId]["amount"];
                                }
                                $rowAmount = numberFormatXLS($TBData[$TBYear][$tbChildId]["amount"]);
                            }

                            if (isset($TBData[$TBPrevYear][$tbChildId])) {
                                if ($TBCat["type"] == "P/L") {
                                    $totalPrevYear = $totalPrevYear + $TBData[$TBPrevYear][$tbChildId]["amount"];
                                } else if ($TBCat["type"] == "B/S") {
                                    $totalPrevYear = $totalPrevYear + $TBData[$TBPrevYear][$tbChildId]["amount"];
                                }
                                $rowPrevAmount = numberFormatXLS($TBData[$TBPrevYear][$tbChildId]["amount"]);
                            }

                            $objPHPExcel->getActiveSheet()->setCellValue('B' . $count, $tbChild["title"]);
                            $objPHPExcel->getActiveSheet()->setCellValue('C' . $count, $tbChild["type"]);
                            $objPHPExcel->getActiveSheet()->setCellValue('D' . $count, $rowAmount);
                            $objPHPExcel->getActiveSheet()->setCellValue('E' . $count, $rowPrevAmount);
                            $count++;
                        }
                    }
                }
                $pl_limit = $limit;
                $limit = $limit + 1;
                //echo $limit+1;die;
                $finalCurrYear = ( $totalCurrYear != 0 ) ? numberFormatXLS($totalCurrYear) : numberFormatXLS("0.00");
                $finalPrevYear = ( $totalPrevYear != 0 ) ? numberFormatXLS($totalPrevYear) : numberFormatXLS("0.00");

                $objPHPExcel->getActiveSheet()->setCellValue('C' . $count, $this->lang->line("TB_ROW_TOTAL"));
                // $objPHPExcel->getActiveSheet()->setCellValue('D'.$count, $finalCurrYear);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $count, '=SUM(D3:D' . ($pl_limit - 1) . ') + SUM(D' . $limit . ':D' . ($count - 1) . ')');
                // $objPHPExcel->getActiveSheet()->setCellValue('E'.$count, $finalPrevYear);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $count, '=SUM(E3:E' . ($pl_limit - 1) . ') + SUM(E' . $limit . ':E' . ($count - 1) . ')');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $count . ':E' . $count)->applyFromArray($setStyle);
            } else {
                die("No Data to export into Excel!");
            }
        } else {
            die("No Categories to export into Excel!");
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        die;
    }

    function ledger_accounts($TBcatID = 0) {
        $prefix = $this->db->dbprefix;
        // For change in Ledger/TB Category at Ledger Listing
        if (isset($_POST["JournalCategories"])) {
            $TBcatID = $this->encrypt->encode($this->input->post("JournalCategories"));
            $this->session->set_userdata('ledgerCategory', $TBcatID);
        }

        // Use session to save/get data on reload
        if (empty($TBcatID)) {
            $ledgerCategory = $this->session->userdata('ledgerCategory');
            if (isset($ledgerCategory) && !empty($ledgerCategory))
                $TBcatID = $ledgerCategory;
        }else if ($TBcatID != 0) {
            $this->session->set_userdata('ledgerCategory', $TBcatID);
        }

        // For change in year at Ledger Listing
        if (isset($_POST["TBYear"])) {
            $TBYear = $this->input->post("TBYear");
            $this->session->set_userdata('TBYear', $TBYear);
        }

        $ledgerDetails = $this->trial_balance->getLedgerDetails($TBcatID);
		//echo "<pre>";print_r($ledgerDetails); echo "</pre>";
        $start = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($ledgerDetails) {
            //if( FALSE ){ // for testing purpose
            $data['items'] = array_slice($ledgerDetails, $start, TB_LEDGER_LISTING_PAGINATION_LIMIT, TRUE);
            $totalItems = count($ledgerDetails);
        } else {
            $data['items'] = FALSE;
            $totalItems = 0;
        }

        $ledger_head = getColumns(array("id" => $this->encrypt->decode($TBcatID)), "Title", $prefix . "trial_balance_categories");
        $data['ledger_head'] = $ledger_head[0]["Title"];

        $url = site_url() . 'ledger_accounts/' . $TBcatID;
        $data['pagination'] = $this->getPagination($url, TB_LEDGER_LISTING_PAGINATION_LIMIT, $totalItems);
        $data['title'] = "Dashboard | Ledger Accounts (" . $data['ledger_head'] . ")";
        $data['page'] = "ledger_accounts";
        $data['sourceDD'] = $this->getSourceDD();
        $data['ledgerDetails'] = $ledgerDetails;
        $data['TBcatID'] = $TBcatID;
		//print_r($data['ledgerDetails']); die('****************');
        $this->load->view('client/trial_balance/ledger_default', $data);
    }

    public function getPagination($url = null, $perPage = TB_LEDGER_LISTING_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */
        $this->load->library('pagination');
        $config['base_url'] = $url;
        $config['num_links'] = 2;
        $config['per_page'] = $perPage;
        $config['total_rows'] = $totalItem;
        $config['uri_segment'] = 5;
        $config['full_tag_open'] = '<ul class="pagination pull-right">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '<span aria-hidden="true">Prev</span><span class="sr-only">Prev</span>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '<span aria-hidden="true">Next</span><span class="sr-only">Next</span>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['last_link'] = FALSE;
        $config['first_link'] = FALSE;
        $config['cur_tag_open'] = '<li><a><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $this->pagination->initialize($config);
        //echo '<pre>';print_r($this->pagination);die;
        return $this->pagination->create_links();
    }

    function getSourceDD($selected = "") {

        $ledgerSource = $this->session->userdata('ledgerSource');
        if (isset($_POST["source"])) {
            $selected = $this->input->post("source");
        } else if (!empty($ledgerSource)) { // Else use session data
            $selected = $ledgerSource;
        }

        $options = array(
            "" => "-- Select --",
            "INVOICE" => "Invoice",
            "SALARY" => "Salary",
            "EXPENSE" => "Expense",
            "PAYEE" => "Payee",
            "VAT" => "VAT",
            "DIVIDEND" => "Dividend",
            "JOURNAL" => "Journal",
            "TBFWD" => "Entry carried forward",
            "BANK" => "Bank statement"
        );
        $input_options = ' id="source" class="input_100percent" ';
        return form_dropdown("source", $options, $selected, $input_options);
    }

    function clean() {
        $this->session->set_userdata('ledgerSource', NULL);
        setRedirect(site_url('ledger_accounts'));
    }

    function showLedgerDetails() {

        if ($this->input->is_ajax_request()) {

            $task = $this->encrypt->decode($this->input->post('task'));
            $itemId = $this->encrypt->decode($this->input->post('ID'));

            // only for, any type of Ledger which is Not considered in cases below
            $viewHTML = "<div class='alert alert-danger text-center'><i class='glyphicon glyphicon-exclamation-sign'></i>&nbsp;";
            $viewHTML .= $this->lang->line("ERROR_LOADING_LEDGER_POPUP_DETAILS");
            $viewHTML .= "</div>";


            switch ($task) {

                case "viewSalary":
                    $data['details'] = $this->trial_balance->getSalaryDetails($itemId);
                    $viewHTML = $this->load->view("client/trial_balance/ledger_details/salary", $data, true);
                    break;

                case "viewJournal":
                    $data['details'] = $this->trial_balance->getJournalDetails($itemId);
                    $viewHTML = $this->load->view("client/trial_balance/ledger_details/journal", $data, true);
                    break;

                case "viewTBFWD":
                    $data['details'] = $this->trial_balance->getTBFWDDetails($itemId);
                    $viewHTML = $this->load->view("client/trial_balance/ledger_details/tbfwd", $data, true);
                    break;

                case "viewBank":
                    $data['details'] = $this->trial_balance->getBankDetails($itemId);
                    $viewHTML = $this->load->view("client/trial_balance/ledger_details/bank", $data, true);
                    break;

                default:
                    break;
            }
            die($viewHTML);
        } else {
            die("You are not allowed to perform this function!");
        }
    }

}

/* End of file trial_balances.php */
/* Location: ./application/controllers/trial_balances.php */
?>
