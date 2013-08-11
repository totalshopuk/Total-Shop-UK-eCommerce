<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Total Shop UK eCommerce Open Source
 *
 * The Function helper to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

function find_chr($string)
{
  $chrstring = "";
  for ($i=0; $i < strlen($string); $i++){
    $chrstring .= "chr(" . ord(substr($string,$i,1)) . ")";
    $chrstring .= ($i==strlen($string)-1)?"":".";
  }
  $toscreenstring = htmlentities($string);
  return $chrstring;
}

function smartQuotesUE($str,$utf='1')
{
	if ($utf='1'){
		$smart = array("\xE2\x80\x93","\xE2\x80\x94","\xE2\x80\x98","\xE2\x80\x99","\xE2\x80\x9C","\xE2\x80\x9D","\xE2\x80\xA2","\xE2\x80\xA6");
		$replace = array('-','-','\'','\'','"','"','*','...');
	}else{
		$smart = array(chr(19),chr(24),chr(25),chr(28),chr(29));
		$replace = array('-','\'','\'','"','"');
	}
	return str_replace($smart, $replace, $str);
}

function formatBytes($b,$p = null)
{
    $units = array("B","kB","MB","GB","TB","PB","EB","ZB","YB");
    $c=0;
    if (!$p && $p !== 0){
        foreach ($units as $k => $u){
            if (($b / pow(1024,$k)) >= 1){
                $r["bytes"] = $b / pow(1024,$k);
                $r["units"] = $u;
                $c++;
            }
        }
        return number_format($r["bytes"],2) . " " . $r["units"];
    }else{
        return number_format($b / pow(1024,$p)) . " " . $units[$p];
    }
}

function url()
{
	$url_array = explode('/',base_url());
	$x = count($url_array);
	$url = '';
	for ($i=3; $i<$x; $i++){
		$url.= '/'.$url_array[$i];
	}
	return $url;
}

function remove_nl($str)
{
	$new_line 	= array("\r\n", "\n", "\r");
	$replace	= ' ';
	return str_replace($new_line, $replace, $str);
}

function truncate($data,$maxlen,$type=1)
{
	if ($type==1){
		if ((strlen($data)>$maxlen) && (strlen($data)>3)){
			$data=substr($data,0,$maxlen-3).'...';
		}
		return $data;
	}
	if ($type==2){
		if ((strlen($data)>$maxlen) && (strlen($data)>3)){
			$data='...'.substr($data,strlen($data)-$maxlen,$maxlen);
		}
		return $data;
	}		
}

function pr($array)
{  
	echo '<pre>'.print_r($array, true).'</pre>';
}

function nl2br2($text)
{
    return preg_replace("/\r\n|\n|\r/", "<br>", $text);
}

function p2nl($text)
{
	$text = preg_replace(array("/<p[^>]*>/iU","/<\/p[^>]*>/iU"), array("","\n"), $text);
    $text = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $text);
	$text = strip_tags($text);
	return $text;
}

function is_num($num)
{
	return preg_match( '/^[0-9]+$/', $num);
}

function build_options($values,$options,$selected)
{		
	if (count($options)<>count($values)){
		$options=$values;
	}
	$build = "\n";
	foreach ($options as $i => $value){
		$option='<option value="'.$values[$i].'" ';
		if ($selected==$values[$i]){
			$option.="SELECTED";
		}	
		$option.='>'.$options[$i].'</option>'; 
		$build.= $option."\n";
	}
	return $build;
}

function dateExp($timestamp)
{
	if ($timestamp!=''){
		$timestamp = strtotime($timestamp);
		if ($timestamp > time()){
			return 1;
		}else{
			return 0;
		}
	}else{
		return 1;
	}
}

function passGen($len=8)
{
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
	$pass = '';
	for ($i = 0; $i < $len; $i++){
		$pass .= $chars[(mt_rand(0,strlen($chars)) % strlen($chars))];
    }
    return $pass;
}

function unique_md5()
{
	$rand = '';
	while (strlen($rand) < 32){
		$rand .= mt_rand(0, mt_getrandmax());
	}
	return md5(uniqid($rand, TRUE));
}

function valid_date($date, $format = 'YYYY-MM-DD'){
    if(strlen($date) >= 8 && strlen($date) <= 10){
        $separator_only = str_replace(array('M','D','Y'),'', $format);
        $separator = $separator_only[0];
        if($separator){
            $regexp = str_replace($separator, "\\" . $separator, $format);
            $regexp = str_replace('MM', '(0[1-9]|1[0-2])', $regexp);
            $regexp = str_replace('M', '(0?[1-9]|1[0-2])', $regexp);
            $regexp = str_replace('DD', '(0[1-9]|[1-2][0-9]|3[0-1])', $regexp);
            $regexp = str_replace('D', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
            $regexp = str_replace('YYYY', '\d{4}', $regexp);
            $regexp = str_replace('YY', '\d{2}', $regexp);
            if($regexp != $date && preg_match('/'.$regexp.'$/', $date)){
                foreach (array_combine(explode($separator,$format), explode($separator,$date)) as $key=>$value) {
                    if ($key == 'YY') $year = '20'.$value;
                    if ($key == 'YYYY') $year = $value;
                    if ($key[0] == 'M') $month = $value;
                    if ($key[0] == 'D') $day = $value;
                }
                if (checkdate($month,$day,$year)) return true;
            }
        }
    }
    return false;
}

function flatten_array(array $var, $prefix=false){
	$return = array();
	foreach ($var as $idx => $value){
		if (is_scalar($value)){
			if ($prefix){
				$return[$prefix.'['.$idx.']'] = $value;
			}else{
				$return[$idx] = $value;
			}
		} else {
			$return = array_merge($return, flatten_array($value, $prefix ? $prefix.'['.$idx.']' : $idx));
		}
	}
	return $return;
}

/* End of file function_helper.php */
/* Location: ./application/helpers/function_helper.php */