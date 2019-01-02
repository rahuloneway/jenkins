<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Backup extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();

			$this->load->helper('globalfunction_helper');
			define("BACKUP_LOG_FILE", "./Backup-Log.php");
		}

		public function file_transfer($folder = null)
		{
			$msg = "Connecting to the Ftp server...";
			error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);

			$source_file = $folder.date('Y-m-d').".zip";

			$ftp_username = "kapil@1wayit.com";
			$ftp_userpass = "kapil@1wayit";
			$ftp_server = "1wayit.com";

			$ftp_conn = ftp_connect($ftp_server);

			if( $ftp_conn )
			{
				
				die('Connected!!!');
				if(ftp_login($ftp_conn, $ftp_username, $ftp_userpass))
				{
					$file_list = ftp_nlist($ftp_conn, ftp_pwd($ftp_conn));
					$flag = 0;
					if(in_array("public_html", $file_list))
					{
						$dir = "public_html/backups";
						$destination_file = $dir."/".date('Y-m-d').".zip";
						$f_list = ftp_nlist($ftp_conn, ftp_pwd($ftp_conn)."public_html");
						if(in_array("backups", $f_list))
						{
							$flag = 1;
						}
					}
					elseif(in_array("backups", $file_list))
					{
						$dir = "backups";
						$destination_file = $dir."/".date('Y-m-d').".zip";
						$f_list = ftp_nlist($ftp_conn, ftp_pwd($ftp_conn));
						if(in_array("backups", $f_list))
						{
							$flag = 1;
						}
					}
					else
					{
						$dir = "backups";
						$destination_file = $dir."/".date('Y-m-d').".zip";
						$f_list = ftp_nlist($ftp_conn, ftp_pwd($ftp_conn));
						if(in_array("backups", $f_list))
						{
							$flag = 1;
						}
					}

					if ( $flag != 1 )
					{
						ftp_mkdir($ftp_conn, $dir);
						$msg = "$dir folder has been created.";
						error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
					}

					if (ftp_put($ftp_conn, $destination_file, $source_file, FTP_BINARY))
					{
						unlink($source_file);
						$msg = "Backup has been uploaded on ".$ftp_server;
						error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
					}
					else
					{
						$msg = "Don't have Permission to upload the backup on server.";
						error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
					}

					ftp_close($ftp_conn);
				}
				else
				{
					$msg = "Login Credentials are Incorrect.";
					error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
				}
			}
			else
			{
				$msg = "Could'nt connect to server. The Hostname is Incorrect.";
				error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
			}
		}

		public function backup_cron()
		{
			$cur_date = date('Y-m-d');
			$date = date('Y-m-d',strtotime('-3 days'));
			$fld = FCPATH."backups/";
			$date_fld = FCPATH."backups/".$cur_date."/";

			$this->file_transfer($fld);die;

			$archive_file = FCPATH."backups/".$cur_date;
			$db = $this->db->database;
			$uname = $this->db->username;
			$pwd = $this->db->password;
			$f = "backup_".$cur_date;
			$db_file = $db.".sql";

			/*------------ checking if Backups directory is created or not  -----------*/
			if(!is_dir($fld))
			{
				mkdir($fld,0777);
				if(!is_dir($date_fld))
				{
					$msg = "Backups directory created.";
					error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
					mkdir($date_fld,0777);
				}
			}
			else if(!is_dir($date_fld))
			{
				$msg = $date_fld." folder has been created.";
				error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
				mkdir($date_fld,0777);
			}

			$this->remove_directory($fld);

			/*------- Checking if today's backup already exists. If yes then don't create backup --------*/
			if(is_dir($date_fld) && (!file_exists($date_fld.$f) && !file_exists($date_fld.$db_file)))
			{
				/*-------------  WEBSITE BACKUP  -------------*/
				$msg = "Generating Website & Database backup...";
				error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
				$site_backup_cmd = "zip -r ".$date_fld.$f." ".FCPATH;
				shell_exec($site_backup_cmd);
				$msg = "Website backup has been generated.";
				error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);

				/*-------------  DATABASE BACKUP  -------------*/
				$database_backup_cmd = "mysqldump -u ".$uname." -p".$pwd." ".$db." > ".$date_fld.$db_file;
				shell_exec($database_backup_cmd);
				$msg = "Database backup has been generated.";
				error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
			}

			if(is_dir($date_fld))
			{
				/*-------- CREATING ARCHIVE FOR EMAIL --------*/
				$archive_backup_cmd = "zip -r ".$archive_file." ".$date_fld;
				shell_exec($archive_backup_cmd);
				$msg = "Backup archive has been created.";
				error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
				//$this->email_backup($fld);
				$this->file_transfer($fld);
			}
		}

		public function remove_directory($folder)
		{
			$d = array();
			$date = strtotime(date('Y-m-d',strtotime('-3 days')));
			$files = glob($folder.'*');
			if(!empty($files))
			{
				foreach($files as $file)
				{
					$file_info = pathinfo($file);
					if(!array_key_exists('extension', $file_info))
					{
						$d = $file_info["basename"];
						$d = strtotime($d);
						if( $d <= $date )
						{
							$this->remove_files($file);
						}
					}
					else
					{
						unlink($file);
					}
				}
			}
		}

		public static function remove_files($dir)
		{
			$files = array_diff(scandir($dir), array('.','..'));
			foreach ($files as $file)
			{
				(is_dir("$dir/$file")) ? remove_files("$dir/$file") : unlink("$dir/$file");
			}
			return rmdir($dir);
		}



		/*public function email_backup($folder)
		{
			ini_set('memory_limit', '-1');

			$date = date('Y-m-d').'.zip';
			$zip_file = FCPATH."backups/".$date;
			$config = Array(
				'mailtype' => 'html',
				'wordwrap' => TRUE,
				'charset' => 'iso-8859-1',
			);

			$this->load->library('email',$config);
			$this->email->from('info@wis-accountancy.co.uk');
			$this->email->to('rishi.bakshi@xcelance.net');
			$this->email->subject('Backup of site');
			$this->email->message('Taking backup');
			$this->email->attach($zip_file);
			if($this->email->send())
			{
				log_message("error","MAIL HAS BEEN SENT!!");
				//unlink($zip_file);
			}
			else
			{
				log_message("error", "ERROR WHILE SENDING MAIL!");
			}
		}*/
	}
?>