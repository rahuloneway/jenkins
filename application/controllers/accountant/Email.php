<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Email extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('accountant/Aemail');
    }

    public function index($id = NULL) {	 	
		
	   if(isset($_POST['search_bs'])){ 
			$this->session->set_userdata('MailBankStatmentFrom', $_POST['StartDate']);
			//$this->session->set_userdata('MailBankStatmentTo', $_POST['EndtDate']);
		}else{
			$this->session->set_userdata('MailBankStatmentFrom', '');
			//$this->session->set_userdata('MailBankStatmentTo', '');
		}	
			
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data['title'] = 'Cashman | Email';
        $data['page'] = "email";
        
		$status = $this->input->post('Status');
		

        $Days_relation_with = $this->input->post('Days_relation_with');
		$Quarters_relation_with = $this->input->post('Quarters_relation_with');
		$filterby = $this->input->post('filterby');		

        if (!empty($status) || !empty($Days_relation_with)) {
            $Days = $Days_relation_with;
			$Quarter = $Quarters_relation_with;
            $statusType = $status;
			$filterby = $filterby;
        } else {
            if (!empty($id)) {
                $Days = $this->session->userdata('Days_relation_with');
				$Quarter = $this->session->userdata('Quarters_relation_with');
                $statusType = $this->session->userdata('Status');
				$filterby = $this->session->userdata('filterby');
                $Days_relation_with = $Days;
                $status = $statusType;
            } else {
                $status = $this->session->userdata('Status');
                if (!empty($status)) {
                    $statusType = $status;
                } else {
                    $statusType = 'ACCOUNT_DUE';
                }
            }
        }		
        $emailstatus = array(
			'Status' => $statusType,
			'Days_relation_with' => @$Days,
			'Quarters_relation_with' => @$Quarter,
			'filterby' => $filterby
        );
        $this->session->set_userdata('emailstatus',$emailstatus);
		
        if ($status == 'RETURN_DUE') {
            $data['return_items'] = $this->Aemail->get_return_items('', EMAIL_PAGINATION_LIMIT, $page, '',$Quarters_relation_with);
            /*if (!empty($Days_relation_with)) {
                foreach ($data['return_items'] as $key => $val) {
                    $due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->EndDate)));
                    //$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
                    $date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
                    //$date_difference = str_replace('-','',$date_difference);
                    $date_difference = trim($date_difference) / (60 * 60 * 24);
                    if ($Days_relation_with == '30') {
                        if ($date_difference > 30) {
                            $arr[] = $val;
                        }
                    } else if ($Days_relation_with == '30-60') {
                        if ($date_difference > 30 && $date_difference < 60) {
                            $arr[] = $val;
                        }
                    } else {
                        if ($date_difference > 60) {
                            $arr[] = $val;
                        }
                    }
                }
                $data['return_items'] = $arr;
            }*/
            $total = $this->Aemail->totalentriesReturnitem();
        } else if ($status == 'VAT_DUE') {			
            $data['vatdue_items'] = $this->Aemail->get_vatdue_items('', EMAIL_PAGINATION_LIMIT, $page, '',$Quarters_relation_with);
			//echo "<pre>";print_r( $data['vatdue_items']); die;
            //if (!empty($Days_relation_with)) {
                /*foreach ($data['vatdue_items'] as $key => $val) {
					//echo "<pre>"; print_r($val);		
					//$due_date = date('Y-m-d', strtotime("+9 months", strtotime($val['EndDate'])));
					$due_date = $val['EndDate'];
					//$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
                    $date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
                    //$date_difference = str_replace('-','',$date_difference);
                    $date_difference = trim($date_difference) / (60 * 60 * 24); 
                    if ($Days_relation_with == '30') {
                        if ($date_difference > 30) {
                            $arr[] = $val;
                        }
                    } else if ($Days_relation_with == '30-60') {
                        if ($date_difference > 30 && $date_difference < 60) {
                            $arr[] = $val;
                        }
                    } else {
                        if ($date_difference > 60) {
                            $arr[] = $val;
                        }
                    }
                }
                $data['vatdue_items'] = $arr;*/
				//echo "<pre>";print_r($data['vatdue_items']); die('*-*-*-*');
            //}
            $total = $this->Aemail->totalentriesVatdue();
        } else {
            $data['annual_items'] = $this->Aemail->get_annual_items('', EMAIL_PAGINATION_LIMIT, $page, '',$Quarters_relation_with);
            /*if (!empty($Days_relation_with)) {
                foreach ($data['annual_items'] as $key => $val) {					
                    $due_date = date('Y-m-d', strtotime("+9 months", strtotime($val->EndDate)));
                    //$annual_date = date('Y-m-d',strtotime($val->ReturnDate));
                    $date_difference = strtotime($due_date) - strtotime(date('Y-m-d'));
                    //$date_difference = str_replace('-','',$date_difference);
                    $date_difference = trim($date_difference) / (60 * 60 * 24);
                    if ($Days_relation_with == '30') {
                        if ($date_difference > 30) {
                            $arr[] = $val;
                        }
                    } else if ($Days_relation_with == '30-60') {
                        if ($date_difference > 30 && $date_difference < 60) {
                            $arr[] = $val;
                        }
                    } else {
                        if ($date_difference > 60) {
                            $arr[] = $val;
                        }
                    }
                }
                $data['annual_items'] = $arr;
            }*/
            $total = $this->Aemail->totalentriesAccountdue();
        }

		
		$search = array(			
			'StartDate' => safe(@$_POST['StartDate']),
			'EndDate' => safe(@$_POST['EndDate'])
		);
		$this->session->set_userdata('BankSearch', $search);
		$data['bankstatment_items'] = $this->Aemail->get_bankStatmentdue_items('', EMAIL_PAGINATION_LIMIT, $page, '',$Quarters_relation_with);
		
		
		
		//echo "<pre>";print_r($data['bankstatment_items']);die;
		
        $data['pagination'] = $this->getPagination(EMAIL_PAGINATION_LIMIT, $total);
		$this->load->view('accountant/email/default', $data);
    }

    private function getPagination($perPage = EMAIL_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */

        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'email';
        $config['num_links'] = 2;
        $config['per_page'] = $perPage;
        $config['total_rows'] = $totalItem;
        $config['uri_segment'] = 2;
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
        $config['cur_tag_open'] = '<li><a ><b>';
        $config['cur_tag_close'] = '</b></a></li>';
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }

    /* Select email template form */

    public function selemailTemplate() {
        $data['templatename'] = $this->Aemail->getTemplatename();
        $this->load->view('accountant/email/select_email_template', $data);
    }

    /* Get email template type */

    public function selemailtemplateType() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('Id');
            if (!empty($id)) {
                $response = $this->Aemail->choseTemplatename($id);
                echo $response;
            }
        }
    }

    /* Add email Template */
    public function addemailTemplate() {
		$data['title'] = 'Add Template';
        $data['page'] = "email";
        $this->load->view('accountant/email/email_template_form', $data);
    }
	
	/* Compose email Template */
	public function sendMailTemplate() {	
		
		if(isset($_POST['cb'])){
			$cid = '';
			$bulkEmailUserDetails = array();
			foreach($_POST['cb']as $val){
				$id = $this->encrypt->decode($val);
				$id= explode('/',$id);				
				$bulkEmailUserDetails[] = $id;				
				//if($cid != '')
					//$cid = $cid.','.$val[2];
				//else
					//$cid = $val;
			}			
			$this->session->set_userdata('bulkEmailUserDetails', $bulkEmailUserDetails);
		}
		if($_POST['mail_type'] == 'bank'){
			$data['mail_type'] = 'bank';			
		}else{
			$data['mail_type'] = 'vat';
		}
		$data['templatename'] = $this->Aemail->getTemplatename($_POST['mail_type']);
		$data['title'] = 'Email Template';
        $data['page'] = "email";		
        $this->load->view('accountant/email/select_email_template', $data);		
	}
	

    /* save email template */

    public function save() {	
		
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['templateName'])) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TEMPLATE_NAME_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('templateDocumentError', $msg);
				//setRedirect(site_url('add_email_template'));
				$data['title'] = 'Add Template';
				$data['page'] = "email";
				$data['post'] = $_POST;
				$this->load->view('accountant/email/email_template_form', $data);	
			/*} else if (empty($_POST['templateType'])) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TEMPLATE_TYPE_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('templateDocumentError', $msg);
				setRedirect(site_url('add_email_template'));*/
            } else if (empty($_POST['templateText'])) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TEMPLATE_TEXT_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('templateDocumentError', $msg);
				//setRedirect(site_url('add_email_template'));
				$data['title'] = 'Add Template';
				$data['page'] = "email";
				$data['post'] = $_POST;
				$this->load->view('accountant/email/email_template_form', $data);	
            } else {
                $template_name = $this->input->post('templateName');
				//$template_type = $this->input->post('templateType');
                $template_text = $this->input->post('templateText');
                $data = array(
                    'Template_name' => $template_name,
					//'Template_type' => $template_type,
                    'Template_text' => $template_text,
                    'AddedOn' => date('Y-m-d')
                );
                $response = $this->Aemail->saveTemplate($data);
                if ($response) {
                    $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('TEMPLATE_TEXT_SUCCESS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('templateDocumentError', $msg);
                    setRedirect(site_url() . 'email');					
                }
            }
						
        }
    }
		
    public function sendMail() {
	
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['savesend'])) {			
						
			if (empty($_POST['templateName'])) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TEMPLATE_NAME_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('templateDocumentError', $msg);
				$data['templatename'] = $this->Aemail->getTemplatename();
				$data['title'] = 'Email Template';
				$data['page'] = "email";
				$data['post'] = $POST;				
				$this->load->view('accountant/email/email_template_form', $data);				
			/*} else if (empty($_POST['templateType'])) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TEMPLATE_TYPE_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('templateDocumentError', $msg);
				$data['templatename'] = $this->Aemail->getTemplatename();
				$data['title'] = 'Email Template';
				$data['page'] = "email";
				$data['post'] = $POST;
                setRedirect(site_url('send_mail_template'));*/
            } else if (empty($_POST['templateText'])) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TEMPLATE_TEXT_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('templateDocumentError', $msg);  
				$data['templatename'] = $this->Aemail->getTemplatename();
				$data['title'] = 'Email Template';
				$data['page'] = "email";
				$data['post'] = $POST;
				$this->load->view('accountant/email/email_template_form', $data);					
            } else {
                $template_name = $this->input->post('templateName');
				//$template_type = $this->input->post('templateType');
                $template_text = $this->input->post('templateText');
                $data = array(
                    'Template_name' => $template_name,
					//'Template_type' => $template_type,
                    'Template_text' => $template_text,
                    'AddedOn' => date('Y-m-d')
                );
                $response = $this->Aemail->saveTemplate($data);				
                if ($response) {					
                    $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('TEMPLATE_TEXT_SUCCESS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('templateDocumentError', $msg); 
					//setRedirect(site_url() . 'email');					
                }
            }
		}	
					
		$this->load->model('accountant/Term');
		$user = $this->session->userdata('user');
		$msg = $_POST['templateText'];
		$subject = $this->input->post('subject');
		//$cid = $_POST['clientId'];
		//$exp = explode(',', $cid);
		$bulkEmailUserDetails = $this->session->userdata('bulkEmailUserDetails');
								
		if (empty($bulkEmailUserDetails)) { 
			$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
			$msg .= $this->lang->line('TERMS_CONDITONS_SEND_MAIL_ERROR');
			$msg .= '</div>';
			echo $this->session->set_flashdata('templateDocumentError', $msg); 
			$data['title'] = 'Email Template';
			$data['page'] = "email";
			$data['post'] = $POST;
			$this->load->view('accountant/email/email_template_form', $data);			
		} else if (empty($subject)) {  
			$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
			$msg .= $this->lang->line('TERMS_CONDITONS_SEND_MAIL_SUBJECT');
			$msg .= '</div>';
			echo $this->session->set_flashdata('templateDocumentError', $msg); 
			$data['title'] = 'Email Template';
			$data['page'] = "email";
			$data['post'] = $POST;
			$this->load->view('accountant/email/email_template_form', $data);
		} else if (empty($msg)) {  
			$msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
			$msg .= $this->lang->line('TERMS_CONDITONS_SEND_MAIL_MESSAGE');
			$msg .= '</div>';
			echo $this->session->set_flashdata('templateDocumentError', $msg); 
			$data['title'] = 'Email Template';
			$data['page'] = "email";
			$data['post'] = $POST;
			$this->load->view('accountant/email/email_template_form', $data);
		} else { 
			$msg='';
				//echo "<pre>"; print_r($bulkEmailUserDetails);
			foreach ($bulkEmailUserDetails as $val) {	
				//echo "<pre>"; print_r($val); 
				if(isset($val[2])){
					#Get querter dwtails
					$thisQuater = $this->Aemail->getVatQuarters($val[0],$val[1],$val[2],$val[3]);
				}
					
				if (!empty($val)) {
					$msg = $_POST['templateText'];					
					$clientinfo = $this->Term->getClientinfo($val[0]);
					$clientinfoParam = unserialize($clientinfo[0]->Params);
					
					if (!empty($clientinfo[0]->Email)) {
						if (strpos($msg, 'FIRSTNAME') !== false) {
							$msg = str_replace('FIRSTNAME', $clientinfo[0]->FirstName, $msg);
						}
						if (strpos($msg, 'LASTNAME') !== false) {
							$msg = str_replace('LASTNAME', $clientinfo[0]->LastName, $msg);
						}
						if (strpos($msg, 'EMAIL') !== false) {
							$msg = str_replace('EMAIL', CASHMAN_FROM_EMAIL_ADDRESS, $msg);
						}						
						if (strpos($msg, 'COMPANY_NAME') !== false) {
							$msg = str_replace('COMPANY_NAME', $clientinfo[0]->Name, $msg);
						}						
						if (strpos($msg, 'COMPANY_REG_NO') !== false) {
							$msg = str_replace('COMPANY_REG_NO', $clientinfo[0]->RegistrationNo, $msg);
						}
						if (strpos($msg, 'VAT_QUARTER') !== false) {
							$msg = str_replace('VAT_QUARTER',$thisQuater, $msg);
						}
						
						if (strpos($msg, 'VAT_REG_NO') !== false) {
							$msg = str_replace('VAT_REG_NO', $clientinfoParam['VATRegistrationNo'], $msg);
						}
						
						if (strpos($msg, 'PDF_LINK') !== false) {
							$pdfLinkParam = $this->encrypt->encode($thisQuater."/".$val[0]."/".$val[2]."/".$val[3]."/".$clientinfo[0]->Name."/".$clientinfoParam['VATRegistrationNo']);
							$pdfLink = site_url()."client/getQuarterPDFMail/$pdfLinkParam";  
							$msg = str_replace('PDF_LINK', $pdfLink, $msg);
						}
												
						if (strpos($msg, 'BANK_STATEMENT_FROM') !== false) {
							//$msg = str_replace('BANK_STATEMENT_FROM', date('Y-m-d',strtotime('last day of last month')) , $msg);
							if($this->session->userdata('MailBankStatmentFrom') != ''){
								$from_bs_date = $this->session->userdata('MailBankStatmentFrom');
								$from_bs_date = date('d-m-Y', strtotime($from_bs_date));
							}else{
								#Get client's bank statment uploade last date PARAM Client id,Company id
								$from_bs_date = getBankStatmentLastUploadDate($val[1],$val[0]);
								if($from_bs_date == ''){
									$from_bs_date = date('d-m-Y',strtotime($clientinfo[0]->AddedOn));
								}else{
									$from_bs_date = date('d-m-Y', strtotime($from_bs_date . ' +1 day'));
								}
							}							
							$msg = str_replace('BANK_STATEMENT_FROM', $from_bs_date , $msg);
						}
						
						if (strpos($msg, 'BANK_STATEMENT_TO') !== false) {
							//$msg = str_replace('BANK_STATEMENT_TO', date('Y-m-d',strtotime('last day of last month')) , $msg);
							//if($this->session->userdata('MailBankStatmentTo') != ''){
								//$to_bs_date = $this->session->userdata('MailBankStatmentTo');
							//}else{
								$to_bs_date = date('d-m-Y');								
							//}							
							$msg = str_replace('BANK_STATEMENT_TO', $to_bs_date , $msg);
						}	
						
						$yearPaid = getPaidYear(date('Y-m-d', strtotime($val[2])));		
						
						$year = explode('/',$yearPaid['value']);
												
						$year = $year[0];
						
						//Get quaters details											
						if (strpos($msg, '{ACCEPT}') !== false) {
							$acceptLink = $clientinfo[0]->CID."-".$thisQuater.'-accept'."-".$year;
							$acceptLink = $this->encrypt->encode($acceptLink);
							$acceptLink = site_url().'due_vat_mail_acction/'.$acceptLink;
							$msg = str_replace('{ACCEPT}', $acceptLink, $msg);
						}
						
						if (strpos($msg, '{REJECT}') !== false) {
							$rejectLink = $clientinfo[0]->CID."-".$thisQuater.'-rejects'."-".$val[3];
							$rejectLink = $this->encrypt->encode($rejectLink);
							$rejectLink = site_url().'due_vat_mail_acction/'.$rejectLink;
							$msg = str_replace('{REJECT}', $rejectLink, $msg);
						}
						
						if (strpos($msg, 'SITE_TITLE') !== false) {
							$msg = str_replace('SITE_TITLE', 'CASHMANN', $msg);
						}
						
						if (strpos($msg, 'COPY_RIGHT_TEXT') !== false) {
							$msg = str_replace('COPY_RIGHT_TEXT', '© 2016 CASHMANN All Rights Reserved', $msg);
						}
						
						if (strpos($msg, 'LOGO') !== false) {
							$logUrl = site_url()."assets/images/logo.png";
							$msg = str_replace('LOGO', '<img alt="Cashmann web application" src="'.$logUrl.'">', $msg);
						}			
						
					    //iconv_set_encoding("internal_encoding", "UTF-8");
						$sendEmail = array(
							'Subject' => $this->input->post('subject'),
							'Message' => utf8_decode($this->cleanString(html_entity_decode($msg, ENT_QUOTES, "UTF-8"))),
							'To' => $clientinfo[0]->Email,
							'From' => CASHMAN_FROM_EMAIL_ADDRESS
						);
						
						//echo $msg;
						//die('Email controller 503');
						
						sendEmail($sendEmail);
						
						$data = array(
							'ClientId' => $val[2] ,
							'CompanyId' => $clientinfo[0]->CID,
							'ToAddress' => $clientinfo[0]->Email,
							'CCAddress' => '',
							'BCCAddress' => '',
							'Body' => $this->cleanString(html_entity_decode($msg, ENT_QUOTES, "UTF-8")),
							'SUBJECT' => $subject,
							'EmailType' => 'OUTBOUND',
							'AddedOn' => date('Y-m-d'),
							'AddedBy' => $user['UserID']
						);
						/* insert email logs */
						emailTracking($data);
					}
				}else{
					$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
					$msg .= $this->lang->line('TERMS_CONDITIONS_EMAIL_SUCCESS');
					$msg .= "</div>";
					$this->session->set_flashdata('templateDocumentError', $msg); 
					setRedirect(site_url('email'));
				}
			} 
				
		}
				
		$msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
		$msg .= $this->lang->line('TERMS_CONDITIONS_EMAIL_SUCCESS');
		$msg .= "</div>";
		$this->session->set_flashdata('templateDocumentError', $msg); 
		setRedirect(site_url('email'));
       
    }

    function cleanString($text) {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[�?ÀÂÃÄ]/u' => 'A',
            '/[�?ÌÎ�?]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => ' ', // Literally a single quote
            '/[“�?«»„]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }
	public function viewEmail(){
        $id = $this->input->post('id');
        $id = $this->encrypt->decode($id);
        if(!empty($id)){
            $response = $this->Aemail->getemailDetails($id);
            $data['email'] =$response;
            $this->load->view('accountant/emaillogs/viewEmail',$data);
        }

    }
	
	public function dueVatMailAcctionnnnnnnn($link){
		$link = $this->encrypt->decode($link);			
		$link = explode('-',$link);		
		if(isset($link[2])){
			$data['mailAcceptStatus'] = $this->Aemail->dueVatMailAcction($link[0],$link[1],$link[2],$link[3]);			
			$data['companyID'] = $link[0];
			$data['quarter'] = $link[1];
			$data['acction'] = $link[2];
			$data['qEndDate'] = $link[3];
			$this->load->view('vatRequest',$data);
		}	
		
	}
	
	
}

?>