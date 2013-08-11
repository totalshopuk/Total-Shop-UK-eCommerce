<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The AJAX controller to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

class _ajax extends MX_Controller
{
	
	function index()
	{
		header('Location: '.url());
		$data['msg'] = 'Redirect';
		$this->load->view('_ajax/_blank',$data);
	}
	
	function basket_update_items()
	{
		if ($this->input->post('items')!=''){
			$data = array();
			$items = array();
			parse_str(str_replace('qty_','',$this->input->post('items')), $items);
			unset($items['first']);
			$basket = $this->session->userdata('basket');
			foreach ($basket as $key => $value){
				if (is_num($items[$key]) && $items[$key]>0){
					$basket[$key]['qty'] = $items[$key];
				}else if ($items[$key]==0){
					unset($basket[$key]);
				}
			}
			$this->session->set_userdata('basket',$basket);
			if (count($basket)==0){
			 	$this->session->unset_userdata('basket');
			}
			$data['msg'] = 'Items Updated';
			$this->load->view('_ajax/_blank',$data);
		}else{
			$data = array();
			$data['msg'] = 'Items Not Updated';
			$this->load->view('_ajax/_blank',$data);
		}
	}
	
	function basket_remove_item()
	{
		if ($this->input->post('key')!=''){
			$data = array();
			$basket = $this->session->userdata('basket');
			unset($basket[$this->input->post('key')]);
			if (count($basket)==0){
				$this->session->unset_userdata('basket');
			}else{
				$this->session->set_userdata('basket',$basket);
			}
			$data['msg'] = 'Item Removed';
			$this->load->view('_ajax/_blank',$data);
		}else{
			$data = array();
			$data['msg'] = 'Item Not Removed';
			$this->load->view('_ajax/_blank',$data);
		}
	}
	
