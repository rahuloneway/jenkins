<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

			$cur_date = date('Y-m-d');
			$date = date('Y-m-d',strtotime('-3 days'));
			$fld = "/backups/";
			$date_fld = "/backups/".$cur_date."/";

			//$this->file_transfer($fld);die;

			$archive_file = "/backups/".$cur_date;
			$db = 'docpoke';
			$uname = 'newvhrm';
			$pwd = '!wayIThrm';
			$f = "qbinfusionsoft_backup_".$cur_date;
			$db_file = $db.".sql";

			/*------------ checking if Backups directory is created or not  -----------*/
			if(!is_dir($fld))
			{
				mkdir($fld,0777);
				if(!is_dir($date_fld))
				{
					$msg = "Backups directory created.";
					//error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
					mkdir($date_fld,0777);
				}
			}
			else if(!is_dir($date_fld))
			{
				$msg = $date_fld." folder has been created.";
				//error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
				mkdir($date_fld,0777);
			}

			//$this->remove_directory($fld);

			/*------- Checking if today's backup already exists. If yes then don't create backup --------*/
			if(is_dir($date_fld) && (!file_exists($date_fld.$f) && !file_exists($date_fld.$db_file)))
			{
				/*-------------  WEBSITE BACKUP  -------------*/
				//$msg = "Generating Website & Database backup...";
				//error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
				//$site_backup_cmd = "zip -r ".$date_fld.$f." ".FCPATH;
				//shell_exec($site_backup_cmd);
				//$msg = "Website backup has been generated.";
				//error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);

				/*-------------  DATABASE BACKUP  -------------*/
				$database_backup_cmd = "mysqldump -u ".$uname." -p".$pwd." ".$db." -q > ".$date_fld.$db_file;
				shell_exec($database_backup_cmd);
				echo $msg = "Database backup has been generated.";
				//error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
			}

			if(is_dir($date_fld))
			{
				/*-------- CREATING ARCHIVE FOR EMAIL --------*/
				$archive_backup_cmd = "zip -r ".$archive_file." ".$date_fld;
				shell_exec($archive_backup_cmd);
				$msg = "Backup archive has been created.";
				//error_log(date('[Y-m-d H:i:s] ') . "  ->  " . $msg . PHP_EOL, 3, BACKUP_LOG_FILE);
				//$this->email_backup($fld);
				//$this->file_transfer($fld);
			}
		
?>