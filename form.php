<?php
$file_name	= "";
$file_type	= "";
$file_size	= 0;
$file_dir	= "";
$file_new_name = "";

function GetPostStr($str)
{
	if (isset($_GET[$str]) && trim($_GET[$str], ' '))
		return trim($_GET[$str], ' ');
	elseif (isset($_POST[$str]) && trim($_POST[$str], ' '))
		return trim($_POST[$str], ' ');
	else
		return FALSE;
}

function GetPostFile($file)
{
	if ($_FILES[$file]["error"] > 0)
		return FALSE;
	else
	{
		global $file_name;
		global $file_type;
		global $file_size;
		global $file_tmp_dir;
		$file_name = $_FILES[$file]["name"];
		$file_type = $_FILES[$file]["type"];
		$file_size = $_FILES[$file]["size"];
		$file_tmp_dir = $_FILES[$file]["tmp_name"];
		return TRUE;
	}
}

function UploadFile($file, $file_tmp_name, $dir)
{
	$time = date("Y-m-d_H-i-s");
	$extend = strchr($file, '.');
	global $file_new_name;
	$file_new_name = substr($file, 0, strpos($file, '.')).$time.$extend;
	if (!move_uploaded_file($file_tmp_name, $dir.$file_new_name) && 
		!move_uploaded_file($file_tmp_name, iconv("UTF-8","gb2312",$dir.$file_new_name)))
		return FALSE;
	else 
		return TRUE;
}
?>