<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Lightbox controller to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

class Lightbox extends MX_Controller
{

	function index()
	{
		header('Location: '.url());
		$this->load->view('_ajax/_blank');
	}
	
	function i()
	{
		$data = array();
		$data['img'] = $this->uri->segment(3,'');
		if (($data['img']=='') || (!file_exists('images/products/'.$data['img']))){
			// $data ['img'] = 'noimage.gif';
		}
		$this->load->view('lightbox',$data);
	}
	
}

/* End of file lightbox.php */
/* Location: ./application/controllers/lightbox.php */