<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Checkout controller to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

class Checkout extends MX_Controller
{

	function index()
	{
		if (is_array($this->session->userdata('basket'))){
			$data = array();
			$data['basket_total'] = $this->master->update_basket_total();
			$data['basket'] = $this->session->userdata('basket');
			$query = $this->db->select('product_key, product_title, product_price')->from('products')->get();
			if ($query->num_rows()>0){
				foreach ($query->result() as $key => $row){
					if (isset($data['basket'][$row->product_key])){
						$data['basket'][$row->product_key]['title'] = $row->product_title;
						$data['basket'][$row->product_key]['price'] = $row->product_price;
					}
				}
			}
			$i=0;
			foreach ($data['basket'] as $key => $value){
				if (!isset($value['price'])){
					$i++;
				}
			}
			if ($i==0){
				$this->load->view('checkout',$data);
			}else{
				$data = array();
				$this->session->unset_userdata('basket');
				$data['content']['body'] = '<table class="normal" align="center" width="400px">';
				$data['content']['body'].= '<tr><td align="center"><br>There are no products in your basket!</td></tr>';
				$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.url().'\';"></td></tr>';
				$data['content']['body'].= '</table>';
				$this->load->view('home',$data);
			}
		}else{
			$data = array();
			$data['content']['body'] = '<table class="normal" align="center" width="400px">';
			$data['content']['body'].= '<tr><td align="center"><br>There are no products in your basket!</td></tr>';
			$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.url().'\';"></td></tr>';
			$data['content']['body'].= '</table>';
			$this->load->view('home',$data);
		}
	}
	
