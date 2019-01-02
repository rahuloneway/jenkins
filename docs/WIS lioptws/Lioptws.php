<?php

/*
 * Copyright 2016 Brandcentrical (Thilina).
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * limitations under the License.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Description of lioptws
 *
 * @author Thilina <http://localhost/>
 */
///namespace lioptws;
/**
 * Log-in to Online portal through Website
 *
 * @copyright  2016 Brandcentrical (Thilina)
 * @license    GNU General Public License
 * @version    1.0
 * @link       http://localhost/
 */ 
class Lioptws extends CI_Controller {
    
    /**
    * Squrity Key. (change this only once if necessary)
    * @var string $_squrityKey
    */
    private $_squrityKey = "3PIQFVSqx3JGAQ3hTbap";
    
    /**
    * $TryLoginSuccessMessage Login Succes Message
    * @var string $_TryLoginSuccessMessage
    */
    protected $_TryLoginSuccessMessage = "Login Success. Please follow the link.";
    
    /**
    * $SCRIPTURI script url
    * @var string $SCRIPTURI (after hosting this library in your system, create a controller and change this variable to relavent url)
    */
    protected $SCRIPTURI = ""; 
    
    
    /**
    * Constructor
    *
    * @param Place   $where  Where something interesting takes place
    * @param integer $repeat How many times something interesting should happen
    * 
    * @throws Some_Exception_Class If something interesting cannot happen
    * @author Thilina <http://localhost/>
    * @return Status
    */ 
    public function __construct() {  
        parent::__construct();
        
        $this->SCRIPTURI = base_url("lioptws//login");
        
        //load model
        $this->load->model('lioptws_model');
        
        //create table if not exists
        $this->lioptws_model->create_table();
        
        //remove old used users (older than 3 months)
        //$_minLimit = new DateTime();
        //date_sub($_minLimit, new DateInterval('P3M'));
        //$_strMinLimit = date_format($_minLimit, 'Y-m-d');
        $_minLimit = strtotime("-3 months");
        $_strMinLimit = date("Y-n-j", $_minLimit);
        $this->lioptws_model->remove_old_users($_strMinLimit);
        
    }
    
    /**
    * validate user
    *
    * @access private
    * @param username   Username
    * @param key Sequrity Key
    * @param &error Validate Error Message
    * 
    * @author Thilina <http://localhost/>
    * @return bool Status
    */ 
    private function _validate_user($username, $key, &$error) {
        $status = false;        
        //
        $_user = $this->lioptws_model->get_user_by_username_key($username, $key);        
        $status =  ( $_user ? true : false );
        $error = ($status) ? "" : "Invalid or expired Link"; 
        if($status){
            $this->lioptws_model->make_link_used_by_username_key($username, $key);
        }
        return $status;
    }
    
    /**
    * validate user
    *
    * @access private
    * @param username Username
    * @param key Sequrity Key
    * @param &error Validate Error Message
    * 
    * @author Thilina <http://localhost/>
    * @return bool Status
    */ 
    private function _validate_user_with_username_password($username, $password, &$error) {
        $status = false;
        
        /*
         * you need to fill the method whith garding blocks and framwork validation method
         * please assign $error with user friendly error message for respective erros
         * ex: Username or Password is Invalid.
        */
        
        return $status;
    }
    
    /**
    * generate key
    *
    * @access private
    * 
    * @author Thilina <http://localhost/>
    * @return string Key
    */ 
    private function _generate_key() {
        $_date = date('Y-m-d H:i:s');
        return md5($_date . $this->_squrityKey);
    }
    
    /**
    * Login user
    * 
    * @access private
    * @param username Username
    * @param &error Login Error Message
    * 
    * @author Thilina <http://localhost/>
    * @return bool Status
    */ 
    private function _login_user($username, &$error) {
        $status = false;
        
        /*
         * you need to fill the method whith garding blocks and connect this with your system
         * please identify user with username. 
         * please assign $error with user friendly error message for respective erros
         * ex: Unknown Username.
        */     
        
        return $status;
    }
    
    /**
    * TryLogin
    * 
    * @access Public
    * @param username Username
    * @param password Password
    * 
    * @author Thilina <http://localhost/>
    * @return void
    * @outputBuffering responce
    */ 
    public function TryLogin($username, $password) {
        //$username, $password, we need to get theis from POST
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        $_responce = array();
        
        /*
         * you need to fill the method whith garding blocks and connect this with your system
         * please identify user with username. 
         * please assign $error with user friendly error message for respective erros
         * ex: Unknown Username.
        */
        
        $_error = "";
        $_result = $this->_validate_user_with_username_password($username, $password, $_error);
        //
        
        if($_result){ //valid user
            $_key = $this->_generate_key();
            $_loginLink = $this->SCRIPTURI . '/' . $username . '/' . $_key; 
            $this->lioptws_model->create_user_login_session($username, $_key);
            
            $_responce = array(
                'status' => 1,
                'message' => $this->_TryLoginSuccessMessage,
                'data' => array(
                    'link' => $_loginLink,
                )
            ); 
        }else{ //may be a invalid user
              $_responce = array(
                  'status' => 0,
                  'message' => $_error,
              );  
        }
                
        $this->_response($_responce);
    }
    
    /**
    * Login
    * 
    * @access Public
    * @param username Username
    * @param key Key
    * 
    * @author Thilina <http://localhost/>
    * @return void
    */ 
    public function Login($username, $key) {
        //$username, $key, we need to get theis from GET
        
        $_responce = array();
                
        $_error = "";
       
        if( $this->_validate_user($username, $key, $_error) ){ //valid user
            
            if( $this->_login_user($username, $_error) ){ //user logged in
                //redirect to dashboard home page
                //redirect(base_url('/admin_home')); // change this link
                echo 'success';
                
            }else{ //user not logged in
                //flash data with errors. use this errors to show errors on login page. ex: Link has been expired
                $this->session->set_flashdata('login-form-errors', $_error);
                //redirect to login page
                //redirect(base_url('/login')); // change this link 
                echo 'Link has been expired';
            }
            
        }else{ //may be a invalid user       
            //flash data with errors. use this errors to show errors on login page. ex: Username Enterd is invalid
            $this->session->set_flashdata('login-form-errors', $_error);
            //redirect to login page
            //redirect(base_url('/login')); // change this link
            echo $_error;
        }
    }
    
    /**
    * responce
    * 
    * @access Private
    * @param data Data to Print
    * @param coode Status code
    * 
    * @author Thilina <http://localhost/>
    * @return void
    * @outputBuffering responce
    */
    private function _response($data = array(), $code = 200) {
        $this->output
                ->set_status_header($code)
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
    }
    
}


