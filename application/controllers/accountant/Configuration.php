<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Configuration extends CI_Controller {

    public function Configuration() {
        parent::__construct();
        checkUserAccess(array('TYPE_ACC'));
        /* Check if logged in user is Director or not */
        $user = $this->session->userdata('user');

        if (categoryName($user['UserParams']['EmploymentLevel']) != 'Director') {
            show_404();
        }
        $this->load->model('accountant/settings');
    }

    public function index() {
        $user = $this->session->userdata('user');
        $data['page'] = 'configuration';
        $data['title'] = 'Cashman | Settings';
        $data['items'] = $this->settings->getItems();
        $data['emailsignature'] = $this->settings->getEmailSignature($user['UserID']);
        $data['template'] = $this->settings->getTemplate();
        $this->load->view('accountant/configuration/default', $data);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $setting_name = $_POST;
            $settings = $this->settings->getItems();
            $insert_settings = array();
            $update_settings = array();
            $email = $this->input->post('email_setting');
            $email_text = $this->input->post('email_text');
            $email_text_createdAcc = $this->input->post('email_text_created_a');
            $type = '';
            if (!empty($email) || !empty($email_text) || !empty($email_text_createdAcc)) {
                $user = $this->session->userdata('user');
                $fetchEmail = $this->settings->getEmailSignature($user['UserID']);
                if (count($fetchEmail) == 0) {
                    $type = 'insert';
                    $this->settings->emaillSetting($user['UserID'], $email, $email_text, $email_text_createdAcc, $type);
                } else if ($fetchEmail[0]->Email_Signature != $email || $fetchEmail[0]->Email_Text != $email_text || $fetchEmail[0]->Email_Text_Created != $email_text_createdAcc) {
                    $type = 'update';
                    $this->settings->emaillSetting($user['UserID'], $email, $email_text, $email_text_createdAcc, $type);
                } else {
                    $type = 'update';
                    $this->settings->emaillSetting($user['UserID'], $fetchEmail[0]->Email_Signature, $fetchEmail[0]->Email_Text, $email_text_createdAcc, $type);
                }
            }
            foreach ($setting_name as $key => $val) {
                if (is_array($val)) {
                    $val = implode(',', $val);
                }
                if (!array_key_exists($key, $settings)) {
                    $insert_settings[] = array(
                        'SettingName' => $key,
                        'SettingValue' => $val
                    );
                } else {
                    $update_settings[] = array(
                        'SettingName' => $key,
                        'SettingValue' => $val
                    );
                }
            }
            //pr($_POST);die;
            //echo '<pre>';print_r($insert_settings);echo '</pre>';
            //echo '<pre>';print_r($update_settings);echo '</pre>';die;
            $response = $this->settings->save($insert_settings, $update_settings);
            if (!$response) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('UNEXPECTED_ERROR_OCCURED');
                $msg .= "</div>";
                $this->session->set_flashdata('configErrors', $msg);
                setRedirect(site_url() . 'configuration');
            } else {
                $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;';
                $msg .= $this->lang->line('CONFIGURATION_SAVE_SUCCESSFUL');
                $msg .= "</div>";
                $this->session->set_flashdata('configErrors', $msg);
                setRedirect(site_url() . 'configuration');
            }
        } else {
            show_404();
        }
    }

    /* Reset email config */

    public function resetemailconfig() {
        $user = $this->session->userdata('user');
        $userId = $user['UserID'];
        if (!empty($userId)) {
            $this->load->model('accountant/Settings');
            $response = $this->Settings->resetemailconfig($userId);
            setRedirect(site_url() . 'configuration');
        } else {
            setRedirect(site_url() . 'configuration');
        }
    }

    public function addemailTemplate() {
        $this->load->view('accountant/configuration/email_template_form');
    }

    public function saveTemplate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (empty($_POST['templateName'])) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TEMPLATE_NAME_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('configErrors', $msg);
                setRedirect(site_url() . 'configuration');
            } else if (empty($_POST['templateText'])) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TEMPLATE_TEXT_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('configErrors', $msg);
                setRedirect(site_url() . 'configuration');
            } else {
                $template_name = $this->input->post('templateName');
                $template_text = $this->input->post('templateText');
                $data = array(
                    'Template_name' => $template_name,
                    'Template_text' => $template_text,
                    'AddedOn' => date('Y-m-d')
                );
                $this->load->model('accountant/Aemail');
                $response = $this->Aemail->saveTemplate($data);
                if ($response) {
                    $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                    $msg .= $this->lang->line('TEMPLATE_TEXT_SUCCESS');
                    $msg .= '</div>';
                    $this->session->set_flashdata('configErrors', $msg);
                    setRedirect(site_url() . 'configuration');
                }
            }
        }
    }

    public function editemailTemplate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('id');
            $id = $this->encrypt->decode($id);
            $this->load->model('accountant/Settings');
            $data['template'] = $this->Settings->editTemplate($id);
            $HTML = $this->load->view('accountant/configuration/edit_email_template_form', $data, TRUE);
            echo json_encode($HTML);
        }
    }

    public function updateTemplate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->input->post('templateId');
            $templatename = $this->input->post('templateName');
            $templatetext = html_entity_decode($this->input->post('templateText'));
            $id = $this->encrypt->decode($id);
            if (empty($templatename)) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TEMPLATE_NAME_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('configErrors', $msg);
                setRedirect(site_url() . 'configuration');
            } else if (empty($templatetext)) {
                $msg = '<div class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TEMPLATE_TEXT_ERROR');
                $msg .= '</div>';
                $this->session->set_flashdata('configErrors', $msg);
                setRedirect(site_url() . 'configuration');
            } else {
                $data = array(
                    'Template_Name'=>$templatename,
                    'Template_Text'=>$templatetext
                );
                $this->load->model('accountant/Settings');
                $response = $this->Settings->updateTemplate($id, $data);
                $msg = '<div class="alert alert-success"><i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;';
                $msg .= $this->lang->line('TEMPLATE_TEXT_UPDATE_SUCCESS');
                $msg .= '</div>';
                $this->session->set_flashdata('configErrors', $msg);
                setRedirect(site_url() . 'configuration');
            }
        }
    }

}