<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Products controller to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

class Products extends MX_Controller
{

	function index()
	{
		header('Location: '.url());
	}
	
	function c()
	{
		$data = array();
		$name = $this->uri->segment(3, 'Home');
		$query = $this->db->select('category_title, category_parentkey')->from('categories')->where('category_key', $name)->get();
		if ($query->num_rows()>0){
			foreach ($query->result() as $key => $row){
				$data['category']['cat_title'] = $row->category_title;
				if ($row->category_parentkey!='_top'){
					$parent_key = $row->category_parentkey;
					$query2 = $this->db->select('category_title')->from('categories')->where('category_key', $parent_key)->get();
					if ($query2->num_rows()>0){
						foreach ($query2->result() as $key2 => $row2){
							$data['category']['parent_title'] = $row2->category_title;
						}
					}
				}else{
					$data['category']['parent_title']='_top';
				}
			}
		}
		$query = $this->db->from('products')->where('product_catkey', $name)->get();
		$total_rows=$query->num_rows();
		if (!is_num($this->uri->segment(4, 0)) || $this->uri->segment(4, 0)>10000){
			header('Location: '.url());
		}else{
			$offset = $this->uri->segment(4, 0);
			if ($this->session->userdata('per_page')!=''){
				$data['per_page'] = $this->session->userdata('per_page');
			}else{
				$data['per_page'] = 4;
			}
			if ($this->session->userdata('sort_view')==''){
				$this->session->set_userdata('sort_view','default');
			}
			$this->db->from('products')->where('product_catkey', $name);
			if ($this->session->userdata('sort_view')=='price_asc'){
				$this->db->order_by('product_price, product_order, product_title','ASC');
			}elseif ($this->session->userdata('sort_view')=='price_desc'){
				$this->db->order_by('product_price DESC, product_order, product_title','ASC');
			}elseif ($this->session->userdata('sort_view')=='item_az'){
				$this->db->order_by('product_title, product_order','ASC');
			}elseif ($this->session->userdata('sort_view')=='item_za'){
				$this->db->order_by('product_title DESC, product_order','ASC');
			}else{
				$this->db->order_by('product_order, product_title','ASC');
			}
			$this->db->limit($data['per_page'], $offset);
			$query = $this->db->get();
			if ($query->num_rows()>0){
				foreach ($query->result() as $key => $row){
					$data['products'][$row->product_key]['key'] 		= $row->product_key;
					$data['products'][$row->product_key]['title'] 		= $row->product_title;
					$data['products'][$row->product_key]['description'] = truncate($row->product_description,100);
					$data['products'][$row->product_key]['price']		= $row->product_price;
					$data['products'][$row->product_key]['buy']			= $row->product_buy;
					$data['products'][$row->product_key]['image'] 		= $row->product_image;
				}
				$this->load->library('pagination');
				$config['uri_segment'] 		= 4;
				$config['num_links'] 		= 10;
				$config['base_url'] 		= url().'products/c/'.$name;
				$config['total_rows'] 		= $total_rows;
				$config['per_page'] 		= $data['per_page'];
				$this->pagination->initialize($config);
				$data['num_pages'] = ceil($config['total_rows']/$config['per_page']);
				$data['per_page_array'] = array(5,10,20,50);
				$data['sort_view'] = $this->session->userdata('sort_view');
				$this->load->view('categories',$data);
			}else{
				$data['content']['body'] = '<table class="normal" align="center" width="400px">';
				$data['content']['body'].= '<tr><td align="center"><br>Sorry there are no products in this category!</td></tr>';
				$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.url().'\';"></td></tr>';
				$data['content']['body'].= '</table>';
				$this->load->view('home',$data);
			}
		}
	}
	
	function p()
	{
		$name = $this->uri->segment(3, 'Home');
		$query = $this->db->from('products')->where('product_key', $name)->get();
		if ($query->num_rows()>0){
			foreach ($query->result() as $key => $row){
				$cat_key = $row->product_catkey;
				$query2 = $this->db->select('category_title, category_parentkey')->from('categories')->where('category_key', $cat_key)->get();
				if ($query2->num_rows()>0){
					foreach ($query2->result() as $key2 => $row2){
						$cat_title = $row2->category_title;
						if ($row2->category_parentkey!='_top'){
							$parent_key = $row2->category_parentkey;
							$query3 = $this->db->select('category_title')->from('categories')->where('category_key', $parent_key)->get();
							if ($query3->num_rows()>0){
								foreach ($query3->result() as $key3 => $row3){
									$parent_title = $row3->category_title;
								}
							}
						}else{
							$parent_title='_top';
						}
					}
				}
				$data['products']['key'] 			= $row->product_key;
				$data['products']['catkey'] 		= $row->product_catkey;
				$data['products']['cat_title'] 		= $cat_title;
				$data['products']['parent_title'] 	= $parent_title;
				$data['products']['title'] 			= $row->product_title;
				$data['products']['description'] 	= auto_link($row->product_description);
				$data['products']['price'] 			= $row->product_price;
				$data['products']['buy'] 			= $row->product_buy;
				$data['products']['image'] 			= $row->product_image;
			}
			$query2 = $this->db
			->select('product_key, product_title, product_price, product_image')
			->from('products')
			->where('product_catkey', $row->product_catkey)
			->where('product_key !=', $row->product_key)
			->order_by('RAND()')
			->limit(3)
			->get();
			if ($query2->num_rows()>0){
				$i=0;
				foreach ($query2->result() as $key2 => $row2){
					$data['products']['related'][$i]['key'] 	= $row2->product_key;
					$data['products']['related'][$i]['title'] 	= $row2->product_title;
					$data['products']['related'][$i]['price'] 	= $row2->product_price;
					$data['products']['related'][$i]['image'] 	= $row2->product_image;
					$i++;
					
				}
			}
			$this->load->view('products', $data);
		}else{
			$data['content']['body'] = '<table class="normal" align="center" width="400px">';
			$data['content']['body'].= '<tr><td align="center"><br>Sorry product not available!</td></tr>';
			$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.url().'\';"></td></tr>';
			$data['content']['body'].= '</table>';
			$this->load->view('home',$data);
		}
	}
	
}

/* End of file products.php */
/* Location: ./application/controllers/products.php */