<?php
function md5_encrypt($head, $str, $tail)
{
	$head = "COM3";
	$tail = "yond";
	$encrypt = $head.$str.$tail;
	echo md5($head, true);
	echo "<br />";
	echo md5($head);
}

md5_encrypt($head, "dd", $tail);
?>