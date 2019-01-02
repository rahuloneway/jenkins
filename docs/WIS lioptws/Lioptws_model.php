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
 *
 * @author Thilina <http://localhost/>
 */
class Lioptws_model extends CI_Model {
    //put your code here
    
    /**
    * Table name
    * @var string $_table 
    */
    private $_table = "tbl_lioptws";
    
    /**
    * Create user login session
    *
    * @access public
    * @param username   Username
    * @param key Sequrity Key
    * 
    * @author Thilina <http://localhost/>
    * @return mix false on error and int index on success
    */ 
    public function create_user_login_session($username, $key){
        $_data = array(
            'liotws_username' => $username,
            'liotws_key' => $key,
            'liotws_created' => date('Y-n-j'),
            'liotws_is_used' => 0,
        );
        $this->db->insert($this->_table, $_data);
        return ($this->db->affected_rows() ? $this->db->insert_id() : false);
    }
    
    /**
    *  create table
    *
    * @access public
    * 
    * @author Thilina <http://localhost/>
    * @return void
    */ 
    public function create_table(){
        if($this->db->table_exists($this->_table)){
            return;
        }                
        $this->load->dbforge();        
        $_fields = array(
                'liotws_id' => array(
                        'type' => 'INT',
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'liotws_username' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '100',
                ),
                'liotws_key' => array(
                        'type' =>'VARCHAR',
                        'constraint' => '100',
                ),
                'liotws_created' => array(
                        'type' => 'TIMESTAMP',
                ),
                'liotws_is_used' => array(
                        'type' => 'INT',
                        'unsigned' => TRUE,
                        'constraint' => '1',
                ),
        );
        $this->dbforge->add_field($_fields);//$this->dbforge->add_column($this->_table , $_fields);
        $this->dbforge->add_key('liotws_id', TRUE);
        $this->dbforge->create_table($this->_table , TRUE);
    }
    
    /**
    *  user by username
    *
    * @access public
    * @param string $username username
    * @param string $key Key 
    * 
    * @author Thilina <http://localhost/>
    * @return array user data
    */
    public function get_user_by_username_key($username, $key){
        $_whereData = array(
            'liotws_username' => $username,
            'liotws_key' => $key,
            'liotws_is_used' => 0,
        );        
        $this->db->where($_whereData);
        $_q = $this->db->get($this->_table);
        return ($_q->num_rows() ? $_q->row_array() : null );
    }
    
    /**
    *  link is used
    *
    * @access public
    * @param string $username username
    * @param string $key Key
    * 
    * @author Thilina <http://localhost/>
    * @return array user data
    */
    public function make_link_used_by_username_key($username, $key){
        $_whereData = array(
            'liotws_username' => $username,
            'liotws_key' => $key,
            'liotws_is_used' => 0,
        );        
        $_data = array(
            'liotws_is_used' => 1,
        );
        $this->db->where($_whereData);
        $this->db->set($_data);
        $_q = $this->db->update($this->_table);
        return ($_q);
    }
    
    /**
    *  remove used users that are older
    *
    * @access public
    * @param timestamp $olderthan older than 
    * 
    * @author Thilina <http://localhost/>
    * @return void
    */
    public function remove_old_users($olderthan){
        $this->db->where('liotws_created', $olderthan);
        $_q = $this->db->delete($this->_table);
        return ( $_q );
    }
        
}
