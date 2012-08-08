<?php
$head = "COM3";
$tail = "yond";
function md5_encrypt($str)
{
	global $head;
	global $tail;
	$encrypt_str = $head.$str.$tail;
	//echo md5($head, true);
	echo md5($encrypt_str);
	echo "<br />";
	echo md5($str);
}

md5_encrypt("ddd");
?>