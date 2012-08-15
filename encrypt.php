<?php
$head = "COM3";						//global value head
$tail = "yond";						//global value tail
function md5_encrypt($str)
{
	global $head;
	global $tail;
	$encrypt_str = $head.$str.$tail;
	//echo md5($head, true);
	return md5($encrypt_str);
}
?>