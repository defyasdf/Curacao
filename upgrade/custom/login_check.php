<?php

$url = 'http://108.171.160.207/onestepcheckout/ajax/login/';
$fields = array(
            'onestepcheckout_username' => "sanjay@gmail.com",
            'onestepcheckout_password' => "snprajapati"
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

echo $result;