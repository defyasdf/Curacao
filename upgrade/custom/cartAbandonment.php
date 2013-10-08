<?php 

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	$link = mysql_connect($server,$user,$pass);
	mysql_select_db($db,$link);	

	$sql = "SELECT * FROM `sales_flat_quote` WHERE `items_qty` > 0 AND `customer_email` != '' AND `customer_firstname` != '' and created_at < '".date('Y-m-d')."' and retargeted = 0 group by customer_email limit 0,500";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)){
		
		$sqlCustomer = 'SELECT * FROM `customer_entity` where email = "'.$row['customer_email'].'"';
		$resultCustomer = mysql_query($sqlCustomer);
		$rowCustomer = mysql_fetch_array($resultCustomer);
		
		$now = time(); // or your date as well
		$your_date = strtotime($rowCustomer['created_at']);
		$datediff = $now - $your_date;
		if(floor($datediff/(60*60*24))>=7){
			//$coupon = file_get_contents('https://www.icuracao.com/commonapi/api/cartabandoncoupon');
			//$couponcode = json_decode($coupon);
			//$email = 'sanju.comp@gmail.com';
			//file_get_contents("http://app.bronto.com/public/?q=direct_add&fn=Public_DirectAddForm&id=acxhzmypejmnhsowqaqxwyyhyesgbcd&email=".$email."&field1=firstname,set,".$row['customer_firstname']."&field2=lastname,set,".$row['customer_lastname']."&field3=Abandoned_Cart,set,True&field4=Abandoned_Cart_Coupon,set,".$couponcode->code."&list5=0bc603ec000000000000000000000005451f");		
			file_get_contents("http://app.bronto.com/public/?q=direct_add&fn=Public_DirectAddForm&id=acxhzmypejmnhsowqaqxwyyhyesgbcd&email=".$row['customer_email']."&field1=firstname,set,".$row['customer_firstname']."&field2=lastname,set,".$row['customer_lastname']."&field3=Abandoned_Cart,set,True&field4=Abandoned_Cart_Coupon,set,".getcoupon()."&list5=0bc603ec000000000000000000000005451f");
			$query = "update sales_flat_quote set retargeted = 1 where entity_id = ".$row['entity_id'];
			if(mysql_query($query)){
				echo 'Info sent to bronto';
			}
		}
	}
	
	function getcoupon(){
		$coupon = file_get_contents('https://www.icuracao.com/commonapi/api/cartabandoncoupon');
		$couponcode = json_decode($coupon);
		if($couponcode->code){
			return $couponcode->code;
		}else{
			getcoupon();
		}
	}