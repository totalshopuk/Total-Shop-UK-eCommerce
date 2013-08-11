<?php 
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Products view to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */
echo modules::run('_main/top');
?>
	
<script type="text/javascript" src="<?php echo url(); ?>js/prototype.js"></script>
<script type="text/javascript" src="<?php echo url(); ?>js/lightbox.js"></script>
<script type="text/javascript" charset="utf-8">
function add_to_basket(key){
	var poststr = 'product='+key;
	ajax('<?php echo url(); ?>_ajax/basket_add', 'status_'+key, poststr, 'POST', '', update_basket, key);
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
</script>
<?php
echo $products['parent_title'].'&nbsp;>&nbsp;<a href="'.url().'products/c/'.$products['catkey'].'">'.$products['cat_title'].'</a>&nbsp;>&nbsp;<strong>'.$products['title'].'</strong>';
echo '<div style="margin-top:5px; width:506px">';
echo '<table class="normal">';
echo '<tr><td align="center" width="340px"><span style="font-size:14px"><strong>'.$products['title'].'</strong></span><br></td><td>&nbsp;</td></tr>';
echo '<tr><td><br>'.nl2br2($products['description']).'</td><td rowspan="3" valign="top" align="center" width="150px">';
if ($products['image']!=''){
	$image_file = 'images/products/'.$products['image'];
	if (file_exists($image_file)){
		echo '<a href="'.url().'lightbox/i/'.$products['image'].'" class="lbOn">'.image_thumb('images/products/'.$products['image'], 150, 150);
		echo '<br><img src="'.url().'images/zoom.gif" alt="Zoom">Zoom</a>';
	}else{
		echo '<img src="'.url().'images/noimage_150x150.gif" alt="No Image!">';
	}
}else{
	echo '<img src="'.url().'images/noimage_150x150.gif" alt="No Image!">';
}
echo '</td></tr>';
echo '<tr><td><br><span style="color:#444">Price:</span>&nbsp;<span style="color:#990000"><strong>&pound;'.number_format($products['price'],2).'</strong></span><br><br></td></tr>';
if ($products['buy']==1){
	echo '<tr><td><input type="button" class="buttonstyle" value="Add To Basket" onclick="add_to_basket(\''.$products['key'].'\');">&nbsp;<span id="status_'.$products['key'].'" style="display:none"></span></td></tr>';
}else{
		echo '<tr><td><strong>Product currently unavailable!</strong>&nbsp;<span id="status_'.$products['key'].'" style="display:none"></span></td></tr>';
}
echo '</table>';
echo '</div>';
echo '<p align="center"><input type="button" class="buttonstyle" value="&lt;&lt; Back" onClick="parent.history.back();"></p>';
?>

<?php echo modules::run('_main/bottom'); ?>