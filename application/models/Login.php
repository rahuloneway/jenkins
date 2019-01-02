<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * 	This function checks if the email exists in the system or not.
     * 	Return Value : Boolean
     */

    public function checkEmail($email = NULL) {
        if ($email != NULL) {
            $prefix = $this->db->dbprefix;
            $this->db->select('Email,FirstName');
            $query = $this->db->get_where('users', array('Email' => $email, 'Status' => 1));

            //$query = $this->db->query('SELECT Email,FirstName from users WHERE Email="'.$this->db->escape($email).'" AND Status=1');

            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
     * 	This function adds a token in the user table to check if recovery process is valid or not.
     * 	Return Value : Boolean
     */

    public function addToken($token = NULL, $email = NULL) {
        if ($token != NULL && $email != NULL) {
            $prefix = $this->db->dbprefix;
            $query = $this->db->query('UPDATE ' . $prefix . 'users SET Token = "' . $token . '" WHERE Email="' . $email . '"');
            if ($this->db->affected_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /*
     * 	This function checks weather the username exists or not
     * 	Return value : array
     */

    public function isUserExists($username = NULL, $password = NULL, $access = 0) {
        /* Check if accountant is trying to login with client access detail */
        $prefix = $this->db->dbprefix;
        $username = $this->db->escape($username);
        $password = $this->db->escape($password);
        if ($access) {
            $status = '';
        } else {
            $status = ' AND u.Status = 1';
        }
        /*
          $select = array(
          'u.*',
          'c.CID',
          'c.RegistrationNo AS CompnayRegNo',
          'c.Params AS CompanyParams',
          'c.EndDate AS CompanyEndDate',
          'tx.PercentRateAfterEndDate',
          'c.IncorporationDate',
          'tx.Type',
          'tx.EndDate',
          'tx.PercentRate'
          );
          $select = implode(',',$select);
         */
        if ($username != NULL) {

            $query = 'SELECT u.*,c.CID,c.RegistrationNo AS CompnayRegNo,c.Email AS CompanyEmail';
            $query .= ',c.Params AS CompanyParams,c.EndDate AS CompanyEndDate,tx.PercentRateAfterEndDate';
            $query .= ',c.IncorporationDate';
            $query .= ',tx.Type,tx.EndDate,tx.PercentRate FROM ' . $prefix . 'users AS u LEFT JOIN ' . $prefix . 'company AS c ON c.ClientID = u.ID';
            $query .= " LEFT JOIN " . $prefix . "tax_rates AS tx ON tx.ClientID = u.ID";
            $query .= " WHERE (u.Username=" . $username . " OR u.Email=" . $username . ") AND u.Password = " . $password . " " . $status;
            $query = $this->db->query($query);
			
			
            /*
              $this->db->select($select);
              //$this->db->from('users AS u');
              $this->db->join('company as c','c.ClientID = u.ID','left');
              $this->db->join('tax_rates as tx','tx.ClientID = u.ID','left');
              $this->db->or_where(array('u.Username'=>$username,'u.Email'=>$username),'OR',TRUE);
              if($status)
              {
              $this->db->get_where('users AS u',array('u.Password'=>$password,'u.Status'=>'1'));
              }else{
              $this->db->get_where('users',array('u.Password'=>$password));
              }

              echo $this->db->last_query();die;
             */
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
            if ($query->num_rows() > 0) {
                $data = $query->result();
                $data = $data[0];
                $data->Params = unserialize($data->Params);
                return $data;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
	
	public function isPortalUserExist($username = NULL) {
        /* Check if accountant is trying to login with client access detail */
        $prefix = $this->db->dbprefix;
        $username = $this->db->escape($username);       
        if ($access) {
            $status = '';
        } else {
            $status = ' AND u.Status = 1';
        }
       
        if ($username != NULL) {

            $query = 'SELECT u.*,c.CID,c.RegistrationNo AS CompnayRegNo,c.Email AS CompanyEmail';
            $query .= ',c.Params AS CompanyParams,c.EndDate AS CompanyEndDate,tx.PercentRateAfterEndDate';
            $query .= ',c.IncorporationDate';
            $query .= ',tx.Type,tx.EndDate,tx.PercentRate FROM ' . $prefix . 'users AS u LEFT JOIN ' . $prefix . 'company AS c ON c.ClientID = u.ID';
            $query .= " LEFT JOIN " . $prefix . "tax_rates AS tx ON tx.ClientID = u.ID";
            $query .= " WHERE u.Username=" . $username . " AND u.Password = " . $password . " " . $status;
            $query = $this->db->query($query);
			
			
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
            if ($query->num_rows() > 0) {
                $data = $query->result();
                $data = $data[0];
                $data->Params = unserialize($data->Params);
                return $data;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
	
	public function clientLogin($username = NULL, $password = NULL, $access = 0) {
        /* Check if accountant is trying to login with client access detail */
        $prefix = $this->db->dbprefix;
        $username = $this->db->escape($username);
        $password = $this->db->escape($password);
        if ($access) {
            $status = '';
        } else {
            $status = ' AND u.Status = 1';
        }
		$companyId = $this->session->userdata('choosedCompanyId');
		if($companyId == '')
			return false;
        if ($username != NULL) {

            $query = 'SELECT u.*,c.CID,c.RegistrationNo AS CompnayRegNo,c.Email AS CompanyEmail';
            $query .= ',c.Params AS CompanyParams,c.EndDate AS CompanyEndDate,tx.PercentRateAfterEndDate';
            $query .= ',c.IncorporationDate';
            $query .= ',tx.Type,tx.EndDate,tx.PercentRate FROM ' . $prefix . 'users AS u LEFT JOIN ' . $prefix . 'company AS c ON c.ClientID = u.ID';
            $query .= " LEFT JOIN " . $prefix . "tax_rates AS tx ON tx.ClientID = u.ID";
            $query .= " WHERE (u.Username=" . $username . " OR u.Email=" . $username . ") AND u.Password = " . $password . " " . $status ." AND c.CID = " . $companyId ;
            $query = $this->db->query($query);
			
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
            if ($query->num_rows() > 0) {
                $data = $query->result();
                $data = $data[0];
                $data->Params = unserialize($data->Params);
                return $data;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
	
	public function clientApiLogin($username = NULL, $password = NULL, $access = 0,$companyId=null) 
	{ 
        /* Check if accountant is trying to login with client access detail */
        $prefix = $this->db->dbprefix;
        $username = $this->db->escape($username);
        $password = $this->db->escape($password);
        if ($access) {
            $status = '';
        } else {
            $status = ' AND u.Status = 1';
        }		
        if ($username != NULL) 
		{
            $query = 'SELECT u.*,c.CID,c.RegistrationNo AS CompnayRegNo,c.Email AS CompanyEmail';
            $query .= ',c.Params AS CompanyParams,c.EndDate AS CompanyEndDate,tx.PercentRateAfterEndDate';
            $query .= ',c.IncorporationDate';
            $query .= ',tx.Type,tx.EndDate,tx.PercentRate FROM ' . $prefix . 'users AS u LEFT JOIN ' . $prefix . 'company AS c ON c.ClientID = u.ID';
            $query .= " LEFT JOIN " . $prefix . "tax_rates AS tx ON tx.ClientID = u.ID";
            $query .= " WHERE (u.Username=" . $username . " OR u.Email=" . $username . ") AND u.Password = " . $password . " " . $status ;
			if( $companyId != '')
				$query .= " AND c.CID = " . $companyId ;
			$query = $this->db->query($query);
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }
            if ($query->num_rows() > 0) {
                $data = $query->result();
                $data = $data[0];
                $data->Params = unserialize($data->Params);
                return $data;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
	
	####################################################################
	# Author : Gurdeep Singh										   #
	# Date   : 18 july 2016											   #	
	# Params : Client Id and fields to fetch						   #	
	####################################################################
	public function getClientCompanies($clientId = NULL,$fields = NULL) {
		if($fields == NULL)
			$fields = '*';
		$prefix = $this->db->dbprefix;
        $this->db->select($fields);
		$this->db->where('ClientID',$clientId);
        $query = $this->db->get('company');
		$data = array();
        if ($query->num_rows() > 0) {
            $data = $query->result();
			return $data;
        } else {
            return $data;
        }		
	}
    /**
     * 	Function: This will check the token for recovering the password.
     * 	Return value: boolean
     */
    public function checkToken($token = NULL) {
        if (!empty($token)) {
            $prefix = $this->db->dbprefix;
            $query = "SELECT ID FROM " . $prefix . "users WHERE Token='" . $token[0] . "' AND Email='" . $token[1] . "'";
            $query = $this->db->query($query);

            /* CHECK FOR DB ERRORS */
            $db_error = $this->db->error();
            if ($db_error['code'] != 0) {
                log_message('error', $db_error['message']);
                return FALSE;
            }

            if ($query->num_rows() > 0) {
                return $query->result();
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function checkQuestionAnswer($data) {
        $prefix = $this->db->dbprefix;
        $this->db->select('ID');
        $query = $this->db->get_where('users', $data);
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateUserPassword($data) {
        $prefix = $this->db->dbprefix;
        $id = $data['ID'];
        unset($data['ID']);
        $data['Token'] = '';
        $this->db->where('ID', $id);
        $this->db->update('users', $data);

        /* CHECK FOR DB ERRORS */
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
    }

    public function updateClientPassword($data) {
        $prefix = $this->db->dbprefix;
        $id = $data['ID'];
        unset($data['ID']);
        $data['Token'] = '';
        $this->db->where('ID', $id);
        $this->db->update('users', $data);

        /* CHECK FOR DB ERRORS */
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
    }

    public function check_username_password($username = NULL, $password = NULL) {

        /* First Check If username/Email exists */
        $prefix = $this->db->dbprefix;
        $this->db->select('Email');
        $this->db->or_where(array('Username' => $username, 'Email' => $username), 'OR', TRUE);
        $query = $this->db->get('users');

        /* CHECK FOR DB ERRORS */
        $db_error = $this->db->error();
        if ($db_error['code'] != 0) {
            log_message('error', $db_error['message']);
            return FALSE;
        }

        if ($query->num_rows() > 0) {
            /* Check if password is correct */
            $this->db->select('Email');
            $this->db->or_where(array('Username' => $username, 'Email' => $username), 'OR', TRUE);
            $this->db->where('Password', $password);
            $query = $this->db->get('users');

            //$query = "SELECT Username AS Present,Email FROM users WHERE (Username='".$username."' OR Email='".$username."') AND Password='".$password."'";
            //$query = $this->db->query($query);
            //ECHO $query->num_rows();DIE;
            if ($query->num_rows() <= 0) {
                return 'password_error';
            } else {
                return TRUE;
            }
        } else {
            return 'id_error';
        }
    }

    public function checkStatus($id) {
        $prefix = $this->db->dbprefix;
        $query = $this->db->get_where('users', array('ID' => $id, 'Status' => '1'));

        /* CHECK FOR DB ERRORS */
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
    }

    public function activateTermAndCondtion($userId = NULL, $term_version = NULL) {
        if (!empty($userId) && !empty($term_version)) {
            $this->db->where('ID', $userId);
            $this->db->update('cashman_users', array('T_AND_C_Version' => $term_version));
            return true;
        }
    }

    /*
     * Save Logs 
     * @Params array
     * @Return True
    */

    public function updateLogs($data = NULL) {
        if (!empty($data)) {
            $this->db->insert('cashman_logs', $data);		
			return TRUE;
        }
    }

}
