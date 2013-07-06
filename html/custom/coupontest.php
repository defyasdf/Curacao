<?php	

	$coupon = file_get_contents('http://m113.icuracao.com/onestepcheckout/ajax/createbuy42coupon/');
	
	$code = json_decode($coupon);
	
	echo $code->code;