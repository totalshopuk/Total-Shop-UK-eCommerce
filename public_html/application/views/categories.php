<?php 
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Categories view to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */
echo modules::run('_main/top');
?>

<script type="text/javascript" charset="utf-8">
function add_to_basket(key){
	var poststr = 'product='+key;
	ajax('<?php echo url(); ?>_ajax/basket_add', 'status_'+key, poststr, 'POST', '', update_basket, key);
}
function sort_view(sort){
	var poststr = 'sort='+sort;
	ajax('<?php echo url(); ?>_ajax/sort_view_categories', 'item_status', poststr, 'POST', '', refresh_page);
}
function update_basket(key){
	var getdata = document.getElementById('status_'+key).innerHTML;
	var getdata_arr = getdata.split('|');
	document.getElementById('status_'+key).innerHTML=getdata_arr[0];
	document.getElementById('status_'+key).style.display='';
	document.getElementById('basket_qty').innerHTML=getdata_arr[1];
	document.getElementById('basket_total').innerHTML=getdata_arr[2];
	window.setTimeout('document.getElementById("status_'+key+'").innerHTML="";', 2000);
}
function items_per_page(val){
	var poststr = 'val='+val;
	ajax('<?php echo url(); ?>_ajax/items_per_page', 'item_status', poststr, 'POST', '', refresh_page);
}
</script>

<?php
$views = array(
	'default' 		=> 'Best Match',
	'price_asc' 	=> 'Price Lowest First',
	'price_desc' 	=> 'Price Highest First',
	'item_az' 		=> 'Condition a-Z',
	'item_za' 		=> 'Condition Z-a'
	);

echo '<table class="normal" cellpadding="0" cellspacing="0" width="506"><tr>';
echo '<td align="left" valign="top">'.$category['parent_title'].'&nbsp;>&nbsp;<strong>'.$category['cat_title'].'</strong></td>';
echo '<td align="right"><span style="color:#444;font-weight:bold">Sort by</span>&nbsp;&nbsp;';
echo '<select id="sort_by" onchange="sort_view(this.value)">';
foreach ($views as $key => $value) {
	if ($sort_view==$key){$selected=' SELECTED';}else{$selected='';}
	echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
}
echo '</select><span id="item_status" style="display:none"></span><br><br>';
echo '</td>';
echo '</tr></table>';
$i=0;
foreach ($products as $product_info) {
	if ($i!=0){
	echo '<hr style="height:1px; background:#ACA899; border:none;">';	
	}
	echo '<div style="width:506px">';
	echo '<table class="normal">';
	echo '<tr><td rowspan="4" width="100px" style="padding-right:10px">';
	if ($product_info['image']!=''){
		$image_file = 'images/products/'.$product_info['image'];
		if (file_exists($image_file)){
			echo '<a href="'.url().'products/p/'.$product_info['key'].'">'.image_thumb('images/products/'.$product_info['image'], 100, 100).'</a>';
		}else{
			echo '<a href="'.url().'products/p/'.$product_info['key'].'"><img src="'.url().'images/noimage_100x100.gif" alt="No Image!"></a>';
		}
	}else{
		echo '<a href="'.url().'products/p/'.$product_info['key'].'"><img src="'.url().'images/noimage_100x100.gif" alt="No Image!"></a>';
	}
	echo '</td><td colspan="2"><span style="font-size:13px"><a href="'.url().'products/p/'.$product_info['key'].'"><strong>'.$product_info['title'].'</strong></a></span></td></tr>';
	echo '<tr><td width="400px" colspan="2">'.$product_info['description'].'</td></tr>';
	echo '<tr><td rowspan="2"><input type="button" class="buttonstyle" value="More Details" onclick="parent.location=\''.url().'products/p/'.$product_info['key'].'\';"></td>';
	echo '<td align="right">';
	echo '<span style="color:#444">Price:</span>&nbsp;<span style="color:#990000"><strong>&pound;'.number_format($product_info['price'],2).'</strong></span>';
	echo '</td>';
	echo '</tr><tr><td align="right"><span id="status_'.$product_info['key'].'" style="display:none"></span>&nbsp;';
	if ($product_info['buy']==1){
		echo '<input type="button" class="buttonstyle" value="Add To Basket" onclick="add_to_basket(\''.$product_info['key'].'\');">';
	}else{
		echo '<strong>Product currently unavailable!</strong>';
	}
	echo '</td></tr></table>';
	echo '</div>';
	$i++;
}
echo '<br><div style="text-align:center">'.$this->pagination->create_links();
echo '</div>';
if ($num_pages>1){
	echo '<div style="margin-top:8px;text-align:right">Items per page:&nbsp;';
	$i=0;
	foreach ($per_page_array as $value) {
		if ($i!=0){
			echo '&nbsp;|&nbsp;';
		}
		if ($per_page==$value){
			echo $value;
		}else{
			echo '<a href="javascript:items_per_page('.$value.');">'.$value.'</a>';
		}
		$i++;
	}
	echo '</div>';
}
?>

<?php echo modules::run('_main/bottom'); ?>