<?php 
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Login view to be used with Total Shop UK eCommerce Open Source
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
function login(){
	var email = URLEncode(document.getElementById('email').value);
	var password = URLEncode(document.getElementById('password').value);
	var poststr = 'email='+email+'&password='+password;
	ajax('<?php echo url(); ?>_ajax/login', 'login_status', poststr, 'POST', '', check);
}

function check(){
	var check = document.getElementById('login_status').innerHTML;
	if (check==1){
		document.getElementById('login_box').style.display="none";
		parent.location="<?php echo url(); ?>";
	}else{
		document.getElementById('login_check').className="error";
		document.getElementById('login_check').innerHTML="Invalid Login!";
	}
}
</script>
<div id="login_box">
	<table class="content" style="border: 1px solid #7A7A9A; background:#F1F1F1; padding:10px; margin-top:30px;">
		<tr>
			<td align="right">Email</td><td colspan="2" align="left"><input id="email" type="text" class="textstyle" style="width:150px"></td>
		</tr>
		<tr>
			<td align="right">Password</td><td colspan="2" align="left"><input id="password" type="password" class="textstyle" onkeypress="if (event.keyCode==13 || event.which==13) login();" style="width:150px"></td>
		</tr>
		<tr align="left">
			<td><span id="login_check"></span><span id="login_status" style="display:none;"></span></td>
			<td align="center"><input id="login" type="button" class="buttonstyle" value="Login" onclick="login();">&nbsp;
			</td>
			<td align="center" class="normal">&nbsp;</td>
		</tr>
	</table>
</div>

<?php echo modules::run('_main/bottom'); ?>