	function delivery()
	{
		if (is_array($this->session->userdata('basket'))){
			$data 				= array();
			$shipping_options 	= array();
			$basket 			= $this->session->userdata('basket');
			$basket_count 		= count($basket);
			$i=0;
			foreach ($basket as $basket_key => $basket_values){
				$query = $this->db->select('product_shipping')->from('products')->where('product_key', $basket_key)->get();
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						if ($i==0){
							$options_array = explode(",", $row->product_shipping);
							if ($basket_count==1){
								$new_array = explode(",", $row->product_shipping);
								foreach ($new_array as $new_key => $new_value){
									if (in_array($new_value,$options_array)){
										$shipping_options[] = $new_value;
									}
								}
							}
						}else{
							$new_array = explode(",", $row->product_shipping);
							foreach ($new_array as $new_key => $new_value){
								if (in_array($new_value,$options_array)){
									$shipping_options[] = $new_value;
								}
							}
						}
						$i++;
					}
				}
			}
			$data['user']['title'] 		= '';
		    $data['user']['firstname'] 	= '';
			$data['user']['lastname'] 	= '';
			$data['user']['email'] 		= '';
			$data['user']['address1'] 	= '';
			$data['user']['address2'] 	= '';
			$data['user']['city'] 		= '';
			$data['user']['county'] 	= '';
			$data['user']['postcode'] 	= '';
			$data['user']['country'] 	= '';
			$data['user']['shipping'] 	= '';
			$query = $this->db->select('country_code, country_title')->from('countries')->order_by('country_title')->get();
			if ($query->num_rows()>0){
				foreach ($query->result() as $key => $row){	
					$data['countries'][$row->country_code] = $row->country_title;
				}
			}
			$query = $this->db->from('shipping')->where('shipping_active', 1)->order_by('shipping_default DESC, shipping_price', 'ASC')->get();
			if ($query->num_rows()>0){
				foreach ($query->result() as $key => $row){	
					if (in_array($row->shipping_id,$shipping_options)){
						if ($row->shipping_default==1){
							$data['ship_default']=$row->shipping_title;
						}else{
							$data['ship_default']='';
						}
						$data['shipping'][$row->shipping_id]['title']		= $row->shipping_title;
						$data['shipping'][$row->shipping_id]['price']		= $row->shipping_price;
						$data['shipping'][$row->shipping_id]['countries'] 	= $row->shipping_countries;
					}else{
						// No shipping options available to cover all the items in your basket, please order separately!
						$data['shipping'][0]['title']		= 'Please contact us!';
						$data['shipping'][0]['price']		= '0.00';
						$data['shipping'][0]['countries'] 	= '0';
					}
				}
			}else{
				$data['shipping'][0]['title']		= 'Please contact us!';
				$data['shipping'][0]['price']		= '0.00';
				$data['shipping'][0]['countries'] 	= '0';
			}
			if ($this->session->userdata('user_id')!=''){
				$data['user']['shipping'] = $this->session->userdata('user_shipping');
			}
			$this->load->view('checkout/delivery',$data);
		}else{
			$data = array();
			$data['content']['body'] = '<table class="normal" align="center" width="400px">';
			$data['content']['body'].= '<tr><td align="center"><br>There are no products in your basket!</td></tr>';
			$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.url().'\';"></td></tr>';
			$data['content']['body'].= '</table>';
			$this->load->view('home',$data);
		}
	}
	
	function summary()
	{
		if (is_array($this->session->userdata('basket'))){
			$data = array();
			$data['basket_total'] 	= $this->master->update_basket_total();
			$data['basket'] 		= $this->session->userdata('basket');
			$query = $this->db->select('product_key, product_title, product_price')->from('products')->get();
			if ($query->num_rows()>0){
				foreach ($query->result() as $key => $row){
					if (isset($data['basket'][$row->product_key])){
						$data['basket'][$row->product_key]['title'] = $row->product_title;
						$data['basket'][$row->product_key]['price'] = $row->product_price;
					}
				}
			}
			$i=0;
			foreach ($data['basket'] as $key => $value){
				if (!isset($value['price'])){
					$i++;
				}
			}
			if ($i==0){
				$ship_title = $this->session->userdata('user_shipping');
				$data['shipping']['title'] = $ship_title;
				$query = $this->db->select('shipping_price')->from('shipping')->where('shipping_title', $ship_title)->get();
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						$data['shipping']['price'] = $row->shipping_price;
					}
				}
				$users_array = $this->session->userdata('users');
				$data['user']['title'] 		= $users_array['user_title'];
			    $data['user']['firstname'] 	= $users_array['user_firstname'];
				$data['user']['lastname'] 	= $users_array['user_lastname'];
				$data['user']['email'] 		= $users_array['user_email'];
				$data['user']['address1'] 	= $users_array['user_address1'];
				$data['user']['address2'] 	= $users_array['user_address2'];
				$data['user']['city'] 		= $users_array['user_city'];
				$data['user']['county'] 	= $users_array['user_county'];
				$data['user']['postcode'] 	= $users_array['user_postcode'];
				$data['user']['country'] 	= $users_array['user_country'];
				$basket_id = $this->session->userdata('basket_id');
				foreach ($data['basket'] as $product_key => $product_info){
					$query = $this->db->select('product_title, product_price')->from('products')->where('product_key', $product_key)->limit(1)->get();
					if ($query->num_rows()>0){
						$i=0;
						$row = $query->row();
						$orders_array = array();
						$orders_array['order_basket'][$i] 	= $basket_id;
						$orders_array['order_date'][$i] 	= date('Y-m-d H:i:s');
						$orders_array['order_item'][$i] 	= $row->product_title;
						$orders_array['order_cost'][$i] 	= $row->product_price;
						$orders_array['order_qty'][$i] 		= $product_info['qty'];
						$orders_array['order_type'][$i] 	= 1;
						$i++;
					}
				}
				$orders_array['order_item'][$i] = $data['shipping']['title'];
				$orders_array['order_cost'][$i] = $data['shipping']['price'];
				$orders_array['order_qty'][$i] 	= 1;
				$orders_array['order_type'][$i] = 2;
				$this->session->set_userdata('orders', $orders_array);
				$data['basket_id'] = $basket_id;
				/* PayPal */
				$this->load->view('checkout/paymodules/paypal',$data);
			}else{
				$data = array();
				$this->session->unset_userdata('basket');
				$data['content']['body'] = '<table class="normal" align="center" width="400px">';
				$data['content']['body'].= '<tr><td align="center"><br>There are no products in your basket!</td></tr>';
				$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.url().'\';"></td></tr>';
				$data['content']['body'].= '</table>';
				$this->load->view('home',$data);
			}
		}else{
			$data = array();
			$data['content']['body'] = '<table class="normal" align="center" width="400px">';
			$data['content']['body'].= '<tr><td align="center"><br>There are no products in your basket!</td></tr>';
			$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.url().'\';"></td></tr>';
			$data['content']['body'].= '</table>';
			$this->load->view('home',$data);
		}
	}
	
	function paypal_success()
	{
		$data = array();
		$data['content']['body'] = '<table class="normal" align="center" width="400px">';
		$data['content']['body'].= '<tr><td align="center"><br>Thank you for your order!</td></tr>';
		$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.url().'\';"></td></tr>';
		$data['content']['body'].= '</table>';
		$this->load->view('home',$data);
	}
	
	function order_cancelled()
	{
		$data = array();
		$data['content']['body'] = '<table class="normal" align="center" width="400px">';
		$data['content']['body'].= '<tr><td align="center"><br>Your order has not been processed!</td></tr>';
		$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.url().'\';"></td></tr>';
		$data['content']['body'].= '</table>';
		$this->load->view('home',$data);
	}
	
	function payment_failed()
	{
		$data = array();
		$data['content']['body'] = '<table class="normal" align="center" width="400px">';
		$data['content']['body'].= '<tr><td align="center"><br>Your order has not been processed!</td></tr>';
		$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.url().'\';"></td></tr>';
		$data['content']['body'].= '</table>';
		$this->load->view('home',$data);
	}
	
}

/* End of file checkout.php */
/* Location: ./application/controllers/checkout.php */