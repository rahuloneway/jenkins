<?php
if (!defined('BASEPATH'))
exit('No direct script access allowed');

class backupCron_model extends CI_Model 
{
	public function Account() 
	{
        parent::__construct();
		ini_set('memory_limit', '-1');
    }
	
	public function database()
	{
		return $this->db->database;
	}
	
	public function all_tables()
	{
		$db = $this->database();
		$return = '';
		$query = $this->db->query('SHOW TABLES');
		$rows = $query->result();
		for($i=0; $i<count($rows); $i++)
		{
			$db_frm = "Tables_in_".$db;
			$allTables[] = $rows[$i]->$db_frm;
		}
		
		foreach($allTables as $table)
		{
			$this->db->select('*');
			$this->db->from($table);
			$query = $this->db->get();
			$rows = $query->result_array();
			$num_fields =  $this->db->list_fields($table);
			$num_fields = count($num_fields);
			$return.= 'DROP TABLE IF EXISTS '.$table.';';
			$query2 = $this->db->query('SHOW CREATE TABLE '.$table);
			$rows2 = $query2->row_array();
			$return.= "\n\n".$rows2['Create Table'].";\n\n";
			
				$row_new = array();
				foreach($rows as $key=>$row)
				{ 
					foreach($row as $row_new)
					{
						$data['row_new2'][$key][] = $row_new;
					}
				}
				  
				foreach($rows as $key=>$val)
				{
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++)
					{
						$row_new2[$j] = addslashes($data['row_new2'][$key][$j]);
						$row_new2[$j] = str_replace("\n","\\n",$data['row_new2'][$key][$j]);
						if (isset($row_new2[$j])) { $return.= '"'.$data['row_new2'][$key][$j].'"' ; }
						else { $return.= '""'; }
						if ($j<($num_fields-1)) { $return.= ','; }
					}
					$return.=");";
				}
			$return.="\n\n";
		}
		return $return;
	}
}