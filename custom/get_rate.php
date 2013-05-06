<?php
//set POST variables
$url = 'http://108.171.160.207/fedex/RateService_v13_php/php/RateWebServiceClient/Rate/RateWebServiceClient.php';
$fields = array(	
				'lname' => 'Prajapati',
				'fname' => 'Sanjay',
				'add' => '1223 Federal Ave',
				'city' => 'Los Angeles',
				'zip' => '90025',
				'state' => 'CA',
				'country' => 'US',
				'phone' => '3234592129'
				);

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

print_r($result);