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

function CheckEmail($email, $checkDNS=FALSE)
{
    $valid = (
            /* Preference for native version of function */
            function_exists('filter_var') and filter_var($email, FILTER_VALIDATE_EMAIL)
            ) || (
                /* The maximum length of an e-mail address is 320 octets, per RFC 2821. */
                strlen($email) <= 320 
                /*
                 * The regex below is based on a regex by Michael Rushton.
                 * However, it is not identical. I changed it to only consider routeable
                 * addresses as valid. Michael's regex considers a@b a valid address
                 * which conflicts with section 2.3.5 of RFC 5321 which states that:
                 *
                 * Only resolvable, fully-qualified domain names (FQDNs) are permitted
                 * when domain names are used in SMTP. In other words, names that can
                 * be resolved to MX RRs or address (i.e., A or AAAA) RRs (as discussed
                 * in Section 5) are permitted, as are CNAME RRs whose targets can be
                 * resolved, in turn, to MX or address RRs. Local nicknames or
                 * unqualified names MUST NOT be used.
                 *
                 * This regex does not handle comments and folding whitespace. While
                 * this is technically valid in an email address, these parts aren't
                 * actually part of the address itself.
                 */
                and preg_match_all(
                    '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?))'. 
                    '{255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?))'.
                    '{65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|'.
                    '(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))'.
                    '(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|'.
                    '(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|'.
                    '(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})'.
                    '(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126})'.'{1,}'.
                    '(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|'.
                    '(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|'.
                    '(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::'.
                    '(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|'.
                    '(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|'.
                    '(?:(?!(?:.*[a-f0-9]:){5,})'.'(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::'.
                    '(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|'.
                    '(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|'.
                    '(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD',
                    $email)
            );
    
    if( $valid )
    {
        //if( $checkDNS && ($domain = end(explode('@',$email, 2))) )
        $arr = explode("@",$email, 2);
    	if( $checkDNS && ($domain = end($arr)) )
    	{
            /*
            Note:
            Adding the dot enforces the root.
            The dot is sometimes necessary if you are searching for a fully qualified domain
            which has the same name as a host on your local domain.
            Of course the dot does not alter results that were OK anyway.
            */
    		if(checkdnsrr($domain . '.', 'A')) return true;
            if (checkdnsrr($domain . '.', 'MX')) return true;
        }
        return true;
    }
    return false;
    	
	/*
	if(preg_match('/^\w[-.\w]*@(\w[-._\w]*\.[a-zA-Z]{2,}.*)$/', $email, $matches))
	{
		if(function_exists('checkdnsrr'))
		{
			if(checkdnsrr($matches[1] . '.', 'MX')) return true;
			if(checkdnsrr($matches[1] . '.', 'A')) return true;
		}
		else
		{
			if(!empty($hostName))
			{
				if( $recType == '' ) $recType = "MX";
				exec("nslookup -type=$recType $hostName", $result);
				foreach ($result as $line)
				{
					if(eregi("^$hostName",$line))
					{
						return true;
					}
				}
				return false;
			}
			return false;
		}
	}
	return false;
	*/
}
//echo CheckEmail('hhyfly99@gmail.com', TRUE)."EEE";

?>