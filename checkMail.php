<?php
	$email = $_POST['email'];
	$ws = "http://www.netglobers.com/media/custom/getUserIdByMail.php";
	
	$msg = "invalid";

	if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email))
	{
		if(!function_exists("simplexml_load_file")){
			require_once(dirname(__FILE__).'/includes/simplexml/simplexml.class.php');

			function simplexml_load_file($file){
				$sx = new simplexml;
				return $sx->xml_load_file($file);
			}
		}

		$xml = simplexml_load_file($ws.'?mail='.$email);
		$result = $xml->result;
		
		if($result == "OK")
		{
			$msg = "ok";
		}
		else
		{
			$msg = "notexists";
		}	
	}
	else
	{
		$msg = "invalid";
	}

	echo $msg;
?>