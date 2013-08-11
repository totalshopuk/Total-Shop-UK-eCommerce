<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Logout controller to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

class Logout extends MX_Controller
{

	function index()
	{
		if ($this->session->userdata('user_id')!=''){
			$data = array();
			$this->session->unset_userdata('user_id');
			$this->session->unset_userdata('user_level');
			header('Location: '.url());
		}else{
			header('Location: '.url().'login');
		}
	}
	
}

/* End of file logout.php */
/* Location: ./application/controllers/logout.php */