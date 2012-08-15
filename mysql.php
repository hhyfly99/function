<?php
//connect mysql
//$server IP:port ->string, $user_name ->string, $pwd ->string, $new_link ->bool, $type ->enum(
//MYSQL_CLIENT_COMPRESS, MYSQL_CLIENT_IGNORE_SPACE, MYSQL_CLIENT_INTERACTIVE, MYSQL_CLIENT_SSL)
//if connected return: connect handle; else return: error
function connect($server, $user_name, $pwd, $new_link, $type)
{
	$conn = mysql_connect($server, $user_name, $pwd, $new_link, $type);
	if (!$conn)
		die("Could not connect".mysql_error());
	else
		return $conn;
}

//select database
//$conn(connect handle) ->string, $db_name ->string
//if selected return: select handle; else return: error
function select_db($conn, $db_name)
{
	$db_selected = mysql_select_db($db_name, $conn);
	if (!$db_selected)
		die("Could not select database ".$db_name.mysql_error());
	else 
		return $db_selected;
}

//query all from a table
//$conn(connect handle) ->string, $table ->string
//if queried return: query result; else return: error
function query_all($conn, $table)
{
	$query = sprintf("select * from %s", mysql_escape_string($table));
	$result = mysql_query($query, $conn);
	if (!$result) 
	{
	    $message  = 'Invalid query: ' . mysql_error() . "\n";
	    $message .= 'Whole query: ' . $query;
    	die($message);
	}
	else 
		return $result;
}

//close connect
//$conn(connect handle) ->string
//if closed return: TRUE; else return: FALSE 
function close($conn)
{
	mysql_close($conn);
}
	
?>