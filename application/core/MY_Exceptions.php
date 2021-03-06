<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Exceptions extends CI_Exceptions
{
	public function __construct()
    {
		parent::__construct();
	
		if(ini_get('error_reporting') == 5111)
		{
			$this->show_hidden_errors();
		}
		
    }
	
	public function show_php_error($severity, $message, $filepath, $line)
	{ 
		$templates_path = config_item('error_views_path');
		if (empty($templates_path))
		{
			$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		$severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;

		// For safety reasons we don't show the full file path in non-CLI requests
		if ( ! is_cli())
		{
			$filepath = str_replace('\\', '/', $filepath);
			if (FALSE !== strpos($filepath, '/'))
			{
				$x = explode('/', $filepath);
				$filepath = $x[count($x)-2].'/'.end($x);
			}

			$template = 'html'.DIRECTORY_SEPARATOR.'error_php';
		}
		else
		{
			$template = 'cli'.DIRECTORY_SEPARATOR.'error_php';
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include($templates_path.$template.'.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}
	
	public function show_hidden_errors()
	{
		$buffer = ob_get_contents();
		ob_end_clean();
		$template = 'html'.DIRECTORY_SEPARATOR.'error_php';
		include(APPPATH.'views/errors/'.$template.'.php');exit;
	}
}
