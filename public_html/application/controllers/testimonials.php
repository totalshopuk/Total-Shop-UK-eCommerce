<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Testimonials controller to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

class Testimonials extends MX_Controller
{

	function index()
	{
		$data = array();
		$this->load->view('home',$data);
	}
	
}

/* End of file testimonials.php */
/* Location: ./application/controllers/testimonials.php */