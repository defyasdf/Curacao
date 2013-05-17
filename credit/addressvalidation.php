<?php 
	ini_set('max_execution_time', 300);
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	$link = mysql_connect($server,$user,$pass);
	mysql_select_db($db,$link);

	
	$url = 'http://108.171.160.207/SOAP/addressvalidation.php';
	$fields = array(	
						'Street' => $_POST['street'],
                        'Zip' => $_POST['zip']
					);
	$fields_string = '';
	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
	
	//open connection
	$ch = curl_init();
	
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	//execute post
	$result = curl_exec($ch);
	
	echo $result;	
	
	if($result == 1){	
		$sql = "UPDATE `credit_app` SET `is_validate_address_complete` = '1'  WHERE `credit_id` = ".$_POST['appid'];
		mysql_query($sql);
	}
?>