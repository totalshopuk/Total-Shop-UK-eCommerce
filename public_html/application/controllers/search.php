<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Search controller to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

class Search extends MX_Controller
{

	function index()
	{
		header('Location: '.url());
		$this->load->view('_ajax/_blank');
	}
	
	function s()
	{
		$search_data = $this->uri->segment(3,'%20');
		$keywords = preg_replace('/[^A-Za-z 0-9\~\%\.\:\_\\-\&]/', '', urldecode($search_data));
		if ($keywords!='' && $keywords!=' '){
			$data['searched'] = $keywords;
		}
		if ($search_data=='%20'){
			$this->db->select('product_key')->from('products');
		}else{
			$keywords_array = explode(' ', $keywords);
			$this->db->select('product_key')->from('products');
			foreach ($keywords_array as $keyword){
				$this->db->like('product_title', $keyword);
			}
		}
		$query = $this->db->get();
		$total_rows=$query->num_rows();
		if (is_num($this->uri->segment(4))){
			$offset = $this->uri->segment(4,0);
		}else{
			$offset = 0;
		}
		if ($this->session->userdata('per_page')!=''){
			$data['per_page'] = $this->session->userdata('per_page');
		}else{
			$data['per_page'] = 4;
		}
		$data['sort_view'] = $this->session->userdata('sort_view');
		if ($search_data=='%20'){
			$this->db->from('products');
			if ($data['sort_view']=='price_asc'){
				$this->db->order_by('product_price, product_order, product_title','ASC');
			}elseif ($data['sort_view']=='price_desc'){
				$this->db->order_by('product_price DESC, product_order, product_title','ASC');
			}elseif ($data['sort_view']=='item_az'){
				$this->db->order_by('product_title, product_order','ASC');
			}elseif ($data['sort_view']=='item_za'){
				$this->db->order_by('product_title DESC, product_order','ASC');
			}else{
				$this->db->order_by('product_order, product_title','ASC');
			}
			$this->db->limit($data['per_page'], $offset);
			$query = $this->db->get();
		}else{
			$this->db->from('products');
			foreach ($keywords_array as $keyword){
				$this->db->like('product_title', $keyword);
			}
			if ($data['sort_view']=='price_asc'){
				$this->db->order_by('product_price, product_order, product_title','ASC');
			}elseif ($data['sort_view']=='price_desc'){
				$this->db->order_by('product_price DESC, product_order, product_title','ASC');
			}elseif ($data['sort_view']=='item_az'){
				$this->db->order_by('product_title, product_order','ASC');
			}elseif ($data['sort_view']=='item_za'){
				$this->db->order_by('product_title DESC, product_order','ASC');
			}else{
				$this->db->order_by('product_order, product_title','ASC');
			}
			$this->db->limit($data['per_page'], $offset);
			$query = $this->db->get();
		}
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
			$config['num_links'] 		= 5;
			$config['base_url'] 		= url().'search/s/'.$keywords;
			$config['total_rows']		= $total_rows;
			$config['per_page'] 		= $data['per_page'];
			$this->pagination->initialize($config);
			$data['num_pages'] = ceil($config['total_rows']/4);
			$data['per_page_array'] = array(5,10,20,50);
			$this->load->view('search',$data);
		}else{
			$data['content']['body'] = '<table class="normal" align="center" width="400px">';
			$data['content']['body'].= '<tr><td align="center"><br>Sorry no products found!</td></tr>';
			$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.url().'\';"></td></tr>';
			$data['content']['body'].= '</table>';
			$this->load->view('home',$data);
		}
	}
	
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */