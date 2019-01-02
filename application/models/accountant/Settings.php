<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends CI_Model {

    public function Settings() {
        parent::__construct();
    }

    public function getItems() {
        $this->db->select('SettingName,SettingValue');
        $query = $this->db->get('configuration');
        /* CHECK FOR DB ERRORS */
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $val) {
                $response[$val->SettingName] = $val->SettingValue;
            }
            return $response;
        } else {
            return array();
        }
    }

    public function save($insert_settings = array(), $update_settings = array()) {
        $prefix = $this->db->dbprefix;
        if (count($insert_settings) > 0) {
            $this->db->insert_batch('configuration', $insert_settings);
            /* CHECK FOR DB ERRORS */
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
        }

        if (count($update_settings) > 0) {
            $this->db->update_batch('configuration', $update_settings, 'SettingName');
            /* CHECK FOR DB ERRORS */
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
        }

        return TRUE;
    }

    /*
     * Email Setting in Global Configuration.
     */

    public function getEmailSignature($userId = NULL) {

        if (!empty($userId)) {
            $this->db->select('Email_Signature,Email_Text,Email_Text_Created');
            $this->db->where('AccountedId', $userId);
            $query = $this->db->get('cashman_email_setting');
            $resutl = $query->result();
            return $resutl;
        }
    }

    public function emaillSetting($userId = NULL, $email = NULL, $email_text = NULL, $email_text_createdAcc, $type = NULL) {
        if (!empty($userId) || !empty($email) || !empty($type) || !empty($email_text)) {
            if ($type == 'update') {
                $this->db->where('AccountedId', $userId);
                $this->db->update('cashman_email_setting', array('Email_Signature' => $email, 'Email_Text' => $email_text, 'Email_Text_Created' => $email_text_createdAcc));
                return TRUE;
            }
            if ($type == 'insert') {
                $data = array('AccountedId' => $userId, 'Email_Signature' => $email, 'Email_Text' => $email_text, 'Email_Text_Created' => $email_text_createdAcc);
                $this->db->insert('cashman_email_setting', $data);
                return TRUE;
            }
        }
    }

    public function resetemailconfig($userId = NULL) {
        if (!empty($userId)) {
            $prefix = $this->db->dbprefix;
            $this->db->where('AccountedId', $userId);
            $this->db->delete($prefix . 'email_setting');
            return TRUE;
        }
    }

    public function getTemplate() {
        $prefix = $this->db->dbprefix;
        $this->db->select('*');
        $this->db->order_by('Template_Name', 'ASC');
        $query = $this->db->get($prefix . "email_template");
        $result = $query->result();
        if (!empty($result)) {
            return $result;
        }
    }

    public function editTemplate($id = NULL) {
        $prefix = $this->db->dbprefix;
        $this->db->select('*');
        $this->db->where('Id', $id);
        $query = $this->db->get($prefix . "email_template");
        $result = $query->result();
        if (!empty($result)) {
            return $result;
        }
    }

    public function updateTemplate($id = NULL, $data = NULL) {
        if (!empty($id) && !empty($data)) {
            $prefix = $this->db->dbprefix;
            $this->db->where('Id',$id);
            $this->db->update($prefix . "email_template",$data);
            return TRUE;
        }
    }

}