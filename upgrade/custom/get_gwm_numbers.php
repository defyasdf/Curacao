<?php 

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
// Server DB setting
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	$link = mysql_connect($server,$user,$pass);
	mysql_select_db($db,$link) or die("No DB");	

	
	$sql = "SELECT increment_id, `coupon_code`, `discount_amount`,`grand_total`,`shipping_amount`,`subtotal`,`tax_amount`   FROM `sales_flat_order` WHERE `created_at` > '2013-08-20 00:00:00' and `coupon_code` != ''";
	$result = mysql_query($sql);
	$i = 0;
	echo '<table border=1><tr><td>#</td><td>Order Number</td><td>Coupon Name</td><td>Code</td><td>Subtotal</td><td>Tax</td><td>Shipping</td><td>Discount</td><td>Grand Total</td></tr>';
	while($row = mysql_fetch_array($result)){
		$cque = "SELECT * FROM `salesrule_coupon` where code = '".$row['coupon_code']."'";
		$cre = mysql_query($cque);
		$cr = mysql_fetch_array($cre);
		
		$coque = "SELECT * FROM `salesrule` where rule_id = '".$cr['rule_id']."'";
		$core = mysql_query($coque);
		$cor = mysql_fetch_array($core);
		if($cor['name']=='Sign up and get $100 off'){
			$i++;
			echo '<tr><td>'.$i.'</td><td>'.$row['increment_id'].'</td><td>'.$cor['name'].'</td><td>'.$cr['code'].'</td><td>'.$row['subtotal'].'</td><td>'.$row['tax_amount'].'</td><td>'.$row['shipping_amount'].'</td><td>'.$row['discount_amount'].'</td><td>'.$row['grand_total'].'</td></tr>';
		//	echo '<tr><td>'.$cr['code'].'</td><td>'.$cor['name'].'</td></tr>';
		
		}	
	}
echo '</table>';