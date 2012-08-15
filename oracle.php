<?php

require_once '../config/default.php';

$username = $oracle_name;
$password = $oracle_pwd;
$db = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$oracle_host)(PORT=$oracle_port))(CONNECT_DATA=(SID=$oracle_db)))";

function OracleConnect($username, $password, $db, $charset, $session_mode)
{
	$connection = oci_connect($username, $password, $db);	
	//$connection = oci_connect("alien", "alien", "192.168.1.180:1521/orcl");
	
	if ($connection)
		return $connection;
	else 
	{
		$e = oci_error();
    	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
}

$connection = OracleConnect($username, $password, $db, "", "", "");

$query = "select * from ALIEN_SCORE";
$statement = oci_parse($connection, $query);
oci_execute($statement, OCI_DEFAULT);

while ($row = oci_fetch_array($statement, OCI_ASSOC))
	print_r($row);

?>