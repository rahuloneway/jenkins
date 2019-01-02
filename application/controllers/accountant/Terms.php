<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Terms extends CI_Controller {

    public function Terms() {
        parent::__construct();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        checkUserAccess(array('TYPE_ACC'));
        $user = $this->session->userdata('user');
        $this->load->model('accountant/Term');
    }

    public function index() {
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $clientId = $this->input->post('clientId');
        $status = $this->input->post('Status');
        $companyName = $this->input->post('CompanyName');
        $data['status'] = $status;
        $data['cid'] = $clientId;
        $data['cmp'] = $companyName;
        $data['page'] = 'terms_conditions';
        $data['title'] = 'Accountant | Terms And Conditions';
        $data['fiile_structure'] = $this->Term->getFileStructure($clientId, TERM_CONDITIONS_PAGINATION_LIMIT, $page, $status, $companyName);
        $data['client'] = $this->Term->getClient();
        $total = $this->Term->getFiletotal();
        $data['pagination'] = $this->getPagination(TERM_CONDITIONS_PAGINATION_LIMIT, $total);
        $this->load->view('accountant/terms/default', $data);
    }

    private function getPagination($perPage = TERM_CONDITIONS_PAGINATION_LIMIT, $totalItem = 0) {
        /* Create Pagination links */
        $this->load->library('pagination');
        $config['base_url'] = site_url() . 'terms_conditions';
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

    public function uploadDocuments() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            /* Check if Created by accountant while accessing the client account */
            $user = $this->session->userdata('user');
            $path = 'assets/uploads/terms';
            $ClientId = $_POST['client'];
            /* STEP - 1 Check if the folder of client exists in the corresponding Category Folder */
            if (!file_exists($path)) {
                //echo 'Path : '.$path;die;
                /* If not exists then create one */
                if (!mkdir($path, 0777, TRUE)) {
                    log_message('error', 'Error in uploading the file to the server');
                    $msg = '<div class="alert alert-danger">';
                    $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>';
                    $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURRED');
                    $msg .= '</div>';
                    $this->session->set_flashdata('uploadDocumentError', $msg);
                    setRedirect('terms_conditions');
                }
            }
            if (!empty($ClientId)) {
                $file = $_FILES['file']['name'];
                $ext = explode('.', $file);
                if ($ext[1] == 'pdf' || $ext[1] == 'PDF') {
                    $checkTC = $this->Term->checkTermsconditions($ClientId);
                    if (!empty($checkTC)) {
                        $file_name = $_FILES['file']['name'];
                        $exp = explode('.', $file_name);
                        $file_name = "UK1014" . rand(100, 1000) . "." . $exp[1];
                        $file_record = array(
                            'VERSION' => $checkTC[0]->Version + 1,
                            'FName' => $file_name,
                            'FType' => $_FILES['file']['type'],
                            'FSize' => $_FILES['file']['size'],
                            'AddedOn' => date('Y-m-d'),
                            'ModifiedOn' => '(NULL)',
                            'Type' => 'PDF',
                            'Status' => '0',
                        );
                        $file_id = $this->Term->updateFile($ClientId, $file_record);
                        @unlink($path . "/" . $checkTC[0]->FName);
                    } else {
                        $file_name = $_FILES['file']['name'];
                        $exp = explode('.', $file_name);
                        $file_name = "UK1014" . rand(100, 1000) . "." . $exp[1];
                        $file_record = array(
                            'VERSION' => 1,
                            'ClientId' => $ClientId,
                            'FName' => $file_name,
                            'FType' => $_FILES['file']['type'],
                            'FSize' => $_FILES['file']['size'],
                            'AddedOn' => date('Y-m-d'),
                            'Type' => 'PDF',
                            'AccountantAccess' => $user['UserID'],
                        );
                        $file_id = $this->Term->saveFile($file_record);
                    }
                } else {
                    log_message('error', 'Unable to delete file record from the database');
                    $msg = '<div class="alert alert-danger">';
                    $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('CASHAMN_CLIENT_TERMPDF_PDF_ERROR');
                    $msg .= '</div>';
                    $this->session->set_flashdata('uploadDocumentError', $msg);
                    setRedirect(site_url() . 'terms_conditions');
                    exit();
                }
            } else {
                log_message('error', 'Unable to delete file record from the database');
                $msg = '<div class="alert alert-danger">';
                $msg .= '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('SELECT_CLIENT');
                $msg .= '</div>';
                $this->session->set_flashdata('uploadDocumentError', $msg);
                setRedirect(site_url() . 'terms_conditions');
                exit();
            }

            /* If folder already exists then copy the file to the destination folder */
            $destination_path = $path;
            $config['upload_path'] = $destination_path;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = '1000';
            $config['max_width'] = '1024';
            $config['max_height'] = '768';
            $config['file_name'] = $file_name;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('file')) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->upload->display_errors();
                $msg .= '</div>';
                $this->session->set_flashdata('uploadDocumentError', $msg);
                setRedirect(site_url() . 'terms_conditions');
                exit();
            }

            /* STEP - 2 After successful upload add the file record in the database name */

            if (empty($file_id)) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('DOCUMENT_FILE_UPLOAD_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('uploadDocumentError', $msg);
                setRedirect(site_url() . 'terms_conditions');
                exit();
            }

            /* STEP - 3 If all steps processed successfully return to the listing view */
            $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
            $msg .= $this->lang->line('DOCUMENT_FILE_UPLOAD_SUCCESS');
            $msg .= '</div>';
            $this->session->set_flashdata('uploadDocumentError', $msg);
            setRedirect(site_url() . 'terms_conditions');
            exit();
        } else {
            show_404();
        }
    }

    public function uploadFrom() {
        $data['company'] = $this->Term->getCompany();
        if (empty($data['company'])) {
            $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
            $msg .= $this->lang->line('DOCUMENT_FILE_UPLOAD_ERROR');
            $msg .= '</div>';
            $this->session->set_flashdata('uploadDocumentError', $msg);
            setRedirect(site_url() . 'terms_conditions');
        } else {
            $data['page'] = 'terms_conditions';
            $HTML = $this->load->view('accountant/terms/uploadform', $data, TRUE);
            echo $HTML;
        }
    }

    public function sendMail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('clientId');
            $id = $this->encrypt->decode($id);
            $subject = $this->input->post('subject');
            $msg = $this->input->post('email_text');
            if (empty($id)) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TERMS_CONDITONS_SEND_MAIL_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('uploadDocumentError', $msg);
                setRedirect(site_url() . 'terms_conditions');
                exit();
            } else if (empty($subject)) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TERMS_CONDITONS_SEND_MAIL_SUBJECT');
                $msg .= '</div>';
                $this->session->set_flashdata('uploadDocumentError', $msg);
                setRedirect(site_url() . 'terms_conditions');
                exit();
            } else if (empty($msg)) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TERMS_CONDITONS_SEND_MAIL_MESSAGE');
                $msg .= '</div>';
                $this->session->set_flashdata('uploadDocumentError', $msg);
                setRedirect(site_url() . 'terms_conditions');
                exit();
            } else {
                $clientinfo = $this->Term->getClientinfo($id);
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
                    if (strpos($msg, 'COMPANY') !== false) {
                        $msg = str_replace('COMPANY', $clientinfo[0]->Name, $msg);
                    }
                    if (strpos($msg, 'SIGNATURE') !== false) {
                        $msg = str_replace('SIGNATURE', CASHMAN_FROM_EMAIL_ADDRESS, $msg);
                    }
                    //$msg = str_replace(chr(194), " ", $msg);
                    iconv_set_encoding("internal_encoding", "UTF-8");
                    $sendEmail = array(
                        'Subject' => $this->input->post('subject'),
                        'Message' => utf8_decode($this->cleanString(html_entity_decode($msg, ENT_QUOTES, "UTF-8"))),
                        'To' => $clientinfo[0]->Email,
                        'From' => CASHMAN_FROM_EMAIL_ADDRESS
                    );
                    $response = sendEmail($sendEmail);
                    if (!$response) {
                        $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                        $msg .= $this->lang->line('TERMS_CONDITIONS_EMAIL_FAILURE');
                        $msg .= "</div>";
                        $this->session->set_flashdata('uploadDocumentError', $msg);
                    } else {
                        $user = $this->session->userdata('user');
                        $data = array(
                            'ClientId' => $id,
                            'CompanyId' => $clientinfo[0]->CID,
                            'ToAddress' => $clientinfo[0]->Email,
                            'CCAddress' => '',
                            'BCCAddress' => '',
                            'Body' => $this->cleanString($msg),
                            'SUBJECT' => $subject,
                            'EmailType' => 'OUTBOUND',
                            'AddedOn' => date('Y-m-d'),
                            'AddedBy' => $user['UserID']
                        );
                        /* insert email logs */
                        emailTracking($data);
                        $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok-circle"></i>&nbsp;';
                        $msg .= $this->lang->line('TERMS_CONDITIONS_EMAIL_SUCCESS') . " " . $clientinfo[0]->Email;
                        $msg .= "</div>";
                        $this->session->set_flashdata('uploadDocumentError', $msg);
                    }
                    setRedirect(site_url() . 'terms_conditions');
                } else {

                    $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('TERMS_CONDITONS_SEND_EMAIL_NOT_EXITS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('uploadDocumentError', $msg);
                    setRedirect(site_url() . 'terms_conditions');
                }
            }
        }
    }

    public function templateForm() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clientId = $this->input->post('id');
            $id = $this->encrypt->decode($clientId);
            if (!empty($clientId)) {
                $clientinfo = $this->Term->getClientinfo($id);
                $this->load->model('accountant/Aemail');
                $data['email'] = $clientinfo[0]->Email;
                $data['templatename'] = $this->Aemail->getTemplatename('terms');
                $data['clientId'] = $clientId;

                $this->load->view('accountant/terms/select_email_term_template', $data);
            } else {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('ERROR_ADD_TEMPLATE');
                $msg .= '</div>';
                $this->session->set_flashdata('uploadDocumentError', $msg);
                setRedirect(site_url() . 'terms_conditions');
            }
        }
    }

    public function selemailtemplateType() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('Id');
            $this->load->model('accountant/Aemail');
            if (!empty($id)) {
                $response = $this->Aemail->choseTemplatename($id);
                echo $response;
            }
        }
    }

    public function selectClient() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('Id');
            $clientinfo = $this->Term->getClientname($id);
            if (!empty($clientinfo)) {
                echo '<option value="' . $clientinfo[0]->ID . '">' . $clientinfo[0]->FirstName . " " . $clientinfo[0]->LastName . '</option>';
            } else {
                echo '<option value="">Select Client</option>';
            }
        }
    }

    function cleanString($text) {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
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
            '/[“”«»„]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

}

?>