<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Login controller to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

class Login extends MX_Controller
{

	function index()
	{
		if ($this->session->userdata('user_id')!=''){
			header('Location: '.url());
		}else{
			$data=array();
			$this->load->view('login',$data);
		}
	}
	
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */