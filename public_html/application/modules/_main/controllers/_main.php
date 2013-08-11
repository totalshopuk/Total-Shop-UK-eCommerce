<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Main controller to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

class _main extends MX_Controller
{

	function top()
	{
		$data = array();
		$query = $this->db->select('category_key, category_parentkey, category_title')->from('categories')->order_by('category_order, category_title')->get();
		foreach ($query->result() as $key => $row){
			$data['categories'][$row->category_key]['key'] 			= $row->category_key;
			$data['categories'][$row->category_key]['parentkey'] 	= $row->category_parentkey;
			$data['categories'][$row->category_key]['title'] 		= $row->category_title;
		}
		$data['level'] = $this->session->userdata('user_level');
		$this->load->view('_top', $data);
	}
	
	function bottom()
	{
		$data = array();
		$data['basket_qty'] 	= $this->master->update_basket_qty();
		$data['basket_total'] 	= $this->master->update_basket_total();
		$this->load->view('_bottom',$data);
	}
	
}

/* End of file _main.php */
/* Location: ./application/modules/_main/controllers/_main.php */