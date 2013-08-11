<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Master model to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

class Master extends CI_Model
{

	function add_to_basket()
	{
		if ($this->session->userdata('basket_id')==''){
			$basket_id = '';
			while (strlen($basket_id) < 32){
				$basket_id .= mt_rand(0, mt_getrandmax());
			}
			$basket_id .= $this->session->userdata('ip_address');
			$baskey_key = md5(uniqid($basket_id, TRUE));
			$this->session->set_userdata('basket_id', $baskey_key);
		}
		$data = array();
		if ($this->input->post('product')!=''){
			$product = $this->input->post('product');
			$query = $this->db->select('product_key')->from('products')->where('product_key', $product)->limit(1)->get();
			if ($query->num_rows()>0){
				$row = $query->row();
				$product_key = $row->product_key;
				$basket_data = $this->session->userdata('basket');
				if (isset($basket_data[$product_key]['qty'])){
					$basket_data[$product_key]['qty']++;
				}else{
					$basket_data[$product_key]['key']=$product_key;
					$basket_data[$product_key]['qty']=1;
				}
				$this->session->set_userdata('basket',$basket_data);
				return '<strong>Item added!</strong>';
			}else{
				return 'Unable to add item to basket!';
			}
		}else{
			return 'Unable to add item to basket!';
		}
	}

	function update_basket_qty()
	{
		$basket_qty = 0;
		if (is_array($this->session->userdata('basket'))){
			$basket_data = $this->session->userdata('basket');
			foreach ($basket_data as $value){
				$basket_qty+=$value['qty'];
			}
		}
		return $basket_qty;
	}

	function update_basket_total()
	{
		$basket_total = 0.00;
		if (is_array($this->session->userdata('basket'))){
			$basket_data = $this->session->userdata('basket');
			$key_test='';
			foreach ($basket_data as $value){
				$query = $this->db->select('product_price')->from('products')->where('product_key', $value['key'])->get();
				foreach ($query->result() as $key => $row){
				    $basket_total+=($row->product_price*$value['qty']);
				}
			}
		}
		return $basket_total;
	}

	function clear_basket()
	{
		$data = array();
		$this->session->unset_userdata('basket');
		$this->session->unset_userdata('basket_id');
	}
	
	function send_email($to,$subject,$html,$text,$bcc='')
	{
		$this->load->library('email');
		// $config['protocol'] = 'smtp';
		// $config['smtp_host'] = 'hostname';
		// $config['smtp_user'] = 'username';
		// $config['smtp_pass'] = 'password';
		// $config['smtp_port'] = '25';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from('sales@totalshopuk.com', 'Total Shop UK eCommerce Open Source');
		$this->email->to($to);
		if ($bcc!=''){
			$this->email->bcc($bcc);
		}
		$this->email->subject($subject);
		$this->email->message($html);
		$this->email->set_alt_message($text);
		if ($this->email->send()){
			return 1;
		}else{
			return false;
		}
	}
	
	function send_email_to_admin($subject,$html,$text)
	{
		$this->load->library('email');
		// $config['protocol'] = 'smtp';
		// $config['smtp_host'] = 'hostname';
		// $config['smtp_user'] = 'username';
		// $config['smtp_pass'] = 'password';
		// $config['smtp_port'] = '25';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		$this->email->from('sales@totalshopuk.com', 'Total Shop UK eCommerce Open Source');
		$this->email->to('admin@totalshopuk.com');
		$this->email->subject($subject);
		$this->email->message($html);
		$this->email->set_alt_message($text);
		if ($this->email->send()){
			return 1;
		}else{
			return false;
		}
	}

	function check_mime($file){
		$mimes = array(
			'png'		=> 'png',
			'jpe'		=> 'jpe',
			'jpg'		=> 'jpg',
			'jpeg'		=> 'jpeg',
			'gif' 		=> 'gif',
			'bmp' 		=> 'bmp',
			'tiff' 		=> 'tiff',
			'tif' 		=> 'tif',
			'svg' 		=> 'svg',
			'svgz' 		=> 'svgz',
			);
		if (in_array(pathinfo($file, PATHINFO_EXTENSION),$mimes)){
			return 1;
		}else{
			return false;
		}
	}
	
}

/* End of file master.php */
/* Location: ./application/models/master.php */