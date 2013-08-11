<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Admin controller to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

class Admin extends MX_Controller
{
	
	function index()
	{
		header('Location: '.url());
		$this->load->view('_ajax/_blank');
	}
	
	function shipping()
	{
		if ($this->session->userdata('user_level')!=1){
			header('Location: '.url());
		}else{
			$data = array();
			$country_codes = array();
			$query = $this->db->select('country_code, country_title')->from('countries')->order_by('country_title')->get();
			if ($query->num_rows()>0){
				foreach ($query->result() as $key => $row){	
					$country_codes[$row->country_code] = $row->country_title;
				}
			}
			$query = $this->db->from('shipping')->order_by('shipping_title')->get();
			if ($query->num_rows()>0){
				foreach ($query->result() as $key => $row){
					$data['shipping'][$row->shipping_id]['title'] 		= $row->shipping_title;
					$data['shipping'][$row->shipping_id]['price'] 		= $row->shipping_price;
					$shipping_country[$row->shipping_id] = explode(",", $row->shipping_countries);
					foreach ($country_codes as $country_code => $country_title){
						if (in_array($country_code,$shipping_country[$row->shipping_id])){
							$data['shipping'][$row->shipping_id]['countries_added'][$country_code] = $country_title;
						}else{
							$data['shipping'][$row->shipping_id]['countries_available'][$country_code] = $country_title;
						}
					}
					$data['shipping'][$row->shipping_id]['default'] 	= $row->shipping_default;
					$data['shipping'][$row->shipping_id]['active'] 		= $row->shipping_active;
				}
			}
			$this->load->view('admin/shipping',$data);
		}
	}
	
	function categories()
	{
		if ($this->session->userdata('user_level')!=1){
			header('Location: '.url());
		}else{
			if ($this->uri->segment(3)!=''){
				$parent_key = $this->uri->segment(3);
				$data = array();
				$query = $this->db
				->select('category_key, category_parentkey, category_title')
				->from('categories')
				->where('category_parentkey', $parent_key)
				->order_by('category_order, category_title')
				->get();
				if ($query->num_rows()>0){
					$data['cats']=1;
					foreach ($query->result() as $key => $row){
					    $data['category'][$row->category_key]['key']		= $row->category_key;
					 	$data['category'][$row->category_key]['parentkey']	= $row->category_parentkey;
					 	$data['category'][$row->category_key]['title']		= $row->category_title;
					}
				}else{
					$data['cats']=0;
				}
				$query = $this->db->select('category_title')->from('categories')->where('category_key', $parent_key)->get();
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
					 	$data['category_title'] = $row->category_title;
					}
				}
				$data['parent_key']=$parent_key;
				$this->load->view('admin/sub_categories',$data);
			}else{
				$data = array();
				$parent_key = '_top';
				$query = $this->db
				->select('category_key, category_parentkey, category_title')
				->from('categories')
				->where('category_parentkey', '_top')
				->order_by('category_order, category_title')
				->get();
				if ($query->num_rows()>0){
					$data['cats']=1;
					foreach ($query->result() as $key => $row){
					    $data['category'][$row->category_key]['key']		= $row->category_key;
					 	$data['category'][$row->category_key]['parentkey']	= $row->category_parentkey;
					 	$data['category'][$row->category_key]['title']		= $row->category_title;
					}
				}else{
					$data['cats']=0;
				}
				$data['parent_key']=$parent_key;
				$this->load->view('admin/categories',$data);
			}
		}
	}
	
	function products()
	{
		if ($this->session->userdata('user_level')!=1){
			header('Location: '.url());
		}else{
			$data = array();
			$catkey = '';
			$i=0;
			$query = $this->db
			->select('category_key, category_title')
			->from('categories')
			->where('category_parentkey', '_top')
			->order_by('category_order, category_title')
			->get();
			if ($query->num_rows()>0){
				foreach ($query->result() as $key => $row){	
					$data['categories'][$row->category_key] = '---'.$row->category_title.'---';
					$query = $this->db
					->select('category_key, category_title')
					->from('categories')
					->where('category_parentkey', $row->category_key)
					->order_by('category_order, category_title')
					->get();
					if ($query->num_rows()>0){
						foreach ($query->result() as $key => $row){	
							if ($i==0){
								$catkey = $this->uri->segment(3,$row->category_key);
								$i++;
							}
							$data['categories'][$row->category_key] = $row->category_title;
						}
					}
				}
			}
			$data['current'] = $catkey;
			$query = $this->db
			->select('product_key, product_catkey, product_title, product_description, product_price, product_buy, product_image, product_shipping')
			->from('products')
			->where('product_catkey', $catkey)
			->order_by('product_order, product_title')
			->get();
			if ($query->num_rows()>0){
				$data['prods']=1;
				foreach ($query->result() as $key => $row){
				    $data['product'][$row->product_key]['key']				= $row->product_key;
				 	$data['product'][$row->product_key]['catkey']			= $row->product_catkey;
				 	$data['product'][$row->product_key]['title']			= $row->product_title;
					$data['product'][$row->product_key]['description']		= $row->product_description;
					$data['product'][$row->product_key]['price']			= $row->product_price;
					$data['product'][$row->product_key]['buy']				= $row->product_buy;
					$data['product'][$row->product_key]['product_image']	= $row->product_image;
					$shipping[$row->product_key] = explode(",", $row->product_shipping);
					$query2 = $this->db->select('shipping_id, shipping_title')->from('shipping')->order_by('shipping_title')->get();
					if ($query2->num_rows()>0){
						foreach ($query2->result() as $key2 => $row2){
							foreach ($shipping as $shipping_key => $shipping_value){
								if (in_array($row2->shipping_id,$shipping_value)){
									$data['shipping'][$shipping_key]['added'][$row2->shipping_id] = $row2->shipping_title;
								}else{
									$data['shipping'][$shipping_key]['avail'][$row2->shipping_id] = $row2->shipping_title;
								}
							}
						}
					}
				}
			}else{
				$data['prods']=0;
			}
			if ($this->session->flashdata('expand_this')!=''){
				$data['expand_this'] = $this->session->flashdata('expand_this');
			}else{
				$data['expand_this'] = '';
			}
			$this->load->view('admin/products',$data);
		}
	}
	
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */