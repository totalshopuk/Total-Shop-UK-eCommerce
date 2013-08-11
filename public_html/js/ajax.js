/**
 * Total Shop UK eCommerce Open Source
 *
 * The AJAX Javascript to be used with Total Shop UK eCommerce Open Source
 *
 * @package		Total Shop UK eCommerce Open Source
 * @author		Jason Davey
 * @copyright	Copyright (C) 2012  Jason Davey - Total Shop UK.
 * @license		http://www.totalshopuk.com/license
 * @version		Version 2.1.3
 */

function getletter(num) {
	if (num < 10) {
		return num;
	} else {
		if (num == 10) {
			return "A";
		}
		if (num == 11) {
			return "B";
		}
		if (num == 12) {
			return "C";
		}
		if (num == 13) {
			return "D";
		}
		if (num == 14) {
			return "E";
		}
		if (num == 15) {
			return "F";
		}
	}
}

function hexfromdec(num) {
	if (num > 65535) {
		return "err!";
	}
	first = Math.round(num / 4096 - 0.5);
	temp1 = num - first * 4096;
	second = Math.round(temp1 / 256 - 0.5);
	temp2 = temp1 - second * 256;
	third = Math.round(temp2 / 16 - 0.5);
	fourth = temp2 - third * 16;
	return "" + getletter(third) + getletter(fourth);
}

function URLEncode(string, encoding) {
	if (encoding == "ascii") {
		var i;
		var hex = "";
		for (i = 0; i < string.length; ++i) {
			hex += "%" + hexfromdec(string.charCodeAt(i));
		}
		return hex;
	} else {
		string = string.replace(/\r\n/g, "\n");
		var utftext = "";
		for (var n = 0; n < string.length; n++) {
			var c = string.charCodeAt(n);
			if (c < 128) {
				utftext += String.fromCharCode(c);
			} else if (c > 127 && c < 2048) {
				utftext += String.fromCharCode(c >> 6 | 192);
				utftext += String.fromCharCode(c & 63 | 128);
			} else {
				utftext += String.fromCharCode(c >> 12 | 224);
				utftext += String.fromCharCode(c >> 6 & 63 | 128);
				utftext += String.fromCharCode(c & 63 | 128);
			}
		}
		return escape(utftext);
	}
}

function URLDecode(encodedString, encoding) {
	if (encoding == "ascii") {
		var output = encodedString;
		var binVal, thisString;
		var myregexp = /(%[^%]{2})/;
		while ((match = myregexp.exec(output)) != null &&
		match.length > 1 && match[1] != "") {
			binVal = parseInt(match[1].substr(1), 16);
			thisString = String.fromCharCode(binVal);
			output = output.replace(match[1], thisString);
		}
		return output;
	} else {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
		while (i < encodedString.length) {
			c = encodedString.charCodeAt(i);
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			} else if (c > 191 && c < 224) {
				c2 = encodedString.charCodeAt(i + 1);
				string += String.fromCharCode((c & 31) << 6 | c2 & 63);
				i += 2;
			} else {
				c2 = encodedString.charCodeAt(i + 1);
				c3 = encodedString.charCodeAt(i + 2);
				string += String.fromCharCode((c & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
				i += 3;
			}
		}
		return unescape(string);
	}
}

function ajax(page, box, parameters, method, loadmessage, call, callparas) {
	ajax_responsetext = "";
	var silent = false;
	if (box == undefined || box == "") {
		silent = true;
	}
	if (method == undefined || method == "") {
		method = "GET";
	}
	if (callparas == undefined) {
		callparas = "";
	}
	if (loadmessage == undefined) {
		loadmessage = "";
	}

	function new_request() {
		if (window.XMLHttpRequest) {
			return new XMLHttpRequest;
		} else if (window.ActiveXObject) {
			try {
				return new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					return new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {
				}
			}
		}
	}

	var xmlhttp = new_request();
	var page = escape(page);
	if (page !== "") {
		if (method == "POST") {
			var url = page;
			xmlhttp.open("POST", url, true);
			xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
			xmlhttp.send(parameters);
		} else {
			if (parameters == "") {
				var url = page;
			} else {
				var url = page + ("?" + parameters + "&trans=" + Math.random());
			}
			xmlhttp.open("GET", url, true);
			xmlhttp.setRequestHeader("Content-Type", "text/html; Charset=ISO_8859-1");
			xmlhttp.send(null);
		}
		if (loadmessage != "") {
			document.getElementById(box).innerHTML = loadmessage;
		}
		xmlhttp.onreadystatechange = function () {if (xmlhttp.readyState == 4 || xmlhttp.readyState == "complete") {if (xmlhttp.status == 200) {if (silent == false) {document.getElementById(box).innerHTML = xmlhttp.responseText;}ajax_responsetext = xmlhttp.responseText;if (call != "" && call != undefined) {call(callparas);}} else {if (silent == false) {document.getElementById(box).innerHTML = "Error Fetching Page";}}}};
	} else if (page == "") {
		if (silent == false) {
			document.getElementById(box).innerHTML = "Error: No Page Specified";
		}
	}
}

function flash(url, w, h) {
	document.write("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"" + w + "\" height=\"" + h + "\">\n");
	document.write("<param name=\"movie\" value=\"" + url + "\" />\n");
	document.write("<param name=\"quality\" value=\"high\">\n");
	document.write("<embed src=\"" + url + "\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"" + w + "\" height=\"" + h + "\"></embed>\n");
	document.write("</object>\n");
}

function nl2br(str){
    return (str + '').replace(/([^>])\n/g, '<br>\n');
}

function br2nl(str){
    return str.replace(/<br\s*\/?>/mg,"\n");
}