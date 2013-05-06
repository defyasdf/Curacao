<?php
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);
	
	function createCouponCode() {
		$chars = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$code = '' ;
		while ($i <= 7)
		{
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$code = $code . $tmp;
			$i++;
		}
    	
		$q = "select * from coupon_codes where code = 'SOC_".$code."'";
		$r = mysql_query($q);
		
		if(mysql_num_rows($r)>0){
			createCouponCode();
		}  
	  
	   return $code;
  	}
	
	for($i=1;$i<11;$i++){
		$sql = "insert into coupon_codes(name, code)values('Curacao_Social_".$i."','SOC_".createCouponCode()."')";
		mysql_query($sql);
	}
	
	echo 'Done';