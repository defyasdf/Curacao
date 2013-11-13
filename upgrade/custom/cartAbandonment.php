<?php 

	require_once('./_includes/ini_settings.php');
	require_once('./_includes/db_config_mage.php');
	require_once('./_includes/mage_head.php');
	
	//$sql = "SELECT * FROM `sales_flat_quote` WHERE `items_qty` > 0 AND `customer_email` != '' AND `customer_firstname` != '' and created_at < '".date('Y-m-d')."' and retargeted = 0 group by customer_email limit 0,500";
	$sql = "SELECT * FROM `sales_flat_quote` WHERE `items_qty` > 0 AND `customer_email` != '' AND `customer_firstname` != '' and created_at <= '2013-09-30' and created_at >= '2013-09-01' and retargeted = 0  group by customer_email";	
	
	$result = mysql_query($sql) or die(mysql_error());
	$num = 1;
	while($row = mysql_fetch_array($result)){	
		$sqlOrder = 'SELECT * FROM `sales_flat_order` where increment_id = "'.$row['reserved_order_id'].'"';
		$resultOrder = mysql_query($sqlOrder);
		//echo mysql_num_rows($resultOrder);
		if(mysql_num_rows($resultOrder)==0){				
			$sqlCustomer = 'SELECT * FROM `customer_entity` where email = "'.$row['customer_email'].'"';
			$resultCustomer = mysql_query($sqlCustomer);
			$rowCustomer = mysql_fetch_array($resultCustomer);
			$now = time(); // or your date as well
			$your_date = strtotime($rowCustomer['created_at']);
			$datediff = $now - $your_date;
			if(floor($datediff/(60*60*24))>=7){
				file_get_contents("http://app.bronto.com/public/?q=direct_add&fn=Public_DirectAddForm&id=acxhzmypejmnhsowqaqxwyyhyesgbcd&email=".$row['customer_email']."&field1=firstname,set,".$row['customer_firstname']."&field2=lastname,set,".$row['customer_lastname']."&field3=Abandoned_Cart,set,True&field4=Abandoned_Cart_Coupon,set,".getcoupon()."&list5=0bc603ec000000000000000000000005451f");
				$query = "update sales_flat_quote set retargeted = 1 where entity_id = ".$row['entity_id'];
				if(mysql_query($query)){
					echo 'Info sent to bronto';
				}
			$num++;	
			}
		}
	}
	echo $num;
	function getcoupon(){
		$coupon = file_get_contents('https://www.icuracao.com/commonapi/api/cartabandoncoupon');
		$couponcode = json_decode($coupon);
		try{
		if($couponcode->code){
			return $couponcode->code;
		}else{
			getcoupon();
		}
		}catch(Exception $ex){
			echo $ex;
			getcoupon();
		}
	}
	
	mysql_close($link);