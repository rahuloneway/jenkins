<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common extends CI_Model {

    public function __construct() {
        parent::__construct();
		$this->checkChooseCompany();
    }
    public function checkChooseCompany() {
		$companyRequired = $this->session->userdata('chooseCompanyRequired');
		$companyId       = $this->session->userdata('choosedCompanyId');		
		if($companyRequired == 'yes')
		{
			$page_url = $this->uri->segment(1); 
			if( ( $companyId <= 0 || $companyId == '' ) && $page_url != 'setcompany')
				setRedirect(site_url() . 'setcompany');
		}			
	}
}