	function save_basket()
	{
		$basket_id = $this->session->userdata('basket_id');
		if ($this->input->post('reset')=='true'){
			$this->master->clear_basket();
			$data['msg'] = 'Basket Cleared!';
		}else{
			$data['msg'] = 'Basket Not Cleared!';
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function reset_basket()
	{
		$data = array();
		if ($this->input->post('reset')=='true'){
			$this->master->clear_basket();
			$data['msg'] = 'Basket Cleared!';
		}else{
			$data['msg'] = 'Basket Not Cleared!';
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function basket_add()
	{
		$data = array();
		$data['msg'] = $this->master->add_to_basket();
		$data['msg'].= '|'.$this->master->update_basket_qty();
		$data['msg'].= '|'.number_format($this->master->update_basket_total(),2);
		$this->load->view('_ajax/_blank',$data);
	}

	function login()
	{
		$data = array();
		if ($this->input->post('email')!='' && $this->input->post('password')!=''){
			$email = $this->input->post('email');
			$pass = $this->input->post('password');
			$query = $this->db->select('user_id, user_level')->from('users')->where('user_email', $email)->where('user_password', $pass)->where('user_active', 1)->get();
			if ($query->num_rows()>0){
				foreach ($query->result() as $key => $row){
					$id 	= $row->user_id;
					$level	= $row->user_level;
				}
				$this->session->set_userdata('user_id', $id);
				$this->session->set_userdata('user_level', $level);
				$data['msg'] = 1;
			}else{
				$data['msg'] = 0;
			}
		}else{
			$data['msg'] = 0;
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function save_details()
	{
		$data['msg'] = 0;
		$this->load->helper('email');
		if ($this->input->post('title')!='' && $this->input->post('firstname')!='' && $this->input->post('lastname')!='' && $this->input->post('email')!='' && $this->input->post('address1')!='' && $this->input->post('city')!='' && $this->input->post('postcode')!='' && $this->input->post('country')!='' && $this->input->post('shipping')!='' && valid_email($this->input->post('email'))){
			$title 	   		= $this->input->post('title');
			$firstname 		= $this->input->post('firstname');
			$lastname   	= $this->input->post('lastname');
			$email  		= $this->input->post('email');
			$address1  		= $this->input->post('address1');
			if ($this->input->post('address2')!=''){$address2 = $this->input->post('address2');}else{$address2 = '';}
			$city 			= $this->input->post('city');
			if ($this->input->post('county')!=''){$county = $this->input->post('county');}else{$county = '';}
			$postcode  		= $this->input->post('postcode');
			$country_code 	= $this->input->post('country');
			$shipping_id	= $this->input->post('shipping');
			$query = $this->db->select('shipping_title, shipping_countries')->from('shipping')->where('shipping_id', $shipping_id)->get();
			if ($query->num_rows()>0){
				foreach ($query->result() as $key => $row){
					$shipping 			= $row->shipping_title;
					$shipping_countries = $row->shipping_countries;
				}
			}
			$ship_countries_array = explode(",",$shipping_countries);
			if (in_array($country_code,$ship_countries_array)){
				$this->session->set_userdata('user_shipping', $shipping);
				$query = $this->db->select('country_title')->from('countries')->where('country_code', $country_code)->get();
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						$country = $row->country_title;
					}
				}
				$users_array = array();
				$users_array['user_date'] 		= date('Y-m-d H:i:s');
				$users_array['user_ip'] 		= $this->session->userdata('ip_address');
				$users_array['user_title'] 		= $title;
				$users_array['user_firstname'] 	= $firstname;
				$users_array['user_lastname'] 	= $lastname;
				$users_array['user_email'] 		= $email;
				$users_array['user_address1'] 	= $address1;
				$users_array['user_address2'] 	= $address2;
				$users_array['user_city'] 		= $city;
				$users_array['user_county'] 	= $county;
				$users_array['user_postcode'] 	= $postcode;
				$users_array['user_country'] 	= $country;
				$this->session->set_userdata('users', $users_array);
			}
			$data['msg'] = 1;
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function sort_view_categories()
	{
		if ($this->input->post('sort')!=''){
			$view = $this->input->post('sort');
			$this->session->set_userdata('sort_view',$view);
			$data['msg'] = 'View Changed!';
		}else{
			$data['msg'] = 'View Not Changed!';
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function items_per_page()
	{
		if (is_num($this->input->post('val'))){
			$per_page = $this->input->post('val');
			$this->session->set_userdata('per_page',$per_page);
			$data['msg'] = 'Per Page Changed!';
		}else{
			$this->session->set_userdata('per_page',4);
			$data['msg'] = 'Per Page Reset!';
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function edit_categories()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('type')=='new' && $this->input->post('title')!=''){
				$stripped_string = preg_replace('/[^A-Za-z0-9 ]/', '', $this->input->post('title'));  
				$created_key = strtolower(str_replace(' ', '-', $stripped_string));
				$query = $this->db->select('category_key')->from('categories')->where('category_key', $created_key)->get();
				if ($query->num_rows()>0){
					$data = array();
					$data['msg'] = 'Duplicate Key!';
				}else{
					$this->db->set('category_key', $created_key);
					$this->db->set('category_title', $this->input->post('title'));
					$this->db->set('category_parentkey', $this->input->post('parentkey'));
					$this->db->insert('categories');
					$data = array();
					$data['msg'] = 'Added!';
				}
			}
			if ($this->input->post('type')=='edit' && $this->input->post('title')!=''){
				$stripped_string = preg_replace('/[^A-Za-z0-9 ]/', '', $this->input->post('title'));  
				$created_key = strtolower(str_replace(' ', '-', $stripped_string));
				$this->db->set('category_key', $created_key);
				$this->db->set('category_title', $this->input->post('title'));
				$this->db->set('category_parentkey', $this->input->post('parentkey'));
				$this->db->where('category_key', $this->input->post('key'));
				$this->db->update('categories');
				$this->db->set('product_catkey', $created_key);
				$this->db->where('product_catkey', $this->input->post('key'));
				$this->db->update('products');
				$this->db->set('category_parentkey', $created_key);
				$this->db->where('category_parentkey', $this->input->post('key'));
				$this->db->update('categories');
				$data = array();
				$data['msg'] = 'Saved!';
			}
			if ($this->input->post('type')=='delete'){
				$catkey = $this->input->post('key');
				$query = $this->db->select('product_key, product_image')->from('products')->where('product_catkey', $catkey)->get();
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						$file_key 	= $row->product_key;
						$ext 		= strrchr($row->product_image, '.');
						if (file_exists('images/products/'.$file_key.$ext)){
							unlink('images/products/'.$file_key.$ext);
					    }
						if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext);
						}
						if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext);
						}
						if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext);
						}
					}
				}
				$this->db->where('product_catkey', $catkey);
				$this->db->delete('products');
				$this->db->where('category_key', $catkey);
				$this->db->delete('categories');
				$query = $this->db->select('category_key')->from('categories')->where('category_parentkey', $catkey)->get();
				$category_keys = array();
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						$category_keys[] = $row->category_key;
					}
				}
				$this->db->where('category_parentkey', $catkey);
				$this->db->delete('categories');
				if (count($category_keys)>0){
					foreach ($category_keys as $value){
						$query2 = $this->db->select('product_key, product_image')->from('products')->where('product_catkey', $value)->get();
						if ($query2->num_rows()>0){
							foreach ($query2->result() as $key2 => $row2){
								$file_key 	= $row2->product_key;
								$ext = strrchr($row2->product_image, '.');
								if (file_exists('images/products/'.$file_key.$ext)){
									unlink('images/products/'.$file_key.$ext);
							    }
								if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext)){
									unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext);
								}
								if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext)){
									unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext);
								}
								if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext)){
									unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext);
								}
							}
						}
						$this->db->where('product_catkey', $value);
						$this->db->delete('products');
					}
				}
				$data = array();
				$data['msg'] = 'Deleted!';
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function sort_categories()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('order')!=''){
				$order_array = explode("divContainer[]=", urldecode($this->input->post('order')));
				$i=0;
				foreach($order_array as $order){
					$order=str_replace('&','',$order);
					if ($order!=''){
						$this->db->set('category_order', $i);
						$this->db->where('category_key', $order);
						$this->db->update('categories');
					}
					$i++;
				}
			}
		}
		$this->load->view('_ajax/_blank');
	}
	
	function edit_shipping()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('title')!='' && $this->input->post('price')!=''){
				$shipping_title 	= preg_replace('/[^A-Za-z0-9 ]/', '', $this->input->post('title'));
				$shipping_price		= $this->input->post('price');
				if ($this->input->post('shipping_default')=='true'){
					$shipping_default 	= 1;
					$this->db->set('shipping_default', 0);
					$this->db->where('shipping_default', 1);
					$this->db->update('shipping');
				}else{
					$shipping_default = 0;
				}
				if ($this->input->post('type')=='new'){
					$query = $this->db->select('shipping_title')->from('shipping')->where('shipping_title', $shipping_title)->get();
					if ($query->num_rows()>0){
						$data = array();
						$data['msg'] = 'Duplicate Title!';
					}else{
						$this->db->set('shipping_title', $shipping_title);
						$this->db->set('shipping_price', $shipping_price);
						$this->db->set('shipping_default', $shipping_default);
						$this->db->set('shipping_active', 1);
						$this->db->insert('shipping');
						$data = array();
						$data['msg'] = 'Added!';
					}
				}
				if ($this->input->post('type')=='edit'){
					$this->db->set('shipping_title', $shipping_title);
					$this->db->set('shipping_price', $shipping_price);
					$this->db->set('shipping_default', $shipping_default);
					$this->db->where('shipping_id', $this->input->post('key'));
					$this->db->update('shipping');
					$data = array();
					$data['msg'] = 'Saved!';
				}
				if ($this->input->post('type')=='delete'){
					$query = $this->db->select('product_key, product_shipping')->from('products')->where('product_shipping !=', '')->get();
					if ($query->num_rows()>0){
						foreach ($query->result() as $key => $row){
							$i=0;
							$shipping_array = explode(',',$row->product_shipping);
							foreach ($shipping_array as $key => $value){
								if ($value==$this->input->post('key')){
									unset($shipping_array[$key]);
									$i=1;
								}
							}
							if ($i==1){
								$this->db->set('product_shipping', implode(',',$shipping_array));
								$this->db->where('product_key', $row->product_key);
								$this->db->update('products');
							}
						}
					}
					$this->db->where('shipping_id', $this->input->post('key'));
					$this->db->delete('shipping');
					$data = array();
					$data['msg'] = 'Deleted!';
				}
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function edit_shipping_country()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('key')!='' && $this->input->post('code')!='' && $this->input->post('code')!='-1'){
				$shipping_id = $this->input->post('key');
				$query = $this->db->select('shipping_countries')->from('shipping')->where('shipping_id', $shipping_id)->get();
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						$cc_array = explode(",", $row->shipping_countries);
						$country_codes = $row->shipping_countries;
					}
				}
				foreach ($cc_array as $cc){
					$country_codes_array[$cc] = $cc;
				}
				if (in_array($this->input->post('code'),$country_codes_array)){
					unset($country_codes_array[$this->input->post('code')]);
					$this->db->set('shipping_countries', implode(',',$country_codes_array));
					$this->db->where('shipping_id', $shipping_id);
					$this->db->update('shipping');
				}else{
					if ($country_codes!=''){
						$country_codes.=','.$this->input->post('code');
					}else{
						$country_codes.=$this->input->post('code');
					}
					$this->db->set('shipping_countries', $country_codes);
					$this->db->where('shipping_id', $shipping_id);
					$this->db->update('shipping');
				}
				$data['msg'] = 'Updated!';
			}else{
				$data['msg'] = 'Not Updated!';
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function sort_products()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('order')!=''){
				$order_array = explode("divContainer[]=", urldecode($this->input->post('order')));
				$i=0;
				foreach($order_array as $order){
					$order=str_replace('&','',$order);
					if ($order!=''){
						$this->db->set('product_order', $i);
						$this->db->where('product_key', $order);
						$this->db->update('products');
						$i++;
					}
				}
			}
		}
		$this->load->view('_ajax/_blank');
	}
	
	function edit_products()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('type')=='new' && $this->input->post('title')!=''){
				$stripped_string = preg_replace('/[^A-Za-z0-9 ]/', '', $this->input->post('title'));  
				$created_key = strtolower(str_replace(' ', '-', $stripped_string));
				$product_buy = $this->input->post('buy');
				if ($product_buy=='true'){
					$can_buy=1;
				}else{
					$can_buy=0;
				}
				$query = $this->db->select('product_key')->from('products')->where('product_key', $created_key)->get();
				if ($query->num_rows()>0){
					$data = array();
					$data['msg'] = 'Duplicate Key!';
				}else{
					$this->db->set('product_key', $created_key);
					$this->db->set('product_title', $this->input->post('title'));
					$this->db->set('product_description', utf8_encode(htmlentities($this->input->post('description'),ENT_QUOTES,"UTF-8")));
					$this->db->set('product_price', str_replace(',', '', $this->input->post('price')));
					$this->db->set('product_buy', $can_buy);
					$this->db->set('product_catkey', $this->input->post('catkey'));
					$this->db->insert('products');
					$data = array();
					$data['msg'] = 'Added!';
				}
				$this->session->set_flashdata('expand_this', $created_key);
			}
			if ($this->input->post('type')=='edit' && $this->input->post('title')!=''){
				$product_key = $this->input->post('key');
				$stripped_string = preg_replace('/[^A-Za-z0-9 ]/', '', $this->input->post('title'));  
				$created_key = strtolower(str_replace(' ', '-', $stripped_string));
				$query = $this->db->select('product_image')->from('products')->where('product_key', $product_key)->get();
				if ($query->result()>0){
					foreach ($query->result() as $key => $row){
						$name = $row->product_image;
					}
					$ext = strrchr($name, '.');
					if (file_exists('images/products/_thumbs/'.$product_key.'_100x100'.$ext)){
						unlink('images/products/_thumbs/'.$product_key.'_100x100'.$ext);
					}
					if (file_exists('images/products/_thumbs/'.$product_key.'_150x150'.$ext)){
						unlink('images/products/_thumbs/'.$product_key.'_150x150'.$ext);
					}
					if (file_exists('images/products/_thumbs/'.$product_key.'_400x400'.$ext)){
						unlink('images/products/_thumbs/'.$product_key.'_400x400'.$ext);
					}
					if (file_exists('images/products/'.$product_key.$ext)){
						rename('images/products/'.$product_key.$ext,'images/products/'.$created_key.$ext);
					}
					$this->db->set('product_image', $created_key.$ext);
					$this->db->where('product_key', $product_key);
					$this->db->update('products');
				}
				$product_buy = $this->input->post('buy');
				if ($product_buy=='true'){
					$can_buy=1;
				}else{
					$can_buy=0;
				}
				$this->db->set('product_key', $created_key);
				$this->db->set('product_title', $this->input->post('title'));
				$this->db->set('product_description', utf8_encode(htmlentities($this->input->post('description'),ENT_QUOTES,"UTF-8")));
				$this->db->set('product_price', str_replace(',', '', $this->input->post('price')));
				$this->db->set('product_buy', $can_buy);
				$this->db->set('product_catkey', $this->input->post('catkey'));
				$this->db->where('product_key', $product_key);
				$this->db->update('products');
				$data = array();
				$data['msg'] = 'Saved!';
				$this->session->set_flashdata('expand_this', $created_key);
			}
			if ($this->input->post('type')=='delete'){
				$file_key = $this->input->post('key');
				$query = $this->db->select('product_image')->from('products')->where('product_key', $file_key)->get();
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						$ext = strrchr($row->product_image, '.');
						if (file_exists('images/products/'.$file_key.$ext)){
							unlink('images/products/'.$file_key.$ext);
					    }
						if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext);
						}
						if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext);
						}
						if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext);
						}
					}
				}
				$this->db->where('product_key', $this->input->post('key'));
				$this->db->delete('products');
				$data = array();
				$data['msg'] = 'Deleted!';
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function edit_product_shipping()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('key')!='' && $this->input->post('shipping')!='' && $this->input->post('shipping')!='-1'){
				$product_key = $this->input->post('key');
				$query = $this->db->select('product_shipping')->from('products')->where('product_key', $product_key)->limit(1)->get();
				if ($query->num_rows()>0){
					$row = $query->row();
					$shipping_arr = explode(",", $row->product_shipping);
					$shipping = $row->product_shipping;
				}
				foreach ($shipping_arr as $sid){
					$shipping_array[$sid] = $sid;
				}
				if (in_array($this->input->post('shipping'),$shipping_array)){
					unset($shipping_array[$this->input->post('shipping')]);
					$this->db->set('product_shipping', implode(",",$shipping_array));
					$this->db->where('product_key', $product_key);
					$this->db->update('products');
				}else{
					if ($shipping!=''){
						$shipping.=','.$this->input->post('shipping');
					}else{
						$shipping.=$this->input->post('shipping');
					}
					$this->db->set('product_shipping', $shipping);
					$this->db->where('product_key', $product_key);
					$this->db->update('products');
				}
				$data['msg'] = 'Updated!';
			}else{
				$data['msg'] = 'Not Updated!';
			}
			$this->session->set_flashdata('expand_this', $product_key);	
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function upload_image()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($_FILES){
				$image_checked = 0;
				foreach ($_FILES as $key => $value){
					if ($this->master->check_mime($value['name'])==1){
						$file_key = str_replace('file_','',$key);
						$query = $this->db->select('product_image')->from('products')->where('product_key', $file_key)->get();
						foreach ($query->result() as $key => $row){
							$name = $row->product_image;
						}
						if ($value['error']==0){
							$ext1 = strtolower(strrchr($value['name'], '.'));
							$ext2 = strrchr($name, '.');
						    if (file_exists('images/products/'.$file_key.$ext1)){
								unlink('images/products/'.$file_key.$ext1);
						    }
						    if (file_exists('images/products/'.$file_key.$ext2)){
								unlink('images/products/'.$file_key.$ext2);
						    }
							if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext1)){
								unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext1);
							}
							if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext2)){
								unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext2);
							}
							if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext1)){
								unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext1);
							}
							if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext2)){
								unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext2);
							}
							if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext1)){
								unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext1);
							}
							if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext2)){
								unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext2);
							}
							move_uploaded_file($value['tmp_name'],'images/products/'.$file_key.$ext1);
							$this->db->set('product_image', $file_key.$ext1);
							$this->db->where('product_key', $file_key);
							$this->db->update('products');
						}
					}
				}
			}
			$data = array();
			$data['msg'] = 'Uploaded!';
			$this->session->set_flashdata('expand_this', $file_key);
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function reset_image()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('key')!=''){
				$file_key = $this->input->post('key');
				$query = $this->db->select('product_image')->from('products')->where('product_key', $file_key)->get();
				foreach ($query->result() as $key => $row){
					$name = $row->product_image;
				}
				$ext = strrchr($name, '.');
			    if (file_exists('images/products/'.$file_key.$ext)){
					unlink('images/products/'.$file_key.$ext);
			    }
				if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext)){
					unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext);
				}
				if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext)){
					unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext);
				}
				if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext)){
					unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext);
				}
				$this->db->set('product_image', '');
				$this->db->where('product_key', $file_key);
				$this->db->update('products');
				$data = array();
				$data['msg'] = 'Reset!';
			}else{
				$data = array();
				$data['msg'] = 'Not Reset!';
			}
			$this->session->set_flashdata('expand_this', $file_key);
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function sort_alpha_products()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			$data = array();
			if ($this->input->post('sort')=='alpha' && $this->input->post('category')!=''){
				$category = $this->input->post('category');
				$i=0;
				$query = $this->db->select('product_key, product_title')->from('products')->where('product_catkey', $category)->order_by('product_title')->get();
				foreach ($query->result() as $key => $row){
					$this->db->set('product_order', $i);
					$this->db->where('product_key', $row->product_key);
					$this->db->update('products');
					$i++;
				}
				$data['msg'] = 'Sorted A-Z!';
			}else{
				$data['msg'] = 'Not Sorted!';
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function sort_alpha_categories()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			$data = array();
			if ($this->input->post('sort')=='alpha' && $this->input->post('parent')!=''){
				$parent = $this->input->post('parent');
				$i=0;
				$query = $this->db->select('category_key, category_title')->from('categories')->where('category_parentkey', $parent)->order_by('category_title')->get();
				foreach ($query->result() as $key => $row){
					$this->db->set('category_order', $i);
					$this->db->where('category_key', $row->category_key);
					$this->db->update('categories');
					$i++;
				}
				$data['msg'] = 'Sorted A-Z!';
			}else{
				$data['msg'] = 'Not Sorted!';
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
}

/* End of file _ajax.php */
/* Location: ./application/controllers/_ajax.php */