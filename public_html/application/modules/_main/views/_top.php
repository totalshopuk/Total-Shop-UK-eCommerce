<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="Last-Modified" content="<?php echo date('D, j M Y H:i:s'); ?> GMT">
	<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="keywords" content="total,shop,uk,ecommerce,open,source">
	<meta name="description" content="Total Shop UK eCommerce Open Source">
	<meta name="author" content="Total Shop UK">
	<meta name="revisit-after" content="3 Days">
	<meta name="robots" content="all">
	<meta name="distribution" content="Global">
	<meta name="rating" content="Safe For Kids">
	<meta name="copyright" content="Total Shop UK <?php echo date('Y'); ?>">
	<title>Total Shop UK :: eCommerce Open Source</title>
	<link rel="stylesheet" href="<?php echo url(); ?>css/lightbox.css" type="text/css">
	<!--[if IE]><style type="text/css">#overlay{filter:alpha(opacity=80);}</style><![endif]-->
	<!--[if lte IE 6]><style type="text/css">img,div,input{behavior:url('<?php echo url(); ?>images/iepngfix.htc');}</style><![endif]-->
	<link rel="stylesheet" href="<?php echo url(); ?>css/style.css" type="text/css" charset="utf-8">
	<script type="text/javascript" src="<?php echo url(); ?>js/ajax.js"></script>
	<script type="text/javascript" src="<?php echo url(); ?>js/jquery.js"></script>
	<script type="text/javascript" charset="utf-8">
	function search(){
		var $j = jQuery.noConflict();
		var search = URLEncode($j("#search").val());
		parent.location='<?php echo url(); ?>search/s/'+search;
	}
	function refresh_page(){
		parent.location=URLDecode('<?php echo urlencode($_SERVER["REQUEST_URI"]); ?>');
	}
	function cancel_basket(){
		var $j = jQuery.noConflict();
		$j.post("<?php echo url(); ?>_ajax/reset_basket",{reset: 'true'},
		function(data){
			if (data!=''){
				$j("#cancel_status").html(data);
				refresh_page();
			}
		});
		return false;
	}
	</script>
</head>
<body>
<table class="main" cellspacing="0" cellpadding="0" border="0">
		<tr><td colspan="3" class="banner"><h1>Total Shop UK Open Source Version</h1><h2>on CodeIgniter v2.1.3</h2></td></tr>
		<tr>
			<td colspan="3" class="top">[ <?php 
			$CI =& get_instance();
			if ($CI->session->userdata('user_id')==''){
				echo '<a href="'.url().'login">Login</a>';
			}else{
				echo '<a href="'.url().'logout">Logout</a>';
			}
			?> ]</td>
		</tr>
		<tr><td colspan="3" class="divider"></td>
		</tr>
	<tr>
		<td colspan="3" class="nav">
			<a href="<?php echo url(); ?>">Home</a>
			<a href="<?php echo url(); ?>about">About Us</a>
			<a href="<?php echo url(); ?>faq">FAQ</a>
			<a href="<?php echo url(); ?>contact">Contact Us</a>
		</td>
	</tr>
	<tr>
		<td class="left">
			<?php
			if ($level==1){
			?>
			<table class="subleft" cellpadding="0" cellspacing="0">
				<tr class="sublefthdr"><td>Admin</td></tr>
				<tr class="subleftcntmargin"><td></td></tr>
				<tr class="subleftcnt"><td><a href="<?php echo url(); ?>admin/categories">Categories</a></td></tr>
				<tr class="subleftcnt"><td><a href="<?php echo url(); ?>admin/products">Products</a></td></tr>
				<tr class="subleftcnt"><td><a href="<?php echo url(); ?>admin/shipping">Shipping</a></td></tr>
				<tr class="subleftcntmargin"><td></td></tr>
			</table>
			<?php
			}
			if (isset($categories)){
				if (count($categories)>0){
					foreach ($categories as $value){
						if ($value['parentkey']=='_top'){
							echo '<table class="subleft" cellpadding="0" cellspacing="0">';
							echo '<tr class="sublefthdr"><td>'.$value['title'].'</td></tr>';
							echo '<tr class="subleftcntmargin"><td></td></tr>';
						}
				
						foreach ($categories as $value2){
				
							if ($value2['parentkey']==$value['key']){
								echo '<tr class="subleftcnt"><td><a href="'.url().'products/c/'.$value2['key'].'">'.$value2['title'].'</a></td></tr>';		
							}
						}
				
						if ($value['parentkey']=='_top'){
							echo '<tr class="subleftcntmargin"><td></td></tr>';
							echo '</table>';
						}

					}
				}
			}else{
				echo '<table class="subleft" cellpadding="0" cellspacing="0">';
				echo '<tr class="sublefthdr"><td>No Categories</td></tr>';
				echo '<tr class="subleftcntmargin"><td></td></tr>';
				echo '<tr class="subleftcnt"><td><a href="javascript:;">No Subcategories</a></td></tr>';
				echo '<tr class="subleftcntmargin"><td></td></tr>';
				echo '</table>';

			}
			?><br>
		</td>
		<td class="center">
			<table class="subcenter">
				<tr>
					<td class="subcentercnt">
						<noscript class="error"><p><img width="506" height="41" alt="" src="<?php echo url(); ?>images/noscript.gif"></p></noscript>