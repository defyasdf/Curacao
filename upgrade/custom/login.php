<?php
	$ch = curl_init("http://108.171.160.207/onestepcheckout/ajax/login/onestepcheckout_username=sanjay%40gmail.com&onestepcheckout_password=snprajapati");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);       
        curl_close($ch);
        echo $output;
?>