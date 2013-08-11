<?php 
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Lightbox view to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="Last-Modified" content="<?php echo date('D, j M Y H:i:s'); ?> GMT">
	<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="keywords" content="<?php echo $img; ?>">
	<meta name="description" content="<?php echo $img; ?>">
	<meta name="author" content="Total Shop UK">
	<meta name="revisit-after" content="3 Days">
	<meta name="robots" content="all">
	<meta name="distribution" content="Global">
	<meta name="rating" content="Safe For Kids">
	<meta name="copyright" content="Total Shop UK <?php echo date('Y'); ?>">
	<title>Total Shop UK eCommerce Open Source :: <?php echo $img; ?></title>
</head>
<body>
	<br><br>
	<?php 
	echo '<div>';
	echo image_thumb('images/products/'.$img, 400, 400);
	echo '<br><br><a href="#" id="lbAction" class="lbAction" rel="deactivate">Close [x]</a>';
	echo '</div>';
	?>
</body>
</html>