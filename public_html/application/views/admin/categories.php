<?php 
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Admin Categories view to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */
echo modules::run('_main/top');
?>

<script src="<?php echo url(); ?>js/prototype.js" type="text/javascript"></script>
<script src="<?php echo url(); ?>js/scriptaculous.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
document.observe('dom:loaded', function(){
	Sortable.create('divContainer',{
		onUpdate: function(item) {
		var list=Sortable.options(item).element;
		var poststr = 'order='+URLEncode(Sortable.serialize(list));
		ajax('<?php echo url(); ?>_ajax/sort_categories', 'order_list', poststr, 'POST');
		},tag: 'div'
	});
});
function save_category(key,type){
	if (type=='delete'){
		if (confirm("This will delete all child subcategories and products, do you wish to continue?")){
			var confirmed='Y';
		}else{
			var confirmed='N';
		}
	}else{
		var confirmed='Y';
	}
	if (confirmed=='Y'){
		var title = URLEncode(document.getElementById('title_'+key).value);
		var poststr = 'type='+type+'&key='+key+'&title='+title+'&parentkey=<?php echo $parent_key; ?>';
		ajax('<?php echo url(); ?>_ajax/edit_categories', 'status_'+key, poststr, 'POST', '', refresh_page);
		window.setTimeout('document.getElementById("status_'+key+'").innerHTML="";', 2000);
	}
}
function sort_alpha(parent){
	var poststr = 'sort=alpha'+'&parent='+parent;
	ajax('<?php echo url(); ?>_ajax/sort_alpha_categories', 'order_list', poststr, 'POST', '', refresh_page);
}
function add_new(key){
	var toggle = document.getElementById('new_toggle').value;
	if (toggle=='off'){
		document.getElementById('new_toggle').value='on';
		document.getElementById(key).style.display='';
	}else{
		document.getElementById('new_toggle').value='off';
		document.getElementById(key).style.display='none';
	}
}
</script>
<?php
echo '<div class="breadcrumb">Admin&nbsp;&gt;&nbsp;<strong>Categories</strong></div>';
?>
<input type="button" class="buttonstyle" value="Add New" onclick="add_new('new_category');">
<input type="button" class="buttonstyle" value="Refresh" onclick="refresh_page();">
<input type="button" class="buttonstyle" value="Sort A-Z" onclick="sort_alpha('_top');">
<div id="new_category" style="margin-top:5px; border:1px #7A7A9A solid;display:none">
<input type="hidden" id="new_toggle" value="off">
<table class="normal" style="background:#F1F1F1" width="100%;"
<tr><td>Title&nbsp;<input type="text" id="title_new" class="textstyle" style="width:200px" value="">&nbsp;<input type="button" class="buttonstyle" value="Save" onclick="save_category('new','new');">&nbsp;<span id="status_new"></span></td></tr>
</table>
</div>
<div id="divContainer">
<?php
$i=1;
if ($cats==1){	
	foreach ($category as $category_info) {
		echo '<div id="div_'.$category_info['key'].'" class="move" style="margin-top:5px; border:1px #7A7A9A solid;">';
		echo '<table class="normal" style="background:#F1F1F1" width="100%;">';
		echo '<tr><td><input type="text" id="title_'.$category_info['key'].'" class="textstyle" style="width:200px" value="'.$category_info['title'].'">&nbsp;<input type="button" class="buttonstyle" value="Save" onclick="save_category(\''.$category_info['key'].'\',\'edit\');">&nbsp;<input type="button" class="buttonstyle" value="Delete" onclick="save_category(\''.$category_info['key'].'\',\'delete\');">&nbsp;<input type="button" class="buttonstyle" value="Subcategories" onclick="javascript:parent.location=\''.url().'admin/categories/'.$category_info['key'].'\';"></td><td><img src="'.url().'images/icon_drag.png" alt="Move"><span id="status_'.$category_info['key'].'" style="display:none"></span></td></tr>';
		echo '</table>';
		echo '</div>';
		$i++;
	}
?>
</div>
<div id="order_list" style="display:none"></div>
<?php
}else{
	echo '<br><br>No Categories!';
}
?>

<?php echo modules::run('_main/bottom'); ?>